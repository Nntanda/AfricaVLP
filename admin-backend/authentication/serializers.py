from rest_framework import serializers
from rest_framework_simplejwt.serializers import TokenObtainPairSerializer
from rest_framework_simplejwt.tokens import RefreshToken
from django.contrib.auth import authenticate
from models_app.models import Admin, User


class AdminTokenObtainPairSerializer(TokenObtainPairSerializer):
    """
    Custom JWT token serializer for Admin users
    """
    
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.fields['email'] = serializers.EmailField()
        del self.fields['username']  # Remove username field
    
    def validate(self, attrs):
        email = attrs.get('email')
        password = attrs.get('password')
        
        if email and password:
            user = authenticate(
                request=self.context.get('request'),
                email=email,
                password=password
            )
            
            if not user:
                raise serializers.ValidationError('Invalid credentials')
            
            if not isinstance(user, Admin):
                raise serializers.ValidationError('Invalid user type')
                
            if user.status != 1:
                raise serializers.ValidationError('Account is inactive')
            
            # Generate tokens
            refresh = RefreshToken.for_user(user)
            
            return {
                'refresh': str(refresh),
                'access': str(refresh.access_token),
                'user': AdminSerializer(user).data
            }
        
        raise serializers.ValidationError('Email and password are required')
    
    @classmethod
    def get_token(cls, user):
        token = super().get_token(user)
        
        # Add custom claims
        token['user_id'] = user.id
        token['email'] = user.email
        token['name'] = user.name
        token['role'] = user.role
        token['user_type'] = 'admin'
        
        return token


class UserTokenObtainPairSerializer(TokenObtainPairSerializer):
    """
    Custom JWT token serializer for regular Users
    """
    
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.fields['email'] = serializers.EmailField()
        del self.fields['username']  # Remove username field
    
    def validate(self, attrs):
        email = attrs.get('email')
        password = attrs.get('password')
        
        if email and password:
            user = authenticate(
                request=self.context.get('request'),
                email=email,
                password=password,
                user_type='user'
            )
            
            if not user:
                raise serializers.ValidationError('Invalid credentials')
            
            if not isinstance(user, User):
                raise serializers.ValidationError('Invalid user type')
                
            if user.status != 1:
                raise serializers.ValidationError('Account is inactive')
                
            if not user.is_email_verified:
                raise serializers.ValidationError('Email not verified')
            
            # Generate tokens
            refresh = RefreshToken.for_user(user)
            
            return {
                'refresh': str(refresh),
                'access': str(refresh.access_token),
                'user': UserSerializer(user).data
            }
        
        raise serializers.ValidationError('Email and password are required')
    
    @classmethod
    def get_token(cls, user):
        token = super().get_token(user)
        
        # Add custom claims
        token['user_id'] = user.id
        token['email'] = user.email
        token['first_name'] = user.first_name
        token['last_name'] = user.last_name
        token['user_type'] = 'user'
        
        return token


class AdminSerializer(serializers.ModelSerializer):
    """
    Serializer for Admin user data
    """
    
    class Meta:
        model = Admin
        fields = [
            'id', 'email', 'name', 'role', 'status', 
            'created', 'modified'
        ]
        read_only_fields = ['id', 'created', 'modified']


class UserSerializer(serializers.ModelSerializer):
    """
    Serializer for User data
    """
    full_name = serializers.SerializerMethodField()
    
    class Meta:
        model = User
        fields = [
            'id', 'first_name', 'last_name', 'full_name', 'email',
            'phone_number', 'short_profile', 'language', 'profile_image',
            'gender', 'date_of_birth', 'current_address', 'availability',
            'is_email_verified', 'preferred_language', 'status',
            'has_volunteering_experience', 'experience_rating'
        ]
        read_only_fields = ['id', 'is_email_verified', 'experience_rating']
    
    def get_full_name(self, obj):
        return f"{obj.first_name} {obj.last_name}".strip()


class PasswordResetSerializer(serializers.Serializer):
    """
    Serializer for password reset request
    """
    email = serializers.EmailField()
    
    def validate_email(self, value):
        # Check if email exists in either Admin or User models
        admin_exists = Admin.objects.filter(email=value, status=1).exists()
        user_exists = User.objects.filter(email=value, status=1).exists()
        
        if not (admin_exists or user_exists):
            raise serializers.ValidationError('No account found with this email address')
        
        return value


class PasswordResetConfirmSerializer(serializers.Serializer):
    """
    Serializer for password reset confirmation
    """
    token = serializers.CharField()
    password = serializers.CharField(min_length=8)
    password_confirm = serializers.CharField()
    
    def validate(self, attrs):
        if attrs['password'] != attrs['password_confirm']:
            raise serializers.ValidationError('Passwords do not match')
        return attrs


class ChangePasswordSerializer(serializers.Serializer):
    """
    Serializer for changing password
    """
    old_password = serializers.CharField()
    new_password = serializers.CharField(min_length=8)
    new_password_confirm = serializers.CharField()
    
    def validate(self, attrs):
        if attrs['new_password'] != attrs['new_password_confirm']:
            raise serializers.ValidationError('New passwords do not match')
        return attrs