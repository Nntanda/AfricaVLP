import React from 'react';
import { Link } from 'react-router-dom';
import FeaturedContent from '../components/home/FeaturedContent';

const Home: React.FC = () => {
  return (
    <div className="space-y-8">
      {/* Hero Section */}
      <section className="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-8 text-center">
        <h1 className="text-4xl font-bold mb-4">
          Welcome to AU-VLP
        </h1>
        <p className="text-xl mb-6">
          African Union Youth Leadership Program - Empowering the next generation of African leaders
        </p>
        <div className="space-x-4">
          <Link 
            to="/about"
            className="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block"
          >
            Learn More
          </Link>
          <Link 
            to="/organizations"
            className="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors inline-block"
          >
            Get Involved
          </Link>
        </div>
      </section>

      {/* Featured Content */}
      <FeaturedContent />

      {/* Quick Stats */}
      <section className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="bg-white rounded-lg shadow-md p-6 text-center">
          <div className="text-3xl font-bold text-blue-600 mb-2">500+</div>
          <div className="text-gray-600">Young Leaders</div>
        </div>
        <div className="bg-white rounded-lg shadow-md p-6 text-center">
          <div className="text-3xl font-bold text-green-600 mb-2">50+</div>
          <div className="text-gray-600">Organizations</div>
        </div>
        <div className="bg-white rounded-lg shadow-md p-6 text-center">
          <div className="text-3xl font-bold text-purple-600 mb-2">25</div>
          <div className="text-gray-600">Countries</div>
        </div>
        <div className="bg-white rounded-lg shadow-md p-6 text-center">
          <div className="text-3xl font-bold text-orange-600 mb-2">100+</div>
          <div className="text-gray-600">Events</div>
        </div>
      </section>

      {/* About Section */}
      <section className="bg-gray-50 rounded-lg p-8">
        <h2 className="text-3xl font-bold text-center mb-6">About AU-VLP</h2>
        <div className="max-w-3xl mx-auto text-center">
          <p className="text-lg text-gray-700 mb-4">
            The African Union Youth Leadership Program is designed to develop the next generation 
            of African leaders through comprehensive training, networking, and mentorship opportunities.
          </p>
          <p className="text-lg text-gray-700 mb-6">
            Join us in building a stronger, more connected Africa through youth empowerment and leadership development.
          </p>
          <Link 
            to="/about"
            className="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors inline-block"
          >
            Learn More About Us
          </Link>
        </div>
      </section>
    </div>
  );
};

export default Home;