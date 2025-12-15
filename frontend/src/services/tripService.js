// src/services/tripService.js
import axios from "axios";
import api from "../config/api";

const API_URL = `${api}/trip`;

// Get all trips for a user
export const getUserTrips = async (userId) => {
  try {
    const response = await axios.get(`${API_URL}/user/${userId}`);
    return response.data;
  } catch (error) {
    console.error("Error getting user trips:", error);
    throw error;
  }
};

// Get single trip by ID
export const getTripById = async (tripId) => {
  try {
    const response = await axios.get(`${API_URL}/${tripId}`);
    return response.data;
  } catch (error) {
    console.error("Error getting trip by ID:", error);
    throw error;
  }
};

// Create new trip
export const createTrip = async (tripData) => {
  try {
    const response = await axios.post(`${API_URL}/`, tripData);
    return response.data;
  } catch (error) {
    console.error("Error creating trip:", error);
    throw error;
  }
};

// Update trip
export const updateTrip = async (tripId, tripData) => {
  try {
    const response = await axios.put(`${API_URL}/${tripId}`, tripData);
    return response.data;
  } catch (error) {
    console.error("Error updating trip:", error);
    throw error;
  }
};

// Delete trip
export const deleteTrip = async (tripId) => {
  try {
    const response = await axios.delete(`${API_URL}/${tripId}`);
    return response.data;
  } catch (error) {
    console.error("Error deleting trip:", error);
    throw error;
  }
};
