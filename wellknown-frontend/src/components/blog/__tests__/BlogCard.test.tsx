import React from 'react';
import { render, screen } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import BlogCard from '../BlogCard';
import { BlogPost } from '../../../types';

const mockBlogPost: BlogPost = {
  id: '1',
  title: 'Test Blog Post',
  content: 'This is a test blog post content that should be displayed in the card component.',
  excerpt: 'This is a test excerpt',
  featured_image: '/test-image.jpg',
  published_at: '2024-03-15T10:00:00Z',
  created_at: '2024-03-15T10:00:00Z',
  updated_at: '2024-03-15T10:00:00Z',
  author: { id: '1', name: 'Test Author', email: 'test@example.com' },
  categories: [
    { id: '1', name: 'Leadership' },
    { id: '2', name: 'Technology' }
  ],
  tags: [
    { id: '1', name: 'innovation' },
    { id: '2', name: 'digital' },
    { id: '3', name: 'transformation' },
    { id: '4', name: 'future' }
  ],
  status: 'published',
  slug: 'test-blog-post'
};

const renderWithRouter = (component: React.ReactElement) => {
  return render(
    <BrowserRouter>
      {component}
    </BrowserRouter>
  );
};

describe('BlogCard', () => {
  it('renders blog post title', () => {
    renderWithRouter(<BlogCard post={mockBlogPost} />);
    expect(screen.getByText('Test Blog Post')).toBeInTheDocument();
  });

  it('renders blog post excerpt', () => {
    renderWithRouter(<BlogCard post={mockBlogPost} />);
    expect(screen.getByText('This is a test excerpt')).toBeInTheDocument();
  });

  it('renders categories as badges', () => {
    renderWithRouter(<BlogCard post={mockBlogPost} />);
    expect(screen.getByText('Leadership')).toBeInTheDocument();
    expect(screen.getByText('Technology')).toBeInTheDocument();
  });

  it('renders first 3 tags and shows count for additional tags', () => {
    renderWithRouter(<BlogCard post={mockBlogPost} />);
    expect(screen.getByText('#innovation')).toBeInTheDocument();
    expect(screen.getByText('#digital')).toBeInTheDocument();
    expect(screen.getByText('#transformation')).toBeInTheDocument();
    expect(screen.getByText('+1 more')).toBeInTheDocument();
  });

  it('renders featured image when provided', () => {
    renderWithRouter(<BlogCard post={mockBlogPost} />);
    const image = screen.getByAltText('Test Blog Post');
    expect(image).toBeInTheDocument();
    expect(image).toHaveAttribute('src', '/test-image.jpg');
  });

  it('renders read more link with correct href', () => {
    renderWithRouter(<BlogCard post={mockBlogPost} />);
    const readMoreLinks = screen.getAllByText('Read More →');
    expect(readMoreLinks[0].closest('a')).toHaveAttribute('href', '/blog/1');
  });

  it('displays formatted date', () => {
    renderWithRouter(<BlogCard post={mockBlogPost} />);
    expect(screen.getByText('March 15, 2024')).toBeInTheDocument();
  });

  it('calculates and displays reading time', () => {
    renderWithRouter(<BlogCard post={mockBlogPost} />);
    expect(screen.getByText(/min read/)).toBeInTheDocument();
  });

  it('falls back to content excerpt when excerpt is not provided', () => {
    const postWithoutExcerpt = { ...mockBlogPost, excerpt: undefined };
    renderWithRouter(<BlogCard post={postWithoutExcerpt} />);
    expect(screen.getByText(/This is a test blog post content/)).toBeInTheDocument();
  });

  it('applies custom className when provided', () => {
    const { container } = renderWithRouter(
      <BlogCard post={mockBlogPost} className="custom-class" />
    );
    expect(container.firstChild).toHaveClass('custom-class');
  });
});