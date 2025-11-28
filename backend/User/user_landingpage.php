<?php
session_start();
require_once '../Config/Database.php';
require_once '../Config/functions.php';

// Debug: Log session data
error_log("Session data: " . print_r($_SESSION, true));
error_log("is_logged_in(): " . (is_logged_in() ? 'true' : 'false'));

// Check if user is logged in
if (!is_logged_in()) {
    error_log("User not logged in, redirecting to login");
    header('Location: ../Login/user_login.php');
    exit();
}

$user_type = get_user_type();
$user_id = get_current_user_id();

// Get user information
if ($user_type === 'personal') {
    $user = get_user_by_id($user_id);
    if ($user) {
        $user_name = $user['first_name'] . ' ' . $user['last_name'];
    } else {
        $user_name = 'Unknown User';
    }
} else {
    $business = get_business_by_id($user_id);
    if ($business) {
        $user_name = $business['business_name'];
    } else {
        $user_name = 'Unknown Business';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>NaviGo - Dashboard</title>
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="Your personal NaviGo dashboard. Manage your trips, explore destinations, and plan your next adventure.">
    <meta name="theme-color" content="#0891b2" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NaviGo Dashboard">
    <meta name="mobile-web-app-capable" content="yes">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="../js/manifest.json">
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="../Assets/Images/icons/icon-48x48.png">
    <link rel="shortcut icon" href="../Assets/Images/icons/icon-48x48.png">
    <link rel="apple-touch-icon" href="../Assets/Images/icons/icon-180x180.png">
    
    <!-- External Resources -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/afed286f48.js" crossorigin="anonymous"></script>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/user_landingpage.css?v=<?php echo time(); ?>">
    
    <!-- Scripts -->
    <script src="../js/dark-mode.js"></script>
    <script src="../js/user-mobile-features.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div class="logo-section">
                <div class="navi-logo">
                    <img src="../Assets/Images/NaviGo_Logo.png" alt="NaviGo Logo" class="logo-image">
                </div>
                <a href="#" class="page-title">
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
                    <img src="../Assets/Images/NaviGo_Logo.png" alt="Profile" class="profile-logo">
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
                    <a href="../User/Menu/Profile.php" class="menu-item">
                        <i class="fas fa-user"></i>
                        <span>View Profile</span>
                    </a>
                    <a href="../Subscription_plan.php" class="menu-item">
                        <i class="fas fa-crown"></i>
                        <span>Plans & Billing</span>
                    </a>
                    <a href="../User/Menu/account_settings.php" class="menu-item">
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

    <!-- Content Toggle -->
    <div class="content-toggle">
        <div class="toggle-container">
            <button class="toggle-btn active">Landing Page</button>
            <button class="toggle-btn">Dashboard</button>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Landing Page Content -->
        <div id="landing-page-content">
            <!-- Hero Section -->
            <section class="hero-section">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Discover Your Next<br>
                        <span class="epic">Epic</span> <span class="adventure">Adventure</span>
                    </h1>
                    
                    <p class="hero-subtitle">
                        Unlock intelligent travel planning with AI-powered recommendations, seamless collaboration, and cost optimization.<br>
                        Turn your wanderlust into unforgettable journeys.
                    </p>
                    
                    <div class="feature-tags">
                        <div class="feature-tag">
                            <div class="dot dot-blue"></div>
                            <span>AI-Powered Planning</span>
                        </div>
                        <div class="feature-tag">
                            <div class="dot dot-green"></div>
                            <span>Group Collaboration</span>
                        </div>
                        <div class="feature-tag">
                            <div class="dot dot-orange"></div>
                            <span>Smart Savings</span>
                        </div>
                    </div>
                    
                    <div class="hero-actions">
                        <a href="#" class="btn-primary" onclick="startJourney()">
                            <i class="fas fa-paper-plane"></i>
                            Start Your Journey
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="#" class="btn-secondary" onclick="exploreFeatures()">
                            <i class="fas fa-map-pin"></i>
                            Explore Features
                        </a>
                    </div>
                    
                    <!-- Stats integrated with hero actions -->
                    <div class="hero-stats">
                        <div class="stat-item">
                            <i class="fas fa-star"></i>
                            <span>4.9/5 Rating</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-users"></i>
                            <span>50K+ Travelers</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-globe"></i>
                            <span>190+ Countries</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main Content Grid -->
            <div class="content-grid">
                <!-- Adventure Planning Form -->
                <section class="adventure-form">
                    <div class="form-title">
                        <i class="fas fa-star"></i>
                        <h2>Plan Your Dream Journey</h2>
                    </div>
                    <p class="form-subtitle">Share your travel dreams with us and we'll craft the perfect adventure tailored just for you!</p>
                    
                    <form>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="budget">Budget (USD)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-dollar-sign"></i>
                                    <input type="number" id="budget" placeholder="1000" value="1000">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="distance">Max Distance (miles)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-map-pin"></i>
                                    <input type="number" id="distance" placeholder="500" value="500">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="duration">Duration (days)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-calendar"></i>
                                    <input type="number" id="duration" placeholder="5" value="5">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="category">Trip Category</label>
                                <div class="select-with-icon">
                                    <select id="category">
                                        <option value="">Choose your adventure</option>
                                        <option value="adventure">Adventure</option>
                                        <option value="relaxation">Relaxation</option>
                                        <option value="cultural">Cultural</option>
                                        <option value="nature">Nature</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <div class="interests-section">
                            <h3>Interests (Optional)</h3>
                            <div class="interests-grid">
                                <div class="interest-item">
                                    <input type="checkbox" id="photography">
                                    <label for="photography">Photography</label>
                                </div>
                                <div class="interest-item">
                                    <input type="checkbox" id="food">
                                    <label for="food">Food & Dining</label>
                                </div>
                                <div class="interest-item">
                                    <input type="checkbox" id="nightlife">
                                    <label for="nightlife">Nightlife</label>
                                </div>
                                <div class="interest-item">
                                    <input type="checkbox" id="shopping">
                                    <label for="shopping">Shopping</label>
                                </div>
                                <div class="interest-item">
                                    <input type="checkbox" id="museums">
                                    <label for="museums">Museums</label>
                                </div>
                                <div class="interest-item">
                                    <input type="checkbox" id="sports">
                                    <label for="sports">Adventure Sports</label>
                                </div>
                                <div class="interest-item">
                                    <input type="checkbox" id="relaxation">
                                    <label for="relaxation">Relaxation</label>
                                </div>
                                <div class="interest-item">
                                    <input type="checkbox" id="nature">
                                    <label for="nature">Nature</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="adventure-btn" onclick="createAdventure(event)">
                            <i class="fas fa-star"></i>
                            Begin My Adventure
                        </button>
                    </form>
                </section>

                <!-- Recent Adventures Sidebar -->
                <aside class="recent-adventures">
                    <div class="sidebar-card">
                        <div class="card-title">
                            <i class="fas fa-map-pin"></i>
                            <h3>Recent Adventures</h3>
                        </div>
                        
                        <div class="adventure-item">
                            <div class="adventure-border adventure-border-green"></div>
                            <div class="adventure-content">
                                <div class="adventure-title">Mountain Adventure in Colorado</div>
                                <div class="adventure-description">Experience the breathtaking beauty of Colorado mountains with guided hiking trips, camping under the stars, and amazing wildlife photography opportunities.</div>
                                <div class="adventure-details">
                                    <span class="adventure-price">$1,200</span>
                                    <span class="adventure-duration">5 days</span>
                                    <span class="adventure-distance">300mi</span>
                                </div>
                                <div class="adventure-tags">
                                    <span class="adventure-tag tag-green">Hiking</span>
                                    <span class="adventure-tag tag-blue">Camping</span>
                                    <span class="adventure-tag tag-purple">Photography</span>
                                </div>
                            </div>
                        </div>

                        <div class="adventure-item">
                            <div class="adventure-border adventure-border-blue"></div>
                            <div class="adventure-content">
                                <div class="adventure-title">Beach Paradise in Maldives</div>
                                <div class="adventure-description">Dive into crystal clear waters, explore vibrant coral reefs, and relax in overwater bungalows in this tropical paradise.</div>
                                <div class="adventure-details">
                                    <span class="adventure-price">$2,500</span>
                                    <span class="adventure-duration">7 days</span>
                                    <span class="adventure-distance">8500mi</span>
                                </div>
                                <div class="adventure-tags">
                                    <span class="adventure-tag tag-blue">Swimming</span>
                                    <span class="adventure-tag tag-green">Snorkeling</span>
                                    <span class="adventure-tag tag-orange">Island Hopping</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div id="dashboard-content" style="display: none;">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h1 class="dashboard-title">Travel Dashboard</h1>
                <p class="dashboard-subtitle">Manage your trips, bookings, and travel preferences in one place</p>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-teal">
                        <i class="fas fa-suitcase"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">23</div>
                        <div class="stat-label">Total Trips</div>
                        <div class="stat-change">+2 from last month</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-green">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">₱12,450</div>
                        <div class="stat-label">This Year Spent</div>
                        <div class="stat-change">+15% from last year</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-purple">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Countries Visited</div>
                        <div class="stat-change">3 new this year</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-orange">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">₱2,100</div>
                        <div class="stat-label">Savings</div>
                        <div class="stat-change">Through bulk discounts</div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Trips Section -->
            <div class="upcoming-trips">
                <h2 class="section-title">Upcoming Trips</h2>
                
                <div class="trip-cards">
                    <!-- Tokyo Trip -->
                    <div class="trip-card">
                        <div class="trip-image">
                            <div class="trip-image-placeholder tokyo-image"></div>
                        </div>
                        <div class="trip-content">
                            <h3 class="trip-destination">Tokyo, Japan</h3>
                            <p class="trip-dates">Oct 15-22, 2025</p>
                            <div class="trip-details">
                                <div class="trip-detail">
                                    <i class="fas fa-user"></i>
                                    <span>2 travelers</span>
                                </div>
                                <div class="trip-detail">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Budget: ₱2,800</span>
                                </div>
                                <div class="trip-detail">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Spent: ₱1,200</span>
                                </div>
                            </div>
                        </div>
                        <div class="trip-actions">
                            <div class="trip-status status-confirmed">Confirmed</div>
                            <div class="progress-section">
                                <div class="progress-label">Trip Planning Progress 43%</div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 43%"></div>
                                </div>
                            </div>
                            <div class="trip-buttons">
                                <button class="btn-trip-primary" onclick="viewJourney(1)">View Journey</button>
                                <button class="btn-trip-secondary" onclick="shareJourney(1)">
                                    <i class="fas fa-share"></i>
                                    Share Trip
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Paris Trip -->
                    <div class="trip-card">
                        <div class="trip-image">
                            <div class="trip-image-placeholder paris-image"></div>
                        </div>
                        <div class="trip-content">
                            <h3 class="trip-destination">Paris, France</h3>
                            <p class="trip-dates">Oct 10-17, 2025</p>
                            <div class="trip-details">
                                <div class="trip-detail">
                                    <i class="fas fa-user"></i>
                                    <span>1 traveler</span>
                                </div>
                                <div class="trip-detail">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Budget: ₱3,200</span>
                                </div>
                                <div class="trip-detail">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Spent: ₱0</span>
                                </div>
                            </div>
                        </div>
                        <div class="trip-actions">
                            <div class="trip-status status-planning">Planning</div>
                            <div class="progress-section">
                                <div class="progress-label">Trip Planning Progress 15%</div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 15%"></div>
                                </div>
                            </div>
                            <div class="trip-buttons">
                                <button class="btn-trip-primary" onclick="viewJourney(1)">View Journey</button>
                                <button class="btn-trip-secondary" onclick="shareJourney(1)">
                                    <i class="fas fa-share"></i>
                                    Share Trip
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-main">
            <div class="footer-section company-info">
                <div class="footer-logo">
                    <div class="logo-circle">
                        <i class="fas fa-plane"></i>
                    </div>
                    <span class="logo-text">NAVIGO</span>
            </div>
                <p class="footer-tagline">Your intelligent travel companion. Moving beyond simple bookings to create meaningful, personalized travel experiences.</p>
                <div class="social-icons">
                <a href="#" class="social-icon">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-icon">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-icon">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="social-icon">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
            </div>
            
            <div class="footer-section">
                <h4>Product</h4>
                <ul class="footer-links">
                    <li><a href="#">Personal Plans</a></li>
                    <li><a href="#">Business Solutions</a></li>
                    <li><a href="#">Enterprise</a></li>
                    <li><a href="#">Mobile App</a></li>
                    <li><a href="#">API</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Support</h4>
                <ul class="footer-links">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Travel Guides</a></li>
                    <li><a href="#">Contact Support</a></li>
                    <li><a href="#">Safety</a></li>
                    <li><a href="#">Community</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Get in Touch</h4>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>navigo@astrinatravels.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>(+63)953 062-4663</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Cebu City, Philippines</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-bottom-left">
                <span>© 2025 NaviGo Adventure Travels. All rights reserved.</span>
            </div>
            <div class="footer-bottom-right">
                <a href="#" class="footer-link">Privacy Policy</a>
                <a href="#" class="footer-link">Terms of Service</a>
                <a href="#" class="footer-link">Cookie Policy</a>
            </div>
        </div>
    </footer>

    <script>
        // Dark mode is now handled by the global dark-mode.js script

        // Form submission
        document.querySelector('.adventure-form form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Adventure planning feature coming soon!');
        });

        // Interest checkboxes
        document.querySelectorAll('.interest-item').forEach(item => {
            item.addEventListener('click', function() {
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            });
        });

        // Toggle buttons
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Show/hide content based on selection
                if (this.textContent === 'Dashboard') {
                    document.getElementById('landing-page-content').style.display = 'none';
                    document.getElementById('dashboard-content').style.display = 'block';
                } else {
                    document.getElementById('landing-page-content').style.display = 'block';
                    document.getElementById('dashboard-content').style.display = 'none';
                }
            });
        });

        // Landing page specific functions
        function startJourney() {
            // Scroll to the adventure form or redirect to Plan page
            document.querySelector('.adventure-form').scrollIntoView({ behavior: 'smooth' });
        }

        function exploreFeatures() {
            // Scroll to features section or show feature overview
            alert('Explore our amazing features!\n• Smart trip planning\n• Real-time updates\n• Budget tracking\n• Social sharing');
        }

        function createAdventure(event) {
            event.preventDefault();
            
            // Get form data
            const budget = document.getElementById('budget').value;
            const duration = document.getElementById('duration').value;
            const travelers = document.getElementById('travelers').value;
            const interests = Array.from(document.querySelectorAll('input[name="interests"]:checked')).map(cb => cb.value);
            
            if (!budget || !duration || !travelers || interests.length === 0) {
                alert('Please fill in all required fields and select at least one interest.');
                return;
            }
            
            alert(`Creating your adventure!\nBudget: $${budget}\nDuration: ${duration} days\nTravelers: ${travelers}\nInterests: ${interests.join(', ')}\n\nThis will redirect to the Plan page with your preferences.`);
            
            // Redirect to Plan page with parameters
            const params = new URLSearchParams({
                budget: budget,
                duration: duration,
                travelers: travelers,
                interests: interests.join(',')
            });
            window.location.href = `Plan.php?${params.toString()}`;
        }

        function viewJourney(tripId) {
            alert(`Viewing journey details for trip ID: ${tripId}\nThis will show complete trip information and itinerary.`);
        }

        function shareJourney(tripId) {
            if (navigator.share) {
                navigator.share({
                    title: 'My Amazing Journey',
                    text: 'Check out my incredible travel experience!',
                    url: window.location.href
                });
            } else {
                alert('Share functionality coming soon!\nTrip ID: ' + tripId);
            }
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

        // Input with icon focus effects
        document.querySelectorAll('.input-with-icon input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>
</body>
</html>