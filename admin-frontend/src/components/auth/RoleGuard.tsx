import React from 'react';
import { useAuthContext } from '../../context/AuthContext';

interface RoleGuardProps {
  children: React.ReactNode;
  allowedRoles: ('super_admin' | 'admin')[];
  fallback?: React.ReactNode;
}

const RoleGuard: React.FC<RoleGuardProps> = ({ 
  children, 
  allowedRoles, 
  fallback = null 
}) => {
  const { user } = useAuthContext();

  if (!user || !allowedRoles.includes(user.role)) {
    return <>{fallback}</>;
  }

  return <>{children}</>;
};

export default RoleGuard;