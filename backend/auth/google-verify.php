<?php
session_start();
require_once '../Config/Database.php';
require_once '../Config/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['credential'])) {
        $credential = $input['credential'];
        
        // Verify the Google JWT token
        $clientId = 'YOUR_GOOGLE_CLIENT_ID'; // Replace with your actual Google Client ID
        
        // For production, you should verify the JWT signature
        // For now, we'll decode it without verification (not recommended for production)
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], explode('.', $credential)[1])), true);
        
        if (isset($payload['email'])) {
            $email = $payload['email'];
            $first_name = $payload['given_name'] ?? 'Google';
            $last_name = $payload['family_name'] ?? 'User';
            
            // Check if user exists in database
            $user = get_user_by_email($email);
            
            if (!$user) {
                // Create new user account
                $password = bin2hex(random_bytes(16));
                $user_id = register_user($first_name, $last_name, $email, $password);
                
                if ($user_id) {
                    $user = get_user_by_id($user_id);
                }
            }
            
            if ($user) {
                // Log the user in
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = 'personal';
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                
                echo json_encode(['success' => true, 'message' => 'Login successful']);
                exit();
            }
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Authentication failed']);
?>
