#!/bin/bash
set -e

# Configuration
SERVICE_NAME="wellknown-backend"
DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"
DB_NAME="${DB_NAME:-africa_vlp}"
DB_USER="${DB_USER:-africa_vlp_user}"
REDIS_HOST="${REDIS_HOST:-redis}"
REDIS_PORT="${REDIS_PORT:-6379}"
MAX_RETRIES=30
RETRY_INTERVAL=2

# Logging functions
log_info() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] [INFO] [$SERVICE_NAME] $1"
}

log_error() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] [ERROR] [$SERVICE_NAME] $1" >&2
}

log_warn() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] [WARN] [$SERVICE_NAME] $1"
}

# Function to wait for a service with exponential backoff
wait_for_service() {
    local host=$1
    local port=$2
    local service_name=$3
    local max_attempts=$4
    local base_delay=$5
    
    log_info "Waiting for $service_name at $host:$port..."
    
    local attempt=1
    local delay=$base_delay
    
    while [ $attempt -le $max_attempts ]; do
        if python3 -c "
import socket
import sys
try:
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.settimeout(5)
    result = sock.connect_ex(('$host', $port))
    sock.close()
    sys.exit(0 if result == 0 else 1)
except Exception as e:
    sys.exit(1)
"; then
            log_info "$service_name is available"
            return 0
        fi
        
        log_warn "$service_name not available (attempt $attempt/$max_attempts). Retrying in ${delay}s..."
        sleep $delay
        
        # Exponential backoff with jitter
        delay=$((delay * 2))
        if [ $delay -gt 30 ]; then
            delay=30
        fi
        
        attempt=$((attempt + 1))
    done
    
    log_error "$service_name is not available after $max_attempts attempts"
    return 1
}

# Function to test database connectivity using Django management command
test_database_connection() {
    log_info "Testing database connection..."
    
    if python3 manage.py wait_for_db --timeout=30 --exponential-backoff 2>/dev/null; then
        log_info "Database connection test passed"
        return 0
    else
        log_error "Database connection test failed"
        return 1
    fi
}

# Function to test Redis connectivity
test_redis_connection() {
    log_info "Testing Redis connection..."
    
    if python3 -c "
import redis
import sys
try:
    r = redis.Redis(host='$REDIS_HOST', port=$REDIS_PORT, db=0, socket_timeout=5)
    r.ping()
    print('Redis connection successful')
except Exception as e:
    print(f'Redis connection failed: {e}')
    sys.exit(1)
" 2>/dev/null; then
        log_info "Redis connection test passed"
        return 0
    else
        log_warn "Redis connection test failed (non-critical)"
        return 1
    fi
}

# Function to create necessary directories with proper permissions
create_directories() {
    log_info "Creating necessary directories..."
    
    local dirs=("logs" "static" "media" "media/uploads" "static/admin" "static/css" "static/js")
    
    for dir in "${dirs[@]}"; do
        if mkdir -p "$dir" 2>/dev/null; then
            log_info "Created directory: $dir"
        else
            log_warn "Failed to create directory: $dir (may already exist)"
        fi
        
        # Set appropriate permissions
        chmod 755 "$dir" 2>/dev/null || log_warn "Failed to set permissions for $dir"
    done
}

# Function to run database migrations safely
run_migrations() {
    log_info "Running database migrations..."
    
    # First, check if we can connect to the database
    if ! test_database_connection; then
        log_error "Cannot connect to database, skipping migrations"
        return 1
    fi
    
    # Use safe migration command
    log_info "Running safe migration check..."
    if python3 manage.py safe_migrate --check-only 2>&1 | tee -a logs/migration_check.log; then
        log_info "Migration check passed, applying migrations..."
        
        # Run safe migrations
        if python3 manage.py safe_migrate 2>&1 | tee -a logs/migration.log; then
            log_info "Migrations completed successfully"
        else
            log_error "Migration failed, check logs/migration.log for details"
            return 1
        fi
    else
        log_error "Migration check failed, check logs/migration_check.log"
        return 1
    fi
    
    return 0
}

# Function to collect static files
collect_static_files() {
    log_info "Collecting static files..."
    
    if python3 manage.py collectstatic --noinput --clear 2>&1 | tee -a logs/collectstatic.log; then
        log_info "Static files collected successfully"
        return 0
    else
        log_error "Failed to collect static files, check logs/collectstatic.log"
        return 1
    fi
}

# Function to validate Django configuration
validate_django_config() {
    log_info "Validating Django configuration..."
    
    if python3 manage.py check --deploy 2>&1 | tee -a logs/django_check.log; then
        log_info "Django configuration validation passed"
        return 0
    else
        log_warn "Django configuration validation had warnings, check logs/django_check.log"
        return 0  # Don't fail on warnings, just log them
    fi
}

# Function to start Gunicorn with proper configuration
start_gunicorn() {
    log_info "Starting Gunicorn server..."
    
    # Gunicorn configuration
    local workers=${GUNICORN_WORKERS:-3}
    local timeout=${GUNICORN_TIMEOUT:-120}
    local max_requests=${GUNICORN_MAX_REQUESTS:-1000}
    local max_requests_jitter=${GUNICORN_MAX_REQUESTS_JITTER:-100}
    local bind_address="0.0.0.0:8000"
    
    log_info "Gunicorn configuration:"
    log_info "  Workers: $workers"
    log_info "  Timeout: $timeout"
    log_info "  Max requests: $max_requests"
    log_info "  Bind address: $bind_address"
    
    exec gunicorn wellknown_backend.wsgi:application \
        --bind "$bind_address" \
        --workers "$workers" \
        --timeout "$timeout" \
        --max-requests "$max_requests" \
        --max-requests-jitter "$max_requests_jitter" \
        --worker-class sync \
        --worker-connections 1000 \
        --preload \
        --access-logfile - \
        --error-logfile - \
        --log-level info \
        --capture-output \
        --enable-stdio-inheritance
}

# Cleanup function for graceful shutdown
cleanup() {
    log_info "Received shutdown signal, cleaning up..."
    # Add any cleanup tasks here
    exit 0
}

# Set up signal handlers
trap cleanup SIGTERM SIGINT

# Main execution flow
main() {
    log_info "Starting $SERVICE_NAME initialization..."
    
    # Create necessary directories
    create_directories
    
    # Wait for required services
    if ! wait_for_service "$DB_HOST" "$DB_PORT" "MySQL" "$MAX_RETRIES" "$RETRY_INTERVAL"; then
        log_error "MySQL service is not available, cannot start"
        exit 1
    fi
    
    # Test Redis (non-critical)
    test_redis_connection
    
    # Validate Django configuration
    if ! validate_django_config; then
        log_error "Django configuration validation failed"
        exit 1
    fi
    
    # Run database migrations
    if ! run_migrations; then
        log_error "Database migration failed"
        exit 1
    fi
    
    # Collect static files
    if ! collect_static_files; then
        log_error "Static file collection failed"
        exit 1
    fi
    
    log_info "$SERVICE_NAME initialization completed successfully"
    
    # Start the application server
    start_gunicorn
}

# Run main function
main "$@" 