"""
Health check views for the admin backend.
These endpoints provide health status information for monitoring and Docker health checks.
"""

import logging
from django.http import JsonResponse
from django.db import connection
from django.core.cache import cache
from django.conf import settings
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import AllowAny
from rest_framework.response import Response
from rest_framework import status
import redis
import time
from datetime import datetime

logger = logging.getLogger(__name__)


@api_view(['GET'])
@permission_classes([AllowAny])
def health_check(request):
    """
    Comprehensive health check endpoint that verifies all critical services.
    Returns HTTP 200 if all services are healthy, HTTP 503 if any service is down.
    """
    health_status = {
        'status': 'healthy',
        'timestamp': datetime.utcnow().isoformat(),
        'service': 'admin-backend',
        'version': '1.0.0',
        'checks': {}
    }
    
    overall_healthy = True
    
    # Database connectivity check
    db_healthy, db_details = check_database_health()
    health_status['checks']['database'] = db_details
    if not db_healthy:
        overall_healthy = False
    
    # Redis connectivity check
    redis_healthy, redis_details = check_redis_health()
    health_status['checks']['redis'] = redis_details
    if not redis_healthy:
        overall_healthy = False
    
    # Cache connectivity check
    cache_healthy, cache_details = check_cache_health()
    health_status['checks']['cache'] = cache_details
    if not cache_healthy:
        overall_healthy = False
    
    # Celery broker check
    celery_healthy, celery_details = check_celery_health()
    health_status['checks']['celery'] = celery_details
    if not celery_healthy:
        overall_healthy = False
    
    # Set overall status
    if not overall_healthy:
        health_status['status'] = 'unhealthy'
        return Response(health_status, status=status.HTTP_503_SERVICE_UNAVAILABLE)
    
    return Response(health_status, status=status.HTTP_200_OK)


@api_view(['GET'])
@permission_classes([AllowAny])
def liveness_check(request):
    """
    Simple liveness check - just confirms the application is running.
    Used by Docker/Kubernetes for basic container health.
    """
    return JsonResponse({
        'status': 'alive',
        'timestamp': datetime.utcnow().isoformat(),
        'service': 'admin-backend'
    })


@api_view(['GET'])
@permission_classes([AllowAny])
def readiness_check(request):
    """
    Readiness check - verifies the application is ready to serve requests.
    Checks critical dependencies like database connectivity.
    """
    ready = True
    checks = {}
    
    # Check database connectivity
    db_healthy, db_details = check_database_health()
    checks['database'] = db_details
    if not db_healthy:
        ready = False
    
    response_data = {
        'status': 'ready' if ready else 'not_ready',
        'timestamp': datetime.utcnow().isoformat(),
        'service': 'admin-backend',
        'checks': checks
    }
    
    if ready:
        return JsonResponse(response_data)
    else:
        return JsonResponse(response_data, status=503)


def check_database_health():
    """Check database connectivity and basic operations."""
    try:
        start_time = time.time()
        
        # Test database connection
        with connection.cursor() as cursor:
            cursor.execute("SELECT 1")
            result = cursor.fetchone()
        
        response_time = (time.time() - start_time) * 1000  # Convert to milliseconds
        
        if result and result[0] == 1:
            return True, {
                'status': 'healthy',
                'response_time_ms': round(response_time, 2),
                'database': settings.DATABASES['default']['NAME'],
                'host': settings.DATABASES['default']['HOST']
            }
        else:
            return False, {
                'status': 'unhealthy',
                'error': 'Database query returned unexpected result',
                'response_time_ms': round(response_time, 2)
            }
            
    except Exception as e:
        logger.error(f"Database health check failed: {str(e)}")
        return False, {
            'status': 'unhealthy',
            'error': str(e),
            'database': settings.DATABASES['default']['NAME'],
            'host': settings.DATABASES['default']['HOST']
        }


def check_redis_health():
    """Check Redis connectivity for Celery broker."""
    try:
        start_time = time.time()
        
        # Parse Redis URL from Celery broker URL
        broker_url = getattr(settings, 'CELERY_BROKER_URL', 'redis://redis:6379/0')
        
        # Create Redis connection
        if broker_url.startswith('redis://'):
            # Extract host, port, and db from URL
            url_parts = broker_url.replace('redis://', '').split('/')
            host_port = url_parts[0].split(':')
            host = host_port[0]
            port = int(host_port[1]) if len(host_port) > 1 else 6379
            db = int(url_parts[1]) if len(url_parts) > 1 else 0
            
            r = redis.Redis(host=host, port=port, db=db, socket_timeout=5)
            
            # Test Redis connection
            r.ping()
            
            response_time = (time.time() - start_time) * 1000
            
            return True, {
                'status': 'healthy',
                'response_time_ms': round(response_time, 2),
                'host': host,
                'port': port,
                'database': db
            }
        else:
            return False, {
                'status': 'unhealthy',
                'error': 'Unsupported broker URL format'
            }
            
    except Exception as e:
        logger.error(f"Redis health check failed: {str(e)}")
        return False, {
            'status': 'unhealthy',
            'error': str(e)
        }


def check_cache_health():
    """Check Django cache backend connectivity."""
    try:
        start_time = time.time()
        
        # Test cache set/get operations
        test_key = 'health_check_test'
        test_value = 'test_value'
        
        cache.set(test_key, test_value, timeout=60)
        retrieved_value = cache.get(test_key)
        
        response_time = (time.time() - start_time) * 1000
        
        if retrieved_value == test_value:
            # Clean up test key
            cache.delete(test_key)
            
            return True, {
                'status': 'healthy',
                'response_time_ms': round(response_time, 2),
                'backend': settings.CACHES['default']['BACKEND']
            }
        else:
            return False, {
                'status': 'unhealthy',
                'error': 'Cache set/get operation failed',
                'response_time_ms': round(response_time, 2)
            }
            
    except Exception as e:
        logger.error(f"Cache health check failed: {str(e)}")
        return False, {
            'status': 'unhealthy',
            'error': str(e)
        }


def check_celery_health():
    """Check Celery worker connectivity."""
    try:
        from celery import current_app
        
        start_time = time.time()
        
        # Get active workers
        inspect = current_app.control.inspect()
        active_workers = inspect.active()
        
        response_time = (time.time() - start_time) * 1000
        
        if active_workers:
            worker_count = len(active_workers)
            return True, {
                'status': 'healthy',
                'response_time_ms': round(response_time, 2),
                'active_workers': worker_count,
                'workers': list(active_workers.keys())
            }
        else:
            return False, {
                'status': 'unhealthy',
                'error': 'No active Celery workers found',
                'response_time_ms': round(response_time, 2)
            }
            
    except Exception as e:
        logger.error(f"Celery health check failed: {str(e)}")
        return False, {
            'status': 'unhealthy',
            'error': str(e)
        }