import React, { useState } from 'react';
import Layout from '../components/layout/Layout';
import { OrganizationList, OrganizationForm } from '../components/organizations';
import { Organization } from '../types/common';
import Button from '../components/ui/Button';

const OrganizationManagement: React.FC = () => {
  const [showForm, setShowForm] = useState(false);
  const [editingOrganization, setEditingOrganization] = useState<Organization | null>(null);
  const [refreshTrigger, setRefreshTrigger] = useState(0);

  const handleCreateNew = () => {
    setEditingOrganization(null);
    setShowForm(true);
  };

  const handleEdit = (organization: Organization) => {
    setEditingOrganization(organization);
    setShowForm(true);
  };

  const handleDelete = async (organization: Organization) => {
    if (window.confirm(`Are you sure you want to delete "${organization.name}"?`)) {
      try {
        // Delete logic will be implemented when the API is available
        console.log('Deleting organization:', organization.id);
        setRefreshTrigger(prev => prev + 1);
      } catch (error) {
        console.error('Error deleting organization:', error);
      }
    }
  };

  const handleSave = (organization: Organization) => {
    setShowForm(false);
    setEditingOrganization(null);
    setRefreshTrigger(prev => prev + 1);
  };

  const handleCancel = () => {
    setShowForm(false);
    setEditingOrganization(null);
  };

  return (
    <Layout>
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-semibold text-gray-900">Organization Management</h1>
            <p className="mt-1 text-sm text-gray-600">
              Manage organizations and their information
            </p>
          </div>
          {!showForm && (
            <Button onClick={handleCreateNew}>
              Create New Organization
            </Button>
          )}
        </div>

        {showForm ? (
          <OrganizationForm
            organization={editingOrganization}
            onSave={handleSave}
            onCancel={handleCancel}
          />
        ) : (
          <div className="bg-white shadow rounded-lg">
            <div className="px-4 py-5 sm:p-6">
              <OrganizationList
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

export default OrganizationManagement;