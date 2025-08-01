@echo off
REM AU-VLP System Monitor Batch Script
REM Provides easy access to system monitoring functionality

setlocal enabledelayedexpansion

echo AU-VLP System Monitor
echo =====================

if "%1"=="status" (
    echo Checking system status...
    python monitor_system.py --status
    goto :end
)

if "%1"=="test" (
    echo Running service recovery tests...
    powershell -ExecutionPolicy Bypass -File test-service-recovery.ps1
    goto :end
)

if "%1"=="help" (
    echo Usage:
    echo   monitor_system.bat           - Start continuous monitoring
    echo   monitor_system.bat status    - Show current status
    echo   monitor_system.bat test      - Run recovery tests
    echo   monitor_system.bat help      - Show this help
    goto :end
)

echo Starting continuous system monitoring...
echo Press Ctrl+C to stop monitoring
echo.
python monitor_system.py

:end
pause