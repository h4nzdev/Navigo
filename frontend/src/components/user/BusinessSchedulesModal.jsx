import React, { useState, useEffect } from "react";
import {
  X,
  Plane,
  Calendar,
  Clock,
  DollarSign,
  Users,
  MapPin,
  ChevronDown,
  Search,
  Filter,
  Star,
  Check,
  AlertCircle,
} from "lucide-react";
import BookingRequestModal from "./BookingRequestModal";
import {
  getSchedule,
  getBusinessSchedules,
} from "../../services/scheduleService";

const BusinessSchedulesModal = ({ isOpen, onClose, business, userId }) => {
  const [schedules, setSchedules] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState("");
  const [filterStatus, setFilterStatus] = useState("active");
  const [sortBy, setSortBy] = useState("departure");
  const [selectedSchedule, setSelectedSchedule] = useState(null);
  const [showBookingModal, setShowBookingModal] = useState(false);

  useEffect(() => {
    if (isOpen && business?._id) {
      fetchBusinessSchedules();
    }
  }, [isOpen, business]);

  const fetchBusinessSchedules = async () => {
    setLoading(true);
    setError(null);
    try {
      // Try to get real schedules first
      const data = await getBusinessSchedules(business._id);

      if (data && data.length > 0) {
        // Format the API data
        const formattedSchedules = data.map((schedule) => {
          const departureDate = new Date(schedule.departure_time);
          const arrivalDate = new Date(schedule.arrival_time);
          const durationMs = arrivalDate - departureDate;
          const durationHours = Math.floor(durationMs / (1000 * 60 * 60));
          const durationMinutes = Math.floor(
            (durationMs % (1000 * 60 * 60)) / (1000 * 60)
          );

          return {
            _id: schedule._id,
            business_id: schedule.business_id,
            from: schedule.from || "Unknown",
            to: schedule.to || "Unknown",
            departure_time: schedule.departure_time,
            arrival_time: schedule.arrival_time,
            duration: `${durationHours}h ${durationMinutes}m`,
            price: schedule.price || 0,
            seats: schedule.seats || 0,
            available_seats: schedule.available_seats || schedule.seats || 0,
            aircraft_type: schedule.aircraft_type || "Not specified",
            amenities: getAmenities(schedule.aircraft_type),
            status: schedule.status || "active",
            created_at: schedule.created_at,
          };
        });

        // Filter only active schedules for users
        const activeSchedules = formattedSchedules.filter(
          (s) => s.status === "active" && s.available_seats > 0
        );

        setSchedules(activeSchedules);
      } else {
        // Fallback to mock data if no real schedules
        setSchedules(getMockSchedules());
      }
    } catch (err) {
      console.error("Error fetching business schedules:", err);
      setError("Failed to load schedules. Showing sample data.");
      setSchedules(getMockSchedules());
    } finally {
      setLoading(false);
    }
  };

  const getMockSchedules = () => {
    return [
      {
        _id: "1",
        business_id: business?._id,
        from: "Manila (MNL)",
        to: "Cebu (CEB)",
        departure_time: "2024-12-15T08:00:00",
        arrival_time: "2024-12-15T09:30:00",
        duration: "1h 30m",
        price: 2500,
        seats: 150,
        available_seats: 120,
        aircraft_type: "Airbus A320",
        amenities: ["WiFi", "Meal", "Entertainment"],
        status: "active",
      },
      {
        _id: "2",
        business_id: business?._id,
        from: "Manila (MNL)",
        to: "Davao (DVO)",
        departure_time: "2024-12-15T14:00:00",
        arrival_time: "2024-12-15T16:00:00",
        duration: "2h",
        price: 3200,
        seats: 180,
        available_seats: 45,
        aircraft_type: "Boeing 737",
        amenities: ["WiFi", "Extra Legroom"],
        status: "active",
      },
      {
        _id: "3",
        business_id: business?._id,
        from: "Cebu (CEB)",
        to: "Boracay (MPH)",
        departure_time: "2024-12-16T10:30:00",
        arrival_time: "2024-12-16T11:15:00",
        duration: "45m",
        price: 1800,
        seats: 120,
        available_seats: 120,
        aircraft_type: "Airbus A321",
        amenities: ["Snack", "Beverage"],
        status: "active",
      },
    ];
  };

  const getAmenities = (aircraftType) => {
    const amenitiesMap = {
      "Airbus A320": ["WiFi", "Meal", "Entertainment", "USB Ports"],
      "Boeing 737": ["WiFi", "Extra Legroom", "On-demand Movies"],
      "Airbus A321": ["Snack", "Beverage", "Magazines"],
      "Boeing 777": ["WiFi", "Premium Meal", "Lounge Access", "Flat-bed Seats"],
      "Embraer E190": ["Complimentary Snack", "Drinks"],
    };
    return amenitiesMap[aircraftType] || ["Standard Service"];
  };

  const formatTime = (dateString) => {
    try {
      const date = new Date(dateString);
      return date.toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
      });
    } catch (err) {
      return "TBD";
    }
  };

  const formatDate = (dateString) => {
    try {
      const date = new Date(dateString);
      return date.toLocaleDateString();
    } catch (err) {
      return "Date TBD";
    }
  };

  const filteredSchedules = schedules
    .filter((schedule) => {
      const matchesSearch =
        schedule.from?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        schedule.to?.toLowerCase().includes(searchTerm.toLowerCase());
      const matchesStatus =
        filterStatus === "all" || schedule.status === filterStatus;
      return matchesSearch && matchesStatus;
    })
    .sort((a, b) => {
      if (sortBy === "price") return a.price - b.price;
      if (sortBy === "departure")
        return new Date(a.departure_time) - new Date(b.departure_time);
      if (sortBy === "duration") {
        const aTime = a.duration
          .split(" ")
          .map(Number)
          .filter((n) => !isNaN(n));
        const bTime = b.duration
          .split(" ")
          .map(Number)
          .filter((n) => !isNaN(n));
        const aMinutes = (aTime[0] || 0) * 60 + (aTime[1] || 0);
        const bMinutes = (bTime[0] || 0) * 60 + (bTime[1] || 0);
        return aMinutes - bMinutes;
      }
      return 0;
    });

  const handleBookSchedule = (schedule) => {
    setSelectedSchedule(schedule);
    setShowBookingModal(true);
  };

  const getSeatAvailabilityClass = (available, total) => {
    const percentage = (available / total) * 100;
    if (percentage > 50) return "text-green-600";
    if (percentage > 20) return "text-yellow-600";
    return "text-red-600";
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
      <div className="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-6xl max-h-[90vh] overflow-y-auto">
        {/* Modal Header */}
        <div className="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
          <div className="flex items-center space-x-3">
            <div className="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
              {business?.logo || "BS"}
            </div>
            <div>
              <h2 className="text-xl font-bold">
                {business?.business_name || "Business"} Schedules
              </h2>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                Browse and book available flights
              </p>
            </div>
          </div>
          <button
            onClick={onClose}
            className="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"
          >
            <X size={20} />
          </button>
        </div>

        {/* Error Message */}
        {error && (
          <div className="mx-6 mt-4 p-3 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 rounded-lg flex items-center gap-2">
            <AlertCircle size={16} />
            <span>{error}</span>
          </div>
        )}

        {/* Controls */}
        <div className="p-6 border-b border-gray-200 dark:border-gray-700">
          <div className="flex flex-col md:flex-row gap-4">
            <div className="relative flex-grow">
              <Search
                className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                size={20}
              />
              <input
                type="text"
                placeholder="Search by route..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
              />
            </div>
            <div className="flex gap-3">
              <select
                value={sortBy}
                onChange={(e) => setSortBy(e.target.value)}
                className="bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold px-4 focus:outline-none focus:ring-2 focus:ring-cyan-500"
              >
                <option value="departure">Sort by: Departure</option>
                <option value="price">Sort by: Price</option>
                <option value="duration">Sort by: Duration</option>
              </select>
              <select
                value={filterStatus}
                onChange={(e) => setFilterStatus(e.target.value)}
                className="bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold px-4 focus:outline-none focus:ring-2 focus:ring-cyan-500"
              >
                <option value="active">Active Only</option>
                <option value="all">All Schedules</option>
              </select>
            </div>
          </div>
        </div>

        {/* Schedules List */}
        <div className="p-6">
          {loading ? (
            <div className="flex justify-center items-center h-64">
              <div className="w-10 h-10 border-2 border-cyan-600 border-t-transparent rounded-full animate-spin"></div>
            </div>
          ) : filteredSchedules.length === 0 ? (
            <div className="text-center py-12">
              <Plane
                size={48}
                className="mx-auto text-gray-300 dark:text-gray-600 mb-4"
              />
              <p className="text-gray-500 dark:text-gray-400">
                No schedules available
              </p>
              <p className="text-sm text-gray-400 dark:text-gray-500 mt-1">
                {schedules.length > 0
                  ? "Try adjusting your search or filters"
                  : "This business currently has no active schedules"}
              </p>
            </div>
          ) : (
            <div className="space-y-4">
              {filteredSchedules.map((schedule) => (
                <div
                  key={schedule._id}
                  className="border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition-shadow"
                >
                  <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {/* Route and Time */}
                    <div className="space-y-2">
                      <div className="font-bold text-lg text-gray-900 dark:text-gray-100">
                        {schedule.from} → {schedule.to}
                      </div>
                      <div className="flex items-center text-sm text-gray-500">
                        <Clock size={14} className="mr-2" />
                        {formatTime(schedule.departure_time)} •{" "}
                        {formatDate(schedule.departure_time)}
                      </div>
                      <div className="text-sm text-gray-700 dark:text-gray-300">
                        <span className="font-semibold">Duration:</span>{" "}
                        {schedule.duration}
                      </div>
                    </div>

                    {/* Aircraft and Amenities */}
                    <div className="space-y-2">
                      <div className="flex items-center gap-2">
                        <Plane size={16} className="text-blue-500" />
                        <span className="text-gray-900 dark:text-gray-100">
                          {schedule.aircraft_type}
                        </span>
                      </div>
                      <div className="flex flex-wrap gap-2">
                        {schedule.amenities?.map((amenity, index) => (
                          <span
                            key={index}
                            className="text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 px-2 py-1 rounded flex items-center gap-1"
                          >
                            <Check size={10} />
                            {amenity}
                          </span>
                        ))}
                      </div>
                    </div>

                    {/* Price and Action */}
                    <div className="space-y-3 text-right">
                      <div>
                        <div className="font-bold text-2xl text-cyan-600">
                          ₱{schedule.price?.toLocaleString() || "0"}
                        </div>
                        <div
                          className={`text-sm font-semibold ${getSeatAvailabilityClass(
                            schedule.available_seats,
                            schedule.seats
                          )}`}
                        >
                          {schedule.available_seats} of {schedule.seats} seats
                          available
                        </div>
                      </div>
                      <button
                        onClick={() => handleBookSchedule(schedule)}
                        disabled={schedule.available_seats <= 0}
                        className={`w-full md:w-auto px-6 py-2 font-semibold rounded-lg transition ${
                          schedule.available_seats > 0
                            ? "bg-cyan-600 text-white hover:bg-cyan-700"
                            : "bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                        }`}
                      >
                        {schedule.available_seats > 0 ? "Book Now" : "Sold Out"}
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>

      {/* Booking Request Modal */}
      {selectedSchedule && (
        <BookingRequestModal
          isOpen={showBookingModal}
          onClose={() => {
            setShowBookingModal(false);
            setSelectedSchedule(null);
          }}
          schedule={selectedSchedule}
          business={business}
          userId={userId}
        />
      )}
    </div>
  );
};

export default BusinessSchedulesModal;
