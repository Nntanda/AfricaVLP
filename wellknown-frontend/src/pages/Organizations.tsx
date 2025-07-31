import React from 'react';

const Organizations: React.FC = () => {
  return (
    <div className="space-y-6">
      <div className="text-center">
        <h1 className="text-3xl font-bold text-gray-900 mb-4">Organizations</h1>
        <p className="text-lg text-gray-600">
          Connect with organizations participating in the AU-VLP program
        </p>
      </div>

      {/* Search and Filter */}
      <div className="bg-white rounded-lg shadow-md p-6">
        <div className="flex flex-col md:flex-row gap-4">
          <div className="flex-1">
            <input
              type="text"
              placeholder="Search organizations..."
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>
          <div className="flex gap-2">
            <select className="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">All Countries</option>
              <option value="ethiopia">Ethiopia</option>
              <option value="kenya">Kenya</option>
              <option value="nigeria">Nigeria</option>
              <option value="south-africa">South Africa</option>
            </select>
            <select className="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">All Types</option>
              <option value="ngo">NGO</option>
              <option value="government">Government</option>
              <option value="private">Private</option>
              <option value="academic">Academic</option>
            </select>
            <button className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
              Search
            </button>
          </div>
        </div>
      </div>

      {/* Organizations Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {[1, 2, 3, 4, 5, 6, 7, 8, 9].map((index) => (
          <div key={index} className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div className="h-32 bg-gradient-to-r from-blue-500 to-purple-600 relative">
              <div className="absolute inset-0 flex items-center justify-center">
                <div className="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                  <span className="text-2xl font-bold text-gray-600">
                    {String.fromCharCode(65 + (index - 1))}
                  </span>
                </div>
              </div>
            </div>
            <div className="p-6">
              <h3 className="text-xl font-semibold text-gray-900 mb-2">
                Organization {index}
              </h3>
              <div className="flex items-center text-sm text-gray-500 mb-3">
                <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Addis Ababa, Ethiopia</span>
              </div>
              <p className="text-gray-600 mb-4">
                A brief description of the organization and its role in the AU-VLP program. 
                This organization focuses on youth development and leadership training.
              </p>
              <div className="flex items-center justify-between">
                <div className="flex items-center text-sm text-gray-500">
                  <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                  </svg>
                  <span>25 members</span>
                </div>
                <button className="text-blue-600 hover:text-blue-800 font-medium">
                  View Profile →
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Pagination */}
      <div className="flex justify-center">
        <nav className="flex space-x-2">
          <button className="px-3 py-2 text-gray-500 hover:text-gray-700">Previous</button>
          <button className="px-3 py-2 bg-blue-600 text-white rounded">1</button>
          <button className="px-3 py-2 text-gray-500 hover:text-gray-700">2</button>
          <button className="px-3 py-2 text-gray-500 hover:text-gray-700">3</button>
          <button className="px-3 py-2 text-gray-500 hover:text-gray-700">Next</button>
        </nav>
      </div>
    </div>
  );
};

export default Organizations;