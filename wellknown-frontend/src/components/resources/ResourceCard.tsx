import React from 'react';
import { Resource } from '../../types';
import Badge from '../ui/Badge';

interface ResourceCardProps {
  resource: Resource;
  className?: string;
}

const ResourceCard: React.FC<ResourceCardProps> = ({ resource, className = '' }) => {
  const formatFileSize = (bytes: number) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  const getFileIcon = (fileType: string) => {
    switch (fileType.toLowerCase()) {
      case 'pdf':
        return (
          <svg className="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        );
      case 'doc':
      case 'docx':
        return (
          <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        );
      case 'ppt':
      case 'pptx':
        return (
          <svg className="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-9 0a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2" />
          </svg>
        );
      case 'mp4':
      case 'avi':
      case 'mov':
        return (
          <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
          </svg>
        );
      default:
        return (
          <svg className="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        );
    }
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  };

  return (
    <div className={`bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow ${className}`}>
      <div className="flex items-start space-x-4">
        <div className="flex-shrink-0">
          <div className="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
            {getFileIcon(resource.file_type || 'file')}
          </div>
        </div>
        <div className="flex-1 min-w-0">
          <div className="flex items-center justify-between mb-2">
            <h3 className="text-lg font-semibold text-gray-900 truncate">
              {resource.title}
            </h3>
            <div className="flex items-center space-x-2 flex-shrink-0">
              <Badge variant="success">
                {resource.file_type?.toUpperCase() || 'FILE'}
              </Badge>
              {resource.file_size && (
                <span className="text-sm text-gray-500">
                  {formatFileSize(resource.file_size)}
                </span>
              )}
            </div>
          </div>
          
          <p className="text-gray-600 mb-3 line-clamp-2">
            {resource.description}
          </p>

          {/* Categories */}
          {resource.categories && resource.categories.length > 0 && (
            <div className="flex flex-wrap gap-1 mb-3">
              {resource.categories.slice(0, 3).map((category) => (
                <Badge key={category.id} variant="outline">
                  {category.name}
                </Badge>
              ))}
              {resource.categories.length > 3 && (
                <span className="text-xs text-gray-500">
                  +{resource.categories.length - 3} more
                </span>
              )}
            </div>
          )}

          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-4 text-sm text-gray-500">
              <span>Added: {formatDate(resource.created_at)}</span>
              {resource.download_count && (
                <>
                  <span>•</span>
                  <span>Downloads: {resource.download_count}</span>
                </>
              )}
              {resource.organization && (
                <>
                  <span>•</span>
                  <span>By: {resource.organization.name}</span>
                </>
              )}
            </div>
            <div className="flex space-x-2">
              {resource.preview_url && (
                <button className="text-blue-600 hover:text-blue-800 font-medium">
                  Preview
                </button>
              )}
              <button 
                onClick={() => window.open(resource.file_url, '_blank')}
                className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
              >
                Download
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ResourceCard;