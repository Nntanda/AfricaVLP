import React, { createContext, useContext, useState, useCallback, useEffect } from 'react';
import {
  SearchContextType,
  SearchFilters,
  SearchParams,
  SearchResponse,
  GlobalSearchResult,
  SearchSuggestion,
  SearchHistoryItem,
  SearchFilterOptions,
  SearchResult
} from '../types/search';
import { SearchAPI } from '../services/api/search';
import { useToast } from '../hooks/useToast';

const SearchContext = createContext<SearchContextType | undefined>(undefined);

interface SearchProviderProps {
  children: React.ReactNode;
}

export const SearchProvider: React.FC<SearchProviderProps> = ({ children }) => {
  const [searchQuery, setSearchQuery] = useState<string>('');
  const [searchResults, setSearchResults] = useState<SearchResult[]>([]);
  const [searchFilters, setSearchFilters] = useState<SearchFilters>({});
  const [isSearching, setIsSearching] = useState<boolean>(false);
  const [searchHistory, setSearchHistory] = useState<SearchHistoryItem[]>([]);
  const [suggestions, setSuggestions] = useState<SearchSuggestion>({
    blog_posts: [],
    news: [],
    events: [],
    organizations: [],
    resources: []
  });
  const [filterOptions, setFilterOptions] = useState<SearchFilterOptions>({
    blog_categories: [],
    news_categories: [],
    tags: [],
    countries: [],
    cities: [],
    languages: [],
    event_types: [],
    organization_types: [],
    resource_types: []
  });

  const { showToast } = useToast();

  // Load filter options on mount
  useEffect(() => {
    loadFilterOptions();
    loadSearchHistory();
  }, []);

  const loadFilterOptions = useCallback(async () => {
    try {
      const options = await SearchAPI.getFilterOptions();
      setFilterOptions(options);
    } catch (error) {
      console.error('Failed to load filter options:', error);
      showToast('Failed to load search filters', 'error');
    }
  }, [showToast]);

  const loadSearchHistory = useCallback(async () => {
    try {
      const history = await SearchAPI.getSearchHistory();
      setSearchHistory(history);
    } catch (error) {
      console.error('Failed to load search history:', error);
    }
  }, []);

  const performSearch = useCallback(async (params: SearchParams): Promise<SearchResponse> => {
    setIsSearching(true);
    try {
      // Default to blog posts if no specific content type is specified
      const response = await SearchAPI.searchBlogPosts(params);
      setSearchResults(response.results);
      
      // Update search history
      if (params.query) {
        await loadSearchHistory();
      }
      
      return response;
    } catch (error) {
      console.error('Search failed:', error);
      showToast('Search failed. Please try again.', 'error');
      throw error;
    } finally {
      setIsSearching(false);
    }
  }, [showToast, loadSearchHistory]);

  const performGlobalSearch = useCallback(async (
    query: string,
    contentTypes: string = 'all',
    limit: number = 5
  ): Promise<GlobalSearchResult> => {
    setIsSearching(true);
    try {
      const response = await SearchAPI.globalSearch(query, contentTypes, limit);
      
      // Flatten results for display
      const allResults: SearchResult[] = [];
      Object.values(response.results).forEach(results => {
        if (Array.isArray(results)) {
          allResults.push(...results);
        }
      });
      setSearchResults(allResults);
      
      return response;
    } catch (error) {
      console.error('Global search failed:', error);
      showToast('Search failed. Please try again.', 'error');
      throw error;
    } finally {
      setIsSearching(false);
    }
  }, [showToast]);

  const getSuggestions = useCallback(async (
    query: string,
    limit: number = 10
  ): Promise<SearchSuggestion> => {
    try {
      const suggestionData = await SearchAPI.getSuggestions(query, limit);
      setSuggestions(suggestionData);
      return suggestionData;
    } catch (error) {
      console.error('Failed to get suggestions:', error);
      return {
        blog_posts: [],
        news: [],
        events: [],
        organizations: [],
        resources: []
      };
    }
  }, []);

  const getSearchHistory = useCallback(async (limit: number = 20): Promise<SearchHistoryItem[]> => {
    try {
      const history = await SearchAPI.getSearchHistory(limit);
      setSearchHistory(history);
      return history;
    } catch (error) {
      console.error('Failed to get search history:', error);
      return [];
    }
  }, []);

  const clearSearchHistory = useCallback(() => {
    setSearchHistory([]);
    // Note: This would typically also clear the history on the server
    // For now, we just clear the local state
  }, []);

  const getFilterOptions = useCallback(async (): Promise<SearchFilterOptions> => {
    try {
      const options = await SearchAPI.getFilterOptions();
      setFilterOptions(options);
      return options;
    } catch (error) {
      console.error('Failed to get filter options:', error);
      return filterOptions;
    }
  }, [filterOptions]);

  const contextValue: SearchContextType = {
    searchQuery,
    searchResults,
    searchFilters,
    isSearching,
    searchHistory,
    suggestions,
    filterOptions,
    setSearchQuery,
    setSearchFilters,
    performSearch,
    performGlobalSearch,
    getSuggestions,
    getSearchHistory,
    clearSearchHistory,
    getFilterOptions
  };

  return (
    <SearchContext.Provider value={contextValue}>
      {children}
    </SearchContext.Provider>
  );
};

export const useSearch = (): SearchContextType => {
  const context = useContext(SearchContext);
  if (context === undefined) {
    throw new Error('useSearch must be used within a SearchProvider');
  }
  return context;
};

export default SearchContext;