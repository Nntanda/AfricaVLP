// Application constants
export const API_ENDPOINTS = {
  AUTH: {
    LOGIN: '/auth/login/',
    LOGOUT: '/auth/logout/',
    REFRESH: '/auth/refresh/',
    PROFILE: '/auth/profile/',
  },
  BLOG: {
    POSTS: '/blog/posts/',
    CATEGORIES: '/blog/categories/',
  },
  NEWS: {
    ARTICLES: '/news/articles/',
    CATEGORIES: '/news/categories/',
  },
  EVENTS: '/events/',
  ORGANIZATIONS: '/organizations/',
  RESOURCES: '/resources/',
  USERS: '/users/',
} as const;

export const ROUTES = {
  HOME: '/',
  LOGIN: '/login',
  BLOG: '/blog',
  NEWS: '/news',
  EVENTS: '/events',
  ORGANIZATIONS: '/organizations',
  RESOURCES: '/resources',
  PROFILE: '/profile',
} as const;

export const STORAGE_KEYS = {
  ACCESS_TOKEN: 'access_token',
  REFRESH_TOKEN: 'refresh_token',
  USER_DATA: 'user_data',
  LANGUAGE: 'language',
} as const;

export const DEFAULT_LANGUAGE = 'en';

export const SUPPORTED_LANGUAGES = [
  { code: 'en', name: 'English' },
  { code: 'fr', name: 'Français' },
  { code: 'ar', name: 'العربية' },
] as const;

export const PAGINATION = {
  DEFAULT_PAGE_SIZE: 10,
  MAX_PAGE_SIZE: 100,
} as const;