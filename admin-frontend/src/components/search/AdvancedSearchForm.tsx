/**
 * Advanced search form component with filters
 */
import React, { useState, useEffect } from 'react';
import { useSearch } from '../../context/SearchContext';
import { searchService } from '../../services/api/search';
import { FilterMetadata, SearchFilters } from '../../types/search';
import { Button } from '../ui/Button';
import { Input } from '../ui/Input';

interface AdvancedSearchFormProps {
  contentType: string;
  onSearch: () => void;
  className?: string;
}

export const AdvancedSearchForm: React.FC<AdvancedSearchFormProps> = ({
  contentType,
  onSearch,
  className = '',
}) => {
  const { searchState, setQuery, setFilters } = useSearch();
  const [filterMetadata, setFilterMetadata] = useState<FilterMetadata | null>(null);
  const [localFilters, setLocalFilters] = useState<SearchFilters>(searchState.filters);
  const [showAdvanced, setShowAdvanced] = useState(false);

  useEffect(() => {
    const loadFilterMetadata = async () => {
      try {
        const metadata = await searchService.getFilterMetadata();
        setFilterMetadata(metadata);
      } catch (error) {
        console.error('Failed to load filter metadata:', error);
      }
    };

    loadFilterMetadata();
  }, []);

  const handleQueryChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setQuery(e.target.value);
  };

  const handleFilterChange = (key: keyof SearchFilters, value: any) => {
    const newFilters = { ...localFilters, [key]: value };
    setLocalFilters(newFilters);
    setFilters(newFilters);
  };

  const handleMultiSelectChange = (key: keyof SearchFilters, value: string, checked: boolean) => {
    const currentValues = (localFilters[key] as string[]) || [];
    const newValues = checked
      ? [...currentValues, value]
      : currentValues.filter(v => v !== value);
    
    handleFilterChange(key, newValues);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSearch();
  };

  const clearFilters = () => {
    const emptyFilters = {};
    setLocalFilters(emptyFilters);
    setFilters(emptyFilters);
  };

  const getRelevantCategories = () => {
    if (!filterMetadata) return [];
    
    switch (contentType) {
      case 'blog_posts':
        return filterMetadata.blog_categories;
      case 'news':
        return filterMetadata.news_categories;
      default:
        return [];
    }
  };

  return (
    <div className={`bg-white rounded-lg shadow-md p-6 ${className}`}>
      <form onSubmit={handleSubmit} className="space-y-4">
        {/* Search Query */}
        <div>
          <label htmlFor="search-query" className="block text-sm font-medium text-gray-700 mb-2">
            Search Query
          </label>
          <Input
            id="search-query"
            type="text"
            value={searchState.query}
            onChange={handleQueryChange}
            placeholder={`Search ${contentType.replace('_', ' ')}...`}
            className="w-full"
          />
        </div>

        {/* Advanced Filters Toggle */}
        <div className="flex justify-between items-center">
          <button
            type="button"
            onClick={() => setShowAdvanced(!showAdvanced)}
            className="text-blue-600 hover:text-blue-800 text-sm font-medium"
          >
            {showAdvanced ? 'Hide' : 'Show'} Advanced Filters
          </button>
          
          <div className="space-x-2">
            <Button type="button" variant="secondary" onClick={clearFilters}>
              Clear Filters
            </Button>
            <Button type="submit" variant="primary">
              Search
            </Button>
          </div>
        </div>

        {/* Advanced Filters */}
        {showAdvanced && (
          <div className="border-t pt-4 space-y-4">
            {/* Date Range */}
            {['blog_posts', 'news', 'resources'].includes(contentType) && (
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label htmlFor="date-from" className="block text-sm font-medium text-gray-700 mb-1">
                    From Date
                  </label>
                  <Input
                    id="date-from"
                    type="date"
                    value={localFilters.dateFrom || ''}
                    onChange={(e) => handleFilterChange('dateFrom', e.target.value)}
                  />
                </div>
                <div>
                  <label htmlFor="date-to" className="block text-sm font-medium text-gray-700 mb-1">
                    To Date
                  </label>
                  <Input
                    id="date-to"
                    type="date"
                    value={localFilters.dateTo || ''}
                    onChange={(e) => handleFilterChange('dateTo', e.target.value)}
                  />
                </div>
              </div>
            )}

            {/* Status Filter */}
            {['blog_posts', 'news'].includes(contentType) && (
              <div>
                <label htmlFor="status" className="block text-sm font-medium text-gray-700 mb-1">
                  Status
                </label>
                <select
                  id="status"
                  value={localFilters.status || ''}
                  onChange={(e) => handleFilterChange('status', e.target.value)}
                  className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">All Statuses</option>
                  <option value="draft">Draft</option>
                  <option value="published">Published</option>
                  <option value="archived">Archived</option>
                </select>
              </div>
            )}

            {/* Categories */}
            {getRelevantCategories().length > 0 && (
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Categories
                </label>
                <div className="max-h-32 overflow-y-auto border border-gray-300 rounded-md p-2">
                  {getRelevantCategories().map((category) => (
                    <label key={category.id} className="flex items-center space-x-2 py-1">
                      <input
                        type="checkbox"
                        checked={(localFilters.categories || []).includes(category.id.toString())}
                        onChange={(e) => handleMultiSelectChange('categories', category.id.toString(), e.target.checked)}
                        className="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                      />
                      <span className="text-sm text-gray-700">{category.name}</span>
                    </label>
                  ))}
                </div>
              </div>
            )}

            {/* Tags */}
            {['blog_posts', 'news'].includes(contentType) && filterMetadata?.tags && (
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Tags
                </label>
                <div className="max-h-32 overflow-y-auto border border-gray-300 rounded-md p-2">
                  {filterMetadata.tags.map((tag) => (
                    <label key={tag.id} className="flex items-center space-x-2 py-1">
                      <input
                        type="checkbox"
                        checked={(localFilters.tags || []).includes(tag.id.toString())}
                        onChange={(e) => handleMultiSelectChange('tags', tag.id.toString(), e.target.checked)}
                        className="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                      />
                      <span className="text-sm text-gray-700">{tag.name}</span>
                    </label>
                  ))}
                </div>
              </div>
            )}

            {/* Location Filters */}
            {['events', 'organizations'].includes(contentType) && filterMetadata && (
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label htmlFor="country" className="block text-sm font-medium text-gray-700 mb-1">
                    Country
                  </label>
                  <select
                    id="country"
                    value={localFilters.country || ''}
                    onChange={(e) => handleFilterChange('country', e.target.value)}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <option value="">All Countries</option>
                    {filterMetadata.countries.map((country) => (
                      <option key={country.id} value={country.id}>
                        {country.name}
                      </option>
                    ))}
                  </select>
                </div>
                <div>
                  <label htmlFor="city" className="block text-sm font-medium text-gray-700 mb-1">
                    City
                  </label>
                  <select
                    id="city"
                    value={localFilters.city || ''}
                    onChange={(e) => handleFilterChange('city', e.target.value)}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    disabled={!localFilters.country}
                  >
                    <option value="">All Cities</option>
                    {filterMetadata.cities
                      .filter(city => !localFilters.country || city.country_id.toString() === localFilters.country)
                      .map((city) => (
                        <option key={city.id} value={city.id}>
                          {city.name}
                        </option>
                      ))}
                  </select>
                </div>
              </div>
            )}
          </div>
        )}
      </form>
    </div>
  );
};