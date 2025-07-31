import React from 'react';
import { useFormValidation } from '../../hooks/useFormValidation';
import { useErrorHandler } from '../../hooks/useErrorHandler';
import { useRetry } from '../../hooks/useRetry';
import ValidatedInput from './ValidatedInput';
import Button from '../ui/Button';

interface LoginFormData {
  email: string;
  password: string;
}

interface EnhancedLoginFormProps {
  onSubmit: (data: LoginFormData) => Promise<void>;
  isLoading?: boolean;
}

const EnhancedLoginForm: React.FC<EnhancedLoginFormProps> = ({ onSubmit, isLoading = false }) => {
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
  } = useFormValidation<LoginFormData>(
    { email: '', password: '' },
    {
      email: {
        required: true,
        pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      },
      password: {
        required: true,
        minLength: 6
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
        handleSuccess('Login successful!');
        reset();
      });
    } catch (error: any) {
      handleError(error, 'Login');
    }
  };

  const isSubmitting = isLoading || isRetrying;

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <ValidatedInput
        name="email"
        label="Email Address"
        type="email"
        value={values.email}
        error={errors.email}
        touched={touched.email}
        required
        placeholder="Enter your email"
        disabled={isSubmitting}
        onChange={handleChange('email')}
        onBlur={handleBlur('email')}
      />

      <ValidatedInput
        name="password"
        label="Password"
        type="password"
        value={values.password}
        error={errors.password}
        touched={touched.password}
        required
        placeholder="Enter your password"
        disabled={isSubmitting}
        onChange={handleChange('password')}
        onBlur={handleBlur('password')}
      />

      <Button
        type="submit"
        disabled={!isValid || isSubmitting}
        loading={isSubmitting}
        className="w-full"
      >
        {isRetrying ? 'Retrying...' : 'Sign In'}
      </Button>

      {isRetrying && (
        <p className="text-sm text-blue-600 text-center">
          Retrying login attempt...
        </p>
      )}
    </form>
  );
};

export default EnhancedLoginForm;