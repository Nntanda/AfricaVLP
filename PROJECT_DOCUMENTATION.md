# AU-VLP Admin Client Rebuild - Project Documentation

## Overview

This document provides comprehensive documentation for the African Union Youth Leadership Program (AU-VLP) admin client rebuild project. The project involves migrating from a CakePHP-based system to a modern Django REST API backend with React frontends while preserving the existing MySQL database structure.

## Project Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Frontend Layer                           │
├─────────────────────────────────────────────────────────────┤
│  Admin React Frontend     │  Well-known React Frontend     │
│  (Port 3000)             │  (Port 3001)                   │
│  - Admin Dashboard       │  - Public Interface            │
│  - Content Management    │  - Organization Profiles       │
│  - User Management       │  - Volunteer Registration      │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     Nginx Reverse Proxy                    │
│                        (Port 80/443)                       │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    Backend Layer                           │
├─────────────────────────────────────────────────────────────┤
│  Admin Backend API       │  Well-known Backend API        │
│  (Port 8000)            │  (Port 8001)                   │
│  - Django REST API      │  - Django REST API             │
│  - JWT Authentication   │  - JWT Authentication          │
│  - Admin Management     │  - User Management             │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    Data & Services Layer                   │
├─────────────────────────────────────────────────────────────┤
│  MySQL Database         │  Redis Cache & Queue            │
│  (Port 3306)           │  (Port 6379)                   │
│  - Existing Schema     │  - Session Storage              │
│  - No Migration        │  - Celery Tasks                │
└─────────────────────────────────────────────────────────────┘
```

### Technology Stack

**Backend:**
- Django 4.2+ with Django REST Framework
- MySQL (existing database, no migration required)
- Redis for caching and task queue
- Celery for background tasks
- JWT authentication with djangorestframework-simplejwt
- Nginx for reverse proxy and static file serving

**Frontend:**
- React 18+ with TypeScript
- Vite for build tooling
- React Router for navigation
- Axios for API communication
- Tailwind CSS for styling
- React Query for state management

**Infrastructure:**
- Docker & Docker Compose for containerization
- Environment-based configuration
- Comprehensive logging and monitoring

## Completed Tasks

### Task 1: Django Backend Project Structure and Core Configuration ✅

#### What Was Accomplished:

**Django Projects Created:**
- `admin-backend/`: Django project for administrative API
- `wellknown-backend/`: Django project for client-facing API

**Core Configuration:**
- Environment management with `.env` files
- MySQL database connection (existing schema)
- Django REST Framework with JWT authentication
- CORS configuration for React frontends
- Celery setup for background tasks
- Redis caching configuration
- Comprehensive logging setup
- Static and media file handling

**Docker Integration:**
- Complete Docker Compose setup
- Nginx reverse proxy configuration
- Service orchestration with proper dependencies
- Volume management for persistent data

**React Projects:**
- `admin-frontend/`: Admin interface with Vite + TypeScript
- `wellknown-frontend/`: Client interface with Vite + TypeScript
- Basic project structure and dependencies
- Development server configuration

#### Key Files Created:
```
├── admin-backend/
│   ├── admin_backend/
│   │   ├── settings.py          # Django configuration
│   │   ├── urls.py              # URL routing
│   │   ├── celery.py            # Celery configuration
│   │   └── __init__.py          # Celery app initialization
│   ├── requirements.txt         # Python dependencies
│   ├── start.sh                 # Container startup script
│   └── Dockerfile               # Container definition
├── wellknown-backend/           # Mirror structure of admin-backend
├── admin-frontend/
│   ├── src/
│   │   ├── App.tsx              # Main React component
│   │   ├── index.tsx            # React entry point
│   │   └── App.css              # Basic styling
│   ├── package.json             # Node dependencies
│   ├── vite.config.ts           # Vite configuration
│   └── Dockerfile               # Container definition
├── wellknown-frontend/          # Mirror structure of admin-frontend
├── nginx/
│   └── nginx.conf               # Reverse proxy configuration
├── docker-compose.yml           # Service orchestration
├── .env                         # Environment variables
└── .env.example                 # Environment template
```

### Task 2: Django Models Mapping to Existing Database Schema ✅

#### What Was Accomplished:

**Core Models Created (Both Backends):**

**User Management Models:**
- `Admin`: Custom user model with role-based authentication
  - Supports super_admin and admin roles
  - Custom manager for user creation
  - JWT-compatible authentication
- `User`: Comprehensive user profile model
  - Volunteer experience tracking
  - Multi-country relationship support
  - Email verification system

**Geographic Models:**
- `Country`: ISO country codes and phone codes
- `City`: City data with country relationships
- `Region`: Regional categorization for content

**Organization Models:**
- `Organization`: Detailed organization profiles
  - Multiple office locations
  - Interest area tracking (15+ categories)
  - Verification status
  - Social media integration
- `OrganizationType`, `CategoryOfOrganization`, `InstitutionType`: Classification

**Content Models:**
- `BlogPost`: Blog content with multilingual support
- `News`: News articles linked to organizations
- `Event`: Event management with location data
- `Resource`: Resource library with categorization
- `Tag`: Universal tagging system
- `PublishingCategory`: Content categorization

**Relationship Models:**
- Junction tables for many-to-many relationships
- Comment systems for all content types
- Organization membership and office management

**Activity Tracking:**
- `ActivityLog`: General system activity logging
- `AdminActivityLog`: Admin-specific activity tracking

**Translation Support:**
- `I18n`: Multilingual content support table

#### Key Features:
- **Database Preservation**: All models use `managed = False`
- **Relationship Integrity**: Proper foreign key relationships
- **Choice Fields**: Comprehensive enums for status, roles, etc.
- **String Methods**: User-friendly representations
- **Custom Managers**: AdminManager for authentication

#### Comprehensive Testing:
- Unit tests for all models
- Relationship testing
- Choice field validation
- String representation testing
- Manager method testing

### Task 3: JWT Authentication System Implementation ✅

#### What Was Accomplished:

**Custom Authentication Backends:**
- `AdminAuthenticationBackend`: Admin user authentication
- `UserAuthenticationBackend`: Regular user authentication
- Legacy password support (CakePHP MD5/SHA1 hashes)
- Django password compatibility
- Multi-user type support

**JWT Token System:**
- Custom JWT serializers with user-specific claims
- Token refresh functionality
- Secure token blacklisting for logout
- Role-based token data

**API Endpoints (Both Backends):**
```
/api/v1/auth/
├── admin/login/              # Admin authentication
├── user/login/               # User authentication
├── logout/                   # Secure logout
├── token/refresh/            # Token refresh
├── verify/                   # Token verification
├── profile/                  # User profile
├── change-password/          # Password change
├── password-reset/           # Password reset request
└── password-reset/confirm/   # Password reset confirmation
```

**Security Features:**
- Role-based authentication flows
- Account status validation
- Email verification requirements
- Secure password hashing
- Token expiration and blacklisting
- Password reset via email

**Middleware:**
- JWT Authentication Middleware
- Activity Log Middleware
- Configurable skip paths

**Email Integration:**
- Password reset email system
- Configurable email backends
- Frontend integration for reset links

**Comprehensive Testing:**
- Authentication backend tests
- API endpoint tests
- Token generation and validation tests
- Password management tests
- Integration tests

### Task 4: Django REST API Serializers and Viewsets ✅

#### What Was Accomplished:

**Comprehensive Serializers (Both Backends):**

**Master Data Serializers:**
- `CountrySerializer`, `CitySerializer`, `RegionSerializer`: Geographic data with relationships
- `OrganizationTypeSerializer`, `CategoryOfOrganizationSerializer`, `InstitutionTypeSerializer`: Classification data
- `TagSerializer`, `PublishingCategorySerializer`, `CategoryOfResourceSerializer`, `ResourceTypeSerializer`: Content categorization

**User Management Serializers:**
- `AdminSerializer`: Role-based field filtering, automatic password hashing, timestamp management
- `UserSerializer`: Nested country/city data, full name computed field, comprehensive profile data
- `OrganizationSerializer`: Nested offices and members, interest areas, verification status
- `OrganizationUserSerializer`, `OrganizationOfficeSerializer`: Relationship management

**Content Management Serializers:**
- `BlogPostSerializer`: Translation support, category/tag relationships, region data
- `NewsSerializer`: Organization relationships, category/tag support, publication status
- `EventSerializer`: Location and organization data, event management
- `ResourceSerializer`: Category relationships, resource library management
- Comment serializers for all content types with user information

**System Serializers:**
- `ActivityLogSerializer`, `AdminActivityLogSerializer`: Audit trail management
- `I18nSerializer`: Translation and internationalization support

**Advanced ViewSets with Role-Based Permissions:**

**Admin Backend ViewSets:**
- **Permission Classes**: 
  - `IsSuperAdminOnly`: Restricts access to super administrators
  - `IsAdminOrReadOnly`: Allows read access to authenticated users, write access to admins
- **Full CRUD Operations**: Complete Create, Read, Update, Delete for all models
- **Advanced Filtering**: Search, ordering, pagination with configurable parameters
- **Custom Actions**: 
  - `publish()`: Publish content (blog posts, news, events, resources)
  - `verify()`: Verify organizations and users
  - `update_status()`: Change user/organization status
  - `verify_email()`: Email verification for users
- **Activity Logging**: All administrative actions automatically logged

**Wellknown Backend ViewSets:**
- **Permission Classes**:
  - `IsOwnerOrReadOnly`: Users can only edit their own content
  - `IsVerifiedUser`: Requires email verification for certain actions
- **Public Access**: Read-only access for most content without authentication
- **User Management**: Profile management, organization membership
- **Interactive Features**:
  - `join()`: Join organizations
  - Comment system for all content types
  - Profile management for authenticated users
- **Content Filtering**: Only published/verified content visible to public

**Key Features Implemented:**

**Advanced Filtering & Search:**
- Django Filter integration for complex filtering
- Full-text search across relevant fields
- Multi-field ordering capabilities
- Configurable pagination (20 default, 100 max)
- Geographic filtering (country, city, region)

**Security & Permissions:**
- Role-based access control (super_admin, admin, user)
- Owner-based permissions for user-generated content
- Email verification requirements for sensitive actions
- Status-based filtering (active/verified content only)
- Automatic activity logging for audit trails

**Data Relationships & Performance:**
- Nested serializers for related data display
- Efficient database queries with `select_related`/`prefetch_related`
- Foreign key name resolution (e.g., `country_name`, `organization_name`)
- Computed fields (e.g., `full_name` for users)
- Relationship management (organization members, offices)

**Content Management Features:**
- Publication workflow (draft → published)
- Content categorization and tagging
- Comment systems with user attribution
- Translation support for multilingual content
- Rich metadata for all content types

#### API Endpoints Structure:

**Admin Backend (`/api/v1/`):**
```
Master Data (Read-Only):
├── countries/              # Country master data
├── cities/                 # Cities with country relationships
├── regions/                # Regional classifications
├── organization-types/     # Organization type classifications
├── organization-categories/# Organization categories
├── institution-types/      # Institution classifications
├── tags/                   # Content tagging system
├── publishing-categories/  # Publishing classifications
├── resource-categories/    # Resource categorization
└── resource-types/         # Resource type classifications

User Management (Admin Access):
├── admins/                 # Admin user CRUD operations
│   ├── POST /              # Create admin
│   ├── GET /{id}/          # Get admin details
│   ├── PUT /{id}/          # Update admin
│   └── DELETE /{id}/       # Delete admin
├── users/                  # User profile management
│   ├── GET /               # List users (filtered)
│   ├── POST /{id}/verify_email/  # Verify user email
│   └── POST /{id}/update_status/ # Update user status
├── organizations/          # Organization management
│   ├── POST /{id}/verify/  # Verify organization
│   ├── GET /{id}/offices/  # Get organization offices
│   └── GET /{id}/members/  # Get organization members
├── organization-users/     # Membership management
└── organization-offices/   # Office location management

Content Management (Admin Access):
├── blog-posts/            # Blog content management
│   ├── POST /{id}/publish/ # Publish blog post
│   └── GET /{id}/comments/ # Get post comments
├── blog-comments/         # Blog comment management
├── news/                  # News article management
│   ├── POST /{id}/publish/ # Publish news article
│   └── GET /{id}/comments/ # Get news comments
├── news-comments/         # News comment management
├── events/                # Event management
│   ├── POST /{id}/publish/ # Publish event
│   └── GET /{id}/comments/ # Get event comments
├── event-comments/        # Event comment management
└── resources/             # Resource library management
    └── POST /{id}/publish/ # Publish resource

System Management (Admin Access):
├── activity-logs/         # General activity monitoring
├── admin-activity-logs/   # Admin-specific activity logs
└── translations/          # I18n content management
```

**Wellknown Backend (`/api/v1/`):**
```
Public Master Data (No Auth Required):
├── countries/             # Public country data
├── cities/                # Public city data with filtering
├── regions/               # Public regional data
├── organization-types/    # Public organization types
├── tags/                  # Public content tags
└── [other master data]    # All classification data

User & Organization Management:
├── users/                 # User profiles
│   ├── GET /              # List verified users (public)
│   ├── POST /             # User registration (public)
│   ├── PUT /{id}/         # Update own profile (owner only)
│   └── GET /{id}/profile/ # Detailed user profile
├── organizations/         # Organization directory
│   ├── GET /              # List verified organizations (public)
│   ├── POST /             # Create organization (verified users)
│   ├── PUT /{id}/         # Update own organization (owner only)
│   ├── POST /{id}/join/   # Join organization (verified users)
│   ├── GET /{id}/offices/ # Get organization offices
│   └── GET /{id}/members/ # Get organization members
├── organization-users/    # Public membership directory
└── organization-offices/  # Public office locations

Public Content (Read-Only):
├── blog-posts/           # Published blog posts only
│   └── GET /{id}/comments/ # Get post comments
├── blog-comments/        # Blog comments (auth required to post)
├── news/                 # Published news articles only
│   └── GET /{id}/comments/ # Get news comments
├── news-comments/        # News comments (auth required to post)
├── events/               # Published events only
│   └── GET /{id}/comments/ # Get event comments
├── event-comments/       # Event comments (auth required to post)
└── resources/            # Published resources only
```

**Testing Infrastructure:**
- **Serializer Tests**: Data serialization/deserialization validation
- **Validation Tests**: Field validation and error handling
- **Relationship Tests**: Nested data and foreign key resolution
- **Permission Tests**: Access control and security validation
- **Custom Action Tests**: Publish, verify, join functionality testing
- **Integration Tests**: End-to-end API workflow testing

**Configuration Updates:**
- **Django Filter**: Added to requirements and INSTALLED_APPS
- **URL Routing**: RESTful API endpoints with Django REST Framework router
- **Pagination**: Configurable page sizes with sensible defaults
- **Logging**: Enhanced activity logging for all administrative actions
- **Permissions**: Comprehensive permission class system

## Project Structure

```
AU-VLP-Admin-Client-Rebuild/
├── .kiro/
│   └── specs/
│       └── admin-client-rebuild/
│           ├── requirements.md
│           ├── design.md
│           └── tasks.md
├── admin-backend/
│   ├── admin_backend/
│   │   ├── __init__.py
│   │   ├── settings.py
│   │   ├── urls.py
│   │   ├── wsgi.py
│   │   ├── asgi.py
│   │   └── celery.py
│   ├── models_app/
│   │   ├── __init__.py
│   │   ├── models.py
│   │   ├── serializers.py        # API serializers
│   │   ├── viewsets.py           # API viewsets
│   │   ├── urls.py               # API URL routing
│   │   ├── tests.py
│   │   ├── test_serializers.py   # Serializer tests
│   │   ├── admin.py
│   │   ├── apps.py
│   │   └── views.py
│   ├── authentication/
│   │   ├── __init__.py
│   │   ├── backends.py
│   │   ├── serializers.py
│   │   ├── views.py
│   │   ├── urls.py
│   │   ├── middleware.py
│   │   └── tests.py
│   ├── logs/
│   ├── static/
│   ├── media/
│   ├── requirements.txt
│   ├── start.sh
│   ├── Dockerfile
│   └── manage.py
├── wellknown-backend/
│   ├── wellknown_backend/
│   │   ├── __init__.py
│   │   ├── settings.py
│   │   ├── urls.py
│   │   ├── wsgi.py
│   │   ├── asgi.py
│   │   └── celery.py
│   ├── core/
│   │   ├── __init__.py
│   │   ├── models.py
│   │   ├── serializers.py        # API serializers
│   │   ├── viewsets.py           # API viewsets
│   │   ├── urls.py               # API URL routing
│   │   ├── tests.py
│   │   ├── admin.py
│   │   ├── apps.py
│   │   └── views.py
│   ├── authentication/
│   │   ├── __init__.py
│   │   ├── backends.py
│   │   ├── serializers.py
│   │   ├── views.py
│   │   ├── urls.py
│   │   ├── middleware.py
│   │   └── tests.py
│   ├── logs/
│   ├── static/
│   ├── media/
│   ├── requirements.txt
│   ├── start.sh
│   ├── Dockerfile
│   └── manage.py
├── admin-frontend/
│   ├── src/
│   │   ├── App.tsx
│   │   ├── App.css
│   │   ├── index.tsx
│   │   └── index.css
│   ├── public/
│   │   └── index.html
│   ├── package.json
│   ├── package-lock.json
│   ├── tsconfig.json
│   ├── tsconfig.node.json
│   ├── vite.config.ts
│   └── Dockerfile
├── wellknown-frontend/
│   ├── src/
│   │   ├── App.tsx
│   │   ├── App.css
│   │   ├── index.tsx
│   │   └── index.css
│   ├── public/
│   │   └── index.html
│   ├── package.json
│   ├── package-lock.json
│   ├── tsconfig.json
│   ├── tsconfig.node.json
│   ├── vite.config.ts
│   └── Dockerfile
├── nginx/
│   └── nginx.conf
├── docker-compose.yml
├── .env
├── .env.example
└── PROJECT_DOCUMENTATION.md
```

## Database Schema

The project preserves the existing MySQL database schema without any migrations. Key tables include:

**User Management:**
- `admins` - Admin users with roles
- `users` - Regular users/volunteers
- `organizations` - Organization profiles
- `organization_users` - User-organization relationships
- `organization_offices` - Organization office locations

**Geographic Data:**
- `countries` - Country master data
- `cities` - City data with country relationships
- `regions` - Regional classifications

**Content Management:**
- `blog_posts` - Blog content
- `news` - News articles
- `events` - Event information
- `resources` - Resource library
- `tags` - Universal tagging system

**Categorization:**
- `blog_categories`, `news_categories` - Content categorization
- `publishing_categories` - Publishing classifications
- `category_of_organizations` - Organization categories
- `category_of_resources` - Resource categories

**Activity Tracking:**
- `activity_logs` - General system activities
- `admin_activity_logs` - Admin-specific activities

**Internationalization:**
- `i18n` - Translation data for multilingual content

## Configuration

### Environment Variables

```bash
# Django Settings
SECRET_KEY=your-secret-key-here
DEBUG=True
ALLOWED_HOSTS=localhost,127.0.0.1,admin-backend,wellknown-backend

# Database Configuration
DB_NAME=africa_vlp
DB_USER=africa_vlp_user
DB_PASSWORD=example_password
DB_HOST=mysql
DB_PORT=3306

# Redis Configuration
REDIS_URL=redis://redis:6379/1
CELERY_BROKER_URL=redis://redis:6379/0
CELERY_RESULT_BACKEND=redis://redis:6379/0

# CORS Configuration
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://127.0.0.1:3000,http://admin-frontend:3000,http://localhost:3001,http://127.0.0.1:3001,http://wellknown-frontend:3000

# Email Configuration (Optional)
EMAIL_BACKEND=django.core.mail.backends.console.EmailBackend
EMAIL_HOST=localhost
EMAIL_PORT=587
EMAIL_USE_TLS=True
EMAIL_HOST_USER=
EMAIL_HOST_PASSWORD=
DEFAULT_FROM_EMAIL=noreply@au-vlp.org

# Frontend URLs
FRONTEND_URL=http://localhost:3000  # admin-backend
FRONTEND_URL=http://localhost:3001  # wellknown-backend
```

### Dependencies

**Backend Dependencies (requirements.txt):**
```
Django>=4.2,<5.0
djangorestframework
mysqlclient
django-cors-headers
gunicorn
djangorestframework-simplejwt
celery
redis
django-environ
django-modeltranslation
django-filter
```

**Frontend Dependencies (package.json):**
```json
{
  "dependencies": {
    "react": "^18.2.0",
    "react-dom": "^18.2.0",
    "react-router-dom": "^6.14.1",
    "axios": "^1.4.0",
    "react-query": "^3.39.3",
    "react-i18next": "^13.0.0",
    "i18next": "^23.4.6",
    "tailwindcss": "^3.3.2"
  },
  "devDependencies": {
    "vite": "^4.4.9",
    "typescript": "^5.1.6",
    "@types/react": "^18.2.14",
    "@types/react-dom": "^18.2.7"
  }
}
```

### Docker Services

```yaml
services:
  mysql:          # Port 3306 - Database
  redis:          # Port 6379 - Cache & Queue
  admin-backend:  # Port 8000 - Admin API
  wellknown-backend: # Port 8001 - Client API
  admin-frontend: # Port 3000 - Admin UI
  wellknown-frontend: # Port 3001 - Client UI
  nginx:          # Port 80/443 - Reverse Proxy
```

## API Documentation

### API Overview

The AU-VLP system provides two distinct API backends:

1. **Admin Backend API** (`http://localhost:8000/api/v1/`): Full administrative access with comprehensive CRUD operations
2. **Wellknown Backend API** (`http://localhost:8001/api/v1/`): Public-facing API with read access to published content

Both APIs follow RESTful conventions and provide consistent response formats with proper HTTP status codes.

### Authentication Endpoints

**Admin Login:**
```http
POST /api/v1/auth/admin/login/
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password123"
}

Response:
{
  "access": "jwt_access_token",
  "refresh": "jwt_refresh_token",
  "user": {
    "id": 1,
    "email": "admin@example.com",
    "name": "Admin User",
    "role": "admin",
    "status": 1
  }
}
```

**User Login:**
```http
POST /api/v1/auth/user/login/
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}

Response:
{
  "access": "jwt_access_token",
  "refresh": "jwt_refresh_token",
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "user@example.com",
    "full_name": "John Doe"
  }
}
```

**Token Refresh:**
```http
POST /api/v1/auth/token/refresh/
Content-Type: application/json

{
  "refresh": "jwt_refresh_token"
}

Response:
{
  "access": "new_jwt_access_token"
}
```

**Profile Access:**
```http
GET /api/v1/auth/profile/
Authorization: Bearer jwt_access_token

Response:
{
  "id": 1,
  "email": "user@example.com",
  "name": "User Name",
  // ... other user fields
}
```

### Content Management Endpoints

**Blog Posts (Admin Backend):**
```http
GET /api/v1/blog-posts/
Authorization: Bearer jwt_access_token

Response:
{
  "count": 25,
  "next": "http://localhost:8000/api/v1/blog-posts/?page=2",
  "previous": null,
  "results": [
    {
      "id": 1,
      "title": "Sample Blog Post",
      "slug": "sample-blog-post",
      "content": "Blog post content...",
      "status": 1,
      "region": 1,
      "region_name": "Africa",
      "created": "2024-01-15T10:30:00Z",
      "modified": "2024-01-15T10:30:00Z",
      "tags": [
        {"id": 1, "title": "Education"}
      ],
      "categories": [
        {"id": 1, "category_id": 1}
      ]
    }
  ]
}
```

**Publish Content:**
```http
POST /api/v1/blog-posts/1/publish/
Authorization: Bearer jwt_access_token

Response:
{
  "message": "Blog post published successfully"
}
```

**Organizations (Public Access):**
```http
GET /api/v1/organizations/
# No authentication required for public data

Response:
{
  "count": 50,
  "next": "http://localhost:8001/api/v1/organizations/?page=2",
  "previous": null,
  "results": [
    {
      "id": 1,
      "name": "Sample Organization",
      "about": "Organization description...",
      "country": 1,
      "country_name": "Kenya",
      "city": 1,
      "city_name": "Nairobi",
      "organization_type": 1,
      "organization_type_name": "NGO",
      "is_verified": true,
      "website": "https://example.org",
      "offices": [
        {
          "id": 1,
          "address": "123 Main St",
          "city_name": "Nairobi",
          "country_name": "Kenya"
        }
      ],
      "members": [
        {
          "id": 1,
          "user_name": "John",
          "role": "admin"
        }
      ]
    }
  ]
}
```

**Join Organization:**
```http
POST /api/v1/organizations/1/join/
Authorization: Bearer jwt_access_token

Response:
{
  "message": "Successfully joined organization"
}
```

### Filtering and Search

**Advanced Filtering:**
```http
# Filter by multiple criteria
GET /api/v1/users/?status=1&gender=Female&has_volunteering_experience=true

# Search across multiple fields
GET /api/v1/organizations/?search=education

# Order results
GET /api/v1/blog-posts/?ordering=-created

# Combine filters
GET /api/v1/news/?organization=1&status=1&search=youth&ordering=title
```

**Geographic Filtering:**
```http
# Get cities in a specific country
GET /api/v1/cities/?country=1

# Get organizations in a specific city
GET /api/v1/organizations/?city=1&country=1
```

### Error Responses

**Standard Error Format:**
```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Invalid input data",
    "details": {
      "field_name": ["This field is required"]
    }
  }
}
```

**Common HTTP Status Codes:**
- `200 OK`: Successful request
- `201 Created`: Resource created successfully
- `400 Bad Request`: Invalid request data
- `401 Unauthorized`: Authentication required
- `403 Forbidden`: Insufficient permissions
- `404 Not Found`: Resource not found
- `500 Internal Server Error`: Server error

## Testing

### Running Tests

**Backend Tests:**
```bash
# Admin Backend
cd admin-backend
python manage.py test

# Wellknown Backend
cd wellknown-backend
python manage.py test
```

**Frontend Tests:**
```bash
# Admin Frontend
cd admin-frontend
npm test

# Wellknown Frontend
cd wellknown-frontend
npm test
```

### Test Coverage

**Backend Testing:**
- Model tests: Creation, relationships, string methods
- Authentication tests: Login, logout, token management
- API endpoint tests: All authentication endpoints
- Backend functionality tests: Password management, user creation
- Integration tests: End-to-end authentication flows

**Frontend Testing:**
- Component tests: React component rendering and behavior
- Integration tests: API communication
- E2E tests: User workflows (planned)

## Development Workflow

### Local Development Setup

1. **Clone Repository:**
   ```bash
   git clone <repository-url>
   cd AU-VLP-Admin-Client-Rebuild
   ```

2. **Environment Setup:**
   ```bash
   cp .env.example .env
   # Edit .env with your configuration
   ```

3. **Start Services:**
   ```bash
   docker-compose up -d
   ```

4. **Access Applications:**
   - Admin Frontend: http://localhost:3000
   - Wellknown Frontend: http://localhost:3001
   - Admin API: http://localhost:8000
   - Wellknown API: http://localhost:8001
   - Nginx Proxy: http://localhost

### Development Commands

**Backend Development:**
```bash
# Run migrations (if needed)
docker-compose exec admin-backend python manage.py migrate

# Create superuser
docker-compose exec admin-backend python manage.py createsuperuser

# Run tests
docker-compose exec admin-backend python manage.py test

# Access Django shell
docker-compose exec admin-backend python manage.py shell
```

**Frontend Development:**
```bash
# Install dependencies
docker-compose exec admin-frontend npm install

# Run development server
docker-compose exec admin-frontend npm run dev

# Build for production
docker-compose exec admin-frontend npm run build
```

## Security Considerations

### Authentication Security
- JWT tokens with configurable expiration
- Token blacklisting for secure logout
- Role-based access control
- Legacy password hash support
- Email verification requirements
- Secure password reset flow

### API Security
- CORS configuration for frontend domains
- Rate limiting (planned)
- Input validation and sanitization
- SQL injection prevention through ORM
- XSS protection with proper serialization

### Infrastructure Security
- Environment variable configuration
- Secure container networking
- Static file serving through Nginx
- HTTPS support (configurable)

## Monitoring and Logging

### Logging Configuration
- Structured logging with different levels
- File-based logging for persistence
- Console logging for development
- Activity logging for audit trails
- Authentication attempt logging

### Activity Tracking
- User action logging
- Admin activity monitoring
- API request tracking
- Error logging and monitoring

## Next Steps

### Upcoming Tasks (Planned)

**Task 5: Implement core API endpoints with proper permissions**
- Blog, News, Event, Resource APIs
- Organization management APIs
- User management APIs
- Search and filtering

**Task 6: Configure Django Admin interface**
- Custom admin interfaces
- Rich text editors
- Translation management
- Activity monitoring

**Task 7-8: React Frontend Development**
- Admin application structure
- Wellknown application structure
- Component libraries
- State management

**Task 9-20: Advanced Features**
- Authentication flows in React
- Core React components
- React Query integration
- Internationalization
- Responsive design
- Error handling
- Search functionality
- Background tasks
- Testing suite
- Production deployment

## Troubleshooting

### Common Issues

**Database Connection:**
- Ensure MySQL service is running
- Check database credentials in .env
- Verify network connectivity between containers

**Authentication Issues:**
- Check JWT token expiration
- Verify user account status
- Ensure email verification for users

**Frontend Build Issues:**
- Clear node_modules and reinstall
- Check Node.js version compatibility
- Verify environment variables

### Logs and Debugging

**View Logs:**
```bash
# All services
docker-compose logs

# Specific service
docker-compose logs admin-backend

# Follow logs
docker-compose logs -f admin-backend
```

**Debug Mode:**
- Set `DEBUG=True` in .env for development
- Check Django logs in `logs/django.log`
- Use browser developer tools for frontend debugging

## Conclusion

The AU-VLP admin client rebuild project has successfully completed the foundational phases, establishing a robust, modern architecture that preserves existing data while providing enhanced functionality and security. With comprehensive API serializers and viewsets now implemented, the system provides full CRUD operations with proper permissions, filtering, and security measures. The system is now ready for the next phase of development, focusing on advanced API features and frontend implementation.

The project demonstrates best practices in:
- Modern web application architecture
- Database preservation during migration
- Secure authentication systems
- RESTful API design with proper serialization
- Role-based permissions and security
- Comprehensive filtering and search capabilities
- Comprehensive testing strategies
- Docker containerization
- Documentation and maintainability

This foundation provides a solid base for the remaining development phases and ensures the system can scale and evolve with future requirements. The API layer is now complete and ready for frontend integration, with comprehensive endpoints for all system functionality.