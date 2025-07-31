import { renderHook, act } from '@testing-library/react';
import { useFormValidation, ValidationRules } from '../useFormValidation';

describe('useFormValidation', () => {
  const initialValues = {
    email: '',
    password: '',
    name: ''
  };

  const rules: ValidationRules = {
    email: {
      required: true,
      pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    },
    password: {
      required: true,
      minLength: 8
    },
    name: {
      required: true,
      maxLength: 50
    }
  };

  it('should initialize with default values', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    expect(result.current.values).toEqual(initialValues);
    expect(result.current.errors).toEqual({});
    expect(result.current.touched).toEqual({});
    expect(result.current.isValid).toBe(false);
  });

  it('should validate required fields', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.validateAll();
    });

    expect(result.current.errors.email).toBe('email is required');
    expect(result.current.errors.password).toBe('password is required');
    expect(result.current.errors.name).toBe('name is required');
    expect(result.current.isValid).toBe(false);
  });

  it('should validate email pattern', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.setValue('email', 'invalid-email');
      result.current.setFieldTouched('email', true);
    });

    expect(result.current.errors.email).toBe('email format is invalid');
  });

  it('should validate minimum length', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.setValue('password', '123');
      result.current.setFieldTouched('password', true);
    });

    expect(result.current.errors.password).toBe('password must be at least 8 characters');
  });

  it('should validate maximum length', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.setValue('name', 'a'.repeat(51));
      result.current.setFieldTouched('name', true);
    });

    expect(result.current.errors.name).toBe('name must be no more than 50 characters');
  });

  it('should validate custom validation rules', () => {
    const customRules: ValidationRules = {
      username: {
        custom: (value) => {
          if (value && value.includes(' ')) {
            return 'Username cannot contain spaces';
          }
          return null;
        }
      }
    };

    const { result } = renderHook(() => 
      useFormValidation({ username: '' }, customRules)
    );

    act(() => {
      result.current.setValue('username', 'user name');
      result.current.setFieldTouched('username', true);
    });

    expect(result.current.errors.username).toBe('Username cannot contain spaces');
  });

  it('should handle form submission with handleChange', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    const mockEvent = {
      target: { value: 'test@example.com' }
    } as React.ChangeEvent<HTMLInputElement>;

    act(() => {
      result.current.handleChange('email')(mockEvent);
    });

    expect(result.current.values.email).toBe('test@example.com');
  });

  it('should handle blur events with handleBlur', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.setValue('email', 'invalid-email');
      result.current.handleBlur('email')();
    });

    expect(result.current.touched.email).toBe(true);
    expect(result.current.errors.email).toBe('email format is invalid');
  });

  it('should reset form to initial state', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.setValue('email', 'test@example.com');
      result.current.setFieldTouched('email', true);
      result.current.setFieldError('email', 'Some error');
    });

    act(() => {
      result.current.reset();
    });

    expect(result.current.values).toEqual(initialValues);
    expect(result.current.errors).toEqual({});
    expect(result.current.touched).toEqual({});
    expect(result.current.isValid).toBe(false);
  });

  it('should set and clear field errors manually', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.setFieldError('email', 'Custom error');
    });

    expect(result.current.errors.email).toBe('Custom error');

    act(() => {
      result.current.clearFieldError('email');
    });

    expect(result.current.errors.email).toBeUndefined();
  });

  it('should update isValid when all fields are valid', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.setValue('email', 'test@example.com');
      result.current.setValue('password', 'password123');
      result.current.setValue('name', 'John Doe');
    });

    expect(result.current.isValid).toBe(true);
  });

  it('should perform real-time validation for touched fields', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    // First touch the field
    act(() => {
      result.current.setFieldTouched('email', true);
    });

    // Then set an invalid value
    act(() => {
      result.current.setValue('email', 'invalid-email');
    });

    expect(result.current.errors.email).toBe('email format is invalid');

    // Fix the value
    act(() => {
      result.current.setValue('email', 'test@example.com');
    });

    expect(result.current.errors.email).toBe('');
  });

  it('should not validate untouched fields in real-time', () => {
    const { result } = renderHook(() => useFormValidation(initialValues, rules));

    act(() => {
      result.current.setValue('email', 'invalid-email');
    });

    // Should not have error since field is not touched
    expect(result.current.errors.email).toBeUndefined();
  });
});