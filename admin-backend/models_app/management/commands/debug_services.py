"""
Service debugging utilities for admin-backend.
Provides tools for diagnosing service connectivity and dependency issues.
"""

import json
import time
import socket
import requests
import subprocess
from datetime import datetime
from django.core.management.base import BaseCommand
from django.conf import settings
from django.db import connection
from django.core.cache import cache
from models_app.utils.logging_utils import get_logger, log_system_event


class Command(BaseCommand):
    help = 'Debug service connectivity and dependencies'
    
    def __init__(self):
        super().__init__()
        self.logger = get_logger('debug_services')
    
    def add_arguments(self, parser):
        parser.add_argument(
            '--service',
            type=str,
            choices=['all', 'database', 'redis', 'wellknown', 'nginx', 'celery', 'network'],
            default='all',
            help='Service to debug (default: all)'
        )
        parser.add_argument(
            '--verbose',
            action='store_true',
            help='Enable verbose output'
        )
        parser.add_argument(
            '--format',
            type=str,
            default='text',
            choices=['json', 'text'],
            help='Output format (json or text)'
        )
    
    def handle(self, *args, **options):
        self.verbose = options['verbose']
        self.format = options['format']
        service = options['service']
        
        log_system_event('debug_services_started', 'success', service=service)
        
        debug_results = {
            'timestamp': datetime.utcnow().isoformat() + 'Z',
            'service': 'admin-backend',
            'debug_target': service,
            'results': {}
        }
        
        if service == 'all':
            debug_results['results']['database'] = self.debug_database()
            debug_results['results']['redis'] = self.debug_redis()
            debug_results['results']['wellknown'] = self.debug_wellknown_backend()
            debug_results['results']['nginx'] = self.debug_nginx()
            debug_results['results']['celery'] = self.debug_celery()
            debug_results['results']['network'] = self.debug_network()
        elif service == 'database':
            debug_results['results']['database'] = self.debug_database()
        elif service == 'redis':
            debug_results['results']['redis'] = self.debug_redis()
        elif service == 'wellknown':
            debug_results['results']['wellknown'] = self.debug_wellknown_backend()
        elif service == 'nginx':
            debug_results['results']['nginx'] = self.debug_nginx()
        elif service == 'celery':
            debug_results['results']['celery'] = self.debug_celery()
        elif service == 'network':
            debug_results['results']['network'] = self.debug_network()
        
        if self.format == 'json':
            self.stdout.write(json.dumps(debug_results, indent=2))
        else:
            self.print_debug_results(debug_results)
        
        log_system_event('debug_services_completed', 'success', service=service)
    
    def debug_database(self):
        """Debug database connectivity and configuration."""
        self.stdout.write("=== Database Debug ===")
        
        debug_info = {
            'configuration': {},
            'connectivity': {},
            'performance': {},
            'issues': []
        }
        
        # Configuration check
        db_config = settings.DATABASES['default']
        debug_info['configuration'] = {
            'engine': db_config['ENGINE'],
            'name': db_config['NAME'],
            'host': db_config['HOST'],
            'port': db_config['PORT'],
            'user': db_config['USER'],
            'options': db_config.get('OPTIONS', {})
        }
        
        if self.verbose:
            self.stdout.write(f"Database Engine: {db_config['ENGINE']}")
            self.stdout.write(f"Database Name: {db_config['NAME']}")
            self.stdout.write(f"Host: {db_config['HOST']}:{db_config['PORT']}")
        
        # Network connectivity check
        try:
            sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            sock.settimeout(5)
            result = sock.connect_ex((db_config['HOST'], int(db_config['PORT'])))
            sock.close()
            
            if result == 0:
                debug_info['connectivity']['network'] = 'success'
                if self.verbose:
                    self.stdout.write("✓ Network connectivity to database: OK")
            else:
                debug_info['connectivity']['network'] = 'failed'
                debug_info['issues'].append(f"Cannot connect to {db_config['HOST']}:{db_config['PORT']}")
                if self.verbose:
                    self.stdout.write("✗ Network connectivity to database: FAILED")
        except Exception as e:
            debug_info['connectivity']['network'] = 'error'
            debug_info['issues'].append(f"Network check error: {str(e)}")
        
        # Database connection test
        try:
            start_time = time.time()
            with connection.cursor() as cursor:
                cursor.execute("SELECT VERSION()")
                version = cursor.fetchone()[0]
                duration = time.time() - start_time
            
            debug_info['connectivity']['database'] = 'success'
            debug_info['performance']['connection_time_ms'] = round(duration * 1000, 2)
            debug_info['configuration']['version'] = version
            
            if self.verbose:
                self.stdout.write(f"✓ Database connection: OK ({duration:.3f}s)")
                self.stdout.write(f"Database version: {version}")
        except Exception as e:
            debug_info['connectivity']['database'] = 'failed'
            debug_info['issues'].append(f"Database connection failed: {str(e)}")
            if self.verbose:
                self.stdout.write(f"✗ Database connection: FAILED - {str(e)}")
        
        # Query performance test
        try:
            start_time = time.time()
            with connection.cursor() as cursor:
                cursor.execute("SELECT COUNT(*) FROM django_migrations")
                count = cursor.fetchone()[0]
                duration = time.time() - start_time
            
            debug_info['performance']['query_time_ms'] = round(duration * 1000, 2)
            debug_info['performance']['migrations_count'] = count
            
            if self.verbose:
                self.stdout.write(f"✓ Query performance: {duration:.3f}s ({count} migrations)")
        except Exception as e:
            debug_info['issues'].append(f"Query test failed: {str(e)}")
        
        return debug_info
    
    def debug_redis(self):
        """Debug Redis connectivity and configuration."""
        self.stdout.write("\n=== Redis Debug ===")
        
        debug_info = {
            'configuration': {},
            'connectivity': {},
            'performance': {},
            'issues': []
        }
        
        # Configuration check
        cache_config = settings.CACHES['default']
        debug_info['configuration'] = {
            'backend': cache_config['BACKEND'],
            'location': cache_config['LOCATION']
        }
        
        if self.verbose:
            self.stdout.write(f"Cache Backend: {cache_config['BACKEND']}")
            self.stdout.write(f"Location: {cache_config['LOCATION']}")
        
        # Parse Redis URL for network test
        try:
            from urllib.parse import urlparse
            parsed = urlparse(cache_config['LOCATION'])
            host = parsed.hostname or 'localhost'
            port = parsed.port or 6379
            
            # Network connectivity check
            sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            sock.settimeout(5)
            result = sock.connect_ex((host, port))
            sock.close()
            
            if result == 0:
                debug_info['connectivity']['network'] = 'success'
                if self.verbose:
                    self.stdout.write(f"✓ Network connectivity to Redis: OK")
            else:
                debug_info['connectivity']['network'] = 'failed'
                debug_info['issues'].append(f"Cannot connect to {host}:{port}")
                if self.verbose:
                    self.stdout.write(f"✗ Network connectivity to Redis: FAILED")
        except Exception as e:
            debug_info['issues'].append(f"Redis URL parsing error: {str(e)}")
        
        # Cache functionality test
        try:
            test_key = 'debug_test_key'
            test_value = f'debug_test_{int(time.time())}'
            
            start_time = time.time()
            cache.set(test_key, test_value, timeout=60)
            set_duration = time.time() - start_time
            
            start_time = time.time()
            retrieved_value = cache.get(test_key)
            get_duration = time.time() - start_time
            
            cache.delete(test_key)
            
            if retrieved_value == test_value:
                debug_info['connectivity']['cache'] = 'success'
                debug_info['performance']['set_time_ms'] = round(set_duration * 1000, 2)
                debug_info['performance']['get_time_ms'] = round(get_duration * 1000, 2)
                
                if self.verbose:
                    self.stdout.write(f"✓ Cache operations: OK (set: {set_duration:.3f}s, get: {get_duration:.3f}s)")
            else:
                debug_info['connectivity']['cache'] = 'failed'
                debug_info['issues'].append("Cache read/write test failed")
                if self.verbose:
                    self.stdout.write("✗ Cache operations: FAILED")
        except Exception as e:
            debug_info['connectivity']['cache'] = 'error'
            debug_info['issues'].append(f"Cache test error: {str(e)}")
            if self.verbose:
                self.stdout.write(f"✗ Cache operations: ERROR - {str(e)}")
        
        return debug_info
    
    def debug_wellknown_backend(self):
        """Debug wellknown-backend service connectivity."""
        self.stdout.write("\n=== Wellknown Backend Debug ===")
        
        debug_info = {
            'connectivity': {},
            'endpoints': {},
            'issues': []
        }
        
        service_host = 'wellknown-backend'
        service_port = 8001
        
        # Network connectivity check
        try:
            sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            sock.settimeout(5)
            result = sock.connect_ex((service_host, service_port))
            sock.close()
            
            if result == 0:
                debug_info['connectivity']['network'] = 'success'
                if self.verbose:
                    self.stdout.write(f"✓ Network connectivity to {service_host}:{service_port}: OK")
            else:
                debug_info['connectivity']['network'] = 'failed'
                debug_info['issues'].append(f"Cannot connect to {service_host}:{service_port}")
                if self.verbose:
                    self.stdout.write(f"✗ Network connectivity to {service_host}:{service_port}: FAILED")
        except Exception as e:
            debug_info['issues'].append(f"Network check error: {str(e)}")
        
        # Health endpoint check
        try:
            start_time = time.time()
            response = requests.get(f'http://{service_host}:{service_port}/health/', timeout=10)
            duration = time.time() - start_time
            
            debug_info['endpoints']['health'] = {
                'status_code': response.status_code,
                'response_time_ms': round(duration * 1000, 2),
                'content_length': len(response.content)
            }
            
            if response.status_code == 200:
                if self.verbose:
                    self.stdout.write(f"✓ Health endpoint: OK ({response.status_code}, {duration:.3f}s)")
            else:
                debug_info['issues'].append(f"Health endpoint returned {response.status_code}")
                if self.verbose:
                    self.stdout.write(f"✗ Health endpoint: {response.status_code}")
        except Exception as e:
            debug_info['endpoints']['health'] = {'error': str(e)}
            debug_info['issues'].append(f"Health endpoint error: {str(e)}")
            if self.verbose:
                self.stdout.write(f"✗ Health endpoint: ERROR - {str(e)}")
        
        return debug_info
    
    def debug_nginx(self):
        """Debug Nginx proxy connectivity."""
        self.stdout.write("\n=== Nginx Debug ===")
        
        debug_info = {
            'connectivity': {},
            'endpoints': {},
            'issues': []
        }
        
        nginx_host = 'nginx'
        nginx_port = 80
        
        # Network connectivity check
        try:
            sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            sock.settimeout(5)
            result = sock.connect_ex((nginx_host, nginx_port))
            sock.close()
            
            if result == 0:
                debug_info['connectivity']['network'] = 'success'
                if self.verbose:
                    self.stdout.write(f"✓ Network connectivity to {nginx_host}:{nginx_port}: OK")
            else:
                debug_info['connectivity']['network'] = 'failed'
                debug_info['issues'].append(f"Cannot connect to {nginx_host}:{nginx_port}")
                if self.verbose:
                    self.stdout.write(f"✗ Network connectivity to {nginx_host}:{nginx_port}: FAILED")
        except Exception as e:
            debug_info['issues'].append(f"Network check error: {str(e)}")
        
        # Test proxy endpoints
        endpoints_to_test = [
            '/health/',
            '/admin/',
            '/api/'
        ]
        
        for endpoint in endpoints_to_test:
            try:
                start_time = time.time()
                response = requests.get(f'http://{nginx_host}{endpoint}', timeout=10)
                duration = time.time() - start_time
                
                debug_info['endpoints'][endpoint] = {
                    'status_code': response.status_code,
                    'response_time_ms': round(duration * 1000, 2),
                    'content_length': len(response.content)
                }
                
                if self.verbose:
                    self.stdout.write(f"Endpoint {endpoint}: {response.status_code} ({duration:.3f}s)")
            except Exception as e:
                debug_info['endpoints'][endpoint] = {'error': str(e)}
                if self.verbose:
                    self.stdout.write(f"Endpoint {endpoint}: ERROR - {str(e)}")
        
        return debug_info
    
    def debug_celery(self):
        """Debug Celery services."""
        self.stdout.write("\n=== Celery Debug ===")
        
        debug_info = {
            'configuration': {},
            'connectivity': {},
            'workers': {},
            'issues': []
        }
        
        # Configuration check
        debug_info['configuration'] = {
            'broker_url': getattr(settings, 'CELERY_BROKER_URL', 'Not configured'),
            'result_backend': getattr(settings, 'CELERY_RESULT_BACKEND', 'Not configured')
        }
        
        if self.verbose:
            self.stdout.write(f"Broker URL: {debug_info['configuration']['broker_url']}")
            self.stdout.write(f"Result Backend: {debug_info['configuration']['result_backend']}")
        
        try:
            from celery import current_app
            
            # Check worker status
            inspect = current_app.control.inspect()
            stats = inspect.stats()
            active = inspect.active()
            
            if stats:
                debug_info['workers']['count'] = len(stats)
                debug_info['workers']['names'] = list(stats.keys())
                debug_info['connectivity']['workers'] = 'success'
                
                if active:
                    active_tasks = sum(len(tasks) for tasks in active.values())
                    debug_info['workers']['active_tasks'] = active_tasks
                
                if self.verbose:
                    self.stdout.write(f"✓ Celery workers: {len(stats)} active")
                    for worker_name in stats.keys():
                        self.stdout.write(f"  - {worker_name}")
            else:
                debug_info['connectivity']['workers'] = 'failed'
                debug_info['issues'].append("No Celery workers responding")
                if self.verbose:
                    self.stdout.write("✗ Celery workers: None responding")
        except Exception as e:
            debug_info['connectivity']['workers'] = 'error'
            debug_info['issues'].append(f"Celery check error: {str(e)}")
            if self.verbose:
                self.stdout.write(f"✗ Celery check: ERROR - {str(e)}")
        
        return debug_info
    
    def debug_network(self):
        """Debug network connectivity and DNS resolution."""
        self.stdout.write("\n=== Network Debug ===")
        
        debug_info = {
            'dns_resolution': {},
            'connectivity': {},
            'issues': []
        }
        
        # Test DNS resolution for key services
        services_to_test = [
            'mysql',
            'redis',
            'wellknown-backend',
            'nginx'
        ]
        
        for service in services_to_test:
            try:
                import socket
                ip = socket.gethostbyname(service)
                debug_info['dns_resolution'][service] = {
                    'status': 'success',
                    'ip': ip
                }
                if self.verbose:
                    self.stdout.write(f"✓ DNS resolution for {service}: {ip}")
            except Exception as e:
                debug_info['dns_resolution'][service] = {
                    'status': 'failed',
                    'error': str(e)
                }
                debug_info['issues'].append(f"DNS resolution failed for {service}: {str(e)}")
                if self.verbose:
                    self.stdout.write(f"✗ DNS resolution for {service}: FAILED - {str(e)}")
        
        # Test Docker network connectivity
        try:
            result = subprocess.run(
                ['docker', 'network', 'ls'],
                capture_output=True,
                text=True,
                timeout=10
            )
            
            if result.returncode == 0:
                debug_info['connectivity']['docker_network'] = 'success'
                if self.verbose:
                    self.stdout.write("✓ Docker network access: OK")
                    self.stdout.write("Docker networks:")
                    for line in result.stdout.strip().split('\n')[1:]:  # Skip header
                        self.stdout.write(f"  {line}")
            else:
                debug_info['connectivity']['docker_network'] = 'failed'
                debug_info['issues'].append("Docker network command failed")
        except Exception as e:
            debug_info['connectivity']['docker_network'] = 'error'
            debug_info['issues'].append(f"Docker network check error: {str(e)}")
        
        return debug_info
    
    def print_debug_results(self, debug_results):
        """Print debug results in human-readable format."""
        self.stdout.write(f"\n=== Debug Results - {debug_results['timestamp']} ===")
        self.stdout.write(f"Service: {debug_results['service']}")
        self.stdout.write(f"Debug Target: {debug_results['debug_target']}")
        
        for service_name, service_debug in debug_results['results'].items():
            self.stdout.write(f"\n--- {service_name.upper()} ---")
            
            if 'issues' in service_debug and service_debug['issues']:
                self.stdout.write(self.style.ERROR("Issues Found:"))
                for issue in service_debug['issues']:
                    self.stdout.write(f"  ✗ {issue}")
            else:
                self.stdout.write(self.style.SUCCESS("No issues found"))
        
        self.stdout.write("")