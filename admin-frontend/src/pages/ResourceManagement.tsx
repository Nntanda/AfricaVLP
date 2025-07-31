import React, { useState } from 'react';
import Layout from '../components/layout/Layout';
import { ResourceList, ResourceForm } from '../components/resources';
import { Resource } from '../types/common';
import Button from '../components/ui/Button';

const ResourceManagement: React.FC = () => {
  const [showForm, setShowForm] = useState(false);
  const [editingResource, setEditingResource] = useState<Resource | null>(null);
  const [refreshTrigger, setRefreshTrigger] = useState(0);

  const handleCreateNew = () => {
    setEditingResource(null);
    setShowForm(true);
  };

  const handleEdit = (resource: Resource) => {
    setEditingResource(resource);
    setShowForm(true);
  };

  const handleDelete = async (resource: Resource) => {
    if (window.confirm(`Are you sure you want to delete "${resource.title}"?`)) {
      try {
        // Delete logic will be implemented when the API is available
        console.log('Deleting resource:', resource.id);
        setRefreshTrigger(prev => prev + 1);
      } catch (error) {
        console.error('Error deleting resource:', error);
      }
    }
  };

  const handleSave = (resource: Resource) => {
    setShowForm(false);
    setEditingResource(null);
    setRefreshTrigger(prev => prev + 1);
  };

  const handleCancel = () => {
    setShowForm(false);
    setEditingResource(null);
  };

  return (
    <Layout>
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-semibold text-gray-900">Resource Management</h1>
            <p className="mt-1 text-sm text-gray-600">
              Manage resources and documents
            </p>
          </div>
          {!showForm && (
            <Button onClick={handleCreateNew}>
              Create New Resource
            </Button>
          )}
        </div>

        {showForm ? (
          <ResourceForm
            resource={editingResource}
            onSave={handleSave}
            onCancel={handleCancel}
          />
        ) : (
          <div className="bg-white shadow rounded-lg">
            <div className="px-4 py-5 sm:p-6">
              <ResourceList
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

export default ResourceManagement;