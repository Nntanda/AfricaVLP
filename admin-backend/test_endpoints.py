#!/usr/bin/env python
"""
Simple script to test API endpoints
"""
import os
import sys
import django
from django.conf import settings

# Setup Django
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'admin_backend.settings')
django.setup()

from rest_framework.test import APIClient
from rest_framework import status
from django.contrib.auth.hashers import make_password
from models_app.models import Admin, User, Country, City, Region, Organization

def test_api_endpoints():
    """Test basic API endpoints"""
    client = APIClient()
    
    print("Testing API endpoints...")
    
    # Test API root
    response = client.get('/api/v1/')
    print(f"API Root: {response.status_code}")
    
    # Test countries endpoint (should be accessible without auth)
    response = client.get('/api/v1/countries/')
    print(f"Countries endpoint: {response.status_code}")
    
    # Test blog posts endpoint (should be accessible without auth for published posts)
    response = client.get('/api/v1/blog-posts/')
    print(f"Blog posts endpoint: {response.status_code}")
    
    # Test admin endpoint (should require authentication)
    response = client.get('/api/v1/admins/')
    print(f"Admin endpoint (no auth): {response.status_code} (should be 401)")
    
    # Test news endpoint
    response = client.get('/api/v1/news/')
    print(f"News endpoint: {response.status_code}")
    
    # Test events endpoint
    response = client.get('/api/v1/events/')
    print(f"Events endpoint: {response.status_code}")
    
    # Test resources endpoint
    response = client.get('/api/v1/resources/')
    print(f"Resources endpoint: {response.status_code}")
    
    # Test organizations endpoint
    response = client.get('/api/v1/organizations/')
    print(f"Organizations endpoint: {response.status_code}")
    
    print("Basic endpoint tests completed!")

if __name__ == '__main__':
    test_api_endpoints()