import { renderHook, act } from '@testing-library/react';
import { useBreakpoint } from '../useBreakpoint';

// Mock window.innerWidth
const mockInnerWidth = (width: number) => {
  Object.defineProperty(window, 'innerWidth', {
    writable: true,
    configurable: true,
    value: width,
  });
};

// Mock window.addEventListener and removeEventListener
const mockAddEventListener = jest.fn();
const mockRemoveEventListener = jest.fn();

Object.defineProperty(window, 'addEventListener', {
  writable: true,
  value: mockAddEventListener,
});

Object.defineProperty(window, 'removeEventListener', {
  writable: true,
  value: mockRemoveEventListener,
});

describe('useBreakpoint', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('should initialize with correct breakpoint for mobile', () => {
    mockInnerWidth(375);
    const { result } = renderHook(() => useBreakpoint());

    expect(result.current.currentBreakpoint).toBe('xs');
    expect(result.current.isMobile).toBe(true);
    expect(result.current.isTablet).toBe(false);
    expect(result.current.isDesktop).toBe(false);
  });

  it('should initialize with correct breakpoint for tablet', () => {
    mockInnerWidth(768);
    const { result } = renderHook(() => useBreakpoint());

    expect(result.current.currentBreakpoint).toBe('md');
    expect(result.current.isMobile).toBe(false);
    expect(result.current.isTablet).toBe(true);
    expect(result.current.isDesktop).toBe(false);
  });

  it('should initialize with correct breakpoint for desktop', () => {
    mockInnerWidth(1280);
    const { result } = renderHook(() => useBreakpoint());

    expect(result.current.currentBreakpoint).toBe('xl');
    expect(result.current.isMobile).toBe(false);
    expect(result.current.isTablet).toBe(false);
    expect(result.current.isDesktop).toBe(true);
  });

  it('should detect all breakpoints correctly', () => {
    const testCases = [
      { width: 320, expected: 'xs' },
      { width: 640, expected: 'sm' },
      { width: 768, expected: 'md' },
      { width: 1024, expected: 'lg' },
      { width: 1280, expected: 'xl' },
      { width: 1536, expected: '2xl' },
    ];

    testCases.forEach(({ width, expected }) => {
      mockInnerWidth(width);
      const { result } = renderHook(() => useBreakpoint());
      expect(result.current.currentBreakpoint).toBe(expected);
    });
  });

  it('should provide correct breakpoint comparison methods', () => {
    mockInnerWidth(1024);
    const { result } = renderHook(() => useBreakpoint());

    expect(result.current.isBreakpoint('lg')).toBe(true);
    expect(result.current.isBreakpoint('md')).toBe(false);
    
    expect(result.current.isAboveBreakpoint('md')).toBe(true);
    expect(result.current.isAboveBreakpoint('xl')).toBe(false);
    
    expect(result.current.isBelowBreakpoint('xl')).toBe(true);
    expect(result.current.isBelowBreakpoint('md')).toBe(false);
  });

  it('should register and cleanup event listeners', () => {
    const { unmount } = renderHook(() => useBreakpoint());

    expect(mockAddEventListener).toHaveBeenCalledWith('resize', expect.any(Function));

    unmount();

    expect(mockRemoveEventListener).toHaveBeenCalledWith('resize', expect.any(Function));
  });

  it('should update breakpoint on window resize', () => {
    mockInnerWidth(375);
    const { result } = renderHook(() => useBreakpoint());

    expect(result.current.currentBreakpoint).toBe('xs');

    // Simulate window resize
    mockInnerWidth(1024);
    act(() => {
      const resizeHandler = mockAddEventListener.mock.calls.find(
        call => call[0] === 'resize'
      )?.[1];
      if (resizeHandler) {
        resizeHandler();
      }
    });

    expect(result.current.currentBreakpoint).toBe('lg');
    expect(result.current.windowWidth).toBe(1024);
  });
});