from django.urls import path, include
from rest_framework.routers import DefaultRouter
from . import viewsets
from . import search_views
from . import task_views

# Create a router and register our viewsets with it
router = DefaultRouter()

# Master data viewsets (read-only)
router.register(r'countries', viewsets.CountryViewSet)
router.register(r'cities', viewsets.CityViewSet)
router.register(r'regions', viewsets.RegionViewSet)
router.register(r'organization-types', viewsets.OrganizationTypeViewSet)
router.register(r'organization-categories', viewsets.CategoryOfOrganizationViewSet)
router.register(r'institution-types', viewsets.InstitutionTypeViewSet)
router.register(r'tags', viewsets.TagViewSet)
router.register(r'publishing-categories', viewsets.PublishingCategoryViewSet)
router.register(r'resource-categories', viewsets.CategoryOfResourceViewSet)
router.register(r'resource-types', viewsets.ResourceTypeViewSet)

# User management viewsets
router.register(r'admins', viewsets.AdminViewSet)
router.register(r'users', viewsets.UserViewSet)
router.register(r'organizations', viewsets.OrganizationViewSet)
router.register(r'organization-users', viewsets.OrganizationUserViewSet)
router.register(r'organization-offices', viewsets.OrganizationOfficeViewSet)

# Content management viewsets
router.register(r'blog-posts', viewsets.BlogPostViewSet)
router.register(r'blog-comments', viewsets.BlogPostCommentViewSet)
router.register(r'news', viewsets.NewsViewSet)
router.register(r'news-comments', viewsets.NewsCommentViewSet)
router.register(r'events', viewsets.EventViewSet)
router.register(r'event-comments', viewsets.EventCommentViewSet)
router.register(r'resources', viewsets.ResourceViewSet)

# Activity logging viewsets
router.register(r'activity-logs', viewsets.ActivityLogViewSet)
router.register(r'admin-activity-logs', viewsets.AdminActivityLogViewSet)

# Translation viewsets
router.register(r'translations', viewsets.I18nViewSet)

# Task management viewsets
router.register(r'task-results', viewsets.TaskResultViewSet)

# The API URLs are now determined automatically by the router.
urlpatterns = [
    path('', include(router.urls)),
    # Search endpoints
    path('search/blog-posts/', search_views.search_blog_posts, name='search-blog-posts'),
    path('search/news/', search_views.search_news, name='search-news'),
    path('search/events/', search_views.search_events, name='search-events'),
    path('search/organizations/', search_views.search_organizations, name='search-organizations'),
    path('search/resources/', search_views.search_resources, name='search-resources'),
    path('search/global/', search_views.global_search, name='global-search'),
    path('search/suggestions/', search_views.search_suggestions, name='search-suggestions'),
    path('search/history/', search_views.search_history, name='search-history'),
    path('search/popular/', search_views.popular_searches, name='popular-searches'),
    path('search/filters/', search_views.search_filters, name='search-filters'),
    
    # Task management endpoints
    path('tasks/email/send/', task_views.send_email_notification, name='send_email_notification'),
    path('tasks/email/bulk/', task_views.send_bulk_emails, name='send_bulk_emails'),
    path('tasks/image/process/', task_views.process_image, name='process_image'),
    path('tasks/export/', task_views.export_data, name='export_data'),
    path('tasks/report/', task_views.generate_report, name='generate_report'),
    path('tasks/publish/', task_views.trigger_content_publishing, name='trigger_content_publishing'),
    path('tasks/cleanup/', task_views.trigger_cleanup, name='trigger_cleanup'),
    path('tasks/health/', task_views.health_check, name='health_check'),
    path('tasks/status/<str:task_id>/', task_views.task_status, name='task_status'),
    path('tasks/workers/', task_views.worker_status, name='worker_status'),
    path('tasks/revoke/<str:task_id>/', task_views.revoke_task, name='revoke_task'),
    path('tasks/download/<path:file_path>/', task_views.download_export, name='download_export'),
]