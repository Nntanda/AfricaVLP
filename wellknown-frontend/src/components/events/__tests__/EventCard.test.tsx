import React from 'react';
import { render, screen } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import EventCard from '../EventCard';
import { Event } from '../../../types';

const mockUpcomingEvent: Event = {
  id: '1',
  title: 'Leadership Workshop 2024',
  description: 'Join us for an interactive workshop focused on developing leadership skills.',
  start_date: '2024-06-15T10:00:00Z',
  end_date: '2024-06-15T16:00:00Z',
  location: 'Addis Ababa, Ethiopia',
  featured_image: '/test-event-image.jpg',
  event_type: 'Workshop',
  max_participants: 50,
  current_participants: 25,
  registration_open: true,
  created_at: '2024-03-15T10:00:00Z',
  updated_at: '2024-03-15T10:00:00Z',
  organization: { id: '1', name: 'Test Org' },
  categories: [{ id: '1', name: 'Leadership' }]
};

const mockPastEvent: Event = {
  ...mockUpcomingEvent,
  id: '2',
  title: 'Past Leadership Workshop',
  start_date: '2024-01-15T10:00:00Z',
  end_date: '2024-01-15T16:00:00Z',
  registration_open: false
};

const renderWithRouter = (component: React.ReactElement) => {
  return render(
    <BrowserRouter>
      {component}
    </BrowserRouter>
  );
};

describe('EventCard', () => {
  it('renders event title', () => {
    renderWithRouter(<EventCard event={mockUpcomingEvent} />);
    expect(screen.getByText('Leadership Workshop 2024')).toBeInTheDocument();
  });

  it('renders event description', () => {
    renderWithRouter(<EventCard event={mockUpcomingEvent} />);
    expect(screen.getByText(/Join us for an interactive workshop/)).toBeInTheDocument();
  });

  it('renders event location', () => {
    renderWithRouter(<EventCard event={mockUpcomingEvent} />);
    expect(screen.getByText('Addis Ababa, Ethiopia')).toBeInTheDocument();
  });

  it('renders event type as badge', () => {
    renderWithRouter(<EventCard event={mockUpcomingEvent} />);
    expect(screen.getByText('Workshop')).toBeInTheDocument();
  });

  it('renders participant count', () => {
    renderWithRouter(<EventCard event={mockUpcomingEvent} />);
    expect(screen.getByText('25/50 participants')).toBeInTheDocument();
  });

  it('shows "Upcoming" badge for future events', () => {
    renderWithRouter(<EventCard event={mockUpcomingEvent} />);
    expect(screen.getByText('Upcoming')).toBeInTheDocument();
  });

  it('shows "Past" badge for past events', () => {
    renderWithRouter(<EventCard event={mockPastEvent} />);
    expect(screen.getByText('Past')).toBeInTheDocument();
  });

  it('renders featured image when provided', () => {
    renderWithRouter(<EventCard event={mockUpcomingEvent} />);
    const image = screen.getByAltText('Leadership Workshop 2024');
    expect(image).toBeInTheDocument();
    expect(image).toHaveAttribute('src', '/test-event-image.jpg');
  });

  it('renders learn more link with correct href', () => {
    renderWithRouter(<EventCard event={mockUpcomingEvent} />);
    const learnMoreLink = screen.getByText('Learn More →');
    expect(learnMoreLink.closest('a')).toHaveAttribute('href', '/events/1');
  });

  it('shows register button for upcoming events with open registration', () => {
    renderWithRouter(<EventCard event={mockUpcomingEvent} />);
    expect(screen.getByText('Register')).toBeInTheDocument();
  });

  it('does not show register button for past events', () => {
    renderWithRouter(<EventCard event={mockPastEvent} />);
    expect(screen.queryByText('Register')).not.toBeInTheDocument();
  });

  it('displays formatted date', () => {
    renderWithRouter(<EventCard event={mockUpcomingEvent} />);
    expect(screen.getByText('Jun 15, 2024')).toBeInTheDocument();
  });

  it('applies custom className when provided', () => {
    const { container } = renderWithRouter(
      <EventCard event={mockUpcomingEvent} className="custom-event-class" />
    );
    expect(container.firstChild).toHaveClass('custom-event-class');
  });

  it('handles events without location', () => {
    const eventWithoutLocation = { ...mockUpcomingEvent, location: undefined };
    renderWithRouter(<EventCard event={eventWithoutLocation} />);
    expect(screen.queryByText('Addis Ababa, Ethiopia')).not.toBeInTheDocument();
  });

  it('handles events without participant limits', () => {
    const eventWithoutLimits = { 
      ...mockUpcomingEvent, 
      max_participants: undefined,
      current_participants: undefined 
    };
    renderWithRouter(<EventCard event={eventWithoutLimits} />);
    expect(screen.queryByText(/participants/)).not.toBeInTheDocument();
  });
});