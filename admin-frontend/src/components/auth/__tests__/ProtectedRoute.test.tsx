import React from 'react';
import { render, screen } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import ProtectedRoute from '../ProtectedRoute';
import { AuthProvider } from '../../../context/AuthContext';

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

const MockAuthProvider: React.FC<{ 
  children: React.ReactNode;
  mockUser?: any;
  mockLoading?: boolean;
  mockAuthenticated?: boolean;
}> = ({ 
  children, 
  mockUser = null, 
  mockLoading = false, 
  mockAuthenticated = false 
}) => {
  const mockAuthValue = {
    user: mockUser,
    isAuthenticated: mockAuthenticated,
    loading: mockLoading,
    login: jest.fn(),
    logout: jest.fn(),
    error: null,
  };

  return (
    <BrowserRouter>
      <AuthProvider>
        {/* Override the context value for testing */}
        <div data-testid="auth-context" data-auth={JSON.stringify(mockAuthValue)}>
          {children}
        </div>
      </AuthProvider>
    </BrowserRouter>
  );
};

describe('ProtectedRoute', () => {
  const TestComponent = () => <div>Protected Content</div>;

  it('should show loading spinner when loading', () => {
    render(
      <MockAuthProvider mockLoading={true}>
        <ProtectedRoute>
          <TestComponent />
        </ProtectedRoute>
      </MockAuthProvider>
    );

    expect(screen.getByText(/loading/i)).toBeInTheDocument();
  });

  it('should redirect to login when not authenticated', () => {
    render(
      <MockAuthProvider mockAuthenticated={false}>
        <ProtectedRoute>
          <TestComponent />
        </ProtectedRoute>
      </MockAuthProvider>
    );

    // Should not show protected content
    expect(screen.queryByText('Protected Content')).not.toBeInTheDocument();
  });

  it('should render children when authenticated', () => {
    const mockUser = { id: 1, username: 'testuser', role: 'admin' };
    
    render(
      <MockAuthProvider mockAuthenticated={true} mockUser={mockUser}>
        <ProtectedRoute>
          <TestComponent />
        </ProtectedRoute>
      </MockAuthProvider>
    );

    expect(screen.getByText('Protected Content')).toBeInTheDocument();
  });

  it('should show access denied for insufficient role', () => {
    const mockUser = { id: 1, username: 'testuser', role: 'admin' };
    
    render(
      <MockAuthProvider mockAuthenticated={true} mockUser={mockUser}>
        <ProtectedRoute requiredRole="super_admin">
          <TestComponent />
        </ProtectedRoute>
      </MockAuthProvider>
    );

    expect(screen.getByText(/access denied/i)).toBeInTheDocument();
    expect(screen.queryByText('Protected Content')).not.toBeInTheDocument();
  });

  it('should allow super_admin to access admin routes', () => {
    const mockUser = { id: 1, username: 'testuser', role: 'super_admin' };
    
    render(
      <MockAuthProvider mockAuthenticated={true} mockUser={mockUser}>
        <ProtectedRoute requiredRole="admin">
          <TestComponent />
        </ProtectedRoute>
      </MockAuthProvider>
    );

    expect(screen.getByText('Protected Content')).toBeInTheDocument();
  });
});