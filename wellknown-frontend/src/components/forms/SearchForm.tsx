import React, { useState } from 'react';
import { Button, Input } from '../ui';

interface SearchFormProps {
  onSearch: (query: string) => void;
  placeholder?: string;
  loading?: boolean;
  initialValue?: string;
}

const SearchForm: React.FC<SearchFormProps> = ({
  onSearch,
  placeholder = 'Search...',
  loading = false,
  initialValue = '',
}) => {
  const [query, setQuery] = useState(initialValue);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSearch(query.trim());
  };

  const handleClear = () => {
    setQuery('');
    onSearch('');
  };

  return (
    <form onSubmit={handleSubmit} className="flex space-x-2">
      <div className="flex-1 relative">
        <Input
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          placeholder={placeholder}
          className="pr-8"
        />
        {query && (
          <button
            type="button"
            onClick={handleClear}
            className="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
          >
            ×
          </button>
        )}
      </div>
      
      <Button type="submit" loading={loading}>
        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <span className="sr-only">Search</span>
      </Button>
    </form>
  );
};

export default SearchForm;