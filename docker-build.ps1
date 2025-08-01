# Docker Build Optimization Script for Windows PowerShell
# This script provides optimized builds with proper caching and platform support

param(
    [string]$Platform = "default",
    [switch]$All,
    [string]$Service = "",
    [switch]$Help
)

# Function to print colored output
function Write-Status {
    param([string]$Message)
    Write-Host "[INFO] $Message" -ForegroundColor Green
}

function Write-Warning {
    param([string]$Message)
    Write-Host "[WARNING] $Message" -ForegroundColor Yellow
}

function Write-Error {
    param([string]$Message)
    Write-Host "[ERROR] $Message" -ForegroundColor Red
}

# Function to build a service
function Build-Service {
    param(
        [string]$ServiceName,
        [string]$Platform = "default",
        [string]$CacheFrom = ""
    )
    
    Write-Status "Building $ServiceName..."
    
    # Build command with optimization
    $buildArgs = @("build")
    
    # Add platform support if specified
    if ($Platform -ne "default") {
        $buildArgs += "--platform", $Platform
    }
    
    # Add cache from if specified
    if ($CacheFrom) {
        $buildArgs += "--cache-from", $CacheFrom
    }
    
    # Add build arguments for optimization
    $buildArgs += "--build-arg", "BUILDKIT_INLINE_CACHE=1"
    $buildArgs += "-t", "$ServiceName`:latest"
    $buildArgs += "./$ServiceName"
    
    # Execute build
    try {
        & docker @buildArgs
        if ($LASTEXITCODE -eq 0) {
            Write-Status "$ServiceName build completed successfully"
        } else {
            Write-Error "$ServiceName build failed"
            exit 1
        }
    } catch {
        Write-Error "Failed to build $ServiceName`: $_"
        exit 1
    }
}

# Function to build all services
function Build-All {
    param([string]$Platform = "default")
    
    Write-Status "Starting build process for all services..."
    Write-Status "Platform: $Platform"
    
    # Build backend services
    Build-Service -ServiceName "admin-backend" -Platform $Platform
    Build-Service -ServiceName "wellknown-backend" -Platform $Platform
    
    # Build frontend services
    Build-Service -ServiceName "admin-frontend" -Platform $Platform
    Build-Service -ServiceName "wellknown-frontend" -Platform $Platform
    
    Write-Status "All services built successfully!"
}

# Function to show usage
function Show-Usage {
    Write-Host "Usage: .\docker-build.ps1 [OPTIONS] [SERVICE]"
    Write-Host ""
    Write-Host "Options:"
    Write-Host "  -Platform PLATFORM         Target platform (default: linux/amd64,linux/arm64)"
    Write-Host "  -All                        Build all services"
    Write-Host "  -Help                       Show this help message"
    Write-Host ""
    Write-Host "Services:"
    Write-Host "  admin-backend               Build admin backend service"
    Write-Host "  wellknown-backend          Build wellknown backend service"
    Write-Host "  admin-frontend             Build admin frontend service"
    Write-Host "  wellknown-frontend         Build wellknown frontend service"
    Write-Host ""
    Write-Host "Examples:"
    Write-Host "  .\docker-build.ps1 -All                    Build all services"
    Write-Host "  .\docker-build.ps1 -Service admin-backend  Build only admin-backend"
    Write-Host "  .\docker-build.ps1 -Platform linux/amd64 -All  Build all services for AMD64 only"
}

# Main execution
if ($Help) {
    Show-Usage
    exit 0
}

$validServices = @("admin-backend", "wellknown-backend", "admin-frontend", "wellknown-frontend")

if ($All) {
    Build-All -Platform $Platform
} elseif ($Service -and $validServices -contains $Service) {
    Build-Service -ServiceName $Service -Platform $Platform
} elseif ($Service -and $validServices -notcontains $Service) {
    Write-Error "Invalid service: $Service"
    Write-Host "Valid services: $($validServices -join ', ')"
    exit 1
} else {
    Write-Error "No service specified. Use -All to build all services or specify a service name with -Service."
    Show-Usage
    exit 1
}