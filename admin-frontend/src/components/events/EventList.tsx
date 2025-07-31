import React, { useState, useEffect } from 'react';
import { Event, PaginatedResponse } from '../../types/common';
import LoadingSpinner from '../common/LoadingSpinner';
import ErrorMessage from '../common/ErrorMessage';
import Button from '../ui/Button';
import Table from '../ui/Table';

interface EventListProps {
  onEdit: (event: Event) => void;
  onDelete: (event: Event) => void;
  onRefresh?: () => void;
  refreshTrigger?: number;
}

const EventList: React.FC<EventListProps> = ({
  onEdit,
  onDelete,
  onRefresh,
  refreshTrigger,
}) => {
  const [events, setEvents] = useState<Event[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [totalCount, setTotalCount] = useState(0);

  const fetchEvents = async (page: number = 1) => {
    try {
      setLoading(true);
      setError(null);
      
      // Mock data for now - replace with actual API call
      const mockEvents: Event[] = [
        {
          id: '1',
          title: 'AU-VLP Leadership Summit 2024',
          description: 'Annual leadership summit for African Union Youth Leadership Program participants.',
          start_date: '2024-03-15T09:00:00Z',
          end_date: '2024-03-17T17:00:00Z',
          location: 'Addis Ababa, Ethiopia',
          organization_id: '1',
          created_at: '2024-01-15T10:00:00Z',
          updated_at: '2024-01-15T10:00:00Z',
        },
        {
          id: '2',
          title: 'Youth Entrepreneurship Workshop',
          description: 'Workshop focused on developing entrepreneurial skills among African youth.',
          start_date: '2024-04-20T14:00:00Z',
          end_date: '2024-04-20T18:00:00Z',
          location: 'Lagos, Nigeria',
          organization_id: '2',
          created_at: '2024-02-01T12:00:00Z',
          updated_at: '2024-02-01T12:00:00Z',
        },
      ];

      setEvents(mockEvents);
      setTotalCount(mockEvents.length);
      setTotalPages(1);
    } catch (err: any) {
      console.error('Error fetching events:', err);
      setError('Failed to load events. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchEvents(currentPage);
  }, [currentPage, refreshTrigger]);

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  const columns = [
    {
      key: 'title',
      label: 'Event',
      render: (event: Event) => (
        <div>
          <div className="text-sm font-medium text-gray-900">{event.title}</div>
          {event.description && (
            <div className="text-sm text-gray-500 truncate max-w-xs">
              {event.description}
            </div>
          )}
        </div>
      ),
    },
    {
      key: 'dates',
      label: 'Date & Time',
      render: (event: Event) => (
        <div className="text-sm text-gray-900">
          <div>Start: {formatDate(event.start_date)}</div>
          <div>End: {formatDate(event.end_date)}</div>
        </div>
      ),
    },
    {
      key: 'location',
      label: 'Location',
      render: (event: Event) => (
        <span className="text-sm text-gray-900">
          {event.location || '-'}
        </span>
      ),
    },
    {
      key: 'created_at',
      label: 'Created',
      render: (event: Event) => (
        <span className="text-sm text-gray-500">
          {new Date(event.created_at).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
          })}
        </span>
      ),
    },
    {
      key: 'actions',
      label: 'Actions',
      render: (event: Event) => (
        <div className="flex space-x-2">
          <Button
            variant="secondary"
            size="sm"
            onClick={() => onEdit(event)}
          >
            Edit
          </Button>
          <Button
            variant="danger"
            size="sm"
            onClick={() => onDelete(event)}
          >
            Delete
          </Button>
        </div>
      ),
    },
  ];

  if (loading) {
    return (
      <div className="flex justify-center items-center h-64">
        <LoadingSpinner />
      </div>
    );
  }

  if (error) {
    return <ErrorMessage message={error} />;
  }

  return (
    <div className="space-y-4">
      <div className="flex justify-between items-center">
        <h3 className="text-lg font-medium text-gray-900">
          Events ({totalCount})
        </h3>
        {onRefresh && (
          <Button variant="secondary" onClick={onRefresh}>
            Refresh
          </Button>
        )}
      </div>

      <Table
        data={events}
        columns={columns}
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={handlePageChange}
        emptyMessage="No events found."
      />
    </div>
  );
};

export default EventList;