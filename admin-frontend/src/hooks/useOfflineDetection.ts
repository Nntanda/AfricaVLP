import { useState, useEffect } from 'react';
import { useErrorHandler } from './useErrorHandler';

export const useOfflineDetection = () => {
  const [isOnline, setIsOnline] = useState(navigator.onLine);
  const [wasOffline, setWasOffline] = useState(false);
  const { handleWarning, handleSuccess } = useErrorHandler();

  useEffect(() => {
    const handleOnline = () => {
      setIsOnline(true);
      if (wasOffline) {
        handleSuccess('Connection restored', 'Back Online');
        setWasOffline(false);
      }
    };

    const handleOffline = () => {
      setIsOnline(false);
      setWasOffline(true);
      handleWarning(
        'You are currently offline. Some features may not be available.',
        'No Internet Connection'
      );
    };

    window.addEventListener('online', handleOnline);
    window.addEventListener('offline', handleOffline);

    return () => {
      window.removeEventListener('online', handleOnline);
      window.removeEventListener('offline', handleOffline);
    };
  }, [wasOffline, handleWarning, handleSuccess]);

  return { isOnline, wasOffline };
};