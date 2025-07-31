import React from 'react';
import { render, screen, waitFor } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { AuthProvider, useAuthContext } from '../../../context/AuthContext';
import { authService } from '../../../services/auth';

// Mock the API client
jest.mock('../../../services/api/client', () => ({
  __esModule: true,
  default: {
    get: jest.fn(),
    post: jest.fn(),
    put: jest.fn(),
    delete: jest.fn(),
  },
}));

// Mock the auth service
jest.mock('../../../services/auth');
const mockAuthService = authService as jest.Mocked<typeof authService>;

// Test component that uses the auth context
const TestComponent: React.FC = () => {
  const { user, isAuthenticated, login, logout, loading, error } = useAuthContext();

  return (
    <div>
      <div data-testid="auth-status">
        {loading ? 'Loading' : isAuthenticated ? 'Authenticated' : 'Not Authenticated'}
      </div>
      {user && <div data-testid="user-info">{user.username}</div>}
      {error && <div data-testid="error">{error}</div>}
      <button
        data-testid="login-btn"
        onClick={() => login({ username: 'test', password: 'password' })}
      >
        Login
      </button>
      <button data-testid="logout-btn" onClick={logout}>
        Logout
      </button>
    </div>
  );
};

describe('AuthContext', () => {
  beforeEach(() => {
    jest.clearAllMocks();
    localStorage.clear();
  });

  it('should provide initial unauthenticated state', () => {
    mockAuthService.getCurrentUser.mockReturnValue(null);
    mockAuthService.isAuthenticated.mockReturnValue(false);

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    expect(screen.getByTestId('auth-status')).toHaveTextContent('Not Authenticated');
  });

  it('should handle successful login', async () => {
    const user = userEvent.setup();
    const mockUser = {
      id: '1',
      username: 'testuser',
      email: 'test@example.com',
      first_name: 'Test',
      last_name: 'User',
      role: 'admin' as const,
      is_active: true,
    };

    mockAuthService.getCurrentUser.mockReturnValue(null);
    mockAuthService.isAuthenticated.mockReturnValue(false);
    mockAuthService.login.mockResolvedValue({
      user: mockUser,
      tokens: { access: 'access-token', refresh: 'refresh-token' },
    });

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    await user.click(screen.getByTestId('login-btn'));

    await waitFor(() => {
      expect(screen.getByTestId('auth-status')).toHaveTextContent('Authenticated');
      expect(screen.getByTestId('user-info')).toHaveTextContent('testuser');
    });

    expect(mockAuthService.login).toHaveBeenCalledWith({
      username: 'test',
      password: 'password',
    });
  });

  it('should handle login error', async () => {
    const user = userEvent.setup();
    const errorMessage = 'Invalid credentials';

    mockAuthService.getCurrentUser.mockReturnValue(null);
    mockAuthService.isAuthenticated.mockReturnValue(false);
    mockAuthService.login.mockRejectedValue(new Error(errorMessage));

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    await user.click(screen.getByTestId('login-btn'));

    await waitFor(() => {
      expect(screen.getByTestId('error')).toHaveTextContent(errorMessage);
      expect(screen.getByTestId('auth-status')).toHaveTextContent('Not Authenticated');
    });
  });

  it('should handle logout', async () => {
    const user = userEvent.setup();
    const mockUser = {
      id: '1',
      username: 'testuser',
      email: 'test@example.com',
      first_name: 'Test',
      last_name: 'User',
      role: 'admin' as const,
      is_active: true,
    };

    mockAuthService.getCurrentUser.mockReturnValue(mockUser);
    mockAuthService.isAuthenticated.mockReturnValue(true);
    mockAuthService.logout.mockResolvedValue();

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    // Initially authenticated
    expect(screen.getByTestId('auth-status')).toHaveTextContent('Authenticated');

    await user.click(screen.getByTestId('logout-btn'));

    await waitFor(() => {
      expect(screen.getByTestId('auth-status')).toHaveTextContent('Not Authenticated');
    });

    expect(mockAuthService.logout).toHaveBeenCalled();
  });

  it('should restore authentication state on mount', () => {
    const mockUser = {
      id: '1',
      username: 'testuser',
      email: 'test@example.com',
      first_name: 'Test',
      last_name: 'User',
      role: 'admin' as const,
      is_active: true,
    };

    mockAuthService.getCurrentUser.mockReturnValue(mockUser);
    mockAuthService.isAuthenticated.mockReturnValue(true);

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    expect(screen.getByTestId('auth-status')).toHaveTextContent('Authenticated');
    expect(screen.getByTestId('user-info')).toHaveTextContent('testuser');
  });
});