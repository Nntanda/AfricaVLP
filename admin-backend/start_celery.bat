@echo off
REM Start Celery services for development on Windows
REM This script starts Celery worker, beat scheduler, and Flower monitoring

echo Starting Celery services...

REM Start Celery worker in new window
echo Starting Celery worker...
start "Celery Worker" cmd /k "celery -A admin_backend worker --loglevel=info --concurrency=4"

REM Wait a moment for worker to start
timeout /t 3 /nobreak >nul

REM Start Celery beat scheduler in new window
echo Starting Celery beat scheduler...
start "Celery Beat" cmd /k "celery -A admin_backend beat --loglevel=info --scheduler django_celery_beat.schedulers:DatabaseScheduler"

REM Wait a moment for beat to start
timeout /t 3 /nobreak >nul

REM Start Flower monitoring in new window
echo Starting Flower monitoring...
start "Celery Flower" cmd /k "celery -A admin_backend flower --port=5555"

echo.
echo All Celery services started in separate windows!
echo Access Flower monitoring at: http://localhost:5555
echo.
echo Close the individual windows to stop each service.
pause