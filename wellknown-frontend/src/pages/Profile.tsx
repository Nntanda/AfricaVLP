import React, { useState } from 'react';
import { useAuth } from '../hooks/useAuth';
import UserProfileForm from '../components/profile/UserProfileForm';
import MessageCenter from '../components/communication/MessageCenter';
import { User } from '../types';

const Profile: React.FC = () => {
  const { user } = useAuth();
  const [activeTab, setActiveTab] = useState<'profile' | 'messages' | 'activity'>('profile');
  const [isEditing, setIsEditing] = useState(false);
  const [loading, setLoading] = useState(false);

  // Mock data - in real app this would come from API
  const mockMessages = [
    {
      id: '1',
      subject: 'Welcome to AU-VLP!',
      content: 'Welcome to the African Union Youth Leadership Program. We are excited to have you join our community.',
      sender: 'AU-VLP Team',
      recipient: user?.email || '',
      created_at: '2024-03-20T10:00:00Z',
      read: false,
      type: 'inbox' as const
    }
  ];

  const handleSaveProfile = async (userData: Partial<User>) => {
    setLoading(true);
    try {
      // In real app, this would make an API call
      console.log('Saving profile data:', userData);
      await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate API call
      setIsEditing(false);
    } catch (error) {
      console.error('Error saving profile:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleSendMessage = async (message: { recipient: string; subject: string; content: string }) => {
    console.log('Sending message:', message);
    // In real app, this would make an API call
  };

  const handleMarkAsRead = async (messageId: string) => {
    console.log('Marking message as read:', messageId);
    // In real app, this would make an API call
  };

  // Mock user data if not available
  const mockUser: User = user || {
    id: '1',
    email: 'john.doe@example.com',
    first_name: 'John',
    last_name: 'Doe',
    phone: '+251 911 123 456',
    bio: 'Passionate youth leader working on community development projects.',
    created_at: '2024-01-15T10:00:00Z',
    updated_at: '2024-03-15T10:00:00Z',
    is_active: true,
    organization: {
      id: '1',
      name: 'Youth Development Organization'
    }
  };

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <div className="text-center">
        <h1 className="text-3xl font-bold text-gray-900 mb-4">My Profile</h1>
        <p className="text-lg text-gray-600">
          Manage your profile information and settings
        </p>
      </div>

      {/* Profile Header */}
      <div className="bg-white rounded-lg shadow-md p-6">
        <div className="flex items-center justify-between">
          <div className="flex items-center space-x-6">
            <div className="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center">
              {mockUser.profile_picture ? (
                <img 
                  src={mockUser.profile_picture} 
                  alt={`${mockUser.first_name} ${mockUser.last_name}`}
                  className="w-24 h-24 rounded-full object-cover"
                />
              ) : (
                <span className="text-2xl font-bold text-gray-600">
                  {mockUser.first_name?.charAt(0)}{mockUser.last_name?.charAt(0)}
                </span>
              )}
            </div>
            <div>
              <h2 className="text-2xl font-bold text-gray-900">
                {mockUser.first_name} {mockUser.last_name}
              </h2>
              <p className="text-gray-600">{mockUser.title || 'Youth Leader'}</p>
              <p className="text-gray-500">
                Member since {new Date(mockUser.created_at).toLocaleDateString('en-US', { 
                  year: 'numeric', 
                  month: 'long' 
                })}
              </p>
            </div>
          </div>
          {!isEditing && (
            <button 
              onClick={() => setIsEditing(true)}
              className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
            >
              Edit Profile
            </button>
          )}
        </div>
      </div>

      {/* Tabs */}
      <div className="bg-white rounded-lg shadow-md">
        <div className="border-b border-gray-200">
          <nav className="flex space-x-8 px-6">
            <button
              onClick={() => setActiveTab('profile')}
              className={`py-4 px-1 border-b-2 font-medium text-sm ${
                activeTab === 'profile'
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              }`}
            >
              Profile Information
            </button>
            <button
              onClick={() => setActiveTab('messages')}
              className={`py-4 px-1 border-b-2 font-medium text-sm ${
                activeTab === 'messages'
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              }`}
            >
              Messages
            </button>
            <button
              onClick={() => setActiveTab('activity')}
              className={`py-4 px-1 border-b-2 font-medium text-sm ${
                activeTab === 'activity'
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              }`}
            >
              Activity
            </button>
          </nav>
        </div>

        <div className="p-6">
          {activeTab === 'profile' && (
            <>
              {isEditing ? (
                <div>
                  <div className="flex justify-between items-center mb-6">
                    <h3 className="text-lg font-semibold text-gray-900">Edit Profile</h3>
                    <button 
                      onClick={() => setIsEditing(false)}
                      className="text-gray-500 hover:text-gray-700"
                    >
                      Cancel
                    </button>
                  </div>
                  <UserProfileForm 
                    user={mockUser} 
                    onSave={handleSaveProfile}
                    loading={loading}
                  />
                </div>
              ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <h3 className="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                    <div className="space-y-3">
                      <div>
                        <label className="text-sm font-medium text-gray-500">Email</label>
                        <p className="text-gray-900">{mockUser.email}</p>
                      </div>
                      <div>
                        <label className="text-sm font-medium text-gray-500">Phone</label>
                        <p className="text-gray-900">{mockUser.phone || 'Not provided'}</p>
                      </div>
                      <div>
                        <label className="text-sm font-medium text-gray-500">Bio</label>
                        <p className="text-gray-900">{mockUser.bio || 'No bio provided'}</p>
                      </div>
                    </div>
                  </div>

                  <div>
                    <h3 className="text-lg font-semibold text-gray-900 mb-4">Organization</h3>
                    <div className="space-y-3">
                      <div>
                        <label className="text-sm font-medium text-gray-500">Organization</label>
                        <p className="text-gray-900">{mockUser.organization?.name || 'Not affiliated'}</p>
                      </div>
                      <div>
                        <label className="text-sm font-medium text-gray-500">Skills</label>
                        <p className="text-gray-900">{mockUser.skills || 'Not specified'}</p>
                      </div>
                      <div>
                        <label className="text-sm font-medium text-gray-500">Interests</label>
                        <p className="text-gray-900">{mockUser.interests || 'Not specified'}</p>
                      </div>
                    </div>
                  </div>
                </div>
              )}
            </>
          )}

          {activeTab === 'messages' && (
            <MessageCenter
              messages={mockMessages}
              onSendMessage={handleSendMessage}
              onMarkAsRead={handleMarkAsRead}
            />
          )}

          {activeTab === 'activity' && (
            <div>
              <h3 className="text-lg font-semibold text-gray-900 mb-4">Activity Summary</h3>
              <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div className="text-center p-4 bg-gray-50 rounded-lg">
                  <div className="text-2xl font-bold text-blue-600">5</div>
                  <div className="text-gray-600">Events Attended</div>
                </div>
                <div className="text-center p-4 bg-gray-50 rounded-lg">
                  <div className="text-2xl font-bold text-green-600">12</div>
                  <div className="text-gray-600">Resources Downloaded</div>
                </div>
                <div className="text-center p-4 bg-gray-50 rounded-lg">
                  <div className="text-2xl font-bold text-purple-600">3</div>
                  <div className="text-gray-600">Blog Posts Read</div>
                </div>
                <div className="text-center p-4 bg-gray-50 rounded-lg">
                  <div className="text-2xl font-bold text-orange-600">8</div>
                  <div className="text-gray-600">Comments Made</div>
                </div>
              </div>

              <h4 className="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h4>
              <div className="space-y-4">
                {[
                  { action: 'Attended', item: 'Leadership Workshop', date: '2 days ago' },
                  { action: 'Downloaded', item: 'Training Manual PDF', date: '1 week ago' },
                  { action: 'Commented on', item: 'Youth Development Blog Post', date: '2 weeks ago' },
                  { action: 'Registered for', item: 'Online Conference', date: '3 weeks ago' }
                ].map((activity, index) => (
                  <div key={index} className="flex items-center space-x-3 py-2">
                    <div className="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <div className="flex-1">
                      <span className="text-gray-900">{activity.action} </span>
                      <span className="font-medium text-gray-900">{activity.item}</span>
                    </div>
                    <span className="text-sm text-gray-500">{activity.date}</span>
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default Profile;