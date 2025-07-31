import { useInfiniteQuery } from 'react-query';
import { apiClient } from '../services/api/client';
import { queryKeys } from '../config/queryClient';

// Generic infinite query hook
export const useInfiniteData = <T>(
  queryKey: any[],
  endpoint: string,
  filters: Record<string, any> = {},
  options: {
    limit?: number;
    staleTime?: number;
    enabled?: boolean;
  } = {}
) => {
  const { limit = 20, staleTime = 5 * 60 * 1000, enabled = true } = options;

  return useInfiniteQuery(
    queryKey,
    async ({ pageParam = 1 }) => {
      const response = await apiClient.get(endpoint, {
        params: {
          ...filters,
          page: pageParam,
          limit,
        },
      });
      return response.data;
    },
    {
      getNextPageParam: (lastPage, pages) => {
        if (lastPage.next) {
          return pages.length + 1;
        }
        return undefined;
      },
      getPreviousPageParam: (firstPage, pages) => {
        if (firstPage.previous && pages.length > 1) {
          return pages.length - 1;
        }
        return undefined;
      },
      staleTime,
      enabled,
      keepPreviousData: true,
    }
  );
};

// Blog posts infinite scroll
export const useInfiniteBlogPosts = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.blogPosts.lists(), 'infinite', filters],
    '/api/v1/blog/posts/',
    filters,
    { limit: 10 }
  );
};

// Users infinite scroll
export const useInfiniteUsers = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.users.lists(), 'infinite', filters],
    '/api/v1/admin/users/',
    filters,
    { limit: 20 }
  );
};

// Organizations infinite scroll
export const useInfiniteOrganizations = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.organizations.lists(), 'infinite', filters],
    '/api/v1/organizations/',
    filters,
    { limit: 15, staleTime: 10 * 60 * 1000 }
  );
};

// Events infinite scroll
export const useInfiniteEvents = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.events.lists(), 'infinite', filters],
    '/api/v1/events/',
    filters,
    { limit: 12 }
  );
};

// Resources infinite scroll
export const useInfiniteResources = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.resources.lists(), 'infinite', filters],
    '/api/v1/resources/',
    filters,
    { limit: 15, staleTime: 15 * 60 * 1000 }
  );
};

// Activity logs infinite scroll
export const useInfiniteActivityLogs = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.activityLogs.lists(), 'infinite', filters],
    '/api/v1/admin/activity-logs/',
    filters,
    { limit: 50, staleTime: 1 * 60 * 1000 }
  );
};

// News infinite scroll
export const useInfiniteNews = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.news.lists(), 'infinite', filters],
    '/api/v1/news/',
    filters,
    { limit: 10 }
  );
};

// Utility hook for infinite scroll intersection observer
export const useInfiniteScroll = (
  hasNextPage: boolean | undefined,
  fetchNextPage: () => void,
  isFetchingNextPage: boolean
) => {
  const loadMoreRef = (node: HTMLElement | null) => {
    if (isFetchingNextPage) return;
    
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting && hasNextPage) {
          fetchNextPage();
        }
      },
      {
        threshold: 0.1,
        rootMargin: '100px',
      }
    );

    if (node) observer.observe(node);

    return () => {
      if (node) observer.unobserve(node);
    };
  };

  return { loadMoreRef };
};

// Hook for virtual scrolling with infinite query
export const useVirtualInfiniteScroll = <T>(
  data: T[],
  itemHeight: number,
  containerHeight: number,
  hasNextPage: boolean | undefined,
  fetchNextPage: () => void
) => {
  const totalItems = data.length;
  const visibleItems = Math.ceil(containerHeight / itemHeight) + 2; // Buffer items
  
  const getVisibleRange = (scrollTop: number) => {
    const startIndex = Math.floor(scrollTop / itemHeight);
    const endIndex = Math.min(startIndex + visibleItems, totalItems);
    
    // Trigger fetch when approaching end
    if (endIndex > totalItems - 10 && hasNextPage) {
      fetchNextPage();
    }
    
    return {
      startIndex: Math.max(0, startIndex),
      endIndex,
      visibleData: data.slice(startIndex, endIndex),
      offsetY: startIndex * itemHeight,
    };
  };

  return {
    getVisibleRange,
    totalHeight: totalItems * itemHeight,
    itemHeight,
  };
};

// Search with infinite scroll
export const useInfiniteSearch = (
  query: string,
  type: 'all' | 'blog' | 'users' | 'organizations' | 'events' | 'resources' = 'all'
) => {
  const getEndpoint = () => {
    switch (type) {
      case 'blog':
        return '/api/v1/blog/posts/';
      case 'users':
        return '/api/v1/admin/users/';
      case 'organizations':
        return '/api/v1/organizations/';
      case 'events':
        return '/api/v1/events/';
      case 'resources':
        return '/api/v1/resources/';
      default:
        return '/api/v1/search/';
    }
  };

  return useInfiniteData(
    ['search', type, query, 'infinite'],
    getEndpoint(),
    { search: query },
    {
      limit: 20,
      enabled: query.length >= 2,
      staleTime: 2 * 60 * 1000,
    }
  );
};

// Filtered infinite scroll
export const useInfiniteFiltered = (
  type: string,
  filters: Record<string, any>
) => {
  const getQueryKey = () => {
    switch (type) {
      case 'blog':
        return [...queryKeys.blogPosts.lists(), 'infinite', 'filtered', filters];
      case 'users':
        return [...queryKeys.users.lists(), 'infinite', 'filtered', filters];
      case 'organizations':
        return [...queryKeys.organizations.lists(), 'infinite', 'filtered', filters];
      case 'events':
        return [...queryKeys.events.lists(), 'infinite', 'filtered', filters];
      case 'resources':
        return [...queryKeys.resources.lists(), 'infinite', 'filtered', filters];
      default:
        return ['filtered', type, 'infinite', filters];
    }
  };

  const getEndpoint = () => {
    switch (type) {
      case 'blog':
        return '/api/v1/blog/posts/';
      case 'users':
        return '/api/v1/admin/users/';
      case 'organizations':
        return '/api/v1/organizations/';
      case 'events':
        return '/api/v1/events/';
      case 'resources':
        return '/api/v1/resources/';
      default:
        return `/api/v1/${type}/`;
    }
  };

  return useInfiniteData(
    getQueryKey(),
    getEndpoint(),
    filters,
    { limit: 20 }
  );
};