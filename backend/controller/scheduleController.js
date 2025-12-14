import Schedule from "../model/schedule.js";

// Create schedule
export const createSchedule = async (req, res) => {
  try {
    const scheduleData = req.body;
    const newSchedule = await Schedule.create(scheduleData);
    res.status(201).json(newSchedule);
  } catch (error) {
    console.error("Create schedule error:", error);
    res.status(500).json({ error: "Server error" });
  }
};

// Get all schedules for a business
export const getBusinessSchedules = async (req, res) => {
  try {
    const { business_id } = req.params;
    const schedules = await Schedule.find({ business_id });
    res.status(200).json(schedules);
  } catch (error) {
    console.error("Get schedules error:", error);
    res.status(500).json({ error: "Server error" });
  }
};

// Get single schedule
export const getSchedule = async (req, res) => {
  try {
    const { id } = req.params;
    const schedule = await Schedule.findById(id);

    if (!schedule) {
      return res.status(404).json({ error: "Schedule not found" });
    }

    res.status(200).json(schedule);
  } catch (error) {
    console.error("Get schedule error:", error);
    res.status(500).json({ error: "Server error" });
  }
};

// Update schedule
export const updateSchedule = async (req, res) => {
  try {
    const { id } = req.params;
    const updateData = req.body;

    const updatedSchedule = await Schedule.findByIdAndUpdate(
      id,
      { $set: updateData },
      { new: true }
    );

    if (!updatedSchedule) {
      return res.status(404).json({ error: "Schedule not found" });
    }

    res.status(200).json(updatedSchedule);
  } catch (error) {
    console.error("Update schedule error:", error);
    res.status(500).json({ error: "Server error" });
  }
};

// Delete schedule
export const deleteSchedule = async (req, res) => {
  try {
    const { id } = req.params;
    const deletedSchedule = await Schedule.findByIdAndDelete(id);

    if (!deletedSchedule) {
      return res.status(404).json({ error: "Schedule not found" });
    }

    res.status(200).json({ message: "Schedule deleted successfully" });
  } catch (error) {
    console.error("Delete schedule error:", error);
    res.status(500).json({ error: "Server error" });
  }
};
