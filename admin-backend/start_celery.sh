#!/bin/bash

# Enhanced Celery services startup script with error handling and logging
# This script starts Celery worker, beat scheduler, and Flower monitoring

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR:${NC} $1" >&2
}

warn() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING:${NC} $1"
}

# Check if Redis is available
check_redis() {
    log "Checking Redis connection..."
    if command -v redis-cli >/dev/null 2>&1; then
        if redis-cli -h ${REDIS_HOST:-localhost} -p ${REDIS_PORT:-6379} ping >/dev/null 2>&1; then
            log "Redis connection successful"
            return 0
        else
            error "Cannot connect to Redis at ${REDIS_HOST:-localhost}:${REDIS_PORT:-6379}"
            return 1
        fi
    else
        warn "redis-cli not found, skipping Redis check"
        return 0
    fi
}

# Check if database is available
check_database() {
    log "Checking database connection..."
    if python manage.py check --database default >/dev/null 2>&1; then
        log "Database connection successful"
        return 0
    else
        error "Database connection failed"
        return 1
    fi
}

# Function to handle cleanup on script exit
cleanup() {
    log "Stopping Celery services..."
    
    # Kill processes gracefully
    if [ ! -z "$WORKER_PID" ]; then
        log "Stopping Celery worker (PID: $WORKER_PID)..."
        kill -TERM $WORKER_PID 2>/dev/null || true
        sleep 2
        kill -KILL $WORKER_PID 2>/dev/null || true
    fi
    
    if [ ! -z "$BEAT_PID" ]; then
        log "Stopping Celery beat (PID: $BEAT_PID)..."
        kill -TERM $BEAT_PID 2>/dev/null || true
        sleep 2
        kill -KILL $BEAT_PID 2>/dev/null || true
    fi
    
    if [ ! -z "$FLOWER_PID" ]; then
        log "Stopping Flower (PID: $FLOWER_PID)..."
        kill -TERM $FLOWER_PID 2>/dev/null || true
        sleep 2
        kill -KILL $FLOWER_PID 2>/dev/null || true
    fi
    
    # Clean up PID files
    rm -f /tmp/celerybeat.pid
    
    log "All Celery services stopped"
    exit 0
}

# Set up signal handlers
trap cleanup SIGINT SIGTERM EXIT

log "Starting enhanced Celery services..."

# Pre-flight checks
if ! check_redis; then
    error "Redis check failed. Please ensure Redis is running."
    exit 1
fi

if ! check_database; then
    error "Database check failed. Please ensure database is available."
    exit 1
fi

# Create logs directory if it doesn't exist
mkdir -p logs

# Start Celery worker in background with enhanced configuration
log "Starting Celery worker with enhanced configuration..."
celery -A admin_backend worker \
    --loglevel=info \
    --concurrency=4 \
    --max-tasks-per-child=1000 \
    --prefetch-multiplier=1 \
    --queues=default,email,media,reports,maintenance \
    --hostname=worker@%h \
    --logfile=logs/celery_worker.log \
    --pidfile=/tmp/celery_worker.pid &
WORKER_PID=$!

# Wait a moment for worker to start
sleep 3

# Check if worker started successfully
if ! kill -0 $WORKER_PID 2>/dev/null; then
    error "Celery worker failed to start"
    exit 1
fi

# Start Celery beat scheduler in background
log "Starting Celery beat scheduler..."
celery -A admin_backend beat \
    --loglevel=info \
    --scheduler django_celery_beat.schedulers:DatabaseScheduler \
    --logfile=logs/celery_beat.log \
    --pidfile=/tmp/celerybeat.pid &
BEAT_PID=$!

# Wait a moment for beat to start
sleep 3

# Check if beat started successfully
if ! kill -0 $BEAT_PID 2>/dev/null; then
    error "Celery beat failed to start"
    exit 1
fi

# Start Flower monitoring in background
log "Starting Flower monitoring..."
celery -A admin_backend flower \
    --port=5555 \
    --broker=redis://${REDIS_HOST:-localhost}:${REDIS_PORT:-6379}/0 \
    --broker_api=redis://${REDIS_HOST:-localhost}:${REDIS_PORT:-6379}/0 \
    --persistent=true \
    --db=flower.db \
    --max_tasks=10000 \
    --logfile=logs/flower.log &
FLOWER_PID=$!

# Wait a moment for flower to start
sleep 3

# Check if flower started successfully
if ! kill -0 $FLOWER_PID 2>/dev/null; then
    error "Flower failed to start"
    exit 1
fi

log "All Celery services started successfully!"
log "Worker PID: $WORKER_PID"
log "Beat PID: $BEAT_PID"
log "Flower PID: $FLOWER_PID"
log ""
log "Service URLs:"
log "  Flower monitoring: http://localhost:5555"
log ""
log "Log files:"
log "  Worker: logs/celery_worker.log"
log "  Beat: logs/celery_beat.log"
log "  Flower: logs/flower.log"
log ""
log "Press Ctrl+C to stop all services"

# Health check function
health_check() {
    local failed=0
    
    # Check worker
    if ! kill -0 $WORKER_PID 2>/dev/null; then
        error "Celery worker is not running"
        failed=1
    fi
    
    # Check beat
    if ! kill -0 $BEAT_PID 2>/dev/null; then
        error "Celery beat is not running"
        failed=1
    fi
    
    # Check flower
    if ! kill -0 $FLOWER_PID 2>/dev/null; then
        error "Flower is not running"
        failed=1
    fi
    
    if [ $failed -eq 1 ]; then
        error "Some services have failed. Initiating shutdown..."
        cleanup
        exit 1
    fi
}

# Monitor services
while true; do
    sleep 30
    health_check
done