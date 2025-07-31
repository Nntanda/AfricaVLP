import React from 'react';
import { render, screen, waitFor, fireEvent } from '@testing-library/react';
import { vi } from 'vitest';
import ActivityLogList from '../ActivityLogList';
import { adminAPI } from '../../../services/api/endpoints';
import { ActivityLog } from '../../../types/common';

// Mock the API
vi.mock('../../../services/api/endpoints', () => ({
  adminAPI: {
    getActivityLogs: vi.fn(),
  },
}));

const mockLogs: ActivityLog[] = [
  {
    id: '1',
    action: 'create',
    description: 'Created new blog post',
    user_id: '1',
    ip_address: '192.168.1.1',
    created_at: '2024-01-15T10:00:00Z',
  },
  {
    id: '2',
    action: 'login',
    description: 'User logged in',
    admin_id: '1',
    ip_address: '192.168.1.2',
    created_at: '2024-01-16T10:00:00Z',
  },
];

const mockProps = {
  onRefresh: vi.fn(),
};

describe('ActivityLogList', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('renders loading state initially', () => {
    (adminAPI.getActivityLogs as any).mockImplementation(() => new Promise(() => {}));
    
    render(<ActivityLogList {...mockProps} />);
    
    expect(screen.getByTestId('loading-spinner')).toBeInTheDocument();
  });

  it('renders activity logs after loading', async () => {
    (adminAPI.getActivityLogs as any).mockResolvedValue({
      data: {
        results: mockLogs,
        count: 2,
      },
    });

    render(<ActivityLogList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('Created new blog post')).toBeInTheDocument();
      expect(screen.getByText('User logged in')).toBeInTheDocument();
    });

    expect(screen.getByText('Activity Logs (2)')).toBeInTheDocument();
  });

  it('renders action badges correctly', async () => {
    (adminAPI.getActivityLogs as any).mockResolvedValue({
      data: {
        results: mockLogs,
        count: 2,
      },
    });

    render(<ActivityLogList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('CREATE')).toBeInTheDocument();
      expect(screen.getByText('LOGIN')).toBeInTheDocument();
    });
  });

  it('applies filters correctly', async () => {
    const filters = {
      action: 'create',
      dateFrom: '2024-01-01',
      dateTo: '2024-01-31',
    };

    (adminAPI.getActivityLogs as any).mockResolvedValue({
      data: {
        results: [mockLogs[0]],
        count: 1,
      },
    });

    render(<ActivityLogList {...mockProps} filters={filters} />);

    await waitFor(() => {
      expect(adminAPI.getActivityLogs).toHaveBeenCalledWith({
        page: 1,
        page_size: 20,
        ordering: '-created_at',
        action: 'create',
        created_at__gte: '2024-01-01',
        created_at__lte: '2024-01-31',
      });
    });
  });

  it('displays error message when API call fails', async () => {
    (adminAPI.getActivityLogs as any).mockRejectedValue(new Error('API Error'));

    render(<ActivityLogList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('Failed to load activity logs. Please try again.')).toBeInTheDocument();
    });
  });

  it('displays empty message when no logs are found', async () => {
    (adminAPI.getActivityLogs as any).mockResolvedValue({
      data: {
        results: [],
        count: 0,
      },
    });

    render(<ActivityLogList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('No activity logs found.')).toBeInTheDocument();
    });
  });

  it('calls refresh function when refresh button is clicked', async () => {
    (adminAPI.getActivityLogs as any).mockResolvedValue({
      data: {
        results: mockLogs,
        count: 2,
      },
    });

    render(<ActivityLogList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('Created new blog post')).toBeInTheDocument();
    });

    const refreshButton = screen.getByText('Refresh');
    fireEvent.click(refreshButton);

    expect(mockProps.onRefresh).toHaveBeenCalled();
  });
});