<?php
session_start();
require_once '../../Config/Database.php';
require_once '../../Config/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: ../Login/user_login.php');
    exit();
}

$user_type = get_user_type();
$user_id = get_current_user_id();

// Get user information
if ($user_type === 'personal') {
    // Get complete profile data
    try {
    $profile_data = get_user_complete_profile($user_id);
    } catch (Exception $e) {
        $profile_data = false;
    }
    
    if ($profile_data) {
        $user_name = $profile_data['first_name'] . ' ' . $profile_data['last_name'];
        $user_email = $profile_data['email'];
        $user_phone = $profile_data['phone'] ?? '';
        $user_bio = $profile_data['bio'] ?? 'Passionate traveler exploring the world one destination at a time. Love experiencing new cultures and cuisines.';
        $user_location = $profile_data['location'] ?? 'Cebu City, Philippines';
        $user_timezone = $profile_data['timezone'] ?? 'UTC';
        $user_date_format = $profile_data['date_format'] ?? 'MM/DD/YYYY';
        $user_currency = $profile_data['currency'] ?? 'USD';
        $profile_photo = $profile_data['profile_photo'] ?? null;
        
        // Get travel stats
        $travel_stats = [
            'total_trips' => $profile_data['total_trips'] ?? 0,
            'countries' => $profile_data['countries_visited'] ?? 0,
            'total_spent' => $profile_data['total_spent'] ?? 0,
            'miles' => $profile_data['total_miles'] ?? 0,
            'favorite_destination' => $profile_data['favorite_destination'] ?? 'No trips yet',
            'next_trip' => $profile_data['next_trip_destination'] ?? 'Plan your first trip'
        ];
        
        // Get subscription info
        $subscription = [
            'plan_name' => $profile_data['plan_name'] ?? 'Free',
            'plan_type' => $profile_data['plan_type'] ?? 'free',
            'status' => $profile_data['subscription_status'] ?? 'active',
            'end_date' => $profile_data['subscription_end_date'] ?? null
        ];
    } else {
        // Fallback to basic user data
        $user = get_user_by_id($user_id);
        if ($user) {
        $user_name = $user['first_name'] . ' ' . $user['last_name'];
        $user_email = $user['email'];
        $user_phone = $user['phone'] ?? '';
        } else {
            $user_name = 'Unknown User';
            $user_email = 'unknown@example.com';
            $user_phone = '';
        }
        $user_bio = 'Passionate traveler exploring the world one destination at a time. Love experiencing new cultures and cuisines.';
        $user_location = 'Cebu City, Philippines';
        $user_timezone = 'UTC';
        $user_date_format = 'MM/DD/YYYY';
        $user_currency = 'USD';
        $profile_photo = null;
        
        $travel_stats = [
            'total_trips' => 0,
            'countries' => 0,
            'total_spent' => 0,
            'miles' => 0,
            'favorite_destination' => 'No trips yet',
            'next_trip' => 'Plan your first trip'
        ];
        
        $subscription = [
            'plan_name' => 'Free',
            'plan_type' => 'free',
            'status' => 'active',
            'end_date' => null
        ];
    }
    
    // Get travel preferences
    try {
    $travel_preferences = get_user_travel_preferences($user_id);
    } catch (Exception $e) {
        $travel_preferences = false;
    }
    if (!$travel_preferences) {
        // Set default preferences
        $travel_preferences = [
            'preferred_class' => 'economy',
            'seat_preference' => 'no_preference',
            'meal_preference' => 'no_preference',
            'star_rating' => 'any',
            'room_type' => 'double',
            'budget_range' => 'mid_range',
            'travel_type' => 'leisure',
            'activity_level' => 'moderate'
        ];
    }
    
    // Get notification settings
    try {
    $notification_settings = get_user_notification_settings($user_id);
    } catch (Exception $e) {
        $notification_settings = false;
    }
    if (!$notification_settings) {
        // Set default settings
        $notification_settings = [
            'booking_confirmations' => true,
            'price_alerts' => true,
            'travel_reminders' => true,
            'weekly_digest' => true,
            'push_notifications' => true,
            'sms_alerts' => false,
            'marketing_emails' => false
        ];
    }
    
    
    // Get achievements
    try {
    $achievements = get_user_achievements($user_id);
    } catch (Exception $e) {
        $achievements = [];
    }
    if (empty($achievements)) {
        // Set default achievements
        $achievements = [
            [
                'title' => 'Welcome Traveler',
                'description' => 'Joined NaviGo',
                'icon' => 'fas fa-user-plus',
                'unlocked' => true,
                'color' => 'blue'
            ]
        ];
    } else {
        // Format achievements for display
        $formatted_achievements = [];
        foreach ($achievements as $achievement) {
            $formatted_achievements[] = [
                'title' => $achievement['achievement_name'],
                'description' => $achievement['description'],
                'icon' => $achievement['icon'] ?? 'fas fa-trophy',
                'unlocked' => $achievement['is_unlocked'],
                'color' => $achievement['is_unlocked'] ? 'green' : 'gray'
            ];
        }
        $achievements = $formatted_achievements;
    }
    
    // Get payment methods
    try {
    $payment_methods = get_user_payment_methods($user_id);
    } catch (Exception $e) {
        $payment_methods = [];
    }
    
    // Get billing history
    try {
    $billing_history = get_user_billing_history($user_id, 5);
    } catch (Exception $e) {
        $billing_history = [];
    }
    
} else {
    $business = get_business_by_id($user_id);
    if ($business) {
    $user_name = $business['business_name'];
    $user_email = $business['email'];
    $user_phone = $business['phone'] ?? '';
    $user_bio = $business['description'] ?? '';
    $user_location = $business['location'] ?? '';
    } else {
        $user_name = 'Unknown Business';
        $user_email = 'unknown@example.com';
        $user_phone = '';
        $user_bio = '';
        $user_location = '';
    }
    $user_timezone = 'UTC';
    $user_date_format = 'MM/DD/YYYY';
    $user_currency = 'USD';
    $profile_photo = null;
    
    $travel_stats = [
        'total_trips' => 0,
        'countries' => 0,
        'total_spent' => 0,
        'miles' => 0,
        'favorite_destination' => 'N/A',
        'next_trip' => 'N/A'
    ];
    
    $subscription = [
        'plan_name' => 'Business',
        'plan_type' => 'business',
        'status' => 'active',
        'end_date' => null
    ];
    
    $travel_preferences = [];
    $notification_settings = [];
    $security_settings = [];
    $achievements = [];
    $payment_methods = [];
    $billing_history = [];
}

// Generate CSRF token
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Profile Settings - NaviGo</title>
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="Manage your NaviGo profile settings. Update your personal information, travel preferences, and account details.">
    <meta name="theme-color" content="#0891b2" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NaviGo Profile">
    <meta name="mobile-web-app-capable" content="yes">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="../../js/manifest.json">
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="../../Assets/Images/icons/icon-48x48.png">
    <link rel="shortcut icon" href="../../Assets/Images/icons/icon-48x48.png">
    <link rel="apple-touch-icon" href="../../Assets/Images/icons/icon-180x180.png">
    
    <!-- External Resources -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/afed286f48.js" crossorigin="anonymous"></script>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../../css/user_landingpage.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/Profile.css?v=<?php echo time(); ?>">
    
    <!-- Scripts -->
    <script src="../../js/dark-mode.js"></script>
    <script src="../../js/user-mobile-features.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div class="logo-section">
                <div class="navi-logo">
                    <img src="../Assets/Images/NaviGo_Logo.png" alt="NaviGo Logo" class="logo-image">
                </div>
                <a href="user_landingpage.php" class="page-title">
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-links">
                <a href="My_trips.php" class="nav-link my-trips">
                    <i class="fas fa-suitcase"></i>
                    <span>My Trips</span>
                </a>
                <a href="Explore.php" class="nav-link explore">
                    <i class="fas fa-compass"></i>
                    <span>Explore</span>
                </a>
                <a href="History.php" class="nav-link history">
                    <i class="fas fa-clock"></i>
                    <span>History</span>
                </a>
                <a href="Plan.php" class="nav-link plan">
                    <i class="fas fa-map-pin"></i>
                    <span>Plan</span>
                </a>
            </div>
        </div>
        <div class="header-right">
            <div class="header-icon dark-mode-toggle">
                <i class="fas fa-moon"></i>
            </div>
            <div class="header-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="user-profile" onclick="toggleProfileMenu()">
                <div class="user-avatar">
                    <img src="../../Assets/Images/NaviGo_Logo.png" alt="Profile" class="profile-logo">
                </div>
                <div class="user-name"><?php echo htmlspecialchars($user_name); ?></div>
                <i class="fas fa-chevron-down profile-arrow"></i>
            </div>
            
            <!-- Profile Dropdown Menu -->
            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-header">
                    <div class="profile-avatar-large">
                        <img src="../../Assets/Images/NaviGo_Logo.png" alt="Profile" class="profile-logo">
                    </div>
                    <div class="profile-info">
                        <div class="profile-name"><?php echo htmlspecialchars($user_name); ?></div>
                        <div class="plan-badge <?php echo $subscription['plan_type']; ?>"><?php echo ucfirst($subscription['plan_name']); ?></div>
                    </div>
                </div>
                
                <div class="profile-menu">
                    <a href="../../User/Menu/Profile.php" class="menu-item active">
                        <i class="fas fa-user"></i>
                        <span>View Profile</span>
                    </a>
                    <a href="../../Subscription_plan.php" class="menu-item">
                        <i class="fas fa-crown"></i>
                        <span>Plans & Billing</span>
                    </a>
                    <a href="../../User/Menu/account_settings.php" class="menu-item">
                        <i class="fas fa-cog"></i>
                        <span>Account Settings</span>
                    </a>
                    <a href="#" class="menu-item">
                        <i class="fas fa-question-circle"></i>
                        <span>Help & Support</span>
                    </a>
                    <div class="menu-divider"></div>
                    <a href="../Login/logout.php" class="menu-item logout" onclick="return confirm('Are you sure you want to end your journey?')">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>End Journey</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="user-page-content">
        
        <!-- Profile Navigation Tabs -->
        <div class="content-toggle">
            <div class="toggle-container">
                <button class="toggle-btn active" data-tab="profile">Profile</button>
                <button class="toggle-btn" data-tab="travel">Travel</button>
                <button class="toggle-btn" data-tab="notifications">Notifications</button>
                <button class="toggle-btn" data-tab="billing">Billing</button>
            </div>
        </div>

        <!-- Profile Settings Header - Only show in Profile tab -->
        <div class="profile-header-section" id="profile-settings-header" style="display: none;">
            <div class="profile-header-content">
                <div class="profile-title-section">
                    <h1 class="profile-title">Profile Settings</h1>
                    <p class="profile-subtitle">Manage your account preferences and travel settings</p>
                </div>
                <button class="edit-profile-btn">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </button>
            </div>
        </div>

        <!-- Main Profile Content -->
        <div class="profile-content">
            <!-- Profile Tab Content -->
            <div id="profile-tab" class="tab-content active">
                <!-- Left Column -->
                <div class="profile-left-column">
                    <!-- Personal Information Section -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h2 class="section-title">Personal Information</h2>
                        </div>
                        
                        <div class="profile-display">
                            <div class="profile-avatar-section">
                                <div class="profile-avatar-large" id="profile-avatar">
                                    <?php if ($profile_photo && file_exists('../Assets/Images/profiles/' . $profile_photo)): ?>
                                        <img src="../Assets/Images/profiles/<?php echo htmlspecialchars($profile_photo); ?>" alt="Profile Photo" class="profile-photo-img">
                                    <?php else: ?>
                                        <img src="../Assets/Images/NaviGo_Logo.png" alt="Profile" class="profile-logo">
                                    <?php endif; ?>
                                </div>
                                <div class="profile-info">
                                    <h3 class="profile-name"><?php echo htmlspecialchars($user_name); ?></h3>
                                    <p class="profile-email"><?php echo htmlspecialchars($user_email); ?></p>
                                    <div class="plan-badge <?php echo $subscription['plan_type']; ?> member-badge"><?php echo ucfirst($subscription['plan_name']); ?> Member</div>
                                </div>
                            </div>
                            <button class="change-photo-btn" id="change-photo-btn">
                                <i class="fas fa-camera"></i>
                                Change Photo
                            </button>
                        </div>

                        <div class="profile-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars(explode(' ', $user_name)[0]); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars(explode(' ', $user_name)[1] ?? ''); ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user_phone); ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($user_location); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="timezone">Timezone</label>
                                    <div class="select-wrapper">
                                        <select id="timezone" name="timezone" disabled>
                                            <option value="UTC" <?php echo ($user_timezone === 'UTC') ? 'selected' : ''; ?>>UTC</option>
                                            <option value="America/New_York" <?php echo ($user_timezone === 'America/New_York') ? 'selected' : ''; ?>>Eastern Time</option>
                                            <option value="America/Chicago" <?php echo ($user_timezone === 'America/Chicago') ? 'selected' : ''; ?>>Central Time</option>
                                            <option value="America/Denver" <?php echo ($user_timezone === 'America/Denver') ? 'selected' : ''; ?>>Mountain Time</option>
                                            <option value="America/Los_Angeles" <?php echo ($user_timezone === 'America/Los_Angeles') ? 'selected' : ''; ?>>Pacific Time</option>
                                            <option value="Europe/London" <?php echo ($user_timezone === 'Europe/London') ? 'selected' : ''; ?>>London</option>
                                            <option value="Europe/Paris" <?php echo ($user_timezone === 'Europe/Paris') ? 'selected' : ''; ?>>Paris</option>
                                            <option value="Asia/Tokyo" <?php echo ($user_timezone === 'Asia/Tokyo') ? 'selected' : ''; ?>>Tokyo</option>
                                            <option value="Asia/Shanghai" <?php echo ($user_timezone === 'Asia/Shanghai') ? 'selected' : ''; ?>>Shanghai</option>
                                        </select>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="bio">Bio</label>
                                <textarea id="bio" name="bio" rows="3" readonly><?php echo htmlspecialchars($user_bio); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Settings Section -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h2 class="section-title">Regional Settings</h2>
                        </div>
                        
                        <div class="profile-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="date_format">Date Format</label>
                                    <div class="select-wrapper">
                                        <select id="date_format" name="date_format" disabled>
                                            <option value="MM/DD/YYYY" <?php echo ($user_date_format === 'MM/DD/YYYY') ? 'selected' : ''; ?>>MM/DD/YYYY</option>
                                            <option value="DD/MM/YYYY" <?php echo ($user_date_format === 'DD/MM/YYYY') ? 'selected' : ''; ?>>DD/MM/YYYY</option>
                                            <option value="YYYY-MM-DD" <?php echo ($user_date_format === 'YYYY-MM-DD') ? 'selected' : ''; ?>>YYYY-MM-DD</option>
                                        </select>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <div class="select-wrapper">
                                        <select id="currency" name="currency" disabled>
                                            <option value="USD" <?php echo ($user_currency === 'USD') ? 'selected' : ''; ?>>USD - US Dollar</option>
                                            <option value="EUR" <?php echo ($user_currency === 'EUR') ? 'selected' : ''; ?>>EUR - Euro</option>
                                            <option value="GBP" <?php echo ($user_currency === 'GBP') ? 'selected' : ''; ?>>GBP - British Pound</option>
                                            <option value="JPY">JPY - Japanese Yen</option>
                                            <option value="PHP">PHP - Philippine Peso</option>
                                        </select>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="profile-right-column">
                    <!-- Travel Stats Section -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h2 class="section-title">Travel Stats</h2>
                        </div>
                        
                        <div class="stats-grid">
                            <div class="stat-card stat-blue">
                                <div class="stat-number"><?php echo $travel_stats['total_trips']; ?></div>
                                <div class="stat-label">Total Trips</div>
                            </div>
                            <div class="stat-card stat-green">
                                <div class="stat-number"><?php echo $travel_stats['countries']; ?></div>
                                <div class="stat-label">Countries</div>
                            </div>
                            <div class="stat-card stat-purple">
                                <div class="stat-number">$<?php echo number_format($travel_stats['total_spent'] / 1000); ?>K</div>
                                <div class="stat-label">Total Spent</div>
                            </div>
                            <div class="stat-card stat-orange">
                                <div class="stat-number"><?php echo number_format($travel_stats['miles'] / 1000); ?>K</div>
                                <div class="stat-label">Miles</div>
                            </div>
                        </div>
                        
                        <div class="destination-info">
                            <div class="destination-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="destination-content">
                                    <span class="destination-label">Favorite Destination</span>
                                    <span class="destination-value"><?php echo htmlspecialchars($travel_stats['favorite_destination']); ?></span>
                                </div>
                            </div>
                            <div class="destination-item">
                                <i class="fas fa-calendar"></i>
                                <div class="destination-content">
                                    <span class="destination-label">Next Trip</span>
                                    <span class="destination-value"><?php echo htmlspecialchars($travel_stats['next_trip']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Achievements Section -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h2 class="section-title">Achievements</h2>
                        </div>
                        
                        <div class="achievements-list">
                            <?php foreach ($achievements as $achievement): ?>
                            <div class="achievement-card <?php echo (isset($achievement['unlocked']) && $achievement['unlocked']) ? 'unlocked' : 'locked'; ?>">
                                <div class="achievement-icon">
                                    <i class="<?php echo isset($achievement['icon']) ? $achievement['icon'] : 'fas fa-trophy'; ?>"></i>
                                </div>
                                <div class="achievement-content">
                                    <h4 class="achievement-title"><?php echo htmlspecialchars(isset($achievement['title']) ? $achievement['title'] : 'Achievement'); ?></h4>
                                    <p class="achievement-description"><?php echo htmlspecialchars(isset($achievement['description']) ? $achievement['description'] : 'No description available'); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Travel Tab Content -->
            <div id="travel-tab" class="tab-content">
                <div class="travel-preferences-grid">
                    <!-- Flight Preferences -->
                    <div class="preference-card">
                        <div class="preference-header">
                            <h3 class="preference-title">Flight Preferences</h3>
                        </div>
                        <div class="preference-fields">
                            <div class="preference-field">
                                <label for="preferred_class">Preferred Class</label>
                                <div class="select-wrapper">
                                    <select id="preferred_class" name="preferred_class">
                                        <option value="">Select class</option>
                                        <option value="economy" <?php echo ($travel_preferences['preferred_class'] === 'economy') ? 'selected' : ''; ?>>Economy</option>
                                        <option value="premium_economy" <?php echo ($travel_preferences['preferred_class'] === 'premium_economy') ? 'selected' : ''; ?>>Premium Economy</option>
                                        <option value="business" <?php echo ($travel_preferences['preferred_class'] === 'business') ? 'selected' : ''; ?>>Business</option>
                                        <option value="first" <?php echo ($travel_preferences['preferred_class'] === 'first') ? 'selected' : ''; ?>>First Class</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <div class="preference-field">
                                <label for="seat_preference">Seat Preference</label>
                                <div class="select-wrapper">
                                    <select id="seat_preference" name="seat_preference">
                                        <option value="window" <?php echo ($travel_preferences['seat_preference'] === 'window') ? 'selected' : ''; ?>>Window</option>
                                        <option value="aisle" <?php echo ($travel_preferences['seat_preference'] === 'aisle') ? 'selected' : ''; ?>>Aisle</option>
                                        <option value="middle" <?php echo ($travel_preferences['seat_preference'] === 'middle') ? 'selected' : ''; ?>>Middle</option>
                                        <option value="no_preference" <?php echo ($travel_preferences['seat_preference'] === 'no_preference') ? 'selected' : ''; ?>>No Preference</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <div class="preference-field">
                                <label for="meal_preference">Meal Preference</label>
                                <div class="select-wrapper">
                                    <select id="meal_preference" name="meal_preference">
                                        <option value="no_preference" <?php echo ($travel_preferences['meal_preference'] === 'no_preference') ? 'selected' : ''; ?>>No Preference</option>
                                        <option value="vegetarian" <?php echo ($travel_preferences['meal_preference'] === 'vegetarian') ? 'selected' : ''; ?>>Vegetarian</option>
                                        <option value="vegan" <?php echo ($travel_preferences['meal_preference'] === 'vegan') ? 'selected' : ''; ?>>Vegan</option>
                                        <option value="halal" <?php echo ($travel_preferences['meal_preference'] === 'halal') ? 'selected' : ''; ?>>Halal</option>
                                        <option value="kosher" <?php echo ($travel_preferences['meal_preference'] === 'kosher') ? 'selected' : ''; ?>>Kosher</option>
                                        <option value="gluten_free" <?php echo ($travel_preferences['meal_preference'] === 'gluten_free') ? 'selected' : ''; ?>>Gluten Free</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hotel Preferences -->
                    <div class="preference-card">
                        <div class="preference-header">
                            <h3 class="preference-title">Hotel Preferences</h3>
                        </div>
                        <div class="preference-fields">
                            <div class="preference-field">
                                <label for="star_rating">Star Rating</label>
                                <div class="select-wrapper">
                                    <select id="star_rating" name="star_rating">
                                        <option value="5" <?php echo ($travel_preferences['star_rating'] === '5') ? 'selected' : ''; ?>>5-Star</option>
                                        <option value="4" <?php echo ($travel_preferences['star_rating'] === '4') ? 'selected' : ''; ?>>4-Star</option>
                                        <option value="3" <?php echo ($travel_preferences['star_rating'] === '3') ? 'selected' : ''; ?>>3-Star</option>
                                        <option value="2" <?php echo ($travel_preferences['star_rating'] === '2') ? 'selected' : ''; ?>>2-Star</option>
                                        <option value="1" <?php echo ($travel_preferences['star_rating'] === '1') ? 'selected' : ''; ?>>1-Star</option>
                                        <option value="any" <?php echo ($travel_preferences['star_rating'] === 'any') ? 'selected' : ''; ?>>Any</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <div class="preference-field">
                                <label for="room_type">Room Type</label>
                                <div class="select-wrapper">
                                    <select id="room_type" name="room_type">
                                        <option value="single" <?php echo ($travel_preferences['room_type'] === 'single') ? 'selected' : ''; ?>>Single</option>
                                        <option value="double" <?php echo ($travel_preferences['room_type'] === 'double') ? 'selected' : ''; ?>>Double</option>
                                        <option value="twin" <?php echo ($travel_preferences['room_type'] === 'twin') ? 'selected' : ''; ?>>Twin</option>
                                        <option value="suite" <?php echo ($travel_preferences['room_type'] === 'suite') ? 'selected' : ''; ?>>Suite</option>
                                        <option value="family" <?php echo ($travel_preferences['room_type'] === 'family') ? 'selected' : ''; ?>>Family</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <div class="preference-field">
                                <label for="budget_range">Budget Range</label>
                                <div class="select-wrapper">
                                    <select id="budget_range" name="budget_range">
                                        <option value="budget" <?php echo ($travel_preferences['budget_range'] === 'budget') ? 'selected' : ''; ?>>Budget ($50-100/night)</option>
                                        <option value="mid_range" <?php echo ($travel_preferences['budget_range'] === 'mid_range') ? 'selected' : ''; ?>>Mid-range ($100-250/night)</option>
                                        <option value="luxury" <?php echo ($travel_preferences['budget_range'] === 'luxury') ? 'selected' : ''; ?>>Luxury ($250+/night)</option>
                                        <option value="any" <?php echo ($travel_preferences['budget_range'] === 'any') ? 'selected' : ''; ?>>Any</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Travel Style -->
                    <div class="preference-card">
                        <div class="preference-header">
                            <h3 class="preference-title">Travel Style</h3>
                        </div>
                        <div class="preference-fields">
                            <div class="preference-field">
                                <label for="travel_type">Travel Type</label>
                                <div class="select-wrapper">
                                    <select id="travel_type" name="travel_type">
                                        <option value="leisure" <?php echo ($travel_preferences['travel_type'] === 'leisure') ? 'selected' : ''; ?>>Leisure</option>
                                        <option value="business" <?php echo ($travel_preferences['travel_type'] === 'business') ? 'selected' : ''; ?>>Business</option>
                                        <option value="adventure" <?php echo ($travel_preferences['travel_type'] === 'adventure') ? 'selected' : ''; ?>>Adventure</option>
                                        <option value="cultural" <?php echo ($travel_preferences['travel_type'] === 'cultural') ? 'selected' : ''; ?>>Cultural</option>
                                        <option value="family" <?php echo ($travel_preferences['travel_type'] === 'family') ? 'selected' : ''; ?>>Family</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <div class="preference-field">
                                <label for="activity_level">Activity Level</label>
                                <div class="select-wrapper">
                                    <select id="activity_level" name="activity_level">
                                        <option value="low" <?php echo ($travel_preferences['activity_level'] === 'low') ? 'selected' : ''; ?>>Low</option>
                                        <option value="moderate" <?php echo ($travel_preferences['activity_level'] === 'moderate') ? 'selected' : ''; ?>>Moderate</option>
                                        <option value="high" <?php echo ($travel_preferences['activity_level'] === 'high') ? 'selected' : ''; ?>>High</option>
                                        <option value="extreme" <?php echo ($travel_preferences['activity_level'] === 'extreme') ? 'selected' : ''; ?>>Extreme</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Tab Content -->
            <div id="notifications-tab" class="tab-content">
                <div class="notifications-container">
                    <div class="notifications-section">
                        <h2 class="notifications-main-title">Notification Preferences</h2>
                        
                        <!-- Email Notifications -->
                        <div class="notification-category">
                            <h3 class="notification-category-title">Email Notifications</h3>
                            <div class="notification-list">
                                <div class="notification-row">
                                    <div class="notification-info">
                                        <h4 class="notification-name">Booking Confirmations</h4>
                                        <p class="notification-desc">Receive confirmation emails for all bookings.</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="booking_confirmations" <?php echo ($notification_settings['booking_confirmations']) ? 'checked' : ''; ?>>
                                        <label for="booking_confirmations"></label>
                                    </div>
                                </div>
                                <div class="notification-row">
                                    <div class="notification-info">
                                        <h4 class="notification-name">Price Alerts</h4>
                                        <p class="notification-desc">Get notified when prices drop for your watched trips.</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="price_alerts" <?php echo ($notification_settings['price_alerts']) ? 'checked' : ''; ?>>
                                        <label for="price_alerts"></label>
                                    </div>
                                </div>
                                <div class="notification-row">
                                    <div class="notification-info">
                                        <h4 class="notification-name">Travel Reminders</h4>
                                        <p class="notification-desc">Reminders about upcoming trips and check-ins.</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="travel_reminders" <?php echo ($notification_settings['travel_reminders']) ? 'checked' : ''; ?>>
                                        <label for="travel_reminders"></label>
                                    </div>
                                </div>
                                <div class="notification-row">
                                    <div class="notification-info">
                                        <h4 class="notification-name">Weekly Digest</h4>
                                        <p class="notification-desc">Weekly summary of travel deals and recommendations.</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="weekly_digest" <?php echo ($notification_settings['weekly_digest']) ? 'checked' : ''; ?>>
                                        <label for="weekly_digest"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Notifications -->
                        <div class="notification-category">
                            <h3 class="notification-category-title">Mobile Notifications</h3>
                            <div class="notification-list">
                                <div class="notification-row">
                                    <div class="notification-info">
                                        <h4 class="notification-name">Push Notifications</h4>
                                        <p class="notification-desc">Allow push notifications on mobile devices.</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="push_notifications" <?php echo ($notification_settings['push_notifications']) ? 'checked' : ''; ?>>
                                        <label for="push_notifications"></label>
                                    </div>
                                </div>
                                <div class="notification-row">
                                    <div class="notification-info">
                                        <h4 class="notification-name">SMS Alerts</h4>
                                        <p class="notification-desc">Critical travel alerts via SMS.</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="sms_alerts" <?php echo ($notification_settings['sms_alerts']) ? 'checked' : ''; ?>>
                                        <label for="sms_alerts"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Marketing -->
                        <div class="notification-category">
                            <h3 class="notification-category-title">Marketing</h3>
                            <div class="notification-list">
                                <div class="notification-row">
                                    <div class="notification-info">
                                        <h4 class="notification-name">Marketing Emails</h4>
                                        <p class="notification-desc">Promotional offers and travel inspiration.</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="marketing_emails" <?php echo ($notification_settings['marketing_emails']) ? 'checked' : ''; ?>>
                                        <label for="marketing_emails"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Tab Content -->
            <div id="security-tab" class="tab-content">
                <div class="security-container">
                    <div class="security-section">
                        <h2 class="security-main-title">Security Settings</h2>
                        
                        <!-- Account Security -->
                        <div class="security-category">
                            <h3 class="security-category-title">Account Security</h3>
                            <div class="security-list">
                                <div class="security-row">
                                    <div class="security-info">
                                        <h4 class="security-name">Two-Factor Authentication</h4>
                                        <p class="security-desc">Add an extra layer of security to your account</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="two_factor" <?php echo ($security_settings['two_factor_enabled']) ? 'checked' : ''; ?>>
                                        <label for="two_factor"></label>
                                    </div>
                                </div>
                                <div class="security-row">
                                    <div class="security-info">
                                        <h4 class="security-name">Login Alerts</h4>
                                        <p class="security-desc">Get notified of new device logins</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="login_alerts" <?php echo ($security_settings['login_alerts']) ? 'checked' : ''; ?>>
                                        <label for="login_alerts"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Settings -->
                        <div class="security-category">
                            <h3 class="security-category-title">Privacy Settings</h3>
                            <div class="security-list">
                                <div class="security-row">
                                    <div class="security-info">
                                        <h4 class="security-name">Data Sharing</h4>
                                        <p class="security-desc">Allow anonymized data sharing for service improvement</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="data_sharing" <?php echo ($security_settings['data_sharing']) ? 'checked' : ''; ?>>
                                        <label for="data_sharing"></label>
                                    </div>
                                </div>
                                <div class="security-row">
                                    <div class="security-info">
                                        <h4 class="security-name">Profile Visibility</h4>
                                    </div>
                                    <div class="select-wrapper">
                                        <select id="profile_visibility" name="profile_visibility">
                                            <option value="private" <?php echo ($security_settings['profile_visibility'] === 'private') ? 'selected' : ''; ?>>Private</option>
                                            <option value="friends" <?php echo ($security_settings['profile_visibility'] === 'friends') ? 'selected' : ''; ?>>Friends Only</option>
                                            <option value="public" <?php echo ($security_settings['profile_visibility'] === 'public') ? 'selected' : ''; ?>>Public</option>
                                        </select>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="security-category">
                            <h3 class="security-category-title">Password</h3>
                            <div class="password-section">
                                <button class="change-password-btn">Change Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Tab Content -->
            <div id="billing-tab" class="tab-content">
                <div class="billing-container">
                    <div class="billing-layout">
                    <!-- Left Column -->
                    <div class="billing-left">
                        <!-- Current Plan -->
                        <div class="billing-card">
                            <h2 class="billing-card-title">Current Plan</h2>
                            <div class="plan-info">
                                <div class="plan-badge billing-plan-badge <?php echo $subscription['plan_type']; ?>">
                                    <h3 class="plan-name"><?php echo htmlspecialchars($subscription['plan_name']); ?> Plan</h3>
                                    <div class="plan-price"><?php 
                                        if ($subscription['plan_type'] === 'free') {
                                            echo 'Free';
                                        } else {
                                            echo 'Price TBD';
                                        }
                                    ?></div>
                                    <p class="plan-description"><?php echo htmlspecialchars($subscription['plan_type'] === 'free' ? 'Basic features' : 'Unlimited trips, group collaboration, priority support'); ?></p>
                                </div>
                                <div class="billing-details">
                                    <?php if ($subscription['end_date']): ?>
                                        <p class="billing-date">Next billing date: <?php echo date('F j, Y', strtotime($subscription['end_date'])); ?></p>
                                    <?php endif; ?>
                                    <p class="billing-renewal">Plan status: <?php echo ucfirst($subscription['status']); ?></p>
                                </div>
                                <div class="plan-buttons">
                                    <button class="plan-btn" onclick="showChangePlanModal()">Change Plan</button>
                                    <button class="plan-btn cancel" onclick="showCancelSubscriptionModal()">Cancel Subscription</button>
                                </div>
                            </div>
                        </div>

                        <!-- Billing History -->
                        <div class="billing-card">
                            <h2 class="billing-card-title">Billing History</h2>
                            <div class="history-list">
                                <?php if (!empty($billing_history)): ?>
                                    <?php foreach ($billing_history as $bill): ?>
                                        <div class="history-item">
                                            <div class="history-info">
                                                <h4 class="history-title"><?php echo htmlspecialchars($bill['description'] ?? 'Subscription'); ?></h4>
                                                <p class="history-date"><?php echo date('F j, Y', strtotime($bill['billing_date'])); ?></p>
                                            </div>
                                            <div class="history-amount">
                                                <span class="amount">$<?php echo number_format($bill['amount'], 2); ?></span>
                                                <button class="download-btn" onclick="downloadInvoice(<?php echo $bill['id']; ?>)">Download</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="history-item">
                                        <div class="history-info">
                                            <h4 class="history-title">No billing history</h4>
                                            <p class="history-date">No invoices found</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="billing-right">
                        <!-- Payment Methods -->
                        <div class="billing-card">
                            <h2 class="billing-card-title">Payment Methods</h2>
                            <div class="payment-section">
                                <?php if (!empty($payment_methods)): ?>
                                    <?php foreach ($payment_methods as $payment): ?>
                                        <div class="payment-card">
                                            <div class="card-icon">
                                                <i class="fas fa-credit-card"></i>
                                            </div>
                                            <div class="card-info">
                                                <div class="card-number">•••• •••• •••• <?php echo htmlspecialchars($payment['last_four_digits']); ?></div>
                                                <div class="card-expiry">Expires <?php echo htmlspecialchars($payment['expiry_month'] . '/' . $payment['expiry_year']); ?></div>
                                            </div>
                                            <?php if ($payment['is_primary']): ?>
                                                <div class="card-badge">
                                                    <span class="primary-badge">Primary</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="payment-card">
                                        <div class="card-icon">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <div class="card-info">
                                            <div class="card-number">No payment methods</div>
                                            <div class="card-expiry">Add a payment method to get started</div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <button class="add-payment-btn" id="add-payment-btn">Add Payment Method</button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // CSRF Token
        const csrfToken = '<?php echo $csrf_token; ?>';
        
        // Toast notification system
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-content">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Profile menu toggle
        function toggleProfileMenu() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Close profile menu when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.getElementById('profileDropdown');
            const userProfile = document.querySelector('.user-profile');
            
            if (!userProfile.contains(event.target)) {
                profileDropdown.classList.remove('show');
            }
        });

        // Profile navigation tabs
        document.querySelectorAll('.toggle-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Hide all tab content
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                
                // Show selected tab content
                const tabId = this.getAttribute('data-tab') + '-tab';
                const targetTab = document.getElementById(tabId);
                if (targetTab) {
                    targetTab.classList.add('active');
                }
                
                // Show/hide Profile Settings header only for Profile tab
                const profileHeader = document.getElementById('profile-settings-header');
                if (this.getAttribute('data-tab') === 'profile') {
                    profileHeader.style.display = 'block';
                } else {
                    profileHeader.style.display = 'none';
                }
            });
        });
        
        // Show Profile Settings header on page load since Profile tab is active by default
        document.addEventListener('DOMContentLoaded', function() {
            const profileHeader = document.getElementById('profile-settings-header');
            profileHeader.style.display = 'block';
        });

        // Edit profile functionality
        document.querySelector('.edit-profile-btn').addEventListener('click', function() {
            const inputs = document.querySelectorAll('.profile-form input, .profile-form textarea, .profile-form select');
            const isEditing = this.textContent.includes('Save');
            
            if (isEditing) {
                // Save changes
                saveProfile();
            } else {
                // Enable editing
                this.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                inputs.forEach(input => {
                    input.readOnly = false;
                    input.disabled = false;
                });
                
                // Add change listeners for auto-save
                inputs.forEach(input => {
                    input.addEventListener('change', markAsChanged);
                    input.addEventListener('input', markAsChanged);
                });
            }
        });

        // Save profile function
        function saveProfile() {
            const formData = new FormData();
            formData.append('csrf_token', csrfToken);
            formData.append('first_name', document.getElementById('first_name').value);
            formData.append('last_name', document.getElementById('last_name').value);
            formData.append('phone', document.getElementById('phone').value);
            formData.append('location', document.getElementById('location').value);
            formData.append('timezone', document.getElementById('timezone').value);
            formData.append('bio', document.getElementById('bio').value);
            formData.append('date_format', document.getElementById('date_format').value);
            formData.append('currency', document.getElementById('currency').value);

            fetch('ajax/update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    // Reset edit mode
                    const editBtn = document.querySelector('.edit-profile-btn');
                    editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit Profile';
                    const inputs = document.querySelectorAll('.profile-form input, .profile-form textarea, .profile-form select');
                    inputs.forEach(input => {
                        input.readOnly = true;
                        input.disabled = true;
                    });
                    hasUnsavedChanges = false;
                    updateSaveIndicator();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('An error occurred while updating profile', 'error');
            });
        }

        // Change photo functionality
        document.querySelector('.change-photo-btn').addEventListener('click', function() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file before upload
                    if (file.size > 5 * 1024 * 1024) { // 5MB limit
                        showToast('File too large. Maximum size is 5MB.', 'error');
                        return;
                    }
                    
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!allowedTypes.includes(file.type)) {
                        showToast('Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.', 'error');
                        return;
                    }
                    
                    // Show preview before upload
                    showPhotoPreview(file);
                }
            };
            input.click();
        });
        
        // Show photo preview before upload
        function showPhotoPreview(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const modal = document.createElement('div');
                modal.className = 'modal-overlay';
                modal.innerHTML = `
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Preview Profile Photo</h3>
                            <button class="modal-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="photo-preview-container">
                                <img src="${e.target.result}" alt="Preview" class="photo-preview">
                            </div>
                            <p class="preview-text">This will be your new profile photo. Do you want to continue?</p>
                            <div class="modal-actions">
                                <button type="button" class="btn-cancel" onclick="closePhotoPreview()">Cancel</button>
                                <button type="button" class="btn-primary" onclick="confirmPhotoUpload()">Use This Photo</button>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                // Store the file for later use
                window.pendingPhotoFile = file;
                
                // Close modal events
                modal.querySelector('.modal-close').addEventListener('click', () => {
                    document.body.removeChild(modal);
                    window.pendingPhotoFile = null;
                });
                
                modal.querySelector('.btn-cancel').addEventListener('click', () => {
                    document.body.removeChild(modal);
                    window.pendingPhotoFile = null;
                });
                
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        document.body.removeChild(modal);
                        window.pendingPhotoFile = null;
                    }
                });
            };
            reader.readAsDataURL(file);
        }
        
        function closePhotoPreview() {
            const modal = document.querySelector('.modal-overlay');
            if (modal) {
                document.body.removeChild(modal);
            }
            window.pendingPhotoFile = null;
        }
        
        function confirmPhotoUpload() {
            if (window.pendingPhotoFile) {
                uploadPhoto(window.pendingPhotoFile);
                closePhotoPreview();
            }
        }

        // Upload photo function
        function uploadPhoto(file) {
            // Show loading state
            const changeBtn = document.getElementById('change-photo-btn');
            const originalText = changeBtn.innerHTML;
            changeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            changeBtn.disabled = true;

            const formData = new FormData();
            formData.append('csrf_token', csrfToken);
            formData.append('profile_photo', file);

            fetch('ajax/upload_photo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    // Update the profile photo display
                    updateProfilePhoto(data.filename);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('An error occurred while uploading photo', 'error');
            })
            .finally(() => {
                // Reset button state
                changeBtn.innerHTML = originalText;
                changeBtn.disabled = false;
            });
        }

        // Update profile photo display
        function updateProfilePhoto(filename) {
            const avatar = document.getElementById('profile-avatar');
            avatar.innerHTML = `<img src="../Assets/Images/profiles/${filename}" alt="Profile Photo" class="profile-photo-img">`;
            
            // Also update the header avatar if it exists
            const headerAvatar = document.querySelector('.profile-avatar-large');
            if (headerAvatar) {
                headerAvatar.innerHTML = `<img src="../Assets/Images/profiles/${filename}" alt="Profile Photo" class="profile-photo-img">`;
            }
        }

        // Travel preferences auto-save
        document.querySelectorAll('#travel-tab select').forEach(select => {
            select.addEventListener('change', function() {
                saveTravelPreferences();
            });
        });

        function saveTravelPreferences() {
            const formData = new FormData();
            formData.append('csrf_token', csrfToken);
            formData.append('preferred_class', document.getElementById('preferred_class').value);
            formData.append('seat_preference', document.getElementById('seat_preference').value);
            formData.append('meal_preference', document.getElementById('meal_preference').value);
            formData.append('star_rating', document.getElementById('star_rating').value);
            formData.append('room_type', document.getElementById('room_type').value);
            formData.append('budget_range', document.getElementById('budget_range').value);
            formData.append('travel_type', document.getElementById('travel_type').value);
            formData.append('activity_level', document.getElementById('activity_level').value);

            fetch('ajax/update_travel_preferences.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Travel preferences updated', 'success');
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('An error occurred while updating preferences', 'error');
            });
        }

        // Notification settings auto-save
        document.querySelectorAll('#notifications-tab input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                saveNotificationSettings();
            });
        });

        function saveNotificationSettings() {
            const formData = new FormData();
            formData.append('csrf_token', csrfToken);
            formData.append('booking_confirmations', document.getElementById('booking_confirmations').checked);
            formData.append('price_alerts', document.getElementById('price_alerts').checked);
            formData.append('travel_reminders', document.getElementById('travel_reminders').checked);
            formData.append('weekly_digest', document.getElementById('weekly_digest').checked);
            formData.append('push_notifications', document.getElementById('push_notifications').checked);
            formData.append('sms_alerts', document.getElementById('sms_alerts').checked);
            formData.append('marketing_emails', document.getElementById('marketing_emails').checked);

            fetch('ajax/update_notifications.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Notification settings updated', 'success');
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('An error occurred while updating notifications', 'error');
            });
        }




        // Payment Methods functionality
        document.getElementById('add-payment-btn').addEventListener('click', function() {
            showPaymentMethodModal();
        });

        function showPaymentMethodModal() {
            const modal = document.createElement('div');
            modal.className = 'modal-overlay';
            modal.innerHTML = `
                <div class="modal-content modal-large">
                    <div class="modal-header">
                        <h3>Add Payment Method</h3>
                        <button class="modal-close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="payment-method-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cardholder_name">Cardholder Name</label>
                                    <input type="text" id="cardholder_name" name="cardholder_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="card_number">Card Number</label>
                                    <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="expiry_month">Expiry Month</label>
                                    <select id="expiry_month" name="expiry_month" required>
                                        <option value="">Month</option>
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="expiry_year">Expiry Year</label>
                                    <select id="expiry_year" name="expiry_year" required>
                                        <option value="">Year</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="address_line_1">Billing Address</label>
                                <input type="text" id="address_line_1" name="address_line_1" placeholder="Street address" required>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" id="city" name="city" required>
                                </div>
                                <div class="form-group">
                                    <label for="state">State/Province</label>
                                    <input type="text" id="state" name="state" required>
                                </div>
                                <div class="form-group">
                                    <label for="postal_code">Postal Code</label>
                                    <input type="text" id="postal_code" name="postal_code" required>
                                </div>
                                <div class="form-group">
                                    <label for="country_code">Country</label>
                                    <select id="country_code" name="country_code" required>
                                        <option value="US">United States</option>
                                        <option value="CA">Canada</option>
                                        <option value="GB">United Kingdom</option>
                                        <option value="AU">Australia</option>
                                        <option value="DE">Germany</option>
                                        <option value="FR">France</option>
                                        <option value="JP">Japan</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="modal-actions">
                                <button type="button" class="btn-cancel" onclick="closePaymentModal()">Cancel</button>
                                <button type="submit" class="btn-primary">Add Payment Method</button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Populate year dropdown
            const currentYear = new Date().getFullYear();
            const yearSelect = document.getElementById('expiry_year');
            for (let i = 0; i < 10; i++) {
                const year = currentYear + i;
                const option = document.createElement('option');
                option.value = year.toString().slice(-2);
                option.textContent = year;
                yearSelect.appendChild(option);
            }
            
            // Format card number
            document.getElementById('card_number').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
                let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
                e.target.value = formattedValue;
            });
            
            // Close modal events
            modal.querySelector('.modal-close').addEventListener('click', () => {
                document.body.removeChild(modal);
            });
            
            modal.querySelector('.btn-cancel').addEventListener('click', () => {
                document.body.removeChild(modal);
            });
            
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
            
            // Form submission
            modal.querySelector('#payment-method-form').addEventListener('submit', function(e) {
                e.preventDefault();
                addPaymentMethod();
            });
        }

        function addPaymentMethod() {
            const formData = new FormData();
            formData.append('csrf_token', csrfToken);
            formData.append('action', 'add');
            formData.append('cardholder_name', document.getElementById('cardholder_name').value);
            formData.append('card_number', document.getElementById('card_number').value.replace(/\s/g, ''));
            formData.append('expiry_month', document.getElementById('expiry_month').value);
            formData.append('expiry_year', document.getElementById('expiry_year').value);
            formData.append('address_line_1', document.getElementById('address_line_1').value);
            formData.append('city', document.getElementById('city').value);
            formData.append('state', document.getElementById('state').value);
            formData.append('postal_code', document.getElementById('postal_code').value);
            formData.append('country_code', document.getElementById('country_code').value);

            fetch('ajax/payment_methods.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    document.querySelector('.modal-overlay').remove();
                    // Refresh payment methods display
                    location.reload();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('An error occurred while adding payment method', 'error');
            });
        }

        function closePaymentModal() {
            const modal = document.querySelector('.modal-overlay');
            if (modal) {
                document.body.removeChild(modal);
            }
        }

        // Invoice download functionality
        function downloadInvoice(invoiceId) {
            const formData = new FormData();
            formData.append('csrf_token', csrfToken);
            formData.append('invoice_id', invoiceId);

            fetch('ajax/generate_invoice.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    return response.blob();
                } else {
                    throw new Error('Failed to generate invoice');
                }
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `invoice_${invoiceId}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                showToast('Invoice downloaded successfully', 'success');
            })
            .catch(error => {
                showToast('Failed to download invoice', 'error');
                console.error('Error:', error);
            });
        }

        // Password strength and toggle functionality
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.parentElement.querySelector('.password-toggle i');
            
            if (field.type === 'password') {
                field.type = 'text';
                button.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                button.className = 'fas fa-eye';
            }
        }

        function checkPasswordStrength(password) {
            let score = 0;
            let feedback = [];
            
            // Length check
            if (password.length >= 8) score += 1;
            else feedback.push('at least 8 characters');
            
            // Character variety checks
            if (/[a-z]/.test(password)) score += 1;
            else feedback.push('lowercase letters');
            
            if (/[A-Z]/.test(password)) score += 1;
            else feedback.push('uppercase letters');
            
            if (/[0-9]/.test(password)) score += 1;
            else feedback.push('numbers');
            
            if (/[^A-Za-z0-9]/.test(password)) score += 1;
            else feedback.push('special characters');
            
            // Common patterns check
            if (!/(.)\1{2,}/.test(password)) score += 1; // No repeated characters
            else feedback.push('avoid repeated characters');
            
            if (!/123|abc|qwe|asd|zxc/i.test(password)) score += 1; // No common sequences
            else feedback.push('avoid common sequences');
            
            return { score, feedback };
        }

        function updatePasswordStrength() {
            const password = document.getElementById('new_password').value;
            const strengthFill = document.getElementById('strength-fill');
            const strengthText = document.getElementById('strength-text');
            
            if (password.length === 0) {
                strengthFill.className = 'strength-fill';
                strengthText.textContent = 'Enter a password';
                strengthText.className = 'strength-text';
                return;
            }
            
            const { score, feedback } = checkPasswordStrength(password);
            
            // Update visual indicator
            strengthFill.className = 'strength-fill';
            strengthText.className = 'strength-text';
            
            if (score <= 2) {
                strengthFill.classList.add('weak');
                strengthText.classList.add('weak');
                strengthText.textContent = 'Weak password';
            } else if (score <= 4) {
                strengthFill.classList.add('fair');
                strengthText.classList.add('fair');
                strengthText.textContent = 'Fair password';
            } else if (score <= 5) {
                strengthFill.classList.add('good');
                strengthText.classList.add('good');
                strengthText.textContent = 'Good password';
            } else {
                strengthFill.classList.add('strong');
                strengthText.classList.add('strong');
                strengthText.textContent = 'Strong password';
            }
            
            // Show feedback for improvement
            if (feedback.length > 0 && score < 6) {
                strengthText.textContent += ' - Add: ' + feedback.slice(0, 2).join(', ');
            }
        }

        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchDiv = document.getElementById('password-match');
            
            if (confirmPassword.length === 0) {
                matchDiv.textContent = '';
                matchDiv.className = 'password-match';
                return;
            }
            
            if (password === confirmPassword) {
                matchDiv.textContent = '✓ Passwords match';
                matchDiv.className = 'password-match match';
            } else {
                matchDiv.textContent = '✗ Passwords do not match';
                matchDiv.className = 'password-match no-match';
            }
        }

        // Auto-save and unsaved changes functionality
        let hasUnsavedChanges = false;
        let autoSaveTimeout = null;
        let originalData = {};

        function initializeAutoSave() {
            // Store original form data
            originalData = getFormData();
            
            // Add change listeners to all form inputs
            const formInputs = document.querySelectorAll('#profile-tab input, #profile-tab select, #profile-tab textarea');
            formInputs.forEach(input => {
                input.addEventListener('input', markAsChanged);
                input.addEventListener('change', markAsChanged);
            });
            
            // Add beforeunload warning
            window.addEventListener('beforeunload', function(e) {
                if (hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                    return e.returnValue;
                }
            });
            
            // Add tab change warning
            document.querySelectorAll('.toggle-btn').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    if (hasUnsavedChanges) {
                        if (!confirm('You have unsaved changes. Do you want to save them before switching tabs?')) {
                            e.preventDefault();
                            return false;
                        }
                        saveProfileData();
                    }
                });
            });
        }

        function getFormData() {
            return {
                first_name: document.getElementById('first_name')?.value || '',
                last_name: document.getElementById('last_name')?.value || '',
                email: document.getElementById('email')?.value || '',
                phone: document.getElementById('phone')?.value || '',
                location: document.getElementById('location')?.value || '',
                timezone: document.getElementById('timezone')?.value || '',
                bio: document.getElementById('bio')?.value || '',
                date_format: document.getElementById('date_format')?.value || '',
                currency: document.getElementById('currency')?.value || ''
            };
        }

        function markAsChanged() {
            hasUnsavedChanges = true;
            updateSaveIndicator();
            
            // Clear existing timeout
            if (autoSaveTimeout) {
                clearTimeout(autoSaveTimeout);
            }
            
            // Set new timeout for auto-save
            autoSaveTimeout = setTimeout(() => {
                saveProfileData();
            }, 2000); // Auto-save after 2 seconds of inactivity
        }

        function updateSaveIndicator() {
            let indicator = document.getElementById('save-indicator');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'save-indicator';
                indicator.className = 'save-indicator';
                document.querySelector('.profile-header').appendChild(indicator);
            }
            
            if (hasUnsavedChanges) {
                indicator.innerHTML = '<i class="fas fa-circle" style="color: #f59e0b;"></i> Unsaved changes';
                indicator.className = 'save-indicator unsaved';
            } else {
                indicator.innerHTML = '<i class="fas fa-check-circle" style="color: #10b981;"></i> All changes saved';
                indicator.className = 'save-indicator saved';
            }
        }

        function saveProfileData() {
            const formData = new FormData();
            formData.append('csrf_token', csrfToken);
            
            const data = getFormData();
            Object.keys(data).forEach(key => {
                formData.append(key, data[key]);
            });

            fetch('ajax/update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hasUnsavedChanges = false;
                    updateSaveIndicator();
                    showToast('Profile saved automatically', 'success');
                } else {
                    showToast('Failed to save profile: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Error saving profile', 'error');
                console.error('Error:', error);
            });
        }

        // Add event listeners for password fields
        document.addEventListener('DOMContentLoaded', function() {
            const newPasswordField = document.getElementById('new_password');
            const confirmPasswordField = document.getElementById('confirm_password');
            
            if (newPasswordField) {
                newPasswordField.addEventListener('input', updatePasswordStrength);
            }
            
            if (confirmPasswordField) {
                confirmPasswordField.addEventListener('input', checkPasswordMatch);
            }
            
            // Initialize auto-save
            initializeAutoSave();
            
            // Initialize form validation
            initializeFormValidation();
        });

        // Subscription Management
        function showChangePlanModal() {
            const modal = document.createElement('div');
            modal.className = 'modal-overlay';
            modal.innerHTML = `
                <div class="modal-content modal-large">
                    <div class="modal-header">
                        <h3>Change Subscription Plan</h3>
                        <button class="modal-close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="plan-comparison">
                            <div class="plan-option">
                                <h4>Free Plan</h4>
                                <div class="plan-price">$0/month</div>
                                <ul class="plan-features">
                                    <li>Basic trip planning</li>
                                    <li>Up to 3 trips</li>
                                    <li>Standard support</li>
                                </ul>
                                <button class="btn-secondary" onclick="changePlan('free')">Select Free</button>
                            </div>
                            
                            <div class="plan-option recommended">
                                <div class="recommended-badge">Recommended</div>
                                <h4>Premium Plan</h4>
                                <div class="plan-price">$29.99/month</div>
                                <ul class="plan-features">
                                    <li>Unlimited trips</li>
                                    <li>Group collaboration</li>
                                    <li>Priority support</li>
                                    <li>Advanced analytics</li>
                                </ul>
                                <button class="btn-primary" onclick="changePlan('premium')">Select Premium</button>
                            </div>
                            
                            <div class="plan-option">
                                <h4>Business Plan</h4>
                                <div class="plan-price">$99.99/month</div>
                                <ul class="plan-features">
                                    <li>Everything in Premium</li>
                                    <li>Team management</li>
                                    <li>API access</li>
                                    <li>Custom integrations</li>
                                </ul>
                                <button class="btn-secondary" onclick="changePlan('business')">Select Business</button>
                            </div>
                        </div>
                        
                        <div class="modal-actions">
                            <button type="button" class="btn-cancel" onclick="closeSubscriptionModal()">Cancel</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Close modal events
            modal.querySelector('.modal-close').addEventListener('click', () => {
                document.body.removeChild(modal);
            });
            
            modal.querySelector('.btn-cancel').addEventListener('click', () => {
                document.body.removeChild(modal);
            });
            
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        }

        function showCancelSubscriptionModal() {
            const modal = document.createElement('div');
            modal.className = 'modal-overlay';
            modal.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Cancel Subscription</h3>
                        <button class="modal-close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="cancel-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h4>Are you sure you want to cancel your subscription?</h4>
                            <p>You will lose access to premium features and your data will be downgraded to the free plan.</p>
                        </div>
                        
                        <div class="cancel-options">
                            <label>
                                <input type="radio" name="cancel_reason" value="too_expensive">
                                Too expensive
                            </label>
                            <label>
                                <input type="radio" name="cancel_reason" value="not_using">
                                Not using the service
                            </label>
                            <label>
                                <input type="radio" name="cancel_reason" value="found_alternative">
                                Found a better alternative
                            </label>
                            <label>
                                <input type="radio" name="cancel_reason" value="other">
                                Other reason
                            </label>
                        </div>
                        
                        <div class="feedback-section" id="feedback-section" style="display: none;">
                            <label for="cancel_feedback">Please tell us more (optional):</label>
                            <textarea id="cancel_feedback" placeholder="Help us improve our service..."></textarea>
                        </div>
                        
                        <div class="modal-actions">
                            <button type="button" class="btn-cancel" onclick="closeSubscriptionModal()">Keep Subscription</button>
                            <button type="button" class="btn-danger" onclick="confirmCancelSubscription()">Cancel Subscription</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Show feedback section for "other" reason
            modal.querySelectorAll('input[name="cancel_reason"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const feedbackSection = modal.querySelector('#feedback-section');
                    if (this.value === 'other') {
                        feedbackSection.style.display = 'block';
                    } else {
                        feedbackSection.style.display = 'none';
                    }
                });
            });
            
            // Close modal events
            modal.querySelector('.modal-close').addEventListener('click', () => {
                document.body.removeChild(modal);
            });
            
            modal.querySelector('.btn-cancel').addEventListener('click', () => {
                document.body.removeChild(modal);
            });
            
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        }

        function changePlan(planType) {
            const formData = new FormData();
            formData.append('csrf_token', csrfToken);
            formData.append('action', 'change_plan');
            formData.append('plan_type', planType);

            fetch('ajax/manage_subscription.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Plan changed successfully!', 'success');
                    document.querySelector('.modal-overlay').remove();
                    location.reload();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Error changing plan', 'error');
                console.error('Error:', error);
            });
        }

        function confirmCancelSubscription() {
            const reason = document.querySelector('input[name="cancel_reason"]:checked')?.value;
            const feedback = document.getElementById('cancel_feedback')?.value || '';
            
            if (!reason) {
                showToast('Please select a reason for cancellation', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('csrf_token', csrfToken);
            formData.append('action', 'cancel_subscription');
            formData.append('reason', reason);
            formData.append('feedback', feedback);

            fetch('ajax/manage_subscription.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Subscription cancelled successfully', 'success');
                    document.querySelector('.modal-overlay').remove();
                    location.reload();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Error cancelling subscription', 'error');
                console.error('Error:', error);
            });
        }

        function closeSubscriptionModal() {
            const modal = document.querySelector('.modal-overlay');
            if (modal) {
                document.body.removeChild(modal);
            }
        }

        // Enhanced Form Validation
        function initializeFormValidation() {
            // Email validation
            const emailField = document.getElementById('email');
            if (emailField) {
                emailField.addEventListener('blur', validateEmail);
                emailField.addEventListener('input', clearEmailError);
            }

            // Phone validation
            const phoneField = document.getElementById('phone');
            if (phoneField) {
                phoneField.addEventListener('blur', validatePhone);
                phoneField.addEventListener('input', clearPhoneError);
            }

            // Name validation
            const nameFields = ['first_name', 'last_name'];
            nameFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('blur', () => validateName(fieldId));
                    field.addEventListener('input', () => clearFieldError(fieldId));
                }
            });

            // Date of birth validation
            const dobField = document.getElementById('date_of_birth');
            if (dobField) {
                dobField.addEventListener('blur', validateDateOfBirth);
                dobField.addEventListener('input', clearFieldError.bind(null, 'date_of_birth'));
            }

            // Bio character count
            const bioField = document.getElementById('bio');
            if (bioField) {
                bioField.addEventListener('input', updateBioCharacterCount);
            }
        }

        function validateEmail() {
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const isValid = emailRegex.test(email);
            
            showFieldValidation('email', isValid, isValid ? 'Valid email address' : 'Please enter a valid email address');
            return isValid;
        }

        function validatePhone() {
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            const isValid = phone === '' || phoneRegex.test(phone.replace(/\s/g, ''));
            
            showFieldValidation('phone', isValid, isValid ? 'Valid phone number' : 'Please enter a valid phone number');
            return isValid;
        }

        function validateName(fieldId) {
            const field = document.getElementById(fieldId);
            const value = field.value.trim();
            const isValid = value.length >= 2 && /^[a-zA-Z\s]+$/.test(value);
            
            showFieldValidation(fieldId, isValid, isValid ? 'Valid name' : 'Name must be at least 2 characters and contain only letters');
            return isValid;
        }

        function validateDateOfBirth() {
            const dob = document.getElementById('date_of_birth').value;
            if (!dob) {
                clearFieldError('date_of_birth');
                return true;
            }
            
            const dobDate = new Date(dob);
            const today = new Date();
            const age = today.getFullYear() - dobDate.getFullYear();
            const isValid = dobDate < today && age >= 13 && age <= 120;
            
            showFieldValidation('date_of_birth', isValid, isValid ? 'Valid date of birth' : 'Please enter a valid date of birth (age 13-120)');
            return isValid;
        }

        function updateBioCharacterCount() {
            const bioField = document.getElementById('bio');
            const charCount = bioField.value.length;
            const maxLength = 500;
            
            let counter = document.getElementById('bio-counter');
            if (!counter) {
                counter = document.createElement('div');
                counter.id = 'bio-counter';
                counter.className = 'char-counter';
                bioField.parentNode.appendChild(counter);
            }
            
            counter.textContent = `${charCount}/${maxLength} characters`;
            counter.className = `char-counter ${charCount > maxLength ? 'over-limit' : ''}`;
            
            if (charCount > maxLength) {
                showFieldValidation('bio', false, 'Bio is too long. Please keep it under 500 characters.');
            } else {
                clearFieldError('bio');
            }
        }

        function showFieldValidation(fieldId, isValid, message) {
            const field = document.getElementById(fieldId);
            if (!field) return;
            
            // Remove existing validation message
            const existingMessage = field.parentNode.querySelector('.validation-message');
            if (existingMessage) {
                existingMessage.remove();
            }
            
            // Add validation styling
            field.classList.remove('field-valid', 'field-invalid');
            field.classList.add(isValid ? 'field-valid' : 'field-invalid');
            
            // Add validation message
            const messageDiv = document.createElement('div');
            messageDiv.className = `validation-message ${isValid ? 'valid' : 'invalid'}`;
            messageDiv.textContent = message;
            field.parentNode.appendChild(messageDiv);
        }

        function clearFieldError(fieldId) {
            const field = document.getElementById(fieldId);
            if (!field) return;
            
            field.classList.remove('field-valid', 'field-invalid');
            const existingMessage = field.parentNode.querySelector('.validation-message');
            if (existingMessage) {
                existingMessage.remove();
            }
        }

        function clearEmailError() {
            clearFieldError('email');
        }

        function clearPhoneError() {
            clearFieldError('phone');
        }

        // Form submission validation
        function validateForm() {
            const validations = [
                validateEmail(),
                validatePhone(),
                validateName('first_name'),
                validateName('last_name'),
                validateDateOfBirth()
            ];
            
            return validations.every(validation => validation);
        }
    </script>
</body>
</html>
