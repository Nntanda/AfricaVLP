import React from 'react';
import { Link } from 'react-router-dom';
import { Organization } from '../../types';
import Badge from '../ui/Badge';

interface OrganizationCardProps {
  organization: Organization;
  className?: string;
}

const OrganizationCard: React.FC<OrganizationCardProps> = ({ organization, className = '' }) => {
  return (
    <div className={`bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow ${className}`}>
      {/* Header with logo/banner */}
      <div className="h-32 bg-gradient-to-r from-blue-500 to-purple-600 relative">
        {organization.logo ? (
          <img 
            src={organization.logo} 
            alt={`${organization.name} logo`}
            className="w-full h-full object-cover"
          />
        ) : (
          <div className="absolute inset-0 flex items-center justify-center">
            <div className="w-16 h-16 bg-white rounded-full flex items-center justify-center">
              <span className="text-2xl font-bold text-gray-600">
                {organization.name.charAt(0).toUpperCase()}
              </span>
            </div>
          </div>
        )}
      </div>
      
      <div className="p-6">
        {/* Organization Name */}
        <h3 className="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">
          <Link 
            to={`/organizations/${organization.id}`}
            className="hover:text-blue-600 transition-colors"
          >
            {organization.name}
          </Link>
        </h3>

        {/* Location and Type */}
        <div className="flex items-center justify-between mb-3">
          <div className="flex items-center text-sm text-gray-500">
            <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>
              {organization.city?.name}, {organization.country?.name}
            </span>
          </div>
          {organization.organization_type && (
            <Badge variant="outline">
              {organization.organization_type}
            </Badge>
          )}
        </div>

        {/* Description */}
        <p className="text-gray-600 mb-4 line-clamp-3">
          {organization.description || 'A participating organization in the AU-VLP program focused on youth development and leadership training.'}
        </p>

        {/* Categories */}
        {organization.categories && organization.categories.length > 0 && (
          <div className="flex flex-wrap gap-1 mb-4">
            {organization.categories.slice(0, 2).map((category) => (
              <Badge key={category.id} variant="secondary">
                {category.name}
              </Badge>
            ))}
            {organization.categories.length > 2 && (
              <span className="text-xs text-gray-500">
                +{organization.categories.length - 2} more
              </span>
            )}
          </div>
        )}

        {/* Footer */}
        <div className="flex items-center justify-between">
          <div className="flex items-center text-sm text-gray-500">
            <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            <span>{organization.member_count || 0} members</span>
          </div>
          <Link 
            to={`/organizations/${organization.id}`}
            className="text-blue-600 hover:text-blue-800 font-medium"
          >
            View Profile →
          </Link>
        </div>

        {/* Contact Info */}
        {(organization.website || organization.email) && (
          <div className="mt-4 pt-4 border-t border-gray-200">
            <div className="flex space-x-4 text-sm">
              {organization.website && (
                <a 
                  href={organization.website}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="text-blue-600 hover:text-blue-800 flex items-center"
                >
                  <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                  </svg>
                  Website
                </a>
              )}
              {organization.email && (
                <a 
                  href={`mailto:${organization.email}`}
                  className="text-blue-600 hover:text-blue-800 flex items-center"
                >
                  <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  Contact
                </a>
              )}
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default OrganizationCard;