import React, { useState } from 'react';
import { useAuthContext } from '../../context/AuthContext';
import Button from '../ui/Button';
import Input from '../ui/Input';
import ErrorMessage from '../common/ErrorMessage';

const UserProfile: React.FC = () => {
  const { user, logout } = useAuthContext();
  const [isEditing, setIsEditing] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [formData, setFormData] = useState({
    first_name: user?.first_name || '',
    last_name: user?.last_name || '',
    email: user?.email || '',
  });

  if (!user) {
    return null;
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      // TODO: Implement profile update API call
      console.log('Updating profile:', formData);
      await new Promise(resolve => setTimeout(resolve, 1000)); // Mock delay
      setIsEditing(false);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Failed to update profile');
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  const handleLogout = async () => {
    try {
      await logout();
    } catch (err) {
      console.error('Logout error:', err);
    }
  };

  return (
    <div className="bg-white shadow rounded-lg p-6">
      <div className="flex items-center justify-between mb-6">
        <h2 className="text-2xl font-bold text-gray-900">User Profile</h2>
        <div className="flex space-x-2">
          {!isEditing && (
            <Button
              variant="secondary"
              onClick={() => setIsEditing(true)}
            >
              Edit Profile
            </Button>
          )}
          <Button
            variant="danger"
            onClick={handleLogout}
          >
            Logout
          </Button>
        </div>
      </div>

      {error && (
        <ErrorMessage
          message={error}
          title="Profile Error"
          onRetry={() => setError(null)}
        />
      )}

      <form onSubmit={handleSubmit}>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Username
            </label>
            <div className="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500">
              {user.username}
            </div>
            <p className="text-xs text-gray-500 mt-1">Username cannot be changed</p>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Role
            </label>
            <div className="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500">
              {user.role === 'super_admin' ? 'Super Admin' : 'Admin'}
            </div>
          </div>

          <Input
            label="First Name"
            name="first_name"
            type="text"
            value={formData.first_name}
            onChange={handleChange}
            disabled={!isEditing || loading}
          />

          <Input
            label="Last Name"
            name="last_name"
            type="text"
            value={formData.last_name}
            onChange={handleChange}
            disabled={!isEditing || loading}
          />

          <div className="md:col-span-2">
            <Input
              label="Email"
              name="email"
              type="email"
              value={formData.email}
              onChange={handleChange}
              disabled={!isEditing || loading}
            />
          </div>
        </div>

        {isEditing && (
          <div className="flex justify-end space-x-3 mt-6">
            <Button
              type="button"
              variant="secondary"
              onClick={() => {
                setIsEditing(false);
                setFormData({
                  first_name: user.first_name || '',
                  last_name: user.last_name || '',
                  email: user.email || '',
                });
                setError(null);
              }}
              disabled={loading}
            >
              Cancel
            </Button>
            <Button
              type="submit"
              variant="primary"
              loading={loading}
            >
              Save Changes
            </Button>
          </div>
        )}
      </form>
    </div>
  );
};

export default UserProfile;