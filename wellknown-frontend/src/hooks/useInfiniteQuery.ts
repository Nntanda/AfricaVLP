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
    { limit: 12 }
  );
};

// News infinite scroll
export const useInfiniteNews = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.news.lists(), 'infinite', filters],
    '/api/v1/news/',
    filters,
    { limit: 12 }
  );
};

// Events infinite scroll
export const useInfiniteEvents = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.events.lists(), 'infinite', filters],
    '/api/v1/events/',
    filters,
    { limit: 15 }
  );
};

// Organizations infinite scroll
export const useInfiniteOrganizations = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.organizations.lists(), 'infinite', filters],
    '/api/v1/organizations/',
    filters,
    { limit: 20, staleTime: 15 * 60 * 1000 }
  );
};

// Resources infinite scroll
export const useInfiniteResources = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.resources.lists(), 'infinite', filters],
    '/api/v1/resources/',
    filters,
    { limit: 18, staleTime: 15 * 60 * 1000 }
  );
};

// User activities infinite scroll
export const useInfiniteUserActivities = (filters: Record<string, any> = {}) => {
  return useInfiniteData(
    [...queryKeys.profile.activities, 'infinite', filters],
    '/api/v1/profile/activities/',
    filters,
    { limit: 25 }
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
        rootMargin: '200px', // Load earlier for better UX
      }
    );

    if (node) observer.observe(node);

    return () => {
      if (node) observer.unobserve(node);
    };
  };

  return { loadMoreRef };
};

// Hook for masonry/grid infinite scroll
export const useMasonryInfiniteScroll = <T>(
  data: T[],
  columns: number,
  hasNextPage: boolean | undefined,
  fetchNextPage: () => void
) => {
  const getColumnItems = () => {
    const columnItems: T[][] = Array.from({ length: columns }, () => []);
    
    data.forEach((item, index) => {
      const columnIndex = index % columns;
      columnItems[columnIndex].push(item);
    });
    
    return columnItems;
  };

  const shouldLoadMore = (visibleItems: number) => {
    // Load more when 80% of items are visible
    return visibleItems > data.length * 0.8 && hasNextPage;
  };

  return {
    getColumnItems,
    shouldLoadMore,
  };
};

// Search with infinite scroll
export const useInfiniteSearch = (
  query: string,
  type: 'all' | 'blog' | 'news' | 'events' | 'organizations' | 'resources' = 'all',
  filters: Record<string, any> = {}
) => {
  const getEndpoint = () => {
    switch (type) {
      case 'blog':
        return '/api/v1/blog/posts/';
      case 'news':
        return '/api/v1/news/';
      case 'events':
        return '/api/v1/events/';
      case 'organizations':
        return '/api/v1/organizations/';
      case 'resources':
        return '/api/v1/resources/';
      default:
        return '/api/v1/search/';
    }
  };

  const searchFilters = {
    ...filters,
    search: query,
  };

  return useInfiniteData(
    ['search', type, query, 'infinite', filters],
    getEndpoint(),
    searchFilters,
    {
      limit: 20,
      enabled: query.length >= 2,
      staleTime: 2 * 60 * 1000,
    }
  );
};

// Category-based infinite scroll
export const useInfiniteCategoryContent = (
  type: 'blog' | 'news' | 'resources' | 'organizations',
  category: string,
  filters: Record<string, any> = {}
) => {
  const getQueryKey = () => {
    switch (type) {
      case 'blog':
        return [...queryKeys.blogPosts.lists(), 'infinite', 'category', category, filters];
      case 'news':
        return [...queryKeys.news.lists(), 'infinite', 'category', category, filters];
      case 'resources':
        return [...queryKeys.resources.lists(), 'infinite', 'category', category, filters];
      case 'organizations':
        return [...queryKeys.organizations.lists(), 'infinite', 'category', category, filters];
    }
  };

  const getEndpoint = () => {
    switch (type) {
      case 'blog':
        return '/api/v1/blog/posts/';
      case 'news':
        return '/api/v1/news/';
      case 'resources':
        return '/api/v1/resources/';
      case 'organizations':
        return '/api/v1/organizations/';
    }
  };

  const categoryFilters = {
    ...filters,
    category,
  };

  return useInfiniteData(
    getQueryKey(),
    getEndpoint(),
    categoryFilters,
    {
      limit: 18,
      enabled: !!category,
      staleTime: 10 * 60 * 1000,
    }
  );
};

// Location-based infinite scroll for events and organizations
export const useInfiniteLocationContent = (
  type: 'events' | 'organizations',
  location: string,
  filters: Record<string, any> = {}
) => {
  const getQueryKey = () => {
    switch (type) {
      case 'events':
        return [...queryKeys.events.lists(), 'infinite', 'location', location, filters];
      case 'organizations':
        return [...queryKeys.organizations.lists(), 'infinite', 'location', location, filters];
    }
  };

  const getEndpoint = () => {
    switch (type) {
      case 'events':
        return '/api/v1/events/';
      case 'organizations':
        return '/api/v1/organizations/';
    }
  };

  const locationFilters = {
    ...filters,
    location,
  };

  return useInfiniteData(
    getQueryKey(),
    getEndpoint(),
    locationFilters,
    {
      limit: 15,
      enabled: !!location,
      staleTime: 10 * 60 * 1000,
    }
  );
};

// Featured content infinite scroll
export const useInfiniteFeaturedContent = (
  type: 'blog' | 'news' | 'events',
  filters: Record<string, any> = {}
) => {
  const getQueryKey = () => {
    switch (type) {
      case 'blog':
        return [...queryKeys.blogPosts.lists(), 'infinite', 'featured', filters];
      case 'news':
        return [...queryKeys.news.lists(), 'infinite', 'featured', filters];
      case 'events':
        return [...queryKeys.events.lists(), 'infinite', 'featured', filters];
    }
  };

  const getEndpoint = () => {
    switch (type) {
      case 'blog':
        return '/api/v1/blog/posts/';
      case 'news':
        return '/api/v1/news/';
      case 'events':
        return '/api/v1/events/';
    }
  };

  const featuredFilters = {
    ...filters,
    featured: true,
  };

  return useInfiniteData(
    getQueryKey(),
    getEndpoint(),
    featuredFilters,
    {
      limit: 12,
      staleTime: 10 * 60 * 1000,
    }
  );
};

// Trending content infinite scroll
export const useInfiniteTrendingContent = (
  type: 'blog' | 'news' | 'events',
  timeframe: 'day' | 'week' | 'month' = 'week'
) => {
  const getQueryKey = () => {
    switch (type) {
      case 'blog':
        return [...queryKeys.blogPosts.lists(), 'infinite', 'trending', timeframe];
      case 'news':
        return [...queryKeys.news.lists(), 'infinite', 'trending', timeframe];
      case 'events':
        return [...queryKeys.events.lists(), 'infinite', 'trending', timeframe];
    }
  };

  const getEndpoint = () => {
    switch (type) {
      case 'blog':
        return '/api/v1/blog/posts/';
      case 'news':
        return '/api/v1/news/';
      case 'events':
        return '/api/v1/events/';
    }
  };

  return useInfiniteData(
    getQueryKey(),
    getEndpoint(),
    { trending: timeframe },
    {
      limit: 15,
      staleTime: 5 * 60 * 1000,
    }
  );
};