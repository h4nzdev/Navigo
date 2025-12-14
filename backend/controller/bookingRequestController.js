import BookingRequest from "../model/booking_request.js";

// Create booking request
export const createBookingRequest = async (req, res) => {
  try {
    const bookingData = req.body;
    const newBooking = await BookingRequest.create(bookingData);
    res.status(201).json(newBooking);
  } catch (error) {
    console.error("Create booking request error:", error);
    res.status(500).json({ error: "Server error" });
  }
};

// Get all booking requests for a business
export const getBusinessBookingRequests = async (req, res) => {
  try {
    const { business_id } = req.params;
    const bookings = await BookingRequest.find({ business_id })
      .populate("schedule_id")
      .populate("customer_id");
    res.status(200).json(bookings);
  } catch (error) {
    console.error("Get booking requests error:", error);
    res.status(500).json({ error: "Server error" });
  }
};

// Get single booking request
export const getBookingRequest = async (req, res) => {
  try {
    const { id } = req.params;
    const booking = await BookingRequest.findById(id)
      .populate("schedule_id")
      .populate("customer_id");

    if (!booking) {
      return res.status(404).json({ error: "Booking request not found" });
    }

    res.status(200).json(booking);
  } catch (error) {
    console.error("Get booking request error:", error);
    res.status(500).json({ error: "Server error" });
  }
};

// Update booking request status
export const updateBookingStatus = async (req, res) => {
  try {
    const { id } = req.params;
    const { status } = req.body;

    const updatedBooking = await BookingRequest.findByIdAndUpdate(
      id,
      { $set: { status } },
      { new: true }
    );

    if (!updatedBooking) {
      return res.status(404).json({ error: "Booking request not found" });
    }

    res.status(200).json(updatedBooking);
  } catch (error) {
    console.error("Update booking status error:", error);
    res.status(500).json({ error: "Server error" });
  }
};

// Update booking request (whole data)
export const updateBookingRequest = async (req, res) => {
  try {
    const { id } = req.params;
    const updateData = req.body;

    const updatedBooking = await BookingRequest.findByIdAndUpdate(
      id,
      { $set: updateData },
      { new: true }
    );

    if (!updatedBooking) {
      return res.status(404).json({ error: "Booking request not found" });
    }

    res.status(200).json(updatedBooking);
  } catch (error) {
    console.error("Update booking request error:", error);
    res.status(500).json({ error: "Server error" });
  }
};

// Delete booking request
export const deleteBookingRequest = async (req, res) => {
  try {
    const { id } = req.params;
    const deletedBooking = await BookingRequest.findByIdAndDelete(id);

    if (!deletedBooking) {
      return res.status(404).json({ error: "Booking request not found" });
    }

    res.status(200).json({ message: "Booking request deleted successfully" });
  } catch (error) {
    console.error("Delete booking request error:", error);
    res.status(500).json({ error: "Server error" });
  }
};
