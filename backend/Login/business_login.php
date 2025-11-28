<?php
session_start();

$error_message = '';
$success_message = '';

// Include functions
require_once '../Config/Database.php';
require_once '../Config/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        $business = authenticate_business($email, $password);
        if ($business) {
            $_SESSION['business_id'] = $business['id'];
            $_SESSION['user_type'] = 'business';
            $_SESSION['business_email'] = $business['email'];
            header('Location: Business/business_dashboard.php');
            exit();
        } else {
            $error_message = 'Invalid email or password.';
            // Show popup warning for non-existent account
            echo "<script>
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        showWarning('Business account not found. Please check your credentials or register as a new partner.', 'error', 'Oops!');
                    }, 500);
                });
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>NaviGo</title>
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="Access your NaviGo business partner portal and connect with millions of travelers worldwide.">
    <meta name="theme-color" content="#8b5cf6" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e1b4b" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NaviGo Business">
    <meta name="mobile-web-app-capable" content="yes">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="../js/manifest.json">
    
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="Assets/Images/icons/icon-180x180.png">
    <link rel="apple-touch-icon" sizes="152x152" href="Assets/Images/icons/icon-144x144.png">
    <link rel="apple-touch-icon" sizes="180x180" href="Assets/Images/icons/icon-180x180.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="Assets/Images/icons/icon-48x48.png">
    <link rel="shortcut icon" href="Assets/Images/icons/icon-48x48.png">
    
    <!-- External Resources -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/afed286f48.js" crossorigin="anonymous"></script>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/business_login.css">
    <link rel="stylesheet" href="../css/warningPanel.css">
    
    <!-- Scripts -->
    <script src="../js/dark-mode.js"></script>
    <script src="../js/mobile-features.js"></script>
    <script src="../js/warningPanel.js"></script>
</head>
<body class="login-page">
    <!-- Dark Mode Toggle Button -->
    <button class="dark-mode-toggle">
        <i class="fas fa-moon"></i>
    </button>
    
    <div class="container">
        <!-- Back Button -->
        <button class="back-button" onclick="window.location.href='../Index.php'">
            <i class="fas fa-arrow-left"></i>
        </button>

        <!-- Dark Mode Toggle -->
        <button class="dark-mode-toggle">
            <i id="dark-mode-icon" class="fas fa-moon"></i>
        </button>

        <div class="login-container">
            <!-- Left Content -->
            <div class="login-content">
                <div class="logo">
                    <div class="logo-container">
                        <img src="Assets/Images/NaviGo_Logo.png" alt="NaviGo Logo" class="logo-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display: none; font-size: 2rem; font-weight: bold; color: #7c3aed;">NaviGo</div>
                    </div>
                </div>
                <h1 class="login-title">
                    <span class="partnership">Your Partnership</span> <span class="starts">Starts Here</span>
                </h1>
                <h2 class="login-subtitle">Welcome to NaviGo</h2>
                <p class="login-description">
                    Join NaviGo's trusted partner network and unlock new opportunities to grow your business and reach millions of travelers worldwide.
                </p>

                <!-- Feature Icons -->
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Grow Your Business</h4>
                            <p>Access new customer segments</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Bank-Grade Security</h4>
                            <p>Enterprise-level protection</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Analytics Dashboard</h4>
                            <p>Detailed business insights</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Seamless API Access</h4>
                            <p>Easy integration and setup</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Login Panel -->
            <div class="login-panel">
                <div class="panel-header">
                    <h3>PARTNER ACCESS</h3>
                    <h4>Business Portal</h4>
                    <p>Join our trusted partner network</p>
                </div>

                <div class="login-form-container">
                    <h5 id="form-title">Partner Access</h5>
                    <p id="form-description">Sign in to your business portal</p>
                    
                    <!-- Toggle Buttons -->
                    <div class="form-toggle">
                        <button type="button" class="toggle-btn active" onclick="showSignIn()">Sign In</button>
                        <button type="button" class="toggle-btn" onclick="showRegister()">Register</button>
                    </div>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Sign In Form -->
                    <form method="POST" action="business_login.php" class="login-form" id="signin-form">
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-briefcase"></i> Business Email
                            </label>
                            <input type="email" id="email" name="email" required 
                                   placeholder="your-business@company.com"
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" id="password" name="password" required
                                   placeholder="Enter your password">
                        </div>

                        <button type="submit" class="btn-primary">
                            <i class="fas fa-handshake"></i> Begin Partnership
                        </button>
                    </form>

                    <!-- Register Form -->
                    <form method="POST" action="business_register.php" class="login-form" id="register-form" style="display: none;" onsubmit="return validateRegistrationForm()">
                        <div class="form-group">
                            <label for="business_name">Business Name</label>
                            <input type="text" id="business_name" name="business_name" required 
                                   placeholder="Your Business Name">
                        </div>

                        <div class="form-group">
                            <label for="business_type">Business Type</label>
                            <select id="business_type" name="business_type" required>
                                <option value="">Select business type</option>
                                <option value="Airlines">Airlines</option>
                                <option value="Hotels">Hotels</option>
                                <option value="transport">Transportation</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">Contact First Name</label>
                                <input type="text" id="first_name" name="first_name" required 
                                       placeholder="John">
                            </div>

                            <div class="form-group">
                                <label for="last_name">Contact Last Name</label>
                                <input type="text" id="last_name" name="last_name" required 
                                       placeholder="Doe">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reg_email">
                                <i class="fas fa-envelope"></i> Business Email
                            </label>
                            <input type="email" id="reg_email" name="email" required 
                                   placeholder="contact@yourbusiness.com">
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                <i class="fas fa-phone"></i> Phone Number
                            </label>
                            <input type="tel" id="phone" name="phone" required
                                   placeholder="+1 (555) 123-4567">
                        </div>

                        <div class="form-group">
                            <label for="reg_password">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" id="reg_password" name="password" required
                                   placeholder="Create a strong password">
                            <div class="password-warning" id="reg-password-warning" style="display: none;">
                                <i class="fas fa-exclamation-circle"></i>
                                <span id="reg-password-warning-text">Password must be at least 8 characters with uppercase, lowercase, and number.</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">
                                <i class="fas fa-lock"></i> Confirm Password
                            </label>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                   placeholder="Repeat your password">
                            <div class="password-warning" id="confirm-password-warning" style="display: none;">
                                <i class="fas fa-exclamation-circle"></i>
                                <span id="confirm-password-warning-text">Passwords do not match.</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary">
                            <i class="fas fa-rocket"></i> Launch Partnership
                        </button>
                    </form>

                    <div class="login-links">
                        <a href="forgot-password.php">Forgot your password?</a>
                    </div>

                    <div class="social-login">
                        <div class="divider">
                            <span>QUICK ACCESS</span>
                        </div>
                        
                        <div class="social-buttons">
                            <button class="btn-social">
                                <i class="fab fa-google"></i>
                                Google
                            </button>
                            <button class="btn-social">
                                <i class="fab fa-microsoft"></i>
                                Microsoft
                            </button>
                        </div>
                    </div>

                    <div class="legal-links">
                        By registering, you agree to our <a href="terms.php">Partner Terms</a> and <a href="privacy.php">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Notification function
        function showNotification(message, type = 'info') {
            // Create modal overlay
            const modal = document.createElement('div');
            modal.className = 'notification-modal-overlay';
            modal.innerHTML = `
                <div class="notification-modal">
                    <div class="notification-header">
                        <div class="notification-icon ${type}">
                            <i class="fas fa-${getNotificationIcon(type)}"></i>
                        </div>
                        <button class="notification-close" onclick="closeNotification()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="notification-body">
                        <h3 class="notification-title">${getNotificationTitle(type)}</h3>
                        <p class="notification-message">${message}</p>
                    </div>
                    <div class="notification-actions">
                        <button class="btn-primary" onclick="closeNotification()">Got it</button>
                    </div>
                </div>
            `;

            // Add to page
            document.body.appendChild(modal);

            // Auto remove after 8 seconds
            setTimeout(() => {
                if (modal.parentElement) {
                    closeNotification();
                }
            }, 8000);
        }

        // Close notification function
        function closeNotification() {
            const modal = document.querySelector('.notification-modal-overlay');
            if (modal) {
                modal.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => modal.remove(), 300);
            }
        }

        // Get notification title based on type
        function getNotificationTitle(type) {
            const titles = {
                success: 'Success!',
                error: 'Oops!',
                info: 'Information',
                warning: 'Warning'
            };
            return titles[type] || titles.info;
        }

        // Notification icon function
        function getNotificationIcon(type) {
            const icons = {
                success: 'check-circle',
                error: 'exclamation-circle',
                info: 'info-circle',
                warning: 'exclamation-triangle'
            };
            return icons[type] || icons.info;
        }

        // Dark mode is now handled by js/dark-mode.js

        // Form switching functions
        function showSignIn() {
            document.getElementById('signin-form').style.display = 'block';
            document.getElementById('register-form').style.display = 'none';
            document.getElementById('form-title').textContent = 'Partner Access';
            document.getElementById('form-description').textContent = 'Sign in to your business portal';
            
            // Update button states
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }
        
        function showRegister() {
            document.getElementById('signin-form').style.display = 'none';
            document.getElementById('register-form').style.display = 'block';
            document.getElementById('form-title').textContent = 'Partner Registration';
            document.getElementById('form-description').textContent = 'Connect with millions of travelers';
            
            // Update button states
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }

        // Password validation function
        function validatePassword(password) {
            const minLength = 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumbers = /\d/.test(password);
            
            if (password.length < minLength) {
                return { valid: false, message: 'Password must be at least 8 characters long.' };
            }
            if (!hasUpperCase) {
                return { valid: false, message: 'Password must contain at least one uppercase letter.' };
            }
            if (!hasLowerCase) {
                return { valid: false, message: 'Password must contain at least one lowercase letter.' };
            }
            if (!hasNumbers) {
                return { valid: false, message: 'Password must contain at least one number.' };
            }
            return { valid: true, message: '' };
        }

        // Show password warning
        function showPasswordWarning(inputId, warningId, textId, message) {
            const input = document.getElementById(inputId);
            const warning = document.getElementById(warningId);
            const text = document.getElementById(textId);
            
            text.textContent = message;
            warning.classList.add('show');
            input.style.borderColor = '#dc2626';
        }

        // Hide password warning
        function hidePasswordWarning(inputId, warningId) {
            const input = document.getElementById(inputId);
            const warning = document.getElementById(warningId);
            
            warning.classList.remove('show');
            input.style.borderColor = '';
        }

        // Validate registration form
        function validateRegistrationForm() {
            const password = document.getElementById('reg_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const validation = validatePassword(password);
            
            // Validate password strength
            if (password.length > 0 && !validation.valid) {
                showPasswordWarning('reg_password', 'reg-password-warning', 'reg-password-warning-text', validation.message);
                return false;
            }
            
            // Validate password confirmation
            if (confirmPassword.length > 0 && password !== confirmPassword) {
                showPasswordWarning('confirm_password', 'confirm-password-warning', 'confirm-password-warning-text', 'Passwords do not match.');
                return false;
            }
            
            // Clear warnings if validation passes
            hidePasswordWarning('reg_password', 'reg-password-warning');
            hidePasswordWarning('confirm_password', 'confirm-password-warning');
            
            return true;
        }

        // Dark mode initialization is now handled by js/dark-mode.js
        
        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('../js/service-worker.js')
                    .then((registration) => {
                        console.log('Service Worker registered successfully:', registration.scope);
                    })
                    .catch((error) => {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }
        
        // Mobile-specific enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Add mobile class to body if on mobile
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768) {
                document.body.classList.add('mobile-device');
            }
            
            // Password validation for registration form
            const regPassword = document.getElementById('reg_password');
            const confirmPassword = document.getElementById('confirm_password');
            
            if (regPassword) {
                regPassword.addEventListener('input', function() {
                    const validation = validatePassword(this.value);
                    if (this.value.length > 0 && !validation.valid) {
                        showPasswordWarning('reg_password', 'reg-password-warning', 'reg-password-warning-text', validation.message);
                    } else {
                        hidePasswordWarning('reg_password', 'reg-password-warning');
                    }
                    
                    // Also check confirm password if it has a value
                    if (confirmPassword && confirmPassword.value.length > 0) {
                        if (this.value !== confirmPassword.value) {
                            showPasswordWarning('confirm_password', 'confirm-password-warning', 'confirm-password-warning-text', 'Passwords do not match.');
                        } else {
                            hidePasswordWarning('confirm_password', 'confirm-password-warning');
                        }
                    }
                });
            }
            
            if (confirmPassword) {
                confirmPassword.addEventListener('input', function() {
                    const password = regPassword.value;
                    if (this.value.length > 0 && password !== this.value) {
                        showPasswordWarning('confirm_password', 'confirm-password-warning', 'confirm-password-warning-text', 'Passwords do not match.');
                    } else {
                        hidePasswordWarning('confirm_password', 'confirm-password-warning');
                    }
                });
            }
            
            // Optimize form inputs for mobile
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                // Prevent zoom on focus for iOS
                input.addEventListener('focus', function() {
                    if (this.type === 'email' || this.type === 'password' || this.type === 'text' || this.type === 'tel') {
                        this.style.fontSize = '16px';
                    }
                });
                
                // Add touch feedback
                input.addEventListener('touchstart', function() {
                    this.classList.add('touch-active');
                }, { passive: true });
                
                input.addEventListener('touchend', function() {
                    setTimeout(() => {
                        this.classList.remove('touch-active');
                    }, 150);
                }, { passive: true });
            });
            
            // Add swipe gesture for form switching on mobile
            let startX = 0;
            let startY = 0;
            
            document.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            }, { passive: true });
            
            document.addEventListener('touchend', function(e) {
                const endX = e.changedTouches[0].clientX;
                const endY = e.changedTouches[0].clientY;
                const diffX = startX - endX;
                const diffY = startY - endY;
                
                // Check if it's a horizontal swipe
                if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                    if (diffX > 0) {
                        // Swipe left - show sign in
                        showSignIn();
                    } else {
                        // Swipe right - show register
                        showRegister();
                    }
                }
            }, { passive: true });
        });
    </script>
</body>
</html>