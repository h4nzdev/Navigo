import React from "react";
import { X, User, Building2 } from "lucide-react";
import { useNavigate } from "react-router-dom";

/**
 * Shared role selector for auth entry points.
 *
 * mode: "login" | "signup"
 */
const AuthRoleSelectorModal = ({ isOpen, mode = "login", onClose }) => {
  const navigate = useNavigate();

  if (!isOpen) return null;

  const isLogin = mode === "login";

  const handleTraveler = () => {
    navigate(isLogin ? "/login/user" : "/register/user");
    onClose && onClose();
  };

  const handleBusiness = () => {
    navigate(isLogin ? "/login/business" : "/register/business");
    onClose && onClose();
  };

  const title = isLogin ? "How would you like to sign in?" : "Get started with NaviGo";
  const subtitle = isLogin
    ? "Choose your portal to continue your journey."
    : "Pick the right experience to create your account.";

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
      <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-3xl w-full mx-4 overflow-hidden">
        <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-800">
          <div>
            <h2 className="text-xl font-bold text-gray-900 dark:text-gray-100">
              {title}
            </h2>
            <p className="text-sm text-gray-500 dark:text-gray-400">
              {subtitle}
            </p>
          </div>
          <button
            type="button"
            onClick={onClose}
            className="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400"
          >
            <X size={18} />
          </button>
        </div>

        <div className="px-6 py-6 md:py-8">
          <div className="grid md:grid-cols-2 gap-6">
            {/* Traveler card */}
            <button
              type="button"
              onClick={handleTraveler}
              className="group text-left bg-cyan-50/80 dark:bg-gray-900 rounded-xl shadow-md hover:shadow-xl border border-cyan-100 dark:border-gray-700 hover:border-cyan-500 dark:hover:border-cyan-500 transition-all duration-200 p-6 flex flex-col justify-between"
            >
              <div>
                <div className="flex justify-between items-start mb-4">
                  <div className="flex items-center">
                    <div className="p-3 bg-cyan-100 text-cyan-600 rounded-full mr-4">
                      <User className="w-5 h-5" />
                    </div>
                    <div>
                      <div className="text-xs text-gray-500 dark:text-gray-400">
                        Travel Pass
                      </div>
                      <div className="font-semibold text-gray-900 dark:text-gray-100">
                        Personal Explorer
                      </div>
                    </div>
                  </div>
                  <div className="text-right">
                    <div className="text-xs text-gray-500 dark:text-gray-400">
                      Type
                    </div>
                    <div className="font-semibold text-gray-900 dark:text-gray-100">
                      INDIVIDUAL
                    </div>
                  </div>
                </div>
                <h3 className="text-lg font-bold mb-2 text-gray-900 dark:text-gray-100">
                  {isLogin ? "Traveler Login" : "Get Started as Traveler"}
                </h3>
                <p className="mb-4 text-sm text-gray-600 dark:text-gray-300">
                  Your personal gateway to intelligent travel planning and unforgettable
                  adventures.
                </p>
                <ul className="mb-4 text-sm text-gray-600 dark:text-gray-300 space-y-1">
                  <li>AI-powered recommendations</li>
                  <li>Secure itinerary storage</li>
                  <li>Group collaboration</li>
                  <li>Cost optimization</li>
                </ul>
              </div>
              <div className="flex justify-between items-center mt-2">
                <div>
                  <span className="block font-semibold text-gray-900 dark:text-gray-100">
                    {isLogin ? "Sign in to your journey" : "Start your journey"}
                  </span>
                  {!isLogin && (
                    <span className="text-xs text-gray-500 dark:text-gray-400">
                      Free plan available
                    </span>
                  )}
                </div>
                <span className="text-2xl text-cyan-500 group-hover:translate-x-1 transition-transform">
                  →
                </span>
              </div>
            </button>

            {/* Business card */}
            <button
              type="button"
              onClick={handleBusiness}
              className="group text-left bg-purple-50/80 dark:bg-gray-900 rounded-xl shadow-md hover:shadow-xl border border-purple-100 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-500 transition-all duration-200 p-6 flex flex-col justify-between"
            >
              <div>
                <div className="flex justify-between items-start mb-4">
                  <div className="flex items-center">
                    <div className="p-3 bg-purple-100 text-purple-600 rounded-full mr-4">
                      <Building2 className="w-5 h-5" />
                    </div>
                    <div>
                      <div className="text-xs text-gray-500 dark:text-gray-400">
                        Partner Access
                      </div>
                      <div className="font-semibold text-gray-900 dark:text-gray-100">
                        Business Elite
                      </div>
                    </div>
                  </div>
                  <div className="text-right">
                    <div className="text-xs text-gray-500 dark:text-gray-400">
                      Type
                    </div>
                    <div className="font-semibold text-gray-900 dark:text-gray-100">
                      ENTERPRISE
                    </div>
                  </div>
                </div>
                <h3 className="text-lg font-bold mb-2 text-gray-900 dark:text-gray-100">
                  {isLogin ? "Business Partner Login" : "Get Started as Business Partner"}
                </h3>
                <p className="mb-4 text-sm text-gray-600 dark:text-gray-300">
                  Connect with travelers worldwide and grow your business through our
                  premium partner network.
                </p>
                <ul className="mb-4 text-sm text-gray-600 dark:text-gray-300 space-y-1">
                  <li>Booking & inventory management</li>
                  <li>Advanced analytics dashboard</li>
                  <li>API & system integrations</li>
                  <li>Enterprise-grade security</li>
                </ul>
              </div>
              <div className="flex justify-between items-center mt-2">
                <div>
                  <span className="block font-semibold text-gray-900 dark:text-gray-100">
                    {isLogin ? "Access partner portal" : "Join our partner network"}
                  </span>
                  {!isLogin && (
                    <span className="text-xs text-gray-500 dark:text-gray-400">
                      Custom enterprise pricing
                    </span>
                  )}
                </div>
                <span className="text-2xl text-purple-500 group-hover:translate-x-1 transition-transform">
                  →
                </span>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AuthRoleSelectorModal;


