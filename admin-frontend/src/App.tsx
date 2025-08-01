import { useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { QueryClientProvider } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';
import { queryClient, prefetchStrategies, backgroundSync } from './config/queryClient';
import Dashboard from './pages/Dashboard';
import Login from './pages/Login';
import BlogManagement from './pages/BlogManagement';
import UserManagement from './pages/UserManagement';
import OrganizationManagement from './pages/OrganizationManagement';
import ActivityLogs from './pages/ActivityLogs';
import ErrorBoundary from './components/common/ErrorBoundary';
import ProtectedRoute from './components/auth/ProtectedRoute';
import { AuthProvider } from './context/AuthContext';
import { ToastProvider } from './context/ToastContext';
import { ToastContainer } from './components/common/Toast';
import { useOfflineDetection } from './hooks/useOfflineDetection';
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
                <Routes>
                    <Route path="/login" element={<Login />} />
                    <Route 
                        path="/" 
                        element={
                            <ProtectedRoute>
                                <Dashboard />
                            </ProtectedRoute>
                        } 
                    />
                    <Route 
                        path="/blog" 
                        element={
                            <ProtectedRoute>
                                <BlogManagement />
                            </ProtectedRoute>
                        } 
                    />
                    <Route 
                        path="/users" 
                        element={
                            <ProtectedRoute requiredRole="super_admin">
                                <UserManagement />
                            </ProtectedRoute>
                        } 
                    />
                    <Route 
                        path="/organizations" 
                        element={
                            <ProtectedRoute>
                                <OrganizationManagement />
                            </ProtectedRoute>
                        } 
                    />
                    <Route 
                        path="/activity-logs" 
                        element={
                            <ProtectedRoute>
                                <ActivityLogs />
                            </ProtectedRoute>
                        } 
                    />
                </Routes>
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