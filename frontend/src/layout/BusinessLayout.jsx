import React, { useState, useContext } from "react";
import TopNavbar from "../components/business/menu/TopNavbar";
import { AuthContext } from "../context/AuthContext";
import CreateScheduleModal from "../components/business/menu/CreateScheduleModal";

const BusinessLayout = ({ children }) => {
  const { user } = useContext(AuthContext);
  const [businessData, setBusinessData] = useState(null);
  const [userData, setUserData] = useState(null);
  const [isCreateScheduleModalOpen, setIsCreateScheduleModalOpen] =
    useState(false);

  // Get user initials
  const getUserInitials = () => {
    if (userData?.first_name && userData?.last_name) {
      return `${userData.first_name.charAt(0)}${userData.last_name.charAt(0)}`;
    }
    return "B";
  };

  return (
    <>
      <TopNavbar
        businessData={businessData}
        userData={userData}
        getUserInitials={getUserInitials}
        onAddSchedule={() => setIsCreateScheduleModalOpen(true)}
      />
      <div>{children}</div>

      <CreateScheduleModal
        isOpen={isCreateScheduleModalOpen}
        onClose={() => setIsCreateScheduleModalOpen(false)}
        businessId={businessData?._id || user?.id}
      />
    </>
  );
};

export default BusinessLayout;
