import React from 'react';
import { useFormValidation } from '../../hooks/useFormValidation';
import { useErrorHandler } from '../../hooks/useErrorHandler';
import { useRetry } from '../../hooks/useRetry';
import ValidatedInput from './ValidatedInput';
import ValidatedTextarea from './ValidatedTextarea';
import Button from '../ui/Button';

interface ContactFormData {
  name: string;
  email: string;
  subject: string;
  message: string;
}

interface EnhancedContactFormProps {
  onSubmit: (data: ContactFormData) => Promise<void>;
  isLoading?: boolean;
}

const EnhancedContactForm: React.FC<EnhancedContactFormProps> = ({ onSubmit, isLoading = false }) => {
  const { handleError, handleSuccess } = useErrorHandler();
  const { retry, isRetrying } = useRetry();

  const {
    values,
    errors,
    touched,
    isValid,
    handleChange,
    handleBlur,
    validateAll,
    reset
  } = useFormValidation<ContactFormData>(
    { name: '', email: '', subject: '', message: '' },
    {
      name: {
        required: true,
        minLength: 2,
        maxLength: 50
      },
      email: {
        required: true,
        pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      },
      subject: {
        required: true,
        minLength: 5,
        maxLength: 100
      },
      message: {
        required: true,
        minLength: 10,
        maxLength: 1000
      }
    }
  );

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!validateAll()) {
      handleError(
        { code: 'VALIDATION_ERROR', message: 'Please fix the form errors' },
        'Form validation'
      );
      return;
    }

    try {
      await retry(async () => {
        await onSubmit(values);
        handleSuccess('Your message has been sent successfully!');
        reset();
      });
    } catch (error: any) {
      handleError(error, 'Contact form submission');
    }
  };

  const isSubmitting = isLoading || isRetrying;

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <ValidatedInput
        name="name"
        label="Full Name"
        value={values.name}
        error={errors.name}
        touched={touched.name}
        required
        placeholder="Enter your full name"
        disabled={isSubmitting}
        onChange={handleChange('name')}
        onBlur={handleBlur('name')}
      />

      <ValidatedInput
        name="email"
        label="Email Address"
        type="email"
        value={values.email}
        error={errors.email}
        touched={touched.email}
        required
        placeholder="Enter your email address"
        disabled={isSubmitting}
        onChange={handleChange('email')}
        onBlur={handleBlur('email')}
      />

      <ValidatedInput
        name="subject"
        label="Subject"
        value={values.subject}
        error={errors.subject}
        touched={touched.subject}
        required
        placeholder="Enter the subject of your message"
        disabled={isSubmitting}
        onChange={handleChange('subject')}
        onBlur={handleBlur('subject')}
      />

      <ValidatedTextarea
        name="message"
        label="Message"
        value={values.message}
        error={errors.message}
        touched={touched.message}
        required
        placeholder="Enter your message"
        rows={6}
        disabled={isSubmitting}
        onChange={handleChange('message')}
        onBlur={handleBlur('message')}
      />

      <Button
        type="submit"
        disabled={!isValid || isSubmitting}
        loading={isSubmitting}
        className="w-full"
      >
        {isRetrying ? 'Retrying...' : 'Send Message'}
      </Button>

      {isRetrying && (
        <p className="text-sm text-blue-600 text-center">
          Retrying message submission...
        </p>
      )}
    </form>
  );
};

export default EnhancedContactForm;