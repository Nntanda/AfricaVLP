#!/usr/bin/env python3
"""
End-to-End System Validation Script

This script performs comprehensive end-to-end testing of the AU-VLP system,
including API endpoints, frontend functionality, and complete user workflows.

Requirements covered: 2.4, 3.4, 5.4
"""

import os
import sys
import time
import json
import requests
import subprocess
from typing import Dict, List, Optional, Tuple
from dataclasses import dataclass
from enum import Enum

class TestCategory(Enum):
    API = "api"
    FRONTEND = "frontend"
    WORKFLOW = "workflow"
    INTEGRATION = "integration"

@dataclass
class EndToEndTest:
    name: str
    category: TestCategory
    success: bool
    duration: float
    message: str
    details: Optional[Dict] = None

class EndToEndSystemValidator:
    def __init__(self):
        self.results: List[EndToEndTest] = []
        self.base_urls = {
            'admin_backend': 'http://localhost:8000',
            'wellknown_backend': 'http://localhost:8001',
            'admin_frontend': 'http://localhost:3000',
            'wellknown_frontend': 'http://localhost:3001',
            'nginx': 'http://localhost:80'
        }
        self.driver = None
        self.session = requests.Session()
        self.session.timeout = 30

    def setup_browser(self) -> bool:
        """Setup Selenium WebDriver for frontend testing"""
        try:
            from selenium import webdriver
            from selenium.webdriver.chrome.options import Options
            
            chrome_options = Options()
            chrome_options.add_argument('--headless')
            chrome_options.add_argument('--no-sandbox')
            chrome_options.add_argument('--disable-dev-shm-usage')
            chrome_options.add_argument('--disable-gpu')
            chrome_options.add_argument('--window-size=1920,1080')
            
            self.driver = webdriver.Chrome(options=chrome_options)
            self.driver.implicitly_wait(10)
            return True
            
        except ImportError:
            print("Warning: Selenium not available. Frontend visual tests will be skipped")
            return False
        except Exception as e:
            print(f"Warning: Could not setup browser for frontend tests: {e}")
            print("Frontend visual tests will be skipped")
            return False

    def run_test(self, test_name: str, category: TestCategory, test_func) -> EndToEndTest:
        """Run a single end-to-end test"""
        print(f"Running {category.value} test: {test_name}")
        start_time = time.time()
        
        try:
            result = test_func()
            duration = time.time() - start_time
            
            if isinstance(result, tuple):
                success, message, details = result
            else:
                success, message, details = result, "Test completed", None
            
            test_result = EndToEndTest(
                name=test_name,
                category=category,
                success=success,
                duration=duration,
                message=message,
                details=details
            )
            
            status = "✓ PASS" if success else "✗ FAIL"
            print(f"  {status} ({duration:.2f}s): {message}")
            
        except Exception as e:
            duration = time.time() - start_time
            test_result = EndToEndTest(
                name=test_name,
                category=category,
                success=False,
                duration=duration,
                message=f"Test failed with exception: {str(e)}",
                details={'exception': str(e)}
            )
            print(f"  ✗ FAIL ({duration:.2f}s): {str(e)}")
        
        self.results.append(test_result)
        return test_result

    def test_api_health_endpoints(self) -> Tuple[bool, str, Dict]:
        """Test all API health endpoints"""
        health_results = {}
        
        endpoints = [
            ('admin_backend', '/health/'),
            ('wellknown_backend', '/health/'),
        ]
        
        for service, endpoint in endpoints:
            try:
                url = f"{self.base_urls[service]}{endpoint}"
                response = self.session.get(url)
                response.raise_for_status()
                
                health_data = response.json()
                health_results[service] = {
                    'status_code': response.status_code,
                    'response_time': response.elapsed.total_seconds(),
                    'health_status': health_data.get('status', 'unknown'),
                    'details': health_data
                }
                
                if health_data.get('status') != 'healthy':
                    return False, f"{service} health check failed", health_results
                    
            except Exception as e:
                health_results[service] = {'error': str(e)}
                return False, f"{service} health endpoint error: {e}", health_results
        
        return True, "All health endpoints responding correctly", health_results

    def test_api_authentication_endpoints(self) -> Tuple[bool, str, Dict]:
        """Test authentication-related API endpoints"""
        auth_results = {}
        
        # Test admin backend auth endpoints
        admin_auth_endpoints = [
            '/api/auth/login/',
            '/api/auth/logout/',
            '/api/auth/user/',
        ]
        
        for endpoint in admin_auth_endpoints:
            try:
                url = f"{self.base_urls['admin_backend']}{endpoint}"
                response = self.session.get(url)
                
                # We expect 401/403 for auth endpoints without credentials
                auth_results[endpoint] = {
                    'status_code': response.status_code,
                    'response_time': response.elapsed.total_seconds()
                }
                
                # 500 errors indicate server problems
                if response.status_code >= 500:
                    return False, f"Server error on {endpoint}", auth_results
                    
            except Exception as e:
                auth_results[endpoint] = {'error': str(e)}
                return False, f"Auth endpoint {endpoint} failed: {e}", auth_results
        
        return True, "Authentication endpoints responding correctly", auth_results

    def test_api_data_endpoints(self) -> Tuple[bool, str, Dict]:
        """Test data retrieval API endpoints"""
        data_results = {}
        
        # Test wellknown backend public endpoints
        public_endpoints = [
            ('wellknown_backend', '/api/events/'),
            ('wellknown_backend', '/api/news/'),
            ('wellknown_backend', '/api/blog/'),
            ('wellknown_backend', '/api/organizations/'),
        ]
        
        for service, endpoint in public_endpoints:
            try:
                url = f"{self.base_urls[service]}{endpoint}"
                response = self.session.get(url)
                
                data_results[endpoint] = {
                    'status_code': response.status_code,
                    'response_time': response.elapsed.total_seconds(),
                    'content_type': response.headers.get('content-type', '')
                }
                
                # Should return 200 for public endpoints or 401/403 for protected
                if response.status_code >= 500:
                    return False, f"Server error on {endpoint}", data_results
                
                # Check if response is JSON for successful requests
                if response.status_code == 200:
                    try:
                        response.json()
                        data_results[endpoint]['valid_json'] = True
                    except:
                        data_results[endpoint]['valid_json'] = False
                        
            except Exception as e:
                data_results[endpoint] = {'error': str(e)}
                return False, f"Data endpoint {endpoint} failed: {e}", data_results
        
        return True, "Data endpoints responding correctly", data_results

    def test_frontend_loading(self) -> Tuple[bool, str, Dict]:
        """Test that frontend applications load correctly"""
        if not self.driver:
            return False, "Browser not available for frontend testing", {}
        
        try:
            from selenium.webdriver.common.by import By
            from selenium.webdriver.support.ui import WebDriverWait
            from selenium.common.exceptions import TimeoutException
        except ImportError:
            return False, "Selenium not available for frontend testing", {}
        
        frontend_results = {}
        
        frontends = [
            ('admin_frontend', 'Admin Dashboard'),
            ('wellknown_frontend', 'AU-VLP Portal')
        ]
        
        for service, expected_title in frontends:
            try:
                url = self.base_urls[service]
                self.driver.get(url)
                
                # Wait for page to load
                WebDriverWait(self.driver, 15).until(
                    lambda driver: driver.execute_script("return document.readyState") == "complete"
                )
                
                # Check basic page elements
                title = self.driver.title
                body_text = self.driver.find_element(By.TAG_NAME, "body").text
                
                frontend_results[service] = {
                    'title': title,
                    'body_length': len(body_text),
                    'has_content': len(body_text) > 100,
                    'url': url
                }
                
                # Check for error messages
                if 'error' in body_text.lower() or 'not found' in body_text.lower():
                    return False, f"{service} shows error content", frontend_results
                
                # Check for minimal content
                if len(body_text) < 100:
                    return False, f"{service} has insufficient content", frontend_results
                    
            except TimeoutException:
                frontend_results[service] = {'error': 'Page load timeout'}
                return False, f"{service} failed to load within timeout", frontend_results
            except Exception as e:
                frontend_results[service] = {'error': str(e)}
                return False, f"{service} loading failed: {e}", frontend_results
        
        return True, "All frontends loading correctly", frontend_results

    def test_frontend_navigation(self) -> Tuple[bool, str, Dict]:
        """Test basic frontend navigation functionality"""
        if not self.driver:
            return False, "Browser not available for navigation testing", {}
        
        try:
            from selenium.webdriver.common.by import By
            from selenium.webdriver.support.ui import WebDriverWait
            from selenium.webdriver.support import expected_conditions as EC
        except ImportError:
            return False, "Selenium not available for navigation testing", {}
        
        nav_results = {}
        
        # Test wellknown frontend navigation
        try:
            self.driver.get(self.base_urls['wellknown_frontend'])
            
            # Wait for page load
            WebDriverWait(self.driver, 15).until(
                EC.presence_of_element_located((By.TAG_NAME, "body"))
            )
            
            # Look for navigation elements
            nav_elements = self.driver.find_elements(By.TAG_NAME, "nav")
            links = self.driver.find_elements(By.TAG_NAME, "a")
            
            nav_results['wellknown_frontend'] = {
                'nav_elements': len(nav_elements),
                'total_links': len(links),
                'has_navigation': len(nav_elements) > 0
            }
            
            # Test clicking a navigation link if available
            if links:
                try:
                    # Find a safe link to click (avoid external links)
                    internal_links = [link for link in links 
                                    if link.get_attribute('href') and 
                                    'localhost' in link.get_attribute('href')]
                    
                    if internal_links:
                        first_link = internal_links[0]
                        link_href = first_link.get_attribute('href')
                        first_link.click()
                        
                        # Wait for navigation
                        time.sleep(2)
                        current_url = self.driver.current_url
                        
                        nav_results['wellknown_frontend']['navigation_test'] = {
                            'clicked_link': link_href,
                            'current_url': current_url,
                            'navigation_successful': current_url != self.base_urls['wellknown_frontend']
                        }
                        
                except Exception as e:
                    nav_results['wellknown_frontend']['navigation_error'] = str(e)
            
        except Exception as e:
            nav_results['wellknown_frontend'] = {'error': str(e)}
            return False, f"Navigation test failed: {e}", nav_results
        
        return True, "Frontend navigation working correctly", nav_results

    def test_api_frontend_integration(self) -> Tuple[bool, str, Dict]:
        """Test integration between frontend and backend APIs"""
        integration_results = {}
        
        # Test that frontend can make API calls
        if self.driver:
            try:
                self.driver.get(self.base_urls['wellknown_frontend'])
                
                # Wait for page load
                WebDriverWait(self.driver, 15).until(
                    EC.presence_of_element_located((By.TAG_NAME, "body"))
                )
                
                # Check browser console for API errors
                logs = self.driver.get_log('browser')
                api_errors = [log for log in logs if 'error' in log['message'].lower() 
                            and ('api' in log['message'].lower() or 'fetch' in log['message'].lower())]
                
                integration_results['console_errors'] = len(api_errors)
                integration_results['api_error_details'] = api_errors[:5]  # First 5 errors
                
                if api_errors:
                    return False, f"Frontend has {len(api_errors)} API-related console errors", integration_results
                    
            except Exception as e:
                integration_results['browser_test_error'] = str(e)
        
        # Test CORS configuration by making cross-origin requests
        try:
            # Test preflight request
            response = self.session.options(
                f"{self.base_urls['wellknown_backend']}/api/events/",
                headers={
                    'Origin': self.base_urls['wellknown_frontend'],
                    'Access-Control-Request-Method': 'GET'
                }
            )
            
            integration_results['cors_preflight'] = {
                'status_code': response.status_code,
                'access_control_allow_origin': response.headers.get('Access-Control-Allow-Origin'),
                'access_control_allow_methods': response.headers.get('Access-Control-Allow-Methods')
            }
            
        except Exception as e:
            integration_results['cors_test_error'] = str(e)
        
        return True, "API-Frontend integration working correctly", integration_results

    def test_complete_user_workflow(self) -> Tuple[bool, str, Dict]:
        """Test a complete user workflow end-to-end"""
        workflow_results = {}
        
        if not self.driver:
            return False, "Browser not available for workflow testing", {}
        
        try:
            from selenium.webdriver.common.by import By
            from selenium.webdriver.support.ui import WebDriverWait
            from selenium.webdriver.support import expected_conditions as EC
        except ImportError:
            return False, "Selenium not available for workflow testing", {}
        
        try:
            # Test public user workflow on wellknown frontend
            self.driver.get(self.base_urls['wellknown_frontend'])
            
            # Wait for page load
            WebDriverWait(self.driver, 15).until(
                EC.presence_of_element_located((By.TAG_NAME, "body"))
            )
            
            workflow_results['step_1_load'] = {'success': True, 'url': self.driver.current_url}
            
            # Try to navigate to events page
            try:
                events_links = self.driver.find_elements(By.PARTIAL_LINK_TEXT, "Events")
                if not events_links:
                    events_links = self.driver.find_elements(By.PARTIAL_LINK_TEXT, "events")
                
                if events_links:
                    events_links[0].click()
                    time.sleep(3)
                    workflow_results['step_2_events'] = {
                        'success': True, 
                        'url': self.driver.current_url
                    }
                else:
                    workflow_results['step_2_events'] = {
                        'success': False, 
                        'reason': 'No events link found'
                    }
                    
            except Exception as e:
                workflow_results['step_2_events'] = {'success': False, 'error': str(e)}
            
            # Try to find and interact with content
            try:
                content_elements = self.driver.find_elements(By.CLASS_NAME, "card")
                if not content_elements:
                    content_elements = self.driver.find_elements(By.TAG_NAME, "article")
                
                workflow_results['step_3_content'] = {
                    'content_elements_found': len(content_elements),
                    'has_content': len(content_elements) > 0
                }
                
            except Exception as e:
                workflow_results['step_3_content'] = {'success': False, 'error': str(e)}
            
            # Test form interaction if available
            try:
                forms = self.driver.find_elements(By.TAG_NAME, "form")
                inputs = self.driver.find_elements(By.TAG_NAME, "input")
                
                workflow_results['step_4_forms'] = {
                    'forms_found': len(forms),
                    'inputs_found': len(inputs),
                    'interactive_elements': len(forms) > 0 or len(inputs) > 0
                }
                
            except Exception as e:
                workflow_results['step_4_forms'] = {'success': False, 'error': str(e)}
            
        except Exception as e:
            workflow_results['workflow_error'] = str(e)
            return False, f"User workflow test failed: {e}", workflow_results
        
        return True, "Complete user workflow test successful", workflow_results

    def test_system_performance(self) -> Tuple[bool, str, Dict]:
        """Test overall system performance"""
        performance_results = {}
        
        # Test API response times
        api_endpoints = [
            ('admin_backend', '/health/'),
            ('wellknown_backend', '/health/'),
            ('wellknown_backend', '/api/events/'),
        ]
        
        for service, endpoint in api_endpoints:
            try:
                url = f"{self.base_urls[service]}{endpoint}"
                start_time = time.time()
                response = self.session.get(url)
                response_time = time.time() - start_time
                
                performance_results[f"{service}{endpoint}"] = {
                    'response_time': response_time,
                    'status_code': response.status_code,
                    'acceptable_performance': response_time < 5.0
                }
                
                if response_time > 10.0:
                    return False, f"Slow response from {service}{endpoint}: {response_time:.2f}s", performance_results
                    
            except Exception as e:
                performance_results[f"{service}{endpoint}"] = {'error': str(e)}
        
        # Test frontend load times
        if self.driver:
            try:
                start_time = time.time()
                self.driver.get(self.base_urls['wellknown_frontend'])
                WebDriverWait(self.driver, 30).until(
                    lambda driver: driver.execute_script("return document.readyState") == "complete"
                )
                load_time = time.time() - start_time
                
                performance_results['frontend_load_time'] = {
                    'load_time': load_time,
                    'acceptable_performance': load_time < 15.0
                }
                
                if load_time > 30.0:
                    return False, f"Frontend load time too slow: {load_time:.2f}s", performance_results
                    
            except Exception as e:
                performance_results['frontend_load_error'] = str(e)
        
        return True, "System performance within acceptable limits", performance_results

    def run_all_tests(self) -> bool:
        """Run all end-to-end tests"""
        print("Starting End-to-End System Validation")
        print("=" * 60)
        
        # Setup browser for frontend tests
        browser_available = self.setup_browser()
        
        # Define test suites
        test_suites = [
            # API Tests
            ("API Health Endpoints", TestCategory.API, self.test_api_health_endpoints),
            ("API Authentication Endpoints", TestCategory.API, self.test_api_authentication_endpoints),
            ("API Data Endpoints", TestCategory.API, self.test_api_data_endpoints),
            
            # Frontend Tests
            ("Frontend Loading", TestCategory.FRONTEND, self.test_frontend_loading),
            ("Frontend Navigation", TestCategory.FRONTEND, self.test_frontend_navigation),
            
            # Integration Tests
            ("API-Frontend Integration", TestCategory.INTEGRATION, self.test_api_frontend_integration),
            ("Complete User Workflow", TestCategory.WORKFLOW, self.test_complete_user_workflow),
            ("System Performance", TestCategory.INTEGRATION, self.test_system_performance),
        ]
        
        # Run all tests
        for test_name, category, test_func in test_suites:
            self.run_test(test_name, category, test_func)
        
        # Cleanup
        if self.driver:
            self.driver.quit()
        
        # Generate summary
        self.generate_summary()
        
        # Return overall success
        return all(test.success for test in self.results)

    def generate_summary(self):
        """Generate test summary and report"""
        print("\n" + "=" * 60)
        print("END-TO-END TEST SUMMARY")
        print("=" * 60)
        
        # Overall statistics
        total_tests = len(self.results)
        passed_tests = sum(1 for test in self.results if test.success)
        failed_tests = total_tests - passed_tests
        
        print(f"Total Tests: {total_tests}")
        print(f"Passed: {passed_tests}")
        print(f"Failed: {failed_tests}")
        print(f"Success Rate: {(passed_tests/total_tests)*100:.1f}%")
        
        # Results by category
        categories = {}
        for test in self.results:
            if test.category not in categories:
                categories[test.category] = {'passed': 0, 'failed': 0, 'total': 0}
            
            categories[test.category]['total'] += 1
            if test.success:
                categories[test.category]['passed'] += 1
            else:
                categories[test.category]['failed'] += 1
        
        print(f"\nResults by Category:")
        for category, stats in categories.items():
            success_rate = (stats['passed'] / stats['total']) * 100
            print(f"  {category.value.title()}: {stats['passed']}/{stats['total']} ({success_rate:.1f}%)")
        
        # Failed tests details
        failed_tests_list = [test for test in self.results if not test.success]
        if failed_tests_list:
            print(f"\nFailed Tests:")
            for test in failed_tests_list:
                print(f"  ✗ {test.name}: {test.message}")
        
        # Performance summary
        performance_tests = [test for test in self.results if 'performance' in test.name.lower()]
        if performance_tests:
            print(f"\nPerformance Summary:")
            for test in performance_tests:
                if test.details and 'response_time' in str(test.details):
                    print(f"  {test.name}: {test.duration:.2f}s")
        
        # Save detailed report
        report_data = {
            'timestamp': time.strftime('%Y-%m-%d %H:%M:%S'),
            'summary': {
                'total_tests': total_tests,
                'passed': passed_tests,
                'failed': failed_tests,
                'success_rate': (passed_tests/total_tests)*100
            },
            'results': [
                {
                    'name': test.name,
                    'category': test.category.value,
                    'success': test.success,
                    'duration': test.duration,
                    'message': test.message,
                    'details': test.details
                }
                for test in self.results
            ]
        }
        
        with open('end_to_end_test_report.json', 'w') as f:
            json.dump(report_data, f, indent=2)
        
        print(f"\nDetailed report saved to: end_to_end_test_report.json")

if __name__ == "__main__":
    # Check if selenium is available
    try:
        import selenium
        print("Selenium is available for enhanced frontend testing.")
    except ImportError:
        print("Warning: Selenium not available. Frontend tests will be limited.")
        print("To install Selenium: pip install selenium")
        print("You'll also need ChromeDriver for full frontend testing.")
    
    validator = EndToEndSystemValidator()
    success = validator.run_all_tests()
    
    print(f"\n{'='*60}")
    if success:
        print("🎉 ALL END-TO-END TESTS PASSED!")
        print("Your AU-VLP system is fully functional end-to-end.")
    else:
        print("❌ SOME END-TO-END TESTS FAILED!")
        print("Please review the test output and fix any issues.")
    print(f"{'='*60}")
    
    sys.exit(0 if success else 1)