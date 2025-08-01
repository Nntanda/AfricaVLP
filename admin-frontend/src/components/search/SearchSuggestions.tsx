import React from 'react';
import { useSearch } from '../../context/SearchContext';

interface SearchSuggestionsProps {
  onSuggestionClick: (suggestion: string) => void;
  className?: string;
  showSuggestions?: boolean;
}

export const SearchSuggestions: React.FC<SearchSuggestionsProps> = ({
  onSuggestionClick,
  className = '',
  showSuggestions = true,
}) => {
  const { suggestions, searchHistory } = useSearch();

  // Check if we have any suggestions or history to show
  const hasSuggestions = Object.values(suggestions).some(arr => arr.length > 0);
  const hasHistory = searchHistory.length > 0;

  if (!showSuggestions || (!hasSuggestions && !hasHistory)) {
    return null;
  }

  const handleSuggestionClick = (suggestion: string) => {
    onSuggestionClick(suggestion);
  };

  return (
    <div className={`absolute z-50 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 ${className}`}>
      <div className="py-1">
        {/* Search History */}
        {hasHistory && (
          <>
            <div className="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide">
              Recent Searches
            </div>
            {searchHistory.slice(0, 3).map((historyItem, index) => (
              <button
                key={`history-${index}`}
                className="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                onClick={() => handleSuggestionClick(historyItem.query)}
              >
                <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {historyItem.query}
              </button>
            ))}
            {hasSuggestions && <div className="border-t border-gray-200" />}
          </>
        )}

        {/* Suggestions */}
        {hasSuggestions && (
          <>
            <div className="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide">
              Suggestions
            </div>
            {/* Blog Post Suggestions */}
            {suggestions.blog_posts.map((suggestion, index) => (
              <button
                key={`blog-${index}`}
                className="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                onClick={() => handleSuggestionClick(suggestion)}
              >
                <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <span className="text-xs text-blue-600 mr-2">Blog:</span>
                {suggestion}
              </button>
            ))}
            {/* News Suggestions */}
            {suggestions.news.map((suggestion, index) => (
              <button
                key={`news-${index}`}
                className="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                onClick={() => handleSuggestionClick(suggestion)}
              >
                <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <span className="text-xs text-green-600 mr-2">News:</span>
                {suggestion}
              </button>
            ))}
            {/* Event Suggestions */}
            {suggestions.events.map((suggestion, index) => (
              <button
                key={`event-${index}`}
                className="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                onClick={() => handleSuggestionClick(suggestion)}
              >
                <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span className="text-xs text-purple-600 mr-2">Event:</span>
                {suggestion}
              </button>
            ))}
            {/* Organization Suggestions */}
            {suggestions.organizations.map((suggestion, index) => (
              <button
                key={`org-${index}`}
                className="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                onClick={() => handleSuggestionClick(suggestion)}
              >
                <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span className="text-xs text-orange-600 mr-2">Org:</span>
                {suggestion}
              </button>
            ))}
            {/* Resource Suggestions */}
            {suggestions.resources.map((suggestion, index) => (
              <button
                key={`resource-${index}`}
                className="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                onClick={() => handleSuggestionClick(suggestion)}
              >
                <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span className="text-xs text-red-600 mr-2">Resource:</span>
                {suggestion}
              </button>
            ))}
          </>
        )}
      </div>
    </div>
  );
};