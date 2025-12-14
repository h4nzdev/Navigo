import React, { useState } from "react";
import {
  X,
  Plane,
  Users,
  DollarSign,
  MessageSquare,
  Calendar,
  Shield,
  AlertCircle,
} from "lucide-react";
import { createBookingRequest } from "../../services/bookingRequestService";

const BookingRequestModal = ({
  isOpen,
  onClose,
  schedule,
  business,
  userId,
}) => {
  const [seatsRequested, setSeatsRequested] = useState(1);
  const [specialRequests, setSpecialRequests] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  if (!isOpen || !schedule) return null;

  const totalPrice = (schedule.price || 0) * seatsRequested;
  const maxSeats = Math.min(schedule.available_seats || 0, 10); // Limit to 10 seats per request

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    // Validation
    if (!userId) {
      setError("You must be logged in to make a booking request.");
      setLoading(false);
      return;
    }

    if (!schedule._id || !schedule.business_id) {
      setError("Invalid schedule information.");
      setLoading(false);
      return;
    }

    try {
      // Create booking request data
      const bookingData = {
        schedule_id: schedule._id,
        customer_id: userId,
        business_id: schedule.business_id,
        seats_requested: seatsRequested,
        price_offered: totalPrice,
        special_requests: specialRequests.trim() || undefined, // Send undefined if empty
        status: "pending",
      };

      // Call the API to create booking request
      const response = await createBookingRequest(bookingData);

      if (response && response._id) {
        alert(
          "✅ Booking request sent successfully! The business will respond soon."
        );
        onClose();
      } else {
        throw new Error("Failed to create booking request");
      }
    } catch (error) {
      console.error("Error submitting booking request:", error);
      setError(
        error.response?.data?.error ||
          "Failed to send booking request. Please try again."
      );
    } finally {
      setLoading(false);
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
      return "TBD";
    }
  };

  const formatDate = (dateString) => {
    try {
      const date = new Date(dateString);
      return date.toLocaleDateString();
    } catch (err) {
      return "Date TBD";
    }
  };

  const businessName =
    business?.business_name || schedule.business_name || "Unknown Business";

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
      <div className="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        {/* Modal Header */}
        <div className="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
          <div className="flex items-center space-x-3">
            <div className="p-2 bg-cyan-100 dark:bg-cyan-900 rounded-lg">
              <Plane className="text-cyan-600 dark:text-cyan-300" size={24} />
            </div>
            <div>
              <h2 className="text-xl font-bold">Request Booking</h2>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                Send booking request to {businessName}
              </p>
            </div>
          </div>
          <button
            onClick={onClose}
            className="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"
            disabled={loading}
          >
            <X size={20} />
          </button>
        </div>

        {/* Error Message */}
        {error && (
          <div className="mx-6 mt-4 p-3 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 rounded-lg flex items-center gap-2">
            <AlertCircle size={16} />
            <span>{error}</span>
          </div>
        )}

        {/* Flight Details */}
        <div className="p-6 border-b border-gray-200 dark:border-gray-700">
          <div className="flex justify-between items-center mb-4">
            <div>
              <div className="font-bold text-lg text-gray-900 dark:text-gray-100">
                {businessName}
              </div>
              <div className="text-sm text-gray-500">
                {schedule.aircraft_type || "Standard Aircraft"}
              </div>
            </div>
            <div className="text-right">
              <div className="font-bold text-2xl text-cyan-600">
                ₱{(schedule.price || 0).toLocaleString()}
              </div>
              <div className="text-sm text-gray-500">per seat</div>
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div className="text-center">
              <div className="font-bold text-gray-900 dark:text-gray-100">
                {formatTime(schedule.departure_time)}
              </div>
              <div className="text-sm text-gray-500">
                {schedule.from || "From"}
              </div>
              <div className="text-xs text-gray-400">
                {formatDate(schedule.departure_time)}
              </div>
            </div>
            <div className="text-center">
              <div className="text-gray-500 text-sm">
                {schedule.duration || "Duration"}
              </div>
              <div className="w-full h-0.5 bg-gray-300 dark:bg-gray-700 my-2 relative">
                <div className="absolute left-1/2 -translate-x-1/2 -translate-y-1/2">
                  <Plane size={16} className="text-cyan-600" />
                </div>
              </div>
              <div className="text-xs text-gray-500">Direct flight</div>
            </div>
            <div className="text-center">
              <div className="font-bold text-gray-900 dark:text-gray-100">
                {formatTime(schedule.arrival_time)}
              </div>
              <div className="text-sm text-gray-500">{schedule.to || "To"}</div>
              <div className="text-xs text-gray-400">
                {formatDate(schedule.arrival_time)}
              </div>
            </div>
          </div>

          <div className="text-sm text-gray-600 dark:text-gray-400">
            <span className="font-semibold">Available seats:</span>{" "}
            {schedule.available_seats || 0} / {schedule.seats || 0}
          </div>
        </div>

        {/* Booking Form */}
        <form onSubmit={handleSubmit} className="p-6">
          <div className="space-y-6">
            {/* Number of Seats */}
            <div className="space-y-2">
              <label className="text-sm font-semibold flex items-center text-gray-900 dark:text-gray-100">
                <Users size={16} className="mr-2 text-gray-400" />
                Number of Seats
              </label>
              <div className="flex items-center gap-4">
                <div className="flex items-center gap-2">
                  <button
                    type="button"
                    onClick={() =>
                      setSeatsRequested(Math.max(1, seatsRequested - 1))
                    }
                    className="w-8 h-8 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled={seatsRequested <= 1 || loading}
                  >
                    -
                  </button>
                  <span className="w-12 text-center font-bold text-lg text-gray-900 dark:text-gray-100">
                    {seatsRequested}
                  </span>
                  <button
                    type="button"
                    onClick={() =>
                      setSeatsRequested(Math.min(maxSeats, seatsRequested + 1))
                    }
                    className="w-8 h-8 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled={
                      seatsRequested >= maxSeats || loading || maxSeats <= 0
                    }
                  >
                    +
                  </button>
                </div>
                <div className="text-sm text-gray-500">
                  {maxSeats > 0
                    ? `Max ${maxSeats} seats per request`
                    : "No seats available"}
                </div>
              </div>
            </div>

            {/* Total Price */}
            <div className="bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-cyan-900/20 dark:to-blue-900/20 p-4 rounded-lg">
              <div className="flex justify-between items-center">
                <div>
                  <div className="text-sm text-gray-500">Total Amount</div>
                  <div className="text-3xl font-bold text-cyan-600">
                    ₱{totalPrice.toLocaleString()}
                  </div>
                </div>
                <DollarSign size={32} className="text-green-500" />
              </div>
              <div className="text-sm text-gray-500 mt-2">
                {seatsRequested} seat{seatsRequested > 1 ? "s" : ""} × ₱
                {(schedule.price || 0).toLocaleString()}
              </div>
            </div>

            {/* Special Requests */}
            <div className="space-y-2">
              <label className="text-sm font-semibold flex items-center text-gray-900 dark:text-gray-100">
                <MessageSquare size={16} className="mr-2 text-gray-400" />
                Special Requests (Optional)
              </label>
              <textarea
                value={specialRequests}
                onChange={(e) => setSpecialRequests(e.target.value)}
                placeholder="Any special requests or requirements..."
                className="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 min-h-[100px] text-gray-900 dark:text-gray-100 disabled:opacity-50"
                disabled={loading}
                maxLength={500}
              />
              <div className="text-xs text-gray-400 text-right">
                {specialRequests.length}/500 characters
              </div>
            </div>

            {/* Important Notes */}
            <div className="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
              <div className="flex items-start gap-2">
                <Shield
                  size={16}
                  className="text-yellow-600 dark:text-yellow-400 mt-0.5"
                />
                <div className="text-sm text-yellow-800 dark:text-yellow-300">
                  <p className="font-semibold">Important Notes:</p>
                  <ul className="list-disc list-inside mt-1 space-y-1">
                    <li>This is a booking request, not a confirmed booking</li>
                    <li>The business will respond within 24 hours</li>
                    <li>Payment will be arranged after acceptance</li>
                    <li>You may negotiate the price during the process</li>
                    <li>Cancellation policy applies per business terms</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          {/* Form Actions */}
          <div className="flex justify-end space-x-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button
              type="button"
              onClick={onClose}
              className="px-6 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-semibold disabled:opacity-50"
              disabled={loading}
            >
              Cancel
            </button>
            <button
              type="submit"
              className="px-6 py-3 bg-cyan-600 text-white hover:bg-cyan-700 rounded-lg font-semibold flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
              disabled={loading || maxSeats <= 0 || seatsRequested > maxSeats}
            >
              {loading ? (
                <>
                  <div className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                  <span>Sending Request...</span>
                </>
              ) : (
                <>
                  <Plane size={18} />
                  <span>
                    {maxSeats <= 0
                      ? "No Seats Available"
                      : "Send Booking Request"}
                  </span>
                </>
              )}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default BookingRequestModal;
