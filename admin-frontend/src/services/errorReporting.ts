import { ApiError } from './api/errorHandler';

export interface ErrorReport {
  error: ApiError;
  context?: string;
  userId?: string;
  timestamp: string;
  userAgent: string;
  url: string;
  stackTrace?: string;
}

class ErrorReportingService {
  private reports: ErrorReport[] = [];
  private maxReports = 100;

  logError(error: ApiError, context?: string, userId?: string): void {
    const report: ErrorReport = {
      error,
      context,
      userId,
      timestamp: new Date().toISOString(),
      userAgent: navigator.userAgent,
      url: window.location.href,
      stackTrace: error instanceof Error ? error.stack : undefined
    };

    // Add to local storage for persistence
    this.reports.unshift(report);
    if (this.reports.length > this.maxReports) {
      this.reports = this.reports.slice(0, this.maxReports);
    }

    // Store in localStorage
    try {
      localStorage.setItem('errorReports', JSON.stringify(this.reports));
    } catch (e) {
      console.warn('Failed to store error reports in localStorage:', e);
    }

    // Log to console in development
    if (process.env.NODE_ENV === 'development') {
      console.error('Error Report:', report);
    }

    // In production, you would send this to your error reporting service
    // this.sendToErrorService(report);
  }

  getReports(): ErrorReport[] {
    return [...this.reports];
  }

  clearReports(): void {
    this.reports = [];
    try {
      localStorage.removeItem('errorReports');
    } catch (e) {
      console.warn('Failed to clear error reports from localStorage:', e);
    }
  }

  private async sendToErrorService(report: ErrorReport): Promise<void> {
    // This would be implemented to send to your error reporting service
    // e.g., Sentry, LogRocket, Bugsnag, etc.
    try {
      // Example implementation:
      // await fetch('/api/errors', {
      //   method: 'POST',
      //   headers: { 'Content-Type': 'application/json' },
      //   body: JSON.stringify(report)
      // });
    } catch (e) {
      console.warn('Failed to send error report:', e);
    }
  }

  // Load reports from localStorage on initialization
  init(): void {
    try {
      const stored = localStorage.getItem('errorReports');
      if (stored) {
        this.reports = JSON.parse(stored);
      }
    } catch (e) {
      console.warn('Failed to load error reports from localStorage:', e);
    }
  }
}

export const errorReportingService = new ErrorReportingService();

// Initialize on module load
errorReportingService.init();