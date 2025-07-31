from django.test import TestCase, Client
from django.urls import reverse
from django.contrib.auth import get_user_model
from django.utils import timezone
from unittest.mock import patch
from .models import (
    Admin, User, Organization, BlogPost, News, Event, Resource,
    ActivityLog, AdminActivityLog, Country, City, Region,
    OrganizationType, CategoryOfOrganization, Tag
)
from .admin import admin_site


class AdminInterfaceTestCase(TestCase):
    """Test cases for Django Admin interface configuration"""
    
    @classmethod
    def setUpClass(cls):
        """Set up class-level test configuration"""
        super().setUpClass()
        # Enable model management for testing
        from . import models
        import inspect
        
        for name, obj in inspect.getmembers(models):
            if inspect.isclass(obj) and hasattr(obj, '_meta') and hasattr(obj._meta, 'managed'):
                obj._meta.managed = True
    
    def setUp(self):
        """Set up test data"""
        self.client = Client()
        
        # Create test admin user
        self.admin_user = Admin.objects.create(
            email='admin@test.com',
            name='Test Admin',
            role='super_admin',
            status=1,
            created=timezone.now(),
            modified=timezone.now()
        )
        self.admin_user.set_password('testpass123')
        self.admin_user.save()
        
        # Create regular admin user
        self.regular_admin = Admin.objects.create(
            email='regular@test.com',
            name='Regular Admin',
            role='admin',
            status=1,
            created=timezone.now(),
            modified=timezone.now()
        )
        self.regular_admin.set_password('testpass123')
        self.regular_admin.save()
        
        # Create test data
        self.country = Country.objects.create(
            name='Test Country',
            nicename='Test Country',
            iso='TC',
            phonecode=123
        )
        
        self.city = City.objects.create(
            name='Test City',
            country=self.country
        )
        
        self.region = Region.objects.create(name='Test Region')
        
        self.org_type = OrganizationType.objects.create(name='Test Org Type')
        
        self.organization = Organization.objects.create(
            name='Test Organization',
            organization_type=self.org_type,
            country=self.country,
            city=self.city,
            email='org@test.com',
            status=1,
            created=timezone.now(),
            modified=timezone.now()
        )
        
        self.user = User.objects.create(
            first_name='Test',
            last_name='User',
            email='user@test.com',
            resident_country=self.country,
            city=self.city,
            status=1,
            created=timezone.now(),
            modified=timezone.now()
        )
        
        self.blog_post = BlogPost.objects.create(
            title='Test Blog Post',
            slug='test-blog-post',
            content='Test content',
            status=1,
            region=self.region,
            created=timezone.now(),
            modified=timezone.now()
        )
        
        self.news = News.objects.create(
            title='Test News',
            slug='test-news',
            content='Test news content',
            organization=self.organization,
            status=1,
            created=timezone.now(),
            modified=timezone.now()
        )
        
        self.event = Event.objects.create(
            title='Test Event',
            slug='test-event',
            content='Test event content',
            organization=self.organization,
            status=1,
            created=timezone.now(),
            modified=timezone.now()
        )
        
        self.resource = Resource.objects.create(
            title='Test Resource',
            slug='test-resource',
            content='Test resource content',
            organization=self.organization,
            status=1,
            created=timezone.now(),
            modified=timezone.now()
        )

    def test_admin_site_configuration(self):
        """Test custom admin site configuration"""
        self.assertEqual(admin_site.site_header, "AU-VLP Admin Portal")
        self.assertEqual(admin_site.site_title, "AU-VLP Admin")
        self.assertEqual(admin_site.index_title, "African Union Youth Leadership Program Administration")

    def test_admin_login_required(self):
        """Test that admin interface requires authentication"""
        response = self.client.get('/admin/')
        self.assertEqual(response.status_code, 302)  # Redirect to login

    def test_admin_login_success(self):
        """Test successful admin login"""
        login_successful = self.client.login(email='admin@test.com', password='testpass123')
        self.assertTrue(login_successful)
        
        response = self.client.get('/admin/')
        self.assertEqual(response.status_code, 200)

    def test_admin_permissions_super_admin(self):
        """Test super admin permissions"""
        self.client.force_login(self.admin_user)
        
        # Super admin should access all admin pages
        admin_urls = [
            '/admin/models_app/admin/',
            '/admin/models_app/user/',
            '/admin/models_app/organization/',
            '/admin/models_app/blogpost/',
            '/admin/models_app/news/',
            '/admin/models_app/event/',
            '/admin/models_app/resource/',
            '/admin/models_app/activitylog/',
        ]
        
        for url in admin_urls:
            response = self.client.get(url)
            self.assertEqual(response.status_code, 200, f"Failed to access {url}")

    def test_admin_permissions_regular_admin(self):
        """Test regular admin permissions"""
        self.client.force_login(self.regular_admin)
        
        # Regular admin should have limited access
        response = self.client.get('/admin/models_app/admin/')
        self.assertEqual(response.status_code, 200)
        
        # Should only see their own admin record
        response = self.client.get('/admin/models_app/admin/')
        self.assertContains(response, 'regular@test.com')
        self.assertNotContains(response, 'admin@test.com')

    def test_admin_model_display(self):
        """Test Admin model admin display"""
        self.client.force_login(self.admin_user)
        
        response = self.client.get('/admin/models_app/admin/')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'admin@test.com')
        self.assertContains(response, 'Test Admin')
        self.assertContains(response, 'super_admin')

    def test_user_model_display(self):
        """Test User model admin display"""
        self.client.force_login(self.admin_user)
        
        response = self.client.get('/admin/models_app/user/')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Test User')
        self.assertContains(response, 'user@test.com')

    def test_organization_model_display(self):
        """Test Organization model admin display"""
        self.client.force_login(self.admin_user)
        
        response = self.client.get('/admin/models_app/organization/')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Test Organization')
        self.assertContains(response, 'Test Country')

    def test_blog_post_admin_display(self):
        """Test BlogPost admin display and functionality"""
        self.client.force_login(self.admin_user)
        
        response = self.client.get('/admin/models_app/blogpost/')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Test Blog Post')
        self.assertContains(response, 'test-blog-post')

    def test_news_admin_display(self):
        """Test News admin display"""
        self.client.force_login(self.admin_user)
        
        response = self.client.get('/admin/models_app/news/')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Test News')
        self.assertContains(response, 'Test Organization')

    def test_event_admin_display(self):
        """Test Event admin display"""
        self.client.force_login(self.admin_user)
        
        response = self.client.get('/admin/models_app/event/')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Test Event')

    def test_resource_admin_display(self):
        """Test Resource admin display"""
        self.client.force_login(self.admin_user)
        
        response = self.client.get('/admin/models_app/resource/')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Test Resource')

    def test_activity_log_admin_readonly(self):
        """Test ActivityLog admin is read-only"""
        self.client.force_login(self.admin_user)
        
        # Create test activity log
        activity_log = ActivityLog.objects.create(
            scope_model='TestModel',
            scope_id='1',
            level='info',
            action='test_action',
            message='Test message'
        )
        
        response = self.client.get('/admin/models_app/activitylog/')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Test message')
        
        # Should not be able to add new activity logs
        response = self.client.get('/admin/models_app/activitylog/add/')
        self.assertEqual(response.status_code, 403)  # Forbidden

    def test_admin_actions(self):
        """Test custom admin actions"""
        self.client.force_login(self.admin_user)
        
        # Test bulk status change actions
        response = self.client.post('/admin/models_app/blogpost/', {
            'action': 'make_published',
            '_selected_action': [self.blog_post.id],
        })
        
        # Should redirect back to changelist
        self.assertEqual(response.status_code, 302)
        
        # Verify status was changed
        self.blog_post.refresh_from_db()
        self.assertEqual(self.blog_post.status, 1)

    def test_admin_search_functionality(self):
        """Test admin search functionality"""
        self.client.force_login(self.admin_user)
        
        # Test blog post search
        response = self.client.get('/admin/models_app/blogpost/?q=Test+Blog')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Test Blog Post')
        
        # Test user search
        response = self.client.get('/admin/models_app/user/?q=Test+User')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Test User')

    def test_admin_filtering(self):
        """Test admin list filtering"""
        self.client.force_login(self.admin_user)
        
        # Test blog post status filtering
        response = self.client.get('/admin/models_app/blogpost/?status__exact=1')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Test Blog Post')
        
        # Test organization country filtering
        response = self.client.get(f'/admin/models_app/organization/?country__id__exact={self.country.id}')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Test Organization')

    def test_admin_form_validation(self):
        """Test admin form validation"""
        self.client.force_login(self.admin_user)
        
        # Test creating admin with duplicate email
        response = self.client.post('/admin/models_app/admin/add/', {
            'email': 'admin@test.com',  # Duplicate email
            'name': 'Another Admin',
            'password1': 'testpass123',
            'password2': 'testpass123',
            'role': 'admin',
            'status': 1,
        })
        
        # Should show form with errors
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'Admin with this Email already exists')

    def test_admin_inline_functionality(self):
        """Test admin inline functionality"""
        self.client.force_login(self.admin_user)
        
        # Test organization with inlines
        response = self.client.get(f'/admin/models_app/organization/{self.organization.id}/change/')
        self.assertEqual(response.status_code, 200)
        
        # Should contain inline forms for users and offices
        self.assertContains(response, 'organization_users')
        self.assertContains(response, 'organization_offices')

    def test_status_display_methods(self):
        """Test custom status display methods"""
        from .admin import AdminAdmin, UserAdmin, BlogPostAdmin
        
        admin_admin = AdminAdmin(Admin, admin_site)
        user_admin = UserAdmin(User, admin_site)
        blog_admin = BlogPostAdmin(BlogPost, admin_site)
        
        # Test admin status display
        status_html = admin_admin.status_display(self.admin_user)
        self.assertIn('color: green', status_html)
        self.assertIn('Active', status_html)
        
        # Test user status display
        status_html = user_admin.status_display(self.user)
        self.assertIn('color: green', status_html)
        self.assertIn('Active', status_html)
        
        # Test blog post status display
        status_html = blog_admin.status_display(self.blog_post)
        self.assertIn('color: green', status_html)
        self.assertIn('Published', status_html)

    def test_custom_admin_templates(self):
        """Test custom admin templates are loaded"""
        self.client.force_login(self.admin_user)
        
        response = self.client.get('/admin/')
        self.assertEqual(response.status_code, 200)
        
        # Should contain custom styling and branding
        self.assertContains(response, 'AU-VLP Admin Portal')
        self.assertContains(response, 'African Union Youth Leadership Program')

    def test_admin_permissions_inheritance(self):
        """Test admin permissions inheritance from mixins"""
        from .admin import RoleBasedPermissionMixin
        
        # Create mock request with admin user
        class MockRequest:
            def __init__(self, user):
                self.user = user
        
        mixin = RoleBasedPermissionMixin()
        
        # Super admin should have all permissions
        request = MockRequest(self.admin_user)
        self.assertTrue(mixin.has_change_permission(request))
        self.assertTrue(mixin.has_delete_permission(request))
        
        # Regular admin should have limited permissions
        request = MockRequest(self.regular_admin)
        self.assertTrue(mixin.has_change_permission(request))
        self.assertFalse(mixin.has_delete_permission(request))


class AdminManagementCommandTestCase(TestCase):
    """Test cases for admin management commands"""
    
    @classmethod
    def setUpClass(cls):
        """Set up class-level test configuration"""
        super().setUpClass()
        # Enable model management for testing
        from . import models
        import inspect
        
        for name, obj in inspect.getmembers(models):
            if inspect.isclass(obj) and hasattr(obj, '_meta') and hasattr(obj._meta, 'managed'):
                obj._meta.managed = True
    
    def test_create_admin_user_command(self):
        """Test create_admin_user management command"""
        from django.core.management import call_command
        from io import StringIO
        
        out = StringIO()
        call_command(
            'create_admin_user',
            '--email=test@example.com',
            '--name=Test Admin',
            '--password=testpass123',
            stdout=out
        )
        
        self.assertIn('Successfully created superuser admin', out.getvalue())
        
        # Verify admin was created
        admin = Admin.objects.get(email='test@example.com')
        self.assertEqual(admin.name, 'Test Admin')
        self.assertEqual(admin.role, 'super_admin')
        self.assertEqual(admin.status, 1)

    def test_create_admin_user_duplicate_email(self):
        """Test create_admin_user command with duplicate email"""
        from django.core.management import call_command
        from io import StringIO
        
        # Create first admin
        Admin.objects.create(
            email='duplicate@example.com',
            name='First Admin',
            role='admin',
            status=1,
            created=timezone.now(),
            modified=timezone.now()
        )
        
        out = StringIO()
        call_command(
            'create_admin_user',
            '--email=duplicate@example.com',
            '--name=Second Admin',
            '--password=testpass123',
            stdout=out
        )
        
        self.assertIn('already exists', out.getvalue())
        
        # Should still only have one admin with this email
        self.assertEqual(Admin.objects.filter(email='duplicate@example.com').count(), 1)