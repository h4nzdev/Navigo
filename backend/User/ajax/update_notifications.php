<?php
session_start();
require_once '../../Config/functions.php';

// Check if user is logged in
if (!is_logged_in() || get_user_type() !== 'personal') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check CSRF token
if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

// Check rate limiting
$user_id = get_current_user_id();
if (!rate_limit_check($user_id, 30, 300)) { // 30 requests per 5 minutes
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many requests. Please try again later.']);
    exit;
}

// Validate and sanitize input
$settings = [
    'booking_confirmations' => isset($_POST['booking_confirmations']) ? (bool)$_POST['booking_confirmations'] : false,
    'price_alerts' => isset($_POST['price_alerts']) ? (bool)$_POST['price_alerts'] : false,
    'travel_reminders' => isset($_POST['travel_reminders']) ? (bool)$_POST['travel_reminders'] : false,
    'weekly_digest' => isset($_POST['weekly_digest']) ? (bool)$_POST['weekly_digest'] : false,
    'push_notifications' => isset($_POST['push_notifications']) ? (bool)$_POST['push_notifications'] : false,
    'sms_alerts' => isset($_POST['sms_alerts']) ? (bool)$_POST['sms_alerts'] : false,
    'marketing_emails' => isset($_POST['marketing_emails']) ? (bool)$_POST['marketing_emails'] : false
];

// Update notification settings
$success = update_user_notification_settings($user_id, $settings);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Notification settings updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update notification settings']);
}
?>
