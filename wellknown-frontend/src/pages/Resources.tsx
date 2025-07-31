import React from 'react';

const Resources: React.FC = () => {
  return (
    <div className="space-y-6">
      <div className="text-center">
        <h1 className="text-3xl font-bold text-gray-900 mb-4">Resources</h1>
        <p className="text-lg text-gray-600">
          Access valuable resources, documents, and materials for the AU-VLP program
        </p>
      </div>

      {/* Resource Categories */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        {[
          { name: 'Documents', icon: '📄', count: 45 },
          { name: 'Videos', icon: '🎥', count: 23 },
          { name: 'Presentations', icon: '📊', count: 18 },
          { name: 'Templates', icon: '📋', count: 12 }
        ].map((category) => (
          <div key={category.name} className="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow cursor-pointer">
            <div className="text-4xl mb-3">{category.icon}</div>
            <h3 className="text-lg font-semibold text-gray-900 mb-1">{category.name}</h3>
            <p className="text-gray-500">{category.count} items</p>
          </div>
        ))}
      </div>

      {/* Search and Filter */}
      <div className="bg-white rounded-lg shadow-md p-6">
        <div className="flex flex-col md:flex-row gap-4">
          <div className="flex-1">
            <input
              type="text"
              placeholder="Search resources..."
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>
          <div className="flex gap-2">
            <select className="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">All Categories</option>
              <option value="documents">Documents</option>
              <option value="videos">Videos</option>
              <option value="presentations">Presentations</option>
              <option value="templates">Templates</option>
            </select>
            <select className="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">All Topics</option>
              <option value="leadership">Leadership</option>
              <option value="training">Training</option>
              <option value="governance">Governance</option>
              <option value="development">Development</option>
            </select>
            <button className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
              Search
            </button>
          </div>
        </div>
      </div>

      {/* Resources List */}
      <div className="space-y-4">
        {[1, 2, 3, 4, 5, 6, 7, 8].map((index) => (
          <div key={index} className="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div className="flex items-start space-x-4">
              <div className="flex-shrink-0">
                <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
              </div>
              <div className="flex-1">
                <div className="flex items-center justify-between mb-2">
                  <h3 className="text-lg font-semibold text-gray-900">
                    Resource Document {index}
                  </h3>
                  <div className="flex items-center space-x-2">
                    <span className="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                      PDF
                    </span>
                    <span className="text-sm text-gray-500">2.5 MB</span>
                  </div>
                </div>
                <p className="text-gray-600 mb-3">
                  This is a comprehensive resource document that covers important aspects of the AU-VLP program. 
                  It includes guidelines, best practices, and practical information for participants.
                </p>
                <div className="flex items-center justify-between">
                  <div className="flex items-center space-x-4 text-sm text-gray-500">
                    <span>Added: March 10, 2024</span>
                    <span>•</span>
                    <span>Category: Leadership</span>
                    <span>•</span>
                    <span>Downloads: 156</span>
                  </div>
                  <div className="flex space-x-2">
                    <button className="text-blue-600 hover:text-blue-800 font-medium">
                      Preview
                    </button>
                    <button className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                      Download
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Load More */}
      <div className="text-center">
        <button className="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors">
          Load More Resources
        </button>
      </div>
    </div>
  );
};

export default Resources;