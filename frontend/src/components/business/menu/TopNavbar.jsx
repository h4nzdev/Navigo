import React, { useContext, useState, useRef, useEffect } from "react";
import {
  Star,
  Bell,
  Settings,
  Moon,
  ChevronDown,
  Building,
  User,
  LogOut,
  HelpCircle,
  Shield,
  CreditCard,
  Plus,
  Calendar,
  BarChart,
  Clock,
  Users,
  Plane,
} from "lucide-react";
import { AuthContext } from "../../../context/AuthContext";
import { useNavigate, useLocation, NavLink } from "react-router-dom";

const TopNavbar = ({
  businessData,
  userData,
  getUserInitials,
  onAddSchedule,
}) => {
  const { logout } = useContext(AuthContext);
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);
  const dropdownRef = useRef(null);
  const navigate = useNavigate();
  const location = useLocation();

  // Business navigation items with paths
  const businessNavItems = [
    {
      label: "Add Flight Schedule",
      icon: <Plus size={18} />,
      onClick: () => onAddSchedule && onAddSchedule(),
      isPrimary: true,
    },
    {
      label: "Flight Schedules",
      icon: <Calendar size={18} />,
      path: "/business/flight-schedules",
      active: "/business/flight-schedules",
    },
    {
      label: "Analytics",
      icon: <BarChart size={18} />,
      path: "/business/analytics",
      active: "/business/analytics",
    },
    {
      label: "Pending Requests",
      icon: <Clock size={18} />,
      path: "/business/request",
      active: "/business/request",
      badge: "3",
    },
  ];

  // Check if nav item is active
  const isActive = (item) => {
    if (item.active === "/business/dashboard") {
      return location.pathname === "/business/dashboard";
    }
    return location.pathname.startsWith(item.active);
  };

  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setIsDropdownOpen(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  const menuItems = [
    {
      label: "Profile",
      icon: <User size={18} />,
      onClick: () => setIsDropdownOpen(false),
    },
    {
      label: "Settings",
      icon: <Settings size={18} />,
      onClick: () => setIsDropdownOpen(false),
    },
    {
      label: "Billing",
      icon: <CreditCard size={18} />,
      onClick: () => setIsDropdownOpen(false),
      badge: userData?.subscription_plan || "Premium",
    },
    {
      label: "Security",
      icon: <Shield size={18} />,
      onClick: () => setIsDropdownOpen(false),
    },
    {
      label: "Help & Support",
      icon: <HelpCircle size={18} />,
      onClick: () => setIsDropdownOpen(false),
    },
    {
      label: "Logout",
      icon: <LogOut size={18} />,
      onClick: () => {
        logout();
        setIsDropdownOpen(false);
      },
      isDanger: true,
    },
  ];

  return (
    <>
      <nav className="bg-white dark:bg-gray-800 shadow-sm px-6 py-4">
        <div className="flex items-center justify-between">
          <div className="flex items-center space-x-4">
            {/* Logo as NavLink to Dashboard */}
            <NavLink
              to="/business/dashboard"
              className="flex items-center space-x-3 hover:opacity-90 transition-opacity"
            >
              <Building size={28} className="text-cyan-600" />
              <div>
                <h1 className="text-xl font-bold text-gray-900 dark:text-white">
                  Partner Portal
                </h1>
                <p className="text-sm text-gray-500 dark:text-gray-400">
                  Business Dashboard
                </p>
              </div>
            </NavLink>
            <span className="bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300 text-sm font-semibold px-3 py-1 rounded-full">
              {businessData?.business_type || "Travel Partner"}
            </span>
          </div>

          <div className="flex items-center space-x-4">
            <button className="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 rounded-lg">
              <Moon size={20} />
            </button>
            <button className="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 rounded-lg">
              <Settings size={20} />
            </button>
            <button className="relative p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 rounded-lg">
              <Bell size={20} />
              <span className="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full"></span>
            </button>

            <div className="relative" ref={dropdownRef}>
              <button
                onClick={() => setIsDropdownOpen(!isDropdownOpen)}
                className="flex items-center space-x-3 px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg cursor-pointer"
              >
                <div className="w-8 h-8 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                  {getUserInitials()}
                </div>
                <div className="text-left">
                  <div className="font-semibold text-sm">
                    {userData
                      ? `${userData.first_name} ${userData.last_name}`
                      : businessData?.business_name || "Business"}
                  </div>
                  <div className="text-xs text-green-500 flex items-center">
                    <Star size={10} className="mr-1" fill="currentColor" />
                    {userData?.subscription_plan || "Premium"} Partner
                  </div>
                </div>
                <ChevronDown
                  size={16}
                  className={`${isDropdownOpen ? "rotate-180" : ""}`}
                />
              </button>

              {isDropdownOpen && (
                <div className="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                  <div className="p-4 border-b border-gray-100 dark:border-gray-700">
                    <div className="flex items-center space-x-3">
                      <div className="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                        {getUserInitials()}
                      </div>
                      <div>
                        <div className="font-semibold text-sm">
                          {userData
                            ? `${userData.first_name} ${userData.last_name}`
                            : businessData?.business_name || "Business"}
                        </div>
                        <div className="text-xs text-gray-500 dark:text-gray-400">
                          {userData?.email || "business@example.com"}
                        </div>
                      </div>
                    </div>
                  </div>

                  <div className="py-2">
                    {menuItems.map((item, index) => (
                      <button
                        key={index}
                        onClick={item.onClick}
                        className={`w-full flex items-center justify-between px-4 py-3 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 ${
                          item.isDanger
                            ? "text-red-600 dark:text-red-400"
                            : "text-gray-700 dark:text-gray-300"
                        }`}
                      >
                        <div className="flex items-center space-x-3">
                          <div
                            className={
                              item.isDanger
                                ? "text-red-500"
                                : "text-gray-500 dark:text-gray-400"
                            }
                          >
                            {item.icon}
                          </div>
                          <span>{item.label}</span>
                        </div>
                        {item.badge && (
                          <span className="px-2 py-1 text-xs font-semibold bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300 rounded-full">
                            {item.badge}
                          </span>
                        )}
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Business Navigation Bar */}
        <div className="flex items-center space-x-1 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
          {businessNavItems.map((item, index) => {
            // For primary button (Add Flight Schedule)
            if (item.isPrimary) {
              return (
                <button
                  key={index}
                  onClick={item.onClick}
                  className={`flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium ${
                    item.isPrimary
                      ? "bg-cyan-600 text-white hover:bg-cyan-700"
                      : "text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                  }`}
                >
                  {item.icon}
                  <span>{item.label}</span>
                  {item.badge && (
                    <span className="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-semibold px-2 py-0.5 rounded-full">
                      {item.badge}
                    </span>
                  )}
                </button>
              );
            }

            // For regular navigation items
            return (
              <NavLink
                key={index}
                to={item.path}
                className={({ isActive: navIsActive }) =>
                  `flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                    isActive(item) || navIsActive
                      ? "bg-cyan-100 text-cyan-700 dark:bg-cyan-900 dark:text-cyan-300 border-b-2 border-cyan-600"
                      : "text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                  }`
                }
              >
                {item.icon}
                <span>{item.label}</span>
                {item.badge && (
                  <span className="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-semibold px-2 py-0.5 rounded-full">
                    {item.badge}
                  </span>
                )}
              </NavLink>
            );
          })}
        </div>
      </nav>
    </>
  );
};

export default TopNavbar;
