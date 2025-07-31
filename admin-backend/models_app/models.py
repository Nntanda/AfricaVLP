from django.db import models
from django.contrib.auth.models import AbstractBaseUser, BaseUserManager
from django.utils import timezone


class AdminManager(BaseUserManager):
    def create_user(self, email, name, password=None, **extra_fields):
        if not email:
            raise ValueError('The Email field must be set')
        email = self.normalize_email(email)
        user = self.model(email=email, name=name, **extra_fields)
        user.set_password(password)
        user.save(using=self._db)
        return user

    def create_superuser(self, email, name, password=None, **extra_fields):
        extra_fields.setdefault('role', 'super_admin')
        extra_fields.setdefault('status', 1)
        return self.create_user(email, name, password, **extra_fields)


class Admin(AbstractBaseUser):
    ROLE_CHOICES = [
        ('super_admin', 'Super Admin'),
        ('admin', 'Admin'),
    ]
    
    STATUS_CHOICES = [
        (0, 'Inactive'),
        (1, 'Active'),
    ]

    id = models.AutoField(primary_key=True)
    email = models.EmailField(max_length=100, unique=True)
    name = models.CharField(max_length=100)
    password = models.CharField(max_length=255)
    token = models.CharField(max_length=255, null=True, blank=True)
    role = models.CharField(max_length=12, choices=ROLE_CHOICES)
    status = models.IntegerField(choices=STATUS_CHOICES)
    created = models.DateTimeField()
    modified = models.DateTimeField()

    objects = AdminManager()

    USERNAME_FIELD = 'email'
    REQUIRED_FIELDS = ['name']

    class Meta:
        db_table = 'admins'
        managed = False  # Don't let Django manage this table

    def __str__(self):
        return f"{self.name} ({self.email})"


class Country(models.Model):
    id = models.AutoField(primary_key=True)
    iso = models.CharField(max_length=2, null=True, blank=True)
    name = models.CharField(max_length=80, null=True, blank=True)
    nicename = models.CharField(max_length=80, null=True, blank=True)
    iso3 = models.CharField(max_length=3, null=True, blank=True)
    numcode = models.SmallIntegerField(null=True, blank=True)
    phonecode = models.IntegerField()

    class Meta:
        db_table = 'countries'
        managed = False

    def __str__(self):
        return self.nicename or self.name or ''


class City(models.Model):
    id = models.AutoField(primary_key=True)
    country = models.ForeignKey(Country, on_delete=models.CASCADE, null=True, blank=True)
    name = models.CharField(max_length=45, null=True, blank=True)

    class Meta:
        db_table = 'cities'
        managed = False

    def __str__(self):
        return self.name or ''


class Region(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=45, null=True, blank=True)

    class Meta:
        db_table = 'regions'
        managed = False

    def __str__(self):
        return self.name or ''


class OrganizationType(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=45, null=True, blank=True)

    class Meta:
        db_table = 'organization_types'
        managed = False

    def __str__(self):
        return self.name or ''


class CategoryOfOrganization(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=45, null=True, blank=True)

    class Meta:
        db_table = 'category_of_organizations'
        managed = False

    def __str__(self):
        return self.name or ''


class InstitutionType(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=45, null=True, blank=True)

    class Meta:
        db_table = 'institution_types'
        managed = False

    def __str__(self):
        return self.name or ''


class User(models.Model):
    GENDER_CHOICES = [
        ('Male', 'Male'),
        ('Female', 'Female'),
        ('Other', 'Other'),
    ]
    
    MARITAL_STATUS_CHOICES = [
        ('Single', 'Single'),
        ('Married', 'Married'),
        ('Divorced', 'Divorced'),
        ('Widowed', 'Widowed'),
    ]
    
    AVAILABILITY_CHOICES = [
        ('Full time', 'Full time'),
        ('Part time', 'Part time'),
    ]

    id = models.AutoField(primary_key=True)
    first_name = models.CharField(max_length=45, null=True, blank=True)
    last_name = models.CharField(max_length=45, null=True, blank=True)
    email = models.CharField(max_length=50, null=True, blank=True)
    password = models.CharField(max_length=255, null=True, blank=True)
    resident_country = models.ForeignKey(Country, on_delete=models.SET_NULL, null=True, blank=True, related_name='resident_users')
    city = models.ForeignKey(City, on_delete=models.SET_NULL, null=True, blank=True)
    phone_number = models.CharField(max_length=15, null=True, blank=True)
    short_profile = models.CharField(max_length=255, null=True, blank=True)
    language = models.CharField(max_length=45, null=True, blank=True)
    profile_image = models.CharField(max_length=255, null=True, blank=True)
    token = models.CharField(max_length=255, null=True, blank=True)
    token_expires = models.DateTimeField(null=True, blank=True)
    gender = models.CharField(max_length=10, choices=GENDER_CHOICES, null=True, blank=True)
    date_of_birth = models.DateField(null=True, blank=True)
    place_of_birth = models.CharField(max_length=45, null=True, blank=True)
    nationality_at_birth = models.ForeignKey(Country, on_delete=models.SET_NULL, null=True, blank=True, related_name='birth_nationality_users')
    current_nationality = models.ForeignKey(Country, on_delete=models.SET_NULL, null=True, blank=True, related_name='current_nationality_users')
    marital_status = models.CharField(max_length=15, choices=MARITAL_STATUS_CHOICES, null=True, blank=True)
    current_address = models.CharField(max_length=255, null=True, blank=True)
    availability = models.CharField(max_length=10, choices=AVAILABILITY_CHOICES, null=True, blank=True)
    is_email_verified = models.BooleanField(default=False)
    preferred_language = models.CharField(max_length=10, null=True, blank=True)
    registration_status = models.IntegerField(default=1)
    created = models.DateTimeField(null=True, blank=True)
    modified = models.DateTimeField(null=True, blank=True)
    status = models.IntegerField(default=1)
    has_volunteering_experience = models.BooleanField(null=True, blank=True)
    volunteered_program = models.CharField(max_length=255, null=True, blank=True)
    year_of_service = models.IntegerField(null=True, blank=True)
    country_served_in = models.ForeignKey(Country, on_delete=models.SET_NULL, null=True, blank=True, related_name='served_users')
    experience_rating = models.DecimalField(max_digits=4, decimal_places=2, default=0.00)

    class Meta:
        db_table = 'users'
        managed = False

    def __str__(self):
        return f"{self.first_name} {self.last_name}".strip() or self.email or f"User {self.id}"


class Organization(models.Model):
    YES_NO_CHOICES = [
        ('Yes', 'Yes'),
        ('No', 'No'),
    ]

    id = models.AutoField(primary_key=True)
    organization_type = models.ForeignKey(OrganizationType, on_delete=models.SET_NULL, null=True, blank=True)
    name = models.CharField(max_length=100, null=True, blank=True)
    about = models.TextField(null=True, blank=True)
    country = models.ForeignKey(Country, on_delete=models.SET_NULL, null=True, blank=True)
    city = models.ForeignKey(City, on_delete=models.SET_NULL, null=True, blank=True)
    logo = models.CharField(max_length=255, null=True, blank=True)
    institution_type = models.ForeignKey(InstitutionType, on_delete=models.SET_NULL, null=True, blank=True)
    government_affliliation = models.CharField(max_length=100, null=True, blank=True)
    category = models.ForeignKey(CategoryOfOrganization, on_delete=models.SET_NULL, null=True, blank=True)
    date_of_establishment = models.DateField(null=True, blank=True)
    address = models.CharField(max_length=255, null=True, blank=True)
    lat = models.CharField(max_length=20, null=True, blank=True)
    lng = models.CharField(max_length=20, null=True, blank=True)
    email = models.CharField(max_length=100, null=True, blank=True)
    phone_number = models.CharField(max_length=16, null=True, blank=True)
    website = models.CharField(max_length=55, null=True, blank=True)
    facebook_url = models.CharField(max_length=255, null=True, blank=True)
    instagram_url = models.CharField(max_length=255, null=True, blank=True)
    twitter_url = models.CharField(max_length=255, null=True, blank=True)
    user = models.ForeignKey(User, on_delete=models.SET_NULL, null=True, blank=True)
    status = models.IntegerField(default=1)
    is_verified = models.BooleanField(default=False)
    created = models.DateTimeField(null=True, blank=True)
    modified = models.DateTimeField(null=True, blank=True)
    
    # Interest areas
    pan_africanism = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    education_skills = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    health_wellbeing = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    no_poverty = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    agriculture_rural = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    democratic_values = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    environmental_sustainability = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    infrastructure_development = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    peace_security = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    culture = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    gender_inequality = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    youth_empowerment = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    reduced_inequality = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    sustainable_city = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)
    responsible_consumption = models.CharField(max_length=3, choices=YES_NO_CHOICES, null=True, blank=True)

    class Meta:
        db_table = 'organizations'
        managed = False

    def __str__(self):
        return self.name or f"Organization {self.id}"


class PublishingCategory(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=45, null=True, blank=True)

    class Meta:
        db_table = 'publishing_categories'
        managed = False

    def __str__(self):
        return self.name or ''


class Tag(models.Model):
    id = models.AutoField(primary_key=True)
    title = models.CharField(max_length=191, null=True, blank=True)

    class Meta:
        db_table = 'tags'
        managed = False

    def __str__(self):
        return self.title or ''


class BlogPost(models.Model):
    STATUS_CHOICES = [
        (1, 'Published'),
        (2, 'Draft'),
        (3, 'Archived'),
    ]

    id = models.AutoField(primary_key=True)
    title = models.CharField(max_length=500, null=True, blank=True)
    slug = models.CharField(max_length=255, null=True, blank=True)
    content = models.TextField(null=True, blank=True)
    image = models.CharField(max_length=255, null=True, blank=True)
    status = models.IntegerField(choices=STATUS_CHOICES, null=True, blank=True)
    region = models.ForeignKey(Region, on_delete=models.SET_NULL, null=True, blank=True)
    created = models.DateTimeField(null=True, blank=True)
    modified = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'blog_posts'
        managed = False

    def __str__(self):
        return self.title or f"Blog Post {self.id}"


class BlogCategory(models.Model):
    id = models.AutoField(primary_key=True)
    blog_post = models.ForeignKey(BlogPost, on_delete=models.CASCADE, null=True, blank=True)
    category_id = models.IntegerField(null=True, blank=True)

    class Meta:
        db_table = 'blog_categories'
        managed = False


class BlogPostTag(models.Model):
    id = models.AutoField(primary_key=True)
    blog_post = models.ForeignKey(BlogPost, on_delete=models.CASCADE)
    tag = models.ForeignKey(Tag, on_delete=models.CASCADE)

    class Meta:
        db_table = 'blog_posts_tags'
        managed = False


class BlogPostComment(models.Model):
    id = models.AutoField(primary_key=True)
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    blog_post = models.ForeignKey(BlogPost, on_delete=models.CASCADE)
    comment = models.TextField(null=True, blank=True)
    created = models.DateTimeField(null=True, blank=True)
    modified = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'blog_post_comments'
        managed = False


class News(models.Model):
    STATUS_CHOICES = [
        (1, 'Published'),
        (2, 'Draft'),
        (3, 'Archived'),
    ]

    id = models.AutoField(primary_key=True)
    organization = models.ForeignKey(Organization, on_delete=models.SET_NULL, null=True, blank=True)
    title = models.CharField(max_length=500, null=True, blank=True)
    slug = models.CharField(max_length=255, null=True, blank=True)
    content = models.TextField(null=True, blank=True)
    image = models.CharField(max_length=255, null=True, blank=True)
    status = models.IntegerField(choices=STATUS_CHOICES, null=True, blank=True)
    region_id = models.IntegerField(null=True, blank=True)
    created = models.DateTimeField(null=True, blank=True)
    modified = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'news'
        managed = False

    def __str__(self):
        return self.title or f"News {self.id}"


class NewsCategory(models.Model):
    id = models.AutoField(primary_key=True)
    news = models.ForeignKey(News, on_delete=models.CASCADE, null=True, blank=True)
    category_id = models.IntegerField(null=True, blank=True)

    class Meta:
        db_table = 'news_categories'
        managed = False


class NewsTag(models.Model):
    id = models.AutoField(primary_key=True)
    news = models.ForeignKey(News, on_delete=models.CASCADE)
    tag = models.ForeignKey(Tag, on_delete=models.CASCADE)

    class Meta:
        db_table = 'news_tags'
        managed = False


class NewsComment(models.Model):
    id = models.AutoField(primary_key=True)
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    news = models.ForeignKey(News, on_delete=models.CASCADE)
    comment = models.TextField(null=True, blank=True)
    created = models.DateTimeField(null=True, blank=True)
    modified = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'news_comments'
        managed = False


class Event(models.Model):
    STATUS_CHOICES = [
        (1, 'Published'),
        (2, 'Draft'),
        (3, 'Archived'),
    ]

    id = models.AutoField(primary_key=True)
    organization = models.ForeignKey(Organization, on_delete=models.SET_NULL, null=True, blank=True)
    title = models.CharField(max_length=500, null=True, blank=True)
    slug = models.CharField(max_length=255, null=True, blank=True)
    content = models.TextField(null=True, blank=True)
    image = models.CharField(max_length=255, null=True, blank=True)
    status = models.IntegerField(choices=STATUS_CHOICES, null=True, blank=True)
    region_id = models.IntegerField(null=True, blank=True)
    created = models.DateTimeField(null=True, blank=True)
    modified = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'events'
        managed = False

    def __str__(self):
        return self.title or f"Event {self.id}"


class EventComment(models.Model):
    id = models.AutoField(primary_key=True)
    event = models.ForeignKey(Event, on_delete=models.CASCADE, null=True, blank=True)
    user = models.ForeignKey(User, on_delete=models.CASCADE, null=True, blank=True)
    comment = models.TextField(null=True, blank=True)
    created = models.DateTimeField(null=True, blank=True)
    modified = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'event_comments'
        managed = False


class CategoryOfResource(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=45, null=True, blank=True)

    class Meta:
        db_table = 'category_of_resources'
        managed = False

    def __str__(self):
        return self.name or ''


class ResourceType(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=45, null=True, blank=True)

    class Meta:
        db_table = 'resource_types'
        managed = False

    def __str__(self):
        return self.name or ''


class Resource(models.Model):
    STATUS_CHOICES = [
        (1, 'Published'),
        (2, 'Draft'),
        (3, 'Archived'),
    ]

    id = models.AutoField(primary_key=True)
    organization = models.ForeignKey(Organization, on_delete=models.SET_NULL, null=True, blank=True)
    title = models.CharField(max_length=500, null=True, blank=True)
    slug = models.CharField(max_length=255, null=True, blank=True)
    content = models.TextField(null=True, blank=True)
    image = models.CharField(max_length=255, null=True, blank=True)
    status = models.IntegerField(choices=STATUS_CHOICES, null=True, blank=True)
    region_id = models.IntegerField(null=True, blank=True)
    created = models.DateTimeField(null=True, blank=True)
    modified = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'resources'
        managed = False

    def __str__(self):
        return self.title or f"Resource {self.id}"


class ResourceCategory(models.Model):
    id = models.AutoField(primary_key=True)
    resource = models.ForeignKey(Resource, on_delete=models.CASCADE, null=True, blank=True)
    category = models.ForeignKey(CategoryOfResource, on_delete=models.CASCADE, null=True, blank=True)

    class Meta:
        db_table = 'resource_categories'
        managed = False


class ActivityLog(models.Model):
    id = models.BigAutoField(primary_key=True)
    created_at = models.DateTimeField(default=timezone.now)
    scope_model = models.CharField(max_length=64)
    scope_id = models.CharField(max_length=64)
    issuer_model = models.CharField(max_length=64, null=True, blank=True)
    issuer_id = models.CharField(max_length=64, null=True, blank=True)
    object_model = models.CharField(max_length=64, null=True, blank=True)
    object_id = models.CharField(max_length=64, null=True, blank=True)
    level = models.CharField(max_length=16)
    action = models.CharField(max_length=64, null=True, blank=True)
    message = models.TextField(null=True, blank=True)
    data = models.TextField(null=True, blank=True)

    class Meta:
        db_table = 'activity_logs'
        managed = False


class AdminActivityLog(models.Model):
    id = models.BigAutoField(primary_key=True)
    created_at = models.DateTimeField(default=timezone.now)
    scope_model = models.CharField(max_length=64)
    scope_id = models.CharField(max_length=64)
    issuer_model = models.CharField(max_length=64, null=True, blank=True)
    issuer_id = models.CharField(max_length=64, null=True, blank=True)
    object_model = models.CharField(max_length=64, null=True, blank=True)
    object_id = models.CharField(max_length=64, null=True, blank=True)
    level = models.CharField(max_length=16)
    action = models.CharField(max_length=64, null=True, blank=True)
    message = models.TextField(null=True, blank=True)
    data = models.TextField(null=True, blank=True)

    class Meta:
        db_table = 'admin_activity_logs'
        managed = False


class OrganizationUser(models.Model):
    id = models.AutoField(primary_key=True)
    organization = models.ForeignKey(Organization, on_delete=models.CASCADE, null=True, blank=True)
    user = models.ForeignKey(User, on_delete=models.CASCADE, null=True, blank=True)
    role = models.CharField(max_length=45, null=True, blank=True)
    status = models.IntegerField(default=1)
    created = models.DateTimeField(null=True, blank=True)
    modified = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'organization_users'
        managed = False


class OrganizationOffice(models.Model):
    id = models.AutoField(primary_key=True)
    organization = models.ForeignKey(Organization, on_delete=models.CASCADE, null=True, blank=True)
    country = models.ForeignKey(Country, on_delete=models.SET_NULL, null=True, blank=True)
    city = models.ForeignKey(City, on_delete=models.SET_NULL, null=True, blank=True)
    address = models.CharField(max_length=255, null=True, blank=True)
    phone_number = models.CharField(max_length=16, null=True, blank=True)
    email = models.CharField(max_length=100, null=True, blank=True)
    created = models.DateTimeField(null=True, blank=True)
    modified = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'organization_offices'
        managed = False


class I18n(models.Model):
    """Translation table for multilingual content"""
    id = models.AutoField(primary_key=True)
    locale = models.CharField(max_length=6)
    model = models.CharField(max_length=255)
    foreign_key = models.IntegerField()
    field = models.CharField(max_length=255)
    content = models.TextField(null=True, blank=True)

    class Meta:
        db_table = 'i18n'
        managed = False


class SearchHistory(models.Model):
    """Model to track search history for analytics and suggestions"""
    id = models.AutoField(primary_key=True)
    user = models.ForeignKey(User, on_delete=models.CASCADE, null=True, blank=True)
    admin = models.ForeignKey(Admin, on_delete=models.CASCADE, null=True, blank=True)
    query = models.CharField(max_length=500)
    results_count = models.IntegerField(default=0)
    filters = models.JSONField(default=dict, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'search_history'
        ordering = ['-created_at']
        indexes = [
            models.Index(fields=['query']),
            models.Index(fields=['created_at']),
            models.Index(fields=['user']),
            models.Index(fields=['admin']),
        ]

    def __str__(self):
        return f"Search: {self.query} ({self.results_count} results)"


class TaskResult(models.Model):
    """
    Model to store Celery task results and metadata.
    This provides a Django-native way to track task execution.
    """
    TASK_STATUS_CHOICES = [
        ('PENDING', 'Pending'),
        ('STARTED', 'Started'),
        ('SUCCESS', 'Success'),
        ('FAILURE', 'Failure'),
        ('RETRY', 'Retry'),
        ('REVOKED', 'Revoked'),
    ]
    
    task_id = models.CharField(max_length=255, unique=True, db_index=True)
    task_name = models.CharField(max_length=255, db_index=True)
    status = models.CharField(max_length=50, choices=TASK_STATUS_CHOICES, default='PENDING')
    result = models.JSONField(blank=True, null=True)
    traceback = models.TextField(blank=True, null=True)
    started_by = models.ForeignKey(Admin, on_delete=models.SET_NULL, blank=True, null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    completed_at = models.DateTimeField(blank=True, null=True)
    
    # Task-specific metadata
    args = models.JSONField(blank=True, null=True, help_text="Task arguments")
    kwargs = models.JSONField(blank=True, null=True, help_text="Task keyword arguments")
    retries = models.IntegerField(default=0)
    eta = models.DateTimeField(blank=True, null=True, help_text="Estimated time of arrival")
    
    class Meta:
        db_table = 'task_results'
        ordering = ['-created_at']
        indexes = [
            models.Index(fields=['task_name', 'status']),
            models.Index(fields=['created_at']),
            models.Index(fields=['started_by']),
        ]
    
    def __str__(self):
        return f"{self.task_name} ({self.task_id}) - {self.status}"
    
    @property
    def duration(self):
        """Calculate task duration if completed"""
        if self.completed_at and self.created_at:
            return self.completed_at - self.created_at
        return None
    
    @property
    def is_completed(self):
        """Check if task is in a completed state"""
        return self.status in ['SUCCESS', 'FAILURE', 'REVOKED']