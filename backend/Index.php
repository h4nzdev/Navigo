<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>NaviGo</title>
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="Your intelligent travel planning and adventure companion. Plan, explore, and discover amazing destinations with NaviGo.">
    <meta name="theme-color" content="#8b5cf6" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e1b4b" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NaviGo">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="msapplication-TileColor" content="#8b5cf6">
    <meta name="msapplication-config" content="browserconfig.xml">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="js/manifest.json">
    
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="Assets/Images/icons/icon-180x180.png">
    <link rel="apple-touch-icon" sizes="152x152" href="Assets/Images/icons/icon-144x144.png">
    <link rel="apple-touch-icon" sizes="180x180" href="Assets/Images/icons/icon-180x180.png">
    <link rel="apple-touch-icon" sizes="167x167" href="Assets/Images/icons/icon-144x144.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="Assets/Images/icons/icon-48x48.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Assets/Images/icons/icon-48x48.png">
    <link rel="shortcut icon" href="Assets/Images/icons/icon-48x48.png">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/Index.css">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/afed286f48.js" crossorigin="anonymous"></script>
    
    <!-- Scripts -->
    <script src="js/dark-mode.js"></script>
    <script src="js/mobile-features.js"></script>
</head>
<body>
    <!-- Dark Mode Toggle Button -->
    <button class="dark-mode-toggle">
        <i class="fas fa-moon"></i>
    </button>
    
    <div class="container">
        <!-- Header Section -->
        <header class="header">
            <div class="logo">
                <div class="logo-container">
                    <img src="Assets/Images/NaviGo_Logo.png" alt="NaviGo Logo" class="logo-image">
                </div>
            </div>
            
            <h1 class="hero-title">
                <span class="ready">Ready for Adventure?</span>
            </h1>
            
            <h2 class="hero-subtitle">Welcome to NaviGo</h2>
            
            <p class="hero-description">
                Your journey begins here. Choose your travel companion and unlock a world of intelligent planning, seamless experiences, and unforgettable adventures.
            </p>
        </header>

        <!-- Cards Container -->
        <div class="cards-container">
            <!-- Personal Account Card -->
            <div class="card personal-card fade-in slide-in-left" onclick="window.location.href='Login/user_login.php'" style="cursor: pointer;">
                <div class="card-header">
                    <div class="card-badge">
                        <div class="badge-icon">
                        <i class="fa-solid fa-suitcase"></i>
                        </div>
                        <div>
                            <div class="badge-text">Travel Pass</div>
                            <div class="badge-title">Personal Explorer</div>
                        </div>
                    </div>
                    <div class="card-type">
                        <div class="type-label">Type</div>
                        <div class="type-value">INDIVIDUAL</div>
                    </div>
                </div>
                
                <h3 class="card-title">Personal Account</h3>
                <p class="card-description">
                    Your personal gateway to intelligent travel planning and unforgettable adventures
                </p>
                
                <div class="card-features">
                    <ul class="features-list">
                        <li>AI-powered recommendations</li>
                        <li>Secure itinerary storage</li>
                        <li>Group collaboration</li>
                        <li>Cost optimization</li>
                        <li>Cross-platform sync</li>
                    </ul>
                </div>
                
                <div class="card-button">
                    <div class="button-text">
                        <span class="button-main">Start Your Journey</span>
                        <span class="button-sub">Free plan available</span>
                    </div>
                    <span class="button-arrow">→</span>
                </div>
            </div>

            <!-- Business Partner Card -->
            <div class="card business-card fade-in slide-in-right" onclick="window.location.href='Login/business_login.php'" style="cursor: pointer;">
                <div class="card-header">
                    <div class="card-badge">
                        <div class="badge-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <div class="badge-text">Partner Access</div>
                            <div class="badge-title">Business Elite</div>
                        </div>
                    </div>
                    <div class="card-type">
                        <div class="type-label">Type</div>
                        <div class="type-value">ENTERPRISE</div>
                    </div>
                </div>
                
                <h3 class="card-title">Business Partner</h3>
                <p class="card-description">
                    Connect with travelers worldwide and grow your business through our premium network
                </p>
                
                <div class="card-features">
                    <ul class="features-list">
                        <li>Booking & inventory management</li>
                        <li>Advanced analytics dashboard</li>
                        <li>System integrations</li>
                        <li>Bulk discount controls</li>
                        <li>Enterprise security</li>
                    </ul>
                </div>
                
                <div class="card-button">
                    <div class="button-text">
                        <span class="button-main">Join Our Network</span>
                        <span class="button-sub">Custom enterprise pricing</span>
                    </div>
                    <span class="button-arrow">→</span>
                </div>
            </div>
        </div>

        <!-- Sign-in Section -->
        <div class="signin-section">
            <div class="signin-box">
                <p class="signin-text">Already have an account?</p>
                <button class="signin-button">
                    Sign in to Your Journey
                    <span>→</span>
                </button>
            </div>
        </div>

        <!-- Quote Section -->
        <div class="quote-section">
            <p class="quote-text">
                "The world is a book, and those who do not travel read only one page." -Saint Augustine
            </p>
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
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <i class="fas fa-${getNotificationIcon(type)}"></i>
                    <span>${message}</span>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

            // Add styles
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                border-radius: 10px;
                padding: 15px 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 15px;
                max-width: 400px;
                animation: slideInRight 0.3s ease;
            `;

            // Add to page
            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }
            }, 5000);
        }

        // Example usage
        // showNotification('Welcome to NaviGo!', 'success');
        
        // Dark mode is now handled by js/dark-mode.js
        
        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('js/service-worker.js')
                    .then((registration) => {
                        console.log('Service Worker registered successfully:', registration.scope);
                        
                        // Check for updates
                        registration.addEventListener('updatefound', () => {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    // New content is available, show update notification
                                    if (confirm('New version available! Reload to update?')) {
                                        window.location.reload();
                                    }
                                }
                            });
                        });
                    })
                    .catch((error) => {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }
        
        // PWA Install Prompt Handling
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('PWA install prompt triggered');
            e.preventDefault();
            deferredPrompt = e;
        });
        
        // Handle PWA installation
        window.addEventListener('appinstalled', () => {
            console.log('PWA was installed');
            deferredPrompt = null;
        });
    </script>
</body>
</html>
