# AU-VLP Environment Configuration Guide

This document provides comprehensive guidance for configuring the AU-VLP (African Union Youth Leadership Program) system environment variables and CORS settings.

## Overview

The AU-VLP system uses environment variables for configuration management across different deployment environments (development, staging, production). This approach ensures security, flexibility, and proper separation of concerns.

## Environment Files

### Main Configuration Files

- `.env` - Main environment configuration (used by Docker Compose)
- `.env.example` - Template with example values
- `admin-frontend/.env` - Admin frontend development configuration
- `admin-frontend/.env.production` - Admin frontend production configuration
- `wellknown-frontend/.env.development` - Well-known frontend development configuration
- `wellknown-frontend/.env.production` - Well-known frontend production configuration

## Core Environment Variables

### Environment Settings

```bash
# Environment type - affects security settings and validation
NODE_ENV=development|staging|production
ENVIRONMENT=development|staging|production
```

### Django Backend Settings

```bash
# Security - CRITICAL: Change in production
SECRET_KEY=your-secret-key-here-change-in-production
DEBUG=True|False

# Allowed hosts for Django
ALLOWED_HOSTS=localhost,127.0.0.1,admin-backend,wellknown-backend,nginx,admin.localhost,wellknown.localhost,admin-api.localhost,wellknown-api.localhost
```

### Database Configuration

```bash
# MySQL database settings
DB_NAME=africa_vlp
DB_USER=africa_vlp_user
DB_PASSWORD=your-database-password-here
DB_HOST=mysql
DB_PORT=3306
DB_CONN_MAX_AGE=300
```

### Redis Configuration

```bash
# Redis for caching and Celery
REDIS_URL=redis://redis:6379/1
CELERY_BROKER_URL=redis://redis:6379/0
CELERY_RESULT_BACKEND=redis://redis:6379/0
```

### CORS Configuration

```bash
# Cross-Origin Resource Sharing settings
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://127.0.0.1:3000,http://admin-frontend:3000,http://localhost:3001,http://127.0.0.1:3001,http://wellknown-frontend:3000,http://admin.localhost,http://wellknown.localhost,http://admin-api.localhost,http://wellknown-api.localhost,http://nginx
```

### Frontend URLs

```bash
# Frontend application URLs for backend reference
ADMIN_FRONTEND_URL=http://localhost:3000
WELLKNOWN_FRONTEND_URL=http://localhost:3001

# API base URLs for frontend applications
ADMIN_API_URL=http://admin-backend:8000
WELLKNOWN_API_URL=http://wellknown-backend:8000
```

### Security Settings

```bash
# Security headers and SSL settings
SECURE_SSL_REDIRECT=False|True
SECURE_BROWSER_XSS_FILTER=True
SECURE_CONTENT_TYPE_NOSNIFF=True
X_FRAME_OPTIONS=SAMEORIGIN|DENY
```

### Email Configuration

```bash
# Email backend settings
EMAIL_BACKEND=django.core.mail.backends.console.EmailBackend
EMAIL_HOST=your-smtp-host
EMAIL_PORT=587
EMAIL_USE_TLS=True
EMAIL_HOST_USER=your-email-username
EMAIL_HOST_PASSWORD=your-email-password
DEFAULT_FROM_EMAIL=noreply@au-vlp.org
```

### Logging Configuration

```bash
# Logging settings
LOG_LEVEL=INFO|DEBUG|WARNING|ERROR
ENABLE_REQUEST_LOGGING=True|False
```

## Frontend Environment Variables

### Admin Frontend

```bash
# API configuration
VITE_API_BASE_URL=/api/v1
VITE_API_TIMEOUT=10000

# Application settings
VITE_APP_TITLE=AU-VLP Admin
VITE_APP_VERSION=1.0.0

# Development settings
VITE_ENABLE_DEBUG=true|false
VITE_ENABLE_ANALYTICS=false|true

# Security settings
VITE_ENABLE_HTTPS=false|true
VITE_SECURE_COOKIES=false|true

# Internationalization
VITE_DEFAULT_LANGUAGE=en
VITE_SUPPORTED_LANGUAGES=en,fr,ar
```

### Well-known Frontend

```bash
# API configuration
VITE_API_BASE_URL=/api/v1
VITE_API_TIMEOUT=10000

# Application settings
VITE_APP_NAME=AU-VLP Well-known
VITE_APP_VERSION=1.0.0

# Feature flags
VITE_ENABLE_DEBUG=true|false
VITE_ENABLE_ANALYTICS=false|true

# Security settings
VITE_ENABLE_HTTPS=false|true
VITE_SECURE_COOKIES=false|true

# External services
VITE_GOOGLE_ANALYTICS_ID=
VITE_SENTRY_DSN=

# Internationalization
VITE_DEFAULT_LANGUAGE=en
VITE_SUPPORTED_LANGUAGES=en,fr,ar
```

## Environment-Specific Configuration

### Development Environment

- `DEBUG=True`
- `CORS_ALLOW_ALL_ORIGINS=True` (automatically set when DEBUG=True)
- Relaxed security settings
- Console email backend
- Detailed logging enabled

### Staging Environment

- `DEBUG=False`
- Specific CORS origins only
- Enhanced security settings
- Real email backend
- Structured logging

### Production Environment

- `DEBUG=False`
- Strict CORS origins
- Maximum security settings
- Production email backend
- Optimized logging

## CORS Configuration Details

### Backend CORS Settings

The Django backends use `django-cors-headers` with the following configuration:

```python
# Allow specific origins
CORS_ALLOWED_ORIGINS = env.list('CORS_ALLOWED_ORIGINS', default=[...])

# Allow credentials (cookies, authorization headers)
CORS_ALLOW_CREDENTIALS = True

# Allow all origins only in debug mode
CORS_ALLOW_ALL_ORIGINS = DEBUG

# Regex patterns for dynamic origins (development only)
CORS_ALLOWED_ORIGIN_REGEXES = [
    r"^http://localhost:\d+$",
    r"^http://127\.0\.0\.1:\d+$",
] if DEBUG else []

# Allowed headers
CORS_ALLOW_HEADERS = [
    'accept',
    'accept-encoding',
    'authorization',
    'content-type',
    'dnt',
    'origin',
    'user-agent',
    'x-csrftoken',
    'x-requested-with',
]

# Exposed headers
CORS_EXPOSE_HEADERS = [
    'content-disposition',
    'content-length',
    'content-type',
]
```

### Nginx CORS Headers

Nginx adds CORS headers for API endpoints:

```nginx
# Handle preflight OPTIONS requests
if ($cors_method = 11) {
    add_header Access-Control-Allow-Origin "$http_origin" always;
    add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, PATCH, OPTIONS" always;
    add_header Access-Control-Allow-Headers "Authorization, Content-Type, Accept, Origin, X-Requested-With, X-CSRFToken" always;
    add_header Access-Control-Allow-Credentials "true" always;
    add_header Access-Control-Max-Age 86400 always;
    return 204;
}

# Handle actual requests
if ($cors_method = 1) {
    add_header Access-Control-Allow-Origin "$http_origin" always;
    add_header Access-Control-Allow-Credentials "true" always;
    add_header Access-Control-Expose-Headers "Content-Disposition, Content-Length, Content-Type" always;
}
```

## Environment Validation

### Validation Commands

Use the management commands to validate your environment configuration:

```bash
# Validate current environment
docker-compose exec admin-backend python manage.py validate_env

# Validate specific environment
docker-compose exec admin-backend python manage.py validate_env --environment production

# Show configuration summary
docker-compose exec admin-backend python manage.py validate_env --summary

# Output as JSON
docker-compose exec admin-backend python manage.py validate_env --json
```

### Validation Rules

The system validates:

1. **Required Variables**: Ensures all required environment variables are set
2. **Security Settings**: Validates security configuration for each environment
3. **CORS Configuration**: Checks CORS origins and settings
4. **Database Configuration**: Validates database connection settings
5. **Production Readiness**: Ensures production-specific requirements are met

## Security Best Practices

### Secret Management

1. **Never commit secrets to version control**
2. **Use strong, unique SECRET_KEY in production**
3. **Change default passwords**
4. **Use environment-specific configurations**

### CORS Security

1. **Specify exact origins in production**
2. **Avoid wildcards (*) in CORS_ALLOWED_ORIGINS**
3. **Use HTTPS in production**
4. **Limit exposed headers**

### Database Security

1. **Use strong database passwords**
2. **Limit database user permissions**
3. **Enable SSL for database connections in production**
4. **Regular security updates**

## Troubleshooting

### Common Issues

1. **CORS Errors**
   - Check CORS_ALLOWED_ORIGINS includes your frontend URL
   - Verify nginx CORS headers are properly configured
   - Ensure credentials are allowed if using authentication

2. **Environment Variable Not Found**
   - Check .env file exists and is properly formatted
   - Verify variable names match exactly
   - Ensure Docker Compose is reading the .env file

3. **Frontend API Connection Issues**
   - Verify VITE_API_BASE_URL is correct
   - Check Vite proxy configuration
   - Ensure backend is running and accessible

### Debug Commands

```bash
# Check environment variables in container
docker-compose exec admin-backend env | grep -E "(SECRET_KEY|DEBUG|CORS|DB_)"

# Test API connectivity
curl -H "Origin: http://localhost:3000" http://localhost:8000/health/live/

# Check nginx configuration
docker-compose exec nginx nginx -t

# View container logs
docker-compose logs admin-backend
docker-compose logs admin-frontend
```

## Migration Guide

### From Previous Configuration

1. **Update .env file** with new variables
2. **Run environment validation** to check configuration
3. **Update frontend .env files** with new API URLs
4. **Test CORS configuration** with frontend applications
5. **Verify all services start correctly**

### Production Deployment

1. **Generate strong SECRET_KEY**
2. **Set DEBUG=False**
3. **Configure production database**
4. **Set up SSL certificates**
5. **Update CORS origins for production domains**
6. **Configure production email backend**
7. **Run full environment validation**

## Support

For issues with environment configuration:

1. Run environment validation commands
2. Check container logs for specific errors
3. Verify all required variables are set
4. Test CORS configuration with browser developer tools
5. Consult this documentation for best practices