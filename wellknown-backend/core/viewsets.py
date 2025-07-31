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


class IsOwnerOrReadOnly(permissions.BasePermission):
    """
    Custom permission to only allow owners of an object to edit it.
    """
    def has_object_permission(self, request, view, obj):
        # Read permissions are allowed to any request,
        # so we'll always allow GET, HEAD or OPTIONS requests.
        if request.method in permissions.SAFE_METHODS:
            return True

        # Write permissions are only allowed to the owner of the object.
        return obj.user == request.user


class IsVerifiedUser(permissions.BasePermission):
    """
    Custom permission to only allow verified users.
    """
    def has_permission(self, request, view):
        return (request.user and 
                request.user.is_authenticated and 
                hasattr(request.user, 'is_email_verified') and
                request.user.is_email_verified)


class CountryViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for Country model - Read only
    """
    queryset = Country.objects.all()
    serializer_class = CountrySerializer
    permission_classes = [permissions.AllowAny]
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
    permission_classes = [permissions.AllowAny]
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
    permission_classes = [permissions.AllowAny]
    filter_backends = [filters.SearchFilter, filters.OrderingFilter]
    search_fields = ['name']
    ordering = ['name']


class OrganizationTypeViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for OrganizationType model - Read only
    """
    queryset = OrganizationType.objects.all()
    serializer_class = OrganizationTypeSerializer
    permission_classes = [permissions.AllowAny]
    ordering = ['name']


class CategoryOfOrganizationViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for CategoryOfOrganization model - Read only
    """
    queryset = CategoryOfOrganization.objects.all()
    serializer_class = CategoryOfOrganizationSerializer
    permission_classes = [permissions.AllowAny]
    ordering = ['name']


class InstitutionTypeViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for InstitutionType model - Read only
    """
    queryset = InstitutionType.objects.all()
    serializer_class = InstitutionTypeSerializer
    permission_classes = [permissions.AllowAny]
    ordering = ['name']


class TagViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for Tag model - Read only for public
    """
    queryset = Tag.objects.all()
    serializer_class = TagSerializer
    permission_classes = [permissions.AllowAny]
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
    permission_classes = [permissions.AllowAny]
    ordering = ['name']


class CategoryOfResourceViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for CategoryOfResource model - Read only
    """
    queryset = CategoryOfResource.objects.all()
    serializer_class = CategoryOfResourceSerializer
    permission_classes = [permissions.AllowAny]
    ordering = ['name']


class ResourceTypeViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for ResourceType model - Read only
    """
    queryset = ResourceType.objects.all()
    serializer_class = ResourceTypeSerializer
    permission_classes = [permissions.AllowAny]
    ordering = ['name']


class UserViewSet(viewsets.ModelViewSet):
    """
    ViewSet for User model - Users can manage their own profiles
    """
    queryset = User.objects.select_related(
        'resident_country', 'city', 'nationality_at_birth', 
        'current_nationality', 'country_served_in'
    ).filter(status=1, is_email_verified=True)
    serializer_class = UserSerializer
    permission_classes = [permissions.IsAuthenticated]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = [
        'gender', 'marital_status', 'availability', 
        'has_volunteering_experience', 'resident_country'
    ]
    search_fields = ['first_name', 'last_name', 'short_profile']
    ordering_fields = ['first_name', 'last_name', 'created', 'experience_rating']
    ordering = ['-created']
    
    def get_queryset(self):
        """Filter to show only active, verified users"""
        return super().get_queryset()
    
    def get_permissions(self):
        """
        Instantiates and returns the list of permissions that this view requires.
        """
        if self.action in ['create', 'list', 'retrieve']:
            permission_classes = [permissions.AllowAny]
        elif self.action in ['update', 'partial_update', 'destroy']:
            permission_classes = [permissions.IsAuthenticated, IsOwnerOrReadOnly]
        else:
            permission_classes = [permissions.IsAuthenticated]
        
        return [permission() for permission in permission_classes]
    
    @action(detail=True, methods=['get'])
    def profile(self, request, pk=None):
        """Get detailed user profile"""
        user = self.get_object()
        serializer = self.get_serializer(user)
        return Response(serializer.data)


class OrganizationViewSet(viewsets.ModelViewSet):
    """
    ViewSet for Organization model - Public read, authenticated write
    """
    queryset = Organization.objects.select_related(
        'organization_type', 'country', 'city', 'institution_type',
        'category', 'user'
    ).prefetch_related('organizationoffice_set', 'organizationuser_set').filter(status=1)
    serializer_class = OrganizationSerializer
    permission_classes = [permissions.IsAuthenticatedOrReadOnly]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = [
        'is_verified', 'organization_type', 'country', 'city',
        'institution_type', 'category'
    ]
    search_fields = ['name', 'about', 'address']
    ordering_fields = ['name', 'created', 'date_of_establishment']
    ordering = ['-created']
    
    def get_queryset(self):
        """Show only verified organizations to public, all to authenticated users"""
        queryset = super().get_queryset()
        if not self.request.user.is_authenticated:
            queryset = queryset.filter(is_verified=True)
        return queryset
    
    def get_permissions(self):
        """
        Instantiates and returns the list of permissions that this view requires.
        """
        if self.action in ['list', 'retrieve']:
            permission_classes = [permissions.AllowAny]
        elif self.action == 'create':
            permission_classes = [IsVerifiedUser]
        elif self.action in ['update', 'partial_update', 'destroy']:
            permission_classes = [permissions.IsAuthenticated, IsOwnerOrReadOnly]
        else:
            permission_classes = [permissions.IsAuthenticated]
        
        return [permission() for permission in permission_classes]
    
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
        members = OrganizationUser.objects.filter(organization=organization, status=1)
        serializer = OrganizationUserSerializer(members, many=True)
        return Response(serializer.data)
    
    @action(detail=True, methods=['post'], permission_classes=[IsVerifiedUser])
    def join(self, request, pk=None):
        """Join organization"""
        organization = self.get_object()
        user = request.user
        
        # Check if user is already a member
        if OrganizationUser.objects.filter(organization=organization, user=user).exists():
            return Response({'error': 'Already a member'}, status=status.HTTP_400_BAD_REQUEST)
        
        # Create membership
        OrganizationUser.objects.create(
            organization=organization,
            user=user,
            role='member',
            status=1,
            created=timezone.now(),
            modified=timezone.now()
        )
        
        logger.info(f"User {user.email} joined organization {organization.name}")
        return Response({'message': 'Successfully joined organization'})


class BlogPostViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for BlogPost model - Read only for public
    """
    queryset = BlogPost.objects.select_related('region').filter(status=1)
    serializer_class = BlogPostSerializer
    permission_classes = [permissions.AllowAny]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['region']
    search_fields = ['title', 'content']
    ordering_fields = ['title', 'created']
    ordering = ['-created']
    
    @action(detail=True, methods=['get'])
    def comments(self, request, pk=None):
        """Get blog post comments"""
        blog_post = self.get_object()
        comments = BlogPostComment.objects.filter(blog_post=blog_post).order_by('-created')
        serializer = BlogPostCommentSerializer(comments, many=True)
        return Response(serializer.data)


class NewsViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for News model - Read only for public
    """
    queryset = News.objects.select_related('organization').filter(status=1)
    serializer_class = NewsSerializer
    permission_classes = [permissions.AllowAny]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['organization', 'region_id']
    search_fields = ['title', 'content']
    ordering_fields = ['title', 'created']
    ordering = ['-created']
    
    @action(detail=True, methods=['get'])
    def comments(self, request, pk=None):
        """Get news comments"""
        news = self.get_object()
        comments = NewsComment.objects.filter(news=news).order_by('-created')
        serializer = NewsCommentSerializer(comments, many=True)
        return Response(serializer.data)


class EventViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for Event model - Read only for public
    """
    queryset = Event.objects.select_related('organization').filter(status=1)
    serializer_class = EventSerializer
    permission_classes = [permissions.AllowAny]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['organization', 'region_id']
    search_fields = ['title', 'content']
    ordering_fields = ['title', 'created']
    ordering = ['-created']
    
    @action(detail=True, methods=['get'])
    def comments(self, request, pk=None):
        """Get event comments"""
        event = self.get_object()
        comments = EventComment.objects.filter(event=event).order_by('-created')
        serializer = EventCommentSerializer(comments, many=True)
        return Response(serializer.data)


class ResourceViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for Resource model - Read only for public
    """
    queryset = Resource.objects.select_related('organization').filter(status=1)
    serializer_class = ResourceSerializer
    permission_classes = [permissions.AllowAny]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['organization', 'region_id']
    search_fields = ['title', 'content']
    ordering_fields = ['title', 'created']
    ordering = ['-created']


class BlogPostCommentViewSet(viewsets.ModelViewSet):
    """
    ViewSet for BlogPostComment model
    """
    queryset = BlogPostComment.objects.select_related('user', 'blog_post').all()
    serializer_class = BlogPostCommentSerializer
    permission_classes = [IsVerifiedUser]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.OrderingFilter]
    filterset_fields = ['blog_post']
    ordering = ['-created']
    
    def perform_create(self, serializer):
        """Set the user to the current user"""
        serializer.save(
            user=self.request.user,
            created=timezone.now(),
            modified=timezone.now()
        )


class NewsCommentViewSet(viewsets.ModelViewSet):
    """
    ViewSet for NewsComment model
    """
    queryset = NewsComment.objects.select_related('user', 'news').all()
    serializer_class = NewsCommentSerializer
    permission_classes = [IsVerifiedUser]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.OrderingFilter]
    filterset_fields = ['news']
    ordering = ['-created']
    
    def perform_create(self, serializer):
        """Set the user to the current user"""
        serializer.save(
            user=self.request.user,
            created=timezone.now(),
            modified=timezone.now()
        )


class EventCommentViewSet(viewsets.ModelViewSet):
    """
    ViewSet for EventComment model
    """
    queryset = EventComment.objects.select_related('user', 'event').all()
    serializer_class = EventCommentSerializer
    permission_classes = [IsVerifiedUser]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.OrderingFilter]
    filterset_fields = ['event']
    ordering = ['-created']
    
    def perform_create(self, serializer):
        """Set the user to the current user"""
        serializer.save(
            user=self.request.user,
            created=timezone.now(),
            modified=timezone.now()
        )


class OrganizationUserViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for OrganizationUser model - Read only
    """
    queryset = OrganizationUser.objects.select_related('organization', 'user').filter(status=1)
    serializer_class = OrganizationUserSerializer
    permission_classes = [permissions.AllowAny]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.OrderingFilter]
    filterset_fields = ['organization', 'role']
    ordering = ['-created']


class OrganizationOfficeViewSet(viewsets.ReadOnlyModelViewSet):
    """
    ViewSet for OrganizationOffice model - Read only
    """
    queryset = OrganizationOffice.objects.select_related('organization', 'country', 'city').all()
    serializer_class = OrganizationOfficeSerializer
    permission_classes = [permissions.AllowAny]
    pagination_class = StandardResultsSetPagination
    filter_backends = [DjangoFilterBackend, filters.SearchFilter]
    filterset_fields = ['organization', 'country', 'city']
    search_fields = ['address', 'email', 'phone_number']
    ordering = ['-created']