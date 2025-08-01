#!/usr/bin/env python3
"""
Infrastructure Testing Script for AU-VLP System

This script validates service startup, inter-service communication,
and database connectivity for the Docker-based AU-VLP system.

Requirements covered: 1.1, 1.3, 3.3, 4.3
"""

import os
import sys
import time
import json
import requests
import subprocess
from typing import Dict, List, Tuple, Optional
from dataclasses import dataclass
from enum import Enum

class TestStatus(Enum):
    PASS = "PASS"
    FAIL = "FAIL"
    SKIP = "SKIP"

@dataclass
class TestResult:
    name: str
    status: TestStatus
    message: str
    duration: float

class InfrastructureTestSuite:
    def __init__(self):
        self.results: List[TestResult] = []
        self.services = {
            'mysql': {'host': 'localhost', 'port': 3306},
            'redis': {'host': 'localhost', 'port': 6379},
            'admin-backend': {'host': 'localhost', 'port': 8000},
            'wellknown-backend': {'host': 'localhost', 'port': 8001},
            'admin-frontend': {'host': 'localhost', 'port': 3000},
            'wellknown-frontend': {'host': 'localhost', 'port': 3001},
            'nginx': {'host': 'localhost', 'port': 80},
            'flower': {'host': 'localhost', 'port': 5555}
        }
        
    def run_test(self, test_name: str, test_func) -> TestResult:
        """Run a single test and record the result"""
        print(f"Running test: {test_name}")
        start_time = time.time()
        
        try:
            test_func()
            duration = time.time() - start_time
            result = TestResult(test_name, TestStatus.PASS, "Test passed", duration)
            print(f"  ✓ PASS ({duration:.2f}s)")
        except Exception as e:
            duration = time.time() - start_time
            result = TestResult(test_name, TestStatus.FAIL, str(e), duration)
            print(f"  ✗ FAIL ({duration:.2f}s): {e}")
        
        self.results.append(result)
        return result

    def test_docker_containers_running(self):
        """Test that all required Docker containers are running"""
        try:
            result = subprocess.run(['docker', 'ps', '--format', 'json'], 
                                  capture_output=True, text=True, check=True)
            containers = [json.loads(line) for line in result.stdout.strip().split('\n') if line]
            
            required_containers = [
                'mysql', 'redis', 'admin-backend', 'wellknown-backend',
                'admin-frontend', 'wellknown-frontend', 'nginx'
            ]
            
            running_containers = [c['Names'] for c in containers]
            
            for required in required_containers:
                if not any(required in name for name in running_containers):
                    raise Exception(f"Required container '{required}' is not running")
                    
        except subprocess.CalledProcessError as e:
            raise Exception(f"Failed to check Docker containers: {e}")

    def test_mysql_connectivity(self):
        """Test MySQL database connectivity"""
        try:
            import mysql.connector
            
            connection = mysql.connector.connect(
                host=self.services['mysql']['host'],
                port=self.services['mysql']['port'],
                user=os.getenv('MYSQL_USER', 'root'),
                password=os.getenv('MYSQL_PASSWORD', 'password'),
                database=os.getenv('MYSQL_DATABASE', 'au_vlp_db'),
                connection_timeout=10
            )
            
            cursor = connection.cursor()
            cursor.execute("SELECT 1")
            result = cursor.fetchone()
            
            if result[0] != 1:
                raise Exception("MySQL query returned unexpected result")
                
            cursor.close()
            connection.close()
            
        except ImportError:
            raise Exception("mysql-connector-python not installed. Install with: pip install mysql-connector-python")
        except Exception as e:
            if 'mysql.connector' in str(type(e)):
                raise Exception(f"MySQL connection failed: {e}")
            else:
                raise Exception(f"MySQL connection failed: {e}")

    def test_redis_connectivity(self):
        """Test Redis connectivity"""
        try:
            import redis
            
            r = redis.Redis(
                host=self.services['redis']['host'],
                port=self.services['redis']['port'],
                decode_responses=True,
                socket_connect_timeout=10
            )
            
            # Test basic operations
            r.set('test_key', 'test_value')
            value = r.get('test_key')
            
            if value != 'test_value':
                raise Exception("Redis set/get operation failed")
                
            r.delete('test_key')
            
        except ImportError:
            raise Exception("redis not installed. Install with: pip install redis")
        except Exception as e:
            if 'redis' in str(type(e)).lower():
                raise Exception(f"Redis connection failed: {e}")
            else:
                raise Exception(f"Redis connection failed: {e}")

    def test_backend_health_endpoints(self):
        """Test Django backend health endpoints"""
        backends = ['admin-backend', 'wellknown-backend']
        
        for backend in backends:
            service = self.services[backend]
            url = f"http://{service['host']}:{service['port']}/health/"
            
            try:
                response = requests.get(url, timeout=10)
                response.raise_for_status()
                
                health_data = response.json()
                if health_data.get('status') != 'healthy':
                    raise Exception(f"{backend} health check failed: {health_data}")
                    
            except requests.RequestException as e:
                raise Exception(f"{backend} health endpoint failed: {e}")

    def test_frontend_accessibility(self):
        """Test that frontend applications are accessible"""
        frontends = ['admin-frontend', 'wellknown-frontend']
        
        for frontend in frontends:
            service = self.services[frontend]
            url = f"http://{service['host']}:{service['port']}"
            
            try:
                response = requests.get(url, timeout=10)
                response.raise_for_status()
                
                # Check for basic HTML structure
                if '<html' not in response.text.lower():
                    raise Exception(f"{frontend} did not return valid HTML")
                    
            except requests.RequestException as e:
                raise Exception(f"{frontend} accessibility test failed: {e}")

    def test_nginx_proxy(self):
        """Test Nginx reverse proxy functionality"""
        try:
            # Test main proxy endpoint
            response = requests.get(f"http://{self.services['nginx']['host']}", timeout=10)
            response.raise_for_status()
            
            # Test API proxy
            api_response = requests.get(
                f"http://{self.services['nginx']['host']}/api/admin/health/", 
                timeout=10
            )
            api_response.raise_for_status()
            
        except requests.RequestException as e:
            raise Exception(f"Nginx proxy test failed: {e}")

    def test_database_migrations(self):
        """Test database migration status"""
        backends = ['admin-backend', 'wellknown-backend']
        
        for backend in backends:
            try:
                # Check migration status via management command
                container_name = f"au-vlp-{backend}-1"  # Adjust based on actual container naming
                
                result = subprocess.run([
                    'docker', 'exec', container_name,
                    'python', 'manage.py', 'showmigrations', '--plan'
                ], capture_output=True, text=True, timeout=30)
                
                if result.returncode != 0:
                    raise Exception(f"{backend} migration check failed: {result.stderr}")
                
                # Check for unapplied migrations
                if '[X]' not in result.stdout:
                    raise Exception(f"{backend} has no applied migrations")
                    
            except subprocess.TimeoutExpired:
                raise Exception(f"{backend} migration check timed out")
            except subprocess.CalledProcessError as e:
                raise Exception(f"{backend} migration check failed: {e}")

    def test_inter_service_communication(self):
        """Test communication between services"""
        # Test admin backend to database
        try:
            response = requests.get(
                f"http://{self.services['admin-backend']['host']}:{self.services['admin-backend']['port']}/api/users/",
                timeout=10
            )
            # Should get 401 or 403 (auth required) rather than 500 (service error)
            if response.status_code >= 500:
                raise Exception("Admin backend database communication failed")
                
        except requests.RequestException as e:
            raise Exception(f"Admin backend communication test failed: {e}")
        
        # Test wellknown backend to database
        try:
            response = requests.get(
                f"http://{self.services['wellknown-backend']['host']}:{self.services['wellknown-backend']['port']}/api/events/",
                timeout=10
            )
            if response.status_code >= 500:
                raise Exception("Wellknown backend database communication failed")
                
        except requests.RequestException as e:
            raise Exception(f"Wellknown backend communication test failed: {e}")

    def test_celery_services(self):
        """Test Celery worker and beat services"""
        try:
            # Test Flower monitoring interface
            response = requests.get(
                f"http://{self.services['flower']['host']}:{self.services['flower']['port']}/api/workers",
                timeout=10
            )
            response.raise_for_status()
            
            workers = response.json()
            if not workers:
                raise Exception("No Celery workers found")
                
        except requests.RequestException as e:
            raise Exception(f"Celery services test failed: {e}")

    def run_all_tests(self):
        """Run all infrastructure tests"""
        print("Starting Infrastructure Test Suite")
        print("=" * 50)
        
        # Service startup validation tests
        self.run_test("Docker Containers Running", self.test_docker_containers_running)
        self.run_test("MySQL Connectivity", self.test_mysql_connectivity)
        self.run_test("Redis Connectivity", self.test_redis_connectivity)
        
        # Backend service tests
        self.run_test("Backend Health Endpoints", self.test_backend_health_endpoints)
        self.run_test("Frontend Accessibility", self.test_frontend_accessibility)
        self.run_test("Nginx Proxy", self.test_nginx_proxy)
        
        # Database and migration tests
        self.run_test("Database Migrations", self.test_database_migrations)
        
        # Inter-service communication tests
        self.run_test("Inter-Service Communication", self.test_inter_service_communication)
        self.run_test("Celery Services", self.test_celery_services)
        
        # Print summary
        self.print_summary()
        
        return self.get_exit_code()

    def print_summary(self):
        """Print test results summary"""
        print("\n" + "=" * 50)
        print("Test Results Summary")
        print("=" * 50)
        
        passed = sum(1 for r in self.results if r.status == TestStatus.PASS)
        failed = sum(1 for r in self.results if r.status == TestStatus.FAIL)
        total = len(self.results)
        
        print(f"Total Tests: {total}")
        print(f"Passed: {passed}")
        print(f"Failed: {failed}")
        print(f"Success Rate: {(passed/total)*100:.1f}%")
        
        if failed > 0:
            print("\nFailed Tests:")
            for result in self.results:
                if result.status == TestStatus.FAIL:
                    print(f"  - {result.name}: {result.message}")
        
        total_duration = sum(r.duration for r in self.results)
        print(f"\nTotal Duration: {total_duration:.2f}s")

    def get_exit_code(self) -> int:
        """Return appropriate exit code based on test results"""
        failed_tests = sum(1 for r in self.results if r.status == TestStatus.FAIL)
        return 1 if failed_tests > 0 else 0

if __name__ == "__main__":
    test_suite = InfrastructureTestSuite()
    exit_code = test_suite.run_all_tests()
    sys.exit(exit_code)