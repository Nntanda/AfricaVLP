"""
Django management command to monitor Celery tasks and workers.
"""

from django.core.management.base import BaseCommand
from django.utils import timezone
from celery import current_app
from celery.events.state import State
from celery.events import EventReceiver
import time
import json


class Command(BaseCommand):
    help = 'Monitor Celery tasks and workers'

    def add_arguments(self, parser):
        parser.add_argument(
            '--format',
            choices=['json', 'table'],
            default='table',
            help='Output format (default: table)'
        )
        parser.add_argument(
            '--watch',
            action='store_true',
            help='Watch for real-time updates'
        )
        parser.add_argument(
            '--interval',
            type=int,
            default=5,
            help='Update interval in seconds (default: 5)'
        )

    def handle(self, *args, **options):
        if options['watch']:
            self.watch_tasks(options)
        else:
            self.show_status(options)

    def show_status(self, options):
        """Show current Celery status"""
        app = current_app
        
        # Get active tasks
        inspect = app.control.inspect()
        
        try:
            # Get worker stats
            stats = inspect.stats()
            active_tasks = inspect.active()
            scheduled_tasks = inspect.scheduled()
            reserved_tasks = inspect.reserved()
            
            if options['format'] == 'json':
                self.output_json({
                    'timestamp': timezone.now().isoformat(),
                    'workers': stats or {},
                    'active_tasks': active_tasks or {},
                    'scheduled_tasks': scheduled_tasks or {},
                    'reserved_tasks': reserved_tasks or {},
                })
            else:
                self.output_table(stats, active_tasks, scheduled_tasks, reserved_tasks)
                
        except Exception as e:
            self.stdout.write(
                self.style.ERROR(f'Error connecting to Celery: {e}')
            )
            self.stdout.write(
                self.style.WARNING('Make sure Celery workers are running')
            )

    def watch_tasks(self, options):
        """Watch tasks in real-time"""
        self.stdout.write(
            self.style.SUCCESS(f'Watching Celery tasks (update every {options["interval"]}s)...')
        )
        self.stdout.write('Press Ctrl+C to stop\n')
        
        try:
            while True:
                self.stdout.write('\033[2J\033[H')  # Clear screen
                self.stdout.write(f'Last updated: {timezone.now().strftime("%Y-%m-%d %H:%M:%S")}\n')
                self.show_status(options)
                time.sleep(options['interval'])
        except KeyboardInterrupt:
            self.stdout.write('\nStopped monitoring.')

    def output_json(self, data):
        """Output data in JSON format"""
        self.stdout.write(json.dumps(data, indent=2, default=str))

    def output_table(self, stats, active_tasks, scheduled_tasks, reserved_tasks):
        """Output data in table format"""
        # Worker Statistics
        self.stdout.write(self.style.SUCCESS('=== WORKER STATISTICS ==='))
        if stats:
            for worker, worker_stats in stats.items():
                self.stdout.write(f'\nWorker: {worker}')
                self.stdout.write(f'  Status: Online')
                self.stdout.write(f'  Pool: {worker_stats.get("pool", {}).get("implementation", "N/A")}')
                self.stdout.write(f'  Processes: {worker_stats.get("pool", {}).get("max-concurrency", "N/A")}')
                self.stdout.write(f'  Total Tasks: {worker_stats.get("total", "N/A")}')
        else:
            self.stdout.write(self.style.WARNING('No workers found'))

        # Active Tasks
        self.stdout.write(f'\n{self.style.SUCCESS("=== ACTIVE TASKS ===")}')
        if active_tasks:
            total_active = sum(len(tasks) for tasks in active_tasks.values())
            self.stdout.write(f'Total active tasks: {total_active}')
            
            for worker, tasks in active_tasks.items():
                if tasks:
                    self.stdout.write(f'\nWorker: {worker}')
                    for task in tasks:
                        self.stdout.write(f'  Task: {task["name"]}')
                        self.stdout.write(f'    ID: {task["id"]}')
                        self.stdout.write(f'    Args: {task.get("args", [])}')
                        self.stdout.write(f'    Started: {task.get("time_start", "N/A")}')
        else:
            self.stdout.write('No active tasks')

        # Scheduled Tasks
        self.stdout.write(f'\n{self.style.SUCCESS("=== SCHEDULED TASKS ===")}')
        if scheduled_tasks:
            total_scheduled = sum(len(tasks) for tasks in scheduled_tasks.values())
            self.stdout.write(f'Total scheduled tasks: {total_scheduled}')
            
            for worker, tasks in scheduled_tasks.items():
                if tasks:
                    self.stdout.write(f'\nWorker: {worker}')
                    for task in tasks:
                        self.stdout.write(f'  Task: {task["request"]["task"]}')
                        self.stdout.write(f'    ID: {task["request"]["id"]}')
                        self.stdout.write(f'    ETA: {task.get("eta", "N/A")}')
        else:
            self.stdout.write('No scheduled tasks')

        # Reserved Tasks
        self.stdout.write(f'\n{self.style.SUCCESS("=== RESERVED TASKS ===")}')
        if reserved_tasks:
            total_reserved = sum(len(tasks) for tasks in reserved_tasks.values())
            self.stdout.write(f'Total reserved tasks: {total_reserved}')
            
            for worker, tasks in reserved_tasks.items():
                if tasks:
                    self.stdout.write(f'\nWorker: {worker}')
                    for task in tasks:
                        self.stdout.write(f'  Task: {task["name"]}')
                        self.stdout.write(f'    ID: {task["id"]}')
        else:
            self.stdout.write('No reserved tasks')