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
    Admin, User, Organization, BlogPost, News, Event, Resource,
    Tag, ActivityLog, AdminActivityLog, OrganizationUser
)
from .serializers import (
    AdminSerializer, UserSerializer, OrganizationSerializer,
    BlogPostSerializer, NewsSerializer, EventSerializer, ResourceSerializer,
    TagSerializer, ActivityLogSerializer
)
from .permissions import IsAdminOrReadOnly, IsSuperAdminOnly, CanManageUsers

logger = logging.getLogger(__name__)


class GlobalSearchView(APIView):
    """
    Global search across multiple content types
    """
    permission_classes = [permissions.IsAuthenticated]
    
    def get(self, request):
        query = request.query_params.get('q', '')
        content_type = request.query_params.get('type', 'all')
        
        if not query:
            return Response({'error': 'Search query is required'}, status=status.HTTP_400_BAD_REQUEST)
        
        results = {}
        
        try:
            if content_type in ['all', 'users']:
                users = User.objects.filter(
                    Q(first_name__icontains=query) |
                    Q(last_name__icontains=query) |
                    Q(email__icontains=query) |
                    Q(short_profile__icontains=query),
                    status=1
                )[:10]
                results['users'] = UserSerializer(users, many=True).data
            
            if content_type in ['all', 'organizations']:
                organizations = Organization.objects.filter(
                    Q(name__icontains=query) |
                    Q(about__icontains=query) |
                    Q(address__icontains=query),
                    status=1
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
            logger.error(f"Global search error: {str(e)}")
            return Response(
                {'error': 'Search failed'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class DashboardStatsView(APIView):
    """
    Dashboard statistics for admin interface
    """
    permission_classes = [IsAdminOrReadOnly]
    
    def get(self, request):
        try:
            # Get date range for recent activity (last 30 days)
            thirty_days_ago = timezone.now() - timedelta(days=30)
            
            stats = {
                'users': {
                    'total': User.objects.filter(status=1).count(),
                    'verified': User.objects.filter(status=1, is_email_verified=True).count(),
                    'recent': User.objects.filter(status=1, created__gte=thirty_days_ago).count(),
                },
                'organizations': {
                    'total': Organization.objects.filter(status=1).count(),
                    'verified': Organization.objects.filter(status=1, is_verified=True).count(),
                    'recent': Organization.objects.filter(status=1, created__gte=thirty_days_ago).count(),
                },
                'content': {
                    'blog_posts': {
                        'total': BlogPost.objects.count(),
                        'published': BlogPost.objects.filter(status=1).count(),
                        'recent': BlogPost.objects.filter(created__gte=thirty_days_ago).count(),
                    },
                    'news': {
                        'total': News.objects.count(),
                        'published': News.objects.filter(status=1).count(),
                        'recent': News.objects.filter(created__gte=thirty_days_ago).count(),
                    },
                    'events': {
                        'total': Event.objects.count(),
                        'published': Event.objects.filter(status=1).count(),
                        'recent': Event.objects.filter(created__gte=thirty_days_ago).count(),
                    },
                    'resources': {
                        'total': Resource.objects.count(),
                        'published': Resource.objects.filter(status=1).count(),
                        'recent': Resource.objects.filter(created__gte=thirty_days_ago).count(),
                    },
                },
                'activity': {
                    'recent_logs': ActivityLog.objects.filter(
                        created_at__gte=thirty_days_ago
                    ).count(),
                    'admin_logs': AdminActivityLog.objects.filter(
                        created_at__gte=thirty_days_ago
                    ).count(),
                }
            }
            
            return Response(stats)
            
        except Exception as e:
            logger.error(f"Dashboard stats error: {str(e)}")
            return Response(
                {'error': 'Failed to fetch dashboard statistics'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class UserManagementView(APIView):
    """
    Advanced user management operations
    """
    permission_classes = [CanManageUsers]
    
    def post(self, request):
        action = request.data.get('action')
        user_ids = request.data.get('user_ids', [])
        
        if not action or not user_ids:
            return Response(
                {'error': 'Action and user_ids are required'},
                status=status.HTTP_400_BAD_REQUEST
            )
        
        try:
            users = User.objects.filter(id__in=user_ids)
            
            if action == 'bulk_verify':
                users.update(is_email_verified=True, modified=timezone.now())
                message = f'Verified {users.count()} users'
                
            elif action == 'bulk_activate':
                users.update(status=1, modified=timezone.now())
                message = f'Activated {users.count()} users'
                
            elif action == 'bulk_deactivate':
                users.update(status=0, modified=timezone.now())
                message = f'Deactivated {users.count()} users'
                
            else:
                return Response(
                    {'error': 'Invalid action'},
                    status=status.HTTP_400_BAD_REQUEST
                )
            
            # Log the bulk action
            AdminActivityLog.objects.create(
                created_at=timezone.now(),
                scope_model='User',
                scope_id='bulk',
                issuer_model='Admin',
                issuer_id=str(request.user.id),
                level='info',
                action=f'bulk_{action}',
                message=f'Admin {request.user.name} performed {action} on {len(user_ids)} users',
                data=f'{{"user_ids": {user_ids}, "action": "{action}"}}'
            )
            
            return Response({'message': message})
            
        except Exception as e:
            logger.error(f"User management error: {str(e)}")
            return Response(
                {'error': 'User management operation failed'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class ContentModerationView(APIView):
    """
    Content moderation operations
    """
    permission_classes = [IsAdminOrReadOnly]
    
    def post(self, request):
        action = request.data.get('action')
        content_type = request.data.get('content_type')
        content_ids = request.data.get('content_ids', [])
        
        if not all([action, content_type, content_ids]):
            return Response(
                {'error': 'Action, content_type, and content_ids are required'},
                status=status.HTTP_400_BAD_REQUEST
            )
        
        try:
            # Map content types to models
            model_map = {
                'blog_posts': BlogPost,
                'news': News,
                'events': Event,
                'resources': Resource,
            }
            
            if content_type not in model_map:
                return Response(
                    {'error': 'Invalid content type'},
                    status=status.HTTP_400_BAD_REQUEST
                )
            
            model = model_map[content_type]
            content_objects = model.objects.filter(id__in=content_ids)
            
            if action == 'bulk_publish':
                content_objects.update(status=1, modified=timezone.now())
                message = f'Published {content_objects.count()} {content_type}'
                
            elif action == 'bulk_unpublish':
                content_objects.update(status=2, modified=timezone.now())
                message = f'Unpublished {content_objects.count()} {content_type}'
                
            elif action == 'bulk_archive':
                content_objects.update(status=3, modified=timezone.now())
                message = f'Archived {content_objects.count()} {content_type}'
                
            else:
                return Response(
                    {'error': 'Invalid action'},
                    status=status.HTTP_400_BAD_REQUEST
                )
            
            # Log the moderation action
            AdminActivityLog.objects.create(
                created_at=timezone.now(),
                scope_model=model.__name__,
                scope_id='bulk',
                issuer_model='Admin',
                issuer_id=str(request.user.id),
                level='info',
                action=f'bulk_{action}',
                message=f'Admin {request.user.name} performed {action} on {len(content_ids)} {content_type}',
                data=f'{{"content_ids": {content_ids}, "content_type": "{content_type}", "action": "{action}"}}'
            )
            
            return Response({'message': message})
            
        except Exception as e:
            logger.error(f"Content moderation error: {str(e)}")
            return Response(
                {'error': 'Content moderation operation failed'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class OrganizationAnalyticsView(APIView):
    """
    Organization analytics and insights
    """
    permission_classes = [IsAdminOrReadOnly]
    
    def get(self, request, organization_id=None):
        try:
            if organization_id:
                # Analytics for specific organization
                try:
                    organization = Organization.objects.get(id=organization_id)
                except Organization.DoesNotExist:
                    return Response(
                        {'error': 'Organization not found'},
                        status=status.HTTP_404_NOT_FOUND
                    )
                
                analytics = {
                    'organization': OrganizationSerializer(organization).data,
                    'members': {
                        'total': OrganizationUser.objects.filter(
                            organization=organization, status=1
                        ).count(),
                        'by_role': OrganizationUser.objects.filter(
                            organization=organization, status=1
                        ).values('role').annotate(count=Count('role'))
                    },
                    'content': {
                        'blog_posts': BlogPost.objects.filter(
                            organization=organization
                        ).values('status').annotate(count=Count('status')),
                        'news': News.objects.filter(
                            organization=organization
                        ).values('status').annotate(count=Count('status')),
                        'events': Event.objects.filter(
                            organization=organization
                        ).values('status').annotate(count=Count('status')),
                        'resources': Resource.objects.filter(
                            organization=organization
                        ).values('status').annotate(count=Count('status')),
                    }
                }
                
            else:
                # Overall organization analytics
                analytics = {
                    'overview': {
                        'total_organizations': Organization.objects.filter(status=1).count(),
                        'verified_organizations': Organization.objects.filter(
                            status=1, is_verified=True
                        ).count(),
                        'organizations_by_type': Organization.objects.filter(
                            status=1
                        ).values('organization_type__name').annotate(
                            count=Count('organization_type')
                        ),
                        'organizations_by_country': Organization.objects.filter(
                            status=1
                        ).values('country__nicename').annotate(
                            count=Count('country')
                        )[:10],  # Top 10 countries
                    },
                    'membership': {
                        'total_memberships': OrganizationUser.objects.filter(status=1).count(),
                        'average_members_per_org': OrganizationUser.objects.filter(
                            status=1
                        ).values('organization').annotate(
                            member_count=Count('user')
                        ).aggregate(avg_members=Avg('member_count'))['avg_members'] or 0,
                    }
                }
            
            return Response(analytics)
            
        except Exception as e:
            logger.error(f"Organization analytics error: {str(e)}")
            return Response(
                {'error': 'Failed to fetch organization analytics'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


@api_view(['GET'])
@permission_classes([IsAdminOrReadOnly])
def export_data(request):
    """
    Export data in various formats
    """
    data_type = request.query_params.get('type')
    format_type = request.query_params.get('format', 'json')
    
    if not data_type:
        return Response(
            {'error': 'Data type is required'},
            status=status.HTTP_400_BAD_REQUEST
        )
    
    try:
        if data_type == 'users':
            users = User.objects.filter(status=1)
            data = UserSerializer(users, many=True).data
            
        elif data_type == 'organizations':
            organizations = Organization.objects.filter(status=1)
            data = OrganizationSerializer(organizations, many=True).data
            
        elif data_type == 'activity_logs':
            logs = ActivityLog.objects.all()[:1000]  # Limit to recent 1000
            data = ActivityLogSerializer(logs, many=True).data
            
        else:
            return Response(
                {'error': 'Invalid data type'},
                status=status.HTTP_400_BAD_REQUEST
            )
        
        # Log the export action
        AdminActivityLog.objects.create(
            created_at=timezone.now(),
            scope_model='Export',
            scope_id=data_type,
            issuer_model='Admin',
            issuer_id=str(request.user.id),
            level='info',
            action='data_export',
            message=f'Admin {request.user.name} exported {data_type} data',
            data=f'{{"data_type": "{data_type}", "format": "{format_type}", "count": {len(data)}}}'
        )
        
        return Response({
            'data_type': data_type,
            'format': format_type,
            'count': len(data),
            'data': data,
            'exported_at': timezone.now().isoformat()
        })
        
    except Exception as e:
        logger.error(f"Data export error: {str(e)}")
        return Response(
            {'error': 'Data export failed'},
            status=status.HTTP_500_INTERNAL_SERVER_ERROR
        )


@api_view(['GET'])
@permission_classes([permissions.IsAuthenticated])
def health_check(request):
    """
    API health check endpoint
    """
    try:
        # Basic database connectivity check
        user_count = User.objects.count()
        org_count = Organization.objects.count()
        
        return Response({
            'status': 'healthy',
            'timestamp': timezone.now().isoformat(),
            'database': 'connected',
            'stats': {
                'users': user_count,
                'organizations': org_count
            }
        })
        
    except Exception as e:
        logger.error(f"Health check error: {str(e)}")
        return Response({
            'status': 'unhealthy',
            'timestamp': timezone.now().isoformat(),
            'error': str(e)
        }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)