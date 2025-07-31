import { useState, useCallback, useEffect } from 'react';

export interface ValidationRule {
  required?: boolean;
  minLength?: number;
  maxLength?: number;
  pattern?: RegExp;
  custom?: (value: any) => string | null;
}

export interface ValidationRules {
  [key: string]: ValidationRule;
}

export interface ValidationErrors {
  [key: string]: string;
}

export const useFormValidation = <T extends Record<string, any>>(
  initialValues: T,
  rules: ValidationRules
) => {
  const [values, setValues] = useState<T>(initialValues);
  const [errors, setErrors] = useState<ValidationErrors>({});
  const [touched, setTouched] = useState<Record<string, boolean>>({});
  const [isValid, setIsValid] = useState(false);

  const validateField = useCallback((name: string, value: any): string => {
    const rule = rules[name];
    if (!rule) return '';

    if (rule.required && (!value || (typeof value === 'string' && value.trim() === ''))) {
      return `${name} is required`;
    }

    if (value && typeof value === 'string') {
      if (rule.minLength && value.length < rule.minLength) {
        return `${name} must be at least ${rule.minLength} characters`;
      }

      if (rule.maxLength && value.length > rule.maxLength) {
        return `${name} must be no more than ${rule.maxLength} characters`;
      }

      if (rule.pattern && !rule.pattern.test(value)) {
        return `${name} format is invalid`;
      }
    }

    if (rule.custom) {
      const customError = rule.custom(value);
      if (customError) return customError;
    }

    return '';
  }, [rules]);

  const validateAll = useCallback((): boolean => {
    const newErrors: ValidationErrors = {};
    let valid = true;

    Object.keys(rules).forEach(name => {
      const error = validateField(name, values[name]);
      if (error) {
        newErrors[name] = error;
        valid = false;
      }
    });

    setErrors(newErrors);
    setIsValid(valid);
    return valid;
  }, [values, rules, validateField]);

  const setValue = useCallback((name: string, value: any) => {
    setValues(prev => ({ ...prev, [name]: value }));
    
    // Real-time validation for touched fields
    if (touched[name]) {
      const error = validateField(name, value);
      setErrors(prev => ({ ...prev, [name]: error }));
    }
  }, [touched, validateField]);

  const setFieldTouched = useCallback((name: string, isTouched = true) => {
    setTouched(prev => ({ ...prev, [name]: isTouched }));
    
    if (isTouched) {
      const error = validateField(name, values[name]);
      setErrors(prev => ({ ...prev, [name]: error }));
    }
  }, [values, validateField]);

  const handleChange = useCallback((name: string) => (
    event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>
  ) => {
    setValue(name, event.target.value);
  }, [setValue]);

  const handleBlur = useCallback((name: string) => () => {
    setFieldTouched(name, true);
  }, [setFieldTouched]);

  const reset = useCallback(() => {
    setValues(initialValues);
    setErrors({});
    setTouched({});
    setIsValid(false);
  }, [initialValues]);

  const setFieldError = useCallback((name: string, error: string) => {
    setErrors(prev => ({ ...prev, [name]: error }));
  }, []);

  const clearFieldError = useCallback((name: string) => {
    setErrors(prev => {
      const newErrors = { ...prev };
      delete newErrors[name];
      return newErrors;
    });
  }, []);

  // Update isValid when errors change
  useEffect(() => {
    const hasErrors = Object.values(errors).some(error => error !== '');
    const hasRequiredFields = Object.keys(rules).some(name => {
      const rule = rules[name];
      return rule.required && (!values[name] || (typeof values[name] === 'string' && values[name].trim() === ''));
    });
    
    setIsValid(!hasErrors && !hasRequiredFields);
  }, [errors, values, rules]);

  return {
    values,
    errors,
    touched,
    isValid,
    setValue,
    setFieldTouched,
    handleChange,
    handleBlur,
    validateAll,
    reset,
    setFieldError,
    clearFieldError
  };
};