from rest_framework import viewsets, permissions, status, filters
from rest_framework.decorators import action
from rest_framework.response import Response
from rest_framework.pagination import PageNumberPagination
from django_filters.rest_framework import DjangoFilterBackend
from django.db.models import Q
from django.utils import timezone
import logging

from .models import (
    Admin, User, Country, City, Region, Organization, OrganizationType,
    CategoryOfOrganization, InstitutionType, BlogPost, News, Event, Resource,
    Tag, PublishingCategory, BlogCategory, BlogPostTag, BlogPostComment,
    NewsCategory, NewsTag, NewsComment, EventComment, CategoryOfResource,
    ResourceType, ResourceCategory, ActivityLog, AdminActivityLog,
    OrganizationUser, OrganizationOffice, I18n
)
from .serializers import (
    AdminSerializer, UserSerializer, CountrySerializer, CitySerializer,
    RegionSerializer, OrganizationSerializer, OrganizationTypeSerializer,
    CategoryOfOrganizationSerializer, InstitutionTypeSerializer,
    BlogPostSerializer, NewsSerializer, EventSerializer, ResourceSerializer,
    TagSerializer, PublishingCategorySerializer, BlogPostCommentSerializer,
    NewsCommentSerializer, EventCommentSerializer, CategoryOfResourceSerializer,
    ResourceTypeSerializer, ActivityLogSerializer, AdminActivityLogSerializer,
    OrganizationUserSerializer, OrganizationOfficeSerializer, I18nSerializer
)

logger = logging.getLogger(__name__)


class StandardResultsSetPagination(PageNumberPagination):
    """Standard pagination class"""
    page_size = 20
    page_size_query_param = 'page_size'
    max_page_size = 100


class IsAdminOrReadOnly(permissions.BasePermission):
    """
    Custom permission to only allow admins to edit objects.
    """
    def has_permission(self, request, view):
        if request.method in permissions.SAFE_METHODS:
            return request.user and request.user.is_authenticated
        return request.user and hasattr(request.user, 'role') and request.user.role in ['admin', 'super_admin']


class IsSuperAdminOnly(permissions.BasePermission):
    """
    Custom permission to only allow super admins.
    """
    def has_permission(self, request, view):
        return request.user and hasattr(request.user, 'role') and request.user.role == 'super_admin'


class CountryViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for Country model - Read only
    """
    queryset = Country.objects.all()
    serializer_class = CountrySerializer
    permission_classes = [permissions.IsAuthenticated]
    filter_backends = [filters.SearchFilter, filters.OrderingFilter]
    search_fields = ['name', 'nicename', 'iso', 'iso3']
    ordering_fields = ['name', 'nicename']
    ordering = ['nicename']


class CityViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for City model - Read only
    """
    queryset = City.objects.select_related('country').all()
    serializer_class = CitySerializer
    permission_classes = [permissions.IsAuthenticated]
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['country']
    search_fields = ['name']
    ordering_fields = ['name']
    ordering = ['name']


class RegionViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for Region model - Read only
    """
    queryset = Region.objects.all()
    serializer_class = RegionSerializer
    permission_classes = [permissions.IsAuthenticated]
    filter_backends = [filters.SearchFilter, filters.OrderingFilter]
    search_fields = ['name']
    ordering = ['name']


class OrganizationTypeViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for OrganizationType model - Read only
    """
    queryset = OrganizationType.objects.all()
    serializer_class = OrganizationTypeSerializer
    permission_classes = [permissions.IsAuthenticated]
    ordering = ['name']


class CategoryOfOrganizationViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for CategoryOfOrganization model - Read only
    """
    queryset = CategoryOfOrganization.objects.all()
    serializer_class = CategoryOfOrganizationSerializer
    permission_classes = [permissions.IsAuthenticated]
    ordering = ['name']


class InstitutionTypeViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for InstitutionType model - Read only
    """
    queryset = InstitutionType.objects.all()
    serializer_class = InstitutionTypeSerializer
    permission_classes = [permissions.IsAuthenticated]
    ordering = ['name']


class TagViewSet(viewsets.ModelViewSet):
    """
    ViewSet for Tag model with CRUD operations
    """
    queryset = Tag.objects.all()
    serializer_class = TagSerializer
    permission_classes = [IsAdminOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [filters.SearchFilter, filters.OrderingFilter]
    search_fields = ['title']
    ordering = ['title']


class PublishingCategoryViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for PublishingCategory model - Read only
    """
    queryset = PublishingCategory.objects.all()
    serializer_class = PublishingCategorySerializer
    permission_classes = [permissions.IsAuthenticated]
    ordering = ['name']


class CategoryOfResourceViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for CategoryOfResource model - Read only
    """
    queryset = CategoryOfResource.objects.all()
    serializer_class = CategoryOfResourceSerializer
    permission_classes = [permissions.IsAuthenticated]
    ordering = ['name']


class ResourceTypeViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for ResourceType model - Read only
    """
    queryset = ResourceType.objects.all()
    serializer_class = ResourceTypeSerializer
    permission_classes = [permissions.IsAuthenticated]
    ordering = ['name']


class AdminViewSet(viewsets.ModelViewSet):
    """
    ViewSet for Admin model with role-based field filtering
    """
    queryset = Admin.objects.all()
    serializer_class = AdminSerializer
    permission_classes = [IsSuperAdminOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['role', 'status']
    search_fields = ['name', 'email']
    ordering_fields = ['name', 'email', 'created']
    ordering = ['-created']
    
    def perform_create(self, serializer):
        """Log admin creation"""
        admin = serializer.save()
        logger.info(f"Admin created: {admin.name} by {self.request.user}")
    
    def perform_update(self, serializer):
        """Log admin update"""
        admin = serializer.save()
        logger.info(f"Admin updated: {admin.name} by {self.request.user}")
    
    def perform_destroy(self, instance):
        """Log admin deletion"""
        logger.info(f"Admin deleted: {instance.name} by {self.request.user}")
        super().perform_destroy(instance)


class UserViewSet(viewsets.ModelViewSet):
    """
    ViewSet for User model with nested organization data
    """
    queryset = User.objects.select_related(
        'resident_country', 'city', 'nationality_at_birth', 
        'current_nationality', 'country_served_in'
    ).all()
    serializer_class = UserSerializer
    permission_classes = [IsAdminOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = [
        'status', 'is_email_verified', 'gender', 'marital_status',
        'availability', 'has_volunteering_experience', 'resident_country'
    ]
    search_fields = ['first_name', 'last_name', 'email', 'short_profile']
    ordering_fields = ['first_name', 'last_name', 'email', 'created', 'experience_rating']
    ordering = ['-created']
    
    @action(detail=True, methods=['post'], permission_classes=[IsSuperAdminOnly])
    def verify_email(self, request, pk=None):
        """Verify user email"""
        user = self.get_object()
        user.is_email_verified = True
        user.modified = timezone.now()
        user.save(update_fields=['is_email_verified', 'modified'])
        logger.info(f"Email verified for user: {user.email} by {request.user}")
        return Response({'message': 'Email verified successfully'})
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def update_status(self, request, pk=None):
        """Update user status"""
        user = self.get_object()
        new_status = request.data.get('status')
        if new_status in [0, 1]:
            user.status = new_status
            user.modified = timezone.now()
            user.save(update_fields=['status', 'modified'])
            logger.info(f"User status updated: {user.email} to {new_status} by {request.user}")
            return Response({'message': 'Status updated successfully'})
        return Response({'error': 'Invalid status'}, status=status.HTTP_400_BAD_REQUEST)
    
    @action(detail=False, methods=['get'], permission_classes=[permissions.IsAuthenticated])
    def profile(self, request):
        """Get current user's profile"""
        if hasattr(request.user, 'id') and isinstance(request.user, User):
            serializer = self.get_serializer(request.user)
            return Response(serializer.data)
        return Response({'error': 'User profile not found'}, status=404)
    
    @action(detail=False, methods=['patch'], permission_classes=[permissions.IsAuthenticated])
    def update_profile(self, request):
        """Update current user's profile"""
        if hasattr(request.user, 'id') and isinstance(request.user, User):
            serializer = self.get_serializer(request.user, data=request.data, partial=True)
            if serializer.is_valid():
                serializer.save()
                logger.info(f"User profile updated: {request.user.email}")
                return Response(serializer.data)
            return Response(serializer.errors, status=400)
        return Response({'error': 'User profile not found'}, status=404)


class OrganizationViewSet(viewsets.ModelViewSet):
    """
    ViewSet for Organization model with nested office and user data
    """
    queryset = Organization.objects.select_related(
        'organization_type', 'country', 'city', 'institution_type',
        'category', 'user'
    ).prefetch_related('organizationoffice_set', 'organizationuser_set').all()
    serializer_class = OrganizationSerializer
    permission_classes = [IsAdminOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = [
        'status', 'is_verified', 'organization_type', 'country', 'city',
        'institution_type', 'category'
    ]
    search_fields = ['name', 'about', 'email', 'address']
    ordering_fields = ['name', 'created', 'date_of_establishment']
    ordering = ['-created']
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def verify(self, request, pk=None):
        """Verify organization"""
        organization = self.get_object()
        organization.is_verified = True
        organization.modified = timezone.now()
        organization.save(update_fields=['is_verified', 'modified'])
        logger.info(f"Organization verified: {organization.name} by {request.user}")
        return Response({'message': 'Organization verified successfully'})
    
    @action(detail=True, methods=['get'])
    def offices(self, request, pk=None):
        """Get organization offices"""
        organization = self.get_object()
        offices = OrganizationOffice.objects.filter(organization=organization)
        serializer = OrganizationOfficeSerializer(offices, many=True)
        return Response(serializer.data)
    
    @action(detail=True, methods=['get'])
    def members(self, request, pk=None):
        """Get organization members"""
        organization = self.get_object()
        members = OrganizationUser.objects.filter(organization=organization)
        serializer = OrganizationUserSerializer(members, many=True)
        return Response(serializer.data)


class BlogPostViewSet(viewsets.ModelViewSet):
    """
    ViewSet for BlogPost model with translation and category support
    """
    queryset = BlogPost.objects.select_related('region').all()
    serializer_class = BlogPostSerializer
    permission_classes = [IsAdminOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['status', 'region']
    search_fields = ['title', 'content', 'slug']
    ordering_fields = ['title', 'created', 'modified']
    ordering = ['-created']
    
    def get_queryset(self):
        """Filter published posts for non-admin users and add advanced filtering"""
        queryset = super().get_queryset()
        
        # Filter published posts for non-admin users
        if not (hasattr(self.request.user, 'role') and self.request.user.role in ['admin', 'super_admin']):
            queryset = queryset.filter(status=1)  # Published only
        
        # Advanced filtering
        category_id = self.request.query_params.get('category')
        if category_id:
            queryset = queryset.filter(blogcategory__category_id=category_id)
        
        tag_id = self.request.query_params.get('tag')
        if tag_id:
            queryset = queryset.filter(blogposttag__tag_id=tag_id)
        
        # Date range filtering
        date_from = self.request.query_params.get('date_from')
        date_to = self.request.query_params.get('date_to')
        if date_from:
            queryset = queryset.filter(created__gte=date_from)
        if date_to:
            queryset = queryset.filter(created__lte=date_to)
        
        return queryset.distinct()
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def publish(self, request, pk=None):
        """Publish blog post"""
        blog_post = self.get_object()
        blog_post.status = 1
        blog_post.modified = timezone.now()
        blog_post.save(update_fields=['status', 'modified'])
        logger.info(f"Blog post published: {blog_post.title} by {request.user}")
        return Response({'message': 'Blog post published successfully'})
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def unpublish(self, request, pk=None):
        """Unpublish blog post"""
        blog_post = self.get_object()
        blog_post.status = 2  # Draft
        blog_post.modified = timezone.now()
        blog_post.save(update_fields=['status', 'modified'])
        logger.info(f"Blog post unpublished: {blog_post.title} by {request.user}")
        return Response({'message': 'Blog post unpublished successfully'})
    
    @action(detail=True, methods=['get'])
    def comments(self, request, pk=None):
        """Get blog post comments"""
        blog_post = self.get_object()
        comments = BlogPostComment.objects.filter(blog_post=blog_post).order_by('-created')
        serializer = BlogPostCommentSerializer(comments, many=True)
        return Response(serializer.data)
    
    @action(detail=False, methods=['get'])
    def featured(self, request):
        """Get featured blog posts"""
        queryset = self.get_queryset().filter(status=1)[:5]  # Top 5 published posts
        serializer = self.get_serializer(queryset, many=True)
        return Response(serializer.data)


class NewsViewSet(viewsets.ModelViewSet):
    """
    ViewSet for News model with tags and categories
    """
    queryset = News.objects.select_related('organization').all()
    serializer_class = NewsSerializer
    permission_classes = [IsAdminOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['status', 'organization', 'region_id']
    search_fields = ['title', 'content', 'slug']
    ordering_fields = ['title', 'created', 'modified']
    ordering = ['-created']
    
    def get_queryset(self):
        """Filter published news for non-admin users and add advanced filtering"""
        queryset = super().get_queryset()
        
        # Filter published news for non-admin users
        if not (hasattr(self.request.user, 'role') and self.request.user.role in ['admin', 'super_admin']):
            queryset = queryset.filter(status=1)  # Published only
        
        # Advanced filtering
        category_id = self.request.query_params.get('category')
        if category_id:
            queryset = queryset.filter(newscategory__category_id=category_id)
        
        tag_id = self.request.query_params.get('tag')
        if tag_id:
            queryset = queryset.filter(newstag__tag_id=tag_id)
        
        # Date range filtering
        date_from = self.request.query_params.get('date_from')
        date_to = self.request.query_params.get('date_to')
        if date_from:
            queryset = queryset.filter(created__gte=date_from)
        if date_to:
            queryset = queryset.filter(created__lte=date_to)
        
        return queryset.distinct()
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def publish(self, request, pk=None):
        """Publish news article"""
        news = self.get_object()
        news.status = 1
        news.modified = timezone.now()
        news.save(update_fields=['status', 'modified'])
        logger.info(f"News published: {news.title} by {request.user}")
        return Response({'message': 'News published successfully'})
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def unpublish(self, request, pk=None):
        """Unpublish news article"""
        news = self.get_object()
        news.status = 2  # Draft
        news.modified = timezone.now()
        news.save(update_fields=['status', 'modified'])
        logger.info(f"News unpublished: {news.title} by {request.user}")
        return Response({'message': 'News unpublished successfully'})
    
    @action(detail=True, methods=['get'])
    def comments(self, request, pk=None):
        """Get news comments"""
        news = self.get_object()
        comments = NewsComment.objects.filter(news=news).order_by('-created')
        serializer = NewsCommentSerializer(comments, many=True)
        return Response(serializer.data)
    
    @action(detail=False, methods=['get'])
    def latest(self, request):
        """Get latest news articles"""
        queryset = self.get_queryset().filter(status=1)[:10]  # Latest 10 published articles
        serializer = self.get_serializer(queryset, many=True)
        return Response(serializer.data)


class EventViewSet(viewsets.ModelViewSet):
    """
    ViewSet for Event model with location-based filtering
    """
    queryset = Event.objects.select_related('organization').all()
    serializer_class = EventSerializer
    permission_classes = [IsAdminOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['status', 'organization', 'region_id']
    search_fields = ['title', 'content', 'slug']
    ordering_fields = ['title', 'created', 'modified']
    ordering = ['-created']
    
    def get_queryset(self):
        """Filter published events for non-admin users and add location-based filtering"""
        queryset = super().get_queryset()
        
        # Filter published events for non-admin users
        if not (hasattr(self.request.user, 'role') and self.request.user.role in ['admin', 'super_admin']):
            queryset = queryset.filter(status=1)  # Published only
        
        # Location-based filtering
        country_id = self.request.query_params.get('country')
        if country_id:
            queryset = queryset.filter(organization__country_id=country_id)
        
        city_id = self.request.query_params.get('city')
        if city_id:
            queryset = queryset.filter(organization__city_id=city_id)
        
        # Date range filtering for events
        date_from = self.request.query_params.get('date_from')
        date_to = self.request.query_params.get('date_to')
        if date_from:
            queryset = queryset.filter(created__gte=date_from)
        if date_to:
            queryset = queryset.filter(created__lte=date_to)
        
        return queryset.distinct()
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def publish(self, request, pk=None):
        """Publish event"""
        event = self.get_object()
        event.status = 1
        event.modified = timezone.now()
        event.save(update_fields=['status', 'modified'])
        logger.info(f"Event published: {event.title} by {request.user}")
        return Response({'message': 'Event published successfully'})
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def unpublish(self, request, pk=None):
        """Unpublish event"""
        event = self.get_object()
        event.status = 2  # Draft
        event.modified = timezone.now()
        event.save(update_fields=['status', 'modified'])
        logger.info(f"Event unpublished: {event.title} by {request.user}")
        return Response({'message': 'Event unpublished successfully'})
    
    @action(detail=True, methods=['get'])
    def comments(self, request, pk=None):
        """Get event comments"""
        event = self.get_object()
        comments = EventComment.objects.filter(event=event).order_by('-created')
        serializer = EventCommentSerializer(comments, many=True)
        return Response(serializer.data)
    
    @action(detail=False, methods=['get'])
    def upcoming(self, request):
        """Get upcoming events"""
        queryset = self.get_queryset().filter(status=1).order_by('created')[:10]
        serializer = self.get_serializer(queryset, many=True)
        return Response(serializer.data)
    
    @action(detail=False, methods=['get'])
    def by_location(self, request):
        """Get events grouped by location"""
        country_id = request.query_params.get('country')
        if not country_id:
            return Response({'error': 'Country parameter is required'}, status=400)
        
        queryset = self.get_queryset().filter(
            status=1,
            organization__country_id=country_id
        )
        serializer = self.get_serializer(queryset, many=True)
        return Response(serializer.data)


class ResourceViewSet(viewsets.ModelViewSet):
    """
    ViewSet for Resource model with category filtering
    """
    queryset = Resource.objects.select_related('organization').all()
    serializer_class = ResourceSerializer
    permission_classes = [IsAdminOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['status', 'organization', 'region_id']
    search_fields = ['title', 'content', 'slug']
    ordering_fields = ['title', 'created', 'modified']
    ordering = ['-created']
    
    def get_queryset(self):
        """Filter published resources for non-admin users and add category filtering"""
        queryset = super().get_queryset()
        
        # Filter published resources for non-admin users
        if not (hasattr(self.request.user, 'role') and self.request.user.role in ['admin', 'super_admin']):
            queryset = queryset.filter(status=1)  # Published only
        
        # Category filtering
        category_id = self.request.query_params.get('category')
        if category_id:
            queryset = queryset.filter(resourcecategory__category_id=category_id)
        
        # Resource type filtering
        resource_type = self.request.query_params.get('resource_type')
        if resource_type:
            queryset = queryset.filter(resourcecategory__category__resourcetype__id=resource_type)
        
        # Date range filtering
        date_from = self.request.query_params.get('date_from')
        date_to = self.request.query_params.get('date_to')
        if date_from:
            queryset = queryset.filter(created__gte=date_from)
        if date_to:
            queryset = queryset.filter(created__lte=date_to)
        
        return queryset.distinct()
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def publish(self, request, pk=None):
        """Publish resource"""
        resource = self.get_object()
        resource.status = 1
        resource.modified = timezone.now()
        resource.save(update_fields=['status', 'modified'])
        logger.info(f"Resource published: {resource.title} by {request.user}")
        return Response({'message': 'Resource published successfully'})
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def unpublish(self, request, pk=None):
        """Unpublish resource"""
        resource = self.get_object()
        resource.status = 2  # Draft
        resource.modified = timezone.now()
        resource.save(update_fields=['status', 'modified'])
        logger.info(f"Resource unpublished: {resource.title} by {request.user}")
        return Response({'message': 'Resource unpublished successfully'})
    
    @action(detail=False, methods=['get'])
    def by_category(self, request):
        """Get resources grouped by category"""
        category_id = request.query_params.get('category')
        if not category_id:
            return Response({'error': 'Category parameter is required'}, status=400)
        
        queryset = self.get_queryset().filter(
            status=1,
            resourcecategory__category_id=category_id
        )
        serializer = self.get_serializer(queryset, many=True)
        return Response(serializer.data)
    
    @action(detail=False, methods=['get'])
    def popular(self, request):
        """Get popular resources"""
        queryset = self.get_queryset().filter(status=1)[:10]  # Top 10 published resources
        serializer = self.get_serializer(queryset, many=True)
        return Response(serializer.data)


class ActivityLogViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for ActivityLog model - Admin monitoring
    """
    queryset = ActivityLog.objects.all()
    serializer_class = ActivityLogSerializer
    permission_classes = [IsAdminOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['level', 'scope_model', 'action']
    search_fields = ['message', 'action']
    ordering = ['-created_at']


class AdminActivityLogViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for AdminActivityLog model - Admin monitoring
    """
    queryset = AdminActivityLog.objects.all()
    serializer_class = AdminActivityLogSerializer
    permission_classes = [IsSuperAdminOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['level', 'scope_model', 'action']
    search_fields = ['message', 'action']
    ordering = ['-created_at']


class BlogPostCommentViewSet(viewsets.ModelViewSet):
    """
    ViewSet for BlogPostComment model
    """
    queryset = BlogPostComment.objects.select_related('user', 'blog_post').all()
    serializer_class = BlogPostCommentSerializer
    permission_classes = [permissions.IsAuthenticated]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.OrderingFilter]
    filterset_fields = ['blog_post', 'user']
    ordering = ['-created']


class NewsCommentViewSet(viewsets.ModelViewSet):
    """
    ViewSet for NewsComment model
    """
    queryset = NewsComment.objects.select_related('user', 'news').all()
    serializer_class = NewsCommentSerializer
    permission_classes = [permissions.IsAuthenticated]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.OrderingFilter]
    filterset_fields = ['news', 'user']
    ordering = ['-created']


class EventCommentViewSet(viewsets.ModelViewSet):
    """
    ViewSet for EventComment model
    """
    queryset = EventComment.objects.select_related('user', 'event').all()
    serializer_class = EventCommentSerializer
    permission_classes = [permissions.IsAuthenticated]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.OrderingFilter]
    filterset_fields = ['event', 'user']
    ordering = ['-created']


class OrganizationUserViewSet(viewsets.ModelViewSet):
    """
    ViewSet for OrganizationUser model - User relationship management
    """
    queryset = OrganizationUser.objects.select_related('organization', 'user').all()
    serializer_class = OrganizationUserSerializer
    permission_classes = [IsAdminOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.OrderingFilter]
    filterset_fields = ['organization', 'user', 'role', 'status']
    ordering = ['-created']
    
    def get_queryset(self):
        """Filter based on user permissions"""
        queryset = super().get_queryset()
        
        # If user is not admin, only show their own memberships
        if not (hasattr(self.request.user, 'role') and self.request.user.role in ['admin', 'super_admin']):
            if hasattr(self.request.user, 'id'):
                queryset = queryset.filter(user_id=self.request.user.id)
        
        return queryset
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def approve(self, request, pk=None):
        """Approve organization membership"""
        membership = self.get_object()
        membership.status = 1  # Approved
        membership.modified = timezone.now()
        membership.save(update_fields=['status', 'modified'])
        logger.info(f"Organization membership approved: {membership.user.email} to {membership.organization.name} by {request.user}")
        return Response({'message': 'Membership approved successfully'})
    
    @action(detail=True, methods=['post'], permission_classes=[IsAdminOrReadOnly])
    def reject(self, request, pk=None):
        """Reject organization membership"""
        membership = self.get_object()
        membership.status = 2  # Rejected
        membership.modified = timezone.now()
        membership.save(update_fields=['status', 'modified'])
        logger.info(f"Organization membership rejected: {membership.user.email} to {membership.organization.name} by {request.user}")
        return Response({'message': 'Membership rejected successfully'})
    
    @action(detail=False, methods=['get'], permission_classes=[permissions.IsAuthenticated])
    def my_memberships(self, request):
        """Get current user's organization memberships"""
        if hasattr(request.user, 'id'):
            memberships = OrganizationUser.objects.filter(user_id=request.user.id)
            serializer = self.get_serializer(memberships, many=True)
            return Response(serializer.data)
        return Response({'error': 'User not found'}, status=400)


class OrganizationOfficeViewSet(viewsets.ModelViewSet):
    """
    ViewSet for OrganizationOffice model
    """
    queryset = OrganizationOffice.objects.select_related('organization', 'country', 'city').all()
    serializer_class = OrganizationOfficeSerializer
    permission_classes = [IsAdminOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter]
    filterset_fields = ['organization', 'country', 'city']
    search_fields = ['address', 'email', 'phone_number']
    ordering = ['-created']


class I18nViewSet(viewsets.ModelViewSet):
    """
    ViewSet for I18n translation model
    """
    queryset = I18n.objects.all()
    serializer_class = I18nSerializer
    permission_classes = [IsAdminOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter]
    filterset_fields = ['locale', 'model', 'field']
    search_fields = ['content']


