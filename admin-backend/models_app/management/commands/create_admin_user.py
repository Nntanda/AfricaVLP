from django.core.management.base import BaseCommand
from django.utils import timezone
from models_app.models import Admin


class Command(BaseCommand):
    help = 'Create a superuser admin for the AU-VLP admin interface'

    def add_arguments(self, parser):
        parser.add_argument('--email', type=str, help='Admin email address')
        parser.add_argument('--name', type=str, help='Admin full name')
        parser.add_argument('--password', type=str, help='Admin password')

    def handle(self, *args, **options):
        email = options.get('email') or input('Email: ')
        name = options.get('name') or input('Full Name: ')
        password = options.get('password') or input('Password: ')

        if Admin.objects.filter(email=email).exists():
            self.stdout.write(
                self.style.ERROR(f'Admin with email {email} already exists')
            )
            return

        admin = Admin.objects.create_superuser(
            email=email,
            name=name,
            password=password
        )
        
        # Set timestamps
        admin.created = timezone.now()
        admin.modified = timezone.now()
        admin.save()

        self.stdout.write(
            self.style.SUCCESS(f'Successfully created superuser admin: {email}')
        )