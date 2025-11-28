<?php
session_start();
require_once '../../Config/Database.php';
require_once '../../Config/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../Login/user_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user information
$user_info = get_user_complete_profile($user_id);
if (!$user_info) {
    header('Location: ../../Login/user_login.php');
    exit();
}

// Set user name for navbar
$user_name = $user_info['first_name'] . ' ' . $user_info['last_name'];

// Get security settings
$security_settings = get_user_security_settings($user_id);
if (!$security_settings) {
    $security_settings = [];
}

// Ensure all required keys exist with default values
$default_settings = [
    'two_factor_enabled' => false,
    'login_alerts' => true,
    'password_expiry' => false,
    'device_trust' => true,
    'data_sharing' => false,
    'profile_visibility' => 'private',
    'anonymous_usage' => false,
    'analytics_performance' => true,
    'marketing_communications' => false,
    'security_alerts' => true,
    'account_updates' => true,
    'system_maintenance' => false,
    'feature_updates' => true,
    'session_timeout' => 30
];

// Merge with defaults to ensure all keys exist
$security_settings = array_merge($default_settings, $security_settings);

// Get active sessions
$active_sessions = get_active_sessions($user_id);

// Get login history
$login_history = get_login_history($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Account Settings - NaviGo</title>
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="Manage your NaviGo account settings. Update security preferences, notifications, and privacy settings.">
    <meta name="theme-color" content="#0891b2" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NaviGo Settings">
    <meta name="mobile-web-app-capable" content="yes">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="../../js/manifest.json">
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="../../Assets/Images/icons/icon-48x48.png">
    <link rel="shortcut icon" href="../../Assets/Images/icons/icon-48x48.png">
    <link rel="apple-touch-icon" href="../../Assets/Images/icons/icon-180x180.png">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../../css/account_settings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
                    <img src="../../Assets/Images/NaviGo_Logo.png" alt="NaviGo Logo" class="logo-image">
                </div>
                <a href="../../User/user_landingpage.php" class="page-title">
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-links">
                <a href="../../User/My_trips.php" class="nav-link my-trips">
                    <i class="fas fa-suitcase"></i>
                    <span>My Trips</span>
                </a>
                <a href="../../User/Explore.php" class="nav-link explore">
                    <i class="fas fa-compass"></i>
                    <span>Explore</span>
                </a>
                <a href="../../User/History.php" class="nav-link history">
                    <i class="fas fa-clock"></i>
                    <span>History</span>
                </a>
                <a href="../../User/Plan.php" class="nav-link plan">
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
                        <img src="../Assets/Images/NaviGo_Logo.png" alt="Profile" class="profile-logo">
                    </div>
                    <div class="profile-info">
                        <div class="profile-name"><?php echo htmlspecialchars($user_name); ?></div>
                        <div class="plan-badge free">Free</div>
                    </div>
                </div>
                
                <div class="profile-menu">
                    <a href="../../User/Menu/Profile.php" class="menu-item">
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
                    <a href="../../Login/logout.php" class="menu-item logout" onclick="return confirm('Are you sure you want to end your journey?')">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>End Journey</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="account-settings-container">
        <!-- Header -->
        <div class="account-header">
            <div class="header-content">
                <h1 class="account-title">Account Settings</h1>
                <p class="account-subtitle">Manage your account security and privacy settings</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-btn active" data-tab="security">
                <i class="fas fa-shield-alt"></i>
                Security
            </button>
            <button class="tab-btn" data-tab="privacy">
                <i class="fas fa-globe"></i>
                Privacy
            </button>
            <button class="tab-btn" data-tab="sessions">
                <i class="fas fa-desktop"></i>
                Sessions
            </button>
            <button class="tab-btn" data-tab="notifications">
                <i class="fas fa-bell"></i>
                Notifications
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content-container">
            <!-- Security Tab -->
            <div id="security-tab" class="tab-content active">
                <div class="security-layout">
                    <!-- Left Column -->
                    <div class="security-left">
                        <!-- Password Settings -->
                        <div class="settings-card">
                            <div class="card-header">
                                <i class="fas fa-key"></i>
                                <h3>Password Settings</h3>
                            </div>
                            <div class="password-form">
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <div class="password-input-container">
                                        <input type="password" id="current_password" placeholder="Enter current password">
                                        <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <div class="password-input-container">
                                        <input type="password" id="new_password" placeholder="Enter new password">
                                        <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength" id="password-strength">
                                        <div class="strength-bar">
                                            <div class="strength-fill" id="strength-fill"></div>
                                        </div>
                                        <div class="strength-text" id="strength-text">Enter a password</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password</label>
                                    <div class="password-input-container">
                                        <input type="password" id="confirm_password" placeholder="Confirm new password">
                                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-match" id="password-match"></div>
                                </div>
                                <button class="update-password-btn" onclick="updatePassword()">Update Password</button>
                                <div class="password-requirements">
                                    <h4>Password requirements:</h4>
                                    <ul>
                                        <li>At least 8 characters long</li>
                                        <li>Contains uppercase and lowercase letters</li>
                                        <li>Contains at least one number</li>
                                        <li>Contains at least one special character</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Security Preferences -->
                        <div class="settings-card">
                            <div class="card-header">
                                <i class="fas fa-lock"></i>
                                <h3>Security Preferences</h3>
                            </div>
                            <div class="preference-list">
                                <div class="preference-item">
                                    <div class="preference-info">
                                        <h4>Login Alerts</h4>
                                        <p>Get notified of new device logins</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="login_alerts" <?php echo $security_settings['login_alerts'] ? 'checked' : ''; ?>>
                                        <label for="login_alerts"></label>
                                    </div>
                                </div>
                                <div class="preference-item">
                                    <div class="preference-info">
                                        <h4>Password Expiry</h4>
                                        <p>Require password change every 90 days</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="password_expiry" <?php echo $security_settings['password_expiry'] ? 'checked' : ''; ?>>
                                        <label for="password_expiry"></label>
                                    </div>
                                </div>
                                <div class="preference-item">
                                    <div class="preference-info">
                                        <h4>Device Trust</h4>
                                        <p>Remember trusted devices for 30 days</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="device_trust" <?php echo $security_settings['device_trust'] ? 'checked' : ''; ?>>
                                        <label for="device_trust"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="security-right">
                        <!-- Two-Factor Authentication -->
                        <div class="settings-card">
                            <div class="card-header">
                                <i class="fas fa-shield-alt"></i>
                                <h3>Two-Factor Authentication</h3>
                            </div>
                            <div class="two-factor-section">
                                <div class="two-factor-item">
                                    <div class="two-factor-info">
                                        <h4>Authenticator App</h4>
                                        <p>Use an authenticator app for login verification</p>
                                    </div>
                                    <div class="two-factor-status">
                                        <span class="status-active">✓ Active</span>
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="two_factor" <?php echo $security_settings['two_factor_enabled'] ? 'checked' : ''; ?>>
                                            <label for="two_factor"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="two-factor-item">
                                    <div class="two-factor-info">
                                        <h4>Backup Codes</h4>
                                        <p>Use these codes if you lose access to your authenticator app</p>
                                    </div>
                                    <button class="view-codes-btn" onclick="viewBackupCodes()">View Backup Codes</button>
                                </div>
                                <div class="two-factor-item">
                                    <div class="two-factor-info">
                                        <h4>Recovery Options</h4>
                                        <p>Email: <?php echo htmlspecialchars($user_info['email']); ?></p>
                                    </div>
                                    <button class="update-email-btn" onclick="updateRecoveryEmail()">Update Recovery Email</button>
                                </div>
                            </div>
                        </div>

                        <!-- Session Timeout -->
                        <div class="settings-card">
                            <div class="card-header">
                                <i class="fas fa-clock"></i>
                                <h3>Session Timeout</h3>
                            </div>
                            <div class="session-timeout">
                                <div class="timeout-selector">
                                    <span id="timeout-display"><?php echo $security_settings['session_timeout']; ?> minutes</span>
                                    <i class="fas fa-chevron-down"></i>
                                    <select id="session_timeout" onchange="updateSessionTimeout()">
                                        <option value="15" <?php echo $security_settings['session_timeout'] == 15 ? 'selected' : ''; ?>>15 minutes</option>
                                        <option value="30" <?php echo $security_settings['session_timeout'] == 30 ? 'selected' : ''; ?>>30 minutes</option>
                                        <option value="60" <?php echo $security_settings['session_timeout'] == 60 ? 'selected' : ''; ?>>1 hour</option>
                                        <option value="120" <?php echo $security_settings['session_timeout'] == 120 ? 'selected' : ''; ?>>2 hours</option>
                                        <option value="480" <?php echo $security_settings['session_timeout'] == 480 ? 'selected' : ''; ?>>8 hours</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Privacy Tab -->
            <div id="privacy-tab" class="tab-content">
                <div class="privacy-container">
                    <div class="settings-card">
                        <div class="card-header">
                            <i class="fas fa-globe"></i>
                            <h3>Privacy Settings</h3>
                        </div>
                        <div class="privacy-sections">
                            <!-- Data Collection -->
                            <div class="privacy-section">
                                <h4>Data Collection</h4>
                                <div class="privacy-item">
                                    <div class="privacy-info">
                                        <h5>Anonymous Usage Data</h5>
                                        <p>Help improve NaviGo by sharing anonymous usage statistics</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="anonymous_usage" <?php echo $security_settings['anonymous_usage'] ? 'checked' : ''; ?>>
                                        <label for="anonymous_usage"></label>
                                    </div>
                                </div>
                                <div class="privacy-item">
                                    <div class="privacy-info">
                                        <h5>Analytics & Performance</h5>
                                        <p>Allow collection of performance and analytics data</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="analytics_performance" <?php echo $security_settings['analytics_performance'] ? 'checked' : ''; ?>>
                                        <label for="analytics_performance"></label>
                                    </div>
                                </div>
                                <div class="privacy-item">
                                    <div class="privacy-info">
                                        <h5>Marketing Communications</h5>
                                        <p>Receive personalized offers and recommendations</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="marketing_communications" <?php echo $security_settings['marketing_communications'] ? 'checked' : ''; ?>>
                                        <label for="marketing_communications"></label>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Visibility -->
                            <div class="privacy-section">
                                <h4>Profile Visibility</h4>
                                <div class="privacy-item">
                                    <div class="privacy-info">
                                        <h5>Who can see your profile</h5>
                                    </div>
                                    <div class="dropdown-selector">
                                        <span id="visibility-display"><?php echo ucfirst($security_settings['profile_visibility']); ?></span>
                                        <i class="fas fa-chevron-down"></i>
                                        <select id="profile_visibility" onchange="updateProfileVisibility()">
                                            <option value="private" <?php echo $security_settings['profile_visibility'] == 'private' ? 'selected' : ''; ?>>Only me</option>
                                            <option value="friends" <?php echo $security_settings['profile_visibility'] == 'friends' ? 'selected' : ''; ?>>Friends Only</option>
                                            <option value="public" <?php echo $security_settings['profile_visibility'] == 'public' ? 'selected' : ''; ?>>Public</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Management -->
                            <div class="privacy-section">
                                <h4>Data Management</h4>
                                <div class="data-management-buttons">
                                    <button class="data-btn" onclick="downloadMyData()">Download My Data</button>
                                    <button class="data-btn delete-btn" onclick="deleteAccount()">Delete Account</button>
                                </div>
                                <div class="delete-warning">
                                    <p>Account deletion is permanent and cannot be undone. All your data will be permanently removed.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sessions Tab -->
            <div id="sessions-tab" class="tab-content">
                <div class="sessions-layout">
                    <!-- Active Sessions -->
                    <div class="sessions-left">
                        <div class="settings-card">
                            <div class="card-header">
                                <i class="fas fa-desktop"></i>
                                <h3>Active Sessions</h3>
                            </div>
                            <div class="sessions-list">
                                <?php foreach ($active_sessions as $session): ?>
                                <div class="session-item">
                                    <div class="session-icon">
                                        <i class="fas fa-<?php echo $session['device_type'] == 'mobile' ? 'mobile-alt' : ($session['device_type'] == 'tablet' ? 'tablet-alt' : 'laptop'); ?>"></i>
                                    </div>
                                    <div class="session-info">
                                        <h4><?php echo htmlspecialchars($session['device_name']); ?></h4>
                                        <p><?php echo htmlspecialchars($session['location']); ?></p>
                                        <span class="session-time"><?php echo $session['is_current'] ? 'Active now' : $session['last_activity']; ?></span>
                                    </div>
                                    <div class="session-actions">
                                        <?php if ($session['is_current']): ?>
                                        <span class="current-badge">Current</span>
                                        <?php else: ?>
                                        <button class="revoke-btn" onclick="revokeSession(<?php echo $session['id']; ?>)">Revoke</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Login History -->
                    <div class="sessions-right">
                        <div class="settings-card">
                            <div class="card-header">
                                <i class="fas fa-history"></i>
                                <h3>Login History</h3>
                            </div>
                            <div class="history-list">
                                <?php foreach ($login_history as $login): ?>
                                <div class="history-item">
                                    <div class="history-info">
                                        <h4><?php echo htmlspecialchars($login['device_name']); ?></h4>
                                        <p><?php echo htmlspecialchars($login['location']); ?></p>
                                        <span class="history-time"><?php echo $login['login_time']; ?></span>
                                    </div>
                                    <div class="history-status">
                                        <span class="status-success">Success</span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div id="notifications-tab" class="tab-content">
                <div class="notifications-container">
                    <div class="settings-card">
                        <div class="card-header">
                            <i class="fas fa-bell"></i>
                            <h3>Account Notifications</h3>
                        </div>
                        <div class="notifications-sections">
                            <div class="notification-category">
                                <h4>Security & Account</h4>
                                <div class="notification-list">
                                    <div class="notification-item">
                                        <div class="notification-info">
                                            <h5>Security Alerts</h5>
                                            <p>Notifications about security-related events</p>
                                        </div>
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="security_alerts" <?php echo $security_settings['security_alerts'] ? 'checked' : ''; ?>>
                                            <label for="security_alerts"></label>
                                        </div>
                                    </div>
                                    <div class="notification-item">
                                        <div class="notification-info">
                                            <h5>Account Updates</h5>
                                            <p>Important changes to your account</p>
                                        </div>
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="account_updates" <?php echo $security_settings['account_updates'] ? 'checked' : ''; ?>>
                                            <label for="account_updates"></label>
                                        </div>
                                    </div>
                                    <div class="notification-item">
                                        <div class="notification-info">
                                            <h5>System Maintenance</h5>
                                            <p>Notifications about scheduled maintenance</p>
                                        </div>
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="system_maintenance" <?php echo $security_settings['system_maintenance'] ? 'checked' : ''; ?>>
                                            <label for="system_maintenance"></label>
                                        </div>
                                    </div>
                                    <div class="notification-item">
                                        <div class="notification-info">
                                            <h5>Feature Updates</h5>
                                            <p>Learn about new features and improvements</p>
                                        </div>
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="feature_updates" <?php echo $security_settings['feature_updates'] ? 'checked' : ''; ?>>
                                            <label for="feature_updates"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <div class="toast-content">
            <i class="fas fa-check-circle"></i>
            <span id="toast-message">Settings updated successfully</span>
        </div>
    </div>

    <!-- 2FA Setup Modal -->
    <div id="two-factor-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h3>Enable Two-Factor Authentication</h3>
                <button class="modal-close" onclick="close2FAModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="2fa-setup-content">
                    <!-- 2FA setup content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="password-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Change Password</h3>
                <button class="modal-close" onclick="closePasswordModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="passwordForm">
                    <div class="form-group">
                        <label for="modal_current_password">Current Password</label>
                        <input type="password" id="modal_current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="modal_new_password">New Password</label>
                        <div class="password-input-container">
                            <input type="password" id="modal_new_password" name="new_password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('modal_new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="modal-password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="modal-strength-fill"></div>
                            </div>
                            <div class="strength-text" id="modal-strength-text">Enter a password</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="modal_confirm_password">Confirm New Password</label>
                        <div class="password-input-container">
                            <input type="password" id="modal_confirm_password" name="confirm_password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('modal_confirm_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-match" id="modal-password-match"></div>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" onclick="closePasswordModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../../js/dark-mode.js"></script>
    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all tabs and content
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(tabId + '-tab').classList.add('active');
            });
        });

        // Auto-save functionality for toggles
        document.querySelectorAll('input[type="checkbox"]').forEach(toggle => {
            toggle.addEventListener('change', function() {
                saveSettings();
            });
        });

        // Auto-save functionality for selects
        document.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', function() {
                saveSettings();
            });
        });

        // Password strength checker
        document.getElementById('new_password').addEventListener('input', function() {
            checkPasswordStrength(this.value, 'password-strength');
        });

        document.getElementById('modal_new_password').addEventListener('input', function() {
            checkPasswordStrength(this.value, 'modal-password-strength');
        });

        // Password confirmation checker
        document.getElementById('confirm_password').addEventListener('input', function() {
            checkPasswordMatch();
        });

        document.getElementById('modal_confirm_password').addEventListener('input', function() {
            checkModalPasswordMatch();
        });

        // 2FA toggle
        document.getElementById('two_factor').addEventListener('change', function() {
            if (this.checked) {
                show2FASetupModal();
            } else {
                disable2FA();
            }
        });

        // Password form submission
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            changePassword();
        });

        function goBack() {
            window.history.back();
        }

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.parentElement.querySelector('.password-toggle i');
            
            if (field.type === 'password') {
                field.type = 'text';
                button.classList.remove('fa-eye');
                button.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                button.classList.remove('fa-eye-slash');
                button.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength(password, containerId) {
            const container = document.getElementById(containerId);
            const strengthFill = container.querySelector('.strength-fill');
            const strengthText = container.querySelector('.strength-text');
            
            let score = 0;
            let strength = 'weak';
            
            if (password.length >= 8) score += 1;
            if (/[a-z]/.test(password)) score += 1;
            if (/[A-Z]/.test(password)) score += 1;
            if (/[0-9]/.test(password)) score += 1;
            if (/[^A-Za-z0-9]/.test(password)) score += 1;
            
            if (score >= 4) strength = 'strong';
            else if (score >= 3) strength = 'good';
            else if (score >= 2) strength = 'fair';
            
            strengthFill.className = 'strength-fill ' + strength;
            strengthText.className = 'strength-text ' + strength;
            strengthText.textContent = strength.charAt(0).toUpperCase() + strength.slice(1) + ' password';
        }

        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirm = document.getElementById('confirm_password').value;
            const matchDiv = document.getElementById('password-match');
            
            if (confirm === '') {
                matchDiv.textContent = '';
                return;
            }
            
            if (password === confirm) {
                matchDiv.textContent = '✓ Passwords match';
                matchDiv.className = 'password-match match';
            } else {
                matchDiv.textContent = '✗ Passwords do not match';
                matchDiv.className = 'password-match no-match';
            }
        }

        function checkModalPasswordMatch() {
            const password = document.getElementById('modal_new_password').value;
            const confirm = document.getElementById('modal_confirm_password').value;
            const matchDiv = document.getElementById('modal-password-match');
            
            if (confirm === '') {
                matchDiv.textContent = '';
                return;
            }
            
            if (password === confirm) {
                matchDiv.textContent = '✓ Passwords match';
                matchDiv.className = 'password-match match';
            } else {
                matchDiv.textContent = '✗ Passwords do not match';
                matchDiv.className = 'password-match no-match';
            }
        }

        function updatePassword() {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (!currentPassword || !newPassword || !confirmPassword) {
                showToast('Please fill in all password fields', 'error');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                showToast('Passwords do not match', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('current_password', currentPassword);
            formData.append('new_password', newPassword);
            formData.append('confirm_password', confirmPassword);
            
            fetch('ajax/change_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Password updated successfully', 'success');
                    document.getElementById('current_password').value = '';
                    document.getElementById('new_password').value = '';
                    document.getElementById('confirm_password').value = '';
                } else {
                    showToast(data.message || 'Failed to update password', 'error');
                }
            })
            .catch(error => {
                showToast('An error occurred while updating password', 'error');
            });
        }

        function changePassword() {
            const currentPassword = document.getElementById('modal_current_password').value;
            const newPassword = document.getElementById('modal_new_password').value;
            const confirmPassword = document.getElementById('modal_confirm_password').value;
            
            if (!currentPassword || !newPassword || !confirmPassword) {
                showToast('Please fill in all password fields', 'error');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                showToast('Passwords do not match', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('current_password', currentPassword);
            formData.append('new_password', newPassword);
            formData.append('confirm_password', confirmPassword);
            
            fetch('ajax/change_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Password updated successfully', 'success');
                    closePasswordModal();
                } else {
                    showToast(data.message || 'Failed to update password', 'error');
                }
            })
            .catch(error => {
                showToast('An error occurred while updating password', 'error');
            });
        }

        function saveSettings() {
            const settings = {
                login_alerts: document.getElementById('login_alerts').checked,
                password_expiry: document.getElementById('password_expiry').checked,
                device_trust: document.getElementById('device_trust').checked,
                two_factor_enabled: document.getElementById('two_factor').checked,
                anonymous_usage: document.getElementById('anonymous_usage').checked,
                analytics_performance: document.getElementById('analytics_performance').checked,
                marketing_communications: document.getElementById('marketing_communications').checked,
                profile_visibility: document.getElementById('profile_visibility').value,
                security_alerts: document.getElementById('security_alerts').checked,
                account_updates: document.getElementById('account_updates').checked,
                system_maintenance: document.getElementById('system_maintenance').checked,
                feature_updates: document.getElementById('feature_updates').checked,
                session_timeout: document.getElementById('session_timeout').value
            };
            
            fetch('ajax/update_security_settings.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(settings)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Settings updated successfully', 'success');
                } else {
                    showToast('Failed to update settings', 'error');
                }
            })
            .catch(error => {
                showToast('An error occurred while updating settings', 'error');
            });
        }

        function updateSessionTimeout() {
            const timeout = document.getElementById('session_timeout').value;
            document.getElementById('timeout-display').textContent = timeout + ' minutes';
            saveSettings();
        }

        function updateProfileVisibility() {
            const visibility = document.getElementById('profile_visibility').value;
            document.getElementById('visibility-display').textContent = visibility.charAt(0).toUpperCase() + visibility.slice(1);
            saveSettings();
        }

        function show2FASetupModal() {
            document.getElementById('two-factor-modal').style.display = 'flex';
            start2FASetup();
        }

        function close2FAModal() {
            document.getElementById('two-factor-modal').style.display = 'none';
            document.getElementById('two_factor').checked = false;
        }

        function start2FASetup() {
            fetch('ajax/setup_2fa.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    get2FAQRCode(data.secret);
                } else {
                    showToast('Failed to setup 2FA', 'error');
                    document.getElementById('two_factor').checked = false;
                }
            })
            .catch(error => {
                showToast('An error occurred while setting up 2FA', 'error');
                document.getElementById('two_factor').checked = false;
            });
        }

        function get2FAQRCode(secret) {
            const content = `
                <div class="fa-step">
                    <h4>Step 1: Install an Authenticator App</h4>
                    <p>Download and install an authenticator app like Google Authenticator, Authy, or Microsoft Authenticator on your mobile device.</p>
                </div>
                <div class="fa-step">
                    <h4>Step 2: Scan QR Code</h4>
                    <p>Scan this QR code with your authenticator app:</p>
                    <div class="qr-code-container">
                        <div class="qr-code-placeholder">QR Code will be generated here</div>
                    </div>
                    <p class="manual-entry-text">Or enter this code manually:</p>
                    <div class="manual-entry-code">${secret}</div>
                </div>
                <div class="fa-step">
                    <h4>Step 3: Enter Verification Code</h4>
                    <p>Enter the 6-digit code from your authenticator app:</p>
                    <input type="text" id="verification_code" class="verification-input" placeholder="000000" maxlength="6">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="close2FAModal()">Cancel</button>
                    <button type="button" class="btn-primary" id="verify-2fa-btn" onclick="verify2FACode()">Verify & Enable</button>
                </div>
            `;
            
            document.getElementById('2fa-setup-content').innerHTML = content;
        }

        function verify2FACode() {
            const code = document.getElementById('verification_code').value;
            
            if (!code || code.length !== 6) {
                showToast('Please enter a valid 6-digit code', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('code', code);
            
            fetch('ajax/setup_2fa.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('2FA enabled successfully!', 'success');
                    close2FAModal();
                } else {
                    showToast(data.message || 'Invalid verification code', 'error');
                }
            })
            .catch(error => {
                showToast('An error occurred while verifying 2FA', 'error');
            });
        }

        function disable2FA() {
            if (!confirm('Are you sure you want to disable 2FA? This will make your account less secure.')) {
                document.getElementById('two_factor').checked = true;
                return;
            }
            
            fetch('ajax/setup_2fa.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({action: 'disable'})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('2FA disabled successfully', 'success');
                } else {
                    showToast('Failed to disable 2FA', 'error');
                    document.getElementById('two_factor').checked = true;
                }
            })
            .catch(error => {
                showToast('An error occurred while disabling 2FA', 'error');
                document.getElementById('two_factor').checked = true;
            });
        }

        function viewBackupCodes() {
            showToast('Backup codes feature coming soon', 'info');
        }

        function updateRecoveryEmail() {
            showToast('Update recovery email feature coming soon', 'info');
        }

        function downloadMyData() {
            showToast('Download data feature coming soon', 'info');
        }

        function deleteAccount() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                showToast('Account deletion feature coming soon', 'info');
            }
        }

        function revokeSession(sessionId) {
            if (confirm('Are you sure you want to revoke this session?')) {
                fetch('ajax/revoke_session.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({session_id: sessionId})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Session revoked successfully', 'success');
                        location.reload();
                    } else {
                        showToast('Failed to revoke session', 'error');
                    }
                })
                .catch(error => {
                    showToast('An error occurred while revoking session', 'error');
                });
            }
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = toast.querySelector('i');
            
            toastMessage.textContent = message;
            
            if (type === 'error') {
                toast.classList.add('toast-error');
                toastIcon.className = 'fas fa-exclamation-circle';
            } else if (type === 'info') {
                toast.classList.remove('toast-error');
                toastIcon.className = 'fas fa-info-circle';
            } else {
                toast.classList.remove('toast-error');
                toastIcon.className = 'fas fa-check-circle';
            }
            
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        function closePasswordModal() {
            document.getElementById('password-modal').style.display = 'none';
        }

        // Profile dropdown functionality
        function toggleProfileMenu() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const userProfile = document.querySelector('.user-profile');
            
            if (!userProfile.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
</body>
</html>
