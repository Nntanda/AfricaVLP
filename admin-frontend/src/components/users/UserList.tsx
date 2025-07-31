import React, { useState, useEffect } from 'react';
import { User, PaginatedResponse } from '../../types/common';
import { adminAPI } from '../../services/api/endpoints';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';
import Button from '../ui/Button';
import Table from '../ui/Table';

interface UserListProps {
  onEdit: (user: User) => void;
  onDelete: (user: User) => void;
  onRefresh?: () => void;
  refreshTrigger?: number;
}

const UserList: React.FC<UserListProps> = ({
  onEdit,
  onDelete,
  onRefresh,
  refreshTrigger,
}) => {
  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [totalCount, setTotalCount] = useState(0);

  const fetchUsers = async (page: number = 1) => {
    try {
      setLoading(true);
      setError(null);
      
      const response = await adminAPI.getUsers({
        page,
        page_size: 10,
        ordering: '-created_at',
      });

      const data: PaginatedResponse<User> = response.data;
      setUsers(data.results);
      setTotalCount(data.count);
      setTotalPages(Math.ceil(data.count / 10));
    } catch (err: any) {
      console.error('Error fetching users:', err);
      setError('Failed to load users. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchUsers(currentPage);
  }, [currentPage, refreshTrigger]);

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
  };

  const getRoleBadge = (role: string) => {
    const roleColors = {
      super_admin: 'bg-red-100 text-red-800',
      admin: 'bg-blue-100 text-blue-800',
      user: 'bg-green-100 text-green-800',
    };

    return (
      <span
        className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
          roleColors[role as keyof typeof roleColors] || 'bg-gray-100 text-gray-800'
        }`}
      >
        {role.replace('_', ' ').toUpperCase()}
      </span>
    );
  };

  const getStatusBadge = (isActive: boolean) => {
    return (
      <span
        className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
          isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
        }`}
      >
        {isActive ? 'Active' : 'Inactive'}
      </span>
    );
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
      label: 'Name',
      render: (user: User) => (
        <div>
          <div className="text-sm font-medium text-gray-900">
            {user.first_name} {user.last_name}
          </div>
          <div className="text-sm text-gray-500">@{user.username}</div>
        </div>
      ),
    },
    {
      key: 'email',
      label: 'Email',
      render: (user: User) => (
        <span className="text-sm text-gray-900">{user.email}</span>
      ),
    },
    {
      key: 'role',
      label: 'Role',
      render: (user: User) => getRoleBadge(user.role),
    },
    {
      key: 'status',
      label: 'Status',
      render: (user: User) => getStatusBadge(user.is_active),
    },
    {
      key: 'created_at',
      label: 'Created',
      render: (user: User) => (
        <span className="text-sm text-gray-500">{formatDate(user.created_at)}</span>
      ),
    },
    {
      key: 'actions',
      label: 'Actions',
      render: (user: User) => (
        <div className="flex space-x-2">
          <Button
            variant="secondary"
            size="sm"
            onClick={() => onEdit(user)}
          >
            Edit
          </Button>
          <Button
            variant="danger"
            size="sm"
            onClick={() => onDelete(user)}
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
          Users ({totalCount})
        </h3>
        {onRefresh && (
          <Button variant="secondary" onClick={onRefresh}>
            Refresh
          </Button>
        )}
      </div>

      <Table
        data={users}
        columns={columns}
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={handlePageChange}
        emptyMessage="No users found."
      />
    </div>
  );
};

export default UserList;