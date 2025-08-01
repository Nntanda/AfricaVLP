"""
Django management command to wait for database availability with retry logic.
"""
import time
import sys
from django.core.management.base import BaseCommand
from django.db import connections, OperationalError
from django.conf import settings


class Command(BaseCommand):
    """Django command to wait for database to be available."""
    
    help = 'Wait for database to be available'
    
    def add_arguments(self, parser):
        parser.add_argument(
            '--timeout',
            type=int,
            default=60,
            help='Maximum time to wait for database (seconds)'
        )
        parser.add_argument(
            '--interval',
            type=int,
            default=2,
            help='Interval between connection attempts (seconds)'
        )
        parser.add_argument(
            '--database',
            type=str,
            default='default',
            help='Database alias to check'
        )
        parser.add_argument(
            '--exponential-backoff',
            action='store_true',
            help='Use exponential backoff for retry intervals'
        )
    
    def handle(self, *args, **options):
        timeout = options['timeout']
        interval = options['interval']
        database = options['database']
        use_exponential_backoff = options['exponential_backoff']
        
        self.stdout.write(f'Waiting for database "{database}" to be available...')
        
        start_time = time.time()
        current_interval = interval
        attempt = 1
        
        while time.time() - start_time < timeout:
            try:
                # Test database connection
                db_conn = connections[database]
                db_conn.ensure_connection()
                
                # Test with a simple query
                with db_conn.cursor() as cursor:
                    cursor.execute('SELECT 1')
                    result = cursor.fetchone()
                    
                if result and result[0] == 1:
                    elapsed_time = time.time() - start_time
                    self.stdout.write(
                        self.style.SUCCESS(
                            f'Database "{database}" is available! '
                            f'(took {elapsed_time:.2f}s, {attempt} attempts)'
                        )
                    )
                    return
                else:
                    raise OperationalError('Unexpected result from test query')
                    
            except OperationalError as e:
                elapsed_time = time.time() - start_time
                remaining_time = timeout - elapsed_time
                
                if remaining_time <= 0:
                    break
                    
                self.stdout.write(
                    self.style.WARNING(
                        f'Database "{database}" unavailable (attempt {attempt}): {e}'
                    )
                )
                
                # Calculate sleep time
                sleep_time = min(current_interval, remaining_time)
                self.stdout.write(f'Retrying in {sleep_time}s...')
                
                time.sleep(sleep_time)
                
                # Update interval for next attempt
                if use_exponential_backoff:
                    current_interval = min(current_interval * 2, 30)  # Cap at 30 seconds
                
                attempt += 1
        
        # If we get here, we've timed out
        self.stdout.write(
            self.style.ERROR(
                f'Database "{database}" is not available after {timeout}s timeout '
                f'({attempt} attempts)'
            )
        )
        sys.exit(1)