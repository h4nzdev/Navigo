import mongoose from "mongoose";

const bookingRequestSchema = new mongoose.Schema({
  schedule_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: "Schedule",
    required: true,
  },
  customer_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: "User",
    required: true,
  },
  business_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: "Business",
    required: true,
  },
  status: {
    type: String,
    default: "pending",
  },
  seats_requested: {
    type: Number,
    required: true,
  },
  price_offered: {
    type: Number,
    required: true,
  },
  special_requests: String,
  created_at: {
    type: Date,
    default: Date.now,
  },
});

const BookingRequest = mongoose.model("BookingRequest", bookingRequestSchema);
export default BookingRequest;
