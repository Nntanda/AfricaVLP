import React, { useState } from 'react';
import { Button, Input } from '../ui';
import { validateEmail } from '../../utils/validators';

interface NewsletterFormProps {
  onSubscribe: (email: string) => Promise<void>;
  loading?: boolean;
}

const NewsletterForm: React.FC<NewsletterFormProps> = ({ onSubscribe, loading = false }) => {
  const [email, setEmail] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    const validation = validateEmail(email);
    if (!validation.isValid) {
      setError(validation.message || 'Invalid email');
      return;
    }
    
    try {
      await onSubscribe(email);
      setEmail('');
      setError('');
    } catch (error) {
      // Error handling is done by parent component
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setEmail(e.target.value);
    if (error) setError('');
  };

  return (
    <form onSubmit={handleSubmit} className="flex space-x-2">
      <div className="flex-1">
        <Input
          type="email"
          value={email}
          onChange={handleChange}
          placeholder="Enter your email"
          error={error}
          required
        />
      </div>
      
      <Button type="submit" loading={loading}>
        Subscribe
      </Button>
    </form>
  );
};

export default NewsletterForm;