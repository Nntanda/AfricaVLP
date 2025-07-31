import { useEffect, useRef, useState } from 'react';

interface SwipeGestureOptions {
  onSwipeLeft?: () => void;
  onSwipeRight?: () => void;
  onSwipeUp?: () => void;
  onSwipeDown?: () => void;
  threshold?: number;
  preventDefaultTouchmove?: boolean;
}

interface TouchPosition {
  x: number;
  y: number;
}

export const useSwipeGestures = <T extends HTMLElement>(
  options: SwipeGestureOptions = {}
) => {
  const {
    onSwipeLeft,
    onSwipeRight,
    onSwipeUp,
    onSwipeDown,
    threshold = 50,
    preventDefaultTouchmove = false,
  } = options;

  const elementRef = useRef<T>(null);
  const [touchStart, setTouchStart] = useState<TouchPosition | null>(null);
  const [touchEnd, setTouchEnd] = useState<TouchPosition | null>(null);

  const minSwipeDistance = threshold;

  const onTouchStart = (e: TouchEvent) => {
    setTouchEnd(null);
    setTouchStart({
      x: e.targetTouches[0].clientX,
      y: e.targetTouches[0].clientY,
    });
  };

  const onTouchMove = (e: TouchEvent) => {
    if (preventDefaultTouchmove) {
      e.preventDefault();
    }
    setTouchEnd({
      x: e.targetTouches[0].clientX,
      y: e.targetTouches[0].clientY,
    });
  };

  const onTouchEnd = () => {
    if (!touchStart || !touchEnd) return;

    const distanceX = touchStart.x - touchEnd.x;
    const distanceY = touchStart.y - touchEnd.y;
    const isLeftSwipe = distanceX > minSwipeDistance;
    const isRightSwipe = distanceX < -minSwipeDistance;
    const isUpSwipe = distanceY > minSwipeDistance;
    const isDownSwipe = distanceY < -minSwipeDistance;

    // Determine if horizontal or vertical swipe is more significant
    const isHorizontalSwipe = Math.abs(distanceX) > Math.abs(distanceY);

    if (isHorizontalSwipe) {
      if (isLeftSwipe && onSwipeLeft) {
        onSwipeLeft();
      } else if (isRightSwipe && onSwipeRight) {
        onSwipeRight();
      }
    } else {
      if (isUpSwipe && onSwipeUp) {
        onSwipeUp();
      } else if (isDownSwipe && onSwipeDown) {
        onSwipeDown();
      }
    }
  };

  useEffect(() => {
    const element = elementRef.current;
    if (!element) return;

    element.addEventListener('touchstart', onTouchStart, { passive: true });
    element.addEventListener('touchmove', onTouchMove, { passive: !preventDefaultTouchmove });
    element.addEventListener('touchend', onTouchEnd, { passive: true });

    return () => {
      element.removeEventListener('touchstart', onTouchStart);
      element.removeEventListener('touchmove', onTouchMove);
      element.removeEventListener('touchend', onTouchEnd);
    };
  }, [touchStart, touchEnd, minSwipeDistance, preventDefaultTouchmove]);

  return elementRef;
};

// Hook for carousel/slider swipe functionality
export const useCarouselSwipe = (
  totalItems: number,
  currentIndex: number,
  onIndexChange: (index: number) => void
) => {
  const swipeRef = useSwipeGestures<HTMLDivElement>({
    onSwipeLeft: () => {
      const nextIndex = currentIndex < totalItems - 1 ? currentIndex + 1 : 0;
      onIndexChange(nextIndex);
    },
    onSwipeRight: () => {
      const prevIndex = currentIndex > 0 ? currentIndex - 1 : totalItems - 1;
      onIndexChange(prevIndex);
    },
    threshold: 50,
  });

  return swipeRef;
};

// Hook for tab swipe functionality
export const useTabSwipe = (
  tabs: string[],
  activeTab: string,
  onTabChange: (tab: string) => void
) => {
  const currentIndex = tabs.indexOf(activeTab);

  const swipeRef = useSwipeGestures<HTMLDivElement>({
    onSwipeLeft: () => {
      const nextIndex = currentIndex < tabs.length - 1 ? currentIndex + 1 : 0;
      onTabChange(tabs[nextIndex]);
    },
    onSwipeRight: () => {
      const prevIndex = currentIndex > 0 ? currentIndex - 1 : tabs.length - 1;
      onTabChange(tabs[prevIndex]);
    },
    threshold: 75,
  });

  return swipeRef;
};