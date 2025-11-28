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
if (!rate_limit_check($user_id, 5, 300)) { // 5 uploads per 5 minutes
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many uploads. Please try again later.']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['profile_photo']) || $_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
    $error_messages = [
        UPLOAD_ERR_INI_SIZE => 'File too large (server limit)',
        UPLOAD_ERR_FORM_SIZE => 'File too large (form limit)',
        UPLOAD_ERR_PARTIAL => 'File upload was interrupted',
        UPLOAD_ERR_NO_FILE => 'No file selected',
        UPLOAD_ERR_NO_TMP_DIR => 'Server configuration error',
        UPLOAD_ERR_CANT_WRITE => 'Server write error',
        UPLOAD_ERR_EXTENSION => 'File upload blocked by extension'
    ];
    
    $error_code = $_FILES['profile_photo']['error'];
    $message = isset($error_messages[$error_code]) ? $error_messages[$error_code] : 'Upload error';
    
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Validate file type and size
$file = $_FILES['profile_photo'];
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$max_size = 5 * 1024 * 1024; // 5MB

if (!in_array($file['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.']);
    exit;
}

if ($file['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 5MB.']);
    exit;
}

// Upload profile photo
$result = upload_profile_photo($user_id, $file);

if ($result['success']) {
    echo json_encode([
        'success' => true, 
        'message' => 'Photo uploaded successfully',
        'filename' => $result['filename']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => $result['message']]);
}
?>
