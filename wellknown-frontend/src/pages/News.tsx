import React, { useState } from 'react';
import NewsCard from '../components/news/NewsCard';
import { News } from '../types';

// Mock data - in real app this would come from API
const mockNews: News[] = [
  {
    id: '1',
    title: 'AU-VLP Launches New Youth Leadership Initiative',
    content: 'The African Union Youth Leadership Program is excited to announce the launch of a new initiative focused on digital leadership skills...',
    excerpt: 'New initiative focuses on developing digital leadership skills for African youth.',
    featured_image: '/images/news/youth-initiative.jpg',
    published_at: '2024-03-20T09:00:00Z',
    created_at: '2024-03-20T09:00:00Z',
    updated_at: '2024-03-20T09:00:00Z',
    author: { id: '1', name: 'AU-VLP Team', email: 'team@au-vlp.org' },
    categories: [{ id: '1', name: 'Announcements' }],
    tags: [{ id: '1', name: 'initiative' }, { id: '2', name: 'digital skills' }],
    status: 'published',
    slug: 'new-youth-leadership-initiative'
  },
  {
    id: '2',
    title: 'Partnership Agreement Signed with Leading African Universities',
    content: 'AU-VLP has signed partnership agreements with 15 leading African universities to expand educational opportunities...',
    excerpt: 'Strategic partnerships with universities will expand educational opportunities for program participants.',
    featured_image: '/images/news/university-partnership.jpg',
    published_at: '2024-03-18T11:30:00Z',
    created_at: '2024-03-18T11:30:00Z',
    updated_at: '2024-03-18T11:30:00Z',
    author: { id: '1', name: 'AU-VLP Team', email: 'team@au-vlp.org' },
    categories: [{ id: '2', name: 'Partnerships' }],
    tags: [{ id: '3', name: 'education' }, { id: '4', name: 'universities' }],
    status: 'published',
    slug: 'university-partnership-agreement'
  }
];

const NewsPage: React.FC = () => {
  const [visibleNews, setVisibleNews] = useState(5);
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('');

  // Filter news based on search and category
  const filteredNews = mockNews.filter(news => {
    const matchesSearch = news.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         news.content.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesCategory = !selectedCategory || 
                           news.categories.some(cat => cat.name.toLowerCase() === selectedCategory.toLowerCase());
    return matchesSearch && matchesCategory;
  });

  const displayedNews = filteredNews.slice(0, visibleNews);
  const hasMore = visibleNews < filteredNews.length;

  const featuredNews = filteredNews[0];

  const handleLoadMore = () => {
    setVisibleNews(prev => prev + 5);
  };

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    setVisibleNews(5);
  };

  return (
    <div className="space-y-6">
      <div className="text-center">
        <h1 className="text-3xl font-bold text-gray-900 mb-4">News</h1>
        <p className="text-lg text-gray-600">
          Latest news and announcements from AU-VLP
        </p>
      </div>

      {/* Search and Filter */}
      <div className="bg-white rounded-lg shadow-md p-6">
        <form onSubmit={handleSearch} className="flex flex-col md:flex-row gap-4">
          <div className="flex-1">
            <input
              type="text"
              placeholder="Search news..."
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
              <option value="announcements">Announcements</option>
              <option value="partnerships">Partnerships</option>
              <option value="events">Events</option>
              <option value="achievements">Achievements</option>
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

      {/* Featured News */}
      {featuredNews && (
        <div className="bg-white rounded-lg shadow-md overflow-hidden">
          <div className="md:flex">
            <div className="md:w-1/3">
              <div className="h-64 md:h-full bg-gray-200 overflow-hidden">
                {featuredNews.featured_image ? (
                  <img 
                    src={featuredNews.featured_image} 
                    alt={featuredNews.title}
                    className="w-full h-full object-cover"
                  />
                ) : (
                  <div className="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600"></div>
                )}
              </div>
            </div>
            <div className="md:w-2/3 p-6">
              <div className="flex items-center text-sm text-gray-500 mb-2">
                <span className="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                  Featured
                </span>
                <span className="ml-2">
                  {new Date(featuredNews.published_at).toLocaleDateString()}
                </span>
              </div>
              <h2 className="text-2xl font-bold text-gray-900 mb-3">
                {featuredNews.title}
              </h2>
              <p className="text-gray-600 mb-4">
                {featuredNews.excerpt || featuredNews.content.substring(0, 200) + '...'}
              </p>
              <a 
                href={`/news/${featuredNews.id}`} 
                className="text-blue-600 hover:text-blue-800 font-medium"
              >
                Read Full Article →
              </a>
            </div>
          </div>
        </div>
      )}

      {/* News List */}
      {displayedNews.length > 0 ? (
        <div className="space-y-6">
          {displayedNews.slice(1).map((news) => (
            <NewsCard key={news.id} news={news} />
          ))}
        </div>
      ) : (
        <div className="text-center py-12">
          <p className="text-gray-500 text-lg">No news articles found matching your criteria.</p>
        </div>
      )}

      {/* Load More Button */}
      {hasMore && (
        <div className="text-center">
          <button 
            onClick={handleLoadMore}
            className="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors"
          >
            Load More News
          </button>
        </div>
      )}
    </div>
  );
};

export default NewsPage;