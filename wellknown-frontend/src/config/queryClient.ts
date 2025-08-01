import { QueryClient } from 'react-query';
// Toast will be handled by components using the hook

// Default query options
const defaultQueryOptions = {
  queries: {
    retry: (failureCount: number, error: any) => {
      // Don't retry on 4xx errors except 408, 429
      if (error?.response?.status >= 400 && error?.response?.status < 500) {
        if (error?.response?.status === 408 || error?.response?.status === 429) {
          return failureCount < 2;
        }
        return false;
      }
      // Retry up to 3 times for other errors
      return failureCount < 3;
    },
    retryDelay: (attemptIndex: number) => Math.min(1000 * 2 ** attemptIndex, 30000),
    staleTime: 5 * 60 * 1000, // 5 minutes
    cacheTime: 10 * 60 * 1000, // 10 minutes
    refetchOnWindowFocus: false,
    refetchOnReconnect: true,
    refetchOnMount: true,
  },
  mutations: {
    retry: false,
    onError: (error: any) => {
      // Global error handling for mutations
      console.error('Mutation error:', error);
      // Toast notifications should be handled by individual components
    },
  },
};

// Create the query client
export const queryClient = new QueryClient({
  defaultOptions: defaultQueryOptions,
});

// Query keys factory for consistent key management
export const queryKeys = {
  // Auth
  auth: {
    profile: ['auth', 'profile'] as const,
    permissions: ['auth', 'permissions'] as const,
  },
  
  // Blog posts
  blogPosts: {
    all: ['blogPosts'] as const,
    lists: () => [...queryKeys.blogPosts.all, 'list'] as const,
    list: (filters: Record<string, any>) => [...queryKeys.blogPosts.lists(), filters] as const,
    details: () => [...queryKeys.blogPosts.all, 'detail'] as const,
    detail: (id: string | number) => [...queryKeys.blogPosts.details(), id] as const,
    categories: ['blogPosts', 'categories'] as const,
    featured: ['blogPosts', 'featured'] as const,
  },
  
  // News
  news: {
    all: ['news'] as const,
    lists: () => [...queryKeys.news.all, 'list'] as const,
    list: (filters: Record<string, any>) => [...queryKeys.news.lists(), filters] as const,
    details: () => [...queryKeys.news.all, 'detail'] as const,
    detail: (id: string | number) => [...queryKeys.news.details(), id] as const,
    categories: ['news', 'categories'] as const,
    featured: ['news', 'featured'] as const,
  },
  
  // Events
  events: {
    all: ['events'] as const,
    lists: () => [...queryKeys.events.all, 'list'] as const,
    list: (filters: Record<string, any>) => [...queryKeys.events.lists(), filters] as const,
    details: () => [...queryKeys.events.all, 'detail'] as const,
    detail: (id: string | number) => [...queryKeys.events.details(), id] as const,
    upcoming: ['events', 'upcoming'] as const,
    byLocation: (location: string) => [...queryKeys.events.all, 'location', location] as const,
  },
  
  // Organizations
  organizations: {
    all: ['organizations'] as const,
    lists: () => [...queryKeys.organizations.all, 'list'] as const,
    list: (filters: Record<string, any>) => [...queryKeys.organizations.lists(), filters] as const,
    details: () => [...queryKeys.organizations.all, 'detail'] as const,
    detail: (id: string | number) => [...queryKeys.organizations.details(), id] as const,
    directory: ['organizations', 'directory'] as const,
    byCategory: (category: string) => [...queryKeys.organizations.all, 'category', category] as const,
  },
  
  // Resources
  resources: {
    all: ['resources'] as const,
    lists: () => [...queryKeys.resources.all, 'list'] as const,
    list: (filters: Record<string, any>) => [...queryKeys.resources.lists(), filters] as const,
    details: () => [...queryKeys.resources.all, 'detail'] as const,
    detail: (id: string | number) => [...queryKeys.resources.details(), id] as const,
    categories: ['resources', 'categories'] as const,
    byCategory: (category: string) => [...queryKeys.resources.all, 'category', category] as const,
  },
  
  // User profile
  profile: {
    current: ['profile', 'current'] as const,
    organizations: ['profile', 'organizations'] as const,
    activities: ['profile', 'activities'] as const,
  },
} as const;

// Prefetch strategies
export const prefetchStrategies = {
  // Prefetch common data on app load
  prefetchCommonData: async () => {
    await Promise.allSettled([
      queryClient.prefetchQuery(queryKeys.blogPosts.featured, () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/blog/posts/?featured=true'))),
      queryClient.prefetchQuery(queryKeys.news.featured, () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/news/?featured=true'))),
      queryClient.prefetchQuery(queryKeys.events.upcoming, () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/events/?upcoming=true'))),
      queryClient.prefetchQuery(queryKeys.blogPosts.categories, () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/blog/categories/'))),
      queryClient.prefetchQuery(queryKeys.news.categories, () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/news/categories/'))),
      queryClient.prefetchQuery(queryKeys.resources.categories, () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/resources/categories/'))),
    ]);
  },
  
  // Prefetch home page data
  prefetchHomeData: async () => {
    const filters = { page: 1, limit: 6 };
    await Promise.allSettled([
      queryClient.prefetchQuery(queryKeys.blogPosts.list(filters), () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/blog/posts/', { params: filters }))),
      queryClient.prefetchQuery(queryKeys.news.list(filters), () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/news/', { params: filters }))),
      queryClient.prefetchQuery(queryKeys.events.list(filters), () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/events/', { params: filters }))),
    ]);
  },
  
  // Prefetch organization directory
  prefetchOrganizationDirectory: async () => {
    const filters = { page: 1, limit: 20 };
    await queryClient.prefetchQuery(
      queryKeys.organizations.list(filters),
      () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/organizations/', { params: filters }))
    );
  },
};

// Background sync configuration
export const backgroundSync = {
  // Sync user-specific data in the background
  syncUserData: () => {
    queryClient.invalidateQueries(queryKeys.auth.profile);
    queryClient.invalidateQueries(queryKeys.profile.current);
    queryClient.invalidateQueries(queryKeys.profile.organizations);
  },
  
  // Sync content data
  syncContentData: () => {
    queryClient.invalidateQueries(queryKeys.blogPosts.featured);
    queryClient.invalidateQueries(queryKeys.news.featured);
    queryClient.invalidateQueries(queryKeys.events.upcoming);
  },
  
  // Setup periodic background sync
  setupPeriodicSync: () => {
    // Sync every 10 minutes when the app is active
    const interval = setInterval(() => {
      if (document.visibilityState === 'visible') {
        backgroundSync.syncContentData();
      }
    }, 10 * 60 * 1000);
    
    return () => clearInterval(interval);
  },
};