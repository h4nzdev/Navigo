import React, { useState, useEffect } from "react";
import {
  ArrowLeft,
  MapPin,
  Calendar,
  Users,
  DollarSign,
  Star,
  Share2,
  Download,
  Edit,
  MoreVertical,
  Clock,
  CheckCircle,
  XCircle,
  AlertCircle,
  ChevronRight,
  Phone,
  Mail,
  Target,
  CheckSquare,
  Square,
  Plus,
  Minus,
  Save,
  TrendingUp,
  Clipboard,
  Plane,
  Map,
  Check,
  X,
  Home,
  Car,
  Camera,
  ShoppingBag,
  Utensils,
  Wifi,
  Heart,
} from "lucide-react";
import { useParams, useNavigate } from "react-router-dom";
import { getTripById } from "../../services/tripService";

const statusColors = {
  Confirmed:
    "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200",
  Planning:
    "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200",
  Business:
    "bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200",
  Booked: "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200",
  Ongoing:
    "bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200",
  Completed:
    "bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200",
  Cancelled: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200",
};

const progressSteps = [
  {
    id: "planning",
    label: "Planning",
    value: 25,
    icon: <Clipboard size={20} />,
  },
  { id: "booked", label: "Booked", value: 50, icon: <Plane size={20} /> },
  { id: "ongoing", label: "Ongoing", value: 75, icon: <Map size={20} /> },
  {
    id: "completed",
    label: "Completed",
    value: 100,
    icon: <Check size={20} />,
  },
  { id: "cancelled", label: "Cancelled", value: 0, icon: <X size={20} /> },
];

const MyTripDetails = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [activeTab, setActiveTab] = useState("overview");
  const [trip, setTrip] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);
  const [isEditingProgress, setIsEditingProgress] = useState(false);
  const [selectedProgress, setSelectedProgress] = useState(0);
  const [newStatus, setNewStatus] = useState("");
  const [tasks, setTasks] = useState([]);
  const [newTask, setNewTask] = useState("");

  // Fetch trip details
  useEffect(() => {
    const fetchTrip = async () => {
      try {
        setIsLoading(true);
        setError(null);
        const data = await getTripById(id);
        setTrip(data);

        // Set initial progress
        setSelectedProgress(data.progress || 0);
        setNewStatus(data.status || "Planning");

        // Initialize default tasks
        setTasks([
          {
            id: 1,
            title: "Book flights",
            completed: false,
            icon: <Plane size={16} />,
          },
          {
            id: 2,
            title: "Reserve accommodation",
            completed: false,
            icon: <Home size={16} />,
          },
          {
            id: 3,
            title: "Plan itinerary",
            completed: false,
            icon: <Map size={16} />,
          },
          {
            id: 4,
            title: "Rent a car",
            completed: false,
            icon: <Car size={16} />,
          },
          {
            id: 5,
            title: "Purchase travel insurance",
            completed: false,
            icon: <Check size={16} />,
          },
        ]);
      } catch (err) {
        console.error("Error fetching trip:", err);
        setError("Failed to load trip details");
      } finally {
        setIsLoading(false);
      }
    };

    if (id) {
      fetchTrip();
    }
  }, [id]);

  const tabs = [
    { id: "overview", label: "Overview", icon: <MapPin size={16} /> },
    { id: "itinerary", label: "Itinerary", icon: <Calendar size={16} /> },
    { id: "expenses", label: "Expenses", icon: <DollarSign size={16} /> },
    { id: "progress", label: "Progress", icon: <TrendingUp size={16} /> },
  ];

  const handleProgressUpdate = (progressValue) => {
    setSelectedProgress(progressValue);

    // Auto-select corresponding status
    const step = progressSteps.find((step) => step.value === progressValue);
    if (step) {
      setNewStatus(step.label);
    }
  };

  const saveProgressChanges = () => {
    if (trip) {
      const updatedTrip = {
        ...trip,
        progress: selectedProgress,
        status: newStatus,
        updated_at: new Date().toISOString(),
      };

      setTrip(updatedTrip);
      setIsEditingProgress(false);

      // Here you would typically make an API call to save changes
      console.log("Saving progress:", updatedTrip);

      // Show success message
      alert("Trip progress updated successfully!");
    }
  };

  const toggleTask = (taskId) => {
    setTasks(
      tasks.map((task) =>
        task.id === taskId ? { ...task, completed: !task.completed } : task
      )
    );
  };

  const addTask = () => {
    if (newTask.trim()) {
      setTasks([
        ...tasks,
        {
          id: tasks.length + 1,
          title: newTask,
          completed: false,
          icon: <Clipboard size={16} />,
        },
      ]);
      setNewTask("");
    }
  };

  const removeTask = (taskId) => {
    setTasks(tasks.filter((task) => task.id !== taskId));
  };

  const getProgressColor = (progress) => {
    if (progress < 25) return "bg-red-500";
    if (progress < 50) return "bg-orange-500";
    if (progress < 75) return "bg-yellow-500";
    if (progress < 100) return "bg-blue-500";
    return "bg-green-500";
  };

  const getStatusFromProgress = (progress) => {
    if (progress === 0) return "Cancelled";
    if (progress <= 25) return "Planning";
    if (progress <= 50) return "Booked";
    if (progress <= 75) return "Ongoing";
    if (progress === 100) return "Completed";
    return "Planning";
  };

  // Loading state
  if (isLoading) {
    return (
      <div className="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
          <h2 className="text-xl font-bold mt-4">Loading trip details...</h2>
          <p className="text-gray-500 mt-2">
            Please wait while we fetch your trip information.
          </p>
        </div>
      </div>
    );
  }

  // Error state
  if (error || !trip) {
    return (
      <div className="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
        <div className="text-center">
          <XCircle className="mx-auto text-red-500" size={64} />
          <h2 className="text-2xl font-bold mt-4">
            {error || "Trip Not Found"}
          </h2>
          <p className="text-gray-500 mt-2">
            The trip you're looking for doesn't exist or could not be loaded.
          </p>
          <button
            onClick={() => navigate("/my-trips")}
            className="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
          >
            Back to Trips
          </button>
        </div>
      </div>
    );
  }

  // Default values based on Trip model
  const tripWithDefaults = {
    ...trip,
    destination: trip.destination || "Unknown Destination",
    country: trip.country || "Unknown Country",
    dates: trip.dates || "Not specified",
    duration: trip.duration || "Not specified",
    travelers: trip.travelers || 1,
    budget: trip.budget || 0,
    spent: trip.spent || 0,
    progress: trip.progress || 0,
    status: trip.status || "Planning",
    rating: trip.rating || "0",
    reviews: trip.reviews || 0,
    image:
      trip.image ||
      "https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
    highlights: trip.highlights || ["Adventure", "Sightseeing", "Relaxation"],
  };

  const completedTasks = tasks.filter((task) => task.completed).length;
  const totalTasks = tasks.length;
  const tasksProgress =
    totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

  return (
    <div className="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen font-sans">
      {/* Back Button */}
      <div className="p-6 lg:p-10">
        <button
          onClick={() => navigate("/my-trips")}
          className="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mb-6"
        >
          <ArrowLeft size={20} />
          Back to Trips
        </button>

        {/* Trip Header */}
        <div className="relative mb-8">
          <div className="relative h-64 md:h-80 rounded-2xl overflow-hidden">
            <img
              src={tripWithDefaults.image}
              alt={tripWithDefaults.destination}
              className="w-full h-full object-cover"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
            <div className="absolute bottom-6 left-6 right-6">
              <div className="flex flex-col md:flex-row md:items-end justify-between">
                <div>
                  <h1 className="text-3xl md:text-4xl font-bold text-white mb-2">
                    {tripWithDefaults.destination}
                  </h1>
                  <div className="flex items-center gap-4 text-white/90">
                    <div className="flex items-center gap-1">
                      <MapPin size={18} />
                      <span>{tripWithDefaults.country}</span>
                    </div>
                    <div className="flex items-center gap-1">
                      <Calendar size={18} />
                      <span>{tripWithDefaults.dates}</span>
                    </div>
                    <div className="flex items-center gap-1">
                      <Users size={18} />
                      <span>
                        {tripWithDefaults.travelers} traveler
                        {tripWithDefaults.travelers > 1 ? "s" : ""}
                      </span>
                    </div>
                  </div>
                </div>
                <div className="flex flex-col items-end gap-2 mt-4 md:mt-0">
                  <div
                    className={`text-sm font-bold px-3 py-1 rounded-full ${
                      statusColors[newStatus] || "bg-gray-100 text-gray-800"
                    }`}
                  >
                    {newStatus}
                  </div>
                  <div className="text-white/90 text-sm">
                    {tripWithDefaults.progress}% Complete
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Action Buttons */}
          <div className="absolute -bottom-4 right-6 flex gap-2">
            <button className="p-3 bg-white dark:bg-gray-800 rounded-full shadow-lg hover:bg-gray-50 dark:hover:bg-gray-700">
              <Share2 size={20} />
            </button>
            <button
              onClick={() => setIsEditingProgress(!isEditingProgress)}
              className="p-3 bg-white dark:bg-gray-800 rounded-full shadow-lg hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              {isEditingProgress ? <Save size={20} /> : <Edit size={20} />}
            </button>
            <button className="p-3 bg-white dark:bg-gray-800 rounded-full shadow-lg hover:bg-gray-50 dark:hover:bg-gray-700">
              <MoreVertical size={20} />
            </button>
          </div>
        </div>

        {/* Trip Info Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          {/* Budget Card */}
          <div className="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
            <div className="flex items-center gap-3 mb-4">
              <div className="p-2 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-lg">
                <DollarSign size={24} />
              </div>
              <div>
                <h3 className="font-semibold">Budget & Spending</h3>
                <p className="text-sm text-gray-500">
                  Total: ${tripWithDefaults.budget}
                </p>
              </div>
            </div>
            <div className="space-y-2">
              <div className="flex justify-between">
                <span className="text-gray-600 dark:text-gray-400">Budget</span>
                <span className="font-semibold">
                  ${tripWithDefaults.budget}
                </span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600 dark:text-gray-400">Spent</span>
                <span className="font-semibold">${tripWithDefaults.spent}</span>
              </div>
              <div className="pt-2">
                <div className="flex justify-between text-sm mb-1">
                  <span>Budget Used</span>
                  <span>
                    {tripWithDefaults.budget > 0
                      ? Math.round(
                          (tripWithDefaults.spent / tripWithDefaults.budget) *
                            100
                        )
                      : 0}
                    %
                  </span>
                </div>
                <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                  <div
                    className="bg-blue-600 h-2 rounded-full"
                    style={{
                      width: `${
                        tripWithDefaults.budget > 0
                          ? Math.min(
                              100,
                              (tripWithDefaults.spent /
                                tripWithDefaults.budget) *
                                100
                            )
                          : 0
                      }%`,
                    }}
                  ></div>
                </div>
              </div>
            </div>
          </div>

          {/* Duration Card */}
          <div className="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
            <div className="flex items-center gap-3 mb-4">
              <div className="p-2 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 rounded-lg">
                <Clock size={24} />
              </div>
              <div>
                <h3 className="font-semibold">Trip Duration</h3>
                <p className="text-sm text-gray-500">
                  {tripWithDefaults.duration}
                </p>
              </div>
            </div>
            <div className="space-y-3">
              <div className="flex justify-between">
                <span className="text-gray-600 dark:text-gray-400">Dates</span>
                <span className="font-semibold">{tripWithDefaults.dates}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600 dark:text-gray-400">
                  Duration
                </span>
                <span className="font-semibold">
                  {tripWithDefaults.duration}
                </span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600 dark:text-gray-400">
                  Travelers
                </span>
                <span className="font-semibold">
                  {tripWithDefaults.travelers}
                </span>
              </div>
            </div>
          </div>

          {/* Rating Card */}
          <div className="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
            <div className="flex items-center gap-3 mb-4">
              <div className="p-2 bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300 rounded-lg">
                <Star size={24} />
              </div>
              <div>
                <h3 className="font-semibold">Trip Rating</h3>
                <p className="text-sm text-gray-500">
                  {tripWithDefaults.reviews} reviews
                </p>
              </div>
            </div>
            <div className="space-y-3">
              <div className="flex items-center gap-2">
                <Star className="text-yellow-400 fill-current" size={24} />
                <span className="text-2xl font-bold">
                  {tripWithDefaults.rating}
                </span>
                <span className="text-gray-500">/ 5.0</span>
              </div>
              <div className="flex flex-wrap gap-2">
                {tripWithDefaults.highlights.map((highlight, index) => (
                  <span
                    key={index}
                    className="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-sm rounded-full"
                  >
                    {highlight}
                  </span>
                ))}
              </div>
            </div>
          </div>
        </div>

        {/* Progress Bar Section */}
        <div className="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div className="flex justify-between items-center mb-6">
            <div>
              <h3 className="text-xl font-bold">Trip Progress</h3>
              <p className="text-gray-600 dark:text-gray-400">
                Track your trip preparation and completion
              </p>
            </div>
            <div className="text-right">
              <div className="text-2xl font-bold">{selectedProgress}%</div>
              <div className="text-sm text-gray-500">Overall Progress</div>
            </div>
          </div>

          {/* Main Progress Bar */}
          <div className="relative mb-8">
            <div className="flex justify-between mb-2">
              <span className="text-sm font-medium">Current Progress</span>
              <span className="text-sm font-medium">{selectedProgress}%</span>
            </div>
            <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
              <div
                className={`h-4 rounded-full transition-all duration-500 ${getProgressColor(
                  selectedProgress
                )}`}
                style={{ width: `${selectedProgress}%` }}
              ></div>
            </div>

            {/* Progress Steps */}
            <div className="flex justify-between mt-6 relative">
              <div className="absolute top-0 left-0 right-0 h-0.5 bg-gray-300 dark:bg-gray-600 -translate-y-4"></div>
              {progressSteps.map((step) => (
                <button
                  key={step.id}
                  onClick={() =>
                    isEditingProgress && handleProgressUpdate(step.value)
                  }
                  className={`flex flex-col items-center relative z-10 ${
                    isEditingProgress
                      ? "cursor-pointer hover:scale-110 transition-transform"
                      : "cursor-default"
                  }`}
                >
                  <div
                    className={`w-12 h-12 rounded-full flex items-center justify-center mb-2 ${
                      selectedProgress >= step.value
                        ? "bg-blue-600 text-white"
                        : "bg-gray-200 dark:bg-gray-700 text-gray-500"
                    }`}
                  >
                    {step.icon}
                  </div>
                  <span
                    className={`text-sm font-medium ${
                      selectedProgress >= step.value
                        ? "text-blue-600 dark:text-blue-400"
                        : "text-gray-500"
                    }`}
                  >
                    {step.label}
                  </span>
                  {isEditingProgress && selectedProgress === step.value && (
                    <div className="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                      <CheckSquare size={12} className="text-white" />
                    </div>
                  )}
                </button>
              ))}
            </div>
          </div>

          {/* Status Control Section */}
          {isEditingProgress && (
            <div className="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-6">
              <h4 className="font-bold mb-3 flex items-center gap-2">
                <Target size={18} />
                Update Trip Status
              </h4>
              <div className="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
                {progressSteps.map((step) => (
                  <button
                    key={step.id}
                    onClick={() => {
                      setSelectedProgress(step.value);
                      setNewStatus(step.label);
                    }}
                    className={`p-3 rounded-lg flex flex-col items-center transition-all ${
                      selectedProgress === step.value
                        ? "bg-blue-600 text-white shadow-lg"
                        : "bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                    }`}
                  >
                    <div className="mb-2">{step.icon}</div>
                    <span className="text-sm font-medium">{step.label}</span>
                  </button>
                ))}
              </div>

              <div className="flex flex-col md:flex-row items-center justify-between gap-4">
                <div className="flex-1">
                  <label className="block text-sm font-medium mb-2">
                    Custom Progress (%)
                  </label>
                  <div className="flex items-center gap-3">
                    <input
                      type="range"
                      min="0"
                      max="100"
                      value={selectedProgress}
                      onChange={(e) => {
                        const value = parseInt(e.target.value);
                        setSelectedProgress(value);
                        setNewStatus(getStatusFromProgress(value));
                      }}
                      className="flex-1"
                    />
                    <span className="font-bold w-16 text-center">
                      {selectedProgress}%
                    </span>
                  </div>
                </div>
                <button
                  onClick={saveProgressChanges}
                  className="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2 whitespace-nowrap"
                >
                  <Save size={18} />
                  Save Changes
                </button>
              </div>
            </div>
          )}

          {/* Quick Stats */}
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div className="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
              <div className="flex items-center gap-2 mb-2">
                <div className="p-2 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 rounded-lg">
                  <CheckCircle size={20} />
                </div>
                <span className="text-2xl font-bold">
                  {completedTasks}/{totalTasks}
                </span>
              </div>
              <div className="text-sm text-gray-600 dark:text-gray-400">
                Tasks Completed
              </div>
            </div>

            <div className="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
              <div className="flex items-center gap-2 mb-2">
                <div className="p-2 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-lg">
                  <TrendingUp size={20} />
                </div>
                <span className="text-2xl font-bold">{tasksProgress}%</span>
              </div>
              <div className="text-sm text-gray-600 dark:text-gray-400">
                Task Progress
              </div>
            </div>

            <div className="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
              <div className="flex items-center gap-2 mb-2">
                <div className="p-2 bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300 rounded-lg">
                  <Calendar size={20} />
                </div>
                <span className="text-2xl font-bold">
                  {Math.round((selectedProgress / 100) * 10)}/10
                </span>
              </div>
              <div className="text-sm text-gray-600 dark:text-gray-400">
                Trip Progress
              </div>
            </div>

            <div className="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
              <div className="flex items-center gap-2 mb-2">
                <div className="p-2 bg-orange-100 dark:bg-orange-900 text-orange-600 dark:text-orange-300 rounded-lg">
                  <DollarSign size={20} />
                </div>
                <span className="text-2xl font-bold">
                  {tripWithDefaults.budget > 0
                    ? Math.round(
                        (tripWithDefaults.spent / tripWithDefaults.budget) * 100
                      )
                    : 0}
                  %
                </span>
              </div>
              <div className="text-sm text-gray-600 dark:text-gray-400">
                Budget Used
              </div>
            </div>
          </div>
        </div>

        {/* Tabs */}
        <div className="mb-8">
          <div className="flex gap-1 bg-gray-100 dark:bg-gray-800 p-1 rounded-lg">
            {tabs.map((tab) => (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={`flex items-center justify-center gap-2 flex-1 py-3 px-4 rounded-md text-sm font-semibold transition-colors ${
                  activeTab === tab.id
                    ? "bg-white dark:bg-gray-700 shadow"
                    : "hover:bg-gray-200 dark:hover:bg-gray-700"
                }`}
              >
                {tab.icon}
                {tab.label}
              </button>
            ))}
          </div>
        </div>

        {/* Tab Content */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
          {activeTab === "progress" ? (
            <div className="space-y-6">
              {/* Tasks Management */}
              <div>
                <div className="flex justify-between items-center mb-6">
                  <h3 className="text-xl font-bold">Trip Preparation Tasks</h3>
                  <div className="text-sm text-gray-500">
                    {completedTasks} of {totalTasks} completed ({tasksProgress}
                    %)
                  </div>
                </div>

                <div className="mb-6">
                  <div className="flex gap-2 mb-4">
                    <input
                      type="text"
                      value={newTask}
                      onChange={(e) => setNewTask(e.target.value)}
                      placeholder="Add a new task..."
                      className="flex-grow px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900"
                      onKeyPress={(e) => e.key === "Enter" && addTask()}
                    />
                    <button
                      onClick={addTask}
                      className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
                    >
                      <Plus size={18} />
                      Add
                    </button>
                  </div>

                  <div className="space-y-3">
                    {tasks.map((task) => (
                      <div
                        key={task.id}
                        className="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg"
                      >
                        <div className="flex items-center gap-4">
                          <button
                            onClick={() => toggleTask(task.id)}
                            className={`w-6 h-6 rounded-full border-2 flex items-center justify-center ${
                              task.completed
                                ? "bg-green-500 border-green-500 text-white"
                                : "border-gray-300 dark:border-gray-600"
                            }`}
                          >
                            {task.completed && <Check size={14} />}
                          </button>
                          <div className="flex items-center gap-3">
                            <div className="text-gray-500">{task.icon}</div>
                            <span
                              className={
                                task.completed
                                  ? "line-through text-gray-500"
                                  : "font-medium"
                              }
                            >
                              {task.title}
                            </span>
                          </div>
                        </div>
                        <div className="flex items-center gap-3">
                          <span
                            className={`px-3 py-1 text-xs rounded-full ${
                              task.completed
                                ? "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                                : "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200"
                            }`}
                          >
                            {task.completed ? "Completed" : "Pending"}
                          </span>
                          <button
                            onClick={() => removeTask(task.id)}
                            className="p-2 text-gray-400 hover:text-red-500 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700"
                          >
                            <Minus size={16} />
                          </button>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>

                {/* Task Progress Visualization */}
                <div className="bg-gray-50 dark:bg-gray-900 p-6 rounded-xl">
                  <h4 className="font-bold mb-4 flex items-center gap-2">
                    <TrendingUp size={20} />
                    Task Completion Progress
                  </h4>
                  <div className="space-y-4">
                    <div className="flex justify-between">
                      <span>Tasks Completed</span>
                      <span className="font-bold">
                        {completedTasks}/{totalTasks}
                      </span>
                    </div>
                    <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                      <div
                        className="bg-green-500 h-3 rounded-full transition-all duration-500"
                        style={{ width: `${tasksProgress}%` }}
                      ></div>
                    </div>
                    <div className="text-right text-sm text-gray-500">
                      {tasksProgress}% complete
                    </div>
                  </div>
                </div>
              </div>

              {/* Progress Timeline */}
              <div>
                <h3 className="text-xl font-bold mb-4">Progress Timeline</h3>
                <div className="space-y-6">
                  {progressSteps.map((step) => (
                    <div key={step.id} className="flex items-start">
                      <div
                        className={`w-12 h-12 rounded-full flex items-center justify-center mr-4 ${
                          selectedProgress >= step.value
                            ? "bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300"
                            : "bg-gray-100 dark:bg-gray-800 text-gray-400"
                        }`}
                      >
                        {step.icon}
                      </div>
                      <div className="flex-grow">
                        <div className="flex justify-between items-center">
                          <h4 className="font-medium text-lg">{step.label}</h4>
                          <span
                            className={`text-sm px-3 py-1 rounded-full ${
                              selectedProgress >= step.value
                                ? "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                                : "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400"
                            }`}
                          >
                            {selectedProgress >= step.value
                              ? "Completed"
                              : "Pending"}
                          </span>
                        </div>
                        <p className="text-sm text-gray-600 dark:text-gray-400 mt-2">
                          {step.label === "Planning" &&
                            "Research destinations, create itinerary, and make initial bookings"}
                          {step.label === "Booked" &&
                            "All major bookings confirmed (flights, accommodation, transportation)"}
                          {step.label === "Ongoing" &&
                            "Currently traveling, enjoying the trip experience"}
                          {step.label === "Completed" &&
                            "Trip finished, all activities completed successfully"}
                          {step.label === "Cancelled" &&
                            "Trip was cancelled or postponed"}
                        </p>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          ) : activeTab === "overview" ? (
            <div className="space-y-6">
              <div>
                <h3 className="text-xl font-bold mb-4">Trip Overview</h3>
                <div className="bg-gray-50 dark:bg-gray-900 p-6 rounded-xl">
                  <p className="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Your trip to {tripWithDefaults.destination},{" "}
                    {tripWithDefaults.country} is{" "}
                    {tripWithDefaults.status.toLowerCase()}.
                    {tripWithDefaults.duration &&
                      ` The trip duration is ${tripWithDefaults.duration}.`}
                    {tripWithDefaults.travelers > 1 &&
                      ` You'll be traveling with ${
                        tripWithDefaults.travelers - 1
                      } other people.`}
                  </p>
                </div>
              </div>

              <div>
                <h3 className="text-xl font-bold mb-4">Trip Highlights</h3>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                  {tripWithDefaults.highlights.map((highlight, index) => (
                    <div
                      key={index}
                      className="bg-gray-50 dark:bg-gray-900 p-4 rounded-xl text-center"
                    >
                      <div className="text-2xl mb-2">
                        {highlight === "Adventure" && "⛰️"}
                        {highlight === "Sightseeing" && "🏛️"}
                        {highlight === "Relaxation" && "🏖️"}
                        {highlight === "Food" && "🍽️"}
                        {highlight === "Shopping" && "🛍️"}
                        {highlight === "Culture" && "🎭"}
                      </div>
                      <span className="font-medium">{highlight}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          ) : activeTab === "itinerary" ? (
            <div className="text-center py-12">
              <Calendar className="mx-auto text-gray-400 mb-4" size={48} />
              <h4 className="text-lg font-semibold mb-2">
                Itinerary Coming Soon
              </h4>
              <p className="text-gray-500">
                Your trip itinerary will be available here.
              </p>
            </div>
          ) : activeTab === "expenses" ? (
            <div className="space-y-6">
              <h3 className="text-xl font-bold mb-6">Expense Summary</h3>
              <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div className="bg-gray-50 dark:bg-gray-900 p-6 rounded-xl">
                  <h4 className="font-bold mb-4">Budget Breakdown</h4>
                  <div className="space-y-4">
                    <div className="flex justify-between">
                      <span>Total Budget</span>
                      <span className="font-bold">
                        ${tripWithDefaults.budget}
                      </span>
                    </div>
                    <div className="flex justify-between">
                      <span>Amount Spent</span>
                      <span className="font-bold">
                        ${tripWithDefaults.spent}
                      </span>
                    </div>
                    <div className="border-t border-gray-300 dark:border-gray-700 pt-4">
                      <div className="flex justify-between font-bold">
                        <span>Remaining</span>
                        <span
                          className={
                            tripWithDefaults.budget - tripWithDefaults.spent >=
                            0
                              ? "text-green-600"
                              : "text-red-600"
                          }
                        >
                          ${tripWithDefaults.budget - tripWithDefaults.spent}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div className="text-center py-12">
                  <DollarSign
                    className="mx-auto text-gray-400 mb-4"
                    size={48}
                  />
                  <h4 className="text-lg font-semibold mb-2">
                    Detailed Expenses
                  </h4>
                  <p className="text-gray-500">
                    Expense breakdown will be available here.
                  </p>
                </div>
              </div>
            </div>
          ) : null}
        </div>
      </div>
    </div>
  );
};

export default MyTripDetails;
