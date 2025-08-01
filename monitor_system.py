#!/usr/bin/env python3
"""
AU-VLP System Monitor with Service Recovery
Monitors Docker services and implements automatic recovery procedures
"""

import json
import subprocess
import time
import logging
import sys
import signal
import threading
from datetime import datetime, timedelta
from typing import Dict, List, Optional, Tuple
from dataclasses import dataclass
from enum import Enum

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('system_monitor.log'),
        logging.StreamHandler(sys.stdout)
    ]
)
logger = logging.getLogger(__name__)

class ServiceState(Enum):
    HEALTHY = "healthy"
    UNHEALTHY = "unhealthy"
    STARTING = "starting"
    STOPPED = "stopped"
    UNKNOWN = "unknown"

@dataclass
class ServiceConfig:
    name: str
    type: str
    criticality: str
    max_restart_attempts: int
    restart_delay: int
    health_check_interval: int
    dependencies: List[str]

@dataclass
class ServiceStatus:
    name: str
    state: ServiceState
    health: str
    last_check: datetime
    restart_count: int
    last_restart: Optional[datetime]

class ServiceMonitor:
    def __init__(self):
        self.services = {
            'mysql': ServiceConfig(
                name='mysql',
                type='database',
                criticality='critical',
                max_restart_attempts=3,
                restart_delay=30,
                health_check_interval=30,
                dependencies=[]
            ),
            'redis': ServiceConfig(
                name='redis',
                type='cache',
                criticality='critical',
                max_restart_attempts=3,
                restart_delay=15,
                health_check_interval=30,
                dependencies=[]
            ),
            'admin-backend': ServiceConfig(
                name='admin-backend',
                type='api',
                criticality='critical',
                max_restart_attempts=3,
                restart_delay=20,
                health_check_interval=30,
                dependencies=['mysql', 'redis']
            ),
            'wellknown-backend': ServiceConfig(
                name='wellknown-backend',
                type='api',
                criticality='critical',
                max_restart_attempts=3,
                restart_delay=20,
                health_check_interval=30,
                dependencies=['mysql', 'redis']
            ),
            'celery-worker': ServiceConfig(
                name='celery-worker',
                type='worker',
                criticality='high',
                max_restart_attempts=5,
                restart_delay=15,
                health_check_interval=60,
                dependencies=['mysql', 'redis', 'admin-backend']
            ),
            'celery-beat': ServiceConfig(
                name='celery-beat',
                type='scheduler',
                criticality='high',
                max_restart_attempts=3,
                restart_delay=20,
                health_check_interval=60,
                dependencies=['mysql', 'redis', 'admin-backend']
            ),
            'celery-flower': ServiceConfig(
                name='celery-flower',
                type='monitor',
                criticality='medium',
                max_restart_attempts=3,
                restart_delay=10,
                health_check_interval=60,
                dependencies=['redis', 'celery-worker']
            ),
            'admin-frontend': ServiceConfig(
                name='admin-frontend',
                type='frontend',
                criticality='high',
                max_restart_attempts=3,
                restart_delay=15,
                health_check_interval=30,
                dependencies=['admin-backend']
            ),
            'wellknown-frontend': ServiceConfig(
                name='wellknown-frontend',
                type='frontend',
                criticality='high',
                max_restart_attempts=3,
                restart_delay=15,
                health_check_interval=30,
                dependencies=['wellknown-backend']
            ),
            'nginx': ServiceConfig(
                name='nginx',
                type='proxy',
                criticality='critical',
                max_restart_attempts=3,
                restart_delay=10,
                health_check_interval=30,
                dependencies=['admin-backend', 'wellknown-backend', 'admin-frontend', 'wellknown-frontend']
            )
        }
        
        self.service_status: Dict[str, ServiceStatus] = {}
        self.running = False
        self.recovery_in_progress = set()
        
        # Initialize service status
        for service_name in self.services:
            self.service_status[service_name] = ServiceStatus(
                name=service_name,
                state=ServiceState.UNKNOWN,
                health="unknown",
                last_check=datetime.now(),
                restart_count=0,
                last_restart=None
            )

    def run_command(self, command: List[str]) -> Tuple[bool, str]:
        """Execute a shell command and return success status and output"""
        try:
            result = subprocess.run(
                command,
                capture_output=True,
                text=True,
                timeout=30
            )
            return result.returncode == 0, result.stdout.strip()
        except subprocess.TimeoutExpired:
            logger.error(f"Command timed out: {' '.join(command)}")
            return False, "Command timed out"
        except Exception as e:
            logger.error(f"Command failed: {' '.join(command)}, Error: {e}")
            return False, str(e)

    def get_service_status(self, service_name: str) -> ServiceStatus:
        """Get current status of a service from Docker Compose"""
        success, output = self.run_command(['docker-compose', 'ps', '--format', 'json', service_name])
        
        if not success or not output:
            logger.warning(f"Failed to get status for service {service_name}")
            return ServiceStatus(
                name=service_name,
                state=ServiceState.UNKNOWN,
                health="unknown",
                last_check=datetime.now(),
                restart_count=self.service_status[service_name].restart_count,
                last_restart=self.service_status[service_name].last_restart
            )
        
        try:
            service_data = json.loads(output)
            
            # Determine service state
            docker_state = service_data.get('State', 'unknown').lower()
            health_status = service_data.get('Health', 'unknown').lower()
            
            if docker_state == 'running':
                if health_status == 'healthy':
                    state = ServiceState.HEALTHY
                elif health_status in ['starting', 'unhealthy']:
                    state = ServiceState.STARTING if health_status == 'starting' else ServiceState.UNHEALTHY
                else:
                    state = ServiceState.HEALTHY  # Assume healthy if no health check
            elif docker_state in ['exited', 'dead']:
                state = ServiceState.STOPPED
            else:
                state = ServiceState.UNKNOWN
            
            return ServiceStatus(
                name=service_name,
                state=state,
                health=health_status,
                last_check=datetime.now(),
                restart_count=self.service_status[service_name].restart_count,
                last_restart=self.service_status[service_name].last_restart
            )
            
        except json.JSONDecodeError as e:
            logger.error(f"Failed to parse service status JSON for {service_name}: {e}")
            return self.service_status[service_name]

    def check_dependencies(self, service_name: str) -> bool:
        """Check if all dependencies of a service are healthy"""
        service_config = self.services[service_name]
        
        for dep_name in service_config.dependencies:
            dep_status = self.service_status.get(dep_name)
            if not dep_status or dep_status.state != ServiceState.HEALTHY:
                logger.info(f"Dependency {dep_name} of {service_name} is not healthy")
                return False
        
        return True

    def restart_service(self, service_name: str) -> bool:
        """Restart a service with proper error handling"""
        if service_name in self.recovery_in_progress:
            logger.info(f"Recovery already in progress for {service_name}")
            return False
        
        service_config = self.services[service_name]
        service_status = self.service_status[service_name]
        
        # Check restart limits
        if service_status.restart_count >= service_config.max_restart_attempts:
            logger.error(f"Service {service_name} has exceeded maximum restart attempts ({service_config.max_restart_attempts})")
            return False
        
        # Check if dependencies are healthy
        if not self.check_dependencies(service_name):
            logger.warning(f"Cannot restart {service_name}: dependencies are not healthy")
            return False
        
        self.recovery_in_progress.add(service_name)
        
        try:
            logger.info(f"Attempting to restart service {service_name} (attempt {service_status.restart_count + 1})")
            
            # Stop the service first
            success, output = self.run_command(['docker-compose', 'stop', service_name])
            if not success:
                logger.error(f"Failed to stop service {service_name}: {output}")
                return False
            
            # Wait for graceful shutdown
            time.sleep(5)
            
            # Start the service
            success, output = self.run_command(['docker-compose', 'start', service_name])
            if not success:
                logger.error(f"Failed to start service {service_name}: {output}")
                return False
            
            # Update restart tracking
            service_status.restart_count += 1
            service_status.last_restart = datetime.now()
            
            logger.info(f"Service {service_name} restart initiated successfully")
            
            # Wait for the service to start
            time.sleep(service_config.restart_delay)
            
            return True
            
        except Exception as e:
            logger.error(f"Exception during restart of {service_name}: {e}")
            return False
        finally:
            self.recovery_in_progress.discard(service_name)

    def handle_service_failure(self, service_name: str):
        """Handle a service failure with appropriate recovery actions"""
        service_config = self.services[service_name]
        service_status = self.service_status[service_name]
        
        logger.warning(f"Service failure detected: {service_name} (state: {service_status.state})")
        
        # For critical services, attempt immediate restart
        if service_config.criticality == 'critical':
            if self.restart_service(service_name):
                logger.info(f"Restart initiated for critical service {service_name}")
            else:
                logger.error(f"Failed to restart critical service {service_name}")
                self.send_alert(service_name, "Critical service restart failed")
        
        # For high priority services, restart with delay
        elif service_config.criticality == 'high':
            logger.info(f"Scheduling restart for high priority service {service_name}")
            threading.Timer(10.0, self.restart_service, args=[service_name]).start()
        
        # For medium priority services, log and monitor
        else:
            logger.info(f"Medium priority service {service_name} failed, monitoring for recovery")

    def send_alert(self, service_name: str, message: str):
        """Send alert for critical service issues"""
        alert_message = f"ALERT: {service_name} - {message} at {datetime.now()}"
        logger.critical(alert_message)
        
        # Here you could integrate with external alerting systems
        # For now, we'll just log the alert
        with open('alerts.log', 'a') as f:
            f.write(f"{alert_message}\n")

    def monitor_services(self):
        """Main monitoring loop"""
        logger.info("Starting service monitoring...")
        
        while self.running:
            try:
                for service_name, service_config in self.services.items():
                    # Get current status
                    current_status = self.get_service_status(service_name)
                    previous_status = self.service_status[service_name]
                    
                    # Update status
                    self.service_status[service_name] = current_status
                    
                    # Check for state changes
                    if current_status.state != previous_status.state:
                        logger.info(f"Service {service_name} state changed: {previous_status.state} -> {current_status.state}")
                    
                    # Handle failures
                    if current_status.state in [ServiceState.UNHEALTHY, ServiceState.STOPPED]:
                        # Only handle if this is a new failure or if enough time has passed
                        if (previous_status.state == ServiceState.HEALTHY or 
                            (current_status.last_restart and 
                             datetime.now() - current_status.last_restart > timedelta(minutes=5))):
                            self.handle_service_failure(service_name)
                
                # Wait before next check
                time.sleep(30)
                
            except KeyboardInterrupt:
                logger.info("Monitoring interrupted by user")
                break
            except Exception as e:
                logger.error(f"Error in monitoring loop: {e}")
                time.sleep(10)

    def print_status_report(self):
        """Print current status of all services"""
        print("\n" + "="*60)
        print("AU-VLP SYSTEM STATUS REPORT")
        print("="*60)
        print(f"Report generated at: {datetime.now()}")
        print()
        
        for service_name, status in self.service_status.items():
            config = self.services[service_name]
            
            status_icon = {
                ServiceState.HEALTHY: "✓",
                ServiceState.UNHEALTHY: "✗",
                ServiceState.STARTING: "⚠",
                ServiceState.STOPPED: "⏹",
                ServiceState.UNKNOWN: "?"
            }.get(status.state, "?")
            
            print(f"{status_icon} {service_name:<20} | {status.state.value:<12} | {config.criticality:<8} | Restarts: {status.restart_count}")
        
        print("\n" + "="*60)

    def start(self):
        """Start the monitoring system"""
        self.running = True
        
        # Set up signal handlers for graceful shutdown
        signal.signal(signal.SIGINT, self.signal_handler)
        signal.signal(signal.SIGTERM, self.signal_handler)
        
        logger.info("AU-VLP System Monitor starting...")
        
        # Start monitoring in a separate thread
        monitor_thread = threading.Thread(target=self.monitor_services)
        monitor_thread.daemon = True
        monitor_thread.start()
        
        # Main thread handles user interaction
        try:
            while self.running:
                time.sleep(60)  # Print status every minute
                self.print_status_report()
        except KeyboardInterrupt:
            pass
        
        self.stop()

    def stop(self):
        """Stop the monitoring system"""
        logger.info("Stopping system monitor...")
        self.running = False

    def signal_handler(self, signum, frame):
        """Handle shutdown signals"""
        logger.info(f"Received signal {signum}, shutting down...")
        self.stop()

def main():
    """Main entry point"""
    if len(sys.argv) > 1:
        if sys.argv[1] == '--status':
            # Just print status and exit
            monitor = ServiceMonitor()
            for service_name in monitor.services:
                status = monitor.get_service_status(service_name)
                monitor.service_status[service_name] = status
            monitor.print_status_report()
            return
        elif sys.argv[1] == '--help':
            print("AU-VLP System Monitor")
            print("Usage:")
            print("  python monitor_system.py          - Start continuous monitoring")
            print("  python monitor_system.py --status - Show current status and exit")
            print("  python monitor_system.py --help   - Show this help message")
            return
    
    # Start continuous monitoring
    monitor = ServiceMonitor()
    monitor.start()

if __name__ == "__main__":
    main()