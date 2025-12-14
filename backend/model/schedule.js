import mongoose from "mongoose";

const scheduleSchema = new mongoose.Schema({
  business_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: "Business",
    required: true,
  },
  from: {
    type: String,
    required: true,
  },
  to: {
    type: String,
    required: true,
  },
  type: {
    type: String,
    default: "airline",
  },
  departure_time: {
    type: Date,
    required: true,
  },
  arrival_time: {
    type: Date,
    required: true,
  },
  price: {
    type: Number,
    required: true,
  },
  seats: {
    type: Number,
    required: true,
  },
  aircraft_type: String,
  status: {
    type: String,
    default: "active",
  },
  created_at: {
    type: Date,
    default: Date.now,
  },
});

const Schedule = mongoose.model("Schedule", scheduleSchema);
export default Schedule;
