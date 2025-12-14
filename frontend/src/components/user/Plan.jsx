import React, { useState, useEffect, useContext } from "react";
import {
  MapPin,
  Plane,
  Building,
  Search,
  Star,
  Users,
  Calendar,
  ArrowLeft,
  Filter,
  ChevronDown,
} from "lucide-react";
import { AuthContext } from "../../context/AuthContext";
import BusinessSchedulesModal from "./BusinessSchedulesModal";
import { getAllBusinesses } from "../../services/businessService";

const Plan = () => {
  const { user } = useContext(AuthContext);
  const [searchTerm, setSearchTerm] = useState("");
  const [filterType, setFilterType] = useState("all");
  const [businesses, setBusinesses] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [selectedBusiness, setSelectedBusiness] = useState(null);
  const [showSchedulesModal, setShowSchedulesModal] = useState(false);

  useEffect(() => {
    fetchBusinesses();
  }, []);

  const fetchBusinesses = async () => {
    setLoading(true);
    setError(null);
    try {
      const data = await getAllBusinesses();

      // Format the API data to include additional information
      const formattedBusinesses = data.map((business) => ({
        _id: business._id,
        business_name: business.business_name,
        business_type: business.business_type,
        description: getBusinessDescription(business.business_type),
        rating: getRandomRating(3.5, 5.0),
        routes: getRandomRoutes(business.business_type),
        aircraft_count: getRandomAircraftCount(business.business_type),
        location: business.location || getDefaultLocation(),
        established: getEstablishedYear(business.created_at),
        logo: getBusinessLogo(business.business_name),
        schedules_count: getRandomSchedulesCount(business.business_type),
        phone: business.phone,
        email: business.email,
      }));

      setBusinesses(formattedBusinesses);
    } catch (err) {
      console.error("Error fetching businesses:", err);
      setError("Failed to load businesses. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  // Helper functions to enrich API data
  const getBusinessDescription = (type) => {
    const descriptions = {
      Airlines: "Premium flight services with best-in-class experience",
      Hotel: "Comfortable accommodations with excellent amenities",
      Rental: "Reliable vehicle rentals for your travel needs",
      "Travel Agency": "Complete travel packages and planning services",
    };
    return descriptions[type] || "Professional travel service provider";
  };

  const getRandomRating = (min, max) => {
    return parseFloat((Math.random() * (max - min) + min).toFixed(1));
  };

  const getRandomRoutes = (type) => {
    const baseRoutes = {
      Airlines: Math.floor(Math.random() * 15) + 5,
      Hotel: Math.floor(Math.random() * 10) + 3,
      Rental: Math.floor(Math.random() * 8) + 2,
      "Travel Agency": Math.floor(Math.random() * 20) + 10,
    };
    return baseRoutes[type] || Math.floor(Math.random() * 10) + 3;
  };

  const getRandomAircraftCount = (type) => {
    if (type === "Airlines") {
      return Math.floor(Math.random() * 15) + 3;
    }
    return 0;
  };

  const getDefaultLocation = () => {
    const locations = ["Manila", "Cebu", "Davao", "Boracay", "Clark"];
    return (
      locations[Math.floor(Math.random() * locations.length)] + ", Philippines"
    );
  };

  const getEstablishedYear = (createdAt) => {
    if (!createdAt) return 2020;
    const date = new Date(createdAt);
    return date.getFullYear();
  };

  const getBusinessLogo = (name) => {
    // Get first two letters of business name
    return name.substring(0, 2).toUpperCase();
  };

  const getRandomSchedulesCount = (type) => {
    const baseCount = {
      Airlines: Math.floor(Math.random() * 30) + 10,
      Hotel: Math.floor(Math.random() * 50) + 20,
      Rental: Math.floor(Math.random() * 40) + 15,
      "Travel Agency": Math.floor(Math.random() * 60) + 25,
    };
    return baseCount[type] || Math.floor(Math.random() * 30) + 10;
  };

  const filteredBusinesses = businesses.filter((business) => {
    const matchesSearch =
      business.business_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      (business.location &&
        business.location.toLowerCase().includes(searchTerm.toLowerCase())) ||
      business.description.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesType =
      filterType === "all" ||
      business.business_type.toLowerCase() === filterType.toLowerCase();
    return matchesSearch && matchesType;
  });

  const handleViewSchedules = (business) => {
    setSelectedBusiness(business);
    setShowSchedulesModal(true);
  };

  const renderStars = (rating) => {
    return (
      <div className="flex items-center">
        {[...Array(5)].map((_, i) => (
          <Star
            key={i}
            size={14}
            className={`mr-0.5 ${
              i < Math.floor(rating)
                ? "text-yellow-500 fill-yellow-500"
                : "text-gray-300"
            }`}
          />
        ))}
        <span className="ml-1 text-sm text-gray-600 dark:text-gray-400">
          {rating}
        </span>
      </div>
    );
  };

  if (loading) {
    return (
      <div className="bg-gray-50 dark:bg-gray-900 min-h-screen flex justify-center items-center">
        <div className="w-10 h-10 border-2 border-cyan-600 border-t-transparent rounded-full animate-spin"></div>
      </div>
    );
  }

  return (
    <div className="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen font-sans">
      <main className="p-6 lg:p-10">
        <div className="max-w-7xl mx-auto">
          {/* Header */}
          <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
              <h1 className="text-4xl font-bold">Browse Travel Businesses</h1>
              <p className="text-gray-500 dark:text-gray-400 mt-2">
                Discover and book from verified travel partners
              </p>
              {error && (
                <div className="mt-2 p-3 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 rounded-lg">
                  {error}
                </div>
              )}
            </div>
            <button className="mt-4 md:mt-0 flex items-center gap-2 text-sm font-semibold text-cyan-600 hover:text-cyan-700 transition">
              <ArrowLeft size={16} />
              Back to Dashboard
            </button>
          </div>

          {/* Search and Filter */}
          <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8">
            <div className="flex flex-col md:flex-row gap-4">
              <div className="relative flex-grow">
                <Search
                  className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                  size={20}
                />
                <input
                  type="text"
                  placeholder="Search businesses by name, location, or description..."
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  className="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
                />
              </div>
              <div className="flex gap-3">
                <select
                  value={filterType}
                  onChange={(e) => setFilterType(e.target.value)}
                  className="bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold px-4 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                >
                  <option value="all">All Types</option>
                  <option value="airlines">Airlines</option>
                  <option value="hotel">Hotels</option>
                  <option value="rental">Car Rentals</option>
                  <option value="travel agency">Travel Agencies</option>
                </select>
                <button className="flex items-center gap-2 px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold">
                  <Filter size={16} />
                  More Filters
                </button>
              </div>
            </div>
          </div>

          {/* Businesses List */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {filteredBusinesses.map((business) => (
              <div
                key={business._id}
                className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow"
              >
                {/* Business Header */}
                <div className="p-6">
                  <div className="flex items-start justify-between mb-4">
                    <div className="flex items-center gap-3">
                      <div className="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        {business.logo}
                      </div>
                      <div>
                        <h3 className="font-bold text-lg">
                          {business.business_name}
                        </h3>
                        <div className="flex items-center gap-2">
                          <span className="text-xs bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300 px-2 py-0.5 rounded-full">
                            {business.business_type}
                          </span>
                          {renderStars(business.rating)}
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* Business Info */}
                  <div className="space-y-3 mb-4">
                    <p className="text-gray-600 dark:text-gray-400 text-sm">
                      {business.description}
                    </p>
                    <div className="flex items-center text-sm text-gray-500">
                      <MapPin size={14} className="mr-2" />
                      {business.location}
                    </div>
                    <div className="grid grid-cols-2 gap-3">
                      {business.aircraft_count > 0 && (
                        <div className="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                          <div className="font-bold text-cyan-600">
                            {business.aircraft_count}
                          </div>
                          <div className="text-xs text-gray-500">Aircraft</div>
                        </div>
                      )}
                      <div className="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div className="font-bold text-cyan-600">
                          {business.routes}
                        </div>
                        <div className="text-xs text-gray-500">Routes</div>
                      </div>
                      <div className="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div className="font-bold text-cyan-600">
                          {business.schedules_count}
                        </div>
                        <div className="text-xs text-gray-500">Schedules</div>
                      </div>
                      <div className="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div className="font-bold text-cyan-600">
                          {business.established}
                        </div>
                        <div className="text-xs text-gray-500">Established</div>
                      </div>
                    </div>
                  </div>

                  {/* Action Button */}
                  <button
                    onClick={() => handleViewSchedules(business)}
                    className="w-full flex items-center justify-center gap-2 bg-cyan-600 text-white font-semibold py-3 rounded-lg hover:bg-cyan-700 transition"
                  >
                    <Calendar size={18} />
                    View {business.schedules_count} Schedules
                  </button>
                </div>
              </div>
            ))}
          </div>

          {filteredBusinesses.length === 0 && (
            <div className="text-center py-12">
              <Building
                size={48}
                className="mx-auto text-gray-300 dark:text-gray-600 mb-4"
              />
              <p className="text-gray-500 dark:text-gray-400">
                No businesses found
              </p>
              <p className="text-sm text-gray-400 dark:text-gray-500 mt-1">
                {businesses.length > 0
                  ? "Try adjusting your search or filters"
                  : "No businesses are currently registered"}
              </p>
            </div>
          )}
        </div>
      </main>

      {/* Business Schedules Modal */}
      {selectedBusiness && (
        <BusinessSchedulesModal
          isOpen={showSchedulesModal}
          onClose={() => {
            setShowSchedulesModal(false);
            setSelectedBusiness(null);
          }}
          business={selectedBusiness}
          userId={user?.id}
        />
      )}
    </div>
  );
};

export default Plan;
