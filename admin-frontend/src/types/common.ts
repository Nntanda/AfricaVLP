// Common type definitions
export interface User {
  id: string;
  username: string;
  email: string;
  first_name: string;
  last_name: string;
  role: 'super_admin' | 'admin' | 'user';
  is_active: boolean;
  created_at: string;
  updated_at: string;
}

export interface Organization {
  id: string;
  name: string;
  description?: string;
  website?: string;
  email?: string;
  phone?: string;
  address?: string;
  city_id?: string;
  country_id?: string;
  created_at: string;
  updated_at: string;
}

export interface BlogPost {
  id: string;
  title: string;
  content: string;
  excerpt?: string;
  featured_image?: string;
  status: 'draft' | 'published' | 'archived';
  author_id: string;
  organization_id?: string;
  created_at: string;
  updated_at: string;
  published_at?: string;
}

export interface Event {
  id: string;
  title: string;
  description: string;
  start_date: string;
  end_date: string;
  location?: string;
  organization_id?: string;
  created_at: string;
  updated_at: string;
}

export interface Resource {
  id: string;
  title: string;
  description?: string;
  file_url?: string;
  category?: string;
  organization_id?: string;
  created_at: string;
  updated_at: string;
}

export interface ActivityLog {
  id: string;
  action: string;
  description: string;
  user_id?: string;
  admin_id?: string;
  ip_address?: string;
  user_agent?: string;
  created_at: string;
}

export interface PaginatedResponse<T> {
  count: number;
  next?: string;
  previous?: string;
  results: T[];
}

export interface ApiError {
  message: string;
  code?: string;
  details?: Record<string, string[]>;
}