# Enhanced Celery Configuration

This document describes the enhanced Celery configuration implemented for the AU-VLP admin backend, including error handling, Redis connection management, logging, and restart policies.

## Overview

The enhanced Celery configuration provides:
- Robust Redis connection handling with automatic retries
- Comprehensive logging for all Celery components
- Proper error handling and task retry mechanisms
- Queue-based task routing for better performance
- Health monitoring and diagnostics
- Graceful restart policies

## Configuration Components

### 1. Celery Application Configuration (`admin_backend/celery.py`)

#### Enhanced Features:
- **Connection Retry Logic**: Automatic retry on Redis connection failures
- **Task Routing**: Separate queues for different task types
- **Signal Handlers**: Comprehensive logging for worker lifecycle events
- **Health Check Tasks**: Built-in health monitoring capabilities

#### Key Settings:
```python
# Broker connection retry settings
broker_connection_retry_on_startup=True
broker_connection_retry=True
broker_connection_max_retries=10
broker_connection_retry_delay=5.0

# Task execution settings
task_acks_late=True
task_reject_on_worker_lost=True
task_track_started=True

# Worker settings
worker_prefetch_multiplier=1
worker_max_tasks_per_child=1000
```

### 2. Django Settings Configuration (`admin_backend/settings.py`)

#### Redis Connection Pool Settings:
- Socket keepalive for persistent connections
- Health check intervals
- Retry policies for connection timeouts

#### Task Annotations:
- Rate limiting per task type
- Custom retry policies
- Time limits for different task categories

### 3. Queue Configuration

The system uses multiple queues for task segregation:

| Queue | Purpose | Tasks |
|-------|---------|-------|
| `default` | General tasks | System tasks, debug tasks |
| `email` | Email processing | Notifications, bulk emails |
| `media` | Media processing | Image processing, file uploads |
| `reports` | Report generation | Data exports, admin reports |
| `maintenance` | System maintenance | Health checks, cleanup tasks |

### 4. Logging Configuration

#### Log Files:
- `logs/celery.log` - General Celery worker and beat logs
- `logs/celery_tasks.log` - Task execution logs
- `logs/celery_worker.log` - Worker-specific logs (development)
- `logs/celery_beat.log` - Beat scheduler logs (development)
- `logs/flower.log` - Flower monitoring logs (development)

#### Log Rotation:
- Maximum file size: 10MB
- Backup count: 5 files
- Automatic rotation when size limit is reached

## Deployment Configurations

### Docker Compose Configuration

#### Celery Worker:
```yaml
celery-worker:
  command: >
    sh -c "
      echo 'Waiting for Redis to be ready...' &&
      while ! redis-cli -h redis -p 6379 ping > /dev/null 2>&1; do
        echo 'Redis not ready, waiting...'
        sleep 2
      done &&
      echo 'Starting Celery worker with enhanced configuration...' &&
      celery -A admin_backend worker 
        --loglevel=info 
        --concurrency=4 
        --max-tasks-per-child=1000 
        --prefetch-multiplier=1 
        --queues=default,email,media,reports,maintenance
        --hostname=worker@%h
    "
  restart: unless-stopped
  deploy:
    restart_policy:
      condition: on-failure
      delay: 5s
      max_attempts: 3
      window: 120s
```

#### Health Checks:
- Worker: `celery -A admin_backend inspect ping`
- Beat: Process and PID file checks
- Flower: HTTP API endpoint checks

### Development Scripts

#### Linux/macOS (`start_celery.sh`):
- Pre-flight checks for Redis and database connectivity
- Enhanced logging with timestamps and colors
- Graceful shutdown handling
- Continuous health monitoring
- Automatic log file creation

#### Windows (`start_celery.bat`):
- Similar functionality adapted for Windows
- Separate command windows for each service
- Enhanced error reporting
- Log file management

## Monitoring and Health Checks

### Built-in Health Check Task

The system includes a `health_check_task` that verifies:
- Database connectivity
- Cache (Redis) connectivity
- Worker responsiveness

### Management Commands

#### `celery_health`
Comprehensive health monitoring command with options:
- `--format json|table|summary` - Output format
- `--watch` - Real-time monitoring
- `--test-tasks` - Execute test tasks
- `--check-redis` - Detailed Redis diagnostics

Usage examples:
```bash
# Basic health check
python manage.py celery_health

# Real-time monitoring
python manage.py celery_health --watch --interval 5

# Test task execution
python manage.py celery_health --test-tasks

# Detailed Redis check
python manage.py celery_health --check-redis --format json
```

#### `celery_monitor`
Enhanced monitoring command for task and worker inspection:
- Real-time task monitoring
- Worker statistics
- Queue length monitoring
- JSON and table output formats

## Error Handling and Retry Policies

### Task-Level Error Handling

All tasks implement:
- Exponential backoff retry strategy
- Maximum retry limits
- Comprehensive error logging
- Graceful degradation

### Connection Error Handling

Redis connection issues are handled through:
- Automatic connection retry with exponential backoff
- Connection pooling with keepalive
- Health check intervals
- Graceful fallback mechanisms

### Worker Error Handling

Worker failures are managed via:
- Automatic restart policies
- Task acknowledgment after completion
- Worker process recycling
- Lost task recovery

## Performance Optimizations

### Worker Configuration
- `worker_prefetch_multiplier=1`: Prevents task hoarding
- `worker_max_tasks_per_child=1000`: Prevents memory leaks
- Queue-based routing: Improves task distribution

### Redis Optimizations
- Connection pooling
- Socket keepalive
- Health check intervals
- Persistent connections

### Task Optimizations
- Rate limiting per task type
- Appropriate time limits
- Queue segregation
- Batch processing for bulk operations

## Security Considerations

### Connection Security
- Redis connection authentication (if configured)
- Network isolation in Docker environment
- Secure inter-service communication

### Task Security
- Input validation in all tasks
- Secure file handling
- Email security measures
- Data sanitization

## Troubleshooting

### Common Issues

1. **Redis Connection Failures**
   - Check Redis service status
   - Verify network connectivity
   - Review connection settings
   - Check Redis logs

2. **Worker Not Starting**
   - Verify database connectivity
   - Check Redis availability
   - Review worker logs
   - Validate Django settings

3. **Tasks Not Processing**
   - Check queue lengths
   - Verify worker registration
   - Review task routing
   - Monitor worker health

4. **High Memory Usage**
   - Check `worker_max_tasks_per_child` setting
   - Monitor task complexity
   - Review memory-intensive tasks
   - Consider worker scaling

### Diagnostic Commands

```bash
# Check worker status
celery -A admin_backend inspect active

# Check queue lengths
python manage.py celery_health --check-redis

# Monitor in real-time
python manage.py celery_monitor --watch

# Test connectivity
python manage.py celery_health --test-tasks
```

## Maintenance

### Regular Tasks
- Monitor log file sizes
- Review error rates
- Check queue lengths
- Verify worker health

### Log Management
- Logs rotate automatically
- Archive old logs as needed
- Monitor disk space usage
- Review error patterns

### Performance Monitoring
- Use Flower for real-time monitoring
- Monitor task execution times
- Track error rates
- Review resource usage

## Environment Variables

Key environment variables for Celery configuration:

```bash
# Redis Configuration
CELERY_BROKER_URL=redis://redis:6379/0
CELERY_RESULT_BACKEND=redis://redis:6379/0
REDIS_HOST=redis
REDIS_PORT=6379

# Worker Configuration
CELERY_WORKER_CONCURRENCY=4
CELERY_WORKER_MAX_TASKS_PER_CHILD=1000

# Logging
CELERY_LOG_LEVEL=INFO
```

## Conclusion

This enhanced Celery configuration provides a robust, scalable, and maintainable task processing system for the AU-VLP admin backend. The configuration emphasizes reliability, observability, and performance while maintaining ease of deployment and maintenance.