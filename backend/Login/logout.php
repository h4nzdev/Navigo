<?php
session_start();

// Debug: Log logout attempt
error_log("Logout attempt from: " . ($_SERVER['HTTP_REFERER'] ?? 'unknown'));

// Destroy all session data
session_destroy();

// Clear any cookies if needed
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to home page
header('Location: ../Index.php');`
exit();
?>
