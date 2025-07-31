import React, { useState, useRef, useEffect } from 'react';

interface ResponsiveImageProps {
  src: string;
  alt: string;
  className?: string;
  sizes?: string;
  srcSet?: string;
  placeholder?: string;
  aspectRatio?: 'square' | '16/9' | '4/3' | '3/2' | 'auto';
  objectFit?: 'cover' | 'contain' | 'fill' | 'none' | 'scale-down';
  loading?: 'lazy' | 'eager';
  onLoad?: () => void;
  onError?: () => void;
}

export const ResponsiveImage: React.FC<ResponsiveImageProps> = ({
  src,
  alt,
  className = '',
  sizes = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw',
  srcSet,
  placeholder = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkxvYWRpbmcuLi48L3RleHQ+PC9zdmc+',
  aspectRatio = 'auto',
  objectFit = 'cover',
  loading = 'lazy',
  onLoad,
  onError,
}) => {
  const [isLoaded, setIsLoaded] = useState(false);
  const [hasError, setHasError] = useState(false);
  const [isInView, setIsInView] = useState(loading === 'eager');
  const imgRef = useRef<HTMLImageElement>(null);

  useEffect(() => {
    if (loading === 'lazy' && imgRef.current) {
      const observer = new IntersectionObserver(
        ([entry]) => {
          if (entry.isIntersecting) {
            setIsInView(true);
            observer.disconnect();
          }
        },
        { threshold: 0.1 }
      );

      observer.observe(imgRef.current);

      return () => observer.disconnect();
    }
  }, [loading]);

  const handleLoad = () => {
    setIsLoaded(true);
    onLoad?.();
  };

  const handleError = () => {
    setHasError(true);
    onError?.();
  };

  const getAspectRatioClass = () => {
    switch (aspectRatio) {
      case 'square':
        return 'aspect-square';
      case '16/9':
        return 'aspect-video';
      case '4/3':
        return 'aspect-[4/3]';
      case '3/2':
        return 'aspect-[3/2]';
      default:
        return '';
    }
  };

  const getObjectFitClass = () => {
    switch (objectFit) {
      case 'cover':
        return 'object-cover';
      case 'contain':
        return 'object-contain';
      case 'fill':
        return 'object-fill';
      case 'none':
        return 'object-none';
      case 'scale-down':
        return 'object-scale-down';
      default:
        return 'object-cover';
    }
  };

  const containerClasses = [
    'relative overflow-hidden',
    getAspectRatioClass(),
    className,
  ].filter(Boolean).join(' ');

  const imageClasses = [
    'w-full h-full transition-opacity duration-300',
    getObjectFitClass(),
    isLoaded ? 'opacity-100' : 'opacity-0',
  ].join(' ');

  return (
    <div className={containerClasses} ref={imgRef}>
      {/* Placeholder */}
      {!isLoaded && !hasError && (
        <img
          src={placeholder}
          alt=""
          className={`absolute inset-0 w-full h-full ${getObjectFitClass()} opacity-50`}
          aria-hidden="true"
        />
      )}

      {/* Main Image */}
      {isInView && !hasError && (
        <img
          src={src}
          srcSet={srcSet}
          sizes={sizes}
          alt={alt}
          className={imageClasses}
          onLoad={handleLoad}
          onError={handleError}
          loading={loading}
        />
      )}

      {/* Error State */}
      {hasError && (
        <div className="absolute inset-0 flex items-center justify-center bg-gray-100">
          <div className="text-center text-gray-500">
            <svg className="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p className="text-sm">Failed to load image</p>
          </div>
        </div>
      )}

      {/* Loading Indicator */}
      {!isLoaded && !hasError && isInView && (
        <div className="absolute inset-0 flex items-center justify-center">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        </div>
      )}
    </div>
  );
};

interface ImageGalleryProps {
  images: Array<{
    src: string;
    alt: string;
    caption?: string;
  }>;
  columns?: {
    sm?: number;
    md?: number;
    lg?: number;
  };
  aspectRatio?: 'square' | '16/9' | '4/3' | '3/2' | 'auto';
  className?: string;
}

export const ImageGallery: React.FC<ImageGalleryProps> = ({
  images,
  columns = { sm: 1, md: 2, lg: 3 },
  aspectRatio = 'square',
  className = '',
}) => {
  const { sm = 1, md = 2, lg = 3 } = columns;
  const gridClasses = `grid grid-cols-${sm} md:grid-cols-${md} lg:grid-cols-${lg} gap-4 ${className}`;

  return (
    <div className={gridClasses}>
      {images.map((image, index) => (
        <div key={index} className="space-y-2">
          <ResponsiveImage
            src={image.src}
            alt={image.alt}
            aspectRatio={aspectRatio}
            className="rounded-lg"
          />
          {image.caption && (
            <p className="text-sm text-gray-600 text-center">{image.caption}</p>
          )}
        </div>
      ))}
    </div>
  );
};