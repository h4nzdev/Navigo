import React, { useState } from "react";
import {
  X,
  Plane,
  Clock,
  DollarSign,
  Check,
  ChevronRight,
  Filter,
  SortAsc,
  Star,
  Users,
  Luggage,
  Wifi,
  Coffee,
  Tv,
  Shield,
} from "lucide-react";

const FlightsModal = ({ isOpen, onClose, searchParams }) => {
  const [sortBy, setSortBy] = useState("price");
  const [selectedFlight, setSelectedFlight] = useState(null);
  const [showFilters, setShowFilters] = useState(false);

  // Mock flights data
  const flights = [
    {
      id: 1,
      airline: "Japan Airlines",
      flightNo: "JL 001",
      departure: "10:00 AM",
      arrival: "2:00 PM",
      duration: "11h 0m",
      stops: "Non-stop",
      price: "$850",
      class: "Economy",
      amenities: ["wifi", "meals", "entertainment"],
      rating: 4.8,
      reviews: 1240,
      baggage: "2 checked bags",
    },
    {
      id: 2,
      airline: "ANA",
      flightNo: "NH 107",
      departure: "11:30 AM",
      arrival: "3:30 PM",
      duration: "11h 0m",
      stops: "Non-stop",
      price: "$920",
      class: "Economy",
      amenities: ["wifi", "meals", "priority"],
      rating: 4.9,
      reviews: 2150,
      baggage: "1 checked bag",
    },
    {
      id: 3,
      airline: "Delta Air Lines",
      flightNo: "DL 167",
      departure: "9:15 AM",
      arrival: "4:45 PM",
      duration: "12h 30m",
      stops: "1 stop (LAX)",
      price: "$780",
      class: "Economy",
      amenities: ["wifi", "snacks"],
      rating: 4.6,
      reviews: 890,
      baggage: "2 checked bags",
    },
    {
      id: 4,
      airline: "United Airlines",
      flightNo: "UA 879",
      departure: "8:45 PM",
      arrival: "6:30 AM (+1)",
      duration: "10h 45m",
      stops: "Non-stop",
      price: "$950",
      class: "Premium Economy",
      amenities: ["wifi", "meals", "extra legroom", "priority"],
      rating: 4.7,
      reviews: 1560,
      baggage: "2 checked bags",
    },
    {
      id: 5,
      airline: "American Airlines",
      flightNo: "AA 169",
      departure: "1:30 PM",
      arrival: "9:45 PM",
      duration: "13h 15m",
      stops: "1 stop (DFW)",
      price: "$720",
      class: "Economy",
      amenities: ["wifi"],
      rating: 4.4,
      reviews: 720,
      baggage: "1 checked bag",
    },
  ];

  const sortOptions = [
    { id: "price", label: "Price (Lowest)" },
    { id: "duration", label: "Duration (Shortest)" },
    { id: "departure", label: "Departure (Earliest)" },
    { id: "rating", label: "Rating (Highest)" },
  ];

  const filterOptions = [
    { id: "nonstop", label: "Non-stop only" },
    { id: "wifi", label: "Wi-Fi available" },
    { id: "meals", label: "Meals included" },
    { id: "baggage", label: "Free baggage" },
  ];

  const sortedFlights = [...flights].sort((a, b) => {
    if (sortBy === "price") {
      return (
        parseFloat(a.price.replace("$", "")) -
        parseFloat(b.price.replace("$", ""))
      );
    } else if (sortBy === "duration") {
      return (
        parseInt(a.duration.replace("h", "")) -
        parseInt(b.duration.replace("h", ""))
      );
    } else if (sortBy === "rating") {
      return b.rating - a.rating;
    }
    return 0;
  });

  const handleSelectFlight = (flight) => {
    setSelectedFlight(flight);
    console.log("Selected Flight:", flight);
  };

  const handleBookFlight = () => {
    if (selectedFlight) {
      console.log("Booking Flight:", selectedFlight);
      alert(
        `Booking ${selectedFlight.airline} ${selectedFlight.flightNo} for ${selectedFlight.price}`
      );
      onClose();
    }
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4">
      <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
        {/* Modal Header */}
        <div className="p-6 border-b border-gray-200 dark:border-gray-700">
          <div className="flex justify-between items-center">
            <div>
              <h2 className="text-2xl font-bold">Available Flights</h2>
              <p className="text-gray-600 dark:text-gray-400">
                {searchParams?.from || "LAX"} → {searchParams?.to || "Tokyo"} •{" "}
                {searchParams?.date || "Oct 15, 2025"}
              </p>
            </div>
            <button
              onClick={onClose}
              className="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition"
            >
              <X size={24} />
            </button>
          </div>

          {/* Sort and Filter */}
          <div className="flex flex-wrap gap-4 mt-4">
            <div className="flex items-center gap-2">
              <SortAsc size={18} className="text-gray-500" />
              <select
                value={sortBy}
                onChange={(e) => setSortBy(e.target.value)}
                className="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-sm font-medium"
              >
                {sortOptions.map((option) => (
                  <option key={option.id} value={option.id}>
                    Sort by: {option.label}
                  </option>
                ))}
              </select>
            </div>
            <button
              onClick={() => setShowFilters(!showFilters)}
              className="flex items-center gap-2 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600"
            >
              <Filter size={16} />
              Filters
            </button>
            <div className="text-sm text-gray-600 dark:text-gray-400">
              {flights.length} flights found
            </div>
          </div>

          {showFilters && (
            <div className="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
              <div className="flex flex-wrap gap-3">
                {filterOptions.map((filter) => (
                  <button
                    key={filter.id}
                    className="px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm hover:border-cyan-500"
                  >
                    {filter.label}
                  </button>
                ))}
              </div>
            </div>
          )}
        </div>

        {/* Modal Body */}
        <div className="p-6 overflow-y-auto max-h-[70vh]">
          <div className="space-y-4">
            {sortedFlights.map((flight) => (
              <div
                key={flight.id}
                className={`border rounded-xl p-4 transition-all ${
                  selectedFlight?.id === flight.id
                    ? "border-cyan-500 bg-cyan-50 dark:bg-cyan-900/20"
                    : "border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600"
                }`}
                onClick={() => handleSelectFlight(flight)}
              >
                <div className="flex flex-col md:flex-row md:items-center gap-4">
                  {/* Airline & Flight Info */}
                  <div className="flex-1">
                    <div className="flex items-center gap-3 mb-2">
                      <div className="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <Plane
                          className="text-blue-600 dark:text-blue-400"
                          size={20}
                        />
                      </div>
                      <div>
                        <h3 className="font-bold">{flight.airline}</h3>
                        <p className="text-sm text-gray-500">
                          {flight.flightNo} • {flight.class}
                        </p>
                      </div>
                      <div className="flex items-center gap-1 ml-auto md:ml-0">
                        <Star
                          size={14}
                          className="text-yellow-400 fill-current"
                        />
                        <span className="font-semibold">{flight.rating}</span>
                        <span className="text-gray-500 text-sm">
                          ({flight.reviews})
                        </span>
                      </div>
                    </div>

                    {/* Flight Times */}
                    <div className="flex items-center justify-between mb-3">
                      <div className="text-center">
                        <div className="font-bold text-lg">
                          {flight.departure}
                        </div>
                        <div className="text-xs text-gray-500">Departure</div>
                      </div>
                      <div className="flex-1 px-4">
                        <div className="relative">
                          <div className="h-0.5 bg-gray-300 dark:bg-gray-700"></div>
                          <div className="absolute left-0 top-1/2 -translate-y-1/2 w-2 h-2 bg-gray-400 rounded-full"></div>
                          <div className="absolute right-0 top-1/2 -translate-y-1/2 w-2 h-2 bg-gray-400 rounded-full"></div>
                          <div className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                            <Plane size={16} className="text-gray-400" />
                          </div>
                        </div>
                        <div className="text-center text-xs text-gray-500 mt-1">
                          {flight.duration}
                        </div>
                      </div>
                      <div className="text-center">
                        <div className="font-bold text-lg">
                          {flight.arrival}
                        </div>
                        <div className="text-xs text-gray-500">Arrival</div>
                      </div>
                    </div>

                    {/* Stops & Amenities */}
                    <div className="flex flex-wrap items-center gap-4">
                      <div className="flex items-center gap-1 text-sm">
                        <Clock size={14} className="text-gray-500" />
                        <span
                          className={
                            flight.stops === "Non-stop"
                              ? "text-green-600 font-medium"
                              : "text-gray-600"
                          }
                        >
                          {flight.stops}
                        </span>
                      </div>
                      <div className="flex items-center gap-1 text-sm">
                        <Luggage size={14} className="text-gray-500" />
                        <span>{flight.baggage}</span>
                      </div>
                      <div className="flex gap-1">
                        {flight.amenities.includes("wifi") && (
                          <span
                            className="p-1 bg-gray-100 dark:bg-gray-700 rounded"
                            title="Wi-Fi"
                          >
                            <Wifi size={12} />
                          </span>
                        )}
                        {flight.amenities.includes("meals") && (
                          <span
                            className="p-1 bg-gray-100 dark:bg-gray-700 rounded"
                            title="Meals"
                          >
                            <Coffee size={12} />
                          </span>
                        )}
                        {flight.amenities.includes("entertainment") && (
                          <span
                            className="p-1 bg-gray-100 dark:bg-gray-700 rounded"
                            title="Entertainment"
                          >
                            <Tv size={12} />
                          </span>
                        )}
                        {flight.amenities.includes("priority") && (
                          <span
                            className="p-1 bg-gray-100 dark:bg-gray-700 rounded"
                            title="Priority"
                          >
                            <Shield size={12} />
                          </span>
                        )}
                      </div>
                    </div>
                  </div>

                  {/* Price & Select Button */}
                  <div className="border-l pl-4 md:pl-6 border-gray-200 dark:border-gray-700 md:w-48">
                    <div className="text-right mb-3">
                      <div className="text-2xl font-bold">{flight.price}</div>
                      <div className="text-sm text-gray-500">per person</div>
                    </div>
                    <button
                      onClick={(e) => {
                        e.stopPropagation();
                        handleSelectFlight(flight);
                      }}
                      className={`w-full py-2 rounded-lg font-semibold transition ${
                        selectedFlight?.id === flight.id
                          ? "bg-cyan-600 text-white hover:bg-cyan-700"
                          : "bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                      }`}
                    >
                      {selectedFlight?.id === flight.id ? (
                        <div className="flex items-center justify-center gap-2">
                          <Check size={16} />
                          Selected
                        </div>
                      ) : (
                        "Select Flight"
                      )}
                    </button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Modal Footer */}
        <div className="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
          <div className="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
              {selectedFlight ? (
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 bg-cyan-100 dark:bg-cyan-900 rounded-lg flex items-center justify-center">
                    <Check
                      className="text-cyan-600 dark:text-cyan-400"
                      size={20}
                    />
                  </div>
                  <div>
                    <div className="font-bold">
                      {selectedFlight.airline} {selectedFlight.flightNo}
                    </div>
                    <div className="text-sm text-gray-500">
                      {selectedFlight.departure} → {selectedFlight.arrival} •{" "}
                      {selectedFlight.price}
                    </div>
                  </div>
                </div>
              ) : (
                <div className="text-gray-500">Select a flight to continue</div>
              )}
            </div>
            <div className="flex gap-3">
              <button
                onClick={onClose}
                className="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold"
              >
                Cancel
              </button>
              <button
                onClick={handleBookFlight}
                disabled={!selectedFlight}
                className={`px-6 py-3 rounded-lg font-semibold flex items-center gap-2 ${
                  selectedFlight
                    ? "bg-cyan-600 text-white hover:bg-cyan-700"
                    : "bg-gray-300 dark:bg-gray-700 text-gray-400 cursor-not-allowed"
                }`}
              >
                Continue to Booking
                <ChevronRight size={18} />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default FlightsModal;
