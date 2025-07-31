import { authAPI } from './api/endpoints';
import { STORAGE_KEYS } from '../utils/constants';
import { LoginCredentials, AuthTokens, AuthUser } from '../types/auth';

class AuthService {
  private refreshPromise: Promise<AuthTokens | null> | null = null;

  async login(credentials: LoginCredentials): Promise<{ user: AuthUser; tokens: AuthTokens }> {
    const response = await authAPI.login(credentials);
    const { user, access, refresh } = response.data;
    
    // Store tokens
    localStorage.setItem(STORAGE_KEYS.ACCESS_TOKEN, access);
    localStorage.setItem(STORAGE_KEYS.REFRESH_TOKEN, refresh);
    localStorage.setItem(STORAGE_KEYS.USER_DATA, JSON.stringify(user));
    
    return { user, tokens: { access, refresh } };
  }

  async logout(): Promise<void> {
    try {
      await authAPI.logout();
    } catch (error) {
      // Continue with logout even if API call fails
      console.warn('Logout API call failed:', error);
    } finally {
      // Clear local storage
      localStorage.removeItem(STORAGE_KEYS.ACCESS_TOKEN);
      localStorage.removeItem(STORAGE_KEYS.REFRESH_TOKEN);
      localStorage.removeItem(STORAGE_KEYS.USER_DATA);
      this.refreshPromise = null;
    }
  }

  async refreshToken(): Promise<AuthTokens | null> {
    // Prevent multiple simultaneous refresh requests
    if (this.refreshPromise) {
      return this.refreshPromise;
    }

    const refreshToken = localStorage.getItem(STORAGE_KEYS.REFRESH_TOKEN);
    if (!refreshToken) return null;

    this.refreshPromise = this.performTokenRefresh(refreshToken);
    const result = await this.refreshPromise;
    this.refreshPromise = null;
    
    return result;
  }

  private async performTokenRefresh(refreshToken: string): Promise<AuthTokens | null> {
    try {
      const response = await authAPI.refresh(refreshToken);
      const { access } = response.data;
      
      localStorage.setItem(STORAGE_KEYS.ACCESS_TOKEN, access);
      
      return { access, refresh: refreshToken };
    } catch (error) {
      // Refresh failed, clear tokens
      await this.logout();
      return null;
    }
  }

  getCurrentUser(): AuthUser | null {
    const userData = localStorage.getItem(STORAGE_KEYS.USER_DATA);
    return userData ? JSON.parse(userData) : null;
  }

  getTokens(): AuthTokens | null {
    const access = localStorage.getItem(STORAGE_KEYS.ACCESS_TOKEN);
    const refresh = localStorage.getItem(STORAGE_KEYS.REFRESH_TOKEN);
    
    return access && refresh ? { access, refresh } : null;
  }

  isAuthenticated(): boolean {
    return !!localStorage.getItem(STORAGE_KEYS.ACCESS_TOKEN);
  }

  // Check if token is expired (basic check)
  isTokenExpired(token: string): boolean {
    try {
      const payload = JSON.parse(atob(token.split('.')[1]));
      const currentTime = Date.now() / 1000;
      return payload.exp < currentTime;
    } catch {
      return true;
    }
  }

  // Get access token, refresh if needed
  async getValidAccessToken(): Promise<string | null> {
    const tokens = this.getTokens();
    if (!tokens) return null;

    // Check if access token is expired
    if (this.isTokenExpired(tokens.access)) {
      const refreshedTokens = await this.refreshToken();
      return refreshedTokens?.access || null;
    }

    return tokens.access;
  }
}

export const authService = new AuthService();