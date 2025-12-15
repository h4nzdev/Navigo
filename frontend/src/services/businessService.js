// src/services/businessService.js
import axios from "axios";
import api from "../config/api";

const API_URL = `${api}/business`;

// Register new business
export const registerBusiness = async (businessData) => {
  try {
    const response = await axios.post(`${API_URL}/register`, businessData);
    return response.data;
  } catch (error) {
    console.error("Error registering business:", error);
    throw error;
  }
};

// Login business
export const loginBusiness = async (businessData) => {
  try {
    const response = await axios.post(`${API_URL}/login`, businessData);
    return response.data;
  } catch (error) {
    console.error("Error logging in business:", error);
    throw error;
  }
};

// Get business profile
export const getBusinessProfile = async (businessId) => {
  try {
    const response = await axios.get(`${API_URL}/profile/${businessId}`);
    return response.data;
  } catch (error) {
    console.error("Error getting business profile:", error);
    throw error;
  }
};

// Update business profile
export const updateBusinessProfile = async (businessId, businessData) => {
  try {
    const response = await axios.put(
      `${API_URL}/profile/${businessId}`,
      businessData
    );
    return response.data;
  } catch (error) {
    console.error("Error updating business profile:", error);
    throw error;
  }
};

// Get all businesses (admin)
export const getAllBusinesses = async () => {
  try {
    const response = await axios.get(`${API_URL}/all`);
    return response.data;
  } catch (error) {
    console.error("Error getting all businesses:", error);
    throw error;
  }
};
