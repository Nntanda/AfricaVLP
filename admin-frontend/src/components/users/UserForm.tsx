import React, { useState, useEffect } from 'react';
import { User } from '../../types/common';
import Button from '../ui/Button';
import Input from '../ui/Input';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';

interface UserFormProps {
  user?: User | null;
  onSave: (user: User) => void;
  onCancel: () => void;
}

interface FormData {
  username: string;
  email: string;
  first_name: string;
  last_name: string;
  role: 'super_admin' | 'admin' | 'user';
  is_active: boolean;
  password?: string;
  confirm_password?: string;
}

const UserForm: React.FC<UserFormProps> = ({
  user,
  onSave,
  onCancel,
}) => {
  const [formData, setFormData] = useState<FormData>({
    username: '',
    email: '',
    first_name: '',
    last_name: '',
    role: 'user',
    is_active: true,
    password: '',
    confirm_password: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [validationErrors, setValidationErrors] = useState<Record<string, string[]>>({});

  useEffect(() => {
    if (user) {
      setFormData({
        username: user.username,
        email: user.email,
        first_name: user.first_name,
        last_name: user.last_name,
        role: user.role,
        is_active: user.is_active,
        password: '',
        confirm_password: '',
      });
    }
  }, [user]);

  const handleInputChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
  ) => {
    const { name, value, type } = e.target;
    const checked = (e.target as HTMLInputElement).checked;
    
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value,
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

    if (!formData.username.trim()) {
      errors.username = ['Username is required'];
    }

    if (!formData.email.trim()) {
      errors.email = ['Email is required'];
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      errors.email = ['Email is invalid'];
    }

    if (!formData.first_name.trim()) {
      errors.first_name = ['First name is required'];
    }

    if (!formData.last_name.trim()) {
      errors.last_name = ['Last name is required'];
    }

    // Password validation for new users
    if (!user) {
      if (!formData.password) {
        errors.password = ['Password is required'];
      } else if (formData.password.length < 8) {
        errors.password = ['Password must be at least 8 characters'];
      }

      if (formData.password !== formData.confirm_password) {
        errors.confirm_password = ['Passwords do not match'];
      }
    } else if (formData.password) {
      // Password validation for existing users (only if password is provided)
      if (formData.password.length < 8) {
        errors.password = ['Password must be at least 8 characters'];
      }

      if (formData.password !== formData.confirm_password) {
        errors.confirm_password = ['Passwords do not match'];
      }
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

      // Prepare data for API
      const submitData = { ...formData };
      delete submitData.confirm_password;
      
      // Don't send empty password for updates
      if (user && !formData.password) {
        delete submitData.password;
      }

      // Mock API call - replace with actual API when available
      const mockUser: User = {
        id: user?.id || Date.now().toString(),
        username: submitData.username,
        email: submitData.email,
        first_name: submitData.first_name,
        last_name: submitData.last_name,
        role: submitData.role,
        is_active: submitData.is_active,
        created_at: user?.created_at || new Date().toISOString(),
        updated_at: new Date().toISOString(),
      };

      onSave(mockUser);
    } catch (err: any) {
      console.error('Error saving user:', err);
      
      if (err.response?.data?.details) {
        setValidationErrors(err.response.data.details);
      } else {
        setError(err.response?.data?.message || 'Failed to save user. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-white shadow rounded-lg">
      <div className="px-4 py-5 sm:p-6">
        <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
          {user ? 'Edit User' : 'Create New User'}
        </h3>

        {error && <ErrorMessage message={error} className="mb-4" />}

        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <Input
                label="Username"
                name="username"
                value={formData.username}
                onChange={handleInputChange}
                error={validationErrors.username?.[0]}
                required
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
                required
              />
            </div>

            <div>
              <Input
                label="First Name"
                name="first_name"
                value={formData.first_name}
                onChange={handleInputChange}
                error={validationErrors.first_name?.[0]}
                required
              />
            </div>

            <div>
              <Input
                label="Last Name"
                name="last_name"
                value={formData.last_name}
                onChange={handleInputChange}
                error={validationErrors.last_name?.[0]}
                required
              />
            </div>
          </div>

          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <label htmlFor="role" className="block text-sm font-medium text-gray-700">
                Role
              </label>
              <select
                id="role"
                name="role"
                value={formData.role}
                onChange={handleInputChange}
                className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="user">User</option>
                <option value="admin">Admin</option>
                <option value="super_admin">Super Admin</option>
              </select>
            </div>

            <div className="flex items-center">
              <input
                id="is_active"
                name="is_active"
                type="checkbox"
                checked={formData.is_active}
                onChange={handleInputChange}
                className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              />
              <label htmlFor="is_active" className="ml-2 block text-sm text-gray-900">
                Active User
              </label>
            </div>
          </div>

          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <Input
                label={user ? "New Password (leave blank to keep current)" : "Password"}
                name="password"
                type="password"
                value={formData.password}
                onChange={handleInputChange}
                error={validationErrors.password?.[0]}
                required={!user}
              />
            </div>

            <div>
              <Input
                label="Confirm Password"
                name="confirm_password"
                type="password"
                value={formData.confirm_password}
                onChange={handleInputChange}
                error={validationErrors.confirm_password?.[0]}
                required={!user || !!formData.password}
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
                user ? 'Update User' : 'Create User'
              )}
            </Button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default UserForm;