import { renderHook, waitFor } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactNode } from 'react';
import { 
  useBlogPosts, 
  useBlogPost, 
  useFeaturedBlogPosts,
  useNews,
  useNewsItem,
  useFeaturedNews,
  useEvents,
  useEvent,
  useUpcomingEvents,
  useOrganizations,
  useOrganization,
  useCurrentUserProfile,
  useProfileMutation
} from '../useQueryData';
import { apiClient } from '../../services/api/client';

// Mock the API client
jest.mock('../../services/api/client');
const mockedApiClient = apiClient as jest.Mocked<typeof apiClient>;

// Mock toast
jest.mock('../useToast', () => ({
  toast: {
    success: jest.fn(),
    error: jest.fn(),
    info: jest.fn(),
  },
}));

// Test wrapper with QueryClient
const createWrapper = () => {
  const queryClient = new QueryClient({
    defaultOptions: {
      queries: {
        retry: false,
        cacheTime: 0,
      },
      mutations: {
        retry: false,
      },
    },
  });

  return ({ children }: { children: ReactNode }) => (
    <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
  );
};

describe('useQueryData hooks - Well-known Frontend', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  describe('useBlogPosts', () => {
    it('should fetch blog posts successfully', async () => {
      const mockData = {
        results: [
          { id: 1, title: 'Test Post 1', content: 'Content 1', featured: false },
          { id: 2, title: 'Test Post 2', content: 'Content 2', featured: true },
        ],
        count: 2,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useBlogPosts(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(mockData);
      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/blog/posts/', {
        params: {},
      });
    });

    it('should handle pagination filters', async () => {
      const filters = { page: 2, limit: 10, category: 'tech' };
      const mockData = { results: [], count: 0, next: null, previous: null };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useBlogPosts(filters), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/blog/posts/', {
        params: filters,
      });
    });
  });

  describe('useFeaturedBlogPosts', () => {
    it('should fetch featured blog posts', async () => {
      const mockData = {
        results: [
          { id: 1, title: 'Featured Post 1', featured: true },
          { id: 2, title: 'Featured Post 2', featured: true },
        ],
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useFeaturedBlogPosts(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(mockData);
      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/blog/posts/?featured=true&limit=6');
    });

    it('should have longer stale time for featured content', async () => {
      const mockData = { results: [] };
      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useFeaturedBlogPosts(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      // Featured content should have 10 minutes stale time
      expect(result.current.isStale).toBe(false);
    });
  });

  describe('useNews', () => {
    it('should fetch news articles successfully', async () => {
      const mockData = {
        results: [
          { id: 1, title: 'News 1', content: 'News content 1' },
          { id: 2, title: 'News 2', content: 'News content 2' },
        ],
        count: 2,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useNews(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(mockData);
      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/news/', {
        params: {},
      });
    });
  });

  describe('useEvents', () => {
    it('should fetch events successfully', async () => {
      const mockData = {
        results: [
          { id: 1, title: 'Event 1', date: '2024-01-01', location: 'City A' },
          { id: 2, title: 'Event 2', date: '2024-01-02', location: 'City B' },
        ],
        count: 2,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useEvents(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(mockData);
      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/events/', {
        params: {},
      });
    });
  });

  describe('useUpcomingEvents', () => {
    it('should fetch upcoming events', async () => {
      const mockData = {
        results: [
          { id: 1, title: 'Upcoming Event 1', date: '2024-12-01' },
          { id: 2, title: 'Upcoming Event 2', date: '2024-12-02' },
        ],
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useUpcomingEvents(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(mockData);
      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/events/?upcoming=true&limit=10');
    });
  });

  describe('useOrganizations', () => {
    it('should fetch organizations successfully', async () => {
      const mockData = {
        results: [
          { id: 1, name: 'Org 1', category: 'NGO', location: 'City A' },
          { id: 2, name: 'Org 2', category: 'Government', location: 'City B' },
        ],
        count: 2,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useOrganizations(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(mockData);
      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/organizations/', {
        params: {},
      });
    });

    it('should have longer stale time for organizations', async () => {
      const mockData = { results: [] };
      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useOrganizations(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      // Organizations should have 15 minutes stale time
      expect(result.current.isStale).toBe(false);
    });
  });

  describe('useCurrentUserProfile', () => {
    it('should fetch current user profile', async () => {
      const mockData = {
        id: 1,
        username: 'testuser',
        email: 'test@example.com',
        profile: {
          first_name: 'Test',
          last_name: 'User',
        },
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useCurrentUserProfile(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(mockData);
      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/profile/');
    });
  });

  describe('useProfileMutation', () => {
    it('should update profile successfully', async () => {
      const updateData = {
        first_name: 'Updated',
        last_name: 'Name',
        bio: 'Updated bio',
      };
      const updatedProfile = { id: 1, ...updateData };

      mockedApiClient.put.mockResolvedValueOnce({ data: updatedProfile });

      const { result } = renderHook(() => useProfileMutation(), {
        wrapper: createWrapper(),
      });

      result.current.mutate(updateData);

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(updatedProfile);
      expect(mockedApiClient.put).toHaveBeenCalledWith('/api/v1/profile/', updateData);
    });

    it('should handle profile update errors', async () => {
      const error = new Error('Profile Update Error');
      mockedApiClient.put.mockRejectedValueOnce(error);

      const { result } = renderHook(() => useProfileMutation(), {
        wrapper: createWrapper(),
      });

      result.current.mutate({ first_name: 'Test' });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      expect(result.current.error).toEqual(error);
    });
  });

  describe('Error handling', () => {
    it('should handle 404 errors for single items', async () => {
      const notFoundError = {
        response: {
          status: 404,
          data: { message: 'Blog post not found' },
        },
      };

      mockedApiClient.get.mockRejectedValueOnce(notFoundError);

      const { result } = renderHook(() => useBlogPost(999), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      expect(result.current.error).toEqual(notFoundError);
    });

    it('should handle network connectivity issues', async () => {
      const networkError = new Error('Network Error');
      networkError.name = 'NetworkError';

      mockedApiClient.get.mockRejectedValueOnce(networkError);

      const { result } = renderHook(() => useFeaturedNews(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      expect(result.current.error).toEqual(networkError);
    });
  });

  describe('Conditional queries', () => {
    it('should not fetch when required parameters are missing', () => {
      const { result } = renderHook(() => useEvent(''), {
        wrapper: createWrapper(),
      });

      expect(result.current.isIdle).toBe(true);
      expect(mockedApiClient.get).not.toHaveBeenCalled();
    });

    it('should not fetch organization when id is null', () => {
      const { result } = renderHook(() => useOrganization(null as any), {
        wrapper: createWrapper(),
      });

      expect(result.current.isIdle).toBe(true);
      expect(mockedApiClient.get).not.toHaveBeenCalled();
    });
  });

  describe('Cache behavior', () => {
    it('should use keepPreviousData for paginated queries', async () => {
      const page1Data = { results: [{ id: 1 }], count: 10, next: 'page2', previous: null };
      const page2Data = { results: [{ id: 2 }], count: 10, next: null, previous: 'page1' };

      mockedApiClient.get.mockResolvedValueOnce({ data: page1Data });

      const { result, rerender } = renderHook(
        ({ page }) => useBlogPosts({ page }),
        {
          wrapper: createWrapper(),
          initialProps: { page: 1 },
        }
      );

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(page1Data);

      // Change to page 2
      mockedApiClient.get.mockResolvedValueOnce({ data: page2Data });
      rerender({ page: 2 });

      // Should still have previous data while loading
      expect(result.current.data).toEqual(page1Data);
      expect(result.current.isFetching).toBe(true);

      await waitFor(() => {
        expect(result.current.data).toEqual(page2Data);
      });
    });
  });
});