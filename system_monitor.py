#!/usr/bin/env python3
"""
Comprehensive system monitoring script for AU-VLP infrastructure.
Monitors all services, containers, and dependencies from outside the containers.
"""

import json
import time
import socket
import requests
import subprocess
import argparse
from datetime import datetime
from typing import Dict, Any, List


class SystemMonitor:
    """
    System-wide monitoring for AU-VLP infrastructure.
    """
    
    def __init__(self, timeout: int = 10):
        self.timeout = timeout
        self.services = {
            'mysql': {'host': 'localhost', 'port': 3306},
            'redis': {'host': 'localhost', 'port': 6379},
            'admin-backend': {'host': 'localhost', 'port': 8000},
            'wellknown-backend': {'host': 'localhost', 'port': 8001},
            'admin-frontend': {'host': 'localhost', 'port': 3000},
            'wellknown-frontend': {'host': 'localhost', 'port': 3001},
            'nginx': {'host': 'localhost', 'port': 80},
        }
    
    def monitor_all(self) -> Dict[str, Any]:
        """Monitor all system components."""
        start_time = time.time()
        
        monitor_results = {
            'timestamp': datetime.utcnow().isoformat() + 'Z',
            'overall_status': 'healthy',
            'duration_ms': 0,
            'checks': {}
        }
        
        # Docker containers check
        monitor_results['checks']['containers'] = self.check_containers()
        
        # Network connectivity checks
        monitor_results['checks']['network'] = self.check_network_connectivity()
        
        # Service health checks
        monitor_results['checks']['services'] = self.check_service_health()
        
        # Database connectivity
        monitor_results['checks']['database'] = self.check_database_connectivity()
        
        # System resources
        monitor_results['checks']['resources'] = self.check_system_resources()
        
        # Determine overall status
        failed_checks = []
        for check_name, check_result in monitor_results['checks'].items():
            if isinstance(check_result, dict) and check_result.get('status') == 'unhealthy':
                failed_checks.append(check_name)
            elif isinstance(check_result, dict) and 'services' in check_result:
                # For nested service checks
                for service_name, service_result in check_result['services'].items():
                    if service_result.get('status') == 'unhealthy':
                        failed_checks.append(f"{check_name}.{service_name}")
        
        if failed_checks:
            monitor_results['overall_status'] = 'unhealthy'
            monitor_results['failed_checks'] = failed_checks
        
        monitor_results['duration_ms'] = round((time.time() - start_time) * 1000, 2)
        
        return monitor_results
    
    def check_containers(self) -> Dict[str, Any]:
        """Check Docker container status."""
        try:
            # Get container status
            result = subprocess.run(
                ['docker', 'ps', '--format', 'json'],
                capture_output=True,
                text=True,
                timeout=self.timeout
            )
            
            if result.returncode != 0:
                return {
                    'status': 'unhealthy',
                    'error': 'Docker command failed',
                    'details': {'docker_accessible': False}
                }
            
            containers = []
            if result.stdout.strip():
                for line in result.stdout.strip().split('\n'):
                    try:
                        container_info = json.loads(line)
                        containers.append({
                            'name': container_info.get('Names', ''),
                            'image': container_info.get('Image', ''),
                            'status': container_info.get('Status', ''),
                            'ports': container_info.get('Ports', '')
                        })
                    except json.JSONDecodeError:
                        continue
            
            # Check for expected containers
            expected_containers = [
                'mysql', 'redis', 'admin-backend', 'wellknown-backend',
                'admin-frontend', 'wellknown-frontend', 'nginx'
            ]
            
            running_containers = [c['name'] for c in containers]
            missing_containers = [name for name in expected_containers 
                                if not any(name in running for running in running_containers)]
            
            status = 'healthy' if not missing_containers else 'unhealthy'
            
            return {
                'status': status,
                'details': {
                    'total_containers': len(containers),
                    'running_containers': running_containers,
                    'missing_containers': missing_containers,
                    'containers': containers
                }
            }
            
        except subprocess.TimeoutExpired:
            return {
                'status': 'unhealthy',
                'error': 'Docker command timed out',
                'details': {'docker_accessible': False}
            }
        except Exception as e:
            return {
                'status': 'unhealthy',
                'error': str(e),
                'details': {'docker_accessible': False}
            }
    
    def check_network_connectivity(self) -> Dict[str, Any]:
        """Check network connectivity to all services."""
        connectivity_results = {
            'status': 'healthy',
            'services': {}
        }
        
        failed_services = []
        
        for service_name, service_config in self.services.items():
            start_time = time.time()
            
            try:
                sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                sock.settimeout(5)
                result = sock.connect_ex((service_config['host'], service_config['port']))
                sock.close()
                
                duration = time.time() - start_time
                
                if result == 0:
                    connectivity_results['services'][service_name] = {
                        'status': 'healthy',
                        'response_time_ms': round(duration * 1000, 2),
                        'host': service_config['host'],
                        'port': service_config['port']
                    }
                else:
                    connectivity_results['services'][service_name] = {
                        'status': 'unhealthy',
                        'error': f"Connection refused to {service_config['host']}:{service_config['port']}",
                        'response_time_ms': round(duration * 1000, 2)
                    }
                    failed_services.append(service_name)
                    
            except Exception as e:
                duration = time.time() - start_time
                connectivity_results['services'][service_name] = {
                    'status': 'unhealthy',
                    'error': str(e),
                    'response_time_ms': round(duration * 1000, 2)
                }
                failed_services.append(service_name)
        
        if failed_services:
            connectivity_results['status'] = 'unhealthy'
            connectivity_results['failed_services'] = failed_services
        
        return connectivity_results
    
    def check_service_health(self) -> Dict[str, Any]:
        """Check service health endpoints."""
        health_results = {
            'status': 'healthy',
            'services': {}
        }
        
        # Health endpoints to check
        health_endpoints = {
            'admin-backend': 'http://localhost:8000/health/',
            'wellknown-backend': 'http://localhost:8001/health/',
            'nginx': 'http://localhost/health/'
        }
        
        failed_services = []
        
        for service_name, endpoint in health_endpoints.items():
            start_time = time.time()
            
            try:
                response = requests.get(endpoint, timeout=self.timeout)
                duration = time.time() - start_time
                
                health_results['services'][service_name] = {
                    'status': 'healthy' if response.status_code == 200 else 'unhealthy',
                    'http_status': response.status_code,
                    'response_time_ms': round(duration * 1000, 2),
                    'content_length': len(response.content)
                }
                
                if response.status_code != 200:
                    failed_services.append(service_name)
                    
            except Exception as e:
                duration = time.time() - start_time
                health_results['services'][service_name] = {
                    'status': 'unhealthy',
                    'error': str(e),
                    'response_time_ms': round(duration * 1000, 2)
                }
                failed_services.append(service_name)
        
        if failed_services:
            health_results['status'] = 'unhealthy'
            health_results['failed_services'] = failed_services
        
        return health_results
    
    def check_database_connectivity(self) -> Dict[str, Any]:
        """Check database connectivity using Docker exec."""
        try:
            # Test MySQL connectivity
            result = subprocess.run([
                'docker', 'exec', 'mysql', 'mysql',
                '-u', 'africa_vlp_user',
                '-pexample_password',
                '-e', 'SELECT 1;'
            ], capture_output=True, text=True, timeout=self.timeout)
            
            if result.returncode == 0:
                return {
                    'status': 'healthy',
                    'details': {
                        'connection_test': 'passed',
                        'mysql_accessible': True
                    }
                }
            else:
                return {
                    'status': 'unhealthy',
                    'error': 'MySQL connection test failed',
                    'details': {
                        'connection_test': 'failed',
                        'error_output': result.stderr.strip()
                    }
                }
                
        except subprocess.TimeoutExpired:
            return {
                'status': 'unhealthy',
                'error': 'Database connection test timed out',
                'details': {'connection_test': 'timeout'}
            }
        except Exception as e:
            return {
                'status': 'unhealthy',
                'error': str(e),
                'details': {'connection_test': 'error'}
            }
    
    def check_system_resources(self) -> Dict[str, Any]:
        """Check system resource usage."""
        try:
            # Get Docker stats
            result = subprocess.run([
                'docker', 'stats', '--no-stream', '--format',
                'table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.MemPerc}}'
            ], capture_output=True, text=True, timeout=self.timeout)
            
            if result.returncode == 0:
                stats_lines = result.stdout.strip().split('\n')[1:]  # Skip header
                container_stats = []
                
                for line in stats_lines:
                    if line.strip():
                        parts = line.split('\t')
                        if len(parts) >= 4:
                            container_stats.append({
                                'container': parts[0].strip(),
                                'cpu_percent': parts[1].strip(),
                                'memory_usage': parts[2].strip(),
                                'memory_percent': parts[3].strip()
                            })
                
                # Check for high resource usage
                warnings = []
                for stat in container_stats:
                    try:
                        cpu_val = float(stat['cpu_percent'].rstrip('%'))
                        mem_val = float(stat['memory_percent'].rstrip('%'))
                        
                        if cpu_val > 80:
                            warnings.append(f"{stat['container']}: High CPU usage ({cpu_val}%)")
                        if mem_val > 85:
                            warnings.append(f"{stat['container']}: High memory usage ({mem_val}%)")
                    except (ValueError, AttributeError):
                        continue
                
                return {
                    'status': 'unhealthy' if warnings else 'healthy',
                    'warnings': warnings if warnings else None,
                    'details': {
                        'container_stats': container_stats,
                        'stats_accessible': True
                    }
                }
            else:
                return {
                    'status': 'unhealthy',
                    'error': 'Docker stats command failed',
                    'details': {'stats_accessible': False}
                }
                
        except Exception as e:
            return {
                'status': 'unhealthy',
                'error': str(e),
                'details': {'stats_accessible': False}
            }
    
    def print_results(self, results: Dict[str, Any], format_type: str = 'text'):
        """Print monitoring results."""
        if format_type == 'json':
            print(json.dumps(results, indent=2))
        else:
            self.print_text_results(results)
    
    def print_text_results(self, results: Dict[str, Any]):
        """Print results in human-readable text format."""
        print(f"\n=== AU-VLP System Monitor - {results['timestamp']} ===")
        print(f"Overall Status: {results['overall_status'].upper()}")
        print(f"Check Duration: {results['duration_ms']}ms")
        
        if results['overall_status'] == 'unhealthy' and 'failed_checks' in results:
            print(f"Failed Checks: {', '.join(results['failed_checks'])}")
        
        print("\n--- Detailed Results ---")
        
        for check_name, check_result in results['checks'].items():
            print(f"\n{check_name.upper()}:")
            
            if isinstance(check_result, dict):
                status = check_result.get('status', 'unknown')
                status_symbol = "✓" if status == 'healthy' else "✗"
                print(f"  {status_symbol} Status: {status.upper()}")
                
                if 'error' in check_result:
                    print(f"  Error: {check_result['error']}")
                
                if 'warnings' in check_result and check_result['warnings']:
                    print("  Warnings:")
                    for warning in check_result['warnings']:
                        print(f"    - {warning}")
                
                if 'services' in check_result:
                    print("  Services:")
                    for service_name, service_result in check_result['services'].items():
                        service_status = service_result.get('status', 'unknown')
                        service_symbol = "✓" if service_status == 'healthy' else "✗"
                        response_time = service_result.get('response_time_ms', 'N/A')
                        print(f"    {service_symbol} {service_name}: {service_status.upper()} ({response_time}ms)")
                        
                        if 'error' in service_result:
                            print(f"      Error: {service_result['error']}")
        
        print("")


def main():
    """Main function for command-line usage."""
    parser = argparse.ArgumentParser(description='AU-VLP System Monitor')
    parser.add_argument(
        '--format',
        choices=['json', 'text'],
        default='text',
        help='Output format (default: text)'
    )
    parser.add_argument(
        '--continuous',
        action='store_true',
        help='Run continuous monitoring'
    )
    parser.add_argument(
        '--interval',
        type=int,
        default=30,
        help='Interval in seconds for continuous monitoring (default: 30)'
    )
    parser.add_argument(
        '--timeout',
        type=int,
        default=10,
        help='Timeout in seconds for health checks (default: 10)'
    )
    
    args = parser.parse_args()
    
    monitor = SystemMonitor(timeout=args.timeout)
    
    if args.continuous:
        print(f"Starting continuous monitoring (interval: {args.interval}s)")
        print("Press Ctrl+C to stop")
        
        try:
            while True:
                results = monitor.monitor_all()
                monitor.print_results(results, args.format)
                
                if args.format == 'text':
                    print(f"Next check in {args.interval} seconds...")
                
                time.sleep(args.interval)
                
        except KeyboardInterrupt:
            print("\nMonitoring stopped by user")
    else:
        results = monitor.monitor_all()
        monitor.print_results(results, args.format)


if __name__ == '__main__':
    main()