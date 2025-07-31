// Environment configuration
export const config = {
  api: {
    baseUrl: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/v1',
    timeout: parseInt(import.meta.env.VITE_API_TIMEOUT || '10000'),
  },
  app: {
    name: import.meta.env.VITE_APP_NAME || 'AU-VLP Well-known',
    version: import.meta.env.VITE_APP_VERSION || '1.0.0',
  },
  features: {
    analytics: import.meta.env.VITE_ENABLE_ANALYTICS === 'true',
    debug: import.meta.env.VITE_ENABLE_DEBUG === 'true',
  },
  services: {
    googleAnalyticsId: import.meta.env.VITE_GOOGLE_ANALYTICS_ID,
    sentryDsn: import.meta.env.VITE_SENTRY_DSN,
  },
  i18n: {
    defaultLanguage: import.meta.env.VITE_DEFAULT_LANGUAGE || 'en',
    supportedLanguages: (import.meta.env.VITE_SUPPORTED_LANGUAGES || 'en,fr,ar').split(','),
  },
} as const;

export default config;