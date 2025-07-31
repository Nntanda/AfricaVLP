import React, { useState } from 'react';
import Button from '../ui/Button';
import Input from '../ui/Input';

interface SearchFilters {
  query: string;
  category?: string;
  dateFrom?: string;
  dateTo?: string;
  status?: string;
}

interface SearchFormProps {
  onSearch: (filters: SearchFilters) => void;
  placeholder?: string;
  loading?: boolean;
  className?: string;
  showAdvanced?: boolean;
  categories?: Array<{ value: string; label: string }>;
  statuses?: Array<{ value: string; label: string }>;
}

const SearchForm: React.FC<SearchFormProps> = ({
  onSearch,
  placeholder = 'Search...',
  loading = false,
  className = '',
  showAdvanced = false,
  categories = [],
  statuses = [],
}) => {
  const [filters, setFilters] = useState<SearchFilters>({
    query: '',
    category: '',
    dateFrom: '',
    dateTo: '',
    status: '',
  });
  const [showAdvancedFilters, setShowAdvancedFilters] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    // Only send non-empty filters
    const activeFilters: SearchFilters = { query: filters.query.trim() };
    
    if (filters.category) activeFilters.category = filters.category;
    if (filters.dateFrom) activeFilters.dateFrom = filters.dateFrom;
    if (filters.dateTo) activeFilters.dateTo = filters.dateTo;
    if (filters.status) activeFilters.status = filters.status;
    
    onSearch(activeFilters);
  };

  const handleInputChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
  ) => {
    const { name, value } = e.target;
    setFilters(prev => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleClear = () => {
    setFilters({
      query: '',
      category: '',
      dateFrom: '',
      dateTo: '',
      status: '',
    });
    onSearch({ query: '' });
  };

  return (
    <div className={`bg-white shadow rounded-lg ${className}`}>
      <div className="px-4 py-5 sm:p-6">
        <form onSubmit={handleSubmit} className="space-y-4">
          {/* Basic Search */}
          <div className="flex space-x-2">
            <div className="flex-1">
              <Input
                name="query"
                value={filters.query}
                onChange={handleInputChange}
                placeholder={placeholder}
                disabled={loading}
                className="w-full"
              />
            </div>
            <Button type="submit" loading={loading}>
              Search
            </Button>
            {showAdvanced && (
              <Button
                type="button"
                variant="secondary"
                onClick={() => setShowAdvancedFilters(!showAdvancedFilters)}
                disabled={loading}
              >
                {showAdvancedFilters ? 'Hide' : 'Advanced'}
              </Button>
            )}
          </div>

          {/* Advanced Filters */}
          {showAdvanced && showAdvancedFilters && (
            <div className="border-t pt-4 space-y-4">
              <h4 className="text-sm font-medium text-gray-900">Advanced Filters</h4>
              
              <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {categories.length > 0 && (
                  <div>
                    <label htmlFor="category" className="block text-sm font-medium text-gray-700">
                      Category
                    </label>
                    <select
                      id="category"
                      name="category"
                      value={filters.category}
                      onChange={handleInputChange}
                      disabled={loading}
                      className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    >
                      <option value="">All Categories</option>
                      {categories.map(category => (
                        <option key={category.value} value={category.value}>
                          {category.label}
                        </option>
                      ))}
                    </select>
                  </div>
                )}

                {statuses.length > 0 && (
                  <div>
                    <label htmlFor="status" className="block text-sm font-medium text-gray-700">
                      Status
                    </label>
                    <select
                      id="status"
                      name="status"
                      value={filters.status}
                      onChange={handleInputChange}
                      disabled={loading}
                      className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    >
                      <option value="">All Statuses</option>
                      {statuses.map(status => (
                        <option key={status.value} value={status.value}>
                          {status.label}
                        </option>
                      ))}
                    </select>
                  </div>
                )}

                <div>
                  <Input
                    label="From Date"
                    name="dateFrom"
                    type="date"
                    value={filters.dateFrom}
                    onChange={handleInputChange}
                    disabled={loading}
                  />
                </div>

                <div>
                  <Input
                    label="To Date"
                    name="dateTo"
                    type="date"
                    value={filters.dateTo}
                    onChange={handleInputChange}
                    disabled={loading}
                  />
                </div>
              </div>

              <div className="flex justify-end space-x-2">
                <Button
                  type="button"
                  variant="secondary"
                  onClick={handleClear}
                  disabled={loading}
                >
                  Clear All
                </Button>
                <Button type="submit" loading={loading}>
                  Apply Filters
                </Button>
              </div>
            </div>
          )}
        </form>
      </div>
    </div>
  );
};

export default SearchForm;