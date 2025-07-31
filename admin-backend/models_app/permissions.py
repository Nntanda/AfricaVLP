from rest_framework import permissions
from rest_framework.permissions import BasePermission
from .models import Admin, User, Organization, OrganizationUser


class IsSuperAdminOnly(BasePermission):
    """
    Custom permission to only allow super admins.
    """
    def has_permission(self, request, view):
        return (request.user and 
                request.user.is_authenticated and 
                hasattr(request.user, 'role') and 
                request.user.role == 'super_admin')


class IsAdminOrReadOnly(BasePermission):
    """
    Custom permission to only allow admins to edit objects.
    Regular authenticated users can read.
    """
    def has_permission(self, request, view):
        if request.method in permissions.SAFE_METHODS:
            return request.user and request.user.is_authenticated
        return (request.user and 
                hasattr(request.user, 'role') and 
                request.user.role in ['admin', 'super_admin'])


class IsAdminOrOwner(BasePermission):
    """
    Custom permission to allow admins full access or owners to edit their own objects.
    """
    def has_permission(self, request, view):
        return request.user and request.user.is_authenticated
    
    def has_object_permission(self, request, view, obj):
        # Read permissions for authenticated users
        if request.method in permissions.SAFE_METHODS:
            return True
        
        # Admin permissions
        if hasattr(request.user, 'role') and request.user.role in ['admin', 'super_admin']:
            return True
        
        # Owner permissions
        if hasattr(obj, 'user') and obj.user == request.user:
            return True
        
        # For User objects, check if it's the same user
        if isinstance(obj, User) and obj == request.user:
            return True
        
        return False


class IsOrganizationMemberOrAdmin(BasePermission):
    """
    Custom permission for organization-related objects.
    Allows organization members and admins.
    """
    def has_permission(self, request, view):
        return request.user and request.user.is_authenticated
    
    def has_object_permission(self, request, view, obj):
        # Admin permissions
        if hasattr(request.user, 'role') and request.user.role in ['admin', 'super_admin']:
            return True
        
        # Check if user is a member of the organization
        if hasattr(obj, 'organization'):
            organization = obj.organization
        elif isinstance(obj, Organization):
            organization = obj
        else:
            return False
        
        # Check organization membership
        return OrganizationUser.objects.filter(
            organization=organization,
            user=request.user,
            status=1
        ).exists()


class IsContentOwnerOrAdmin(BasePermission):
    """
    Custom permission for content objects (blog posts, news, events, resources).
    Allows content creators, organization members, and admins.
    """
    def has_permission(self, request, view):
        return request.user and request.user.is_authenticated
    
    def has_object_permission(self, request, view, obj):
        # Admin permissions
        if hasattr(request.user, 'role') and request.user.role in ['admin', 'super_admin']:
            return True
        
        # Check if user is associated with the content's organization
        if hasattr(obj, 'organization') and obj.organization:
            return OrganizationUser.objects.filter(
                organization=obj.organization,
                user=request.user,
                status=1
            ).exists()
        
        return False


class IsVerifiedUser(BasePermission):
    """
    Custom permission to only allow verified users.
    """
    def has_permission(self, request, view):
        return (request.user and 
                request.user.is_authenticated and 
                hasattr(request.user, 'is_email_verified') and
                request.user.is_email_verified and
                hasattr(request.user, 'status') and
                request.user.status == 1)


class IsActiveUser(BasePermission):
    """
    Custom permission to only allow active users.
    """
    def has_permission(self, request, view):
        return (request.user and 
                request.user.is_authenticated and
                hasattr(request.user, 'status') and
                request.user.status == 1)


class CanManageUsers(BasePermission):
    """
    Custom permission for user management operations.
    """
    def has_permission(self, request, view):
        return (request.user and 
                request.user.is_authenticated and 
                hasattr(request.user, 'role') and 
                request.user.role in ['admin', 'super_admin'])
    
    def has_object_permission(self, request, view, obj):
        # Super admin can manage all users
        if request.user.role == 'super_admin':
            return True
        
        # Regular admin cannot manage other admins
        if isinstance(obj, Admin):
            return False
        
        return True


class CanPublishContent(BasePermission):
    """
    Custom permission for content publishing operations.
    """
    def has_permission(self, request, view):
        if not (request.user and request.user.is_authenticated):
            return False
        
        # Admins can always publish
        if hasattr(request.user, 'role') and request.user.role in ['admin', 'super_admin']:
            return True
        
        # Verified users can publish content for their organizations
        return (hasattr(request.user, 'is_email_verified') and
                request.user.is_email_verified and
                hasattr(request.user, 'status') and
                request.user.status == 1)
    
    def has_object_permission(self, request, view, obj):
        # Admin permissions
        if hasattr(request.user, 'role') and request.user.role in ['admin', 'super_admin']:
            return True
        
        # Check if user can publish for the content's organization
        if hasattr(obj, 'organization') and obj.organization:
            return OrganizationUser.objects.filter(
                organization=obj.organization,
                user=request.user,
                role__in=['admin', 'editor', 'owner'],
                status=1
            ).exists()
        
        return False