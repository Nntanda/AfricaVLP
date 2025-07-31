import { useQueryClient } from 'react-query';
import { toast } from './useToast';
import { useAuth } from './useAuth';

// Error types
interface ApiError {
  response?: {
    status: number;
    data?: {
      message?: string;
      detail?: string;
      errors?: Record<string, string[]>;
    };
  };
  message?: string;
}

// Global error handler hook
export const useQueryErrorHandler = () => {
  const queryClient = useQueryClient();
  const { logout } = useAuth();

  const handleError = (error: ApiError, context?: string) => {
    const status = error.response?.status;
    const message = error.response?.data?.message || error.response?.data?.detail || error.message;

    // Handle different error types
    switch (status) {
      case 401:
        // Unauthorized - logout user
        toast.error('Session expired. Please login again.');
        logout();
        break;
      
      case 403:
        // Forbidden
        toast.error('You do not have permission to perform this action.');
        break;
      
      case 404:
        // Not found
        toast.error(context ? `${context} not found` : 'Resource not found');
        break;
      
      case 422:
        // Validation error
        const errors = error.response?.data?.errors;
        if (errors) {
          Object.entries(errors).forEach(([field, messages]) => {
            messages.forEach((msg: string) => {
              toast.error(`${field}: ${msg}`);
            });
          });
        } else {
          toast.error(message || 'Validation error occurred');
        }
        break;
      
      case 429:
        // Rate limited
        toast.error('Too many requests. Please try again later.');
        break;
      
      case 500:
        // Server error
        toast.error('Server error occurred. Please try again later.');
        break;
      
      default:
        // Generic error
        toast.error(message || 'An unexpected error occurred');
    }
  };

  const retryFailedQueries = () => {
    queryClient.refetchQueries({
      type: 'inactive',
      stale: true,
    });
  };

  const clearErrorQueries = () => {
    queryClient.resetQueries({
      type: 'inactive',
    });
  };

  return {
    handleError,
    retryFailedQueries,
    clearErrorQueries,
  };
};

// Specific error handlers for different contexts
export const useBlogPostErrorHandler = () => {
  const { handleError } = useQueryErrorHandler();

  return {
    handleFetchError: (error: ApiError) => handleError(error, 'Blog post'),
    handleCreateError: (error: ApiError) => handleError(error, 'Failed to create blog post'),
    handleUpdateError: (error: ApiError) => handleError(error, 'Failed to update blog post'),
    handleDeleteError: (error: ApiError) => handleError(error, 'Failed to delete blog post'),
  };
};

export const useUserErrorHandler = () => {
  const { handleError } = useQueryErrorHandler();

  return {
    handleFetchError: (error: ApiError) => handleError(error, 'User'),
    handleCreateError: (error: ApiError) => handleError(error, 'Failed to create user'),
    handleUpdateError: (error: ApiError) => handleError(error, 'Failed to update user'),
    handleDeleteError: (error: ApiError) => handleError(error, 'Failed to delete user'),
  };
};

export const useOrganizationErrorHandler = () => {
  const { handleError } = useQueryErrorHandler();

  return {
    handleFetchError: (error: ApiError) => handleError(error, 'Organization'),
    handleCreateError: (error: ApiError) => handleError(error, 'Failed to create organization'),
    handleUpdateError: (error: ApiError) => handleError(error, 'Failed to update organization'),
    handleDeleteError: (error: ApiError) => handleError(error, 'Failed to delete organization'),
  };
};

// Network status hook
export const useNetworkStatus = () => {
  const queryClient = useQueryClient();

  const handleOnline = () => {
    toast.success('Connection restored');
    queryClient.refetchQueries({
      type: 'inactive',
    });
  };

  const handleOffline = () => {
    toast.warning('Connection lost. Working in offline mode.');
  };

  // Set up event listeners
  if (typeof window !== 'undefined') {
    window.addEventListener('online', handleOnline);
    window.addEventListener('offline', handleOffline);
  }

  return {
    isOnline: typeof window !== 'undefined' ? navigator.onLine : true,
    handleOnline,
    handleOffline,
  };
};

// Query retry utilities
export const useQueryRetry = () => {
  const queryClient = useQueryClient();

  const retryQuery = (queryKey: any[]) => {
    queryClient.refetchQueries(queryKey);
  };

  const retryAllQueries = () => {
    queryClient.refetchQueries();
  };

  const retryFailedQueries = () => {
    queryClient.refetchQueries({
      type: 'inactive',
    });
  };

  return {
    retryQuery,
    retryAllQueries,
    retryFailedQueries,
  };
};