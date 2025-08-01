# Design Document

## Overview

This design addresses the systematic resolution of 142 TypeScript compilation errors in admin-frontend. The approach focuses on fixing import/export issues, resolving type mismatches, adding missing dependencies, and ensuring proper type safety throughout the application.

## Architecture

### Error Categories
1. **Import/Export Errors**: Missing or incorrect module exports (apiClient, toast, useAuth, searchService)
2. **Missing Dependencies**: External libraries without proper type declarations (@mui/material, date-fns, vitest)
3. **Type Interface Mismatches**: Properties that don't match defined interfaces (SearchContext, Table columns)
4. **Implicit Any Types**: Parameters and variables without explicit type annotations

### Fix Strategy
- **Phase 1**: Fix critical import/export issues that block basic functionality
- **Phase 2**: Resolve type interface mismatches and property access errors
- **Phase 3**: Add explicit types to eliminate implicit 'any' errors
- **Phase 4**: Handle missing dependencies and external library types

## Components and Interfaces

### Import/Export Fixes

#### API Client Export
```typescript
// Current issue: apiClient not exported properly
// Fix: Ensure proper default export in client.ts
export default apiClient;
// And update imports to use default import
import apiClient from '../services/api/client';
```

#### Toast Hook Export
```typescript
// Current issue: toast not exported from useToast
// Fix: Export toast function properly
export const toast = {
  success: (message: string) => addToast({ type: 'success', message }),
  error: (message: string) => addToast({ type: 'error', message }),
  // ... other methods
};
```

#### Auth Hook Export
```typescript
// Current issue: useAuth not exported properly
// Fix: Ensure proper export in useAuth.ts
export const useAuth = () => {
  // ... implementation
};
```

### Type Interface Fixes

#### SearchContext Interface
```typescript
interface SearchContextType {
  searchState: SearchState;
  setQuery: (query: string) => void;
  setFilters: (filters: SearchFilters) => void;
  clearSearch: () => void;
  performSearch: (params: SearchParams) => Promise<void>;
  getSuggestions: (query: string, type: number) => Promise<void>;
  addToHistory: (query: string) => void;
  hideSuggestions: () => void;
  suggestions: SearchSuggestion;
}
```

#### Table Column Interface
```typescript
interface DataTableColumn<T> {
  key: string;
  title: string; // Add missing title property
  label: string;
  render: (item: T) => JSX.Element;
}
```

### Type Safety Improvements

#### Event Handler Types
```typescript
// Fix implicit any in event handlers
const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
  // ... implementation
};

const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
  // ... implementation
};
```

#### Array Method Types
```typescript
// Fix implicit any in array callbacks
categories.map((category: Category) => (
  <option key={category.id} value={category.id}>
    {category.name}
  </option>
));
```

## Data Models

### Search Types
```typescript
interface SearchFilters {
  countryId?: number;
  cityId?: number;
  category?: string;
  tags?: string[];
  dateFrom?: string;
  dateTo?: string;
  status?: string;
}

interface SearchParams {
  query?: string;
  filters?: SearchFilters;
  page?: number;
  limit?: number;
}
```

### API Response Types
```typescript
interface ApiResponse<T> {
  data: T;
  status: number;
  statusText: string;
  headers: any;
  config: any;
}
```

## Error Handling

### Missing Dependencies Strategy
1. **Mock Missing Dependencies**: For development, create mock implementations
2. **Type-Only Imports**: Use type-only imports where possible to avoid runtime dependencies
3. **Conditional Imports**: Use dynamic imports for optional dependencies

### Type Safety Strategy
1. **Strict Type Checking**: Enable strict mode in TypeScript configuration
2. **Explicit Type Annotations**: Add explicit types for all function parameters
3. **Interface Validation**: Ensure all object properties match defined interfaces

## Testing Strategy

### Validation Approach
1. **Incremental Fixing**: Fix errors in batches by category
2. **Compilation Validation**: Run `npx tsc --noEmit` after each batch
3. **Build Verification**: Ensure `npm run build` succeeds after major fixes
4. **Functionality Testing**: Verify that fixes don't break existing functionality

### Success Criteria
- Zero TypeScript compilation errors in admin-frontend
- Successful build completion
- All imports resolve correctly
- All type interfaces are properly defined and used