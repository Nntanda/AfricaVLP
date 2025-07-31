import { renderHook, act } from '@testing-library/react';
import useAuth from '../useAuth';
import { authService } from '../../services/auth';

// Mock the API client
jest.mock('../../services/api/client', () => ({
  __esModule: true,
  default: {
    get: jest.fn(),
    post: jest.fn(),
    put: jest.fn(),
    delete: jest.fn(),
  },
}));

// Mock the auth service
jest.mock('../../services/auth');
const mockAuthService = authService as jest.Mocked<typeof authService>;

describe('useAuth', () => {
  beforeEach(() => {
    jest.clearAllMocks();
    // Reset localStorage
    localStorage.clear();
  });

  it('should initialize with unauthenticated state', () => {
    mockAuthService.getCurrentUser.mockReturnValue(null);
    mockAuthService.isAuthenticated.mockReturnValue(false);

    const { result } = renderHook(() => useAuth());

    expect(result.current.user).toBeNull();
    expect(result.current.isAuthenticated).toBe(false);
    expect(result.current.loading).toBe(false);
    expect(result.current.error).toBeNull();
  });

  it('should initialize with authenticated state when user exists', () => {
    const mockUser = { 
      id: '1', 
      username: 'testuser', 
      email: 'test@example.com',
      first_name: 'Test',
      last_name: 'User',
      role: 'admin' as const,
      is_active: true
    };
    mockAuthService.getCurrentUser.mockReturnValue(mockUser);
    mockAuthService.isAuthenticated.mockReturnValue(true);

    const { result } = renderHook(() => useAuth());

    expect(result.current.user).toEqual(mockUser);
    expect(result.current.isAuthenticated).toBe(true);
    expect(result.current.loading).toBe(false);
  });

  it('should handle successful login', async () => {
    const mockUser = { 
      id: '1', 
      username: 'testuser', 
      email: 'test@example.com',
      first_name: 'Test',
      last_name: 'User',
      role: 'admin' as const,
      is_active: true
    };
    const mockTokens = { access: 'access-token', refresh: 'refresh-token' };
    
    mockAuthService.getCurrentUser.mockReturnValue(null);
    mockAuthService.isAuthenticated.mockReturnValue(false);
    mockAuthService.login.mockResolvedValue({ user: mockUser, tokens: mockTokens });

    const { result } = renderHook(() => useAuth());

    await act(async () => {
      await result.current.login({ username: 'testuser', password: 'password' });
    });

    expect(result.current.user).toEqual(mockUser);
    expect(result.current.isAuthenticated).toBe(true);
    expect(result.current.loading).toBe(false);
    expect(result.current.error).toBeNull();
    expect(mockAuthService.login).toHaveBeenCalledWith({
      username: 'testuser',
      password: 'password',
    });
  });

  it('should handle login error', async () => {
    const errorMessage = 'Invalid credentials';
    mockAuthService.getCurrentUser.mockReturnValue(null);
    mockAuthService.isAuthenticated.mockReturnValue(false);
    mockAuthService.login.mockRejectedValue(new Error(errorMessage));

    const { result } = renderHook(() => useAuth());

    await act(async () => {
      try {
        await result.current.login({ username: 'testuser', password: 'wrong' });
      } catch (error) {
        // Expected to throw
      }
    });

    expect(result.current.user).toBeNull();
    expect(result.current.isAuthenticated).toBe(false);
    expect(result.current.loading).toBe(false);
    expect(result.current.error).toBe(errorMessage);
  });

  it('should handle logout', async () => {
    const mockUser = { 
      id: '1', 
      username: 'testuser', 
      email: 'test@example.com',
      first_name: 'Test',
      last_name: 'User',
      role: 'admin' as const,
      is_active: true
    };
    mockAuthService.getCurrentUser.mockReturnValue(mockUser);
    mockAuthService.isAuthenticated.mockReturnValue(true);
    mockAuthService.logout.mockResolvedValue();

    const { result } = renderHook(() => useAuth());

    await act(async () => {
      await result.current.logout();
    });

    expect(result.current.user).toBeNull();
    expect(result.current.isAuthenticated).toBe(false);
    expect(result.current.loading).toBe(false);
    expect(mockAuthService.logout).toHaveBeenCalled();
  });

  it('should handle logout error gracefully', async () => {
    const mockUser = { 
      id: '1', 
      username: 'testuser', 
      email: 'test@example.com',
      first_name: 'Test',
      last_name: 'User',
      role: 'admin' as const,
      is_active: true
    };
    mockAuthService.getCurrentUser.mockReturnValue(mockUser);
    mockAuthService.isAuthenticated.mockReturnValue(true);
    mockAuthService.logout.mockRejectedValue(new Error('Logout failed'));

    const consoleSpy = jest.spyOn(console, 'error').mockImplementation();

    const { result } = renderHook(() => useAuth());

    await act(async () => {
      await result.current.logout();
    });

    expect(result.current.user).toBeNull();
    expect(result.current.isAuthenticated).toBe(false);
    expect(result.current.loading).toBe(false);
    expect(consoleSpy).toHaveBeenCalledWith('Logout error:', expect.any(Error));

    consoleSpy.mockRestore();
  });

  it('should handle successful token refresh', async () => {
    const mockTokens = { access: 'new-access-token', refresh: 'refresh-token' };
    mockAuthService.getCurrentUser.mockReturnValue(null);
    mockAuthService.isAuthenticated.mockReturnValue(false);
    mockAuthService.refreshToken.mockResolvedValue(mockTokens);

    const { result } = renderHook(() => useAuth());

    let refreshResult: boolean;
    await act(async () => {
      refreshResult = await result.current.refreshToken();
    });

    expect(refreshResult!).toBe(true);
    expect(mockAuthService.refreshToken).toHaveBeenCalled();
  });

  it('should handle token refresh failure', async () => {
    mockAuthService.getCurrentUser.mockReturnValue(null);
    mockAuthService.isAuthenticated.mockReturnValue(false);
    mockAuthService.refreshToken.mockRejectedValue(new Error('Refresh failed'));

    const consoleSpy = jest.spyOn(console, 'error').mockImplementation();

    const { result } = renderHook(() => useAuth());

    let refreshResult: boolean;
    await act(async () => {
      refreshResult = await result.current.refreshToken();
    });

    expect(refreshResult!).toBe(false);
    expect(result.current.user).toBeNull();
    expect(result.current.error).toBe('Session expired. Please log in again.');
    expect(consoleSpy).toHaveBeenCalledWith('Token refresh error:', expect.any(Error));

    consoleSpy.mockRestore();
  });

  it('should clear error', () => {
    mockAuthService.getCurrentUser.mockReturnValue(null);
    mockAuthService.isAuthenticated.mockReturnValue(false);

    const { result } = renderHook(() => useAuth());

    // Set an error first
    act(() => {
      result.current.clearError();
    });

    expect(result.current.error).toBeNull();
  });
});