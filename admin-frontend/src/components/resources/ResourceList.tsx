import React, { useState, useEffect } from 'react';
import { Resource, PaginatedResponse } from '../../types/common';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';
import Button from '../ui/Button';
import Table from '../ui/Table';

interface ResourceListProps {
  onEdit: (resource: Resource) => void;
  onDelete: (resource: Resource) => void;
  onRefresh?: () => void;
  refreshTrigger?: number;
}

const ResourceList: React.FC<ResourceListProps> = ({
  onEdit,
  onDelete,
  onRefresh,
  refreshTrigger,
}) => {
  const [resources, setResources] = useState<Resource[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [totalCount, setTotalCount] = useState(0);

  const fetchResources = async (page: number = 1) => {
    try {
      setLoading(true);
      setError(null);
      
      // Mock data for now - replace with actual API call
      const mockResources: Resource[] = [
        {
          id: '1',
          title: 'AU-VLP Program Guidelines',
          description: 'Comprehensive guidelines for the African Union Youth Leadership Program.',
          file_url: 'https://example.com/guidelines.pdf',
          category: 'Documentation',
          organization_id: '1',
          created_at: '2024-01-15T10:00:00Z',
          updated_at: '2024-01-15T10:00:00Z',
        },
        {
          id: '2',
          title: 'Leadership Training Materials',
          description: 'Training materials for leadership development workshops.',
          file_url: 'https://example.com/training.zip',
          category: 'Training',
          organization_id: '1',
          created_at: '2024-02-01T12:00:00Z',
          updated_at: '2024-02-01T12:00:00Z',
        },
      ];

      setResources(mockResources);
      setTotalCount(mockResources.length);
      setTotalPages(1);
    } catch (err: any) {
      console.error('Error fetching resources:', err);
      setError('Failed to load resources. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchResources(currentPage);
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

  const getCategoryBadge = (category?: string) => {
    if (!category) return null;
    
    const categoryColors = {
      Documentation: 'bg-blue-100 text-blue-800',
      Training: 'bg-green-100 text-green-800',
      Template: 'bg-purple-100 text-purple-800',
      Guide: 'bg-yellow-100 text-yellow-800',
    };

    return (
      <span
        className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
          categoryColors[category as keyof typeof categoryColors] || 'bg-gray-100 text-gray-800'
        }`}
      >
        {category}
      </span>
    );
  };

  const columns = [
    {
      key: 'title',
      label: 'Resource',
      render: (resource: Resource) => (
        <div>
          <div className="text-sm font-medium text-gray-900">{resource.title}</div>
          {resource.description && (
            <div className="text-sm text-gray-500 truncate max-w-xs">
              {resource.description}
            </div>
          )}
        </div>
      ),
    },
    {
      key: 'category',
      label: 'Category',
      render: (resource: Resource) => getCategoryBadge(resource.category),
    },
    {
      key: 'file_url',
      label: 'File',
      render: (resource: Resource) => (
        resource.file_url ? (
          <a
            href={resource.file_url}
            target="_blank"
            rel="noopener noreferrer"
            className="text-sm text-blue-600 hover:text-blue-800"
          >
            Download
          </a>
        ) : (
          <span className="text-sm text-gray-500">-</span>
        )
      ),
    },
    {
      key: 'created_at',
      label: 'Created',
      render: (resource: Resource) => (
        <span className="text-sm text-gray-500">{formatDate(resource.created_at)}</span>
      ),
    },
    {
      key: 'actions',
      label: 'Actions',
      render: (resource: Resource) => (
        <div className="flex space-x-2">
          <Button
            variant="secondary"
            size="sm"
            onClick={() => onEdit(resource)}
          >
            Edit
          </Button>
          <Button
            variant="danger"
            size="sm"
            onClick={() => onDelete(resource)}
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
          Resources ({totalCount})
        </h3>
        {onRefresh && (
          <Button variant="secondary" onClick={onRefresh}>
            Refresh
          </Button>
        )}
      </div>

      <Table
        data={resources}
        columns={columns}
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={handlePageChange}
        emptyMessage="No resources found."
      />
    </div>
  );
};

export default ResourceList;