from django.test import TestCase, Client
from django.urls import reverse
from django.contrib.auth.hashers import make_password
from rest_framework.test import APITestCase
from rest_framework import status
from rest_framework_simplejwt.tokens import RefreshToken
from models_app.models import Admin, User
from .backends import AdminAuthenticationBackend, UserAuthenticationBackend
from .serializers import AdminTokenObtainPairSerializer, UserTokenObtainPairSerializer
import json


class AuthenticationBackendTest(TestCase):
    def setUp(self):
        self.admin_backend = AdminAuthenticationBackend()
        self.user_backend = UserAuthenticationBackend()
        
        # Create test admin
        self.admin = Admin.objects.create(
            email='admin@test.com',
            name='Test Admin',
            password=make_password('testpass123'),
            role='admin',
            status=1
        )
        
        # Create test user
        self.user = User.objects.create(
            first_name='John',
            last_name='Doe',
            email='user@test.com',
            password=make_password('testpass123'),
            status=1,
            is_email_verified=True
        )
    
    def test_admin_authentication_success(self):
        """Test successful admin authentication"""
        user = self.admin_backend.authenticate(
            None, 
            email='admin@test.com', 
            password='testpass123'
        )
        self.assertEqual(user, self.admin)
    
    def test_admin_authentication_failure(self):
        """Test failed admin authentication"""
        user = self.admin_backend.authenticate(
            None, 
            email='admin@test.com', 
            password='wrongpass'
        )
        self.assertIsNone(user)
    
    def test_user_authentication_success(self):
        """Test successful user authentication"""
        user = self.user_backend.authenticate(
            None, 
            email='user@test.com', 
            password='testpass123',
            user_type='user'
        )
        self.assertEqual(user, self.user)
    
    def test_user_authentication_failure(self):
        """Test failed user authentication"""
        user = self.user_backend.authenticate(
            None, 
            email='user@test.com', 
            password='wrongpass',
            user_type='user'
        )
        self.assertIsNone(user)
    
    def test_get_user_admin(self):
        """Test getting admin user by ID"""
        user = self.admin_backend.get_user(self.admin.id)
        self.assertEqual(user, self.admin)
    
    def test_get_user_regular(self):
        """Test getting regular user by ID"""
        user = self.user_backend.get_user(self.user.id)
        self.assertEqual(user, self.user)


class AuthenticationAPITest(APITestCase):
    def setUp(self):
        self.client = Client()
        
        # Create test admin
        self.admin = Admin.objects.create(
            email='admin@test.com',
            name='Test Admin',
            password=make_password('testpass123'),
            role='admin',
            status=1
        )
        
        # Create test user
        self.user = User.objects.create(
            first_name='John',
            last_name='Doe',
            email='user@test.com',
            password=make_password('testpass123'),
            status=1,
            is_email_verified=True
        )
    
    def test_admin_login_success(self):
        """Test successful admin login"""
        url = reverse('admin_login')
        data = {
            'email': 'admin@test.com',
            'password': 'testpass123'
        }
        response = self.client.post(url, data, content_type='application/json')
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        response_data = json.loads(response.content)
        self.assertIn('access', response_data)
        self.assertIn('refresh', response_data)
        self.assertIn('user', response_data)
    
    def test_admin_login_failure(self):
        """Test failed admin login"""
        url = reverse('admin_login')
        data = {
            'email': 'admin@test.com',
            'password': 'wrongpass'
        }
        response = self.client.post(url, data, content_type='application/json')
        
        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)
    
    def test_user_login_success(self):
        """Test successful user login"""
        url = reverse('user_login')
        data = {
            'email': 'user@test.com',
            'password': 'testpass123'
        }
        response = self.client.post(url, data, content_type='application/json')
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        response_data = json.loads(response.content)
        self.assertIn('access', response_data)
        self.assertIn('refresh', response_data)
        self.assertIn('user', response_data)
    
    def test_user_login_failure(self):
        """Test failed user login"""
        url = reverse('user_login')
        data = {
            'email': 'user@test.com',
            'password': 'wrongpass'
        }
        response = self.client.post(url, data, content_type='application/json')
        
        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)
    
    def test_profile_access_with_token(self):
        """Test accessing profile with valid token"""
        # Get token for admin
        refresh = RefreshToken.for_user(self.admin)
        access_token = str(refresh.access_token)
        
        url = reverse('profile')
        headers = {'HTTP_AUTHORIZATION': f'Bearer {access_token}'}
        response = self.client.get(url, **headers)
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        response_data = json.loads(response.content)
        self.assertEqual(response_data['email'], 'admin@test.com')
    
    def test_profile_access_without_token(self):
        """Test accessing profile without token"""
        url = reverse('profile')
        response = self.client.get(url)
        
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)
    
    def test_token_verification(self):
        """Test token verification endpoint"""
        # Get token for user
        refresh = RefreshToken.for_user(self.user)
        access_token = str(refresh.access_token)
        
        url = reverse('verify_token')
        headers = {'HTTP_AUTHORIZATION': f'Bearer {access_token}'}
        response = self.client.get(url, **headers)
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        response_data = json.loads(response.content)
        self.assertTrue(response_data['valid'])
        self.assertIn('user', response_data)
    
    def test_logout(self):
        """Test logout functionality"""
        # Get tokens for user
        refresh = RefreshToken.for_user(self.user)
        access_token = str(refresh.access_token)
        refresh_token = str(refresh)
        
        url = reverse('logout')
        headers = {'HTTP_AUTHORIZATION': f'Bearer {access_token}'}
        data = {'refresh_token': refresh_token}
        response = self.client.post(url, data, **headers)
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
    
    def test_password_reset_request(self):
        """Test password reset request"""
        url = reverse('password_reset')
        data = {'email': 'admin@test.com'}
        response = self.client.post(url, data, content_type='application/json')
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        response_data = json.loads(response.content)
        self.assertIn('message', response_data)


class TokenSerializerTest(TestCase):
    def setUp(self):
        self.admin = Admin.objects.create(
            email='admin@test.com',
            name='Test Admin',
            password=make_password('testpass123'),
            role='admin',
            status=1
        )
        
        self.user = User.objects.create(
            first_name='John',
            last_name='Doe',
            email='user@test.com',
            password=make_password('testpass123'),
            status=1,
            is_email_verified=True
        )
    
    def test_admin_token_serializer(self):
        """Test admin token serializer"""
        serializer = AdminTokenObtainPairSerializer()
        token = serializer.get_token(self.admin)
        
        self.assertEqual(token['user_id'], self.admin.id)
        self.assertEqual(token['email'], self.admin.email)
        self.assertEqual(token['name'], self.admin.name)
        self.assertEqual(token['role'], self.admin.role)
        self.assertEqual(token['user_type'], 'admin')
    
    def test_user_token_serializer(self):
        """Test user token serializer"""
        serializer = UserTokenObtainPairSerializer()
        token = serializer.get_token(self.user)
        
        self.assertEqual(token['user_id'], self.user.id)
        self.assertEqual(token['email'], self.user.email)
        self.assertEqual(token['first_name'], self.user.first_name)
        self.assertEqual(token['last_name'], self.user.last_name)
        self.assertEqual(token['user_type'], 'user')


class PasswordManagementTest(TestCase):
    def setUp(self):
        self.client = Client()
        self.admin = Admin.objects.create(
            email='admin@test.com',
            name='Test Admin',
            password=make_password('oldpass123'),
            role='admin',
            status=1
        )
    
    def test_change_password(self):
        """Test password change functionality"""
        # Get token for admin
        refresh = RefreshToken.for_user(self.admin)
        access_token = str(refresh.access_token)
        
        url = reverse('change_password')
        headers = {'HTTP_AUTHORIZATION': f'Bearer {access_token}'}
        data = {
            'old_password': 'oldpass123',
            'new_password': 'newpass123',
            'new_password_confirm': 'newpass123'
        }
        response = self.client.post(url, data, **headers)
        
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        
        # Verify password was changed
        self.admin.refresh_from_db()
        from django.contrib.auth.hashers import check_password
        self.assertTrue(check_password('newpass123', self.admin.password))
    
    def test_change_password_wrong_old_password(self):
        """Test password change with wrong old password"""
        # Get token for admin
        refresh = RefreshToken.for_user(self.admin)
        access_token = str(refresh.access_token)
        
        url = reverse('change_password')
        headers = {'HTTP_AUTHORIZATION': f'Bearer {access_token}'}
        data = {
            'old_password': 'wrongpass',
            'new_password': 'newpass123',
            'new_password_confirm': 'newpass123'
        }
        response = self.client.post(url, data, **headers)
        
        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)