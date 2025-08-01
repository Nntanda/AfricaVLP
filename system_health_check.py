#!/usr/bin/env python3
"""
Comprehensive System Health Check Script

This script provides a complete health assessment of the AU-VLP system,
combining infrastructure, API, and frontend validation into a single report.

Requirements covered: 2.4, 3.4, 5.4
"""

import os
import sys
import time
import json
import requests
import subprocess
from typing import Dict, List, Optional
from dataclasses import dataclass
from enum import Enum

class HealthStatus(Enum):
    HEALTHY = "healthy"
    WARNING = "warning"
    CRITICAL = "critical"
    UNKNOWN = "unknown"

@dataclass
class HealthCheck:
    component: str
    status: HealthStatus
    message: str
    details: Optional[Dict] = None
    response_time: Optional[float] = None

class SystemHealthChecker:
    def __init__(self):
        self.checks: List[HealthCheck] = []
        self.start_time = time.time()
        
        self.services = {
            'mysql': {'host': 'localhost', 'port': 3306, 'type': 'database'},
            'redis': {'host': 'localhost', 'port': 6379, 'type': 'cache'},
            'admin-backend': {'host': 'localhost', 'port': 8000, 'type': 'api'},
            'wellknown-backend': {'host': 'localhost', 'port': 8001, 'type': 'api'},
            'admin-frontend': {'host': 'localhost', 'port': 3000, 'type': 'frontend'},
            'wellknown-frontend': {'host': 'localhost', 'port': 3001, 'type': 'frontend'},
            'nginx': {'host': 'localhost', 'port': 80, 'type': 'proxy'},
            'flower': {'host': 'localhost', 'port': 5555, 'type': 'monitoring'}
        }

    def add_check(self, component: str, status: HealthStatus, message: str, 
                  details: Optional[Dict] = None, response_time: Optional[float] = None):
        """Add a health check result"""
        check = HealthCheck(component, status, message, details, response_time)
        self.checks.append(check)
        
        status_icon = {
            HealthStatus.HEALTHY: "✓",
            HealthStatus.WARNING: "⚠",
            HealthStatus.CRITICAL: "✗",
            HealthStatus.UNKNOWN: "?"
        }
        
        time_str = f" ({response_time:.3f}s)" if response_time else ""
        print(f"  {status_icon[status]} {component}: {message}{time_str}")

    def check_docker_services(self):
        """Check Docker container status"""
        print("Checking Docker Services...")
        
        try:
            # Get container status
            result = subprocess.run([
                'docker', 'ps', '--format', 
                '{{.Names}}\t{{.Status}}\t{{.Ports}}'
            ], capture_output=True, text=True, check=True)
            
            running_containers = {}
            for line in result.stdout.strip().split('\n'):
                if line:
                    parts = line.split('\t')
                    if len(parts) >= 2:
                        name = parts[0]
                        status = parts[1]
                        running_containers[name] = status
            
            # Check each expected service
            for service_name in self.services.keys():
                container_found = False
                for container_name, status in running_containers.items():
                    if service_name in container_name:
                        container_found = True
                        if 'Up' in status:
                            self.add_check(
                                f"Docker/{service_name}",
                                HealthStatus.HEALTHY,
                                f"Container running: {status}"
                            )
                        else:
                            self.add_check(
                                f"Docker/{service_name}",
                                HealthStatus.CRITICAL,
                                f"Container not running: {status}"
                            )
                        break
                
                if not container_found:
                    self.add_check(
                        f"Docker/{service_name}",
                        HealthStatus.CRITICAL,
                        "Container not found"
                    )
                    
        except subprocess.CalledProcessError as e:
            self.add_check(
                "Docker/System",
                HealthStatus.CRITICAL,
                f"Docker command failed: {e}"
            )

    def check_database_health(self):
        """Check database connectivity and health"""
        print("Checking Database Health...")
        
        try:
            import mysql.connector
            
            start_time = time.time()
            connection = mysql.connector.connect(
                host=self.services['mysql']['host'],
                port=self.services['mysql']['port'],
                user=os.getenv('MYSQL_USER', 'root'),
                password=os.getenv('MYSQL_PASSWORD', 'password'),
                database=os.getenv('MYSQL_DATABASE', 'au_vlp_db'),
                connection_timeout=10
            )
            response_time = time.time() - start_time
            
            cursor = connection.cursor()
            
            # Test basic connectivity
            cursor.execute("SELECT 1")
            cursor.fetchone()
            
            # Check database size
            cursor.execute("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = %s", 
                         (os.getenv('MYSQL_DATABASE', 'au_vlp_db'),))
            table_count = cursor.fetchone()[0]
            
            # Check for recent activity
            cursor.execute("SELECT COUNT(*) FROM django_migrations")
            migration_count = cursor.fetchone()[0]
            
            cursor.close()
            connection.close()
            
            self.add_check(
                "Database/MySQL",
                HealthStatus.HEALTHY,
                f"Connected successfully, {table_count} tables, {migration_count} migrations",
                {'table_count': table_count, 'migration_count': migration_count},
                response_time
            )
            
        except Exception as e:
            self.add_check(
                "Database/MySQL",
                HealthStatus.CRITICAL,
                f"Connection failed: {str(e)}"
            )

    def check_redis_health(self):
        """Check Redis connectivity and health"""
        print("Checking Redis Health...")
        
        try:
            import redis
            
            start_time = time.time()
            r = redis.Redis(
                host=self.services['redis']['host'],
                port=self.services['redis']['port'],
                decode_responses=True,
                socket_connect_timeout=10
            )
            
            # Test connectivity
            r.ping()
            response_time = time.time() - start_time
            
            # Get Redis info
            info = r.info()
            memory_usage = info.get('used_memory_human', 'unknown')
            connected_clients = info.get('connected_clients', 0)
            
            self.add_check(
                "Cache/Redis",
                HealthStatus.HEALTHY,
                f"Connected successfully, {connected_clients} clients, {memory_usage} memory",
                {'connected_clients': connected_clients, 'memory_usage': memory_usage},
                response_time
            )
            
        except ImportError:
            self.add_check(
                "Cache/Redis",
                HealthStatus.CRITICAL,
                "redis package not installed. Install with: pip install redis"
            )
        except Exception as e:
            self.add_check(
                "Cache/Redis",
                HealthStatus.CRITICAL,
                f"Connection failed: {str(e)}"
            )

    def check_api_health(self):
        """Check API service health"""
        print("Checking API Services...")
        
        api_services = ['admin-backend', 'wellknown-backend']
        
        for service in api_services:
            try:
                service_config = self.services[service]
                url = f"http://{service_config['host']}:{service_config['port']}/health/"
                
                start_time = time.time()
                response = requests.get(url, timeout=15)
                response_time = time.time() - start_time
                
                if response.status_code == 200:
                    health_data = response.json()
                    status = health_data.get('status', 'unknown')
                    
                    if status == 'healthy':
                        self.add_check(
                            f"API/{service}",
                            HealthStatus.HEALTHY,
                            f"Health endpoint responding: {status}",
                            health_data,
                            response_time
                        )
                    else:
                        self.add_check(
                            f"API/{service}",
                            HealthStatus.WARNING,
                            f"Health endpoint reports: {status}",
                            health_data,
                            response_time
                        )
                else:
                    self.add_check(
                        f"API/{service}",
                        HealthStatus.CRITICAL,
                        f"Health endpoint returned {response.status_code}",
                        None,
                        response_time
                    )
                    
            except requests.RequestException as e:
                self.add_check(
                    f"API/{service}",
                    HealthStatus.CRITICAL,
                    f"Health check failed: {str(e)}"
                )

    def check_frontend_health(self):
        """Check frontend service health"""
        print("Checking Frontend Services...")
        
        frontend_services = ['admin-frontend', 'wellknown-frontend']
        
        for service in frontend_services:
            try:
                service_config = self.services[service]
                url = f"http://{service_config['host']}:{service_config['port']}"
                
                start_time = time.time()
                response = requests.get(url, timeout=15)
                response_time = time.time() - start_time
                
                if response.status_code == 200:
                    content_length = len(response.text)
                    has_html = '<html' in response.text.lower()
                    has_react = 'react' in response.text.lower() or 'root' in response.text.lower()
                    
                    if has_html and content_length > 1000:
                        self.add_check(
                            f"Frontend/{service}",
                            HealthStatus.HEALTHY,
                            f"Serving content ({content_length} bytes)",
                            {'content_length': content_length, 'has_react': has_react},
                            response_time
                        )
                    else:
                        self.add_check(
                            f"Frontend/{service}",
                            HealthStatus.WARNING,
                            f"Minimal content served ({content_length} bytes)",
                            {'content_length': content_length},
                            response_time
                        )
                else:
                    self.add_check(
                        f"Frontend/{service}",
                        HealthStatus.CRITICAL,
                        f"HTTP {response.status_code}",
                        None,
                        response_time
                    )
                    
            except requests.RequestException as e:
                self.add_check(
                    f"Frontend/{service}",
                    HealthStatus.CRITICAL,
                    f"Request failed: {str(e)}"
                )

    def check_proxy_health(self):
        """Check Nginx proxy health"""
        print("Checking Proxy Service...")
        
        try:
            service_config = self.services['nginx']
            url = f"http://{service_config['host']}:{service_config['port']}"
            
            start_time = time.time()
            response = requests.get(url, timeout=10)
            response_time = time.time() - start_time
            
            if response.status_code < 500:
                self.add_check(
                    "Proxy/Nginx",
                    HealthStatus.HEALTHY,
                    f"Proxy responding (HTTP {response.status_code})",
                    {'status_code': response.status_code},
                    response_time
                )
            else:
                self.add_check(
                    "Proxy/Nginx",
                    HealthStatus.CRITICAL,
                    f"Proxy error (HTTP {response.status_code})",
                    {'status_code': response.status_code},
                    response_time
                )
                
        except requests.RequestException as e:
            self.add_check(
                "Proxy/Nginx",
                HealthStatus.CRITICAL,
                f"Proxy unreachable: {str(e)}"
            )

    def check_monitoring_health(self):
        """Check monitoring services health"""
        print("Checking Monitoring Services...")
        
        try:
            service_config = self.services['flower']
            url = f"http://{service_config['host']}:{service_config['port']}"
            
            start_time = time.time()
            response = requests.get(url, timeout=10)
            response_time = time.time() - start_time
            
            if response.status_code == 200:
                self.add_check(
                    "Monitoring/Flower",
                    HealthStatus.HEALTHY,
                    "Celery monitoring available",
                    None,
                    response_time
                )
            else:
                self.add_check(
                    "Monitoring/Flower",
                    HealthStatus.WARNING,
                    f"Monitoring service returned {response.status_code}",
                    {'status_code': response.status_code},
                    response_time
                )
                
        except requests.RequestException as e:
            self.add_check(
                "Monitoring/Flower",
                HealthStatus.WARNING,
                f"Monitoring unavailable: {str(e)}"
            )

    def check_system_resources(self):
        """Check system resource usage"""
        print("Checking System Resources...")
        
        try:
            # Check Docker system usage
            result = subprocess.run([
                'docker', 'system', 'df', '--format', 'json'
            ], capture_output=True, text=True, check=True)
            
            # Note: docker system df doesn't output JSON despite the flag
            # So we'll parse the output differently
            lines = result.stdout.strip().split('\n')
            if len(lines) > 1:
                # Parse the output for basic info
                self.add_check(
                    "System/Docker",
                    HealthStatus.HEALTHY,
                    "Docker system responding to commands"
                )
            
        except subprocess.CalledProcessError:
            self.add_check(
                "System/Docker",
                HealthStatus.WARNING,
                "Could not check Docker system resources"
            )
        
        # Check disk space if possible
        try:
            result = subprocess.run(['df', '-h', '.'], capture_output=True, text=True)
            if result.returncode == 0:
                lines = result.stdout.strip().split('\n')
                if len(lines) > 1:
                    parts = lines[1].split()
                    if len(parts) >= 5:
                        usage_percent = parts[4].rstrip('%')
                        if usage_percent.isdigit():
                            usage = int(usage_percent)
                            if usage < 80:
                                status = HealthStatus.HEALTHY
                                message = f"Disk usage: {usage}%"
                            elif usage < 90:
                                status = HealthStatus.WARNING
                                message = f"Disk usage high: {usage}%"
                            else:
                                status = HealthStatus.CRITICAL
                                message = f"Disk usage critical: {usage}%"
                            
                            self.add_check("System/Disk", status, message, {'usage_percent': usage})
                        
        except (subprocess.CalledProcessError, FileNotFoundError):
            # df command not available (Windows) or failed
            pass

    def run_health_check(self) -> Dict:
        """Run complete system health check"""
        print("AU-VLP System Health Check")
        print("=" * 50)
        print(f"Started at: {time.strftime('%Y-%m-%d %H:%M:%S')}")
        print()
        
        # Run all health checks
        health_check_functions = [
            self.check_docker_services,
            self.check_database_health,
            self.check_redis_health,
            self.check_api_health,
            self.check_frontend_health,
            self.check_proxy_health,
            self.check_monitoring_health,
            self.check_system_resources
        ]
        
        for check_func in health_check_functions:
            try:
                check_func()
            except Exception as e:
                self.add_check(
                    f"System/{check_func.__name__}",
                    HealthStatus.CRITICAL,
                    f"Health check failed: {str(e)}"
                )
            print()
        
        # Generate summary
        return self.generate_health_summary()

    def generate_health_summary(self) -> Dict:
        """Generate comprehensive health summary"""
        total_duration = time.time() - self.start_time
        
        print("=" * 50)
        print("SYSTEM HEALTH SUMMARY")
        print("=" * 50)
        
        # Count by status
        status_counts = {status: 0 for status in HealthStatus}
        for check in self.checks:
            status_counts[check.status] += 1
        
        total_checks = len(self.checks)
        healthy_checks = status_counts[HealthStatus.HEALTHY]
        
        print(f"Total Checks: {total_checks}")
        print(f"Healthy: {status_counts[HealthStatus.HEALTHY]}")
        print(f"Warning: {status_counts[HealthStatus.WARNING]}")
        print(f"Critical: {status_counts[HealthStatus.CRITICAL]}")
        print(f"Unknown: {status_counts[HealthStatus.UNKNOWN]}")
        
        if total_checks > 0:
            health_percentage = (healthy_checks / total_checks) * 100
            print(f"Health Score: {health_percentage:.1f}%")
        else:
            health_percentage = 0
        
        print(f"Check Duration: {total_duration:.2f}s")
        
        # Overall system status
        if status_counts[HealthStatus.CRITICAL] > 0:
            overall_status = HealthStatus.CRITICAL
            print(f"\n🔴 SYSTEM STATUS: CRITICAL")
            print("Critical issues detected. System may not function properly.")
        elif status_counts[HealthStatus.WARNING] > 0:
            overall_status = HealthStatus.WARNING
            print(f"\n🟡 SYSTEM STATUS: WARNING")
            print("Some issues detected. System should function but may have degraded performance.")
        else:
            overall_status = HealthStatus.HEALTHY
            print(f"\n🟢 SYSTEM STATUS: HEALTHY")
            print("All systems operational.")
        
        # Critical issues
        critical_checks = [check for check in self.checks if check.status == HealthStatus.CRITICAL]
        if critical_checks:
            print(f"\nCritical Issues:")
            for check in critical_checks:
                print(f"  ✗ {check.component}: {check.message}")
        
        # Warnings
        warning_checks = [check for check in self.checks if check.status == HealthStatus.WARNING]
        if warning_checks:
            print(f"\nWarnings:")
            for check in warning_checks:
                print(f"  ⚠ {check.component}: {check.message}")
        
        # Performance summary
        timed_checks = [check for check in self.checks if check.response_time is not None]
        if timed_checks:
            avg_response_time = sum(check.response_time for check in timed_checks) / len(timed_checks)
            print(f"\nPerformance Summary:")
            print(f"  Average Response Time: {avg_response_time:.3f}s")
            
            slow_checks = [check for check in timed_checks if check.response_time > 5.0]
            if slow_checks:
                print(f"  Slow Components:")
                for check in slow_checks:
                    print(f"    - {check.component}: {check.response_time:.3f}s")
        
        # Save detailed report
        report_data = {
            'timestamp': time.strftime('%Y-%m-%d %H:%M:%S'),
            'overall_status': overall_status.value,
            'health_score': health_percentage,
            'total_duration': total_duration,
            'summary': {
                'total_checks': total_checks,
                'healthy': status_counts[HealthStatus.HEALTHY],
                'warning': status_counts[HealthStatus.WARNING],
                'critical': status_counts[HealthStatus.CRITICAL],
                'unknown': status_counts[HealthStatus.UNKNOWN]
            },
            'checks': [
                {
                    'component': check.component,
                    'status': check.status.value,
                    'message': check.message,
                    'response_time': check.response_time,
                    'details': check.details
                }
                for check in self.checks
            ]
        }
        
        with open('system_health_report.json', 'w') as f:
            json.dump(report_data, f, indent=2)
        
        print(f"\nDetailed report saved to: system_health_report.json")
        
        return report_data

if __name__ == "__main__":
    checker = SystemHealthChecker()
    health_report = checker.run_health_check()
    
    # Exit with appropriate code
    overall_status = health_report.get('overall_status', 'unknown')
    if overall_status == 'critical':
        sys.exit(2)
    elif overall_status == 'warning':
        sys.exit(1)
    else:
        sys.exit(0)