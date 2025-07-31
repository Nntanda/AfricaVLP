import React from 'react';

interface PaginationProps {
  currentPage: number;
  totalPages: number;
  onPageChange: (page: number) => void;
  showFirstLast?: boolean;
  maxVisiblePages?: number;
}

const Pagination: React.FC<PaginationProps> = ({
  currentPage,
  totalPages,
  onPageChange,
  showFirstLast = true,
  maxVisiblePages = 5,
}) => {
  if (totalPages <= 1) return null;

  const getVisiblePages = (): number[] => {
    const half = Math.floor(maxVisiblePages / 2);
    let start = Math.max(1, currentPage - half);
    let end = Math.min(totalPages, start + maxVisiblePages - 1);
    
    if (end - start + 1 < maxVisiblePages) {
      start = Math.max(1, end - maxVisiblePages + 1);
    }
    
    return Array.from({ length: end - start + 1 }, (_, i) => start + i);
  };

  const visiblePages = getVisiblePages();

  const buttonClass = (isActive: boolean, isDisabled: boolean = false) => [
    'px-3 py-2 text-sm font-medium border',
    isActive
      ? 'bg-blue-600 text-white border-blue-600'
      : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50',
    isDisabled && 'opacity-50 cursor-not-allowed hover:bg-white',
  ].filter(Boolean).join(' ');

  return (
    <nav className="flex items-center justify-center space-x-1">
      {showFirstLast && currentPage > 1 && (
        <button
          onClick={() => onPageChange(1)}
          className={buttonClass(false)}
        >
          First
        </button>
      )}
      
      <button
        onClick={() => onPageChange(currentPage - 1)}
        disabled={currentPage <= 1}
        className={buttonClass(false, currentPage <= 1)}
      >
        Previous
      </button>
      
      {visiblePages.map((page) => (
        <button
          key={page}
          onClick={() => onPageChange(page)}
          className={buttonClass(page === currentPage)}
        >
          {page}
        </button>
      ))}
      
      <button
        onClick={() => onPageChange(currentPage + 1)}
        disabled={currentPage >= totalPages}
        className={buttonClass(false, currentPage >= totalPages)}
      >
        Next
      </button>
      
      {showFirstLast && currentPage < totalPages && (
        <button
          onClick={() => onPageChange(totalPages)}
          className={buttonClass(false)}
        >
          Last
        </button>
      )}
    </nav>
  );
};

export default Pagination;