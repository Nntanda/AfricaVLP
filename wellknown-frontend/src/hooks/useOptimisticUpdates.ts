import { useMutation, useQueryClient } from 'react-query';
import { apiClient } from '../services/api/client';
import { queryKeys } from '../config/queryClient';
import { toast } from './useToast';

// Generic optimistic update hook
export const useOptimisticMutation = <TData, TVariables>(
  mutationFn: (variables: TVariables) => Promise<TData>,
  options: {
    queryKey: any[];
    updateFn: (oldData: any, variables: TVariables) => any;
    successMessage?: string;
    errorMessage?: string;
  }
) => {
  const queryClient = useQueryClient();

  return useMutation(mutationFn, {
    onMutate: async (variables) => {
      // Cancel any outgoing refetches
      await queryClient.cancelQueries(options.queryKey);

      // Snapshot the previous value
      const previousData = queryClient.getQueryData(options.queryKey);

      // Optimistically update to the new value
      queryClient.setQueryData(options.queryKey, (old: any) => 
        options.updateFn(old, variables)
      );

      // Return a context object with the snapshotted value
      return { previousData };
    },
    onError: (err, variables, context) => {
      // If the mutation fails, use the context returned from onMutate to roll back
      if (context?.previousData) {
        queryClient.setQueryData(options.queryKey, context.previousData);
      }
      toast.error(options.errorMessage || 'An error occurred');
    },
    onSuccess: () => {
      if (options.successMessage) {
        toast.success(options.successMessage);
      }
    },
    onSettled: () => {
      // Always refetch after error or success
      queryClient.invalidateQueries(options.queryKey);
    },
  });
};

// Profile optimistic updates
export const useProfileOptimisticUpdate = () => {
  const queryClient = useQueryClient();

  return useMutation(
    async (data: any) => {
      const response = await apiClient.put('/api/v1/profile/', data);
      return response.data;
    },
    {
      onMutate: async (newData) => {
        await queryClient.cancelQueries(queryKeys.profile.current);

        const previousData = queryClient.getQueryData(queryKeys.profile.current);

        queryClient.setQueryData(queryKeys.profile.current, (old: any) => ({
          ...old,
          ...newData,
          updated_at: new Date().toISOString(),
        }));

        // Also update auth profile if it exists
        queryClient.setQueryData(queryKeys.auth.profile, (old: any) => 
          old ? { ...old, ...newData } : old
        );

        return { previousData };
      },
      onError: (err, newData, context) => {
        if (context?.previousData) {
          queryClient.setQueryData(queryKeys.profile.current, context.previousData);
        }
        toast.error('Failed to update profile');
      },
      onSuccess: () => {
        toast.success('Profile updated successfully');
      },
      onSettled: () => {
        queryClient.invalidateQueries(queryKeys.profile.current);
        queryClient.invalidateQueries(queryKeys.auth.profile);
      },
    }
  );
};

// Contact form with optimistic feedback
export const useContactFormOptimistic = () => {
  return useMutation(
    async (data: any) => {
      // Simulate delay for better UX
      await new Promise(resolve => setTimeout(resolve, 1000));
      const response = await apiClient.post('/api/v1/contact/', data);
      return response.data;
    },
    {
      onMutate: () => {
        // Show immediate feedback
        toast.info('Sending message...');
      },
      onError: () => {
        toast.error('Failed to send message. Please try again.');
      },
      onSuccess: () => {
        toast.success('Message sent successfully! We will get back to you soon.');
      },
    }
  );
};

// Newsletter subscription with optimistic feedback
export const useNewsletterOptimistic = () => {
  return useMutation(
    async (data: { email: string }) => {
      const response = await apiClient.post('/api/v1/newsletter/subscribe/', data);
      return response.data;
    },
    {
      onMutate: () => {
        toast.info('Subscribing to newsletter...');
      },
      onError: (error: any) => {
        const message = error?.response?.data?.message || 'Failed to subscribe. Please try again.';
        toast.error(message);
      },
      onSuccess: () => {
        toast.success('Successfully subscribed to newsletter!');
      },
    }
  );
};

// Organization membership request with optimistic update
export const useOrganizationMembershipRequest = () => {
  const queryClient = useQueryClient();

  return useMutation(
    async (data: { organizationId: string | number; message?: string }) => {
      const response = await apiClient.post(`/api/v1/organizations/${data.organizationId}/join/`, {
        message: data.message,
      });
      return response.data;
    },
    {
      onMutate: async (variables) => {
        // Update user organizations optimistically
        await queryClient.cancelQueries(queryKeys.profile.organizations);

        const previousData = queryClient.getQueryData(queryKeys.profile.organizations);

        queryClient.setQueryData(queryKeys.profile.organizations, (old: any) => {
          if (!old) return old;
          return [
            ...old,
            {
              id: variables.organizationId,
              status: 'pending',
              requested_at: new Date().toISOString(),
            },
          ];
        });

        toast.info('Sending membership request...');

        return { previousData };
      },
      onError: (err, variables, context) => {
        if (context?.previousData) {
          queryClient.setQueryData(queryKeys.profile.organizations, context.previousData);
        }
        toast.error('Failed to send membership request');
      },
      onSuccess: () => {
        toast.success('Membership request sent successfully!');
      },
      onSettled: () => {
        queryClient.invalidateQueries(queryKeys.profile.organizations);
      },
    }
  );
};

// Event registration with optimistic update
export const useEventRegistrationOptimistic = () => {
  const queryClient = useQueryClient();

  return useMutation(
    async (data: { eventId: string | number; registrationData?: any }) => {
      const response = await apiClient.post(`/api/v1/events/${data.eventId}/register/`, data.registrationData);
      return response.data;
    },
    {
      onMutate: async (variables) => {
        // Update event detail optimistically
        await queryClient.cancelQueries(queryKeys.events.detail(variables.eventId));

        const previousData = queryClient.getQueryData(queryKeys.events.detail(variables.eventId));

        queryClient.setQueryData(queryKeys.events.detail(variables.eventId), (old: any) => {
          if (!old) return old;
          return {
            ...old,
            is_registered: true,
            registration_status: 'pending',
            registered_at: new Date().toISOString(),
          };
        });

        // Update user activities
        queryClient.setQueryData(queryKeys.profile.activities, (old: any) => {
          if (!old?.results) return old;
          return {
            ...old,
            results: [
              {
                id: `temp-${Date.now()}`,
                type: 'event_registration',
                event_id: variables.eventId,
                created_at: new Date().toISOString(),
                status: 'pending',
              },
              ...old.results,
            ],
          };
        });

        toast.info('Registering for event...');

        return { previousData };
      },
      onError: (err, variables, context) => {
        if (context?.previousData) {
          queryClient.setQueryData(queryKeys.events.detail(variables.eventId), context.previousData);
        }
        toast.error('Failed to register for event');
      },
      onSuccess: () => {
        toast.success('Successfully registered for event!');
      },
      onSettled: (data, error, variables) => {
        queryClient.invalidateQueries(queryKeys.events.detail(variables.eventId));
        queryClient.invalidateQueries(queryKeys.profile.activities);
      },
    }
  );
};

// Resource bookmark toggle with optimistic update
export const useResourceBookmarkToggle = () => {
  const queryClient = useQueryClient();

  return useMutation(
    async (data: { resourceId: string | number; isBookmarked: boolean }) => {
      if (data.isBookmarked) {
        await apiClient.delete(`/api/v1/resources/${data.resourceId}/bookmark/`);
      } else {
        await apiClient.post(`/api/v1/resources/${data.resourceId}/bookmark/`);
      }
      return { ...data, isBookmarked: !data.isBookmarked };
    },
    {
      onMutate: async (variables) => {
        await queryClient.cancelQueries(queryKeys.resources.detail(variables.resourceId));

        const previousData = queryClient.getQueryData(queryKeys.resources.detail(variables.resourceId));

        queryClient.setQueryData(queryKeys.resources.detail(variables.resourceId), (old: any) => {
          if (!old) return old;
          return {
            ...old,
            is_bookmarked: !variables.isBookmarked,
          };
        });

        // Update in lists if present
        queryClient.setQueriesData(
          { queryKey: queryKeys.resources.lists(), exact: false },
          (old: any) => {
            if (!old?.results) return old;
            return {
              ...old,
              results: old.results.map((item: any) =>
                item.id === variables.resourceId 
                  ? { ...item, is_bookmarked: !variables.isBookmarked }
                  : item
              ),
            };
          }
        );

        return { previousData };
      },
      onError: (err, variables, context) => {
        if (context?.previousData) {
          queryClient.setQueryData(queryKeys.resources.detail(variables.resourceId), context.previousData);
        }
        toast.error('Failed to update bookmark');
      },
      onSuccess: (data) => {
        toast.success(data.isBookmarked ? 'Resource bookmarked' : 'Bookmark removed');
      },
      onSettled: (data, error, variables) => {
        queryClient.invalidateQueries(queryKeys.resources.detail(variables.resourceId));
        queryClient.invalidateQueries(queryKeys.resources.lists());
      },
    }
  );
};