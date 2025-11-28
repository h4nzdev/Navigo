<?php
session_start();
require_once '../../Config/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check rate limiting
$user_id = get_current_user_id();
if (!rate_limit_check($user_id, 20, 300)) { // 20 requests per 5 minutes
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many requests. Please try again later.']);
    exit;
}

// Get JSON input for new account settings
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    // Fallback to POST data for backward compatibility
    $data = $_POST;
}

// Validate and sanitize input
$settings = [
    'login_alerts' => isset($data['login_alerts']) ? (bool)$data['login_alerts'] : false,
    'password_expiry' => isset($data['password_expiry']) ? (bool)$data['password_expiry'] : false,
    'device_trust' => isset($data['device_trust']) ? (bool)$data['device_trust'] : false,
    'two_factor_enabled' => isset($data['two_factor_enabled']) ? (bool)$data['two_factor_enabled'] : false,
    'anonymous_usage' => isset($data['anonymous_usage']) ? (bool)$data['anonymous_usage'] : false,
    'analytics_performance' => isset($data['analytics_performance']) ? (bool)$data['analytics_performance'] : false,
    'marketing_communications' => isset($data['marketing_communications']) ? (bool)$data['marketing_communications'] : false,
    'profile_visibility' => sanitize_input($data['profile_visibility'] ?? 'private'),
    'security_alerts' => isset($data['security_alerts']) ? (bool)$data['security_alerts'] : false,
    'account_updates' => isset($data['account_updates']) ? (bool)$data['account_updates'] : false,
    'system_maintenance' => isset($data['system_maintenance']) ? (bool)$data['system_maintenance'] : false,
    'feature_updates' => isset($data['feature_updates']) ? (bool)$data['feature_updates'] : false,
    'session_timeout' => isset($data['session_timeout']) ? (int)$data['session_timeout'] : 30
];

// Validate profile visibility
$valid_visibility = ['private', 'friends', 'public'];
if (!in_array($settings['profile_visibility'], $valid_visibility)) {
    echo json_encode(['success' => false, 'message' => 'Invalid profile visibility setting']);
    exit;
}

// Validate session timeout
$valid_timeouts = [15, 30, 60, 120, 480];
if (!in_array($settings['session_timeout'], $valid_timeouts)) {
    echo json_encode(['success' => false, 'message' => 'Invalid session timeout setting']);
    exit;
}

// Update security settings
$success = update_security_settings($user_id, $settings);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Settings updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update settings']);
}
?>
