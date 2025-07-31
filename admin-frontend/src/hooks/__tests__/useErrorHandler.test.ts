import { renderHook, act } from '@testing-library/react';
import { AxiosError } from 'axios';
import { useErrorHandler } from '../useErrorHandler';
import { useToast } from '../../context/ToastContext';

// Mock the toast context
jest.mock('../../context/ToastContext');
const mockUseToast = useToast as jest.MockedFunction<typeof useToast>;

describe('useErrorHandler', () => {
  const mockAddToast = jest.fn();

  beforeEach(() => {
    mockUseToast.mockReturnValue({
      toasts: [],
      addToast: mockAddToast,
      removeToast: jest.fn(),
      clearToasts: jest.fn()
    });
    mockAddToast.mockClear();
  });

  it('should handle AxiosError and show error toast', () => {
    const { result } = renderHook(() => useErrorHandler());

    const axiosError = {
      response: {
        status: 400,
        data: {
          error: {
            code: 'VALIDATION_ERROR',
            message: 'Invalid input data'
          }
        }
      }
    } as AxiosError;

    act(() => {
      result.current.handleError(axiosError, 'form submission');
    });

    expect(mockAddToast).toHaveBeenCalledWith({
      type: 'error',
      title: 'Error',
      message: 'form submission: Please check your input and try again',
      duration: 5000,
      action: undefined
    });
  });

  it('should handle network errors with retry action', () => {
    const { result } = renderHook(() => useErrorHandler());

    const networkError = {
      request: {},
      response: undefined
    } as AxiosError;

    // Mock window.location.reload
    const mockReload = jest.fn();
    Object.defineProperty(window, 'location', {
      value: { reload: mockReload },
      writable: true
    });

    act(() => {
      result.current.handleError(networkError);
    });

    expect(mockAddToast).toHaveBeenCalledWith({
      type: 'error',
      title: 'Error',
      message: 'Connection failed. Please check your internet connection',
      duration: 0, // Keep network errors visible
      action: {
        label: 'Retry',
        onClick: expect.any(Function)
      }
    });
  });

  it('should handle success messages', () => {
    const { result } = renderHook(() => useErrorHandler());

    act(() => {
      result.current.handleSuccess('Data saved successfully');
    });

    expect(mockAddToast).toHaveBeenCalledWith({
      type: 'success',
      title: 'Success',
      message: 'Data saved successfully',
      duration: 3000
    });
  });

  it('should handle warning messages', () => {
    const { result } = renderHook(() => useErrorHandler());

    act(() => {
      result.current.handleWarning('This action cannot be undone', 'Warning');
    });

    expect(mockAddToast).toHaveBeenCalledWith({
      type: 'warning',
      title: 'Warning',
      message: 'This action cannot be undone',
      duration: 4000
    });
  });

  it('should handle info messages', () => {
    const { result } = renderHook(() => useErrorHandler());

    act(() => {
      result.current.handleInfo('New features available');
    });

    expect(mockAddToast).toHaveBeenCalledWith({
      type: 'info',
      title: 'Info',
      message: 'New features available',
      duration: 4000
    });
  });

  it('should handle ApiError objects directly', () => {
    const { result } = renderHook(() => useErrorHandler());

    const apiError = {
      code: 'SERVER_ERROR',
      message: 'Internal server error',
      status: 500
    };

    act(() => {
      result.current.handleError(apiError);
    });

    expect(mockAddToast).toHaveBeenCalledWith({
      type: 'error',
      title: 'Error',
      message: 'Server error. Please try again later',
      duration: 5000,
      action: {
        label: 'Retry',
        onClick: expect.any(Function)
      }
    });
  });
});