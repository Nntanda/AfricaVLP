/**
 * Comprehensive search page for admin frontend
 */
import React, { useState, useEffect } from 'react';
import { useSearch } from '../context/SearchContext';
import { useSearchParams } from '../hooks/useSearchParams';
import { AdvancedSearchForm } from '../components/search/AdvancedSearchForm';
import { SearchResults } from '../components/search/SearchResults';
import { SearchSuggestions } from '../components/search/SearchSuggestions';

const CONTENT_TYPES = [
  { value: 'blog_posts', label: 'Blog Posts' },
  { value: 'news', label: 'News Articles' },
  { value: 'events', label: 'Events' },
  { value: 'organizations', label: 'Organizations' },
  { value: 'resources', label: 'Resources' },
  { value: 'universal', label: 'All Content' },
];

export const Search: React.FC = () => {
  const { 
    searchState, 
    setQuery, 
    setFilters, 
    performSearch, 
    clearSearch,
    getSuggestions 
  } = useSearch();
  
  const { parseSearchParams, updateSearchParams } = useSearchParams();
  const [selectedContentType, setSelectedContentType] = useState('blog_posts');
  const [showSuggestions, setShowSuggestions] = useState(false);

  // Initialize search from URL parameters
  useEffect(() => {
    const { query, filters } = parseSearchParams();
    if (query || Object.keys(filters).length > 0) {
      setQuery(query);
      setFilters(filters);
      if (query) {
        performSearch(selectedContentType);
      }
    }
  }, []);

  // Update URL when search state changes
  useEffect(() => {
    updateSearchParams(searchState.query, searchState.filters);
  }, [searchState.query, searchState.filters, updateSearchParams]);

  const handleSearch = async () => {
    await performSearch(selectedContentType);
    setShowSuggestions(false);
  };

  const handleQueryChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const query = e.target.value;
    setQuery(query);
    
    // Get suggestions for non-empty queries
    if (query.length >= 2) {
      getSuggestions(query, selectedContentType);
      setShowSuggestions(true);
    } else {
      setShowSuggestions(false);
    }
  };

  const handleSuggestionClick = (suggestion: string) => {
    setQuery(suggestion);
    setShowSuggestions(false);
    performSearch(selectedContentType);
  };

  const handleContentTypeChange = (contentType: string) => {
    setSelectedContentType(contentType);
    if (searchState.query) {
      performSearch(contentType);
    }
  };

  const handleResultClick = (result: any) => {
    // Navigate to the specific item's detail page
    // This would depend on your routing structure
    console.log('Result clicked:', result);
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Page Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">Search</h1>
          <p className="mt-2 text-gray-600">
            Search across all content types with advanced filtering options
          </p>
        </div>

        {/* Content Type Selector */}
        <div className="mb-6">
          <div className="flex flex-wrap gap-2">
            {CONTENT_TYPES.map((type) => (
              <button
                key={type.value}
                onClick={() => handleContentTypeChange(type.value)}
                className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                  selectedContentType === type.value
                    ? 'bg-blue-600 text-white'
                    : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
                }`}
              >
                {type.label}
              </button>
            ))}
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Search Form */}
          <div className="lg:col-span-1">
            <div className="sticky top-4">
              <AdvancedSearchForm
                contentType={selectedContentType}
                onSearch={handleSearch}
              />
            </div>
          </div>

          {/* Search Results */}
          <div className="lg:col-span-2">
            <SearchResults
              contentType={selectedContentType}
              onResultClick={handleResultClick}
            />
          </div>
        </div>

        {/* Quick Search Bar (Alternative) */}
        <div className="fixed bottom-4 right-4 lg:hidden">
          <div className="relative">
            <input
              type="text"
              value={searchState.query}
              onChange={handleQueryChange}
              onFocus={() => setShowSuggestions(true)}
              onBlur={() => setTimeout(() => setShowSuggestions(false), 200)}
              placeholder="Quick search..."
              className="w-64 px-4 py-2 border border-gray-300 rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button
              onClick={handleSearch}
              className="absolute right-2 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600"
            >
              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
            
            {showSuggestions && (
              <SearchSuggestions
                onSuggestionClick={handleSuggestionClick}
                className="w-64"
              />
            )}
          </div>
        </div>
      </div>
    </div>
  );
};