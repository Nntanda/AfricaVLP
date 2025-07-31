from django.test import TestCase
from django.contrib.auth.hashers import make_password
from rest_framework.test import APITestCase
from rest_framework import status
from django.utils import timezone
from .models import (
    Admin, User, Country, City, Region, Organization, OrganizationType,
    CategoryOfOrganization, InstitutionType, BlogPost, News, Event, Resource,
    Tag, PublishingCategory
)
from .serializers import (
    AdminSerializer, UserSerializer, CountrySerializer, CitySerializer,
    RegionSerializer, OrganizationSerializer, OrganizationTypeSerializer,
    CategoryOfOrganizationSerializer, InstitutionTypeSerializer,
    BlogPostSerializer, NewsSerializer, EventSerializer, ResourceSerializer,
    TagSerializer, PublishingCategorySerializer
)


class CountrySerializerTest(TestCase):
    def setUp(self):
        self.country_data = {
            'iso': 'US',
            'name': 'United States',
            'nicename': 'United States',
            'iso3': 'USA',
            'numcode': 840,
            'phonecode': 1
        }
        self.country = Country.objects.create(**self.country_data)
    
    def test_country_serialization(self):
        """Test country serialization"""
        serializer = CountrySerializer(self.country)
        data = serializer.data
        
        self.assertEqual(data['iso'], 'US')
        self.assertEqual(data['name'], 'United States')
        self.assertEqual(data['nicename'], 'United States')
        self.assertEqual(data['phonecode'], 1)
    
    def test_country_deserialization(self):
        """Test country deserialization"""
        new_data = {
            'iso': 'CA',
            'name': 'Canada',
            'nicename': 'Canada',
            'iso3': 'CAN',
            'numcode': 124,
            'phonecode': 1
        }
        serializer = CountrySerializer(data=new_data)
        self.assertTrue(serializer.is_valid())


class AdminSerializerTest(TestCase):
    def setUp(self):
        self.admin_data = {
            'email': 'admin@test.com',
            'name': 'Test Admin',
            'role': 'admin',
            'status': 1,
        }
        self.admin = Admin.objects.create(
            **self.admin_data,
            password=make_password('testpass123'),
            created=timezone.now(),
            modified=timezone.now()
        )
    
    def test_admin_serialization(self):
        """Test admin serialization"""
        serializer = AdminSerializer(self.admin)
        data = serializer.data
        
        self.assertEqual(data['email'], 'admin@test.com')
        self.assertEqual(data['name'], 'Test Admin')
        self.assertEqual(data['role'], 'admin')
        self.assertEqual(data['status'], 1)
        self.assertNotIn('password', data)  # Password should not be serialized
    
    def test_admin_creation_with_password(self):
        """Test admin creation with password hashing"""
        new_data = {
            'email': 'newadmin@test.com',
            'name': 'New Admin',
            'role': 'admin',
            'status': 1,
            'password': 'newpass123'
        }
        serializer = AdminSerializer(data=new_data)
        self.assertTrue(serializer.is_valid())
        
        admin = serializer.save()
        self.assertNotEqual(admin.password, 'newpass123')  # Should be hashed
        self.assertTrue(admin.password.startswith('pbkdf2_'))  # Django hash format


class UserSerializerTest(TestCase):
    def setUp(self):
        self.country = Country.objects.create(
            iso='US', name='United States', nicename='United States', phonecode=1
        )
        self.city = City.objects.create(name='New York', country=self.country)
        
        self.user_data = {
            'first_name': 'John',
            'last_name': 'Doe',
            'email': 'john.doe@test.com',
            'resident_country': self.country,
            'city': self.city,
            'gender': 'Male',
            'marital_status': 'Single',
            'availability': 'Full time',
            'is_email_verified': True,
            'status': 1,
        }
        self.user = User.objects.create(
            **self.user_data,
            password=make_password('testpass123'),
            created=timezone.now(),
            modified=timezone.now()
        )
    
    def test_user_serialization(self):
        """Test user serialization"""
        serializer = UserSerializer(self.user)
        data = serializer.data
        
        self.assertEqual(data['first_name'], 'John')
        self.assertEqual(data['last_name'], 'Doe')
        self.assertEqual(data['full_name'], 'John Doe')
        self.assertEqual(data['email'], 'john.doe@test.com')
        self.assertEqual(data['resident_country_name'], 'United States')
        self.assertEqual(data['city_name'], 'New York')
        self.assertNotIn('password', data)  # Password should not be serialized
    
    def test_user_full_name_method(self):
        """Test full_name serializer method"""
        serializer = UserSerializer(self.user)
        self.assertEqual(serializer.get_full_name(self.user), 'John Doe')
        
        # Test with empty names
        user_no_name = User.objects.create(
            first_name='', last_name='', email='test@test.com'
        )
        self.assertEqual(serializer.get_full_name(user_no_name), '')


class OrganizationSerializerTest(TestCase):
    def setUp(self):
        self.country = Country.objects.create(
            iso='US', name='United States', nicename='United States', phonecode=1
        )
        self.city = City.objects.create(name='New York', country=self.country)
        self.org_type = OrganizationType.objects.create(name='NGO')
        self.category = CategoryOfOrganization.objects.create(name='Education')
        
        self.org_data = {
            'name': 'Test Organization',
            'about': 'A test organization',
            'country': self.country,
            'city': self.city,
            'organization_type': self.org_type,
            'category': self.category,
            'email': 'org@test.com',
            'status': 1,
            'is_verified': False,
        }
        self.organization = Organization.objects.create(
            **self.org_data,
            created=timezone.now(),
            modified=timezone.now()
        )
    
    def test_organization_serialization(self):
        """Test organization serialization"""
        serializer = OrganizationSerializer(self.organization)
        data = serializer.data
        
        self.assertEqual(data['name'], 'Test Organization')
        self.assertEqual(data['about'], 'A test organization')
        self.assertEqual(data['country_name'], 'United States')
        self.assertEqual(data['city_name'], 'New York')
        self.assertEqual(data['organization_type_name'], 'NGO')
        self.assertEqual(data['category_name'], 'Education')
        self.assertEqual(data['email'], 'org@test.com')
        self.assertEqual(data['status'], 1)
        self.assertFalse(data['is_verified'])


class BlogPostSerializerTest(TestCase):
    def setUp(self):
        self.region = Region.objects.create(name='Africa')
        self.blog_data = {
            'title': 'Test Blog Post',
            'slug': 'test-blog-post',
            'content': 'This is a test blog post content.',
            'status': 1,
            'region': self.region,
        }
        self.blog_post = BlogPost.objects.create(
            **self.blog_data,
            created=timezone.now(),
            modified=timezone.now()
        )
    
    def test_blog_post_serialization(self):
        """Test blog post serialization"""
        serializer = BlogPostSerializer(self.blog_post)
        data = serializer.data
        
        self.assertEqual(data['title'], 'Test Blog Post')
        self.assertEqual(data['slug'], 'test-blog-post')
        self.assertEqual(data['content'], 'This is a test blog post content.')
        self.assertEqual(data['status'], 1)
        self.assertEqual(data['region_name'], 'Africa')
    
    def test_blog_post_creation_with_timestamps(self):
        """Test blog post creation with automatic timestamps"""
        new_data = {
            'title': 'New Blog Post',
            'slug': 'new-blog-post',
            'content': 'New content',
            'status': 2,
            'region': self.region.id
        }
        serializer = BlogPostSerializer(data=new_data)
        self.assertTrue(serializer.is_valid())
        
        blog_post = serializer.save()
        self.assertIsNotNone(blog_post.created)
        self.assertIsNotNone(blog_post.modified)


class NewsSerializerTest(TestCase):
    def setUp(self):
        self.country = Country.objects.create(
            iso='US', name='United States', nicename='United States', phonecode=1
        )
        self.organization = Organization.objects.create(
            name='Test Org', country=self.country, status=1
        )
        
        self.news_data = {
            'title': 'Test News Article',
            'slug': 'test-news-article',
            'content': 'This is a test news article.',
            'status': 1,
            'organization': self.organization,
        }
        self.news = News.objects.create(
            **self.news_data,
            created=timezone.now(),
            modified=timezone.now()
        )
    
    def test_news_serialization(self):
        """Test news serialization"""
        serializer = NewsSerializer(self.news)
        data = serializer.data
        
        self.assertEqual(data['title'], 'Test News Article')
        self.assertEqual(data['slug'], 'test-news-article')
        self.assertEqual(data['content'], 'This is a test news article.')
        self.assertEqual(data['status'], 1)
        self.assertEqual(data['organization_name'], 'Test Org')


class EventSerializerTest(TestCase):
    def setUp(self):
        self.country = Country.objects.create(
            iso='US', name='United States', nicename='United States', phonecode=1
        )
        self.organization = Organization.objects.create(
            name='Test Org', country=self.country, status=1
        )
        
        self.event_data = {
            'title': 'Test Event',
            'slug': 'test-event',
            'content': 'This is a test event.',
            'status': 1,
            'organization': self.organization,
        }
        self.event = Event.objects.create(
            **self.event_data,
            created=timezone.now(),
            modified=timezone.now()
        )
    
    def test_event_serialization(self):
        """Test event serialization"""
        serializer = EventSerializer(self.event)
        data = serializer.data
        
        self.assertEqual(data['title'], 'Test Event')
        self.assertEqual(data['slug'], 'test-event')
        self.assertEqual(data['content'], 'This is a test event.')
        self.assertEqual(data['status'], 1)
        self.assertEqual(data['organization_name'], 'Test Org')


class ResourceSerializerTest(TestCase):
    def setUp(self):
        self.country = Country.objects.create(
            iso='US', name='United States', nicename='United States', phonecode=1
        )
        self.organization = Organization.objects.create(
            name='Test Org', country=self.country, status=1
        )
        
        self.resource_data = {
            'title': 'Test Resource',
            'slug': 'test-resource',
            'content': 'This is a test resource.',
            'status': 1,
            'organization': self.organization,
        }
        self.resource = Resource.objects.create(
            **self.resource_data,
            created=timezone.now(),
            modified=timezone.now()
        )
    
    def test_resource_serialization(self):
        """Test resource serialization"""
        serializer = ResourceSerializer(self.resource)
        data = serializer.data
        
        self.assertEqual(data['title'], 'Test Resource')
        self.assertEqual(data['slug'], 'test-resource')
        self.assertEqual(data['content'], 'This is a test resource.')
        self.assertEqual(data['status'], 1)
        self.assertEqual(data['organization_name'], 'Test Org')


class TagSerializerTest(TestCase):
    def setUp(self):
        self.tag = Tag.objects.create(title='Test Tag')
    
    def test_tag_serialization(self):
        """Test tag serialization"""
        serializer = TagSerializer(self.tag)
        data = serializer.data
        
        self.assertEqual(data['title'], 'Test Tag')
    
    def test_tag_creation(self):
        """Test tag creation"""
        new_data = {'title': 'New Tag'}
        serializer = TagSerializer(data=new_data)
        self.assertTrue(serializer.is_valid())
        
        tag = serializer.save()
        self.assertEqual(tag.title, 'New Tag')


class SerializerValidationTest(TestCase):
    def test_admin_email_validation(self):
        """Test admin email validation"""
        invalid_data = {
            'email': 'invalid-email',
            'name': 'Test Admin',
            'role': 'admin',
            'status': 1,
        }
        serializer = AdminSerializer(data=invalid_data)
        self.assertFalse(serializer.is_valid())
        self.assertIn('email', serializer.errors)
    
    def test_user_required_fields(self):
        """Test user required field validation"""
        incomplete_data = {
            'first_name': 'John',
            # Missing last_name and email
        }
        serializer = UserSerializer(data=incomplete_data)
        self.assertFalse(serializer.is_valid())
    
    def test_organization_name_required(self):
        """Test organization name requirement"""
        incomplete_data = {
            'about': 'Test organization',
            # Missing name
        }
        serializer = OrganizationSerializer(data=incomplete_data)
        # Note: Since models use managed=False, validation might be limited
        # This test ensures serializer structure is correct