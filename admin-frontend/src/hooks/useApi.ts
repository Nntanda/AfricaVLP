import { useState, useEffect } from 'react';
import { AxiosResponse } from 'axios';
import { handleApiError } from '../services/utils';

interface UseApiOptions {
  immediate?: boolean;
}

interface UseApiReturn<T> {
  data: T | null;
  loading: boolean;
  error: string | null;
  execute: (...args: any[]) => Promise<T>;
  reset: () => void;
}

const useApi = <T = any>(
  apiFunction: (...args: any[]) => Promise<AxiosResponse<T>>,
  options: UseApiOptions = {}
): UseApiReturn<T> => {
  const [data, setData] = useState<T | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const execute = async (...args: any[]): Promise<T> => {
    setLoading(true);
    setError(null);

    try {
      const response = await apiFunction(...args);
      setData(response.data);
      return response.data;
    } catch (err: any) {
      const apiError = handleApiError(err);
      setError(apiError.message);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const reset = () => {
    setData(null);
    setError(null);
    setLoading(false);
  };

  useEffect(() => {
    if (options.immediate) {
      execute();
    }
  }, []);

  return {
    data,
    loading,
    error,
    execute,
    reset,
  };
};

export default useApi;