// src/services/exploreService.js
import axios from "axios";
import api from "../config/api";

const API_URL = `${api}/explore`;

// Get all destinations
export const getAllDestinations = async () => {
  try {
    const response = await axios.get(`${API_URL}/`);
    return response.data;
  } catch (error) {
    console.error("Error getting destinations:", error);
    throw error;
  }
};

// Get single destination by ID
export const getDestinationById = async (destinationId) => {
  try {
    const response = await axios.get(`${API_URL}/${destinationId}`);
    return response.data;
  } catch (error) {
    console.error("Error getting destination by ID:", error);
    throw error;
  }
};

// Search destinations
export const searchDestinations = async (query, country) => {
  try {
    const params = {};
    if (query) params.query = query;
    if (country) params.country = country;

    const response = await axios.get(`${API_URL}/search`, { params });
    return response.data;
  } catch (error) {
    console.error("Error searching destinations:", error);
    throw error;
  }
};

// Create new destination
export const createDestination = async (destinationData) => {
  try {
    const response = await axios.post(`${API_URL}/`, destinationData);
    return response.data;
  } catch (error) {
    console.error("Error creating destination:", error);
    throw error;
  }
};
