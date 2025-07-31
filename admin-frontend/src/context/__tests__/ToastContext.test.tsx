import React from 'react';
import { render, screen, act, waitFor } from '@testing-library/react';
import { ToastProvider, useToast } from '../ToastContext';

// Test component that uses the toast context
const TestComponent: React.FC = () => {
  const { toasts, addToast, removeToast, clearToasts } = useToast();

  return (
    <div>
      <div data-testid="toast-count">{toasts.length}</div>
      <button
        onClick={() => addToast({
          type: 'success',
          title: 'Success',
          message: 'Operation completed',
          duration: 1000
        })}
        data-testid="add-success-toast"
      >
        Add Success Toast
      </button>
      <button
        onClick={() => addToast({
          type: 'error',
          title: 'Error',
          message: 'Something went wrong',
          duration: 0 // Persistent toast
        })}
        data-testid="add-error-toast"
      >
        Add Error Toast
      </button>
      <button
        onClick={() => toasts.length > 0 && removeToast(toasts[0].id)}
        data-testid="remove-toast"
      >
        Remove First Toast
      </button>
      <button
        onClick={clearToasts}
        data-testid="clear-toasts"
      >
        Clear All Toasts
      </button>
      {toasts.map(toast => (
        <div key={toast.id} data-testid={`toast-${toast.type}`}>
          {toast.title}: {toast.message}
        </div>
      ))}
    </div>
  );
};

describe('ToastContext', () => {
  beforeEach(() => {
    jest.useFakeTimers();
  });

  afterEach(() => {
    jest.runOnlyPendingTimers();
    jest.useRealTimers();
  });

  it('should provide toast context to children', () => {
    render(
      <ToastProvider>
        <TestComponent />
      </ToastProvider>
    );

    expect(screen.getByTestId('toast-count')).toHaveTextContent('0');
  });

  it('should add toasts', () => {
    render(
      <ToastProvider>
        <TestComponent />
      </ToastProvider>
    );

    act(() => {
      screen.getByTestId('add-success-toast').click();
    });

    expect(screen.getByTestId('toast-count')).toHaveTextContent('1');
    expect(screen.getByTestId('toast-success')).toHaveTextContent('Success: Operation completed');
  });

  it('should remove toasts manually', () => {
    render(
      <ToastProvider>
        <TestComponent />
      </ToastProvider>
    );

    act(() => {
      screen.getByTestId('add-success-toast').click();
    });

    expect(screen.getByTestId('toast-count')).toHaveTextContent('1');

    act(() => {
      screen.getByTestId('remove-toast').click();
    });

    expect(screen.getByTestId('toast-count')).toHaveTextContent('0');
  });

  it('should auto-remove toasts after duration', async () => {
    render(
      <ToastProvider>
        <TestComponent />
      </ToastProvider>
    );

    act(() => {
      screen.getByTestId('add-success-toast').click();
    });

    expect(screen.getByTestId('toast-count')).toHaveTextContent('1');

    // Fast-forward time by 1000ms (the duration set in the test)
    act(() => {
      jest.advanceTimersByTime(1000);
    });

    await waitFor(() => {
      expect(screen.getByTestId('toast-count')).toHaveTextContent('0');
    });
  });

  it('should not auto-remove persistent toasts', async () => {
    render(
      <ToastProvider>
        <TestComponent />
      </ToastProvider>
    );

    act(() => {
      screen.getByTestId('add-error-toast').click();
    });

    expect(screen.getByTestId('toast-count')).toHaveTextContent('1');

    // Fast-forward time by a large amount
    act(() => {
      jest.advanceTimersByTime(10000);
    });

    // Toast should still be there since duration is 0
    expect(screen.getByTestId('toast-count')).toHaveTextContent('1');
  });

  it('should clear all toasts', () => {
    render(
      <ToastProvider>
        <TestComponent />
      </ToastProvider>
    );

    act(() => {
      screen.getByTestId('add-success-toast').click();
      screen.getByTestId('add-error-toast').click();
    });

    expect(screen.getByTestId('toast-count')).toHaveTextContent('2');

    act(() => {
      screen.getByTestId('clear-toasts').click();
    });

    expect(screen.getByTestId('toast-count')).toHaveTextContent('0');
  });

  it('should throw error when used outside provider', () => {
    // Suppress console.error for this test
    const consoleSpy = jest.spyOn(console, 'error').mockImplementation(() => {});

    expect(() => {
      render(<TestComponent />);
    }).toThrow('useToast must be used within a ToastProvider');

    consoleSpy.mockRestore();
  });
});