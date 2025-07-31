import { useEffect } from 'react';
import { BrowserRouter as Router } from 'react-router-dom';
import { QueryClientProvider } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';
import { queryClient, prefetchStrategies, backgroundSync } from './config/queryClient';
import ErrorBoundary from './components/common/ErrorBoundary';
import { AuthProvider } from './context/AuthContext';
import { ToastProvider, ToastContainer } from './context/ToastContext';
import { useOfflineDetection } from './hooks/useOfflineDetection';
import AppRoutes from './routes';
import './i18n';
import './App.css';

const AppContent: React.FC = () => {
  useOfflineDetection(); // Initialize offline detection

  useEffect(() => {
    // Prefetch common data on app load
    prefetchStrategies.prefetchCommonData();
    
    // Setup background sync
    const cleanup = backgroundSync.setupPeriodicSync();
    
    // Set initial document direction based on stored language
    const storedLanguage = localStorage.getItem('i18nextLng');
    if (storedLanguage === 'ar') {
      document.documentElement.dir = 'rtl';
      document.documentElement.lang = 'ar';
    } else {
      document.documentElement.dir = 'ltr';
      document.documentElement.lang = storedLanguage || 'en';
    }
    
    return cleanup;
  }, []);

  return (
    <Router>
      <div className="App">
        <AppRoutes />
        <ToastContainer />
      </div>
    </Router>
  );
};

function App() {
  return (
    <ErrorBoundary>
      <QueryClientProvider client={queryClient}>
        <ToastProvider>
          <AuthProvider>
            <AppContent />
          </AuthProvider>
        </ToastProvider>
        <ReactQueryDevtools initialIsOpen={false} />
      </QueryClientProvider>
    </ErrorBoundary>
  );
}

export default App;