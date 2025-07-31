/**
 * Search suggestions dropdown component
 */
import React from 'react';
import { useSearch } from '../../context/SearchContext';

interface SearchSuggestionsProps {
  onSuggestionClick: (suggestion: string) => void;
  className?: string;
}

export const SearchSuggestions: React.FC<SearchSuggestionsProps> = ({
  onSuggestionClick,
  className = '',
}) => {
  const { searchState, hideSuggestions } = useSearch();

  if (!searchState.showSuggestions || searchState.suggestions.length === 0) {
    return null;
  }

  const handleSuggestionClick = (suggestion: string) => {
    onSuggestionClick(suggestion);
    hideSuggestions();
  };

  return (
    <div className={`absolute z-50 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 ${className}`}>
      <div className="py-1">
        {/* Search History */}
        {searchState.history.length > 0 && (
          <>
            <div className="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide">
              Recent Searches
            </div>
            {searchState.history.slice(0, 3).map((historyItem, index) => (
              <button
                key={`history-${index}`}
                className="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                onClick={() => handleSuggestionClick(historyItem)}
              >
                <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {historyItem}
              </button>
            ))}
            {searchState.suggestions.length > 0 && <div className="border-t border-gray-200" />}
          </>
        )}

        {/* Suggestions */}
        {searchState.suggestions.length > 0 && (
          <>
            <div className="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide">
              Suggestions
            </div>
            {searchState.suggestions.map((suggestion, index) => (
              <button
                key={`suggestion-${index}`}
                className="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                onClick={() => handleSuggestionClick(suggestion)}
              >
                <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                {suggestion}
              </button>
            ))}
          </>
        )}
      </div>
    </div>
  );
};