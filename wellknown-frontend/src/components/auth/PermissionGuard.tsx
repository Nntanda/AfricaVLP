import React from 'react';
import { useAuthContext } from '../../context/AuthContext';

interface PermissionGuardProps {
  children: React.ReactNode;
  requiredPermissions: string[];
  requireAll?: boolean; // If true, user must have ALL permissions. If false, user needs ANY permission
  fallback?: React.ReactNode;
}

const PermissionGuard: React.FC<PermissionGuardProps> = ({ 
  children, 
  requiredPermissions,
  requireAll = false,
  fallback = null 
}) => {
  const { user } = useAuthContext();

  if (!user || !user.permissions) {
    return <>{fallback}</>;
  }

  const hasPermission = requireAll
    ? requiredPermissions.every(permission => user.permissions.includes(permission))
    : requiredPermissions.some(permission => user.permissions.includes(permission));

  if (!hasPermission) {
    return <>{fallback}</>;
  }

  return <>{children}</>;
};

export default PermissionGuard;