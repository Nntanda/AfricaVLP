"""
Search API views for the admin backend.
Provides REST API endpoints for search and filtering functionality.
"""

from rest_framework import status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response
from rest_framework.pagination import PageNumberPagination
from django.utils.decorators import method_decorator
from django.views.decorators.cache import cache_page
from django.core.cache import cache
from typing import Dict, Any, List

from .search import SearchManager, SearchHistoryManager
from .serializers import (
    BlogPostSerializer, NewsSerializer, EventSerializer,
    OrganizationSerializer, ResourceSerializer
)
from .models import BlogCategory, NewsCategory, Tag, Country, City


class SearchPagination(PageNumberPagination):
    """Custom pagination for search results."""
    page_size = 20
    page_size_query_param = 'page_size'
    max_page_size = 100


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def search_blog_posts(request):
    """
    Search blog posts with advanced filtering.
    
    Query Parameters:
    - q: Search query
    - categories: Comma-separated category IDs
    - tags: Comma-separated tag IDs
    - organization_id: Organization ID
    - date_from: Start date (YYYY-MM-DD)
    - date_to: End date (YYYY-MM-DD)
    - status: Post status
    - language: Content language
    - page: Page number
    - page_size: Results per page
    """
    query = request.GET.get('q', '')
    categories = request.GET.get('categories', '')
    tags = request.GET.get('tags', '')
    organization_id = request.GET.get('organization_id')
    date_from = request.GET.get('date_from')
    date_to = request.GET.get('date_to')
    status_filter = request.GET.get('status')
    language = request.GET.get('language')
    
    # Parse comma-separated IDs
    category_ids = [int(x) for x in categories.split(',') if x.strip().isdigit()]
    tag_ids = [int(x) for x in tags.split(',') if x.strip().isdigit()]
    
    # Perform search
    results = SearchManager.search_blog_posts(
        query=query,
        categories=category_ids if category_ids else None,
        tags=tag_ids if tag_ids else None,
        organization_id=int(organization_id) if organization_id else None,
        date_from=date_from,
        date_to=date_to,
        status=status_filter,
        language=language
    )
    
    # Paginate results
    paginator = SearchPagination()
    paginated_results = paginator.paginate_queryset(results, request)
    
    # Serialize results
    serializer = BlogPostSerializer(paginated_results, many=True, context={'request': request})
    
    # Highlight search terms in results
    if query:
        for item in serializer.data:
            item['title'] = SearchManager.highlight_search_results(item['title'], query)
            item['excerpt'] = SearchManager.highlight_search_results(item.get('excerpt', ''), query)
    
    # Save search history
    if request.user.is_authenticated:
        SearchHistoryManager.save_search_query(
            user_id=request.user.id,
            query=query,
            results_count=results.count(),
            filters={
                'categories': category_ids,
                'tags': tag_ids,
                'organization_id': organization_id,
                'date_from': date_from,
                'date_to': date_to,
                'status': status_filter,
                'language': language
            }
        )
    
    return paginator.get_paginated_response(serializer.data)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def search_news(request):
    """
    Search news articles with advanced filtering.
    """
    query = request.GET.get('q', '')
    categories = request.GET.get('categories', '')
    tags = request.GET.get('tags', '')
    organization_id = request.GET.get('organization_id')
    date_from = request.GET.get('date_from')
    date_to = request.GET.get('date_to')
    status_filter = request.GET.get('status')
    
    # Parse comma-separated IDs
    category_ids = [int(x) for x in categories.split(',') if x.strip().isdigit()]
    tag_ids = [int(x) for x in tags.split(',') if x.strip().isdigit()]
    
    # Perform search
    results = SearchManager.search_news(
        query=query,
        categories=category_ids if category_ids else None,
        tags=tag_ids if tag_ids else None,
        organization_id=int(organization_id) if organization_id else None,
        date_from=date_from,
        date_to=date_to,
        status=status_filter
    )
    
    # Paginate results
    paginator = SearchPagination()
    paginated_results = paginator.paginate_queryset(results, request)
    
    # Serialize results
    serializer = NewsSerializer(paginated_results, many=True, context={'request': request})
    
    # Highlight search terms
    if query:
        for item in serializer.data:
            item['title'] = SearchManager.highlight_search_results(item['title'], query)
            item['summary'] = SearchManager.highlight_search_results(item.get('summary', ''), query)
    
    # Save search history
    if request.user.is_authenticated:
        SearchHistoryManager.save_search_query(
            user_id=request.user.id,
            query=query,
            results_count=results.count(),
            filters={
                'categories': category_ids,
                'tags': tag_ids,
                'organization_id': organization_id,
                'date_from': date_from,
                'date_to': date_to,
                'status': status_filter
            }
        )
    
    return paginator.get_paginated_response(serializer.data)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def search_events(request):
    """
    Search events with location-based filtering.
    """
    query = request.GET.get('q', '')
    organization_id = request.GET.get('organization_id')
    country_id = request.GET.get('country_id')
    city_id = request.GET.get('city_id')
    date_from = request.GET.get('date_from')
    date_to = request.GET.get('date_to')
    event_type = request.GET.get('event_type')
    status_filter = request.GET.get('status')
    
    # Perform search
    results = SearchManager.search_events(
        query=query,
        organization_id=int(organization_id) if organization_id else None,
        country_id=int(country_id) if country_id else None,
        city_id=int(city_id) if city_id else None,
        date_from=date_from,
        date_to=date_to,
        event_type=event_type,
        status=status_filter
    )
    
    # Paginate results
    paginator = SearchPagination()
    paginated_results = paginator.paginate_queryset(results, request)
    
    # Serialize results
    serializer = EventSerializer(paginated_results, many=True, context={'request': request})
    
    # Highlight search terms
    if query:
        for item in serializer.data:
            item['title'] = SearchManager.highlight_search_results(item['title'], query)
            item['description'] = SearchManager.highlight_search_results(item.get('description', ''), query)
    
    # Save search history
    if request.user.is_authenticated:
        SearchHistoryManager.save_search_query(
            user_id=request.user.id,
            query=query,
            results_count=results.count(),
            filters={
                'organization_id': organization_id,
                'country_id': country_id,
                'city_id': city_id,
                'date_from': date_from,
                'date_to': date_to,
                'event_type': event_type,
                'status': status_filter
            }
        )
    
    return paginator.get_paginated_response(serializer.data)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def search_organizations(request):
    """
    Search organizations with location-based filtering.
    """
    query = request.GET.get('q', '')
    country_id = request.GET.get('country_id')
    city_id = request.GET.get('city_id')
    organization_type = request.GET.get('organization_type')
    status_filter = request.GET.get('status')
    
    # Perform search
    results = SearchManager.search_organizations(
        query=query,
        country_id=int(country_id) if country_id else None,
        city_id=int(city_id) if city_id else None,
        organization_type=organization_type,
        status=status_filter
    )
    
    # Paginate results
    paginator = SearchPagination()
    paginated_results = paginator.paginate_queryset(results, request)
    
    # Serialize results
    serializer = OrganizationSerializer(paginated_results, many=True, context={'request': request})
    
    # Highlight search terms
    if query:
        for item in serializer.data:
            item['name'] = SearchManager.highlight_search_results(item['name'], query)
            item['description'] = SearchManager.highlight_search_results(item.get('description', ''), query)
    
    # Save search history
    if request.user.is_authenticated:
        SearchHistoryManager.save_search_query(
            user_id=request.user.id,
            query=query,
            results_count=results.count(),
            filters={
                'country_id': country_id,
                'city_id': city_id,
                'organization_type': organization_type,
                'status': status_filter
            }
        )
    
    return paginator.get_paginated_response(serializer.data)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def search_resources(request):
    """
    Search resources with category filtering.
    """
    query = request.GET.get('q', '')
    categories = request.GET.get('categories', '')
    organization_id = request.GET.get('organization_id')
    resource_type = request.GET.get('resource_type')
    language = request.GET.get('language')
    
    # Parse comma-separated IDs
    category_ids = [int(x) for x in categories.split(',') if x.strip().isdigit()]
    
    # Perform search
    results = SearchManager.search_resources(
        query=query,
        categories=category_ids if category_ids else None,
        organization_id=int(organization_id) if organization_id else None,
        resource_type=resource_type,
        language=language
    )
    
    # Paginate results
    paginator = SearchPagination()
    paginated_results = paginator.paginate_queryset(results, request)
    
    # Serialize results
    serializer = ResourceSerializer(paginated_results, many=True, context={'request': request})
    
    # Highlight search terms
    if query:
        for item in serializer.data:
            item['title'] = SearchManager.highlight_search_results(item['title'], query)
            item['description'] = SearchManager.highlight_search_results(item.get('description', ''), query)
    
    # Save search history
    if request.user.is_authenticated:
        SearchHistoryManager.save_search_query(
            user_id=request.user.id,
            query=query,
            results_count=results.count(),
            filters={
                'categories': category_ids,
                'organization_id': organization_id,
                'resource_type': resource_type,
                'language': language
            }
        )
    
    return paginator.get_paginated_response(serializer.data)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
@method_decorator(cache_page(60 * 5))  # Cache for 5 minutes
def search_suggestions(request):
    """
    Get search suggestions based on query.
    """
    query = request.GET.get('q', '')
    limit = int(request.GET.get('limit', 10))
    
    if len(query) < 2:
        return Response({'suggestions': {}})
    
    # Check cache first
    cache_key = f'search_suggestions_{query}_{limit}'
    cached_suggestions = cache.get(cache_key)
    
    if cached_suggestions:
        return Response({'suggestions': cached_suggestions})
    
    # Get suggestions
    suggestions = SearchManager.get_search_suggestions(query, limit)
    
    # Cache results for 5 minutes
    cache.set(cache_key, suggestions, 300)
    
    return Response({'suggestions': suggestions})


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def search_history(request):
    """
    Get user's search history.
    """
    limit = int(request.GET.get('limit', 20))
    
    history = SearchHistoryManager.get_user_search_history(
        user_id=request.user.id,
        limit=limit
    )
    
    history_data = []
    for item in history:
        history_data.append({
            'query': item.query,
            'results_count': item.results_count,
            'filters': item.filters,
            'created_at': item.created_at.isoformat()
        })
    
    return Response({'history': history_data})


@api_view(['GET'])
@permission_classes([IsAuthenticated])
@method_decorator(cache_page(60 * 30))  # Cache for 30 minutes
def popular_searches(request):
    """
    Get popular search queries.
    """
    limit = int(request.GET.get('limit', 10))
    
    popular = SearchHistoryManager.get_popular_searches(limit)
    
    return Response({'popular_searches': popular})


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def global_search(request):
    """
    Global search across all content types.
    """
    query = request.GET.get('q', '')
    content_types = request.GET.get('types', 'all')  # all, blog, news, events, organizations, resources
    limit = int(request.GET.get('limit', 5))  # Results per content type
    
    if not query:
        return Response({'results': {}})
    
    results = {}
    
    # Search blog posts
    if content_types == 'all' or 'blog' in content_types:
        blog_results = SearchManager.search_blog_posts(query=query)[:limit]
        blog_serializer = BlogPostSerializer(blog_results, many=True, context={'request': request})
        results['blog_posts'] = blog_serializer.data
    
    # Search news
    if content_types == 'all' or 'news' in content_types:
        news_results = SearchManager.search_news(query=query)[:limit]
        news_serializer = NewsSerializer(news_results, many=True, context={'request': request})
        results['news'] = news_serializer.data
    
    # Search events
    if content_types == 'all' or 'events' in content_types:
        event_results = SearchManager.search_events(query=query)[:limit]
        event_serializer = EventSerializer(event_results, many=True, context={'request': request})
        results['events'] = event_serializer.data
    
    # Search organizations
    if content_types == 'all' or 'organizations' in content_types:
        org_results = SearchManager.search_organizations(query=query)[:limit]
        org_serializer = OrganizationSerializer(org_results, many=True, context={'request': request})
        results['organizations'] = org_serializer.data
    
    # Search resources
    if content_types == 'all' or 'resources' in content_types:
        resource_results = SearchManager.search_resources(query=query)[:limit]
        resource_serializer = ResourceSerializer(resource_results, many=True, context={'request': request})
        results['resources'] = resource_serializer.data
    
    # Highlight search terms in all results
    if query:
        for content_type, items in results.items():
            for item in items:
                if 'title' in item:
                    item['title'] = SearchManager.highlight_search_results(item['title'], query)
                if 'name' in item:
                    item['name'] = SearchManager.highlight_search_results(item['name'], query)
                if 'description' in item:
                    item['description'] = SearchManager.highlight_search_results(item.get('description', ''), query)
                if 'excerpt' in item:
                    item['excerpt'] = SearchManager.highlight_search_results(item.get('excerpt', ''), query)
                if 'summary' in item:
                    item['summary'] = SearchManager.highlight_search_results(item.get('summary', ''), query)
    
    # Save search history
    if request.user.is_authenticated:
        total_results = sum(len(items) for items in results.values())
        SearchHistoryManager.save_search_query(
            user_id=request.user.id,
            query=query,
            results_count=total_results,
            filters={'content_types': content_types, 'limit': limit}
        )
    
    return Response({
        'query': query,
        'results': results,
        'total_results': sum(len(items) for items in results.values())
    })


@api_view(['GET'])
@permission_classes([IsAuthenticated])
@method_decorator(cache_page(60 * 10))  # Cache for 10 minutes
def search_filters(request):
    """
    Get available filter options for search.
    """
    filters = {
        'blog_categories': list(BlogCategory.objects.values('id', 'name')),
        'news_categories': list(NewsCategory.objects.values('id', 'name')),
        'tags': list(Tag.objects.values('id', 'name')),
        'countries': list(Country.objects.values('id', 'name')),
        'cities': list(City.objects.values('id', 'name', 'country_id')),
        'languages': [
            {'code': 'en', 'name': 'English'},
            {'code': 'fr', 'name': 'French'},
            {'code': 'ar', 'name': 'Arabic'}
        ],
        'event_types': [
            {'value': 'conference', 'label': 'Conference'},
            {'value': 'workshop', 'label': 'Workshop'},
            {'value': 'seminar', 'label': 'Seminar'},
            {'value': 'training', 'label': 'Training'},
            {'value': 'meeting', 'label': 'Meeting'}
        ],
        'organization_types': [
            {'value': 'ngo', 'label': 'NGO'},
            {'value': 'government', 'label': 'Government'},
            {'value': 'private', 'label': 'Private'},
            {'value': 'academic', 'label': 'Academic'},
            {'value': 'international', 'label': 'International'}
        ],
        'resource_types': [
            {'value': 'document', 'label': 'Document'},
            {'value': 'video', 'label': 'Video'},
            {'value': 'audio', 'label': 'Audio'},
            {'value': 'image', 'label': 'Image'},
            {'value': 'link', 'label': 'Link'}
        ]
    }
    
    return Response({'filters': filters})