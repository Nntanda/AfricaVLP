# AU-VLP Well-known Frontend

This is the client-facing React frontend application for the African Union Youth Leadership Program (AU-VLP) well-known system.

## Features

- Modern React 18 with TypeScript
- React Router for navigation with protected routes
- Tailwind CSS for styling
- Axios for API communication with automatic token refresh
- React Query for state management and caching
- Internationalization support with react-i18next
- Comprehensive error handling and user feedback
- Responsive design for mobile and desktop
- Component-based architecture with reusable UI components

## Project Structure

```
src/
├── components/          # Reusable components
│   ├── auth/           # Authentication components
│   ├── common/         # Common UI components
│   ├── forms/          # Form components
│   ├── layout/         # Layout components
│   └── ui/             # Basic UI components
├── config/             # Configuration files
├── context/            # React contexts
├── hooks/              # Custom hooks
├── pages/              # Page components
├── routes/             # Route configuration
├── services/           # API services
├── types/              # TypeScript type definitions
└── utils/              # Utility functions
```

## Getting Started

### Prerequisites

- Node.js 16+ 
- npm or yarn

### Installation

1. Install dependencies:
```bash
npm install
```

2. Copy environment variables:
```bash
cp .env.example .env.development
```

3. Update environment variables in `.env.development` as needed.

### Development

Start the development server:
```bash
npm run dev
```

The application will be available at `http://localhost:3000`.

### Building

Build for production:
```bash
npm run build
```

Serve the built application:
```bash
npm run serve
```

## Environment Variables

- `VITE_API_BASE_URL`: Backend API base URL
- `VITE_API_TIMEOUT`: API request timeout in milliseconds
- `VITE_APP_NAME`: Application name
- `VITE_ENABLE_DEBUG`: Enable debug mode
- `VITE_ENABLE_ANALYTICS`: Enable analytics
- `VITE_DEFAULT_LANGUAGE`: Default language code
- `VITE_SUPPORTED_LANGUAGES`: Comma-separated list of supported languages

## Key Components

### Authentication
- `AuthContext`: Manages authentication state
- `ProtectedRoute`: Protects routes requiring authentication
- `LoginForm`: User login form

### Layout
- `Layout`: Main application layout with header, navigation, and footer
- `Header`: Application header with branding
- `Navigation`: Main navigation menu
- `Footer`: Application footer

### UI Components
- `Button`: Reusable button component with variants
- `Input`: Form input component with validation
- `Card`: Content card component
- `Modal`: Modal dialog component
- `Badge`: Status badge component
- `Pagination`: Pagination component

### Error Handling
- `ErrorBoundary`: Catches and displays React errors
- `Toast`: Toast notification system
- `useErrorHandler`: Hook for handling API errors

## API Integration

The application uses Axios for API communication with:
- Automatic JWT token management
- Request/response interceptors
- Error handling and retry logic
- Token refresh functionality

## Internationalization

The application supports multiple languages using react-i18next:
- English (en)
- French (fr) 
- Arabic (ar)

## Contributing

1. Follow the existing code structure and patterns
2. Use TypeScript for type safety
3. Write tests for new components
4. Follow the established naming conventions
5. Update documentation as needed

## License

This project is part of the AU-VLP system and is proprietary software.