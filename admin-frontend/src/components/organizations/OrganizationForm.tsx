import React, { useState, useEffect } from 'react';
import { Organization } from '../../types/common';
import Button from '../ui/Button';
import Input from '../ui/Input';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';

interface OrganizationFormProps {
  organization?: Organization | null;
  onSave: (organization: Organization) => void;
  onCancel: () => void;
}

interface FormData {
  name: string;
  description: string;
  website: string;
  email: string;
  phone: string;
  address: string;
}

const OrganizationForm: React.FC<OrganizationFormProps> = ({
  organization,
  onSave,
  onCancel,
}) => {
  const [formData, setFormData] = useState<FormData>({
    name: '',
    description: '',
    website: '',
    email: '',
    phone: '',
    address: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [validationErrors, setValidationErrors] = useState<Record<string, string[]>>({});

  useEffect(() => {
    if (organization) {
      setFormData({
        name: organization.name,
        description: organization.description || '',
        website: organization.website || '',
        email: organization.email || '',
        phone: organization.phone || '',
        address: organization.address || '',
      });
    }
  }, [organization]);

  const handleInputChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>
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

    if (!formData.name.trim()) {
      errors.name = ['Organization name is required'];
    }

    if (formData.email && !/\S+@\S+\.\S+/.test(formData.email)) {
      errors.email = ['Email is invalid'];
    }

    if (formData.website && !formData.website.match(/^https?:\/\/.+/)) {
      errors.website = ['Website must be a valid URL starting with http:// or https://'];
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
      const mockOrganization: Organization = {
        id: organization?.id || Date.now().toString(),
        name: formData.name,
        description: formData.description || undefined,
        website: formData.website || undefined,
        email: formData.email || undefined,
        phone: formData.phone || undefined,
        address: formData.address || undefined,
        created_at: organization?.created_at || new Date().toISOString(),
        updated_at: new Date().toISOString(),
      };

      onSave(mockOrganization);
    } catch (err: any) {
      console.error('Error saving organization:', err);
      
      if (err.response?.data?.details) {
        setValidationErrors(err.response.data.details);
      } else {
        setError(err.response?.data?.message || 'Failed to save organization. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-white shadow rounded-lg">
      <div className="px-4 py-5 sm:p-6">
        <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
          {organization ? 'Edit Organization' : 'Create New Organization'}
        </h3>

        {error && <ErrorMessage message={error} className="mb-4" />}

        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <Input
              label="Organization Name"
              name="name"
              value={formData.name}
              onChange={handleInputChange}
              error={validationErrors.name?.[0]}
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
              placeholder="Brief description of the organization..."
            />
            {validationErrors.description && (
              <p className="mt-1 text-sm text-red-600">{validationErrors.description[0]}</p>
            )}
          </div>

          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <Input
                label="Website"
                name="website"
                type="url"
                value={formData.website}
                onChange={handleInputChange}
                error={validationErrors.website?.[0]}
                placeholder="https://example.com"
              />
            </div>

            <div>
              <Input
                label="Email"
                name="email"
                type="email"
                value={formData.email}
                onChange={handleInputChange}
                error={validationErrors.email?.[0]}
                placeholder="contact@organization.com"
              />
            </div>
          </div>

          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <Input
                label="Phone"
                name="phone"
                type="tel"
                value={formData.phone}
                onChange={handleInputChange}
                error={validationErrors.phone?.[0]}
                placeholder="+1 (555) 123-4567"
              />
            </div>

            <div>
              <Input
                label="Address"
                name="address"
                value={formData.address}
                onChange={handleInputChange}
                error={validationErrors.address?.[0]}
                placeholder="Street address, City, Country"
              />
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
                organization ? 'Update Organization' : 'Create Organization'
              )}
            </Button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default OrganizationForm;