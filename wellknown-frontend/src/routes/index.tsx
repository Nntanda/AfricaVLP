import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Home from '../pages/Home';
import Login from '../pages/Login';
import Blog from '../pages/Blog';
import News from '../pages/News';
import Events from '../pages/Events';
import Organizations from '../pages/Organizations';
import Resources from '../pages/Resources';
import Profile from '../pages/Profile';
import ProtectedRoute from '../components/auth/ProtectedRoute';
import Layout from '../components/layout/Layout';
import { ROUTES } from '../utils/constants';

const AppRoutes: React.FC = () => {
  return (
    <Routes>
      {/* Public routes without layout */}
      <Route path={ROUTES.LOGIN} element={<Login />} />
      
      {/* Public routes with layout */}
      <Route path="/" element={<Layout />}>
        <Route index element={<Home />} />
        <Route path={ROUTES.BLOG} element={<Blog />} />
        <Route path={ROUTES.NEWS} element={<News />} />
        <Route path={ROUTES.EVENTS} element={<Events />} />
        <Route path={ROUTES.ORGANIZATIONS} element={<Organizations />} />
        <Route path={ROUTES.RESOURCES} element={<Resources />} />
        
        {/* Protected routes */}
        <Route 
          path={ROUTES.PROFILE} 
          element={
            <ProtectedRoute>
              <Profile />
            </ProtectedRoute>
          } 
        />
      </Route>
      
      {/* Catch all route for 404 */}
      <Route path="*" element={<div>Page Not Found</div>} />
    </Routes>
  );
};

export default AppRoutes;