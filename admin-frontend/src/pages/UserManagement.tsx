import React, { useState } from 'react';
import Layout from '../components/layout/Layout';
import { UserList, UserForm } from '../components/users';
import { User } from '../types/common';
import Button from '../components/ui/Button';

const UserManagement: React.FC = () => {
  const [showForm, setShowForm] = useState(false);
  const [editingUser, setEditingUser] = useState<User | null>(null);
  const [refreshTrigger, setRefreshTrigger] = useState(0);

  const handleCreateNew = () => {
    setEditingUser(null);
    setShowForm(true);
  };

  const handleEdit = (user: User) => {
    setEditingUser(user);
    setShowForm(true);
  };

  const handleDelete = async (user: User) => {
    if (window.confirm(`Are you sure you want to delete user "${user.username}"?`)) {
      try {
        // Delete logic will be implemented when the API is available
        console.log('Deleting user:', user.id);
        setRefreshTrigger(prev => prev + 1);
      } catch (error) {
        console.error('Error deleting user:', error);
      }
    }
  };

  const handleSave = (user: User) => {
    setShowForm(false);
    setEditingUser(null);
    setRefreshTrigger(prev => prev + 1);
  };

  const handleCancel = () => {
    setShowForm(false);
    setEditingUser(null);
  };

  return (
    <Layout>
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-semibold text-gray-900">User Management</h1>
            <p className="mt-1 text-sm text-gray-600">
              Manage users and their roles
            </p>
          </div>
          {!showForm && (
            <Button onClick={handleCreateNew}>
              Create New User
            </Button>
          )}
        </div>

        {showForm ? (
          <UserForm
            user={editingUser}
            onSave={handleSave}
            onCancel={handleCancel}
          />
        ) : (
          <div className="bg-white shadow rounded-lg">
            <div className="px-4 py-5 sm:p-6">
              <UserList
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

export default UserManagement;