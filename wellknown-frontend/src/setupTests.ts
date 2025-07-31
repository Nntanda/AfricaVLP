import '@testing-library/jest-dom';

// Mock import.meta.env
Object.defineProperty(global, 'import', {
  value: {
    meta: {
      env: {
        VITE_API_BASE_URL: 'http://localhost:8000/api/v1',
        VITE_API_TIMEOUT: '10000',
        VITE_APP_NAME: 'AU-VLP Well-known Test',
        VITE_ENABLE_DEBUG: 'true',
        VITE_ENABLE_ANALYTICS: 'false',
        VITE_DEFAULT_LANGUAGE: 'en',
        VITE_SUPPORTED_LANGUAGES: 'en,fr,ar',
      },
    },
  },
});

// Mock localStorage
const localStorageMock = {
  getItem: jest.fn(),
  setItem: jest.fn(),
  removeItem: jest.fn(),
  clear: jest.fn(),
};
Object.defineProperty(window, 'localStorage', {
  value: localStorageMock,
});

// Mock window.location
Object.defineProperty(window, 'location', {
  value: {
    href: 'http://localhost:3000',
    pathname: '/',
    assign: jest.fn(),
    replace: jest.fn(),
    reload: jest.fn(),
    ancestorOrigins: {} as DOMStringList,
    hash: '',
    host: 'localhost:3000',
    hostname: 'localhost',
    origin: 'http://localhost:3000',
    port: '3000',
    protocol: 'http:',
    search: '',
    toString: () => 'http://localhost:3000',
  },
  writable: true,
});

// Mock console methods to reduce noise in tests
Object.defineProperty(global, 'console', {
  value: {
    ...console,
    warn: jest.fn(),
    error: jest.fn(),
  },
});