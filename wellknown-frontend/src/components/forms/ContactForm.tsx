import React, { useState } from 'react';
import { Button, Input } from '../ui';
import { validateEmail, validateRequired } from '../../utils/validators';

interface ContactFormData {
  name: string;
  email: string;
  subject: string;
  message: string;
}

interface ContactFormProps {
  onSubmit: (data: ContactFormData) => Promise<void>;
  loading?: boolean;
}

const ContactForm: React.FC<ContactFormProps> = ({ onSubmit, loading = false }) => {
  const [formData, setFormData] = useState<ContactFormData>({
    name: '',
    email: '',
    subject: '',
    message: '',
  });
  
  const [errors, setErrors] = useState<Partial<ContactFormData>>({});

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    
    // Clear error when user starts typing
    if (errors[name as keyof ContactFormData]) {
      setErrors(prev => ({ ...prev, [name]: undefined }));
    }
  };

  const validateForm = (): boolean => {
    const newErrors: Partial<ContactFormData> = {};
    
    const nameValidation = validateRequired(formData.name, 'Name');
    if (!nameValidation.isValid) newErrors.name = nameValidation.message;
    
    const emailValidation = validateEmail(formData.email);
    if (!emailValidation.isValid) newErrors.email = emailValidation.message;
    
    const subjectValidation = validateRequired(formData.subject, 'Subject');
    if (!subjectValidation.isValid) newErrors.subject = subjectValidation.message;
    
    const messageValidation = validateRequired(formData.message, 'Message');
    if (!messageValidation.isValid) newErrors.message = messageValidation.message;
    
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!validateForm()) return;
    
    try {
      await onSubmit(formData);
      // Reset form on success
      setFormData({ name: '', email: '', subject: '', message: '' });
    } catch (error) {
      // Error handling is done by parent component
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Input
          label="Name"
          name="name"
          value={formData.name}
          onChange={handleChange}
          error={errors.name}
          required
        />
        
        <Input
          label="Email"
          name="email"
          type="email"
          value={formData.email}
          onChange={handleChange}
          error={errors.email}
          required
        />
      </div>
      
      <Input
        label="Subject"
        name="subject"
        value={formData.subject}
        onChange={handleChange}
        error={errors.subject}
        required
      />
      
      <div>
        <label htmlFor="message" className="block text-sm font-medium text-gray-700 mb-1">
          Message
        </label>
        <textarea
          id="message"
          name="message"
          rows={5}
          value={formData.message}
          onChange={handleChange}
          className={`block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-1 sm:text-sm ${
            errors.message
              ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500'
              : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'
          }`}
          required
        />
        {errors.message && (
          <p className="mt-1 text-sm text-red-600">{errors.message}</p>
        )}
      </div>
      
      <Button type="submit" loading={loading} className="w-full">
        Send Message
      </Button>
    </form>
  );
};

export default ContactForm;