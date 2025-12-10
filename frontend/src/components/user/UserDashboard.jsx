import React, { useState } from "react";
import {
  Briefcase,
  MapPin,
  ArrowRight,
  Star,
  Users,
  Globe,
  DollarSign,
  LineChart,
  CreditCard,
  Share2,
  Plane,
  Facebook,
  Twitter,
  Instagram,
  Linkedin,
  X,
  Calendar,
  Users as UsersIcon,
  CheckCircle,
  Clock,
} from "lucide-react";

// Dropdown options
const destinationOptions = [
  "Select a destination",
  "Tokyo, Japan",
  "Paris, France",
  "Bali, Indonesia",
  "New York, USA",
  "Sydney, Australia",
  "Rome, Italy",
  "Bangkok, Thailand",
  "Dubai, UAE",
];

const durationOptions = [
  "Select duration",
  "3-5 days",
  "6-8 days",
  "9-12 days",
  "2+ weeks",
];

const travelStyleOptions = [
  "Select travel style",
  "Luxury & Comfort",
  "Budget & Adventure",
  "Family Friendly",
  "Solo Travel",
  "Romantic Getaway",
  "Cultural Experience",
];

// Mock Data
const recentAdventures = [
  {
    title: "Mountain Adventure in Colorado",
    description:
      "Experience the breathtaking beauty of Colorado mountains with guided hiking trips and camping.",
    price: "₱1,200",
    duration: "5 days",
    distance: "300mi",
    tags: ["Hiking", "Camping", "Photography"],
    borderColor: "border-green-500",
  },
  {
    title: "Beach Paradise in Maldives",
    description:
      "Dive into crystal clear waters, explore vibrant coral reefs, and relax in overwater bungalows.",
    price: "₱2,500",
    duration: "7 days",
    distance: "8500mi",
    tags: ["Swimming", "Snorkeling", "Island Hopping"],
    borderColor: "border-blue-500",
  },
];

const upcomingTrips = [
  {
    destination: "Tokyo, Japan",
    dates: "Oct 15-22, 2025",
    travelers: "2 travelers",
    budget: "₱2,800",
    spent: "₱1,200",
    status: "Confirmed",
    progress: 43,
    imageClass:
      "bg-[url('https://images.unsplash.com/photo-1542051841857-5f90071e7989?auto=format&fit=crop&w=800&q=60')]",
  },
  {
    destination: "Paris, France",
    dates: "Nov 10-17, 2025",
    travelers: "1 traveler",
    budget: "₱3,200",
    spent: "₱0",
    status: "Planning",
    progress: 15,
    imageClass:
      "bg-[url('https://images.unsplash.com/photo-1502602898657-3e91760c0341?auto=format&fit=crop&w=800&q=60')]",
  },
];

const statsCards = [
  {
    icon: Briefcase,
    number: "23",
    label: "Total Trips",
    change: "+2 from last month",
    color: "text-cyan-500",
  },
  {
    icon: DollarSign,
    number: "₱12,450",
    label: "This Year Spent",
    change: "+15% from last year",
    color: "text-green-500",
  },
  {
    icon: Globe,
    number: "12",
    label: "Countries Visited",
    change: "3 new this year",
    color: "text-purple-500",
  },
  {
    icon: LineChart,
    number: "₱2,100",
    label: "Savings",
    change: "Through bulk discounts",
    color: "text-orange-500",
  },
];

// Travel Plans Modal Component
const TravelPlansModal = ({ isOpen, onClose, userSelection }) => {
  if (!isOpen) return null;

  const { destination, duration, travelStyle } = userSelection;

  // Mock travel plans based on user input
  const generatePlans = (input) => {
    if (
      input.destination === "Select a destination" ||
      input.duration === "Select duration" ||
      input.travelStyle === "Select travel style"
    ) {
      return [];
    }

    return [
      {
        id: 1,
        title: `Premium ${input.destination} Experience`,
        description: `Based on your selection of ${input.travelStyle.toLowerCase()} for ${input.duration.toLowerCase()}, we've crafted an exclusive itinerary.`,
        duration: "7 days",
        price: "$1,899",
        highlights: [
          "Private Guided Tours",
          "Luxury Accommodation",
          "All Meals Included",
          "VIP Access",
        ],
        rating: 4.8,
        bestFor: "Couples & Luxury Travelers",
        inclusions: ["Flights", "Hotels", "Transfers", "Activities"],
      },
      {
        id: 2,
        title: `Budget-Friendly ${input.destination} Adventure`,
        description:
          "Perfect for travelers looking for an authentic experience without breaking the bank.",
        duration: "5 days",
        price: "$899",
        highlights: [
          "Local Homestay",
          "Group Activities",
          "Public Transport",
          "Street Food Tours",
        ],
        rating: 4.5,
        bestFor: "Backpackers & Students",
        inclusions: [
          "Budget Hotels",
          "Some Meals",
          "Local Guides",
          "Entry Fees",
        ],
      },
      {
        id: 3,
        title: `Family ${input.destination} Getaway`,
        description:
          "Kid-friendly activities and comfortable accommodations for the whole family.",
        duration: "8 days",
        price: "$2,499",
        highlights: [
          "Family Suites",
          "Childcare Services",
          "Educational Tours",
          "Theme Park Visits",
        ],
        rating: 4.9,
        bestFor: "Families with Children",
        inclusions: [
          "Family Accommodation",
          "All Meals",
          "Child Activities",
          "Insurance",
        ],
      },
    ];
  };

  const plans = generatePlans(userSelection);

  return (
    <div className="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4">
      <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        {/* Modal Header */}
        <div className="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
          <div>
            <h2 className="text-2xl font-bold flex items-center gap-2">
              <Star className="text-yellow-500" size={24} />
              Your Personalized Travel Plans
            </h2>
            <p className="text-gray-600 dark:text-gray-400 mt-1">
              Based on your selection: {destination} for {duration} (
              {travelStyle})
            </p>
          </div>
          <button
            onClick={onClose}
            className="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition"
          >
            <X size={24} />
          </button>
        </div>

        {/* Modal Body */}
        <div className="p-6 overflow-y-auto max-h-[70vh]">
          {plans.length === 0 ? (
            <div className="text-center py-12">
              <MapPin size={48} className="mx-auto text-gray-400 mb-4" />
              <h3 className="text-xl font-semibold mb-2">
                No Plans Generated Yet
              </h3>
              <p className="text-gray-600 dark:text-gray-400">
                Please select all options to see personalized plans.
              </p>
            </div>
          ) : (
            <div className="space-y-6">
              {plans.map((plan) => (
                <div
                  key={plan.id}
                  className="border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:shadow-lg transition-shadow"
                >
                  <div className="flex justify-between items-start mb-4">
                    <div>
                      <h3 className="text-xl font-bold mb-2">{plan.title}</h3>
                      <p className="text-gray-600 dark:text-gray-400 mb-3">
                        {plan.description}
                      </p>
                    </div>
                    <div className="text-right">
                      <div className="text-2xl font-bold text-cyan-600">
                        {plan.price}
                      </div>
                      <div className="text-sm text-gray-500">
                        {plan.duration}
                      </div>
                    </div>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                      <h4 className="font-semibold mb-2 flex items-center gap-2">
                        <CheckCircle size={16} className="text-green-500" />
                        Highlights
                      </h4>
                      <ul className="space-y-1">
                        {plan.highlights.map((highlight, idx) => (
                          <li
                            key={idx}
                            className="flex items-center gap-2 text-sm"
                          >
                            <div className="w-1.5 h-1.5 bg-cyan-500 rounded-full"></div>
                            {highlight}
                          </li>
                        ))}
                      </ul>
                    </div>
                    <div>
                      <h4 className="font-semibold mb-2 flex items-center gap-2">
                        <CheckCircle size={16} className="text-green-500" />
                        What's Included
                      </h4>
                      <ul className="space-y-1">
                        {plan.inclusions.map((inclusion, idx) => (
                          <li
                            key={idx}
                            className="flex items-center gap-2 text-sm"
                          >
                            <div className="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                            {inclusion}
                          </li>
                        ))}
                      </ul>
                    </div>
                  </div>

                  <div className="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div className="flex items-center gap-4">
                      <div className="flex items-center gap-1">
                        <Star
                          size={16}
                          className="text-yellow-500 fill-current"
                        />
                        <span className="font-semibold">{plan.rating}/5</span>
                      </div>
                      <div className="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <UsersIcon size={16} />
                        <span>Best for: {plan.bestFor}</span>
                      </div>
                    </div>
                    <div className="flex gap-3">
                      <button className="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Save for Later
                      </button>
                      <button className="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition flex items-center gap-2">
                        <Calendar size={16} />
                        Book Now
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>

        {/* Modal Footer */}
        <div className="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
          <div className="flex justify-between items-center">
            <div className="text-sm text-gray-600 dark:text-gray-400">
              <Clock size={16} className="inline mr-1" />
              Plans are generated in real-time based on your preferences
            </div>
            <div className="flex gap-3">
              <button
                onClick={onClose}
                className="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
              >
                Close
              </button>
              <button className="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition flex items-center gap-2">
                <Share2 size={16} />
                Share Plans
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

// Main Component
const UserDashboard = () => {
  const [view, setView] = useState("landing");
  const [showPlansModal, setShowPlansModal] = useState(false);

  // Simple form state with dropdowns
  const [destination, setDestination] = useState("Select a destination");
  const [duration, setDuration] = useState("Select duration");
  const [travelStyle, setTravelStyle] = useState("Select travel style");

  // Simple form handler
  const handleSubmit = (e) => {
    e.preventDefault();

    // Check if all fields are selected
    if (
      destination !== "Select a destination" &&
      duration !== "Select duration" &&
      travelStyle !== "Select travel style"
    ) {
      setShowPlansModal(true);
    } else {
      alert("Please select all options to see travel plans!");
    }
  };

  const LandingPageContent = () => (
    <div>
      <section className="text-center py-20 px-6 bg-gray-50 dark:bg-gray-900">
        <h1 className="text-5xl md:text-6xl font-extrabold mb-4">
          Discover Your Next
          <br />
          <span className="text-cyan-500">Epic</span>{" "}
          <span className="text-orange-400">Adventure</span>
        </h1>
        <p className="max-w-3xl mx-auto text-lg text-gray-600 dark:text-gray-300 mb-8">
          Unlock intelligent travel planning with AI-powered recommendations,
          seamless collaboration, and cost optimization. Turn your wanderlust
          into unforgettable journeys.
        </p>
        <div className="flex justify-center gap-4 mb-10 flex-wrap">
          <button
            onClick={() => {
              if (
                destination !== "Select a destination" &&
                duration !== "Select duration" &&
                travelStyle !== "Select travel style"
              ) {
                setShowPlansModal(true);
              } else {
                alert("Please select all options first!");
              }
            }}
            className="bg-cyan-600 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:bg-cyan-700 transition flex items-center gap-2"
          >
            <Plane size={18} />
            Start Your Journey <ArrowRight size={18} />
          </button>
          <button className="bg-white dark:bg-gray-800 text-gray-800 dark:text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition flex items-center gap-2">
            <MapPin size={18} />
            Explore Features
          </button>
        </div>
        <div className="flex justify-center gap-8 text-gray-600 dark:text-gray-300 flex-wrap">
          <div className="flex items-center gap-2">
            <Star size={18} className="text-yellow-400" />
            <span>4.9/5 Rating</span>
          </div>
          <div className="flex items-center gap-2">
            <Users size={18} className="text-green-400" />
            <span>50K+ Travelers</span>
          </div>
          <div className="flex items-center gap-2">
            <Globe size={18} className="text-blue-400" />
            <span>190+ Countries</span>
          </div>
        </div>
      </section>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 p-8 max-w-7xl mx-auto">
        <section className="lg:col-span-2 bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg">
          <h2 className="text-2xl font-bold mb-2 flex items-center">
            <Star size={22} className="text-yellow-400 mr-2" />
            Plan Your Dream Journey
          </h2>
          <p className="text-gray-500 dark:text-gray-400 mb-6">
            Select your preferences, and we'll craft the perfect adventure
            tailored for you!
          </p>
          <form onSubmit={handleSubmit} className="space-y-6">
            {/* Destination Dropdown */}
            <div>
              <label className="block text-gray-700 dark:text-gray-300 mb-2">
                <MapPin size={18} className="inline mr-2" />
                Destination
              </label>
              <select
                value={destination}
                onChange={(e) => setDestination(e.target.value)}
                className="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
              >
                {destinationOptions.map((option, index) => (
                  <option key={index} value={option}>
                    {option}
                  </option>
                ))}
              </select>
            </div>

            {/* Duration Dropdown */}
            <div>
              <label className="block text-gray-700 dark:text-gray-300 mb-2">
                <Briefcase size={18} className="inline mr-2" />
                Trip Duration
              </label>
              <select
                value={duration}
                onChange={(e) => setDuration(e.target.value)}
                className="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
              >
                {durationOptions.map((option, index) => (
                  <option key={index} value={option}>
                    {option}
                  </option>
                ))}
              </select>
            </div>

            {/* Travel Style Dropdown */}
            <div>
              <label className="block text-gray-700 dark:text-gray-300 mb-2">
                <Users size={18} className="inline mr-2" />
                Travel Style
              </label>
              <select
                value={travelStyle}
                onChange={(e) => setTravelStyle(e.target.value)}
                className="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
              >
                {travelStyleOptions.map((option, index) => (
                  <option key={index} value={option}>
                    {option}
                  </option>
                ))}
              </select>
            </div>

            <button
              type="submit"
              className="w-full bg-orange-500 text-white font-bold py-3 rounded-lg hover:bg-orange-600 transition flex items-center justify-center gap-2"
            >
              <Star size={20} />
              Begin My Adventure
            </button>
          </form>
        </section>
        <aside>
          <div className="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
            <h3 className="text-xl font-bold mb-4 flex items-center">
              <MapPin size={20} className="text-cyan-500 mr-2" />
              Recent Adventures
            </h3>
            <div className="space-y-4">
              {recentAdventures.map((adv, index) => (
                <div
                  key={index}
                  className={`border-l-4 ${adv.borderColor} pl-4`}
                >
                  <h4 className="font-bold">{adv.title}</h4>
                  <p className="text-sm text-gray-500 dark:text-gray-400 mb-2">
                    {adv.description}
                  </p>
                  <div className="flex justify-between text-xs font-semibold text-gray-600 dark:text-gray-300">
                    <span>{adv.price}</span>
                    <span>{adv.duration}</span>
                    <span>{adv.distance}</span>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </aside>
      </div>
    </div>
  );

  const DashboardContent = () => (
    <div className="p-8 max-w-7xl mx-auto">
      <h1 className="text-3xl font-bold mb-2">Travel Dashboard</h1>
      <p className="text-gray-500 dark:text-gray-400 mb-8">
        Manage your trips, bookings, and travel preferences in one place.
      </p>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {statsCards.map((card, index) => (
          <div
            key={index}
            className="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md flex items-center gap-5"
          >
            <div className={`text-3xl ${card.color}`}>
              {React.createElement(card.icon, { size: 32 })}
            </div>
            <div>
              <div className="text-2xl font-bold">{card.number}</div>
              <div className="text-gray-500 dark:text-gray-400">
                {card.label}
              </div>
              <div className="text-xs text-green-500 font-semibold">
                {card.change}
              </div>
            </div>
          </div>
        ))}
      </div>

      <h2 className="text-2xl font-bold mb-6">Upcoming Trips</h2>
      <div className="space-y-6">
        {upcomingTrips.map((trip, index) => (
          <div
            key={index}
            className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg flex flex-col md:flex-row overflow-hidden"
          >
            <div
              className={`w-full md:w-1/3 h-48 md:h-auto bg-cover bg-center ${trip.imageClass}`}
            ></div>
            <div className="flex-1 p-6">
              <h3 className="text-xl font-bold">{trip.destination}</h3>
              <p className="text-gray-500 dark:text-gray-400 mb-4">
                {trip.dates}
              </p>
              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm mb-4">
                <div className="flex items-center gap-2">
                  <Users size={16} />
                  <span>{trip.travelers}</span>
                </div>
                <div className="flex items-center gap-2">
                  <DollarSign size={16} />
                  <span>Budget: {trip.budget}</span>
                </div>
                <div className="flex items-center gap-2">
                  <CreditCard size={16} />
                  <span>Spent: {trip.spent}</span>
                </div>
              </div>
              <div className="mb-2 text-sm">
                Trip Planning Progress {trip.progress}%
              </div>
              <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-4">
                <div
                  className="bg-cyan-600 h-2.5 rounded-full"
                  style={{ width: `${trip.progress}%` }}
                ></div>
              </div>
              <div className="flex gap-4 flex-wrap">
                <button className="bg-cyan-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-cyan-700 transition">
                  View Journey
                </button>
                <button className="bg-gray-200 dark:bg-gray-700 font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
                  <Share2 size={16} />
                  Share Trip
                </button>
              </div>
            </div>
            <div
              className={`p-4 flex items-center justify-center font-bold ${
                trip.status === "Confirmed"
                  ? "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                  : "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200"
              }`}
            >
              {trip.status}
            </div>
          </div>
        ))}
      </div>
    </div>
  );

  const Footer = () => (
    <footer className="bg-gray-800 text-gray-300 mt-12">
      <div className="max-w-7xl mx-auto px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <h3 className="text-lg font-bold mb-4">NaviGo</h3>
            <p className="text-sm">
              Your trusted partner for unforgettable travel experiences around
              the world.
            </p>
          </div>
          <div>
            <h4 className="font-bold mb-4">Quick Links</h4>
            <ul className="space-y-2 text-sm">
              <li>
                <a href="#" className="hover:text-white">
                  About Us
                </a>
              </li>
              <li>
                <a href="#" className="hover:text-white">
                  Contact
                </a>
              </li>
              <li>
                <a href="#" className="hover:text-white">
                  Support
                </a>
              </li>
              <li>
                <a href="#" className="hover:text-white">
                  FAQ
                </a>
              </li>
            </ul>
          </div>
          <div>
            <h4 className="font-bold mb-4">Legal</h4>
            <ul className="space-y-2 text-sm">
              <li>
                <a href="#" className="hover:text-white">
                  Privacy Policy
                </a>
              </li>
              <li>
                <a href="#" className="hover:text-white">
                  Terms of Service
                </a>
              </li>
              <li>
                <a href="#" className="hover:text-white">
                  Cookie Policy
                </a>
              </li>
            </ul>
          </div>
          <div>
            <h4 className="font-bold mb-4">Connect</h4>
            <div className="flex space-x-4">
              <a href="#" className="hover:text-white">
                <Facebook size={20} />
              </a>
              <a href="#" className="hover:text-white">
                <Twitter size={20} />
              </a>
              <a href="#" className="hover:text-white">
                <Instagram size={20} />
              </a>
              <a href="#" className="hover:text-white">
                <Linkedin size={20} />
              </a>
            </div>
          </div>
        </div>
      </div>
      <div className="text-center py-4 border-t border-gray-700 text-sm">
        © 2025 NaviGo Adventure Travels. All rights reserved.
      </div>
    </footer>
  );

  return (
    <div className="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen font-sans">
      <TravelPlansModal
        isOpen={showPlansModal}
        onClose={() => setShowPlansModal(false)}
        userSelection={{ destination, duration, travelStyle }}
      />

      <div className="flex justify-center py-6">
        <div className="bg-gray-200 dark:bg-gray-700 p-1 rounded-lg flex">
          <button
            onClick={() => setView("landing")}
            className={`px-6 py-2 rounded-md font-semibold ${
              view === "landing" ? "bg-white dark:bg-gray-800 shadow" : ""
            }`}
          >
            Landing Page
          </button>
          <button
            onClick={() => setView("dashboard")}
            className={`px-6 py-2 rounded-md font-semibold ${
              view === "dashboard" ? "bg-white dark:bg-gray-800 shadow" : ""
            }`}
          >
            Dashboard
          </button>
        </div>
      </div>
      <main>
        {view === "landing" ? <LandingPageContent /> : <DashboardContent />}
      </main>
      <Footer />
    </div>
  );
};

export default UserDashboard;
