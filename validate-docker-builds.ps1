# Docker Build Validation Script
# This script validates that all Docker builds work correctly with the new configurations

param(
    [switch]$Quick,
    [switch]$Verbose
)

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

function Test-DockerBuild {
    param(
        [string]$ServiceName,
        [string]$ServicePath
    )
    
    Write-Status "Testing build for $ServiceName..."
    
    try {
        $buildResult = & docker build -t "$ServiceName-test" $ServicePath 2>&1
        
        if ($LASTEXITCODE -eq 0) {
            Write-Status "$ServiceName build: PASSED"
            
            # Clean up test image
            & docker rmi "$ServiceName-test" | Out-Null
            
            return $true
        } else {
            Write-Error "$ServiceName build: FAILED"
            if ($Verbose) {
                Write-Host $buildResult
            }
            return $false
        }
    } catch {
        Write-Error "$ServiceName build: ERROR - $_"
        return $false
    }
}

function Test-DockerfileOptimizations {
    param([string]$DockerfilePath)
    
    $content = Get-Content $DockerfilePath -Raw
    $optimizations = @()
    
    # Check for multi-stage build
    if ($content -match "FROM .+ AS \w+") {
        $optimizations += "Multi-stage build: PASS"
    } else {
        $optimizations += "Multi-stage build: FAIL"
    }
    
    # Check for non-root user
    if ($content -match "USER \w+") {
        $optimizations += "Non-root user: PASS"
    } else {
        $optimizations += "Non-root user: FAIL"
    }
    
    # Check for health check
    if ($content -match "HEALTHCHECK") {
        $optimizations += "Health check: PASS"
    } else {
        $optimizations += "Health check: FAIL"
    }
    
    # Check for .dockerignore
    $dockerignorePath = Join-Path (Split-Path $DockerfilePath) ".dockerignore"
    if (Test-Path $dockerignorePath) {
        $optimizations += ".dockerignore: PASS"
    } else {
        $optimizations += ".dockerignore: FAIL"
    }
    
    return $optimizations
}

# Main validation
Write-Status "Starting Docker build validation..."
Write-Status "Quick mode: $Quick"
Write-Status "Verbose mode: $Verbose"
Write-Host ""

$services = @(
    @{Name = "admin-backend"; Path = "./admin-backend"},
    @{Name = "wellknown-backend"; Path = "./wellknown-backend"},
    @{Name = "admin-frontend"; Path = "./admin-frontend"},
    @{Name = "wellknown-frontend"; Path = "./wellknown-frontend"}
)

$results = @()
$totalTests = 0
$passedTests = 0

foreach ($service in $services) {
    Write-Host "=" * 50
    Write-Status "Validating $($service.Name)"
    Write-Host "=" * 50
    
    # Test Dockerfile optimizations
    if (-not $Quick) {
        Write-Status "Checking Dockerfile optimizations..."
        $dockerfilePath = Join-Path $service.Path "Dockerfile"
        $optimizations = Test-DockerfileOptimizations $dockerfilePath
        
        foreach ($opt in $optimizations) {
            Write-Host "  $opt"
        }
        Write-Host ""
    }
    
    # Test build
    $totalTests++
    $buildResult = Test-DockerBuild $service.Name $service.Path
    
    if ($buildResult) {
        $passedTests++
    }
    
    $results += @{
        Service = $service.Name
        BuildPassed = $buildResult
    }
    
    Write-Host ""
}

# Summary
Write-Host "=" * 60
Write-Status "VALIDATION SUMMARY"
Write-Host "=" * 60

foreach ($result in $results) {
    $status = if ($result.BuildPassed) { "PASSED" } else { "FAILED" }
    $color = if ($result.BuildPassed) { "Green" } else { "Red" }
    Write-Host "$($result.Service): $status" -ForegroundColor $color
}

Write-Host ""
Write-Status "Total tests: $totalTests"
Write-Status "Passed: $passedTests"
Write-Status "Failed: $($totalTests - $passedTests)"

if ($passedTests -eq $totalTests) {
    Write-Status "All Docker builds are working correctly!"
    exit 0
} else {
    Write-Error "Some Docker builds failed. Please check the output above."
    exit 1
}