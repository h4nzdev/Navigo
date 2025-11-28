<?php
session_start();
require_once '../Config/Database.php';
require_once '../Config/functions.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $business_name = sanitize_input($_POST['business_name']);
    $business_type = sanitize_input($_POST['business_type']);
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input
    if (empty($business_name) || empty($business_type) || empty($first_name) || 
        empty($last_name) || empty($email) || empty($phone) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } elseif (!validate_email($email)) {
        $error_message = 'Please enter a valid email address.';
    } elseif (!validate_password($password)) {
        $error_message = 'Password must be at least 8 characters with uppercase, lowercase, and number.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        // Attempt to register the business
        $business_id = register_business($business_name, $business_type, $first_name, $last_name, $email, $password, $phone);
        
        if ($business_id) {
            // Registration successful, log the business in
            $_SESSION['business_id'] = $business_id;
            $_SESSION['user_type'] = 'business';
            $_SESSION['business_email'] = $email;
            $_SESSION['business_name'] = $business_name;
            
            // Redirect to business dashboard
            header('Location: Business/business_dashboard.php');
            exit();
        } else {
            $error_message = 'Registration failed. Email may already be in use.';
        }
    }
}

// If there's an error, redirect back to login with error message
if ($error_message) {
    header('Location: business_login.php?error=' . urlencode($error_message));
    exit();
}
?>
