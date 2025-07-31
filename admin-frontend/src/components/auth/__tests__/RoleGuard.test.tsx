import React from 'react';
import { render, screen } from '@testing-library/react';
import RoleGuard from '../RoleGuard';
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
}> = ({ children, mockUser = null }) => {
  const mockAuthValue = {
    user: mockUser,
    isAuthenticated: !!mockUser,
    loading: false,
    login: jest.fn(),
    logout: jest.fn(),
    error: null,
  };

  return (
    <AuthProvider>
      <div data-testid="auth-context" data-auth={JSON.stringify(mockAuthValue)}>
        {children}
      </div>
    </AuthProvider>
  );
};

describe('RoleGuard', () => {
  const TestComponent = () => <div>Role Protected Content</div>;
  const FallbackComponent = () => <div>Access Denied</div>;

  it('should render children when user has required role', () => {
    const mockUser = { id: 1, username: 'testuser', role: 'admin' };
    
    render(
      <MockAuthProvider mockUser={mockUser}>
        <RoleGuard allowedRoles={['admin']}>
          <TestComponent />
        </RoleGuard>
      </MockAuthProvider>
    );

    expect(screen.getByText('Role Protected Content')).toBeInTheDocument();
  });

  it('should not render children when user lacks required role', () => {
    const mockUser = { id: 1, username: 'testuser', role: 'admin' };
    
    render(
      <MockAuthProvider mockUser={mockUser}>
        <RoleGuard allowedRoles={['super_admin']}>
          <TestComponent />
        </RoleGuard>
      </MockAuthProvider>
    );

    expect(screen.queryByText('Role Protected Content')).not.toBeInTheDocument();
  });

  it('should render fallback when user lacks required role', () => {
    const mockUser = { id: 1, username: 'testuser', role: 'admin' };
    
    render(
      <MockAuthProvider mockUser={mockUser}>
        <RoleGuard allowedRoles={['super_admin']} fallback={<FallbackComponent />}>
          <TestComponent />
        </RoleGuard>
      </MockAuthProvider>
    );

    expect(screen.getByText('Access Denied')).toBeInTheDocument();
    expect(screen.queryByText('Role Protected Content')).not.toBeInTheDocument();
  });

  it('should allow super_admin to access admin content', () => {
    const mockUser = { id: 1, username: 'testuser', role: 'super_admin' };
    
    render(
      <MockAuthProvider mockUser={mockUser}>
        <RoleGuard allowedRoles={['admin']}>
          <TestComponent />
        </RoleGuard>
      </MockAuthProvider>
    );

    expect(screen.getByText('Role Protected Content')).toBeInTheDocument();
  });

  it('should not render children when user is null', () => {
    render(
      <MockAuthProvider mockUser={null}>
        <RoleGuard allowedRoles={['admin']}>
          <TestComponent />
        </RoleGuard>
      </MockAuthProvider>
    );

    expect(screen.queryByText('Role Protected Content')).not.toBeInTheDocument();
  });
});