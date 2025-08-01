# Multi-stage build for optimization
FROM node:18-alpine AS base

# Install build dependencies for native modules and platform-specific packages
RUN apk add --no-cache \
    python3 \
    make \
    g++ \
    libc6-compat \
    curl \
    git

# Set working directory
WORKDIR /app

# Create non-root user
RUN addgroup -g 1001 -S nodejs && \
    adduser -S reactuser -u 1001

# Production dependencies stage
FROM base AS deps

# Copy package files
COPY package*.json ./

# Install dependencies with platform-specific optimizations
RUN npm ci --only=production --silent --no-audit --prefer-offline && \
    npm cache clean --force

# Development dependencies stage
FROM base AS dev-deps

# Copy package files
COPY package*.json ./

# Install all dependencies (including dev dependencies)
RUN npm ci --silent --no-audit --prefer-offline && \
    npm cache clean --force

# Production build stage
FROM dev-deps AS builder

# Copy source code
COPY . .

# Build the application
RUN npm run build

# Development stage
FROM dev-deps AS development

# Copy source code
COPY --chown=reactuser:nodejs . .

# Switch to non-root user
USER reactuser

# Health check
HEALTHCHECK --interval=30s --timeout=30s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:3000 || exit 1

EXPOSE 3000

# Set environment for development
ENV NODE_ENV=development
ENV VITE_HOST=0.0.0.0
ENV VITE_PORT=3000

CMD ["npm", "run", "dev"]

# Production stage
FROM base AS production

# Copy production dependencies
COPY --from=deps /app/node_modules ./node_modules

# Copy built application
COPY --from=builder /app/dist ./dist
COPY --from=builder /app/package*.json ./

# Switch to non-root user
USER reactuser

# Health check
HEALTHCHECK --interval=30s --timeout=30s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:3000 || exit 1

EXPOSE 3000

# Set environment for production
ENV NODE_ENV=production

CMD ["npm", "run", "serve"] 