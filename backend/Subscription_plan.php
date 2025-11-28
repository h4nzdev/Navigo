<?php
session_start();
require_once 'Config/Database.php';
require_once 'Config/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: Login/user_login.php');
    exit();
}

$user_type = get_user_type();
$user_id = get_current_user_id();

// Get user information
if ($user_type === 'personal') {
    $user = get_user_by_id($user_id);
    $user_name = $user['first_name'] . ' ' . $user['last_name'];
} else {
    $business = get_business_by_id($user_id);
    $user_name = $business['business_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Plans - NaviGo</title>
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="Assets/Images/icons/icon-48x48.png">
    <link rel="shortcut icon" href="Assets/Images/icons/icon-48x48.png">
    <link rel="apple-touch-icon" href="Assets/Images/icons/icon-180x180.png">
    
    <!-- Theme Colors -->
    <meta name="theme-color" content="#0891b2" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
    
    <!-- External Resources -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/afed286f48.js" crossorigin="anonymous"></script>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/user_landingpage.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/Subscriptionplan.css?v=<?php echo time(); ?>">
    <script src="js/dark-mode.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div class="logo-section">
                <div class="navi-logo">
                    <img src="Assets/Images/NaviGo_Logo.png" alt="NaviGo Logo" class="logo-image">
                </div>
                <a href="#" class="page-title">
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-links">
                <a href="User/My_trips.php" class="nav-link my-trips">
                    <i class="fas fa-suitcase"></i>
                    <span>My Trips</span>
                </a>
                <a href="User/Explore.php" class="nav-link explore">
                    <i class="fas fa-compass"></i>
                    <span>Explore</span>
                </a>
                <a href="User/History.php" class="nav-link history">
                    <i class="fas fa-clock"></i>
                    <span>History</span>
                </a>
                <a href="User/Plan.php" class="nav-link plan">
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
                    <img src="Assets/Images/NaviGo_Logo.png" alt="Profile" class="profile-logo">
                </div>
                <div class="user-name"><?php echo htmlspecialchars($user_name); ?></div>
                <i class="fas fa-chevron-down profile-arrow"></i>
            </div>
            
            <!-- Profile Dropdown Menu -->
            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-header">
                    <div class="profile-avatar-large">
                        <img src="Assets/Images/NaviGo_Logo.png" alt="Profile" class="profile-logo">
                    </div>
                    <div class="profile-info">
                        <div class="profile-name"><?php echo htmlspecialchars($user_name); ?></div>
                        <div class="plan-badge free dropdown-badge">Free</div>
                    </div>
                </div>
                
                <div class="profile-menu">
                    <a href="User/Menu/Profile.php" class="menu-item">
                        <i class="fas fa-user"></i>
                        <span>View Profile</span>
                    </a>
                    <a href="" class="menu-item">
                        <i class="fas fa-crown"></i>
                        <span>Plans & Billing</span>
                    </a>
                    <a href="User/Menu/account_settings.php" class="menu-item">
                        <i class="fas fa-cog"></i>
                        <span>Account Settings</span>
                    </a>
                    <a href="#" class="menu-item">
                        <i class="fas fa-question-circle"></i>
                        <span>Help & Support</span>
                    </a>
                    <div class="menu-divider"></div>
                    <a href="Login/logout.php" class="menu-item logout" onclick="return confirm('Are you sure you want to end your journey?')">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>End Journey</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="subscription-page-header">
            <div class="manage-plan-section">
                <i class="fas fa-crown"></i>
                <span>Manage Your Plan</span>
            </div>
            <h1 class="subscription-page-title">Subscription Plans</h1>
            <p class="subscription-page-subtitle">Choose the perfect plan for your travel adventures. Upgrade anytime to unlock more features.</p>
            <div class="current-plan-badge free">
                <i class="fas fa-star"></i>
                <span>Current Plan: Free</span>
            </div>
        </div>
        
        <!-- Subscription Plans -->
        <div class="plans-container">
            <div class="plan-card free-plan">
                <div class="current-plan-indicator">
                    <i class="fas fa-check"></i>
                    <span>Current Plan</span>
                </div>
                <div class="plan-icon">
                    <i class="fas fa-map"></i>
                </div>
                <h3 class="plan-name">Free</h3>
                <div class="plan-price">
                    <span class="price-amount">₱0</span>
                    <span class="price-period">/month</span>
                </div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> Search destinations, flights & hotels</li>
                    <li><i class="fas fa-check"></i> Simple itineraries</li>
                    <li><i class="fas fa-check"></i> Limited travel guides</li>
                    <li><i class="fas fa-check"></i> Last 3 trips</li>
                    <li><i class="fas fa-check"></i> 1 calendar</li>
                </ul>
                <button class="plan-btn current-plan-btn">Current Plan</button>
            </div>
            
            <div class="plan-card traveler-plan">
                <div class="plan-icon">
                    <i class="fas fa-plane"></i>
                </div>
                <h3 class="plan-name">Traveler</h3>
                <div class="plan-price">
                    <span class="price-amount">₱250</span>
                    <span class="price-period">/month</span>
                </div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> AI recommendations</li>
                    <li><i class="fas fa-check"></i> Booking & cloud storage</li>
                    <li><i class="fas fa-check"></i> Multi-calendar</li>
                    <li><i class="fas fa-check"></i> Offline access</li>
                    <li><i class="fas fa-check"></i> Priority support</li>
                </ul>
                <button class="plan-btn upgrade-btn">Upgrade to Traveler</button>
            </div>
            
            <div class="plan-card premium-plan">
                <div class="plan-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <h3 class="plan-name">Premium</h3>
                <div class="plan-price">
                    <span class="price-amount">₱499</span>
                    <span class="price-period">/month</span>
                </div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> All Traveler features</li>
                    <li><i class="fas fa-check"></i> 24/7 concierge service</li>
                    <li><i class="fas fa-check"></i> Group travel (up to 10)</li>
                    <li><i class="fas fa-check"></i> Integrated travel insurance</li>
                    <li><i class="fas fa-check"></i> Priority support</li>
                </ul>
                <button class="plan-btn upgrade-btn">Upgrade to Premium</button>
            </div>
        </div>
        
        <!-- Help Section -->
        <div class="help-section">
            <div class="help-card">
                <h2 class="help-title">Need help choosing the right plan?</h2>
                <p class="help-description">Our travel experts are here to help you find the perfect plan for your adventures. All plans include secure cloud storage, mobile sync, and 30-day money-back guarantee.</p>
                
                <div class="benefits-grid">
                    <div class="benefit-item">
                        <div class="benefit-value blue">99.9%</div>
                        <div class="benefit-label">Uptime</div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-value green">24/7</div>
                        <div class="benefit-label">Support</div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-value purple">30</div>
                        <div class="benefit-label">Day trial</div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-value orange">50K+</div>
                        <div class="benefit-label">Happy travelers</div>
                    </div>
                </div>
                
                <div class="help-buttons">
                    <button class="btn-contact-sales">
                        Contact Sales Team
                    </button>
                    <button class="btn-compare-features">
                        Compare All Features
                    </button>
                </div>
                
                <div class="help-disclaimer">
                    No setup fees • Cancel anytime • 30-day money-back guarantee
                </div>
            </div>
        </div>
    </main>

    <script>
        // Dark mode is now handled by the global dark-mode.js script

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
    </script>
</body>
</html>
