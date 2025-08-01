from rest_framework import status, permissions
from rest_framework.decorators import api_view, permission_classes
from rest_framework.response import Response
from rest_framework.views import APIView
from rest_framework_simplejwt.views import TokenObtainPairView, TokenRefreshView
from rest_framework_simplejwt.tokens import RefreshToken
from rest_framework_simplejwt.exceptions import TokenError, InvalidToken
from django.contrib.auth import authenticate
from django.contrib.auth.hashers import make_password, check_password
from django.core.mail import send_mail
from django.conf import settings
from django.utils.crypto import get_random_string
from django.utils import timezone
from datetime import timedelta
import logging

from .serializers import (
    AdminTokenObtainPairSerializer,
    UserTokenObtainPairSerializer,
    AdminSerializer,
    UserSerializer,
    PasswordResetSerializer,
    PasswordResetConfirmSerializer,
    ChangePasswordSerializer
)
from models_app.models import Admin, User
from models_app.utils.logging_utils import (
    get_logger, log_security_event, log_user_action, log_api_call
)

logger = get_logger('authentication')


class AdminLoginView(TokenObtainPairView):
    """
    Admin login endpoint
    """
    serializer_class = AdminTokenObtainPairSerializer
    
    def post(self, request, *args, **kwargs):
        email = request.data.get('email')
        client_ip = self._get_client_ip(request)
        
        try:
            response = super().post(request, *args, **kwargs)
            if response.status_code == 200:
                # Get user ID from response or lookup
                try:
                    admin = Admin.objects.get(email=email)
                    user_id = admin.id
                except Admin.DoesNotExist:
                    user_id = None
                
                log_security_event(
                    'admin_login_success',
                    f"Admin login successful for {email}",
                    user_id=user_id,
                    ip_address=client_ip,
                    email=email
                )
                log_user_action(user_id, 'login', 'admin_portal', email=email)
            return response
        except Exception as e:
            log_security_event(
                'admin_login_failed',
                f"Admin login failed for {email}: {str(e)}",
                ip_address=client_ip,
                email=email,
                error=str(e)
            )
            return Response(
                {'error': 'Login failed'},
                status=status.HTTP_400_BAD_REQUEST
            )
    
    def _get_client_ip(self, request):
        """Extract client IP address from request."""
        x_forwarded_for = request.META.get('HTTP_X_FORWARDED_FOR')
        if x_forwarded_for:
            ip = x_forwarded_for.split(',')[0].strip()
        else:
            ip = request.META.get('REMOTE_ADDR', '')
        return ip


class UserLoginView(TokenObtainPairView):
    """
    User login endpoint
    """
    serializer_class = UserTokenObtainPairSerializer
    
    def post(self, request, *args, **kwargs):
        email = request.data.get('email')
        client_ip = self._get_client_ip(request)
        
        try:
            response = super().post(request, *args, **kwargs)
            if response.status_code == 200:
                # Get user ID from response or lookup
                try:
                    user = User.objects.get(email=email)
                    user_id = user.id
                except User.DoesNotExist:
                    user_id = None
                
                log_security_event(
                    'user_login_success',
                    f"User login successful for {email}",
                    user_id=user_id,
                    ip_address=client_ip,
                    email=email
                )
                log_user_action(user_id, 'login', 'user_portal', email=email)
            return response
        except Exception as e:
            log_security_event(
                'user_login_failed',
                f"User login failed for {email}: {str(e)}",
                ip_address=client_ip,
                email=email,
                error=str(e)
            )
            return Response(
                {'error': 'Login failed'},
                status=status.HTTP_400_BAD_REQUEST
            )
    
    def _get_client_ip(self, request):
        """Extract client IP address from request."""
        x_forwarded_for = request.META.get('HTTP_X_FORWARDED_FOR')
        if x_forwarded_for:
            ip = x_forwarded_for.split(',')[0].strip()
        else:
            ip = request.META.get('REMOTE_ADDR', '')
        return ip


class LogoutView(APIView):
    """
    Logout endpoint - blacklist the refresh token
    """
    permission_classes = [permissions.IsAuthenticated]
    
    def post(self, request):
        try:
            refresh_token = request.data.get('refresh_token')
            if refresh_token:
                token = RefreshToken(refresh_token)
                token.blacklist()
                
            logger.info(f"User logout successful: {request.user}")
            return Response(
                {'message': 'Successfully logged out'},
                status=status.HTTP_200_OK
            )
        except TokenError:
            return Response(
                {'error': 'Invalid token'},
                status=status.HTTP_400_BAD_REQUEST
            )
        except Exception as e:
            logger.error(f"Logout error: {str(e)}")
            return Response(
                {'error': 'Logout failed'},
                status=status.HTTP_400_BAD_REQUEST
            )


class ProfileView(APIView):
    """
    Get current user profile
    """
    permission_classes = [permissions.IsAuthenticated]
    
    def get(self, request):
        try:
            user = request.user
            
            if isinstance(user, Admin):
                serializer = AdminSerializer(user)
            elif isinstance(user, User):
                serializer = UserSerializer(user)
            else:
                return Response(
                    {'error': 'Invalid user type'},
                    status=status.HTTP_400_BAD_REQUEST
                )
            
            return Response(serializer.data, status=status.HTTP_200_OK)
            
        except Exception as e:
            logger.error(f"Profile fetch error: {str(e)}")
            return Response(
                {'error': 'Failed to fetch profile'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class ChangePasswordView(APIView):
    """
    Change user password
    """
    permission_classes = [permissions.IsAuthenticated]
    
    def post(self, request):
        try:
            serializer = ChangePasswordSerializer(data=request.data)
            if not serializer.is_valid():
                return Response(
                    serializer.errors,
                    status=status.HTTP_400_BAD_REQUEST
                )
            
            user = request.user
            old_password = serializer.validated_data['old_password']
            new_password = serializer.validated_data['new_password']
            
            # Verify old password
            if not check_password(old_password, user.password):
                return Response(
                    {'error': 'Current password is incorrect'},
                    status=status.HTTP_400_BAD_REQUEST
                )
            
            # Update password
            user.password = make_password(new_password)
            user.modified = timezone.now()
            user.save(update_fields=['password', 'modified'])
            
            logger.info(f"Password changed for user: {user}")
            return Response(
                {'message': 'Password changed successfully'},
                status=status.HTTP_200_OK
            )
            
        except Exception as e:
            logger.error(f"Password change error: {str(e)}")
            return Response(
                {'error': 'Failed to change password'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


class PasswordResetRequestView(APIView):
    """
    Request password reset
    """
    permission_classes = [permissions.AllowAny]
    
    def post(self, request):
        try:
            serializer = PasswordResetSerializer(data=request.data)
            if not serializer.is_valid():
                return Response(
                    serializer.errors,
                    status=status.HTTP_400_BAD_REQUEST
                )
            
            email = serializer.validated_data['email']
            
            # Generate reset token
            reset_token = get_random_string(32)
            token_expires = timezone.now() + timedelta(hours=1)
            
            # Try to find user in Admin model first
            try:
                user = Admin.objects.get(email=email, status=1)
                user.token = reset_token
                user.modified = timezone.now()
                user.save(update_fields=['token', 'modified'])
                user_type = 'admin'
            except Admin.DoesNotExist:
                # Try User model
                try:
                    user = User.objects.get(email=email, status=1)
                    user.token = reset_token
                    user.token_expires = token_expires
                    user.modified = timezone.now()
                    user.save(update_fields=['token', 'token_expires', 'modified'])
                    user_type = 'user'
                except User.DoesNotExist:
                    # Don't reveal if email exists or not
                    return Response(
                        {'message': 'If the email exists, a reset link has been sent'},
                        status=status.HTTP_200_OK
                    )
            
            # Send reset email (implement based on your email service)
            self._send_reset_email(email, reset_token, user_type)
            
            logger.info(f"Password reset requested for: {email}")
            return Response(
                {'message': 'If the email exists, a reset link has been sent'},
                status=status.HTTP_200_OK
            )
            
        except Exception as e:
            logger.error(f"Password reset request error: {str(e)}")
            return Response(
                {'error': 'Failed to process password reset request'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )
    
    def _send_reset_email(self, email, token, user_type):
        """
        Send password reset email
        """
        try:
            reset_url = f"{settings.FRONTEND_URL}/reset-password?token={token}&type={user_type}"
            
            subject = 'Password Reset - AU-VLP'
            message = f"""
            Hello,
            
            You have requested a password reset for your AU-VLP account.
            
            Please click the following link to reset your password:
            {reset_url}
            
            This link will expire in 1 hour.
            
            If you did not request this reset, please ignore this email.
            
            Best regards,
            AU-VLP Team
            """
            
            send_mail(
                subject,
                message,
                settings.DEFAULT_FROM_EMAIL,
                [email],
                fail_silently=False,
            )
            
        except Exception as e:
            logger.error(f"Failed to send reset email: {str(e)}")


class PasswordResetConfirmView(APIView):
    """
    Confirm password reset
    """
    permission_classes = [permissions.AllowAny]
    
    def post(self, request):
        try:
            serializer = PasswordResetConfirmSerializer(data=request.data)
            if not serializer.is_valid():
                return Response(
                    serializer.errors,
                    status=status.HTTP_400_BAD_REQUEST
                )
            
            token = serializer.validated_data['token']
            new_password = serializer.validated_data['password']
            
            # Try to find user with this token
            user = None
            try:
                user = Admin.objects.get(token=token, status=1)
            except Admin.DoesNotExist:
                try:
                    user = User.objects.get(
                        token=token, 
                        status=1,
                        token_expires__gt=timezone.now()
                    )
                except User.DoesNotExist:
                    return Response(
                        {'error': 'Invalid or expired reset token'},
                        status=status.HTTP_400_BAD_REQUEST
                    )
            
            # Update password and clear token
            user.password = make_password(new_password)
            user.token = None
            if hasattr(user, 'token_expires'):
                user.token_expires = None
            user.modified = timezone.now()
            user.save()
            
            logger.info(f"Password reset completed for user: {user}")
            return Response(
                {'message': 'Password reset successfully'},
                status=status.HTTP_200_OK
            )
            
        except Exception as e:
            logger.error(f"Password reset confirm error: {str(e)}")
            return Response(
                {'error': 'Failed to reset password'},
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )


@api_view(['GET'])
@permission_classes([permissions.IsAuthenticated])
def verify_token(request):
    """
    Verify if the current token is valid
    """
    try:
        user = request.user
        if isinstance(user, Admin):
            user_data = AdminSerializer(user).data
        elif isinstance(user, User):
            user_data = UserSerializer(user).data
        else:
            return Response(
                {'error': 'Invalid user type'},
                status=status.HTTP_400_BAD_REQUEST
            )
        
        return Response({
            'valid': True,
            'user': user_data
        }, status=status.HTTP_200_OK)
        
    except Exception as e:
        logger.error(f"Token verification error: {str(e)}")
        return Response(
            {'valid': False, 'error': 'Invalid token'},
            status=status.HTTP_401_UNAUTHORIZED
        )