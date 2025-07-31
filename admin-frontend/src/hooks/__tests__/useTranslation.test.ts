import { renderHook } from '@testing-library/react';
import { useTranslation } from '../useTranslation';

// Mock react-i18next
jest.mock('react-i18next', () => ({
  useTranslation: jest.fn(),
}));

const mockUseI18nTranslation = require('react-i18next').useTranslation;

describe('useTranslation hook', () => {
  const mockT = jest.fn((key: string) => key);
  const mockI18n = {
    language: 'en',
    changeLanguage: jest.fn(),
  };

  beforeEach(() => {
    mockUseI18nTranslation.mockReturnValue({
      t: mockT,
      i18n: mockI18n,
      ready: true,
    });
  });

  afterEach(() => {
    jest.clearAllMocks();
  });

  it('returns translation functions', () => {
    const { result } = renderHook(() => useTranslation());
    
    expect(result.current.t).toBeDefined();
    expect(result.current.i18n).toBeDefined();
    expect(result.current.translateContent).toBeDefined();
    expect(result.current.formatDate).toBeDefined();
    expect(result.current.formatNumber).toBeDefined();
    expect(result.current.getTextDirection).toBeDefined();
    expect(result.current.isRTL).toBeDefined();
  });

  it('translates dynamic content correctly', () => {
    const { result } = renderHook(() => useTranslation());
    
    // Test string content
    expect(result.current.translateContent('Hello')).toBe('Hello');
    
    // Test object content with current language
    const content = { en: 'Hello', fr: 'Bonjour' };
    expect(result.current.translateContent(content)).toBe('Hello');
  });
});  i
t('handles Arabic RTL correctly', () => {
    mockI18n.language = 'ar';
    const { result } = renderHook(() => useTranslation());
    
    expect(result.current.isRTL).toBe(true);
    expect(result.current.getTextDirection()).toBe('rtl');
  });

  it('handles LTR languages correctly', () => {
    mockI18n.language = 'en';
    const { result } = renderHook(() => useTranslation());
    
    expect(result.current.isRTL).toBe(false);
    expect(result.current.getTextDirection()).toBe('ltr');
  });

  it('formats dates with correct locale', () => {
    const { result } = renderHook(() => useTranslation());
    const testDate = new Date('2024-01-15');
    
    const formatted = result.current.formatDate(testDate);
    expect(formatted).toBeDefined();
  });

  it('formats numbers with correct locale', () => {
    const { result } = renderHook(() => useTranslation());
    
    const formatted = result.current.formatNumber(1234.56);
    expect(formatted).toBeDefined();
  });

  it('returns fallback for empty content', () => {
    const { result } = renderHook(() => useTranslation());
    
    expect(result.current.translateContent(null, 'fallback')).toBe('fallback');
    expect(result.current.translateContent('', 'fallback')).toBe('fallback');
  });
});