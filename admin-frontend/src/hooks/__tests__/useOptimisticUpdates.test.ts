import { renderHook, waitFor, act } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactNode } from 'react';
import { 
  useBlogPostOptimisticUpdate,
  useUserOptimisticUpdate,
  useBulkDeleteOptimistic,
  useStatusToggleOptimistic
} from '../useOptimisticUpdates';
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

describe('useOptimisticUpdates hooks', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  describe('useBlogPostOptimisticUpdate', () => {
    it('should optimistically update blog post', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      // Pre-populate cache with existing data
      const existingPost = { id: 1, title: 'Original Title', content: 'Original Content' };
      queryClient.setQueryData(['blogPosts', 'detail', 1], existingPost);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const updateData = { id: 1, title: 'Updated Title', content: 'Updated Content' };
      const serverResponse = { ...updateData, updated_at: '2024-01-01T12:00:00Z' };

      mockedApiClient.put.mockResolvedValueOnce({ data: serverResponse });

      const { result } = renderHook(() => useBlogPostOptimisticUpdate(), { wrapper });

      // Trigger optimistic update
      act(() => {
        result.current.mutate(updateData);
      });

      // Check that cache was updated optimistically
      const cachedData = queryClient.getQueryData(['blogPosts', 'detail', 1]);
      expect(cachedData).toMatchObject({
        id: 1,
        title: 'Updated Title',
        content: 'Updated Content',
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.put).toHaveBeenCalledWith('/api/v1/blog/posts/1/', updateData);
    });

    it('should rollback on error', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const originalPost = { id: 1, title: 'Original Title', content: 'Original Content' };
      queryClient.setQueryData(['blogPosts', 'detail', 1], originalPost);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const updateData = { id: 1, title: 'Updated Title' };
      const error = new Error('Update failed');

      mockedApiClient.put.mockRejectedValueOnce(error);

      const { result } = renderHook(() => useBlogPostOptimisticUpdate(), { wrapper });

      act(() => {
        result.current.mutate(updateData);
      });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      // Check that cache was rolled back
      const cachedData = queryClient.getQueryData(['blogPosts', 'detail', 1]);
      expect(cachedData).toEqual(originalPost);
    });

    it('should update lists optimistically', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const listData = {
        results: [
          { id: 1, title: 'Post 1', content: 'Content 1' },
          { id: 2, title: 'Post 2', content: 'Content 2' },
        ],
      };
      queryClient.setQueryData(['blogPosts', 'list', {}], listData);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const updateData = { id: 1, title: 'Updated Post 1' };
      mockedApiClient.put.mockResolvedValueOnce({ data: updateData });

      const { result } = renderHook(() => useBlogPostOptimisticUpdate(), { wrapper });

      act(() => {
        result.current.mutate(updateData);
      });

      // Check that list was updated optimistically
      const cachedList = queryClient.getQueryData(['blogPosts', 'list', {}]) as any;
      expect(cachedList.results[0]).toMatchObject({
        id: 1,
        title: 'Updated Post 1',
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });
    });
  });

  describe('useUserOptimisticUpdate', () => {
    it('should optimistically update user data', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const existingUser = { id: 1, username: 'user1', email: 'user1@test.com' };
      queryClient.setQueryData(['users', 'detail', 1], existingUser);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const updateData = { id: 1, email: 'updated@test.com' };
      mockedApiClient.put.mockResolvedValueOnce({ data: updateData });

      const { result } = renderHook(() => useUserOptimisticUpdate(), { wrapper });

      act(() => {
        result.current.mutate(updateData);
      });

      const cachedData = queryClient.getQueryData(['users', 'detail', 1]);
      expect(cachedData).toMatchObject({
        id: 1,
        email: 'updated@test.com',
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });
    });
  });

  describe('useBulkDeleteOptimistic', () => {
    it('should optimistically remove items from list', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const listData = {
        results: [
          { id: 1, title: 'Item 1' },
          { id: 2, title: 'Item 2' },
          { id: 3, title: 'Item 3' },
        ],
        count: 3,
      };
      const queryKey = ['items', 'list'];
      queryClient.setQueryData(queryKey, listData);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      mockedApiClient.delete
        .mockResolvedValueOnce({})
        .mockResolvedValueOnce({});

      const { result } = renderHook(() => 
        useBulkDeleteOptimistic('/api/v1/items', queryKey), 
        { wrapper }
      );

      const idsToDelete = [1, 3];

      act(() => {
        result.current.mutate(idsToDelete);
      });

      // Check optimistic update
      const cachedData = queryClient.getQueryData(queryKey) as any;
      expect(cachedData.results).toHaveLength(1);
      expect(cachedData.results[0]).toEqual({ id: 2, title: 'Item 2' });
      expect(cachedData.count).toBe(1);

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.delete).toHaveBeenCalledTimes(2);
      expect(mockedApiClient.delete).toHaveBeenCalledWith('/api/v1/items/1/');
      expect(mockedApiClient.delete).toHaveBeenCalledWith('/api/v1/items/3/');
    });

    it('should rollback on bulk delete error', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const originalData = {
        results: [
          { id: 1, title: 'Item 1' },
          { id: 2, title: 'Item 2' },
        ],
        count: 2,
      };
      const queryKey = ['items', 'list'];
      queryClient.setQueryData(queryKey, originalData);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      mockedApiClient.delete.mockRejectedValueOnce(new Error('Delete failed'));

      const { result } = renderHook(() => 
        useBulkDeleteOptimistic('/api/v1/items', queryKey), 
        { wrapper }
      );

      act(() => {
        result.current.mutate([1]);
      });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      // Check rollback
      const cachedData = queryClient.getQueryData(queryKey);
      expect(cachedData).toEqual(originalData);
    });
  });

  describe('useStatusToggleOptimistic', () => {
    it('should optimistically toggle status', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const listData = {
        results: [
          { id: 1, title: 'Item 1', is_active: true },
          { id: 2, title: 'Item 2', is_active: false },
        ],
      };
      const queryKey = ['items', 'list'];
      queryClient.setQueryData(queryKey, listData);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      mockedApiClient.patch.mockResolvedValueOnce({ 
        data: { id: 1, title: 'Item 1', is_active: false } 
      });

      const { result } = renderHook(() => 
        useStatusToggleOptimistic('/api/v1/items', queryKey), 
        { wrapper }
      );

      act(() => {
        result.current.mutate({ id: 1, status: false });
      });

      // Check optimistic update
      const cachedData = queryClient.getQueryData(queryKey) as any;
      expect(cachedData.results[0].is_active).toBe(false);

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.patch).toHaveBeenCalledWith('/api/v1/items/1/', {
        is_active: false,
      });
    });

    it('should use custom status field', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const listData = {
        results: [{ id: 1, title: 'Item 1', published: true }],
      };
      const queryKey = ['items', 'list'];
      queryClient.setQueryData(queryKey, listData);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      mockedApiClient.patch.mockResolvedValueOnce({ 
        data: { id: 1, title: 'Item 1', published: false } 
      });

      const { result } = renderHook(() => 
        useStatusToggleOptimistic('/api/v1/items', queryKey, 'published'), 
        { wrapper }
      );

      act(() => {
        result.current.mutate({ id: 1, status: false });
      });

      const cachedData = queryClient.getQueryData(queryKey) as any;
      expect(cachedData.results[0].published).toBe(false);

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.patch).toHaveBeenCalledWith('/api/v1/items/1/', {
        published: false,
      });
    });
  });
});