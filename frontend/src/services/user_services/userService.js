// src/services/userService.js
import axios from "axios";
import api from "../../config/api";

const API_URL = api;

export const getUserData = async (userId) => {
  try {
    const response = await axios.get(`${API_URL}/user/profile/${userId}`);
    return response.data;
  } catch (error) {
    console.error("Error getting user data:", error);
    throw error;
  }
};

export const updateUserData = async (userId, data) => {
  try {
    const response = await axios.put(`${API_URL}/user/profile/${userId}`, data);
    return response.data;
  } catch (error) {
    console.error("Error updating user data:", error);
    throw error;
  }
};
