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
  });

  describe('getErrorMessage', () => {
    it('should return appropriate message for validation errors', () => {
      const error = { code: 'VALIDATION_ERROR', message: 'Invalid data' };
      const result = ApiErrorHandler.getErrorMessage(error);
      expect(result).toBe('Please check your input and try again');
    });

    it('should return appropriate message for network errors', () => {
      const error = { code: 'NETWORK_ERROR', message: 'Network failed' };
      const result = ApiErrorHandler.getErrorMessage(error);
      expect(result).toBe('Connection failed. Please check your internet connection');
    });
  });

  describe('isRetryableError', () => {
    it('should return true for network errors', () => {
      const error = { code: 'NETWORK_ERROR', message: 'Network failed' };
      expect(ApiErrorHandler.isRetryableError(error)).toBe(true);
    });

    it('should return false for validation errors', () => {
      const error = { code: 'VALIDATION_ERROR', message: 'Invalid data' };
      expect(ApiErrorHandler.isRetryableError(error)).toBe(false);
    });
  });
});