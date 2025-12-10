import React from "react";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import Explore from "../components/user/Explore";
import History from "../components/user/History";
import MyTrips from "../components/user/MyTrips";
import Plan from "../components/user/Plan";
import Profile from "../components/user/menu/Profile";
import AccountSettings from "../components/user/menu/AccountSettings";
import UserLayout from "../layout/UserLayout";
import UserDashboard from "../components/user/UserDashboard";
import MyTripDetails from "../components/user/MyTripsDetails";
import Subscription from "../components/user/Subscription";

const UserRoutes = () => {
  return (
    <BrowserRouter>
      <Routes>
        <Route
          path="/dashboard"
          element={
            <UserLayout>
              <UserDashboard />
            </UserLayout>
          }
        />
        <Route
          path="/explore"
          element={
            <UserLayout>
              <Explore />
            </UserLayout>
          }
        />
        <Route
          path="/history"
          element={
            <UserLayout>
              <History />
            </UserLayout>
          }
        />
        <Route
          path="/my-trips"
          element={
            <UserLayout>
              <MyTrips />
            </UserLayout>
          }
        />
        <Route
          path="/my-trips/:id"
          element={
            <UserLayout>
              <MyTripDetails />
            </UserLayout>
          }
        />
        <Route
          path="/plan"
          element={
            <UserLayout>
              <Plan />
            </UserLayout>
          }
        />
        <Route
          path="/subscription"
          element={
            <UserLayout>
              <Subscription />
            </UserLayout>
          }
        />
        <Route
          path="/profile"
          element={
            <UserLayout>
              <Profile />
            </UserLayout>
          }
        />
        <Route
          path="/account-settings"
          element={
            <UserLayout>
              <AccountSettings />
            </UserLayout>
          }
        />
        <Route path="*" element={<Navigate to="/dashboard" />} />
      </Routes>
    </BrowserRouter>
  );
};

export default UserRoutes;
