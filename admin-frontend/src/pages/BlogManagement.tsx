import React, { useState } from 'react';
import Layout from '../components/layout/Layout';
import { BlogPostList, BlogPostForm } from '../components/blog';
import { BlogPost } from '../types/common';
import Button from '../components/ui/Button';

const BlogManagement: React.FC = () => {
  const [showForm, setShowForm] = useState(false);
  const [editingPost, setEditingPost] = useState<BlogPost | null>(null);
  const [refreshTrigger, setRefreshTrigger] = useState(0);

  const handleCreateNew = () => {
    setEditingPost(null);
    setShowForm(true);
  };

  const handleEdit = (post: BlogPost) => {
    setEditingPost(post);
    setShowForm(true);
  };

  const handleDelete = async (post: BlogPost) => {
    if (window.confirm(`Are you sure you want to delete "${post.title}"?`)) {
      try {
        // Delete logic will be implemented when the API is available
        console.log('Deleting post:', post.id);
        setRefreshTrigger(prev => prev + 1);
      } catch (error) {
        console.error('Error deleting post:', error);
      }
    }
  };

  const handleSave = (post: BlogPost) => {
    setShowForm(false);
    setEditingPost(null);
    setRefreshTrigger(prev => prev + 1);
  };

  const handleCancel = () => {
    setShowForm(false);
    setEditingPost(null);
  };

  return (
    <Layout>
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-semibold text-gray-900">Blog Management</h1>
            <p className="mt-1 text-sm text-gray-600">
              Create, edit, and manage blog posts
            </p>
          </div>
          {!showForm && (
            <Button onClick={handleCreateNew}>
              Create New Post
            </Button>
          )}
        </div>

        {showForm ? (
          <BlogPostForm
            post={editingPost}
            onSave={handleSave}
            onCancel={handleCancel}
          />
        ) : (
          <div className="bg-white shadow rounded-lg">
            <div className="px-4 py-5 sm:p-6">
              <BlogPostList
                onEdit={handleEdit}
                onDelete={handleDelete}
                refreshTrigger={refreshTrigger}
              />
            </div>
          </div>
        )}
      </div>
    </Layout>
  );
};

export default BlogManagement;