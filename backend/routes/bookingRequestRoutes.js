import express from "express";
import {
  createBookingRequest,
  getBusinessBookingRequests,
  getBookingRequest,
  updateBookingStatus,
  updateBookingRequest,
  deleteBookingRequest,
} from "../controller/bookingRequestController.js";

const bookingRequestRouter = express.Router();

// Create booking request
bookingRequestRouter.post("/", createBookingRequest);

// Get all booking requests for a business
bookingRequestRouter.get("/business/:business_id", getBusinessBookingRequests);

// Get single booking request
bookingRequestRouter.get("/:id", getBookingRequest);

// Update booking request status only
bookingRequestRouter.patch("/:id/status", updateBookingStatus);

// Update booking request (whole data)
bookingRequestRouter.put("/:id", updateBookingRequest);

// Delete booking request
bookingRequestRouter.delete("/:id", deleteBookingRequest);

export default bookingRequestRouter;
