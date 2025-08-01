"""
Environment configuration validation utility.
"""
import os
import logging
from typing import Dict, List, Optional, Any

logger = logging.getLogger(__name__)


class EnvironmentValidator:
    """Validates environment configuration for the AU-VLP system."""
    
    REQUIRED_VARS = {
        'development': [
            'SECRET_KEY',
            'DB_NAME',
            'DB_USER',
            'DB_PASSWORD',
            'DB_HOST',
            'REDIS_URL',
            'CELERY_BROKER_URL',
        ],
        'staging': [
            'SECRET_KEY',
            'DB_NAME',
            'DB_USER',
            'DB_PASSWORD',
            'DB_HOST',
            'REDIS_URL',
            'CELERY_BROKER_URL',
            'EMAIL_HOST',
            'EMAIL_HOST_USER',
            'EMAIL_HOST_PASSWORD',
        ],
        'production': [
            'SECRET_KEY',
            'DB_NAME',
            'DB_USER',
            'DB_PASSWORD',
            'DB_HOST',
            'REDIS_URL',
            'CELERY_BROKER_URL',
            'EMAIL_HOST',
            'EMAIL_HOST_USER',
            'EMAIL_HOST_PASSWORD',
            'SECURE_SSL_REDIRECT',
        ]
    }
    
    SECURITY_VARS = [
        'SECURE_SSL_REDIRECT',
        'SECURE_BROWSER_XSS_FILTER',
        'SECURE_CONTENT_TYPE_NOSNIFF',
        'X_FRAME_OPTIONS',
    ]
    
    @classmethod
    def validate_environment(cls, environment: str = None) -> Dict[str, Any]:
        """
        Validate environment configuration.
        
        Args:
            environment: Environment name (development, staging, production)
            
        Returns:
            Dict containing validation results
        """
        if environment is None:
            environment = os.getenv('ENVIRONMENT', 'development')
            
        results = {
            'environment': environment,
            'valid': True,
            'missing_vars': [],
            'warnings': [],
            'errors': [],
        }
        
        # Check if environment is valid
        if environment not in cls.REQUIRED_VARS:
            results['valid'] = False
            results['errors'].append(f"Invalid environment: {environment}")
            return results
            
        # Check required variables
        required_vars = cls.REQUIRED_VARS[environment]
        for var in required_vars:
            if not os.getenv(var):
                results['missing_vars'].append(var)
                results['valid'] = False
                
        # Check security configuration for production
        if environment == 'production':
            cls._validate_production_security(results)
            
        # Check CORS configuration
        cls._validate_cors_configuration(results)
        
        # Check database configuration
        cls._validate_database_configuration(results)
        
        # Log results
        if results['valid']:
            logger.info(f"Environment validation passed for {environment}")
        else:
            logger.error(f"Environment validation failed for {environment}: {results}")
            
        return results
    
    @classmethod
    def _validate_production_security(cls, results: Dict[str, Any]) -> None:
        """Validate production security settings."""
        debug = os.getenv('DEBUG', 'False').lower()
        if debug == 'true':
            results['errors'].append("DEBUG should be False in production")
            results['valid'] = False
            
        secret_key = os.getenv('SECRET_KEY', '')
        if 'django-insecure' in secret_key or len(secret_key) < 50:
            results['errors'].append("SECRET_KEY should be a strong, unique key in production")
            results['valid'] = False
            
        allowed_hosts = os.getenv('ALLOWED_HOSTS', '')
        if '*' in allowed_hosts:
            results['warnings'].append("ALLOWED_HOSTS contains '*' which is not recommended for production")
    
    @classmethod
    def _validate_cors_configuration(cls, results: Dict[str, Any]) -> None:
        """Validate CORS configuration."""
        cors_origins = os.getenv('CORS_ALLOWED_ORIGINS', '')
        if not cors_origins:
            results['warnings'].append("CORS_ALLOWED_ORIGINS is not set")
            
        # Check for localhost in production
        environment = results['environment']
        if environment == 'production' and 'localhost' in cors_origins:
            results['warnings'].append("CORS_ALLOWED_ORIGINS contains localhost in production")
    
    @classmethod
    def _validate_database_configuration(cls, results: Dict[str, Any]) -> None:
        """Validate database configuration."""
        db_password = os.getenv('DB_PASSWORD', '')
        if db_password == 'example_password':
            results['warnings'].append("DB_PASSWORD is using default example value")
            
        db_conn_max_age = os.getenv('DB_CONN_MAX_AGE', '300')
        try:
            max_age = int(db_conn_max_age)
            if max_age < 0:
                results['warnings'].append("DB_CONN_MAX_AGE should be non-negative")
        except ValueError:
            results['warnings'].append("DB_CONN_MAX_AGE should be a valid integer")
    
    @classmethod
    def get_configuration_summary(cls) -> Dict[str, Any]:
        """Get a summary of current configuration."""
        environment = os.getenv('ENVIRONMENT', 'development')
        
        return {
            'environment': environment,
            'debug': os.getenv('DEBUG', 'False').lower() == 'true',
            'database': {
                'host': os.getenv('DB_HOST', 'localhost'),
                'name': os.getenv('DB_NAME', 'africa_vlp'),
                'user': os.getenv('DB_USER', 'africa_vlp_user'),
            },
            'redis': {
                'url': os.getenv('REDIS_URL', 'redis://localhost:6379/1'),
            },
            'cors': {
                'allowed_origins': os.getenv('CORS_ALLOWED_ORIGINS', '').split(','),
            },
            'security': {
                'ssl_redirect': os.getenv('SECURE_SSL_REDIRECT', 'False').lower() == 'true',
                'xss_filter': os.getenv('SECURE_BROWSER_XSS_FILTER', 'True').lower() == 'true',
                'content_type_nosniff': os.getenv('SECURE_CONTENT_TYPE_NOSNIFF', 'True').lower() == 'true',
            }
        }


def validate_environment_on_startup():
    """Validate environment configuration on Django startup."""
    try:
        validator = EnvironmentValidator()
        results = validator.validate_environment()
        
        if not results['valid']:
            logger.error("Environment validation failed!")
            for error in results['errors']:
                logger.error(f"ERROR: {error}")
            for missing_var in results['missing_vars']:
                logger.error(f"MISSING: {missing_var}")
                
        for warning in results['warnings']:
            logger.warning(f"WARNING: {warning}")
            
        return results
        
    except Exception as e:
        logger.error(f"Environment validation error: {e}")
        return {'valid': False, 'errors': [str(e)]}