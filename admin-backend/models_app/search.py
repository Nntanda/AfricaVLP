"""
Search functionality for the admin backend.
Provides full-text search, filtering, and advanced search capabilities.
"""

from django.db.models import Q, QuerySet
from django.contrib.postgres.search import SearchVector, SearchQuery, SearchRank
from django.db import models
from typing import Dict, Any, List, Optional
import re

from .models import (
    BlogPost, News, Event, Organization, Resource, User,
    BlogCategory, NewsCategory, Tag, Country, City
)


class SearchManager:
    """
    Centralized search manager for handling complex search operations
    across different models with full-text search capabilities.
    """
    
    @staticmethod
    def search_blog_posts(
        query: str = "",
        categories: List[int] = None,
        tags: List[int] = None,
        organization_id: int = None,
        date_from: str = None,
        date_to: str = None,
        status: str = None,
        language: str = None
    ) -> QuerySet:
        """
        Advanced search for blog posts with full-text search and filtering.
        """
        queryset = BlogPost.objects.select_related(
            'organization', 'created_by'
        ).prefetch_related('categories', 'tags')
        
        # Full-text search
        if query:
            search_vector = SearchVector('title', weight='A') + \
                          SearchVector('content', weight='B') + \
                          SearchVector('excerpt', weight='C')
            search_query = SearchQuery(query)
            queryset = queryset.annotate(
                search=search_vector,
                rank=SearchRank(search_vector, search_query)
            ).filter(search=search_query).order_by('-rank', '-created_at')
        else:
            queryset = queryset.order_by('-created_at')
        
        # Category filtering
        if categories:
            queryset = queryset.filter(categories__id__in=categories)
        
        # Tag filtering
        if tags:
            queryset = queryset.filter(tags__id__in=tags)
        
        # Organization filtering
        if organization_id:
            queryset = queryset.filter(organization_id=organization_id)
        
        # Date range filtering
        if date_from:
            queryset = queryset.filter(created_at__gte=date_from)
        if date_to:
            queryset = queryset.filter(created_at__lte=date_to)
        
        # Status filtering
        if status:
            queryset = queryset.filter(status=status)
        
        # Language filtering
        if language:
            queryset = queryset.filter(language=language)
        
        return queryset.distinct()
    
    @staticmethod
    def search_news(
        query: str = "",
        categories: List[int] = None,
        tags: List[int] = None,
        organization_id: int = None,
        date_from: str = None,
        date_to: str = None,
        status: str = None
    ) -> QuerySet:
        """
        Advanced search for news articles with full-text search and filtering.
        """
        queryset = News.objects.select_related(
            'organization', 'created_by'
        ).prefetch_related('categories', 'tags')
        
        # Full-text search
        if query:
            search_vector = SearchVector('title', weight='A') + \
                          SearchVector('content', weight='B') + \
                          SearchVector('summary', weight='C')
            search_query = SearchQuery(query)
            queryset = queryset.annotate(
                search=search_vector,
                rank=SearchRank(search_vector, search_query)
            ).filter(search=search_query).order_by('-rank', '-created_at')
        else:
            queryset = queryset.order_by('-created_at')
        
        # Apply filters (similar to blog posts)
        if categories:
            queryset = queryset.filter(categories__id__in=categories)
        if tags:
            queryset = queryset.filter(tags__id__in=tags)
        if organization_id:
            queryset = queryset.filter(organization_id=organization_id)
        if date_from:
            queryset = queryset.filter(created_at__gte=date_from)
        if date_to:
            queryset = queryset.filter(created_at__lte=date_to)
        if status:
            queryset = queryset.filter(status=status)
        
        return queryset.distinct()
    
    @staticmethod
    def search_events(
        query: str = "",
        organization_id: int = None,
        country_id: int = None,
        city_id: int = None,
        date_from: str = None,
        date_to: str = None,
        event_type: str = None,
        status: str = None
    ) -> QuerySet:
        """
        Advanced search for events with location-based filtering.
        """
        queryset = Event.objects.select_related(
            'organization', 'country', 'city', 'created_by'
        )
        
        # Full-text search
        if query:
            search_vector = SearchVector('title', weight='A') + \
                          SearchVector('description', weight='B') + \
                          SearchVector('location', weight='C')
            search_query = SearchQuery(query)
            queryset = queryset.annotate(
                search=search_vector,
                rank=SearchRank(search_vector, search_query)
            ).filter(search=search_query).order_by('-rank', '-start_date')
        else:
            queryset = queryset.order_by('-start_date')
        
        # Location-based filtering
        if country_id:
            queryset = queryset.filter(country_id=country_id)
        if city_id:
            queryset = queryset.filter(city_id=city_id)
        
        # Other filters
        if organization_id:
            queryset = queryset.filter(organization_id=organization_id)
        if date_from:
            queryset = queryset.filter(start_date__gte=date_from)
        if date_to:
            queryset = queryset.filter(end_date__lte=date_to)
        if event_type:
            queryset = queryset.filter(event_type=event_type)
        if status:
            queryset = queryset.filter(status=status)
        
        return queryset.distinct()
    
    @staticmethod
    def search_organizations(
        query: str = "",
        country_id: int = None,
        city_id: int = None,
        organization_type: str = None,
        status: str = None
    ) -> QuerySet:
        """
        Advanced search for organizations with location-based filtering.
        """
        queryset = Organization.objects.select_related(
            'country', 'city'
        ).prefetch_related('users')
        
        # Full-text search
        if query:
            search_vector = SearchVector('name', weight='A') + \
                          SearchVector('description', weight='B') + \
                          SearchVector('mission', weight='C')
            search_query = SearchQuery(query)
            queryset = queryset.annotate(
                search=search_vector,
                rank=SearchRank(search_vector, search_query)
            ).filter(search=search_query).order_by('-rank', 'name')
        else:
            queryset = queryset.order_by('name')
        
        # Location-based filtering
        if country_id:
            queryset = queryset.filter(country_id=country_id)
        if city_id:
            queryset = queryset.filter(city_id=city_id)
        
        # Other filters
        if organization_type:
            queryset = queryset.filter(organization_type=organization_type)
        if status:
            queryset = queryset.filter(status=status)
        
        return queryset.distinct()
    
    @staticmethod
    def search_resources(
        query: str = "",
        categories: List[int] = None,
        organization_id: int = None,
        resource_type: str = None,
        language: str = None
    ) -> QuerySet:
        """
        Advanced search for resources with category filtering.
        """
        queryset = Resource.objects.select_related(
            'organization', 'created_by'
        ).prefetch_related('categories')
        
        # Full-text search
        if query:
            search_vector = SearchVector('title', weight='A') + \
                          SearchVector('description', weight='B') + \
                          SearchVector('tags', weight='C')
            search_query = SearchQuery(query)
            queryset = queryset.annotate(
                search=search_vector,
                rank=SearchRank(search_vector, search_query)
            ).filter(search=search_query).order_by('-rank', '-created_at')
        else:
            queryset = queryset.order_by('-created_at')
        
        # Category filtering
        if categories:
            queryset = queryset.filter(categories__id__in=categories)
        
        # Other filters
        if organization_id:
            queryset = queryset.filter(organization_id=organization_id)
        if resource_type:
            queryset = queryset.filter(resource_type=resource_type)
        if language:
            queryset = queryset.filter(language=language)
        
        return queryset.distinct()
    
    @staticmethod
    def get_search_suggestions(query: str, limit: int = 10) -> Dict[str, List[str]]:
        """
        Get search suggestions based on existing content.
        """
        suggestions = {
            'blog_posts': [],
            'news': [],
            'events': [],
            'organizations': [],
            'resources': []
        }
        
        if len(query) < 2:
            return suggestions
        
        # Blog post suggestions
        blog_titles = BlogPost.objects.filter(
            title__icontains=query
        ).values_list('title', flat=True)[:limit]
        suggestions['blog_posts'] = list(blog_titles)
        
        # News suggestions
        news_titles = News.objects.filter(
            title__icontains=query
        ).values_list('title', flat=True)[:limit]
        suggestions['news'] = list(news_titles)
        
        # Event suggestions
        event_titles = Event.objects.filter(
            title__icontains=query
        ).values_list('title', flat=True)[:limit]
        suggestions['events'] = list(event_titles)
        
        # Organization suggestions
        org_names = Organization.objects.filter(
            name__icontains=query
        ).values_list('name', flat=True)[:limit]
        suggestions['organizations'] = list(org_names)
        
        # Resource suggestions
        resource_titles = Resource.objects.filter(
            title__icontains=query
        ).values_list('title', flat=True)[:limit]
        suggestions['resources'] = list(resource_titles)
        
        return suggestions
    
    @staticmethod
    def highlight_search_results(text: str, query: str) -> str:
        """
        Highlight search terms in text results.
        """
        if not query or not text:
            return text
        
        # Split query into individual terms
        terms = query.split()
        highlighted_text = text
        
        for term in terms:
            # Case-insensitive highlighting
            pattern = re.compile(re.escape(term), re.IGNORECASE)
            highlighted_text = pattern.sub(
                f'<mark>{term}</mark>', 
                highlighted_text
            )
        
        return highlighted_text


class SearchHistoryManager:
    """
    Manager for handling search history and analytics.
    """
    
    @staticmethod
    def save_search_query(user_id: int, query: str, results_count: int, filters: Dict[str, Any] = None):
        """
        Save search query for history and analytics.
        """
        from .models import SearchHistory
        
        SearchHistory.objects.create(
            user_id=user_id,
            query=query,
            results_count=results_count,
            filters=filters or {}
        )
    
    @staticmethod
    def get_user_search_history(user_id: int, limit: int = 20) -> QuerySet:
        """
        Get user's recent search history.
        """
        from .models import SearchHistory
        
        return SearchHistory.objects.filter(
            user_id=user_id
        ).order_by('-created_at')[:limit]
    
    @staticmethod
    def get_popular_searches(limit: int = 10) -> List[Dict[str, Any]]:
        """
        Get most popular search queries.
        """
        from .models import SearchHistory
        from django.db.models import Count
        
        popular = SearchHistory.objects.values('query').annotate(
            count=Count('query')
        ).order_by('-count')[:limit]
        
        return list(popular)