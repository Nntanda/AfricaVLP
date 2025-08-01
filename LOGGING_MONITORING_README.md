# AU-VLP Logging and Monitoring Guide

This document describes the comprehensive logging and monitoring system implemented for the AU-VLP infrastructure.

## Overview

The AU-VLP system now includes:
- **Structured JSON logging** across all Django services
- **Request/response logging** with performance metrics
- **Service health monitoring** commands
- **Debugging utilities** for troubleshooting
- **System-wide monitoring** scripts

## Structured Logging

### Features

- **JSON-formatted logs** for easy parsing and analysis
- **Request/response tracking** with unique request IDs
- **Performance metrics** logging
- **Security event logging** for authentication and authorization
- **Business event logging** for audit trails
- **Database query logging** with performance monitoring

### Log Formats

All logs follow a consistent JSON structure:

```json
{
  "timestamp": "2024-01-31T10:30:00.000Z",
  "level": "INFO",
  "logger": "admin_backend.requests",
  "message": "Request GET /api/users/",
  "service": "admin-backend",
  "module": "views",
  "function": "list",
  "line": 42,
  "process_id": 1234,
  "thread_id": 5678,
  "extra": {
    "request_data": {
      "method": "GET",
      "path": "/api/users/",
      "user_id": 123,
      "remote_addr": "192.168.1.100"
    },
    "event_type": "request_start"
  }
}
```

### Log Files

Each service generates multiple log files:

#### Admin Backend (`admin-backend/logs/`)
- `django_structured.log` - Main application logs
- `requests.log` - HTTP request/response logs
- `database.log` - Database query logs
- `errors.log` - Error-level logs only
- `celery.log` - Celery worker logs
- `celery_tasks.log` - Celery task execution logs

#### Wellknown Backend (`wellknown-backend/logs/`)
- `django_structured.log` - Main application logs
- `requests.log` - HTTP request/response logs
- `database.log` - Database query logs
- `errors.log` - Error-level logs only

### Using Logging Utilities

#### Import the utilities:

```python
from models_app.utils.logging_utils import (
    get_logger, log_security_event, log_user_action, 
    log_business_event, log_performance_metric
)

# Or for wellknown-backend:
from core.utils.logging_utils import (
    get_logger, log_security_event, log_user_action,
    log_business_event, log_performance_metric
)
```

#### Basic logging:

```python
logger = get_logger('my_module')
logger.info("Something happened", extra={'user_id': 123, 'action': 'create'})
```

#### Security events:

```python
log_security_event(
    'login_attempt',
    'User attempted login',
    user_id=123,
    ip_address='192.168.1.100',
    success=True
)
```

#### Business events:

```python
log_business_event(
    'user_registration',
    'New user registered',
    user_id=456,
    email='user@example.com'
)
```

#### Performance metrics:

```python
log_performance_metric('api_response_time', 250.5, 'ms', endpoint='/api/users/')
```

#### Function call logging decorator:

```python
@log_function_call('my_module', log_args=True, log_result=False)
def my_function(param1, param2):
    return "result"
```

## Service Monitoring

### Health Check Commands

Both services include comprehensive health monitoring commands.

#### Admin Backend

```bash
# Single health check
docker exec admin-backend python manage.py service_monitor

# Continuous monitoring
docker exec admin-backend python manage.py service_monitor --continuous --interval 30

# JSON output
docker exec admin-backend python manage.py service_monitor --format json
```

#### Wellknown Backend

```bash
# Single health check
docker exec wellknown-backend python manage.py service_monitor

# Continuous monitoring
docker exec wellknown-backend python manage.py service_monitor --continuous --interval 30

# JSON output
docker exec wellknown-backend python manage.py service_monitor --format json
```

### Health Check Results

The health checks monitor:

- **Database connectivity** and performance
- **Redis connectivity** and cache operations
- **Inter-service communication** (admin ↔ wellknown)
- **Nginx proxy** health
- **Container resources** (CPU, memory, disk)
- **Celery workers** (admin-backend only)

Example output:
```
=== Service Health Check - 2024-01-31T10:30:00.000Z ===
Service: admin-backend
Overall Status: HEALTHY

--- Individual Checks ---
database             HEALTHY         (45ms)
redis                HEALTHY         (12ms)
wellknown_backend    HEALTHY         (89ms)
nginx                HEALTHY         (23ms)
container            HEALTHY         (N/A)
resources            HEALTHY         (N/A)
celery               HEALTHY         (N/A)
```

## Debugging Utilities

### Service Debug Commands

For troubleshooting connectivity and configuration issues.

#### Admin Backend

```bash
# Debug all services
docker exec admin-backend python manage.py debug_services

# Debug specific service
docker exec admin-backend python manage.py debug_services --service database

# Verbose output
docker exec admin-backend python manage.py debug_services --verbose

# JSON output
docker exec admin-backend python manage.py debug_services --format json
```

Available debug targets:
- `all` - Debug all services
- `database` - Database connectivity and configuration
- `redis` - Redis connectivity and cache operations
- `wellknown` - Wellknown-backend service connectivity
- `nginx` - Nginx proxy connectivity
- `celery` - Celery worker status
- `network` - Network connectivity and DNS resolution

#### Wellknown Backend

```bash
# Debug all services
docker exec wellknown-backend python manage.py debug_services

# Debug specific service
docker exec wellknown-backend python manage.py debug_services --service database
```

Available debug targets:
- `all` - Debug all services
- `database` - Database connectivity and configuration
- `redis` - Redis connectivity and cache operations
- `admin` - Admin-backend service connectivity
- `nginx` - Nginx proxy connectivity
- `network` - Network connectivity and DNS resolution

## System-Wide Monitoring

### System Monitor Script

The `system_monitor.py` script provides comprehensive monitoring from outside the containers.

#### Usage

```bash
# Single system check
python system_monitor.py

# Continuous monitoring
python system_monitor.py --continuous --interval 60

# JSON output
python system_monitor.py --format json

# Windows batch file
monitor_system.bat --continuous
```

#### What it monitors

- **Docker containers** - Status and resource usage
- **Network connectivity** - Port accessibility for all services
- **Service health endpoints** - HTTP health checks
- **Database connectivity** - Direct MySQL connection test
- **System resources** - Container CPU and memory usage

#### Example output

```
=== AU-VLP System Monitor - 2024-01-31T10:30:00.000Z ===
Overall Status: HEALTHY
Check Duration: 2847ms

--- Detailed Results ---

CONTAINERS:
  ✓ Status: HEALTHY

NETWORK:
  ✓ Status: HEALTHY
  Services:
    ✓ mysql: HEALTHY (23ms)
    ✓ redis: HEALTHY (12ms)
    ✓ admin-backend: HEALTHY (45ms)
    ✓ wellknown-backend: HEALTHY (67ms)
    ✓ nginx: HEALTHY (34ms)

SERVICES:
  ✓ Status: HEALTHY
  Services:
    ✓ admin-backend: HEALTHY (156ms)
    ✓ wellknown-backend: HEALTHY (134ms)
    ✓ nginx: HEALTHY (89ms)
```

## Log Analysis

### Viewing Logs

#### Real-time log monitoring

```bash
# Admin backend logs
docker exec admin-backend tail -f logs/django_structured.log

# Request logs
docker exec admin-backend tail -f logs/requests.log

# Error logs only
docker exec admin-backend tail -f logs/errors.log
```

#### Searching logs

```bash
# Search for specific user actions
docker exec admin-backend grep "user_id.*123" logs/requests.log

# Search for errors
docker exec admin-backend grep "ERROR" logs/django_structured.log

# Search for slow queries
docker exec admin-backend grep "Slow database query" logs/database.log
```

### Log Rotation

Logs are automatically rotated when they reach:
- **50MB** for main application logs
- **20MB** for database and error logs
- **10 backup files** are kept for each log type

## Performance Monitoring

### Key Metrics Logged

- **Request response times** - All HTTP requests
- **Database query times** - Individual queries and operations
- **Cache operation times** - Redis get/set operations
- **Function execution times** - Using the decorator
- **Health check durations** - Service monitoring times

### Slow Query Detection

Database queries slower than 100ms are automatically logged as warnings:

```json
{
  "level": "WARNING",
  "message": "Slow database query (0.156s)",
  "extra": {
    "query_duration_ms": 156.7,
    "sql": "SELECT * FROM users WHERE status = 1",
    "event_type": "database_query"
  }
}
```

## Security Monitoring

### Security Events Logged

- **Login attempts** (successful and failed)
- **Password changes**
- **Token validation failures**
- **Unauthorized access attempts**
- **Suspicious activity patterns**

### Example Security Log

```json
{
  "level": "WARNING",
  "message": "Security event: Failed login attempt",
  "extra": {
    "security_event_type": "login_failed",
    "user_id": null,
    "ip_address": "192.168.1.100",
    "email": "attacker@example.com",
    "event_type": "security_event"
  }
}
```

## Troubleshooting

### Common Issues

#### 1. Logs not appearing
- Check if log directories exist: `docker exec admin-backend ls -la logs/`
- Verify log level configuration in Django settings
- Check file permissions

#### 2. Health checks failing
- Run debug commands to identify specific issues
- Check network connectivity between containers
- Verify service configurations

#### 3. High resource usage warnings
- Check container stats: `docker stats`
- Review slow query logs
- Monitor request patterns

### Getting Help

1. **Check service logs** first for error messages
2. **Run health checks** to identify failing components
3. **Use debug commands** for detailed troubleshooting
4. **Monitor system resources** for performance issues

## Configuration

### Environment Variables

Key environment variables for logging:

- `DEBUG` - Enables debug-level logging
- `LOG_LEVEL` - Override default log level
- `STRUCTURED_LOGGING` - Enable/disable JSON formatting

### Customizing Log Levels

Edit the logging configuration in Django settings:

```python
LOGGING['loggers']['my_app'] = {
    'handlers': ['console_structured', 'file_structured'],
    'level': 'DEBUG',
    'propagate': False,
}
```

### Adding Custom Loggers

```python
from models_app.utils.logging_utils import get_logger

# Create custom logger
logger = get_logger('my_custom_module')

# Use with structured data
logger.info("Custom event", extra={
    'event_type': 'custom_event',
    'custom_field': 'custom_value'
})
```

This comprehensive logging and monitoring system provides full visibility into the AU-VLP infrastructure, enabling proactive monitoring, quick troubleshooting, and detailed audit trails.