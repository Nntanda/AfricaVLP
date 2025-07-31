import React from 'react';
import { NavLink } from 'react-router-dom';

interface SidebarItem {
  name: string;
  href: string;
  icon?: React.ReactNode;
  children?: SidebarItem[];
}

interface SidebarProps {
  items?: SidebarItem[];
  isOpen?: boolean;
  onToggle?: () => void;
}

const defaultSidebarItems: SidebarItem[] = [
  {
    name: 'Dashboard',
    href: '/',
    icon: (
      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
      </svg>
    ),
  },
  {
    name: 'Content Management',
    href: '#',
    icon: (
      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
      </svg>
    ),
    children: [
      { name: 'Blog Posts', href: '/blog' },
      { name: 'News Articles', href: '/news' },
      { name: 'Events', href: '/events' },
      { name: 'Resources', href: '/resources' },
    ],
  },
  {
    name: 'User Management',
    href: '/users',
    icon: (
      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
      </svg>
    ),
  },
  {
    name: 'Organizations',
    href: '/organizations',
    icon: (
      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
      </svg>
    ),
  },
  {
    name: 'Activity Logs',
    href: '/activity-logs',
    icon: (
      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
    ),
  },
];

const Sidebar: React.FC<SidebarProps> = ({ 
  items = defaultSidebarItems, 
  isOpen = true,
  onToggle 
}) => {
  return (
    <div className={`bg-gray-900 text-white transition-all duration-300 ${isOpen ? 'w-64' : 'w-16'}`}>
      <div className="flex items-center justify-between p-4">
        <h2 className={`font-bold text-lg ${isOpen ? 'block' : 'hidden'}`}>
          AU-VLP Admin
        </h2>
        {onToggle && (
          <button
            onClick={onToggle}
            className="p-1 rounded-md hover:bg-gray-800"
          >
            <svg
              className="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
          </button>
        )}
      </div>

      <nav className="mt-8">
        <div className="px-2 space-y-1">
          {items.map((item) => (
            <div key={item.name}>
              <NavLink
                to={item.href}
                className={({ isActive }) =>
                  `group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200 ${
                    isActive
                      ? 'bg-gray-800 text-white'
                      : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                  }`
                }
              >
                {item.icon && (
                  <span className="mr-3 flex-shrink-0">{item.icon}</span>
                )}
                <span className={isOpen ? 'block' : 'hidden'}>{item.name}</span>
              </NavLink>

              {/* Submenu items */}
              {item.children && isOpen && (
                <div className="ml-8 mt-1 space-y-1">
                  {item.children.map((child) => (
                    <NavLink
                      key={child.name}
                      to={child.href}
                      className={({ isActive }) =>
                        `group flex items-center px-2 py-1 text-sm font-medium rounded-md transition-colors duration-200 ${
                          isActive
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                        }`
                      }
                    >
                      {child.name}
                    </NavLink>
                  ))}
                </div>
              )}
            </div>
          ))}
        </div>
      </nav>
    </div>
  );
};

export default Sidebar;