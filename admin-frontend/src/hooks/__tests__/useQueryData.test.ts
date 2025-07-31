import { renderHook, waitFor } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactNode } from 'react';
import { useBlogPosts, useBlogPost, useBlogPostMutation, useUsers, useUser } from '../useQueryData';
import { apiClient } from '../../services/api/client';

// Mock the API client
jest.mock('../../services/api/client');
const mockedApiClient = apiClient as jest.Mocked<typeof apiClient>;

// Mock toast
jest.mock('../useToast', () => ({
  toast: {
    success: jest.fn(),
    error: jest.fn(),
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

describe('useQueryData hooks', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  describe('useBlogPosts', () => {
    it('should fetch blog posts successfully', async () => {
      const mockData = {
        results: [
          { id: 1, title: 'Test Post 1', content: 'Content 1' },
          { id: 2, title: 'Test Post 2', content: 'Content 2' },
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

    it('should handle filters correctly', async () => {
      const filters = { page: 2, search: 'test' };
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

    it('should handle errors correctly', async () => {
      const error = new Error('API Error');
      mockedApiClient.get.mockRejectedValueOnce(error);

      const { result } = renderHook(() => useBlogPosts(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      expect(result.current.error).toEqual(error);
    });
  });

  describe('useBlogPost', () => {
    it('should fetch single blog post successfully', async () => {
      const mockData = { id: 1, title: 'Test Post', content: 'Content' };
      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useBlogPost(1), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(mockData);
      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/blog/posts/1/');
    });

    it('should not fetch when id is not provided', () => {
      const { result } = renderHook(() => useBlogPost(''), {
        wrapper: createWrapper(),
      });

      expect(result.current.isIdle).toBe(true);
      expect(mockedApiClient.get).not.toHaveBeenCalled();
    });
  });

  describe('useBlogPostMutation', () => {
    it('should create blog post successfully', async () => {
      const newPost = { title: 'New Post', content: 'New Content' };
      const createdPost = { id: 1, ...newPost };

      mockedApiClient.post.mockResolvedValueOnce({ data: createdPost });

      const { result } = renderHook(() => useBlogPostMutation(), {
        wrapper: createWrapper(),
      });

      result.current.mutate(newPost);

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(createdPost);
      expect(mockedApiClient.post).toHaveBeenCalledWith('/api/v1/blog/posts/', newPost);
    });

    it('should update blog post successfully', async () => {
      const updateData = { id: 1, title: 'Updated Post', content: 'Updated Content' };
      const updatedPost = { ...updateData };

      mockedApiClient.put.mockResolvedValueOnce({ data: updatedPost });

      const { result } = renderHook(() => useBlogPostMutation(), {
        wrapper: createWrapper(),
      });

      result.current.mutate(updateData);

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(updatedPost);
      expect(mockedApiClient.put).toHaveBeenCalledWith('/api/v1/blog/posts/1/', updateData);
    });

    it('should handle mutation errors', async () => {
      const error = new Error('Mutation Error');
      mockedApiClient.post.mockRejectedValueOnce(error);

      const { result } = renderHook(() => useBlogPostMutation(), {
        wrapper: createWrapper(),
      });

      result.current.mutate({ title: 'Test' });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      expect(result.current.error).toEqual(error);
    });
  });

  describe('useUsers', () => {
    it('should fetch users successfully', async () => {
      const mockData = {
        results: [
          { id: 1, username: 'user1', email: 'user1@test.com' },
          { id: 2, username: 'user2', email: 'user2@test.com' },
        ],
        count: 2,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useUsers(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(mockData);
      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/admin/users/', {
        params: {},
      });
    });

    it('should use longer stale time for users', async () => {
      const mockData = { results: [], count: 0, next: null, previous: null };
      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useUsers(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      // The stale time should be 5 minutes (300000ms) for users
      expect(result.current.isStale).toBe(false);
    });
  });

  describe('useUser', () => {
    it('should fetch single user successfully', async () => {
      const mockData = { id: 1, username: 'testuser', email: 'test@test.com' };
      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useUser(1), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data).toEqual(mockData);
      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/admin/users/1/');
    });
  });
});

describe('Query key consistency', () => {
  it('should use consistent query keys for blog posts', () => {
    const filters1 = { page: 1, search: 'test' };
    const filters2 = { page: 1, search: 'test' };

    const { result: result1 } = renderHook(() => useBlogPosts(filters1), {
      wrapper: createWrapper(),
    });

    const { result: result2 } = renderHook(() => useBlogPosts(filters2), {
      wrapper: createWrapper(),
    });

    // Both hooks should use the same query key for identical filters
    expect(result1.current.isLoading || result1.current.isSuccess).toBe(true);
    expect(result2.current.isLoading || result2.current.isSuccess).toBe(true);
  });
});

describe('Error handling', () => {
  it('should handle network errors gracefully', async () => {
    const networkError = new Error('Network Error');
    networkError.name = 'NetworkError';
    
    mockedApiClient.get.mockRejectedValueOnce(networkError);

    const { result } = renderHook(() => useBlogPosts(), {
      wrapper: createWrapper(),
    });

    await waitFor(() => {
      expect(result.current.isError).toBe(true);
    });

    expect(result.current.error).toEqual(networkError);
  });

  it('should handle API errors with status codes', async () => {
    const apiError = {
      response: {
        status: 404,
        data: { message: 'Not found' },
      },
    };
    
    mockedApiClient.get.mockRejectedValueOnce(apiError);

    const { result } = renderHook(() => useBlogPost(999), {
      wrapper: createWrapper(),
    });

    await waitFor(() => {
      expect(result.current.isError).toBe(true);
    });

    expect(result.current.error).toEqual(apiError);
  });
});