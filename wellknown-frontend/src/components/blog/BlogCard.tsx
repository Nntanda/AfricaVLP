import React from 'react';
import { Link } from 'react-router-dom';
import { BlogPost } from '../../types';
import { Badge } from '../ui/Badge';

interface BlogCardProps {
  post: BlogPost;
  className?: string;
}

const BlogCard: React.FC<BlogCardProps> = ({ post, className = '' }) => {
  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  const getReadingTime = (content: string) => {
    const wordsPerMinute = 200;
    const wordCount = content.split(' ').length;
    return Math.ceil(wordCount / wordsPerMinute);
  };

  return (
    <article className={`bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow ${className}`}>
      {post.featured_image && (
        <div className="h-48 bg-gray-200 overflow-hidden">
          <img 
            src={post.featured_image} 
            alt={post.title}
            className="w-full h-full object-cover"
          />
        </div>
      )}
      
      <div className="p-6">
        {/* Categories and Date */}
        <div className="flex items-center justify-between mb-3">
          <div className="flex flex-wrap gap-2">
            {post.categories.map((category) => (
              <Badge key={category.id} variant="secondary">
                {category.name}
              </Badge>
            ))}
          </div>
          <span className="text-sm text-gray-500">
            {formatDate(post.published_at || post.created_at)}
          </span>
        </div>

        {/* Title */}
        <h3 className="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">
          <Link 
            to={`/blog/${post.id}`}
            className="hover:text-blue-600 transition-colors"
          >
            {post.title}
          </Link>
        </h3>

        {/* Excerpt */}
        <p className="text-gray-600 mb-4 line-clamp-3">
          {post.excerpt || post.content.substring(0, 150) + '...'}
        </p>

        {/* Tags */}
        {post.tags.length > 0 && (
          <div className="flex flex-wrap gap-1 mb-4">
            {post.tags.slice(0, 3).map((tag) => (
              <span 
                key={tag.id}
                className="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded"
              >
                #{tag.name}
              </span>
            ))}
            {post.tags.length > 3 && (
              <span className="text-xs text-gray-500">
                +{post.tags.length - 3} more
              </span>
            )}
          </div>
        )}

        {/* Footer */}
        <div className="flex justify-between items-center">
          <Link 
            to={`/blog/${post.id}`}
            className="text-blue-600 hover:text-blue-800 font-medium"
          >
            Read More →
          </Link>
          <span className="text-sm text-gray-500">
            {getReadingTime(post.content)} min read
          </span>
        </div>
      </div>
    </article>
  );
};

export default BlogCard;