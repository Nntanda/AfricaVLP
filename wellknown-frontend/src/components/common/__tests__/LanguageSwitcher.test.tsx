import React from 'react';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { useTranslation } from 'react-i18next';
import LanguageSwitcher from '../LanguageSwitcher';

// Mock react-i18next
jest.mock('react-i18next', () => ({
  useTranslation: jest.fn(),
}));

const mockUseTranslation = useTranslation as jest.MockedFunction<typeof useTranslation>;

describe('LanguageSwitcher', () => {
  const mockChangeLanguage = jest.fn();
  const mockI18n = {
    language: 'en',
    changeLanguage: mockChangeLanguage,
  };

  beforeEach(() => {
    mockUseTranslation.mockReturnValue({
      i18n: mockI18n,
      t: jest.fn(),
      ready: true,
    } as any);
    
    // Mock document properties
    Object.defineProperty(document.documentElement, 'dir', {
      writable: true,
      value: 'ltr',
    });
    Object.defineProperty(document.documentElement, 'lang', {
      writable: true,
      value: 'en',
    });
  });

  afterEach(() => {
    jest.clearAllMocks();
  });

  it('renders with current language', () => {
    render(<LanguageSwitcher />);
    
    expect(screen.getByText('🇺🇸')).toBeInTheDocument();
    // English text might be hidden on small screens
  });

  it('shows dropdown when clicked', () => {
    render(<LanguageSwitcher />);
    
    const button = screen.getByRole('button');
    fireEvent.click(button);
    
    expect(screen.getByText('Français')).toBeInTheDocument();
    expect(screen.getByText('العربية')).toBeInTheDocument();
  });

  it('changes language when option is selected', async () => {
    render(<LanguageSwitcher />);
    
    const button = screen.getByRole('button');
    fireEvent.click(button);
    
    const frenchOption = screen.getByText('Français');
    fireEvent.click(frenchOption);
    
    await waitFor(() => {
      expect(mockChangeLanguage).toHaveBeenCalledWith('fr');
    });
  });

  it('sets RTL direction for Arabic', async () => {
    render(<LanguageSwitcher />);
    
    const button = screen.getByRole('button');
    fireEvent.click(button);
    
    const arabicOption = screen.getByText('العربية');
    fireEvent.click(arabicOption);
    
    await waitFor(() => {
      expect(mockChangeLanguage).toHaveBeenCalledWith('ar');
      expect(document.documentElement.dir).toBe('rtl');
      expect(document.documentElement.lang).toBe('ar');
    });
  });

  it('sets LTR direction for non-Arabic languages', async () => {
    mockI18n.language = 'ar';
    render(<LanguageSwitcher />);
    
    const button = screen.getByRole('button');
    fireEvent.click(button);
    
    const englishOption = screen.getByText('English');
    fireEvent.click(englishOption);
    
    await waitFor(() => {
      expect(mockChangeLanguage).toHaveBeenCalledWith('en');
      expect(document.documentElement.dir).toBe('ltr');
      expect(document.documentElement.lang).toBe('en');
    });
  });

  it('closes dropdown after language selection', async () => {
    render(<LanguageSwitcher />);
    
    const button = screen.getByRole('button');
    fireEvent.click(button);
    
    expect(screen.getByText('Français')).toBeInTheDocument();
    
    const frenchOption = screen.getByText('Français');
    fireEvent.click(frenchOption);
    
    await waitFor(() => {
      expect(screen.queryByText('Français')).not.toBeInTheDocument();
    });
  });

  it('shows checkmark for current language', () => {
    render(<LanguageSwitcher />);
    
    const button = screen.getByRole('button');
    fireEvent.click(button);
    
    const englishOption = screen.getByText('English').closest('button');
    expect(englishOption).toHaveClass('bg-blue-50', 'text-blue-700');
  });
});