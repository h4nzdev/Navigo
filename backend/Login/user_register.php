<?php
session_start();
require_once '../Config/Database.php';
require_once '../Config/functions.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = sanitize_input($_POST['firstname']);
    $last_name = sanitize_input($_POST['lastname']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    // Validate input
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } elseif (!validate_email($email)) {
        $error_message = 'Please enter a valid email address.';
    } elseif (!validate_password($password)) {
        $error_message = 'Password must be at least 8 characters with uppercase, lowercase, and number.';
    } else {
        // Attempt to register the user
        $user_id = register_user($first_name, $last_name, $email, $password);
        
        if ($user_id) {
            // Registration successful, log the user in
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_type'] = 'personal';
            $_SESSION['user_email'] = $email;
            
            // Redirect to user landing page
            header('Location: User/user_landingpage.php');
            exit();
        } else {
            $error_message = 'Registration failed. Email may already be in use.';
        }
    }
}

// If there's an error, redirect back to login with error message
if ($error_message) {
    header('Location: user_login.php?error=' . urlencode($error_message));
    exit();
}
?>
