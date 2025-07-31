from django.contrib.auth.backends import BaseBackend
from django.contrib.auth.hashers import check_password
from models_app.models import Admin, User
import hashlib


class AdminAuthenticationBackend(BaseBackend):
    """
    Custom authentication backend for Admin users
    Supports existing password hashes from CakePHP
    """
    
    def authenticate(self, request, email=None, password=None, **kwargs):
        if email is None or password is None:
            return None
            
        try:
            admin = Admin.objects.get(email=email, status=1)
            
            # Check if password matches (support both Django and legacy hashes)
            if self._check_password(password, admin.password):
                return admin
                
        except Admin.DoesNotExist:
            return None
            
        return None
    
    def get_user(self, user_id):
        try:
            return Admin.objects.get(pk=user_id)
        except Admin.DoesNotExist:
            return None
    
    def _check_password(self, raw_password, encoded_password):
        """
        Check password against both Django and legacy CakePHP hashes
        """
        # Try Django's password checking first
        if check_password(raw_password, encoded_password):
            return True
            
        # Try CakePHP/legacy hash formats
        # CakePHP typically uses SHA1 or MD5 with salt
        if self._check_legacy_password(raw_password, encoded_password):
            return True
            
        return False
    
    def _check_legacy_password(self, raw_password, encoded_password):
        """
        Check against legacy password formats
        """
        # Simple MD5 check (common in older systems)
        if len(encoded_password) == 32:  # MD5 hash length
            md5_hash = hashlib.md5(raw_password.encode()).hexdigest()
            return md5_hash == encoded_password
            
        # Simple SHA1 check
        if len(encoded_password) == 40:  # SHA1 hash length
            sha1_hash = hashlib.sha1(raw_password.encode()).hexdigest()
            return sha1_hash == encoded_password
            
        # CakePHP style hash (SHA1 with salt)
        # Format: sha1(password + salt) or similar
        # This would need to be customized based on actual legacy format
        
        return False


class UserAuthenticationBackend(BaseBackend):
    """
    Custom authentication backend for regular Users
    """
    
    def authenticate(self, request, email=None, password=None, user_type='user', **kwargs):
        if user_type != 'user' or email is None or password is None:
            return None
            
        try:
            user = User.objects.get(email=email, status=1, is_email_verified=True)
            
            # Check if password matches
            if self._check_password(password, user.password):
                return user
                
        except User.DoesNotExist:
            return None
            
        return None
    
    def get_user(self, user_id):
        try:
            return User.objects.get(pk=user_id)
        except User.DoesNotExist:
            return None
    
    def _check_password(self, raw_password, encoded_password):
        """
        Check password against both Django and legacy hashes
        """
        # Try Django's password checking first
        if check_password(raw_password, encoded_password):
            return True
            
        # Try legacy hash formats
        if self._check_legacy_password(raw_password, encoded_password):
            return True
            
        return False
    
    def _check_legacy_password(self, raw_password, encoded_password):
        """
        Check against legacy password formats
        """
        # Simple MD5 check
        if len(encoded_password) == 32:
            md5_hash = hashlib.md5(raw_password.encode()).hexdigest()
            return md5_hash == encoded_password
            
        # Simple SHA1 check
        if len(encoded_password) == 40:
            sha1_hash = hashlib.sha1(raw_password.encode()).hexdigest()
            return sha1_hash == encoded_password
            
        return False