// Common types for the well-known application
export interface User {
  id: number;
  username: string;
  email: string;
  first_name: string;
  last_name: string;
  organization_id?: number;
  is_active: boolean;
  created_at: string;
  updated_at: string;
}

export interface Organization {
  id: number;
  name: string;
  description?: string;
  website?: string;
  email?: string;
  phone?: string;
  address?: string;
  city_id?: number;
  country_id?: number;
  is_active: boolean;
  created_at: string;
  updated_at: string;
}

export interface BlogPost {
  id: number;
  title: string;
  content: string;
  excerpt?: string;
  featured_image?: string;
  author_id: number;
  organization_id?: number;
  is_published: boolean;
  published_at?: string;
  created_at: string;
  updated_at: string;
  categories: Category[];
  tags: Tag[];
}

export interface News {
  id: number;
  title: string;
  content: string;
  excerpt?: string;
  featured_image?: string;
  author_id: number;
  is_published: boolean;
  published_at?: string;
  created_at: string;
  updated_at: string;
  categories: Category[];
  tags: Tag[];
}

export interface Event {
  id: number;
  title: string;
  description: string;
  start_date: string;
  end_date: string;
  location?: string;
  city_id?: number;
  organization_id?: number;
  is_active: boolean;
  created_at: string;
  updated_at: string;
}

export interface Resource {
  id: number;
  title: string;
  description?: string;
  file_url?: string;
  resource_type: string;
  organization_id?: number;
  is_active: boolean;
  created_at: string;
  updated_at: string;
  categories: Category[];
}

export interface Category {
  id: number;
  name: string;
  slug: string;
  description?: string;
  type: 'blog' | 'news' | 'resource';
}

export interface Tag {
  id: number;
  name: string;
  slug: string;
}

export interface ApiResponse<T> {
  data: T;
  message?: string;
  status: 'success' | 'error';
}

export interface PaginatedResponse<T> {
  data: T[];
  pagination: {
    current_page: number;
    total_pages: number;
    total_items: number;
    items_per_page: number;
  };
}

export interface AuthUser {
  id: number;
  username: string;
  email: string;
  first_name: string;
  last_name: string;
  organization?: Organization;
  permissions: string[];
}

export interface LoginCredentials {
  username: string;
  password: string;
}

export interface AuthTokens {
  access: string;
  refresh: string;
}