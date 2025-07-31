import { useQueryClient } from 'react-query';
import { useEffect, useRef } from 'react';
import { queryKeys, backgroundSync } from '../config/queryClient';

// Background sync hook for admin application
export const useBackgroundSync = () => {
  const queryClient = useQueryClient();
  const syncIntervalRef = useRef<NodeJS.Timeout | null>(null);
  const visibilityChangeRef = useRef<() => void>();

  // Sync critical admin data
  const syncCriticalData = () => {
    backgroundSync.syncCriticalData();
  };

  // Sync all admin data
  const syncAllData = () => {
    queryClient.invalidateQueries(queryKeys.blogPosts.all);
    queryClient.invalidateQueries(queryKeys.users.all);
    queryClient.invalidateQueries(queryKeys.organizations.all);
    queryClient.invalidateQueries(queryKeys.events.all);
    queryClient.invalidateQueries(queryKeys.resources.all);
    queryClient.invalidateQueries(queryKeys.news.all);
  };

  // Sync specific data type
  const syncDataType = (type: 'blog' | 'users' | 'organizations' | 'events' | 'resources' | 'news' | 'activity') => {
    switch (type) {
      case 'blog':
        queryClient.invalidateQueries(queryKeys.blogPosts.all);
        break;
      case 'users':
        queryClient.invalidateQueries(queryKeys.users.all);
        break;
      case 'organizations':
        queryClient.invalidateQueries(queryKeys.organizations.all);
        break;
      case 'events':
        queryClient.invalidateQueries(queryKeys.events.all);
        break;
      case 'resources':
        queryClient.invalidateQueries(queryKeys.resources.all);
        break;
      case 'news':
        queryClient.invalidateQueries(queryKeys.news.all);
        break;
      case 'activity':
        queryClient.invalidateQueries(queryKeys.activityLogs.all);
        break;
    }
  };

  // Setup periodic sync
  const setupPeriodicSync = (interval: number = 5 * 60 * 1000) => {
    if (syncIntervalRef.current) {
      clearInterval(syncIntervalRef.current);
    }

    syncIntervalRef.current = setInterval(() => {
      if (document.visibilityState === 'visible') {
        syncCriticalData();
      }
    }, interval);

    return () => {
      if (syncIntervalRef.current) {
        clearInterval(syncIntervalRef.current);
      }
    };
  };

  // Setup visibility change sync
  const setupVisibilitySync = () => {
    const handleVisibilityChange = () => {
      if (document.visibilityState === 'visible') {
        // Sync when user returns to tab
        syncCriticalData();
      }
    };

    document.addEventListener('visibilitychange', handleVisibilityChange);
    visibilityChangeRef.current = handleVisibilityChange;

    return () => {
      document.removeEventListener('visibilitychange', handleVisibilityChange);
    };
  };

  // Setup focus sync
  const setupFocusSync = () => {
    const handleFocus = () => {
      syncCriticalData();
    };

    window.addEventListener('focus', handleFocus);

    return () => {
      window.removeEventListener('focus', handleFocus);
    };
  };

  // Setup online sync
  const setupOnlineSync = () => {
    const handleOnline = () => {
      // Sync all data when coming back online
      syncAllData();
    };

    window.addEventListener('online', handleOnline);

    return () => {
      window.removeEventListener('online', handleOnline);
    };
  };

  // Cleanup function
  const cleanup = () => {
    if (syncIntervalRef.current) {
      clearInterval(syncIntervalRef.current);
    }
    if (visibilityChangeRef.current) {
      document.removeEventListener('visibilitychange', visibilityChangeRef.current);
    }
  };

  useEffect(() => {
    const cleanupPeriodic = setupPeriodicSync();
    const cleanupVisibility = setupVisibilitySync();
    const cleanupFocus = setupFocusSync();
    const cleanupOnline = setupOnlineSync();

    return () => {
      cleanupPeriodic();
      cleanupVisibility();
      cleanupFocus();
      cleanupOnline();
      cleanup();
    };
  }, []);

  return {
    syncCriticalData,
    syncAllData,
    syncDataType,
    setupPeriodicSync,
    cleanup,
  };
};

// Real-time sync hook for activity logs
export const useActivityLogSync = () => {
  const queryClient = useQueryClient();
  const intervalRef = useRef<NodeJS.Timeout | null>(null);

  const startRealTimeSync = (interval: number = 30 * 1000) => {
    if (intervalRef.current) {
      clearInterval(intervalRef.current);
    }

    intervalRef.current = setInterval(() => {
      if (document.visibilityState === 'visible') {
        queryClient.invalidateQueries(queryKeys.activityLogs.all);
      }
    }, interval);

    return () => {
      if (intervalRef.current) {
        clearInterval(intervalRef.current);
      }
    };
  };

  const stopRealTimeSync = () => {
    if (intervalRef.current) {
      clearInterval(intervalRef.current);
      intervalRef.current = null;
    }
  };

  useEffect(() => {
    const cleanup = startRealTimeSync();
    return cleanup;
  }, []);

  return {
    startRealTimeSync,
    stopRealTimeSync,
  };
};

// Selective sync hook for specific pages
export const usePageSync = (page: 'dashboard' | 'blog' | 'users' | 'organizations' | 'events' | 'resources' | 'activity') => {
  const queryClient = useQueryClient();

  const syncPageData = () => {
    switch (page) {
      case 'dashboard':
        queryClient.invalidateQueries(queryKeys.auth.profile);
        queryClient.invalidateQueries(queryKeys.activityLogs.lists());
        queryClient.invalidateQueries(queryKeys.blogPosts.lists());
        break;
      case 'blog':
        queryClient.invalidateQueries(queryKeys.blogPosts.all);
        queryClient.invalidateQueries(queryKeys.blogPosts.categories);
        queryClient.invalidateQueries(queryKeys.blogPosts.tags);
        break;
      case 'users':
        queryClient.invalidateQueries(queryKeys.users.all);
        break;
      case 'organizations':
        queryClient.invalidateQueries(queryKeys.organizations.all);
        break;
      case 'events':
        queryClient.invalidateQueries(queryKeys.events.all);
        break;
      case 'resources':
        queryClient.invalidateQueries(queryKeys.resources.all);
        break;
      case 'activity':
        queryClient.invalidateQueries(queryKeys.activityLogs.all);
        break;
    }
  };

  useEffect(() => {
    const handleFocus = () => {
      syncPageData();
    };

    window.addEventListener('focus', handleFocus);

    return () => {
      window.removeEventListener('focus', handleFocus);
    };
  }, [page]);

  return {
    syncPageData,
  };
};

// Batch sync hook for multiple operations
export const useBatchSync = () => {
  const queryClient = useQueryClient();

  const batchSync = (operations: Array<() => void>) => {
    // Batch multiple sync operations
    queryClient.getQueryCache().batch(() => {
      operations.forEach(operation => operation());
    });
  };

  const batchInvalidate = (queryKeys: any[][]) => {
    queryClient.getQueryCache().batch(() => {
      queryKeys.forEach(key => {
        queryClient.invalidateQueries(key);
      });
    });
  };

  const batchRefetch = (queryKeys: any[][]) => {
    queryClient.getQueryCache().batch(() => {
      queryKeys.forEach(key => {
        queryClient.refetchQueries(key);
      });
    });
  };

  return {
    batchSync,
    batchInvalidate,
    batchRefetch,
  };
};