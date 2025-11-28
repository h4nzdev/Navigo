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
if (!rate_limit_check($user_id, 10, 300)) { // 10 requests per 5 minutes
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many requests. Please try again later.']);
    exit;
}

// Validate input
$first_name = sanitize_input($_POST['first_name'] ?? '');
$last_name = sanitize_input($_POST['last_name'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$location = sanitize_input($_POST['location'] ?? '');
$bio = sanitize_input($_POST['bio'] ?? '');
$timezone = sanitize_input($_POST['timezone'] ?? 'UTC');
$date_format = sanitize_input($_POST['date_format'] ?? 'MM/DD/YYYY');
$currency = sanitize_input($_POST['currency'] ?? 'USD');

// Validate required fields
if (empty($first_name) || empty($last_name)) {
    echo json_encode(['success' => false, 'message' => 'First name and last name are required']);
    exit;
}

// Validate date format
$valid_date_formats = ['MM/DD/YYYY', 'DD/MM/YYYY', 'YYYY-MM-DD'];
if (!in_array($date_format, $valid_date_formats)) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format']);
    exit;
}

// Validate currency (basic check)
if (strlen($currency) !== 3) {
    echo json_encode(['success' => false, 'message' => 'Invalid currency code']);
    exit;
}

// Update profile
$success = update_user_profile($user_id, $first_name, $last_name, $phone, $location, $bio, $timezone, $date_format, $currency);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
}
?>
