import React from 'react';
import { render, screen } from '@testing-library/react';
import PermissionGuard from '../PermissionGuard';
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

describe('PermissionGuard', () => {
  const TestComponent = () => <div>Permission Protected Content</div>;
  const FallbackComponent = () => <div>Access Denied</div>;

  it('should render children when user has required permission', () => {
    const mockUser = { 
      id: 1, 
      username: 'testuser', 
      permissions: ['read_posts', 'write_posts'] 
    };
    
    render(
      <MockAuthProvider mockUser={mockUser}>
        <PermissionGuard requiredPermissions={['read_posts']}>
          <TestComponent />
        </PermissionGuard>
      </MockAuthProvider>
    );

    expect(screen.getByText('Permission Protected Content')).toBeInTheDocument();
  });

  it('should not render children when user lacks required permission', () => {
    const mockUser = { 
      id: 1, 
      username: 'testuser', 
      permissions: ['read_posts'] 
    };
    
    render(
      <MockAuthProvider mockUser={mockUser}>
        <PermissionGuard requiredPermissions={['admin_access']}>
          <TestComponent />
        </PermissionGuard>
      </MockAuthProvider>
    );

    expect(screen.queryByText('Permission Protected Content')).not.toBeInTheDocument();
  });

  it('should render fallback when user lacks required permission', () => {
    const mockUser = { 
      id: 1, 
      username: 'testuser', 
      permissions: ['read_posts'] 
    };
    
    render(
      <MockAuthProvider mockUser={mockUser}>
        <PermissionGuard 
          requiredPermissions={['admin_access']} 
          fallback={<FallbackComponent />}
        >
          <TestComponent />
        </PermissionGuard>
      </MockAuthProvider>
    );

    expect(screen.getByText('Access Denied')).toBeInTheDocument();
    expect(screen.queryByText('Permission Protected Content')).not.toBeInTheDocument();
  });

  it('should require ALL permissions when requireAll is true', () => {
    const mockUser = { 
      id: 1, 
      username: 'testuser', 
      permissions: ['read_posts'] 
    };
    
    render(
      <MockAuthProvider mockUser={mockUser}>
        <PermissionGuard 
          requiredPermissions={['read_posts', 'write_posts']} 
          requireAll={true}
        >
          <TestComponent />
        </PermissionGuard>
      </MockAuthProvider>
    );

    expect(screen.queryByText('Permission Protected Content')).not.toBeInTheDocument();
  });

  it('should require ANY permission when requireAll is false', () => {
    const mockUser = { 
      id: 1, 
      username: 'testuser', 
      permissions: ['read_posts'] 
    };
    
    render(
      <MockAuthProvider mockUser={mockUser}>
        <PermissionGuard 
          requiredPermissions={['read_posts', 'write_posts']} 
          requireAll={false}
        >
          <TestComponent />
        </PermissionGuard>
      </MockAuthProvider>
    );

    expect(screen.getByText('Permission Protected Content')).toBeInTheDocument();
  });

  it('should not render children when user is null', () => {
    render(
      <MockAuthProvider mockUser={null}>
        <PermissionGuard requiredPermissions={['read_posts']}>
          <TestComponent />
        </PermissionGuard>
      </MockAuthProvider>
    );

    expect(screen.queryByText('Permission Protected Content')).not.toBeInTheDocument();
  });

  it('should not render children when user has no permissions', () => {
    const mockUser = { 
      id: 1, 
      username: 'testuser', 
      permissions: [] 
    };
    
    render(
      <MockAuthProvider mockUser={mockUser}>
        <PermissionGuard requiredPermissions={['read_posts']}>
          <TestComponent />
        </PermissionGuard>
      </MockAuthProvider>
    );

    expect(screen.queryByText('Permission Protected Content')).not.toBeInTheDocument();
  });
});