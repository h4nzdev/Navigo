// src/services/preferencesService.js
import axios from "axios";
import api from "../../config/api";

const API_URL = api;

export const getPreferences = async (userId) => {
  try {
    const response = await axios.get(`${API_URL}/user/preferences/${userId}`);
    return response.data;
  } catch (error) {
    console.error("Error getting preferences:", error);
    throw error;
  }
};

export const updatePreferences = async (userId, data) => {
  try {
    const response = await axios.put(
      `${API_URL}/user/preferences/${userId}`,
      data
    );
    return response.data;
  } catch (error) {
    console.error("Error updating preferences:", error);
    throw error;
  }
};
