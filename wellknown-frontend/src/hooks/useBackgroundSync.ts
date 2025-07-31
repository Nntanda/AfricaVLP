import { useQueryClient } from 'react-query';
import { useEffect, useRef } from 'react';
import { queryKeys, backgroundSync } from '../config/queryClient';

// Background sync hook for well-known application
export const useBackgroundSync = () => {
  const queryClient = useQueryClient();
  const syncIntervalRef = useRef<NodeJS.Timeout | null>(null);
  const visibilityChangeRef = useRef<() => void>();

  // Sync user-specific data
  const syncUserData = () => {
    backgroundSync.syncUserData();
  };

  // Sync content data
  const syncContentData = () => {
    backgroundSync.syncContentData();
  };

  // Sync all data
  const syncAllData = () => {
    queryClient.invalidateQueries(queryKeys.blogPosts.all);
    queryClient.invalidateQueries(queryKeys.news.all);
    queryClient.invalidateQueries(queryKeys.events.all);
    queryClient.invalidateQueries(queryKeys.organizations.all);
    queryClient.invalidateQueries(queryKeys.resources.all);
    queryClient.invalidateQueries(queryKeys.profile.current);
  };

  // Sync specific data type
  const syncDataType = (type: 'blog' | 'news' | 'events' | 'organizations' | 'resources' | 'profile') => {
    switch (type) {
      case 'blog':
        queryClient.invalidateQueries(queryKeys.blogPosts.all);
        break;
      case 'news':
        queryClient.invalidateQueries(queryKeys.news.all);
        break;
      case 'events':
        queryClient.invalidateQueries(queryKeys.events.all);
        break;
      case 'organizations':
        queryClient.invalidateQueries(queryKeys.organizations.all);
        break;
      case 'resources':
        queryClient.invalidateQueries(queryKeys.resources.all);
        break;
      case 'profile':
        queryClient.invalidateQueries(queryKeys.profile.current);
        queryClient.invalidateQueries(queryKeys.profile.organizations);
        queryClient.invalidateQueries(queryKeys.profile.activities);
        break;
    }
  };

  // Setup periodic sync
  const setupPeriodicSync = (interval: number = 10 * 60 * 1000) => {
    if (syncIntervalRef.current) {
      clearInterval(syncIntervalRef.current);
    }

    syncIntervalRef.current = setInterval(() => {
      if (document.visibilityState === 'visible') {
        syncContentData();
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
        syncContentData();
        syncUserData();
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
      syncUserData();
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
    syncUserData,
    syncContentData,
    syncAllData,
    syncDataType,
    setupPeriodicSync,
    cleanup,
  };
};

// Content freshness sync hook
export const useContentFreshnessSync = () => {
  const queryClient = useQueryClient();

  const syncFeaturedContent = () => {
    queryClient.invalidateQueries(queryKeys.blogPosts.featured);
    queryClient.invalidateQueries(queryKeys.news.featured);
    queryClient.invalidateQueries(queryKeys.events.upcoming);
  };

  const syncCategorizedContent = (category: string) => {
    queryClient.invalidateQueries(['blog', 'category', category]);
    queryClient.invalidateQueries(['news', 'category', category]);
    queryClient.invalidateQueries(['resources', 'category', category]);
    queryClient.invalidateQueries(['organizations', 'category', category]);
  };

  const syncLocationContent = (location: string) => {
    queryClient.invalidateQueries(['events', 'location', location]);
    queryClient.invalidateQueries(['organizations', 'location', location]);
  };

  useEffect(() => {
    // Sync featured content every 15 minutes
    const interval = setInterval(() => {
      if (document.visibilityState === 'visible') {
        syncFeaturedContent();
      }
    }, 15 * 60 * 1000);

    return () => clearInterval(interval);
  }, []);

  return {
    syncFeaturedContent,
    syncCategorizedContent,
    syncLocationContent,
  };
};

// User activity sync hook
export const useUserActivitySync = () => {
  const queryClient = useQueryClient();
  const intervalRef = useRef<NodeJS.Timeout | null>(null);

  const startActivitySync = (interval: number = 2 * 60 * 1000) => {
    if (intervalRef.current) {
      clearInterval(intervalRef.current);
    }

    intervalRef.current = setInterval(() => {
      if (document.visibilityState === 'visible') {
        queryClient.invalidateQueries(queryKeys.profile.activities);
      }
    }, interval);

    return () => {
      if (intervalRef.current) {
        clearInterval(intervalRef.current);
      }
    };
  };

  const stopActivitySync = () => {
    if (intervalRef.current) {
      clearInterval(intervalRef.current);
      intervalRef.current = null;
    }
  };

  const syncUserActivities = () => {
    queryClient.invalidateQueries(queryKeys.profile.activities);
    queryClient.invalidateQueries(queryKeys.profile.organizations);
  };

  useEffect(() => {
    const cleanup = startActivitySync();
    return cleanup;
  }, []);

  return {
    startActivitySync,
    stopActivitySync,
    syncUserActivities,
  };
};

// Page-specific sync hook
export const usePageSync = (page: 'home' | 'blog' | 'news' | 'events' | 'organizations' | 'resources' | 'profile') => {
  const queryClient = useQueryClient();

  const syncPageData = () => {
    switch (page) {
      case 'home':
        queryClient.invalidateQueries(queryKeys.blogPosts.featured);
        queryClient.invalidateQueries(queryKeys.news.featured);
        queryClient.invalidateQueries(queryKeys.events.upcoming);
        break;
      case 'blog':
        queryClient.invalidateQueries(queryKeys.blogPosts.all);
        queryClient.invalidateQueries(queryKeys.blogPosts.categories);
        break;
      case 'news':
        queryClient.invalidateQueries(queryKeys.news.all);
        queryClient.invalidateQueries(queryKeys.news.categories);
        break;
      case 'events':
        queryClient.invalidateQueries(queryKeys.events.all);
        queryClient.invalidateQueries(queryKeys.events.upcoming);
        break;
      case 'organizations':
        queryClient.invalidateQueries(queryKeys.organizations.all);
        queryClient.invalidateQueries(queryKeys.organizations.directory);
        break;
      case 'resources':
        queryClient.invalidateQueries(queryKeys.resources.all);
        queryClient.invalidateQueries(queryKeys.resources.categories);
        break;
      case 'profile':
        queryClient.invalidateQueries(queryKeys.profile.current);
        queryClient.invalidateQueries(queryKeys.profile.organizations);
        queryClient.invalidateQueries(queryKeys.profile.activities);
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

// Smart sync hook that adapts based on user behavior
export const useSmartSync = () => {
  const queryClient = useQueryClient();
  const lastActivityRef = useRef<number>(Date.now());
  const syncFrequencyRef = useRef<number>(10 * 60 * 1000); // Start with 10 minutes

  const updateActivity = () => {
    lastActivityRef.current = Date.now();
    // Increase sync frequency for active users
    syncFrequencyRef.current = Math.max(2 * 60 * 1000, syncFrequencyRef.current * 0.8);
  };

  const adaptiveSyncInterval = () => {
    const timeSinceActivity = Date.now() - lastActivityRef.current;
    
    if (timeSinceActivity > 30 * 60 * 1000) {
      // User inactive for 30+ minutes, reduce sync frequency
      syncFrequencyRef.current = Math.min(30 * 60 * 1000, syncFrequencyRef.current * 1.5);
    }
    
    return syncFrequencyRef.current;
  };

  const smartSync = () => {
    const interval = adaptiveSyncInterval();
    
    setTimeout(() => {
      if (document.visibilityState === 'visible') {
        // Sync based on user activity patterns
        if (syncFrequencyRef.current <= 5 * 60 * 1000) {
          // High activity - sync everything
          queryClient.invalidateQueries();
        } else {
          // Low activity - sync only critical data
          queryClient.invalidateQueries(queryKeys.auth.profile);
          queryClient.invalidateQueries(queryKeys.blogPosts.featured);
          queryClient.invalidateQueries(queryKeys.news.featured);
        }
      }
      smartSync(); // Schedule next sync
    }, interval);
  };

  useEffect(() => {
    // Track user activity
    const events = ['click', 'scroll', 'keypress', 'mousemove'];
    
    const handleActivity = () => {
      updateActivity();
    };

    events.forEach(event => {
      document.addEventListener(event, handleActivity, { passive: true });
    });

    // Start smart sync
    smartSync();

    return () => {
      events.forEach(event => {
        document.removeEventListener(event, handleActivity);
      });
    };
  }, []);

  return {
    updateActivity,
    getCurrentSyncFrequency: () => syncFrequencyRef.current,
  };
};