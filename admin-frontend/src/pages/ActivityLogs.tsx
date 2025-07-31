import React, { useState } from 'react';
import Layout from '../components/layout/Layout';
import { ActivityLogList, ActivityLogFilters } from '../components/activity';

const ActivityLogs: React.FC = () => {
  const [filters, setFilters] = useState<{
    action?: string;
    dateFrom?: string;
    dateTo?: string;
  }>({});
  const [refreshTrigger, setRefreshTrigger] = useState(0);

  const handleFiltersChange = (newFilters: {
    action?: string;
    dateFrom?: string;
    dateTo?: string;
  }) => {
    setFilters(newFilters);
    setRefreshTrigger(prev => prev + 1);
  };

  const handleRefresh = () => {
    setRefreshTrigger(prev => prev + 1);
  };

  return (
    <Layout>
      <div className="space-y-6">
        <div>
          <h1 className="text-2xl font-semibold text-gray-900">Activity Logs</h1>
          <p className="mt-1 text-sm text-gray-600">
            View and filter system activity and user actions
          </p>
        </div>

        <ActivityLogFilters onFiltersChange={handleFiltersChange} />

        <div className="bg-white shadow rounded-lg">
          <div className="px-4 py-5 sm:p-6">
            <ActivityLogList
              filters={filters}
              onRefresh={handleRefresh}
              refreshTrigger={refreshTrigger}
            />
          </div>
        </div>
      </div>
    </Layout>
  );
};

export default ActivityLogs;