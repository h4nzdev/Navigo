import React, { useState, useEffect, useContext } from "react";
import {
  BarChart3,
  LineChart,
  PieChart,
  TrendingUp,
  TrendingDown,
  DollarSign,
  Users,
  Calendar,
  Plane,
  Download,
  Filter,
  ChevronRight,
} from "lucide-react";
import { AuthContext } from "../../context/AuthContext";

const Analytics = () => {
  const { user } = useContext(AuthContext);
  const [timeFilter, setTimeFilter] = useState("month");
  const [loading, setLoading] = useState(false);

  // Mock analytics data
  const analyticsData = {
    revenue: {
      total: "₱248,650",
      change: "+24%",
      isPositive: true,
      data: [
        45000, 52000, 48000, 62000, 58000, 72000, 68000, 79000, 85000, 92000,
        88000, 95000,
      ],
      labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
    },
    bookings: {
      total: "1,248",
      change: "+15%",
      isPositive: true,
      data: [120, 145, 130, 165, 150, 180, 170, 195, 210, 225, 220, 235],
      labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
    },
    customers: {
      total: "1,024",
      change: "+12%",
      isPositive: true,
      data: [85, 92, 88, 95, 90, 102, 98, 108, 115, 120, 118, 125],
      labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
    },
    routes: [
      { name: "MNL → CEB", value: 245, percentage: 32 },
      { name: "MNL → DVO", value: 180, percentage: 24 },
      { name: "CEB → MPH", value: 120, percentage: 16 },
      { name: "DVO → MNL", value: 95, percentage: 13 },
      { name: "Others", value: 115, percentage: 15 },
    ],
    performance: {
      conversion: "42.5%",
      satisfaction: "4.8/5",
      responseTime: "1.2h",
      occupancy: "78%",
    },
  };

  const formatCurrency = (amount) => {
    return `₱${parseInt(amount).toLocaleString()}`;
  };

  if (loading) {
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
            Analytics Dashboard
          </h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">
            Insights and performance metrics for your business
          </p>
        </div>
        <div className="flex gap-3">
          <select
            value={timeFilter}
            onChange={(e) => setTimeFilter(e.target.value)}
            className="bg-white dark:bg-gray-800 rounded-lg font-semibold px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700"
          >
            <option value="week">Last Week</option>
            <option value="month">Last Month</option>
            <option value="quarter">Last Quarter</option>
            <option value="year">Last Year</option>
          </select>
          <button className="flex items-center gap-2 px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 font-semibold">
            <Download size={16} />
            Export
          </button>
        </div>
      </div>

      {/* Key Metrics */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div className="flex items-center justify-between mb-4">
            <div className="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
              <DollarSign
                className="text-blue-600 dark:text-blue-300"
                size={24}
              />
            </div>
            <div
              className={`flex items-center text-sm font-semibold ${
                analyticsData.revenue.isPositive
                  ? "text-green-500"
                  : "text-red-500"
              }`}
            >
              {analyticsData.revenue.isPositive ? (
                <TrendingUp size={16} />
              ) : (
                <TrendingDown size={16} />
              )}
              {analyticsData.revenue.change}
            </div>
          </div>
          <h3 className="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">
            Total Revenue
          </h3>
          <p className="text-3xl font-bold text-gray-900 dark:text-gray-100">
            {analyticsData.revenue.total}
          </p>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div className="flex items-center justify-between mb-4">
            <div className="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
              <Calendar
                className="text-green-600 dark:text-green-300"
                size={24}
              />
            </div>
            <div
              className={`flex items-center text-sm font-semibold ${
                analyticsData.bookings.isPositive
                  ? "text-green-500"
                  : "text-red-500"
              }`}
            >
              {analyticsData.bookings.isPositive ? (
                <TrendingUp size={16} />
              ) : (
                <TrendingDown size={16} />
              )}
              {analyticsData.bookings.change}
            </div>
          </div>
          <h3 className="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">
            Total Bookings
          </h3>
          <p className="text-3xl font-bold text-gray-900 dark:text-gray-100">
            {analyticsData.bookings.total}
          </p>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div className="flex items-center justify-between mb-4">
            <div className="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
              <Users
                className="text-purple-600 dark:text-purple-300"
                size={24}
              />
            </div>
            <div
              className={`flex items-center text-sm font-semibold ${
                analyticsData.customers.isPositive
                  ? "text-green-500"
                  : "text-red-500"
              }`}
            >
              {analyticsData.customers.isPositive ? (
                <TrendingUp size={16} />
              ) : (
                <TrendingDown size={16} />
              )}
              {analyticsData.customers.change}
            </div>
          </div>
          <h3 className="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">
            Total Customers
          </h3>
          <p className="text-3xl font-bold text-gray-900 dark:text-gray-100">
            {analyticsData.customers.total}
          </p>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div className="flex items-center justify-between mb-4">
            <div className="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
              <Plane
                className="text-orange-600 dark:text-orange-300"
                size={24}
              />
            </div>
            <div className="text-sm font-semibold text-green-500">
              <TrendingUp size={16} />
              +8%
            </div>
          </div>
          <h3 className="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">
            Avg. Occupancy
          </h3>
          <p className="text-3xl font-bold text-gray-900 dark:text-gray-100">
            78%
          </p>
        </div>
      </div>

      {/* Charts Section */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Revenue Chart */}
        <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div className="flex justify-between items-center mb-6">
            <h3 className="text-lg font-bold text-gray-900 dark:text-gray-100">
              Revenue Trend
            </h3>
            <LineChart className="text-cyan-600" size={24} />
          </div>
          <div className="h-64 flex items-end space-x-1">
            {analyticsData.revenue.data.map((value, index) => (
              <div key={index} className="flex-1 flex flex-col items-center">
                <div
                  className="w-full bg-gradient-to-t from-cyan-500 to-cyan-300 rounded-t-lg"
                  style={{
                    height: `${(value / 100000) * 100}%`,
                    minHeight: "4px",
                  }}
                ></div>
                <span className="text-xs text-gray-500 dark:text-gray-400 mt-2">
                  {analyticsData.revenue.labels[index]}
                </span>
              </div>
            ))}
          </div>
          <div className="mt-4 text-sm text-gray-500 dark:text-gray-400">
            Monthly revenue in Philippine Peso
          </div>
        </div>

        {/* Top Routes Chart */}
        <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div className="flex justify-between items-center mb-6">
            <h3 className="text-lg font-bold text-gray-900 dark:text-gray-100">
              Top Routes
            </h3>
            <PieChart className="text-green-600" size={24} />
          </div>
          <div className="space-y-4">
            {analyticsData.routes.map((route, index) => (
              <div key={index} className="flex items-center justify-between">
                <div className="flex items-center space-x-3">
                  <div
                    className={`w-3 h-3 rounded-full ${
                      index === 0
                        ? "bg-blue-500"
                        : index === 1
                        ? "bg-green-500"
                        : index === 2
                        ? "bg-purple-500"
                        : index === 3
                        ? "bg-orange-500"
                        : "bg-gray-500"
                    }`}
                  ></div>
                  <span className="text-gray-700 dark:text-gray-300">
                    {route.name}
                  </span>
                </div>
                <div className="flex items-center space-x-4">
                  <div className="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div
                      className={`h-2 rounded-full ${
                        index === 0
                          ? "bg-blue-500"
                          : index === 1
                          ? "bg-green-500"
                          : index === 2
                          ? "bg-purple-500"
                          : index === 3
                          ? "bg-orange-500"
                          : "bg-gray-500"
                      }`}
                      style={{ width: `${route.percentage}%` }}
                    ></div>
                  </div>
                  <span className="font-semibold text-gray-900 dark:text-gray-100">
                    {route.value} bookings
                  </span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Performance Metrics */}
      <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <h3 className="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">
          Performance Metrics
        </h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div className="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <div className="text-2xl font-bold text-blue-600 dark:text-blue-400">
              {analyticsData.performance.conversion}
            </div>
            <div className="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Conversion Rate
            </div>
          </div>
          <div className="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
            <div className="text-2xl font-bold text-green-600 dark:text-green-400">
              {analyticsData.performance.satisfaction}
            </div>
            <div className="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Customer Satisfaction
            </div>
          </div>
          <div className="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
            <div className="text-2xl font-bold text-purple-600 dark:text-purple-400">
              {analyticsData.performance.responseTime}
            </div>
            <div className="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Avg Response Time
            </div>
          </div>
          <div className="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
            <div className="text-2xl font-bold text-orange-600 dark:text-orange-400">
              {analyticsData.performance.occupancy}
            </div>
            <div className="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Seat Occupancy
            </div>
          </div>
        </div>
      </div>

      {/* Insights */}
      <div className="bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-xl shadow-lg p-6">
        <div className="flex justify-between items-start">
          <div>
            <h3 className="text-xl font-bold">Business Insights</h3>
            <p className="opacity-90 mt-2">
              Key findings from your performance data
            </p>
          </div>
          <BarChart3 size={28} />
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
          <div className="bg-white/20 p-4 rounded-lg">
            <div className="font-bold">Peak Booking Days</div>
            <div className="text-sm opacity-90 mt-1">
              Friday & Saturday have 35% higher bookings
            </div>
          </div>
          <div className="bg-white/20 p-4 rounded-lg">
            <div className="font-bold">Most Profitable Route</div>
            <div className="text-sm opacity-90 mt-1">
              MNL → DVO generates 28% of total revenue
            </div>
          </div>
          <div className="bg-white/20 p-4 rounded-lg">
            <div className="font-bold">Customer Growth</div>
            <div className="text-sm opacity-90 mt-1">
              New customer acquisition up by 18% this quarter
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Analytics;
