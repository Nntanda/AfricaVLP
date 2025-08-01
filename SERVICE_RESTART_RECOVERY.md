# AU-VLP Service Restart and Recovery Policies

## Overview

This document describes the comprehensive restart and recovery policies implemented for the AU-VLP Docker infrastructure. The system includes automatic restart policies, graceful shutdown handling, service dependency recovery mechanisms, and comprehensive testing procedures.

## Restart Policies Configuration

### Service-Level Restart Policies

All services are configured with `restart: unless-stopped` policy, which means:
- Services automatically restart if they exit with a non-zero status
- Services do not restart if they are manually stopped
- Services restart when Docker daemon restarts (unless manually stopped)

### Deploy-Level Restart Policies

Each service also has deploy-level restart policies for additional control:

| Service | Condition | Delay | Max Attempts | Window |
|---------|-----------|-------|--------------|--------|
| mysql | on-failure | 10s | 5 | 300s |
| redis | on-failure | 5s | 5 | 300s |
| admin-backend | on-failure | 10s | 3 | 180s |
| wellknown-backend | on-failure | 10s | 3 | 180s |
| celery-worker | on-failure | 5s | 3 | 120s |
| celery-beat | on-failure | 10s | 3 | 120s |
| celery-flower | on-failure | 5s | 3 | 120s |
| admin-frontend | on-failure | 5s | 3 | 120s |
| wellknown-frontend | on-failure | 5s | 3 | 120s |
| nginx | on-failure | 5s | 3 | 120s |

## Graceful Shutdown Configuration

### Stop Grace Periods

Each service has a configured grace period for shutdown:

- **Database Services (MySQL)**: 30s - Allows time for transaction completion
- **Backend Services**: 30s - Allows time for request completion and cleanup
- **Frontend Services**: 15s - Allows time for build processes to complete
- **Proxy Services (Nginx)**: 10s - Quick shutdown for load balancer
- **Cache Services (Redis)**: 10s - Quick shutdown with data persistence

### Shutdown Sequence

Services shut down in reverse dependency order:
1. Nginx (proxy layer)
2. Frontend services
3. Backend services
4. Celery services
5. Redis and MySQL (data layer)

## Service Dependencies and Recovery

### Dependency Chain

```
mysql, redis (foundation)
    ↓
admin-backend, wellknown-backend (core APIs)
    ↓
celery-worker, celery-beat (task processing)
    ↓
admin-frontend, wellknown-frontend (user interfaces)
    ↓
nginx (proxy and load balancing)
```

### Dependency Recovery Mechanisms

1. **Health Check Dependencies**: Services wait for dependencies to be healthy before starting
2. **Automatic Restart**: If a dependency fails and recovers, dependent services automatically reconnect
3. **Circuit Breaker Pattern**: Services implement connection retry logic with exponential backoff
4. **Graceful Degradation**: Non-critical features continue working when optional dependencies fail

## Health Check Configuration

### Health Check Parameters

| Service | Interval | Timeout | Retries | Start Period |
|---------|----------|---------|---------|--------------|
| mysql | 30s | 10s | 5 | 30s |
| redis | 30s | 10s | 5 | 10s |
| admin-backend | 30s | 10s | 5 | 60s |
| wellknown-backend | 30s | 10s | 5 | 60s |
| celery-worker | 30s | 10s | 3 | 60s |
| celery-beat | 60s | 10s | 3 | 60s |
| celery-flower | 30s | 10s | 3 | 30s |
| admin-frontend | 30s | 10s | 5 | 60s |
| wellknown-frontend | 30s | 10s | 5 | 60s |
| nginx | 30s | 10s | 3 | 30s |

### Health Check Endpoints

- **Backend Services**: `/health/live/` - Django health check endpoint
- **Frontend Services**: `/` - Basic HTTP response check
- **Database**: `mysqladmin ping` - MySQL connectivity check
- **Cache**: `redis-cli ping` - Redis connectivity check
- **Celery Worker**: `celery inspect ping` - Worker process check
- **Celery Beat**: Process and PID file check
- **Nginx**: HTTP response check

## Monitoring and Alerting

### System Monitor (`monitor_system.py`)

The system includes a comprehensive monitoring script that:

- **Continuous Monitoring**: Checks service health every 30 seconds
- **Automatic Recovery**: Attempts to restart failed services
- **Dependency Awareness**: Considers service dependencies before restart attempts
- **Alert Generation**: Logs critical failures and generates alerts
- **Status Reporting**: Provides real-time system status reports

### Service Criticality Levels

| Level | Services | Recovery Action |
|-------|----------|-----------------|
| Critical | mysql, redis, backends, nginx | Immediate restart attempt |
| High | frontends, celery-worker, celery-beat | Delayed restart (10s) |
| Medium | celery-flower | Monitor and log only |

### Recovery Limits

- **Maximum Restart Attempts**: 3-5 per service (varies by criticality)
- **Restart Window**: 120-300 seconds (varies by service type)
- **Recovery Timeout**: 60-120 seconds per service
- **Alert Threshold**: After 2 consecutive restart failures

## Testing and Validation

### Recovery Testing Script (`test-service-recovery.ps1`)

Comprehensive testing script that validates:

1. **Individual Service Recovery**: Tests each service's ability to recover from failure
2. **Dependency Recovery**: Tests recovery when dependencies fail and recover
3. **Graceful Shutdown**: Validates proper shutdown procedures
4. **System Recovery**: Tests complete system startup and recovery

### Test Scenarios

1. **Service Failure Simulation**:
   - Stop individual services
   - Monitor recovery time
   - Validate health after recovery

2. **Dependency Failure Testing**:
   - Stop MySQL and verify dependent services detect failure
   - Restart MySQL and verify dependent services reconnect
   - Test cascade recovery scenarios

3. **Resource Exhaustion Testing**:
   - Simulate high load conditions
   - Test service behavior under resource constraints
   - Validate recovery after resource availability

4. **Network Partition Testing**:
   - Simulate network connectivity issues
   - Test service isolation and recovery
   - Validate inter-service communication restoration

## Usage Instructions

### Starting the System

```bash
# Start all services with restart policies
docker-compose up -d

# Monitor startup progress
docker-compose ps
```

### Monitoring Services

```bash
# Check current status
python monitor_system.py --status

# Start continuous monitoring
python monitor_system.py

# Windows batch script
monitor_system.bat status
monitor_system.bat
```

### Testing Recovery

```bash
# Run comprehensive recovery tests
powershell -ExecutionPolicy Bypass -File test-service-recovery.ps1

# Test specific service recovery
powershell -ExecutionPolicy Bypass -File test-service-recovery.ps1 -Service mysql

# Windows batch script
monitor_system.bat test
```

### Manual Recovery Operations

```bash
# Restart specific service
docker-compose restart <service-name>

# Check service logs
docker-compose logs -f <service-name>

# Force recreate service
docker-compose up -d --force-recreate <service-name>

# Graceful system shutdown
docker-compose down --timeout 60

# Emergency stop (immediate)
docker-compose kill
```

## Troubleshooting

### Common Recovery Issues

1. **Service Won't Start**:
   - Check dependency health: `docker-compose ps`
   - Review service logs: `docker-compose logs <service>`
   - Verify configuration: Check environment variables and volumes

2. **Repeated Restart Loops**:
   - Check resource constraints: CPU, memory, disk space
   - Review health check configuration
   - Verify network connectivity between services

3. **Slow Recovery Times**:
   - Adjust health check intervals
   - Optimize service startup scripts
   - Review resource allocation

4. **Dependency Issues**:
   - Verify service dependency chain
   - Check network connectivity
   - Review service discovery configuration

### Recovery Best Practices

1. **Monitoring**:
   - Use the system monitor for continuous oversight
   - Set up external alerting for critical failures
   - Regularly review recovery logs and metrics

2. **Testing**:
   - Run recovery tests regularly (weekly recommended)
   - Test during low-traffic periods
   - Document and address any test failures

3. **Maintenance**:
   - Keep Docker and Docker Compose updated
   - Regularly review and update restart policies
   - Monitor resource usage trends

4. **Documentation**:
   - Keep runbooks updated with recovery procedures
   - Document any custom recovery scripts or procedures
   - Maintain incident response procedures

## Configuration Files

- `docker-compose.yml` - Main service configuration with restart policies
- `monitor_system.py` - System monitoring and recovery script
- `test-service-recovery.ps1` - Recovery testing script
- `monitor_system.bat` - Windows convenience script

## Metrics and Reporting

The system tracks the following recovery metrics:

- **Mean Time to Recovery (MTTR)**: Average time for service recovery
- **Service Availability**: Percentage uptime per service
- **Restart Frequency**: Number of restarts per service per time period
- **Dependency Impact**: How often dependency failures affect other services
- **Recovery Success Rate**: Percentage of successful automatic recoveries

These metrics are logged and can be used for system optimization and capacity planning.