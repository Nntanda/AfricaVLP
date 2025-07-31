import React, { useState } from 'react';
import Layout from '../components/layout/Layout';
import { EventList, EventForm } from '../components/events';
import { Event } from '../types/common';
import Button from '../components/ui/Button';

const EventManagement: React.FC = () => {
  const [showForm, setShowForm] = useState(false);
  const [editingEvent, setEditingEvent] = useState<Event | null>(null);
  const [refreshTrigger, setRefreshTrigger] = useState(0);

  const handleCreateNew = () => {
    setEditingEvent(null);
    setShowForm(true);
  };

  const handleEdit = (event: Event) => {
    setEditingEvent(event);
    setShowForm(true);
  };

  const handleDelete = async (event: Event) => {
    if (window.confirm(`Are you sure you want to delete "${event.title}"?`)) {
      try {
        // Delete logic will be implemented when the API is available
        console.log('Deleting event:', event.id);
        setRefreshTrigger(prev => prev + 1);
      } catch (error) {
        console.error('Error deleting event:', error);
      }
    }
  };

  const handleSave = (event: Event) => {
    setShowForm(false);
    setEditingEvent(null);
    setRefreshTrigger(prev => prev + 1);
  };

  const handleCancel = () => {
    setShowForm(false);
    setEditingEvent(null);
  };

  return (
    <Layout>
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-semibold text-gray-900">Event Management</h1>
            <p className="mt-1 text-sm text-gray-600">
              Create and manage events
            </p>
          </div>
          {!showForm && (
            <Button onClick={handleCreateNew}>
              Create New Event
            </Button>
          )}
        </div>

        {showForm ? (
          <EventForm
            event={editingEvent}
            onSave={handleSave}
            onCancel={handleCancel}
          />
        ) : (
          <div className="bg-white shadow rounded-lg">
            <div className="px-4 py-5 sm:p-6">
              <EventList
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

export default EventManagement;