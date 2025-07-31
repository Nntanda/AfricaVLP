/**
 * Search API service for the wellknown frontend.
 */
import { apiClient } from './client';
import { 
  SearchFilters, 
  SearchResults, 
  SearchSuggestion, 
  UniversalSearchResults,
  FilterMetadata 
} from '../../types/search';

export interface PaginatedSearchResults<T> {
  results: T[];
  pagination: {
    page: number;
    page_size: number;
    total_pages: number;
    total_count: number;
    has_next: boolean;
    has_previous: boolean;
  };
  query: string;
  filters: SearchFilters;
}

class SearchService {
  /**
   * Search blog posts with filters and pagination
   */
  async searchBlogPosts(
    query: string = '',
    filters: SearchFilters = {},
    page: number = 1,
    pageSize: number = 20
  ): Promise<PaginatedSearchResults<any>> {
    const params = new URLSearchParams({
      q: query,
      page: page.toString(),
      page_size: pageSize.toString(),
    });

    // Add filters to params
    if (filters.dateFrom) params.append('date_from', filters.dateFrom);
    if (filters.dateTo) params.append('date_to', filters.dateTo);
    if (filters.categories) {
      filters.categories.forEach(cat => params.append('categories', cat));
    }
    if (filters.tags) {
      filters.tags.forEach(tag => params.append('tags', tag));
    }

    const response = await apiClient.get(`/search/blog-posts/?${params}`);
    return response.data;
  }

  /**
   * Search news articles with filters and pagination
   */
  async searchNews(
    query: string = '',
    filters: SearchFilters = {},
    page: number = 1,
    pageSize: number = 20
  ): Promise<PaginatedSearchResults<any>> {
    const params = new URLSearchParams({
      q: query,
      page: page.toString(),
      page_size: pageSize.toString(),
    });

    // Add filters to params
    if (filters.dateFrom) params.append('date_from', filters.dateFrom);
    if (filters.dateTo) params.append('date_to', filters.dateTo);
    if (filters.categories) {
      filters.categories.forEach(cat => params.append('categories', cat));
    }
    if (filters.tags) {
      filters.tags.forEach(tag => params.append('tags', tag));
    }

    const response = await apiClient.get(`/search/news/?${params}`);
    return response.data;
  }

  /**
   * Search events with location-based filters
   */
  async searchEvents(
    query: string = '',
    filters: SearchFilters = {},
    page: number = 1,
    pageSize: number = 20
  ): Promise<PaginatedSearchResults<any>> {
    const params = new URLSearchParams({
      q: query,
      page: page.toString(),
      page_size: pageSize.toString(),
    });

    // Add filters to params
    if (filters.dateFrom) params.append('date_from', filters.dateFrom);
    if (filters.dateTo) params.append('date_to', filters.dateTo);
    if (filters.country) params.append('country', filters.country);
    if (filters.city) params.append('city', filters.city);
    if (filters.categories) {
      filters.categories.forEach(cat => params.append('categories', cat));
    }

    const response = await apiClient.get(`/search/events/?${params}`);
    return response.data;
  }

  /**
   * Search organizations with location-based filters
   */
  async searchOrganizations(
    query: string = '',
    filters: SearchFilters = {},
    page: number = 1,
    pageSize: number = 20
  ): Promise<PaginatedSearchResults<any>> {
    const params = new URLSearchParams({
      q: query,
      page: page.toString(),
      page_size: pageSize.toString(),
    });

    // Add filters to params
    if (filters.country) params.append('country', filters.country);
    if (filters.city) params.append('city', filters.city);
    if (filters.categories) {
      filters.categories.forEach(cat => params.append('categories', cat));
    }

    const response = await apiClient.get(`/search/organizations/?${params}`);
    return response.data;
  }

  /**
   * Universal search across all content types
   */
  async universalSearch(
    query: string,
    contentTypes: string[] = ['blog_posts', 'news', 'events', 'organizations'],
    limit: number = 5
  ): Promise<UniversalSearchResults> {
    const params = new URLSearchParams({
      q: query,
      limit: limit.toString(),
    });

    contentTypes.forEach(type => params.append('types', type));

    const response = await apiClient.get(`/search/universal/?${params}`);
    return response.data;
  }

  /**
   * Get search suggestions
   */
  async getSearchSuggestions(
    query: string,
    contentType: string = 'blog_posts',
    limit: number = 5
  ): Promise<SearchSuggestion[]> {
    const params = new URLSearchParams({
      q: query,
      type: contentType,
      limit: limit.toString(),
    });

    const response = await apiClient.get(`/search/suggestions/?${params}`);
    return response.data.suggestions;
  }

  /**
   * Get filter metadata (categories, tags, countries, cities)
   */
  async getFilterMetadata(): Promise<FilterMetadata> {
    const response = await apiClient.get('/search/filters-metadata/');
    return response.data;
  }
}

export const searchService = new SearchService();