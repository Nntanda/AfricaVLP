import { renderHook, waitFor } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactNode } from 'react';
import { 
  useInfiniteBlogPosts, 
  useInfiniteNews,
  useInfiniteEvents,
  useInfiniteOrganizations,
  useInfiniteScroll,
  useInfiniteSearch,
  useInfiniteCategoryContent,
  useMasonryInfiniteScroll
} from '../useInfiniteQuery';
import { apiClient } from '../../services/api/client';

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

describe('useInfiniteQuery hooks - Well-known Frontend', () => {
  beforeEach(() => {
    jest.clearAllMocks();
    mockIntersectionObserver.mockClear();
  });

  describe('useInfiniteBlogPosts', () => {
    it('should fetch first page of blog posts', async () => {
      const mockPage1 = {
        results: [
          { id: 1, title: 'Post 1', featured: false },
          { id: 2, title: 'Post 2', featured: true },
        ],
        count: 24,
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
        params: { page: 1, limit: 12 },
      });
    });

    it('should handle category filters', async () => {
      const filters = { category: 'technology' };
      const mockData = {
        results: [{ id: 1, title: 'Tech Post', category: 'technology' }],
        count: 1,
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
        params: { ...filters, page: 1, limit: 12 },
      });
    });
  });

  describe('useInfiniteNews', () => {
    it('should fetch news with correct limit', async () => {
      const mockData = {
        results: [
          { id: 1, title: 'News 1', published_at: '2024-01-01' },
          { id: 2, title: 'News 2', published_at: '2024-01-02' },
        ],
        count: 2,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useInfiniteNews(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/news/', {
        params: { page: 1, limit: 12 },
      });
    });
  });

  describe('useInfiniteEvents', () => {
    it('should fetch events with correct limit', async () => {
      const mockData = {
        results: [
          { id: 1, title: 'Event 1', date: '2024-12-01', location: 'City A' },
          { id: 2, title: 'Event 2', date: '2024-12-02', location: 'City B' },
        ],
        count: 2,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useInfiniteEvents(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/events/', {
        params: { page: 1, limit: 15 },
      });
    });
  });

  describe('useInfiniteOrganizations', () => {
    it('should fetch organizations with longer stale time', async () => {
      const mockData = {
        results: [
          { id: 1, name: 'Org 1', category: 'NGO' },
          { id: 2, name: 'Org 2', category: 'Government' },
        ],
        count: 2,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useInfiniteOrganizations(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/organizations/', {
        params: { page: 1, limit: 20 },
      });
      
      // Should not be stale immediately due to longer stale time
      expect(result.current.isStale).toBe(false);
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
      const query = 'test search';
      const mockData = {
        results: [
          { id: 1, title: 'Search result 1', type: 'blog' },
          { id: 2, title: 'Search result 2', type: 'news' },
        ],
        count: 2,
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

    it('should use specific endpoint for typed search', async () => {
      const query = 'events search';
      const mockData = { results: [], count: 0, next: null, previous: null };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useInfiniteSearch(query, 'events'), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/events/', {
        params: { search: query, page: 1, limit: 20 },
      });
    });

    it('should include additional filters', async () => {
      const query = 'test';
      const filters = { category: 'tech', location: 'city' };
      const mockData = { results: [], count: 0, next: null, previous: null };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useInfiniteSearch(query, 'blog', filters), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/blog/posts/', {
        params: { search: query, ...filters, page: 1, limit: 20 },
      });
    });
  });

  describe('useInfiniteCategoryContent', () => {
    it('should fetch blog posts by category', async () => {
      const category = 'technology';
      const mockData = {
        results: [{ id: 1, title: 'Tech Post', category }],
        count: 1,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useInfiniteCategoryContent('blog', category), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/blog/posts/', {
        params: { category, page: 1, limit: 18 },
      });
    });

    it('should not fetch when category is empty', () => {
      const { result } = renderHook(() => useInfiniteCategoryContent('blog', ''), {
        wrapper: createWrapper(),
      });

      expect(result.current.isIdle).toBe(true);
      expect(mockedApiClient.get).not.toHaveBeenCalled();
    });

    it('should handle resources by category', async () => {
      const category = 'documents';
      const mockData = {
        results: [{ id: 1, title: 'Document 1', category }],
        count: 1,
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: mockData });

      const { result } = renderHook(() => useInfiniteCategoryContent('resources', category), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.get).toHaveBeenCalledWith('/api/v1/resources/', {
        params: { category, page: 1, limit: 18 },
      });
    });
  });

  describe('useInfiniteScroll', () => {
    it('should create intersection observer with correct options', () => {
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
          rootMargin: '200px', // Well-known app uses larger margin for better UX
        }
      );
    });

    it('should handle intersection correctly', () => {
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
  });

  describe('useMasonryInfiniteScroll', () => {
    it('should distribute items across columns correctly', () => {
      const data = [
        { id: 1, title: 'Item 1' },
        { id: 2, title: 'Item 2' },
        { id: 3, title: 'Item 3' },
        { id: 4, title: 'Item 4' },
        { id: 5, title: 'Item 5' },
      ];

      const { result } = renderHook(() => 
        useMasonryInfiniteScroll(data, 3, true, jest.fn())
      );

      const columnItems = result.current.getColumnItems();

      expect(columnItems).toHaveLength(3);
      expect(columnItems[0]).toEqual([data[0], data[3]]); // Items 0, 3
      expect(columnItems[1]).toEqual([data[1], data[4]]); // Items 1, 4
      expect(columnItems[2]).toEqual([data[2]]);          // Item 2
    });

    it('should determine when to load more correctly', () => {
      const data = Array.from({ length: 20 }, (_, i) => ({ id: i + 1 }));

      const { result } = renderHook(() => 
        useMasonryInfiniteScroll(data, 3, true, jest.fn())
      );

      // Should load more when 80% (16 items) are visible
      expect(result.current.shouldLoadMore(16)).toBe(true);
      expect(result.current.shouldLoadMore(15)).toBe(false);
    });

    it('should not load more when no next page', () => {
      const data = Array.from({ length: 10 }, (_, i) => ({ id: i + 1 }));

      const { result } = renderHook(() => 
        useMasonryInfiniteScroll(data, 3, false, jest.fn())
      );

      expect(result.current.shouldLoadMore(8)).toBe(false);
    });
  });

  describe('Error handling', () => {
    it('should handle network errors in infinite queries', async () => {
      const networkError = new Error('Network Error');
      mockedApiClient.get.mockRejectedValueOnce(networkError);

      const { result } = renderHook(() => useInfiniteBlogPosts(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      expect(result.current.error).toEqual(networkError);
    });

    it('should handle errors when fetching next page', async () => {
      const mockPage1 = {
        results: [{ id: 1, title: 'Post 1' }],
        next: 'page2',
        previous: null,
      };

      const error = new Error('Next page error');

      mockedApiClient.get
        .mockResolvedValueOnce({ data: mockPage1 })
        .mockRejectedValueOnce(error);

      const { result } = renderHook(() => useInfiniteBlogPosts(), {
        wrapper: createWrapper(),
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      // Try to fetch next page
      result.current.fetchNextPage();

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      expect(result.current.error).toEqual(error);
    });
  });

  describe('Performance optimizations', () => {
    it('should use keepPreviousData for smooth transitions', async () => {
      const initialData = {
        results: [{ id: 1, title: 'Initial' }],
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: initialData });

      const { result, rerender } = renderHook(
        ({ filters }) => useInfiniteBlogPosts(filters),
        {
          wrapper: createWrapper(),
          initialProps: { filters: { category: 'tech' } },
        }
      );

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      const newData = {
        results: [{ id: 2, title: 'New' }],
        next: null,
        previous: null,
      };

      mockedApiClient.get.mockResolvedValueOnce({ data: newData });

      // Change filters
      rerender({ filters: { category: 'science' } });

      // Should keep previous data while loading
      expect(result.current.data?.pages[0]).toEqual(initialData);
      expect(result.current.isFetching).toBe(true);

      await waitFor(() => {
        expect(result.current.data?.pages[0]).toEqual(newData);
      });
    });
  });
});