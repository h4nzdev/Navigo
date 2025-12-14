import axios from "axios";

const API_URL = "http://localhost:3000/api/booking-request";

// Create booking request
export const createBookingRequest = async (bookingData) => {
  try {
    const response = await axios.post(`${API_URL}/`, bookingData);
    return response.data;
  } catch (error) {
    console.error("Error creating booking request:", error);
    throw error;
  }
};

// Get all booking requests for a business
export const getBusinessBookingRequests = async (businessId) => {
  try {
    const response = await axios.get(`${API_URL}/business/${businessId}`);
    return response.data;
  } catch (error) {
    console.error("Error getting business booking requests:", error);
    throw error;
  }
};

// Get single booking request
export const getBookingRequest = async (bookingId) => {
  try {
    const response = await axios.get(`${API_URL}/${bookingId}`);
    return response.data;
  } catch (error) {
    console.error("Error getting booking request:", error);
    throw error;
  }
};

// Update booking request status
export const updateBookingStatus = async (bookingId, status) => {
  try {
    const response = await axios.patch(`${API_URL}/${bookingId}/status`, {
      status,
    });
    return response.data;
  } catch (error) {
    console.error("Error updating booking status:", error);
    throw error;
  }
};

// Update booking request
export const updateBookingRequest = async (bookingId, bookingData) => {
  try {
    const response = await axios.put(`${API_URL}/${bookingId}`, bookingData);
    return response.data;
  } catch (error) {
    console.error("Error updating booking request:", error);
    throw error;
  }
};

// Delete booking request
export const deleteBookingRequest = async (bookingId) => {
  try {
    const response = await axios.delete(`${API_URL}/${bookingId}`);
    return response.data;
  } catch (error) {
    console.error("Error deleting booking request:", error);
    throw error;
  }
};
