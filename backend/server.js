// server.js
import express from "express";
import cors from "cors";
import dotenv from "dotenv";
import userRouter from "./routes/userRoutes.js";
import { configDB } from "./config/db.js";
import userAuthRouter from "./routes/authentication/userAuthRoutes.js";
import tripRouter from "./routes/tripRoutes.js";
import exploreRouter from "./routes/exploreRoutes.js";
import businessRouter from "./routes/businessRoutes.js";
import scheduleRouter from "./routes/scheduleRoutes.js";
import bookingRequestRouter from "./routes/bookingRequestRoutes.js";

// Load environment variables
dotenv.config();

// Initialize express app
const app = express();

// Set port
const PORT = process.env.PORT || 3000;

// Database connection
configDB();

// Middleware
app.use(cors()); // Enable CORS for all routes
app.use(express.json()); // Parse JSON request bodies

// API Routes
app.use("/api/user", userRouter);
app.use("/api/trip", tripRouter);
app.use("/api/explore", exploreRouter);
app.use("/api/business", businessRouter);
app.use("/api/schedule", scheduleRouter);
app.use("/api/booking-request", bookingRequestRouter);

//Authnetication
app.use("/api/user/auth", userAuthRouter);

// Start server
app.listen(PORT, () => {
  console.log(`✅ Server is running on port ${PORT}`);
  console.log(`🌐 http://localhost:${PORT}`);
});
