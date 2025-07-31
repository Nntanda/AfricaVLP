import React from 'react';

const Footer: React.FC = () => {
  return (
    <footer className="bg-gray-800 text-white py-8 mt-auto">
      <div className="container mx-auto px-4">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div>
            <h3 className="text-lg font-semibold mb-4">AU-VLP</h3>
            <p className="text-gray-300">
              African Union Youth Leadership Program - Empowering the next generation of African leaders.
            </p>
          </div>
          <div>
            <h3 className="text-lg font-semibold mb-4">Quick Links</h3>
            <ul className="space-y-2">
              <li><a href="/blog" className="text-gray-300 hover:text-white">Blog</a></li>
              <li><a href="/news" className="text-gray-300 hover:text-white">News</a></li>
              <li><a href="/events" className="text-gray-300 hover:text-white">Events</a></li>
              <li><a href="/organizations" className="text-gray-300 hover:text-white">Organizations</a></li>
            </ul>
          </div>
          <div>
            <h3 className="text-lg font-semibold mb-4">Contact</h3>
            <p className="text-gray-300">
              For more information about the AU-VLP program, please contact us.
            </p>
          </div>
        </div>
        <div className="border-t border-gray-700 mt-8 pt-8 text-center">
          <p className="text-gray-300">
            © {new Date().getFullYear()} African Union Youth Leadership Program. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;