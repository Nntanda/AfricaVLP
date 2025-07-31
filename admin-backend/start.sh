#!/bin/sh
set -e

# Install netcat if not available
apt-get update && apt-get install -y netcat-openbsd

# Wait for MySQL
echo "Waiting for MySQL..."
while ! nc -z mysql 3306; do
  sleep 1
done

# Create necessary directories
mkdir -p logs static media

echo "Running migrations..."
python manage.py migrate --noinput

echo "Collecting static files..."
python manage.py collectstatic --noinput

echo "Starting Gunicorn server..."
exec gunicorn admin_backend.wsgi:application --bind 0.0.0.0:8000 --workers 3 --timeout 120 