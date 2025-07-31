import { useQueryClient } from 'react-query';
import { queryKeys, prefetchStrategies } from '../config/queryClient';
import { apiClient } from '../services/api/client';

// Prefetch hook for admin dashboard
export const useDashboardPrefetch = () => {
  const queryClient = useQueryClient();

  const prefetchDashboardData = async () => {
    await prefetchStrategies.prefetchDashboardData();
  };

  const prefetchOnHover = (type: 'blog' | 'users' | 'organizations' | 'events' | 'resources') => {
    const filters = { page: 1, limit: 10 };
    
    switch (type) {
      case 'blog':
        queryClient.prefetchQuery(
          queryKeys.blogPosts.list(filters),
          () => apiClient.get('/api/v1/blog/posts/', { params: filters })
        );
        break;
      case 'users':
        queryClient.prefetchQuery(
          queryKeys.users.list(filters),
          () => apiClient.get('/api/v1/admin/users/', { params: filters })
        );
        break;
      case 'organizations':
        queryClient.prefetchQuery(
          queryKeys.organizations.list(filters),
          () => apiClient.get('/api/v1/organizations/', { params: filters })
        );
        break;
      case 'events':
        queryClient.prefetchQuery(
          queryKeys.events.list(filters),
          () => apiClient.get('/api/v1/events/', { params: filters })
        );
        break;
      case 'resources':
        queryClient.prefetchQuery(
          queryKeys.resources.list(filters),
          () => apiClient.get('/api/v1/resources/', { params: filters })
        );
        break;
    }
  };

  const prefetchDetailOnHover = (type: string, id: string | number) => {
    switch (type) {
      case 'blog':
        queryClient.prefetchQuery(
          queryKeys.blogPosts.detail(id),
          () => apiClient.get(`/api/v1/blog/posts/${id}/`)
        );
        break;
      case 'user':
        queryClient.prefetchQuery(
          queryKeys.users.detail(id),
          () => apiClient.get(`/api/v1/admin/users/${id}/`)
        );
        break;
      case 'organization':
        queryClient.prefetchQuery(
          queryKeys.organizations.detail(id),
          () => apiClient.get(`/api/v1/organizations/${id}/`)
        );
        break;
      case 'event':
        queryClient.prefetchQuery(
          queryKeys.events.detail(id),
          () => apiClient.get(`/api/v1/events/${id}/`)
        );
        break;
      case 'resource':
        queryClient.prefetchQuery(
          queryKeys.resources.detail(id),
          () => apiClient.get(`/api/v1/resources/${id}/`)
        );
        break;
    }
  };

  return {
    prefetchDashboardData,
    prefetchOnHover,
    prefetchDetailOnHover,
  };
};

// Prefetch hook for form data
export const useFormDataPrefetch = () => {
  const queryClient = useQueryClient();

  const prefetchFormOptions = async () => {
    await Promise.allSettled([
      queryClient.prefetchQuery(
        queryKeys.blogPosts.categories,
        () => apiClient.get('/api/v1/blog/categories/')
      ),
      queryClient.prefetchQuery(
        queryKeys.news.categories,
        () => apiClient.get('/api/v1/news/categories/')
      ),
      queryClient.prefetchQuery(
        queryKeys.blogPosts.tags,
        () => apiClient.get('/api/v1/blog/tags/')
      ),
    ]);
  };

  const prefetchUserFormData = async () => {
    await Promise.allSettled([
      queryClient.prefetchQuery(
        queryKeys.organizations.all,
        () => apiClient.get('/api/v1/organizations/?all=true')
      ),
    ]);
  };

  const prefetchOrganizationFormData = async () => {
    await Promise.allSettled([
      queryClient.prefetchQuery(
        ['countries'],
        () => apiClient.get('/api/v1/countries/')
      ),
      queryClient.prefetchQuery(
        ['categories'],
        () => apiClient.get('/api/v1/categories/')
      ),
    ]);
  };

  return {
    prefetchFormOptions,
    prefetchUserFormData,
    prefetchOrganizationFormData,
  };
};

// Prefetch hook for search and filtering
export const useSearchPrefetch = () => {
  const queryClient = useQueryClient();

  const prefetchSearchResults = (query: string, type?: string) => {
    const filters = { search: query, limit: 20 };
    
    if (type) {
      switch (type) {
        case 'blog':
          queryClient.prefetchQuery(
            queryKeys.blogPosts.list(filters),
            () => apiClient.get('/api/v1/blog/posts/', { params: filters })
          );
          break;
        case 'users':
          queryClient.prefetchQuery(
            queryKeys.users.list(filters),
            () => apiClient.get('/api/v1/admin/users/', { params: filters })
          );
          break;
        case 'organizations':
          queryClient.prefetchQuery(
            queryKeys.organizations.list(filters),
            () => apiClient.get('/api/v1/organizations/', { params: filters })
          );
          break;
      }
    } else {
      // Prefetch all types
      Promise.allSettled([
        queryClient.prefetchQuery(
          queryKeys.blogPosts.list(filters),
          () => apiClient.get('/api/v1/blog/posts/', { params: filters })
        ),
        queryClient.prefetchQuery(
          queryKeys.users.list(filters),
          () => apiClient.get('/api/v1/admin/users/', { params: filters })
        ),
        queryClient.prefetchQuery(
          queryKeys.organizations.list(filters),
          () => apiClient.get('/api/v1/organizations/', { params: filters })
        ),
      ]);
    }
  };

  const prefetchFilteredResults = (filters: Record<string, any>, type: string) => {
    switch (type) {
      case 'blog':
        queryClient.prefetchQuery(
          queryKeys.blogPosts.list(filters),
          () => apiClient.get('/api/v1/blog/posts/', { params: filters })
        );
        break;
      case 'users':
        queryClient.prefetchQuery(
          queryKeys.users.list(filters),
          () => apiClient.get('/api/v1/admin/users/', { params: filters })
        );
        break;
      case 'organizations':
        queryClient.prefetchQuery(
          queryKeys.organizations.list(filters),
          () => apiClient.get('/api/v1/organizations/', { params: filters })
        );
        break;
      case 'events':
        queryClient.prefetchQuery(
          queryKeys.events.list(filters),
          () => apiClient.get('/api/v1/events/', { params: filters })
        );
        break;
      case 'resources':
        queryClient.prefetchQuery(
          queryKeys.resources.list(filters),
          () => apiClient.get('/api/v1/resources/', { params: filters })
        );
        break;
    }
  };

  return {
    prefetchSearchResults,
    prefetchFilteredResults,
  };
};

// Prefetch hook for pagination
export const usePaginationPrefetch = () => {
  const queryClient = useQueryClient();

  const prefetchNextPage = (currentFilters: Record<string, any>, type: string) => {
    const nextPageFilters = {
      ...currentFilters,
      page: (currentFilters.page || 1) + 1,
    };

    switch (type) {
      case 'blog':
        queryClient.prefetchQuery(
          queryKeys.blogPosts.list(nextPageFilters),
          () => apiClient.get('/api/v1/blog/posts/', { params: nextPageFilters })
        );
        break;
      case 'users':
        queryClient.prefetchQuery(
          queryKeys.users.list(nextPageFilters),
          () => apiClient.get('/api/v1/admin/users/', { params: nextPageFilters })
        );
        break;
      case 'organizations':
        queryClient.prefetchQuery(
          queryKeys.organizations.list(nextPageFilters),
          () => apiClient.get('/api/v1/organizations/', { params: nextPageFilters })
        );
        break;
      case 'events':
        queryClient.prefetchQuery(
          queryKeys.events.list(nextPageFilters),
          () => apiClient.get('/api/v1/events/', { params: nextPageFilters })
        );
        break;
      case 'resources':
        queryClient.prefetchQuery(
          queryKeys.resources.list(nextPageFilters),
          () => apiClient.get('/api/v1/resources/', { params: nextPageFilters })
        );
        break;
      case 'activityLogs':
        queryClient.prefetchQuery(
          queryKeys.activityLogs.list(nextPageFilters),
          () => apiClient.get('/api/v1/admin/activity-logs/', { params: nextPageFilters })
        );
        break;
    }
  };

  const prefetchPreviousPage = (currentFilters: Record<string, any>, type: string) => {
    const prevPage = (currentFilters.page || 1) - 1;
    if (prevPage < 1) return;

    const prevPageFilters = {
      ...currentFilters,
      page: prevPage,
    };

    switch (type) {
      case 'blog':
        queryClient.prefetchQuery(
          queryKeys.blogPosts.list(prevPageFilters),
          () => apiClient.get('/api/v1/blog/posts/', { params: prevPageFilters })
        );
        break;
      case 'users':
        queryClient.prefetchQuery(
          queryKeys.users.list(prevPageFilters),
          () => apiClient.get('/api/v1/admin/users/', { params: prevPageFilters })
        );
        break;
      case 'organizations':
        queryClient.prefetchQuery(
          queryKeys.organizations.list(prevPageFilters),
          () => apiClient.get('/api/v1/organizations/', { params: prevPageFilters })
        );
        break;
    }
  };

  return {
    prefetchNextPage,
    prefetchPreviousPage,
  };
};