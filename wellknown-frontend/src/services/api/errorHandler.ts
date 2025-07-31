import { AxiosError } from 'axios';

export interface ApiError {
  code: string;
  message: string;
  details?: Record<string, string[]>;
  status?: number;
}

export interface ErrorResponse {
  error: ApiError;
}

export class ApiErrorHandler {
  static handleError(error: AxiosError): ApiError {
    if (error.response) {
      // Server responded with error status
      const data = error.response.data as ErrorResponse;
      return {
        code: data.error?.code || 'SERVER_ERROR',
        message: data.error?.message || 'An error occurred on the server',
        details: data.error?.details,
        status: error.response.status,
      };
    } else if (error.request) {
      // Network error
      return {
        code: 'NETWORK_ERROR',
        message: 'Unable to connect to the server. Please check your internet connection.',
        status: 0,
      };
    } else {
      // Request setup error
      return {
        code: 'REQUEST_ERROR',
        message: error.message || 'An unexpected error occurred',
      };
    }
  }

  static getErrorMessage(error: ApiError): string {
    switch (error.code) {
      case 'VALIDATION_ERROR':
        return 'Please check your input and try again';
      case 'AUTHENTICATION_ERROR':
        return 'Please log in to continue';
      case 'AUTHORIZATION_ERROR':
        return 'You do not have permission to perform this action';
      case 'NOT_FOUND':
        return 'The requested resource was not found';
      case 'NETWORK_ERROR':
        return 'Connection failed. Please check your internet connection';
      case 'SERVER_ERROR':
        return 'Server error. Please try again later';
      default:
        return error.message || 'An unexpected error occurred';
    }
  }

  static isRetryableError(error: ApiError): boolean {
    return ['NETWORK_ERROR', 'SERVER_ERROR'].includes(error.code) || 
           (error.status !== undefined && error.status >= 500);
  }
}