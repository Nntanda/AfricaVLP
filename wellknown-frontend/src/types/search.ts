/**
 * Search-related type definitions for wellknown frontend
 */

export interface SearchFilters {
  dateFrom?: string;
  dateTo?: string;
  categories?: string[];
  tags?: string[];
  country?: string;
  city?: string;
}

export interface SearchResults<T> {
  results: T[];
  total: number;
  page: number;
  pageSize: number;
  totalPages: number;
}

export interface UniversalSearchResults {
  results: {
    blog_posts?: any[];
    news?: any[];
    events?: any[];
    organizations?: any[];
  };
  query: string;
  content_types: string[];
}

export interface SearchSuggestion {
  id: string;
  title: string;
  type: string;
}

export interface FilterMetadata {
  blog_categories: Array<{ id: number; name: string }>;
  news_categories: Array<{ id: number; name: string }>;
  tags: Array<{ id: number; name: string }>;
  countries: Array<{ id: number; name: string }>;
  cities: Array<{ id: number; name: string; country_id: number }>;
}

export interface SearchState {
  query: string;
  filters: SearchFilters;
  results: any[];
  loading: boolean;
  error: string | null;
  suggestions: SearchSuggestion[];
  showSuggestions: boolean;
  history: string[];
}

export interface SearchContextType {
  searchState: SearchState;
  setQuery: (query: string) => void;
  setFilters: (filters: SearchFilters) => void;
  performSearch: (contentType: string) => Promise<void>;
  clearSearch: () => void;
  addToHistory: (query: string) => void;
  getSuggestions: (query: string, contentType: string) => Promise<void>;
  hideSuggestions: () => void;
}