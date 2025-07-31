/**
 * Tests for SearchResults component
 */
import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import { SearchResults } from '../SearchResults';
import { SearchProvider } from '../../../context/SearchContext';

// Mock the search context
const mockSearchState = {
  query: 'test query',
  filters: {},
  results: [
    {
      id: 1,
      title: 'Test Blog Post',
      content: 'This is a test blog post content',
      created_at: '2024-01-01T00:00:00Z',
      author: { name: 'Test Author' },
      status: 'published',
      categories: [{ id: 1, name: 'Technology' }],
      tags: [{ id: 1, name: 'testing' }]
    },
    {
      id: 2,
      title: 'Another Test Post',
      content: 'Another test content',
      created_at: '2024-01-02T00:00:00Z',
      author: { name: 'Another Author' },
      status: 'draft'
    }
  ],
  loading: false,
  error: null,
  suggestions: [],
  showSuggestions: false,
  history: []
};

const MockSearchProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  return (
    <SearchProvider>
      {children}
    </SearchProvider>
  );
};

// Mock the useSearch hook
jest.mock('../../../context/SearchContext', () => ({
  ...jest.requireActual('../../../context/SearchContext'),
  useSearch: () => ({
    searchState: mockSearchState,
    setQuery: jest.fn(),
    setFilters: jest.fn(),
    performSearch: jest.fn(),
    clearSearch: jest.fn(),
    addToHistory: jest.fn(),
    getSuggestions: jest.fn(),
    hideSuggestions: jest.fn(),
  })
}));

describe('SearchResults', () => {
  const mockOnResultClick = jest.fn();

  beforeEach(() => {
    mockOnResultClick.mockClear();
  });

  it('renders search results correctly', () => {
    render(
      <SearchResults
        contentType="blog_posts"
        onResultClick={mockOnResultClick}
      />
    );

    expect(screen.getByText('Found 2 results for "test query"')).toBeInTheDocument();
    expect(screen.getByText('Test Blog Post')).toBeInTheDocument();
    expect(screen.getByText('Another Test Post')).toBeInTheDocument();
  });

  it('highlights search query in results', () => {
    render(
      <SearchResults
        contentType="blog_posts"
        onResultClick={mockOnResultClick}
      />
    );

    // Check if the content contains highlighted text
    const blogPostContent = screen.getByText(/This is a test blog post content/);
    expect(blogPostContent).toBeInTheDocument();
  });

  it('displays metadata correctly', () => {
    render(
      <SearchResults
        contentType="blog_posts"
        onResultClick={mockOnResultClick}
      />
    );

    expect(screen.getByText('By: Test Author')).toBeInTheDocument();
    expect(screen.getByText('By: Another Author')).toBeInTheDocument();
    expect(screen.getByText('published')).toBeInTheDocument();
    expect(screen.getByText('draft')).toBeInTheDocument();
  });

  it('displays categories and tags', () => {
    render(
      <SearchResults
        contentType="blog_posts"
        onResultClick={mockOnResultClick}
      />
    );

    expect(screen.getByText('Technology')).toBeInTheDocument();
    expect(screen.getByText('#testing')).toBeInTheDocument();
  });

  it('calls onResultClick when result is clicked', () => {
    render(
      <SearchResults
        contentType="blog_posts"
        onResultClick={mockOnResultClick}
      />
    );

    const firstResult = screen.getByText('Test Blog Post').closest('div');
    fireEvent.click(firstResult!);

    expect(mockOnResultClick).toHaveBeenCalledWith(mockSearchState.results[0]);
  });

  it('shows loading state', () => {
    const loadingSearchState = { ...mockSearchState, loading: true };
    
    jest.doMock('../../../context/SearchContext', () => ({
      useSearch: () => ({
        searchState: loadingSearchState,
        setQuery: jest.fn(),
        setFilters: jest.fn(),
        performSearch: jest.fn(),
        clearSearch: jest.fn(),
        addToHistory: jest.fn(),
        getSuggestions: jest.fn(),
        hideSuggestions: jest.fn(),
      })
    }));

    render(
      <SearchResults
        contentType="blog_posts"
        onResultClick={mockOnResultClick}
      />
    );

    expect(screen.getByTestId('loading-spinner')).toBeInTheDocument();
  });

  it('shows error state', () => {
    const errorSearchState = { 
      ...mockSearchState, 
      loading: false, 
      error: 'Search failed' 
    };
    
    jest.doMock('../../../context/SearchContext', () => ({
      useSearch: () => ({
        searchState: errorSearchState,
        setQuery: jest.fn(),
        setFilters: jest.fn(),
        performSearch: jest.fn(),
        clearSearch: jest.fn(),
        addToHistory: jest.fn(),
        getSuggestions: jest.fn(),
        hideSuggestions: jest.fn(),
      })
    }));

    render(
      <SearchResults
        contentType="blog_posts"
        onResultClick={mockOnResultClick}
      />
    );

    expect(screen.getByText('Search failed')).toBeInTheDocument();
  });

  it('shows no results message', () => {
    const emptySearchState = { 
      ...mockSearchState, 
      results: [] 
    };
    
    jest.doMock('../../../context/SearchContext', () => ({
      useSearch: () => ({
        searchState: emptySearchState,
        setQuery: jest.fn(),
        setFilters: jest.fn(),
        performSearch: jest.fn(),
        clearSearch: jest.fn(),
        addToHistory: jest.fn(),
        getSuggestions: jest.fn(),
        hideSuggestions: jest.fn(),
      })
    }));

    render(
      <SearchResults
        contentType="blog_posts"
        onResultClick={mockOnResultClick}
      />
    );

    expect(screen.getByText('No results found for "test query"')).toBeInTheDocument();
  });
});