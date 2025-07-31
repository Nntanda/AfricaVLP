import React, { useState } from 'react';
import Button from '../ui/Button';
import Input from '../ui/Input';

interface ContactFormProps {
  onSubmit: (data: { name: string; email: string; message: string }) => Promise<void>;
  loading?: boolean;
}

const ContactForm: React.FC<ContactFormProps> = ({ onSubmit, loading = false }) => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    message: '',
  });

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await onSubmit(formData);
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <Input
        label="Name"
        name="name"
        type="text"
        required
        value={formData.name}
        onChange={handleChange}
        disabled={loading}
      />
      
      <Input
        label="Email"
        name="email"
        type="email"
        required
        value={formData.email}
        onChange={handleChange}
        disabled={loading}
      />
      
      <div>
        <label className="block text-sm font-medium text-gray-700 mb-1">
          Message
        </label>
        <textarea
          name="message"
          rows={4}
          required
          value={formData.message}
          onChange={handleChange}
          disabled={loading}
          className="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        />
      </div>
      
      <Button type="submit" loading={loading}>
        Send Message
      </Button>
    </form>
  );
};

export default ContactForm;