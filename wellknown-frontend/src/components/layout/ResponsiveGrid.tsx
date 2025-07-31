import React from 'react';

interface ResponsiveGridProps {
  children: React.ReactNode;
  columns?: {
    sm?: number;
    md?: number;
    lg?: number;
    xl?: number;
  };
  gap?: string;
  className?: string;
}

export const ResponsiveGrid: React.FC<ResponsiveGridProps> = ({
  children,
  columns = { sm: 1, md: 2, lg: 3, xl: 4 },
  gap = 'gap-4',
  className = '',
}) => {
  const getGridCols = () => {
    const { sm = 1, md = 2, lg = 3, xl = 4 } = columns;
    return `grid-cols-${sm} md:grid-cols-${md} lg:grid-cols-${lg} xl:grid-cols-${xl}`;
  };

  return (
    <div className={`grid ${getGridCols()} ${gap} ${className}`}>
      {children}
    </div>
  );
};

interface ResponsiveFlexProps {
  children: React.ReactNode;
  direction?: 'row' | 'col';
  wrap?: boolean;
  justify?: 'start' | 'center' | 'end' | 'between' | 'around' | 'evenly';
  align?: 'start' | 'center' | 'end' | 'stretch';
  gap?: string;
  className?: string;
}

export const ResponsiveFlex: React.FC<ResponsiveFlexProps> = ({
  children,
  direction = 'row',
  wrap = false,
  justify = 'start',
  align = 'start',
  gap = 'gap-4',
  className = '',
}) => {
  const flexClasses = [
    'flex',
    direction === 'col' ? 'flex-col' : 'flex-row',
    wrap ? 'flex-wrap' : '',
    `justify-${justify}`,
    `items-${align}`,
    gap,
    className,
  ].filter(Boolean).join(' ');

  return (
    <div className={flexClasses}>
      {children}
    </div>
  );
};

interface ResponsiveContainerProps {
  children: React.ReactNode;
  maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | 'full';
  padding?: string;
  className?: string;
}

export const ResponsiveContainer: React.FC<ResponsiveContainerProps> = ({
  children,
  maxWidth = 'xl',
  padding = 'px-4 sm:px-6 lg:px-8',
  className = '',
}) => {
  const maxWidthClass = maxWidth === 'full' ? 'max-w-full' : `max-w-${maxWidth}`;
  
  return (
    <div className={`mx-auto ${maxWidthClass} ${padding} ${className}`}>
      {children}
    </div>
  );
};