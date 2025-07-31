"""
API views for Celery task management and monitoring.
"""

from rest_framework import status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response
from django.utils import timezone
from django.core.files.storage import default_storage
from celery import current_app
from celery.result import AsyncResult
import json

from .tasks import (
    send_notification_email, send_bulk_notification_emails, send_welcome_email,
    process_uploaded_image, export_data_to_csv, generate_admin_report,
    publish_scheduled_content, cleanup_expired_content, system_health_check
)
from .models import Admin


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def send_email_notification(request):
    """
    Send email notification task.
    
    POST data:
    - recipient_email: Email address
    - subject: Email subject
    - template_name: Template name
    - context: Template context (optional)
    """
    try:
        data = request.data
        recipient_email = data.get('recipient_email')
        subject = data.get('subject')
        template_name = data.get('template_name')
        context = data.get('context', {})
        
        if not all([recipient_email, subject, template_name]):
            return Response({
                'error': 'recipient_email, subject, and template_name are required'
            }, status=status.HTTP_400_BAD_REQUEST)
        
        # Add current user to context
        context['sender'] = {
            'name': f"{request.user.first_name} {request.user.last_name}",
            'email': request.user.email
        }
        
        # Start task
        task = send_notification_email.delay(
            recipient_email, subject, template_name, context
        )
        
        return Response({
            'task_id': task.id,
            'status': 'started',
            'message': 'Email notification task started'
        })
        
    except Exception as e:
        return Response({
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def send_bulk_emails(request):
    """
    Send bulk email notifications.
    
    POST data:
    - recipient_emails: List of email addresses
    - subject: Email subject
    - template_name: Template name
    - context: Template context (optional)
    """
    try:
        data = request.data
        recipient_emails = data.get('recipient_emails', [])
        subject = data.get('subject')
        template_name = data.get('template_name')
        context = data.get('context', {})
        
        if not all([recipient_emails, subject, template_name]):
            return Response({
                'error': 'recipient_emails, subject, and template_name are required'
            }, status=status.HTTP_400_BAD_REQUEST)
        
        if not isinstance(recipient_emails, list):
            return Response({
                'error': 'recipient_emails must be a list'
            }, status=status.HTTP_400_BAD_REQUEST)
        
        # Add current user to context
        context['sender'] = {
            'name': f"{request.user.first_name} {request.user.last_name}",
            'email': request.user.email
        }
        
        # Start task
        task = send_bulk_notification_emails.delay(
            recipient_emails, subject, template_name, context
        )
        
        return Response({
            'task_id': task.id,
            'status': 'started',
            'recipient_count': len(recipient_emails),
            'message': 'Bulk email task started'
        })
        
    except Exception as e:
        return Response({
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def process_image(request):
    """
    Process uploaded image.
    
    POST data:
    - file_path: Path to uploaded image
    - max_width: Maximum width (optional, default: 1200)
    - max_height: Maximum height (optional, default: 800)
    - quality: JPEG quality (optional, default: 85)
    """
    try:
        data = request.data
        file_path = data.get('file_path')
        max_width = data.get('max_width', 1200)
        max_height = data.get('max_height', 800)
        quality = data.get('quality', 85)
        
        if not file_path:
            return Response({
                'error': 'file_path is required'
            }, status=status.HTTP_400_BAD_REQUEST)
        
        # Start task
        task = process_uploaded_image.delay(
            file_path, max_width, max_height, quality
        )
        
        return Response({
            'task_id': task.id,
            'status': 'started',
            'message': 'Image processing task started'
        })
        
    except Exception as e:
        return Response({
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def export_data(request):
    """
    Export model data to CSV.
    
    POST data:
    - model_name: Name of model to export
    - filters: Optional filters (dict)
    - fields: Optional list of fields
    """
    try:
        data = request.data
        model_name = data.get('model_name')
        filters = data.get('filters', {})
        fields = data.get('fields')
        
        if not model_name:
            return Response({
                'error': 'model_name is required'
            }, status=status.HTTP_400_BAD_REQUEST)
        
        # Start task
        task = export_data_to_csv.delay(model_name, filters, fields)
        
        return Response({
            'task_id': task.id,
            'status': 'started',
            'message': 'Data export task started'
        })
        
    except Exception as e:
        return Response({
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def generate_report(request):
    """
    Generate admin report.
    
    POST data:
    - report_type: Type of report ('activity', 'users', 'content')
    - date_from: Start date (YYYY-MM-DD, optional)
    - date_to: End date (YYYY-MM-DD, optional)
    """
    try:
        data = request.data
        report_type = data.get('report_type')
        date_from = data.get('date_from')
        date_to = data.get('date_to')
        
        if not report_type:
            return Response({
                'error': 'report_type is required'
            }, status=status.HTTP_400_BAD_REQUEST)
        
        if report_type not in ['activity', 'users', 'content']:
            return Response({
                'error': 'report_type must be one of: activity, users, content'
            }, status=status.HTTP_400_BAD_REQUEST)
        
        # Start task
        task = generate_admin_report.delay(report_type, date_from, date_to)
        
        return Response({
            'task_id': task.id,
            'status': 'started',
            'message': 'Report generation task started'
        })
        
    except Exception as e:
        return Response({
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def trigger_content_publishing(request):
    """
    Manually trigger scheduled content publishing.
    """
    try:
        # Start task
        task = publish_scheduled_content.delay()
        
        return Response({
            'task_id': task.id,
            'status': 'started',
            'message': 'Content publishing task started'
        })
        
    except Exception as e:
        return Response({
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def trigger_cleanup(request):
    """
    Manually trigger content cleanup.
    """
    try:
        # Start task
        task = cleanup_expired_content.delay()
        
        return Response({
            'task_id': task.id,
            'status': 'started',
            'message': 'Content cleanup task started'
        })
        
    except Exception as e:
        return Response({
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def health_check(request):
    """
    Perform system health check.
    """
    try:
        # Start task
        task = system_health_check.delay()
        
        return Response({
            'task_id': task.id,
            'status': 'started',
            'message': 'Health check task started'
        })
        
    except Exception as e:
        return Response({
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def task_status(request, task_id):
    """
    Get status of a Celery task.
    """
    try:
        result = AsyncResult(task_id)
        
        response_data = {
            'task_id': task_id,
            'status': result.status,
            'ready': result.ready(),
            'successful': result.successful() if result.ready() else None,
            'failed': result.failed() if result.ready() else None,
        }
        
        if result.ready():
            if result.successful():
                response_data['result'] = result.result
            elif result.failed():
                response_data['error'] = str(result.result)
        else:
            response_data['info'] = result.info
        
        return Response(response_data)
        
    except Exception as e:
        return Response({
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def worker_status(request):
    """
    Get status of Celery workers.
    """
    try:
        app = current_app
        inspect = app.control.inspect()
        
        # Get worker information
        stats = inspect.stats()
        active_tasks = inspect.active()
        scheduled_tasks = inspect.scheduled()
        reserved_tasks = inspect.reserved()
        
        return Response({
            'timestamp': timezone.now().isoformat(),
            'workers': stats or {},
            'active_tasks': active_tasks or {},
            'scheduled_tasks': scheduled_tasks or {},
            'reserved_tasks': reserved_tasks or {},
            'total_workers': len(stats) if stats else 0,
            'total_active_tasks': sum(len(tasks) for tasks in (active_tasks or {}).values()),
            'total_scheduled_tasks': sum(len(tasks) for tasks in (scheduled_tasks or {}).values()),
            'total_reserved_tasks': sum(len(tasks) for tasks in (reserved_tasks or {}).values()),
        })
        
    except Exception as e:
        return Response({
            'error': str(e),
            'message': 'Unable to connect to Celery workers'
        }, status=status.HTTP_503_SERVICE_UNAVAILABLE)


@api_view(['DELETE'])
@permission_classes([IsAuthenticated])
def revoke_task(request, task_id):
    """
    Revoke a Celery task.
    """
    try:
        app = current_app
        app.control.revoke(task_id, terminate=True)
        
        return Response({
            'task_id': task_id,
            'status': 'revoked',
            'message': 'Task has been revoked'
        })
        
    except Exception as e:
        return Response({
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def download_export(request, file_path):
    """
    Download exported file.
    """
    try:
        if not default_storage.exists(file_path):
            return Response({
                'error': 'File not found'
            }, status=status.HTTP_404_NOT_FOUND)
        
        # Security check - only allow downloads from exports directory
        if not file_path.startswith('exports/') and not file_path.startswith('reports/'):
            return Response({
                'error': 'Access denied'
            }, status=status.HTTP_403_FORBIDDEN)
        
        file_url = default_storage.url(file_path)
        
        return Response({
            'file_url': file_url,
            'file_path': file_path,
            'message': 'File ready for download'
        })
        
    except Exception as e:
        return Response({
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)