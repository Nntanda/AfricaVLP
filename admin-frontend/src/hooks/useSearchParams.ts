import { useState, useEffect, useCallback } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { SearchFilters, SearchParams } from '../types/search';

interface UseSearchParamsReturn {
  searchParams: SearchParams;
  updateSearchParams: (params: Partial<SearchParams>) => void;
  clearSearchParams: () => void;
  getSearchParamsFromUrl: () => SearchParams;
  setSearchParamsInUrl: (params: SearchParams) => void;
}

export const useSearchParams = (): UseSearchParamsReturn => {
  const location = useLocation();
  const navigate = useNavigate();
  const [searchParams, setSearchParams] = useState<SearchParams>({
    query: '',
    page: 1,
    pageSize: 20
  });

  // Parse search parameters from URL
  const getSearchParamsFromUrl = useCallback((): SearchParams => {
    const urlParams = new URLSearchParams(location.search);
    
    const params: SearchParams = {
      query: urlParams.get('q') || '',
      page: parseInt(urlParams.get('page') || '1', 10),
      pageSize: parseInt(urlParams.get('page_size') || '20', 10)
    };

    // Parse filter parameters
    const filters: SearchFilters = {};
    
    const categories = urlParams.get('categories');
    if (categories) {
      filters.categories = categories.split(',').map(id => parseInt(id, 10)).filter(id => !isNaN(id));
    }

    const tags = urlParams.get('tags');
    if (tags) {
      filters.tags = tags.split(',').map(id => parseInt(id, 10)).filter(id => !isNaN(id));
    }

    const organizationId = urlParams.get('organization_id');
    if (organizationId) {
      filters.organizationId = parseInt(organizationId, 10);
    }

    const dateFrom = urlParams.get('date_from');
    if (dateFrom) {
      filters.dateFrom = dateFrom;
    }

    const dateTo = urlParams.get('date_to');
    if (dateTo) {
      filters.dateTo = dateTo;
    }

    const status = urlParams.get('status');
    if (status) {
      filters.status = status;
    }

    const language = urlParams.get('language');
    if (language) {
      filters.language = language;
    }

    const countryId = urlParams.get('country_id');
    if (countryId) {
      filters.countryId = parseInt(countryId, 10);
    }

    const cityId = urlParams.get('city_id');
    if (cityId) {
      filters.cityId = parseInt(cityId, 10);
    }

    const eventType = urlParams.get('event_type');
    if (eventType) {
      filters.eventType = eventType;
    }

    const organizationType = urlParams.get('organization_type');
    if (organizationType) {
      filters.organizationType = organizationType;
    }

    const resourceType = urlParams.get('resource_type');
    if (resourceType) {
      filters.resourceType = resourceType;
    }

    return { ...params, ...filters };
  }, [location.search]);

  // Set search parameters in URL
  const setSearchParamsInUrl = useCallback((params: SearchParams) => {
    const urlParams = new URLSearchParams();

    // Add basic parameters
    if (params.query) {
      urlParams.set('q', params.query);
    }
    if (params.page && params.page > 1) {
      urlParams.set('page', params.page.toString());
    }
    if (params.pageSize && params.pageSize !== 20) {
      urlParams.set('page_size', params.pageSize.toString());
    }

    // Add filter parameters
    if (params.categories?.length) {
      urlParams.set('categories', params.categories.join(','));
    }
    if (params.tags?.length) {
      urlParams.set('tags', params.tags.join(','));
    }
    if (params.organizationId) {
      urlParams.set('organization_id', params.organizationId.toString());
    }
    if (params.dateFrom) {
      urlParams.set('date_from', params.dateFrom);
    }
    if (params.dateTo) {
      urlParams.set('date_to', params.dateTo);
    }
    if (params.status) {
      urlParams.set('status', params.status);
    }
    if (params.language) {
      urlParams.set('language', params.language);
    }
    if (params.countryId) {
      urlParams.set('country_id', params.countryId.toString());
    }
    if (params.cityId) {
      urlParams.set('city_id', params.cityId.toString());
    }
    if (params.eventType) {
      urlParams.set('event_type', params.eventType);
    }
    if (params.organizationType) {
      urlParams.set('organization_type', params.organizationType);
    }
    if (params.resourceType) {
      urlParams.set('resource_type', params.resourceType);
    }

    const newUrl = `${location.pathname}?${urlParams.toString()}`;
    navigate(newUrl, { replace: true });
  }, [location.pathname, navigate]);

  // Update search parameters
  const updateSearchParams = useCallback((newParams: Partial<SearchParams>) => {
    const updatedParams = { ...searchParams, ...newParams };
    setSearchParams(updatedParams);
    setSearchParamsInUrl(updatedParams);
  }, [searchParams, setSearchParamsInUrl]);

  // Clear search parameters
  const clearSearchParams = useCallback(() => {
    const clearedParams: SearchParams = {
      query: '',
      page: 1,
      pageSize: 20
    };
    setSearchParams(clearedParams);
    navigate(location.pathname, { replace: true });
  }, [location.pathname, navigate]);

  // Initialize search parameters from URL on mount and location change
  useEffect(() => {
    const paramsFromUrl = getSearchParamsFromUrl();
    setSearchParams(paramsFromUrl);
  }, [getSearchParamsFromUrl]);

  return {
    searchParams,
    updateSearchParams,
    clearSearchParams,
    getSearchParamsFromUrl,
    setSearchParamsInUrl
  };
};