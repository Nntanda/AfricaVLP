import React from 'react';
import { render, screen } from '@testing-library/react';
import { ResponsiveGrid, ResponsiveFlex, ResponsiveContainer } from '../ResponsiveGrid';

describe('ResponsiveGrid', () => {
  it('renders children correctly', () => {
    render(
      <ResponsiveGrid>
        <div>Item 1</div>
        <div>Item 2</div>
      </ResponsiveGrid>
    );

    expect(screen.getByText('Item 1')).toBeInTheDocument();
    expect(screen.getByText('Item 2')).toBeInTheDocument();
  });

  it('applies default grid classes', () => {
    const { container } = render(
      <ResponsiveGrid>
        <div>Item</div>
      </ResponsiveGrid>
    );

    const gridElement = container.firstChild as HTMLElement;
    expect(gridElement).toHaveClass('grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3', 'xl:grid-cols-4', 'gap-4');
  });

  it('applies custom column configuration', () => {
    const { container } = render(
      <ResponsiveGrid columns={{ sm: 2, md: 3, lg: 4, xl: 5 }}>
        <div>Item</div>
      </ResponsiveGrid>
    );

    const gridElement = container.firstChild as HTMLElement;
    expect(gridElement).toHaveClass('grid-cols-2', 'md:grid-cols-3', 'lg:grid-cols-4', 'xl:grid-cols-5');
  });

  it('applies custom gap and className', () => {
    const { container } = render(
      <ResponsiveGrid gap="gap-8" className="custom-class">
        <div>Item</div>
      </ResponsiveGrid>
    );

    const gridElement = container.firstChild as HTMLElement;
    expect(gridElement).toHaveClass('gap-8', 'custom-class');
  });
});

describe('ResponsiveFlex', () => {
  it('renders children correctly', () => {
    render(
      <ResponsiveFlex>
        <div>Item 1</div>
        <div>Item 2</div>
      </ResponsiveFlex>
    );

    expect(screen.getByText('Item 1')).toBeInTheDocument();
    expect(screen.getByText('Item 2')).toBeInTheDocument();
  });

  it('applies default flex classes', () => {
    const { container } = render(
      <ResponsiveFlex>
        <div>Item</div>
      </ResponsiveFlex>
    );

    const flexElement = container.firstChild as HTMLElement;
    expect(flexElement).toHaveClass('flex', 'flex-row', 'justify-start', 'items-start', 'gap-4');
  });

  it('applies column direction', () => {
    const { container } = render(
      <ResponsiveFlex direction="col">
        <div>Item</div>
      </ResponsiveFlex>
    );

    const flexElement = container.firstChild as HTMLElement;
    expect(flexElement).toHaveClass('flex-col');
  });

  it('applies wrap and custom justify/align', () => {
    const { container } = render(
      <ResponsiveFlex wrap justify="center" align="center">
        <div>Item</div>
      </ResponsiveFlex>
    );

    const flexElement = container.firstChild as HTMLElement;
    expect(flexElement).toHaveClass('flex-wrap', 'justify-center', 'items-center');
  });
});

describe('ResponsiveContainer', () => {
  it('renders children correctly', () => {
    render(
      <ResponsiveContainer>
        <div>Content</div>
      </ResponsiveContainer>
    );

    expect(screen.getByText('Content')).toBeInTheDocument();
  });

  it('applies default container classes', () => {
    const { container } = render(
      <ResponsiveContainer>
        <div>Content</div>
      </ResponsiveContainer>
    );

    const containerElement = container.firstChild as HTMLElement;
    expect(containerElement).toHaveClass('mx-auto', 'max-w-xl', 'px-4', 'sm:px-6', 'lg:px-8');
  });

  it('applies custom maxWidth', () => {
    const { container } = render(
      <ResponsiveContainer maxWidth="2xl">
        <div>Content</div>
      </ResponsiveContainer>
    );

    const containerElement = container.firstChild as HTMLElement;
    expect(containerElement).toHaveClass('max-w-2xl');
  });

  it('applies full width', () => {
    const { container } = render(
      <ResponsiveContainer maxWidth="full">
        <div>Content</div>
      </ResponsiveContainer>
    );

    const containerElement = container.firstChild as HTMLElement;
    expect(containerElement).toHaveClass('max-w-full');
  });
});