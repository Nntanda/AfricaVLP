import React, { useState, useEffect } from 'react';
import { Organization, PaginatedResponse } from '../../types/common';
import { adminAPI } from '../../services/api/endpoints';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';
import Button from '../ui/Button';
import Table from '../ui/Table';

interface OrganizationListProps {
  onEdit: (organization: Organization) => void;
  onDelete: (organization: Organization) => void;
  onRefresh?: () => void;
  refreshTrigger?: number;
}

const OrganizationList: React.FC<OrganizationListProps> = ({
  onEdit,
  onDelete,
  onRefresh,
  refreshTrigger,
}) => {
  const [organizations, setOrganizations] = useState<Organization[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [totalCount, setTotalCount] = useState(0);

  const fetchOrganizations = async (page: number = 1) => {
    try {
      setLoading(true);
      setError(null);
      
      const response = await adminAPI.getOrganizations({
        page,
        page_size: 10,
        ordering: '-created_at',
      });

      const data: PaginatedResponse<Organization> = response.data;
      setOrganizations(data.results);
      setTotalCount(data.count);
      setTotalPages(Math.ceil(data.count / 10));
    } catch (err: any) {
      console.error('Error fetching organizations:', err);
      setError('Failed to load organizations. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchOrganizations(currentPage);
  }, [currentPage, refreshTrigger]);

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    });
  };

  const columns = [
    {
      key: 'name',
      label: 'Organization',
      render: (org: Organization) => (
        <div>
          <div className="text-sm font-medium text-gray-900">{org.name}</div>
          {org.description && (
            <div className="text-sm text-gray-500 truncate max-w-xs">
              {org.description}
            </div>
          )}
        </div>
      ),
    },
    {
      key: 'contact',
      label: 'Contact',
      render: (org: Organization) => (
        <div className="text-sm text-gray-900">
          {org.email && <div>{org.email}</div>}
          {org.phone && <div>{org.phone}</div>}
        </div>
      ),
    },
    {
      key: 'website',
      label: 'Website',
      render: (org: Organization) => (
        org.website ? (
          <a
            href={org.website}
            target="_blank"
            rel="noopener noreferrer"
            className="text-sm text-blue-600 hover:text-blue-800"
          >
            {org.website}
          </a>
        ) : (
          <span className="text-sm text-gray-500">-</span>
        )
      ),
    },
    {
      key: 'address',
      label: 'Location',
      render: (org: Organization) => (
        <div className="text-sm text-gray-900">
          {org.address && <div>{org.address}</div>}
        </div>
      ),
    },
    {
      key: 'created_at',
      label: 'Created',
      render: (org: Organization) => (
        <span className="text-sm text-gray-500">{formatDate(org.created_at)}</span>
      ),
    },
    {
      key: 'actions',
      label: 'Actions',
      render: (org: Organization) => (
        <div className="flex space-x-2">
          <Button
            variant="secondary"
            size="sm"
            onClick={() => onEdit(org)}
          >
            Edit
          </Button>
          <Button
            variant="danger"
            size="sm"
            onClick={() => onDelete(org)}
          >
            Delete
          </Button>
        </div>
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
          Organizations ({totalCount})
        </h3>
        {onRefresh && (
          <Button variant="secondary" onClick={onRefresh}>
            Refresh
          </Button>
        )}
      </div>

      <Table
        data={organizations}
        columns={columns}
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={handlePageChange}
        emptyMessage="No organizations found."
      />
    </div>
  );
};

export default OrganizationList;