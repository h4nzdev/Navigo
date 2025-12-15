import React, { useState } from "react";
import {
  MoveRight,
  Compass,
  Map,
  Wallet,
  Star,
  Users,
  Globe,
  Plane,
  Shield,
  Zap,
  MapPin,
  Calendar,
  ChevronDown,
  Play,
  Award,
  TrendingUp,
  CheckCircle,
  Facebook,
  Twitter,
  Instagram,
  Linkedin,
} from "lucide-react";
import logo from "../assets/NaviGo_Logo.png";
import { Link } from "react-router-dom";
import { Sun, Moon } from "lucide-react";
import AuthRoleSelectorModal from "../components/auth/AuthRoleSelectorModal";
import { useTheme } from "../context/ThemeContext";

const LandingPage = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [authMode, setAuthMode] = useState("login");
  const [isAuthSelectorOpen, setIsAuthSelectorOpen] = useState(false);
  const { isDark, toggleTheme } = useTheme();

  const startJourney = () => {
    document
      .getElementById("adventure-form")
      .scrollIntoView({ behavior: "smooth" });
  };

  const exploreFeatures = () => {
    document.getElementById("features").scrollIntoView({ behavior: "smooth" });
  };

  const stats = [
    { number: "50K+", label: "Happy Travelers" },
    { number: "190+", label: "Countries Covered" },
    { number: "4.9/5", label: "Customer Rating" },
    { number: "24/7", label: "Support" },
  ];

  const features = [
    {
      icon: <Zap className="w-8 h-8" />,
      title: "AI-Powered Planning",
      description:
        "Smart recommendations tailored to your preferences and budget",
      color: "text-yellow-500",
    },
    {
      icon: <Shield className="w-8 h-8" />,
      title: "Secure & Reliable",
      description:
        "Bank-level security for all your bookings and personal data",
      color: "text-green-500",
    },
    {
      icon: <Users className="w-8 h-8" />,
      title: "Group Collaboration",
      description: "Plan trips together with friends and family in real-time",
      color: "text-blue-500",
    },
    {
      icon: <TrendingUp className="w-8 h-8" />,
      title: "Cost Optimization",
      description: "Smart algorithms find the best deals and save you money",
      color: "text-purple-500",
    },
    {
      icon: <Globe className="w-8 h-8" />,
      title: "Global Coverage",
      description:
        "Access to millions of destinations and experiences worldwide",
      color: "text-cyan-500",
    },
    {
      icon: <Award className="w-8 h-8" />,
      title: "Premium Support",
      description: "24/7 concierge service for all your travel needs",
      color: "text-orange-500",
    },
  ];

  const testimonials = [
    {
      name: "Sarah Chen",
      role: "Adventure Traveler",
      content:
        "NaviGo transformed how I plan trips! The AI suggestions are spot-on and saved me hours of research.",
      rating: 5,
    },
    {
      name: "Marcus Rodriguez",
      role: "Business Traveler",
      content:
        "As a frequent flyer, NaviGo streamlined my entire travel process. The cost tracking is incredible!",
      rating: 5,
    },
    {
      name: "Emma Thompson",
      role: "Family Traveler",
      content:
        "Planning family vacations used to be stressful. Now its fun and collaborative with NaviGo!",
      rating: 5,
    },
  ];

  return (
    <div className="bg-gradient-to-br from-gray-50 to-cyan-50 dark:from-gray-900 dark:to-gray-800 text-gray-900 dark:text-gray-100">
      {/* Navigation */}
      <nav className="fixed w-full bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm z-50 shadow-sm">
        <div className="container mx-auto px-4 py-4">
          <div className="flex justify-between items-center">
            <a href="#hero" className="flex items-center space-x-2">
              <div className="w-8 h-8  rounded-lg">
                <img src={logo} alt="" className="w-full h-full " />
              </div>
              <span className="text-xl font-bold text-slate-200">NaviGo</span>
            </a>

            {/* Desktop Menu */}
            <div className="hidden md:flex items-center space-x-8">
              <a
                href="#features"
                className="font-medium hover:text-cyan-600 transition-colors"
              >
                Features
              </a>
              <a
                href="#testimonials"
                className="font-medium hover:text-cyan-600 transition-colors"
              >
                Testimonials
              </a>
              <a
                href="#pricing"
                className="font-medium hover:text-cyan-600 transition-colors"
              >
                Pricing
              </a>
              <a
                href="#contact"
                className="font-medium hover:text-cyan-600 transition-colors"
              >
                Contact
              </a>
            </div>

            <div className="hidden md:flex items-center space-x-4">
              <button
                type="button"
                onClick={toggleTheme}
                className="p-2 rounded-full border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 mr-2"
                aria-label="Toggle theme"
              >
                {isDark ? <Sun className="w-4 h-4" /> : <Moon className="w-4 h-4" />}
              </button>
              <button
                type="button"
                onClick={() => {
                  setAuthMode("login");
                  setIsAuthSelectorOpen(true);
                }}
                className="px-4 py-2 font-medium hover:text-cyan-600 transition-colors"
              >
                Login
              </button>
              <button
                type="button"
                onClick={() => {
                  setAuthMode("signup");
                  setIsAuthSelectorOpen(true);
                }}
                className="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-full font-semibold transition-colors"
              >
                Get Started
              </button>
            </div>

            {/* Mobile Menu Button */}
            <button
              className="md:hidden"
              onClick={() => setIsMenuOpen(!isMenuOpen)}
            >
              <div className="space-y-1">
                <div className="w-6 h-0.5 bg-gray-900 dark:bg-gray-100"></div>
                <div className="w-6 h-0.5 bg-gray-900 dark:bg-gray-100"></div>
                <div className="w-6 h-0.5 bg-gray-900 dark:bg-gray-100"></div>
              </div>
            </button>
          </div>

          {/* Mobile Menu */}
          {isMenuOpen && (
            <div className="md:hidden mt-4 pb-4 space-y-4">
              <a
                href="#features"
                className="block font-medium hover:text-cyan-600 transition-colors"
              >
                Features
              </a>
              <a
                href="#testimonials"
                className="block font-medium hover:text-cyan-600 transition-colors"
              >
                Testimonials
              </a>
              <a
                href="#pricing"
                className="block font-medium hover:text-cyan-600 transition-colors"
              >
                Pricing
              </a>
              <a
                href="#contact"
                className="block font-medium hover:text-cyan-600 transition-colors"
              >
                Contact
              </a>
              <div className="pt-4 space-y-2">
                <button
                  type="button"
                  onClick={toggleTheme}
                  className="w-full flex items-center justify-center px-4 py-2 font-medium border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-200"
                >
                  {isDark ? (
                    <>
                      <Sun className="w-4 h-4 mr-2" /> Light Mode
                    </>
                  ) : (
                    <>
                      <Moon className="w-4 h-4 mr-2" /> Dark Mode
                    </>
                  )}
                </button>
                <button
                  type="button"
                  className="w-full px-4 py-2 font-medium hover:text-cyan-600 transition-colors border border-gray-300 rounded-lg"
                  onClick={() => {
                    setAuthMode("login");
                    setIsAuthSelectorOpen(true);
                  }}
                >
                  Login
                </button>
                <button
                  type="button"
                  className="w-full bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors"
                  onClick={() => {
                    setAuthMode("signup");
                    setIsAuthSelectorOpen(true);
                  }}
                >
                  Get Started
                </button>
              </div>
            </div>
          )}
        </div>
      </nav>

      {/* Hero Section */}
      <section
        id="hero"
        className="relative min-h-screen flex items-center justify-center pt-16"
      >
        <div className="absolute inset-0 bg-gradient-to-r from-cyan-600/20 to-blue-600/20"></div>
        <div className="container mx-auto px-4 text-center relative z-10">
          <div className="max-w-4xl mx-auto">
            <div className="inline-flex items-center px-4 py-2 rounded-full bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 text-sm font-medium mb-6">
              <Star className="w-4 h-4 mr-2 fill-current" />
              Trusted by 50,000+ travelers worldwide
            </div>

            <h1 className="text-5xl md:text-7xl font-bold mb-6 leading-tight">
              Your Next
              <span className="block bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">
                Epic Adventure
              </span>
              Awaits
            </h1>

            <p className="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto leading-relaxed">
              AI-powered travel planning that turns your wanderlust into
              unforgettable journeys. Smart, seamless, and absolutely magical.
            </p>

            <div className="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
              <button
                onClick={startJourney}
                className="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-4 px-8 rounded-full transition-all duration-300 transform hover:scale-105 flex items-center gap-3 shadow-lg"
              >
                <Plane className="w-5 h-5" />
                Start Your Journey
                <MoveRight className="w-5 h-5" />
              </button>

              <button
                onClick={exploreFeatures}
                className="border-2 border-gray-300 dark:border-gray-600 hover:border-cyan-600 dark:hover:border-cyan-600 text-gray-700 dark:text-gray-300 font-semibold py-4 px-8 rounded-full transition-all duration-300 flex items-center gap-3 group"
              >
                <Play className="w-5 h-5 group-hover:text-cyan-600" />
                Watch Demo
              </button>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-2xl mx-auto">
              {stats.map((stat, index) => (
                <div key={index} className="text-center">
                  <div className="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-1">
                    {stat.number}
                  </div>
                  <div className="text-sm text-gray-600 dark:text-gray-400">
                    {stat.label}
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Scroll Indicator */}
        <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
          <ChevronDown className="w-6 h-6 text-gray-400" />
        </div>
      </section>

      {/* Features Section */}
      <section id="features" className="py-20 bg-white dark:bg-gray-800">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-4xl md:text-5xl font-bold mb-4">
              Why Travelers
              <span className="block text-cyan-600">Choose NaviGo</span>
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
              Experience the future of travel planning with our cutting-edge
              features designed for modern explorers.
            </p>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            {features.map((feature, index) => (
              <div
                key={index}
                className="group p-8 rounded-2xl bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 border border-gray-200 dark:border-gray-600 hover:border-cyan-300 dark:hover:border-cyan-600 transition-all duration-300 hover:shadow-xl"
              >
                <div
                  className={`${feature.color} mb-4 group-hover:scale-110 transition-transform duration-300`}
                >
                  {feature.icon}
                </div>
                <h3 className="text-xl font-bold mb-3 group-hover:text-cyan-600 transition-colors">
                  {feature.title}
                </h3>
                <p className="text-gray-600 dark:text-gray-300 leading-relaxed">
                  {feature.description}
                </p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Adventure Form Section */}
      <section
        id="adventure-form"
        className="py-20 bg-gradient-to-br from-cyan-600 to-blue-600"
      >
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center text-white">
            <h2 className="text-4xl md:text-5xl font-bold mb-4">
              Ready to Begin Your
              <span className="block">Adventure?</span>
            </h2>
            <p className="text-xl mb-8 opacity-90 max-w-2xl mx-auto">
              Tell us about your dream trip and let our AI craft the perfect
              itinerary for you.
            </p>

            <form className="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-2xl">
              <div className="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                  <label className="block text-gray-700 dark:text-gray-300 font-semibold mb-3 text-left">
                    <MapPin className="w-5 h-5 inline mr-2 text-cyan-600" />
                    Dream Destination
                  </label>
                  <input
                    type="text"
                    placeholder="Where do you want to go?"
                    className="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500"
                  />
                </div>
                <div>
                  <label className="block text-gray-700 dark:text-gray-300 font-semibold mb-3 text-left">
                    <Calendar className="w-5 h-5 inline mr-2 text-cyan-600" />
                    Travel Dates
                  </label>
                  <input
                    type="date"
                    className="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500"
                  />
                </div>
              </div>

              <div className="mb-6">
                <label className="block text-gray-700 dark:text-gray-300 font-semibold mb-3 text-left">
                  Travel Style
                </label>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                  {["Adventure", "Luxury", "Cultural", "Relaxation"].map(
                    (style) => (
                      <label
                        key={style}
                        className="flex items-center space-x-2 p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-cyan-500 cursor-pointer"
                      >
                        <input
                          type="checkbox"
                          className="text-cyan-600 focus:ring-cyan-500"
                        />
                        <span className="text-gray-700 dark:text-gray-300">
                          {style}
                        </span>
                      </label>
                    )
                  )}
                </div>
              </div>

              <button
                type="submit"
                className="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-4 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center justify-center gap-3"
              >
                <Compass className="w-5 h-5" />
                Create My Dream Itinerary
                <MoveRight className="w-5 h-5" />
              </button>
            </form>
          </div>
        </div>
      </section>

      {/* Testimonials */}
      <section id="testimonials" className="py-20 bg-gray-50 dark:bg-gray-900">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-4xl md:text-5xl font-bold mb-4">
              Loved by
              <span className="block text-cyan-600">Travelers Worldwide</span>
            </h2>
          </div>

          <div className="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            {testimonials.map((testimonial, index) => (
              <div
                key={index}
                className="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg"
              >
                <div className="flex items-center mb-4">
                  {[...Array(testimonial.rating)].map((_, i) => (
                    <Star
                      key={i}
                      className="w-5 h-5 text-yellow-400 fill-current"
                    />
                  ))}
                </div>
                <p className="text-gray-600 dark:text-gray-300 mb-6 italic">
                  "{testimonial.content}"
                </p>
                <div>
                  <div className="font-bold text-gray-900 dark:text-white">
                    {testimonial.name}
                  </div>
                  <div className="text-sm text-cyan-600">
                    {testimonial.role}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-gradient-to-r from-cyan-600 to-blue-600 text-white">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-4xl md:text-5xl font-bold mb-4">
            Start Your Journey Today
          </h2>
          <p className="text-xl mb-8 opacity-90 max-w-2xl mx-auto">
            Join thousands of travelers who have transformed their travel
            experiences with NaviGo.
          </p>
          <button className="bg-white text-cyan-600 hover:bg-gray-100 font-bold py-4 px-12 rounded-full transition-all duration-300 transform hover:scale-105 shadow-2xl">
            Get Started Free
          </button>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-800 text-white py-12">
        <div className="container mx-auto px-4">
          <div className="grid md:grid-cols-4 gap-8 mb-8">
            <div>
              <div className="flex items-center space-x-2 mb-4">
                <div className="w-8 h-8 bg-cyan-600 rounded-lg"></div>
                <span className="text-xl font-bold">NaviGo</span>
              </div>
              <p className="text-gray-400">
                Your intelligent travel companion for unforgettable adventures
                around the world.
              </p>
            </div>

            <div>
              <h4 className="font-bold mb-4">Product</h4>
              <ul className="space-y-2 text-gray-400">
                <li>
                  <a href="#" className="hover:text-white transition-colors">
                    Features
                  </a>
                </li>
                <li>
                  <a href="#" className="hover:text-white transition-colors">
                    Pricing
                  </a>
                </li>
                <li>
                  <a href="#" className="hover:text-white transition-colors">
                    API
                  </a>
                </li>
              </ul>
            </div>

            <div>
              <h4 className="font-bold mb-4">Company</h4>
              <ul className="space-y-2 text-gray-400">
                <li>
                  <a href="#" className="hover:text-white transition-colors">
                    About
                  </a>
                </li>
                <li>
                  <a href="#" className="hover:text-white transition-colors">
                    Blog
                  </a>
                </li>
                <li>
                  <a href="#" className="hover:text-white transition-colors">
                    Careers
                  </a>
                </li>
              </ul>
            </div>

            <div>
              <h4 className="font-bold mb-4">Connect</h4>
              <div className="flex space-x-4">
                <a
                  href="#"
                  className="text-gray-400 hover:text-white transition-colors"
                >
                  <Facebook className="w-5 h-5" />
                </a>
                <a
                  href="#"
                  className="text-gray-400 hover:text-white transition-colors"
                >
                  <Twitter className="w-5 h-5" />
                </a>
                <a
                  href="#"
                  className="text-gray-400 hover:text-white transition-colors"
                >
                  <Instagram className="w-5 h-5" />
                </a>
                <a
                  href="#"
                  className="text-gray-400 hover:text-white transition-colors"
                >
                  <Linkedin className="w-5 h-5" />
                </a>
              </div>
            </div>
          </div>

          <div className="border-t border-gray-700 pt-8 text-center text-gray-400">
            <p>© 2025 NaviGo Adventure Travels. All rights reserved.</p>
          </div>
        </div>
      </footer>

      <AuthRoleSelectorModal
        isOpen={isAuthSelectorOpen}
        mode={authMode}
        onClose={() => setIsAuthSelectorOpen(false)}
      />
    </div>
  );
};

export default LandingPage;
