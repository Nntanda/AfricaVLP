import React, { useState, useEffect } from 'react';
import { BlogPost } from '../../types/common';
import { blogAPI } from '../../services/api/endpoints';
import Button from '../ui/Button';
import Input from '../ui/Input';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';

interface BlogPostFormProps {
  post?: BlogPost | null;
  onSave: (post: BlogPost) => void;
  onCancel: () => void;
}

interface FormData {
  title: string;
  content: string;
  excerpt: string;
  featured_image: string;
  status: 'draft' | 'published' | 'archived';
}

const BlogPostForm: React.FC<BlogPostFormProps> = ({
  post,
  onSave,
  onCancel,
}) => {
  const [formData, setFormData] = useState<FormData>({
    title: '',
    content: '',
    excerpt: '',
    featured_image: '',
    status: 'draft',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [validationErrors, setValidationErrors] = useState<Record<string, string[]>>({});

  useEffect(() => {
    if (post) {
      setFormData({
        title: post.title,
        content: post.content,
        excerpt: post.excerpt || '',
        featured_image: post.featured_image || '',
        status: post.status,
      });
    }
  }, [post]);

  const handleInputChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>
  ) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value,
    }));
    
    // Clear validation error for this field
    if (validationErrors[name]) {
      setValidationErrors(prev => ({
        ...prev,
        [name]: [],
      }));
    }
  };

  const validateForm = (): boolean => {
    const errors: Record<string, string[]> = {};

    if (!formData.title.trim()) {
      errors.title = ['Title is required'];
    }

    if (!formData.content.trim()) {
      errors.content = ['Content is required'];
    }

    setValidationErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!validateForm()) {
      return;
    }

    try {
      setLoading(true);
      setError(null);

      let response;
      if (post) {
        // Update existing post
        response = await blogAPI.updatePost(post.id, formData);
      } else {
        // Create new post
        response = await blogAPI.createPost(formData);
      }

      onSave(response.data);
    } catch (err: any) {
      console.error('Error saving blog post:', err);
      
      if (err.response?.data?.details) {
        setValidationErrors(err.response.data.details);
      } else {
        setError(err.response?.data?.message || 'Failed to save blog post. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-white shadow rounded-lg">
      <div className="px-4 py-5 sm:p-6">
        <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
          {post ? 'Edit Blog Post' : 'Create New Blog Post'}
        </h3>

        {error && <ErrorMessage message={error} className="mb-4" />}

        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <Input
              label="Title"
              name="title"
              value={formData.title}
              onChange={handleInputChange}
              error={validationErrors.title?.[0]}
              required
            />
          </div>

          <div>
            <label htmlFor="content" className="block text-sm font-medium text-gray-700">
              Content
            </label>
            <textarea
              id="content"
              name="content"
              rows={10}
              value={formData.content}
              onChange={handleInputChange}
              className={`mt-1 block w-full border rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 ${
                validationErrors.content ? 'border-red-300' : 'border-gray-300'
              }`}
              required
            />
            {validationErrors.content && (
              <p className="mt-1 text-sm text-red-600">{validationErrors.content[0]}</p>
            )}
          </div>

          <div>
            <Input
              label="Excerpt"
              name="excerpt"
              value={formData.excerpt}
              onChange={handleInputChange}
              error={validationErrors.excerpt?.[0]}
              placeholder="Brief description of the post..."
            />
          </div>

          <div>
            <Input
              label="Featured Image URL"
              name="featured_image"
              type="url"
              value={formData.featured_image}
              onChange={handleInputChange}
              error={validationErrors.featured_image?.[0]}
              placeholder="https://example.com/image.jpg"
            />
          </div>

          <div>
            <label htmlFor="status" className="block text-sm font-medium text-gray-700">
              Status
            </label>
            <select
              id="status"
              name="status"
              value={formData.status}
              onChange={handleInputChange}
              className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="draft">Draft</option>
              <option value="published">Published</option>
              <option value="archived">Archived</option>
            </select>
          </div>

          <div className="flex justify-end space-x-3">
            <Button
              type="button"
              variant="secondary"
              onClick={onCancel}
              disabled={loading}
            >
              Cancel
            </Button>
            <Button
              type="submit"
              variant="primary"
              disabled={loading}
            >
              {loading ? (
                <>
                  <LoadingSpinner size="sm" />
                  <span className="ml-2">Saving...</span>
                </>
              ) : (
                post ? 'Update Post' : 'Create Post'
              )}
            </Button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default BlogPostForm;