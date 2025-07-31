import React from 'react';
import { render, screen, waitFor, fireEvent } from '@testing-library/react';
import { vi } from 'vitest';
import UserList from '../UserList';
import { adminAPI } from '../../../services/api/endpoints';
import { User } from '../../../types/common';

// Mock the API
vi.mock('../../../services/api/endpoints', () => ({
  adminAPI: {
    getUsers: vi.fn(),
  },
}));

const mockUsers: User[] = [
  {
    id: '1',
    username: 'john_doe',
    email: 'john@example.com',
    first_name: 'John',
    last_name: 'Doe',
    role: 'admin',
    is_active: true,
    created_at: '2024-01-15T10:00:00Z',
    updated_at: '2024-01-15T10:00:00Z',
  },
  {
    id: '2',
    username: 'jane_smith',
    email: 'jane@example.com',
    first_name: 'Jane',
    last_name: 'Smith',
    role: 'user',
    is_active: false,
    created_at: '2024-01-16T10:00:00Z',
    updated_at: '2024-01-16T10:00:00Z',
  },
];

const mockProps = {
  onEdit: vi.fn(),
  onDelete: vi.fn(),
  onRefresh: vi.fn(),
};

describe('UserList', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('renders loading state initially', () => {
    (adminAPI.getUsers as any).mockImplementation(() => new Promise(() => {}));
    
    render(<UserList {...mockProps} />);
    
    expect(screen.getByTestId('loading-spinner')).toBeInTheDocument();
  });

  it('renders users after loading', async () => {
    (adminAPI.getUsers as any).mockResolvedValue({
      data: {
        results: mockUsers,
        count: 2,
      },
    });

    render(<UserList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('John Doe')).toBeInTheDocument();
      expect(screen.getByText('Jane Smith')).toBeInTheDocument();
    });

    expect(screen.getByText('Users (2)')).toBeInTheDocument();
  });

  it('renders role badges correctly', async () => {
    (adminAPI.getUsers as any).mockResolvedValue({
      data: {
        results: mockUsers,
        count: 2,
      },
    });

    render(<UserList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('ADMIN')).toBeInTheDocument();
      expect(screen.getByText('USER')).toBeInTheDocument();
    });
  });

  it('renders status badges correctly', async () => {
    (adminAPI.getUsers as any).mockResolvedValue({
      data: {
        results: mockUsers,
        count: 2,
      },
    });

    render(<UserList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('Active')).toBeInTheDocument();
      expect(screen.getByText('Inactive')).toBeInTheDocument();
    });
  });

  it('calls onEdit when edit button is clicked', async () => {
    (adminAPI.getUsers as any).mockResolvedValue({
      data: {
        results: mockUsers,
        count: 2,
      },
    });

    render(<UserList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('John Doe')).toBeInTheDocument();
    });

    const editButtons = screen.getAllByText('Edit');
    fireEvent.click(editButtons[0]);

    expect(mockProps.onEdit).toHaveBeenCalledWith(mockUsers[0]);
  });

  it('calls onDelete when delete button is clicked', async () => {
    (adminAPI.getUsers as any).mockResolvedValue({
      data: {
        results: mockUsers,
        count: 2,
      },
    });

    render(<UserList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('John Doe')).toBeInTheDocument();
    });

    const deleteButtons = screen.getAllByText('Delete');
    fireEvent.click(deleteButtons[0]);

    expect(mockProps.onDelete).toHaveBeenCalledWith(mockUsers[0]);
  });

  it('displays error message when API call fails', async () => {
    (adminAPI.getUsers as any).mockRejectedValue(new Error('API Error'));

    render(<UserList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('Failed to load users. Please try again.')).toBeInTheDocument();
    });
  });

  it('displays empty message when no users are found', async () => {
    (adminAPI.getUsers as any).mockResolvedValue({
      data: {
        results: [],
        count: 0,
      },
    });

    render(<UserList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('No users found.')).toBeInTheDocument();
    });
  });
});