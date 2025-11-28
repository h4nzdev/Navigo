<?php
require_once 'Database.php';

// Database connection instance
$database = new Database();
$pdo = $database->getConnection();

// =============================================
// USER AUTHENTICATION FUNCTIONS
// =============================================

function authenticate_user($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, password FROM users WHERE email = ? AND is_active = TRUE");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        // Update last login
        $update_stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $update_stmt->execute([$user['id']]);
        
        return $user;
    }
    
    return false;
}

function authenticate_business($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id, business_name, email, password FROM businesses WHERE email = ? AND is_active = TRUE");
    $stmt->execute([$email]);
    $business = $stmt->fetch();
    
    if ($business && password_verify($password, $business['password'])) {
        // Update last login
        $update_stmt = $pdo->prepare("UPDATE businesses SET last_login = NOW() WHERE id = ?");
        $update_stmt->execute([$business['id']]);
        
        return $business;
    }
    
    return false;
}

function register_user($first_name, $last_name, $email, $password) {
    global $pdo;
    
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $verification_token = bin2hex(random_bytes(32));
        
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, verification_token) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$first_name, $last_name, $email, $hashed_password, $verification_token]);
        
        if ($result) {
            return $pdo->lastInsertId();
        }
    } catch(PDOException $e) {
        // Handle duplicate email error
        if ($e->getCode() == 23000) {
            return false; // Email already exists
        }
        throw $e;
    }
    
    return false;
}

function register_business($business_name, $business_type, $contact_first_name, $contact_last_name, $email, $password, $phone) {
    global $pdo;
    
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $verification_token = bin2hex(random_bytes(32));
        
        $stmt = $pdo->prepare("INSERT INTO businesses (business_name, business_type, contact_first_name, contact_last_name, email, password, phone, verification_token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$business_name, $business_type, $contact_first_name, $contact_last_name, $email, $hashed_password, $phone, $verification_token]);
        
        if ($result) {
            return $pdo->lastInsertId();
        }
    } catch(PDOException $e) {
        // Handle duplicate email error
        if ($e->getCode() == 23000) {
            return false; // Email already exists
        }
        throw $e;
    }
    
    return false;
}

// =============================================
// SESSION MANAGEMENT FUNCTIONS
// =============================================

function is_logged_in() {
    return isset($_SESSION['user_id']) || isset($_SESSION['business_id']);
}

function get_user_type() {
    if (isset($_SESSION['user_type'])) {
        return $_SESSION['user_type'];
    }
    return null;
}

function get_current_user_id() {
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    } elseif (isset($_SESSION['business_id'])) {
        return $_SESSION['business_id'];
    }
    return null;
}

function get_user_by_id($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND is_active = TRUE");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function get_business_by_id($business_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM businesses WHERE id = ? AND is_active = TRUE");
    $stmt->execute([$business_id]);
    return $stmt->fetch();
}

// =============================================
// UTILITY FUNCTIONS
// =============================================

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generate_booking_reference() {
    return 'NAV' . strtoupper(uniqid());
}

function generate_join_code() {
    return strtoupper(substr(uniqid(), -8));
}

// =============================================
// ITINERARY FUNCTIONS
// =============================================

function create_itinerary($user_id, $title, $description, $destination, $start_date, $end_date, $budget = null) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO itineraries (user_id, title, description, destination, start_date, end_date, budget) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([$user_id, $title, $description, $destination, $start_date, $end_date, $budget]);
    
    if ($result) {
        return $pdo->lastInsertId();
    }
    
    return false;
}

function get_user_itineraries($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM itineraries WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function get_itinerary_by_id($itinerary_id, $user_id = null) {
    global $pdo;
    
    if ($user_id) {
        $stmt = $pdo->prepare("SELECT * FROM itineraries WHERE id = ? AND user_id = ?");
        $stmt->execute([$itinerary_id, $user_id]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM itineraries WHERE id = ?");
        $stmt->execute([$itinerary_id]);
    }
    
    return $stmt->fetch();
}

// =============================================
// BOOKING FUNCTIONS
// =============================================

function create_booking($user_id, $business_id, $service_id, $itinerary_id, $service_name, $booking_date, $check_in_date, $check_out_date, $quantity, $total_amount, $special_requests = null) {
    global $pdo;
    
    $booking_reference = generate_booking_reference();
    
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, business_id, service_id, itinerary_id, booking_reference, service_name, booking_date, check_in_date, check_out_date, quantity, total_amount, special_requests) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([$user_id, $business_id, $service_id, $itinerary_id, $booking_reference, $service_name, $booking_date, $check_in_date, $check_out_date, $quantity, $total_amount, $special_requests]);
    
    if ($result) {
        return $booking_reference;
    }
    
    return false;
}

function get_user_bookings($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT b.*, bs.service_name, bs.service_type, bus.business_name 
        FROM bookings b 
        LEFT JOIN business_services bs ON b.service_id = bs.id 
        LEFT JOIN businesses bus ON b.business_id = bus.id 
        WHERE b.user_id = ? 
        ORDER BY b.created_at DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// =============================================
// NOTIFICATION FUNCTIONS
// =============================================

function create_notification($user_id, $business_id, $type, $title, $message, $action_url = null) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, business_id, type, title, message, action_url) VALUES (?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([$user_id, $business_id, $type, $title, $message, $action_url]);
    
    return $result;
}

function get_user_notifications($user_id, $limit = 10) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$user_id, $limit]);
    return $stmt->fetchAll();
}

function mark_notification_read($notification_id, $user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE, read_at = NOW() WHERE id = ? AND user_id = ?");
    return $stmt->execute([$notification_id, $user_id]);
}

// =============================================
// ERROR HANDLING
// =============================================

function handle_database_error($e) {
    error_log("Database Error: " . $e->getMessage());
    
    // Don't expose database errors to users in production
    if (defined('DEBUG') && DEBUG) {
        return "Database Error: " . $e->getMessage();
    } else {
        return "An error occurred. Please try again later.";
    }
}

// =============================================
// VALIDATION FUNCTIONS
// =============================================

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_password($password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{8,}$/', $password);
}

function validate_phone($phone) {
    // Basic phone validation - adjust regex as needed
    return preg_match('/^[\+]?[1-9][\d]{0,15}$/', $phone);
}

// =============================================
// SECURITY FUNCTIONS
// =============================================

function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function rate_limit_check($identifier, $max_attempts = 5, $time_window = 300) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as attempts 
        FROM sessions 
        WHERE user_id = ? AND last_activity > DATE_SUB(NOW(), INTERVAL ? SECOND)
    ");
    $stmt->execute([$identifier, $time_window]);
    $result = $stmt->fetch();
    
    return $result['attempts'] < $max_attempts;
}

// =============================================
// PROFILE MANAGEMENT FUNCTIONS
// =============================================

function get_user_complete_profile($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT 
            u.id,
            u.first_name,
            u.last_name,
            u.email,
            u.date_of_birth,
            u.is_active,
            u.email_verified,
            u.last_login,
            ups.phone,
            ups.location,
            ups.timezone,
            ups.bio,
            ups.date_format,
            ups.currency,
            ups.profile_photo,
            uts.total_trips,
            uts.countries_visited,
            uts.total_spent,
            uts.total_miles,
            uts.favorite_destination,
            uts.next_trip_destination,
            uts.last_trip_date,
            sp.plan_name,
            sp.plan_type,
            us.status as subscription_status,
            us.end_date as subscription_end_date
        FROM users u
        LEFT JOIN user_profile_settings ups ON u.id = ups.user_id
        LEFT JOIN user_travel_stats uts ON u.id = uts.user_id
        LEFT JOIN user_subscriptions us ON u.id = us.user_id AND us.status = 'active'
        LEFT JOIN subscription_plans sp ON us.plan_id = sp.id
        WHERE u.id = ? AND u.is_active = TRUE
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function update_user_profile($user_id, $first_name, $last_name, $phone, $location, $bio, $timezone, $date_format, $currency) {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Update users table
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$first_name, $last_name, $user_id]);
        
        // Update or insert profile settings
        $stmt = $pdo->prepare("
            INSERT INTO user_profile_settings (user_id, phone, location, bio, timezone, date_format, currency)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                phone = VALUES(phone),
                location = VALUES(location),
                bio = VALUES(bio),
                timezone = VALUES(timezone),
                date_format = VALUES(date_format),
                currency = VALUES(currency),
                updated_at = NOW()
        ");
        $stmt->execute([$user_id, $phone, $location, $bio, $timezone, $date_format, $currency]);
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function upload_profile_photo($user_id, $file) {
    global $pdo;
    
    // Validate file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.'];
    }
    
    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'File too large. Maximum size is 5MB.'];
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . $user_id . '_' . time() . '.' . $extension;
    $upload_path = '../Assets/Images/profiles/' . $filename;
    
    // Create directory if it doesn't exist
    $dir = dirname($upload_path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Process and resize image
    $processed = process_profile_image($file['tmp_name'], $upload_path);
    if (!$processed) {
        return ['success' => false, 'message' => 'Failed to process image.'];
    }
    
    // Get old photo filename to delete it
    $stmt = $pdo->prepare("SELECT profile_photo FROM user_profile_settings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $old_photo = $stmt->fetchColumn();
    
    // Update database
    $stmt = $pdo->prepare("
        INSERT INTO user_profile_settings (user_id, profile_photo)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE
            profile_photo = VALUES(profile_photo),
            updated_at = NOW()
    ");
    $stmt->execute([$user_id, $filename]);
    
    // Delete old photo if it exists
    if ($old_photo && file_exists('../Assets/Images/profiles/' . $old_photo)) {
        unlink('../Assets/Images/profiles/' . $old_photo);
    }
    
    return ['success' => true, 'filename' => $filename];
}

function process_profile_image($source_path, $destination_path) {
    // Get image info
    $image_info = getimagesize($source_path);
    if (!$image_info) {
        return false;
    }
    
    $width = $image_info[0];
    $height = $image_info[1];
    $mime_type = $image_info['mime'];
    
    // Create image resource based on type
    switch ($mime_type) {
        case 'image/jpeg':
            $source_image = imagecreatefromjpeg($source_path);
            break;
        case 'image/png':
            $source_image = imagecreatefrompng($source_path);
            break;
        case 'image/gif':
            $source_image = imagecreatefromgif($source_path);
            break;
        case 'image/webp':
            $source_image = imagecreatefromwebp($source_path);
            break;
        default:
            return false;
    }
    
    if (!$source_image) {
        return false;
    }
    
    // Calculate new dimensions (square, max 300x300)
    $max_size = 300;
    if ($width > $height) {
        $new_width = $max_size;
        $new_height = ($height * $max_size) / $width;
    } else {
        $new_height = $max_size;
        $new_width = ($width * $max_size) / $height;
    }
    
    // Create new image
    $new_image = imagecreatetruecolor($new_width, $new_height);
    
    // Preserve transparency for PNG and GIF
    if ($mime_type == 'image/png' || $mime_type == 'image/gif') {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
        imagefill($new_image, 0, 0, $transparent);
    }
    
    // Resize image
    imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    // Save image
    $result = false;
    switch ($mime_type) {
        case 'image/jpeg':
            $result = imagejpeg($new_image, $destination_path, 90);
            break;
        case 'image/png':
            $result = imagepng($new_image, $destination_path, 9);
            break;
        case 'image/gif':
            $result = imagegif($new_image, $destination_path);
            break;
        case 'image/webp':
            $result = imagewebp($new_image, $destination_path, 90);
            break;
    }
    
    // Clean up
    imagedestroy($source_image);
    imagedestroy($new_image);
    
    return $result;
}

// =============================================
// TRAVEL PREFERENCES FUNCTIONS
// =============================================

function get_user_travel_preferences($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM user_travel_preferences WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function update_user_travel_preferences($user_id, $preferences) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO user_travel_preferences (
                user_id, preferred_class, seat_preference, meal_preference, 
                star_rating, room_type, budget_range, travel_type, activity_level
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                preferred_class = VALUES(preferred_class),
                seat_preference = VALUES(seat_preference),
                meal_preference = VALUES(meal_preference),
                star_rating = VALUES(star_rating),
                room_type = VALUES(room_type),
                budget_range = VALUES(budget_range),
                travel_type = VALUES(travel_type),
                activity_level = VALUES(activity_level),
                updated_at = NOW()
        ");
        
        $stmt->execute([
            $user_id,
            $preferences['preferred_class'],
            $preferences['seat_preference'],
            $preferences['meal_preference'],
            $preferences['star_rating'],
            $preferences['room_type'],
            $preferences['budget_range'],
            $preferences['travel_type'],
            $preferences['activity_level']
        ]);
        
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// =============================================
// NOTIFICATION SETTINGS FUNCTIONS
// =============================================

function get_user_notification_settings($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM user_notification_settings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function update_user_notification_settings($user_id, $settings) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO user_notification_settings (
                user_id, booking_confirmations, price_alerts, travel_reminders,
                weekly_digest, push_notifications, sms_alerts, marketing_emails
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                booking_confirmations = VALUES(booking_confirmations),
                price_alerts = VALUES(price_alerts),
                travel_reminders = VALUES(travel_reminders),
                weekly_digest = VALUES(weekly_digest),
                push_notifications = VALUES(push_notifications),
                sms_alerts = VALUES(sms_alerts),
                marketing_emails = VALUES(marketing_emails),
                updated_at = NOW()
        ");
        
        $stmt->execute([
            $user_id,
            $settings['booking_confirmations'] ? 1 : 0,
            $settings['price_alerts'] ? 1 : 0,
            $settings['travel_reminders'] ? 1 : 0,
            $settings['weekly_digest'] ? 1 : 0,
            $settings['push_notifications'] ? 1 : 0,
            $settings['sms_alerts'] ? 1 : 0,
            $settings['marketing_emails'] ? 1 : 0
        ]);
        
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// =============================================
// SECURITY SETTINGS FUNCTIONS
// =============================================

function get_user_security_settings($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM user_security_settings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function update_user_password($user_id, $current_password, $new_password) {
    global $pdo;
    
    try {
        // Get current password
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($current_password, $user['password'])) {
            return ['success' => false, 'message' => 'Current password is incorrect.'];
        }
        
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$hashed_password, $user_id]);
        
        // Update security settings
        $stmt = $pdo->prepare("
            INSERT INTO user_security_settings (user_id, last_password_change)
            VALUES (?, NOW())
            ON DUPLICATE KEY UPDATE
                last_password_change = NOW(),
                updated_at = NOW()
        ");
        $stmt->execute([$user_id]);
        
        return ['success' => true, 'message' => 'Password updated successfully.'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to update password.'];
    }
}

function enable_2fa($user_id) {
    global $pdo;
    
    // Generate secret key for TOTP
    $secret = base32_encode(random_bytes(20));
    $backup_codes = generate_backup_codes();
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO user_security_settings (user_id, two_factor_enabled, two_factor_secret, two_factor_backup_codes)
            VALUES (?, 1, ?, ?)
            ON DUPLICATE KEY UPDATE
                two_factor_enabled = 1,
                two_factor_secret = VALUES(two_factor_secret),
                two_factor_backup_codes = VALUES(two_factor_backup_codes),
                updated_at = NOW()
        ");
        
        $stmt->execute([$user_id, $secret, json_encode($backup_codes)]);
        
        return ['success' => true, 'secret' => $secret, 'backup_codes' => $backup_codes];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to enable 2FA.'];
    }
}

function disable_2fa($user_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            UPDATE user_security_settings 
            SET two_factor_enabled = 0, two_factor_secret = NULL, two_factor_backup_codes = NULL, updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);
        
        return ['success' => true, 'message' => '2FA disabled successfully.'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to disable 2FA.'];
    }
}

function update_security_settings($user_id, $settings) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO user_security_settings (user_id, login_alerts, data_sharing, profile_visibility)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                login_alerts = VALUES(login_alerts),
                data_sharing = VALUES(data_sharing),
                profile_visibility = VALUES(profile_visibility),
                updated_at = NOW()
        ");
        
        $stmt->execute([
            $user_id,
            $settings['login_alerts'] ? 1 : 0,
            $settings['data_sharing'] ? 1 : 0,
            $settings['profile_visibility']
        ]);
        
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// =============================================
// SUBSCRIPTION AND BILLING FUNCTIONS
// =============================================

function get_user_subscription($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT us.*, sp.plan_name, sp.plan_type, sp.price, sp.currency, sp.billing_cycle
        FROM user_subscriptions us
        JOIN subscription_plans sp ON us.plan_id = sp.id
        WHERE us.user_id = ? AND us.status = 'active'
        ORDER BY us.created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function get_user_billing_history($user_id, $limit = 10) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT * FROM billing_history 
        WHERE user_id = ? 
        ORDER BY billing_date DESC 
        LIMIT ?
    ");
    $stmt->execute([$user_id, $limit]);
    return $stmt->fetchAll();
}

function get_user_payment_methods($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT * FROM user_payment_methods 
        WHERE user_id = ? AND is_active = 1 
        ORDER BY is_primary DESC, created_at DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function add_payment_method($user_id, $payment_data) {
    global $pdo;
    
    try {
        // If this is the first payment method, make it primary
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_payment_methods WHERE user_id = ? AND is_active = 1");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();
        $is_primary = $result['count'] == 0;
        
        $stmt = $pdo->prepare("
            INSERT INTO user_payment_methods (
                user_id, payment_type, card_brand, last_four_digits, 
                expiry_month, expiry_year, cardholder_name, is_primary, payment_token
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $user_id,
            $payment_data['payment_type'],
            $payment_data['card_brand'],
            $payment_data['last_four_digits'],
            $payment_data['expiry_month'],
            $payment_data['expiry_year'],
            $payment_data['cardholder_name'],
            $is_primary ? 1 : 0,
            $payment_data['payment_token']
        ]);
        
        return ['success' => true, 'message' => 'Payment method added successfully.'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to add payment method.'];
    }
}

function remove_payment_method($user_id, $payment_method_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            UPDATE user_payment_methods 
            SET is_active = 0, updated_at = NOW()
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$payment_method_id, $user_id]);
        
        return ['success' => true, 'message' => 'Payment method removed successfully.'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to remove payment method.'];
    }
}

// =============================================
// ACHIEVEMENTS AND STATS FUNCTIONS
// =============================================

function get_user_achievements($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT * FROM user_achievements 
        WHERE user_id = ? 
        ORDER BY is_unlocked DESC, unlocked_at DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function get_user_travel_stats($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM user_travel_stats WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

// =============================================
// UTILITY FUNCTIONS
// =============================================

function base32_encode($data) {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $output = '';
    $v = 0;
    $vbits = 0;
    
    for ($i = 0, $j = strlen($data); $i < $j; $i++) {
        $v <<= 8;
        $v += ord($data[$i]);
        $vbits += 8;
        
        while ($vbits >= 5) {
            $vbits -= 5;
            $output .= $alphabet[$v >> $vbits];
            $v &= ((1 << $vbits) - 1);
        }
    }
    
    if ($vbits > 0) {
        $v <<= (5 - $vbits);
        $output .= $alphabet[$v];
    }
    
    return $output;
}

function generate_backup_codes($count = 8) {
    $codes = [];
    for ($i = 0; $i < $count; $i++) {
        $codes[] = strtoupper(substr(md5(uniqid()), 0, 8));
    }
    return $codes;
}

function get_active_sessions($user_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                id,
                ip_address,
                user_agent,
                last_activity,
                created_at
            FROM sessions 
            WHERE user_id = ? AND user_type = 'personal'
            ORDER BY last_activity DESC
        ");
        $stmt->execute([$user_id]);
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Process sessions to add device information
        $processed_sessions = [];
        foreach ($sessions as $session) {
            $user_agent = $session['user_agent'];
            $device_type = 'desktop';
            $device_name = 'Unknown Device';
            
            // Simple device detection
            if (preg_match('/Mobile|Android|iPhone|iPad/', $user_agent)) {
                $device_type = 'mobile';
                $device_name = 'Mobile Device';
            } elseif (preg_match('/Tablet|iPad/', $user_agent)) {
                $device_type = 'tablet';
                $device_name = 'Tablet';
            } else {
                $device_type = 'desktop';
                $device_name = 'Desktop Computer';
            }
            
            // Extract browser info
            if (preg_match('/Chrome\/([0-9.]+)/', $user_agent, $matches)) {
                $device_name = 'Chrome Browser';
            } elseif (preg_match('/Firefox\/([0-9.]+)/', $user_agent, $matches)) {
                $device_name = 'Firefox Browser';
            } elseif (preg_match('/Safari\/([0-9.]+)/', $user_agent, $matches)) {
                $device_name = 'Safari Browser';
            } elseif (preg_match('/Edge\/([0-9.]+)/', $user_agent, $matches)) {
                $device_name = 'Edge Browser';
            }
            
            $processed_sessions[] = [
                'id' => $session['id'],
                'ip_address' => $session['ip_address'],
                'device_type' => $device_type,
                'device_name' => $device_name,
                'last_activity' => $session['last_activity'],
                'created_at' => $session['created_at']
            ];
        }
        
        return $processed_sessions;
    } catch (PDOException $e) {
        handle_database_error($e);
        return [];
    }
}

function get_login_history($user_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                id,
                ip_address,
                user_agent,
                last_activity,
                created_at
            FROM sessions 
            WHERE user_id = ? AND user_type = 'personal'
            ORDER BY created_at DESC
            LIMIT 20
        ");
        $stmt->execute([$user_id]);
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Process sessions to add device information
        $processed_history = [];
        foreach ($sessions as $session) {
            $user_agent = $session['user_agent'];
            $device_type = 'desktop';
            $device_name = 'Unknown Device';
            
            // Simple device detection
            if (preg_match('/Mobile|Android|iPhone|iPad/', $user_agent)) {
                $device_type = 'mobile';
                $device_name = 'Mobile Device';
            } elseif (preg_match('/Tablet|iPad/', $user_agent)) {
                $device_type = 'tablet';
                $device_name = 'Tablet';
            } else {
                $device_type = 'desktop';
                $device_name = 'Desktop Computer';
            }
            
            // Extract browser info
            if (preg_match('/Chrome\/([0-9.]+)/', $user_agent, $matches)) {
                $device_name = 'Chrome Browser';
            } elseif (preg_match('/Firefox\/([0-9.]+)/', $user_agent, $matches)) {
                $device_name = 'Firefox Browser';
            } elseif (preg_match('/Safari\/([0-9.]+)/', $user_agent, $matches)) {
                $device_name = 'Safari Browser';
            } elseif (preg_match('/Edge\/([0-9.]+)/', $user_agent, $matches)) {
                $device_name = 'Edge Browser';
            }
            
            $processed_history[] = [
                'id' => $session['id'],
                'ip_address' => $session['ip_address'],
                'device_type' => $device_type,
                'device_name' => $device_name,
                'login_time' => $session['created_at'],
                'last_activity' => $session['last_activity']
            ];
        }
        
        return $processed_history;
    } catch (PDOException $e) {
        handle_database_error($e);
        return [];
    }
}

?>
