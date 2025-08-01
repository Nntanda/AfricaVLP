from django.urls import path, include
from rest_framework.routers import DefaultRouter
from . import viewsets
from . import health_views

# Create a router and register our viewsets with it
router = DefaultRouter()

# Master data viewsets (read-only, public access)
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

# User and organization viewsets (public read, authenticated write)
router.register(r'users', viewsets.UserViewSet)
router.register(r'organizations', viewsets.OrganizationViewSet)
router.register(r'organization-users', viewsets.OrganizationUserViewSet)
router.register(r'organization-offices', viewsets.OrganizationOfficeViewSet)

# Content viewsets (public read-only)
router.register(r'blog-posts', viewsets.BlogPostViewSet)
router.register(r'blog-comments', viewsets.BlogPostCommentViewSet)
router.register(r'news', viewsets.NewsViewSet)
router.register(r'news-comments', viewsets.NewsCommentViewSet)
router.register(r'events', viewsets.EventViewSet)
router.register(r'event-comments', viewsets.EventCommentViewSet)
router.register(r'resources', viewsets.ResourceViewSet)

# The API URLs are now determined automatically by the router.
urlpatterns = [
    path('', include(router.urls)),
    
    # Health check endpoints
    path('health/', health_views.health_check, name='health_check'),
    path('health/live/', health_views.liveness_check, name='liveness_check'),
    path('health/ready/', health_views.readiness_check, name='readiness_check'),
]