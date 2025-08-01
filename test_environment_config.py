#!/usr/bin/env python3
"""
Test script to verify environment configuration changes.
"""
import os
import sys
from pathlib import Path

def test_env_file_structure():
    """Test that .env files have the correct structure."""
    print("Testing .env file structure...")
    
    # Check main .env file
    env_file = Path('.env')
    if not env_file.exists():
        print("❌ .env file not found")
        return False
        
    with open(env_file, 'r') as f:
        content = f.read()
        
    required_sections = [
        'Environment Configuration',
        'Django Settings',
        'Database Configuration',
        'Redis Configuration',
        'CORS Configuration',
        'Frontend URLs',
        'API Base URLs',
        'Security Settings',
        'Email Configuration',
        'Logging Configuration',
        'Environment Validation'
    ]
    
    missing_sections = []
    for section in required_sections:
        if f"# {section}" not in content:
            missing_sections.append(section)
            
    if missing_sections:
        print(f"❌ Missing sections in .env: {missing_sections}")
        return False
        
    print("✅ .env file structure is correct")
    return True

def test_cors_origins():
    """Test CORS origins configuration."""
    print("Testing CORS origins configuration...")
    
    with open('.env', 'r') as f:
        content = f.read()
        
    # Find CORS_ALLOWED_ORIGINS line
    cors_line = None
    for line in content.split('\n'):
        if line.startswith('CORS_ALLOWED_ORIGINS='):
            cors_line = line
            break
            
    if not cors_line:
        print("❌ CORS_ALLOWED_ORIGINS not found in .env")
        return False
        
    origins = cors_line.split('=', 1)[1].split(',')
    
    required_origins = [
        'http://localhost:3000',
        'http://localhost:3001',
        'http://admin-frontend:3000',
        'http://wellknown-frontend:3000',
        'http://admin.localhost',
        'http://wellknown.localhost',
        'http://nginx'
    ]
    
    missing_origins = []
    for origin in required_origins:
        if origin not in origins:
            missing_origins.append(origin)
            
    if missing_origins:
        print(f"❌ Missing CORS origins: {missing_origins}")
        return False
        
    print("✅ CORS origins configuration is correct")
    return True

def test_frontend_env_files():
    """Test frontend environment files."""
    print("Testing frontend environment files...")
    
    frontend_files = [
        'admin-frontend/.env',
        'admin-frontend/.env.production',
        'wellknown-frontend/.env.development',
        'wellknown-frontend/.env.production'
    ]
    
    for file_path in frontend_files:
        if not Path(file_path).exists():
            print(f"❌ {file_path} not found")
            return False
            
        with open(file_path, 'r') as f:
            content = f.read()
            
        # Check for API base URL
        if 'VITE_API_BASE_URL' not in content:
            print(f"❌ VITE_API_BASE_URL not found in {file_path}")
            return False
            
        # Check production files use proxy
        if '.env.production' in file_path:
            if 'VITE_API_BASE_URL=/api/v1' not in content:
                print(f"❌ Production file {file_path} should use proxy URL")
                return False
                
    print("✅ Frontend environment files are correct")
    return True

def test_vite_config():
    """Test Vite configuration files."""
    print("Testing Vite configuration files...")
    
    vite_files = [
        'admin-frontend/vite.config.ts',
        'wellknown-frontend/vite.config.ts'
    ]
    
    for file_path in vite_files:
        if not Path(file_path).exists():
            print(f"❌ {file_path} not found")
            return False
            
        with open(file_path, 'r') as f:
            content = f.read()
            
        # Check for health endpoint proxy
        if "'/health':" not in content:
            print(f"❌ Health endpoint proxy not found in {file_path}")
            return False
            
        # Check for proper backend target
        if 'admin-frontend' in file_path:
            if 'http://admin-backend:8000' not in content:
                print(f"❌ Wrong backend target in {file_path}")
                return False
        else:
            if 'http://wellknown-backend:8000' not in content:
                print(f"❌ Wrong backend target in {file_path}")
                return False
                
    print("✅ Vite configuration files are correct")
    return True

def test_validation_utilities():
    """Test that validation utilities exist."""
    print("Testing validation utilities...")
    
    validation_files = [
        'admin-backend/models_app/utils/env_validator.py',
        'wellknown-backend/core/utils/env_validator.py',
        'admin-backend/models_app/management/commands/validate_env.py',
        'wellknown-backend/core/management/commands/validate_env.py'
    ]
    
    for file_path in validation_files:
        if not Path(file_path).exists():
            print(f"❌ {file_path} not found")
            return False
            
    print("✅ Validation utilities are present")
    return True

def main():
    """Run all tests."""
    print("🔍 Testing AU-VLP Environment Configuration")
    print("=" * 50)
    
    tests = [
        test_env_file_structure,
        test_cors_origins,
        test_frontend_env_files,
        test_vite_config,
        test_validation_utilities
    ]
    
    passed = 0
    total = len(tests)
    
    for test in tests:
        try:
            if test():
                passed += 1
            print()
        except Exception as e:
            print(f"❌ Test failed with error: {e}")
            print()
    
    print("=" * 50)
    print(f"Results: {passed}/{total} tests passed")
    
    if passed == total:
        print("🎉 All environment configuration tests passed!")
        return 0
    else:
        print("❌ Some tests failed. Please check the configuration.")
        return 1

if __name__ == "__main__":
    sys.exit(main())