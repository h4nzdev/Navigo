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
if (!rate_limit_check($user_id, 20, 300)) { // 20 requests per 5 minutes
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many requests. Please try again later.']);
    exit;
}

// Validate and sanitize input
$preferences = [
    'preferred_class' => sanitize_input($_POST['preferred_class'] ?? 'economy'),
    'seat_preference' => sanitize_input($_POST['seat_preference'] ?? 'no_preference'),
    'meal_preference' => sanitize_input($_POST['meal_preference'] ?? 'no_preference'),
    'star_rating' => sanitize_input($_POST['star_rating'] ?? 'any'),
    'room_type' => sanitize_input($_POST['room_type'] ?? 'double'),
    'budget_range' => sanitize_input($_POST['budget_range'] ?? 'mid_range'),
    'travel_type' => sanitize_input($_POST['travel_type'] ?? 'leisure'),
    'activity_level' => sanitize_input($_POST['activity_level'] ?? 'moderate')
];

// Validate enum values
$valid_classes = ['economy', 'premium_economy', 'business', 'first'];
$valid_seats = ['window', 'aisle', 'middle', 'no_preference'];
$valid_meals = ['vegetarian', 'vegan', 'halal', 'kosher', 'gluten_free', 'no_preference'];
$valid_ratings = ['1', '2', '3', '4', '5', 'any'];
$valid_rooms = ['single', 'double', 'twin', 'suite', 'family'];
$valid_budgets = ['budget', 'mid_range', 'luxury', 'any'];
$valid_types = ['leisure', 'business', 'adventure', 'cultural', 'family'];
$valid_levels = ['low', 'moderate', 'high', 'extreme'];

if (!in_array($preferences['preferred_class'], $valid_classes) ||
    !in_array($preferences['seat_preference'], $valid_seats) ||
    !in_array($preferences['meal_preference'], $valid_meals) ||
    !in_array($preferences['star_rating'], $valid_ratings) ||
    !in_array($preferences['room_type'], $valid_rooms) ||
    !in_array($preferences['budget_range'], $valid_budgets) ||
    !in_array($preferences['travel_type'], $valid_types) ||
    !in_array($preferences['activity_level'], $valid_levels)) {
    
    echo json_encode(['success' => false, 'message' => 'Invalid preference values']);
    exit;
}

// Update travel preferences
$success = update_user_travel_preferences($user_id, $preferences);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Travel preferences updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update travel preferences']);
}
?>
