import { useMutation, useQueryClient } from 'react-query';
import apiClient from '../services/api/client';
import { queryKeys } from '../config/queryClient';
import { useToast } from '../context/ToastContext';

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
  const { addToast } = useToast();

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

// Blog post optimistic updates
export const useBlogPostOptimisticUpdate = () => {
  const queryClient = useQueryClient();

  return useMutation(
    async (data: { id: string | number; [key: string]: any }) => {
      const response = await apiClient.put(`/api/v1/blog/posts/${data.id}/`, data);
      return response.data;
    },
    {
      onMutate: async (newData) => {
        await queryClient.cancelQueries(queryKeys.blogPosts.detail(newData.id));

        const previousData = queryClient.getQueryData(queryKeys.blogPosts.detail(newData.id));

        queryClient.setQueryData(queryKeys.blogPosts.detail(newData.id), (old: any) => ({
          ...old,
          ...newData,
          updated_at: new Date().toISOString(),
        }));

        // Also update in lists if present
        queryClient.setQueriesData(
          { queryKey: queryKeys.blogPosts.lists(), exact: false },
          (old: any) => {
            if (!old?.results) return old;
            return {
              ...old,
              results: old.results.map((item: any) =>
                item.id === newData.id ? { ...item, ...newData, updated_at: new Date().toISOString() } : item
              ),
            };
          }
        );

        return { previousData };
      },
      onError: (err, newData, context) => {
        if (context?.previousData) {
          queryClient.setQueryData(queryKeys.blogPosts.detail(newData.id), context.previousData);
        }
        toast.error('Failed to update blog post');
      },
      onSuccess: () => {
        toast.success('Blog post updated successfully');
      },
      onSettled: (data, error, variables) => {
        queryClient.invalidateQueries(queryKeys.blogPosts.detail(variables.id));
        queryClient.invalidateQueries(queryKeys.blogPosts.lists());
      },
    }
  );
};

// User optimistic updates
export const useUserOptimisticUpdate = () => {
  const queryClient = useQueryClient();

  return useMutation(
    async (data: { id: string | number; [key: string]: any }) => {
      const response = await apiClient.put(`/api/v1/admin/users/${data.id}/`, data);
      return response.data;
    },
    {
      onMutate: async (newData) => {
        await queryClient.cancelQueries(queryKeys.users.detail(newData.id));

        const previousData = queryClient.getQueryData(queryKeys.users.detail(newData.id));

        queryClient.setQueryData(queryKeys.users.detail(newData.id), (old: any) => ({
          ...old,
          ...newData,
          updated_at: new Date().toISOString(),
        }));

        // Update in lists
        queryClient.setQueriesData(
          { queryKey: queryKeys.users.lists(), exact: false },
          (old: any) => {
            if (!old?.results) return old;
            return {
              ...old,
              results: old.results.map((item: any) =>
                item.id === newData.id ? { ...item, ...newData, updated_at: new Date().toISOString() } : item
              ),
            };
          }
        );

        return { previousData };
      },
      onError: (err, newData, context) => {
        if (context?.previousData) {
          queryClient.setQueryData(queryKeys.users.detail(newData.id), context.previousData);
        }
        toast.error('Failed to update user');
      },
      onSuccess: () => {
        toast.success('User updated successfully');
      },
      onSettled: (data, error, variables) => {
        queryClient.invalidateQueries(queryKeys.users.detail(variables.id));
        queryClient.invalidateQueries(queryKeys.users.lists());
      },
    }
  );
};

// Organization optimistic updates
export const useOrganizationOptimisticUpdate = () => {
  const queryClient = useQueryClient();

  return useMutation(
    async (data: { id: string | number; [key: string]: any }) => {
      const response = await apiClient.put(`/api/v1/organizations/${data.id}/`, data);
      return response.data;
    },
    {
      onMutate: async (newData) => {
        await queryClient.cancelQueries(queryKeys.organizations.detail(newData.id));

        const previousData = queryClient.getQueryData(queryKeys.organizations.detail(newData.id));

        queryClient.setQueryData(queryKeys.organizations.detail(newData.id), (old: any) => ({
          ...old,
          ...newData,
          updated_at: new Date().toISOString(),
        }));

        // Update in lists
        queryClient.setQueriesData(
          { queryKey: queryKeys.organizations.lists(), exact: false },
          (old: any) => {
            if (!old?.results) return old;
            return {
              ...old,
              results: old.results.map((item: any) =>
                item.id === newData.id ? { ...item, ...newData, updated_at: new Date().toISOString() } : item
              ),
            };
          }
        );

        return { previousData };
      },
      onError: (err, newData, context) => {
        if (context?.previousData) {
          queryClient.setQueryData(queryKeys.organizations.detail(newData.id), context.previousData);
        }
        toast.error('Failed to update organization');
      },
      onSuccess: () => {
        toast.success('Organization updated successfully');
      },
      onSettled: (data, error, variables) => {
        queryClient.invalidateQueries(queryKeys.organizations.detail(variables.id));
        queryClient.invalidateQueries(queryKeys.organizations.lists());
      },
    }
  );
};

// Bulk operations with optimistic updates
export const useBulkDeleteOptimistic = <T extends { id: string | number }>(
  endpoint: string,
  queryKey: any[]
) => {
  const queryClient = useQueryClient();

  return useMutation(
    async (ids: (string | number)[]) => {
      await Promise.all(
        ids.map(id => apiClient.delete(`${endpoint}/${id}/`))
      );
    },
    {
      onMutate: async (ids) => {
        await queryClient.cancelQueries(queryKey);

        const previousData = queryClient.getQueryData(queryKey);

        queryClient.setQueryData(queryKey, (old: any) => {
          if (!old?.results) return old;
          return {
            ...old,
            results: old.results.filter((item: T) => !ids.includes(item.id)),
            count: old.count - ids.length,
          };
        });

        return { previousData };
      },
      onError: (err, ids, context) => {
        if (context?.previousData) {
          queryClient.setQueryData(queryKey, context.previousData);
        }
        toast.error('Failed to delete items');
      },
      onSuccess: (data, ids) => {
        toast.success(`Successfully deleted ${ids.length} item(s)`);
      },
      onSettled: () => {
        queryClient.invalidateQueries(queryKey);
      },
    }
  );
};

// Status toggle optimistic update
export const useStatusToggleOptimistic = (
  endpoint: string,
  queryKey: any[],
  statusField: string = 'is_active'
) => {
  const queryClient = useQueryClient();

  return useMutation(
    async ({ id, status }: { id: string | number; status: boolean }) => {
      const response = await apiClient.patch(`${endpoint}/${id}/`, {
        [statusField]: status,
      });
      return response.data;
    },
    {
      onMutate: async ({ id, status }) => {
        await queryClient.cancelQueries(queryKey);

        const previousData = queryClient.getQueryData(queryKey);

        queryClient.setQueryData(queryKey, (old: any) => {
          if (!old?.results) return old;
          return {
            ...old,
            results: old.results.map((item: any) =>
              item.id === id ? { ...item, [statusField]: status } : item
            ),
          };
        });

        return { previousData };
      },
      onError: (err, variables, context) => {
        if (context?.previousData) {
          queryClient.setQueryData(queryKey, context.previousData);
        }
        toast.error('Failed to update status');
      },
      onSuccess: () => {
        toast.success('Status updated successfully');
      },
      onSettled: () => {
        queryClient.invalidateQueries(queryKey);
      },
    }
  );
};