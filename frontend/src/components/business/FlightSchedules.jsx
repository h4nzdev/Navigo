import React, { useState, useEffect, useContext } from "react";
import {
  Plane,
  Calendar,
  MapPin,
  Clock,
  DollarSign,
  Users,
  Search,
  Filter,
  Edit,
  Trash2,
  Plus,
  ChevronRight,
} from "lucide-react";
import { AuthContext } from "../../context/AuthContext";
import { getBusinessSchedules } from "../../services/scheduleService";

const FlightSchedules = () => {
  const [schedules, setSchedules] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState("");
  const [filterStatus, setFilterStatus] = useState("all");
  const [error, setError] = useState(null);
  const { user } = useContext(AuthContext);
  const businessId = user?._id;

  useEffect(() => {
    const fetchSchedules = async () => {
      if (!businessId) {
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        setError(null);

        // Try to get real data first
        const data = await getBusinessSchedules(businessId);

        if (data && data.length > 0) {
          // Format the API data to match our structure
          const formattedSchedules = data.map((schedule) => ({
            _id: schedule._id,
            from: schedule.from,
            to: schedule.to,
            departure_time: schedule.departure_time,
            arrival_time: schedule.arrival_time,
            price: schedule.price,
            seats: schedule.seats,
            available_seats: schedule.available_seats || schedule.seats, // Default to total seats if not available
            aircraft_type: schedule.aircraft_type || "Unknown",
            status: schedule.status || "active",
            created_at: schedule.created_at,
          }));

          setSchedules(formattedSchedules);
        } else {
          // Fallback to mock data if no real data
          const mockSchedules = [
            {
              _id: "1",
              from: "Manila (MNL)",
              to: "Cebu (CEB)",
              departure_time: "2024-12-15T08:00:00",
              arrival_time: "2024-12-15T09:30:00",
              price: 2500,
              seats: 150,
              available_seats: 120,
              aircraft_type: "Airbus A320",
              status: "active",
              created_at: "2024-12-01",
            },
            {
              _id: "2",
              from: "Manila (MNL)",
              to: "Davao (DVO)",
              departure_time: "2024-12-15T14:00:00",
              arrival_time: "2024-12-15T16:00:00",
              price: 3200,
              seats: 180,
              available_seats: 45,
              aircraft_type: "Boeing 737",
              status: "active",
              created_at: "2024-12-01",
            },
          ];

          setSchedules(mockSchedules);
        }
      } catch (err) {
        console.error("Error fetching schedules:", err);
        setError("Failed to load flight schedules. Please try again.");

        // Fallback to mock data on error
        const mockSchedules = [
          {
            _id: "3",
            from: "Cebu (CEB)",
            to: "Boracay (MPH)",
            departure_time: "2024-12-16T10:30:00",
            arrival_time: "2024-12-16T11:15:00",
            price: 1800,
            seats: 120,
            available_seats: 120,
            aircraft_type: "Airbus A321",
            status: "upcoming",
            created_at: "2024-12-02",
          },
          {
            _id: "4",
            from: "Davao (DVO)",
            to: "Manila (MNL)",
            departure_time: "2024-12-14T18:00:00",
            arrival_time: "2024-12-14T20:00:00",
            price: 3200,
            seats: 180,
            available_seats: 0,
            aircraft_type: "Boeing 737",
            status: "completed",
            created_at: "2024-11-30",
          },
        ];

        setSchedules(mockSchedules);
      } finally {
        setLoading(false);
      }
    };

    fetchSchedules();
  }, [businessId]);

  const formatTime = (dateString) => {
    try {
      const date = new Date(dateString);
      return date.toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
      });
    } catch (err) {
      return "Invalid time";
    }
  };

  const formatDate = (dateString) => {
    try {
      const date = new Date(dateString);
      return date.toLocaleDateString();
    } catch (err) {
      return "Invalid date";
    }
  };

  const getStatusColor = (status) => {
    switch (status?.toLowerCase()) {
      case "active":
        return "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300";
      case "upcoming":
        return "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300";
      case "completed":
        return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300";
      default:
        return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300";
    }
  };

  const filteredSchedules = schedules.filter((schedule) => {
    const matchesSearch =
      schedule.from?.toLowerCase().includes(searchTerm.toLowerCase()) ||
      schedule.to?.toLowerCase().includes(searchTerm.toLowerCase()) ||
      schedule.aircraft_type?.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesStatus =
      filterStatus === "all" ||
      schedule.status?.toLowerCase() === filterStatus.toLowerCase();
    return matchesSearch && matchesStatus;
  });

  const handleEdit = (scheduleId) => {
    console.log("Edit schedule:", scheduleId);
  };

  const handleDelete = async (scheduleId) => {
    if (window.confirm("Are you sure you want to delete this schedule?")) {
      try {
        // API call to delete schedule would go here
        console.log("Delete schedule:", scheduleId);

        // Update local state
        setSchedules(
          schedules.filter((schedule) => schedule._id !== scheduleId)
        );
      } catch (err) {
        console.error("Error deleting schedule:", err);
        alert("Failed to delete schedule. Please try again.");
      }
    }
  };

  const totalRevenue = schedules.reduce((sum, schedule) => {
    const soldSeats =
      schedule.seats - (schedule.available_seats || schedule.seats);
    return sum + soldSeats * schedule.price;
  }, 0);

  if (loading) {
    return (
      <div className="p-6 space-y-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
        {/* Page Header */}
        <div>
          <h1 className="text-3xl font-bold text-gray-900 dark:text-gray-100">
            Flight Schedules
          </h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">
            Manage and monitor your flight schedules
          </p>
        </div>

        <div className="flex justify-center items-center h-64">
          <div className="w-10 h-10 border-2 border-cyan-600 border-t-transparent rounded-full animate-spin"></div>
        </div>
      </div>
    );
  }

  return (
    <div className="p-6 space-y-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
      {/* Page Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900 dark:text-gray-100">
          Flight Schedules
        </h1>
        <p className="text-gray-600 dark:text-gray-400 mt-2">
          Manage and monitor your flight schedules
        </p>

        {/* Error Message */}
        {error && (
          <div className="mt-4 p-4 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 rounded-lg">
            {error}
          </div>
        )}
      </div>

      {/* Header Stats */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                Total Schedules
              </p>
              <p className="text-3xl font-bold text-gray-900 dark:text-gray-100">
                {schedules.length}
              </p>
            </div>
            <Calendar className="text-cyan-600" size={32} />
          </div>
        </div>
        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                Active Flights
              </p>
              <p className="text-3xl font-bold text-gray-900 dark:text-gray-100">
                {
                  schedules.filter((s) => s.status?.toLowerCase() === "active")
                    .length
                }
              </p>
            </div>
            <Plane className="text-green-600" size={32} />
          </div>
        </div>
        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                Estimated Revenue
              </p>
              <p className="text-3xl font-bold text-gray-900 dark:text-gray-100">
                ₱{totalRevenue.toLocaleString()}
              </p>
            </div>
            <DollarSign className="text-purple-600" size={32} />
          </div>
        </div>
      </div>

      {/* Controls */}
      <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div className="flex flex-col md:flex-row gap-4 mb-6">
          <div className="relative flex-grow">
            <Search
              className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
              size={20}
            />
            <input
              type="text"
              placeholder="Search by route, aircraft..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 text-gray-900 dark:text-gray-100"
            />
          </div>
          <div className="flex gap-3">
            <select
              value={filterStatus}
              onChange={(e) => setFilterStatus(e.target.value)}
              className="bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold px-4 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-gray-900 dark:text-gray-100"
            >
              <option value="all">All Status</option>
              <option value="active">Active</option>
              <option value="upcoming">Upcoming</option>
              <option value="completed">Completed</option>
            </select>
            <button className="flex items-center gap-2 px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold text-gray-900 dark:text-gray-100">
              <Filter size={16} />
              More Filters
            </button>
          </div>
        </div>

        {/* Schedules Table */}
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b border-gray-200 dark:border-gray-700">
                <th className="text-left py-3 px-4 text-gray-500 dark:text-gray-400 font-semibold">
                  Route
                </th>
                <th className="text-left py-3 px-4 text-gray-500 dark:text-gray-400 font-semibold">
                  Schedule
                </th>
                <th className="text-left py-3 px-4 text-gray-500 dark:text-gray-400 font-semibold">
                  Aircraft
                </th>
                <th className="text-left py-3 px-4 text-gray-500 dark:text-gray-400 font-semibold">
                  Seats
                </th>
                <th className="text-left py-3 px-4 text-gray-500 dark:text-gray-400 font-semibold">
                  Price
                </th>
                <th className="text-left py-3 px-4 text-gray-500 dark:text-gray-400 font-semibold">
                  Status
                </th>
                <th className="text-left py-3 px-4 text-gray-500 dark:text-gray-400 font-semibold">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody>
              {filteredSchedules.map((schedule) => (
                <tr
                  key={schedule._id}
                  className="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                  <td className="py-4 px-4">
                    <div className="flex items-center space-x-2">
                      <MapPin size={16} className="text-gray-400" />
                      <div>
                        <div className="font-semibold text-gray-900 dark:text-gray-100">
                          {schedule.from || "Unknown"}
                        </div>
                        <div className="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                          <ChevronRight size={12} className="mx-1" />
                          {schedule.to || "Unknown"}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td className="py-4 px-4">
                    <div>
                      <div className="font-semibold flex items-center text-gray-900 dark:text-gray-100">
                        <Clock size={14} className="mr-2 text-gray-400" />
                        {formatTime(schedule.departure_time)}
                      </div>
                      <div className="text-sm text-gray-500 dark:text-gray-400">
                        {formatDate(schedule.departure_time)}
                      </div>
                    </div>
                  </td>
                  <td className="py-4 px-4">
                    <div className="flex items-center space-x-2">
                      <Plane size={16} className="text-blue-500" />
                      <span className="text-gray-900 dark:text-gray-100">
                        {schedule.aircraft_type || "Unknown"}
                      </span>
                    </div>
                  </td>
                  <td className="py-4 px-4">
                    <div>
                      <div className="font-semibold text-gray-900 dark:text-gray-100">
                        {schedule.available_seats || schedule.seats}/
                        {schedule.seats}
                      </div>
                      <div className="text-sm text-gray-500 dark:text-gray-400">
                        <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-1">
                          <div
                            className="bg-green-500 h-2 rounded-full"
                            style={{
                              width: `${
                                ((schedule.available_seats || schedule.seats) /
                                  schedule.seats) *
                                100
                              }%`,
                            }}
                          ></div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td className="py-4 px-4">
                    <div className="flex items-center">
                      <DollarSign size={14} className="text-green-500 mr-1" />
                      <span className="font-semibold text-gray-900 dark:text-gray-100">
                        ₱{schedule.price?.toLocaleString() || "0"}
                      </span>
                    </div>
                  </td>
                  <td className="py-4 px-4">
                    <span
                      className={`text-xs font-bold px-2 py-1 rounded-full ${getStatusColor(
                        schedule.status
                      )}`}
                    >
                      {(schedule.status || "active").toUpperCase()}
                    </span>
                  </td>
                  <td className="py-4 px-4">
                    <div className="flex space-x-2">
                      <button
                        onClick={() => handleEdit(schedule._id)}
                        className="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg"
                        title="Edit"
                      >
                        <Edit size={16} />
                      </button>
                      <button
                        onClick={() => handleDelete(schedule._id)}
                        className="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"
                        title="Delete"
                      >
                        <Trash2 size={16} />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {filteredSchedules.length === 0 && (
          <div className="text-center py-12">
            <Plane
              size={48}
              className="mx-auto text-gray-300 dark:text-gray-600 mb-4"
            />
            <p className="text-gray-500 dark:text-gray-400">
              No flight schedules found
            </p>
            <p className="text-sm text-gray-400 dark:text-gray-500 mt-1">
              {schedules.length > 0
                ? "Try adjusting your search or filters"
                : "Create your first flight schedule to get started"}
            </p>
          </div>
        )}

        {/* Footer */}
        <div className="flex justify-between items-center mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
          <div className="text-sm text-gray-500 dark:text-gray-400">
            Showing {filteredSchedules.length} of {schedules.length} schedules
          </div>
          <button className="flex items-center gap-2 text-cyan-600 hover:text-cyan-700 font-semibold">
            View All Schedules
            <ChevronRight size={16} />
          </button>
        </div>
      </div>
    </div>
  );
};

export default FlightSchedules;
