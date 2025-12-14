import React, { useState, useEffect, useContext } from "react";
import {
  Clock,
  User,
  Mail,
  Plane,
  Hotel,
  Car,
  DollarSign,
  CheckCircle,
  XCircle,
  MessageSquare,
  Search,
  Filter,
  MoreVertical,
  AlertCircle,
  RefreshCw,
  Calendar,
  Users,
} from "lucide-react";
import { AuthContext } from "../../context/AuthContext";
import {
  getBusinessBookingRequests,
  updateBookingStatus,
  updateBookingRequest,
} from "../../services/bookingRequestService";

const PendingRequests = () => {
  const { user } = useContext(AuthContext);
  const [requests, setRequests] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchTerm, setSearchTerm] = useState("");
  const [filterType, setFilterType] = useState("all");
  const [selectedRequest, setSelectedRequest] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchBookingRequests();
  }, []);

  const fetchBookingRequests = async () => {
    setLoading(true);
    setError(null);
    try {
      const data = await getBusinessBookingRequests(user._id);

      if (data && data.length > 0) {
        // Format the API data
        const formattedRequests = data.map((request) => {
          // Extract schedule information
          const schedule = request.schedule_id || {};
          const customer = request.customer_id || {};

          // Calculate time left
          const createdAt = new Date(request.created_at);
          const now = new Date();
          const hoursDiff = Math.floor((now - createdAt) / (1000 * 60 * 60));
          const daysDiff = Math.floor(hoursDiff / 24);

          let timeLeft = "";
          if (daysDiff > 0) {
            timeLeft = `${daysDiff}d left`;
          } else if (hoursDiff > 0) {
            timeLeft = `${24 - hoursDiff}h left`;
          } else {
            timeLeft = "Less than 1h left";
          }

          // Calculate price comparison if we have market price
          const requestedPrice = request.price_offered || 0;
          const schedulePrice = schedule.price || requestedPrice * 1.1; // Default 10% higher
          const marketComparison =
            schedulePrice > 0
              ? (
                  ((schedulePrice - requestedPrice) / schedulePrice) *
                  100
                ).toFixed(1)
              : "0";

          return {
            _id: request._id,
            customer: {
              name: `${customer.first_name || "Customer"} ${
                customer.last_name || ""
              }`,
              email: customer.email || "No email",
              phone: customer.phone || "No phone",
              initials:
                customer.first_name?.charAt(0) +
                  customer.last_name?.charAt(0) || "CU",
            },
            type: "flight", // Default to flight, you could add type to schedule
            route: `${schedule.from || "Unknown"} → ${
              schedule.to || "Unknown"
            }`,
            details: `${request.seats_requested || 1} passenger${
              request.seats_requested > 1 ? "s" : ""
            } • ${schedule.aircraft_type || "Standard"}`,
            requestedPrice: `₱${requestedPrice.toLocaleString()}`,
            marketPrice: `₱${schedulePrice.toLocaleString()}`,
            marketComparison: marketComparison,
            status: request.status || "pending",
            priority: getPriorityFromSeats(request.seats_requested),
            timeLeft: timeLeft,
            createdAt: request.created_at,
            notes: request.special_requests || "",
            schedule: schedule,
            rawRequest: request, // Keep original for updates
          };
        });

        setRequests(formattedRequests);
      } else {
        // Fallback to mock data if no real data
        setRequests(getMockRequests());
      }
    } catch (err) {
      console.error("Error fetching booking requests:", err);
      setError("Failed to load booking requests. Showing sample data.");
      setRequests(getMockRequests());
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const getMockRequests = () => {
    return [
      {
        _id: "BR-001",
        customer: {
          name: "Anna Mae Regis",
          email: "anna.regis@email.com",
          phone: "+63 912 345 6789",
          initials: "AMR",
        },
        type: "flight",
        route: "Manila (MNL) → Cebu (CEB)",
        details: "2 passengers • Business Class • Window seats preferred",
        requestedPrice: "₱4,800",
        marketPrice: "₱5,200",
        marketComparison: "7.7",
        status: "pending",
        priority: "high",
        timeLeft: "2h left",
        createdAt: "2024-12-15T10:30:00",
        notes: "Customer wants early morning flight",
      },
      {
        _id: "BR-002",
        customer: {
          name: "John Smith",
          email: "john.smith@email.com",
          phone: "+63 917 890 1234",
          initials: "JS",
        },
        type: "flight",
        route: "Manila (MNL) → Davao (DVO)",
        details: "1 passenger • Economy • Extra baggage",
        requestedPrice: "₱3,500",
        marketPrice: "₱3,800",
        marketComparison: "7.9",
        status: "pending",
        priority: "low",
        timeLeft: "8h left",
        createdAt: "2024-12-15T08:45:00",
        notes: "",
      },
    ];
  };

  const getPriorityFromSeats = (seats) => {
    if (seats >= 4) return "high";
    if (seats >= 2) return "medium";
    return "low";
  };

  const getTypeIcon = (type) => {
    switch (type) {
      case "flight":
        return <Plane size={20} className="text-blue-500" />;
      case "hotel":
        return <Hotel size={20} className="text-green-500" />;
      case "car":
        return <Car size={20} className="text-purple-500" />;
      default:
        return <Plane size={20} className="text-orange-500" />;
    }
  };

  const getStatusColor = (status) => {
    switch (status) {
      case "pending":
        return "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300";
      case "negotiating":
        return "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300";
      case "accepted":
        return "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300";
      case "declined":
        return "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300";
      default:
        return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300";
    }
  };

  const getPriorityColor = (priority) => {
    switch (priority) {
      case "high":
        return "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300";
      case "medium":
        return "bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300";
      case "low":
        return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300";
      default:
        return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300";
    }
  };

  const formatTime = (dateString) => {
    try {
      const date = new Date(dateString);
      return date.toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
      });
    } catch (err) {
      return "N/A";
    }
  };

  const formatDate = (dateString) => {
    try {
      const date = new Date(dateString);
      return date.toLocaleDateString();
    } catch (err) {
      return "N/A";
    }
  };

  const filteredRequests = requests.filter((request) => {
    const matchesSearch =
      request.customer.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      request.route.toLowerCase().includes(searchTerm.toLowerCase()) ||
      request._id.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesType = filterType === "all" || request.type === filterType;
    return matchesSearch && matchesType;
  });

  const handleAccept = async (requestId) => {
    if (!window.confirm("Accept this booking request?")) return;

    try {
      // Update status via API
      const updatedRequest = await updateBookingStatus(requestId, "accepted");

      // Update local state
      setRequests((prev) =>
        prev.map((req) =>
          req._id === requestId
            ? { ...req, status: "accepted", timeLeft: "Accepted" }
            : req
        )
      );

      alert("✅ Booking request accepted successfully!");
    } catch (err) {
      console.error("Error accepting booking request:", err);
      alert("❌ Failed to accept booking request. Please try again.");
    }
  };

  const handleDecline = async (requestId) => {
    if (!window.confirm("Decline this booking request?")) return;

    try {
      // Update status via API
      const updatedRequest = await updateBookingStatus(requestId, "declined");

      // Update local state
      setRequests((prev) =>
        prev.map((req) =>
          req._id === requestId
            ? { ...req, status: "declined", timeLeft: "Declined" }
            : req
        )
      );

      alert("✅ Booking request declined.");
    } catch (err) {
      console.error("Error declining booking request:", err);
      alert("❌ Failed to decline booking request. Please try again.");
    }
  };

  const handleNegotiate = async (requestId) => {
    const request = requests.find((req) => req._id === requestId);
    if (!request) return;

    const newPrice = prompt(
      `Enter counter offer price (Current: ${request.requestedPrice}):`,
      request.requestedPrice.replace(/[^0-9]/g, "")
    );

    if (!newPrice || isNaN(newPrice)) return;

    try {
      // Update booking request with negotiation
      const updateData = {
        status: "negotiating",
        price_offered: parseFloat(newPrice),
        special_requests: request.notes || "Counter offer made",
      };

      const updatedRequest = await updateBookingRequest(requestId, updateData);

      // Update local state
      setRequests((prev) =>
        prev.map((req) =>
          req._id === requestId
            ? {
                ...req,
                status: "negotiating",
                requestedPrice: `₱${parseFloat(newPrice).toLocaleString()}`,
                notes: req.notes + " (Counter offer sent)",
                timeLeft: "Negotiating",
              }
            : req
        )
      );

      alert(
        `✅ Counter offer of ₱${parseFloat(newPrice).toLocaleString()} sent!`
      );
    } catch (err) {
      console.error("Error sending counter offer:", err);
      alert("❌ Failed to send counter offer. Please try again.");
    }
  };

  const handleRefresh = () => {
    setRefreshing(true);
    fetchBookingRequests();
  };

  const pendingCount = requests.filter((r) => r.status === "pending").length;
  const negotiatingCount = requests.filter(
    (r) => r.status === "negotiating"
  ).length;
  const acceptedCount = requests.filter((r) => r.status === "accepted").length;

  if (loading && !refreshing) {
    return (
      <div className="p-6 space-y-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div className="flex justify-center items-center h-64">
          <div className="w-10 h-10 border-2 border-cyan-600 border-t-transparent rounded-full animate-spin"></div>
        </div>
      </div>
    );
  }

  return (
    <div className="p-6 space-y-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
      {/* Page Header */}
      <div className="flex justify-between items-start">
        <div>
          <h1 className="text-3xl font-bold text-gray-900 dark:text-gray-100">
            Booking Requests
          </h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">
            Manage and respond to customer booking requests
          </p>
          {error && (
            <div className="mt-2 p-3 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 rounded-lg flex items-center gap-2">
              <AlertCircle size={16} />
              <span>{error}</span>
            </div>
          )}
        </div>
        <button
          onClick={handleRefresh}
          className="flex items-center gap-2 px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 font-semibold disabled:opacity-50"
          disabled={refreshing}
        >
          <RefreshCw size={16} className={refreshing ? "animate-spin" : ""} />
          {refreshing ? "Refreshing..." : "Refresh"}
        </button>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                Pending Review
              </p>
              <p className="text-3xl font-bold text-yellow-600 dark:text-yellow-400">
                {pendingCount}
              </p>
            </div>
            <Clock className="text-yellow-600" size={32} />
          </div>
        </div>
        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                Under Negotiation
              </p>
              <p className="text-3xl font-bold text-blue-600 dark:text-blue-400">
                {negotiatingCount}
              </p>
            </div>
            <MessageSquare className="text-blue-600" size={32} />
          </div>
        </div>
        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                Accepted
              </p>
              <p className="text-3xl font-bold text-green-600 dark:text-green-400">
                {acceptedCount}
              </p>
            </div>
            <CheckCircle className="text-green-600" size={32} />
          </div>
        </div>
        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                Total Requests
              </p>
              <p className="text-3xl font-bold text-cyan-600 dark:text-cyan-400">
                {requests.length}
              </p>
            </div>
            <Users className="text-cyan-600" size={32} />
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
              placeholder="Search by customer, route, or ID..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 text-gray-900 dark:text-gray-100"
            />
          </div>
          <div className="flex gap-3">
            <select
              value={filterType}
              onChange={(e) => setFilterType(e.target.value)}
              className="bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold px-4 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-gray-900 dark:text-gray-100"
            >
              <option value="all">All Status</option>
              <option value="pending">Pending</option>
              <option value="negotiating">Negotiating</option>
              <option value="accepted">Accepted</option>
              <option value="declined">Declined</option>
            </select>
            <button className="flex items-center gap-2 px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold text-gray-900 dark:text-gray-100">
              <Filter size={16} />
              More Filters
            </button>
          </div>
        </div>

        {/* Requests List */}
        <div className="space-y-4">
          {filteredRequests.map((request) => (
            <div
              key={request._id}
              className="border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition-shadow bg-white dark:bg-gray-800"
            >
              {/* Request Header */}
              <div className="flex justify-between items-start mb-4">
                <div className="flex items-center gap-3">
                  {getTypeIcon(request.type)}
                  <div>
                    <div className="font-bold text-gray-900 dark:text-gray-100">
                      {request._id}
                    </div>
                    <div className="text-sm text-gray-500 dark:text-gray-400">
                      {request.route}
                    </div>
                    <div className="text-xs text-gray-400 flex items-center gap-2 mt-1">
                      <Calendar size={12} />
                      {formatDate(request.createdAt)} at{" "}
                      {formatTime(request.createdAt)}
                    </div>
                  </div>
                </div>
                <div className="flex items-center gap-2">
                  <span
                    className={`text-xs font-bold px-2 py-1 rounded-full ${getPriorityColor(
                      request.priority
                    )}`}
                  >
                    {request.priority.toUpperCase()}
                  </span>
                  <span
                    className={`text-xs font-bold px-2 py-1 rounded-full ${getStatusColor(
                      request.status
                    )}`}
                  >
                    {request.status.toUpperCase()}
                  </span>
                  <span className="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                    <Clock size={14} className="mr-1" />
                    {request.timeLeft}
                  </span>
                </div>
              </div>

              {/* Request Details */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div className="flex items-center">
                  <div className="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                    {request.customer.initials}
                  </div>
                  <div>
                    <div className="font-semibold text-gray-900 dark:text-gray-100">
                      {request.customer.name}
                    </div>
                    <div className="text-sm text-gray-500 dark:text-gray-400">
                      {request.customer.email}
                    </div>
                    <div className="text-xs text-gray-400 dark:text-gray-500">
                      {request.customer.phone}
                    </div>
                  </div>
                </div>
                <div className="space-y-2">
                  <div className="text-sm text-gray-700 dark:text-gray-300">
                    {request.details}
                  </div>
                  <div className="flex items-center justify-between">
                    <div>
                      <div className="font-bold text-lg text-green-600 dark:text-green-400">
                        {request.requestedPrice}
                      </div>
                      <div className="text-sm text-gray-500 dark:text-gray-400">
                        Market: {request.marketPrice}
                      </div>
                    </div>
                    {request.marketComparison && (
                      <div className="text-xs text-green-600 dark:text-green-400 font-semibold bg-green-100 dark:bg-green-900 px-2 py-1 rounded">
                        {request.marketComparison}% below market
                      </div>
                    )}
                  </div>
                </div>
              </div>

              {/* Notes & Actions */}
              <div className="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-700">
                {request.notes && (
                  <div className="text-sm text-gray-600 dark:text-gray-400">
                    <span className="font-semibold">Notes: </span>
                    {request.notes}
                  </div>
                )}
                <div className="flex gap-3 ml-auto">
                  {request.status === "pending" && (
                    <>
                      <button
                        onClick={() => handleDecline(request._id)}
                        className="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg font-semibold"
                      >
                        <XCircle size={16} />
                        Decline
                      </button>
                      <button
                        onClick={() => handleNegotiate(request._id)}
                        className="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-semibold text-gray-900 dark:text-gray-100"
                      >
                        <MessageSquare size={16} />
                        Negotiate
                      </button>
                      <button
                        onClick={() => handleAccept(request._id)}
                        className="flex items-center gap-2 px-4 py-2 bg-cyan-600 text-white hover:bg-cyan-700 rounded-lg font-semibold"
                      >
                        <CheckCircle size={16} />
                        Accept
                      </button>
                    </>
                  )}
                  {request.status === "negotiating" && (
                    <>
                      <button
                        onClick={() => handleDecline(request._id)}
                        className="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg font-semibold"
                      >
                        <XCircle size={16} />
                        Decline
                      </button>
                      <button
                        onClick={() => handleNegotiate(request._id)}
                        className="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg font-semibold"
                      >
                        <MessageSquare size={16} />
                        Counter Offer
                      </button>
                      <button
                        onClick={() => handleAccept(request._id)}
                        className="flex items-center gap-2 px-4 py-2 bg-cyan-600 text-white hover:bg-cyan-700 rounded-lg font-semibold"
                      >
                        <CheckCircle size={16} />
                        Accept Offer
                      </button>
                    </>
                  )}
                  {(request.status === "accepted" ||
                    request.status === "declined") && (
                    <div className="text-sm text-gray-500 italic">
                      Request {request.status}
                    </div>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>

        {filteredRequests.length === 0 && (
          <div className="text-center py-12">
            <Clock
              size={48}
              className="mx-auto text-gray-300 dark:text-gray-600 mb-4"
            />
            <p className="text-gray-500 dark:text-gray-400">
              No booking requests found
            </p>
            <p className="text-sm text-gray-400 dark:text-gray-500 mt-1">
              {requests.length > 0
                ? "Try adjusting your search or filters"
                : "No booking requests have been made yet"}
            </p>
          </div>
        )}
      </div>
    </div>
  );
};

export default PendingRequests;
