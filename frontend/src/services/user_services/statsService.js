// src/services/statsService.js
import axios from "axios";
import api from "../../config/api";

const API_URL = api;

export const getStats = async (userId) => {
  try {
    const response = await axios.get(`${API_URL}/user/stats/${userId}`);
    return response.data;
  } catch (error) {
    console.error("Error getting stats:", error);
    throw error;
  }
};

export const updateStats = async (userId, data) => {
  try {
    const response = await axios.put(`${API_URL}/user/stats/${userId}`, data);
    return response.data;
  } catch (error) {
    console.error("Error updating stats:", error);
    throw error;
  }
};
