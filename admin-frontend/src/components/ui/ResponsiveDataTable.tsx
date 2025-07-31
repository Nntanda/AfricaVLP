import React, { useState } from 'react';

interface Column<T> {
  key: keyof T;
  header: string;
  render?: (value: any, row: T) => React.ReactNode;
  sortable?: boolean;
  width?: string;
  mobileHidden?: boolean;
  priority?: 'high' | 'medium' | 'low';
}

interface ResponsiveDataTableProps<T> {
  data: T[];
  columns: Column<T>[];
  loading?: boolean;
  emptyMessage?: string;
  onRowClick?: (row: T) => void;
  className?: string;
  mobileCardView?: boolean;
  sortable?: boolean;
}

export function ResponsiveDataTable<T extends Record<string, any>>({
  data,
  columns,
  loading = false,
  emptyMessage = 'No data available',
  onRowClick,
  className = '',
  mobileCardView = true,
  sortable = true,
}: ResponsiveDataTableProps<T>) {
  const [sortConfig, setSortConfig] = useState<{
    key: keyof T | null;
    direction: 'asc' | 'desc';
  }>({ key: null, direction: 'asc' });

  const handleSort = (key: keyof T) => {
    if (!sortable) return;
    
    let direction: 'asc' | 'desc' = 'asc';
    if (sortConfig.key === key && sortConfig.direction === 'asc') {
      direction = 'desc';
    }
    setSortConfig({ key, direction });
  };

  const sortedData = React.useMemo(() => {
    if (!sortConfig.key) return data;

    return [...data].sort((a, b) => {
      const aValue = a[sortConfig.key!];
      const bValue = b[sortConfig.key!];

      if (aValue < bValue) {
        return sortConfig.direction === 'asc' ? -1 : 1;
      }
      if (aValue > bValue) {
        return sortConfig.direction === 'asc' ? 1 : -1;
      }
      return 0;
    });
  }, [data, sortConfig]);

  const visibleColumns = columns.filter(col => !col.mobileHidden);
  const priorityColumns = columns.filter(col => col.priority === 'high');

  if (loading) {
    return (
      <div className="flex justify-center items-center p-8">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
      </div>
    );
  }

  if (data.length === 0) {
    return (
      <div className="text-center py-8 text-gray-500">
        {emptyMessage}
      </div>
    );
  }

  return (
    <div className={`overflow-hidden ${className}`}>
      {/* Desktop Table View */}
      <div className="hidden md:block overflow-x-auto">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              {columns.map((column) => (
                <th
                  key={String(column.key)}
                  className={`px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ${
                    column.sortable && sortable ? 'cursor-pointer hover:bg-gray-100' : ''
                  }`}
                  style={{ width: column.width }}
                  onClick={() => column.sortable && sortable && handleSort(column.key)}
                >
                  <div className="flex items-center space-x-1">
                    <span>{column.header}</span>
                    {column.sortable && sortable && (
                      <span className="text-gray-400">
                        {sortConfig.key === column.key ? (
                          sortConfig.direction === 'asc' ? '↑' : '↓'
                        ) : (
                          '↕'
                        )}
                      </span>
                    )}
                  </div>
                </th>
              ))}
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {sortedData.map((row, index) => (
              <tr
                key={index}
                className={`${
                  onRowClick ? 'cursor-pointer hover:bg-gray-50' : ''
                } transition-colors`}
                onClick={() => onRowClick?.(row)}
              >
                {columns.map((column) => (
                  <td
                    key={String(column.key)}
                    className="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                  >
                    {column.render
                      ? column.render(row[column.key], row)
                      : String(row[column.key] || '')}
                  </td>
                ))}
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Mobile Card View */}
      {mobileCardView && (
        <div className="md:hidden space-y-4">
          {sortedData.map((row, index) => (
            <div
              key={index}
              className={`bg-white border border-gray-200 rounded-lg p-4 shadow-sm ${
                onRowClick ? 'cursor-pointer hover:shadow-md' : ''
              } transition-shadow`}
              onClick={() => onRowClick?.(row)}
            >
              {/* High Priority Fields */}
              <div className="space-y-2 mb-3">
                {priorityColumns.map((column) => (
                  <div key={String(column.key)} className="flex justify-between items-start">
                    <span className="text-sm font-medium text-gray-900">
                      {column.header}:
                    </span>
                    <span className="text-sm text-gray-600 text-right ml-2">
                      {column.render
                        ? column.render(row[column.key], row)
                        : String(row[column.key] || '')}
                    </span>
                  </div>
                ))}
              </div>

              {/* Other Fields */}
              <div className="border-t border-gray-100 pt-3 space-y-1">
                {visibleColumns
                  .filter(col => col.priority !== 'high')
                  .map((column) => (
                    <div key={String(column.key)} className="flex justify-between items-start">
                      <span className="text-xs text-gray-500">
                        {column.header}:
                      </span>
                      <span className="text-xs text-gray-700 text-right ml-2">
                        {column.render
                          ? column.render(row[column.key], row)
                          : String(row[column.key] || '')}
                      </span>
                    </div>
                  ))}
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Mobile Table View (Alternative) */}
      {!mobileCardView && (
        <div className="md:hidden overflow-x-auto">
          <table className="min-w-full divide-y divide-gray-200">
            <thead className="bg-gray-50">
              <tr>
                {visibleColumns.map((column) => (
                  <th
                    key={String(column.key)}
                    className="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    {column.header}
                  </th>
                ))}
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-gray-200">
              {sortedData.map((row, index) => (
                <tr
                  key={index}
                  className={`${
                    onRowClick ? 'cursor-pointer hover:bg-gray-50' : ''
                  } transition-colors`}
                  onClick={() => onRowClick?.(row)}
                >
                  {visibleColumns.map((column) => (
                    <td
                      key={String(column.key)}
                      className="px-3 py-2 text-xs text-gray-900"
                    >
                      {column.render
                        ? column.render(row[column.key], row)
                        : String(row[column.key] || '')}
                    </td>
                  ))}
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}

// Status Badge Component for table cells
export const StatusBadge: React.FC<{
  status: string;
  variant?: 'success' | 'warning' | 'error' | 'info';
}> = ({ status, variant = 'info' }) => {
  const variants = {
    success: 'bg-green-100 text-green-800',
    warning: 'bg-yellow-100 text-yellow-800',
    error: 'bg-red-100 text-red-800',
    info: 'bg-blue-100 text-blue-800',
  };

  return (
    <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${variants[variant]}`}>
      {status}
    </span>
  );
};

// Action Buttons Component for table cells
export const TableActions: React.FC<{
  actions: Array<{
    label: string;
    onClick: () => void;
    variant?: 'primary' | 'secondary' | 'danger';
    icon?: React.ReactNode;
  }>;
}> = ({ actions }) => {
  return (
    <div className="flex space-x-2">
      {actions.map((action, index) => {
        const variants = {
          primary: 'text-primary-600 hover:text-primary-900',
          secondary: 'text-gray-600 hover:text-gray-900',
          danger: 'text-red-600 hover:text-red-900',
        };

        return (
          <button
            key={index}
            onClick={(e) => {
              e.stopPropagation();
              action.onClick();
            }}
            className={`${variants[action.variant || 'secondary']} hover:underline text-sm font-medium touch-manipulation`}
            title={action.label}
          >
            {action.icon ? (
              <span className="flex items-center space-x-1">
                {action.icon}
                <span className="hidden sm:inline">{action.label}</span>
              </span>
            ) : (
              action.label
            )}
          </button>
        );
      })}
    </div>
  );
};