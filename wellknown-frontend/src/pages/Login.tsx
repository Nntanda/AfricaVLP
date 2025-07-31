import React from 'react';
import { Navigate, useLocation } from 'react-router-dom';
import { useAuthContext } from '../context/AuthContext';
import LoginForm from '../components/auth/LoginForm';

const Login: React.FC = () => {
  const { isAuthenticated, login, loading, error, clearError } = useAuthContext();
  const location = useLocation();

  if (isAuthenticated) {
    // Redirect to the page they tried to visit or home
    const from = location.state?.from?.pathname || '/';
    return <Navigate to={from} replace />;
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8">
        <div>
          <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
            AU-VLP Portal
          </h2>
          <p className="mt-2 text-center text-sm text-gray-600">
            Sign in to your account
          </p>
        </div>
        
        <LoginForm
          onSubmit={login}
          loading={loading}
          error={error || undefined}
          onClearError={clearError}
        />
      </div>
    </div>
  );
};

export default Login;