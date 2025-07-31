import React from 'react';
import { render, screen } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import ProtectedRoute from '../ProtectedRoute';
import { AuthProvider } from '../../../context/AuthContext';

// Mock the API client
jest.mock('../../../services/api/client', () => ({
  __esModule: true,
  apiClient: {
    get: jest.fn(),
    post: jest.fn(),
    put: jest.fn(),
    delete: jest.fn(),
    patch: jest.fn(),
  },
  default: {
    get: jest.fn(),
    post: jest.fn(),
    put: jest.fn(),
    delete: jest.fn(),
    patch: jest.fn(),
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

    // Check for loading spinner
    expect(screen.getByRole('status', { hidden: true })).toBeInTheDocument();
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
    const mockUser = { 
      id: 1, 
      username: 'testuser', 
      email: 'test@example.com',
      permissions: ['read_posts'] 
    };
    
    render(
      <MockAuthProvider mockAuthenticated={true} mockUser={mockUser}>
        <ProtectedRoute>
          <TestComponent />
        </ProtectedRoute>
      </MockAuthProvider>
    );

    expect(screen.getByText('Protected Content')).toBeInTheDocument();
  });
});