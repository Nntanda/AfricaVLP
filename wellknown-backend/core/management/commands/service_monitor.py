"""
Service monitoring command for wellknown-backend.
Monitors container health, database connectivity, and service dependencies.
"""

import json
import time
import requests
import subprocess
from datetime import datetime, timedelta
from django.core.management.base import BaseCommand
from django.conf import settings
from django.db import connection
from django.core.cache import cache
from core.utils.logging_utils import get_logger, log_system_event, log_performance_metric


class Command(BaseCommand):
    help = 'Monitor service health and dependencies'
    
    def __init__(self):
        super().__init__()
        self.logger = get_logger('service_monitor')
    
    def add_arguments(self, parser):
        parser.add_argument(
            '--format',
            type=str,
            default='json',
            choices=['json', 'text'],
            help='Output format (json or text)'
        )
        parser.add_argument(
            '--continuous',
            action='store_true',
            help='Run continuously with interval checks'
        )
        parser.add_argument(
            '--interval',
            type=int,
            default=30,
            help='Interval in seconds for continuous monitoring (default: 30)'
        )
        parser.add_argument(
            '--timeout',
            type=int,
            default=10,
            help='Timeout in seconds for health checks (default: 10)'
        )
    
    def handle(self, *args, **options):
        self.format = options['format']
        self.timeout = options['timeout']
        
        if options['continuous']:
            self.run_continuous_monitoring(options['interval'])
        else:
            self.run_single_check()
    
    def run_continuous_monitoring(self, interval):
        """Run continuous monitoring with specified interval."""
        self.stdout.write(f"Starting continuous monitoring (interval: {interval}s)")
        log_system_event('service_monitor_started', 'success', interval=interval)
        
        try:
            while True:
                health_status = self.check_all_services()
                
                if self.format == 'json':
                    self.stdout.write(json.dumps(health_status, indent=2))
                else:
                    self.print_text_status(health_status)
                
                time.sleep(interval)
                
        except KeyboardInterrupt:
            self.stdout.write("\nMonitoring stopped by user")
            log_system_event('service_monitor_stopped', 'success', reason='user_interrupt')
        except Exception as e:
            self.stdout.write(f"Monitoring error: {e}")
            log_system_event('service_monitor_error', 'error', error=str(e))
    
    def run_single_check(self):
        """Run a single health check."""
        health_status = self.check_all_services()
        
        if self.format == 'json':
            self.stdout.write(json.dumps(health_status, indent=2))
        else:
            self.print_text_status(health_status)
    
    def check_all_services(self):
        """Check health of all services and dependencies."""
        start_time = time.time()
        
        health_status = {
            'timestamp': datetime.utcnow().isoformat() + 'Z',
            'service': 'wellknown-backend',
            'overall_status': 'healthy',
            'checks': {}
        }
        
        # Database connectivity check
        health_status['checks']['database'] = self.check_database()
        
        # Redis connectivity check
        health_status['checks']['redis'] = self.check_redis()
        
        # External service dependencies
        health_status['checks']['admin_backend'] = self.check_admin_backend()
        health_status['checks']['nginx'] = self.check_nginx()
        
        # Container health checks
        health_status['checks']['container'] = self.check_container_health()
        
        # System resources
        health_status['checks']['resources'] = self.check_system_resources()
        
        # Determine overall status
        failed_checks = [name for name, check in health_status['checks'].items() 
                        if check['status'] != 'healthy']
        
        if failed_checks:
            health_status['overall_status'] = 'unhealthy'
            health_status['failed_checks'] = failed_checks
        
        # Log performance metric
        duration = time.time() - start_time
        log_performance_metric('health_check_duration', duration * 1000, 'ms')
        
        # Log system event
        log_system_event(
            'health_check_completed',
            health_status['overall_status'],
            failed_checks=failed_checks if failed_checks else None,
            duration_ms=round(duration * 1000, 2)
        )
        
        return health_status
    
    def check_database(self):
        """Check database connectivity and performance."""
        start_time = time.time()
        
        try:
            # Test basic connectivity
            with connection.cursor() as cursor:
                cursor.execute("SELECT 1")
                cursor.fetchone()
            
            # Test query performance
            with connection.cursor() as cursor:
                cursor.execute("SELECT COUNT(*) FROM django_migrations")
                result = cursor.fetchone()
            
            duration = time.time() - start_time
            
            return {
                'status': 'healthy',
                'response_time_ms': round(duration * 1000, 2),
                'details': {
                    'connection_status': 'connected',
                    'migrations_count': result[0] if result else 0
                }
            }
            
        except Exception as e:
            duration = time.time() - start_time
            return {
                'status': 'unhealthy',
                'response_time_ms': round(duration * 1000, 2),
                'error': str(e),
                'details': {
                    'connection_status': 'failed'
                }
            }
    
    def check_redis(self):
        """Check Redis connectivity and performance."""
        start_time = time.time()
        
        try:
            # Test cache connectivity
            test_key = 'health_check_test'
            test_value = f'test_{int(time.time())}'
            
            cache.set(test_key, test_value, timeout=60)
            retrieved_value = cache.get(test_key)
            cache.delete(test_key)
            
            duration = time.time() - start_time
            
            if retrieved_value == test_value:
                return {
                    'status': 'healthy',
                    'response_time_ms': round(duration * 1000, 2),
                    'details': {
                        'connection_status': 'connected',
                        'read_write_test': 'passed'
                    }
                }
            else:
                return {
                    'status': 'unhealthy',
                    'response_time_ms': round(duration * 1000, 2),
                    'error': 'Cache read/write test failed',
                    'details': {
                        'connection_status': 'connected',
                        'read_write_test': 'failed'
                    }
                }
                
        except Exception as e:
            duration = time.time() - start_time
            return {
                'status': 'unhealthy',
                'response_time_ms': round(duration * 1000, 2),
                'error': str(e),
                'details': {
                    'connection_status': 'failed'
                }
            }
    
    def check_admin_backend(self):
        """Check admin-backend service health."""
        start_time = time.time()
        
        try:
            # Try to reach the health endpoint
            response = requests.get(
                'http://admin-backend:8000/health/',
                timeout=self.timeout
            )
            
            duration = time.time() - start_time
            
            if response.status_code == 200:
                return {
                    'status': 'healthy',
                    'response_time_ms': round(duration * 1000, 2),
                    'details': {
                        'http_status': response.status_code,
                        'response_data': response.json() if response.content else None
                    }
                }
            else:
                return {
                    'status': 'unhealthy',
                    'response_time_ms': round(duration * 1000, 2),
                    'error': f'HTTP {response.status_code}',
                    'details': {
                        'http_status': response.status_code
                    }
                }
                
        except Exception as e:
            duration = time.time() - start_time
            return {
                'status': 'unhealthy',
                'response_time_ms': round(duration * 1000, 2),
                'error': str(e),
                'details': {
                    'connection_status': 'failed'
                }
            }
    
    def check_nginx(self):
        """Check Nginx proxy health."""
        start_time = time.time()
        
        try:
            # Try to reach through nginx
            response = requests.get(
                'http://nginx/health/',
                timeout=self.timeout
            )
            
            duration = time.time() - start_time
            
            return {
                'status': 'healthy' if response.status_code == 200 else 'unhealthy',
                'response_time_ms': round(duration * 1000, 2),
                'details': {
                    'http_status': response.status_code
                }
            }
            
        except Exception as e:
            duration = time.time() - start_time
            return {
                'status': 'unhealthy',
                'response_time_ms': round(duration * 1000, 2),
                'error': str(e),
                'details': {
                    'connection_status': 'failed'
                }
            }
    
    def check_container_health(self):
        """Check Docker container health."""
        try:
            # Get container stats using docker command
            result = subprocess.run(
                ['docker', 'stats', '--no-stream', '--format', 
                 'table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.MemPerc}}'],
                capture_output=True,
                text=True,
                timeout=self.timeout
            )
            
            if result.returncode == 0:
                return {
                    'status': 'healthy',
                    'details': {
                        'docker_accessible': True,
                        'stats_output': result.stdout.strip()
                    }
                }
            else:
                return {
                    'status': 'unhealthy',
                    'error': 'Docker stats command failed',
                    'details': {
                        'docker_accessible': False,
                        'error_output': result.stderr.strip()
                    }
                }
                
        except subprocess.TimeoutExpired:
            return {
                'status': 'unhealthy',
                'error': 'Docker stats command timed out',
                'details': {
                    'docker_accessible': False
                }
            }
        except Exception as e:
            return {
                'status': 'unhealthy',
                'error': str(e),
                'details': {
                    'docker_accessible': False
                }
            }
    
    def check_system_resources(self):
        """Check system resource usage."""
        try:
            import psutil
            
            cpu_percent = psutil.cpu_percent(interval=1)
            memory = psutil.virtual_memory()
            disk = psutil.disk_usage('/')
            
            # Determine status based on resource usage
            status = 'healthy'
            warnings = []
            
            if cpu_percent > 80:
                status = 'unhealthy'
                warnings.append(f'High CPU usage: {cpu_percent}%')
            
            if memory.percent > 85:
                status = 'unhealthy'
                warnings.append(f'High memory usage: {memory.percent}%')
            
            if disk.percent > 90:
                status = 'unhealthy'
                warnings.append(f'High disk usage: {disk.percent}%')
            
            return {
                'status': status,
                'warnings': warnings if warnings else None,
                'details': {
                    'cpu_percent': cpu_percent,
                    'memory_percent': memory.percent,
                    'memory_available_mb': round(memory.available / 1024 / 1024, 2),
                    'disk_percent': disk.percent,
                    'disk_free_gb': round(disk.free / 1024 / 1024 / 1024, 2)
                }
            }
            
        except ImportError:
            return {
                'status': 'unknown',
                'error': 'psutil not available',
                'details': {
                    'psutil_available': False
                }
            }
        except Exception as e:
            return {
                'status': 'unhealthy',
                'error': str(e),
                'details': {
                    'resource_check_failed': True
                }
            }
    
    def print_text_status(self, health_status):
        """Print health status in human-readable text format."""
        timestamp = health_status['timestamp']
        overall_status = health_status['overall_status']
        
        self.stdout.write(f"\n=== Service Health Check - {timestamp} ===")
        self.stdout.write(f"Service: {health_status['service']}")
        
        if overall_status == 'healthy':
            self.stdout.write(self.style.SUCCESS(f"Overall Status: {overall_status.upper()}"))
        else:
            self.stdout.write(self.style.ERROR(f"Overall Status: {overall_status.upper()}"))
            if 'failed_checks' in health_status:
                self.stdout.write(f"Failed Checks: {', '.join(health_status['failed_checks'])}")
        
        self.stdout.write("\n--- Individual Checks ---")
        
        for check_name, check_result in health_status['checks'].items():
            status = check_result['status']
            response_time = check_result.get('response_time_ms', 'N/A')
            
            if status == 'healthy':
                status_display = self.style.SUCCESS(status.upper())
            elif status == 'unhealthy':
                status_display = self.style.ERROR(status.upper())
            else:
                status_display = self.style.WARNING(status.upper())
            
            self.stdout.write(f"{check_name:20} {status_display:15} ({response_time}ms)")
            
            if 'error' in check_result:
                self.stdout.write(f"  Error: {check_result['error']}")
            
            if 'warnings' in check_result and check_result['warnings']:
                for warning in check_result['warnings']:
                    self.stdout.write(f"  Warning: {warning}")
        
        self.stdout.write("")