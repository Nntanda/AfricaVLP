import React, { useState } from 'react';
import Button from '../ui/Button';
import Input from '../ui/Input';

interface ActivityLogFiltersProps {
  onFiltersChange: (filters: {
    action?: string;
    dateFrom?: string;
    dateTo?: string;
  }) => void;
}

const ActivityLogFilters: React.FC<ActivityLogFiltersProps> = ({
  onFiltersChange,
}) => {
  const [filters, setFilters] = useState({
    action: '',
    dateFrom: '',
    dateTo: '',
  });

  const actions = [
    { value: '', label: 'All Actions' },
    { value: 'create', label: 'Create' },
    { value: 'update', label: 'Update' },
    { value: 'delete', label: 'Delete' },
    { value: 'login', label: 'Login' },
    { value: 'logout', label: 'Logout' },
  ];

  const handleInputChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
  ) => {
    const { name, value } = e.target;
    setFilters(prev => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleApplyFilters = () => {
    const activeFilters: any = {};
    
    if (filters.action) {
      activeFilters.action = filters.action;
    }
    if (filters.dateFrom) {
      activeFilters.dateFrom = filters.dateFrom;
    }
    if (filters.dateTo) {
      activeFilters.dateTo = filters.dateTo;
    }

    onFiltersChange(activeFilters);
  };

  const handleClearFilters = () => {
    setFilters({
      action: '',
      dateFrom: '',
      dateTo: '',
    });
    onFiltersChange({});
  };

  return (
    <div className="bg-white shadow rounded-lg">
      <div className="px-4 py-5 sm:p-6">
        <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
          Filter Activity Logs
        </h3>

        <div className="grid grid-cols-1 gap-6 sm:grid-cols-3">
          <div>
            <label htmlFor="action" className="block text-sm font-medium text-gray-700">
              Action Type
            </label>
            <select
              id="action"
              name="action"
              value={filters.action}
              onChange={handleInputChange}
              className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            >
              {actions.map(action => (
                <option key={action.value} value={action.value}>
                  {action.label}
                </option>
              ))}
            </select>
          </div>

          <div>
            <Input
              label="From Date"
              name="dateFrom"
              type="date"
              value={filters.dateFrom}
              onChange={handleInputChange}
            />
          </div>

          <div>
            <Input
              label="To Date"
              name="dateTo"
              type="date"
              value={filters.dateTo}
              onChange={handleInputChange}
            />
          </div>
        </div>

        <div className="mt-6 flex justify-end space-x-3">
          <Button
            variant="secondary"
            onClick={handleClearFilters}
          >
            Clear Filters
          </Button>
          <Button
            variant="primary"
            onClick={handleApplyFilters}
          >
            Apply Filters
          </Button>
        </div>
      </div>
    </div>
  );
};

export default ActivityLogFilters;