/**
 * Tests for SearchContext
 */
import React from 'react';
import { renderHook, act } from '@testing-library/react';
import { SearchProvider, useSearch } from '../SearchContext';
import { searchService } from '../../services/api/search';

// Mock the search service
jest.mock('../../services/api/search', () => ({
  searchService: {
    searchBlogPosts: jest.fn(),
    searchNews: jest.fn(),
    searchEvents: jest.fn(),
    searchOrganizations: jest.fn(),
    searchResources: jest.fn(),
    universalSearch: jest.fn(),
    getSearchSuggestions: jest.fn(),
  }
}));

// Mock localStorage
const mockLocalStorage = {
  getItem: jest.fn(),
  setItem: jest.fn(),
  removeItem: jest.fn(),
};
Object.defineProperty(window, 'localStorage', {
  value: mockLocalStorage
});

describe('SearchContext', () => {
  const wrapper = ({ children }: { children: React.ReactNode }) => (
    <SearchProvider>{children}</SearchProvider>
  );

  beforeEach(() => {
    jest.clearAllMocks();
    mockLocalStorage.getItem.mockReturnValue('[]');
  });

  it('provides initial search state', () => {
    const { result } = renderHook(() => useSearch(), { wrapper });

    expect(result.current.searchState).toEqual({
      query: '',
      filters: {},
      results: [],
      loading: false,
      error: null,
      suggestions: [],
      showSuggestions: false,
      history: []
    });
  });

  it('updates query', () => {
    const { result } = renderHook(() => useSearch(), { wrapper });

    act(() => {
      result.current.setQuery('test query');
    });

    expect(result.current.searchState.query).toBe('test query');
  });

  it('updates filters', () => {
    const { result } = renderHook(() => useSearch(), { wrapper });
    const filters = { status: 'published' };

    act(() => {
      result.current.setFilters(filters);
    });

    expect(result.current.searchState.filters).toEqual(filters);
  });

  it('performs search successfully', async () => {
    const mockResults = {
      results: [{ id: 1, title: 'Test Post' }],
      pagination: { page: 1, total_count: 1 }
    };
    
    (searchService.searchBlogPosts as jest.Mock).mockResolvedValue(mockResults);

    const { result } = renderHook(() => useSearch(), { wrapper });

    act(() => {
      result.current.setQuery('test');
    });

    await act(async () => {
      await result.current.performSearch('blog_posts');
    });

    expect(searchService.searchBlogPosts).toHaveBeenCalledWith('test', {});
    expect(result.current.searchState.results).toEqual(mockResults.results);
    expect(result.current.searchState.loading).toBe(false);
    expect(result.current.searchState.error).toBeNull();
  });

  it('handles search error', async () => {
    const errorMessage = 'Search failed';
    (searchService.searchBlogPosts as jest.Mock).mockRejectedValue(new Error(errorMessage));

    const { result } = renderHook(() => useSearch(), { wrapper });

    act(() => {
      result.current.setQuery('test');
    });

    await act(async () => {
      await result.current.performSearch('blog_posts');
    });

    expect(result.current.searchState.error).toBe(errorMessage);
    expect(result.current.searchState.loading).toBe(false);
  });

  it('adds query to history', () => {
    const { result } = renderHook(() => useSearch(), { wrapper });

    act(() => {
      result.current.addToHistory('test query');
    });

    expect(result.current.searchState.history).toContain('test query');
    expect(mockLocalStorage.setItem).toHaveBeenCalledWith(
      'searchHistory',
      JSON.stringify(['test query'])
    );
  });

  it('gets search suggestions', async () => {
    const mockSuggestions = ['suggestion 1', 'suggestion 2'];
    (searchService.getSearchSuggestions as jest.Mock).mockResolvedValue(mockSuggestions);

    const { result } = renderHook(() => useSearch(), { wrapper });

    await act(async () => {
      await result.current.getSuggestions('test', 'blog_posts');
    });

    expect(searchService.getSearchSuggestions).toHaveBeenCalledWith('test', 'blog_posts');
    expect(result.current.searchState.suggestions).toEqual(mockSuggestions);
    expect(result.current.searchState.showSuggestions).toBe(true);
  });

  it('clears search', () => {
    const { result } = renderHook(() => useSearch(), { wrapper });

    // Set some search state first
    act(() => {
      result.current.setQuery('test');
      result.current.setFilters({ status: 'published' });
    });

    act(() => {
      result.current.clearSearch();
    });

    expect(result.current.searchState.query).toBe('');
    expect(result.current.searchState.results).toEqual([]);
    expect(result.current.searchState.error).toBeNull();
    expect(result.current.searchState.suggestions).toEqual([]);
    expect(result.current.searchState.showSuggestions).toBe(false);
  });

  it('hides suggestions', () => {
    const { result } = renderHook(() => useSearch(), { wrapper });

    // Show suggestions first
    act(() => {
      result.current.getSuggestions('test', 'blog_posts');
    });

    act(() => {
      result.current.hideSuggestions();
    });

    expect(result.current.searchState.showSuggestions).toBe(false);
  });

  it('throws error when used outside provider', () => {
    const { result } = renderHook(() => useSearch());

    expect(result.error).toEqual(
      Error('useSearch must be used within a SearchProvider')
    );
  });
});