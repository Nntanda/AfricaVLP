import { useTranslation as useI18nTranslation } from 'react-i18next';
import { useMemo } from 'react';

// Extended hook for dynamic content translation
export const useTranslation = (namespace?: string) => {
  const { t, i18n, ready } = useI18nTranslation(namespace);

  // Helper function to translate dynamic content from API
  const translateContent = useMemo(() => {
    return (content: any, fallbackKey?: string) => {
      if (!content) return fallbackKey ? t(fallbackKey) : '';
      
      // If content is a string, return as is
      if (typeof content === 'string') {
        return content;
      }
      
      // If content is an object with translations
      if (typeof content === 'object') {
        const currentLang = i18n.language;
        
        // Try current language first
        if (content[currentLang]) {
          return content[currentLang];
        }
        
        // Try fallback languages
        if (content.en) {
          return content.en;
        }
        
        // Try any available language
        const availableKeys = Object.keys(content);
        if (availableKeys.length > 0) {
          return content[availableKeys[0]];
        }
      }
      
      return fallbackKey ? t(fallbackKey) : '';
    };
  }, [t, i18n.language]);

  // Helper function to get localized date format
  const formatDate = useMemo(() => {
    return (date: Date | string, options?: Intl.DateTimeFormatOptions) => {
      const dateObj = typeof date === 'string' ? new Date(date) : date;
      const locale = i18n.language === 'ar' ? 'ar-SA' : i18n.language;
      
      const defaultOptions: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        ...options,
      };
      
      return new Intl.DateTimeFormat(locale, defaultOptions).format(dateObj);
    };
  }, [i18n.language]);

  // Helper function to get localized number format
  const formatNumber = useMemo(() => {
    return (number: number, options?: Intl.NumberFormatOptions) => {
      const locale = i18n.language === 'ar' ? 'ar-SA' : i18n.language;
      return new Intl.NumberFormat(locale, options).format(number);
    };
  }, [i18n.language]);

  // Helper function to get text direction
  const getTextDirection = useMemo(() => {
    return () => i18n.language === 'ar' ? 'rtl' : 'ltr';
  }, [i18n.language]);

  // Helper function to check if current language is RTL
  const isRTL = useMemo(() => {
    return i18n.language === 'ar';
  }, [i18n.language]);

  return {
    t,
    i18n,
    ready,
    translateContent,
    formatDate,
    formatNumber,
    getTextDirection,
    isRTL,
    currentLanguage: i18n.language,
  };
};

export default useTranslation;