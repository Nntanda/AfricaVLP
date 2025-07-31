import { useState, useEffect, useCallback } from 'react';
import { authService } from '../services/auth';
import { AuthUser, LoginCredentials } from '../types';

interface UseAuthReturn {
  user: AuthUser | null;
  isAuthenticated: boolean;
  loading: boolean;
  login: (credentials: LoginCredentials) => Promise<void>;
  logout: () => Promise<void>;
  refreshToken: () => Promise<boolean>;
  clearError: () => void;
  error: string | null;
}

const useAuth = (): UseAuthReturn => {
  const [user, setUser] = useState<AuthUser | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const initializeAuth = useCallback(async () => {
    const currentUser = authService.getCurrentUser();
    const isAuthenticated = authService.isAuthenticated();
    
    if (isAuthenticated && currentUser) {
      // Check if token needs refresh
      const tokens = authService.getTokens();
      if (tokens && authService.isTokenExpired(tokens.access)) {
        try {
          await authService.refreshToken();
          setUser(currentUser);
        } catch (err) {
          // Token refresh failed, clear user
          setUser(null);
          setError('Session expired. Please log in again.');
        }
      } else {
        setUser(currentUser);
      }
    }
    
    setLoading(false);
  }, []);

  useEffect(() => {
    initializeAuth();
  }, [initializeAuth]);

  // Set up periodic token refresh
  useEffect(() => {
    if (!user) return;

    const interval = setInterval(async () => {
      const tokens = authService.getTokens();
      if (tokens && authService.isTokenExpired(tokens.access)) {
        try {
          await authService.refreshToken();
        } catch (err) {
          console.error('Background token refresh failed:', err);
          setUser(null);
          setError('Session expired. Please log in again.');
        }
      }
    }, 5 * 60 * 1000); // Check every 5 minutes

    return () => clearInterval(interval);
  }, [user]);

  const login = async (credentials: LoginCredentials) => {
    setLoading(true);
    setError(null);
    
    try {
      const { user: authUser } = await authService.login(credentials);
      setUser(authUser);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Login failed');
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const logout = async () => {
    setLoading(true);
    
    try {
      await authService.logout();
    } catch (err) {
      console.error('Logout error:', err);
    } finally {
      // Always clear user and error on logout, even if API call fails
      setUser(null);
      setError(null);
      setLoading(false);
    }
  };

  const refreshToken = async (): Promise<boolean> => {
    try {
      const tokens = await authService.refreshToken();
      return !!tokens;
    } catch (err) {
      console.error('Token refresh error:', err);
      setUser(null);
      setError('Session expired. Please log in again.');
      return false;
    }
  };

  const clearError = () => {
    setError(null);
  };

  return {
    user,
    isAuthenticated: !!user,
    loading,
    login,
    logout,
    refreshToken,
    clearError,
    error,
  };
};

export default useAuth;