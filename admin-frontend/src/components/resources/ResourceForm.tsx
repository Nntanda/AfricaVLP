import React, { useState, useEffect } from 'react';
import { Resource } from '../../types/common';
import Button from '../ui/Button';
import Input from '../ui/Input';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';

interface ResourceFormProps {
  resource?: Resource | null;
  onSave: (resource: Resource) => void;
  onCancel: () => void;
}

interface FormData {
  title: string;
  description: string;
  file_url: string;
  category: string;
}

const ResourceForm: React.FC<ResourceFormProps> = ({
  resource,
  onSave,
  onCancel,
}) => {
  const [formData, setFormData] = useState<FormData>({
    title: '',
    description: '',
    file_url: '',
    category: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [validationErrors, setValidationErrors] = useState<Record<string, string[]>>({});

  const categories = [
    'Documentation',
    'Training',
    'Template',
    'Guide',
    'Policy',
    'Report',
    'Other',
  ];

  useEffect(() => {
    if (resource) {
      setFormData({
        title: resource.title,
        description: resource.description || '',
        file_url: resource.file_url || '',
        category: resource.category || '',
      });
    }
  }, [resource]);

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
      errors.title = ['Resource title is required'];
    }

    if (formData.file_url && !formData.file_url.match(/^https?:\/\/.+/)) {
      errors.file_url = ['File URL must be a valid URL starting with http:// or https://'];
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

      // Mock API call - replace with actual API when available
      const mockResource: Resource = {
        id: resource?.id || Date.now().toString(),
        title: formData.title,
        description: formData.description || undefined,
        file_url: formData.file_url || undefined,
        category: formData.category || undefined,
        created_at: resource?.created_at || new Date().toISOString(),
        updated_at: new Date().toISOString(),
      };

      onSave(mockResource);
    } catch (err: any) {
      console.error('Error saving resource:', err);
      
      if (err.response?.data?.details) {
        setValidationErrors(err.response.data.details);
      } else {
        setError(err.response?.data?.message || 'Failed to save resource. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-white shadow rounded-lg">
      <div className="px-4 py-5 sm:p-6">
        <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
          {resource ? 'Edit Resource' : 'Create New Resource'}
        </h3>

        {error && <ErrorMessage message={error} className="mb-4" />}

        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <Input
              label="Resource Title"
              name="title"
              value={formData.title}
              onChange={handleInputChange}
              error={validationErrors.title?.[0]}
              required
            />
          </div>

          <div>
            <label htmlFor="description" className="block text-sm font-medium text-gray-700">
              Description
            </label>
            <textarea
              id="description"
              name="description"
              rows={4}
              value={formData.description}
              onChange={handleInputChange}
              className={`mt-1 block w-full border rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 ${
                validationErrors.description ? 'border-red-300' : 'border-gray-300'
              }`}
              placeholder="Resource description..."
            />
            {validationErrors.description && (
              <p className="mt-1 text-sm text-red-600">{validationErrors.description[0]}</p>
            )}
          </div>

          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <Input
                label="File URL"
                name="file_url"
                type="url"
                value={formData.file_url}
                onChange={handleInputChange}
                error={validationErrors.file_url?.[0]}
                placeholder="https://example.com/file.pdf"
              />
            </div>

            <div>
              <label htmlFor="category" className="block text-sm font-medium text-gray-700">
                Category
              </label>
              <select
                id="category"
                name="category"
                value={formData.category}
                onChange={handleInputChange}
                className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">Select a category</option>
                {categories.map(category => (
                  <option key={category} value={category}>
                    {category}
                  </option>
                ))}
              </select>
            </div>
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
                resource ? 'Update Resource' : 'Create Resource'
              )}
            </Button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ResourceForm;