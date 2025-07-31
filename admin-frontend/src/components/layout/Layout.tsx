import React, { useState } from 'react';
import Header from './Header';
import Footer from './Footer';
import Navigation from './Navigation';
import Sidebar from './Sidebar';
import ErrorBoundary from '../common/ErrorBoundary';

interface LayoutProps {
  children: React.ReactNode;
  showSidebar?: boolean;
  showNavigation?: boolean;
  user?: {
    name: string;
    role: string;
  };
  onLogout?: () => void;
}

const Layout: React.FC<LayoutProps> = ({
  children,
  showSidebar = true,
  showNavigation = false,
  user,
  onLogout,
}) => {
  const [sidebarOpen, setSidebarOpen] = useState(true);

  const toggleSidebar = () => {
    setSidebarOpen(!sidebarOpen);
  };

  return (
    <ErrorBoundary>
      <div className="min-h-screen bg-gray-50">
        {/* Header */}
        <Header user={user} onLogout={onLogout} />

        {/* Navigation (alternative to sidebar) */}
        {showNavigation && !showSidebar && <Navigation />}

        <div className="flex">
          {/* Sidebar */}
          {showSidebar && (
            <Sidebar
              isOpen={sidebarOpen}
              onToggle={toggleSidebar}
            />
          )}

          {/* Main Content */}
          <main className="flex-1 overflow-x-hidden">
            <div className="py-6">
              <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {children}
              </div>
            </div>
          </main>
        </div>

        {/* Footer */}
        <Footer />
      </div>
    </ErrorBoundary>
  );
};

export default Layout;