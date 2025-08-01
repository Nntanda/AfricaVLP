#!/usr/bin/env python3
"""
End-to-End Validation Test Runner

This script runs all end-to-end validation tests and provides
a comprehensive assessment of system functionality.

Requirements covered: 2.4, 3.4, 5.4
"""

import os
import sys
import time
import json
import subprocess
from typing import Dict, List
from dataclasses import dataclass

@dataclass
class ValidationResult:
    test_name: str
    success: bool
    duration: float
    exit_code: int
    output: str
    error: str = ""

class EndToEndValidationRunner:
    def __init__(self):
        self.results: List[ValidationResult] = []
        self.start_time = time.time()

    def run_validation_script(self, script_name: str, description: str) -> ValidationResult:
        """Run a validation script and capture results"""
        print(f"\n{'='*60}")
        print(f"Running {description}")
        print(f"{'='*60}")
        
        if not os.path.exists(script_name):
            print(f"✗ Script {script_name} not found!")
            return ValidationResult(
                test_name=description,
                success=False,
                duration=0,
                exit_code=-1,
                output="",
                error=f"Script {script_name} not found"
            )
        
        start_time = time.time()
        
        try:
            # Make script executable
            os.chmod(script_name, 0o755)
            
            # Run the script
            result = subprocess.run([
                sys.executable, script_name
            ], capture_output=True, text=True, timeout=600)  # 10 minute timeout
            
            duration = time.time() - start_time
            success = result.returncode == 0
            
            # Print output
            if result.stdout:
                print(result.stdout)
            
            if result.stderr and not success:
                print("STDERR:", result.stderr)
            
            validation_result = ValidationResult(
                test_name=description,
                success=success,
                duration=duration,
                exit_code=result.returncode,
                output=result.stdout,
                error=result.stderr
            )
            
            if success:
                print(f"\n✓ {description} completed successfully ({duration:.2f}s)")
            else:
                print(f"\n✗ {description} failed with exit code {result.returncode} ({duration:.2f}s)")
            
            return validation_result
            
        except subprocess.TimeoutExpired:
            duration = time.time() - start_time
            error_msg = f"Validation timed out after {duration:.2f}s"
            print(f"\n✗ {error_msg}")
            
            return ValidationResult(
                test_name=description,
                success=False,
                duration=duration,
                exit_code=-2,
                output="",
                error=error_msg
            )
            
        except Exception as e:
            duration = time.time() - start_time
            error_msg = f"Validation failed with exception: {e}"
            print(f"\n✗ {error_msg}")
            
            return ValidationResult(
                test_name=description,
                success=False,
                duration=duration,
                exit_code=-3,
                output="",
                error=error_msg
            )

    def check_prerequisites(self) -> bool:
        """Check that all required dependencies are available"""
        print("Checking Prerequisites for End-to-End Validation...")
        
        # Check Python packages
        required_packages = [
            'requests',
            'mysql-connector-python',
            'redis'
        ]
        
        missing_packages = []
        for package in required_packages:
            try:
                __import__(package.replace('-', '_'))
                print(f"✓ {package} is available")
            except ImportError:
                missing_packages.append(package)
                print(f"✗ {package} is missing")
        
        # Check optional packages
        optional_packages = ['selenium']
        for package in optional_packages:
            try:
                __import__(package)
                print(f"✓ {package} is available (enhanced frontend testing)")
            except ImportError:
                print(f"⚠ {package} is missing (limited frontend testing)")
        
        if missing_packages:
            print(f"\nTo install missing packages, run:")
            print(f"pip install {' '.join(missing_packages)}")
            return False
        
        return True

    def run_all_validations(self) -> bool:
        """Run all end-to-end validation tests"""
        print("AU-VLP End-to-End Validation Suite")
        print("=" * 60)
        print(f"Started at: {time.strftime('%Y-%m-%d %H:%M:%S')}")
        
        # Check prerequisites
        if not self.check_prerequisites():
            print("\n✗ Prerequisites not met. Some tests may fail.")
            print("Continuing with available tests...")
        
        print()
        
        # Define validation tests in order
        validation_tests = [
            ("system_health_check.py", "System Health Check"),
            ("test_end_to_end_system.py", "End-to-End System Tests"),
        ]
        
        # Run each validation
        for script, description in validation_tests:
            result = self.run_validation_script(script, description)
            self.results.append(result)
        
        # Generate final report
        return self.generate_final_report()

    def generate_final_report(self) -> bool:
        """Generate comprehensive validation report"""
        total_duration = time.time() - self.start_time
        
        print("\n" + "=" * 60)
        print("END-TO-END VALIDATION REPORT")
        print("=" * 60)
        
        # Summary statistics
        total_validations = len(self.results)
        passed_validations = sum(1 for r in self.results if r.success)
        failed_validations = total_validations - passed_validations
        
        print(f"Total Validations: {total_validations}")
        print(f"Passed: {passed_validations}")
        print(f"Failed: {failed_validations}")
        
        if total_validations > 0:
            success_rate = (passed_validations / total_validations) * 100
            print(f"Success Rate: {success_rate:.1f}%")
        else:
            success_rate = 0
        
        print(f"Total Duration: {total_duration:.2f}s")
        
        # Detailed results
        print(f"\nDetailed Results:")
        for result in self.results:
            status = "✓ PASS" if result.success else "✗ FAIL"
            print(f"  {result.test_name}: {status} ({result.duration:.2f}s)")
            if not result.success:
                if result.exit_code > 0:
                    print(f"    Exit Code: {result.exit_code}")
                if result.error:
                    print(f"    Error: {result.error}")
        
        # System readiness assessment
        print(f"\nSystem Readiness Assessment:")
        if failed_validations == 0:
            print("  🟢 SYSTEM READY")
            print("  All end-to-end validations passed successfully.")
            print("  The AU-VLP system is fully functional and ready for use.")
        elif failed_validations == 1:
            print("  🟡 SYSTEM PARTIALLY READY")
            print("  Most validations passed but some issues were detected.")
            print("  The system may function but with limited capabilities.")
        else:
            print("  🔴 SYSTEM NOT READY")
            print("  Multiple validation failures detected.")
            print("  The system requires fixes before it can be used reliably.")
        
        # Recommendations
        print(f"\nRecommendations:")
        if failed_validations == 0:
            print("  ✓ System is ready for development and testing")
            print("  ✓ All services are functioning correctly")
            print("  ✓ End-to-end workflows are working")
        else:
            print("  ✗ Review failed validations and fix underlying issues")
            print("  ✗ Check service logs for detailed error information")
            print("  ✗ Ensure all Docker containers are running properly")
            
            # Specific recommendations based on failures
            for result in self.results:
                if not result.success:
                    if "health" in result.test_name.lower():
                        print("  ✗ Check Docker Compose configuration and service health")
                    elif "end-to-end" in result.test_name.lower():
                        print("  ✗ Verify API endpoints and frontend functionality")
        
        # Save detailed report
        report_data = {
            'timestamp': time.strftime('%Y-%m-%d %H:%M:%S'),
            'total_duration': total_duration,
            'summary': {
                'total_validations': total_validations,
                'passed': passed_validations,
                'failed': failed_validations,
                'success_rate': success_rate
            },
            'system_readiness': 'ready' if failed_validations == 0 else 'partial' if failed_validations == 1 else 'not_ready',
            'results': [
                {
                    'test_name': result.test_name,
                    'success': result.success,
                    'duration': result.duration,
                    'exit_code': result.exit_code,
                    'error': result.error
                }
                for result in self.results
            ]
        }
        
        with open('end_to_end_validation_report.json', 'w') as f:
            json.dump(report_data, f, indent=2)
        
        print(f"\nDetailed report saved to: end_to_end_validation_report.json")
        
        return failed_validations == 0

if __name__ == "__main__":
    runner = EndToEndValidationRunner()
    success = runner.run_all_validations()
    
    print(f"\n{'='*60}")
    if success:
        print("🎉 ALL END-TO-END VALIDATIONS PASSED!")
        print("Your AU-VLP system is fully validated and ready for use.")
    else:
        print("❌ SOME END-TO-END VALIDATIONS FAILED!")
        print("Please review the validation results and fix any issues.")
    print(f"{'='*60}")
    
    sys.exit(0 if success else 1)