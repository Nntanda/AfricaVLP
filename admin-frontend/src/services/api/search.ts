import { apiClient } from './client';
import {
  SearchParams,
  SearchResponse,
  GlobalSearchResult,
  SearchSuggestion,
  SearchHistoryItem,
  PopularSearch,
  SearchFilterOptions,
  SearchContentType
} from '../../types/search';

export class SearchAPI {
  /**
   * Search blog posts with advanced filtering
   */
  static async searchBlogPosts(params: SearchParams): Promise<SearchResponse> {
    const queryParams = new URLSearchParams();
    
    if (params.query) queryParams.append('q', params.query);
    if (params.categories?.length) queryParams.append('categories', params.categories.join(','));
    if (params.tags?.length) queryParams.append('tags', params.tags.join(','));
    if (params.organizationId) queryParams.append('organization_id', params.organizationId.toString());
    if (params.dateFrom) queryParams.append('date_from', params.dateFrom);
    if (params.dateTo) queryParams.append('date_to', params.dateTo);
    if (params.status) queryParams.append('status', params.status);
    if (params.language) queryParams.append('language', params.language);
    if (params.page) queryParams.append('page', params.page.toString());
    if (params.pageSize) queryParams.append('page_size', params.pageSize.toString());

    const response = await apiClient.get(`/search/blog-posts/?${queryParams.toString()}`);
    return response.data;
  }

  /**
   * Search news articles with advanced filtering
   */
  static async searchNews(params: SearchParams): Promise<SearchResponse> {
    const queryParams = new URLSearchParams();
    
    if (params.query) queryParams.append('q', params.query);
    if (params.categories?.length) queryParams.append('categories', params.categories.join(','));
    if (params.tags?.length) queryParams.append('tags', params.tags.join(','));
    if (params.organizationId) queryParams.append('organization_id', params.organizationId.toString());
    if (params.dateFrom) queryParams.append('date_from', params.dateFrom);
    if (params.dateTo) queryParams.append('date_to', params.dateTo);
    if (params.status) queryParams.append('status', params.status);
    if (params.page) queryParams.append('page', params.page.toString());
    if (params.pageSize) queryParams.append('page_size', params.pageSize.toString());

    const response = await apiClient.get(`/search/news/?${queryParams.toString()}`);
    return response.data;
  }

  /**
   * Search events with location-based filtering
   */
  static async searchEvents(params: SearchParams): Promise<SearchResponse> {
    const queryParams = new URLSearchParams();
    
    if (params.query) queryParams.append('q', params.query);
    if (params.organizationId) queryParams.append('organization_id', params.organizationId.toString());
    if (params.countryId) queryParams.append('country_id', params.countryId.toString());
    if (params.cityId) queryParams.append('city_id', params.cityId.toString());
    if (params.dateFrom) queryParams.append('date_from', params.dateFrom);
    if (params.dateTo) queryParams.append('date_to', params.dateTo);
    if (params.eventType) queryParams.append('event_type', params.eventType);
    if (params.status) queryParams.append('status', params.status);
    if (params.page) queryParams.append('page', params.page.toString());
    if (params.pageSize) queryParams.append('page_size', params.pageSize.toString());

    const response = await apiClient.get(`/search/events/?${queryParams.toString()}`);
    return response.data;
  }

  /**
   * Search organizations with location-based filtering
   */
  static async searchOrganizations(params: SearchParams): Promise<SearchResponse> {
    const queryParams = new URLSearchParams();
    
    if (params.query) queryParams.append('q', params.query);
    if (params.countryId) queryParams.append('country_id', params.countryId.toString());
    if (params.cityId) queryParams.append('city_id', params.cityId.toString());
    if (params.organizationType) queryParams.append('organization_type', params.organizationType);
    if (params.status) queryParams.append('status', params.status);
    if (params.page) queryParams.append('page', params.page.toString());
    if (params.pageSize) queryParams.append('page_size', params.pageSize.toString());

    const response = await apiClient.get(`/search/organizations/?${queryParams.toString()}`);
    return response.data;
  }

  /**
   * Search resources with category filtering
   */
  static async searchResources(params: SearchParams): Promise<SearchResponse> {
    const queryParams = new URLSearchParams();
    
    if (params.query) queryParams.append('q', params.query);
    if (params.categories?.length) queryParams.append('categories', params.categories.join(','));
    if (params.organizationId) queryParams.append('organization_id', params.organizationId.toString());
    if (params.resourceType) queryParams.append('resource_type', params.resourceType);
    if (params.language) queryParams.append('language', params.language);
    if (params.page) queryParams.append('page', params.page.toString());
    if (params.pageSize) queryParams.append('page_size', params.pageSize.toString());

    const response = await apiClient.get(`/search/resources/?${queryParams.toString()}`);
    return response.data;
  }

  /**
   * Global search across all content types
   */
  static async globalSearch(
    query: string,
    contentTypes: string = 'all',
    limit: number = 5
  ): Promise<GlobalSearchResult> {
    const queryParams = new URLSearchParams();
    queryParams.append('q', query);
    queryParams.append('types', contentTypes);
    queryParams.append('limit', limit.toString());

    const response = await apiClient.get(`/search/global/?${queryParams.toString()}`);
    return response.data;
  }

  /**
   * Get search suggestions based on query
   */
  static async getSuggestions(query: string, limit: number = 10): Promise<SearchSuggestion> {
    const queryParams = new URLSearchParams();
    queryParams.append('q', query);
    queryParams.append('limit', limit.toString());

    const response = await apiClient.get(`/search/suggestions/?${queryParams.toString()}`);
    return response.data.suggestions;
  }

  /**
   * Get user's search history
   */
  static async getSearchHistory(limit: number = 20): Promise<SearchHistoryItem[]> {
    const queryParams = new URLSearchParams();
    queryParams.append('limit', limit.toString());

    const response = await apiClient.get(`/search/history/?${queryParams.toString()}`);
    return response.data.history;
  }

  /**
   * Get popular search queries
   */
  static async getPopularSearches(limit: number = 10): Promise<PopularSearch[]> {
    const queryParams = new URLSearchParams();
    queryParams.append('limit', limit.toString());

    const response = await apiClient.get(`/search/popular/?${queryParams.toString()}`);
    return response.data.popular_searches;
  }

  /**
   * Get available filter options
   */
  static async getFilterOptions(): Promise<SearchFilterOptions> {
    const response = await apiClient.get('/search/filters/');
    return response.data.filters;
  }

  /**
   * Search specific content type with unified interface
   */
  static async searchContentType(
    contentType: SearchContentType,
    params: SearchParams
  ): Promise<SearchResponse> {
    switch (contentType) {
      case 'blog_posts':
        return this.searchBlogPosts(params);
      case 'news':
        return this.searchNews(params);
      case 'events':
        return this.searchEvents(params);
      case 'organizations':
        return this.searchOrganizations(params);
      case 'resources':
        return this.searchResources(params);
      default:
        throw new Error(`Unsupported content type: ${contentType}`);
    }
  }
}