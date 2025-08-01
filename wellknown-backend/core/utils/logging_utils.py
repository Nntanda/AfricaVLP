"""
Logging utilities for wellknown-backend service.
Provides helper functions for consistent structured logging.
"""

import logging
import time
import functools
from typing import Any, Dict, Optional, Callable
from django.conf import settings
from django.db import connection


def get_logger(name: str) -> logging.Logger:
    """Get a logger with the service prefix."""
    return logging.getLogger(f'wellknown_backend.{name}')


def log_function_call(logger_name: str = None, log_args: bool = False, log_result: bool = False):
    """
    Decorator to log function calls with execution time.
    
    Args:
        logger_name: Name of the logger to use (defaults to module name)
        log_args: Whether to log function arguments
        log_result: Whether to log function return value
    """
    def decorator(func: Callable) -> Callable:
        @functools.wraps(func)
        def wrapper(*args, **kwargs):
            logger = get_logger(logger_name or func.__module__)
            start_time = time.time()
            
            log_data = {
                'function': func.__name__,
                'module': func.__module__,
                'event_type': 'function_call_start'
            }
            
            if log_args:
                log_data['args'] = str(args)
                log_data['kwargs'] = str(kwargs)
            
            logger.debug(f"Calling {func.__name__}", extra=log_data)
            
            try:
                result = func(*args, **kwargs)
                duration = time.time() - start_time
                
                log_data.update({
                    'duration_ms': round(duration * 1000, 2),
                    'event_type': 'function_call_success'
                })
                
                if log_result:
                    log_data['result'] = str(result)
                
                logger.debug(f"Completed {func.__name__} in {duration:.3f}s", extra=log_data)
                return result
                
            except Exception as e:
                duration = time.time() - start_time
                
                log_data.update({
                    'duration_ms': round(duration * 1000, 2),
                    'error': str(e),
                    'error_type': type(e).__name__,
                    'event_type': 'function_call_error'
                })
                
                logger.error(f"Error in {func.__name__}: {e}", extra=log_data, exc_info=True)
                raise
        
        return wrapper
    return decorator


def log_database_operation(operation: str, table: str = None, **extra_data):
    """
    Log database operations with query count and timing.
    
    Args:
        operation: Description of the database operation
        table: Table name being operated on
        **extra_data: Additional data to include in the log
    """
    logger = get_logger('database')
    
    initial_queries = len(connection.queries)
    start_time = time.time()
    
    def log_completion():
        duration = time.time() - start_time
        query_count = len(connection.queries) - initial_queries
        
        log_data = {
            'operation': operation,
            'table': table,
            'duration_ms': round(duration * 1000, 2),
            'query_count': query_count,
            'event_type': 'database_operation',
            **extra_data
        }
        
        if query_count > 5:  # Log warning for operations with many queries
            logger.warning(f"Database operation '{operation}' used {query_count} queries", extra=log_data)
        else:
            logger.info(f"Database operation '{operation}' completed", extra=log_data)
    
    return log_completion


def log_api_call(endpoint: str, method: str, user_id: int = None, **extra_data):
    """
    Log API calls with structured data.
    
    Args:
        endpoint: API endpoint being called
        method: HTTP method
        user_id: ID of the user making the call
        **extra_data: Additional data to include in the log
    """
    logger = get_logger('api')
    
    log_data = {
        'endpoint': endpoint,
        'method': method,
        'user_id': user_id,
        'event_type': 'api_call',
        **extra_data
    }
    
    logger.info(f"API call {method} {endpoint}", extra=log_data)


def log_business_event(event_type: str, description: str, user_id: int = None, **extra_data):
    """
    Log business events with structured data.
    
    Args:
        event_type: Type of business event
        description: Human-readable description
        user_id: ID of the user associated with the event
        **extra_data: Additional event data
    """
    logger = get_logger('business')
    
    log_data = {
        'business_event_type': event_type,
        'description': description,
        'user_id': user_id,
        'event_type': 'business_event',
        **extra_data
    }
    
    logger.info(f"Business event: {description}", extra=log_data)


def log_security_event(event_type: str, description: str, user_id: int = None, ip_address: str = None, **extra_data):
    """
    Log security-related events.
    
    Args:
        event_type: Type of security event
        description: Human-readable description
        user_id: ID of the user associated with the event
        ip_address: IP address of the request
        **extra_data: Additional security data
    """
    logger = get_logger('security')
    
    log_data = {
        'security_event_type': event_type,
        'description': description,
        'user_id': user_id,
        'ip_address': ip_address,
        'event_type': 'security_event',
        **extra_data
    }
    
    # Security events are always logged as warnings or higher
    if event_type in ['failed_login', 'unauthorized_access', 'suspicious_activity']:
        logger.warning(f"Security event: {description}", extra=log_data)
    else:
        logger.info(f"Security event: {description}", extra=log_data)


def log_performance_metric(metric_name: str, value: float, unit: str = 'ms', **extra_data):
    """
    Log performance metrics.
    
    Args:
        metric_name: Name of the performance metric
        value: Metric value
        unit: Unit of measurement
        **extra_data: Additional metric data
    """
    logger = get_logger('performance')
    
    log_data = {
        'metric_name': metric_name,
        'metric_value': value,
        'metric_unit': unit,
        'event_type': 'performance_metric',
        **extra_data
    }
    
    logger.info(f"Performance metric: {metric_name} = {value}{unit}", extra=log_data)


class LoggingContext:
    """
    Context manager for adding consistent logging context.
    """
    
    def __init__(self, logger_name: str, context_data: Dict[str, Any]):
        self.logger = get_logger(logger_name)
        self.context_data = context_data
        self.start_time = None
    
    def __enter__(self):
        self.start_time = time.time()
        self.logger.debug("Starting operation", extra={
            'event_type': 'operation_start',
            **self.context_data
        })
        return self
    
    def __exit__(self, exc_type, exc_val, exc_tb):
        duration = time.time() - self.start_time
        
        log_data = {
            'duration_ms': round(duration * 1000, 2),
            **self.context_data
        }
        
        if exc_type is None:
            log_data['event_type'] = 'operation_success'
            self.logger.debug("Operation completed successfully", extra=log_data)
        else:
            log_data.update({
                'event_type': 'operation_error',
                'error_type': exc_type.__name__,
                'error_message': str(exc_val)
            })
            self.logger.error("Operation failed", extra=log_data, exc_info=True)
    
    def log(self, level: str, message: str, **extra_data):
        """Log a message within this context."""
        log_data = {**self.context_data, **extra_data}
        getattr(self.logger, level)(message, extra=log_data)


# Convenience functions for common logging patterns
def log_user_action(user_id: int, action: str, resource: str = None, **extra_data):
    """Log user actions for audit purposes."""
    log_business_event(
        'user_action',
        f"User {user_id} performed {action}" + (f" on {resource}" if resource else ""),
        user_id=user_id,
        action=action,
        resource=resource,
        **extra_data
    )


def log_system_event(event: str, status: str = 'success', **extra_data):
    """Log system events."""
    logger = get_logger('system')
    
    log_data = {
        'system_event': event,
        'status': status,
        'event_type': 'system_event',
        **extra_data
    }
    
    if status == 'error':
        logger.error(f"System event: {event} - {status}", extra=log_data)
    elif status == 'warning':
        logger.warning(f"System event: {event} - {status}", extra=log_data)
    else:
        logger.info(f"System event: {event} - {status}", extra=log_data)