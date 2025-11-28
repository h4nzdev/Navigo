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
if (!rate_limit_check($user_id, 5, 300)) { // 5 password changes per 5 minutes
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many password change attempts. Please try again later.']);
    exit;
}

// Validate input
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate required fields
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    echo json_encode(['success' => false, 'message' => 'All password fields are required']);
    exit;
}

// Validate password match
if ($new_password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'New passwords do not match']);
    exit;
}

// Validate password strength
if (strlen($new_password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
    exit;
}

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $new_password)) {
    echo json_encode(['success' => false, 'message' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number']);
    exit;
}

// Update password
$result = update_user_password($user_id, $current_password, $new_password);

echo json_encode($result);
?>
