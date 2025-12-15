import React, { useState } from "react";

const BusinessProfile = () => {
  // Static business data based on the model
  const [businessData, setBusinessData] = useState({
    name: "John Smith",
    email: "john@example.com",
    business_name: "Travel Adventures Inc.",
    phone: "+1 (555) 123-4567",
    address: "123 Business Ave, Suite 100, New York, NY 10001",
    website: "www.traveladventures.com",
    business_type: "Travel Agency",
    description:
      "Providing exceptional travel experiences for over 10 years. Specializing in adventure tours, luxury getaways, and corporate travel solutions.",
    logo: "https://via.placeholder.com/150",
    subscription_plan: "Pro",
    subscription_status: "Active",
    created_at: "2023-01-15",
  });

  const [isEditing, setIsEditing] = useState(false);
  const [editedData, setEditedData] = useState({ ...businessData });

  const handleEdit = () => {
    if (isEditing) {
      // Save changes
      setBusinessData(editedData);
    }
    setIsEditing(!isEditing);
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setEditedData((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 p-4 md:p-6">
      <div className=" mx-auto">
        {/* Header */}
        <div className="mb-8">
          <div className="inline-block bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 px-4 py-2 rounded-full mb-4">
            <span className="font-medium">Business Profile</span>
          </div>
          <h1 className="text-3xl font-bold mb-2">Account Information</h1>
          <p className="text-gray-600 dark:text-gray-400">
            Manage your business details and subscription information
          </p>
        </div>

        <div className="flex flex-col lg:flex-row gap-6">
          {/* Left Column - Business Details */}
          <div className="lg:w-2/3">
            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
              <div className="flex justify-between items-center mb-6">
                <h2 className="text-xl font-bold">Business Details</h2>
                <button
                  onClick={handleEdit}
                  className={`px-4 py-2 rounded-lg font-medium ${
                    isEditing
                      ? "bg-green-500 text-white hover:bg-green-600"
                      : "bg-blue-500 text-white hover:bg-blue-600"
                  } transition-colors`}
                >
                  {isEditing ? "Save Changes" : "Edit Profile"}
                </button>
              </div>

              <div className="grid md:grid-cols-2 gap-6">
                <div className="space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                      Contact Name
                    </label>
                    {isEditing ? (
                      <input
                        type="text"
                        name="name"
                        value={editedData.name}
                        onChange={handleChange}
                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700"
                      />
                    ) : (
                      <div className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                        {businessData.name}
                      </div>
                    )}
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                      Business Email
                    </label>
                    {isEditing ? (
                      <input
                        type="email"
                        name="email"
                        value={editedData.email}
                        onChange={handleChange}
                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700"
                      />
                    ) : (
                      <div className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                        {businessData.email}
                      </div>
                    )}
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                      Phone Number
                    </label>
                    {isEditing ? (
                      <input
                        type="tel"
                        name="phone"
                        value={editedData.phone}
                        onChange={handleChange}
                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700"
                      />
                    ) : (
                      <div className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                        {businessData.phone}
                      </div>
                    )}
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                      Business Type
                    </label>
                    {isEditing ? (
                      <input
                        type="text"
                        name="business_type"
                        value={editedData.business_type}
                        onChange={handleChange}
                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700"
                      />
                    ) : (
                      <div className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                        {businessData.business_type}
                      </div>
                    )}
                  </div>
                </div>

                <div className="space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                      Business Name
                    </label>
                    {isEditing ? (
                      <input
                        type="text"
                        name="business_name"
                        value={editedData.business_name}
                        onChange={handleChange}
                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700"
                      />
                    ) : (
                      <div className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                        {businessData.business_name}
                      </div>
                    )}
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                      Website
                    </label>
                    {isEditing ? (
                      <input
                        type="url"
                        name="website"
                        value={editedData.website}
                        onChange={handleChange}
                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700"
                      />
                    ) : (
                      <div className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                        {businessData.website}
                      </div>
                    )}
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                      Address
                    </label>
                    {isEditing ? (
                      <textarea
                        name="address"
                        value={editedData.address}
                        onChange={handleChange}
                        rows="2"
                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700"
                      />
                    ) : (
                      <div className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                        {businessData.address}
                      </div>
                    )}
                  </div>
                </div>
              </div>

              {/* Description Field - Full Width */}
              <div className="mt-6">
                <label className="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                  Business Description
                </label>
                {isEditing ? (
                  <textarea
                    name="description"
                    value={editedData.description}
                    onChange={handleChange}
                    rows="3"
                    className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700"
                  />
                ) : (
                  <div className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                    {businessData.description}
                  </div>
                )}
              </div>
            </div>

            {/* Account Information Card */}
            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
              <h2 className="text-xl font-bold mb-4">Account Information</h2>
              <div className="grid md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Account Created
                  </label>
                  <div className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                    {businessData.created_at}
                  </div>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                    User Role
                  </label>
                  <div className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                    Business
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Right Column - Subscription & Logo */}
          <div className="lg:w-1/3">
            {/* Subscription Card */}
            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
              <h2 className="text-xl font-bold mb-4">Subscription Details</h2>
              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Current Plan
                  </label>
                  <div className="flex items-center justify-between px-4 py-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <span className="font-medium text-blue-600 dark:text-blue-300">
                      {businessData.subscription_plan}
                    </span>
                    <span
                      className={`px-2 py-1 text-xs rounded-full ${
                        businessData.subscription_status === "Active"
                          ? "bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-300"
                          : "bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300"
                      }`}
                    >
                      {businessData.subscription_status}
                    </span>
                  </div>
                </div>

                <div>
                  <p className="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    Your current subscription includes all{" "}
                    {businessData.subscription_plan} plan features.
                  </p>
                  <button className="w-full bg-blue-500 text-white py-3 rounded-lg font-medium hover:bg-blue-600 transition-colors">
                    Manage Subscription
                  </button>
                </div>
              </div>
            </div>

            {/* Logo Card */}
            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
              <h2 className="text-xl font-bold mb-4">Business Logo</h2>
              <div className="flex flex-col items-center">
                <div className="w-32 h-32 mb-4 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                  <img
                    src={businessData.logo}
                    alt="Business Logo"
                    className="w-full h-full object-cover"
                  />
                </div>

                {isEditing ? (
                  <div className="space-y-2 w-full">
                    <input
                      type="text"
                      name="logo"
                      value={editedData.logo}
                      onChange={handleChange}
                      placeholder="Enter image URL"
                      className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-sm"
                    />
                    <p className="text-xs text-gray-500 dark:text-gray-400">
                      Enter a valid image URL for your logo
                    </p>
                  </div>
                ) : (
                  <p className="text-sm text-gray-600 dark:text-gray-400 text-center">
                    Current business logo
                  </p>
                )}

                <button className="mt-4 w-full border border-gray-300 dark:border-gray-600 py-2 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                  Upload New Logo
                </button>
              </div>
            </div>

            {/* Danger Zone */}
            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mt-6 border border-red-200 dark:border-red-800">
              <h2 className="text-xl font-bold mb-4 text-red-600 dark:text-red-400">
                Danger Zone
              </h2>
              <div className="space-y-3">
                <p className="text-sm text-gray-600 dark:text-gray-400">
                  Once you delete your account, there is no going back. Please
                  be certain.
                </p>
                <button className="w-full bg-red-500 text-white py-3 rounded-lg font-medium hover:bg-red-600 transition-colors">
                  Delete Account
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default BusinessProfile;
