import React from 'react';
import { Outlet } from 'react-router-dom';
import Header from './Header';
import Navigation from './Navigation';
import Footer from './Footer';
import ErrorBoundary from '../common/ErrorBoundary';

const Layout: React.FC = () => {
  return (
    <div className="min-h-screen flex flex-col">
      <ErrorBoundary>
        <Header />
        <Navigation />
        <main className="flex-grow container mx-auto px-4 py-8">
          <Outlet />
        </main>
        <Footer />
      </ErrorBoundary>
    </div>
  );
};

export default Layout;