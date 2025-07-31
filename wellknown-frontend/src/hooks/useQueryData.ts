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
      staleTime: 5 * 60 * 1000, // 5 minutes
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

export const useFeaturedBlogPosts = () => {
  return useQuery(
    queryKeys.blogPosts.featured,
    async () => {
      const response = await apiClient.get('/api/v1/blog/posts/?featured=true&limit=6');
      return response.data;
    },
    {
      staleTime: 10 * 60 * 1000, // 10 minutes
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
      staleTime: 5 * 60 * 1000, // 5 minutes
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

export const useFeaturedNews = () => {
  return useQuery(
    queryKeys.news.featured,
    async () => {
      const response = await apiClient.get('/api/v1/news/?featured=true&limit=6');
      return response.data;
    },
    {
      staleTime: 10 * 60 * 1000, // 10 minutes
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

export const useUpcomingEvents = () => {
  return useQuery(
    queryKeys.events.upcoming,
    async () => {
      const response = await apiClient.get('/api/v1/events/?upcoming=true&limit=10');
      return response.data;
    },
    {
      staleTime: 5 * 60 * 1000, // 5 minutes
    }
  );
};

export const useEventsByLocation = (location: string) => {
  return useQuery(
    queryKeys.events.byLocation(location),
    async () => {
      const response = await apiClient.get(`/api/v1/events/?location=${location}`);
      return response.data;
    },
    {
      enabled: !!location,
      staleTime: 10 * 60 * 1000, // 10 minutes
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
      staleTime: 15 * 60 * 1000, // 15 minutes
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

export const useOrganizationDirectory = () => {
  return useQuery(
    queryKeys.organizations.directory,
    async () => {
      const response = await apiClient.get('/api/v1/organizations/?directory=true');
      return response.data;
    },
    {
      staleTime: 30 * 60 * 1000, // 30 minutes
    }
  );
};

export const useOrganizationsByCategory = (category: string) => {
  return useQuery(
    queryKeys.organizations.byCategory(category),
    async () => {
      const response = await apiClient.get(`/api/v1/organizations/?category=${category}`);
      return response.data;
    },
    {
      enabled: !!category,
      staleTime: 15 * 60 * 1000, // 15 minutes
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
      staleTime: 15 * 60 * 1000, // 15 minutes
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

export const useResourcesByCategory = (category: string) => {
  return useQuery(
    queryKeys.resources.byCategory(category),
    async () => {
      const response = await apiClient.get(`/api/v1/resources/?category=${category}`);
      return response.data;
    },
    {
      enabled: !!category,
      staleTime: 15 * 60 * 1000, // 15 minutes
    }
  );
};

// User Profile hooks
export const useCurrentUserProfile = () => {
  return useQuery(
    queryKeys.profile.current,
    async () => {
      const response = await apiClient.get('/api/v1/profile/');
      return response.data;
    },
    {
      staleTime: 5 * 60 * 1000, // 5 minutes
    }
  );
};

export const useUserOrganizations = () => {
  return useQuery(
    queryKeys.profile.organizations,
    async () => {
      const response = await apiClient.get('/api/v1/profile/organizations/');
      return response.data;
    },
    {
      staleTime: 10 * 60 * 1000, // 10 minutes
    }
  );
};

export const useUserActivities = (filters: ListFilters = {}) => {
  return useQuery(
    queryKeys.profile.activities,
    async () => {
      const response = await apiClient.get('/api/v1/profile/activities/', {
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

export const useProfileMutation = () => {
  const queryClient = useQueryClient();
  
  return useMutation(
    async (data: any) => {
      const response = await apiClient.put('/api/v1/profile/', data);
      return response.data;
    },
    {
      onSuccess: (data) => {
        queryClient.setQueryData(queryKeys.profile.current, data);
        queryClient.invalidateQueries(queryKeys.auth.profile);
        toast.success('Profile updated successfully');
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

export const useResourceCategories = () => {
  return useQuery(
    queryKeys.resources.categories,
    async () => {
      const response = await apiClient.get('/api/v1/resources/categories/');
      return response.data;
    },
    {
      staleTime: 30 * 60 * 1000, // 30 minutes
    }
  );
};

// Contact form mutation
export const useContactFormMutation = () => {
  return useMutation(
    async (data: any) => {
      const response = await apiClient.post('/api/v1/contact/', data);
      return response.data;
    },
    {
      onSuccess: () => {
        toast.success('Message sent successfully! We will get back to you soon.');
      },
    }
  );
};

// Newsletter subscription mutation
export const useNewsletterMutation = () => {
  return useMutation(
    async (data: { email: string }) => {
      const response = await apiClient.post('/api/v1/newsletter/subscribe/', data);
      return response.data;
    },
    {
      onSuccess: () => {
        toast.success('Successfully subscribed to newsletter!');
      },
    }
  );
};