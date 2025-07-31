import React, { useState } from 'react';
import Button from '../ui/Button';
import Input from '../ui/Input';

interface LoginFormProps {
  onSubmit: (credentials: { username: string; password: string }) => Promise<void>;
  loading?: boolean;
  error?: string;
}

const LoginForm: React.FC<LoginFormProps> = ({ onSubmit, loading = false, error }) => {
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
    <form onSubmit={handleSubmit} className="space-y-4">
      <Input
        label="Username"
        name="username"
        type="text"
        required
        value={credentials.username}
        onChange={handleChange}
        disabled={loading}
      />
      
      <Input
        label="Password"
        name="password"
        type="password"
        required
        value={credentials.password}
        onChange={handleChange}
        disabled={loading}
      />
      
      {error && (
        <div className="text-red-600 text-sm">{error}</div>
      )}
      
      <Button
        type="submit"
        variant="primary"
        loading={loading}
        className="w-full"
      >
        Sign In
      </Button>
    </form>
  );
};

export default LoginForm;