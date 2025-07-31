# Celery Background Tasks Documentation

This document describes the Celery background task system implemented for the AU-VLP admin backend.

## Overview

The system provides background task processing for:
- Email notifications
- Image processing
- Data export
- Content publishing
- System maintenance

## Setup and Configuration

### Requirements

The following packages are required (already included in requirements.txt):
- `celery`
- `django-celery-beat`
- `flower`
- `redis`
- `Pillow`

### Redis Configuration

Celery uses Redis as both message broker and result backend. Configure in settings.py:

```python
CELERY_BROKER_URL = 'redis://redis:6379/0'
CELERY_RESULT_BACKEND = 'redis://redis:6379/0'
```

### Starting Celery Services

#### Using Docker Compose (Recommended)

```bash
docker-compose up celery-worker celery-beat celery-flower
```

#### Manual Start (Development)

**Linux/Mac:**
```bash
./start_celery.sh
```

**Windows:**
```batch
start_celery.bat
```

**Individual Services:**
```bash
# Worker
celery -A admin_backend worker --loglevel=info --concurrency=4

# Beat Scheduler
celery -A admin_backend beat --loglevel=info --scheduler django_celery_beat.schedulers:DatabaseScheduler

# Flower Monitoring
celery -A admin_backend flower --port=5555
```

## Available Tasks

### Email Tasks

#### send_notification_email
Send a single notification email using templates.

```python
from models_app.tasks import send_notification_email

result = send_notification_email.delay(
    recipient_email='user@example.com',
    subject='Welcome to AU-VLP',
    template_name='welcome_user',
    context={'user': user_object}
)
```

#### send_bulk_notification_emails
Send emails to multiple recipients.

```python
from models_app.tasks import send_bulk_notification_emails

result = send_bulk_notification_emails.delay(
    recipient_emails=['user1@example.com', 'user2@example.com'],
    subject='Newsletter',
    template_name='newsletter',
    context={'content': 'Newsletter content'}
)
```

#### send_welcome_email
Send welcome email to new users or admins.

```python
from models_app.tasks import send_welcome_email

# For new user
result = send_welcome_email.delay(user_id=123, user_type='user')

# For new admin
result = send_welcome_email.delay(admin_id=456, user_type='admin')
```

### Image Processing Tasks

#### process_uploaded_image
Process uploaded images: resize, optimize, create thumbnails.

```python
from models_app.tasks import process_uploaded_image

result = process_uploaded_image.delay(
    file_path='uploads/image.jpg',
    max_width=1200,
    max_height=800,
    quality=85
)
```

### Data Export Tasks

#### export_data_to_csv
Export model data to CSV files.

```python
from models_app.tasks import export_data_to_csv

result = export_data_to_csv.delay(
    model_name='user',
    filters={'status': 1},
    fields=['id', 'email', 'first_name', 'last_name']
)
```

#### generate_admin_report
Generate administrative reports.

```python
from models_app.tasks import generate_admin_report

result = generate_admin_report.delay(
    report_type='activity',  # 'activity', 'users', or 'content'
    date_from='2024-01-01',
    date_to='2024-01-31'
)
```

### Content Publishing Tasks

#### publish_scheduled_content
Publish content scheduled for publication (runs automatically).

```python
from models_app.tasks import publish_scheduled_content

result = publish_scheduled_content.delay()
```

#### cleanup_expired_content
Clean up old activity logs and expired content.

```python
from models_app.tasks import cleanup_expired_content

result = cleanup_expired_content.delay()
```

### System Maintenance Tasks

#### system_health_check
Perform system health checks.

```python
from models_app.tasks import system_health_check

result = system_health_check.delay()
```

## Scheduled Tasks

The following tasks run automatically via Celery Beat:

- **publish_scheduled_content**: Every 5 minutes
- **cleanup_expired_content**: Daily
- **system_health_check**: Every 30 minutes

## API Endpoints

### Task Management Endpoints

All endpoints require authentication and are prefixed with `/api/v1/tasks/`.

#### POST /api/v1/tasks/email/send/
Send single email notification.

**Request Body:**
```json
{
    "recipient_email": "user@example.com",
    "subject": "Test Subject",
    "template_name": "test_template",
    "context": {"name": "Test User"}
}
```

#### POST /api/v1/tasks/email/bulk/
Send bulk email notifications.

**Request Body:**
```json
{
    "recipient_emails": ["user1@example.com", "user2@example.com"],
    "subject": "Bulk Email",
    "template_name": "newsletter",
    "context": {"content": "Newsletter content"}
}
```

#### POST /api/v1/tasks/image/process/
Process uploaded image.

**Request Body:**
```json
{
    "file_path": "uploads/image.jpg",
    "max_width": 1200,
    "max_height": 800,
    "quality": 85
}
```

#### POST /api/v1/tasks/export/
Export data to CSV.

**Request Body:**
```json
{
    "model_name": "user",
    "filters": {"status": 1},
    "fields": ["id", "email", "first_name"]
}
```

#### POST /api/v1/tasks/report/
Generate admin report.

**Request Body:**
```json
{
    "report_type": "activity",
    "date_from": "2024-01-01",
    "date_to": "2024-01-31"
}
```

#### GET /api/v1/tasks/status/{task_id}/
Get task status and result.

**Response:**
```json
{
    "task_id": "abc123",
    "status": "SUCCESS",
    "ready": true,
    "successful": true,
    "result": {"success": true, "message": "Task completed"}
}
```

#### GET /api/v1/tasks/workers/
Get Celery worker status.

**Response:**
```json
{
    "timestamp": "2024-01-01T12:00:00Z",
    "total_workers": 1,
    "total_active_tasks": 2,
    "workers": {"worker1": {"total": 10}}
}
```

#### DELETE /api/v1/tasks/revoke/{task_id}/
Revoke a running task.

## Monitoring

### Flower Web Interface

Access the Flower monitoring interface at: http://localhost:5555

Features:
- Real-time task monitoring
- Worker status and statistics
- Task history and results
- Task retry and revoke capabilities

### Django Admin

Task results are stored in the `TaskResult` model and can be viewed in Django Admin.

### Management Commands

#### Monitor Celery Tasks

```bash
python manage.py celery_monitor
```

Options:
- `--format json`: Output in JSON format
- `--watch`: Watch for real-time updates
- `--interval 5`: Update interval in seconds

## Email Templates

Email templates are stored in `templates/emails/` directory:

- `welcome_admin.html` / `welcome_admin.txt`: Admin welcome email
- `welcome_user.html` / `welcome_user.txt`: User welcome email

### Template Context Variables

Templates have access to:
- `user`: User or Admin object
- `frontend_url`: Frontend application URL
- `site_name`: Site name
- Custom context variables passed to the task

## Error Handling

### Task Retries

Tasks automatically retry on failure with exponential backoff:
- Maximum retries: 3
- Retry delay: 60 seconds * (2 ^ retry_count)

### Error Logging

All task errors are logged to:
- Django logging system
- Celery result backend
- TaskResult model (if enabled)

### Task Monitoring

Monitor task failures through:
- Flower web interface
- Django admin TaskResult model
- Application logs

## Performance Considerations

### Worker Configuration

- **Concurrency**: Set based on CPU cores and task types
- **Memory**: Monitor memory usage for image processing tasks
- **Prefetch**: Adjust prefetch multiplier for task distribution

### Task Optimization

- Use `bind=True` for tasks that need retry logic
- Implement task result expiration
- Use task routing for different task types
- Monitor task execution time

### Scaling

- Add more worker processes for CPU-bound tasks
- Use separate queues for different task types
- Implement task prioritization
- Consider using multiple Redis instances

## Troubleshooting

### Common Issues

1. **Redis Connection Error**
   - Check Redis server status
   - Verify connection settings
   - Check network connectivity

2. **Worker Not Starting**
   - Check Python path and virtual environment
   - Verify Django settings
   - Check for import errors

3. **Tasks Not Executing**
   - Verify worker is running
   - Check task routing configuration
   - Monitor Celery logs

4. **Memory Issues**
   - Monitor worker memory usage
   - Adjust concurrency settings
   - Implement task result cleanup

### Debugging

Enable debug logging:

```python
LOGGING = {
    'loggers': {
        'celery': {
            'handlers': ['console'],
            'level': 'DEBUG',
        },
    },
}
```

### Health Checks

Use the system health check task to verify:
- Database connectivity
- Cache connectivity
- Storage accessibility

```python
from models_app.tasks import system_health_check
result = system_health_check.delay()
print(result.get())
```

## Security Considerations

- Validate all task inputs
- Sanitize file paths for image processing
- Limit email recipients for bulk operations
- Implement rate limiting for API endpoints
- Use secure file storage for exports
- Encrypt sensitive task results

## Best Practices

1. **Task Design**
   - Keep tasks idempotent
   - Use appropriate timeouts
   - Implement proper error handling
   - Log task execution details

2. **Resource Management**
   - Clean up temporary files
   - Limit task execution time
   - Monitor resource usage
   - Implement result expiration

3. **Monitoring**
   - Set up alerting for failed tasks
   - Monitor worker health
   - Track task execution metrics
   - Regular performance reviews

4. **Deployment**
   - Use process managers (systemd, supervisor)
   - Implement graceful shutdowns
   - Configure log rotation
   - Set up monitoring and alerting