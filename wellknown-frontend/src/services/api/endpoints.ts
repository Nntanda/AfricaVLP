import apiClient from './client';

// Auth endpoints
export const authAPI = {
  login: (credentials: { username: string; password: string }) =>
    apiClient.post('/auth/login/', credentials),
  
  logout: () =>
    apiClient.post('/auth/logout/'),
  
  refresh: (refreshToken: string) =>
    apiClient.post('/auth/refresh/', { refresh: refreshToken }),
  
  profile: () =>
    apiClient.get('/auth/profile/'),
};

// Blog endpoints
export const blogAPI = {
  getPosts: (params?: any) =>
    apiClient.get('/blog/posts/', { params }),
  
  getPost: (id: string) =>
    apiClient.get(`/blog/posts/${id}/`),
};

// News endpoints
export const newsAPI = {
  getArticles: (params?: any) =>
    apiClient.get('/news/articles/', { params }),
  
  getArticle: (id: string) =>
    apiClient.get(`/news/articles/${id}/`),
};

// Events endpoints
export const eventsAPI = {
  getEvents: (params?: any) =>
    apiClient.get('/events/', { params }),
  
  getEvent: (id: string) =>
    apiClient.get(`/events/${id}/`),
};

// Organizations endpoints
export const organizationsAPI = {
  getOrganizations: (params?: any) =>
    apiClient.get('/organizations/', { params }),
  
  getOrganization: (id: string) =>
    apiClient.get(`/organizations/${id}/`),
};

// Resources endpoints
export const resourcesAPI = {
  getResources: (params?: any) =>
    apiClient.get('/resources/', { params }),
  
  getResource: (id: string) =>
    apiClient.get(`/resources/${id}/`),
};

// Users endpoints
export const usersAPI = {
  getProfile: () =>
    apiClient.get('/users/profile/'),
  
  updateProfile: (data: any) =>
    apiClient.put('/users/profile/', data),
};