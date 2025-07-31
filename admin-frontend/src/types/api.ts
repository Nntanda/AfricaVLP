// API type definitions
export interface ApiResponse<T = any> {
  data: T;
  status: number;
  message?: string;
}

export interface ApiErrorResponse {
  error: {
    message: string;
    code?: string;
    details?: Record<string, string[]>;
  };
}

export interface QueryParams {
  page?: number;
  page_size?: number;
  search?: string;
  ordering?: string;
  [key: string]: any;
}