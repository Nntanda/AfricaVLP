"""
Tests for Celery tasks.
"""

import os
import json
from unittest.mock import patch, MagicMock
from django.test import TestCase, override_settings
from django.core.files.base import ContentFile
from django.core.files.storage import default_storage
from django.utils import timezone
from celery import current_app
from celery.result import AsyncResult

from .models import Admin, User, Organization, BlogPost, News, Event, ActivityLog
from .tasks import (
    send_notification_email, send_bulk_notification_emails, send_welcome_email,
    process_uploaded_image, export_data_to_csv, generate_admin_report,
    publish_scheduled_content, cleanup_expired_content, system_health_check
)


class CeleryTaskTestCase(TestCase):
    """Base test case for Celery tasks"""
    
    def setUp(self):
        # Set Celery to eager mode for testing
        current_app.conf.task_always_eager = True
        current_app.conf.task_eager_propagates = True
        
        # Create test data
        self.admin = Admin.objects.create(
            email='admin@test.com',
            first_name='Test',
            last_name='Admin',
            role='admin',
            status='active'
        )
        
        self.organization = Organization.objects.create(
            name='Test Organization',
            email='org@test.com',
            status='active'
        )
        
        self.user = User.objects.create(
            email='user@test.com',
            first_name='Test',
            last_name='User',
            organization=self.organization
        )


class EmailTaskTests(CeleryTaskTestCase):
    """Tests for email-related tasks"""
    
    @patch('models_app.tasks.send_mail')
    @patch('models_app.tasks.render_to_string')
    def test_send_notification_email_success(self, mock_render, mock_send_mail):
        """Test successful email sending"""
        mock_render.side_effect = ['<html>Test</html>', 'Test text']
        mock_send_mail.return_value = True
        
        result = send_notification_email(
            'test@example.com',
            'Test Subject',
            'test_template',
            {'name': 'Test User'}
        )
        
        self.assertTrue(result['success'])
        self.assertEqual(result['recipient'], 'test@example.com')
        mock_render.assert_called()
    
    @patch('models_app.tasks.send_notification_email')
    def test_send_bulk_notification_emails(self, mock_send_email):
        """Test bulk email sending"""
        mock_send_email.delay.return_value.get.return_value = {'success': True}
        
        emails = ['test1@example.com', 'test2@example.com']
        result = send_bulk_notification_emails(
            emails,
            'Test Subject',
            'test_template',
            {'name': 'Test'}
        )
        
        self.assertEqual(result['total'], 2)
        self.assertEqual(result['successful'], 2)
        self.assertEqual(result['failed'], 0)
    
    @patch('models_app.tasks.send_notification_email')
    def test_send_welcome_email_admin(self, mock_send_email):
        """Test welcome email for admin"""
        mock_send_email.delay.return_value.get.return_value = {'success': True}
        
        result = send_welcome_email(self.admin.id, 'admin')
        
        self.assertTrue(result['success'])
        mock_send_email.delay.assert_called_once()
    
    @patch('models_app.tasks.send_notification_email')
    def test_send_welcome_email_user(self, mock_send_email):
        """Test welcome email for user"""
        mock_send_email.delay.return_value.get.return_value = {'success': True}
        
        result = send_welcome_email(self.user.id, 'user')
        
        self.assertTrue(result['success'])
        mock_send_email.delay.assert_called_once()
    
    def test_send_welcome_email_not_found(self):
        """Test welcome email with non-existent user"""
        result = send_welcome_email(99999, 'user')
        
        self.assertFalse(result['success'])
        self.assertIn('not found', result['message'])


class ImageProcessingTaskTests(CeleryTaskTestCase):
    """Tests for image processing tasks"""
    
    @patch('models_app.tasks.Image')
    @patch('models_app.tasks.default_storage')
    def test_process_uploaded_image_success(self, mock_storage, mock_image):
        """Test successful image processing"""
        # Mock PIL Image
        mock_img = MagicMock()
        mock_img.size = (2000, 1500)
        mock_img.width = 2000
        mock_img.height = 1500
        mock_img.mode = 'RGB'
        mock_image.open.return_value = mock_img
        
        # Mock storage
        mock_storage.open.return_value.__enter__.return_value = MagicMock()
        mock_storage.save.return_value = 'processed_image.jpg'
        mock_storage.delete.return_value = True
        
        result = process_uploaded_image('test_image.jpg')
        
        self.assertTrue(result['success'])
        self.assertEqual(result['original_path'], 'test_image.jpg')
        self.assertIn('thumbnail_path', result)
    
    @patch('models_app.tasks.Image')
    @patch('models_app.tasks.default_storage')
    def test_process_uploaded_image_failure(self, mock_storage, mock_image):
        """Test image processing failure"""
        mock_storage.open.side_effect = Exception('File not found')
        
        # This should be called with bind=True, so we need to mock the task instance
        with patch('models_app.tasks.process_uploaded_image.retry') as mock_retry:
            mock_retry.side_effect = Exception('Max retries exceeded')
            
            result = process_uploaded_image('nonexistent.jpg')
            
            self.assertFalse(result['success'])
            self.assertIn('Failed to process image', result['message'])


class DataExportTaskTests(CeleryTaskTestCase):
    """Tests for data export tasks"""
    
    @patch('models_app.tasks.default_storage')
    def test_export_data_to_csv_success(self, mock_storage):
        """Test successful data export"""
        mock_storage.save.return_value = 'exports/admin_20240101_120000.csv'
        
        result = export_data_to_csv('admin')
        
        self.assertTrue(result['success'])
        self.assertEqual(result['model'], 'admin')
        self.assertGreater(result['record_count'], 0)
        mock_storage.save.assert_called_once()
    
    def test_export_data_unknown_model(self):
        """Test export with unknown model"""
        result = export_data_to_csv('unknown_model')
        
        self.assertFalse(result['success'])
        self.assertIn('Unknown model', result['message'])
    
    @patch('models_app.tasks.default_storage')
    def test_generate_admin_report_activity(self, mock_storage):
        """Test activity report generation"""
        mock_storage.save.return_value = 'reports/activity_report_20240101.json'
        
        # Create some activity logs
        ActivityLog.objects.create(
            action='test_action',
            details='Test details'
        )
        
        result = generate_admin_report('activity')
        
        self.assertTrue(result['success'])
        self.assertEqual(result['report_type'], 'activity')
        self.assertIn('data', result)
        mock_storage.save.assert_called_once()
    
    def test_generate_admin_report_users(self):
        """Test users report generation"""
        result = generate_admin_report('users')
        
        self.assertTrue(result['success'])
        self.assertEqual(result['report_type'], 'users')
        self.assertIn('total_users', result['data'])
    
    def test_generate_admin_report_content(self):
        """Test content report generation"""
        result = generate_admin_report('content')
        
        self.assertTrue(result['success'])
        self.assertEqual(result['report_type'], 'content')
        self.assertIn('blog_posts', result['data'])


class ContentPublishingTaskTests(CeleryTaskTestCase):
    """Tests for content publishing tasks"""
    
    def test_publish_scheduled_content(self):
        """Test publishing scheduled content"""
        # Create scheduled content
        past_time = timezone.now() - timezone.timedelta(hours=1)
        
        blog_post = BlogPost.objects.create(
            title='Test Blog Post',
            content='Test content',
            status='scheduled',
            publish_date=past_time,
            organization=self.organization
        )
        
        result = publish_scheduled_content()
        
        self.assertTrue(result['success'])
        self.assertGreater(result['results']['total'], 0)
        
        # Check that blog post was published
        blog_post.refresh_from_db()
        self.assertEqual(blog_post.status, 'published')
    
    def test_cleanup_expired_content(self):
        """Test cleanup of expired content"""
        # Create old activity log
        old_date = timezone.now() - timezone.timedelta(days=400)
        
        with patch('django.utils.timezone.now') as mock_now:
            mock_now.return_value = old_date
            old_log = ActivityLog.objects.create(
                action='old_action',
                details='Old details'
            )
        
        result = cleanup_expired_content()
        
        self.assertTrue(result['success'])
        # The old log should be deleted
        self.assertFalse(ActivityLog.objects.filter(id=old_log.id).exists())


class SystemMaintenanceTaskTests(CeleryTaskTestCase):
    """Tests for system maintenance tasks"""
    
    @patch('models_app.tasks.connection')
    @patch('models_app.tasks.cache')
    @patch('models_app.tasks.default_storage')
    def test_system_health_check_success(self, mock_storage, mock_cache, mock_connection):
        """Test successful system health check"""
        # Mock successful connections
        mock_connection.cursor.return_value.__enter__.return_value.execute.return_value = None
        mock_cache.set.return_value = True
        mock_cache.get.return_value = 'ok'
        mock_storage.save.return_value = 'health_check.txt'
        mock_storage.exists.return_value = True
        mock_storage.delete.return_value = True
        
        result = system_health_check()
        
        self.assertTrue(result['success'])
        self.assertTrue(result['health_status']['overall'])
        self.assertTrue(result['health_status']['database'])
        self.assertTrue(result['health_status']['cache'])
        self.assertTrue(result['health_status']['storage'])
    
    @patch('models_app.tasks.connection')
    @patch('models_app.tasks.cache')
    @patch('models_app.tasks.default_storage')
    def test_system_health_check_failure(self, mock_storage, mock_cache, mock_connection):
        """Test system health check with failures"""
        # Mock failed connections
        mock_connection.cursor.side_effect = Exception('DB connection failed')
        mock_cache.set.side_effect = Exception('Cache connection failed')
        mock_storage.save.side_effect = Exception('Storage failed')
        
        result = system_health_check()
        
        self.assertTrue(result['success'])  # Task succeeds even if checks fail
        self.assertFalse(result['health_status']['overall'])
        self.assertFalse(result['health_status']['database'])
        self.assertFalse(result['health_status']['cache'])
        self.assertFalse(result['health_status']['storage'])


class TaskIntegrationTests(CeleryTaskTestCase):
    """Integration tests for task workflows"""
    
    @patch('models_app.tasks.send_notification_email')
    def test_user_registration_workflow(self, mock_send_email):
        """Test complete user registration workflow with email"""
        mock_send_email.delay.return_value.get.return_value = {'success': True}
        
        # Simulate user registration
        new_user = User.objects.create(
            email='newuser@test.com',
            first_name='New',
            last_name='User',
            organization=self.organization
        )
        
        # Send welcome email
        result = send_welcome_email(new_user.id, 'user')
        
        self.assertTrue(result['success'])
        mock_send_email.delay.assert_called_once()
    
    @patch('models_app.tasks.process_uploaded_image')
    @patch('models_app.tasks.send_notification_email')
    def test_content_creation_workflow(self, mock_send_email, mock_process_image):
        """Test content creation with image processing and notifications"""
        mock_process_image.delay.return_value.get.return_value = {
            'success': True,
            'thumbnail_path': 'thumb.jpg'
        }
        mock_send_email.delay.return_value.get.return_value = {'success': True}
        
        # Create blog post with image
        blog_post = BlogPost.objects.create(
            title='Test Post with Image',
            content='Content with image',
            organization=self.organization,
            status='published'
        )
        
        # Process image
        image_result = process_uploaded_image.delay('test_image.jpg').get()
        
        # Send notification
        email_result = send_notification_email.delay(
            'subscriber@test.com',
            'New Blog Post',
            'new_blog_post',
            {'blog_post': blog_post}
        ).get()
        
        self.assertTrue(image_result['success'])
        self.assertTrue(email_result['success'])


@override_settings(CELERY_TASK_ALWAYS_EAGER=True)
class TaskAPITests(CeleryTaskTestCase):
    """Tests for task API endpoints"""
    
    def setUp(self):
        super().setUp()
        self.client.force_authenticate(user=self.admin)
    
    def test_send_email_notification_api(self):
        """Test email notification API endpoint"""
        data = {
            'recipient_email': 'test@example.com',
            'subject': 'Test Subject',
            'template_name': 'test_template',
            'context': {'name': 'Test User'}
        }
        
        with patch('models_app.tasks.send_notification_email') as mock_task:
            mock_task.delay.return_value.id = 'test-task-id'
            
            response = self.client.post('/api/v1/tasks/email/send/', data, format='json')
            
            self.assertEqual(response.status_code, 200)
            self.assertEqual(response.data['task_id'], 'test-task-id')
            self.assertEqual(response.data['status'], 'started')
    
    def test_export_data_api(self):
        """Test data export API endpoint"""
        data = {
            'model_name': 'admin',
            'filters': {},
            'fields': ['id', 'email', 'first_name']
        }
        
        with patch('models_app.tasks.export_data_to_csv') as mock_task:
            mock_task.delay.return_value.id = 'export-task-id'
            
            response = self.client.post('/api/v1/tasks/export/', data, format='json')
            
            self.assertEqual(response.status_code, 200)
            self.assertEqual(response.data['task_id'], 'export-task-id')
    
    def test_worker_status_api(self):
        """Test worker status API endpoint"""
        with patch('celery.current_app.control.inspect') as mock_inspect:
            mock_inspect.return_value.stats.return_value = {'worker1': {'total': 10}}
            mock_inspect.return_value.active.return_value = {'worker1': []}
            mock_inspect.return_value.scheduled.return_value = {'worker1': []}
            mock_inspect.return_value.reserved.return_value = {'worker1': []}
            
            response = self.client.get('/api/v1/tasks/workers/')
            
            self.assertEqual(response.status_code, 200)
            self.assertEqual(response.data['total_workers'], 1)
    
    def test_task_status_api(self):
        """Test task status API endpoint"""
        with patch('celery.result.AsyncResult') as mock_result:
            mock_result.return_value.status = 'SUCCESS'
            mock_result.return_value.ready.return_value = True
            mock_result.return_value.successful.return_value = True
            mock_result.return_value.failed.return_value = False
            mock_result.return_value.result = {'success': True}
            
            response = self.client.get('/api/v1/tasks/status/test-task-id/')
            
            self.assertEqual(response.status_code, 200)
            self.assertEqual(response.data['status'], 'SUCCESS')
            self.assertTrue(response.data['ready'])