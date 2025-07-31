export interface SearchFilters {
  categories?: number[];
  tags?: number[];
  organizationId?: number;
  dateFrom?: string;
  dateTo?: string;
  status?: string;
  language?: string;
  countryId?: number;
  cityId?: number;
  eventType?: string;
  organizationType?: string;
  resourceType?: string;
}

export interface SearchParams extends SearchFilters {
  query: string;
  page?: number;
  pageSize?: number;
}

export interface SearchResult {
  id: number;
  title?: string;
  name?: string;
  description?: string;
  excerpt?: string;
  summary?: string;
  content?: string;
  image?: string;
  status?: number;
  createdAt?: string;
  modifiedAt?: string;
  organization?: {
    id: number;
    name: string;
  };
  categories?: Array<{
    id: number;
    name: string;
  }>;
  tags?: Array<{
    id: number;
    name: string;
  }>;
  country?: {
    id: number;
    name: string;
  };
  city?: {
    id: number;
    name: string;
  };
}

export interface SearchResponse {
  count: number;
  next: string | null;
  previous: string | null;
  results: SearchResult[];
}

export interface GlobalSearchResult {
  query: string;
  results: {
    blog_posts?: SearchResult[];
    news?: SearchResult[];
    events?: SearchResult[];
    organizations?: SearchResult[];
    resources?: SearchResult[];
  };
  total_results: number;
}

export interface SearchSuggestion {
  blog_posts: string[];
  news: string[];
  events: string[];
  organizations: string[];
  resources: string[];
}

export interface SearchHistoryItem {
  query: string;
  results_count: number;
  filters: SearchFilters;
  created_at: string;
}

export interface PopularSearch {
  query: string;
  count: number;
}

export interface FilterOption {
  id?: number;
  value?: string;
  code?: string;
  name?: string;
  label?: string;
}

export interface SearchFilterOptions {
  blog_categories: FilterOption[];
  news_categories: FilterOption[];
  tags: FilterOption[];
  countries: FilterOption[];
  cities: FilterOption[];
  languages: FilterOption[];
  event_types: FilterOption[];
  organization_types: FilterOption[];
  resource_types: FilterOption[];
}

export type SearchContentType = 'blog_posts' | 'news' | 'events' | 'organizations' | 'resources';

export interface AdvancedSearchFormData {
  query: string;
  contentTypes: SearchContentType[];
  filters: SearchFilters;
  sortBy?: string;
  sortOrder?: 'asc' | 'desc';
}

export interface SearchContextType {
  searchQuery: string;
  searchResults: SearchResult[];
  searchFilters: SearchFilters;
  isSearching: boolean;
  searchHistory: SearchHistoryItem[];
  suggestions: SearchSuggestion;
  filterOptions: SearchFilterOptions;
  setSearchQuery: (query: string) => void;
  setSearchFilters: (filters: SearchFilters) => void;
  performSearch: (params: SearchParams) => Promise<SearchResponse>;
  performGlobalSearch: (query: string, contentTypes?: string, limit?: number) => Promise<GlobalSearchResult>;
  getSuggestions: (query: string, limit?: number) => Promise<SearchSuggestion>;
  getSearchHistory: (limit?: number) => Promise<SearchHistoryItem[]>;
  clearSearchHistory: () => void;
  getFilterOptions: () => Promise<SearchFilterOptions>;
}