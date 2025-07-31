"""
Celery tasks for the AU-VLP admin backend.

This module contains background tasks for:
- Email notifications
- Image processing
- Data export
- Content publishing
- System maintenance
"""

import os
import csv
import logging
from datetime import datetime, timedelta
from io import StringIO
from typing import Dict, List, Any, Optional

from celery import shared_task
from celery.exceptions import Retry
from django.conf import settings
from django.core.mail import send_mail, EmailMultiAlternatives
from django.template.loader import render_to_string
from django.utils import timezone
from django.db import transaction
from django.core.files.storage import default_storage
from django.core.files.base import ContentFile

from .models import (
    Admin, User, Organization, BlogPost, News, Event, Resource,
    ActivityLog, AdminActivityLog
)

logger = logging.getLogger(__name__)


# Email Tasks
@shared_task(bind=True, max_retries=3, default_retry_delay=60)
def send_notification_email(self, recipient_email: str, subject: str, 
                          template_name: str, context: Dict[str, Any]) -> Dict[str, Any]:
    """
    Send notification email using template.
    
    Args:
        recipient_email: Email address to send to
        subject: Email subject
        template_name: Template name (without .html extension)
        context: Template context variables
        
    Returns:
        Dict with success status and message
    """
    try:
        # Render email templates
        html_content = render_to_string(f'emails/{template_name}.html', context)
        text_content = render_to_string(f'emails/{template_name}.txt', context)
        
        # Create email message
        email = EmailMultiAlternatives(
            subject=subject,
            body=text_content,
            from_email=settings.DEFAULT_FROM_EMAIL,
            to=[recipient_email]
        )
        email.attach_alternative(html_content, "text/html")
        
        # Send email
        email.send()
        
        logger.info(f"Email sent successfully to {recipient_email}")
        return {
            'success': True,
            'message': f'Email sent to {recipient_email}',
            'recipient': recipient_email
        }
        
    except Exception as exc:
        logger.error(f"Failed to send email to {recipient_email}: {str(exc)}")
        
        # Retry with exponential backoff
        if self.request.retries < self.max_retries:
            raise self.retry(exc=exc, countdown=60 * (2 ** self.request.retries))
        
        return {
            'success': False,
            'message': f'Failed to send email after {self.max_retries} retries: {str(exc)}',
            'recipient': recipient_email
        }


@shared_task
def send_bulk_notification_emails(recipient_emails: List[str], subject: str,
                                template_name: str, context: Dict[str, Any]) -> Dict[str, Any]:
    """
    Send notification emails to multiple recipients.
    
    Args:
        recipient_emails: List of email addresses
        subject: Email subject
        template_name: Template name
        context: Template context variables
        
    Returns:
        Dict with results summary
    """
    results = {
        'total': len(recipient_emails),
        'successful': 0,
        'failed': 0,
        'errors': []
    }
    
    for email in recipient_emails:
        try:
            result = send_notification_email.delay(email, subject, template_name, context)
            if result.get('success', False):
                results['successful'] += 1
            else:
                results['failed'] += 1
                results['errors'].append(f"{email}: {result.get('message', 'Unknown error')}")
        except Exception as exc:
            results['failed'] += 1
            results['errors'].append(f"{email}: {str(exc)}")
    
    logger.info(f"Bulk email task completed: {results['successful']}/{results['total']} successful")
    return results


@shared_task
def send_welcome_email(user_id: int, user_type: str = 'user') -> Dict[str, Any]:
    """
    Send welcome email to new user or admin.
    
    Args:
        user_id: ID of the user/admin
        user_type: 'user' or 'admin'
        
    Returns:
        Dict with task result
    """
    try:
        if user_type == 'admin':
            user = Admin.objects.get(id=user_id)
            template_name = 'welcome_admin'
        else:
            user = User.objects.get(id=user_id)
            template_name = 'welcome_user'
        
        context = {
            'user': user,
            'frontend_url': settings.FRONTEND_URL,
            'site_name': 'AU-VLP Portal'
        }
        
        return send_notification_email.delay(
            user.email,
            f"Welcome to AU-VLP Portal",
            template_name,
            context
        ).get()
        
    except (Admin.DoesNotExist, User.DoesNotExist):
        error_msg = f"{user_type.title()} with ID {user_id} not found"
        logger.error(error_msg)
        return {'success': False, 'message': error_msg}


# Image Processing Tasks
@shared_task(bind=True, max_retries=3)
def process_uploaded_image(self, file_path: str, max_width: int = 1200, 
                         max_height: int = 800, quality: int = 85) -> Dict[str, Any]:
    """
    Process uploaded image: resize, optimize, and create thumbnails.
    
    Args:
        file_path: Path to the uploaded image
        max_width: Maximum width for resized image
        max_height: Maximum height for resized image
        quality: JPEG quality (1-100)
        
    Returns:
        Dict with processing results
    """
    try:
        from PIL import Image, ImageOps
        import io
        
        # Open and process the image
        with default_storage.open(file_path, 'rb') as image_file:
            image = Image.open(image_file)
            
            # Convert to RGB if necessary
            if image.mode in ('RGBA', 'LA', 'P'):
                image = image.convert('RGB')
            
            # Auto-orient based on EXIF data
            image = ImageOps.exif_transpose(image)
            
            # Resize if necessary
            original_size = image.size
            if image.width > max_width or image.height > max_height:
                image.thumbnail((max_width, max_height), Image.Resampling.LANCZOS)
            
            # Save optimized image
            output = io.BytesIO()
            image.save(output, format='JPEG', quality=quality, optimize=True)
            output.seek(0)
            
            # Replace original file
            default_storage.delete(file_path)
            default_storage.save(file_path, ContentFile(output.getvalue()))
            
            # Create thumbnail
            thumbnail_path = file_path.replace('.', '_thumb.')
            thumbnail = image.copy()
            thumbnail.thumbnail((300, 300), Image.Resampling.LANCZOS)
            
            thumb_output = io.BytesIO()
            thumbnail.save(thumb_output, format='JPEG', quality=80, optimize=True)
            thumb_output.seek(0)
            
            default_storage.save(thumbnail_path, ContentFile(thumb_output.getvalue()))
            
            logger.info(f"Image processed successfully: {file_path}")
            return {
                'success': True,
                'original_path': file_path,
                'thumbnail_path': thumbnail_path,
                'original_size': original_size,
                'new_size': image.size,
                'message': 'Image processed successfully'
            }
            
    except Exception as exc:
        logger.error(f"Failed to process image {file_path}: {str(exc)}")
        
        if self.request.retries < self.max_retries:
            raise self.retry(exc=exc, countdown=60 * (2 ** self.request.retries))
        
        return {
            'success': False,
            'message': f'Failed to process image: {str(exc)}',
            'original_path': file_path
        }


# Data Export Tasks
@shared_task(bind=True)
def export_data_to_csv(self, model_name: str, filters: Dict[str, Any] = None,
                      fields: List[str] = None) -> Dict[str, Any]:
    """
    Export model data to CSV file.
    
    Args:
        model_name: Name of the model to export
        filters: Optional filters to apply
        fields: Optional list of fields to include
        
    Returns:
        Dict with export results and file path
    """
    try:
        # Map model names to model classes
        model_map = {
            'admin': Admin,
            'user': User,
            'organization': Organization,
            'blogpost': BlogPost,
            'news': News,
            'event': Event,
            'resource': Resource,
            'activitylog': ActivityLog,
            'adminactivitylog': AdminActivityLog,
        }
        
        model_class = model_map.get(model_name.lower())
        if not model_class:
            raise ValueError(f"Unknown model: {model_name}")
        
        # Build queryset
        queryset = model_class.objects.all()
        if filters:
            queryset = queryset.filter(**filters)
        
        # Get field names
        if not fields:
            fields = [field.name for field in model_class._meta.fields]
        
        # Create CSV content
        output = StringIO()
        writer = csv.writer(output)
        
        # Write header
        writer.writerow(fields)
        
        # Write data rows
        for obj in queryset:
            row = []
            for field in fields:
                value = getattr(obj, field, '')
                if value is None:
                    value = ''
                elif hasattr(value, 'strftime'):  # DateTime field
                    value = value.strftime('%Y-%m-%d %H:%M:%S')
                row.append(str(value))
            writer.writerow(row)
        
        # Save to file
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        filename = f'exports/{model_name}_{timestamp}.csv'
        
        csv_content = output.getvalue()
        file_path = default_storage.save(filename, ContentFile(csv_content.encode('utf-8')))
        
        logger.info(f"Data export completed: {file_path}")
        return {
            'success': True,
            'file_path': file_path,
            'model': model_name,
            'record_count': queryset.count(),
            'message': f'Successfully exported {queryset.count()} records'
        }
        
    except Exception as exc:
        logger.error(f"Failed to export {model_name} data: {str(exc)}")
        return {
            'success': False,
            'message': f'Export failed: {str(exc)}',
            'model': model_name
        }


@shared_task
def generate_admin_report(report_type: str, date_from: str = None, 
                         date_to: str = None) -> Dict[str, Any]:
    """
    Generate administrative reports.
    
    Args:
        report_type: Type of report ('activity', 'users', 'content')
        date_from: Start date (YYYY-MM-DD format)
        date_to: End date (YYYY-MM-DD format)
        
    Returns:
        Dict with report results
    """
    try:
        from django.utils.dateparse import parse_date
        
        # Parse dates
        start_date = parse_date(date_from) if date_from else timezone.now().date() - timedelta(days=30)
        end_date = parse_date(date_to) if date_to else timezone.now().date()
        
        report_data = {}
        
        if report_type == 'activity':
            # Activity report
            admin_activities = AdminActivityLog.objects.filter(
                created_at__date__range=[start_date, end_date]
            ).count()
            
            user_activities = ActivityLog.objects.filter(
                created_at__date__range=[start_date, end_date]
            ).count()
            
            report_data = {
                'admin_activities': admin_activities,
                'user_activities': user_activities,
                'total_activities': admin_activities + user_activities,
                'date_range': f"{start_date} to {end_date}"
            }
            
        elif report_type == 'users':
            # User statistics report
            total_users = User.objects.count()
            new_users = User.objects.filter(
                created_at__date__range=[start_date, end_date]
            ).count()
            
            active_users = User.objects.filter(
                last_login__date__range=[start_date, end_date]
            ).count()
            
            report_data = {
                'total_users': total_users,
                'new_users': new_users,
                'active_users': active_users,
                'date_range': f"{start_date} to {end_date}"
            }
            
        elif report_type == 'content':
            # Content statistics report
            blog_posts = BlogPost.objects.filter(
                created_at__date__range=[start_date, end_date]
            ).count()
            
            news_articles = News.objects.filter(
                created_at__date__range=[start_date, end_date]
            ).count()
            
            events = Event.objects.filter(
                created_at__date__range=[start_date, end_date]
            ).count()
            
            report_data = {
                'blog_posts': blog_posts,
                'news_articles': news_articles,
                'events': events,
                'total_content': blog_posts + news_articles + events,
                'date_range': f"{start_date} to {end_date}"
            }
        
        # Save report to file
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        filename = f'reports/{report_type}_report_{timestamp}.json'
        
        import json
        report_json = json.dumps(report_data, indent=2, default=str)
        file_path = default_storage.save(filename, ContentFile(report_json.encode('utf-8')))
        
        logger.info(f"Report generated: {file_path}")
        return {
            'success': True,
            'report_type': report_type,
            'file_path': file_path,
            'data': report_data,
            'message': f'Report generated successfully'
        }
        
    except Exception as exc:
        logger.error(f"Failed to generate {report_type} report: {str(exc)}")
        return {
            'success': False,
            'message': f'Report generation failed: {str(exc)}',
            'report_type': report_type
        }


# Content Publishing Tasks
@shared_task
def publish_scheduled_content() -> Dict[str, Any]:
    """
    Publish content that is scheduled for publication.
    
    Returns:
        Dict with publishing results
    """
    try:
        now = timezone.now()
        results = {
            'blog_posts': 0,
            'news_articles': 0,
            'events': 0,
            'total': 0
        }
        
        with transaction.atomic():
            # Publish scheduled blog posts
            blog_posts = BlogPost.objects.filter(
                status='scheduled',
                publish_date__lte=now
            )
            blog_count = blog_posts.update(status='published')
            results['blog_posts'] = blog_count
            
            # Publish scheduled news articles
            news_articles = News.objects.filter(
                status='scheduled',
                publish_date__lte=now
            )
            news_count = news_articles.update(status='published')
            results['news_articles'] = news_count
            
            # Publish scheduled events
            events = Event.objects.filter(
                status='scheduled',
                publish_date__lte=now
            )
            event_count = events.update(status='published')
            results['events'] = event_count
            
            results['total'] = blog_count + news_count + event_count
        
        if results['total'] > 0:
            logger.info(f"Published {results['total']} scheduled content items")
        
        return {
            'success': True,
            'results': results,
            'message': f"Published {results['total']} content items"
        }
        
    except Exception as exc:
        logger.error(f"Failed to publish scheduled content: {str(exc)}")
        return {
            'success': False,
            'message': f'Content publishing failed: {str(exc)}'
        }


@shared_task
def cleanup_expired_content() -> Dict[str, Any]:
    """
    Clean up expired or old content based on retention policies.
    
    Returns:
        Dict with cleanup results
    """
    try:
        cutoff_date = timezone.now() - timedelta(days=365)  # 1 year retention
        results = {
            'activity_logs': 0,
            'admin_activity_logs': 0,
            'total': 0
        }
        
        with transaction.atomic():
            # Clean up old activity logs
            activity_logs = ActivityLog.objects.filter(created_at__lt=cutoff_date)
            activity_count = activity_logs.count()
            activity_logs.delete()
            results['activity_logs'] = activity_count
            
            # Clean up old admin activity logs
            admin_logs = AdminActivityLog.objects.filter(created_at__lt=cutoff_date)
            admin_count = admin_logs.count()
            admin_logs.delete()
            results['admin_activity_logs'] = admin_count
            
            results['total'] = activity_count + admin_count
        
        if results['total'] > 0:
            logger.info(f"Cleaned up {results['total']} old records")
        
        return {
            'success': True,
            'results': results,
            'message': f"Cleaned up {results['total']} old records"
        }
        
    except Exception as exc:
        logger.error(f"Failed to cleanup expired content: {str(exc)}")
        return {
            'success': False,
            'message': f'Content cleanup failed: {str(exc)}'
        }


# System Maintenance Tasks
@shared_task
def system_health_check() -> Dict[str, Any]:
    """
    Perform system health checks and report status.
    
    Returns:
        Dict with health check results
    """
    try:
        from django.db import connection
        from django.core.cache import cache
        
        health_status = {
            'database': False,
            'cache': False,
            'storage': False,
            'overall': False
        }
        
        # Check database connection
        try:
            with connection.cursor() as cursor:
                cursor.execute("SELECT 1")
            health_status['database'] = True
        except Exception as exc:
            logger.error(f"Database health check failed: {str(exc)}")
        
        # Check cache connection
        try:
            cache.set('health_check', 'ok', 30)
            if cache.get('health_check') == 'ok':
                health_status['cache'] = True
        except Exception as exc:
            logger.error(f"Cache health check failed: {str(exc)}")
        
        # Check storage
        try:
            test_file = 'health_check.txt'
            default_storage.save(test_file, ContentFile(b'health check'))
            if default_storage.exists(test_file):
                default_storage.delete(test_file)
                health_status['storage'] = True
        except Exception as exc:
            logger.error(f"Storage health check failed: {str(exc)}")
        
        # Overall health
        health_status['overall'] = all([
            health_status['database'],
            health_status['cache'],
            health_status['storage']
        ])
        
        logger.info(f"System health check completed: {health_status}")
        return {
            'success': True,
            'health_status': health_status,
            'timestamp': timezone.now().isoformat(),
            'message': 'Health check completed'
        }
        
    except Exception as exc:
        logger.error(f"System health check failed: {str(exc)}")
        return {
            'success': False,
            'message': f'Health check failed: {str(exc)}'
        }