from rest_framework import permissions
from rest_framework.permissions import BasePermission
from .models import Admin, User, Organization, OrganizationUser


class IsOwnerOrReadOnly(BasePermission):
    """
    Custom permission to only allow owners of an object to edit it.
    """
    def has_object_permission(self, request, view, obj):
        # Read permissions are allowed to any request,
        # so we'll always allow GET, HEAD or OPTIONS requests.
        if request.method in permissions.SAFE_METHODS:
            return True

        # Write permissions are only allowed to the owner of the object.
        if hasattr(obj, 'user'):
            return obj.user == request.user
        
        # For User objects, check if it's the same user
        if isinstance(obj, User):
            return obj == request.user
        
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


class IsOrganizationMember(BasePermission):
    """
    Custom permission for organization members.
    """
    def has_permission(self, request, view):
        return request.user and request.user.is_authenticated
    
    def has_object_permission(self, request, view, obj):
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


class CanJoinOrganization(BasePermission):
    """
    Custom permission for joining organizations.
    """
    def has_permission(self, request, view):
        return (request.user and 
                request.user.is_authenticated and 
                hasattr(request.user, 'is_email_verified') and
                request.user.is_email_verified and
                hasattr(request.user, 'status') and
                request.user.status == 1)
    
    def has_object_permission(self, request, view, obj):
        # Cannot join if already a member
        if isinstance(obj, Organization):
            return not OrganizationUser.objects.filter(
                organization=obj,
                user=request.user
            ).exists()
        
        return True


class CanComment(BasePermission):
    """
    Custom permission for commenting on content.
    """
    def has_permission(self, request, view):
        return (request.user and 
                request.user.is_authenticated and 
                hasattr(request.user, 'is_email_verified') and
                request.user.is_email_verified and
                hasattr(request.user, 'status') and
                request.user.status == 1)


class IsCommentOwner(BasePermission):
    """
    Custom permission for comment owners.
    """
    def has_object_permission(self, request, view, obj):
        # Read permissions for everyone
        if request.method in permissions.SAFE_METHODS:
            return True
        
        # Write permissions only for comment owner
        return hasattr(obj, 'user') and obj.user == request.user


class CanCreateOrganization(BasePermission):
    """
    Custom permission for creating organizations.
    """
    def has_permission(self, request, view):
        if request.method != 'POST':
            return True
        
        return (request.user and 
                request.user.is_authenticated and 
                hasattr(request.user, 'is_email_verified') and
                request.user.is_email_verified and
                hasattr(request.user, 'status') and
                request.user.status == 1)


class IsPublicOrAuthenticated(BasePermission):
    """
    Custom permission that allows public read access but requires authentication for write operations.
    """
    def has_permission(self, request, view):
        if request.method in permissions.SAFE_METHODS:
            return True
        
        return request.user and request.user.is_authenticated


class IsVerifiedOrganization(BasePermission):
    """
    Custom permission to check if organization is verified for public access.
    """
    def has_object_permission(self, request, view, obj):
        # If user is authenticated, they can see all organizations
        if request.user and request.user.is_authenticated:
            return True
        
        # Public users can only see verified organizations
        if isinstance(obj, Organization):
            return obj.is_verified and obj.status == 1
        
        return True