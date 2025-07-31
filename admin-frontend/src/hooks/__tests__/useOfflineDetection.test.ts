import { renderHook } from '@testing-library/react';
import { useOfflineDetection } from '../useOfflineDetection';
import { useErrorHandler } from '../useErrorHandler';

// Mock the error handler
jest.mock('../useErrorHandler');
const mockUseErrorHandler = useErrorHandler as jest.MockedFunction<typeof useErrorHandler>;

describe('useOfflineDetection', () => {
  const mockHandleWarning = jest.fn();
  const mockHandleSuccess = jest.fn();

  beforeEach(() => {
    mockUseErrorHandler.mockReturnValue({
      handleError: jest.fn(),
      handleSuccess: mockHandleSuccess,
      handleWarning: mockHandleWarning,
      handleInfo: jest.fn()
    });
    mockHandleWarning.mockClear();
    mockHandleSuccess.mockClear();
  });

  it('should initialize with current online status', () => {
    // Mock navigator.onLine
    Object.defineProperty(navigator, 'onLine', {
      writable: true,
      value: true
    });

    const { result } = renderHook(() => useOfflineDetection());

    expect(result.current.isOnline).toBe(true);
    expect(result.current.wasOffline).toBe(false);
  });

  it('should handle going offline', () => {
    Object.defineProperty(navigator, 'onLine', {
      writable: true,
      value: true
    });

    const { result } = renderHook(() => useOfflineDetection());

    // Simulate going offline
    Object.defineProperty(navigator, 'onLine', {
      writable: true,
      value: false
    });

    // Trigger offline event
    const offlineEvent = new Event('offline');
    window.dispatchEvent(offlineEvent);

    expect(result.current.isOnline).toBe(false);
    expect(result.current.wasOffline).toBe(true);
    expect(mockHandleWarning).toHaveBeenCalledWith(
      'You are currently offline. Some features may not be available.',
      'No Internet Connection'
    );
  });

  it('should handle coming back online', () => {
    Object.defineProperty(navigator, 'onLine', {
      writable: true,
      value: false
    });

    const { result } = renderHook(() => useOfflineDetection());

    // First go offline to set wasOffline to true
    const offlineEvent = new Event('offline');
    window.dispatchEvent(offlineEvent);

    expect(result.current.wasOffline).toBe(true);

    // Now come back online
    Object.defineProperty(navigator, 'onLine', {
      writable: true,
      value: true
    });

    const onlineEvent = new Event('online');
    window.dispatchEvent(onlineEvent);

    expect(result.current.isOnline).toBe(true);
    expect(result.current.wasOffline).toBe(false);
    expect(mockHandleSuccess).toHaveBeenCalledWith(
      'Connection restored',
      'Back Online'
    );
  });

  it('should not show "back online" message if was never offline', () => {
    Object.defineProperty(navigator, 'onLine', {
      writable: true,
      value: true
    });

    renderHook(() => useOfflineDetection());

    // Trigger online event without ever going offline
    const onlineEvent = new Event('online');
    window.dispatchEvent(onlineEvent);

    expect(mockHandleSuccess).not.toHaveBeenCalled();
  });

  it('should clean up event listeners on unmount', () => {
    const removeEventListenerSpy = jest.spyOn(window, 'removeEventListener');

    const { unmount } = renderHook(() => useOfflineDetection());

    unmount();

    expect(removeEventListenerSpy).toHaveBeenCalledWith('online', expect.any(Function));
    expect(removeEventListenerSpy).toHaveBeenCalledWith('offline', expect.any(Function));

    removeEventListenerSpy.mockRestore();
  });
});