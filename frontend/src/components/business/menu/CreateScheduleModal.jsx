import React, { useContext, useState } from "react";
import { X, Plane, MapPin, Calendar, Users, DollarSign } from "lucide-react";
import { createSchedule } from "../../../services/scheduleService";
import { AuthContext } from "../../../context/AuthContext";

const CreateScheduleModal = ({ isOpen, onClose }) => {
  const { user } = useContext(AuthContext);
  const [formData, setFormData] = useState({
    from: "",
    to: "",
    type: "airline",
    departure_time: "",
    arrival_time: "",
    price: "",
    seats: "",
    aircraft_type: "",
  });
  const [loading, setLoading] = useState(false);

  if (!isOpen) return null;

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    const payload = {
      ...formData,
      business_id: user._id, // ✅ GUARANTEED VALUE
    };

    console.log("Payload sent:", payload);

    try {
      await createSchedule(payload);

      setFormData({
        from: "",
        to: "",
        type: "airline",
        departure_time: "",
        arrival_time: "",
        price: "",
        seats: "",
        aircraft_type: "",
      });

      onClose();
    } catch (error) {
      console.error("Error creating schedule:", error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
      <div className="bg-white dark:bg-gray-800 rounded-xl w-full max-w-lg">
        {/* Modal Header */}
        <div className="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
          <div className="flex items-center space-x-3">
            <div className="p-2 bg-cyan-100 dark:bg-cyan-900 rounded-lg">
              <Plane className="text-cyan-600 dark:text-cyan-300" size={24} />
            </div>
            <div>
              <h2 className="text-xl font-bold">Create Schedule</h2>
              <p className="text-sm text-gray-500 dark:text-gray-400">
                Add new flight schedule
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

        {/* Modal Form */}
        <form onSubmit={handleSubmit} className="p-6">
          <div className="space-y-4">
            {/* From */}
            <div className="space-y-2">
              <label className="text-sm font-semibold flex items-center">
                <MapPin size={16} className="mr-2 text-gray-400" />
                From
              </label>
              <input
                type="text"
                name="from"
                value={formData.from}
                onChange={handleChange}
                placeholder="e.g., Manila (MNL)"
                className="w-full text-white px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
                required
              />
            </div>

            {/* To */}
            <div className="space-y-2">
              <label className="text-sm font-semibold flex items-center">
                <MapPin size={16} className="mr-2 text-gray-400" />
                To
              </label>
              <input
                type="text"
                name="to"
                value={formData.to}
                onChange={handleChange}
                placeholder="e.g., Cebu (CEB)"
                className="w-full px-4 py-2 text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
                required
              />
            </div>

            {/* Departure Time */}
            <div className="space-y-2">
              <label className="text-sm font-semibold flex items-center">
                <Calendar size={16} className="mr-2 text-gray-400" />
                Departure Time
              </label>
              <input
                type="datetime-local"
                name="departure_time"
                value={formData.departure_time}
                onChange={handleChange}
                className="w-full px-4 py-2 text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
                required
              />
            </div>

            {/* Arrival Time */}
            <div className="space-y-2">
              <label className="text-sm font-semibold flex items-center">
                <Calendar size={16} className="mr-2 text-gray-400" />
                Arrival Time
              </label>
              <input
                type="datetime-local"
                name="arrival_time"
                value={formData.arrival_time}
                onChange={handleChange}
                className="w-full px-4 py-2 text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
                required
              />
            </div>

            {/* Seats */}
            <div className="space-y-2">
              <label className="text-sm font-semibold flex items-center">
                <Users size={16} className="mr-2 text-gray-400" />
                Available Seats
              </label>
              <input
                type="number"
                name="seats"
                value={formData.seats}
                onChange={handleChange}
                min="1"
                placeholder="e.g., 150"
                className="w-full px-4 py-2 text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
                required
              />
            </div>

            {/* Price */}
            <div className="space-y-2">
              <label className="text-sm font-semibold flex items-center">
                <DollarSign size={16} className="mr-2 text-gray-400" />
                Price
              </label>
              <input
                type="number"
                name="price"
                value={formData.price}
                onChange={handleChange}
                min="0"
                step="0.01"
                placeholder="e.g., 2500.00"
                className="w-full px-4 py-2 text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
                required
              />
            </div>

            {/* Aircraft Type */}
            <div className="space-y-2">
              <label className="text-sm font-semibold">Aircraft Type</label>
              <select
                name="aircraft_type"
                value={formData.aircraft_type}
                onChange={handleChange}
                className="w-full px-4 py-2 text-white bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
              >
                <option value="">Select aircraft</option>
                <option value="Boeing 737">Boeing 737</option>
                <option value="Airbus A320">Airbus A320</option>
                <option value="Airbus A321">Airbus A321</option>
                <option value="Boeing 777">Boeing 777</option>
                <option value="Airbus A350">Airbus A350</option>
              </select>
            </div>
          </div>

          {/* Form Actions */}
          <div className="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button
              type="button"
              onClick={onClose}
              className="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-semibold"
              disabled={loading}
            >
              Cancel
            </button>
            <button
              type="submit"
              className="px-4 py-2 bg-cyan-600 text-white hover:bg-cyan-700 rounded-lg font-semibold flex items-center space-x-2 disabled:opacity-50"
              disabled={loading}
            >
              {loading ? (
                <>
                  <div className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                  <span>Creating...</span>
                </>
              ) : (
                <>
                  <Plane size={18} />
                  <span>Create Schedule</span>
                </>
              )}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default CreateScheduleModal;
