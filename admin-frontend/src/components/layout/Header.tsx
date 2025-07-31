import React from 'react';
import LanguageSwitcher from '../common/LanguageSwitcher';
import { useTranslation } from '../../hooks/useTranslation';

interface HeaderProps {
  title?: string;
  user?: {
    name: string;
    role: string;
  };
  onLogout?: () => void;
}

const Header: React.FC<HeaderProps> = ({ 
  title = 'AU-VLP Admin', 
  user,
  onLogout 
}) => {
  const { t } = useTranslation();
  return (
    <header className="bg-white shadow-sm border-b border-gray-200">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          {/* Logo and Title */}
          <div className="flex items-center">
            <div className="flex-shrink-0">
              <h1 className="text-xl font-semibold text-gray-900">
                {title}
              </h1>
            </div>
          </div>

          {/* User Menu */}
          <div className="flex items-center space-x-4">
            <LanguageSwitcher />
            {user && (
              <div className="flex items-center space-x-3">
                <div className="text-sm">
                  <p className="text-gray-900 font-medium">{user.name}</p>
                  <p className="text-gray-500 capitalize">{user.role}</p>
                </div>
                <div className="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                  <span className="text-white text-sm font-medium">
                    {user.name.charAt(0).toUpperCase()}
                  </span>
                </div>
                {onLogout && (
                  <button
                    onClick={onLogout}
                    className="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium"
                  >
                    {t('auth.logout')}
                  </button>
                )}
              </div>
            )}
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;