<?php
session_start();

$error_message = '';
$success_message = '';

// Check for registration error messages
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
}

// Include functions
require_once '../Config/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        $user = authenticate_user($email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = 'personal';
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            
            // Debug: Check if session is set
            error_log("Login successful for user: " . $user['email']);
            error_log("Session user_id: " . $_SESSION['user_id']);
            
            // Redirect to user landing page
            header('Location: ../User/user_landingpage.php');
            exit();
        } else {
            $error_message = 'Invalid email or password. Please check your credentials or register for a new account.';
            // Show popup warning for non-existent account
            echo "<script>
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        showWarning('Account does not exist! Please check your email or create a new account.', 'error', 'Oops!');
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
    <meta name="description" content="Sign in to your NaviGo account and continue your travel journey with intelligent planning and seamless experiences.">
    <meta name="theme-color" content="#8b5cf6" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e1b4b" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NaviGo Login">
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
    <link rel="stylesheet" href="../css/user_login.css">
    <link rel="stylesheet" href="../css/warningPanel.css">
    
    <!-- Scripts -->
    <script src="../js/dark-mode.js"></script>
    <script src="../js/mobile-features.js"></script>
    <script src="../js/warningPanel.js"></script>
</head>
<body class="login-page">
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
                        <div style="display: none; font-size: 2rem; font-weight: bold; color: #0891b2;">NaviGo</div>
                    </div>
                </div>
                <h1 class="login-title">
                    <span class="journey">Your Journey</span> <span class="starts">Starts Here</span>
                </h1>
                <h2 class="login-subtitle">Welcome to NaviGo</h2>
                <p class="login-description">
                    Your intelligent travel companion for seamless trip planning, cost optimization, 
                    and collaborative group adventures that create lasting memories.
                </p>

                <!-- Feature Icons -->
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="feature-content">
                            <h4>AI-Powered Planning</h4>
                            <p>Smart recommendations tailored for you</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Secure & Synced</h4>
                            <p>All your adventures in one place</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Group Adventures</h4>
                            <p>Collaborate and explore together</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-map-pin"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Smart Savings</h4>
                            <p>Optimize costs on every journey</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Login Panel -->
            <div class="login-panel">
                <div class="panel-header">
                    <h3>NAVIGO ACCESS</h3>
                    <h4>Your Travel Portal</h4>
                    <p>Access your personalized journey hub</p>
                </div>

                <div class="login-form-container">
                    <h5>Access Your Journey</h5>
                    <p id="form-description">Sign in to continue your travel story</p>
                    
                    <!-- Toggle Buttons -->
                    <div class="form-toggle">
                        <button type="button" class="toggle-btn active" onclick="showSignIn()">Sign In</button>
                        <button type="button" class="toggle-btn" onclick="showNewJourney()">New Journey</button>
                    </div>
                    

                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Sign In Form -->
                    <form method="POST" action="user_login.php" class="login-form" id="signin-form" onsubmit="return validateSignInForm()">
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-map-pin"></i> Email Address
                            </label>
                            <input type="email" id="email" name="email" required 
                                   placeholder="traveler@example.com"
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-shield-alt"></i> Password
                            </label>
                            <input type="password" id="password" name="password" required
                                   placeholder="Your secure password">
                            <div class="password-warning" id="password-warning" style="display: none;">
                                <i class="fas fa-exclamation-circle"></i>
                                <span id="password-warning-text">Password must be at least 8 characters with uppercase, lowercase, and number.</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary">
                            <i class="fas fa-plane"></i> Begin Journey
                        </button>
                    </form>

                    <!-- New Journey Form -->
                    <form method="POST" action="user_register.php" class="login-form" id="newjourney-form" style="display: none;" onsubmit="return validateRegistrationForm()">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="text" id="firstname" name="firstname" required 
                                       placeholder="Explorer">
                            </div>

                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" id="lastname" name="lastname" required 
                                       placeholder="Adventurer">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reg_email">
                                <i class="fas fa-globe"></i> Email Address
                            </label>
                            <input type="email" id="reg_email" name="email" required 
                                   placeholder="explorer@wanderlust.com">
                        </div>

                        <div class="form-group">
                            <label for="reg_password">
                                <i class="fas fa-shield-alt"></i> Secure Password
                            </label>
                            <input type="password" id="reg_password" name="password" required
                                   placeholder="Create your travel key">
                            <div class="password-warning" id="reg-password-warning" style="display: none;">
                                <i class="fas fa-exclamation-circle"></i>
                                <span id="reg-password-warning-text">Password must be at least 8 characters with uppercase, lowercase, and number.</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">
                                <i class="fas fa-shield-alt"></i> Confirm Password
                            </label>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                   placeholder="Repeat your travel key">
                            <div class="password-warning" id="confirm-password-warning" style="display: none;">
                                <i class="fas fa-exclamation-circle"></i>
                                <span id="confirm-password-warning-text">Passwords do not match.</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary">
                            <i class="fas fa-compass"></i> Start Adventure
                        </button>
                    </form>

                    <div class="login-links">
                        <a href="forgot-password.php">Lost your travel pass?</a>
                    </div>

                    <div class="social-login">
                        <div class="divider">
                            <span>QUICK ACCESS</span>
                        </div>
                        
                        <div class="social-buttons">
                            <button class="btn-social" onclick="signInWithGoogle()">
                                <i class="fab fa-google"></i>
                                Google
                            </button>
                            <button class="btn-social" onclick="signInWithMicrosoft()">
                                <i class="fab fa-microsoft"></i>
                                Microsoft
                            </button>
                        </div>
                    </div>

                    <div class="legal-links">
                        By continuing your journey, you agree to our <a href="terms.php">Terms of Service</a> and <a href="privacy.php">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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

        // Example usage
        // showNotification('Welcome to NaviGo!', 'success');
        
        // Tab switching functions
        function showSignIn() {
            document.getElementById('signin-form').style.display = 'block';
            document.getElementById('newjourney-form').style.display = 'none';
            document.getElementById('form-description').textContent = 'Sign in to continue your travel story';
            
            // Update button states
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }
        
        function showNewJourney() {
            document.getElementById('signin-form').style.display = 'none';
            document.getElementById('newjourney-form').style.display = 'block';
            document.getElementById('form-description').textContent = 'Create your account to start your adventure';
            
            // Update button states
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }
        
        // Dark mode is now handled by js/dark-mode.js
        
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
            warning.style.display = 'block';
            input.style.borderColor = '#dc2626';
        }

        // Hide password warning
        function hidePasswordWarning(inputId, warningId) {
            const input = document.getElementById(inputId);
            const warning = document.getElementById(warningId);
            
            warning.style.display = 'none';
            input.style.borderColor = '';
        }

        // Validate sign-in form - REMOVED (no password validation for sign-in)
        function validateSignInForm() {
            return true;
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

        // Mobile-specific enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Add mobile class to body if on mobile
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768) {
                document.body.classList.add('mobile-device');
            }
            
            // Password validation for sign-in form - REMOVED (only show on New Journey)

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
                    if (this.type === 'email' || this.type === 'password' || this.type === 'text') {
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
                        // Swipe right - show new journey
                        showNewJourney();
                    }
                }
            }, { passive: true });
        });

        // Social Login Functions
        function signInWithGoogle() {
            // Google OAuth 2.0 implementation
            const clientId = 'YOUR_GOOGLE_CLIENT_ID'; // Replace with your actual Google Client ID
            const redirectUri = window.location.origin + '/auth/google-callback.php';
            const scope = 'openid email profile';
            
            const authUrl = `https://accounts.google.com/o/oauth2/v2/auth?` +
                `client_id=${clientId}&` +
                `redirect_uri=${encodeURIComponent(redirectUri)}&` +
                `scope=${encodeURIComponent(scope)}&` +
                `response_type=code&` +
                `access_type=offline&` +
                `prompt=consent`;
            
            // Open Google OAuth in popup window
            const popup = window.open(authUrl, 'googleAuth', 'width=500,height=600,scrollbars=yes,resizable=yes');
            
            // Listen for the popup to close and handle the result
            const checkClosed = setInterval(() => {
                if (popup.closed) {
                    clearInterval(checkClosed);
                    // You can add additional handling here if needed
                }
            }, 1000);
        }

        function signInWithMicrosoft() {
            // Microsoft OAuth 2.0 implementation
            const clientId = 'YOUR_MICROSOFT_CLIENT_ID'; // Replace with your actual Microsoft Client ID
            const redirectUri = window.location.origin + '/auth/microsoft-callback.php';
            const scope = 'openid email profile';
            
            const authUrl = `https://login.microsoftonline.com/common/oauth2/v2.0/authorize?` +
                `client_id=${clientId}&` +
                `redirect_uri=${encodeURIComponent(redirectUri)}&` +
                `scope=${encodeURIComponent(scope)}&` +
                `response_type=code&` +
                `response_mode=query&` +
                `prompt=consent`;
            
            // Open Microsoft OAuth in popup window
            const popup = window.open(authUrl, 'microsoftAuth', 'width=500,height=600,scrollbars=yes,resizable=yes');
            
            // Listen for the popup to close and handle the result
            const checkClosed = setInterval(() => {
                if (popup.closed) {
                    clearInterval(checkClosed);
                    // You can add additional handling here if needed
                }
            }, 1000);
        }

        // Alternative implementation using Google Identity Services (newer method)
        function initializeGoogleSignIn() {
            // Load Google Identity Services
            if (typeof google !== 'undefined' && google.accounts) {
                google.accounts.id.initialize({
                    client_id: 'YOUR_GOOGLE_CLIENT_ID', // Replace with your actual Google Client ID
                    callback: handleGoogleResponse
                });
            }
        }

        function handleGoogleResponse(response) {
            // Handle the Google OAuth response
            console.log('Google OAuth Response:', response);
            
            // Send the response to your server for verification
            fetch('auth/google-verify.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ credential: response.credential })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to landing page on successful authentication
                    window.location.href = 'User/user_landingpage.php';
                } else {
                    showNotification('Google sign-in failed: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Google sign-in failed. Please try again.', 'error');
            });
        }

        // Initialize Google Sign-In when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Load Google Identity Services script
            const script = document.createElement('script');
            script.src = 'https://accounts.google.com/gsi/client';
            script.onload = initializeGoogleSignIn;
            document.head.appendChild(script);
        });
    </script>
</body>
</html>
