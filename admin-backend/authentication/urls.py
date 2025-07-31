from django.urls import path
from rest_framework_simplejwt.views import TokenRefreshView
from . import views

urlpatterns = [
    # Authentication endpoints
    path('admin/login/', views.AdminLoginView.as_view(), name='admin_login'),
    path('user/login/', views.UserLoginView.as_view(), name='user_login'),
    path('logout/', views.LogoutView.as_view(), name='logout'),
    path('token/refresh/', TokenRefreshView.as_view(), name='token_refresh'),
    path('verify/', views.verify_token, name='verify_token'),
    
    # Profile endpoints
    path('profile/', views.ProfileView.as_view(), name='profile'),
    
    # Password management
    path('change-password/', views.ChangePasswordView.as_view(), name='change_password'),
    path('password-reset/', views.PasswordResetRequestView.as_view(), name='password_reset'),
    path('password-reset/confirm/', views.PasswordResetConfirmView.as_view(), name='password_reset_confirm'),
]