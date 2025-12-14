import express from "express";
import {
  createSchedule,
  getBusinessSchedules,
  getSchedule,
  updateSchedule,
  deleteSchedule,
} from "../controller/scheduleController.js";

const scheduleRouter = express.Router();

// Create schedule
scheduleRouter.post("/", createSchedule);

// Get all schedules for a business
scheduleRouter.get("/business/:business_id", getBusinessSchedules);

// Get single schedule
scheduleRouter.get("/:id", getSchedule);

// Update schedule
scheduleRouter.put("/:id", updateSchedule);

// Delete schedule
scheduleRouter.delete("/:id", deleteSchedule);

export default scheduleRouter;
