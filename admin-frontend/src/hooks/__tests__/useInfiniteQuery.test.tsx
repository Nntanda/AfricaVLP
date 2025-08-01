import { renderHook, waitFor } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactNode } from 'react';
import { 
  useInfiniteBlogPosts, 
  useInfiniteUsers, 
  useInfiniteScroll,
  useInfiniteSearch 
} from '../useInfiniteQuery';
import apiClient from '../../services/api/client';

// Mock the API client
jest.mock('../../services/api/client');
const mockedApiClient = apiClient as jest.Mocked<typeof apiClient>;

// Mock intersection observer
const mockIntersectionObserver = jest.fn();
mockIntersectionObserver.mockReturnValue({
  observe: jest.fn(),
  unobserve: jest.fn(),
  disconnect: jest.fn(),
});
window.IntersectionObserver = mockIntersectionObserver;

// Test wrapper with QueryClient
const createWrapper = () => {
  const queryClient = new QueryClient({
    defaultOptions: {
      queries: {
        retry: false,
        cacheTime: 0,
      },
    },
  });

  return ({ children }: { children: ReactNode }) => (
    <QueryClientProvider client={queryClient}>
      {children}
    </QueryClientProvider>
  );
};

describe('useInfiniteQuery hooks', () => {
  beforeEach(() => {
    jest.clearAllMocks();
    mockIntersectionObserver.mockClear();
  });

  describe('useInfiniteBlogPosts', () => {
    it('should fetch first page of blog posts', async () => {
      const mockPage1 = {
        results: [
          { id: 1, title: 'Post 1' },
          { id: 2, title: 'Post 2' },
        ],
        count: 20,
        next: 'http://api.com/blog/posts/?page=2',
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockPage1 });

      const { result } = renderHook(() => useInfiniteBlogPosts(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.data?.pages).toHaveLength(1);
      expect(result.current.data?.pages[0]).toEqual(mockPage1);
      expect(result.current.hasNextPage).toBe(true);
      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/blog/posts/', {
        params: { page: 1, limit: 10 },
      });
    });

    it('should fetch next page when fetchNextPage is called', async () => {
      const mockPage1 = {
        results: [{ id: 1, title: 'Post 1' }],
        count: 20,
        next: 'http://api.com/blog/posts/?page=2',
        previous: null,
      };

      const mockPage2 = {
        results: [{ id: 2, title: 'Post 2' }],
        count: 20,
        next: null,
        previous: 'http://api.com/blog/posts/?page=1',
      };

      mockedApiClient.get
        .mockResolvedValueOnce({ data: mockPage1 })
        .mockResolvedValueOnce({ data: mockPage2 });

      const { result } = renderHook(() => useInfiniteBlogPosts(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      // Fetch next page
      result.current.fetchNextPage();

      await waitFor(() => {
        expect(result.current.data?.pages).toHaveLength(2);
      });

      expect(result.current.data?.pages[0]).toEqual(mockPage1);
      expect(result.current.data?.pages[1]).toEqual(mockPage2);
      expect(result.current.hasNextPage).toBe(false);
      expect(mockedApiClient.get).toHaveBeenCalledTimes(2);
      expect(mockedApiClient.get).toHaveBeenNthCalledWith(2, '/api/v1/blog/posts/', {
        params: { page: 2, limit: 10 },
      });
    });

    it('should handle filters correctly', async () => {
      const filters = { search: 'test', category: 'tech' };
      const mockData = {
        results: [],
        count: 0,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useInfiniteBlogPosts(filters), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/blog/posts/', {
        params: { ...filters, page: 1, limit: 10 },
      });
    });

    it('should handle errors correctly', async () => {
      const error = new Error('API Error');
      mockedApiClient.get.mockRejectedValueOnce(error);

      const { result } = renderHook(() => useInfiniteBlogPosts(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      expect(result.current.error).toEqual(error);
    });
  });

  describe('useInfiniteUsers', () => {
    it('should fetch users with correct limit', async () => {
      const mockData = {
        results: [{ id: 1, username: 'user1' }],
        count: 1,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useInfiniteUsers(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/admin/users/', {
        params: { page: 1, limit: 20 },
      });
    });
  });

  describe('useInfiniteSearch', () => {
    it('should not fetch when query is too short', () => {
      const { result } = renderHook(() => useInfiniteSearch('a'), {
        wrapper: createWrapper(),
      });

      expect(result.current.isIdle).toBe(true);
      expect(mockedApiClient.get).not.toHaveBeenCalled();
    });

    it('should fetch when query is long enough', async () => {
      const query = 'test query';
      const mockData = {
        results: [{ id: 1, title: 'Search result' }],
        count: 1,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useInfiniteSearch(query), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/search/', {
        params: { search: query, page: 1, limit: 20 },
      });
    });

    it('should use correct endpoint for specific types', async () => {
      const query = 'test';
      const mockData = { results: [], count: 0, next: null, previous: null };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useInfiniteSearch(query, 'blog'), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/blog/posts/', {
        params: { search: query, page: 1, limit: 20 },
      });
    });
  });

  describe('useInfiniteScroll', () => {
    it('should create intersection observer', () => {
      const mockFetchNextPage = jest.fn();
      const { result } = renderHook(() => 
        useInfiniteScroll(true, mockFetchNextPage, false)
      );

      const mockElement = document.createElement('div');
      result.current.loadMoreRef(mockElement);

      expect(mockIntersectionObserver).toHaveBeenCalledWith(
        expect.any(Function),
        {
          threshold: 0.1,
          rootMargin: '100px',
        }
      );
    });

    it('should not create observer when fetching', () => {
      const mockFetchNextPage = jest.fn();
      const { result } = renderHook(() => 
        useInfiniteScroll(true, mockFetchNextPage, true)
      );

      const mockElement = document.createElement('div');
      result.current.loadMoreRef(mockElement);

      expect(mockIntersectionObserver).not.toHaveBeenCalled();
    });

    it('should call fetchNextPage when element intersects', () => {
      const mockFetchNextPage = jest.fn();
      let intersectionCallback: (entries: any[]) => void;

      mockIntersectionObserver.mockImplementation((callback) => {
        intersectionCallback = callback;
        return {
          observe: jest.fn(),
          unobserve: jest.fn(),
          disconnect: jest.fn(),
        };
      });

      const { result } = renderHook(() => 
        useInfiniteScroll(true, mockFetchNextPage, false)
      );

      const mockElement = document.createElement('div');
      result.current.loadMoreRef(mockElement);

      // Simulate intersection
      intersectionCallback([{ isIntersecting: true }]);

      expect(mockFetchNextPage).toHaveBeenCalled();
    });

    it('should not call fetchNextPage when no next page', () => {
      const mockFetchNextPage = jest.fn();
      let intersectionCallback: (entries: any[]) => void;

      mockIntersectionObserver.mockImplementation((callback) => {
        intersectionCallback = callback;
        return {
          observe: jest.fn(),
          unobserve: jest.fn(),
          disconnect: jest.fn(),
        };
      });

      const { result } = renderHook(() => 
        useInfiniteScroll(false, mockFetchNextPage, false)
      );

      const mockElement = document.createElement('div');
      result.current.loadMoreRef(mockElement);

      // Simulate intersection
      intersectionCallback([{ isIntersecting: true }]);

      expect(mockFetchNextPage).not.toHaveBeenCalled();
    });
  });

  describe('Page parameters', () => {
    it('should calculate next page correctly', async () => {
      const mockPage1 = {
        results: [{ id: 1 }],
        next: 'http://api.com/page2',
        previous: null,
      };

      const mockPage2 = {
        results: [{ id: 2 }],
        next: 'http://api.com/page3',
        previous: 'http://api.com/page1',
      };

      mockedApiClient.get
        .mockResolvedValueOnce({ data: mockPage1 })
        .mockResolvedValueOnce({ data: mockPage2 });

      const { result } = renderHook(() => useInfiniteBlogPosts(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      result.current.fetchNextPage();

      await waitFor(() => {
        expect(result.current.data?.pages).toHaveLength(2);
      });

      expect(mockedApiClient.get).toHaveBeenNthCalledWith(1, '/api/v1/blog/posts/', {
        params: { page: 1, limit: 10 },
      });
      expect(mockedApiClient.get).toHaveBeenNthCalledWith(2, '/api/v1/blog/posts/', {
        params: { page: 2, limit: 10 },
      });
    });

    it('should handle last page correctly', async () => {
      const mockLastPage = {
        results: [{ id: 1 }],
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockLastPage });

      const { result } = renderHook(() => useInfiniteBlogPosts(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(result.current.hasNextPage).toBe(false);
    });
  });
});