import React, { useState } from 'react';
import Button from '../ui/Button';
import Input from '../ui/Input';
import AuthErrorHandler from './AuthErrorHandler';

interface LoginFormProps {
  onSubmit: (credentials: { username: string; password: string }) => Promise<void>;
  loading?: boolean;
  error?: string;
  onClearError?: () => void;
}

const LoginForm: React.FC<LoginFormProps> = ({ onSubmit, loading = false, error, onClearError }) => {
  const [credentials, setCredentials] = useState({
    username: '',
    password: '',
  });

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await onSubmit(credentials);
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setCredentials({
      ...credentials,
      [e.target.name]: e.target.value,
    });
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <Input
        label="Username"
        name="username"
        type="text"
        value={credentials.username}
        onChange={handleChange}
        disabled={loading}
        required
      />
      
      <Input
        label="Password"
        name="password"
        type="password"
        value={credentials.password}
        onChange={handleChange}
        disabled={loading}
        required
      />
      
      <AuthErrorHandler
        error={error}
        onRetry={() => onSubmit(credentials)}
        onClear={onClearError}
      />
      
      <Button
        type="submit"
        loading={loading}
        className="w-full"
      >
        Sign In
      </Button>
    </form>
  );
};

export default LoginForm;