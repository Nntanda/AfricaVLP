import React from 'react';
import { render, screen } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import NewsCard from '../NewsCard';
import { News } from '../../../types';

const mockNews: News = {
  id: '1',
  title: 'Test News Article',
  content: 'This is a test news article content that should be displayed in the card component.',
  excerpt: 'This is a test news excerpt',
  featured_image: '/test-news-image.jpg',
  published_at: '2024-03-20T09:00:00Z',
  created_at: '2024-03-20T09:00:00Z',
  updated_at: '2024-03-20T09:00:00Z',
  author: { id: '1', name: 'News Author', email: 'news@example.com' },
  categories: [
    { id: '1', name: 'Announcements' },
    { id: '2', name: 'Updates' }
  ],
  tags: [
    { id: '1', name: 'important' },
    { id: '2', name: 'update' }
  ],
  status: 'published',
  slug: 'test-news-article'
};

const renderWithRouter = (component: React.ReactElement) => {
  return render(
    <BrowserRouter>
      {component}
    </BrowserRouter>
  );
};

describe('NewsCard', () => {
  it('renders news article title', () => {
    renderWithRouter(<NewsCard news={mockNews} />);
    expect(screen.getByText('Test News Article')).toBeInTheDocument();
  });

  it('renders news article excerpt', () => {
    renderWithRouter(<NewsCard news={mockNews} />);
    expect(screen.getByText('This is a test news excerpt')).toBeInTheDocument();
  });

  it('renders categories as badges', () => {
    renderWithRouter(<NewsCard news={mockNews} />);
    expect(screen.getByText('Announcements')).toBeInTheDocument();
    expect(screen.getByText('Updates')).toBeInTheDocument();
  });

  it('renders tags with green styling', () => {
    renderWithRouter(<NewsCard news={mockNews} />);
    expect(screen.getByText('#important')).toBeInTheDocument();
    expect(screen.getByText('#update')).toBeInTheDocument();
  });

  it('renders featured image when provided', () => {
    renderWithRouter(<NewsCard news={mockNews} />);
    const image = screen.getByAltText('Test News Article');
    expect(image).toBeInTheDocument();
    expect(image).toHaveAttribute('src', '/test-news-image.jpg');
  });

  it('renders read more link with correct href', () => {
    renderWithRouter(<NewsCard news={mockNews} />);
    const readMoreLinks = screen.getAllByText('Read More →');
    expect(readMoreLinks[0].closest('a')).toHaveAttribute('href', '/news/1');
  });

  it('displays formatted date', () => {
    renderWithRouter(<NewsCard news={mockNews} />);
    expect(screen.getByText('March 20, 2024')).toBeInTheDocument();
  });

  it('calculates and displays reading time', () => {
    renderWithRouter(<NewsCard news={mockNews} />);
    expect(screen.getByText(/min read/)).toBeInTheDocument();
  });

  it('falls back to content excerpt when excerpt is not provided', () => {
    const newsWithoutExcerpt = { ...mockNews, excerpt: undefined };
    renderWithRouter(<NewsCard news={newsWithoutExcerpt} />);
    expect(screen.getByText(/This is a test news article content/)).toBeInTheDocument();
  });

  it('applies custom className when provided', () => {
    const { container } = renderWithRouter(
      <NewsCard news={mockNews} className="custom-news-class" />
    );
    expect(container.firstChild).toHaveClass('custom-news-class');
  });

  it('shows tag count when more than 3 tags', () => {
    const newsWithManyTags = {
      ...mockNews,
      tags: [
        { id: '1', name: 'tag1' },
        { id: '2', name: 'tag2' },
        { id: '3', name: 'tag3' },
        { id: '4', name: 'tag4' },
        { id: '5', name: 'tag5' }
      ]
    };
    renderWithRouter(<NewsCard news={newsWithManyTags} />);
    expect(screen.getByText('+2 more')).toBeInTheDocument();
  });
});