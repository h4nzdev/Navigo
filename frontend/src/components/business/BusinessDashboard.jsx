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

const BusinessDashboard = () => {
  const { user } = useContext(AuthContext);
  const [businessData, setBusinessData] = useState(null);
  const [userData, setUserData] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [activeTab, setActiveTab] = useState("requests");
  const [timeFilter, setTimeFilter] = useState("today");
  const [isCreateScheduleModalOpen, setIsCreateScheduleModalOpen] =
    useState(false);

  // Mock requests data
  const requests = [
    {
      id: "BR-001",
      customer: "Anna Mae Regis",
      customerEmail: "anna.regis@email.com",
      customerInitials: "AMR",
      type: "flight",
      route: "New York (JFK) → Paris (CDG)",
      details: "2 passengers • Business Class",
      requestedPrice: "₱2,400",
      marketComparison: "-15%",
      status: "pending",
      priority: "high",
      timeLeft: "2h left",
      createdAt: "2024-01-15T10:30:00",
    },
    {
      id: "BR-002",
      customer: "John Smith",
      customerEmail: "john.smith@email.com",
      customerInitials: "JS",
      type: "hotel",
      route: "Tokyo, Japan",
      details: "4 nights • 2 rooms • Deluxe Suite",
      requestedPrice: "₱18,500",
      marketComparison: "-8%",
      status: "accepted",
      priority: "medium",
      timeLeft: "Completed",
      createdAt: "2024-01-14T14:20:00",
    },
    {
      id: "BR-003",
      customer: "Maria Garcia",
      customerEmail: "maria.garcia@email.com",
      customerInitials: "MG",
      type: "car",
      route: "Rental - Barcelona",
      details: "7 days • SUV • Full Insurance",
      requestedPrice: "₱9,200",
      marketComparison: "-12%",
      status: "negotiating",
      priority: "medium",
      timeLeft: "1d left",
      createdAt: "2024-01-15T09:15:00",
    },
  ];

  // Stats data - Updated to use userData
  const stats = [
    {
      title: "Total Requests",
      value: "24",
      change: "+15%",
      isPositive: true,
      icon: TrendingUp,
      color: "bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300",
    },
    {
      title: "Active Customers",
      value: "156",
      change: "+8%",
      isPositive: true,
      icon: Users,
      color:
        "bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300",
    },
    {
      title: "Revenue (MTD)",
      value: userData?.currency === "USD" ? "$248,650" : "₱248,650",
      change: "+24%",
      isPositive: true,
      icon: DollarSign,
      color:
        "bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300",
    },
    {
      title: "Avg Response Time",
      value: "1.2h",
      change: "-0.3h",
      isPositive: true,
      icon: Clock,
      color:
        "bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-300",
    },
  ];

  // Recent activities
  const activities = [
    {
      type: "request",
      title: "New booking request received",
      description: "Flight: NYC → LHR • Business Class",
      time: "10 minutes ago",
      icon: Plane,
    },
    {
      type: "payment",
      title: "Payment confirmed",
      description: `${
        userData?.currency === "USD" ? "$" : "₱"
      }12,400 • Booking #BK-4567`,
      time: "2 hours ago",
      icon: CreditCard,
    },
    {
      type: "customer",
      title: "New customer registered",
      description: "Emily Chen • 5-star rating",
      time: "5 hours ago",
      icon: Users,
    },
  ];

  // Fetch user and business data
  useEffect(() => {
    const fetchData = async () => {
      if (user?._id) {
        try {
          // Fetch both user data and business data in parallel
          const [userDataResponse, businessDataResponse] = await Promise.all([
            getUserData(user._id),
            getBusinessProfile(user._id),
          ]);

          setUserData(userDataResponse);
          setBusinessData(businessDataResponse);
        } catch (error) {
          console.error("Error fetching data:", error);
        } finally {
          setIsLoading(false);
        }
      }
    };

    fetchData();
  }, [user]);

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
        return "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200";
      case "negotiating":
        return "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200";
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
      default:
        return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200";
    }
  };

  // Format date based on user's preference
  const formatDate = (dateString) => {
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

  // Get user initials
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

  return (
    <div className="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen font-sans">
      <CreateScheduleModal
        isOpen={isCreateScheduleModalOpen}
        onClose={() => setIsCreateScheduleModalOpen(false)}
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
          {stats.map((stat, index) => (
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
                  // Profile Tab
                  <div className="space-y-6">
                    <div className="flex items-center justify-between mb-6">
                      <h3 className="text-xl font-bold">Personal Profile</h3>
                      <button className="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">
                        Edit Profile
                      </button>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div className="space-y-4">
                        <div className="flex items-center gap-3">
                          <User className="text-gray-400" size={20} />
                          <div>
                            <div className="text-sm text-gray-500">Name</div>
                            <div className="font-semibold">
                              {userData
                                ? `${userData.first_name} ${userData.last_name}`
                                : "Not available"}
                            </div>
                          </div>
                        </div>

                        <div className="flex items-center gap-3">
                          <Mail className="text-gray-400" size={20} />
                          <div>
                            <div className="text-sm text-gray-500">Email</div>
                            <div className="font-semibold">
                              {userData?.email || "Not available"}
                            </div>
                          </div>
                        </div>

                        <div className="flex items-center gap-3">
                          <Phone className="text-gray-400" size={20} />
                          <div>
                            <div className="text-sm text-gray-500">Phone</div>
                            <div className="font-semibold">
                              {userData?.phone || "Not available"}
                            </div>
                          </div>
                        </div>

                        <div className="flex items-center gap-3">
                          <Calendar className="text-gray-400" size={20} />
                          <div>
                            <div className="text-sm text-gray-500">
                              Date of Birth
                            </div>
                            <div className="font-semibold">
                              {userData?.date_of_birth
                                ? formatDate(userData.date_of_birth)
                                : "Not available"}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div className="space-y-4">
                        <div className="flex items-center gap-3">
                          <MapPin className="text-gray-400" size={20} />
                          <div>
                            <div className="text-sm text-gray-500">
                              Location
                            </div>
                            <div className="font-semibold">
                              {userData?.location || "Not specified"}
                            </div>
                          </div>
                        </div>

                        <div className="flex items-center gap-3">
                          <Globe className="text-gray-400" size={20} />
                          <div>
                            <div className="text-sm text-gray-500">
                              Timezone
                            </div>
                            <div className="font-semibold">
                              {userData?.timezone || "Not specified"}
                            </div>
                          </div>
                        </div>

                        <div className="flex items-center gap-3">
                          <div className="text-gray-400 font-bold">$</div>
                          <div>
                            <div className="text-sm text-gray-500">
                              Currency
                            </div>
                            <div className="font-semibold">
                              {userData?.currency || "Not specified"}
                            </div>
                          </div>
                        </div>

                        <div className="flex items-center gap-3">
                          <div className="text-gray-400 font-bold">📅</div>
                          <div>
                            <div className="text-sm text-gray-500">
                              Date Format
                            </div>
                            <div className="font-semibold">
                              {userData?.date_format || "Not specified"}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    {/* Bio Section */}
                    {userData?.bio && (
                      <div className="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div className="text-sm text-gray-500 mb-2">Bio</div>
                        <div className="text-gray-700 dark:text-gray-300">
                          {userData.bio}
                        </div>
                      </div>
                    )}

                    {/* Subscription Info */}
                    <div className="mt-6 p-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-lg">
                      <div className="flex justify-between items-center">
                        <div>
                          <div className="text-sm opacity-80">
                            Subscription Plan
                          </div>
                          <div className="text-xl font-bold">
                            {userData?.subscription_plan || "Premium"}
                          </div>
                        </div>
                        <div className="text-right">
                          <div
                            className={`text-sm font-semibold ${getSubscriptionColor(
                              userData?.subscription_status
                            )}`}
                          >
                            {userData?.subscription_status?.toUpperCase() ||
                              "ACTIVE"}
                          </div>
                          <div className="text-xs opacity-80">
                            Auto-renew: {userData?.auto_renew ? "ON" : "OFF"}
                          </div>
                        </div>
                      </div>
                    </div>
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
                        </select>
                      </div>
                    </div>

                    {/* Requests List */}
                    <div className="space-y-4">
                      {requests.map((request) => (
                        <div
                          key={request.id}
                          className="border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition-shadow"
                        >
                          {/* Request Header */}
                          <div className="flex justify-between items-start mb-4">
                            <div className="flex items-center gap-3">
                              {getTypeIcon(request.type)}
                              <div>
                                <div className="font-bold">{request.id}</div>
                                <div className="text-sm text-gray-500">
                                  {request.route}
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
                              <span className="text-sm text-gray-500 flex items-center">
                                <Clock size={14} className="mr-1" />
                                {request.timeLeft}
                              </span>
                            </div>
                          </div>

                          {/* Request Details */}
                          <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div className="flex items-center">
                              <div className="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                {request.customerInitials}
                              </div>
                              <div>
                                <div className="font-semibold">
                                  {request.customer}
                                </div>
                                <div className="text-sm text-gray-500">
                                  {request.customerEmail}
                                </div>
                              </div>
                            </div>
                            <div className="space-y-1">
                              <div className="text-sm">{request.details}</div>
                              <div className="font-bold text-lg text-green-600">
                                {request.requestedPrice}
                                <span className="ml-2 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs font-semibold px-2 py-1 rounded-full">
                                  {request.marketComparison} below market
                                </span>
                              </div>
                            </div>
                          </div>

                          {/* Actions */}
                          <div className="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <button className="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg font-semibold">
                              <XCircle size={16} />
                              Decline
                            </button>
                            <button className="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-semibold">
                              <MessageSquare size={16} />
                              Negotiate
                            </button>
                            <button className="flex items-center gap-2 px-4 py-2 bg-cyan-600 text-white hover:bg-cyan-700 rounded-lg font-semibold">
                              <CheckCircle size={16} />
                              Accept
                            </button>
                          </div>
                        </div>
                      ))}
                    </div>

                    {/* View All Button */}
                    <div className="mt-6 text-center">
                      <button className="flex items-center gap-2 text-cyan-600 hover:text-cyan-700 font-semibold mx-auto">
                        View All Requests <ChevronRight size={16} />
                      </button>
                    </div>
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
                  <div className="text-2xl font-bold">42.5%</div>
                  <div className="text-sm opacity-90">
                    +5.2% from last month
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
                  <span className="font-semibold">2025</span>
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
                  <span className="font-semibold">12</span>
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
                <button className="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors flex flex-col items-center justify-center">
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
