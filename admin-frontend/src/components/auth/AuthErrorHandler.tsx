import React from 'react';
import ErrorMessage from '../common/ErrorMessage';

interface AuthErrorHandlerProps {
  error: string | null;
  onRetry?: () => void;
  onClear?: () => void;
}

const AuthErrorHandler: React.FC<AuthErrorHandlerProps> = ({ 
  error, 
  onRetry, 
  onClear 
}) => {
  if (!error) return null;

  const getErrorMessage = (error: string): string => {
    // Map common authentication errors to user-friendly messages
    if (error.includes('401') || error.includes('Unauthorized')) {
      return 'Invalid username or password. Please try again.';
    }
    if (error.includes('403') || error.includes('Forbidden')) {
      return 'You do not have permission to access this resource.';
    }
    if (error.includes('Network Error') || error.includes('timeout')) {
      return 'Network error. Please check your connection and try again.';
    }
    if (error.includes('token') && error.includes('expired')) {
      return 'Your session has expired. Please log in again.';
    }
    
    return error;
  };

  const getErrorTitle = (error: string): string => {
    if (error.includes('401') || error.includes('Unauthorized')) {
      return 'Authentication Failed';
    }
    if (error.includes('403') || error.includes('Forbidden')) {
      return 'Access Denied';
    }
    if (error.includes('Network Error') || error.includes('timeout')) {
      return 'Connection Error';
    }
    if (error.includes('token') && error.includes('expired')) {
      return 'Session Expired';
    }
    
    return 'Authentication Error';
  };

  return (
    <div className="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
      <div className="flex">
        <div className="flex-shrink-0">
          <svg className="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
          </svg>
        </div>
        <div className="ml-3 flex-1">
          <h3 className="text-sm font-medium text-red-800">
            {getErrorTitle(error)}
          </h3>
          <div className="mt-2 text-sm text-red-700">
            {getErrorMessage(error)}
          </div>
          {(onRetry || onClear) && (
            <div className="mt-3 flex space-x-2">
              {onRetry && (
                <button
                  onClick={onRetry}
                  className="text-sm bg-red-100 text-red-800 px-3 py-1 rounded-md hover:bg-red-200"
                >
                  Try Again
                </button>
              )}
              {onClear && (
                <button
                  onClick={onClear}
                  className="text-sm text-red-600 hover:text-red-800"
                >
                  Dismiss
                </button>
              )}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default AuthErrorHandler;