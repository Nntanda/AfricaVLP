"""
Integration tests for API endpoints
Tests all CRUD operations, filtering, search, and permissions
"""
from django.test import TestCase
from django.urls import reverse
from django.contrib.auth.hashers import make_password
from rest_framework.test import APITestCase, APIClient
from rest_framework import status
from rest_framework_simplejwt.tokens import RefreshToken
from unittest.mock import patch
import json
from datetime import datetime, timedelta

from .models import (
    Admin, User, Country, City, Region, Organization, OrganizationType,
    CategoryOfOrganization, InstitutionType, BlogPost, News, Event, Resource,
    Tag, PublishingCategory, BlogCategory, BlogPostTag, BlogPostComment,
    NewsCategory, NewsTag, NewsComment, EventComment, CategoryOfResource,
    ResourceType, ResourceCategory, ActivityLog, AdminActivityLog,
    OrganizationUser, OrganizationOffice, I18n
)


class BaseAPITestCase(APITestCase):
    """Base test case with common setup for API tests"""
    
    def setUp(self):
        self.client = APIClient()
        
        # Create test admin users
        self.super_admin = Admin.objects.create(
            email='superadmin@test.com',
            name='Super Admin',
            password=make_password('testpass123'),
            role='super_admin',
            status=1
        )
        
        self.admin = Admin.objects.create(
            email='admin@test.com',
            name='Test Admin',
            password=make_password('testpass123'),
            role='admin',
            status=1
        )
        
        # Create test regular user
        self.user = User.objects.create(
            first_name='John',
            last_name='Doe',
            email='user@test.com',
            password=make_password('testpass123'),
            status=1,
            is_email_verified=True
        )
        
        # Create test country and city
        self.country = Country.objects.create(
            iso='US',
            name='United States',
            nicename='United States',
            iso3='USA',
            numcode=840,
            phonecode=1
        )
        
        self.city = City.objects.create(
            name='New York',
            country=self.country
        )
        
        # Create test region
        self.region = Region.objects.create(
            name='North America'
        )
        
        # Create test organization type and category
        self.org_type = OrganizationType.objects.create(
            name='Non-Profit'
        )
        
        self.org_category = CategoryOfOrganization.objects.create(
            name='Education'
        )
        
        self.institution_type = InstitutionType.objects.create(
            name='University'
        )
        
        # Create test organization
        self.organization = Organization.objects.create(
            name='Test Organization',
            about='A test organization',
            email='org@test.com',
            phone_number='+1234567890',
            website='https://test.org',
            status=1,
            is_verified=True,
            organization_type=self.org_type,
            country=self.country,
            city=self.city,
            category=self.org_category,
            institution_type=self.institution_type,
            user=self.user
        )
        
        # Create test tags
        self.tag1 = Tag.objects.create(title='Technology')
        self.tag2 = Tag.objects.create(title='Education')
        
        # Create test content
        self.blog_post = BlogPost.objects.create(
            title='Test Blog Post',
            slug='test-blog-post',
            content='This is a test blog post content.',
            status=1,
            region=self.region
        )
        
        self.news = News.objects.create(
            title='Test News Article',
            slug='test-news-article',
            content='This is a test news article content.',
            status=1,
            organization=self.organization,
            region_id=self.region.id
        )
        
        self.event = Event.objects.create(
            title='Test Event',
            slug='test-event',
            content='This is a test event description.',
            status=1,
            organization=self.organization,
            region_id=self.region.id
        )
        
        self.resource = Resource.objects.create(
            title='Test Resource',
            slug='test-resource',
            content='This is a test resource description.',
            status=1,
            organization=self.organization,
            region_id=self.region.id
        )
    
    def get_admin_token(self, admin_user=None):
        """Get JWT token for admin user"""
        if admin_user is None:
            admin_user = self.admin
        refresh = RefreshToken.for_user(admin_user)
        return str(refresh.access_token)
    
    def get_user_token(self, user=None):
        """Get JWT token for regular user"""
        if user is None:
            user = self.user
        refresh = RefreshToken.for_user(user)
        return str(refresh.access_token)
    
    def authenticate_admin(self, admin_user=None):
        """Authenticate as admin user"""
        token = self.get_admin_token(admin_user)
        self.client.credentials(HTTP_AUTHORIZATION=f'Bearer {token}')
    
    def authenticate_user(self, user=None):
        """Authenticate as regular user"""
        token = self.get_user_token(user)
        self.client.credentials(HTTP_AUTHORIZATION=f'Bearer {token}')
    
    def clear_authentication(self):
        """Clear authentication credentials"""
        self.client.credentials()


class AdminManagementAPITest(BaseAPITestCase):
    """Test admin management endpoints"""
    
    def test_admin_list_requires_super_admin(self):
        """Test that admin list requires super admin permission"""
        # Test without authentication
        response = self.client.get('/api/v1/admins/')
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)
        
        # Test with regular admin
        self.authenticate_admin()
        response = self.client.get('/api/v1/admins/')
        self.assertEqual(response.status_code, status.HTTP_403_FORBIDDEN)
        
        # Test with super admin
        self.authenticate_admin(self.super_admin)
        response = self.client.get('/api/v1/admins/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
    
    def test_admin_create(self):
        """Test creating a new admin"""
        self.authenticate_admin(self.super_admin)
        
        data = {
            'email': 'newadmin@test.com',
            'name': 'New Admin',
            'role': 'admin',
            'status': 1,
            'password': 'newpass123'
        }
        
        response = self.client.post('/api/v1/admins/', data)
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        
        # Verify admin was created
        admin = Admin.objects.get(email='newadmin@test.com')
        self.assertEqual(admin.name, 'New Admin')
        self.assertEqual(admin.role, 'admin')
    
    def test_admin_update(self):
        """Test updating an admin"""
        self.authenticate_admin(self.super_admin)
        
        data = {
            'name': 'Updated Admin Name',
            'role': 'super_admin'
        }
        
        response = self.client.patch(f'/api/v1/admins/{self.admin.id}/', data)
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify admin was updated
        self.admin.refresh_from_db()
        self.assertEqual(self.admin.name, 'Updated Admin Name')
        self.assertEqual(self.admin.role, 'super_admin')
    
    def test_admin_delete(self):
        """Test deleting an admin"""
        self.authenticate_admin(self.super_admin)
        
        response = self.client.delete(f'/api/v1/admins/{self.admin.id}/')
        self.assertEqual(response.status_code, status.HTTP_204_NO_CONTENT)
        
        # Verify admin was deleted
        self.assertFalse(Admin.objects.filter(id=self.admin.id).exists())
    
    def test_admin_search_and_filter(self):
        """Test admin search and filtering"""
        self.authenticate_admin(self.super_admin)
        
        # Test search by name
        response = self.client.get('/api/v1/admins/?search=Test')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertGreater(data['count'], 0)
        
        # Test filter by role
        response = self.client.get('/api/v1/admins/?role=admin')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        for admin in data['results']:
            self.assertEqual(admin['role'], 'admin')
        
        # Test filter by status
        response = self.client.get('/api/v1/admins/?status=1')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        for admin in data['results']:
            self.assertEqual(admin['status'], 1)


class UserManagementAPITest(BaseAPITestCase):
    """Test user management endpoints"""
    
    def test_user_list_requires_authentication(self):
        """Test that user list requires authentication"""
        response = self.client.get('/api/v1/users/')
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)
        
        # Test with authentication
        self.authenticate_admin()
        response = self.client.get('/api/v1/users/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
    
    def test_user_create_by_admin(self):
        """Test creating a user by admin"""
        self.authenticate_admin()
        
        data = {
            'first_name': 'Jane',
            'last_name': 'Smith',
            'email': 'jane.smith@test.com',
            'gender': 'Female',
            'marital_status': 'Single',
            'availability': 'Part time',
            'status': 1,
            'is_email_verified': True
        }
        
        response = self.client.post('/api/v1/users/', data)
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        
        # Verify user was created
        user = User.objects.get(email='jane.smith@test.com')
        self.assertEqual(user.first_name, 'Jane')
        self.assertEqual(user.last_name, 'Smith')
    
    def test_user_update_by_admin(self):
        """Test updating a user by admin"""
        self.authenticate_admin()
        
        data = {
            'first_name': 'Updated John',
            'availability': 'Full time'
        }
        
        response = self.client.patch(f'/api/v1/users/{self.user.id}/', data)
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify user was updated
        self.user.refresh_from_db()
        self.assertEqual(self.user.first_name, 'Updated John')
        self.assertEqual(self.user.availability, 'Full time')
    
    def test_user_email_verification(self):
        """Test user email verification by super admin"""
        # Create unverified user
        unverified_user = User.objects.create(
            first_name='Unverified',
            last_name='User',
            email='unverified@test.com',
            status=1,
            is_email_verified=False
        )
        
        self.authenticate_admin(self.super_admin)
        
        response = self.client.post(f'/api/v1/users/{unverified_user.id}/verify_email/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify email was verified
        unverified_user.refresh_from_db()
        self.assertTrue(unverified_user.is_email_verified)
    
    def test_user_status_update(self):
        """Test updating user status"""
        self.authenticate_admin()
        
        data = {'status': 0}  # Deactivate user
        
        response = self.client.post(f'/api/v1/users/{self.user.id}/update_status/', data)
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify status was updated
        self.user.refresh_from_db()
        self.assertEqual(self.user.status, 0)
    
    def test_user_search_and_filter(self):
        """Test user search and filtering"""
        self.authenticate_admin()
        
        # Test search by name
        response = self.client.get('/api/v1/users/?search=John')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertGreater(data['count'], 0)
        
        # Test filter by gender
        response = self.client.get('/api/v1/users/?gender=Male')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Test filter by status
        response = self.client.get('/api/v1/users/?status=1')
        self.assertEqual(response.status_code, status.HTTP_200_OK)


class OrganizationManagementAPITest(BaseAPITestCase):
    """Test organization management endpoints"""
    
    def test_organization_list_public_access(self):
        """Test that organization list allows public access"""
        response = self.client.get('/api/v1/organizations/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
    
    def test_organization_create_requires_admin(self):
        """Test that organization creation requires admin permission"""
        data = {
            'name': 'New Organization',
            'about': 'A new organization',
            'email': 'neworg@test.com',
            'status': 1
        }
        
        # Test without authentication
        response = self.client.post('/api/v1/organizations/', data)
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)
        
        # Test with admin authentication
        self.authenticate_admin()
        response = self.client.post('/api/v1/organizations/', data)
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
    
    def test_organization_verification(self):
        """Test organization verification by admin"""
        # Create unverified organization
        unverified_org = Organization.objects.create(
            name='Unverified Org',
            about='An unverified organization',
            email='unverified@org.com',
            status=1,
            is_verified=False,
            user=self.user
        )
        
        self.authenticate_admin()
        
        response = self.client.post(f'/api/v1/organizations/{unverified_org.id}/verify/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify organization was verified
        unverified_org.refresh_from_db()
        self.assertTrue(unverified_org.is_verified)
    
    def test_organization_offices_endpoint(self):
        """Test organization offices endpoint"""
        # Create test office
        office = OrganizationOffice.objects.create(
            organization=self.organization,
            address='123 Test Street',
            country=self.country,
            city=self.city,
            email='office@test.com',
            phone_number='+1234567890'
        )
        
        response = self.client.get(f'/api/v1/organizations/{self.organization.id}/offices/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        data = response.json()
        self.assertEqual(len(data), 1)
        self.assertEqual(data[0]['address'], '123 Test Street')
    
    def test_organization_members_endpoint(self):
        """Test organization members endpoint"""
        # Create test membership
        membership = OrganizationUser.objects.create(
            organization=self.organization,
            user=self.user,
            role='member',
            status=1
        )
        
        response = self.client.get(f'/api/v1/organizations/{self.organization.id}/members/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        data = response.json()
        self.assertEqual(len(data), 1)
        self.assertEqual(data[0]['role'], 'member')
    
    def test_organization_search_and_filter(self):
        """Test organization search and filtering"""
        # Test search by name
        response = self.client.get('/api/v1/organizations/?search=Test')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertGreater(data['count'], 0)
        
        # Test filter by verification status
        response = self.client.get('/api/v1/organizations/?is_verified=true')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        for org in data['results']:
            self.assertTrue(org['is_verified'])
        
        # Test filter by country
        response = self.client.get(f'/api/v1/organizations/?country={self.country.id}')
        self.assertEqual(response.status_code, status.HTTP_200_OK)


class BlogPostAPITest(BaseAPITestCase):
    """Test blog post endpoints"""
    
    def test_blog_post_list_public_access(self):
        """Test that blog post list allows public access"""
        response = self.client.get('/api/v1/blog-posts/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Should only show published posts for non-admin users
        data = response.json()
        for post in data['results']:
            self.assertEqual(post['status'], 1)  # Published
    
    def test_blog_post_admin_sees_all_statuses(self):
        """Test that admin can see posts with all statuses"""
        # Create draft post
        draft_post = BlogPost.objects.create(
            title='Draft Post',
            slug='draft-post',
            content='This is a draft post.',
            status=2,  # Draft
            region=self.region
        )
        
        self.authenticate_admin()
        response = self.client.get('/api/v1/blog-posts/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Admin should see both published and draft posts
        data = response.json()
        statuses = [post['status'] for post in data['results']]
        self.assertIn(1, statuses)  # Published
        self.assertIn(2, statuses)  # Draft
    
    def test_blog_post_create_requires_admin(self):
        """Test that blog post creation requires admin permission"""
        data = {
            'title': 'New Blog Post',
            'slug': 'new-blog-post',
            'content': 'This is a new blog post.',
            'status': 1,
            'region': self.region.id
        }
        
        # Test without authentication
        response = self.client.post('/api/v1/blog-posts/', data)
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)
        
        # Test with admin authentication
        self.authenticate_admin()
        response = self.client.post('/api/v1/blog-posts/', data)
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
    
    def test_blog_post_publish_action(self):
        """Test blog post publish action"""
        # Create draft post
        draft_post = BlogPost.objects.create(
            title='Draft Post',
            slug='draft-post',
            content='This is a draft post.',
            status=2,  # Draft
            region=self.region
        )
        
        self.authenticate_admin()
        
        response = self.client.post(f'/api/v1/blog-posts/{draft_post.id}/publish/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify post was published
        draft_post.refresh_from_db()
        self.assertEqual(draft_post.status, 1)  # Published
    
    def test_blog_post_comments_endpoint(self):
        """Test blog post comments endpoint"""
        # Create test comment
        comment = BlogPostComment.objects.create(
            blog_post=self.blog_post,
            user=self.user,
            comment='This is a test comment.'
        )
        
        response = self.client.get(f'/api/v1/blog-posts/{self.blog_post.id}/comments/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        data = response.json()
        self.assertEqual(len(data), 1)
        self.assertEqual(data[0]['comment'], 'This is a test comment.')
    
    def test_blog_post_search_and_filter(self):
        """Test blog post search and filtering"""
        # Test search by title
        response = self.client.get('/api/v1/blog-posts/?search=Test')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertGreater(data['count'], 0)
        
        # Test filter by region
        response = self.client.get(f'/api/v1/blog-posts/?region={self.region.id}')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Test date range filtering
        today = datetime.now().strftime('%Y-%m-%d')
        response = self.client.get(f'/api/v1/blog-posts/?date_from={today}')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
    
    def test_blog_post_featured_endpoint(self):
        """Test featured blog posts endpoint"""
        response = self.client.get('/api/v1/blog-posts/featured/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIsInstance(data, list)
    
    def test_blog_post_unpublish_action(self):
        """Test blog post unpublish action"""
        self.authenticate_admin()
        
        response = self.client.post(f'/api/v1/blog-posts/{self.blog_post.id}/unpublish/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify post was unpublished
        self.blog_post.refresh_from_db()
        self.assertEqual(self.blog_post.status, 2)  # Draft


class NewsAPITest(BaseAPITestCase):
    """Test news endpoints"""
    
    def test_news_list_public_access(self):
        """Test that news list allows public access"""
        response = self.client.get('/api/v1/news/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Should only show published news for non-admin users
        data = response.json()
        for article in data['results']:
            self.assertEqual(article['status'], 1)  # Published
    
    def test_news_create_requires_admin(self):
        """Test that news creation requires admin permission"""
        data = {
            'title': 'New News Article',
            'slug': 'new-news-article',
            'content': 'This is a new news article.',
            'status': 1,
            'organization': self.organization.id,
            'region_id': self.region.id
        }
        
        # Test without authentication
        response = self.client.post('/api/v1/news/', data)
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)
        
        # Test with admin authentication
        self.authenticate_admin()
        response = self.client.post('/api/v1/news/', data)
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
    
    def test_news_publish_action(self):
        """Test news publish action"""
        # Create draft news
        draft_news = News.objects.create(
            title='Draft News',
            slug='draft-news',
            content='This is a draft news article.',
            status=2,  # Draft
            organization=self.organization,
            region_id=self.region.id
        )
        
        self.authenticate_admin()
        
        response = self.client.post(f'/api/v1/news/{draft_news.id}/publish/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify news was published
        draft_news.refresh_from_db()
        self.assertEqual(draft_news.status, 1)  # Published
    
    def test_news_filter_by_organization(self):
        """Test filtering news by organization"""
        response = self.client.get(f'/api/v1/news/?organization={self.organization.id}')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        data = response.json()
        for article in data['results']:
            self.assertEqual(article['organization'], self.organization.id)
    
    def test_news_latest_endpoint(self):
        """Test latest news endpoint"""
        response = self.client.get('/api/v1/news/latest/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIsInstance(data, list)
    
    def test_news_unpublish_action(self):
        """Test news unpublish action"""
        self.authenticate_admin()
        
        response = self.client.post(f'/api/v1/news/{self.news.id}/unpublish/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify news was unpublished
        self.news.refresh_from_db()
        self.assertEqual(self.news.status, 2)  # Draft


class EventAPITest(BaseAPITestCase):
    """Test event endpoints"""
    
    def test_event_list_public_access(self):
        """Test that event list allows public access"""
        response = self.client.get('/api/v1/events/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
    
    def test_event_create_requires_admin(self):
        """Test that event creation requires admin permission"""
        data = {
            'title': 'New Event',
            'slug': 'new-event',
            'content': 'This is a new event.',
            'status': 1,
            'organization': self.organization.id,
            'region_id': self.region.id
        }
        
        # Test without authentication
        response = self.client.post('/api/v1/events/', data)
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)
        
        # Test with admin authentication
        self.authenticate_admin()
        response = self.client.post('/api/v1/events/', data)
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
    
    def test_event_location_filtering(self):
        """Test event filtering by location (region)"""
        response = self.client.get(f'/api/v1/events/?region_id={self.region.id}')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        data = response.json()
        for event in data['results']:
            self.assertEqual(event['region_id'], self.region.id)
    
    def test_event_country_filtering(self):
        """Test event filtering by country"""
        response = self.client.get(f'/api/v1/events/?country={self.country.id}')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
    
    def test_event_upcoming_endpoint(self):
        """Test upcoming events endpoint"""
        response = self.client.get('/api/v1/events/upcoming/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIsInstance(data, list)
    
    def test_event_by_location_endpoint(self):
        """Test events by location endpoint"""
        response = self.client.get(f'/api/v1/events/by_location/?country={self.country.id}')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIsInstance(data, list)


class ResourceAPITest(BaseAPITestCase):
    """Test resource endpoints"""
    
    def test_resource_list_public_access(self):
        """Test that resource list allows public access"""
        response = self.client.get('/api/v1/resources/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
    
    def test_resource_create_requires_admin(self):
        """Test that resource creation requires admin permission"""
        data = {
            'title': 'New Resource',
            'slug': 'new-resource',
            'content': 'This is a new resource.',
            'status': 1,
            'organization': self.organization.id,
            'region_id': self.region.id
        }
        
        # Test without authentication
        response = self.client.post('/api/v1/resources/', data)
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)
        
        # Test with admin authentication
        self.authenticate_admin()
        response = self.client.post('/api/v1/resources/', data)
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
    
    def test_resource_category_filtering(self):
        """Test resource filtering by organization"""
        response = self.client.get(f'/api/v1/resources/?organization={self.organization.id}')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        data = response.json()
        for resource in data['results']:
            self.assertEqual(resource['organization'], self.organization.id)
    
    def test_resource_popular_endpoint(self):
        """Test popular resources endpoint"""
        response = self.client.get('/api/v1/resources/popular/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIsInstance(data, list)
    
    def test_resource_by_category_endpoint(self):
        """Test resources by category endpoint"""
        # Create a resource category first
        category = CategoryOfResource.objects.create(name='Test Category')
        
        response = self.client.get(f'/api/v1/resources/by_category/?category={category.id}')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIsInstance(data, list)


class ActivityLogAPITest(BaseAPITestCase):
    """Test activity log endpoints"""
    
    def test_activity_log_requires_admin(self):
        """Test that activity log access requires admin permission"""
        # Test without authentication
        response = self.client.get('/api/v1/activity-logs/')
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)
        
        # Test with admin authentication
        self.authenticate_admin()
        response = self.client.get('/api/v1/activity-logs/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
    
    def test_admin_activity_log_requires_super_admin(self):
        """Test that admin activity log requires super admin permission"""
        # Test with regular admin
        self.authenticate_admin()
        response = self.client.get('/api/v1/admin-activity-logs/')
        self.assertEqual(response.status_code, status.HTTP_403_FORBIDDEN)
        
        # Test with super admin
        self.authenticate_admin(self.super_admin)
        response = self.client.get('/api/v1/admin-activity-logs/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
    
    def test_activity_log_filtering(self):
        """Test activity log filtering"""
        # Create test activity log
        log = ActivityLog.objects.create(
            scope_model='User',
            scope_id='1',
            level='info',
            action='create',
            message='User created successfully'
        )
        
        self.authenticate_admin()
        
        # Test filter by level
        response = self.client.get('/api/v1/activity-logs/?level=info')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Test filter by action
        response = self.client.get('/api/v1/activity-logs/?action=create')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Test search
        response = self.client.get('/api/v1/activity-logs/?search=User')
        self.assertEqual(response.status_code, status.HTTP_200_OK)


class UserProfileAPITest(BaseAPITestCase):
    """Test user profile endpoints"""
    
    def test_user_profile_endpoint(self):
        """Test user profile endpoint"""
        self.authenticate_user()
        
        response = self.client.get('/api/v1/users/profile/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertEqual(data['email'], self.user.email)
    
    def test_user_profile_update(self):
        """Test user profile update"""
        self.authenticate_user()
        
        data = {
            'first_name': 'Updated John',
            'short_profile': 'Updated profile description'
        }
        
        response = self.client.patch('/api/v1/users/update_profile/', data)
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify user was updated
        self.user.refresh_from_db()
        self.assertEqual(self.user.first_name, 'Updated John')


class OrganizationMembershipAPITest(BaseAPITestCase):
    """Test organization membership endpoints"""
    
    def test_organization_membership_approval(self):
        """Test organization membership approval"""
        # Create test membership
        membership = OrganizationUser.objects.create(
            organization=self.organization,
            user=self.user,
            role='member',
            status=0  # Pending
        )
        
        self.authenticate_admin()
        
        response = self.client.post(f'/api/v1/organization-users/{membership.id}/approve/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify membership was approved
        membership.refresh_from_db()
        self.assertEqual(membership.status, 1)  # Approved
    
    def test_organization_membership_rejection(self):
        """Test organization membership rejection"""
        # Create test membership
        membership = OrganizationUser.objects.create(
            organization=self.organization,
            user=self.user,
            role='member',
            status=0  # Pending
        )
        
        self.authenticate_admin()
        
        response = self.client.post(f'/api/v1/organization-users/{membership.id}/reject/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify membership was rejected
        membership.refresh_from_db()
        self.assertEqual(membership.status, 2)  # Rejected
    
    def test_my_memberships_endpoint(self):
        """Test my memberships endpoint"""
        # Create test membership
        membership = OrganizationUser.objects.create(
            organization=self.organization,
            user=self.user,
            role='member',
            status=1
        )
        
        self.authenticate_user()
        
        response = self.client.get('/api/v1/organization-users/my_memberships/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertEqual(len(data), 1)
        self.assertEqual(data[0]['role'], 'member')


class CommentAPITest(BaseAPITestCase):
    """Test comment endpoints"""
    
    def test_blog_comment_create_requires_authentication(self):
        """Test that blog comment creation requires authentication"""
        data = {
            'blog_post': self.blog_post.id,
            'comment': 'This is a test comment.'
        }
        
        # Test without authentication
        response = self.client.post('/api/v1/blog-comments/', data)
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)
        
        # Test with authentication
        self.authenticate_user()
        response = self.client.post('/api/v1/blog-comments/', data)
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
    
    def test_news_comment_create(self):
        """Test news comment creation"""
        self.authenticate_user()
        
        data = {
            'news': self.news.id,
            'comment': 'This is a news comment.'
        }
        
        response = self.client.post('/api/v1/news-comments/', data)
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        
        # Verify comment was created with correct user
        comment = NewsComment.objects.get(id=response.json()['id'])
        self.assertEqual(comment.user, self.user)
    
    def test_event_comment_create(self):
        """Test event comment creation"""
        self.authenticate_user()
        
        data = {
            'event': self.event.id,
            'comment': 'This is an event comment.'
        }
        
        response = self.client.post('/api/v1/event-comments/', data)
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)


class PaginationTest(BaseAPITestCase):
    """Test pagination functionality"""
    
    def test_pagination_parameters(self):
        """Test pagination parameters"""
        self.authenticate_admin()
        
        # Test default pagination
        response = self.client.get('/api/v1/users/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIn('count', data)
        self.assertIn('next', data)
        self.assertIn('previous', data)
        self.assertIn('results', data)
        
        # Test custom page size
        response = self.client.get('/api/v1/users/?page_size=5')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertLessEqual(len(data['results']), 5)
    
    def test_pagination_navigation(self):
        """Test pagination navigation"""
        # Create multiple users for pagination testing
        for i in range(25):
            User.objects.create(
                first_name=f'User{i}',
                last_name='Test',
                email=f'user{i}@test.com',
                status=1,
                is_email_verified=True
            )
        
        self.authenticate_admin()
        
        # Test first page
        response = self.client.get('/api/v1/users/?page_size=10')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertEqual(len(data['results']), 10)
        self.assertIsNotNone(data['next'])
        self.assertIsNone(data['previous'])
        
        # Test second page
        response = self.client.get('/api/v1/users/?page=2&page_size=10')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIsNotNone(data['previous'])


class PermissionTest(BaseAPITestCase):
    """Test permission classes"""
    
    def test_super_admin_only_permission(self):
        """Test IsSuperAdminOnly permission"""
        endpoints = [
            '/api/v1/admins/',
            '/api/v1/admin-activity-logs/',
        ]
        
        for endpoint in endpoints:
            # Test without authentication
            response = self.client.get(endpoint)
            self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)
            
            # Test with regular admin
            self.authenticate_admin()
            response = self.client.get(endpoint)
            self.assertEqual(response.status_code, status.HTTP_403_FORBIDDEN)
            
            # Test with super admin
            self.authenticate_admin(self.super_admin)
            response = self.client.get(endpoint)
            self.assertEqual(response.status_code, status.HTTP_200_OK)
            
            self.clear_authentication()
    
    def test_admin_or_read_only_permission(self):
        """Test IsAdminOrReadOnly permission"""
        endpoints = [
            '/api/v1/users/',
            '/api/v1/organizations/',
            '/api/v1/blog-posts/',
            '/api/v1/news/',
           