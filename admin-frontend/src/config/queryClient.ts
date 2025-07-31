import { QueryClient } from 'react-query';
import { toast } from '../hooks/useToast';

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
      const message = error?.response?.data?.message || error?.message || 'An error occurred';
      toast.error(message);
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
    tags: ['blogPosts', 'tags'] as const,
  },
  
  // Users
  users: {
    all: ['users'] as const,
    lists: () => [...queryKeys.users.all, 'list'] as const,
    list: (filters: Record<string, any>) => [...queryKeys.users.lists(), filters] as const,
    details: () => [...queryKeys.users.all, 'detail'] as const,
    detail: (id: string | number) => [...queryKeys.users.details(), id] as const,
  },
  
  // Organizations
  organizations: {
    all: ['organizations'] as const,
    lists: () => [...queryKeys.organizations.all, 'list'] as const,
    list: (filters: Record<string, any>) => [...queryKeys.organizations.lists(), filters] as const,
    details: () => [...queryKeys.organizations.all, 'detail'] as const,
    detail: (id: string | number) => [...queryKeys.organizations.details(), id] as const,
  },
  
  // Events
  events: {
    all: ['events'] as const,
    lists: () => [...queryKeys.events.all, 'list'] as const,
    list: (filters: Record<string, any>) => [...queryKeys.events.lists(), filters] as const,
    details: () => [...queryKeys.events.all, 'detail'] as const,
    detail: (id: string | number) => [...queryKeys.events.details(), id] as const,
  },
  
  // Resources
  resources: {
    all: ['resources'] as const,
    lists: () => [...queryKeys.resources.all, 'list'] as const,
    list: (filters: Record<string, any>) => [...queryKeys.resources.lists(), filters] as const,
    details: () => [...queryKeys.resources.all, 'detail'] as const,
    detail: (id: string | number) => [...queryKeys.resources.details(), id] as const,
  },
  
  // Activity logs
  activityLogs: {
    all: ['activityLogs'] as const,
    lists: () => [...queryKeys.activityLogs.all, 'list'] as const,
    list: (filters: Record<string, any>) => [...queryKeys.activityLogs.lists(), filters] as const,
  },
  
  // News
  news: {
    all: ['news'] as const,
    lists: () => [...queryKeys.news.all, 'list'] as const,
    list: (filters: Record<string, any>) => [...queryKeys.news.lists(), filters] as const,
    details: () => [...queryKeys.news.all, 'detail'] as const,
    detail: (id: string | number) => [...queryKeys.news.details(), id] as const,
    categories: ['news', 'categories'] as const,
  },
} as const;

// Prefetch strategies
export const prefetchStrategies = {
  // Prefetch common data on app load
  prefetchCommonData: async () => {
    await Promise.allSettled([
      queryClient.prefetchQuery(queryKeys.auth.profile, () => import('../services/auth').then(m => m.getCurrentUser())),
      queryClient.prefetchQuery(queryKeys.blogPosts.categories, () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/blog/categories/'))),
      queryClient.prefetchQuery(queryKeys.news.categories, () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/news/categories/'))),
    ]);
  },
  
  // Prefetch dashboard data
  prefetchDashboardData: async () => {
    const filters = { page: 1, limit: 10 };
    await Promise.allSettled([
      queryClient.prefetchQuery(queryKeys.blogPosts.list(filters), () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/blog/posts/', { params: filters }))),
      queryClient.prefetchQuery(queryKeys.users.list(filters), () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/admin/users/', { params: filters }))),
      queryClient.prefetchQuery(queryKeys.activityLogs.list(filters), () => import('../services/api/client').then(m => m.apiClient.get('/api/v1/admin/activity-logs/', { params: filters }))),
    ]);
  },
};

// Background sync configuration
export const backgroundSync = {
  // Sync critical data in the background
  syncCriticalData: () => {
    queryClient.invalidateQueries(queryKeys.auth.profile);
    queryClient.invalidateQueries(queryKeys.activityLogs.all);
  },
  
  // Setup periodic background sync
  setupPeriodicSync: () => {
    // Sync every 5 minutes when the app is active
    const interval = setInterval(() => {
      if (document.visibilityState === 'visible') {
        backgroundSync.syncCriticalData();
      }
    }, 5 * 60 * 1000);
    
    return () => clearInterval(interval);
  },
};