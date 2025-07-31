import React, { useState, useEffect } from 'react';
import { Event } from '../../types/common';
import Button from '../ui/Button';
import Input from '../ui/Input';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';

interface EventFormProps {
  event?: Event | null;
  onSave: (event: Event) => void;
  onCancel: () => void;
}

interface FormData {
  title: string;
  description: string;
  start_date: string;
  end_date: string;
  location: string;
}

const EventForm: React.FC<EventFormProps> = ({
  event,
  onSave,
  onCancel,
}) => {
  const [formData, setFormData] = useState<FormData>({
    title: '',
    description: '',
    start_date: '',
    end_date: '',
    location: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [validationErrors, setValidationErrors] = useState<Record<string, string[]>>({});

  useEffect(() => {
    if (event) {
      setFormData({
        title: event.title,
        description: event.description,
        start_date: event.start_date.slice(0, 16), // Format for datetime-local input
        end_date: event.end_date.slice(0, 16),
        location: event.location || '',
      });
    }
  }, [event]);

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

    if (!formData.title.trim()) {
      errors.title = ['Event title is required'];
    }

    if (!formData.start_date) {
      errors.start_date = ['Start date is required'];
    }

    if (!formData.end_date) {
      errors.end_date = ['End date is required'];
    }

    if (formData.start_date && formData.end_date) {
      const startDate = new Date(formData.start_date);
      const endDate = new Date(formData.end_date);
      
      if (endDate <= startDate) {
        errors.end_date = ['End date must be after start date'];
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

      // Mock API call - replace with actual API when available
      const mockEvent: Event = {
        id: event?.id || Date.now().toString(),
        title: formData.title,
        description: formData.description,
        start_date: new Date(formData.start_date).toISOString(),
        end_date: new Date(formData.end_date).toISOString(),
        location: formData.location || undefined,
        created_at: event?.created_at || new Date().toISOString(),
        updated_at: new Date().toISOString(),
      };

      onSave(mockEvent);
    } catch (err: any) {
      console.error('Error saving event:', err);
      
      if (err.response?.data?.details) {
        setValidationErrors(err.response.data.details);
      } else {
        setError(err.response?.data?.message || 'Failed to save event. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-white shadow rounded-lg">
      <div className="px-4 py-5 sm:p-6">
        <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
          {event ? 'Edit Event' : 'Create New Event'}
        </h3>

        {error && <ErrorMessage message={error} className="mb-4" />}

        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <Input
              label="Event Title"
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
              placeholder="Event description..."
            />
            {validationErrors.description && (
              <p className="mt-1 text-sm text-red-600">{validationErrors.description[0]}</p>
            )}
          </div>

          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <label htmlFor="start_date" className="block text-sm font-medium text-gray-700">
                Start Date & Time
              </label>
              <input
                type="datetime-local"
                id="start_date"
                name="start_date"
                value={formData.start_date}
                onChange={handleInputChange}
                className={`mt-1 block w-full border rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 ${
                  validationErrors.start_date ? 'border-red-300' : 'border-gray-300'
                }`}
                required
              />
              {validationErrors.start_date && (
                <p className="mt-1 text-sm text-red-600">{validationErrors.start_date[0]}</p>
              )}
            </div>

            <div>
              <label htmlFor="end_date" className="block text-sm font-medium text-gray-700">
                End Date & Time
              </label>
              <input
                type="datetime-local"
                id="end_date"
                name="end_date"
                value={formData.end_date}
                onChange={handleInputChange}
                className={`mt-1 block w-full border rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 ${
                  validationErrors.end_date ? 'border-red-300' : 'border-gray-300'
                }`}
                required
              />
              {validationErrors.end_date && (
                <p className="mt-1 text-sm text-red-600">{validationErrors.end_date[0]}</p>
              )}
            </div>
          </div>

          <div>
            <Input
              label="Location"
              name="location"
              value={formData.location}
              onChange={handleInputChange}
              error={validationErrors.location?.[0]}
              placeholder="Event location (city, country)"
            />
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
                event ? 'Update Event' : 'Create Event'
              )}
            </Button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default EventForm;