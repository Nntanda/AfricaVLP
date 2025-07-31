// API response types
export interface ApiResponse<T = any> {
  data: T;
  message?: string;
  status: number;
}

export interface ApiError {
  message: string;
  code?: string;
  details?: Record<string, string[]>;
  status?: number;
}

export interface PaginatedResponse<T> {
  results: T[];
  count: number;
  next: string | null;
  previous: string | null;
}

export interface ApiRequestConfig {
  params?: Record<string, any>;
  headers?: Record<string, string>;
  timeout?: number;
}