import React, { useState, useEffect } from 'react';
import { BlogPost, PaginatedResponse } from '../../types/common';
import { blogAPI } from '../../services/api/endpoints';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';
import Button from '../ui/Button';
import Table from '../ui/Table';

interface BlogPostListProps {
  onEdit: (post: BlogPost) => void;
  onDelete: (post: BlogPost) => void;
  onRefresh?: () => void;
  refreshTrigger?: number;
}

const BlogPostList: React.FC<BlogPostListProps> = ({
  onEdit,
  onDelete,
  onRefresh,
  refreshTrigger,
}) => {
  const [posts, setPosts] = useState<BlogPost[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [totalCount, setTotalCount] = useState(0);

  const fetchPosts = async (page: number = 1) => {
    try {
      setLoading(true);
      setError(null);
      
      const response = await blogAPI.getPosts({
        page,
        page_size: 10,
        ordering: '-created_at',
      });

      const data: PaginatedResponse<BlogPost> = response.data;
      setPosts(data.results);
      setTotalCount(data.count);
      setTotalPages(Math.ceil(data.count / 10));
    } catch (err: any) {
      console.error('Error fetching blog posts:', err);
      setError('Failed to load blog posts. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchPosts(currentPage);
  }, [currentPage, refreshTrigger]);

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
  };

  const getStatusBadge = (status: string) => {
    const statusColors = {
      published: 'bg-green-100 text-green-800',
      draft: 'bg-yellow-100 text-yellow-800',
      archived: 'bg-gray-100 text-gray-800',
    };

    return (
      <span
        className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
          statusColors[status as keyof typeof statusColors] || 'bg-gray-100 text-gray-800'
        }`}
      >
        {status.charAt(0).toUpperCase() + status.slice(1)}
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
      key: 'title',
      label: 'Title',
      render: (post: BlogPost) => (
        <div className="max-w-xs truncate">
          <div className="text-sm font-medium text-gray-900">{post.title}</div>
          {post.excerpt && (
            <div className="text-sm text-gray-500 truncate">{post.excerpt}</div>
          )}
        </div>
      ),
    },
    {
      key: 'status',
      label: 'Status',
      render: (post: BlogPost) => getStatusBadge(post.status),
    },
    {
      key: 'created_at',
      label: 'Created',
      render: (post: BlogPost) => (
        <span className="text-sm text-gray-500">{formatDate(post.created_at)}</span>
      ),
    },
    {
      key: 'published_at',
      label: 'Published',
      render: (post: BlogPost) => (
        <span className="text-sm text-gray-500">
          {post.published_at ? formatDate(post.published_at) : '-'}
        </span>
      ),
    },
    {
      key: 'actions',
      label: 'Actions',
      render: (post: BlogPost) => (
        <div className="flex space-x-2">
          <Button
            variant="secondary"
            size="sm"
            onClick={() => onEdit(post)}
          >
            Edit
          </Button>
          <Button
            variant="danger"
            size="sm"
            onClick={() => onDelete(post)}
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
          Blog Posts ({totalCount})
        </h3>
        {onRefresh && (
          <Button variant="secondary" onClick={onRefresh}>
            Refresh
          </Button>
        )}
      </div>

      <Table
        data={posts}
        columns={columns}
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={handlePageChange}
        emptyMessage="No blog posts found."
      />
    </div>
  );
};

export default BlogPostList;