import React from 'react';
import { render, screen, waitFor, fireEvent } from '@testing-library/react';
import BlogPostList from '../BlogPostList';
import { BlogPost } from '../../../types/common';

// Mock the API
const mockGetPosts = jest.fn();
jest.mock('../../../services/api/endpoints', () => ({
  blogAPI: {
    getPosts: mockGetPosts,
  },
}));

const mockBlogPosts: BlogPost[] = [
  {
    id: '1',
    title: 'Test Blog Post 1',
    content: 'This is test content 1',
    excerpt: 'Test excerpt 1',
    status: 'published',
    author_id: '1',
    created_at: '2024-01-15T10:00:00Z',
    updated_at: '2024-01-15T10:00:00Z',
    published_at: '2024-01-15T10:00:00Z',
  },
  {
    id: '2',
    title: 'Test Blog Post 2',
    content: 'This is test content 2',
    excerpt: 'Test excerpt 2',
    status: 'draft',
    author_id: '1',
    created_at: '2024-01-16T10:00:00Z',
    updated_at: '2024-01-16T10:00:00Z',
  },
];

const mockProps = {
  onEdit: jest.fn(),
  onDelete: jest.fn(),
  onRefresh: jest.fn(),
};

describe('BlogPostList', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('renders blog posts after loading', async () => {
    mockGetPosts.mockResolvedValue({
      data: {
        results: mockBlogPosts,
        count: 2,
      },
    });

    render(<BlogPostList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('Test Blog Post 1')).toBeInTheDocument();
      expect(screen.getByText('Test Blog Post 2')).toBeInTheDocument();
    });
  });

  it('calls onEdit when edit button is clicked', async () => {
    mockGetPosts.mockResolvedValue({
      data: {
        results: mockBlogPosts,
        count: 2,
      },
    });

    render(<BlogPostList {...mockProps} />);

    await waitFor(() => {
      expect(screen.getByText('Test Blog Post 1')).toBeInTheDocument();
    });

    const editButtons = screen.getAllByText('Edit');
    fireEvent.click(editButtons[0]);

    expect(mockProps.onEdit).toHaveBeenCalledWith(mockBlogPosts[0]);
  });
});