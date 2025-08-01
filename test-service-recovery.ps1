#!/usr/bin/env pwsh
# Service Recovery Testing Script for AU-VLP Docker Infrastructure
# Tests failure scenarios and recovery procedures

param(
    [string]$Service = "",
    [switch]$All = $false,
    [switch]$Verbose = $false
)

# Color output functions
function Write-Success { param($Message) Write-Host $Message -ForegroundColor Green }
function Write-Error { param($Message) Write-Host $Message -ForegroundColor Red }
function Write-Warning { param($Message) Write-Host $Message -ForegroundColor Yellow }
function Write-Info { param($Message) Write-Host $Message -ForegroundColor Cyan }

# Test configuration
$Services = @(
    @{ Name = "mysql"; Type = "database"; CriticalityLevel = "high"; RecoveryTime = 60 },
    @{ Name = "redis"; Type = "cache"; CriticalityLevel = "high"; RecoveryTime = 30 },
    @{ Name = "admin-backend"; Type = "api"; CriticalityLevel = "high"; RecoveryTime = 45 },
    @{ Name = "wellknown-backend"; Type = "api"; CriticalityLevel = "high"; RecoveryTime = 45 },
    @{ Name = "celery-worker"; Type = "worker"; CriticalityLevel = "medium"; RecoveryTime = 30 },
    @{ Name = "celery-beat"; Type = "scheduler"; CriticalityLevel = "medium"; RecoveryTime = 30 },
    @{ Name = "celery-flower"; Type = "monitor"; CriticalityLevel = "low"; RecoveryTime = 20 },
    @{ Name = "admin-frontend"; Type = "frontend"; CriticalityLevel = "medium"; RecoveryTime = 30 },
    @{ Name = "wellknown-frontend"; Type = "frontend"; CriticalityLevel = "medium"; RecoveryTime = 30 },
    @{ Name = "nginx"; Type = "proxy"; CriticalityLevel = "high"; RecoveryTime = 20 }
)

function Test-ServiceHealth {
    param([string]$ServiceName)
    
    Write-Info "Testing health of service: $ServiceName"
    
    $healthStatus = docker-compose ps --format json | ConvertFrom-Json | Where-Object { $_.Service -eq $ServiceName }
    
    if (-not $healthStatus) {
        Write-Error "Service $ServiceName not found"
        return $false
    }
    
    $isHealthy = $healthStatus.Health -eq "healthy" -or $healthStatus.State -eq "running"
    
    if ($isHealthy) {
        Write-Success "✓ Service $ServiceName is healthy"
    } else {
        Write-Error "✗ Service $ServiceName is unhealthy (State: $($healthStatus.State), Health: $($healthStatus.Health))"
    }
    
    return $isHealthy
}

function Test-ServiceRecovery {
    param(
        [hashtable]$ServiceConfig,
        [int]$TestDuration = 120
    )
    
    $serviceName = $ServiceConfig.Name
    $recoveryTime = $ServiceConfig.RecoveryTime
    
    Write-Info "Starting recovery test for service: $serviceName"
    Write-Info "Expected recovery time: $recoveryTime seconds"
    
    # Step 1: Verify service is initially healthy
    if (-not (Test-ServiceHealth $serviceName)) {
        Write-Error "Service $serviceName is not healthy before test. Skipping recovery test."
        return $false
    }
    
    # Step 2: Simulate failure by stopping the service
    Write-Warning "Simulating failure: Stopping service $serviceName"
    docker-compose stop $serviceName
    Start-Sleep -Seconds 5
    
    # Step 3: Verify service is stopped
    $stoppedStatus = docker-compose ps --format json | ConvertFrom-Json | Where-Object { $_.Service -eq $serviceName }
    if ($stoppedStatus.State -ne "exited") {
        Write-Error "Failed to stop service $serviceName for testing"
        return $false
    }
    Write-Success "✓ Service $serviceName successfully stopped"
    
    # Step 4: Start the service and monitor recovery
    Write-Info "Starting service recovery..."
    docker-compose start $serviceName
    
    $recoveryStartTime = Get-Date
    $recovered = $false
    $maxWaitTime = [Math]::Max($recoveryTime * 2, 60) # Wait at least 60 seconds, or 2x expected recovery time
    
    Write-Info "Monitoring recovery for up to $maxWaitTime seconds..."
    
    while ((Get-Date) -lt $recoveryStartTime.AddSeconds($maxWaitTime)) {
        Start-Sleep -Seconds 10
        
        if (Test-ServiceHealth $serviceName) {
            $actualRecoveryTime = ((Get-Date) - $recoveryStartTime).TotalSeconds
            Write-Success "✓ Service $serviceName recovered successfully in $([Math]::Round($actualRecoveryTime, 1)) seconds"
            
            if ($actualRecoveryTime -le $recoveryTime) {
                Write-Success "✓ Recovery time within expected threshold ($recoveryTime seconds)"
            } else {
                Write-Warning "⚠ Recovery took longer than expected ($recoveryTime seconds)"
            }
            
            $recovered = $true
            break
        }
        
        Write-Info "Service $serviceName still recovering..."
    }
    
    if (-not $recovered) {
        Write-Error "✗ Service $serviceName failed to recover within $maxWaitTime seconds"
        
        # Show logs for debugging
        Write-Info "Showing recent logs for debugging:"
        docker-compose logs --tail=20 $serviceName
    }
    
    return $recovered
}

function Test-DependencyRecovery {
    Write-Info "Testing service dependency recovery scenarios..."
    
    # Test scenario: Stop MySQL and verify dependent services recover when it comes back
    Write-Info "Testing MySQL dependency recovery..."
    
    # Stop MySQL
    Write-Warning "Stopping MySQL to test dependency recovery"
    docker-compose stop mysql
    Start-Sleep -Seconds 10
    
    # Check that dependent services detect the failure
    $dependentServices = @("admin-backend", "wellknown-backend", "celery-worker", "celery-beat")
    
    Write-Info "Checking if dependent services detect MySQL failure..."
    foreach ($service in $dependentServices) {
        $logs = docker-compose logs --tail=10 $service 2>&1
        if ($logs -match "database|mysql|connection.*error|connection.*failed") {
            Write-Success "✓ Service $service detected MySQL failure"
        } else {
            Write-Warning "⚠ Service $service may not have detected MySQL failure"
        }
    }
    
    # Restart MySQL
    Write-Info "Restarting MySQL..."
    docker-compose start mysql
    
    # Wait for MySQL to be healthy
    $mysqlHealthy = $false
    $maxWait = 120
    $waitStart = Get-Date
    
    while ((Get-Date) -lt $waitStart.AddSeconds($maxWait)) {
        if (Test-ServiceHealth "mysql") {
            $mysqlHealthy = $true
            break
        }
        Start-Sleep -Seconds 5
    }
    
    if (-not $mysqlHealthy) {
        Write-Error "MySQL failed to recover, cannot test dependency recovery"
        return $false
    }
    
    # Check that dependent services recover
    Write-Info "Checking if dependent services recover after MySQL restart..."
    Start-Sleep -Seconds 30 # Give services time to reconnect
    
    $allRecovered = $true
    foreach ($service in $dependentServices) {
        if (Test-ServiceHealth $service) {
            Write-Success "✓ Service $service recovered after MySQL restart"
        } else {
            Write-Error "✗ Service $service failed to recover after MySQL restart"
            $allRecovered = $false
        }
    }
    
    return $allRecovered
}

function Test-GracefulShutdown {
    Write-Info "Testing graceful shutdown procedures..."
    
    # Test graceful shutdown of all services
    Write-Info "Initiating graceful shutdown of all services..."
    $shutdownStart = Get-Date
    
    docker-compose down --timeout 60
    
    $shutdownTime = ((Get-Date) - $shutdownStart).TotalSeconds
    Write-Info "Graceful shutdown completed in $([Math]::Round($shutdownTime, 1)) seconds"
    
    # Verify all containers are stopped
    $runningContainers = docker-compose ps --format json | ConvertFrom-Json | Where-Object { $_.State -eq "running" }
    
    if ($runningContainers.Count -eq 0) {
        Write-Success "✓ All services shut down gracefully"
        return $true
    } else {
        Write-Error "✗ Some services failed to shut down gracefully:"
        $runningContainers | ForEach-Object { Write-Error "  - $($_.Service)" }
        return $false
    }
}

function Test-SystemRecovery {
    Write-Info "Testing complete system recovery..."
    
    # Start all services
    Write-Info "Starting all services..."
    docker-compose up -d
    
    # Monitor startup progress
    $startupStart = Get-Date
    $maxStartupTime = 300 # 5 minutes
    $allHealthy = $false
    
    Write-Info "Monitoring system startup (timeout: $maxStartupTime seconds)..."
    
    while ((Get-Date) -lt $startupStart.AddSeconds($maxStartupTime)) {
        Start-Sleep -Seconds 15
        
        $healthyServices = 0
        $totalServices = $Services.Count
        
        foreach ($service in $Services) {
            if (Test-ServiceHealth $service.Name) {
                $healthyServices++
            }
        }
        
        Write-Info "System startup progress: $healthyServices/$totalServices services healthy"
        
        if ($healthyServices -eq $totalServices) {
            $startupTime = ((Get-Date) - $startupStart).TotalSeconds
            Write-Success "✓ Complete system recovery successful in $([Math]::Round($startupTime, 1)) seconds"
            $allHealthy = $true
            break
        }
    }
    
    if (-not $allHealthy) {
        Write-Error "✗ System failed to fully recover within $maxStartupTime seconds"
        
        # Show status of all services
        Write-Info "Current service status:"
        foreach ($service in $Services) {
            Test-ServiceHealth $service.Name
        }
    }
    
    return $allHealthy
}

function Show-RecoveryReport {
    param([hashtable[]]$TestResults)
    
    Write-Info "`n=== SERVICE RECOVERY TEST REPORT ==="
    Write-Info "Test completed at: $(Get-Date)"
    Write-Info "Total tests run: $($TestResults.Count)"
    
    $passedTests = $TestResults | Where-Object { $_.Passed }
    $failedTests = $TestResults | Where-Object { -not $_.Passed }
    
    Write-Success "Passed: $($passedTests.Count)"
    Write-Error "Failed: $($failedTests.Count)"
    
    if ($failedTests.Count -gt 0) {
        Write-Error "`nFailed Tests:"
        $failedTests | ForEach-Object {
            Write-Error "  - $($_.TestName): $($_.ErrorMessage)"
        }
    }
    
    Write-Info "`nRecommendations:"
    if ($failedTests.Count -eq 0) {
        Write-Success "✓ All recovery tests passed. System is resilient to failures."
    } else {
        Write-Warning "⚠ Some recovery tests failed. Review the following:"
        Write-Warning "  1. Check service health check configurations"
        Write-Warning "  2. Verify restart policies are properly configured"
        Write-Warning "  3. Review service dependency chains"
        Write-Warning "  4. Check resource limits and constraints"
    }
}

# Main execution
Write-Info "AU-VLP Service Recovery Testing Script"
Write-Info "======================================"

$testResults = @()

try {
    if ($Service -and -not $All) {
        # Test specific service
        $serviceConfig = $Services | Where-Object { $_.Name -eq $Service }
        if (-not $serviceConfig) {
            Write-Error "Service '$Service' not found. Available services: $($Services.Name -join ', ')"
            exit 1
        }
        
        $result = Test-ServiceRecovery $serviceConfig
        $testResults += @{
            TestName = "Service Recovery: $Service"
            Passed = $result
            ErrorMessage = if (-not $result) { "Service failed to recover properly" } else { "" }
        }
    } else {
        # Run comprehensive tests
        Write-Info "Running comprehensive recovery tests..."
        
        # Test individual service recovery
        foreach ($serviceConfig in $Services) {
            if ($serviceConfig.CriticalityLevel -eq "high") {
                Write-Info "`nTesting critical service: $($serviceConfig.Name)"
                $result = Test-ServiceRecovery $serviceConfig
                $testResults += @{
                    TestName = "Service Recovery: $($serviceConfig.Name)"
                    Passed = $result
                    ErrorMessage = if (-not $result) { "Service failed to recover properly" } else { "" }
                }
            }
        }
        
        # Test dependency recovery
        $depResult = Test-DependencyRecovery
        $testResults += @{
            TestName = "Dependency Recovery"
            Passed = $depResult
            ErrorMessage = if (-not $depResult) { "Dependency recovery failed" } else { "" }
        }
        
        # Test graceful shutdown
        $shutdownResult = Test-GracefulShutdown
        $testResults += @{
            TestName = "Graceful Shutdown"
            Passed = $shutdownResult
            ErrorMessage = if (-not $shutdownResult) { "Graceful shutdown failed" } else { "" }
        }
        
        # Test system recovery
        $systemResult = Test-SystemRecovery
        $testResults += @{
            TestName = "System Recovery"
            Passed = $systemResult
            ErrorMessage = if (-not $systemResult) { "System recovery failed" } else { "" }
        }
    }
    
    # Show final report
    Show-RecoveryReport $testResults
    
    # Exit with appropriate code
    $failedCount = ($testResults | Where-Object { -not $_.Passed }).Count
    exit $failedCount
    
} catch {
    Write-Error "Test execution failed: $($_.Exception.Message)"
    exit 1
}