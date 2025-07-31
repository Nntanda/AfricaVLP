import { renderHook, waitFor, act } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactNode } from 'react';
import { 
  useProfileOptimisticUpdate,
  useContactFormOptimistic,
  useNewsletterOptimistic,
  useOrganizationMembershipRequest,
  useEventRegistrationOptimistic,
  useResourceBookmarkToggle
} from '../useOptimisticUpdates';
import { apiClient } from '../../services/api/client';

// Mock the API client
jest.mock('../../services/api/client');
const mockedApiClient = apiClient as jest.Mocked<typeof apiClient>;

// Mock toast
jest.mock('../useToast', () => ({
  toast: {
    success: jest.fn(),
    error: jest.fn(),
    info: jest.fn(),
  },
}));

// Test wrapper with QueryClient
const createWrapper = () => {
  const queryClient = new QueryClient({
    defaultOptions: {
      queries: {
        retry: false,
        cacheTime: 0,
      },
      mutations: {
        retry: false,
      },
    },
  });

  return ({ children }: { children: ReactNode }) => (
    <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
  );
};

describe('useOptimisticUpdates hooks - Well-known Frontend', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  describe('useProfileOptimisticUpdate', () => {
    it('should optimistically update profile', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const existingProfile = {
        id: 1,
        first_name: 'John',
        last_name: 'Doe',
        email: 'john@example.com',
      };
      queryClient.setQueryData(['profile', 'current'], existingProfile);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const updateData = { first_name: 'Jane', bio: 'Updated bio' };
      const serverResponse = { ...existingProfile, ...updateData, updated_at: '2024-01-01T12:00:00Z' };

      mockedApiClient.put.mockResolvedValueOnce({ data: serverResponse });

      const { result } = renderHook(() => useProfileOptimisticUpdate(), { wrapper });

      act(() => {
        result.current.mutate(updateData);
      });

      // Check optimistic update
      const cachedData = queryClient.getQueryData(['profile', 'current']);
      expect(cachedData).toMatchObject({
        id: 1,
        first_name: 'Jane',
        bio: 'Updated bio',
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.put).toHaveBeenCalledWith('/api/v1/profile/', updateData);
    });

    it('should update auth profile cache as well', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const existingProfile = { id: 1, first_name: 'John', email: 'john@example.com' };
      const existingAuthProfile = { id: 1, username: 'john', email: 'john@example.com' };
      
      queryClient.setQueryData(['profile', 'current'], existingProfile);
      queryClient.setQueryData(['auth', 'profile'], existingAuthProfile);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const updateData = { first_name: 'Jane' };
      mockedApiClient.put.mockResolvedValueOnce({ data: { ...existingProfile, ...updateData } });

      const { result } = renderHook(() => useProfileOptimisticUpdate(), { wrapper });

      act(() => {
        result.current.mutate(updateData);
      });

      // Check both caches were updated
      const profileData = queryClient.getQueryData(['profile', 'current']);
      const authData = queryClient.getQueryData(['auth', 'profile']);
      
      expect(profileData).toMatchObject({ first_name: 'Jane' });
      expect(authData).toMatchObject({ first_name: 'Jane' });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });
    });

    it('should rollback on error', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const originalProfile = { id: 1, first_name: 'John', email: 'john@example.com' };
      queryClient.setQueryData(['profile', 'current'], originalProfile);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const updateData = { first_name: 'Jane' };
      const error = new Error('Update failed');

      mockedApiClient.put.mockRejectedValueOnce(error);

      const { result } = renderHook(() => useProfileOptimisticUpdate(), { wrapper });

      act(() => {
        result.current.mutate(updateData);
      });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      // Check rollback
      const cachedData = queryClient.getQueryData(['profile', 'current']);
      expect(cachedData).toEqual(originalProfile);
    });
  });

  describe('useContactFormOptimistic', () => {
    it('should show immediate feedback', async () => {
      const { toast } = require('../useToast');
      
      const contactData = {
        name: 'John Doe',
        email: 'john@example.com',
        message: 'Test message',
      };

      mockedApiClient.post.mockResolvedValueOnce({ data: { success: true } });

      const { result } = renderHook(() => useContactFormOptimistic(), {
        wrapper: createWrapper(),
      });

      act(() => {
        result.current.mutate(contactData);
      });

      // Should show immediate feedback
      expect(toast.info).toHaveBeenCalledWith('Sending message...');

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(toast.success).toHaveBeenCalledWith('Message sent successfully! We will get back to you soon.');
      expect(mockedApiClient.post).toHaveBeenCalledWith('/api/v1/contact/', contactData);
    });

    it('should handle contact form errors', async () => {
      const { toast } = require('../useToast');
      
      const error = new Error('Send failed');
      mockedApiClient.post.mockRejectedValueOnce(error);

      const { result } = renderHook(() => useContactFormOptimistic(), {
        wrapper: createWrapper(),
      });

      act(() => {
        result.current.mutate({ name: 'Test' });
      });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      expect(toast.error).toHaveBeenCalledWith('Failed to send message. Please try again.');
    });
  });

  describe('useNewsletterOptimistic', () => {
    it('should handle newsletter subscription', async () => {
      const { toast } = require('../useToast');
      
      const subscriptionData = { email: 'test@example.com' };
      mockedApiClient.post.mockResolvedValueOnce({ data: { success: true } });

      const { result } = renderHook(() => useNewsletterOptimistic(), {
        wrapper: createWrapper(),
      });

      act(() => {
        result.current.mutate(subscriptionData);
      });

      expect(toast.info).toHaveBeenCalledWith('Subscribing to newsletter...');

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(toast.success).toHaveBeenCalledWith('Successfully subscribed to newsletter!');
      expect(mockedApiClient.post).toHaveBeenCalledWith('/api/v1/newsletter/subscribe/', subscriptionData);
    });

    it('should handle subscription errors with custom message', async () => {
      const { toast } = require('../useToast');
      
      const error = {
        response: {
          data: { message: 'Email already subscribed' }
        }
      };
      mockedApiClient.post.mockRejectedValueOnce(error);

      const { result } = renderHook(() => useNewsletterOptimistic(), {
        wrapper: createWrapper(),
      });

      act(() => {
        result.current.mutate({ email: 'test@example.com' });
      });

      await waitFor(() => {
        expect(result.current.isError).toBe(true);
      });

      expect(toast.error).toHaveBeenCalledWith('Email already subscribed');
    });
  });

  describe('useOrganizationMembershipRequest', () => {
    it('should optimistically add membership request', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const existingOrganizations = [
        { id: 1, name: 'Org 1', status: 'member' },
      ];
      queryClient.setQueryData(['profile', 'organizations'], existingOrganizations);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const requestData = { organizationId: 2, message: 'Please accept me' };
      mockedApiClient.post.mockResolvedValueOnce({ data: { success: true } });

      const { result } = renderHook(() => useOrganizationMembershipRequest(), { wrapper });

      act(() => {
        result.current.mutate(requestData);
      });

      // Check optimistic update
      const cachedData = queryClient.getQueryData(['profile', 'organizations']) as any[];
      expect(cachedData).toHaveLength(2);
      expect(cachedData[1]).toMatchObject({
        id: 2,
        status: 'pending',
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.post).toHaveBeenCalledWith('/api/v1/organizations/2/join/', {
        message: 'Please accept me',
      });
    });
  });

  describe('useEventRegistrationOptimistic', () => {
    it('should optimistically update event registration', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const existingEvent = {
        id: 1,
        title: 'Test Event',
        is_registered: false,
      };
      queryClient.setQueryData(['events', 'detail', 1], existingEvent);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const registrationData = { eventId: 1, registrationData: { notes: 'Looking forward' } };
      mockedApiClient.post.mockResolvedValueOnce({ data: { success: true } });

      const { result } = renderHook(() => useEventRegistrationOptimistic(), { wrapper });

      act(() => {
        result.current.mutate(registrationData);
      });

      // Check optimistic update
      const cachedEvent = queryClient.getQueryData(['events', 'detail', 1]) as any;
      expect(cachedEvent.is_registered).toBe(true);
      expect(cachedEvent.registration_status).toBe('pending');

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.post).toHaveBeenCalledWith(
        '/api/v1/events/1/register/',
        { notes: 'Looking forward' }
      );
    });

    it('should update user activities optimistically', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const existingActivities = {
        results: [{ id: 1, type: 'other', created_at: '2024-01-01' }],
      };
      queryClient.setQueryData(['profile', 'activities'], existingActivities);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const registrationData = { eventId: 1 };
      mockedApiClient.post.mockResolvedValueOnce({ data: { success: true } });

      const { result } = renderHook(() => useEventRegistrationOptimistic(), { wrapper });

      act(() => {
        result.current.mutate(registrationData);
      });

      // Check activities were updated
      const cachedActivities = queryClient.getQueryData(['profile', 'activities']) as any;
      expect(cachedActivities.results).toHaveLength(2);
      expect(cachedActivities.results[0]).toMatchObject({
        type: 'event_registration',
        event_id: 1,
        status: 'pending',
      });

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });
    });
  });

  describe('useResourceBookmarkToggle', () => {
    it('should optimistically toggle bookmark', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const existingResource = {
        id: 1,
        title: 'Test Resource',
        is_bookmarked: true,
      };
      queryClient.setQueryData(['resources', 'detail', 1], existingResource);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const toggleData = { resourceId: 1, isBookmarked: true };
      mockedApiClient.delete.mockResolvedValueOnce({});

      const { result } = renderHook(() => useResourceBookmarkToggle(), { wrapper });

      act(() => {
        result.current.mutate(toggleData);
      });

      // Check optimistic update
      const cachedResource = queryClient.getQueryData(['resources', 'detail', 1]) as any;
      expect(cachedResource.is_bookmarked).toBe(false);

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.delete).toHaveBeenCalledWith('/api/v1/resources/1/bookmark/');
    });

    it('should add bookmark when not bookmarked', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const existingResource = {
        id: 1,
        title: 'Test Resource',
        is_bookmarked: false,
      };
      queryClient.setQueryData(['resources', 'detail', 1], existingResource);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const toggleData = { resourceId: 1, isBookmarked: false };
      mockedApiClient.post.mockResolvedValueOnce({});

      const { result } = renderHook(() => useResourceBookmarkToggle(), { wrapper });

      act(() => {
        result.current.mutate(toggleData);
      });

      // Check optimistic update
      const cachedResource = queryClient.getQueryData(['resources', 'detail', 1]) as any;
      expect(cachedResource.is_bookmarked).toBe(true);

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });

      expect(mockedApiClient.post).toHaveBeenCalledWith('/api/v1/resources/1/bookmark/');
    });

    it('should update resource lists optimistically', async () => {
      const queryClient = new QueryClient({
        defaultOptions: { queries: { retry: false }, mutations: { retry: false } },
      });

      const resourcesList = {
        results: [
          { id: 1, title: 'Resource 1', is_bookmarked: true },
          { id: 2, title: 'Resource 2', is_bookmarked: false },
        ],
      };
      queryClient.setQueryData(['resources', 'list', {}], resourcesList);

      const wrapper = ({ children }: { children: ReactNode }) => (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
      );

      const toggleData = { resourceId: 1, isBookmarked: true };
      mockedApiClient.delete.mockResolvedValueOnce({});

      const { result } = renderHook(() => useResourceBookmarkToggle(), { wrapper });

      act(() => {
        result.current.mutate(toggleData);
      });

      // Check list was updated
      const cachedList = queryClient.getQueryData(['resources', 'list', {}]) as any;
      expect(cachedList.results[0].is_bookmarked).toBe(false);

      await waitFor(() => {
        expect(result.current.isSuccess).toBe(true);
      });
    });
  });
});