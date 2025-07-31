from rest_framework import serializers
from django.contrib.auth.hashers import make_password
from django.utils import timezone
from .models import (
    Admin, User, Country, City, Region, Organization, OrganizationType,
    CategoryOfOrganization, InstitutionType, BlogPost, News, Event, Resource,
    Tag, PublishingCategory, BlogCategory, BlogPostTag, BlogPostComment,
    NewsCategory, NewsTag, NewsComment, EventComment, CategoryOfResource,
    ResourceType, ResourceCategory, ActivityLog, AdminActivityLog,
    OrganizationUser, OrganizationOffice, I18n
)


class CountrySerializer(serializers.ModelSerializer):
    """Serializer for Country model"""
    
    class Meta:
        model = Country
        fields = ['id', 'iso', 'name', 'nicename', 'iso3', 'numcode', 'phonecode']
        read_only_fields = ['id']


class CitySerializer(serializers.ModelSerializer):
    """Serializer for City model"""
    country_name = serializers.CharField(source='country.nicename', read_only=True)
    
    class Meta:
        model = City
        fields = ['id', 'name', 'country', 'country_name']
        read_only_fields = ['id']


class RegionSerializer(serializers.ModelSerializer):
    """Serializer for Region model"""
    
    class Meta:
        model = Region
        fields = ['id', 'name']
        read_only_fields = ['id']


class OrganizationTypeSerializer(serializers.ModelSerializer):
    """Serializer for OrganizationType model"""
    
    class Meta:
        model = OrganizationType
        fields = ['id', 'name']
        read_only_fields = ['id']


class CategoryOfOrganizationSerializer(serializers.ModelSerializer):
    """Serializer for CategoryOfOrganization model"""
    
    class Meta:
        model = CategoryOfOrganization
        fields = ['id', 'name']
        read_only_fields = ['id']


class InstitutionTypeSerializer(serializers.ModelSerializer):
    """Serializer for InstitutionType model"""
    
    class Meta:
        model = InstitutionType
        fields = ['id', 'name']
        read_only_fields = ['id']


class TagSerializer(serializers.ModelSerializer):
    """Serializer for Tag model"""
    
    class Meta:
        model = Tag
        fields = ['id', 'title']
        read_only_fields = ['id']


class PublishingCategorySerializer(serializers.ModelSerializer):
    """Serializer for PublishingCategory model"""
    
    class Meta:
        model = PublishingCategory
        fields = ['id', 'name']
        read_only_fields = ['id']


class CategoryOfResourceSerializer(serializers.ModelSerializer):
    """Serializer for CategoryOfResource model"""
    
    class Meta:
        model = CategoryOfResource
        fields = ['id', 'name']
        read_only_fields = ['id']


class ResourceTypeSerializer(serializers.ModelSerializer):
    """Serializer for ResourceType model"""
    
    class Meta:
        model = ResourceType
        fields = ['id', 'name']
        read_only_fields = ['id']


class AdminSerializer(serializers.ModelSerializer):
    """Serializer for Admin model with role-based field filtering"""
    
    class Meta:
        model = Admin
        fields = [
            'id', 'email', 'name', 'role', 'status', 
            'created', 'modified'
        ]
        read_only_fields = ['id', 'created', 'modified']
        extra_kwargs = {
            'password': {'write_only': True}
        }
    
    def create(self, validated_data):
        """Create admin with hashed password"""
        if 'password' in validated_data:
            validated_data['password'] = make_password(validated_data['password'])
        validated_data['created'] = timezone.now()
        validated_data['modified'] = timezone.now()
        return super().create(validated_data)
    
    def update(self, instance, validated_data):
        """Update admin with hashed password if provided"""
        if 'password' in validated_data:
            validated_data['password'] = make_password(validated_data['password'])
        validated_data['modified'] = timezone.now()
        return super().update(instance, validated_data)


class UserSerializer(serializers.ModelSerializer):
    """Serializer for User model with nested organization data"""
    full_name = serializers.SerializerMethodField()
    resident_country_name = serializers.CharField(source='resident_country.nicename', read_only=True)
    city_name = serializers.CharField(source='city.name', read_only=True)
    nationality_at_birth_name = serializers.CharField(source='nationality_at_birth.nicename', read_only=True)
    current_nationality_name = serializers.CharField(source='current_nationality.nicename', read_only=True)
    country_served_in_name = serializers.CharField(source='country_served_in.nicename', read_only=True)
    
    class Meta:
        model = User
        fields = [
            'id', 'first_name', 'last_name', 'full_name', 'email',
            'resident_country', 'resident_country_name', 'city', 'city_name',
            'phone_number', 'short_profile', 'language', 'profile_image',
            'gender', 'date_of_birth', 'place_of_birth',
            'nationality_at_birth', 'nationality_at_birth_name',
            'current_nationality', 'current_nationality_name',
            'marital_status', 'current_address', 'availability',
            'is_email_verified', 'preferred_language', 'registration_status',
            'status', 'has_volunteering_experience', 'volunteered_program',
            'year_of_service', 'country_served_in', 'country_served_in_name',
            'experience_rating', 'created', 'modified'
        ]
        read_only_fields = [
            'id', 'full_name', 'is_email_verified', 'experience_rating',
            'created', 'modified', 'resident_country_name', 'city_name',
            'nationality_at_birth_name', 'current_nationality_name',
            'country_served_in_name'
        ]
        extra_kwargs = {
            'password': {'write_only': True}
        }
    
    def get_full_name(self, obj):
        return f"{obj.first_name} {obj.last_name}".strip()
    
    def create(self, validated_data):
        """Create user with hashed password"""
        if 'password' in validated_data:
            validated_data['password'] = make_password(validated_data['password'])
        validated_data['created'] = timezone.now()
        validated_data['modified'] = timezone.now()
        return super().create(validated_data)
    
    def update(self, instance, validated_data):
        """Update user with hashed password if provided"""
        if 'password' in validated_data:
            validated_data['password'] = make_password(validated_data['password'])
        validated_data['modified'] = timezone.now()
        return super().update(instance, validated_data)


class OrganizationOfficeSerializer(serializers.ModelSerializer):
    """Serializer for OrganizationOffice model"""
    country_name = serializers.CharField(source='country.nicename', read_only=True)
    city_name = serializers.CharField(source='city.name', read_only=True)
    
    class Meta:
        model = OrganizationOffice
        fields = [
            'id', 'organization', 'country', 'country_name', 
            'city', 'city_name', 'address', 'phone_number', 
            'email', 'created', 'modified'
        ]
        read_only_fields = ['id', 'created', 'modified', 'country_name', 'city_name']


class OrganizationUserSerializer(serializers.ModelSerializer):
    """Serializer for OrganizationUser model"""
    user_name = serializers.CharField(source='user.first_name', read_only=True)
    user_email = serializers.CharField(source='user.email', read_only=True)
    
    class Meta:
        model = OrganizationUser
        fields = [
            'id', 'organization', 'user', 'user_name', 'user_email',
            'role', 'status', 'created', 'modified'
        ]
        read_only_fields = ['id', 'created', 'modified', 'user_name', 'user_email']


class OrganizationSerializer(serializers.ModelSerializer):
    """Serializer for Organization model with nested office and user data"""
    organization_type_name = serializers.CharField(source='organization_type.name', read_only=True)
    country_name = serializers.CharField(source='country.nicename', read_only=True)
    city_name = serializers.CharField(source='city.name', read_only=True)
    institution_type_name = serializers.CharField(source='institution_type.name', read_only=True)
    category_name = serializers.CharField(source='category.name', read_only=True)
    user_name = serializers.CharField(source='user.first_name', read_only=True)
    offices = OrganizationOfficeSerializer(many=True, read_only=True, source='organizationoffice_set')
    members = OrganizationUserSerializer(many=True, read_only=True, source='organizationuser_set')
    
    class Meta:
        model = Organization
        fields = [
            'id', 'organization_type', 'organization_type_name', 'name', 'about',
            'country', 'country_name', 'city', 'city_name', 'logo',
            'institution_type', 'institution_type_name', 'government_affliliation',
            'category', 'category_name', 'date_of_establishment', 'address',
            'lat', 'lng', 'email', 'phone_number', 'website',
            'facebook_url', 'instagram_url', 'twitter_url',
            'user', 'user_name', 'status', 'is_verified',
            'created', 'modified', 'offices', 'members',
            # Interest areas
            'pan_africanism', 'education_skills', 'health_wellbeing',
            'no_poverty', 'agriculture_rural', 'democratic_values',
            'environmental_sustainability', 'infrastructure_development',
            'peace_security', 'culture', 'gender_inequality',
            'youth_empowerment', 'reduced_inequality', 'sustainable_city',
            'responsible_consumption'
        ]
        read_only_fields = [
            'id', 'created', 'modified', 'organization_type_name',
            'country_name', 'city_name', 'institution_type_name',
            'category_name', 'user_name', 'offices', 'members'
        ]
    
    def create(self, validated_data):
        validated_data['created'] = timezone.now()
        validated_data['modified'] = timezone.now()
        return super().create(validated_data)
    
    def update(self, instance, validated_data):
        validated_data['modified'] = timezone.now()
        return super().update(instance, validated_data)


class BlogPostSerializer(serializers.ModelSerializer):
    """Serializer for BlogPost model with translation and category support"""
    region_name = serializers.CharField(source='region.name', read_only=True)
    tags = TagSerializer(many=True, read_only=True, source='blogposttag_set.tag')
    categories = serializers.SerializerMethodField()
    
    class Meta:
        model = BlogPost
        fields = [
            'id', 'title', 'slug', 'content', 'image', 'status',
            'region', 'region_name', 'created', 'modified',
            'tags', 'categories'
        ]
        read_only_fields = ['id', 'created', 'modified', 'region_name', 'tags', 'categories']
    
    def get_categories(self, obj):
        """Get categories for this blog post"""
        categories = BlogCategory.objects.filter(blog_post=obj)
        return [{'id': cat.id, 'category_id': cat.category_id} for cat in categories]
    
    def create(self, validated_data):
        validated_data['created'] = timezone.now()
        validated_data['modified'] = timezone.now()
        return super().create(validated_data)
    
    def update(self, instance, validated_data):
        validated_data['modified'] = timezone.now()
        return super().update(instance, validated_data)


class BlogPostCommentSerializer(serializers.ModelSerializer):
    """Serializer for BlogPostComment model"""
    user_name = serializers.SerializerMethodField()
    blog_post_title = serializers.CharField(source='blog_post.title', read_only=True)
    
    class Meta:
        model = BlogPostComment
        fields = [
            'id', 'user', 'user_name', 'blog_post', 'blog_post_title',
            'comment', 'created', 'modified'
        ]
        read_only_fields = ['id', 'created', 'modified', 'user_name', 'blog_post_title']
    
    def get_user_name(self, obj):
        return f"{obj.user.first_name} {obj.user.last_name}".strip()


class NewsSerializer(serializers.ModelSerializer):
    """Serializer for News model with tags and categories"""
    organization_name = serializers.CharField(source='organization.name', read_only=True)
    tags = TagSerializer(many=True, read_only=True, source='newstag_set.tag')
    categories = serializers.SerializerMethodField()
    
    class Meta:
        model = News
        fields = [
            'id', 'organization', 'organization_name', 'title', 'slug',
            'content', 'image', 'status', 'region_id', 'created', 'modified',
            'tags', 'categories'
        ]
        read_only_fields = ['id', 'created', 'modified', 'organization_name', 'tags', 'categories']
    
    def get_categories(self, obj):
        """Get categories for this news article"""
        categories = NewsCategory.objects.filter(news=obj)
        return [{'id': cat.id, 'category_id': cat.category_id} for cat in categories]
    
    def create(self, validated_data):
        validated_data['created'] = timezone.now()
        validated_data['modified'] = timezone.now()
        return super().create(validated_data)
    
    def update(self, instance, validated_data):
        validated_data['modified'] = timezone.now()
        return super().update(instance, validated_data)


class NewsCommentSerializer(serializers.ModelSerializer):
    """Serializer for NewsComment model"""
    user_name = serializers.SerializerMethodField()
    news_title = serializers.CharField(source='news.title', read_only=True)
    
    class Meta:
        model = NewsComment
        fields = [
            'id', 'user', 'user_name', 'news', 'news_title',
            'comment', 'created', 'modified'
        ]
        read_only_fields = ['id', 'created', 'modified', 'user_name', 'news_title']
    
    def get_user_name(self, obj):
        return f"{obj.user.first_name} {obj.user.last_name}".strip()


class EventSerializer(serializers.ModelSerializer):
    """Serializer for Event model with location and organization data"""
    organization_name = serializers.CharField(source='organization.name', read_only=True)
    
    class Meta:
        model = Event
        fields = [
            'id', 'organization', 'organization_name', 'title', 'slug',
            'content', 'image', 'status', 'region_id', 'created', 'modified'
        ]
        read_only_fields = ['id', 'created', 'modified', 'organization_name']
    
    def create(self, validated_data):
        validated_data['created'] = timezone.now()
        validated_data['modified'] = timezone.now()
        return super().create(validated_data)
    
    def update(self, instance, validated_data):
        validated_data['modified'] = timezone.now()
        return super().update(instance, validated_data)


class EventCommentSerializer(serializers.ModelSerializer):
    """Serializer for EventComment model"""
    user_name = serializers.SerializerMethodField()
    event_title = serializers.CharField(source='event.title', read_only=True)
    
    class Meta:
        model = EventComment
        fields = [
            'id', 'event', 'event_title', 'user', 'user_name',
            'comment', 'created', 'modified'
        ]
        read_only_fields = ['id', 'created', 'modified', 'user_name', 'event_title']
    
    def get_user_name(self, obj):
        return f"{obj.user.first_name} {obj.user.last_name}".strip()


class ResourceSerializer(serializers.ModelSerializer):
    """Serializer for Resource model with categories"""
    organization_name = serializers.CharField(source='organization.name', read_only=True)
    categories = serializers.SerializerMethodField()
    
    class Meta:
        model = Resource
        fields = [
            'id', 'organization', 'organization_name', 'title', 'slug',
            'content', 'image', 'status', 'region_id', 'created', 'modified',
            'categories'
        ]
        read_only_fields = ['id', 'created', 'modified', 'organization_name', 'categories']
    
    def get_categories(self, obj):
        """Get categories for this resource"""
        categories = ResourceCategory.objects.filter(resource=obj)
        return [
            {
                'id': cat.id,
                'category_id': cat.category.id,
                'category_name': cat.category.name
            } for cat in categories
        ]
    
    def create(self, validated_data):
        validated_data['created'] = timezone.now()
        validated_data['modified'] = timezone.now()
        return super().create(validated_data)
    
    def update(self, instance, validated_data):
        validated_data['modified'] = timezone.now()
        return super().update(instance, validated_data)


class ActivityLogSerializer(serializers.ModelSerializer):
    """Serializer for ActivityLog model"""
    
    class Meta:
        model = ActivityLog
        fields = [
            'id', 'created_at', 'scope_model', 'scope_id',
            'issuer_model', 'issuer_id', 'object_model', 'object_id',
            'level', 'action', 'message', 'data'
        ]
        read_only_fields = ['id', 'created_at']


class AdminActivityLogSerializer(serializers.ModelSerializer):
    """Serializer for AdminActivityLog model"""
    
    class Meta:
        model = AdminActivityLog
        fields = [
            'id', 'created_at', 'scope_model', 'scope_id',
            'issuer_model', 'issuer_id', 'object_model', 'object_id',
            'level', 'action', 'message', 'data'
        ]
        read_only_fields = ['id', 'created_at']


class I18nSerializer(serializers.ModelSerializer):
    """Serializer for I18n translation model"""
    
    class Meta:
        model = I18n
        fields = ['id', 'locale', 'model', 'foreign_key', 'field', 'content']
        read_only_fields = ['id']


# Pagination classes for large datasets
class StandardResultsSetPagination(serializers.Serializer):
    """Standard pagination serializer"""
    page_size = 20
    page_size_query_param = 'page_size'
    max_page_size = 100