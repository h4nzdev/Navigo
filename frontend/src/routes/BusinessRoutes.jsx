import React from "react";
import { BrowserRouter, Navigate, Route, Routes } from "react-router-dom";
import BusinessDashboard from "../components/business/BusinessDashboard";
import BusinessLayout from "../layout/BusinessLayout";
import FlightSchedules from "../components/business/FlightSchedules";
import Analytics from "../components/business/Analytics";
import PendingRequests from "../components/business/PendingRequests";

const BusinessRoutes = () => {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="*" element={<Navigate to="/business/dashboard" />} />
        <Route
          path="/business/dashboard"
          element={
            <BusinessLayout>
              <BusinessDashboard />
            </BusinessLayout>
          }
        />
        <Route
          path="/business/flight-schedules"
          element={
            <BusinessLayout>
              <FlightSchedules />
            </BusinessLayout>
          }
        />
        <Route
          path="/business/analytics"
          element={
            <BusinessLayout>
              <Analytics />
            </BusinessLayout>
          }
        />
        <Route
          path="/business/request"
          element={
            <BusinessLayout>
              <PendingRequests />
            </BusinessLayout>
          }
        />
      </Routes>
    </BrowserRouter>
  );
};

export default BusinessRoutes;
