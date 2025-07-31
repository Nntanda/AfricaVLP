import { useCallback } from 'react';
import { ApiError } from '../services/api/types';
import { handleApiError, isAuthError, isNetworkError } from '../services/api/errorHandler';
import { AxiosError } from 'axios';

interface UseErrorHandlerReturn {
  handleError: (error: Error | AxiosError) => ApiError;
  handleAuthError: () => void;
  handleNetworkError: () => void;
}

export const useErrorHandler = (): UseErrorHandlerReturn => {
  const handleError = useCallback((error: Error | AxiosError): ApiError => {
    let apiError: ApiError;
    
    if (error.name === 'AxiosError') {
      apiError = handleApiError(error as AxiosError);
    } else {
      apiError = {
        message: error.message || 'An unexpected error occurred',
        code: 'UNKNOWN_ERROR',
      };
    }
    
    // Log error for debugging
    if (process.env.NODE_ENV === 'development') {
      console.error('Error handled:', apiError);
    }
    
    // Handle specific error types
    if (isAuthError(apiError)) {
      handleAuthError();
    } else if (isNetworkError(apiError)) {
      handleNetworkError();
    }
    
    return apiError;
  }, []);
  
  const handleAuthError = useCallback(() => {
    // Clear tokens and redirect to login
    localStorage.removeItem('access_token');
    localStorage.removeItem('refresh_token');
    localStorage.removeItem('user_data');
    
    if (window.location.pathname !== '/login') {
      window.location.href = '/login';
    }
  }, []);
  
  const handleNetworkError = useCallback(() => {
    // Could show a network error toast or modal
    console.warn('Network error detected');
  }, []);
  
  return {
    handleError,
    handleAuthError,
    handleNetworkError,
  };
};