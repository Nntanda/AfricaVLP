"""
Test settings for admin-backend
"""
from .settings import *

# Use SQLite for testing
DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.sqlite3',
        'NAME': ':memory:',
    }
}

# Allow migrations for tests to create tables
# MIGRATION_MODULES = DisableMigrations()

# Disable Redis/Celery for tests
CELERY_TASK_ALWAYS_EAGER = True
CELERY_TASK_EAGER_PROPAGATES = True

# Disable logging during tests
LOGGING = {
    'version': 1,
    'disable_existing_loggers': False,
    'handlers': {
        'null': {
            'class': 'logging.NullHandler',
        },
    },
    'root': {
        'handlers': ['null'],
    },
}

# Speed up password hashing for tests
PASSWORD_HASHERS = [
    'django.contrib.auth.hashers.MD5PasswordHasher',
]

# Disable debug toolbar for tests
if 'debug_toolbar' in INSTALLED_APPS:
    INSTALLED_APPS.remove('debug_toolbar')

if 'debug_toolbar.middleware.DebugToolbarMiddleware' in MIDDLEWARE:
    MIDDLEWARE.remove('debug_toolbar.middleware.DebugToolbarMiddleware')
# Override model management for tests
def make_models_managed():
    """Make all models managed for testing"""
    from django.apps import apps
    for model in apps.get_models():
        if hasattr(model._meta, 'managed') and not model._meta.managed:
            model._meta.managed = True

# Make models managed for testing
import django
if django.apps.apps.ready:
    make_models_managed()
else:
    # If apps aren't ready yet, we'll do this in a different way
    from django.core.management import execute_from_command_line
    import sys
    
    # Monkey patch to make models managed
    original_setup = django.setup
    def patched_setup():
        result = original_setup()
        make_models_managed()
        return result
    django.setup = patched_setup