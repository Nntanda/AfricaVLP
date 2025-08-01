"""
Django management command for comprehensive Celery health monitoring.
"""

import json
import time
from datetime import datetime, timedelta
from django.core.management.base import BaseCommand
from django.utils import timezone
from django.db import connection
from django.core.cache import cache
from celery import current_app
from celery.exceptions import WorkerLostError
import redis
import logging

logger = logging.getLogger(__name__)


class Command(BaseCommand):
    help = 'Comprehensive Celery health monitoring and diagnostics'

    def add_arguments(self, parser):
        parser.add_argument(
            '--format',
            choices=['json', 'table', 'summary'],
            default='summary',
            help='Output format (default: summary)'
        )
        parser.add_argument(
            '--watch',
            action='store_true',
            help='Watch for real-time updates'
        )
        parser.add_argument(
            '--interval',
            type=int,
            default=10,
            help='Update interval in seconds (default: 10)'
        )
        parser.add_argument(
            '--test-tasks',
            action='store_true',
            help='Run test tasks to verify functionality'
        )
        parser.add_argument(
            '--check-redis',
            action='store_true',
            help='Perform detailed Redis connection checks'
        )

    def handle(self, *args, **options):
        if options['watch']:
            self.watch_health(options)
        elif options['test_tasks']:
            self.test_task_execution(options)
        elif options['check_redis']:
            self.check_redis_health(options)
        else:
            self.show_health_status(options)

    def show_health_status(self, options):
        """Show comprehensive health status"""
        health_data = self.collect_health_data()
        
        if options['format'] == 'json':
            self.stdout.write(json.dumps(health_data, indent=2, default=str))
        elif options['format'] == 'table':
            self.output_table_format(health_data)
        else:
            self.output_summary_format(health_data)

    def collect_health_data(self):
        """Collect comprehensive health data"""
        app = current_app
        inspect = app.control.inspect()
        
        health_data = {
            'timestamp': timezone.now(),
            'overall_status': 'unknown',
            'components': {
                'redis': self.check_redis_connection(),
                'database': self.check_database_connection(),
                'workers': self.check_workers(inspect),
                'queues': self.check_queues(inspect),
                'scheduled_tasks': self.check_scheduled_tasks(inspect),
            },
            'metrics': self.collect_metrics(inspect),
        }
        
        # Determine overall status
        component_statuses = [comp.get('status', 'unknown') for comp in health_data['components'].values()]
        if all(status == 'healthy' for status in component_statuses):
            health_data['overall_status'] = 'healthy'
        elif any(status == 'critical' for status in component_statuses):
            health_data['overall_status'] = 'critical'
        else:
            health_data['overall_status'] = 'degraded'
        
        return health_data

    def check_redis_connection(self):
        """Check Redis connection health"""
        try:
            from django.conf import settings
            broker_url = getattr(settings, 'CELERY_BROKER_URL', 'redis://localhost:6379/0')
            
            # Parse Redis URL
            if broker_url.startswith('redis://'):
                url_parts = broker_url.replace('redis://', '').split('/')
                host_port = url_parts[0].split(':')
                host = host_port[0] if host_port[0] else 'localhost'
                port = int(host_port[1]) if len(host_port) > 1 else 6379
                db = int(url_parts[1]) if len(url_parts) > 1 else 0
            else:
                host, port, db = 'localhost', 6379, 0
            
            # Test connection
            r = redis.Redis(host=host, port=port, db=db, socket_connect_timeout=5)
            
            # Basic connectivity
            start_time = time.time()
            r.ping()
            ping_time = (time.time() - start_time) * 1000
            
            # Get Redis info
            info = r.info()
            
            return {
                'status': 'healthy',
                'host': host,
                'port': port,
                'db': db,
                'ping_time_ms': round(ping_time, 2),
                'version': info.get('redis_version', 'unknown'),
                'connected_clients': info.get('connected_clients', 0),
                'used_memory_human': info.get('used_memory_human', 'unknown'),
                'uptime_in_seconds': info.get('uptime_in_seconds', 0),
            }
            
        except Exception as e:
            return {
                'status': 'critical',
                'error': str(e),
                'message': 'Cannot connect to Redis broker'
            }

    def check_database_connection(self):
        """Check database connection health"""
        try:
            start_time = time.time()
            with connection.cursor() as cursor:
                cursor.execute("SELECT 1")
                cursor.fetchone()
            query_time = (time.time() - start_time) * 1000
            
            return {
                'status': 'healthy',
                'query_time_ms': round(query_time, 2),
                'vendor': connection.vendor,
                'database': connection.settings_dict.get('NAME', 'unknown'),
            }
            
        except Exception as e:
            return {
                'status': 'critical',
                'error': str(e),
                'message': 'Cannot connect to database'
            }

    def check_workers(self, inspect):
        """Check worker health and status"""
        try:
            stats = inspect.stats()
            active = inspect.active()
            
            if not stats:
                return {
                    'status': 'critical',
                    'message': 'No workers found',
                    'count': 0
                }
            
            workers = []
            for worker_name, worker_stats in stats.items():
                worker_active_tasks = len(active.get(worker_name, []))
                
                workers.append({
                    'name': worker_name,
                    'status': 'online',
                    'pool': worker_stats.get('pool', {}).get('implementation', 'unknown'),
                    'processes': worker_stats.get('pool', {}).get('max-concurrency', 0),
                    'total_tasks': worker_stats.get('total', 0),
                    'active_tasks': worker_active_tasks,
                })
            
            return {
                'status': 'healthy',
                'count': len(workers),
                'workers': workers
            }
            
        except Exception as e:
            return {
                'status': 'critical',
                'error': str(e),
                'message': 'Cannot inspect workers'
            }

    def check_queues(self, inspect):
        """Check queue status and lengths"""
        try:
            # Get active queues from workers
            active_queues = inspect.active_queues()
            
            if not active_queues:
                return {
                    'status': 'degraded',
                    'message': 'No active queues found',
                    'queues': []
                }
            
            queues = []
            all_queue_names = set()
            
            for worker, worker_queues in active_queues.items():
                for queue_info in worker_queues:
                    queue_name = queue_info['name']
                    all_queue_names.add(queue_name)
            
            # Try to get queue lengths from Redis
            try:
                from django.conf import settings
                broker_url = getattr(settings, 'CELERY_BROKER_URL', 'redis://localhost:6379/0')
                
                if broker_url.startswith('redis://'):
                    url_parts = broker_url.replace('redis://', '').split('/')
                    host_port = url_parts[0].split(':')
                    host = host_port[0] if host_port[0] else 'localhost'
                    port = int(host_port[1]) if len(host_port) > 1 else 6379
                    db = int(url_parts[1]) if len(url_parts) > 1 else 0
                    
                    r = redis.Redis(host=host, port=port, db=db)
                    
                    for queue_name in all_queue_names:
                        length = r.llen(queue_name)
                        queues.append({
                            'name': queue_name,
                            'length': length,
                            'status': 'healthy' if length < 1000 else 'degraded'
                        })
                else:
                    # Non-Redis broker
                    for queue_name in all_queue_names:
                        queues.append({
                            'name': queue_name,
                            'length': 'unknown',
                            'status': 'healthy'
                        })
                        
            except Exception:
                # Fallback if Redis check fails
                for queue_name in all_queue_names:
                    queues.append({
                        'name': queue_name,
                        'length': 'unknown',
                        'status': 'healthy'
                    })
            
            return {
                'status': 'healthy',
                'count': len(queues),
                'queues': queues
            }
            
        except Exception as e:
            return {
                'status': 'critical',
                'error': str(e),
                'message': 'Cannot inspect queues'
            }

    def check_scheduled_tasks(self, inspect):
        """Check scheduled tasks status"""
        try:
            scheduled = inspect.scheduled()
            
            if not scheduled:
                return {
                    'status': 'healthy',
                    'message': 'No scheduled tasks',
                    'count': 0
                }
            
            total_scheduled = sum(len(tasks) for tasks in scheduled.values())
            
            return {
                'status': 'healthy',
                'count': total_scheduled,
                'by_worker': {worker: len(tasks) for worker, tasks in scheduled.items()}
            }
            
        except Exception as e:
            return {
                'status': 'degraded',
                'error': str(e),
                'message': 'Cannot inspect scheduled tasks'
            }

    def collect_metrics(self, inspect):
        """Collect performance metrics"""
        try:
            stats = inspect.stats()
            
            if not stats:
                return {}
            
            total_tasks = sum(worker_stats.get('total', 0) for worker_stats in stats.values())
            
            return {
                'total_tasks_processed': total_tasks,
                'worker_count': len(stats),
                'timestamp': timezone.now()
            }
            
        except Exception:
            return {}

    def output_summary_format(self, health_data):
        """Output health data in summary format"""
        status_colors = {
            'healthy': self.style.SUCCESS,
            'degraded': self.style.WARNING,
            'critical': self.style.ERROR,
            'unknown': self.style.NOTICE
        }
        
        # Overall status
        overall_status = health_data['overall_status']
        color_func = status_colors.get(overall_status, self.style.NOTICE)
        
        self.stdout.write(f"\n{color_func('=== CELERY HEALTH STATUS ===')}")
        self.stdout.write(f"Overall Status: {color_func(overall_status.upper())}")
        self.stdout.write(f"Timestamp: {health_data['timestamp']}")
        
        # Component status
        self.stdout.write(f"\n{self.style.SUCCESS('=== COMPONENT STATUS ===')}")
        
        for component, data in health_data['components'].items():
            status = data.get('status', 'unknown')
            color_func = status_colors.get(status, self.style.NOTICE)
            self.stdout.write(f"{component.title()}: {color_func(status.upper())}")
            
            if status == 'critical' and 'error' in data:
                self.stdout.write(f"  Error: {data['error']}")
            elif component == 'redis' and status == 'healthy':
                self.stdout.write(f"  Ping: {data.get('ping_time_ms', 0)}ms")
                self.stdout.write(f"  Clients: {data.get('connected_clients', 0)}")
            elif component == 'workers' and status == 'healthy':
                self.stdout.write(f"  Count: {data.get('count', 0)}")
                for worker in data.get('workers', []):
                    self.stdout.write(f"    {worker['name']}: {worker['active_tasks']} active tasks")
        
        # Metrics
        if health_data.get('metrics'):
            self.stdout.write(f"\n{self.style.SUCCESS('=== METRICS ===')}")
            metrics = health_data['metrics']
            self.stdout.write(f"Total Tasks Processed: {metrics.get('total_tasks_processed', 0)}")
            self.stdout.write(f"Active Workers: {metrics.get('worker_count', 0)}")

    def output_table_format(self, health_data):
        """Output health data in table format"""
        # Implementation for detailed table format
        self.stdout.write(json.dumps(health_data, indent=2, default=str))

    def watch_health(self, options):
        """Watch health status in real-time"""
        self.stdout.write(
            self.style.SUCCESS(f'Watching Celery health (update every {options["interval"]}s)...')
        )
        self.stdout.write('Press Ctrl+C to stop\n')
        
        try:
            while True:
                # Clear screen (works on most terminals)
                self.stdout.write('\033[2J\033[H')
                self.stdout.write(f'Last updated: {timezone.now().strftime("%Y-%m-%d %H:%M:%S")}\n')
                self.show_health_status(options)
                time.sleep(options['interval'])
        except KeyboardInterrupt:
            self.stdout.write('\nStopped monitoring.')

    def test_task_execution(self, options):
        """Test task execution to verify functionality"""
        self.stdout.write(self.style.SUCCESS('Testing Celery task execution...'))
        
        try:
            from admin_backend.celery import health_check_task
            
            # Submit test task
            result = health_check_task.delay()
            self.stdout.write(f'Test task submitted: {result.id}')
            
            # Wait for result
            try:
                task_result = result.get(timeout=30)
                self.stdout.write(self.style.SUCCESS('Test task completed successfully!'))
                self.stdout.write(f'Result: {task_result}')
            except Exception as e:
                self.stdout.write(self.style.ERROR(f'Test task failed: {e}'))
                
        except Exception as e:
            self.stdout.write(self.style.ERROR(f'Cannot submit test task: {e}'))

    def check_redis_health(self, options):
        """Perform detailed Redis health checks"""
        self.stdout.write(self.style.SUCCESS('Performing detailed Redis health checks...'))
        
        redis_data = self.check_redis_connection()
        
        if options['format'] == 'json':
            self.stdout.write(json.dumps(redis_data, indent=2, default=str))
        else:
            if redis_data['status'] == 'healthy':
                self.stdout.write(self.style.SUCCESS('Redis Status: HEALTHY'))
                self.stdout.write(f"Host: {redis_data['host']}:{redis_data['port']}")
                self.stdout.write(f"Database: {redis_data['db']}")
                self.stdout.write(f"Version: {redis_data['version']}")
                self.stdout.write(f"Ping Time: {redis_data['ping_time_ms']}ms")
                self.stdout.write(f"Connected Clients: {redis_data['connected_clients']}")
                self.stdout.write(f"Memory Usage: {redis_data['used_memory_human']}")
                self.stdout.write(f"Uptime: {redis_data['uptime_in_seconds']} seconds")
            else:
                self.stdout.write(self.style.ERROR('Redis Status: CRITICAL'))
                self.stdout.write(f"Error: {redis_data.get('error', 'Unknown error')}")