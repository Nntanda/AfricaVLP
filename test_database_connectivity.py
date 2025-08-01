#!/usr/bin/env python3
"""
Database Connectivity and Migration Testing Script

This script tests database connectivity, migration status, and data integrity
for the AU-VLP system.

Requirements covered: 3.3, 4.3
"""

import os
import sys
import time
import json
import subprocess
from typing import Dict, List, Tuple, Optional
from dataclasses import dataclass

@dataclass
class MigrationInfo:
    app: str
    migration: str
    applied: bool

class DatabaseTester:
    def __init__(self):
        self.db_config = {
            'host': os.getenv('MYSQL_HOST', 'localhost'),
            'port': int(os.getenv('MYSQL_PORT', '3306')),
            'user': os.getenv('MYSQL_USER', 'root'),
            'password': os.getenv('MYSQL_PASSWORD', 'password'),
            'database': os.getenv('MYSQL_DATABASE', 'au_vlp_db')
        }
        
        self.backends = ['admin-backend', 'wellknown-backend']
        self.connection = None

    def connect_to_database(self) -> bool:
        """Establish connection to MySQL database"""
        try:
            import mysql.connector
            
            print("Connecting to MySQL database...")
            self.connection = mysql.connector.connect(
                **self.db_config,
                connection_timeout=30,
                autocommit=True
            )
            
            if self.connection.is_connected():
                print("✓ Successfully connected to MySQL database")
                return True
            else:
                print("✗ Failed to connect to MySQL database")
                return False
                
        except ImportError:
            print("✗ mysql-connector-python not installed. Install with: pip install mysql-connector-python")
            return False
        except Exception as e:
            print(f"✗ Database connection error: {e}")
            return False

    def test_basic_connectivity(self) -> bool:
        """Test basic database operations"""
        if not self.connection or not self.connection.is_connected():
            return False
        
        try:
            cursor = self.connection.cursor()
            
            # Test basic query
            cursor.execute("SELECT 1 as test")
            result = cursor.fetchone()
            
            if result[0] != 1:
                print("✗ Basic query test failed")
                return False
            
            # Test database selection
            cursor.execute(f"USE {self.db_config['database']}")
            
            # Test table listing
            cursor.execute("SHOW TABLES")
            tables = cursor.fetchall()
            
            print(f"✓ Database contains {len(tables)} tables")
            
            cursor.close()
            return True
            
        except Exception as e:
            print(f"✗ Basic connectivity test failed: {e}")
            return False

    def test_database_schema(self) -> bool:
        """Test database schema integrity"""
        try:
            cursor = self.connection.cursor()
            
            # Check for essential tables
            essential_tables = [
                'django_migrations',
                'auth_user',
                'django_content_type',
                'django_session'
            ]
            
            cursor.execute("SHOW TABLES")
            existing_tables = [table[0] for table in cursor.fetchall()]
            
            missing_tables = []
            for table in essential_tables:
                if table not in existing_tables:
                    missing_tables.append(table)
            
            if missing_tables:
                print(f"✗ Missing essential tables: {', '.join(missing_tables)}")
                return False
            
            print("✓ All essential tables present")
            
            # Check table structures
            for table in essential_tables:
                cursor.execute(f"DESCRIBE {table}")
                columns = cursor.fetchall()
                
                if not columns:
                    print(f"✗ Table {table} has no columns")
                    return False
            
            print("✓ Table structures appear valid")
            cursor.close()
            return True
            
        except Exception as e:
            print(f"✗ Schema validation failed: {e}")
            return False

    def get_migration_status(self, backend: str) -> List[MigrationInfo]:
        """Get migration status for a Django backend"""
        try:
            container_name = f"au-vlp-{backend}-1"  # Adjust based on actual naming
            
            # Get migration status
            result = subprocess.run([
                'docker', 'exec', container_name,
                'python', 'manage.py', 'showmigrations', '--plan'
            ], capture_output=True, text=True, timeout=60)
            
            if result.returncode != 0:
                print(f"✗ Failed to get migration status for {backend}: {result.stderr}")
                return []
            
            migrations = []
            current_app = None
            
            for line in result.stdout.split('\n'):
                line = line.strip()
                if not line:
                    continue
                
                if line.endswith(':'):
                    current_app = line[:-1]
                elif line.startswith('[') and current_app:
                    applied = line.startswith('[X]')
                    migration_name = line[4:].strip()
                    migrations.append(MigrationInfo(current_app, migration_name, applied))
            
            return migrations
            
        except subprocess.TimeoutExpired:
            print(f"✗ Migration status check timed out for {backend}")
            return []
        except subprocess.CalledProcessError as e:
            print(f"✗ Migration status check failed for {backend}: {e}")
            return []

    def test_migrations(self) -> bool:
        """Test migration status for all backends"""
        print("Testing database migrations...")
        
        all_migrations_ok = True
        
        for backend in self.backends:
            print(f"\nChecking migrations for {backend}:")
            
            migrations = self.get_migration_status(backend)
            
            if not migrations:
                print(f"  ✗ Could not retrieve migration status")
                all_migrations_ok = False
                continue
            
            applied_count = sum(1 for m in migrations if m.applied)
            total_count = len(migrations)
            unapplied_count = total_count - applied_count
            
            print(f"  Total migrations: {total_count}")
            print(f"  Applied: {applied_count}")
            print(f"  Unapplied: {unapplied_count}")
            
            if unapplied_count > 0:
                print(f"  ⚠ Warning: {unapplied_count} unapplied migrations")
                unapplied = [m for m in migrations if not m.applied]
                for migration in unapplied[:5]:  # Show first 5
                    print(f"    - {migration.app}: {migration.migration}")
                if len(unapplied) > 5:
                    print(f"    ... and {len(unapplied) - 5} more")
            else:
                print(f"  ✓ All migrations applied")
        
        return all_migrations_ok

    def test_database_performance(self) -> bool:
        """Test basic database performance"""
        try:
            cursor = self.connection.cursor()
            
            # Test query performance
            start_time = time.time()
            cursor.execute("SELECT COUNT(*) FROM django_migrations")
            result = cursor.fetchone()
            query_time = time.time() - start_time
            
            print(f"✓ Query performance: {query_time:.3f}s for migration count")
            
            if query_time > 5.0:
                print("⚠ Warning: Query took longer than expected")
            
            # Test connection pool
            start_time = time.time()
            for i in range(10):
                cursor.execute("SELECT 1")
                cursor.fetchone()
            pool_time = time.time() - start_time
            
            print(f"✓ Connection pool test: {pool_time:.3f}s for 10 queries")
            
            cursor.close()
            return True
            
        except Exception as e:
            print(f"✗ Performance test failed: {e}")
            return False

    def test_data_integrity(self) -> bool:
        """Test basic data integrity"""
        try:
            cursor = self.connection.cursor()
            
            # Check for foreign key constraints
            cursor.execute("""
                SELECT COUNT(*) 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_SCHEMA = %s 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            """, (self.db_config['database'],))
            
            fk_count = cursor.fetchone()[0]
            print(f"✓ Database has {fk_count} foreign key constraints")
            
            # Check for orphaned records (basic check)
            cursor.execute("""
                SELECT COUNT(*) FROM django_content_type 
                WHERE app_label NOT IN (
                    SELECT DISTINCT app_label FROM django_migrations
                )
            """)
            
            orphaned = cursor.fetchone()[0]
            if orphaned > 0:
                print(f"⚠ Warning: {orphaned} potentially orphaned content types")
            else:
                print("✓ No obvious data integrity issues found")
            
            cursor.close()
            return True
            
        except Exception as e:
            print(f"✗ Data integrity test failed: {e}")
            return False

    def test_backup_restore_capability(self) -> bool:
        """Test database backup and restore capability"""
        try:
            # Test mysqldump availability
            result = subprocess.run([
                'docker', 'exec', 'au-vlp-mysql-1',
                'mysqldump', '--version'
            ], capture_output=True, text=True, timeout=10)
            
            if result.returncode != 0:
                print("✗ mysqldump not available in container")
                return False
            
            # Test backup creation (dry run)
            backup_cmd = [
                'docker', 'exec', 'au-vlp-mysql-1',
                'mysqldump', 
                f'-u{self.db_config["user"]}',
                f'-p{self.db_config["password"]}',
                '--single-transaction',
                '--routines',
                '--triggers',
                self.db_config['database']
            ]
            
            result = subprocess.run(
                backup_cmd + ['--where=1=0'],  # Empty backup for testing
                capture_output=True, text=True, timeout=30
            )
            
            if result.returncode != 0:
                print(f"✗ Backup test failed: {result.stderr}")
                return False
            
            print("✓ Database backup capability verified")
            return True
            
        except subprocess.TimeoutExpired:
            print("✗ Backup test timed out")
            return False
        except subprocess.CalledProcessError as e:
            print(f"✗ Backup test failed: {e}")
            return False

    def run_all_tests(self) -> bool:
        """Run all database tests"""
        print("Starting Database Connectivity and Migration Tests")
        print("=" * 60)
        
        success = True
        
        # Basic connectivity
        if not self.connect_to_database():
            return False
        
        # Run all tests
        tests = [
            ("Basic Connectivity", self.test_basic_connectivity),
            ("Database Schema", self.test_database_schema),
            ("Migrations", self.test_migrations),
            ("Performance", self.test_database_performance),
            ("Data Integrity", self.test_data_integrity),
            ("Backup Capability", self.test_backup_restore_capability)
        ]
        
        for test_name, test_func in tests:
            print(f"\n--- {test_name} Test ---")
            try:
                if not test_func():
                    success = False
            except Exception as e:
                print(f"✗ {test_name} test failed with exception: {e}")
                success = False
        
        # Cleanup
        if self.connection and self.connection.is_connected():
            self.connection.close()
            print("\n✓ Database connection closed")
        
        print("\n" + "=" * 60)
        if success:
            print("✓ All database tests passed!")
        else:
            print("✗ Some database tests failed!")
        
        return success

if __name__ == "__main__":
    tester = DatabaseTester()
    success = tester.run_all_tests()
    sys.exit(0 if success else 1)