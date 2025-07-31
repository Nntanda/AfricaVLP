import { renderHook, act } from '@testing-library/react';
import { useFormValidation, ValidationRules } from '../useFormValidation';

describe('useFormValidation', () => {
  const initialValues = {
    email: '',
    message: '',
    name: ''
  };

  const rules: ValidationRules = {
    email: {
      required: true,
      pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    },
    message: {
      required: true,
      minLength: 10,
      maxLength: 500
    },
    name: {
      required: true
    }
  };

  it('should initialize with default values', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    expect(result.current.values).toEqual(initialValues);
    expect(result.current.errors).toEqual({});
    expect(result.current.touched).toEqual({});
    expect(result.current.isValid).toBe(false);
  });

  it('should validate all fields', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      const isValid = result.current.validateAll();
      expect(isValid).toBe(false);
    });

    expect(result.current.errors.email).toBe('email is required');
    expect(result.current.errors.message).toBe('message is required');
    expect(result.current.errors.name).toBe('name is required');
  });

  it('should validate email format', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.setValue('email', 'invalid-email');
      result.current.setFieldTouched('email', true);
    });

    expect(result.current.errors.email).toBe('email format is invalid');
  });

  it('should validate message length', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.setValue('message', 'short');
      result.current.setFieldTouched('message', true);
    });

    expect(result.current.errors.message).toBe('message must be at least 10 characters');

    act(() => {
      result.current.setValue('message', 'a'.repeat(501));
    });

    expect(result.current.errors.message).toBe('message must be no more than 500 characters');
  });

  it('should be valid when all fields are correct', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.setValue('email', 'test@example.com');
      result.current.setValue('message', 'This is a valid message that is long enough');
      result.current.setValue('name', 'John Doe');
    });

    expect(result.current.isValid).toBe(true);
  });

  it('should handle custom validation', () => {
    const customRules: ValidationRules = {
      phone: {
        custom: (value) => {
          if (value && !/^\d{10}$/.test(value)) {
            return 'Phone number must be 10 digits';
          }
          return null;
        }
      }
    };

    const { result } = renderHook(() => 
      useFormValidation({ phone: '' }, customRules)
    );

    act(() => {
      result.current.setValue('phone', '123');
      result.current.setFieldTouched('phone', true);
    });

    expect(result.current.errors.phone).toBe('Phone number must be 10 digits');
  });
});