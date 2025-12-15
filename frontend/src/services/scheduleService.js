import axios from "axios";
import api from "../config/api";

const API_URL = `${api}/schedule`;

// Create schedule
export const createSchedule = async (scheduleData) => {
  try {
    const response = await axios.post(`${API_URL}/`, scheduleData);
    return response.data;
  } catch (error) {
    console.error("Error creating schedule:", error);
    throw error;
  }
};

// Get all schedules for a business
export const getBusinessSchedules = async (businessId) => {
  try {
    const response = await axios.get(`${API_URL}/business/${businessId}`);
    return response.data;
  } catch (error) {
    console.error("Error getting business schedules:", error);
    throw error;
  }
};

// Get single schedule
export const getSchedule = async (scheduleId) => {
  try {
    const response = await axios.get(`${API_URL}/${scheduleId}`);
    return response.data;
  } catch (error) {
    console.error("Error getting schedule:", error);
    throw error;
  }
};

// Update schedule
export const updateSchedule = async (scheduleId, scheduleData) => {
  try {
    const response = await axios.put(`${API_URL}/${scheduleId}`, scheduleData);
    return response.data;
  } catch (error) {
    console.error("Error updating schedule:", error);
    throw error;
  }
};

// Delete schedule
export const deleteSchedule = async (scheduleId) => {
  try {
    const response = await axios.delete(`${API_URL}/${scheduleId}`);
    return response.data;
  } catch (error) {
    console.error("Error deleting schedule:", error);
    throw error;
  }
};
