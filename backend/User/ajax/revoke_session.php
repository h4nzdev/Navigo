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
if (!rate_limit_check($user_id, 10, 300)) { // 10 requests per 5 minutes
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many requests. Please try again later.']);
    exit;
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['session_id'])) {
    echo json_encode(['success' => false, 'message' => 'Session ID is required']);
    exit;
}

$session_id = (int)$data['session_id'];

// Revoke the session
$success = revoke_user_session($user_id, $session_id);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Session revoked successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to revoke session']);
}
?>
