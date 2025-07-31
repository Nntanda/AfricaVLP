#!/usr/bin/env python
"""
Simple test to verify API endpoints are working
"""
import os
import sys
import django
from django.test import TestCase
from django.test.client import Client
from django.urls import reverse

# Setup Django
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'admin_backend.settings')
django.setup()

def test_endpoints():
    """Test basic endpoints"""
    from django.test import Client
    
    client = Client()
    
    print("Testing basic endpoints...")
    
    # Test API root
    response = client.get('/api/v1/')
    print(f"API Root: {response.status_code}")
    
    # Test countries endpoint
    response = client.get('/api/v1/countries/')
    print(f"Countries: {response.status_code}")
    
    # Test blog posts endpoint
    response = client.get('/api/v1/blog-posts/')
    print(f"Blog posts: {response.status_code}")
    
    # Test news endpoint
    response = client.get('/api/v1/news/')
    print(f"News: {response.status_code}")
    
    # Test events endpoint
    response = client.get('/api/v1/events/')
    print(f"Events: {response.status_code}")
    
    # Test resources endpoint
    response = client.get('/api/v1/resources/')
    print(f"Resources: {response.status_code}")
    
    # Test organizations endpoint
    response = client.get('/api/v1/organizations/')
    print(f"Organizations: {response.status_code}")
    
    # Test admin endpoint (should require auth)
    response = client.get('/api/v1/admins/')
    print(f"Admins (no auth): {response.status_code} (should be 401)")
    
    print("Basic endpoint tests completed!")

if __name__ == '__main__':
    test_endpoints()