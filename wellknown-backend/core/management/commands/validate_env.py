"""
Django management command to validate environment configuration.
"""
from django.core.management.base import BaseCommand
from django.utils import timezone
import json

from core.utils.env_validator import EnvironmentValidator


class Command(BaseCommand):
    help = 'Validate environment configuration for AU-VLP system'

    def add_arguments(self, parser):
        parser.add_argument(
            '--environment',
            type=str,
            help='Environment to validate (development, staging, production)',
            default=None
        )
        parser.add_argument(
            '--json',
            action='store_true',
            help='Output results in JSON format',
        )
        parser.add_argument(
            '--summary',
            action='store_true',
            help='Show configuration summary',
        )

    def handle(self, *args, **options):
        self.stdout.write(
            self.style.SUCCESS(f'AU-VLP Environment Validation - {timezone.now()}')
        )
        self.stdout.write('=' * 60)

        validator = EnvironmentValidator()
        
        if options['summary']:
            self.show_configuration_summary(validator)
            return

        # Validate environment
        results = validator.validate_environment(options['environment'])
        
        if options['json']:
            self.stdout.write(json.dumps(results, indent=2))
            return

        # Display results in human-readable format
        self.display_validation_results(results)

    def show_configuration_summary(self, validator):
        """Display configuration summary."""
        summary = validator.get_configuration_summary()
        
        self.stdout.write(self.style.HTTP_INFO('Configuration Summary:'))
        self.stdout.write(f"Environment: {summary['environment']}")
        self.stdout.write(f"Debug Mode: {summary['debug']}")
        
        self.stdout.write('\nDatabase Configuration:')
        for key, value in summary['database'].items():
            self.stdout.write(f"  {key}: {value}")
            
        self.stdout.write('\nRedis Configuration:')
        self.stdout.write(f"  URL: {summary['redis']['url']}")
        
        self.stdout.write('\nCORS Configuration:')
        origins = summary['cors']['allowed_origins']
        if origins and origins[0]:  # Check if not empty
            for origin in origins:
                if origin.strip():  # Skip empty strings
                    self.stdout.write(f"  - {origin.strip()}")
        else:
            self.stdout.write('  No CORS origins configured')
            
        self.stdout.write('\nSecurity Configuration:')
        for key, value in summary['security'].items():
            self.stdout.write(f"  {key}: {value}")

    def display_validation_results(self, results):
        """Display validation results in human-readable format."""
        environment = results['environment']
        
        if results['valid']:
            self.stdout.write(
                self.style.SUCCESS(f'✓ Environment validation PASSED for {environment}')
            )
        else:
            self.stdout.write(
                self.style.ERROR(f'✗ Environment validation FAILED for {environment}')
            )

        # Display errors
        if results['errors']:
            self.stdout.write(self.style.ERROR('\nErrors:'))
            for error in results['errors']:
                self.stdout.write(self.style.ERROR(f'  ✗ {error}'))

        # Display missing variables
        if results['missing_vars']:
            self.stdout.write(self.style.ERROR('\nMissing Required Variables:'))
            for var in results['missing_vars']:
                self.stdout.write(self.style.ERROR(f'  ✗ {var}'))

        # Display warnings
        if results['warnings']:
            self.stdout.write(self.style.WARNING('\nWarnings:'))
            for warning in results['warnings']:
                self.stdout.write(self.style.WARNING(f'  ⚠ {warning}'))

        # Display recommendations
        self.stdout.write('\nRecommendations:')
        if results['missing_vars']:
            self.stdout.write('  • Set missing environment variables in your .env file')
        if any('localhost' in warning for warning in results['warnings']):
            self.stdout.write('  • Update CORS origins for production deployment')
        if any('example' in warning for warning in results['warnings']):
            self.stdout.write('  • Change default passwords and keys for security')
        
        self.stdout.write('\n' + '=' * 60)
        
        if results['valid']:
            self.stdout.write(
                self.style.SUCCESS('Environment configuration is valid!')
            )
        else:
            self.stdout.write(
                self.style.ERROR('Please fix the above issues before deployment.')
            )