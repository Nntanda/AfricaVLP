# Nginx Reverse Proxy Optimizations

This document outlines the optimizations implemented in the nginx configuration for the AU-VLP system.

## Overview

The nginx configuration has been optimized to provide:
- Proper upstream health checks
- Better error handling and fallback mechanisms
- WebSocket support for development servers
- Static file serving with proper caching headers
- Enhanced security and performance

## Key Optimizations

### 1. Upstream Health Checks

```nginx
upstream admin_backend {
    server admin-backend:8000 max_fails=3 fail_timeout=30s;
    keepalive 32;
}
```

- **max_fails=3**: Mark server as unavailable after 3 failed attempts
- **fail_timeout=30s**: Wait 30 seconds before retrying failed server
- **keepalive=32**: Maintain 32 persistent connections to upstream

### 2. Error Handling and Fallback Mechanisms

```nginx
# Error handling
proxy_next_upstream error timeout invalid_header http_500 http_502 http_503 http_504;
proxy_next_upstream_tries 3;
proxy_next_upstream_timeout 30s;

# Custom error pages
error_page 502 503 504 /50x.html;
```

- Automatic failover to next upstream server on errors
- Maximum 3 retry attempts with 30-second timeout
- Custom error pages for better user experience

### 3. WebSocket Support for Development

```nginx
# Map for WebSocket upgrade
map $http_upgrade $connection_upgrade {
    default upgrade;
    '' close;
}

# WebSocket configuration
proxy_http_version 1.1;
proxy_set_header Upgrade $http_upgrade;
proxy_set_header Connection $connection_upgrade;
proxy_cache_bypass $http_upgrade;
```

- Proper WebSocket upgrade handling
- Support for Vite HMR (Hot Module Replacement)
- Long-lived connections for development servers

### 4. Static File Caching

```nginx
# Static assets with aggressive caching
location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
    add_header Vary "Accept-Encoding";
}

# Media files with moderate caching
location /media/ {
    expires 30d;
    add_header Cache-Control "public";
}
```

- 1-year caching for static assets (JS, CSS, images, fonts)
- 30-day caching for media files
- Proper cache headers for browser optimization

### 5. Performance Optimizations

```nginx
# Basic optimizations
sendfile on;
tcp_nopush on;
tcp_nodelay on;
keepalive_timeout 65;
client_max_body_size 100M;

# Gzip compression
gzip on;
gzip_comp_level 6;
gzip_types text/plain text/css application/javascript application/json;
```

- Efficient file serving with sendfile
- TCP optimizations for better network performance
- Gzip compression for text-based content

### 6. Rate Limiting

```nginx
# Rate limiting zones
limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
limit_req_zone $binary_remote_addr zone=frontend:10m rate=30r/s;

# Applied to locations
limit_req zone=api burst=20 nodelay;
```

- API endpoints: 10 requests/second with burst of 20
- Frontend endpoints: 30 requests/second with burst of 50
- Protection against abuse and DoS attacks

### 7. Security Headers

```nginx
# Security headers
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
```

- Protection against clickjacking, MIME sniffing, XSS
- Proper referrer policy for privacy

### 8. Enhanced Logging

```nginx
log_format main '$remote_addr - $remote_user [$time_local] "$request" '
                '$status $body_bytes_sent "$http_referer" '
                '"$http_user_agent" "$http_x_forwarded_for" '
                'rt=$request_time uct="$upstream_connect_time" '
                'uht="$upstream_header_time" urt="$upstream_response_time"';
```

- Detailed logging including upstream timing information
- Request timing for performance analysis

## Service Configuration

### Backend Services (Django APIs)
- Health check endpoints with fast timeouts (5s)
- API endpoints with standard timeouts (30s)
- Static and media file serving with caching
- Error handling with upstream failover

### Frontend Services (React/Vite)
- WebSocket support for HMR
- SPA routing fallback
- Static asset caching
- Development server optimizations

### Additional Services
- Celery Flower monitoring interface
- Service discovery endpoint
- Health check endpoint for load balancers

## Testing the Configuration

The nginx configuration has been validated for syntax correctness. To test in the Docker environment:

```bash
# Start the services
docker-compose up -d

# Test service availability
curl http://admin.localhost
curl http://wellknown.localhost
curl http://admin-api.localhost/health/
curl http://wellknown-api.localhost/health/

# Test service discovery
curl http://localhost/
```

## Monitoring and Debugging

- Access logs: `/var/log/nginx/access.log`
- Error logs: `/var/log/nginx/error.log`
- Upstream timing information included in logs
- Health check endpoint: `http://localhost/health`
- Service discovery: `http://localhost/`

## Requirements Satisfied

This implementation addresses the following requirements:

- **Requirement 5.3**: Nginx properly proxies requests to backend services
- **Requirement 5.4**: All services communicate successfully with proper error handling

The optimizations ensure reliable service communication, proper error handling, and optimal performance for both development and production environments.