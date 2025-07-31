from django.utils.deprecation import MiddlewareMixin
from django.http import JsonResponse
from rest_framework_simplejwt.authentication import JWTAuthentication
from rest_framework_simplejwt.exceptions import InvalidToken, TokenError
from core.models import Admin, User
import logging

logger = logging.getLogger(__name__)


class JWTAuthenticationMiddleware(MiddlewareMixin):
    """
    Middleware to handle JWT authentication for API requests
    """
    
    def __init__(self, get_response):
        self.get_response = get_response
        self.jwt_auth = JWTAuthentication()
        super().__init__(get_response)
    
    def process_request(self, request):
        # Skip authentication for certain paths
        skip_paths = [
            '/api/v1/auth/admin/login/',
            '/api/v1/auth/user/login/',
            '/api/v1/auth/password-reset/',
            '/api/v1/auth/password-reset/confirm/',
            '/api/v1/auth/token/refresh/',
            '/admin/',
            '/static/',
            '/media/',
        ]
        
        # Check if request path should skip authentication
        if any(request.path.startswith(path) for path in skip_paths):
            return None
        
        # Only process API requests
        if not request.path.startswith('/api/'):
            return None
        
        try:
            # Try to authenticate using JWT
            auth_result = self.jwt_auth.authenticate(request)
            if auth_result:
                user, token = auth_result
                request.user = user
                request.auth = token
                
                # Log successful authentication
                logger.debug(f"JWT authentication successful for user: {user}")
                
        except (InvalidToken, TokenError) as e:
            logger.warning(f"JWT authentication failed: {str(e)}")
            return JsonResponse(
                {'error': 'Invalid or expired token'},
                status=401
            )
        except Exception as e:
            logger.error(f"JWT authentication error: {str(e)}")
            return JsonResponse(
                {'error': 'Authentication failed'},
                status=401
            )
        
        return None


class ActivityLogMiddleware(MiddlewareMixin):
    """
    Middleware to log user activities
    """
    
    def __init__(self, get_response):
        self.get_response = get_response
        super().__init__(get_response)
    
    def process_response(self, request, response):
        # Log API activities for authenticated users
        if (hasattr(request, 'user') and 
            request.user and 
            hasattr(request.user, 'id') and
            request.path.startswith('/api/')):
            
            try:
                self._log_activity(request, response)
            except Exception as e:
                logger.error(f"Activity logging error: {str(e)}")
        
        return response
    
    def _log_activity(self, request, response):
        """
        Log user activity
        """
        from core.models import ActivityLog, AdminActivityLog
        from django.utils import timezone
        
        user = request.user
        method = request.method
        path = request.path
        status_code = response.status_code
        
        # Determine log level based on status code
        if status_code < 400:
            level = 'info'
        elif status_code < 500:
            level = 'warning'
        else:
            level = 'error'
        
        # Create activity log entry
        log_data = {
            'created_at': timezone.now(),
            'scope_model': user.__class__.__name__,
            'scope_id': str(user.id),
            'issuer_model': user.__class__.__name__,
            'issuer_id': str(user.id),
            'level': level,
            'action': f"{method} {path}",
            'message': f"API request: {method} {path} - Status: {status_code}",
            'data': f'{{"method": "{method}", "path": "{path}", "status": {status_code}}}'
        }
        
        # Use appropriate log model based on user type
        if isinstance(user, Admin):
            AdminActivityLog.objects.create(**log_data)
        else:
            ActivityLog.objects.create(**log_data)