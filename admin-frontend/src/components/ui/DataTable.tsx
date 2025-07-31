import React from 'react';
import Table from './Table';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';
import Button from './Button';

interface DataTableColumn<T> {
  key: string;
  label: string;
  render?: (item: T) => React.ReactNode;
  sortable?: boolean;
  width?: string;
}

interface DataTableProps<T> {
  data: T[];
  columns: DataTableColumn<T>[];
  loading?: boolean;
  error?: string | null;
  currentPage?: number;
  totalPages?: number;
  totalCount?: number;
  onPageChange?: (page: number) => void;
  onSort?: (column: string, direction: 'asc' | 'desc') => void;
  onRefresh?: () => void;
  emptyMessage?: string;
  title?: string;
  actions?: React.ReactNode;
  className?: string;
}

const DataTable = <T extends Record<string, any>>({
  data,
  columns,
  loading = false,
  error = null,
  currentPage = 1,
  totalPages = 1,
  totalCount,
  onPageChange,
  onSort,
  onRefresh,
  emptyMessage = 'No data found.',
  title,
  actions,
  className = '',
}: DataTableProps<T>) => {
  if (loading) {
    return (
      <div className={`bg-white shadow rounded-lg ${className}`}>
        <div className="px-4 py-5 sm:p-6">
          <div className="flex justify-center items-center h-64">
            <LoadingSpinner />
          </div>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className={`bg-white shadow rounded-lg ${className}`}>
        <div className="px-4 py-5 sm:p-6">
          <ErrorMessage message={error} />
          {onRefresh && (
            <div className="mt-4 flex justify-center">
              <Button variant="secondary" onClick={onRefresh}>
                Try Again
              </Button>
            </div>
          )}
        </div>
      </div>
    );
  }

  return (
    <div className={`bg-white shadow rounded-lg ${className}`}>
      {(title || actions || onRefresh || totalCount !== undefined) && (
        <div className="px-4 py-5 sm:px-6 border-b border-gray-200">
          <div className="flex justify-between items-center">
            <div>
              {title && (
                <h3 className="text-lg font-medium text-gray-900">
                  {title}
                  {totalCount !== undefined && (
                    <span className="ml-2 text-sm font-normal text-gray-500">
                      ({totalCount.toLocaleString()})
                    </span>
                  )}
                </h3>
              )}
            </div>
            <div className="flex space-x-2">
              {onRefresh && (
                <Button variant="secondary" size="sm" onClick={onRefresh}>
                  <svg
                    className="h-4 w-4 mr-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth={2}
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                    />
                  </svg>
                  Refresh
                </Button>
              )}
              {actions}
            </div>
          </div>
        </div>
      )}

      <div className="px-4 py-5 sm:p-6">
        <Table
          data={data}
          columns={columns}
          currentPage={currentPage}
          totalPages={totalPages}
          onPageChange={onPageChange}
          onSort={onSort}
          emptyMessage={emptyMessage}
        />
      </div>
    </div>
  );
};

export default DataTable;