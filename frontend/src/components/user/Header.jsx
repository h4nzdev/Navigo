import React, { useContext, useState, useEffect, useRef } from "react";
import { Link, useLocation } from "react-router-dom";
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
  LogOut,
  CheckCircle,
  AlertCircle,
  Info,
  Calendar,
  Plane,
  DollarSign,
  X,
} from "lucide-react";

import logo from "../../assets/NaviGo_Logo.png";
import { AuthContext } from "../../context/AuthContext";

const Header = () => {
  const [profileOpen, setProfileOpen] = useState(false);
  const [notificationsOpen, setNotificationsOpen] = useState(false);
  const [notifications, setNotifications] = useState([
    {
      id: 1,
      type: "booking",
      title: "Flight Booking Confirmed",
      message: "Your flight to Tokyo has been successfully booked.",
      time: "2 hours ago",
      read: false,
      icon: Plane,
      iconColor: "text-green-500",
    },
    {
      id: 2,
      type: "payment",
      title: "Payment Received",
      message: "Payment for your Paris trip has been processed.",
      time: "5 hours ago",
      read: false,
      icon: DollarSign,
      iconColor: "text-blue-500",
    },
    {
      id: 3,
      type: "reminder",
      title: "Upcoming Trip Reminder",
      message: "Your Tokyo trip starts in 3 days. Check your itinerary.",
      time: "1 day ago",
      read: true,
      icon: Calendar,
      iconColor: "text-orange-500",
    },
    {
      id: 4,
      type: "alert",
      title: "Weather Alert",
      message: "Heavy rain expected in Paris during your travel dates.",
      time: "2 days ago",
      read: true,
      icon: AlertCircle,
      iconColor: "text-yellow-500",
    },
    {
      id: 5,
      type: "update",
      title: "App Update Available",
      message: "New features added. Update to the latest version.",
      time: "3 days ago",
      read: true,
      icon: Info,
      iconColor: "text-purple-500",
    },
  ]);

  const { logout } = useContext(AuthContext);
  const location = useLocation();

  const profileRef = useRef(null);
  const notificationsRef = useRef(null);

  // Helper function to check if current path matches
  const isActive = (path) => {
    return (
      location.pathname === path || location.pathname.startsWith(`${path}/`)
    );
  };

  // Mark all as read
  const markAllAsRead = () => {
    setNotifications(notifications.map((notif) => ({ ...notif, read: true })));
  };

  // Mark single notification as read
  const markAsRead = (id) => {
    setNotifications(
      notifications.map((notif) =>
        notif.id === id ? { ...notif, read: true } : notif
      )
    );
  };

  // Delete notification
  const deleteNotification = (id, e) => {
    e.stopPropagation();
    setNotifications(notifications.filter((notif) => notif.id !== id));
  };

  // Clear all notifications
  const clearAllNotifications = () => {
    setNotifications([]);
  };

  // Count unread notifications
  const unreadCount = notifications.filter((notif) => !notif.read).length;

  // Close dropdowns when clicking outside
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (profileRef.current && !profileRef.current.contains(event.target)) {
        setProfileOpen(false);
      }
      if (
        notificationsRef.current &&
        !notificationsRef.current.contains(event.target)
      ) {
        setNotificationsOpen(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  return (
    <header className="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50 flex items-center justify-between px-6 py-3">
      <div className="flex items-center space-x-6">
        <Link to="/" className="flex items-center space-x-2">
          <img src={logo} alt="NaviGo Logo" className="h-10" />
          <span className="font-bold text-xl text-cyan-600">Dashboard</span>
        </Link>
        <nav className="hidden md:flex items-center space-x-4">
          <Link
            to="/my-trips"
            className={`flex items-center space-x-2 transition-colors ${
              isActive("/my-trips")
                ? "text-cyan-600 font-semibold"
                : "text-gray-500 hover:text-cyan-600"
            }`}
          >
            <Briefcase size={20} />
            <span>My Trips</span>
          </Link>
          <Link
            to="/explore"
            className={`flex items-center space-x-2 transition-colors ${
              isActive("/explore")
                ? "text-cyan-600 font-semibold"
                : "text-gray-500 hover:text-cyan-600"
            }`}
          >
            <Compass size={20} />
            <span>Explore</span>
          </Link>
          <Link
            to="/history"
            className={`flex items-center space-x-2 transition-colors ${
              isActive("/history")
                ? "text-cyan-600 font-semibold"
                : "text-gray-500 hover:text-cyan-600"
            }`}
          >
            <Clock size={20} />
            <span>History</span>
          </Link>
          <Link
            to="/plan"
            className={`flex items-center space-x-2 transition-colors ${
              isActive("/plan")
                ? "text-cyan-600 font-semibold"
                : "text-gray-500 hover:text-cyan-600"
            }`}
          >
            <MapPin size={20} />
            <span>Plan</span>
          </Link>
        </nav>
      </div>
      <div className="flex items-center space-x-4">
        <button className="text-gray-500 hover:text-cyan-600">
          <Moon size={22} />
        </button>

        {/* Notifications Dropdown */}
        <div className="relative" ref={notificationsRef}>
          <button
            className="text-gray-500 hover:text-cyan-600 relative"
            onClick={() => {
              setNotificationsOpen(!notificationsOpen);
              setProfileOpen(false);
            }}
          >
            <Bell size={22} />
            {unreadCount > 0 && (
              <span className="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                {unreadCount > 9 ? "9+" : unreadCount}
              </span>
            )}
          </button>

          {notificationsOpen && (
            <div className="absolute right-0 mt-2 w-80 md:w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl py-2 z-50 max-h-[500px] overflow-hidden flex flex-col">
              {/* Notifications Header */}
              <div className="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <div className="flex justify-between items-center">
                  <h3 className="font-bold text-lg">Notifications</h3>
                  <div className="flex gap-2">
                    {unreadCount > 0 && (
                      <button
                        onClick={markAllAsRead}
                        className="text-sm text-cyan-600 hover:text-cyan-700 font-medium"
                      >
                        Mark all as read
                      </button>
                    )}
                    {notifications.length > 0 && (
                      <button
                        onClick={clearAllNotifications}
                        className="text-sm text-red-500 hover:text-red-600 font-medium"
                      >
                        Clear all
                      </button>
                    )}
                  </div>
                </div>
                <div className="text-sm text-gray-500 mt-1">
                  {unreadCount > 0
                    ? `${unreadCount} unread notification${
                        unreadCount !== 1 ? "s" : ""
                      }`
                    : "All caught up!"}
                </div>
              </div>

              {/* Notifications List */}
              <div className="overflow-y-auto flex-1">
                {notifications.length > 0 ? (
                  <div className="divide-y divide-gray-100 dark:divide-gray-700">
                    {notifications.map((notification) => {
                      const Icon = notification.icon;
                      return (
                        <div
                          key={notification.id}
                          className={`px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors ${
                            !notification.read
                              ? "bg-cyan-50/50 dark:bg-cyan-900/10"
                              : ""
                          }`}
                          onClick={() => markAsRead(notification.id)}
                        >
                          <div className="flex gap-3">
                            <div
                              className={`p-2 rounded-full ${notification.iconColor} bg-opacity-20`}
                            >
                              <Icon size={18} />
                            </div>
                            <div className="flex-1 min-w-0">
                              <div className="flex justify-between items-start">
                                <h4 className="font-semibold text-sm text-orange-400 truncate">
                                  {notification.title}
                                </h4>
                                <button
                                  onClick={(e) =>
                                    deleteNotification(notification.id, e)
                                  }
                                  className="ml-2 p-1 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full"
                                >
                                  <X size={14} />
                                </button>
                              </div>
                              <p className="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {notification.message}
                              </p>
                              <div className="flex justify-between items-center mt-2">
                                <span className="text-xs text-gray-500">
                                  {notification.time}
                                </span>
                                {!notification.read && (
                                  <span className="w-2 h-2 bg-cyan-500 rounded-full"></span>
                                )}
                              </div>
                            </div>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                ) : (
                  <div className="text-center py-8">
                    <Bell className="mx-auto text-gray-400 mb-3" size={32} />
                    <p className="text-gray-500">No notifications yet</p>
                    <p className="text-sm text-gray-400 mt-1">
                      You're all caught up!
                    </p>
                  </div>
                )}
              </div>

              {/* Notifications Footer */}
              <div className="border-t border-gray-200 dark:border-gray-700 px-4 py-3">
                <Link
                  to="/notifications"
                  className="text-center block text-sm text-cyan-600 hover:text-cyan-700 font-medium"
                  onClick={() => setNotificationsOpen(false)}
                >
                  View all notifications
                </Link>
              </div>
            </div>
          )}
        </div>

        {/* Profile Dropdown */}
        <div className="relative" ref={profileRef}>
          <div
            className="flex items-center space-x-2 cursor-pointer"
            onClick={() => {
              setProfileOpen(!profileOpen);
              setNotificationsOpen(false);
            }}
          >
            <img
              src={logo}
              alt="Profile"
              className="h-9 w-9 rounded-full border-2 border-cyan-500"
            />
            <span className="hidden text-cyan-100 sm:block font-semibold">
              User Name
            </span>
            <ChevronDown
              size={16}
              color="white"
              className={`transition-transform ${
                profileOpen ? "rotate-180" : ""
              }`}
            />
          </div>
          {profileOpen && (
            <div className="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-xl py-2 z-50">
              <Link
                to="/profile"
                className={`flex items-center px-4 py-2 text-sm transition-colors ${
                  isActive("/profile")
                    ? "text-cyan-600 font-semibold bg-cyan-50 dark:bg-cyan-900/20"
                    : "text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                }`}
                onClick={() => setProfileOpen(false)}
              >
                <User size={16} className="mr-3" />
                View Profile
              </Link>
              <Link
                to="/plan"
                className={`flex items-center px-4 py-2 text-sm transition-colors ${
                  isActive("/plan")
                    ? "text-cyan-600 font-semibold bg-cyan-50 dark:bg-cyan-900/20"
                    : "text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                }`}
                onClick={() => setProfileOpen(false)}
              >
                <Crown size={16} className="mr-3" />
                Trips & Plans
              </Link>
              <Link
                to="/subscription"
                className={`flex items-center px-4 py-2 text-sm transition-colors ${
                  isActive("/subscription")
                    ? "text-cyan-600 font-semibold bg-cyan-50 dark:bg-cyan-900/20"
                    : "text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                }`}
                onClick={() => setProfileOpen(false)}
              >
                <Crown size={16} className="mr-3" />
                Subscription & Billing
              </Link>
              <Link
                to="/account-settings"
                className={`flex items-center px-4 py-2 text-sm transition-colors ${
                  isActive("/account-settings")
                    ? "text-cyan-600 font-semibold bg-cyan-50 dark:bg-cyan-900/20"
                    : "text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                }`}
                onClick={() => setProfileOpen(false)}
              >
                <Settings size={16} className="mr-3" />
                Account Settings
              </Link>
              <div className="border-t border-gray-200 dark:border-gray-700 my-2"></div>
              <button
                onClick={() => {
                  logout();
                  setProfileOpen(false);
                }}
                className="flex items-center px-4 py-2 text-sm text-red-500 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left"
              >
                <LogOut size={16} className="mr-3" />
                End Journey
              </button>
            </div>
          )}
        </div>
      </div>
    </header>
  );
};

export default Header;
