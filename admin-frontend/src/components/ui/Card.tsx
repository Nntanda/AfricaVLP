import React from 'react';

interface CardProps {
  children: React.ReactNode;
  className?: string;
  padding?: boolean;
}

const Card: React.FC<CardProps> = ({
  children,
  className = '',
  padding = true,
}) => {
  const classes = `bg-white shadow rounded-lg ${padding ? 'p-6' : ''} ${className}`;
  
  return (
    <div className={classes}>
      {children}
    </div>
  );
};

export default Card;