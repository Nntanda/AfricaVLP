#!/usr/bin/env python3
"""
Comprehensive Infrastructure Test Runner

This script runs all infrastructure tests in the correct order
and provides a unified report of the system health.

Requirements covered: 1.1, 1.3, 3.3, 4.3
"""

import os
import sys
import time
import json
import subprocess
from typing import Dict, List
from dataclasses import dataclass
from enum import Enum

class TestSuite(Enum):
    STARTUP = "startup"
    INFRASTRUCTURE = "infrastructure"
    DATABASE = "database"

@dataclass
class TestResult:
    suite: TestSuite
    success: bool
    duration: float
    output: str
    error: str = ""

class InfrastructureTestRunner:
    def __init__(self):
        self.results: List[TestResult] = []
        self.start_time = time.time()

    def run_test_script(self, script_name: str, suite: TestSuite, description: str) -> TestResult:
        """Run a test script and capture results"""
        print(f"\n{'='*60}")
        print(f"Running {description}")
        print(f"{'='*60}")
        
        start_time = time.time()
        
        try:
            # Make script executable
            os.chmod(script_name, 0o755)
            
            # Run the script
            result = subprocess.run([
                sys.executable, script_name
            ], capture_output=True, text=True, timeout=300)  # 5 minute timeout
            
            duration = time.time() - start_time
            success = result.returncode == 0
            
            # Print output in real-time style
            if result.stdout:
                print(result.stdout)
            
            if result.stderr and not success:
                print("STDERR:", result.stderr)
            
            test_result = TestResult(
                suite=suite,
                success=success,
                duration=duration,
                output=result.stdout,
                error=result.stderr
            )
            
            if success:
                print(f"\n✓ {description} completed successfully ({duration:.2f}s)")
            else:
                print(f"\n✗ {description} failed ({duration:.2f}s)")
            
            return test_result
            
        except subprocess.TimeoutExpired:
            duration = time.time() - start_time
            error_msg = f"Test timed out after {duration:.2f}s"
            print(f"\n✗ {error_msg}")
            
            return TestResult(
                suite=suite,
                success=False,
                duration=duration,
                output="",
                error=error_msg
            )
            
        except Exception as e:
            duration = time.time() - start_time
            error_msg = f"Test failed with exception: {e}"
            print(f"\n✗ {error_msg}")
            
            return TestResult(
                suite=suite,
                success=False,
                duration=duration,
                output="",
                error=error_msg
            )

    def check_prerequisites(self) -> bool:
        """Check that all required tools and services are available"""
        print("Checking prerequisites...")
        
        # Check Docker
        try:
            subprocess.run(['docker', '--version'], 
                         capture_output=True, check=True)
            print("✓ Docker is available")
        except (subprocess.CalledProcessError, FileNotFoundError):
            print("✗ Docker is not available")
            return False
        
        # Check Docker Compose
        try:
            subprocess.run(['docker-compose', '--version'], 
                         capture_output=True, check=True)
            print("✓ Docker Compose is available")
        except (subprocess.CalledProcessError, FileNotFoundError):
            print("✗ Docker Compose is not available")
            return False
        
        # Check Python packages
        required_packages = ['mysql-connector-python', 'redis', 'requests']
        missing_packages = []
        
        for package in required_packages:
            try:
                __import__(package.replace('-', '_'))
                print(f"✓ {package} is available")
            except ImportError:
                missing_packages.append(package)
                print(f"✗ {package} is missing")
        
        if missing_packages:
            print(f"\nTo install missing packages, run:")
            print(f"pip install {' '.join(missing_packages)}")
            return False
        
        return True

    def run_all_tests(self) -> bool:
        """Run all infrastructure test suites"""
        print("AU-VLP Infrastructure Test Suite")
        print("=" * 60)
        print(f"Started at: {time.strftime('%Y-%m-%d %H:%M:%S')}")
        
        # Check prerequisites
        if not self.check_prerequisites():
            print("\n✗ Prerequisites not met. Aborting tests.")
            return False
        
        # Define test suites in order
        test_suites = [
            ("validate_service_startup.py", TestSuite.STARTUP, "Service Startup Validation"),
            ("test_database_connectivity.py", TestSuite.DATABASE, "Database Connectivity Tests"),
            ("test_infrastructure.py", TestSuite.INFRASTRUCTURE, "Infrastructure Integration Tests")
        ]
        
        # Run each test suite
        for script, suite, description in test_suites:
            if not os.path.exists(script):
                print(f"\n✗ Test script {script} not found!")
                self.results.append(TestResult(
                    suite=suite,
                    success=False,
                    duration=0,
                    output="",
                    error=f"Script {script} not found"
                ))
                continue
            
            result = self.run_test_script(script, suite, description)
            self.results.append(result)
            
            # If a critical test fails, consider stopping
            if not result.success and suite in [TestSuite.STARTUP, TestSuite.DATABASE]:
                print(f"\n⚠ Critical test suite {suite.value} failed.")
                print("Continuing with remaining tests, but system may not be fully functional.")
        
        # Generate final report
        self.generate_final_report()
        
        # Return overall success
        return all(result.success for result in self.results)

    def generate_final_report(self):
        """Generate comprehensive test report"""
        total_duration = time.time() - self.start_time
        
        print("\n" + "=" * 60)
        print("FINAL TEST REPORT")
        print("=" * 60)
        
        # Summary statistics
        total_tests = len(self.results)
        passed_tests = sum(1 for r in self.results if r.success)
        failed_tests = total_tests - passed_tests
        
        print(f"Total Test Suites: {total_tests}")
        print(f"Passed: {passed_tests}")
        print(f"Failed: {failed_tests}")
        print(f"Success Rate: {(passed_tests/total_tests)*100:.1f}%")
        print(f"Total Duration: {total_duration:.2f}s")
        
        # Detailed results by suite
        print(f"\nDetailed Results:")
        for result in self.results:
            status = "✓ PASS" if result.success else "✗ FAIL"
            print(f"  {result.suite.value.title()}: {status} ({result.duration:.2f}s)")
            if not result.success and result.error:
                print(f"    Error: {result.error}")
        
        # Recommendations
        print(f"\nRecommendations:")
        if failed_tests == 0:
            print("  ✓ All tests passed! Your AU-VLP system is ready for use.")
        else:
            print("  ✗ Some tests failed. Please review the following:")
            
            for result in self.results:
                if not result.success:
                    if result.suite == TestSuite.STARTUP:
                        print("    - Check Docker Compose configuration and service dependencies")
                        print("    - Verify all containers are building successfully")
                        print("    - Check service health endpoints")
                    elif result.suite == TestSuite.DATABASE:
                        print("    - Verify MySQL connection parameters")
                        print("    - Check database migrations status")
                        print("    - Ensure database schema is correct")
                    elif result.suite == TestSuite.INFRASTRUCTURE:
                        print("    - Check inter-service communication")
                        print("    - Verify network connectivity between containers")
                        print("    - Review service logs for errors")
        
        # Save detailed report
        report_data = {
            'timestamp': time.strftime('%Y-%m-%d %H:%M:%S'),
            'total_duration': total_duration,
            'summary': {
                'total_tests': total_tests,
                'passed': passed_tests,
                'failed': failed_tests,
                'success_rate': (passed_tests/total_tests)*100
            },
            'results': [
                {
                    'suite': result.suite.value,
                    'success': result.success,
                    'duration': result.duration,
                    'error': result.error
                }
                for result in self.results
            ]
        }
        
        with open('infrastructure_test_report.json', 'w') as f:
            json.dump(report_data, f, indent=2)
        
        print(f"\nDetailed report saved to: infrastructure_test_report.json")

if __name__ == "__main__":
    runner = InfrastructureTestRunner()
    success = runner.run_all_tests()
    
    print(f"\n{'='*60}")
    if success:
        print("🎉 ALL INFRASTRUCTURE TESTS PASSED!")
        print("Your AU-VLP system is ready for development and testing.")
    else:
        print("❌ SOME INFRASTRUCTURE TESTS FAILED!")
        print("Please review the test output and fix any issues before proceeding.")
    print(f"{'='*60}")
    
    sys.exit(0 if success else 1)