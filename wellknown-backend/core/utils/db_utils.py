"""
Database utility functions for connection handling and retry logic.
"""
import time
import logging
from functools import wraps
from django.db import connections, OperationalError, InterfaceError
from django.db.utils import DatabaseError
from django.conf import settings

logger = logging.getLogger(__name__)


class DatabaseConnectionError(Exception):
    """Custom exception for database connection issues."""
    pass


def retry_db_operation(max_retries=3, base_delay=1, exponential_backoff=True):
    """
    Decorator to retry database operations with exponential backoff.
    
    Args:
        max_retries (int): Maximum number of retry attempts
        base_delay (float): Base delay between retries in seconds
        exponential_backoff (bool): Whether to use exponential backoff
    """
    def decorator(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            last_exception = None
            delay = base_delay
            
            for attempt in range(max_retries + 1):
                try:
                    return func(*args, **kwargs)
                except (OperationalError, InterfaceError, DatabaseError) as e:
                    last_exception = e
                    
                    if attempt == max_retries:
                        logger.error(
                            f"Database operation failed after {max_retries} retries: {e}"
                        )
                        break
                    
                    logger.warning(
                        f"Database operation failed (attempt {attempt + 1}/{max_retries + 1}): {e}. "
                        f"Retrying in {delay}s..."
                    )
                    
                    time.sleep(delay)
                    
                    if exponential_backoff:
                        delay = min(delay * 2, 30)  # Cap at 30 seconds
            
            raise DatabaseConnectionError(
                f"Database operation failed after {max_retries} retries: {last_exception}"
            )
        
        return wrapper
    return decorator


@retry_db_operation(max_retries=5, base_delay=2)
def test_database_connection(database_alias='default'):
    """
    Test database connection with retry logic.
    
    Args:
        database_alias (str): Database alias to test
        
    Returns:
        bool: True if connection is successful
        
    Raises:
        DatabaseConnectionError: If connection fails after retries
    """
    try:
        connection = connections[database_alias]
        
        # Ensure connection is established
        connection.ensure_connection()
        
        # Test with a simple query
        with connection.cursor() as cursor:
            cursor.execute('SELECT 1')
            result = cursor.fetchone()
            
        if result and result[0] == 1:
            logger.info(f"Database connection test successful for '{database_alias}'")
            return True
        else:
            raise OperationalError("Unexpected result from database test query")
            
    except Exception as e:
        logger.error(f"Database connection test failed for '{database_alias}': {e}")
        raise


def get_database_info(database_alias='default'):
    """
    Get database connection information.
    
    Args:
        database_alias (str): Database alias to check
        
    Returns:
        dict: Database connection information
    """
    try:
        connection = connections[database_alias]
        db_settings = connection.settings_dict
        
        return {
            'engine': db_settings.get('ENGINE', 'Unknown'),
            'name': db_settings.get('NAME', 'Unknown'),
            'host': db_settings.get('HOST', 'Unknown'),
            'port': db_settings.get('PORT', 'Unknown'),
            'user': db_settings.get('USER', 'Unknown'),
            'charset': db_settings.get('OPTIONS', {}).get('charset', 'Unknown'),
            'conn_max_age': db_settings.get('CONN_MAX_AGE', 0),
            'conn_health_checks': db_settings.get('CONN_HEALTH_CHECKS', False),
        }
    except Exception as e:
        logger.error(f"Failed to get database info for '{database_alias}': {e}")
        return {}


def close_database_connections():
    """Close all database connections."""
    try:
        for alias in connections:
            connections[alias].close()
        logger.info("All database connections closed")
    except Exception as e:
        logger.error(f"Error closing database connections: {e}")


def check_database_health(database_alias='default'):
    """
    Comprehensive database health check.
    
    Args:
        database_alias (str): Database alias to check
        
    Returns:
        dict: Health check results
    """
    health_status = {
        'database': database_alias,
        'status': 'unknown',
        'connection': False,
        'query_test': False,
        'info': {},
        'errors': []
    }
    
    try:
        # Get database info
        health_status['info'] = get_database_info(database_alias)
        
        # Test connection
        test_database_connection(database_alias)
        health_status['connection'] = True
        health_status['query_test'] = True
        health_status['status'] = 'healthy'
        
        logger.info(f"Database health check passed for '{database_alias}'")
        
    except DatabaseConnectionError as e:
        health_status['errors'].append(str(e))
        health_status['status'] = 'unhealthy'
        logger.error(f"Database health check failed for '{database_alias}': {e}")
        
    except Exception as e:
        health_status['errors'].append(f"Unexpected error: {e}")
        health_status['status'] = 'error'
        logger.error(f"Database health check error for '{database_alias}': {e}")
    
    return health_status


def wait_for_database(database_alias='default', timeout=60, interval=2):
    """
    Wait for database to become available.
    
    Args:
        database_alias (str): Database alias to wait for
        timeout (int): Maximum time to wait in seconds
        interval (int): Check interval in seconds
        
    Returns:
        bool: True if database becomes available
        
    Raises:
        DatabaseConnectionError: If database doesn't become available within timeout
    """
    start_time = time.time()
    attempt = 1
    
    logger.info(f"Waiting for database '{database_alias}' to become available...")
    
    while time.time() - start_time < timeout:
        try:
            test_database_connection(database_alias)
            elapsed_time = time.time() - start_time
            logger.info(
                f"Database '{database_alias}' is available! "
                f"(took {elapsed_time:.2f}s, {attempt} attempts)"
            )
            return True
            
        except DatabaseConnectionError:
            elapsed_time = time.time() - start_time
            remaining_time = timeout - elapsed_time
            
            if remaining_time <= 0:
                break
            
            logger.warning(
                f"Database '{database_alias}' not available (attempt {attempt}). "
                f"Retrying in {interval}s..."
            )
            
            time.sleep(min(interval, remaining_time))
            attempt += 1
    
    raise DatabaseConnectionError(
        f"Database '{database_alias}' is not available after {timeout}s timeout "
        f"({attempt} attempts)"
    )