import React, { useState } from "react";
import {
  Briefcase,
  Compass,
  Clock,
  MapPin,
  Moon,
  Bell,
  ChevronDown,
  User,
  Crown,
  Settings,
  HelpCircle,
  LogOut,
  Plus,
  Search,
  SlidersHorizontal,
  Star,
  Share2,
  MoreVertical,
  Plane,
  DollarSign,
  BarChart,
  Users,
} from "lucide-react";
import { Link } from "react-router-dom";
import CreateTripModal from "./CreateTripModal";

const tripsData = [
  {
    id: 1,
    destination: "Tokyo, Japan",
    country: "Japan",
    dates: "Oct 15-22, 2025",
    duration: "8 days",
    travelers: 2,
    budget: "$2,800",
    spent: "$1,200",
    progress: 85,
    status: "Confirmed",
    rating: "4.8",
    reviews: "1240",
    image:
      "https://images.unsplash.com/photo-1542051841857-5f90071e7989?auto=format&fit=crop&w=800&q=60",
    highlights: ["Cherry Blossom", "Temples", "Cuisine"],
  },
  {
    id: 2,
    destination: "Paris, France",
    country: "France",
    dates: "Nov 10-17, 2025",
    duration: "7 days",
    travelers: 1,
    budget: "$3,200",
    spent: "$450",
    progress: 35,
    status: "Planning",
    rating: "4.9",
    reviews: "2150",
    image:
      "https://images.unsplash.com/photo-1502602898657-3e91760c0341?auto=format&fit=crop&w=800&q=60",
    highlights: ["Art Museums", "Fine Dining", "Architecture"],
  },
  {
    id: 3,
    destination: "New York, USA",
    country: "USA",
    dates: "Dec 5-8, 2025",
    duration: "4 days",
    travelers: 1,
    budget: "$1,800",
    spent: "$1,800",
    progress: 100,
    status: "Business",
    rating: "4.7",
    reviews: "890",
    image:
      "https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?auto=format&fit=crop&w=800&q=60",
    highlights: ["Conference", "Networking", "Central Park"],
  },
];

const summaryData = [
  {
    icon: Plane,
    title: "Total Trips",
    value: "3",
    color: "bg-blue-100 text-blue-600",
  },
  {
    icon: DollarSign,
    title: "Total Budget",
    value: "$7,800",
    color: "bg-green-100 text-green-600",
  },
  {
    icon: MapPin,
    title: "Countries",
    value: "3",
    color: "bg-purple-100 text-purple-600",
  },
  {
    icon: BarChart,
    title: "Avg Progress",
    value: "73%",
    color: "bg-orange-100 text-orange-600",
  },
];

const statusColors = {
  Confirmed: "bg-green-100 text-green-800",
  Planning: "bg-yellow-100 text-yellow-800",
  Business: "bg-indigo-100 text-indigo-800",
};

const TripCard = ({ trip }) => (
  <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
    <div className="relative">
      <img
        src={trip.image}
        alt={trip.destination}
        className="w-full h-48 object-cover"
      />
      <div
        className={`absolute top-3 left-3 text-xs font-bold px-2 py-1 rounded-full ${
          statusColors[trip.status]
        }`}
      >
        {trip.status}
      </div>
      <button className="absolute top-3 right-3 w-8 h-8 rounded-full flex items-center justify-center bg-white/80 backdrop-blur-sm text-gray-700">
        <MoreVertical size={18} />
      </button>
    </div>
    <div className="p-5">
      <h3 className="text-xl font-bold truncate">{trip.destination}</h3>
      <div className="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
        <MapPin size={14} className="mr-1" /> {trip.country}
      </div>
      <div className="flex justify-between items-center text-sm mb-3">
        <div className="flex items-center font-semibold">
          <Star
            size={16}
            className="text-yellow-400 mr-1"
            fill="currentColor"
          />
          {trip.rating}{" "}
          <span className="text-gray-500 font-normal ml-1">
            ({trip.reviews})
          </span>
        </div>
        <div className="text-gray-500 font-medium">{trip.duration}</div>
      </div>
      <div className="text-sm text-gray-600 dark:text-gray-300 space-y-1 mb-4">
        <p>
          <span className="font-semibold">Dates:</span> {trip.dates}
        </p>
        <p>
          <span className="font-semibold">Travelers:</span> {trip.travelers}
        </p>
      </div>
      <div className="flex flex-wrap gap-2 mb-4">
        {trip.highlights.map((tag) => (
          <span
            key={tag}
            className="bg-gray-100 dark:bg-gray-700 text-xs font-medium px-2 py-1 rounded-full"
          >
            {tag}
          </span>
        ))}
      </div>
      <div className="flex justify-between items-center font-semibold text-gray-700 dark:text-gray-200 mb-4">
        <div>
          <p className="text-xs font-normal text-gray-500">Budget</p>
          {trip.budget}
        </div>
        <div className="text-right">
          <p className="text-xs font-normal text-gray-500">Spent</p>
          {trip.spent}
        </div>
      </div>
      <div className="flex gap-2">
        <Link
          to={`/my-trips/${trip.id}`}
          className="w-full bg-cyan-600 text-white font-semibold py-2 px-3 rounded-lg hover:bg-cyan-700 transition text-sm"
        >
          View Details
        </Link>
        <button className="p-2 bg-gray-200 dark:bg-gray-700 rounded-lg">
          <Share2 size={16} />
        </button>
      </div>
    </div>
  </div>
);

const MyTrips = () => {
  const [profileOpen, setProfileOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState("");
  const [filter, setFilter] = useState("all");
  const [showCreateModal, setShowCreateModal] = useState(false);

  const filteredTrips = tripsData.filter((trip) => {
    const matchesSearch =
      trip.destination.toLowerCase().includes(searchTerm.toLowerCase()) ||
      trip.highlights.some((h) =>
        h.toLowerCase().includes(searchTerm.toLowerCase())
      );
    const matchesFilter =
      filter === "all" || trip.status.toLowerCase() === filter;
    return matchesSearch && matchesFilter;
  });

  return (
    <div className="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen font-sans">
      <main className="p-6 lg:p-10">
        <div className="max-w-7xl mx-auto">
          <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
              <h1 className="text-4xl font-bold">My Trips</h1>
              <p className="text-gray-500 dark:text-gray-400 mt-2">
                Your smart travel companion for managing all adventures.
              </p>
            </div>
            <button
              onClick={() => setShowCreateModal(true)} // Open modal
              className="mt-4 md:mt-0 flex items-center gap-2 bg-cyan-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-cyan-700 transition"
            >
              <Plus size={20} />
              Create New Trip
            </button>
          </div>

          <div className="mb-8 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md flex flex-col md:flex-row gap-4">
            <div className="relative flex-grow">
              <Search
                className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                size={20}
              />
              <input
                type="text"
                placeholder="Search trips..."
                className="w-full pl-12 pr-4 py-3 bg-gray-100 dark:bg-gray-700 border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
                onChange={(e) => setSearchTerm(e.target.value)}
              />
            </div>
            <select
              className="bg-gray-100 dark:bg-gray-700 border-transparent rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-cyan-500"
              onChange={(e) => setFilter(e.target.value)}
            >
              <option value="all">All Trips</option>
              <option value="confirmed">Confirmed</option>
              <option value="planning">Planning</option>
              <option value="business">Business</option>
            </select>
            <button className="flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold">
              <SlidersHorizontal size={16} />
              More Filters
            </button>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            {summaryData.map((item) => (
              <div
                key={item.title}
                className="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md flex items-center gap-4"
              >
                <div className={`p-3 rounded-full ${item.color}`}>
                  <item.icon size={24} />
                </div>
                <div>
                  <div className="text-gray-500 dark:text-gray-400 text-sm">
                    {item.title}
                  </div>
                  <div className="text-2xl font-bold">{item.value}</div>
                </div>
              </div>
            ))}
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {filteredTrips.map((trip) => (
              <TripCard key={trip.id} trip={trip} />
            ))}
          </div>
          {filteredTrips.length === 0 && (
            <div className="text-center py-20 col-span-full">
              <h3 className="text-2xl font-semibold">
                No Matching Trips Found
              </h3>
              <p className="text-gray-500">Try a different search or filter.</p>
            </div>
          )}
        </div>
      </main>

      <CreateTripModal
        isOpen={showCreateModal}
        onClose={() => setShowCreateModal(false)}
      />
    </div>
  );
};

export default MyTrips;
