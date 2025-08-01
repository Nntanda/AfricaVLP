#!/bin/bash

# Docker Build Optimization Script
# This script provides optimized builds with proper caching and platform support

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to build a service
build_service() {
    local service=$1
    local platform=${2:-"linux/amd64,linux/arm64"}
    local cache_from=${3:-""}
    
    print_status "Building $service..."
    
    # Build command with optimization
    local build_cmd="docker build"
    
    # Add platform support if specified
    if [ "$platform" != "default" ]; then
        build_cmd="$build_cmd --platform $platform"
    fi
    
    # Add cache from if specified
    if [ -n "$cache_from" ]; then
        build_cmd="$build_cmd --cache-from $cache_from"
    fi
    
    # Add build arguments for optimization
    build_cmd="$build_cmd --build-arg BUILDKIT_INLINE_CACHE=1"
    
    # Execute build
    $build_cmd -t "$service:latest" "./$service"
    
    if [ $? -eq 0 ]; then
        print_status "$service build completed successfully"
    else
        print_error "$service build failed"
        exit 1
    fi
}

# Function to build all services
build_all() {
    local platform=${1:-"default"}
    
    print_status "Starting build process for all services..."
    print_status "Platform: $platform"
    
    # Build backend services
    build_service "admin-backend" "$platform"
    build_service "wellknown-backend" "$platform"
    
    # Build frontend services
    build_service "admin-frontend" "$platform"
    build_service "wellknown-frontend" "$platform"
    
    print_status "All services built successfully!"
}

# Function to show usage
show_usage() {
    echo "Usage: $0 [OPTIONS] [SERVICE]"
    echo ""
    echo "Options:"
    echo "  -p, --platform PLATFORM    Target platform (default: linux/amd64,linux/arm64)"
    echo "  -a, --all                   Build all services"
    echo "  -h, --help                  Show this help message"
    echo ""
    echo "Services:"
    echo "  admin-backend               Build admin backend service"
    echo "  wellknown-backend          Build wellknown backend service"
    echo "  admin-frontend             Build admin frontend service"
    echo "  wellknown-frontend         Build wellknown frontend service"
    echo ""
    echo "Examples:"
    echo "  $0 -a                      Build all services"
    echo "  $0 admin-backend           Build only admin-backend"
    echo "  $0 -p linux/amd64 -a       Build all services for AMD64 only"
}

# Parse command line arguments
PLATFORM="default"
BUILD_ALL=false
SERVICE=""

while [[ $# -gt 0 ]]; do
    case $1 in
        -p|--platform)
            PLATFORM="$2"
            shift 2
            ;;
        -a|--all)
            BUILD_ALL=true
            shift
            ;;
        -h|--help)
            show_usage
            exit 0
            ;;
        admin-backend|wellknown-backend|admin-frontend|wellknown-frontend)
            SERVICE="$1"
            shift
            ;;
        *)
            print_error "Unknown option: $1"
            show_usage
            exit 1
            ;;
    esac
done

# Main execution
if [ "$BUILD_ALL" = true ]; then
    build_all "$PLATFORM"
elif [ -n "$SERVICE" ]; then
    build_service "$SERVICE" "$PLATFORM"
else
    print_error "No service specified. Use -a to build all services or specify a service name."
    show_usage
    exit 1
fi