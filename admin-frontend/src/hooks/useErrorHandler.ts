import { useCallback } from 'react';
import { AxiosError } from 'axios';
import { useToast } from '../context/ToastContext';
import { ApiErrorHandler, ApiError } from '../services/api/errorHandler';

export const useErrorHandler = () => {
  const { addToast } = useToast();

  const handleError = useCallback((error: AxiosError | ApiError, context?: string) => {
    let apiError: ApiError;
    
    if ('response' in error || 'request' in error) {
      // It's an AxiosError
      apiError = ApiErrorHandler.handleError(error as AxiosError);
    } else {
      // It's already an ApiError
      apiError = error as ApiError;
    }

    // Log error for debugging
    console.error(`Error ${context ? `in ${context}` : ''}:`, apiError);

    // Show user-friendly error message
    const message = ApiErrorHandler.getErrorMessage(apiError);
    
    addToast({
      type: 'error',
      title: 'Error',
      message: context ? `${context}: ${message}` : message,
      duration: apiError.code === 'NETWORK_ERROR' ? 0 : 5000, // Keep network errors visible
      action: ApiErrorHandler.isRetryableError(apiError) ? {
        label: 'Retry',
        onClick: () => window.location.reload()
      } : undefined
    });

    return apiError;
  }, [addToast]);

  const handleSuccess = useCallback((message: string, title = 'Success') => {
    addToast({
      type: 'success',
      title,
      message,
      duration: 3000
    });
  }, [addToast]);

  const handleWarning = useCallback((message: string, title = 'Warning') => {
    addToast({
      type: 'warning',
      title,
      message,
      duration: 4000
    });
  }, [addToast]);

  const handleInfo = useCallback((message: string, title = 'Info') => {
    addToast({
      type: 'info',
      title,
      message,
      duration: 4000
    });
  }, [addToast]);

  return {
    handleError,
    handleSuccess,
    handleWarning,
    handleInfo
  };
};