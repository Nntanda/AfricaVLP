from rest_framework import status, permissions
from rest_framework.decorators import api_view, permission_classes
from rest_framework.response import Response
from rest_framework.views import APIView
from rest_framework.pagination import PageNumberPagination
from django.db.models import Q, Count, Avg
from django.utils import timezone
from datetime import timedelta
import logging

from .models import (
    User, Organization, BlogPost, News, Event, Resource,
    Tag, OrganizationUser, BlogPostComment, NewsComment, EventComment
)
from .serializers import (
    UserSerializer, OrganizationSerializer, BlogPostSerializer,
    NewsSerializer, EventSerializer, ResourceSerializer, TagSerializer
)
from .permissions import (
    IsVerifiedUser, CanJoinOrganization, CanComment, IsActiveUser
)

logger = logging.getLogger(__name__)


class PublicSearchView(APIView):
    """
    Public search across published content
    """
    permission_classes = [permissions.AllowAny]
    
    def get(self, request):
        query = request.query_params.get('q', '')
        content_type = request.query_params.get('type', 'all')
        
        if not query:
            return Response({'error': 'Search query is required'}, status=status.HTTP_400_BAD_REQUEST)
        
        results = {}
        
        try:
            if content_type in ['all', 'organizations']:
                organizations = Organization.objects.filter(
                    Q(name__icontains=query) |
                    Q(about__icontains=query) |
                    Q(address__icontains=query),
                    status=1,
                    is_verified=True
                )[:10]
                results['organizations'] = OrganizationSerializer(organizations, many=True).data
            
            if content_type in ['all', 'blog_posts']:
                blog_posts = BlogPost.objects.filter(
                    Q(title__icontains=query) |
                    Q(content__icontains=query),
                    status=1
                )[:10]
                results['blog_posts'] = BlogPostSerializer(blog_posts, many=True).data
            
            if content_type in ['all', 'news']:
                news = News.objects.filter(
                    Q(title__icontains=query) |
                    Q(content__icontains=query),
                    status=1
                )[:10]
                results['news'] = NewsSerializer(news, many=True).data
            
            if content_type in ['all', 'events']:
                events = Event.objects.filter(
                    Q(title__icontains=query) |
                    Q(content__icontains=query),
                    status=1
                )[:10]
                results['events'] = EventSerializer(events, many=True).data
            
            if content_type in ['all', 'resources']:
                resources = Resource.objects.filter(
                    Q(title__icontains=query) |
                    Q(content__icontains=query),
                    status=1
                )[:10]
                results['resources'] = ResourceSerializer(resources, many=True).data
            
            return Response({
                'query': query,
                'results': results,
                'total_results': sum(len(v) for v in results.values())
            })
            
        except Exception as e:
            logger.error(f"Public search error: {str(e)}")
            return Response(
                {'error': 'Search failed'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class FeaturedContentView(APIView):
    """
    Get featured content for homepage
    """
    permission_classes = [permissions.AllowAny]
    
    def get(self, request):
        try:
            # Get recent published content
            recent_blog_posts = BlogPost.objects.filter(status=1).order_by('-created')[:5]
            recent_news = News.objects.filter(status=1).order_by('-created')[:5]
            upcoming_events = Event.objects.filter(status=1).order_by('-created')[:5]
            featured_organizations = Organization.objects.filter(
                status=1, is_verified=True
            ).order_by('-created')[:6]
            
            return Response({
                'blog_posts': BlogPostSerializer(recent_blog_posts, many=True).data,
                'news': NewsSerializer(recent_news, many=True).data,
                'events': EventSerializer(upcoming_events, many=True).data,
                'organizations': OrganizationSerializer(featured_organizations, many=True).data,
            })
            
        except Exception as e:
            logger.error(f"Featured content error: {str(e)}")
            return Response(
                {'error': 'Failed to fetch featured content'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class UserProfileView(APIView):
    """
    Enhanced user profile management
    """
    permission_classes = [IsActiveUser]
    
    def get(self, request, user_id=None):
        try:
            if user_id:
                # Get specific user profile (public view)
                try:
                    user = User.objects.get(id=user_id, status=1, is_email_verified=True)
                except User.DoesNotExist:
                    return Response(
                        {'error': 'User not found'},
                        status=status.HTTP_404_NOT_FOUND
                    )
            else:
                # Get current user's profile
                user = request.user
            
            # Get user's organizations
            user_organizations = OrganizationUser.objects.filter(
                user=user, status=1
            ).select_related('organization')
            
            # Get user's recent activity (comments, etc.)
            recent_comments = {
                'blog_comments': BlogPostComment.objects.filter(
                    user=user
                ).order_by('-created')[:5],
                'news_comments': NewsComment.objects.filter(
                    user=user
                ).order_by('-created')[:5],
                'event_comments': EventComment.objects.filter(
                    user=user
                ).order_by('-created')[:5],
            }
            
            profile_data = UserSerializer(user).data
            profile_data['organizations'] = [
                {
                    'id': ou.organization.id,
                    'name': ou.organization.name,
                    'role': ou.role,
                    'joined_date': ou.created
                } for ou in user_organizations
            ]
            profile_data['activity_summary'] = {
                'total_comments': sum(len(comments) for comments in recent_comments.values()),
                'organizations_count': len(user_organizations),
            }
            
            return Response(profile_data)
            
        except Exception as e:
            logger.error(f"User profile error: {str(e)}")
            return Response(
                {'error': 'Failed to fetch user profile'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class OrganizationDirectoryView(APIView):
    """
    Organization directory with filtering and search
    """
    permission_classes = [permissions.AllowAny]
    
    def get(self, request):
        try:
            # Base queryset - only verified organizations for public
            queryset = Organization.objects.filter(status=1, is_verified=True)
            
            # Apply filters
            organization_type = request.query_params.get('type')
            country = request.query_params.get('country')
            category = request.query_params.get('category')
            search = request.query_params.get('search')
            
            if organization_type:
                queryset = queryset.filter(organization_type_id=organization_type)
            
            if country:
                queryset = queryset.filter(country_id=country)
            
            if category:
                queryset = queryset.filter(category_id=category)
            
            if search:
                queryset = queryset.filter(
                    Q(name__icontains=search) |
                    Q(about__icontains=search) |
                    Q(address__icontains=search)
                )
            
            # Pagination
            paginator = PageNumberPagination()
            paginator.page_size = 12
            page = paginator.paginate_queryset(queryset, request)
            
            serializer = OrganizationSerializer(page, many=True)
            
            return paginator.get_paginated_response(serializer.data)
            
        except Exception as e:
            logger.error(f"Organization directory error: {str(e)}")
            return Response(
                {'error': 'Failed to fetch organization directory'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class ContentFeedView(APIView):
    """
    Personalized content feed for authenticated users
    """
    permission_classes = [IsActiveUser]
    
    def get(self, request):
        try:
            # Get user's organization memberships
            user_organizations = OrganizationUser.objects.filter(
                user=request.user, status=1
            ).values_list('organization_id', flat=True)
            
            # Get content from user's organizations and general content
            blog_posts = BlogPost.objects.filter(
                Q(organization_id__in=user_organizations) | Q(organization__isnull=True),
                status=1
            ).order_by('-created')[:10]
            
            news = News.objects.filter(
                Q(organization_id__in=user_organizations) | Q(organization__isnull=True),
                status=1
            ).order_by('-created')[:10]
            
            events = Event.objects.filter(
                Q(organization_id__in=user_organizations) | Q(organization__isnull=True),
                status=1
            ).order_by('-created')[:10]
            
            resources = Resource.objects.filter(
                Q(organization_id__in=user_organizations) | Q(organization__isnull=True),
                status=1
            ).order_by('-created')[:10]
            
            return Response({
                'blog_posts': BlogPostSerializer(blog_posts, many=True).data,
                'news': NewsSerializer(news, many=True).data,
                'events': EventSerializer(events, many=True).data,
                'resources': ResourceSerializer(resources, many=True).data,
                'personalized': len(user_organizations) > 0
            })
            
        except Exception as e:
            logger.error(f"Content feed error: {str(e)}")
            return Response(
                {'error': 'Failed to fetch content feed'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class OrganizationMembershipView(APIView):
    """
    Manage organization memberships
    """
    permission_classes = [IsVerifiedUser]
    
    def post(self, request):
        action = request.data.get('action')
        organization_id = request.data.get('organization_id')
        
        if not action or not organization_id:
            return Response(
                {'error': 'Action and organization_id are required'},
                status=status.HTTP_400_BAD_REQUEST
            )
        
        try:
            organization = Organization.objects.get(
                id=organization_id, status=1, is_verified=True
            )
        except Organization.DoesNotExist:
            return Response(
                {'error': 'Organization not found'},
                status=status.HTTP_404_NOT_FOUND
            )
        
        try:
            if action == 'join':
                # Check if already a member
                if OrganizationUser.objects.filter(
                    organization=organization, user=request.user
                ).exists():
                    return Response(
                        {'error': 'Already a member of this organization'},
                        status=status.HTTP_400_BAD_REQUEST
                    )
                
                # Create membership
                OrganizationUser.objects.create(
                    organization=organization,
                    user=request.user,
                    role='member',
                    status=1,
                    created=timezone.now(),
                    modified=timezone.now()
                )
                
                message = f'Successfully joined {organization.name}'
                
            elif action == 'leave':
                # Remove membership
                membership = OrganizationUser.objects.filter(
                    organization=organization, user=request.user
                ).first()
                
                if not membership:
                    return Response(
                        {'error': 'Not a member of this organization'},
                        status=status.HTTP_400_BAD_REQUEST
                    )
                
                membership.delete()
                message = f'Successfully left {organization.name}'
                
            else:
                return Response(
                    {'error': 'Invalid action'},
                    status=status.HTTP_400_BAD_REQUEST
                )
            
            return Response({'message': message})
            
        except Exception as e:
            logger.error(f"Organization membership error: {str(e)}")
            return Response(
                {'error': 'Membership operation failed'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class PublicStatsView(APIView):
    """
    Public statistics about the platform
    """
    permission_classes = [permissions.AllowAny]
    
    def get(self, request):
        try:
            stats = {
                'users': {
                    'total_verified': User.objects.filter(
                        status=1, is_email_verified=True
                    ).count(),
                    'with_volunteering_experience': User.objects.filter(
                        status=1, is_email_verified=True, has_volunteering_experience=True
                    ).count(),
                },
                'organizations': {
                    'total_verified': Organization.objects.filter(
                        status=1, is_verified=True
                    ).count(),
                    'by_type': Organization.objects.filter(
                        status=1, is_verified=True
                    ).values('organization_type__name').annotate(
                        count=Count('organization_type')
                    ),
                },
                'content': {
                    'blog_posts': BlogPost.objects.filter(status=1).count(),
                    'news_articles': News.objects.filter(status=1).count(),
                    'events': Event.objects.filter(status=1).count(),
                    'resources': Resource.objects.filter(status=1).count(),
                },
                'engagement': {
                    'total_memberships': OrganizationUser.objects.filter(status=1).count(),
                    'total_comments': (
                        BlogPostComment.objects.count() +
                        NewsComment.objects.count() +
                        EventComment.objects.count()
                    ),
                }
            }
            
            return Response(stats)
            
        except Exception as e:
            logger.error(f"Public stats error: {str(e)}")
            return Response(
                {'error': 'Failed to fetch statistics'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


@api_view(['GET'])
@permission_classes([permissions.AllowAny])
def health_check(request):
    """
    Public API health check endpoint
    """
    try:
        # Basic database connectivity check
        org_count = Organization.objects.filter(status=1, is_verified=True).count()
        content_count = (
            BlogPost.objects.filter(status=1).count() +
            News.objects.filter(status=1).count() +
            Event.objects.filter(status=1).count() +
            Resource.objects.filter(status=1).count()
        )
        
        return Response({
            'status': 'healthy',
            'timestamp': timezone.now().isoformat(),
            'database': 'connected',
            'stats': {
                'verified_organizations': org_count,
                'published_content': content_count
            }
        })
        
    except Exception as e:
        logger.error(f"Health check error: {str(e)}")
        return Response({
            'status': 'unhealthy',
            'timestamp': timezone.now().isoformat(),
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)