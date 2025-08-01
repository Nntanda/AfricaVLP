#!/usr/bin/env python3
"""
Service Startup Validation Script

This script validates that all Docker services start up correctly
and reach a healthy state within expected timeframes.

Requirements covered: 1.1, 1.3
"""

import os
import sys
import time
import json
import subprocess
import requests
from typing import Dict, List, Optional
from dataclasses import dataclass
from enum import Enum

class ServiceStatus(Enum):
    STARTING = "starting"
    HEALTHY = "healthy"
    UNHEALTHY = "unhealthy"
    FAILED = "failed"

@dataclass
class ServiceInfo:
    name: str
    container_name: str
    health_endpoint: Optional[str]
    expected_startup_time: int  # seconds
    max_wait_time: int  # seconds

class ServiceStartupValidator:
    def __init__(self):
        self.services = [
            ServiceInfo("MySQL", "mysql", None, 30, 60),
            ServiceInfo("Redis", "redis", None, 10, 30),
            ServiceInfo("Admin Backend", "admin-backend", "http://localhost:8000/health/", 45, 90),
            ServiceInfo("Wellknown Backend", "wellknown-backend", "http://localhost:8001/health/", 45, 90),
            ServiceInfo("Admin Frontend", "admin-frontend", "http://localhost:3000", 30, 60),
            ServiceInfo("Wellknown Frontend", "wellknown-frontend", "http://localhost:3001", 30, 60),
            ServiceInfo("Nginx", "nginx", "http://localhost:80", 15, 30),
            ServiceInfo("Celery Worker", "celery-worker", None, 20, 45),
            ServiceInfo("Celery Beat", "celery-beat", None, 15, 30),
            ServiceInfo("Flower", "flower", "http://localhost:5555", 20, 45)
        ]

    def get_container_status(self, container_name: str) -> Dict:
        """Get detailed container status from Docker"""
        try:
            result = subprocess.run([
                'docker', 'ps', '--filter', f'name={container_name}',
                '--format', 'json'
            ], capture_output=True, text=True, check=True)
            
            if not result.stdout.strip():
                return {'status': 'not_found'}
            
            container_info = json.loads(result.stdout.strip().split('\n')[0])
            
            # Get detailed inspect information
            inspect_result = subprocess.run([
                'docker', 'inspect', container_info['Names']
            ], capture_output=True, text=True, check=True)
            
            inspect_data = json.loads(inspect_result.stdout)[0]
            
            return {
                'status': 'running' if inspect_data['State']['Running'] else 'stopped',
                'health': inspect_data['State'].get('Health', {}).get('Status', 'none'),
                'started_at': inspect_data['State']['StartedAt'],
                'exit_code': inspect_data['State']['ExitCode'],
                'error': inspect_data['State'].get('Error', ''),
                'restart_count': inspect_data['RestartCount']
            }
            
        except subprocess.CalledProcessError as e:
            return {'status': 'error', 'error': str(e)}

    def check_health_endpoint(self, url: str, timeout: int = 10) -> bool:
        """Check if a service health endpoint is responding"""
        try:
            response = requests.get(url, timeout=timeout)
            return response.status_code < 500
        except requests.RequestException:
            return False

    def wait_for_service(self, service: ServiceInfo) -> ServiceStatus:
        """Wait for a service to become healthy"""
        print(f"Waiting for {service.name} to start...")
        
        start_time = time.time()
        
        while time.time() - start_time < service.max_wait_time:
            container_status = self.get_container_status(service.container_name)
            
            if container_status['status'] == 'not_found':
                print(f"  Container {service.container_name} not found")
                time.sleep(2)
                continue
            
            if container_status['status'] == 'error':
                print(f"  Error checking container: {container_status['error']}")
                return ServiceStatus.FAILED
            
            if container_status['status'] != 'running':
                print(f"  Container not running (exit code: {container_status['exit_code']})")
                if container_status['error']:
                    print(f"  Error: {container_status['error']}")
                return ServiceStatus.FAILED
            
            # Check Docker health status if available
            if container_status['health'] == 'healthy':
                print(f"  ✓ {service.name} is healthy (Docker health check)")
                return ServiceStatus.HEALTHY
            elif container_status['health'] == 'unhealthy':
                print(f"  ✗ {service.name} is unhealthy (Docker health check)")
                return ServiceStatus.UNHEALTHY
            
            # Check custom health endpoint if available
            if service.health_endpoint:
                if self.check_health_endpoint(service.health_endpoint):
                    print(f"  ✓ {service.name} is responding to health checks")
                    return ServiceStatus.HEALTHY
                else:
                    elapsed = time.time() - start_time
                    print(f"  Waiting for {service.name} health endpoint... ({elapsed:.1f}s)")
            else:
                # For services without health endpoints, assume healthy if running
                elapsed = time.time() - start_time
                if elapsed > service.expected_startup_time:
                    print(f"  ✓ {service.name} appears to be running")
                    return ServiceStatus.HEALTHY
                else:
                    print(f"  Waiting for {service.name} startup... ({elapsed:.1f}s)")
            
            time.sleep(2)
        
        print(f"  ✗ {service.name} failed to start within {service.max_wait_time}s")
        return ServiceStatus.FAILED

    def validate_startup_order(self) -> bool:
        """Validate that services start in the correct dependency order"""
        print("Validating service startup order...")
        
        # Define dependency order
        startup_phases = [
            ["MySQL", "Redis"],  # Data layer first
            ["Admin Backend", "Wellknown Backend"],  # Application layer
            ["Celery Worker", "Celery Beat", "Flower"],  # Task processing
            ["Admin Frontend", "Wellknown Frontend"],  # Frontend layer
            ["Nginx"]  # Proxy layer last
        ]
        
        all_healthy = True
        
        for phase_num, phase_services in enumerate(startup_phases, 1):
            print(f"\nPhase {phase_num}: {', '.join(phase_services)}")
            
            phase_results = {}
            for service_name in phase_services:
                service = next((s for s in self.services if s.name == service_name), None)
                if service:
                    status = self.wait_for_service(service)
                    phase_results[service_name] = status
                    
                    if status != ServiceStatus.HEALTHY:
                        all_healthy = False
            
            # Check if all services in this phase are healthy before proceeding
            failed_services = [name for name, status in phase_results.items() 
                             if status == ServiceStatus.FAILED]
            
            if failed_services:
                print(f"Phase {phase_num} failed - services not healthy: {', '.join(failed_services)}")
                return False
            
            print(f"Phase {phase_num} completed successfully")
        
        return all_healthy

    def run_startup_validation(self) -> bool:
        """Run complete startup validation"""
        print("Starting Service Startup Validation")
        print("=" * 50)
        
        # First, check if Docker Compose is running
        try:
            result = subprocess.run(['docker-compose', 'ps'], 
                                  capture_output=True, text=True, check=True)
            if 'Up' not in result.stdout:
                print("Warning: Docker Compose services may not be running")
        except subprocess.CalledProcessError:
            print("Error: Could not check Docker Compose status")
            return False
        
        # Validate startup order
        success = self.validate_startup_order()
        
        if success:
            print("\n✓ All services started successfully!")
            print("Service startup validation completed.")
        else:
            print("\n✗ Service startup validation failed!")
            print("Some services failed to start properly.")
        
        return success

    def generate_startup_report(self):
        """Generate a detailed startup report"""
        print("\nGenerating startup report...")
        
        report = {
            'timestamp': time.strftime('%Y-%m-%d %H:%M:%S'),
            'services': {}
        }
        
        for service in self.services:
            container_status = self.get_container_status(service.container_name)
            health_status = None
            
            if service.health_endpoint:
                health_status = self.check_health_endpoint(service.health_endpoint)
            
            report['services'][service.name] = {
                'container_status': container_status,
                'health_endpoint_status': health_status,
                'expected_startup_time': service.expected_startup_time,
                'max_wait_time': service.max_wait_time
            }
        
        # Save report to file
        with open('startup_validation_report.json', 'w') as f:
            json.dump(report, f, indent=2)
        
        print("Startup report saved to: startup_validation_report.json")

if __name__ == "__main__":
    validator = ServiceStartupValidator()
    
    success = validator.run_startup_validation()
    validator.generate_startup_report()
    
    sys.exit(0 if success else 1)