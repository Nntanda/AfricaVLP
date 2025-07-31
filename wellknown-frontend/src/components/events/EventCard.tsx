import React from 'react';
import { Link } from 'react-router-dom';
import { Event } from '../../types';
import { Badge } from '../ui/Badge';

interface EventCardProps {
  event: Event;
  className?: string;
}

const EventCard: React.FC<EventCardProps> = ({ event, className = '' }) => {
  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  };

  const formatTime = (dateString: string) => {
    return new Date(dateString).toLocaleTimeString('en-US', {
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  const isUpcoming = new Date(event.start_date) > new Date();
  const isPast = new Date(event.end_date) < new Date();

  return (
    <div className={`bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow ${className}`}>
      {event.featured_image && (
        <div className="h-48 bg-gray-200 overflow-hidden relative">
          <img 
            src={event.featured_image} 
            alt={event.title}
            className="w-full h-full object-cover"
          />
          <div className="absolute top-4 left-4">
            <Badge variant={isUpcoming ? 'success' : isPast ? 'secondary' : 'warning'}>
              {isUpcoming ? 'Upcoming' : isPast ? 'Past' : 'Ongoing'}
            </Badge>
          </div>
        </div>
      )}
      
      <div className="p-6">
        {/* Date and Location */}
        <div className="flex items-center text-sm text-gray-500 mb-3">
          <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span>{formatDate(event.start_date)}</span>
          {event.start_time && (
            <>
              <span className="mx-2">•</span>
              <span>{formatTime(event.start_date)}</span>
            </>
          )}
          {event.location && (
            <>
              <span className="mx-2">•</span>
              <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>{event.location}</span>
            </>
          )}
        </div>

        {/* Title */}
        <h3 className="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">
          <Link 
            to={`/events/${event.id}`}
            className="hover:text-blue-600 transition-colors"
          >
            {event.title}
          </Link>
        </h3>

        {/* Description */}
        <p className="text-gray-600 mb-4 line-clamp-3">
          {event.description}
        </p>

        {/* Event Type and Capacity */}
        <div className="flex items-center justify-between mb-4">
          {event.event_type && (
            <Badge variant="outline">
              {event.event_type}
            </Badge>
          )}
          {event.max_participants && (
            <span className="text-sm text-gray-500">
              <svg className="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
              {event.current_participants || 0}/{event.max_participants} participants
            </span>
          )}
        </div>

        {/* Footer */}
        <div className="flex justify-between items-center">
          <Link 
            to={`/events/${event.id}`}
            className="text-blue-600 hover:text-blue-800 font-medium"
          >
            {isUpcoming ? 'Learn More' : 'View Details'} →
          </Link>
          {isUpcoming && event.registration_open && (
            <button className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
              Register
            </button>
          )}
        </div>
      </div>
    </div>
  );
};

export default EventCard;