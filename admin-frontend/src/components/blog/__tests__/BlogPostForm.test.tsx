import React from 'react';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import BlogPostForm from '../BlogPostForm';
import { BlogPost } from '../../../types/common';

// Mock the API
const mockCreatePost = jest.fn();
const mockUpdatePost = jest.fn();
jest.mock('../../../services/api/endpoints', () => ({
  blogAPI: {
    createPost: mockCreatePost,
    updatePost: mockUpdatePost,
  },
}));

const mockPost: BlogPost = {
  id: '1',
  title: 'Test Blog Post',
  content: 'This is test content',
  excerpt: 'Test excerpt',
  status: 'published',
  author_id: '1',
  created_at: '2024-01-15T10:00:00Z',
  updated_at: '2024-01-15T10:00:00Z',
  published_at: '2024-01-15T10:00:00Z',
};

const mockProps = {
  onSave: jest.fn(),
  onCancel: jest.fn(),
};

describe('BlogPostForm', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('renders create form when no post is provided', () => {
    render(<BlogPostForm {...mockProps} />);
    
    expect(screen.getByText('Create New Blog Post')).toBeInTheDocument();
    expect(screen.getByText('Create Post')).toBeInTheDocument();
  });

  it('renders edit form when post is provided', () => {
    render(<BlogPostForm {...mockProps} post={mockPost} />);
    
    expect(screen.getByText('Edit Blog Post')).toBeInTheDocument();
    expect(screen.getByText('Update Post')).toBeInTheDocument();
    expect(screen.getByDisplayValue('Test Blog Post')).toBeInTheDocument();
  });

  it('calls onCancel when cancel button is clicked', () => {
    render(<BlogPostForm {...mockProps} />);
    
    const cancelButton = screen.getByText('Cancel');
    fireEvent.click(cancelButton);

    expect(mockProps.onCancel).toHaveBeenCalled();
  });
});