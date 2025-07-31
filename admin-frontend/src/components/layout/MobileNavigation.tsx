import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';

interface MobileNavigationProps {
  isOpen: boolean;
  onClose: () => void;
  menuItems: Array<{
    path: string;
    label: string;
    icon?: React.ReactNode;
    children?: Array<{
      path: string;
      label: string;
    }>;
  }>;
}

export const MobileNavigation: React.FC<MobileNavigationProps> = ({
  isOpen,
  onClose,
  menuItems,
}) => {
  const location = useLocation();
  const [expandedItems, setExpandedItems] = useState<string[]>([]);

  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'unset';
    }

    return () => {
      document.body.style.overflow = 'unset';
    };
  }, [isOpen]);

  const toggleExpanded = (path: string) => {
    setExpandedItems(prev =>
      prev.includes(path)
        ? prev.filter(item => item !== path)
        : [...prev, path]
    );
  };

  const isActive = (path: string) => location.pathname === path;

  return (
    <>
      {/* Backdrop */}
      {isOpen && (
        <div
          className="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
          onClick={onClose}
        />
      )}

      {/* Mobile Navigation */}
      <div
        className={`fixed top-0 left-0 h-full w-80 bg-white shadow-lg transform transition-transform duration-300 ease-in-out z-50 lg:hidden ${
          isOpen ? 'translate-x-0' : '-translate-x-full'
        }`}
      >
        <div className="flex flex-col h-full">
          {/* Header */}
          <div className="flex items-center justify-between p-4 border-b">
            <h2 className="text-lg font-semibold text-gray-900">Menu</h2>
            <button
              onClick={onClose}
              className="p-2 rounded-md hover:bg-gray-100 touch-manipulation"
              aria-label="Close menu"
            >
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          {/* Navigation Items */}
          <nav className="flex-1 overflow-y-auto py-4">
            <ul className="space-y-1">
              {menuItems.map((item) => (
                <li key={item.path}>
                  {item.children ? (
                    <div>
                      <button
                        onClick={() => toggleExpanded(item.path)}
                        className={`w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-50 touch-manipulation ${
                          expandedItems.includes(item.path) ? 'bg-gray-50' : ''
                        }`}
                      >
                        <div className="flex items-center space-x-3">
                          {item.icon && <span className="text-gray-500">{item.icon}</span>}
                          <span className="text-gray-900">{item.label}</span>
                        </div>
                        <svg
                          className={`w-5 h-5 text-gray-400 transform transition-transform ${
                            expandedItems.includes(item.path) ? 'rotate-90' : ''
                          }`}
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                        </svg>
                      </button>
                      {expandedItems.includes(item.path) && (
                        <ul className="bg-gray-50">
                          {item.children.map((child) => (
                            <li key={child.path}>
                              <Link
                                to={child.path}
                                onClick={onClose}
                                className={`block px-8 py-2 text-sm hover:bg-gray-100 touch-manipulation ${
                                  isActive(child.path) ? 'text-primary-600 bg-primary-50' : 'text-gray-700'
                                }`}
                              >
                                {child.label}
                              </Link>
                            </li>
                          ))}
                        </ul>
                      )}
                    </div>
                  ) : (
                    <Link
                      to={item.path}
                      onClick={onClose}
                      className={`flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 touch-manipulation ${
                        isActive(item.path) ? 'text-primary-600 bg-primary-50' : 'text-gray-900'
                      }`}
                    >
                      {item.icon && <span className="text-gray-500">{item.icon}</span>}
                      <span>{item.label}</span>
                    </Link>
                  )}
                </li>
              ))}
            </ul>
          </nav>
        </div>
      </div>
    </>
  );
};

interface MobileMenuButtonProps {
  onClick: () => void;
  isOpen: boolean;
}

export const MobileMenuButton: React.FC<MobileMenuButtonProps> = ({ onClick, isOpen }) => {
  return (
    <button
      onClick={onClick}
      className="lg:hidden p-2 rounded-md hover:bg-gray-100 touch-manipulation"
      aria-label="Toggle menu"
    >
      <svg
        className={`w-6 h-6 transform transition-transform ${isOpen ? 'rotate-90' : ''}`}
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        {isOpen ? (
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
        ) : (
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
        )}
      </svg>
    </button>
  );
};