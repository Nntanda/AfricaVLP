import React from 'react';
import { Link } from 'react-router-dom';
import { BlogPost, News, Event } from '../../types';

interface FeaturedContentProps {
  featuredBlog?: BlogPost;
  featuredNews?: News;
  upcomingEvent?: Event;
  loading?: boolean;
}

const FeaturedContent: React.FC<FeaturedContentProps> = ({
  featuredBlog,
  featuredNews,
  upcomingEvent,
  loading = false
}) => {
  if (loading) {
    return (
      <section className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {[1, 2, 3].map((i) => (
          <div key={i} className="bg-white rounded-lg shadow-md p-6 animate-pulse">
            <div className="h-4 bg-gray-200 rounded mb-3"></div>
            <div className="h-3 bg-gray-200 rounded mb-4"></div>
            <div className="h-3 bg-gray-200 rounded w-1/2"></div>
          </div>
        ))}
      </section>
    );
  }

  return (
    <section className="grid grid-cols-1 md:grid-cols-3 gap-6">
      {/* Featured Blog */}
      <div className="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <h3 className="text-xl font-semibold mb-3">Latest Blog Post</h3>
        {featuredBlog ? (
          <>
            <h4 className="font-medium text-gray-900 mb-2 line-clamp-2">
              {featuredBlog.title}
            </h4>
            <p className="text-gray-600 mb-4 line-clamp-3">
              {featuredBlog.excerpt || featuredBlog.content.substring(0, 150) + '...'}
            </p>
            <Link 
              to={`/blog/${featuredBlog.id}`}
              className="text-blue-600 hover:text-blue-800 font-medium"
            >
              Read More →
            </Link>
          </>
        ) : (
          <p className="text-gray-600 mb-4">
            Stay updated with the latest insights from the AU-VLP community.
          </p>
        )}
        <Link 
          to="/blog"
          className="text-blue-600 hover:text-blue-800 font-medium"
        >
          View All Posts →
        </Link>
      </div>

      {/* Featured News */}
      <div className="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <h3 className="text-xl font-semibold mb-3">Latest News</h3>
        {featuredNews ? (
          <>
            <h4 className="font-medium text-gray-900 mb-2 line-clamp-2">
              {featuredNews.title}
            </h4>
            <p className="text-gray-600 mb-4 line-clamp-3">
              {featuredNews.excerpt || featuredNews.content.substring(0, 150) + '...'}
            </p>
            <Link 
              to={`/news/${featuredNews.id}`}
              className="text-blue-600 hover:text-blue-800 font-medium"
            >
              Read More →
            </Link>
          </>
        ) : (
          <p className="text-gray-600 mb-4">
            Stay updated with the latest developments in the AU-VLP program.
          </p>
        )}
        <Link 
          to="/news"
          className="text-blue-600 hover:text-blue-800 font-medium"
        >
          View All News →
        </Link>
      </div>

      {/* Upcoming Event */}
      <div className="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <h3 className="text-xl font-semibold mb-3">Upcoming Event</h3>
        {upcomingEvent ? (
          <>
            <h4 className="font-medium text-gray-900 mb-2 line-clamp-2">
              {upcomingEvent.title}
            </h4>
            <div className="text-sm text-gray-500 mb-2">
              {new Date(upcomingEvent.start_date).toLocaleDateString()}
            </div>
            <p className="text-gray-600 mb-4 line-clamp-3">
              {upcomingEvent.description}
            </p>
            <Link 
              to={`/events/${upcomingEvent.id}`}
              className="text-blue-600 hover:text-blue-800 font-medium"
            >
              Learn More →
            </Link>
          </>
        ) : (
          <p className="text-gray-600 mb-4">
            Discover upcoming events and opportunities to participate.
          </p>
        )}
        <Link 
          to="/events"
          className="text-blue-600 hover:text-blue-800 font-medium"
        >
          View All Events →
        </Link>
      </div>
    </section>
  );
};

export default FeaturedContent;