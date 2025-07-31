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
export const useContentErrorHandler = () => {
  const { handleError } = useQueryErrorHandler();

  return {
    handleBlogError: (error: ApiError) => handleError(error, 'Blog post'),
    handleNewsError: (error: ApiError) => handleError(error, 'News article'),
    handleEventError: (error: ApiError) => handleError(error, 'Event'),
    handleResourceError: (error: ApiError) => handleError(error, 'Resource'),
    handleOrganizationError: (error: ApiError) => handleError(error, 'Organization'),
  };
};

export const useProfileErrorHandler = () => {
  const { handleError } = useQueryErrorHandler();

  return {
    handleFetchError: (error: ApiError) => handleError(error, 'Profile'),
    handleUpdateError: (error: ApiError) => handleError(error, 'Failed to update profile'),
    handleMembershipError: (error: ApiError) => handleError(error, 'Membership request failed'),
    handleRegistrationError: (error: ApiError) => handleError(error, 'Event registration failed'),
  };
};

export const useFormErrorHandler = () => {
  const { handleError } = useQueryErrorHandler();

  return {
    handleContactError: (error: ApiError) => handleError(error, 'Failed to send message'),
    handleNewsletterError: (error: ApiError) => handleError(error, 'Newsletter subscription failed'),
    handleSearchError: (error: ApiError) => handleError(error, 'Search failed'),
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
    toast.warning('Connection lost. Some features may not work properly.');
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

  const retryWithBackoff = async (queryKey: any[], maxRetries: number = 3) => {
    for (let i = 0; i < maxRetries; i++) {
      try {
        await queryClient.refetchQueries(queryKey);
        break;
      } catch (error) {
        if (i === maxRetries - 1) throw error;
        // Exponential backoff
        await new Promise(resolve => setTimeout(resolve, Math.pow(2, i) * 1000));
      }
    }
  };

  return {
    retryQuery,
    retryAllQueries,
    retryFailedQueries,
    retryWithBackoff,
  };
};