"""
Django management command for safe database migrations with existing data.
"""
import sys
from django.core.management.base import BaseCommand
from django.core.management import call_command
from django.db import connections, transaction
from django.db.migrations.executor import MigrationExecutor
from django.db.migrations.loader import MigrationLoader
from django.conf import settings


class Command(BaseCommand):
    """Django command to safely run migrations on existing databases."""
    
    help = 'Safely run database migrations with existing data protection'
    
    def add_arguments(self, parser):
        parser.add_argument(
            '--database',
            type=str,
            default='default',
            help='Database alias to migrate'
        )
        parser.add_argument(
            '--dry-run',
            action='store_true',
            help='Show what migrations would be applied without running them'
        )
        parser.add_argument(
            '--check-only',
            action='store_true',
            help='Only check migration status, do not apply'
        )
        parser.add_argument(
            '--force',
            action='store_true',
            help='Force migrations even if risks are detected'
        )
    
    def handle(self, *args, **options):
        database = options['database']
        dry_run = options['dry_run']
        check_only = options['check_only']
        force = options['force']
        
        self.stdout.write(f'Checking migration status for database "{database}"...')
        
        try:
            # Get database connection
            connection = connections[database]
            
            # Create migration executor
            executor = MigrationExecutor(connection)
            
            # Get migration plan
            migration_plan = executor.migration_plan(executor.loader.graph.leaf_nodes())
            
            if not migration_plan:
                self.stdout.write(
                    self.style.SUCCESS('No migrations to apply - database is up to date')
                )
                return
            
            # Display migration plan
            self.stdout.write(f'Found {len(migration_plan)} migrations to apply:')
            for migration, backwards in migration_plan:
                direction = 'REVERSE' if backwards else 'APPLY'
                self.stdout.write(f'  {direction}: {migration.app_label}.{migration.name}')
            
            if check_only:
                self.stdout.write('Check complete - use without --check-only to apply migrations')
                return
            
            if dry_run:
                self.stdout.write('Dry run complete - use without --dry-run to apply migrations')
                return
            
            # Check for potentially risky migrations
            risky_operations = self._check_for_risky_operations(migration_plan, connection)
            
            if risky_operations and not force:
                self.stdout.write(
                    self.style.WARNING('Potentially risky operations detected:')
                )
                for risk in risky_operations:
                    self.stdout.write(f'  - {risk}')
                self.stdout.write(
                    self.style.WARNING(
                        'Use --force to proceed anyway, or review migrations manually'
                    )
                )
                return
            
            # Apply migrations with transaction protection
            self.stdout.write('Applying migrations...')
            
            try:
                with transaction.atomic(using=database):
                    # Run migrations
                    call_command(
                        'migrate',
                        database=database,
                        verbosity=2,
                        interactive=False,
                        stdout=self.stdout,
                        stderr=self.stderr
                    )
                
                self.stdout.write(
                    self.style.SUCCESS('Migrations applied successfully!')
                )
                
            except Exception as e:
                self.stdout.write(
                    self.style.ERROR(f'Migration failed: {e}')
                )
                self.stdout.write(
                    'Transaction rolled back - database state preserved'
                )
                sys.exit(1)
                
        except Exception as e:
            self.stdout.write(
                self.style.ERROR(f'Error during migration check: {e}')
            )
            sys.exit(1)
    
    def _check_for_risky_operations(self, migration_plan, connection):
        """Check for potentially risky migration operations."""
        risky_operations = []
        
        for migration, backwards in migration_plan:
            if backwards:
                risky_operations.append(
                    f'Reverse migration: {migration.app_label}.{migration.name}'
                )
                continue
            
            # Load migration file to check operations
            try:
                migration_instance = migration
                for operation in migration_instance.operations:
                    operation_name = operation.__class__.__name__
                    
                    # Check for potentially destructive operations
                    if operation_name in ['DeleteModel', 'RemoveField']:
                        risky_operations.append(
                            f'Destructive operation in {migration.app_label}.{migration.name}: '
                            f'{operation_name}'
                        )
                    
                    elif operation_name == 'AlterField':
                        # Check for field type changes that might cause data loss
                        if hasattr(operation, 'field'):
                            field_type = operation.field.__class__.__name__
                            if field_type in ['CharField', 'TextField'] and hasattr(operation.field, 'max_length'):
                                risky_operations.append(
                                    f'Field size change in {migration.app_label}.{migration.name}: '
                                    f'{operation.model_name}.{operation.name}'
                                )
                    
                    elif operation_name == 'RunSQL':
                        risky_operations.append(
                            f'Raw SQL operation in {migration.app_label}.{migration.name}'
                        )
                        
            except Exception as e:
                risky_operations.append(
                    f'Could not analyze migration {migration.app_label}.{migration.name}: {e}'
                )
        
        return risky_operations