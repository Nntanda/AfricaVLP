import React from 'react';
import { Link } from 'react-router-dom';
import LanguageSwitcher from '../common/LanguageSwitcher';
import { useTranslation } from '../../hooks/useTranslation';

const Header: React.FC = () => {
  const { t } = useTranslation();
  return (
    <header className="bg-white shadow-sm border-b border-gray-200">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          <div className="flex items-center">
            <Link to="/" className="flex items-center">
              <div className="flex-shrink-0">
                <img
                  className="h-8 w-8"
                  src="/logo.svg"
                  alt="AU-VLP"
                  onError={(e) => {
                    e.currentTarget.style.display = 'none';
                  }}
                />
              </div>
              <div className="ml-3">
                <h1 className="text-xl font-bold text-gray-900">AU-VLP</h1>
                <p className="text-xs text-gray-500">African Union Youth Leadership Program</p>
              </div>
            </Link>
          </div>
          
          <nav className="hidden md:flex space-x-8">
            <Link
              to="/"
              className="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
            >
              {t('navigation.home')}
            </Link>
            <Link
              to="/blog"
              className="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
            >
              {t('navigation.blog')}
            </Link>
            <Link
              to="/news"
              className="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
            >
              {t('navigation.news')}
            </Link>
            <Link
              to="/events"
              className="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
            >
              {t('navigation.events')}
            </Link>
            <Link
              to="/organizations"
              className="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
            >
              {t('navigation.organizations')}
            </Link>
          </nav>

          <div className="flex items-center space-x-4">
            <LanguageSwitcher />
            <Link
              to="/login"
              className="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
            >
              {t('navigation.login')}
            </Link>
            <Link
              to="/register"
              className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium"
            >
              {t('navigation.register')}
            </Link>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;