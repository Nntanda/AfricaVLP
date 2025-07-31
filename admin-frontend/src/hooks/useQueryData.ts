import { useQuery, useMutation, useQueryClient, UseQueryOptions, UseMutationOptions } from 'react-query';
import { apiClient } from '../services/api/client';
import { queryKeys } from '../config/queryClient';
import { toast } from './useToast';

// Generic types
interface PaginatedResponse<T> {
  results: T[];
  count: number;
  next: string | null;
  previous: string | null;
}

interface ListFilters {
  page?: number;
  limit?: number;
  search?: string;
  ordering?: string;
  [key: string]: any;
}

// Blog Posts hooks
export const useBlogPosts = (filters: ListFilters = {}) => {
  return useQuery(
    queryKeys.blogPosts.list(filters),
    async () => {
      const response = await apiClient.get<PaginatedResponse<any>>('/api/v1/blog/posts/', {
        params: filters,
      });
      return response.data;
    },
    {
      keepPreviousData: true,
      staleTime: 2 * 60 * 1000, // 2 minutes
    }
  );
};

export const useBlogPost = (id: string | number) => {
  return useQuery(
    queryKeys.blogPosts.detail(id),
    async () => {
      const response = await apiClient.get(`/api/v1/blog/posts/${id}/`);
      return response.data;
    },
    {
      enabled: !!id,
    }
  );
};

export const useBlogPostMutation = () => {
  const queryClient = useQueryClient();
  
  return useMutation(
    async (data: { id?: string | number; [key: string]: any }) => {
      if (data.id) {
        const response = await apiClient.put(`/api/v1/blog/posts/${data.id}/`, data);
        return response.data;
      } else {
        const response = await apiClient.post('/api/v1/blog/posts/', data);
        return response.data;
      }
    },
    {
      onSuccess: (data, variables) => {
        // Invalidate and refetch blog posts list
        queryClient.invalidateQueries(queryKeys.blogPosts.lists());
        
        // Update the specific blog post cache
        if (variables.id) {
          queryClient.setQueryData(queryKeys.blogPosts.detail(variables.id), data);
        }
        
        toast.success(variables.id ? 'Blog post updated successfully' : 'Blog post created successfully');
      },
    }
  );
};

export const useBlogPostDelete = () => {
  const queryClient = useQueryClient();
  
  return useMutation(
    async (id: string | number) => {
      await apiClient.delete(`/api/v1/blog/posts/${id}/`);
    },
    {
      onSuccess: (_, id) => {
        // Remove from cache
        queryClient.removeQueries(queryKeys.blogPosts.detail(id));
        // Invalidate lists
        queryClient.invalidateQueries(queryKeys.blogPosts.lists());
        toast.success('Blog post deleted successfully');
      },
    }
  );
};

// Users hooks
export const useUsers = (filters: ListFilters = {}) => {
  return useQuery(
    queryKeys.users.list(filters),
    async () => {
      const response = await apiClient.get<PaginatedResponse<any>>('/api/v1/admin/users/', {
        params: filters,
      });
      return response.data;
    },
    {
      keepPreviousData: true,
      staleTime: 5 * 60 * 1000, // 5 minutes
    }
  );
};

export const useUser = (id: string | number) => {
  return useQuery(
    queryKeys.users.detail(id),
    async () => {
      const response = await apiClient.get(`/api/v1/admin/users/${id}/`);
      return response.data;
    },
    {
      enabled: !!id,
    }
  );
};

export const useUserMutation = () => {
  const queryClient = useQueryClient();
  
  return useMutation(
    async (data: { id?: string | number; [key: string]: any }) => {
      if (data.id) {
        const response = await apiClient.put(`/api/v1/admin/users/${data.id}/`, data);
        return response.data;
      } else {
        const response = await apiClient.post('/api/v1/admin/users/', data);
        return response.data;
      }
    },
    {
      onSuccess: (data, variables) => {
        queryClient.invalidateQueries(queryKeys.users.lists());
        if (variables.id) {
          queryClient.setQueryData(queryKeys.users.detail(variables.id), data);
        }
        toast.success(variables.id ? 'User updated successfully' : 'User created successfully');
      },
    }
  );
};

// Organizations hooks
export const useOrganizations = (filters: ListFilters = {}) => {
  return useQuery(
    queryKeys.organizations.list(filters),
    async () => {
      const response = await apiClient.get<PaginatedResponse<any>>('/api/v1/organizations/', {
        params: filters,
      });
      return response.data;
    },
    {
      keepPreviousData: true,
      staleTime: 10 * 60 * 1000, // 10 minutes
    }
  );
};

export const useOrganization = (id: string | number) => {
  return useQuery(
    queryKeys.organizations.detail(id),
    async () => {
      const response = await apiClient.get(`/api/v1/organizations/${id}/`);
      return response.data;
    },
    {
      enabled: !!id,
    }
  );
};

export const useOrganizationMutation = () => {
  const queryClient = useQueryClient();
  
  return useMutation(
    async (data: { id?: string | number; [key: string]: any }) => {
      if (data.id) {
        const response = await apiClient.put(`/api/v1/organizations/${data.id}/`, data);
        return response.data;
      } else {
        const response = await apiClient.post('/api/v1/organizations/', data);
        return response.data;
      }
    },
    {
      onSuccess: (data, variables) => {
        queryClient.invalidateQueries(queryKeys.organizations.lists());
        if (variables.id) {
          queryClient.setQueryData(queryKeys.organizations.detail(variables.id), data);
        }
        toast.success(variables.id ? 'Organization updated successfully' : 'Organization created successfully');
      },
    }
  );
};

// Events hooks
export const useEvents = (filters: ListFilters = {}) => {
  return useQuery(
    queryKeys.events.list(filters),
    async () => {
      const response = await apiClient.get<PaginatedResponse<any>>('/api/v1/events/', {
        params: filters,
      });
      return response.data;
    },
    {
      keepPreviousData: true,
      staleTime: 5 * 60 * 1000, // 5 minutes
    }
  );
};

export const useEvent = (id: string | number) => {
  return useQuery(
    queryKeys.events.detail(id),
    async () => {
      const response = await apiClient.get(`/api/v1/events/${id}/`);
      return response.data;
    },
    {
      enabled: !!id,
    }
  );
};

export const useEventMutation = () => {
  const queryClient = useQueryClient();
  
  return useMutation(
    async (data: { id?: string | number; [key: string]: any }) => {
      if (data.id) {
        const response = await apiClient.put(`/api/v1/events/${data.id}/`, data);
        return response.data;
      } else {
        const response = await apiClient.post('/api/v1/events/', data);
        return response.data;
      }
    },
    {
      onSuccess: (data, variables) => {
        queryClient.invalidateQueries(queryKeys.events.lists());
        if (variables.id) {
          queryClient.setQueryData(queryKeys.events.detail(variables.id), data);
        }
        toast.success(variables.id ? 'Event updated successfully' : 'Event created successfully');
      },
    }
  );
};

// Resources hooks
export const useResources = (filters: ListFilters = {}) => {
  return useQuery(
    queryKeys.resources.list(filters),
    async () => {
      const response = await apiClient.get<PaginatedResponse<any>>('/api/v1/resources/', {
        params: filters,
      });
      return response.data;
    },
    {
      keepPreviousData: true,
      staleTime: 10 * 60 * 1000, // 10 minutes
    }
  );
};

export const useResource = (id: string | number) => {
  return useQuery(
    queryKeys.resources.detail(id),
    async () => {
      const response = await apiClient.get(`/api/v1/resources/${id}/`);
      return response.data;
    },
    {
      enabled: !!id,
    }
  );
};

export const useResourceMutation = () => {
  const queryClient = useQueryClient();
  
  return useMutation(
    async (data: { id?: string | number; [key: string]: any }) => {
      if (data.id) {
        const response = await apiClient.put(`/api/v1/resources/${data.id}/`, data);
        return response.data;
      } else {
        const response = await apiClient.post('/api/v1/resources/', data);
        return response.data;
      }
    },
    {
      onSuccess: (data, variables) => {
        queryClient.invalidateQueries(queryKeys.resources.lists());
        if (variables.id) {
          queryClient.setQueryData(queryKeys.resources.detail(variables.id), data);
        }
        toast.success(variables.id ? 'Resource updated successfully' : 'Resource created successfully');
      },
    }
  );
};

// Activity Logs hooks
export const useActivityLogs = (filters: ListFilters = {}) => {
  return useQuery(
    queryKeys.activityLogs.list(filters),
    async () => {
      const response = await apiClient.get<PaginatedResponse<any>>('/api/v1/admin/activity-logs/', {
        params: filters,
      });
      return response.data;
    },
    {
      keepPreviousData: true,
      staleTime: 1 * 60 * 1000, // 1 minute
      refetchInterval: 30 * 1000, // Refetch every 30 seconds for real-time updates
    }
  );
};

// News hooks
export const useNews = (filters: ListFilters = {}) => {
  return useQuery(
    queryKeys.news.list(filters),
    async () => {
      const response = await apiClient.get<PaginatedResponse<any>>('/api/v1/news/', {
        params: filters,
      });
      return response.data;
    },
    {
      keepPreviousData: true,
      staleTime: 2 * 60 * 1000, // 2 minutes
    }
  );
};

export const useNewsItem = (id: string | number) => {
  return useQuery(
    queryKeys.news.detail(id),
    async () => {
      const response = await apiClient.get(`/api/v1/news/${id}/`);
      return response.data;
    },
    {
      enabled: !!id,
    }
  );
};

export const useNewsMutation = () => {
  const queryClient = useQueryClient();
  
  return useMutation(
    async (data: { id?: string | number; [key: string]: any }) => {
      if (data.id) {
        const response = await apiClient.put(`/api/v1/news/${data.id}/`, data);
        return response.data;
      } else {
        const response = await apiClient.post('/api/v1/news/', data);
        return response.data;
      }
    },
    {
      onSuccess: (data, variables) => {
        queryClient.invalidateQueries(queryKeys.news.lists());
        if (variables.id) {
          queryClient.setQueryData(queryKeys.news.detail(variables.id), data);
        }
        toast.success(variables.id ? 'News updated successfully' : 'News created successfully');
      },
    }
  );
};

// Categories hooks
export const useBlogCategories = () => {
  return useQuery(
    queryKeys.blogPosts.categories,
    async () => {
      const response = await apiClient.get('/api/v1/blog/categories/');
      return response.data;
    },
    {
      staleTime: 30 * 60 * 1000, // 30 minutes
    }
  );
};

export const useNewsCategories = () => {
  return useQuery(
    queryKeys.news.categories,
    async () => {
      const response = await apiClient.get('/api/v1/news/categories/');
      return response.data;
    },
    {
      staleTime: 30 * 60 * 1000, // 30 minutes
    }
  );
};