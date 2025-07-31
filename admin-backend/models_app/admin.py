from django.contrib import admin
from django.contrib.auth.admin import UserAdmin as BaseUserAdmin
from django.utils.html import format_html
from django.urls import reverse
from django.utils.safestring import mark_safe
from django.db import models
from django.forms import Textarea
from django.utils import timezone
from django import forms
from .models import (
    Admin, User, Organization, BlogPost, News, Event, Resource,
    ActivityLog, AdminActivityLog, Country, City, Region,
    OrganizationType, CategoryOfOrganization, InstitutionType,
    PublishingCategory, Tag, CategoryOfResource, ResourceType,
    BlogCategory, BlogPostTag, BlogPostComment, NewsCategory,
    NewsTag, NewsComment, EventComment, ResourceCategory,
    OrganizationUser, OrganizationOffice, I18n
)


# Custom Admin Site Configuration
class AdminSiteConfig(admin.AdminSite):
    site_header = "AU-VLP Admin Portal"
    site_title = "AU-VLP Admin"
    index_title = "African Union Youth Leadership Program Administration"
    
    def has_permission(self, request):
        """
        Check if the user has permission to access the admin site.
        Only allow Admin users with proper roles.
        """
        return (
            request.user.is_active and 
            hasattr(request.user, 'role') and 
            request.user.role in ['super_admin', 'admin']
        )


# Replace the default admin site
admin_site = AdminSiteConfig(name='admin')


# Mixins for common functionality
class ReadOnlyAdminMixin:
    """Mixin to make certain fields read-only"""
    def get_readonly_fields(self, request, obj=None):
        readonly_fields = list(super().get_readonly_fields(request, obj))
        if obj:  # Editing existing object
            readonly_fields.extend(['created', 'modified'])
        return readonly_fields


class RoleBasedPermissionMixin:
    """Mixin to handle role-based permissions"""
    def has_change_permission(self, request, obj=None):
        if not super().has_change_permission(request, obj):
            return False
        
        # Super admins can edit everything
        if hasattr(request.user, 'role') and request.user.role == 'super_admin':
            return True
        
        # Regular admins have limited permissions
        if hasattr(request.user, 'role') and request.user.role == 'admin':
            # Define specific permissions for regular admins
            return True
        
        return False
    
    def has_delete_permission(self, request, obj=None):
        if not super().has_delete_permission(request, obj):
            return False
        
        # Only super admins can delete
        return hasattr(request.user, 'role') and request.user.role == 'super_admin'


# Admin Model Configurations
@admin.register(Admin, site=admin_site)
class AdminAdmin(RoleBasedPermissionMixin, admin.ModelAdmin):
    list_display = ('email', 'name', 'role', 'status_display', 'created', 'modified')
    list_filter = ('role', 'status', 'created')
    search_fields = ('email', 'name')
    ordering = ('-created',)
    
    fieldsets = (
        (None, {'fields': ('email', 'password')}),
        ('Personal info', {'fields': ('name',)}),
        ('Permissions', {'fields': ('role', 'status')}),
        ('Important dates', {'fields': ('created', 'modified')}),
    )
    
    add_fieldsets = (
        (None, {
            'classes': ('wide',),
            'fields': ('email', 'name', 'password1', 'password2', 'role', 'status'),
        }),
    )
    
    readonly_fields = ('created', 'modified')
    
    def status_display(self, obj):
        if obj.status == 1:
            return format_html('<span style="color: green;">Active</span>')
        else:
            return format_html('<span style="color: red;">Inactive</span>')
    status_display.short_description = 'Status'
    
    def get_queryset(self, request):
        qs = super().get_queryset(request)
        # Super admins see all, regular admins see only themselves
        if hasattr(request.user, 'role') and request.user.role == 'admin':
            qs = qs.filter(id=request.user.id)
        return qs
    
    def save_model(self, request, obj, form, change):
        if not change:  # Creating new admin
            if 'password1' in form.cleaned_data:
                obj.set_password(form.cleaned_data['password1'])
            obj.created = timezone.now()
        obj.modified = timezone.now()
        super().save_model(request, obj, form, change)
    
    def get_form(self, request, obj=None, **kwargs):
        from django import forms
        form = super().get_form(request, obj, **kwargs)
        if not obj:  # Adding new admin
            form.base_fields['password1'] = forms.CharField(
                label='Password',
                widget=forms.PasswordInput,
                help_text='Enter a password for the new admin.'
            )
            form.base_fields['password2'] = forms.CharField(
                label='Password confirmation',
                widget=forms.PasswordInput,
                help_text='Enter the same password as before, for verification.'
            )
        return form


@admin.register(User, site=admin_site)
class UserAdmin(ReadOnlyAdminMixin, RoleBasedPermissionMixin, admin.ModelAdmin):
    list_display = ('full_name', 'email', 'resident_country', 'city', 'status_display', 'is_email_verified', 'created')
    list_filter = ('status', 'is_email_verified', 'gender', 'marital_status', 'resident_country', 'created')
    search_fields = ('first_name', 'last_name', 'email', 'phone_number')
    ordering = ('-created',)
    
    fieldsets = (
        ('Basic Information', {
            'fields': ('first_name', 'last_name', 'email', 'phone_number', 'short_profile')
        }),
        ('Location', {
            'fields': ('resident_country', 'city', 'current_address')
        }),
        ('Personal Details', {
            'fields': ('gender', 'date_of_birth', 'place_of_birth', 'marital_status')
        }),
        ('Nationality', {
            'fields': ('nationality_at_birth', 'current_nationality')
        }),
        ('Volunteering Experience', {
            'fields': ('has_volunteering_experience', 'volunteered_program', 'year_of_service', 'country_served_in', 'experience_rating')
        }),
        ('Account Status', {
            'fields': ('status', 'is_email_verified', 'registration_status', 'availability')
        }),
        ('System Fields', {
            'fields': ('profile_image', 'language', 'preferred_language', 'created', 'modified'),
            'classes': ('collapse',)
        }),
    )
    
    readonly_fields = ('created', 'modified', 'token', 'token_expires')
    
    def full_name(self, obj):
        return f"{obj.first_name} {obj.last_name}".strip() or obj.email or f"User {obj.id}"
    full_name.short_description = 'Name'
    
    def status_display(self, obj):
        if obj.status == 1:
            return format_html('<span style="color: green;">Active</span>')
        else:
            return format_html('<span style="color: red;">Inactive</span>')
    status_display.short_description = 'Status'


@admin.register(Organization, site=admin_site)
class OrganizationAdmin(ReadOnlyAdminMixin, RoleBasedPermissionMixin, admin.ModelAdmin):
    list_display = ('name', 'organization_type', 'country', 'city', 'is_verified', 'status_display', 'created')
    list_filter = ('organization_type', 'category', 'institution_type', 'country', 'is_verified', 'status', 'created')
    search_fields = ('name', 'email', 'phone_number', 'website')
    ordering = ('-created',)
    
    fieldsets = (
        ('Basic Information', {
            'fields': ('name', 'about', 'organization_type', 'category', 'institution_type')
        }),
        ('Location', {
            'fields': ('country', 'city', 'address', 'lat', 'lng')
        }),
        ('Contact Information', {
            'fields': ('email', 'phone_number', 'website')
        }),
        ('Social Media', {
            'fields': ('facebook_url', 'instagram_url', 'twitter_url'),
            'classes': ('collapse',)
        }),
        ('Organization Details', {
            'fields': ('date_of_establishment', 'government_affliliation', 'logo')
        }),
        ('Interest Areas', {
            'fields': (
                'pan_africanism', 'education_skills', 'health_wellbeing', 'no_poverty',
                'agriculture_rural', 'democratic_values', 'environmental_sustainability',
                'infrastructure_development', 'peace_security', 'culture', 'gender_inequality',
                'youth_empowerment', 'reduced_inequality', 'sustainable_city', 'responsible_consumption'
            ),
            'classes': ('collapse',)
        }),
        ('Status', {
            'fields': ('status', 'is_verified', 'user')
        }),
        ('System Fields', {
            'fields': ('created', 'modified'),
            'classes': ('collapse',)
        }),
    )
    
    def status_display(self, obj):
        if obj.status == 1:
            return format_html('<span style="color: green;">Active</span>')
        else:
            return format_html('<span style="color: red;">Inactive</span>')
    status_display.short_description = 'Status'


@admin.register(BlogPost, site=admin_site)
class BlogPostAdmin(ReadOnlyAdminMixin, RoleBasedPermissionMixin, admin.ModelAdmin):
    list_display = ('title', 'slug', 'status_display', 'region', 'created', 'modified')
    list_filter = ('status', 'region', 'created')
    search_fields = ('title', 'slug', 'content')
    ordering = ('-created',)
    prepopulated_fields = {'slug': ('title',)}
    
    fieldsets = (
        ('Content', {
            'fields': ('title', 'slug', 'content', 'image')
        }),
        ('Publishing', {
            'fields': ('status', 'region')
        }),
        ('System Fields', {
            'fields': ('created', 'modified'),
            'classes': ('collapse',)
        }),
    )
    
    formfield_overrides = {
        models.TextField: {'widget': Textarea(attrs={'rows': 10, 'cols': 80})},
    }
    
    def status_display(self, obj):
        status_colors = {1: 'green', 2: 'orange', 3: 'red'}
        status_names = {1: 'Published', 2: 'Draft', 3: 'Archived'}
        color = status_colors.get(obj.status, 'black')
        name = status_names.get(obj.status, 'Unknown')
        return format_html(f'<span style="color: {color};">{name}</span>')
    status_display.short_description = 'Status'
    
    def get_form(self, request, obj=None, **kwargs):
        form = super().get_form(request, obj, **kwargs)
        # Add rich text editor class for content field
        if 'content' in form.base_fields:
            form.base_fields['content'].widget.attrs.update({
                'class': 'vLargeTextField',
                'rows': 15
            })
        return form


@admin.register(News, site=admin_site)
class NewsAdmin(ReadOnlyAdminMixin, RoleBasedPermissionMixin, admin.ModelAdmin):
    list_display = ('title', 'organization', 'status_display', 'created', 'modified')
    list_filter = ('status', 'organization', 'created')
    search_fields = ('title', 'slug', 'content')
    ordering = ('-created',)
    prepopulated_fields = {'slug': ('title',)}
    
    fieldsets = (
        ('Content', {
            'fields': ('title', 'slug', 'content', 'image')
        }),
        ('Publishing', {
            'fields': ('status', 'organization', 'region_id')
        }),
        ('System Fields', {
            'fields': ('created', 'modified'),
            'classes': ('collapse',)
        }),
    )
    
    formfield_overrides = {
        models.TextField: {'widget': Textarea(attrs={'rows': 10, 'cols': 80})},
    }
    
    def status_display(self, obj):
        status_colors = {1: 'green', 2: 'orange', 3: 'red'}
        status_names = {1: 'Published', 2: 'Draft', 3: 'Archived'}
        color = status_colors.get(obj.status, 'black')
        name = status_names.get(obj.status, 'Unknown')
        return format_html(f'<span style="color: {color};">{name}</span>')
    status_display.short_description = 'Status'


@admin.register(Event, site=admin_site)
class EventAdmin(ReadOnlyAdminMixin, RoleBasedPermissionMixin, admin.ModelAdmin):
    list_display = ('title', 'organization', 'status_display', 'created', 'modified')
    list_filter = ('status', 'organization', 'created')
    search_fields = ('title', 'slug', 'content')
    ordering = ('-created',)
    prepopulated_fields = {'slug': ('title',)}
    
    fieldsets = (
        ('Content', {
            'fields': ('title', 'slug', 'content', 'image')
        }),
        ('Event Details', {
            'fields': ('organization', 'region_id')
        }),
        ('Publishing', {
            'fields': ('status',)
        }),
        ('System Fields', {
            'fields': ('created', 'modified'),
            'classes': ('collapse',)
        }),
    )
    
    formfield_overrides = {
        models.TextField: {'widget': Textarea(attrs={'rows': 10, 'cols': 80})},
    }
    
    def status_display(self, obj):
        status_colors = {1: 'green', 2: 'orange', 3: 'red'}
        status_names = {1: 'Published', 2: 'Draft', 3: 'Archived'}
        color = status_colors.get(obj.status, 'black')
        name = status_names.get(obj.status, 'Unknown')
        return format_html(f'<span style="color: {color};">{name}</span>')
    status_display.short_description = 'Status'


@admin.register(Resource, site=admin_site)
class ResourceAdmin(ReadOnlyAdminMixin, RoleBasedPermissionMixin, admin.ModelAdmin):
    list_display = ('title', 'organization', 'status_display', 'created', 'modified')
    list_filter = ('status', 'organization', 'created')
    search_fields = ('title', 'slug', 'content')
    ordering = ('-created',)
    prepopulated_fields = {'slug': ('title',)}
    
    fieldsets = (
        ('Content', {
            'fields': ('title', 'slug', 'content', 'image')
        }),
        ('Resource Details', {
            'fields': ('organization', 'region_id')
        }),
        ('Publishing', {
            'fields': ('status',)
        }),
        ('System Fields', {
            'fields': ('created', 'modified'),
            'classes': ('collapse',)
        }),
    )
    
    formfield_overrides = {
        models.TextField: {'widget': Textarea(attrs={'rows': 10, 'cols': 80})},
    }
    
    def status_display(self, obj):
        status_colors = {1: 'green', 2: 'orange', 3: 'red'}
        status_names = {1: 'Published', 2: 'Draft', 3: 'Archived'}
        color = status_colors.get(obj.status, 'black')
        name = status_names.get(obj.status, 'Unknown')
        return format_html(f'<span style="color: {color};">{name}</span>')
    status_display.short_description = 'Status'


@admin.register(ActivityLog, site=admin_site)
class ActivityLogAdmin(admin.ModelAdmin):
    list_display = ('created_at', 'scope_model', 'scope_id', 'level', 'action', 'message_preview')
    list_filter = ('level', 'scope_model', 'action', 'created_at')
    search_fields = ('scope_model', 'scope_id', 'action', 'message')
    ordering = ('-created_at',)
    readonly_fields = ('created_at', 'scope_model', 'scope_id', 'issuer_model', 'issuer_id', 
                      'object_model', 'object_id', 'level', 'action', 'message', 'data')
    
    def message_preview(self, obj):
        if obj.message:
            return obj.message[:100] + '...' if len(obj.message) > 100 else obj.message
        return '-'
    message_preview.short_description = 'Message Preview'
    
    def has_add_permission(self, request):
        return False  # Activity logs should not be manually created
    
    def has_change_permission(self, request, obj=None):
        return False  # Activity logs should not be edited
    
    def has_delete_permission(self, request, obj=None):
        # Only super admins can delete logs
        return hasattr(request.user, 'role') and request.user.role == 'super_admin'


@admin.register(AdminActivityLog, site=admin_site)
class AdminActivityLogAdmin(ActivityLogAdmin):
    pass


# Register supporting models with simpler admin interfaces
@admin.register(Country, site=admin_site)
class CountryAdmin(admin.ModelAdmin):
    list_display = ('nicename', 'name', 'iso', 'iso3', 'phonecode')
    search_fields = ('name', 'nicename', 'iso', 'iso3')
    ordering = ('nicename',)


@admin.register(City, site=admin_site)
class CityAdmin(admin.ModelAdmin):
    list_display = ('name', 'country')
    list_filter = ('country',)
    search_fields = ('name',)
    ordering = ('name',)


@admin.register(Region, site=admin_site)
class RegionAdmin(admin.ModelAdmin):
    list_display = ('name',)
    search_fields = ('name',)
    ordering = ('name',)


@admin.register(OrganizationType, site=admin_site)
class OrganizationTypeAdmin(admin.ModelAdmin):
    list_display = ('name',)
    search_fields = ('name',)
    ordering = ('name',)


@admin.register(CategoryOfOrganization, site=admin_site)
class CategoryOfOrganizationAdmin(admin.ModelAdmin):
    list_display = ('name',)
    search_fields = ('name',)
    ordering = ('name',)


@admin.register(InstitutionType, site=admin_site)
class InstitutionTypeAdmin(admin.ModelAdmin):
    list_display = ('name',)
    search_fields = ('name',)
    ordering = ('name',)


@admin.register(PublishingCategory, site=admin_site)
class PublishingCategoryAdmin(admin.ModelAdmin):
    list_display = ('name',)
    search_fields = ('name',)
    ordering = ('name',)


@admin.register(Tag, site=admin_site)
class TagAdmin(admin.ModelAdmin):
    list_display = ('title',)
    search_fields = ('title',)
    ordering = ('title',)


@admin.register(CategoryOfResource, site=admin_site)
class CategoryOfResourceAdmin(admin.ModelAdmin):
    list_display = ('name',)
    search_fields = ('name',)
    ordering = ('name',)


@admin.register(ResourceType, site=admin_site)
class ResourceTypeAdmin(admin.ModelAdmin):
    list_display = ('name',)
    search_fields = ('name',)
    ordering = ('name',)


# Inline admin classes for related models
class OrganizationUserInline(admin.TabularInline):
    model = OrganizationUser
    extra = 0
    readonly_fields = ('created', 'modified')


class OrganizationOfficeInline(admin.TabularInline):
    model = OrganizationOffice
    extra = 0
    readonly_fields = ('created', 'modified')


class BlogCategoryInline(admin.TabularInline):
    model = BlogCategory
    extra = 0


class BlogPostTagInline(admin.TabularInline):
    model = BlogPostTag
    extra = 0


class BlogPostCommentInline(admin.TabularInline):
    model = BlogPostComment
    extra = 0
    readonly_fields = ('created', 'modified')


class NewsCategoryInline(admin.TabularInline):
    model = NewsCategory
    extra = 0


class NewsTagInline(admin.TabularInline):
    model = NewsTag
    extra = 0


class NewsCommentInline(admin.TabularInline):
    model = NewsComment
    extra = 0
    readonly_fields = ('created', 'modified')


class EventCommentInline(admin.TabularInline):
    model = EventComment
    extra = 0
    readonly_fields = ('created', 'modified')


class ResourceCategoryInline(admin.TabularInline):
    model = ResourceCategory
    extra = 0


# Add inlines to main admin classes
OrganizationAdmin.inlines = [OrganizationUserInline, OrganizationOfficeInline]
BlogPostAdmin.inlines = [BlogCategoryInline, BlogPostTagInline, BlogPostCommentInline]
NewsAdmin.inlines = [NewsCategoryInline, NewsTagInline, NewsCommentInline]
EventAdmin.inlines = [EventCommentInline]
ResourceAdmin.inlines = [ResourceCategoryInline]


# Register translation model for multilingual support
@admin.register(I18n, site=admin_site)
class I18nAdmin(admin.ModelAdmin):
    list_display = ('locale', 'model', 'foreign_key', 'field', 'content_preview')
    list_filter = ('locale', 'model', 'field')
    search_fields = ('model', 'field', 'content')
    ordering = ('model', 'foreign_key', 'field', 'locale')
    
    def content_preview(self, obj):
        if obj.content:
            return obj.content[:100] + '...' if len(obj.content) > 100 else obj.content
        return '-'
    content_preview.short_description = 'Content Preview'


# Custom admin actions
def make_published(modeladmin, request, queryset):
    queryset.update(status=1)
make_published.short_description = "Mark selected items as published"


def make_draft(modeladmin, request, queryset):
    queryset.update(status=2)
make_draft.short_description = "Mark selected items as draft"


def make_archived(modeladmin, request, queryset):
    queryset.update(status=3)
make_archived.short_description = "Mark selected items as archived"


# Add actions to content admin classes
BlogPostAdmin.actions = [make_published, make_draft, make_archived]
NewsAdmin.actions = [make_published, make_draft, make_archived]
EventAdmin.actions = [make_published, make_draft, make_archived]
ResourceAdmin.actions = [make_published, make_draft, make_archived]