import React, { useState } from 'react';
import { Button } from '../ui/Button';
import { Input } from '../ui/Input';
import { Modal } from '../ui/Modal';

interface Message {
  id: string;
  subject: string;
  content: string;
  sender: string;
  recipient: string;
  created_at: string;
  read: boolean;
  type: 'inbox' | 'sent' | 'draft';
}

interface MessageCenterProps {
  messages: Message[];
  onSendMessage: (message: { recipient: string; subject: string; content: string }) => void;
  onMarkAsRead: (messageId: string) => void;
  loading?: boolean;
}

const MessageCenter: React.FC<MessageCenterProps> = ({
  messages,
  onSendMessage,
  onMarkAsRead,
  loading = false
}) => {
  const [activeTab, setActiveTab] = useState<'inbox' | 'sent' | 'compose'>('inbox');
  const [selectedMessage, setSelectedMessage] = useState<Message | null>(null);
  const [showComposeModal, setShowComposeModal] = useState(false);
  const [composeForm, setComposeForm] = useState({
    recipient: '',
    subject: '',
    content: ''
  });

  const filteredMessages = messages.filter(message => {
    if (activeTab === 'inbox') return message.type === 'inbox';
    if (activeTab === 'sent') return message.type === 'sent';
    return false;
  });

  const unreadCount = messages.filter(m => m.type === 'inbox' && !m.read).length;

  const handleComposeSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSendMessage(composeForm);
    setComposeForm({ recipient: '', subject: '', content: '' });
    setShowComposeModal(false);
  };

  const handleMessageClick = (message: Message) => {
    setSelectedMessage(message);
    if (!message.read && message.type === 'inbox') {
      onMarkAsRead(message.id);
    }
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  return (
    <div className="bg-white rounded-lg shadow-md overflow-hidden">
      {/* Header */}
      <div className="border-b border-gray-200 p-6">
        <div className="flex justify-between items-center">
          <h2 className="text-xl font-semibold text-gray-900">Messages</h2>
          <Button
            onClick={() => setShowComposeModal(true)}
            className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
          >
            Compose
          </Button>
        </div>
      </div>

      {/* Tabs */}
      <div className="border-b border-gray-200">
        <nav className="flex space-x-8 px-6">
          <button
            onClick={() => setActiveTab('inbox')}
            className={`py-4 px-1 border-b-2 font-medium text-sm ${
              activeTab === 'inbox'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700'
            }`}
          >
            Inbox {unreadCount > 0 && (
              <span className="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                {unreadCount}
              </span>
            )}
          </button>
          <button
            onClick={() => setActiveTab('sent')}
            className={`py-4 px-1 border-b-2 font-medium text-sm ${
              activeTab === 'sent'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700'
            }`}
          >
            Sent
          </button>
        </nav>
      </div>

      {/* Message List */}
      <div className="divide-y divide-gray-200">
        {loading ? (
          <div className="p-6 text-center">
            <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            <p className="mt-2 text-gray-500">Loading messages...</p>
          </div>
        ) : filteredMessages.length === 0 ? (
          <div className="p-6 text-center text-gray-500">
            No messages in {activeTab}
          </div>
        ) : (
          filteredMessages.map((message) => (
            <div
              key={message.id}
              onClick={() => handleMessageClick(message)}
              className={`p-4 hover:bg-gray-50 cursor-pointer ${
                !message.read && message.type === 'inbox' ? 'bg-blue-50' : ''
              }`}
            >
              <div className="flex justify-between items-start">
                <div className="flex-1">
                  <div className="flex items-center space-x-2">
                    <h4 className={`text-sm ${
                      !message.read && message.type === 'inbox' 
                        ? 'font-semibold text-gray-900' 
                        : 'font-medium text-gray-700'
                    }`}>
                      {activeTab === 'inbox' ? message.sender : message.recipient}
                    </h4>
                    {!message.read && message.type === 'inbox' && (
                      <span className="w-2 h-2 bg-blue-600 rounded-full"></span>
                    )}
                  </div>
                  <p className={`text-sm mt-1 ${
                    !message.read && message.type === 'inbox' 
                      ? 'font-medium text-gray-900' 
                      : 'text-gray-600'
                  }`}>
                    {message.subject}
                  </p>
                  <p className="text-sm text-gray-500 mt-1 line-clamp-2">
                    {message.content}
                  </p>
                </div>
                <span className="text-xs text-gray-400 ml-4">
                  {formatDate(message.created_at)}
                </span>
              </div>
            </div>
          ))
        )}
      </div>

      {/* Message Detail Modal */}
      {selectedMessage && (
        <Modal
          isOpen={!!selectedMessage}
          onClose={() => setSelectedMessage(null)}
          title={selectedMessage.subject}
        >
          <div className="space-y-4">
            <div className="border-b border-gray-200 pb-4">
              <div className="flex justify-between items-center">
                <div>
                  <p className="text-sm text-gray-600">
                    From: <span className="font-medium">{selectedMessage.sender}</span>
                  </p>
                  <p className="text-sm text-gray-600">
                    To: <span className="font-medium">{selectedMessage.recipient}</span>
                  </p>
                </div>
                <span className="text-sm text-gray-500">
                  {formatDate(selectedMessage.created_at)}
                </span>
              </div>
            </div>
            <div className="prose max-w-none">
              <p className="whitespace-pre-wrap">{selectedMessage.content}</p>
            </div>
          </div>
        </Modal>
      )}

      {/* Compose Modal */}
      <Modal
        isOpen={showComposeModal}
        onClose={() => setShowComposeModal(false)}
        title="Compose Message"
      >
        <form onSubmit={handleComposeSubmit} className="space-y-4">
          <Input
            label="Recipient"
            value={composeForm.recipient}
            onChange={(e) => setComposeForm(prev => ({ ...prev, recipient: e.target.value }))}
            placeholder="Enter recipient email or username"
            required
          />
          <Input
            label="Subject"
            value={composeForm.subject}
            onChange={(e) => setComposeForm(prev => ({ ...prev, subject: e.target.value }))}
            placeholder="Enter message subject"
            required
          />
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Message
            </label>
            <textarea
              value={composeForm.content}
              onChange={(e) => setComposeForm(prev => ({ ...prev, content: e.target.value }))}
              rows={6}
              className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Type your message here..."
              required
            />
          </div>
          <div className="flex justify-end space-x-3">
            <Button
              type="button"
              variant="outline"
              onClick={() => setShowComposeModal(false)}
            >
              Cancel
            </Button>
            <Button type="submit">
              Send Message
            </Button>
          </div>
        </form>
      </Modal>
    </div>
  );
};

export default MessageCenter;