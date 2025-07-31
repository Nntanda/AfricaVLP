import { useQueryClient } from 'react-query';
import { queryKeys, prefetchStrategies } from '../config/queryClient';
import { apiClient } from '../services/api/client';

// Prefetch hook for home page
export const useHomePrefetch = () => {
  const queryClient = useQueryClient();

  const prefetchHomeData = async () => {
    await prefetchStrategies.prefetchHomeData();
  };

  const prefetchOnHover = (type: 'blog' | 'news' | 'events' | 'organizations' | 'resources') => {
    const filters = { page: 1, limit: 6 };
    
    switch (type) {
      case 'blog':
        queryClient.prefetchQuery(
          queryKeys.blogPosts.list(filters),
          () => apiClient.get('/api/v1/blog/posts/', { params: filters })
        );
        break;
      case 'news':
        queryClient.prefetchQuery(
          queryKeys.news.list(filters),
          () => apiClient.get('/api/v1/news/', { params: filters })
        );
        break;
      case 'events':
        queryClient.prefetchQuery(
          queryKeys.events.list(filters),
          () => apiClient.get('/api/v1/events/', { params: filters })
        );
        break;
      case 'organizations':
        queryClient.prefetchQuery(
          queryKeys.organizations.list(filters),
          () => apiClient.get('/api/v1/organizations/', { params: filters })
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
      case 'news':
        queryClient.prefetchQuery(
          queryKeys.news.detail(id),
          () => apiClient.get(`/api/v1/news/${id}/`)
        );
        break;
      case 'event':
        queryClient.prefetchQuery(
          queryKeys.events.detail(id),
          () => apiClient.get(`/api/v1/events/${id}/`)
        );
        break;
      case 'organization':
        queryClient.prefetchQuery(
          queryKeys.organizations.detail(id),
          () => apiClient.get(`/api/v1/organizations/${id}/`)
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
    prefetchHomeData,
    prefetchOnHover,
    prefetchDetailOnHover,
  };
};

// Prefetch hook for content browsing
export const useContentPrefetch = () => {
  const queryClient = useQueryClient();

  const prefetchRelatedContent = async (type: string, id: string | number) => {
    switch (type) {
      case 'blog':
        // Prefetch related blog posts
        queryClient.prefetchQuery(
          ['blogPosts', 'related', id],
          () => apiClient.get(`/api/v1/blog/posts/${id}/related/`)
        );
        break;
      case 'news':
        // Prefetch related news
        queryClient.prefetchQuery(
          ['news', 'related', id],
          () => apiClient.get(`/api/v1/news/${id}/related/`)
        );
        break;
      case 'event':
        // Prefetch similar events
        queryClient.prefetchQuery(
          ['events', 'similar', id],
          () => apiClient.get(`/api/v1/events/${id}/similar/`)
        );
        break;
    }
  };

  const prefetchCategoryContent = (category: string, type: string) => {
    const filters = { category, limit: 10 };
    
    switch (type) {
      case 'blog':
        queryClient.prefetchQuery(
          queryKeys.blogPosts.list(filters),
          () => apiClient.get('/api/v1/blog/posts/', { params: filters })
        );
        break;
      case 'news':
        queryClient.prefetchQuery(
          queryKeys.news.list(filters),
          () => apiClient.get('/api/v1/news/', { params: filters })
        );
        break;
      case 'resources':
        queryClient.prefetchQuery(
          queryKeys.resources.byCategory(category),
          () => apiClient.get(`/api/v1/resources/?category=${category}`)
        );
        break;
      case 'organizations':
        queryClient.prefetchQuery(
          queryKeys.organizations.byCategory(category),
          () => apiClient.get(`/api/v1/organizations/?category=${category}`)
        );
        break;
    }
  };

  const prefetchLocationContent = (location: string) => {
    queryClient.prefetchQuery(
      queryKeys.events.byLocation(location),
      () => apiClient.get(`/api/v1/events/?location=${location}`)
    );
    
    queryClient.prefetchQuery(
      ['organizations', 'location', location],
      () => apiClient.get(`/api/v1/organizations/?location=${location}`)
    );
  };

  return {
    prefetchRelatedContent,
    prefetchCategoryContent,
    prefetchLocationContent,
  };
};

// Prefetch hook for search functionality
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
        case 'news':
          queryClient.prefetchQuery(
            queryKeys.news.list(filters),
            () => apiClient.get('/api/v1/news/', { params: filters })
          );
          break;
        case 'events':
          queryClient.prefetchQuery(
            queryKeys.events.list(filters),
            () => apiClient.get('/api/v1/events/', { params: filters })
          );
          break;
        case 'organizations':
          queryClient.prefetchQuery(
            queryKeys.organizations.list(filters),
            () => apiClient.get('/api/v1/organizations/', { params: filters })
          );
          break;
        case 'resources':
          queryClient.prefetchQuery(
            queryKeys.resources.list(filters),
            () => apiClient.get('/api/v1/resources/', { params: filters })
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
          queryKeys.news.list(filters),
          () => apiClient.get('/api/v1/news/', { params: filters })
        ),
        queryClient.prefetchQuery(
          queryKeys.events.list(filters),
          () => apiClient.get('/api/v1/events/', { params: filters })
        ),
        queryClient.prefetchQuery(
          queryKeys.organizations.list(filters),
          () => apiClient.get('/api/v1/organizations/', { params: filters })
        ),
        queryClient.prefetchQuery(
          queryKeys.resources.list(filters),
          () => apiClient.get('/api/v1/resources/', { params: filters })
        ),
      ]);
    }
  };

  const prefetchPopularSearches = () => {
    queryClient.prefetchQuery(
      ['search', 'popular'],
      () => apiClient.get('/api/v1/search/popular/')
    );
  };

  const prefetchSearchSuggestions = (query: string) => {
    if (query.length >= 2) {
      queryClient.prefetchQuery(
        ['search', 'suggestions', query],
        () => apiClient.get(`/api/v1/search/suggestions/?q=${query}`)
      );
    }
  };

  return {
    prefetchSearchResults,
    prefetchPopularSearches,
    prefetchSearchSuggestions,
  };
};

// Prefetch hook for user profile
export const useProfilePrefetch = () => {
  const queryClient = useQueryClient();

  const prefetchProfileData = async () => {
    await Promise.allSettled([
      queryClient.prefetchQuery(
        queryKeys.profile.current,
        () => apiClient.get('/api/v1/profile/')
      ),
      queryClient.prefetchQuery(
        queryKeys.profile.organizations,
        () => apiClient.get('/api/v1/profile/organizations/')
      ),
      queryClient.prefetchQuery(
        queryKeys.profile.activities,
        () => apiClient.get('/api/v1/profile/activities/')
      ),
    ]);
  };

  const prefetchUserContent = (userId: string | number) => {
    const filters = { user: userId, limit: 10 };
    
    Promise.allSettled([
      queryClient.prefetchQuery(
        ['user', userId, 'blog-posts'],
        () => apiClient.get('/api/v1/blog/posts/', { params: filters })
      ),
      queryClient.prefetchQuery(
        ['user', userId, 'events'],
        () => apiClient.get('/api/v1/events/', { params: filters })
      ),
      queryClient.prefetchQuery(
        ['user', userId, 'resources'],
        () => apiClient.get('/api/v1/resources/', { params: filters })
      ),
    ]);
  };

  return {
    prefetchProfileData,
    prefetchUserContent,
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
      case 'news':
        queryClient.prefetchQuery(
          queryKeys.news.list(nextPageFilters),
          () => apiClient.get('/api/v1/news/', { params: nextPageFilters })
        );
        break;
      case 'events':
        queryClient.prefetchQuery(
          queryKeys.events.list(nextPageFilters),
          () => apiClient.get('/api/v1/events/', { params: nextPageFilters })
        );
        break;
      case 'organizations':
        queryClient.prefetchQuery(
          queryKeys.organizations.list(nextPageFilters),
          () => apiClient.get('/api/v1/organizations/', { params: nextPageFilters })
        );
        break;
      case 'resources':
        queryClient.prefetchQuery(
          queryKeys.resources.list(nextPageFilters),
          () => apiClient.get('/api/v1/resources/', { params: nextPageFilters })
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
      case 'news':
        queryClient.prefetchQuery(
          queryKeys.news.list(prevPageFilters),
          () => apiClient.get('/api/v1/news/', { params: prevPageFilters })
        );
        break;
      case 'events':
        queryClient.prefetchQuery(
          queryKeys.events.list(prevPageFilters),
          () => apiClient.get('/api/v1/events/', { params: prevPageFilters })
        );
        break;
      case 'organizations':
        queryClient.prefetchQuery(
          queryKeys.organizations.list(prevPageFilters),
          () => apiClient.get('/api/v1/organizations/', { params: prevPageFilters })
        );
        break;
      case 'resources':
        queryClient.prefetchQuery(
          queryKeys.resources.list(prevPageFilters),
          () => apiClient.get('/api/v1/resources/', { params: prevPageFilters })
        );
        break;
    }
  };

  return {
    prefetchNextPage,
    prefetchPreviousPage,
  };
};