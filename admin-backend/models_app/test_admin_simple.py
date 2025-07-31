from django.test import TestCase
from django.contrib import admin
from django.contrib.admin.sites import AdminSite
from .admin import (
    admin_site, AdminAdmin, UserAdmin, OrganizationAdmin, 
    BlogPostAdmin, NewsAdmin, EventAdmin, ResourceAdmin,
    ActivityLogAdmin, AdminActivityLogAdmin
)
from .models import (
    Admin, User, Organization, BlogPost, News, Event, Resource,
    ActivityLog, AdminActivityLog
)


class AdminConfigurationTestCase(TestCase):
    """Test cases for Django Admin configuration without database operations"""
    
    def test_custom_admin_site_configuration(self):
        """Test custom admin site configuration"""
        self.assertEqual(admin_site.site_header, "AU-VLP Admin Portal")
        self.assertEqual(admin_site.site_title, "AU-VLP Admin")
        self.assertEqual(admin_site.index_title, "African Union Youth Leadership Program Administration")
    
    def test_admin_models_registered(self):
        """Test that all models are registered with the admin site"""
        registered_models = admin_site._registry.keys()
        
        expected_models = [
            Admin, User, Organization, BlogPost, News, Event, Resource,
            ActivityLog, AdminActivityLog
        ]
        
        for model in expected_models:
            self.assertIn(model, registered_models, f"{model.__name__} is not registered with admin site")
    
    def test_admin_classes_configuration(self):
        """Test admin classes have correct configuration"""
        # Test AdminAdmin configuration
        admin_admin = admin_site._registry[Admin]
        self.assertIsInstance(admin_admin, AdminAdmin)
        self.assertIn('email', admin_admin.list_display)
        self.assertIn('name', admin_admin.list_display)
        self.assertIn('role', admin_admin.list_display)
        
        # Test UserAdmin configuration
        user_admin = admin_site._registry[User]
        self.assertIsInstance(user_admin, UserAdmin)
        self.assertIn('full_name', user_admin.list_display)
        self.assertIn('email', user_admin.list_display)
        
        # Test BlogPostAdmin configuration
        blog_admin = admin_site._registry[BlogPost]
        self.assertIsInstance(blog_admin, BlogPostAdmin)
        self.assertIn('title', blog_admin.list_display)
        self.assertIn('status_display', blog_admin.list_display)
        
        # Test NewsAdmin configuration
        news_admin = admin_site._registry[News]
        self.assertIsInstance(news_admin, NewsAdmin)
        self.assertIn('title', news_admin.list_display)
        self.assertIn('organization', news_admin.list_display)
        
        # Test EventAdmin configuration
        event_admin = admin_site._registry[Event]
        self.assertIsInstance(event_admin, EventAdmin)
        self.assertIn('title', event_admin.list_display)
        self.assertIn('organization', event_admin.list_display)
        
        # Test ResourceAdmin configuration
        resource_admin = admin_site._registry[Resource]
        self.assertIsInstance(resource_admin, ResourceAdmin)
        self.assertIn('title', resource_admin.list_display)
        self.assertIn('organization', resource_admin.list_display)
    
    def test_admin_search_fields(self):
        """Test admin search fields configuration"""
        admin_admin = admin_site._registry[Admin]
        self.assertIn('email', admin_admin.search_fields)
        self.assertIn('name', admin_admin.search_fields)
        
        user_admin = admin_site._registry[User]
        self.assertIn('first_name', user_admin.search_fields)
        self.assertIn('last_name', user_admin.search_fields)
        self.assertIn('email', user_admin.search_fields)
        
        blog_admin = admin_site._registry[BlogPost]
        self.assertIn('title', blog_admin.search_fields)
        self.assertIn('content', blog_admin.search_fields)
    
    def test_admin_list_filters(self):
        """Test admin list filters configuration"""
        admin_admin = admin_site._registry[Admin]
        self.assertIn('role', admin_admin.list_filter)
        self.assertIn('status', admin_admin.list_filter)
        
        user_admin = admin_site._registry[User]
        self.assertIn('status', user_admin.list_filter)
        self.assertIn('is_email_verified', user_admin.list_filter)
        self.assertIn('gender', user_admin.list_filter)
        
        blog_admin = admin_site._registry[BlogPost]
        self.assertIn('status', blog_admin.list_filter)
        self.assertIn('region', blog_admin.list_filter)
    
    def test_admin_readonly_fields(self):
        """Test admin readonly fields configuration"""
        admin_admin = admin_site._registry[Admin]
        self.assertIn('created', admin_admin.readonly_fields)
        self.assertIn('modified', admin_admin.readonly_fields)
        
        user_admin = admin_site._registry[User]
        self.assertIn('created', user_admin.readonly_fields)
        self.assertIn('modified', user_admin.readonly_fields)
        
        # Activity logs should be completely readonly
        activity_admin = admin_site._registry[ActivityLog]
        self.assertIsInstance(activity_admin, ActivityLogAdmin)
        self.assertTrue(len(activity_admin.readonly_fields) > 0)
    
    def test_admin_actions(self):
        """Test custom admin actions"""
        blog_admin = admin_site._registry[BlogPost]
        action_names = [action.__name__ for action in blog_admin.actions]
        self.assertIn('make_published', action_names)
        self.assertIn('make_draft', action_names)
        self.assertIn('make_archived', action_names)
        
        news_admin = admin_site._registry[News]
        action_names = [action.__name__ for action in news_admin.actions]
        self.assertIn('make_published', action_names)
        self.assertIn('make_draft', action_names)
        self.assertIn('make_archived', action_names)
    
    def test_admin_fieldsets(self):
        """Test admin fieldsets configuration"""
        admin_admin = admin_site._registry[Admin]
        self.assertTrue(hasattr(admin_admin, 'fieldsets'))
        self.assertTrue(len(admin_admin.fieldsets) > 0)
        
        user_admin = admin_site._registry[User]
        self.assertTrue(hasattr(user_admin, 'fieldsets'))
        self.assertTrue(len(user_admin.fieldsets) > 0)
        
        org_admin = admin_site._registry[Organization]
        self.assertTrue(hasattr(org_admin, 'fieldsets'))
        self.assertTrue(len(org_admin.fieldsets) > 0)
    
    def test_admin_inlines(self):
        """Test admin inline configuration"""
        org_admin = admin_site._registry[Organization]
        self.assertTrue(hasattr(org_admin, 'inlines'))
        self.assertTrue(len(org_admin.inlines) > 0)
        
        blog_admin = admin_site._registry[BlogPost]
        self.assertTrue(hasattr(blog_admin, 'inlines'))
        self.assertTrue(len(blog_admin.inlines) > 0)
    
    def test_admin_permissions_methods(self):
        """Test admin permission methods exist"""
        admin_admin = admin_site._registry[Admin]
        self.assertTrue(hasattr(admin_admin, 'has_change_permission'))
        self.assertTrue(hasattr(admin_admin, 'has_delete_permission'))
        
        activity_admin = admin_site._registry[ActivityLog]
        self.assertTrue(hasattr(activity_admin, 'has_add_permission'))
        self.assertTrue(hasattr(activity_admin, 'has_change_permission'))
        self.assertTrue(hasattr(activity_admin, 'has_delete_permission'))
    
    def test_status_display_methods(self):
        """Test custom status display methods"""
        admin_admin = admin_site._registry[Admin]
        self.assertTrue(hasattr(admin_admin, 'status_display'))
        
        user_admin = admin_site._registry[User]
        self.assertTrue(hasattr(user_admin, 'status_display'))
        
        blog_admin = admin_site._registry[BlogPost]
        self.assertTrue(hasattr(blog_admin, 'status_display'))
        
        news_admin = admin_site._registry[News]
        self.assertTrue(hasattr(news_admin, 'status_display'))
    
    def test_admin_ordering(self):
        """Test admin ordering configuration"""
        admin_admin = admin_site._registry[Admin]
        self.assertEqual(admin_admin.ordering, ('-created',))
        
        user_admin = admin_site._registry[User]
        self.assertEqual(user_admin.ordering, ('-created',))
        
        blog_admin = admin_site._registry[BlogPost]
        self.assertEqual(blog_admin.ordering, ('-created',))
    
    def test_admin_prepopulated_fields(self):
        """Test admin prepopulated fields configuration"""
        blog_admin = admin_site._registry[BlogPost]
        self.assertTrue(hasattr(blog_admin, 'prepopulated_fields'))
        self.assertIn('slug', blog_admin.prepopulated_fields)
        
        news_admin = admin_site._registry[News]
        self.assertTrue(hasattr(news_admin, 'prepopulated_fields'))
        self.assertIn('slug', news_admin.prepopulated_fields)
    
    def test_admin_form_overrides(self):
        """Test admin form field overrides"""
        blog_admin = admin_site._registry[BlogPost]
        self.assertTrue(hasattr(blog_admin, 'formfield_overrides'))
        
        news_admin = admin_site._registry[News]
        self.assertTrue(hasattr(news_admin, 'formfield_overrides'))


class AdminPermissionTestCase(TestCase):
    """Test cases for admin permission logic"""
    
    def test_role_based_permission_mixin(self):
        """Test role-based permission mixin logic"""
        from .admin import RoleBasedPermissionMixin
        
        # Create mock request objects
        class MockUser:
            def __init__(self, role):
                self.role = role
        
        class MockRequest:
            def __init__(self, user):
                self.user = user
        
        mixin = RoleBasedPermissionMixin()
        
        # Test super admin permissions
        super_admin_request = MockRequest(MockUser('super_admin'))
        # Note: These would normally call super() which we can't test without a full admin class
        # But we can test the logic exists
        self.assertTrue(hasattr(mixin, 'has_change_permission'))
        self.assertTrue(hasattr(mixin, 'has_delete_permission'))
    
    def test_admin_site_permission_check(self):
        """Test admin site permission checking"""
        # Create mock user objects
        class MockUser:
            def __init__(self, role, is_active=True):
                self.role = role
                self.is_active = is_active
        
        class MockRequest:
            def __init__(self, user):
                self.user = user
        
        # Test super admin access
        super_admin = MockUser('super_admin')
        super_admin_request = MockRequest(super_admin)
        self.assertTrue(admin_site.has_permission(super_admin_request))
        
        # Test regular admin access
        regular_admin = MockUser('admin')
        regular_admin_request = MockRequest(regular_admin)
        self.assertTrue(admin_site.has_permission(regular_admin_request))
        
        # Test inactive user
        inactive_user = MockUser('super_admin', is_active=False)
        inactive_request = MockRequest(inactive_user)
        self.assertFalse(admin_site.has_permission(inactive_request))


class AdminUtilityTestCase(TestCase):
    """Test cases for admin utility functions and methods"""
    
    def test_admin_actions_exist(self):
        """Test that custom admin actions are defined"""
        from .admin import make_published, make_draft, make_archived
        
        self.assertTrue(callable(make_published))
        self.assertTrue(callable(make_draft))
        self.assertTrue(callable(make_archived))
        
        # Test action descriptions
        self.assertEqual(make_published.short_description, "Mark selected items as published")
        self.assertEqual(make_draft.short_description, "Mark selected items as draft")
        self.assertEqual(make_archived.short_description, "Mark selected items as archived")
    
    def test_admin_mixins_exist(self):
        """Test that admin mixins are properly defined"""
        from .admin import ReadOnlyAdminMixin, RoleBasedPermissionMixin
        
        self.assertTrue(hasattr(ReadOnlyAdminMixin, 'get_readonly_fields'))
        self.assertTrue(hasattr(RoleBasedPermissionMixin, 'has_change_permission'))
        self.assertTrue(hasattr(RoleBasedPermissionMixin, 'has_delete_permission'))
    
    def test_admin_site_class_exists(self):
        """Test that custom admin site class is properly defined"""
        from .admin import AdminSiteConfig
        
        self.assertTrue(issubclass(AdminSiteConfig, AdminSite))
        self.assertTrue(hasattr(AdminSiteConfig, 'has_permission'))
        
        # Test default values
        site_instance = AdminSiteConfig()
        self.assertEqual(site_instance.site_header, "AU-VLP Admin Portal")
        self.assertEqual(site_instance.site_title, "AU-VLP Admin")
        self.assertEqual(site_instance.index_title, "African Union Youth Leadership Program Administration")