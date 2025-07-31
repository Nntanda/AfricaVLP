import React, { useState } from 'react';
import BlogCard from '../components/blog/BlogCard';
import { Pagination } from '../components/ui/Pagination';
import { BlogPost } from '../types';

// Mock data - in real app this would come from API
const mockBlogPosts: BlogPost[] = [
  {
    id: '1',
    title: 'Leadership in the Digital Age: Adapting to Change',
    content: 'In today\'s rapidly evolving digital landscape, young African leaders must adapt their leadership styles to meet new challenges...',
    excerpt: 'Exploring how young African leaders can adapt their leadership styles in the digital age.',
    featured_image: '/images/blog/leadership-digital.jpg',
    published_at: '2024-03-15T10:00:00Z',
    created_at: '2024-03-15T10:00:00Z',
    updated_at: '2024-03-15T10:00:00Z',
    author: { id: '1', name: 'John Doe', email: 'john@example.com' },
    categories: [{ id: '1', name: 'Leadership' }],
    tags: [{ id: '1', name: 'digital transformation' }, { id: '2', name: 'leadership' }],
    status: 'published',
    slug: 'leadership-digital-age'
  },
  {
    id: '2',
    title: 'Building Sustainable Communities Across Africa',
    content: 'Community building is at the heart of sustainable development. This post explores successful community initiatives...',
    excerpt: 'Discover how young leaders are building sustainable communities across the African continent.',
    featured_image: '/images/blog/community-building.jpg',
    published_at: '2024-03-12T14:30:00Z',
    created_at: '2024-03-12T14:30:00Z',
    updated_at: '2024-03-12T14:30:00Z',
    author: { id: '2', name: 'Jane Smith', email: 'jane@example.com' },
    categories: [{ id: '2', name: 'Community Development' }],
    tags: [{ id: '3', name: 'sustainability' }, { id: '4', name: 'community' }],
    status: 'published',
    slug: 'building-sustainable-communities'
  }
];

const Blog: React.FC = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('');
  const [currentPage, setCurrentPage] = useState(1);
  const postsPerPage = 6;

  // Filter posts based on search and category
  const filteredPosts = mockBlogPosts.filter(post => {
    const matchesSearch = post.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         post.content.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesCategory = !selectedCategory || 
                           post.categories.some(cat => cat.name.toLowerCase() === selectedCategory.toLowerCase());
    return matchesSearch && matchesCategory;
  });

  // Pagination
  const totalPages = Math.ceil(filteredPosts.length / postsPerPage);
  const startIndex = (currentPage - 1) * postsPerPage;
  const paginatedPosts = filteredPosts.slice(startIndex, startIndex + postsPerPage);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    setCurrentPage(1);
  };

  return (
    <div className="space-y-6">
      <div className="text-center">
        <h1 className="text-3xl font-bold text-gray-900 mb-4">Blog</h1>
        <p className="text-lg text-gray-600">
          Insights, stories, and updates from the AU-VLP community
        </p>
      </div>

      {/* Search and Filter Section */}
      <div className="bg-white rounded-lg shadow-md p-6">
        <form onSubmit={handleSearch} className="flex flex-col md:flex-row gap-4">
          <div className="flex-1">
            <input
              type="text"
              placeholder="Search blog posts..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>
          <div className="flex gap-2">
            <select 
              value={selectedCategory}
              onChange={(e) => setSelectedCategory(e.target.value)}
              className="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">All Categories</option>
              <option value="leadership">Leadership</option>
              <option value="community development">Community Development</option>
              <option value="events">Events</option>
              <option value="success stories">Success Stories</option>
            </select>
            <button 
              type="submit"
              className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors"
            >
              Search
            </button>
          </div>
        </form>
      </div>

      {/* Blog Posts Grid */}
      {paginatedPosts.length > 0 ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {paginatedPosts.map((post) => (
            <BlogCard key={post.id} post={post} />
          ))}
        </div>
      ) : (
        <div className="text-center py-12">
          <p className="text-gray-500 text-lg">No blog posts found matching your criteria.</p>
        </div>
      )}

      {/* Pagination */}
      {totalPages > 1 && (
        <div className="flex justify-center">
          <Pagination
            currentPage={currentPage}
            totalPages={totalPages}
            onPageChange={setCurrentPage}
          />
        </div>
      )}
    </div>
  );
};

export default Blog;