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
    $user_name = $user['first_name'] . ' ' . $user['last_name'];
} else {
    $business = get_business_by_id($user_id);
    $user_name = $business['business_name'];
}

// Sample trip data - in a real application, this would come from the database
$trips = [
    [
        'id' => 1,
        'destination' => 'Tokyo, Japan',
        'country' => 'Japan',
        'dates' => 'Oct 15-22, 2025',
        'duration' => '8 days',
        'travelers' => 2,
        'budget' => '$2,800',
        'spent' => '$1,200',
        'progress' => 85,
        'status' => 'Confirmed',
        'rating' => '4.8',
        'reviews' => '1240',
        'image' => 'https://picsum.photos/400/300?random=1',
        'activities' => [
            'flights' => 2,
            'hotels' => 3,
            'activities' => 5,
            'transport' => 2
        ],
        'highlights' => ['Cherry Blossom Season', 'Traditional Temples', 'Modern Architecture']
    ],
    [
        'id' => 2,
        'destination' => 'Paris, France',
        'country' => 'France',
        'dates' => 'Oct 10-17, 2025',
        'duration' => '7 days',
        'travelers' => 1,
        'budget' => '$3,200',
        'spent' => '$450',
        'progress' => 35,
        'status' => 'Planning',
        'rating' => '4.9',
        'reviews' => '2150',
        'image' => 'https://picsum.photos/400/300?random=2',
        'activities' => [
            'flights' => 1,
            'hotels' => 1,
            'activities' => 2,
            'transport' => 0
        ],
        'highlights' => ['Art Museums', 'Fine Dining', 'Historic Architecture']
    ],
    [
        'id' => 3,
        'destination' => 'New York, USA',
        'country' => 'USA',
        'dates' => 'Oct 5-8, 2025',
        'duration' => '4 days',
        'travelers' => 1,
        'budget' => '$1,800',
        'spent' => '$1,800',
        'progress' => 100,
        'status' => 'Business',
        'reviews' => '890',
        'image' => 'https://picsum.photos/400/300?random=3',
        'activities' => [
            'flights' => 2,
            'hotels' => 1,
            'activities' => 1,
            'transport' => 3
        ],
        'highlights' => ['Business Conference', 'Networking Events', 'Central Park']
    ]
];

// Calculate summary statistics
$total_trips = count($trips);
$total_budget = array_sum(array_map(function($trip) {
    return (int)str_replace(['$', ','], '', $trip['budget']);
}, $trips));
$countries = count(array_unique(array_map(function($trip) {
    return explode(',', $trip['destination'])[1];
}, $trips)));
$avg_progress = array_sum(array_column($trips, 'progress')) / count($trips);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>My Trips - NaviGo</title>
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="Manage your trips with NaviGo. View, edit, and track all your travel plans and adventures.">
    <meta name="theme-color" content="#0891b2" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NaviGo My Trips">
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
    <link rel="stylesheet" href="../css/my_trips.css?v=<?php echo time(); ?>">
    
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
                <a href="My_trips.php" class="nav-link my-trips active">
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

    <!-- Main Content -->
    <main class="user-page-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title-section">
                <h1 class="page-title">My Trips</h1>
                <p class="page-subtitle">Your smart travel companion for managing and tracking all adventures</p>
            </div>
            <button class="create-trip-btn" onclick="createNewTrip()">
                <i class="fas fa-plus"></i>
                Create New Trip
            </button>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section">
            <div class="search-container">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search trips, destinations, or highlights...">
                </div>
                <select class="filter-dropdown">
                    <option value="all">All Trips</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="planning">Planning</option>
                    <option value="business">Business</option>
                </select>
                <button class="filter-btn" onclick="toggleMoreFilters()">
                    <i class="fas fa-filter"></i>
                    More Filters
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <div class="card-icon flights">
                    <i class="fas fa-plane"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">Total Trips</div>
                    <div class="card-value"><?php echo $total_trips; ?></div>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="card-icon budget">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">Total Budget</div>
                    <div class="card-value">$<?php echo number_format($total_budget); ?></div>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="card-icon countries">
                    <i class="fas fa-map-pin"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">Countries</div>
                    <div class="card-value"><?php echo $countries; ?></div>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="card-icon progress">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">Avg Progress</div>
                    <div class="card-value"><?php echo round($avg_progress); ?>%</div>
                </div>
            </div>
        </div>

        <!-- Trip Cards -->
        <div class="trip-cards-container">
            <?php foreach ($trips as $trip): ?>
            <div class="trip-card">
                <div class="card-image">
                    <img src="<?php echo isset($trip['image']) && !empty($trip['image']) ? $trip['image'] : 'https://picsum.photos/400/300?random=0'; ?>" alt="<?php echo htmlspecialchars($trip['destination']); ?>" class="trip-image" onerror="this.src='https://via.placeholder.com/400x300/0891b2/ffffff?text=Image+Not+Found'">
                    <div class="trip-status status-<?php echo strtolower($trip['status']); ?>">
                        <?php echo $trip['status']; ?>
                    </div>
                    <div class="card-actions">
                        <button class="action-btn trip-menu-btn">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
                
                <div class="card-content">
                    <div class="location-info">
                        <h3 class="destination-name"><?php echo htmlspecialchars($trip['destination']); ?></h3>
                        <div class="location-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo isset($trip['country']) ? $trip['country'] : explode(', ', $trip['destination'])[1] ?? 'Destination'; ?></span>
                        </div>
                    </div>
                    
                    <div class="rating-duration">
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <span><?php echo isset($trip['rating']) ? $trip['rating'] : '4.5'; ?> (<?php echo isset($trip['reviews']) ? $trip['reviews'] : '0'; ?>)</span>
                        </div>
                        <div class="duration"><?php echo $trip['duration']; ?></div>
                    </div>
                    
                    <div class="trip-details">
                        <div class="detail-item">
                            <span class="detail-label">Dates</span>
                            <span class="detail-value"><?php echo $trip['dates']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Travelers</span>
                            <span class="detail-value"><?php echo $trip['travelers']; ?></span>
                        </div>
                    </div>
                    
                    <div class="highlights">
                        <?php if (isset($trip['highlights']) && is_array($trip['highlights'])): ?>
                            <?php foreach (array_slice($trip['highlights'], 0, 3) as $highlight): ?>
                            <span class="highlight-tag"><?php echo htmlspecialchars($highlight); ?></span>
                            <?php endforeach; ?>
                            <?php if (count($trip['highlights']) > 3): ?>
                            <span class="highlight-tag">+<?php echo count($trip['highlights']) - 3; ?> more</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="highlight-tag">Travel</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="trip-financials">
                        <div class="financial-item">
                            <div class="financial-label">Budget</div>
                            <div class="financial-value"><?php echo $trip['budget']; ?></div>
                        </div>
                        <div class="financial-item">
                            <div class="financial-label">Spent</div>
                            <div class="financial-value"><?php echo $trip['spent']; ?></div>
                        </div>
                    </div>
                    
                    <div class="card-buttons">
                        <button class="btn-primary" onclick="viewTripDetails(<?php echo $trip['id']; ?>)">View Details</button>
                        <button class="btn-secondary" onclick="shareTrip(<?php echo $trip['id']; ?>)">
                            <i class="fas fa-share"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
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

        // My Trips specific functions
        function createNewTrip() {
            // Redirect to Plan page for creating new trip
            window.location.href = 'Plan.php';
        }

        function toggleMoreFilters() {
            alert('More filters functionality coming soon!');
        }

        function viewTripDetails(tripId) {
            // Redirect to trip details page or show modal
            alert('Viewing details for trip ID: ' + tripId + '\nThis feature will be implemented soon!');
        }

        function shareTrip(tripId) {
            if (navigator.share) {
                navigator.share({
                    title: 'My Trip',
                    text: 'Check out my amazing trip!',
                    url: window.location.href
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    alert('Trip link copied to clipboard!');
                });
            }
        }

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const tripCards = document.querySelectorAll('.trip-card');
                    
                    tripCards.forEach(card => {
                        const destination = card.querySelector('.destination-name').textContent.toLowerCase();
                        const country = card.querySelector('.location-detail span').textContent.toLowerCase();
                        
                        if (destination.includes(searchTerm) || country.includes(searchTerm)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }

            // Filter dropdown functionality
            const filterDropdown = document.querySelector('.filter-dropdown');
            if (filterDropdown) {
                filterDropdown.addEventListener('change', function() {
                    const filterValue = this.value;
                    const tripCards = document.querySelectorAll('.trip-card');
                    
                    tripCards.forEach(card => {
                        if (filterValue === 'all') {
                            card.style.display = 'block';
                        } else {
                            const status = card.querySelector('.trip-status').textContent.toLowerCase();
                            if (status.includes(filterValue)) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
