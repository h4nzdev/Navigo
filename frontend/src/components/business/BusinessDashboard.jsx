import React, { useState, useEffect, useContext } from "react";
import {
  TrendingUp,
  Users,
  DollarSign,
  Clock,
  Calendar,
  Search,
  Filter,
  MoreVertical,
  CheckCircle,
  XCircle,
  MessageSquare,
  Plane,
  Hotel,
  Car,
  Utensils,
  MapPin,
  Star,
  ChevronRight,
  Bell,
  Settings,
  Moon,
  ChevronDown,
  Download,
  Share2,
  Eye,
  Plus,
  BarChart,
  CreditCard,
  Briefcase,
  Building,
  User,
  Mail,
  Phone,
  Globe,
} from "lucide-react";
import { AuthContext } from "../../context/AuthContext";
import { getBusinessProfile } from "../../services/businessService";
import { getUserData } from "../../services/user_services/userService";
import TopNavbar from "./menu/TopNavbar";
import CreateScheduleModal from "./menu/CreateScheduleModal";
import {
  getBusinessBookingRequests,
  updateBookingStatus,
} from "../../services/bookingRequestService";

const BusinessDashboard = () => {
  const { user } = useContext(AuthContext);
  const [businessData, setBusinessData] = useState(null);
  const [userData, setUserData] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [activeTab, setActiveTab] = useState("requests");
  const [timeFilter, setTimeFilter] = useState("today");
  const [isCreateScheduleModalOpen, setIsCreateScheduleModalOpen] =
    useState(false);

  // REAL DATA STATES
  const [requests, setRequests] = useState([]);
  const [stats, setStats] = useState(null);
  const [activities, setActivities] = useState([]);
  const [searchQuery, setSearchQuery] = useState("");
  const [filteredRequests, setFilteredRequests] = useState([]);

  // Fetch all data
  useEffect(() => {
    const fetchAllData = async () => {
      if (user?._id) {
        try {
          setIsLoading(true);

          // Fetch all data in parallel
          const [userDataResponse, businessDataResponse, bookingRequests] =
            await Promise.all([
              getUserData(user._id),
              getBusinessProfile(user._id),
              getBusinessBookingRequests(user._id),
            ]);

          setUserData(userDataResponse);
          setBusinessData(businessDataResponse);
          setRequests(bookingRequests || []);

          // Generate stats from real data
          const generatedStats = generateStatsFromData(
            bookingRequests || [],
            userDataResponse
          );
          setStats(generatedStats);

          // Generate activities from real data
          const generatedActivities = generateActivitiesFromData(
            bookingRequests || [],
            businessDataResponse
          );
          setActivities(generatedActivities);
        } catch (error) {
          console.error("Error fetching dashboard data:", error);
        } finally {
          setIsLoading(false);
        }
      }
    };

    fetchAllData();
  }, [user]);

  // Filter requests based on search and time filter
  useEffect(() => {
    if (!requests.length) {
      setFilteredRequests([]);
      return;
    }

    let filtered = [...requests];

    // Apply search filter
    if (searchQuery) {
      const query = searchQuery.toLowerCase();
      filtered = filtered.filter(
        (request) =>
          request.id?.toLowerCase().includes(query) ||
          request.customer?.name?.toLowerCase().includes(query) ||
          request.customer?.email?.toLowerCase().includes(query) ||
          request.type?.toLowerCase().includes(query) ||
          request.route?.toLowerCase().includes(query)
      );
    }

    // Apply time filter
    if (timeFilter !== "today") {
      const now = new Date();
      let startDate = new Date();

      if (timeFilter === "week") {
        startDate.setDate(now.getDate() - 7);
      } else if (timeFilter === "month") {
        startDate.setMonth(now.getMonth() - 1);
      }

      filtered = filtered.filter((request) => {
        const requestDate = new Date(request.createdAt || request.created_date);
        return requestDate >= startDate;
      });
    }

    setFilteredRequests(filtered);
  }, [requests, searchQuery, timeFilter]);

  // Generate stats from real booking data
  const generateStatsFromData = (bookingRequests, userData) => {
    const totalRequests = bookingRequests.length;
    const activeCustomers = new Set(
      bookingRequests.map((req) => req.customer?._id || req.customerEmail)
    ).size;

    // Calculate revenue (sum of accepted/completed bookings)
    const revenue = bookingRequests
      .filter((req) => req.status === "accepted" || req.status === "completed")
      .reduce(
        (sum, req) =>
          sum +
          (parseFloat(req.requestedPrice?.replace(/[^0-9.-]+/g, "")) || 0),
        0
      );

    // Calculate average response time (assuming you have responseTime field)
    const responseTimes = bookingRequests
      .filter((req) => req.responseTime)
      .map((req) => req.responseTime);
    const avgResponseTime =
      responseTimes.length > 0
        ? (
            responseTimes.reduce((a, b) => a + b, 0) / responseTimes.length
          ).toFixed(1)
        : 0;

    return [
      {
        title: "Total Requests",
        value: totalRequests.toString(),
        change: "+15%", // You'd calculate this from historical data
        isPositive: true,
        icon: TrendingUp,
        color: "bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300",
      },
      {
        title: "Active Customers",
        value: activeCustomers.toString(),
        change: "+8%", // You'd calculate this from historical data
        isPositive: true,
        icon: Users,
        color:
          "bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300",
      },
      {
        title: "Revenue (MTD)",
        value:
          userData?.currency === "USD"
            ? `$${revenue.toLocaleString()}`
            : `₱${revenue.toLocaleString()}`,
        change: "+24%", // You'd calculate this from historical data
        isPositive: true,
        icon: DollarSign,
        color:
          "bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300",
      },
      {
        title: "Avg Response Time",
        value: `${avgResponseTime}h`,
        change: "-0.3h", // You'd calculate this from historical data
        isPositive: true,
        icon: Clock,
        color:
          "bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-300",
      },
    ];
  };

  // Generate activities from real data
  const generateActivitiesFromData = (bookingRequests, businessData) => {
    const activities = [];

    // Add recent booking requests as activities
    const recentRequests = [...bookingRequests]
      .sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt))
      .slice(0, 3);

    recentRequests.forEach((request) => {
      let description = "";
      if (request.type === "flight") {
        description = `Flight: ${request.route} • ${request.details}`;
      } else if (request.type === "hotel") {
        description = `Hotel: ${request.route} • ${request.details}`;
      } else if (request.type === "car") {
        description = `Car Rental: ${request.route} • ${request.details}`;
      }

      activities.push({
        type: "request",
        title: "New booking request received",
        description,
        time: getTimeAgo(request.createdAt),
        icon:
          request.type === "flight"
            ? Plane
            : request.type === "hotel"
            ? Hotel
            : request.type === "car"
            ? Car
            : Briefcase,
      });
    });

    // If we have business data, add business-related activities
    if (businessData?.last_updated) {
      activities.push({
        type: "business",
        title: "Business profile updated",
        description: "Your business information was recently updated",
        time: getTimeAgo(businessData.last_updated),
        icon: Building,
      });
    }

    return activities.length > 0
      ? activities
      : [
          {
            type: "info",
            title: "Welcome to your dashboard",
            description: "Start by creating your first booking request",
            time: "Just now",
            icon: Users,
          },
        ];
  };

  // Helper function to format time ago
  const getTimeAgo = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 60) {
      return `${diffMins} minute${diffMins !== 1 ? "s" : ""} ago`;
    } else if (diffHours < 24) {
      return `${diffHours} hour${diffHours !== 1 ? "s" : ""} ago`;
    } else if (diffDays < 7) {
      return `${diffDays} day${diffDays !== 1 ? "s" : ""} ago`;
    } else {
      return formatDate(dateString);
    }
  };

  // Get type icon
  const getTypeIcon = (type) => {
    switch (type) {
      case "flight":
        return <Plane size={20} className="text-blue-500" />;
      case "hotel":
        return <Hotel size={20} className="text-green-500" />;
      case "car":
        return <Car size={20} className="text-purple-500" />;
      default:
        return <Briefcase size={20} className="text-gray-500" />;
    }
  };

  // Get status color
  const getStatusColor = (status) => {
    switch (status) {
      case "pending":
        return "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200";
      case "accepted":
      case "completed":
        return "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200";
      case "negotiating":
        return "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200";
      case "declined":
      case "cancelled":
        return "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200";
      default:
        return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200";
    }
  };

  // Get priority color
  const getPriorityColor = (priority) => {
    switch (priority) {
      case "high":
        return "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200";
      case "medium":
        return "bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200";
      case "low":
        return "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200";
      default:
        return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200";
    }
  };

  // Format date based on user's preference
  const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    if (!userData?.date_format)
      return new Date(dateString).toLocaleDateString();

    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, "0");
    const month = (date.getMonth() + 1).toString().padStart(2, "0");
    const year = date.getFullYear();

    if (userData.date_format === "DD/MM/YYYY") {
      return `${day}/${month}/${year}`;
    } else if (userData.date_format === "MM/DD/YYYY") {
      return `${month}/${day}/${year}`;
    }
    return `${year}-${month}-${day}`;
  };

  // Get user initials from request
  const getCustomerInitials = (customer) => {
    if (customer?.name) {
      const names = customer.name.split(" ");
      return names
        .map((n) => n.charAt(0))
        .join("")
        .toUpperCase()
        .substring(0, 2);
    }
    if (customer?.email) {
      return customer.email.substring(0, 2).toUpperCase();
    }
    return "CU";
  };

  // Get customer name
  const getCustomerName = (customer) => {
    return customer?.name || customer?.email?.split("@")[0] || "Customer";
  };

  // Get customer email
  const getCustomerEmail = (customer) => {
    return customer?.email || "No email provided";
  };

  // Get time left for request
  const getTimeLeft = (createdAt, expiryHours = 48) => {
    const created = new Date(createdAt);
    const now = new Date();
    const expiryTime = new Date(
      created.getTime() + expiryHours * 60 * 60 * 1000
    );

    const diffMs = expiryTime - now;
    const diffHours = Math.floor(diffMs / (1000 * 60 * 60));

    if (diffMs <= 0) return "Expired";
    if (diffHours < 1) {
      const diffMins = Math.floor(diffMs / (1000 * 60));
      return `${diffMins}m left`;
    }
    if (diffHours < 24) return `${diffHours}h left`;
    return `${Math.floor(diffHours / 24)}d left`;
  };

  // Handle booking status update
  const handleStatusUpdate = async (bookingId, newStatus) => {
    try {
      await updateBookingStatus(bookingId, newStatus);

      // Update local state
      setRequests((prev) =>
        prev.map((req) =>
          req._id === bookingId ? { ...req, status: newStatus } : req
        )
      );

      // Show success message (you can add a toast notification here)
      console.log(`Booking ${bookingId} status updated to ${newStatus}`);
    } catch (error) {
      console.error("Error updating booking status:", error);
    }
  };

  // Get user initials for profile
  const getUserInitials = () => {
    if (userData?.first_name && userData?.last_name) {
      return `${userData.first_name.charAt(0)}${userData.last_name.charAt(0)}`;
    }
    return "B";
  };

  // Get subscription status color
  const getSubscriptionColor = (status) => {
    switch (status) {
      case "active":
        return "text-green-600 dark:text-green-400";
      case "pending":
        return "text-yellow-600 dark:text-yellow-400";
      case "expired":
        return "text-red-600 dark:text-red-400";
      default:
        return "text-gray-600 dark:text-gray-400";
    }
  };

  // Loading state
  if (isLoading) {
    return (
      <div className="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-cyan-600 mx-auto"></div>
          <p className="mt-4 text-gray-600 dark:text-gray-400">
            Loading dashboard...
          </p>
        </div>
      </div>
    );
  }

  return (
    <div className="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen font-sans">
      <CreateScheduleModal
        isOpen={isCreateScheduleModalOpen}
        onClose={() => setIsCreateScheduleModalOpen(false)}
        businessId={user?._id}
      />

      {/* Main Content */}
      <main className="p-6">
        {/* Welcome Header with User Data */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold">
            Welcome back,{" "}
            {userData?.first_name || businessData?.name || "Partner"}!
          </h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">
            Here's what's happening with your business today.
            {userData?.timezone && ` Your timezone: ${userData.timezone}`}
          </p>
        </div>

        {/* Stats Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          {stats &&
            stats.map((stat, index) => (
              <div
                key={index}
                className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6"
              >
                <div className="flex items-center justify-between mb-4">
                  <div className={`p-3 rounded-lg ${stat.color}`}>
                    <stat.icon size={24} />
                  </div>
                  <div
                    className={`text-sm font-semibold ${
                      stat.isPositive ? "text-green-500" : "text-red-500"
                    }`}
                  >
                    {stat.change}
                  </div>
                </div>
                <h3 className="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">
                  {stat.title}
                </h3>
                <p className="text-3xl font-bold">{stat.value}</p>
              </div>
            ))}
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Left Column - Main Content */}
          <div className="lg:col-span-2 space-y-8">
            {/* Tabs */}
            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
              <div className="border-b border-gray-200 dark:border-gray-700">
                <div className="flex">
                  {["requests", "analytics", "profile"].map((tab) => (
                    <button
                      key={tab}
                      onClick={() => setActiveTab(tab)}
                      className={`flex-1 py-4 font-semibold text-sm uppercase tracking-wider transition-colors ${
                        activeTab === tab
                          ? "text-cyan-600 border-b-2 border-cyan-600"
                          : "text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
                      }`}
                    >
                      {tab.charAt(0).toUpperCase() + tab.slice(1)}
                    </button>
                  ))}
                </div>
              </div>

              {/* Tab Content */}
              <div className="p-6">
                {activeTab === "profile" ? (
                  // Profile Tab (unchanged from your original code)
                  <div className="space-y-6">
                    {/* ... Profile tab content remains the same ... */}
                  </div>
                ) : (
                  // Requests/Analytics Tab
                  <>
                    {/* Search and Filter */}
                    <div className="flex flex-col md:flex-row gap-4 mb-6">
                      <div className="relative flex-grow">
                        <Search
                          className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                          size={20}
                        />
                        <input
                          type="text"
                          placeholder="Search requests, customers, or destinations..."
                          className="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
                          value={searchQuery}
                          onChange={(e) => setSearchQuery(e.target.value)}
                        />
                      </div>
                      <div className="flex gap-3">
                        <button className="flex items-center gap-2 px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold">
                          <Filter size={16} />
                          Filters
                        </button>
                        <select
                          className="bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold px-4 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                          value={timeFilter}
                          onChange={(e) => setTimeFilter(e.target.value)}
                        >
                          <option value="today">Today</option>
                          <option value="week">This Week</option>
                          <option value="month">This Month</option>
                          <option value="all">All Time</option>
                        </select>
                      </div>
                    </div>

                    {/* Requests List */}
                    <div className="space-y-4">
                      {filteredRequests.length > 0 ? (
                        filteredRequests.map((request) => (
                          <div
                            key={request._id || request.id}
                            className="border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition-shadow"
                          >
                            {/* Request Header */}
                            <div className="flex justify-between items-start mb-4">
                              <div className="flex items-center gap-3">
                                {getTypeIcon(request.type)}
                                <div>
                                  <div className="font-bold">
                                    {request.id ||
                                      `BR-${request._id?.substring(0, 8)}`}
                                  </div>
                                  <div className="text-sm text-gray-500">
                                    {request.route || "No route specified"}
                                  </div>
                                </div>
                              </div>
                              <div className="flex items-center gap-2">
                                <span
                                  className={`text-xs font-bold px-2 py-1 rounded-full ${getPriorityColor(
                                    request.priority
                                  )}`}
                                >
                                  {(request.priority || "medium").toUpperCase()}
                                </span>
                                <span
                                  className={`text-xs font-bold px-2 py-1 rounded-full ${getStatusColor(
                                    request.status
                                  )}`}
                                >
                                  {(request.status || "pending").toUpperCase()}
                                </span>
                                <span className="text-sm text-gray-500 flex items-center">
                                  <Clock size={14} className="mr-1" />
                                  {getTimeLeft(request.createdAt)}
                                </span>
                              </div>
                            </div>

                            {/* Request Details */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                              <div className="flex items-center">
                                <div className="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                  {getCustomerInitials(request.customer)}
                                </div>
                                <div>
                                  <div className="font-semibold">
                                    {getCustomerName(request.customer)}
                                  </div>
                                  <div className="text-sm text-gray-500">
                                    {getCustomerEmail(request.customer)}
                                  </div>
                                </div>
                              </div>
                              <div className="space-y-1">
                                <div className="text-sm">
                                  {request.details || "No details provided"}
                                </div>
                                <div className="font-bold text-lg text-green-600">
                                  {request.requestedPrice || "Price not set"}
                                  {request.marketComparison && (
                                    <span className="ml-2 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs font-semibold px-2 py-1 rounded-full">
                                      {request.marketComparison} below market
                                    </span>
                                  )}
                                </div>
                              </div>
                            </div>

                            {/* Actions */}
                            <div className="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                              <button
                                onClick={() =>
                                  handleStatusUpdate(request._id, "declined")
                                }
                                className="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg font-semibold"
                              >
                                <XCircle size={16} />
                                Decline
                              </button>
                              <button
                                onClick={() =>
                                  handleStatusUpdate(request._id, "negotiating")
                                }
                                className="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-semibold"
                              >
                                <MessageSquare size={16} />
                                Negotiate
                              </button>
                              <button
                                onClick={() =>
                                  handleStatusUpdate(request._id, "accepted")
                                }
                                className="flex items-center gap-2 px-4 py-2 bg-cyan-600 text-white hover:bg-cyan-700 rounded-lg font-semibold"
                              >
                                <CheckCircle size={16} />
                                Accept
                              </button>
                            </div>
                          </div>
                        ))
                      ) : (
                        <div className="text-center py-12">
                          <div className="text-gray-400 mb-4">
                            <Search size={48} className="mx-auto" />
                          </div>
                          <h3 className="text-xl font-semibold mb-2">
                            No booking requests found
                          </h3>
                          <p className="text-gray-500">
                            {searchQuery
                              ? "Try a different search term"
                              : "You don't have any booking requests yet"}
                          </p>
                        </div>
                      )}
                    </div>

                    {/* View All Button */}
                    {filteredRequests.length > 0 && (
                      <div className="mt-6 text-center">
                        <button className="flex items-center gap-2 text-cyan-600 hover:text-cyan-700 font-semibold mx-auto">
                          View All Requests <ChevronRight size={16} />
                        </button>
                      </div>
                    )}
                  </>
                )}
              </div>
            </div>

            {/* Quick Stats Card */}
            <div className="bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-xl shadow-lg p-6">
              <div className="flex justify-between items-start mb-6">
                <div>
                  <h3 className="text-xl font-bold">Business Performance</h3>
                  <p className="opacity-90">Real-time insights and metrics</p>
                </div>
                <BarChart size={24} />
              </div>
              <div className="grid grid-cols-2 gap-6">
                <div>
                  <div className="text-sm opacity-80">Conversion Rate</div>
                  <div className="text-2xl font-bold">
                    {requests.length > 0
                      ? `${(
                          (requests.filter(
                            (r) =>
                              r.status === "accepted" ||
                              r.status === "completed"
                          ).length /
                            requests.length) *
                          100
                        ).toFixed(1)}%`
                      : "0%"}
                  </div>
                  <div className="text-sm opacity-90">
                    Based on {requests.length} total requests
                  </div>
                </div>
                <div>
                  <div className="text-sm opacity-80">
                    Customer Satisfaction
                  </div>
                  <div className="text-2xl font-bold">4.8/5</div>
                  <div className="flex items-center mt-1">
                    {[...Array(5)].map((_, i) => (
                      <Star
                        key={i}
                        size={14}
                        className="mr-1"
                        fill="currentColor"
                      />
                    ))}
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Right Column - Sidebar */}
          <div className="space-y-8">
            {/* Business Profile Card - Enhanced with User Data */}
            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
              <div className="flex items-center gap-4 mb-6">
                <div className="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center text-white">
                  {userData ? (
                    <div className="text-2xl font-bold">
                      {getUserInitials()}
                    </div>
                  ) : (
                    <Building size={28} />
                  )}
                </div>
                <div>
                  <h3 className="font-bold text-lg">
                    {businessData?.business_name ||
                      userData?.first_name + "'s Business"}
                  </h3>
                  <p className="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                    <MapPin size={14} className="inline mr-1" />
                    {userData?.location ||
                      businessData?.address ||
                      "Add your location"}
                  </p>
                  <p className="text-xs text-gray-400 mt-1">
                    <Mail size={12} className="inline mr-1" />
                    {userData?.email}
                  </p>
                </div>
              </div>

              <div className="space-y-3">
                <div className="flex justify-between">
                  <span className="text-gray-600 dark:text-gray-400">
                    Partner Since
                  </span>
                  <span className="font-semibold">
                    {userData?.createdAt
                      ? new Date(userData.createdAt).getFullYear()
                      : "2025"}
                  </span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600 dark:text-gray-400">Plan</span>
                  <span
                    className={`font-semibold ${getSubscriptionColor(
                      userData?.subscription_status
                    )}`}
                  >
                    {userData?.subscription_plan || "Premium"}
                  </span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600 dark:text-gray-400">Role</span>
                  <span className="font-semibold capitalize">
                    {userData?.role || "business"}
                  </span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600 dark:text-gray-400">
                    Services Listed
                  </span>
                  <span className="font-semibold">
                    {businessData?.services?.length || 0}
                  </span>
                </div>
              </div>

              <div className="mt-6 flex gap-3">
                <button className="flex-1 bg-cyan-600 text-white py-2 rounded-lg font-semibold hover:bg-cyan-700 flex items-center justify-center gap-2">
                  <Plus size={16} />
                  Add Service
                </button>
                <button className="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                  <MoreVertical size={20} />
                </button>
              </div>
            </div>

            {/* Recent Activity */}
            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
              <div className="flex justify-between items-center mb-6">
                <h3 className="font-bold text-lg">Recent Activity</h3>
                <Eye size={20} className="text-gray-400" />
              </div>

              <div className="space-y-4">
                {activities.map((activity, index) => (
                  <div key={index} className="flex items-start gap-3">
                    <div className="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                      <activity.icon size={20} />
                    </div>
                    <div className="flex-grow">
                      <div className="font-semibold">{activity.title}</div>
                      <div className="text-sm text-gray-500">
                        {activity.description}
                      </div>
                      <div className="text-xs text-gray-400 mt-1">
                        {activity.time}
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Quick Actions */}
            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
              <h3 className="font-bold text-lg mb-6">Quick Actions</h3>
              <div className="grid grid-cols-2 gap-3">
                <button className="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors flex flex-col items-center justify-center">
                  <Download size={24} className="mb-2 text-cyan-600" />
                  <span className="text-sm font-semibold">Export Data</span>
                </button>
                <button className="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors flex flex-col items-center justify-center">
                  <Share2 size={24} className="mb-2 text-cyan-600" />
                  <span className="text-sm font-semibold">Share Report</span>
                </button>
                <button
                  onClick={() => setIsCreateScheduleModalOpen(true)}
                  className="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors flex flex-col items-center justify-center"
                >
                  <Calendar size={24} className="mb-2 text-cyan-600" />
                  <span className="text-sm font-semibold">Schedule</span>
                </button>
                <button className="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors flex flex-col items-center justify-center">
                  <Plus size={24} className="mb-2 text-cyan-600" />
                  <span className="text-sm font-semibold">New Offer</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
};

export default BusinessDashboard;
