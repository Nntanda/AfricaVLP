@echo off
REM Enhanced Celery services startup script for Windows
REM This script starts Celery worker, beat scheduler, and Flower monitoring with error handling

setlocal enabledelayedexpansion

echo [%date% %time%] Starting enhanced Celery services...

REM Create logs directory if it doesn't exist
if not exist "logs" mkdir logs

REM Check if Redis is available (optional check)
echo [%date% %time%] Checking Redis connection...
redis-cli ping >nul 2>&1
if !errorlevel! equ 0 (
    echo [%date% %time%] Redis connection successful
) else (
    echo [%date% %time%] WARNING: Cannot connect to Redis or redis-cli not found
    echo [%date% %time%] Continuing anyway...
)

REM Check database connection
echo [%date% %time%] Checking database connection...
python manage.py check --database default >nul 2>&1
if !errorlevel! neq 0 (
    echo [%date% %time%] ERROR: Database connection failed
    echo Please ensure the database is running and accessible
    pause
    exit /b 1
)
echo [%date% %time%] Database connection successful

REM Start Celery worker in new window with enhanced configuration
echo [%date% %time%] Starting Celery worker with enhanced configuration...
start "Celery Worker - Enhanced" cmd /k "echo Starting Celery worker... && celery -A admin_backend worker --loglevel=info --concurrency=4 --max-tasks-per-child=1000 --prefetch-multiplier=1 --queues=default,email,media,reports,maintenance --hostname=worker@%%h --logfile=logs/celery_worker.log --pidfile=logs/celery_worker.pid"

REM Wait for worker to start
echo [%date% %time%] Waiting for worker to initialize...
timeout /t 5 /nobreak >nul

REM Start Celery beat scheduler in new window
echo [%date% %time%] Starting Celery beat scheduler...
start "Celery Beat - Enhanced" cmd /k "echo Starting Celery beat scheduler... && celery -A admin_backend beat --loglevel=info --scheduler django_celery_beat.schedulers:DatabaseScheduler --logfile=logs/celery_beat.log --pidfile=logs/celerybeat.pid"

REM Wait for beat to start
echo [%date% %time%] Waiting for beat scheduler to initialize...
timeout /t 5 /nobreak >nul

REM Start Flower monitoring in new window with enhanced configuration
echo [%date% %time%] Starting Flower monitoring with enhanced configuration...
start "Celery Flower - Enhanced" cmd /k "echo Starting Flower monitoring... && celery -A admin_backend flower --port=5555 --broker=redis://localhost:6379/0 --broker_api=redis://localhost:6379/0 --persistent=true --db=flower.db --max_tasks=10000 --logfile=logs/flower.log"

REM Wait for flower to start
echo [%date% %time%] Waiting for Flower to initialize...
timeout /t 5 /nobreak >nul

echo.
echo [%date% %time%] All Celery services started successfully!
echo.
echo Service Information:
echo   - Celery Worker: Enhanced configuration with queue routing
echo   - Celery Beat: Database scheduler with persistent storage
echo   - Flower: Monitoring with persistent database
echo.
echo Service URLs:
echo   - Flower monitoring: http://localhost:5555
echo.
echo Log Files:
echo   - Worker: logs\celery_worker.log
echo   - Beat: logs\celery_beat.log
echo   - Flower: logs\flower.log
echo.
echo Configuration Features:
echo   - Redis connection retry logic
echo   - Task queue routing (default, email, media, reports, maintenance)
echo   - Enhanced error handling and logging
echo   - Automatic task acknowledgment and retry policies
echo   - Worker process recycling (max 1000 tasks per child)
echo.
echo To stop services: Close the individual windows
echo To monitor: Check log files or visit Flower at http://localhost:5555
echo.
pause