<?php
session_start();
require_once '../Config/Database.php';
require_once '../Config/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
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
    <title>Explore - NaviGo</title>
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="Explore amazing destinations with NaviGo. Discover new places, plan your next adventure, and find the perfect travel experiences.">
    <meta name="theme-color" content="#0891b2" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NaviGo Explore">
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
    <link rel="stylesheet" href="../css/Explore.css?v=<?php echo time(); ?>">
    
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
                <a href="user_landingpage.php" class="page-title">
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-links">
                <a href="My_trips.php" class="nav-link my-trips">
                    <i class="fas fa-suitcase"></i>
                    <span>My Trips</span>
                </a>
                <a href="Explore.php" class="nav-link explore active">
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

    <!-- Main Content -->
    <main class="user-page-content">
        <div class="page-header">
            <h1 class="page-title">Explore Destinations</h1>
            <p class="page-subtitle">Discover amazing places around the world. Get personalized recommendations based on your preferences and travel style.</p>
        </div>
        
        <!-- Search and Filter Section -->
        <div class="search-filter-section">
            <div class="search-container">
                <div class="search-bar">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search destinations, countries, or activities..." class="search-input">
                </div>
                <div class="filter-buttons">
                        <button class="filter-btn budget-btn" onclick="toggleBudgetFilter()">
                            <span>All Budgets</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <button class="filter-btn more-filters-btn" onclick="toggleMoreFilters()">
                            <i class="fas fa-filter"></i>
                            <span>More Filters</span>
                        </button>
                </div>
            </div>
            
            <!-- Category Pills -->
            <div class="category-pills">
                <button class="category-pill active" data-category="all">
                    <i class="fas fa-compass"></i>
                    <span>All Destinations</span>
                </button>
                <button class="category-pill" data-category="beach">
                    <i class="fas fa-umbrella-beach"></i>
                    <span>Beach & Islands</span>
                </button>
                <button class="category-pill" data-category="culture">
                    <i class="fas fa-building"></i>
                    <span>Culture & History</span>
                </button>
                <button class="category-pill" data-category="adventure">
                    <i class="fas fa-mountain"></i>
                    <span>Adventure</span>
                </button>
                <button class="category-pill" data-category="romantic">
                    <i class="fas fa-heart"></i>
                    <span>Romantic</span>
                </button>
                <button class="category-pill" data-category="luxury">
                    <i class="fas fa-star"></i>
                    <span>Luxury</span>
                </button>
                <button class="category-pill" data-category="nature">
                    <i class="fas fa-leaf"></i>
                    <span>Nature</span>
                </button>
            </div>
        </div>
        
        <!-- Content Tabs -->
        <div class="content-tabs">
            <button class="content-tab active" data-tab="destinations">
                <span>Destinations</span>
            </button>
            <button class="content-tab" data-tab="experiences">
                <span>Experiences</span>
            </button>
            <button class="content-tab" data-tab="inspiration">
                <span>Inspiration</span>
            </button>
        </div>
        
        <!-- Trending Now Section -->
        <div class="trending-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-trending-up trending-icon"></i>
                    Trending Now
                </h2>
            </div>
            
            <div class="destinations-grid">
                <!-- Santorini Card -->
                <div class="destination-card">
                    <div class="card-image">
                        <img src="https://images.unsplash.com/photo-1570077188660-9787c0a69d96?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Santorini, Greece">
                        <div class="price-indicator">$$$</div>
                            <div class="card-actions">
                                <button class="action-btn heart-btn" onclick="toggleHeart(this)">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="action-btn share-btn" onclick="shareDestination(this)">
                                    <i class="fas fa-share"></i>
                                </button>
                            </div>
                        <div class="trending-badge">Trending</div>
                    </div>
                    <div class="card-content">
                        <div class="location-info">
                            <h3 class="destination-name">Santorini, Greece</h3>
                            <div class="location-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Greece</span>
                            </div>
                        </div>
                        <div class="rating-duration">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>4.8 (2420)</span>
                            </div>
                            <div class="duration">5-7 days</div>
                        </div>
                        <div class="highlights">
                            <span class="highlight-tag">Sunset Views</span>
                            <span class="highlight-tag">White Architecture</span>
                            <span class="highlight-tag">+1 more</span>
                        </div>
                        <div class="trip-details">
                            <div class="detail-item">
                                <span class="detail-label">Best Time</span>
                                <span class="detail-value">Apr - Oct</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Budget/day</span>
                                <span class="detail-value">$150-$300</span>
                            </div>
                        </div>
                            <div class="card-buttons">
                                <button class="btn-primary" onclick="planTrip('Santorini, Greece')">Plan Trip</button>
                                <button class="btn-secondary" onclick="learnMore('Santorini, Greece')">Learn More</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kyoto Card -->
                <div class="destination-card">
                    <div class="card-image">
                        <img src="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Kyoto, Japan">
                        <div class="price-indicator">$$</div>
                            <div class="card-actions">
                                <button class="action-btn heart-btn" onclick="toggleHeart(this)">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="action-btn share-btn" onclick="shareDestination(this)">
                                    <i class="fas fa-share"></i>
                                </button>
                            </div>
                        <div class="trending-badge">Trending</div>
                    </div>
                    <div class="card-content">
                        <div class="location-info">
                            <h3 class="destination-name">Kyoto, Japan</h3>
                            <div class="location-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Japan</span>
                            </div>
                        </div>
                        <div class="rating-duration">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>4.9 (3150)</span>
                            </div>
                            <div class="duration">4-6 days</div>
                        </div>
                        <div class="highlights">
                            <span class="highlight-tag">Ancient Temples</span>
                            <span class="highlight-tag">Cherry Blossoms</span>
                            <span class="highlight-tag">+1 more</span>
                        </div>
                        <div class="trip-details">
                            <div class="detail-item">
                                <span class="detail-label">Best Time</span>
                                <span class="detail-value">Oct - Dec, Mar - May</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Budget/day</span>
                                <span class="detail-value">$100-$200</span>
                            </div>
                        </div>
                            <div class="card-buttons">
                                <button class="btn-primary" onclick="planTrip('Kyoto, Japan')">Plan Trip</button>
                                <button class="btn-secondary" onclick="learnMore('Kyoto, Japan')">Learn More</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dubai Card -->
                <div class="destination-card">
                    <div class="card-image">
                        <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Dubai, UAE">
                        <div class="price-indicator">$$$$</div>
                            <div class="card-actions">
                                <button class="action-btn heart-btn" onclick="toggleHeart(this)">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="action-btn share-btn" onclick="shareDestination(this)">
                                    <i class="fas fa-share"></i>
                                </button>
                            </div>
                        <div class="trending-badge">Trending</div>
                    </div>
                    <div class="card-content">
                        <div class="location-info">
                            <h3 class="destination-name">Dubai, UAE</h3>
                            <div class="location-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>UAE</span>
                            </div>
                        </div>
                        <div class="rating-duration">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>4.6 (1820)</span>
                            </div>
                            <div class="duration">4-5 days</div>
                        </div>
                        <div class="highlights">
                            <span class="highlight-tag">Modern Architecture</span>
                            <span class="highlight-tag">Luxury Shopping</span>
                            <span class="highlight-tag">+1 more</span>
                        </div>
                        <div class="trip-details">
                            <div class="detail-item">
                                <span class="detail-label">Best Time</span>
                                <span class="detail-value">Nov - Mar</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Budget/day</span>
                                <span class="detail-value">$250-$500</span>
                            </div>
                        </div>
                            <div class="card-buttons">
                                <button class="btn-primary" onclick="planTrip('Dubai, UAE')">Plan Trip</button>
                                <button class="btn-secondary" onclick="learnMore('Dubai, UAE')">Learn More</button>
                            </div>
                        </div>
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

        // Explore specific functions
        function toggleBudgetFilter() {
            alert('Budget filter functionality coming soon!');
        }

        function toggleMoreFilters() {
            alert('More filters functionality coming soon!');
        }

        function planTrip(destination) {
            // Redirect to Plan page with destination pre-filled
            window.location.href = `Plan.php?destination=${encodeURIComponent(destination)}`;
        }

        function learnMore(destination) {
            alert(`Learning more about ${destination}!\nThis feature will show detailed information about the destination.`);
        }

        function toggleHeart(button) {
            const icon = button.querySelector('i');
            if (icon.classList.contains('fas')) {
                icon.classList.remove('fas');
                icon.classList.add('far');
                button.style.color = '#6b7280';
            } else {
                icon.classList.remove('far');
                icon.classList.add('fas');
                button.style.color = '#ef4444';
            }
        }

        function shareDestination(button) {
            if (navigator.share) {
                navigator.share({
                    title: 'Amazing Destination',
                    text: 'Check out this amazing destination!',
                    url: window.location.href
                });
            } else {
                alert('Share functionality coming soon!');
            }
        }

        // Category pill functionality
        document.addEventListener('DOMContentLoaded', function() {
            const categoryPills = document.querySelectorAll('.category-pill');
            categoryPills.forEach(pill => {
                pill.addEventListener('click', function() {
                    // Remove active class from all pills
                    categoryPills.forEach(p => p.classList.remove('active'));
                    // Add active class to clicked pill
                    this.classList.add('active');
                    
                    // Here you would typically filter the destinations based on category
                    const category = this.getAttribute('data-category');
                    console.log('Selected category:', category);
                });
            });

            // Content tab functionality
            const contentTabs = document.querySelectorAll('.content-tab');
            contentTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    contentTabs.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Here you would typically show different content based on tab
                    const tabType = this.getAttribute('data-tab');
                    console.log('Selected tab:', tabType);
                });
            });

            // Heart button functionality
            const heartButtons = document.querySelectorAll('.heart-btn');
            heartButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.classList.toggle('liked');
                    const icon = this.querySelector('i');
                    if (this.classList.contains('liked')) {
                        icon.className = 'fas fa-heart';
                        icon.style.color = '#ef4444';
                    } else {
                        icon.className = 'fas fa-heart';
                        icon.style.color = '';
                    }
                });
            });

            // Share button functionality
            const shareButtons = document.querySelectorAll('.share-btn');
            shareButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Here you would implement actual sharing functionality
                    console.log('Share button clicked');
                });
            });

            // Plan Trip button functionality
            const planTripButtons = document.querySelectorAll('.btn-primary');
            planTripButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Here you would redirect to trip planning page
                    console.log('Plan trip button clicked');
                    // window.location.href = 'Plan.php';
                });
            });

            // Learn More button functionality
            const learnMoreButtons = document.querySelectorAll('.btn-secondary');
            learnMoreButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Here you would show more details about the destination
                    console.log('Learn more button clicked');
                });
            });

            // Search functionality
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    console.log('Searching for:', searchTerm);
                    // Here you would implement actual search functionality
                });
            }

            // Filter button functionality
            const budgetBtn = document.querySelector('.budget-btn');
            if (budgetBtn) {
                budgetBtn.addEventListener('click', function() {
                    console.log('Budget filter clicked');
                    // Here you would show budget filter options
                });
            }

            const moreFiltersBtn = document.querySelector('.more-filters-btn');
            if (moreFiltersBtn) {
                moreFiltersBtn.addEventListener('click', function() {
                    console.log('More filters clicked');
                    // Here you would show additional filter options
                });
            }
        });
    </script>
</body>
</html>
