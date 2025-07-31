#!/bin/bash

# Start Celery services for development
# This script starts Celery worker, beat scheduler, and Flower monitoring

echo "Starting Celery services..."

# Function to handle cleanup on script exit
cleanup() {
    echo "Stopping Celery services..."
    kill $WORKER_PID $BEAT_PID $FLOWER_PID 2>/dev/null
    exit 0
}

# Set up signal handlers
trap cleanup SIGINT SIGTERM

# Start Celery worker in background
echo "Starting Celery worker..."
celery -A admin_backend worker --loglevel=info --concurrency=4 &
WORKER_PID=$!

# Start Celery beat scheduler in background
echo "Starting Celery beat scheduler..."
celery -A admin_backend beat --loglevel=info --scheduler django_celery_beat.schedulers:DatabaseScheduler &
BEAT_PID=$!

# Start Flower monitoring in background
echo "Starting Flower monitoring (http://localhost:5555)..."
celery -A admin_backend flower --port=5555 &
FLOWER_PID=$!

echo "All Celery services started!"
echo "Worker PID: $WORKER_PID"
echo "Beat PID: $BEAT_PID"
echo "Flower PID: $FLOWER_PID"
echo ""
echo "Access Flower monitoring at: http://localhost:5555"
echo "Press Ctrl+C to stop all services"

# Wait for all background processes
wait $WORKER_PID $BEAT_PID $FLOWER_PID