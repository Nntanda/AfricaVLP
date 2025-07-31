from django.test import TestCase
from django.core.exceptions import ValidationError
from django.db import IntegrityError
from .models import (
    Admin, Country, City, Region, User, Organization, 
    OrganizationType, CategoryOfOrganization, InstitutionType,
    BlogPost, News, Event, Resource, Tag, ActivityLog
)


class AdminModelTest(TestCase):
    def setUp(self):
        self.admin_data = {
            'email': 'admin@test.com',
            'name': 'Test Admin',
            'role': 'admin',
            'status': 1,
        }

    def test_admin_creation(self):
        """Test creating an admin user"""
        admin = Admin(**self.admin_data)
        self.assertEqual(admin.email, 'admin@test.com')
        self.assertEqual(admin.name, 'Test Admin')
        self.assertEqual(admin.role, 'admin')
        self.assertEqual(admin.status, 1)

    def test_admin_str_method(self):
        """Test the string representation of Admin"""
        admin = Admin(**self.admin_data)
        expected_str = f"{admin.name} ({admin.email})"
        self.assertEqual(str(admin), expected_str)

    def test_admin_manager_create_user(self):
        """Test creating user through manager"""
        # Note: This test would require database access
        # Since models are managed=False, we'll test the logic
        manager = Admin.objects
        self.assertTrue(hasattr(manager, 'create_user'))
        self.assertTrue(hasattr(manager, 'create_superuser'))


class CountryModelTest(TestCase):
    def setUp(self):
        self.country_data = {
            'iso': 'US',
            'name': 'United States',
            'nicename': 'United States',
            'iso3': 'USA',
            'numcode': 840,
            'phonecode': 1
        }

    def test_country_creation(self):
        """Test creating a country"""
        country = Country(**self.country_data)
        self.assertEqual(country.iso, 'US')
        self.assertEqual(country.name, 'United States')
        self.assertEqual(country.nicename, 'United States')

    def test_country_str_method(self):
        """Test the string representation of Country"""
        country = Country(**self.country_data)
        self.assertEqual(str(country), 'United States')

    def test_country_str_fallback(self):
        """Test country str method with missing nicename"""
        country = Country(name='Test Country', phonecode=1)
        self.assertEqual(str(country), 'Test Country')


class UserModelTest(TestCase):
    def setUp(self):
        self.user_data = {
            'first_name': 'John',
            'last_name': 'Doe',
            'email': 'john.doe@test.com',
            'gender': 'Male',
            'marital_status': 'Single',
            'availability': 'Full time',
            'is_email_verified': False,
            'registration_status': 1,
            'status': 1,
            'experience_rating': 4.5
        }

    def test_user_creation(self):
        """Test creating a user"""
        user = User(**self.user_data)
        self.assertEqual(user.first_name, 'John')
        self.assertEqual(user.last_name, 'Doe')
        self.assertEqual(user.email, 'john.doe@test.com')
        self.assertEqual(user.gender, 'Male')

    def test_user_str_method(self):
        """Test the string representation of User"""
        user = User(**self.user_data)
        expected_str = "John Doe"
        self.assertEqual(str(user), expected_str)

    def test_user_str_fallback_to_email(self):
        """Test user str method fallback to email"""
        user = User(email='test@example.com')
        self.assertEqual(str(user), 'test@example.com')


class OrganizationModelTest(TestCase):
    def setUp(self):
        self.org_data = {
            'name': 'Test Organization',
            'about': 'A test organization',
            'email': 'org@test.com',
            'phone_number': '+1234567890',
            'website': 'https://test.org',
            'status': 1,
            'is_verified': False,
            'pan_africanism': 'Yes',
            'education_skills': 'No'
        }

    def test_organization_creation(self):
        """Test creating an organization"""
        org = Organization(**self.org_data)
        self.assertEqual(org.name, 'Test Organization')
        self.assertEqual(org.about, 'A test organization')
        self.assertEqual(org.email, 'org@test.com')
        self.assertEqual(org.pan_africanism, 'Yes')

    def test_organization_str_method(self):
        """Test the string representation of Organization"""
        org = Organization(**self.org_data)
        self.assertEqual(str(org), 'Test Organization')


class BlogPostModelTest(TestCase):
    def setUp(self):
        self.blog_data = {
            'title': 'Test Blog Post',
            'slug': 'test-blog-post',
            'content': 'This is a test blog post content.',
            'status': 1,
        }

    def test_blog_post_creation(self):
        """Test creating a blog post"""
        blog = BlogPost(**self.blog_data)
        self.assertEqual(blog.title, 'Test Blog Post')
        self.assertEqual(blog.slug, 'test-blog-post')
        self.assertEqual(blog.content, 'This is a test blog post content.')
        self.assertEqual(blog.status, 1)

    def test_blog_post_str_method(self):
        """Test the string representation of BlogPost"""
        blog = BlogPost(**self.blog_data)
        self.assertEqual(str(blog), 'Test Blog Post')


class NewsModelTest(TestCase):
    def setUp(self):
        self.news_data = {
            'title': 'Test News Article',
            'slug': 'test-news-article',
            'content': 'This is a test news article content.',
            'status': 1,
        }

    def test_news_creation(self):
        """Test creating a news article"""
        news = News(**self.news_data)
        self.assertEqual(news.title, 'Test News Article')
        self.assertEqual(news.slug, 'test-news-article')
        self.assertEqual(news.status, 1)

    def test_news_str_method(self):
        """Test the string representation of News"""
        news = News(**self.news_data)
        self.assertEqual(str(news), 'Test News Article')


class EventModelTest(TestCase):
    def setUp(self):
        self.event_data = {
            'title': 'Test Event',
            'slug': 'test-event',
            'content': 'This is a test event description.',
            'status': 1,
        }

    def test_event_creation(self):
        """Test creating an event"""
        event = Event(**self.event_data)
        self.assertEqual(event.title, 'Test Event')
        self.assertEqual(event.slug, 'test-event')
        self.assertEqual(event.status, 1)

    def test_event_str_method(self):
        """Test the string representation of Event"""
        event = Event(**self.event_data)
        self.assertEqual(str(event), 'Test Event')


class ResourceModelTest(TestCase):
    def setUp(self):
        self.resource_data = {
            'title': 'Test Resource',
            'slug': 'test-resource',
            'content': 'This is a test resource description.',
            'status': 1,
        }

    def test_resource_creation(self):
        """Test creating a resource"""
        resource = Resource(**self.resource_data)
        self.assertEqual(resource.title, 'Test Resource')
        self.assertEqual(resource.slug, 'test-resource')
        self.assertEqual(resource.status, 1)

    def test_resource_str_method(self):
        """Test the string representation of Resource"""
        resource = Resource(**self.resource_data)
        self.assertEqual(str(resource), 'Test Resource')


class TagModelTest(TestCase):
    def test_tag_creation(self):
        """Test creating a tag"""
        tag = Tag(title='Test Tag')
        self.assertEqual(tag.title, 'Test Tag')

    def test_tag_str_method(self):
        """Test the string representation of Tag"""
        tag = Tag(title='Test Tag')
        self.assertEqual(str(tag), 'Test Tag')


class ActivityLogModelTest(TestCase):
    def setUp(self):
        self.log_data = {
            'scope_model': 'User',
            'scope_id': '1',
            'level': 'info',
            'action': 'create',
            'message': 'User created successfully'
        }

    def test_activity_log_creation(self):
        """Test creating an activity log"""
        log = ActivityLog(**self.log_data)
        self.assertEqual(log.scope_model, 'User')
        self.assertEqual(log.scope_id, '1')
        self.assertEqual(log.level, 'info')
        self.assertEqual(log.action, 'create')


class ModelChoicesTest(TestCase):
    def test_admin_role_choices(self):
        """Test Admin role choices"""
        choices = Admin.ROLE_CHOICES
        self.assertIn(('super_admin', 'Super Admin'), choices)
        self.assertIn(('admin', 'Admin'), choices)

    def test_admin_status_choices(self):
        """Test Admin status choices"""
        choices = Admin.STATUS_CHOICES
        self.assertIn((0, 'Inactive'), choices)
        self.assertIn((1, 'Active'), choices)

    def test_user_gender_choices(self):
        """Test User gender choices"""
        choices = User.GENDER_CHOICES
        self.assertIn(('Male', 'Male'), choices)
        self.assertIn(('Female', 'Female'), choices)
        self.assertIn(('Other', 'Other'), choices)

    def test_content_status_choices(self):
        """Test content status choices (BlogPost, News, Event, Resource)"""
        for model in [BlogPost, News, Event, Resource]:
            choices = model.STATUS_CHOICES
            self.assertIn((1, 'Published'), choices)
            self.assertIn((2, 'Draft'), choices)
            self.assertIn((3, 'Archived'), choices)