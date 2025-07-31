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

// Admin endpoints
export const adminAPI = {
  getUsers: (params?: any) =>
    apiClient.get('/admin/users/', { params }),
  
  getOrganizations: (params?: any) =>
    apiClient.get('/admin/organizations/', { params }),
  
  getActivityLogs: (params?: any) =>
    apiClient.get('/admin/activity-logs/', { params }),
};

// Blog endpoints
export const blogAPI = {
  getPosts: (params?: any) =>
    apiClient.get('/blog/posts/', { params }),
  
  getPost: (id: string) =>
    apiClient.get(`/blog/posts/${id}/`),
  
  createPost: (data: any) =>
    apiClient.post('/blog/posts/', data),
  
  updatePost: (id: string, data: any) =>
    apiClient.put(`/blog/posts/${id}/`, data),
  
  deletePost: (id: string) =>
    apiClient.delete(`/blog/posts/${id}/`),
};

// News endpoints
export const newsAPI = {
  getArticles: (params?: any) =>
    apiClient.get('/news/articles/', { params }),
  
  getArticle: (id: string) =>
    apiClient.get(`/news/articles/${id}/`),
  
  createArticle: (data: any) =>
    apiClient.post('/news/articles/', data),
  
  updateArticle: (id: string, data: any) =>
    apiClient.put(`/news/articles/${id}/`, data),
  
  deleteArticle: (id: string) =>
    apiClient.delete(`/news/articles/${id}/`),
};