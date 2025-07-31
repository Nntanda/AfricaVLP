import { renderHook, act } from '@testing-library/react';
import { useRetry } from '../useRetry';
import { ApiErrorHandler } from '../../services/api/errorHandler';

// Mock the ApiErrorHandler
jest.mock('../../services/api/errorHandler');
const mockApiErrorHandler = ApiErrorHandler as jest.Mocked<typeof ApiErrorHandler>;

describe('useRetry', () => {
  beforeEach(() => {
    jest.useFakeTimers();
    mockApiErrorHandler.isRetryableError.mockClear();
  });

  afterEach(() => {
    jest.runOnlyPendingTimers();
    jest.useRealTimers();
  });

  it('should successfully execute operation on first try', async () => {
    const { result } = renderHook(() => useRetry());
    const mockOperation = jest.fn().mockResolvedValue('success');

    let operationResult: string;
    await act(async () => {
      operationResult = await result.current.retry(mockOperation);
    });

    expect(operationResult!).toBe('success');
    expect(mockOperation).toHaveBeenCalledTimes(1);
    expect(result.current.retryCount).toBe(0);
    expect(result.current.isRetrying).toBe(false);
  });

  it('should retry on retryable errors', async () => {
    const { result } = renderHook(() => useRetry());
    const error = { code: 'NETWORK_ERROR', message: 'Network failed' };
    const mockOperation = jest.fn()
      .mockRejectedValueOnce(error)
      .mockRejectedValueOnce(error)
      .mockResolvedValue('success');

    mockApiErrorHandler.isRetryableError.mockReturnValue(true);

    let operationResult: string;
    const retryPromise = act(async () => {
      const promise = result.current.retry(mockOperation, { delay: 100 });
      
      // Advance timers to trigger retries
      jest.advanceTimersByTime(100);
      jest.advanceTimersByTime(100);
      
      operationResult = await promise;
    });

    await retryPromise;

    expect(operationResult!).toBe('success');
    expect(mockOperation).toHaveBeenCalledTimes(3);
  });

  it('should not retry on non-retryable errors', async () => {
    const { result } = renderHook(() => useRetry());
    const error = { code: 'VALIDATION_ERROR', message: 'Invalid data' };
    const mockOperation = jest.fn().mockRejectedValue(error);

    mockApiErrorHandler.isRetryableError.mockReturnValue(false);

    await act(async () => {
      try {
        await result.current.retry(mockOperation);
      } catch (e) {
        expect(e).toBe(error);
      }
    });

    expect(mockOperation).toHaveBeenCalledTimes(1);
  });

  it('should respect maxAttempts option', async () => {
    const { result } = renderHook(() => useRetry());
    const error = { code: 'NETWORK_ERROR', message: 'Network failed' };
    const mockOperation = jest.fn().mockRejectedValue(error);

    mockApiErrorHandler.isRetryableError.mockReturnValue(true);

    await act(async () => {
      try {
        const promise = result.current.retry(mockOperation, { maxAttempts: 2, delay: 100 });
        
        // Advance timer for the retry delay
        jest.advanceTimersByTime(100);
        
        await promise;
      } catch (e) {
        expect(e).toBe(error);
      }
    });

    expect(mockOperation).toHaveBeenCalledTimes(2);
  });

  it('should use exponential backoff when enabled', async () => {
    const { result } = renderHook(() => useRetry());
    const error = { code: 'NETWORK_ERROR', message: 'Network failed' };
    const mockOperation = jest.fn().mockRejectedValue(error);

    mockApiErrorHandler.isRetryableError.mockReturnValue(true);

    const startTime = Date.now();
    jest.spyOn(Date, 'now').mockImplementation(() => startTime);

    await act(async () => {
      try {
        const promise = result.current.retry(mockOperation, { 
          maxAttempts: 3, 
          delay: 100, 
          backoff: true 
        });
        
        // First retry: 100ms delay
        jest.advanceTimersByTime(100);
        // Second retry: 200ms delay (100 * 2^1)
        jest.advanceTimersByTime(200);
        
        await promise;
      } catch (e) {
        expect(e).toBe(error);
      }
    });

    expect(mockOperation).toHaveBeenCalledTimes(3);
  });

  it('should reset retry state', () => {
    const { result } = renderHook(() => useRetry());

    act(() => {
      result.current.reset();
    });

    expect(result.current.retryCount).toBe(0);
    expect(result.current.isRetrying).toBe(false);
  });

  it('should track retry count and retrying state', async () => {
    const { result } = renderHook(() => useRetry());
    const error = { code: 'NETWORK_ERROR', message: 'Network failed' };
    const mockOperation = jest.fn()
      .mockRejectedValueOnce(error)
      .mockResolvedValue('success');

    mockApiErrorHandler.isRetryableError.mockReturnValue(true);

    await act(async () => {
      const promise = result.current.retry(mockOperation, { delay: 100 });
      
      // Check state during retry
      expect(result.current.retryCount).toBe(0);
      expect(result.current.isRetrying).toBe(false);
      
      jest.advanceTimersByTime(100);
      
      await promise;
    });

    // After successful completion
    expect(result.current.retryCount).toBe(0);
    expect(result.current.isRetrying).toBe(false);
  });
});