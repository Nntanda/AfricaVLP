import os
import logging
from celery import Celery
from celery.signals import worker_ready, worker_shutdown, task_failure, task_retry, task_success
from kombu import Connection
from kombu.exceptions import OperationalError
import time

# Set the default Django settings module for the 'celery' program.
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'admin_backend.settings')

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = Celery('admin_backend')

# Using a string here means the worker doesn't have to serialize
# the configuration object to child processes.
app.config_from_object('django.conf:settings', namespace='CELERY')

# Enhanced Celery configuration with error handling and retries
app.conf.update(
    # Broker connection retry settings
    broker_connection_retry_on_startup=True,
    broker_connection_retry=True,
    broker_connection_max_retries=10,
    broker_connection_retry_delay=5.0,
    
    # Task execution settings
    task_acks_late=True,
    task_reject_on_worker_lost=True,
    task_track_started=True,
    
    # Worker settings
    worker_prefetch_multiplier=1,
    worker_max_tasks_per_child=1000,
    worker_disable_rate_limits=False,
    
    # Result backend settings
    result_backend_transport_options={
        'retry_on_timeout': True,
        'retry_policy': {
            'timeout': 5.0,
        }
    },
    
    # Serialization settings
    task_serializer='json',
    accept_content=['json'],
    result_serializer='json',
    
    # Timezone settings
    timezone='UTC',
    enable_utc=True,
    
    # Task routing
    task_routes={
        'models_app.tasks.send_notification_email': {'queue': 'email'},
        'models_app.tasks.send_bulk_notification_emails': {'queue': 'email'},
        'models_app.tasks.process_uploaded_image': {'queue': 'media'},
        'models_app.tasks.export_data_to_csv': {'queue': 'reports'},
        'models_app.tasks.generate_admin_report': {'queue': 'reports'},
        'models_app.tasks.system_health_check': {'queue': 'maintenance'},
        'models_app.tasks.cleanup_expired_content': {'queue': 'maintenance'},
    },
    
    # Queue configuration
    task_default_queue='default',
    task_queues={
        'default': {
            'exchange': 'default',
            'routing_key': 'default',
        },
        'email': {
            'exchange': 'email',
            'routing_key': 'email',
        },
        'media': {
            'exchange': 'media',
            'routing_key': 'media',
        },
        'reports': {
            'exchange': 'reports',
            'routing_key': 'reports',
        },
        'maintenance': {
            'exchange': 'maintenance',
            'routing_key': 'maintenance',
        },
    },
)

# Load task modules from all registered Django apps.
app.autodiscover_tasks()


def test_redis_connection(broker_url, max_retries=5, retry_delay=2):
    """Test Redis connection with retries"""
    for attempt in range(max_retries):
        try:
            with Connection(broker_url) as conn:
                conn.ensure_connection(max_retries=3)
                logger.info("Redis connection successful")
                return True
        except OperationalError as e:
            logger.warning(f"Redis connection attempt {attempt + 1} failed: {e}")
            if attempt < max_retries - 1:
                time.sleep(retry_delay)
            else:
                logger.error("Failed to connect to Redis after all retries")
                return False
    return False


# Signal handlers for comprehensive logging
@worker_ready.connect
def worker_ready_handler(sender=None, **kwargs):
    """Log when worker is ready"""
    logger.info(f"Celery worker {sender} is ready and waiting for tasks")
    
    # Test Redis connection on worker startup
    from django.conf import settings
    broker_url = getattr(settings, 'CELERY_BROKER_URL', 'redis://redis:6379/0')
    if not test_redis_connection(broker_url):
        logger.error("Worker started but Redis connection failed")


@worker_shutdown.connect
def worker_shutdown_handler(sender=None, **kwargs):
    """Log when worker is shutting down"""
    logger.info(f"Celery worker {sender} is shutting down")


@task_failure.connect
def task_failure_handler(sender=None, task_id=None, exception=None, traceback=None, einfo=None, **kwargs):
    """Log task failures with detailed information"""
    logger.error(
        f"Task {sender.name} (ID: {task_id}) failed: {exception}\n"
        f"Traceback: {traceback}"
    )


@task_retry.connect
def task_retry_handler(sender=None, task_id=None, reason=None, einfo=None, **kwargs):
    """Log task retries"""
    logger.warning(
        f"Task {sender.name} (ID: {task_id}) is being retried. Reason: {reason}"
    )


@task_success.connect
def task_success_handler(sender=None, task_id=None, result=None, **kwargs):
    """Log successful task completion"""
    logger.info(f"Task {sender.name} (ID: {task_id}) completed successfully")


@app.task(bind=True)
def debug_task(self):
    """Debug task for testing Celery functionality"""
    logger.info(f'Debug task executed: {self.request!r}')
    return f'Request: {self.request!r}'


@app.task(bind=True, max_retries=3, default_retry_delay=60)
def health_check_task(self):
    """Health check task to verify Celery is working"""
    try:
        from django.db import connection
        from django.core.cache import cache
        
        # Test database connection
        with connection.cursor() as cursor:
            cursor.execute("SELECT 1")
        
        # Test cache connection
        cache.set('celery_health_check', 'ok', 30)
        if cache.get('celery_health_check') != 'ok':
            raise Exception("Cache test failed")
        
        logger.info("Celery health check passed")
        return {
            'status': 'healthy',
            'timestamp': time.time(),
            'worker': self.request.hostname
        }
        
    except Exception as exc:
        logger.error(f"Celery health check failed: {exc}")
        if self.request.retries < self.max_retries:
            raise self.retry(exc=exc, countdown=60 * (2 ** self.request.retries))
        return {
            'status': 'unhealthy',
            'error': str(exc),
            'timestamp': time.time(),
            'worker': self.request.hostname
        }