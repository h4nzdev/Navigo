import React, { useState } from "react";
import { Link } from "react-router-dom";
import {
  ArrowLeft,
  Check,
  X,
  Crown,
  Shield,
  Zap,
  Globe,
  Users,
  Calendar,
  Bell,
  Star,
  Gift,
  HelpCircle,
  CreditCard,
  TrendingUp,
  Award,
  Lock,
  BadgeCheck,
} from "lucide-react";

const Subscriptions = () => {
  const [billingCycle, setBillingCycle] = useState("monthly"); // monthly or annual
  const [selectedPlan, setSelectedPlan] = useState("pro");

  const plans = [
    {
      id: "basic",
      name: "Basic",
      price: {
        monthly: "₱99",
        annual: "₱999.99",
      },
      description: "Essential features for casual travelers",
      color: "blue",
      features: [
        { text: "Up to 3 trips per month", included: true },
        { text: "Basic trip planning", included: true },
        { text: "Flight price alerts", included: true },
        { text: "Standard support", included: true },
        { text: "Basic analytics", included: true },
        { text: "AI travel recommendations", included: false },
        { text: "Multi-city trip planning", included: false },
        { text: "Priority customer support", included: false },
        { text: "Advanced analytics dashboard", included: false },
        { text: "Collaborative trip planning", included: false },
        { text: "Offline maps access", included: false },
        { text: "Travel insurance discount", included: false },
      ],
      icon: Globe,
      popular: false,
      saving: "$0",
    },
    {
      id: "pro",
      name: "Pro",
      price: {
        monthly: "₱299.99",
        annual: "₱1,099.99",
      },
      description: "Perfect for frequent travelers",
      color: "cyan",
      features: [
        { text: "Unlimited trips", included: true },
        { text: "Advanced trip planning", included: true },
        { text: "Smart price alerts", included: true },
        { text: "Priority support", included: true },
        { text: "Advanced analytics", included: true },
        { text: "AI travel recommendations", included: true },
        { text: "Multi-city trip planning", included: true },
        { text: "Priority customer support", included: true },
        { text: "Advanced analytics dashboard", included: true },
        { text: "Collaborative trip planning", included: false },
        { text: "Offline maps access", included: false },
        { text: "Travel insurance discount", included: false },
      ],
      icon: Crown,
      popular: true,
      saving: "$20",
    },
    {
      id: "premium",
      name: "Premium",
      price: {
        monthly: "₱329.99",
        annual: "₱3,299.99",
      },
      description: "Everything for business & luxury travelers",
      color: "purple",
      features: [
        { text: "Unlimited trips", included: true },
        { text: "Premium trip planning", included: true },
        { text: "Real-time price alerts", included: true },
        { text: "24/7 VIP support", included: true },
        { text: "Enterprise analytics", included: true },
        { text: "Advanced AI recommendations", included: true },
        { text: "Complex multi-city planning", included: true },
        { text: "Dedicated account manager", included: true },
        { text: "Custom analytics dashboard", included: true },
        { text: "Collaborative trip planning", included: true },
        { text: "Offline maps access", included: true },
        { text: "Travel insurance discount", included: true },
      ],
      icon: Award,
      popular: false,
      saving: "$60",
    },
  ];

  const benefits = [
    {
      icon: Shield,
      title: "Secure Payments",
      description: "Bank-level security for all transactions",
    },
    {
      icon: Zap,
      title: "Instant Access",
      description: "Get premium features immediately",
    },
    {
      icon: Gift,
      title: "Free Trial",
      description: "7-day free trial for all plans",
    },
    {
      icon: TrendingUp,
      title: "Cancel Anytime",
      description: "No long-term commitments",
    },
  ];

  const currentPlan = {
    name: "Free",
    expires: "Dec 31, 2024",
    features: ["Basic trip planning", "3 trips limit"],
  };

  const handleSubscribe = (planId) => {
    const plan = plans.find((p) => p.id === planId);
    console.log("Subscribing to plan:", plan);
    console.log("Billing cycle:", billingCycle);
    console.log("Selected plan ID:", planId);

    // In a real app, this would redirect to payment gateway
    alert(
      `Subscribing to ${plan.name} plan (${billingCycle})! Check console for details.`
    );
  };

  return (
    <div className="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen font-sans">
      <div className="max-w-7xl mx-auto p-6 lg:p-10">
        {/* Header */}
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
          <div>
            <Link
              to="/"
              className="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mb-4"
            >
              <ArrowLeft size={20} />
              Back to Dashboard
            </Link>
            <h1 className="text-4xl font-bold">Subscription Plans</h1>
            <p className="text-gray-500 dark:text-gray-400 mt-2">
              Choose the perfect plan for your travel needs
            </p>
          </div>

          {/* Current Plan */}
          <div className="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md mt-4 md:mt-0">
            <div className="flex items-center gap-3">
              <div className="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <Crown size={20} className="text-gray-600 dark:text-gray-400" />
              </div>
              <div>
                <div className="text-sm text-gray-500">Current Plan</div>
                <div className="font-bold">{currentPlan.name}</div>
                <div className="text-xs text-gray-500">
                  Expires: {currentPlan.expires}
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Billing Toggle */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8">
          <div className="flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
              <h2 className="text-xl font-bold mb-2">Choose Billing Cycle</h2>
              <p className="text-gray-500 dark:text-gray-400">
                Save up to 20% with annual billing
              </p>
            </div>
            <div className="flex items-center gap-4">
              <div className="bg-gray-100 dark:bg-gray-700 p-1 rounded-xl flex">
                <button
                  onClick={() => setBillingCycle("monthly")}
                  className={`px-6 py-2 rounded-lg font-semibold transition-colors ${
                    billingCycle === "monthly"
                      ? "bg-white dark:bg-gray-600 shadow"
                      : "hover:bg-gray-200 dark:hover:bg-gray-600"
                  }`}
                >
                  Monthly
                </button>
                <button
                  onClick={() => setBillingCycle("annual")}
                  className={`px-6 py-2 rounded-lg font-semibold transition-colors ${
                    billingCycle === "annual"
                      ? "bg-white dark:bg-gray-600 shadow"
                      : "hover:bg-gray-200 dark:hover:bg-gray-600"
                  }`}
                >
                  Annual
                  <span className="ml-2 px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs rounded-full">
                    Save 20%
                  </span>
                </button>
              </div>
            </div>
          </div>
        </div>

        {/* Plans Comparison */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
          {plans.map((plan) => {
            const Icon = plan.icon;
            const isSelected = selectedPlan === plan.id;

            return (
              <div
                key={plan.id}
                className={`relative rounded-2xl shadow-lg overflow-hidden transition-transform hover:-translate-y-2 ${
                  isSelected ? "ring-2 ring-cyan-500 dark:ring-cyan-400" : ""
                } ${
                  plan.popular
                    ? "border-t-4 border-cyan-500 dark:border-cyan-400"
                    : ""
                }`}
              >
                {plan.popular && (
                  <div className="absolute top-0 left-0 right-0 bg-cyan-500 dark:bg-cyan-600 text-white text-center py-2 text-sm font-bold">
                    MOST POPULAR
                  </div>
                )}

                <div
                  className={`pt-${
                    plan.popular ? "12" : "8"
                  } pb-8 px-6 bg-white dark:bg-gray-800`}
                >
                  {/* Plan Header */}
                  <div className="text-center mb-6">
                    <div
                      className={`inline-flex p-3 rounded-full mb-4 ${
                        plan.color === "blue"
                          ? "bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400"
                          : plan.color === "cyan"
                          ? "bg-cyan-100 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-400"
                          : "bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400"
                      }`}
                    >
                      <Icon size={28} />
                    </div>
                    <h3 className="text-2xl font-bold">{plan.name}</h3>
                    <p className="text-gray-500 dark:text-gray-400 mt-2">
                      {plan.description}
                    </p>
                  </div>

                  {/* Price */}
                  <div className="text-center mb-6">
                    <div className="flex items-center justify-center gap-2">
                      <span className="text-4xl font-bold">
                        {plan.price[billingCycle]}
                      </span>
                      <span className="text-gray-500">
                        / {billingCycle === "monthly" ? "month" : "year"}
                      </span>
                    </div>
                    {billingCycle === "annual" && plan.saving !== "$0" && (
                      <div className="text-green-600 dark:text-green-400 text-sm font-medium mt-2">
                        Save {plan.saving} annually
                      </div>
                    )}
                  </div>

                  {/* Select Button */}
                  <button
                    onClick={() => {
                      setSelectedPlan(plan.id);
                      handleSubscribe(plan.id);
                    }}
                    className={`w-full py-3 rounded-lg font-bold text-lg transition-colors mb-6 ${
                      isSelected
                        ? plan.popular
                          ? "bg-cyan-600 text-white hover:bg-cyan-700"
                          : plan.color === "blue"
                          ? "bg-blue-600 text-white hover:bg-blue-700"
                          : "bg-purple-600 text-white hover:bg-purple-700"
                        : "bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600"
                    }`}
                  >
                    {isSelected ? "Selected" : "Select Plan"}
                  </button>

                  {/* Features List */}
                  <div className="space-y-3">
                    {plan.features.map((feature, index) => (
                      <div key={index} className="flex items-center gap-3">
                        {feature.included ? (
                          <Check
                            className="text-green-500 flex-shrink-0"
                            size={18}
                          />
                        ) : (
                          <X
                            className="text-gray-400 flex-shrink-0"
                            size={18}
                          />
                        )}
                        <span
                          className={`text-sm ${
                            feature.included
                              ? ""
                              : "text-gray-500 dark:text-gray-400"
                          }`}
                        >
                          {feature.text}
                        </span>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            );
          })}
        </div>

        {/* Benefits */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mb-12">
          <h2 className="text-2xl font-bold text-center mb-8">
            Why Choose NaviGo Premium?
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {benefits.map((benefit, index) => {
              const Icon = benefit.icon;
              return (
                <div key={index} className="text-center p-4">
                  <div className="inline-flex p-3 bg-cyan-100 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-400 rounded-full mb-4">
                    <Icon size={24} />
                  </div>
                  <h3 className="font-bold text-lg mb-2">{benefit.title}</h3>
                  <p className="text-gray-600 dark:text-gray-400 text-sm">
                    {benefit.description}
                  </p>
                </div>
              );
            })}
          </div>
        </div>

        {/* FAQ */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
          <h2 className="text-2xl font-bold text-center mb-8">
            Frequently Asked Questions
          </h2>
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {[
              {
                q: "Can I switch plans anytime?",
                a: "Yes, you can upgrade or downgrade your plan at any time. The changes will take effect immediately.",
              },
              {
                q: "Is there a free trial?",
                a: "All paid plans come with a 7-day free trial. No credit card required for the trial period.",
              },
              {
                q: "What payment methods do you accept?",
                a: "We accept all major credit cards (Visa, MasterCard, American Express), PayPal, and Apple Pay.",
              },
              {
                q: "Can I cancel my subscription?",
                a: "Yes, you can cancel anytime. Your subscription will remain active until the end of your billing period.",
              },
              {
                q: "Do you offer discounts for teams?",
                a: "Yes, we offer special pricing for teams of 5 or more. Contact our sales team for custom pricing.",
              },
              {
                q: "Is my payment information secure?",
                a: "We use bank-level encryption and never store your payment information. All transactions are processed securely.",
              },
            ].map((faq, index) => (
              <div
                key={index}
                className="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg"
              >
                <div className="flex items-start gap-3">
                  <HelpCircle
                    className="text-cyan-600 dark:text-cyan-400 mt-1 flex-shrink-0"
                    size={20}
                  />
                  <div>
                    <h3 className="font-bold mb-2">{faq.q}</h3>
                    <p className="text-gray-600 dark:text-gray-400 text-sm">
                      {faq.a}
                    </p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Support Section */}
        <div className="text-center mt-12">
          <p className="text-gray-500 dark:text-gray-400 mb-6">
            Need help choosing a plan?
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <button className="px-6 py-3 bg-gray-200 dark:bg-gray-700 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 flex items-center justify-center gap-2">
              <Bell size={18} />
              Contact Support
            </button>
            <button className="px-6 py-3 bg-cyan-600 text-white rounded-lg font-semibold hover:bg-cyan-700 flex items-center justify-center gap-2">
              <CreditCard size={18} />
              Start Free Trial
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Subscriptions;
