import React from 'react';
import { MoveRight, Compass, Map, Wallet } from 'lucide-react';

const LandingPage = () => {
  const startJourney = () => {
    document.getElementById('adventure-form').scrollIntoView({ behavior: 'smooth' });
  };

  const exploreFeatures = () => {
    alert('Explore our amazing features!\n• Smart trip planning\n• Real-time updates\n• Budget tracking\n• Social sharing');
  };

  return (
    <div className="bg-gray-100 text-gray-800">
      {/* Hero Section */}
      <header className="relative h-screen bg-cover bg-center" style={{ backgroundImage: "url('https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?q=80&w=2070&auto=format&fit=crop')" }}>
        <div className="absolute inset-0 bg-black opacity-50"></div>
        <div className="relative z-10 flex flex-col items-center justify-center h-full text-white">
          <h1 className="text-5xl font-bold mb-4">Your Adventure Awaits</h1>
          <p className="text-xl mb-8">Discover and plan your next journey with NaviGo.</p>
          <div className="flex space-x-4">
            <button onClick={startJourney} className="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-full flex items-center">
              Start Your Journey <MoveRight className="ml-2" />
            </button>
            <button onClick={exploreFeatures} className="bg-transparent border-2 border-white text-white font-bold py-2 px-6 rounded-full">
              Explore Features
            </button>
          </div>
        </div>
      </header>

      {/* Features Section */}
      <section className="py-20">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold text-center mb-12">Features</h2>
          <div className="grid md:grid-cols-3 gap-8">
            <div className="bg-white p-8 rounded-lg shadow-md text-center">
              <Compass size={48} className="mx-auto text-blue-500 mb-4" />
              <h3 className="text-xl font-bold mb-2">Smart Trip Planning</h3>
              <p>Effortlessly plan your trips with our intelligent routing and suggestion engine.</p>
            </div>
            <div className="bg-white p-8 rounded-lg shadow-md text-center">
              <Map size={48} className="mx-auto text-blue-500 mb-4" />
              <h3 className="text-xl font-bold mb-2">Real-time Updates</h3>
              <p>Stay informed with live updates on your travel plans and destinations.</p>
            </div>
            <div className="bg-white p-8 rounded-lg shadow-md text-center">
              <Wallet size={48} className="mx-auto text-blue-500 mb-4" />
              <h3 className="text-xl font-bold mb-2">Budget Tracking</h3>
              <p>Keep track of your expenses and manage your travel budget with ease.</p>
            </div>
          </div>
        </div>
      </section>

      {/* Adventure Form Section */}
      <section id="adventure-form" className="py-20 bg-gray-200">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold text-center mb-8">Plan Your Adventure</h2>
          <form className="max-w-xl mx-auto">
            <div className="mb-4">
              <label htmlFor="destination" className="block text-gray-700 font-bold mb-2">Destination</label>
              <input type="text" id="destination" className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="e.g., Paris, France" />
            </div>
            <div className="mb-4">
              <label htmlFor="dates" className="block text-gray-700 font-bold mb-2">Dates</label>
              <input type="date" id="dates" className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div className="text-center">
              <button type="submit" className="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-full">
                Create My Trip
              </button>
            </div>
          </form>
        </div>
      </section>


      {/* Footer */}
      <footer className="bg-gray-800 text-white py-8">
        <div className="container mx-auto px-4 flex justify-between items-center">
          <span>© 2025 NaviGo Adventure Travels. All rights reserved.</span>
          <div>
            <a href="#" className="hover:text-gray-400 mx-2">Privacy Policy</a>
            <a href="#" className="hover:text-gray-400 mx-2">Terms of Service</a>
            <a href="#" className="hover:text-gray-400 mx-2">Cookie Policy</a>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default LandingPage;
