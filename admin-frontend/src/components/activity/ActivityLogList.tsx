import React, { useState, useEffect } from 'react';
import { ActivityLog, PaginatedResponse } from '../../types/common';
import { adminAPI } from '../../services/api/endpoints';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';
import Button from '../ui/Button';
import Table from '../ui/Table';

interface ActivityLogListProps {
  filters?: {
    action?: string;
    dateFrom?: string;
    dateTo?: string;
  };
  onRefresh?: () => void;
  refreshTrigger?: number;
}

const ActivityLogList: React.FC<ActivityLogListProps> = ({
  filters,
  onRefresh,
  refreshTrigger,
}) => {
  const [logs, setLogs] = useState<ActivityLog[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [totalCount, setTotalCount] = useState(0);

  const fetchLogs = async (page: number = 1) => {
    try {
      setLoading(true);
      setError(null);
      
      const params: any = {
        page,
        page_size: 20,
        ordering: '-created_at',
      };

      // Apply filters
      if (filters?.action) {
        params.action = filters.action;
      }
      if (filters?.dateFrom) {
        params.created_at__gte = filters.dateFrom;
      }
      if (filters?.dateTo) {
        params.created_at__lte = filters.dateTo;
      }

      const response = await adminAPI.getActivityLogs(params);

      const data: PaginatedResponse<ActivityLog> = response.data;
      setLogs(data.results);
      setTotalCount(data.count);
      setTotalPages(Math.ceil(data.count / 20));
    } catch (err: any) {
      console.error('Error fetching activity logs:', err);
      setError('Failed to load activity logs. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchLogs(currentPage);
  }, [currentPage, refreshTrigger, filters]);

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  const getActionBadge = (action: string) => {
    const actionColors = {
      create: 'bg-green-100 text-green-800',
      update: 'bg-blue-100 text-blue-800',
      delete: 'bg-red-100 text-red-800',
      login: 'bg-purple-100 text-purple-800',
      logout: 'bg-gray-100 text-gray-800',
    };

    return (
      <span
        className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
          actionColors[action as keyof typeof actionColors] || 'bg-gray-100 text-gray-800'
        }`}
      >
        {action.toUpperCase()}
      </span>
    );
  };

  const columns = [
    {
      key: 'action',
      label: 'Action',
      render: (log: ActivityLog) => getActionBadge(log.action),
    },
    {
      key: 'description',
      label: 'Description',
      render: (log: ActivityLog) => (
        <div className="max-w-md">
          <div className="text-sm text-gray-900">{log.description}</div>
        </div>
      ),
    },
    {
      key: 'user',
      label: 'User',
      render: (log: ActivityLog) => (
        <div className="text-sm text-gray-900">
          {log.user_id ? `User ID: ${log.user_id}` : 
           log.admin_id ? `Admin ID: ${log.admin_id}` : 
           'System'}
        </div>
      ),
    },
    {
      key: 'ip_address',
      label: 'IP Address',
      render: (log: ActivityLog) => (
        <span className="text-sm text-gray-500 font-mono">
          {log.ip_address || '-'}
        </span>
      ),
    },
    {
      key: 'created_at',
      label: 'Timestamp',
      render: (log: ActivityLog) => (
        <span className="text-sm text-gray-500">{formatDate(log.created_at)}</span>
      ),
    },
  ];

  if (loading) {
    return (
      <div className="flex justify-center items-center h-64">
        <LoadingSpinner />
      </div>
    );
  }

  if (error) {
    return <ErrorMessage message={error} />;
  }

  return (
    <div className="space-y-4">
      <div className="flex justify-between items-center">
        <h3 className="text-lg font-medium text-gray-900">
          Activity Logs ({totalCount})
        </h3>
        {onRefresh && (
          <Button variant="secondary" onClick={onRefresh}>
            Refresh
          </Button>
        )}
      </div>

      <Table
        data={logs}
        columns={columns}
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={handlePageChange}
        emptyMessage="No activity logs found."
      />
    </div>
  );
};

export default ActivityLogList;