"""
URL configuration for admin_backend project.

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/4.2/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  path('', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  path('', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.urls import include, path
    2. Add a URL to urlpatterns:  path('blog/', include('blog.urls'))
"""
from django.contrib import admin
from django.urls import path, include
from django.conf import settings
from django.conf.urls.static import static
from django.http import JsonResponse
from models_app.admin import admin_site

def api_root(request):
    return JsonResponse({
        'message': 'AU-VLP Admin Backend API',
        'version': '1.0.0',
        'endpoints': {
            'admin': '/admin/',
            'api': '/api/v1/',
        }
    })

urlpatterns = [
    path('admin/', admin_site.urls),  # Use our custom admin site
    path('api/v1/', api_root, name='api-root'),
    path('api/v1/auth/', include('authentication.urls')),
    path('api/v1/', include('models_app.urls')),
    path('', api_root, name='root'),
]

if settings.DEBUG:
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
    urlpatterns += static(settings.STATIC_URL, document_root=settings.STATIC_ROOT)
