import React from "react";

const BusinessBilling = () => {
  const plans = [
    {
      name: "Basic",
      price: "₱0",
      period: "/month",
      description: "Perfect for getting started",
      features: [
        "Basic search functionality",
        "Simple itineraries",
        "Limited travel guides",
        "Last 3 trips history",
        "1 calendar integration",
      ],
      current: true,
      buttonText: "Current Plan",
      buttonDisabled: true,
    },
    {
      name: "Pro",
      price: "₱250",
      period: "/month",
      description: "For frequent travelers",
      features: [
        "AI recommendations",
        "Booking & cloud storage",
        "Multi-calendar sync",
        "Offline access",
        "Priority support",
      ],
      current: false,
      buttonText: "Upgrade to Pro",
      buttonDisabled: false,
    },
    {
      name: "Premium",
      price: "₱499",
      period: "/month",
      description: "Complete travel solution",
      features: [
        "All Pro features included",
        "24/7 concierge service",
        "Group travel (up to 10)",
        "Integrated travel insurance",
        "Premium priority support",
      ],
      current: false,
      buttonText: "Upgrade to Premium",
      buttonDisabled: false,
    },
  ];

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 p-6">
      <div className="max-w-6xl mx-auto">
        {/* Header */}
        <div className="text-center mb-10">
          <div className="inline-block bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 px-4 py-2 rounded-full mb-4">
            <span className="font-medium">Manage Your Plan</span>
          </div>
          <h1 className="text-3xl font-bold mb-3">Subscription Plans</h1>
          <p className="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Choose the perfect plan for your travel needs. All plans include
            secure cloud storage and mobile sync.
          </p>
        </div>

        {/* Plans Grid */}
        <div className="flex flex-col md:flex-row gap-6 mb-10">
          {plans.map((plan, index) => (
            <div
              key={index}
              className="flex-1 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 relative"
            >
              {plan.current && (
                <div className="absolute top-0 right-0 bg-blue-500 text-white px-3 py-1 rounded-bl-lg rounded-tr-lg text-sm font-medium">
                  Current Plan
                </div>
              )}

              {/* Plan Header */}
              <div className="text-center mb-6">
                <h3 className="text-2xl font-bold mb-2">{plan.name}</h3>
                <p className="text-gray-600 dark:text-gray-400 mb-4">
                  {plan.description}
                </p>
                <div className="text-4xl font-bold text-blue-500">
                  {plan.price}
                  <span className="text-lg font-normal text-gray-600 dark:text-gray-400">
                    {plan.period}
                  </span>
                </div>
              </div>

              {/* Features List */}
              <ul className="space-y-3 mb-8">
                {plan.features.map((feature, idx) => (
                  <li key={idx} className="flex items-start">
                    <span className="text-blue-500 mr-3 mt-1">✓</span>
                    <span className="text-gray-700 dark:text-gray-300">
                      {feature}
                    </span>
                  </li>
                ))}
              </ul>

              {/* Action Button */}
              <button
                className={`w-full py-3 rounded-lg font-medium ${
                  plan.current
                    ? "bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-400 cursor-not-allowed"
                    : "bg-blue-500 text-white hover:bg-blue-600 transition-colors"
                }`}
                disabled={plan.current}
              >
                {plan.buttonText}
              </button>
            </div>
          ))}
        </div>

        {/* Footer Info */}
        <div className="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <h2 className="text-xl font-bold text-center mb-4">
            Need help choosing?
          </h2>
          <p className="text-center text-gray-600 dark:text-gray-400 mb-6 max-w-2xl mx-auto">
            Our team is here to help you find the perfect plan. All plans
            include a 30-day money-back guarantee.
          </p>

          <div className="flex flex-wrap justify-center gap-6 mb-6">
            <div className="text-center">
              <div className="text-2xl font-bold text-blue-500">99.9%</div>
              <div className="text-gray-600 dark:text-gray-400">Uptime</div>
            </div>
            <div className="text-center">
              <div className="text-2xl font-bold text-blue-500">24/7</div>
              <div className="text-gray-600 dark:text-gray-400">Support</div>
            </div>
            <div className="text-center">
              <div className="text-2xl font-bold text-blue-500">30</div>
              <div className="text-gray-600 dark:text-gray-400">Day trial</div>
            </div>
          </div>

          <div className="flex justify-center gap-4">
            <button className="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
              Contact Support
            </button>
            <button className="border border-gray-300 dark:border-gray-600 px-6 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
              Compare Features
            </button>
          </div>

          <p className="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
            No setup fees • Cancel anytime • 30-day money-back guarantee
          </p>
        </div>
      </div>
    </div>
  );
};

export default BusinessBilling;
