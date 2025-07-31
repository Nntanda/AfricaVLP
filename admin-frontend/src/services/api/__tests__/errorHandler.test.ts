import { AxiosError } from 'axios';
import { ApiErrorHandler } from '../errorHandler';

describe('ApiErrorHandler', () => {
  describe('handleError', () => {
    it('should handle server response errors', () => {
      const axiosError = {
        response: {
          status: 400,
          data: {
            error: {
              code: 'VALIDATION_ERROR',
              message: 'Invalid input data',
              details: { email: ['This field is required'] }
            }
          }
        }
      } as AxiosError;

      const result = ApiErrorHandler.handleError(axiosError);

      expect(result).toEqual({
        code: 'VALIDATION_ERROR',
        message: 'Invalid input data',
        details: { email: ['This field is required'] },
        status: 400
      });
    });

    it('should handle network errors', () => {
      const axiosError = {
        request: {},
        response: undefined
      } as AxiosError;

      const result = ApiErrorHandler.handleError(axiosError);

      expect(result).toEqual({
        code: 'NETWORK_ERROR',
        message: 'Unable to connect to the server. Please check your internet connection.',
        status: 0
      });
    });

    it('should handle request setup errors', () => {
      const axiosError = {
        message: 'Request failed to setup'
      } as AxiosError;

      const result = ApiErrorHandler.handleError(axiosError);

      expect(result).toEqual({
        code: 'REQUEST_ERROR',
        message: 'Request failed to setup'
      });
    });

    it('should handle server errors without proper error structure', () => {
      const axiosError = {
        response: {
          status: 500,
          data: {}
        }
      } as AxiosError;

      const result = ApiErrorHandler.handleError(axiosError);

      expect(result).toEqual({
        code: 'SERVER_ERROR',
        message: 'An error occurred on the server',
        details: undefined,
        status: 500
      });
    });
  });

  describe('getErrorMessage', () => {
    it('should return appropriate message for validation errors', () => {
      const error = { code: 'VALIDATION_ERROR', message: 'Invalid data' };
      const result = ApiErrorHandler.getErrorMessage(error);
      expect(result).toBe('Please check your input and try again');
    });

    it('should return appropriate message for authentication errors', () => {
      const error = { code: 'AUTHENTICATION_ERROR', message: 'Unauthorized' };
      const result = ApiErrorHandler.getErrorMessage(error);
      expect(result).toBe('Please log in to continue');
    });

    it('should return appropriate message for authorization errors', () => {
      const error = { code: 'AUTHORIZATION_ERROR', message: 'Forbidden' };
      const result = ApiErrorHandler.getErrorMessage(error);
      expect(result).toBe('You do not have permission to perform this action');
    });

    it('should return appropriate message for not found errors', () => {
      const error = { code: 'NOT_FOUND', message: 'Resource not found' };
      const result = ApiErrorHandler.getErrorMessage(error);
      expect(result).toBe('The requested resource was not found');
    });

    it('should return appropriate message for network errors', () => {
      const error = { code: 'NETWORK_ERROR', message: 'Network failed' };
      const result = ApiErrorHandler.getErrorMessage(error);
      expect(result).toBe('Connection failed. Please check your internet connection');
    });

    it('should return appropriate message for server errors', () => {
      const error = { code: 'SERVER_ERROR', message: 'Internal server error' };
      const result = ApiErrorHandler.getErrorMessage(error);
      expect(result).toBe('Server error. Please try again later');
    });

    it('should return original message for unknown error codes', () => {
      const error = { code: 'UNKNOWN_ERROR', message: 'Something went wrong' };
      const result = ApiErrorHandler.getErrorMessage(error);
      expect(result).toBe('Something went wrong');
    });

    it('should return default message when no message is provided', () => {
      const error = { code: 'UNKNOWN_ERROR', message: '' };
      const result = ApiErrorHandler.getErrorMessage(error);
      expect(result).toBe('An unexpected error occurred');
    });
  });

  describe('isRetryableError', () => {
    it('should return true for network errors', () => {
      const error = { code: 'NETWORK_ERROR', message: 'Network failed' };
      expect(ApiErrorHandler.isRetryableError(error)).toBe(true);
    });

    it('should return true for server errors', () => {
      const error = { code: 'SERVER_ERROR', message: 'Server error' };
      expect(ApiErrorHandler.isRetryableError(error)).toBe(true);
    });

    it('should return true for 5xx status codes', () => {
      const error = { code: 'UNKNOWN_ERROR', message: 'Error', status: 500 };
      expect(ApiErrorHandler.isRetryableError(error)).toBe(true);
    });

    it('should return false for validation errors', () => {
      const error = { code: 'VALIDATION_ERROR', message: 'Invalid data' };
      expect(ApiErrorHandler.isRetryableError(error)).toBe(false);
    });

    it('should return false for 4xx status codes', () => {
      const error = { code: 'UNKNOWN_ERROR', message: 'Error', status: 400 };
      expect(ApiErrorHandler.isRetryableError(error)).toBe(false);
    });
  });
});