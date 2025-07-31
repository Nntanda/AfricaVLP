import { AxiosError } from 'axios';
import { ApiError } from '../types/common';

export const handleApiError = (error: AxiosError): ApiError => {
  if (error.response?.data) {
    const responseData = error.response.data as any;
    return {
      message: responseData.message || responseData.detail || 'An error occurred',
      code: responseData.code,
      details: responseData.errors || responseData.non_field_errors,
    };
  }
  
  if (error.request) {
    return {
      message: 'Network error. Please check your connection.',
      code: 'NETWORK_ERROR',
    };
  }
  
  return {
    message: error.message || 'An unexpected error occurred',
    code: 'UNKNOWN_ERROR',
  };
};

export const buildQueryString = (params: Record<string, any>): string => {
  const searchParams = new URLSearchParams();
  
  Object.entries(params).forEach(([key, value]) => {
    if (value !== undefined && value !== null && value !== '') {
      searchParams.append(key, String(value));
    }
  });
  
  return searchParams.toString();
};