"""
Enhanced logging configuration for wellknown-backend service.
Provides structured logging with JSON formatting and request/response tracking.
"""

import json
import logging
import time
from datetime import datetime
from typing import Any, Dict, Optional
from django.conf import settings
from django.http import HttpRequest, HttpResponse
from django.utils.deprecation import MiddlewareMixin


class StructuredFormatter(logging.Formatter):
    """
    Custom formatter that outputs structured JSON logs.
    """
    
    def format(self, record: logging.LogRecord) -> str:
        """Format log record as structured JSON."""
        log_data = {
            'timestamp': datetime.utcnow().isoformat() + 'Z',
            'level': record.levelname,
            'logger': record.name,
            'message': record.getMessage(),
            'service': 'wellknown-backend',
            'module': record.module,
            'function': record.funcName,
            'line': record.lineno,
            'process_id': record.process,
            'thread_id': record.thread,
        }
        
        # Add exception information if present
        if record.exc_info:
            log_data['exception'] = {
                'type': record.exc_info[0].__name__ if record.exc_info[0] else None,
                'message': str(record.exc_info[1]) if record.exc_info[1] else None,
                'traceback': self.formatException(record.exc_info) if record.exc_info else None
            }
        
        # Add extra fields from the log record
        extra_fields = {}
        for key, value in record.__dict__.items():
            if key not in ['name', 'msg', 'args', 'levelname', 'levelno', 'pathname', 
                          'filename', 'module', 'lineno', 'funcName', 'created', 
                          'msecs', 'relativeCreated', 'thread', 'threadName', 
                          'processName', 'process', 'getMessage', 'exc_info', 
                          'exc_text', 'stack_info']:
                extra_fields[key] = value
        
        if extra_fields:
            log_data['extra'] = extra_fields
        
        return json.dumps(log_data, default=str, ensure_ascii=False)


class RequestResponseLoggingMiddleware(MiddlewareMixin):
    """
    Middleware to log HTTP requests and responses with structured data.
    """
    
    def __init__(self, get_response):
        self.get_response = get_response
        self.logger = logging.getLogger('wellknown_backend.requests')
        super().__init__(get_response)
    
    def process_request(self, request: HttpRequest) -> None:
        """Log incoming request details."""
        request._start_time = time.time()
        
        # Skip logging for certain paths to reduce noise
        skip_paths = ['/health/', '/admin/jsi18n/', '/static/', '/media/']
        if any(request.path.startswith(path) for path in skip_paths):
            request._skip_logging = True
            return
        
        request._skip_logging = False
        
        # Log request details
        request_data = {
            'request_id': getattr(request, 'request_id', None),
            'method': request.method,
            'path': request.path,
            'query_params': dict(request.GET),
            'user_id': request.user.id if hasattr(request, 'user') and request.user.is_authenticated else None,
            'user_agent': request.META.get('HTTP_USER_AGENT', ''),
            'remote_addr': self._get_client_ip(request),
            'content_type': request.content_type,
            'content_length': request.META.get('CONTENT_LENGTH', 0),
        }
        
        # Add request body for non-GET requests (with size limit)
        if request.method != 'GET' and hasattr(request, 'body'):
            try:
                body_size = len(request.body)
                if body_size > 0 and body_size < 10000:  # Log body only if < 10KB
                    if request.content_type == 'application/json':
                        try:
                            request_data['body'] = json.loads(request.body.decode('utf-8'))
                        except (json.JSONDecodeError, UnicodeDecodeError):
                            request_data['body'] = '<binary_or_invalid_json>'
                    else:
                        request_data['body'] = '<non_json_content>'
                elif body_size >= 10000:
                    request_data['body'] = f'<large_content_{body_size}_bytes>'
            except Exception:
                request_data['body'] = '<error_reading_body>'
        
        self.logger.info(
            f"Request {request.method} {request.path}",
            extra={'request_data': request_data, 'event_type': 'request_start'}
        )
    
    def process_response(self, request: HttpRequest, response: HttpResponse) -> HttpResponse:
        """Log response details."""
        if getattr(request, '_skip_logging', True):
            return response
        
        duration = time.time() - getattr(request, '_start_time', time.time())
        
        response_data = {
            'request_id': getattr(request, 'request_id', None),
            'method': request.method,
            'path': request.path,
            'status_code': response.status_code,
            'duration_ms': round(duration * 1000, 2),
            'content_type': response.get('Content-Type', ''),
            'content_length': len(response.content) if hasattr(response, 'content') else 0,
        }
        
        # Determine log level based on status code
        if response.status_code >= 500:
            log_level = logging.ERROR
        elif response.status_code >= 400:
            log_level = logging.WARNING
        else:
            log_level = logging.INFO
        
        self.logger.log(
            log_level,
            f"Response {response.status_code} for {request.method} {request.path} ({duration:.3f}s)",
            extra={'response_data': response_data, 'event_type': 'request_end'}
        )
        
        return response
    
    def process_exception(self, request: HttpRequest, exception: Exception) -> None:
        """Log unhandled exceptions."""
        if getattr(request, '_skip_logging', True):
            return
        
        duration = time.time() - getattr(request, '_start_time', time.time())
        
        exception_data = {
            'request_id': getattr(request, 'request_id', None),
            'method': request.method,
            'path': request.path,
            'duration_ms': round(duration * 1000, 2),
            'exception_type': type(exception).__name__,
            'exception_message': str(exception),
        }
        
        self.logger.error(
            f"Exception in {request.method} {request.path}: {type(exception).__name__}: {exception}",
            extra={'exception_data': exception_data, 'event_type': 'request_exception'},
            exc_info=True
        )
    
    def _get_client_ip(self, request: HttpRequest) -> str:
        """Extract client IP address from request."""
        x_forwarded_for = request.META.get('HTTP_X_FORWARDED_FOR')
        if x_forwarded_for:
            ip = x_forwarded_for.split(',')[0].strip()
        else:
            ip = request.META.get('REMOTE_ADDR', '')
        return ip


class DatabaseQueryLoggingHandler(logging.Handler):
    """
    Custom handler to log database queries with performance metrics.
    """
    
    def emit(self, record: logging.LogRecord) -> None:
        """Process database query log records."""
        if hasattr(record, 'duration') and hasattr(record, 'sql'):
            query_data = {
                'query_duration_ms': float(record.duration) * 1000,
                'sql': record.sql,
                'params': getattr(record, 'params', None),
                'event_type': 'database_query'
            }
            
            # Log slow queries as warnings
            if float(record.duration) > 0.1:  # Queries slower than 100ms
                level = logging.WARNING
                message = f"Slow database query ({record.duration}s)"
            else:
                level = logging.DEBUG
                message = f"Database query ({record.duration}s)"
            
            # Create new log record for structured logging
            logger = logging.getLogger('wellknown_backend.database')
            logger.log(level, message, extra=query_data)


def get_enhanced_logging_config(base_dir, debug=False):
    """
    Generate enhanced logging configuration with structured formatting.
    """
    log_level = 'DEBUG' if debug else 'INFO'
    
    return {
        'version': 1,
        'disable_existing_loggers': False,
        'formatters': {
            'structured': {
                '()': 'core.logging_config.StructuredFormatter',
            },
            'verbose': {
                'format': '{levelname} {asctime} {module} {process:d} {thread:d} {message}',
                'style': '{',
            },
            'simple': {
                'format': '{levelname} {message}',
                'style': '{',
            },
        },
        'handlers': {
            'console_structured': {
                'level': log_level,
                'class': 'logging.StreamHandler',
                'formatter': 'structured',
            },
            'console_simple': {
                'level': 'DEBUG',
                'class': 'logging.StreamHandler',
                'formatter': 'simple',
            },
            'file_structured': {
                'level': 'INFO',
                'class': 'logging.handlers.RotatingFileHandler',
                'filename': base_dir / 'logs' / 'django_structured.log',
                'formatter': 'structured',
                'maxBytes': 50 * 1024 * 1024,  # 50MB
                'backupCount': 10,
            },
            'file_requests': {
                'level': 'INFO',
                'class': 'logging.handlers.RotatingFileHandler',
                'filename': base_dir / 'logs' / 'requests.log',
                'formatter': 'structured',
                'maxBytes': 50 * 1024 * 1024,  # 50MB
                'backupCount': 10,
            },
            'file_database': {
                'level': 'DEBUG',
                'class': 'logging.handlers.RotatingFileHandler',
                'filename': base_dir / 'logs' / 'database.log',
                'formatter': 'structured',
                'maxBytes': 20 * 1024 * 1024,  # 20MB
                'backupCount': 5,
            },
            'file_errors': {
                'level': 'ERROR',
                'class': 'logging.handlers.RotatingFileHandler',
                'filename': base_dir / 'logs' / 'errors.log',
                'formatter': 'structured',
                'maxBytes': 20 * 1024 * 1024,  # 20MB
                'backupCount': 10,
            },
        },
        'root': {
            'handlers': ['console_structured', 'file_structured'],
            'level': log_level,
        },
        'loggers': {
            'django': {
                'handlers': ['console_structured', 'file_structured'],
                'level': 'INFO',
                'propagate': False,
            },
            'django.request': {
                'handlers': ['console_structured', 'file_errors'],
                'level': 'ERROR',
                'propagate': False,
            },
            'django.db.backends': {
                'handlers': ['file_database'],
                'level': 'DEBUG' if debug else 'INFO',
                'propagate': False,
            },
            'wellknown_backend.requests': {
                'handlers': ['console_structured', 'file_requests'],
                'level': 'INFO',
                'propagate': False,
            },
            'wellknown_backend.database': {
                'handlers': ['file_database'],
                'level': 'DEBUG',
                'propagate': False,
            },
            'core': {
                'handlers': ['console_structured', 'file_structured'],
                'level': log_level,
                'propagate': False,
            },
            'authentication': {
                'handlers': ['console_structured', 'file_structured'],
                'level': log_level,
                'propagate': False,
            },
        },
    }