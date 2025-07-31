"""
Tests for search functionality
"""
from django.test import TestCase
from django.contrib.auth import get_user_model
from rest_framework.test import APIClient
from rest_framework import status
from django.urls import reverse

from .models import BlogPost, News, Event, Organization, Country, City
from .search import BlogPostSearch, NewsSearch, EventSearch, OrganizationSearch

User = get_user_model()


class SearchTestCase(TestCase):
    def setUp(self):
        """Set up test data"""
        self.client = APIClient()
        
        # Create test user
        self.user = User.objects.create_user(
            username='testuser',
            email='test@example.com',
            password='testpass123'
        )
        self.client.force_authenticate(user=self.user)
        
        # Create test data
        self.country = Country.objects.create(name='Test Country')
        self.city = City.objects.create(name='Test City', country=self.country)
        
        self.organization = Organization.objects.create(
            name='Test Organization',
            city=self.city
        )
        
        # Create blog posts
        self.blog_post1 = BlogPost.objects.create(
            title='Django REST Framework Tutorial',
            content='Learn how to build APIs with Django REST Framework',
            organization=self.organization,
            status='published'
        )
        
        self.blog_post2 = BlogPost.objects.create(
            title='React Frontend Development',
            content='Building modern frontends with React and TypeScript',
            organization=self.organization,
            status='published'
        )
        
        # Create news articles
        self.news1 = News.objects.create(
            title='Tech Conference 2024',
            content='Annual technology conference featuring Django and React',
            organization=self.organization,
            status='published'
        )


class BlogPostSearchTest(SearchTestCase):
    def test_search_by_title(self):
        """Test searching blog posts by title"""
        results = BlogPostSearch.search('Django')
        self.assertEqual(len(results), 1)
        self.assertEqual(results[0].title, 'Django REST Framework Tutorial')
    
    def test_search_by_content(self):
        """Test searching blog posts by content"""
        results = BlogPostSearch.search('APIs')
        self.assertEqual(len(results), 1)
        self.assertEqual(results[0].title, 'Django REST Framework Tutorial')
    
    def test_search_with_filters(self):
        """Test searching with status filter"""
        filters = {'status': 'published'}
        results = BlogPostSearch.search('', filters)
        self.assertEqual(len(results), 2)
    
    def test_empty_search(self):
        """Test empty search returns all results"""
        results = BlogPostSearch.search('')
        self.assertEqual(len(results), 2)
    
    def test_no_results(self):
        """Test search with no matching results"""
        results = BlogPostSearch.search('nonexistent')
        self.assertEqual(len(results), 0)


class NewsSearchTest(SearchTestCase):
    def test_search_news(self):
        """Test searching news articles"""
        results = NewsSearch.search('Conference')
        self.assertEqual(len(results), 1)
        self.assertEqual(results[0].title, 'Tech Conference 2024')


class SearchAPITest(SearchTestCase):
    def test_search_blog_posts_api(self):
        """Test blog posts search API endpoint"""
        url = reverse('search-blog-posts')
        response = self.client.get(url, {'q': 'Django'})
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIn('results', data)
        self.assertIn('pagination', data)
        self.assertEqual(len(data['results']), 1)
    
    def test_search_with_filters_api(self):
        """Test search API with filters"""
        url = reverse('search-blog-posts')
        response = self.client.get(url, {
            'q': '',
            'status': 'published'
        })
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertEqual(len(data['results']), 2)
    
    def test_universal_search_api(self):
        """Test universal search API"""
        url = reverse('universal-search')
        response = self.client.get(url, {'q': 'Django'})
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIn('results', data)
        self.assertIn('blog_posts', data['results'])
    
    def test_search_suggestions_api(self):
        """Test search suggestions API"""
        url = reverse('search-suggestions')
        response = self.client.get(url, {'q': 'Dj', 'type': 'blog_posts'})
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIn('suggestions', data)
    
    def test_filter_metadata_api(self):
        """Test filter metadata API"""
        url = reverse('search-filters-metadata')
        response = self.client.get(url)
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        data = response.json()
        self.assertIn('countries', data)
        self.assertIn('cities', data)
    
    def test_unauthorized_access(self):
        """Test that search requires authentication"""
        self.client.force_authenticate(user=None)
        url = reverse('search-blog-posts')
        response = self.client.get(url, {'q': 'Django'})
        
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)


class SearchUtilsTest(TestCase):
    def test_clean_search_query(self):
        """Test search query cleaning"""
        from .search import SearchManager
        
        # Test normal query
        cleaned = SearchManager.clean_search_query('hello world')
        self.assertEqual(cleaned, 'hello world')
        
        # Test query with special characters
        cleaned = SearchManager.clean_search_query('hello@#$%world!')
        self.assertEqual(cleaned, 'hello world')
        
        # Test empty query
        cleaned = SearchManager.clean_search_query('')
        self.assertEqual(cleaned, '')
        
        # Test whitespace normalization
        cleaned = SearchManager.clean_search_query('  hello   world  ')
        self.assertEqual(cleaned, 'hello world')