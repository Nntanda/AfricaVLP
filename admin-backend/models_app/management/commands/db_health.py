"""
Django management command for database health checks.
"""
import json
from django.core.management.base import BaseCommand
from models_app.utils.db_utils import check_database_health, get_database_info


class Command(BaseCommand):
    """Django command to check database health status."""
    
    help = 'Check database health and connection status'
    
    def add_arguments(self, parser):
        parser.add_argument(
            '--database',
            type=str,
            default='default',
            help='Database alias to check'
        )
        parser.add_argument(
            '--json',
            action='store_true',
            help='Output results in JSON format'
        )
        parser.add_argument(
            '--verbose',
            action='store_true',
            help='Show detailed information'
        )
    
    def handle(self, *args, **options):
        database = options['database']
        output_json = options['json']
        verbose = options['verbose']
        
        # Perform health check
        health_status = check_database_health(database)
        
        if output_json:
            # Output as JSON
            self.stdout.write(json.dumps(health_status, indent=2))
        else:
            # Human-readable output
            self.stdout.write(f'Database Health Check: {database}')
            self.stdout.write('-' * 40)
            
            # Status
            status_color = self.style.SUCCESS if health_status['status'] == 'healthy' else self.style.ERROR
            self.stdout.write(f'Status: {status_color(health_status["status"].upper())}')
            
            # Connection status
            conn_status = 'OK' if health_status['connection'] else 'FAILED'
            conn_color = self.style.SUCCESS if health_status['connection'] else self.style.ERROR
            self.stdout.write(f'Connection: {conn_color(conn_status)}')
            
            # Query test status
            query_status = 'OK' if health_status['query_test'] else 'FAILED'
            query_color = self.style.SUCCESS if health_status['query_test'] else self.style.ERROR
            self.stdout.write(f'Query Test: {query_color(query_status)}')
            
            # Show errors if any
            if health_status['errors']:
                self.stdout.write('\nErrors:')
                for error in health_status['errors']:
                    self.stdout.write(f'  - {self.style.ERROR(error)}')
            
            # Show detailed info if requested
            if verbose and health_status['info']:
                self.stdout.write('\nDatabase Information:')
                for key, value in health_status['info'].items():
                    self.stdout.write(f'  {key}: {value}')
        
        # Exit with appropriate code
        if health_status['status'] != 'healthy':
            exit(1)