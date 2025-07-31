import React from 'react';

interface ResponsiveFormProps {
  children: React.ReactNode;
  onSubmit: (e: React.FormEvent) => void;
  className?: string;
  layout?: 'vertical' | 'horizontal';
}

export const ResponsiveForm: React.FC<ResponsiveFormProps> = ({
  children,
  onSubmit,
  className = '',
  layout = 'vertical',
}) => {
  const formClasses = [
    'space-y-4 md:space-y-6',
    layout === 'horizontal' ? 'md:space-y-0 md:space-x-4 md:flex md:flex-wrap' : '',
    className,
  ].filter(Boolean).join(' ');

  return (
    <form onSubmit={onSubmit} className={formClasses}>
      {children}
    </form>
  );
};

interface ResponsiveFieldGroupProps {
  children: React.ReactNode;
  className?: string;
  columns?: {
    sm?: number;
    md?: number;
    lg?: number;
  };
}

export const ResponsiveFieldGroup: React.FC<ResponsiveFieldGroupProps> = ({
  children,
  className = '',
  columns = { sm: 1, md: 2 },
}) => {
  const { sm = 1, md = 2, lg = md } = columns;
  const gridClasses = `grid grid-cols-${sm} md:grid-cols-${md} lg:grid-cols-${lg} gap-4 md:gap-6 ${className}`;

  return (
    <div className={gridClasses}>
      {children}
    </div>
  );
};

interface MobileOptimizedInputProps extends React.InputHTMLAttributes<HTMLInputElement> {
  label: string;
  error?: string;
  hint?: string;
  required?: boolean;
  icon?: React.ReactNode;
  fullWidth?: boolean;
}

export const MobileOptimizedInput: React.FC<MobileOptimizedInputProps> = ({
  label,
  error,
  hint,
  required,
  icon,
  fullWidth = true,
  className = '',
  ...props
}) => {
  const inputId = props.id || `input-${Math.random().toString(36).substr(2, 9)}`;

  const inputClasses = [
    'block w-full px-3 py-3 md:py-2 text-base md:text-sm border border-gray-300 rounded-md shadow-sm',
    'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
    'placeholder-gray-400',
    'touch-manipulation', // Improves touch responsiveness
    error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '',
    icon ? 'pl-10' : '',
    className,
  ].filter(Boolean).join(' ');

  const containerClasses = fullWidth ? 'w-full' : '';

  return (
    <div className={containerClasses}>
      <label htmlFor={inputId} className="block text-sm font-medium text-gray-700 mb-1">
        {label}
        {required && <span className="text-red-500 ml-1">*</span>}
      </label>
      
      <div className="relative">
        {icon && (
          <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span className="text-gray-400">{icon}</span>
          </div>
        )}
        
        <input
          {...props}
          id={inputId}
          className={inputClasses}
          aria-invalid={error ? 'true' : 'false'}
          aria-describedby={error ? `${inputId}-error` : hint ? `${inputId}-hint` : undefined}
        />
      </div>

      {hint && !error && (
        <p id={`${inputId}-hint`} className="mt-1 text-sm text-gray-500">
          {hint}
        </p>
      )}

      {error && (
        <p id={`${inputId}-error`} className="mt-1 text-sm text-red-600" role="alert">
          {error}
        </p>
      )}
    </div>
  );
};

interface MobileOptimizedTextareaProps extends React.TextareaHTMLAttributes<HTMLTextAreaElement> {
  label: string;
  error?: string;
  hint?: string;
  required?: boolean;
  fullWidth?: boolean;
  autoResize?: boolean;
}

export const MobileOptimizedTextarea: React.FC<MobileOptimizedTextareaProps> = ({
  label,
  error,
  hint,
  required,
  fullWidth = true,
  autoResize = false,
  className = '',
  ...props
}) => {
  const textareaId = props.id || `textarea-${Math.random().toString(36).substr(2, 9)}`;

  const textareaClasses = [
    'block w-full px-3 py-3 md:py-2 text-base md:text-sm border border-gray-300 rounded-md shadow-sm',
    'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
    'placeholder-gray-400',
    'touch-manipulation',
    'resize-vertical', // Allow vertical resize only
    error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '',
    autoResize ? 'resize-none' : '',
    className,
  ].filter(Boolean).join(' ');

  const containerClasses = fullWidth ? 'w-full' : '';

  const handleInput = (e: React.FormEvent<HTMLTextAreaElement>) => {
    if (autoResize) {
      const target = e.target as HTMLTextAreaElement;
      target.style.height = 'auto';
      target.style.height = `${target.scrollHeight}px`;
    }
  };

  return (
    <div className={containerClasses}>
      <label htmlFor={textareaId} className="block text-sm font-medium text-gray-700 mb-1">
        {label}
        {required && <span className="text-red-500 ml-1">*</span>}
      </label>
      
      <textarea
        {...props}
        id={textareaId}
        className={textareaClasses}
        onInput={handleInput}
        aria-invalid={error ? 'true' : 'false'}
        aria-describedby={error ? `${textareaId}-error` : hint ? `${textareaId}-hint` : undefined}
      />

      {hint && !error && (
        <p id={`${textareaId}-hint`} className="mt-1 text-sm text-gray-500">
          {hint}
        </p>
      )}

      {error && (
        <p id={`${textareaId}-error`} className="mt-1 text-sm text-red-600" role="alert">
          {error}
        </p>
      )}
    </div>
  );
};

interface MobileOptimizedSelectProps extends React.SelectHTMLAttributes<HTMLSelectElement> {
  label: string;
  error?: string;
  hint?: string;
  required?: boolean;
  fullWidth?: boolean;
  options: Array<{ value: string; label: string; disabled?: boolean }>;
}

export const MobileOptimizedSelect: React.FC<MobileOptimizedSelectProps> = ({
  label,
  error,
  hint,
  required,
  fullWidth = true,
  options,
  className = '',
  ...props
}) => {
  const selectId = props.id || `select-${Math.random().toString(36).substr(2, 9)}`;

  const selectClasses = [
    'block w-full px-3 py-3 md:py-2 text-base md:text-sm border border-gray-300 rounded-md shadow-sm',
    'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
    'bg-white',
    'touch-manipulation',
    error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '',
    className,
  ].filter(Boolean).join(' ');

  const containerClasses = fullWidth ? 'w-full' : '';

  return (
    <div className={containerClasses}>
      <label htmlFor={selectId} className="block text-sm font-medium text-gray-700 mb-1">
        {label}
        {required && <span className="text-red-500 ml-1">*</span>}
      </label>
      
      <select
        {...props}
        id={selectId}
        className={selectClasses}
        aria-invalid={error ? 'true' : 'false'}
        aria-describedby={error ? `${selectId}-error` : hint ? `${selectId}-hint` : undefined}
      >
        {options.map((option) => (
          <option key={option.value} value={option.value} disabled={option.disabled}>
            {option.label}
          </option>
        ))}
      </select>

      {hint && !error && (
        <p id={`${selectId}-hint`} className="mt-1 text-sm text-gray-500">
          {hint}
        </p>
      )}

      {error && (
        <p id={`${selectId}-error`} className="mt-1 text-sm text-red-600" role="alert">
          {error}
        </p>
      )}
    </div>
  );
};