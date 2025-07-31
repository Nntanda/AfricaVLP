import React from 'react';

interface Option {
  value: string;
  label: string;
}

interface ValidatedSelectProps {
  name: string;
  label: string;
  value: string;
  options: Option[];
  error?: string;
  touched?: boolean;
  required?: boolean;
  placeholder?: string;
  disabled?: boolean;
  onChange: (event: React.ChangeEvent<HTMLSelectElement>) => void;
  onBlur: () => void;
  className?: string;
}

const ValidatedSelect: React.FC<ValidatedSelectProps> = ({
  name,
  label,
  value,
  options,
  error,
  touched,
  required,
  placeholder,
  disabled,
  onChange,
  onBlur,
  className = ''
}) => {
  const hasError = touched && error;
  
  const selectClasses = `
    w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500
    ${hasError 
      ? 'border-red-500 focus:border-red-500 focus:ring-red-500' 
      : 'border-gray-300 focus:border-blue-500'
    }
    ${disabled ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'}
    ${className}
  `.trim();

  return (
    <div className="mb-4">
      <label htmlFor={name} className="block text-sm font-medium text-gray-700 mb-1">
        {label}
        {required && <span className="text-red-500 ml-1">*</span>}
      </label>
      
      <select
        id={name}
        name={name}
        value={value}
        onChange={onChange}
        onBlur={onBlur}
        disabled={disabled}
        className={selectClasses}
        aria-invalid={hasError ? 'true' : 'false'}
        aria-describedby={hasError ? `${name}-error` : undefined}
      >
        {placeholder && (
          <option value="" disabled>
            {placeholder}
          </option>
        )}
        {options.map(option => (
          <option key={option.value} value={option.value}>
            {option.label}
          </option>
        ))}
      </select>
      
      {hasError && (
        <p id={`${name}-error`} className="mt-1 text-sm text-red-600" role="alert">
          {error}
        </p>
      )}
    </div>
  );
};

export default ValidatedSelect;