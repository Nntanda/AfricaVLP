import { useState, useCallback } from 'react';
import { ApiError, ApiErrorHandler } from '../services/api/errorHandler';

interface RetryOptions {
  maxAttempts?: number;
  delay?: number;
  backoff?: boolean;
}

export const useRetry = () => {
  const [retryCount, setRetryCount] = useState(0);
  const [isRetrying, setIsRetrying] = useState(false);

  const retry = useCallback(async <T>(
    operation: () => Promise<T>,
    options: RetryOptions = {}
  ): Promise<T> => {
    const { maxAttempts = 3, delay = 1000, backoff = true } = options;
    
    let lastError: ApiError | null = null;
    
    for (let attempt = 0; attempt < maxAttempts; attempt++) {
      try {
        setRetryCount(attempt);
        setIsRetrying(attempt > 0);
        
        const result = await operation();
        
        // Reset on success
        setRetryCount(0);
        setIsRetrying(false);
        
        return result;
      } catch (error: any) {
        lastError = error;
        
        // Don't retry if it's not a retryable error
        if (!ApiErrorHandler.isRetryableError(error)) {
          break;
        }
        
        // Don't retry on last attempt
        if (attempt === maxAttempts - 1) {
          break;
        }
        
        // Calculate delay with optional backoff
        const currentDelay = backoff ? delay * Math.pow(2, attempt) : delay;
        await new Promise(resolve => setTimeout(resolve, currentDelay));
      }
    }
    
    setIsRetrying(false);
    throw lastError;
  }, []);

  const reset = useCallback(() => {
    setRetryCount(0);
    setIsRetrying(false);
  }, []);

  return {
    retry,
    retryCount,
    isRetrying,
    reset
  };
};