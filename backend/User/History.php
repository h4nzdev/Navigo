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

// Sample travel history data
$travel_stats = [
    'total_trips' => 5,
    'total_spent' => 12400,
    'countries' => 5,
    'days_traveled' => 37,
    'photos' => 620,
    'avg_rating' => 4.6
];

$trip_history = [
    [
        'id' => 1,
        'destination' => 'Tokyo, Japan',
        'country' => 'Japan',
        'dates' => 'Oct 15-22, 2025',
        'duration' => '8 days',
        'type' => 'leisure',
        'image' => 'https://picsum.photos/400/300?random=4',
        'description' => 'Amazing cultural experience. The food was incredible and the temples were breathtaking.',
        'total_cost' => 2800,
        'rating' => 5,
        'photos' => 127,
        'is_favorite' => true,
        'activities' => [
            'flights' => 2,
            'hotels' => 3,
            'activities' => 8,
            'transport' => 5
        ],
        'highlights' => ['Cherry Blossoms', 'Temples', 'Sushi']
    ],
    [
        'id' => 2,
        'destination' => 'Paris, France',
        'country' => 'France',
        'dates' => 'Oct 5-12, 2025',
        'duration' => '7 days',
        'type' => 'leisure',
        'image' => 'https://picsum.photos/400/300?random=5',
        'description' => 'Romantic getaway with amazing art and architecture. The Seine river cruise was magical.',
        'total_cost' => 3200,
        'rating' => 4,
        'photos' => 89,
        'is_favorite' => false,
        'activities' => [
            'flights' => 2,
            'hotels' => 2,
            'activities' => 6,
            'transport' => 3
        ],
        'highlights' => ['Eiffel Tower', 'Louvre', 'Seine River']
    ],
    [
        'id' => 3,
        'destination' => 'New York, USA',
        'country' => 'USA',
        'dates' => 'Oct 10-14, 2025',
        'duration' => '5 days',
        'type' => 'business',
        'image' => 'https://picsum.photos/400/300?random=6',
        'description' => 'Productive business trip with some leisure time. Caught a great Broadway show.',
        'total_cost' => 1800,
        'rating' => 4,
        'photos' => 45,
        'is_favorite' => false,
        'activities' => [
            'flights' => 2,
            'hotels' => 1,
            'activities' => 2,
            'transport' => 4
        ],
        'highlights' => ['Business Conference', 'Central Park', 'Broadway']
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Travel History - NaviGo</title>
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="View your travel history with NaviGo. Relive your past adventures and track your travel journey.">
    <meta name="theme-color" content="#0891b2" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NaviGo History">
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
    <link rel="stylesheet" href="../css/History.css?v=<?php echo time(); ?>">
    
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
                <a href="Explore.php" class="nav-link explore">
                    <i class="fas fa-compass"></i>
                    <span>Explore</span>
                </a>
                <a href="History.php" class="nav-link history active">
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

    <main class="user-page-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title-section">
                <h1 class="page-title">Travel History</h1>
                <p class="page-subtitle">Your complete travel journey with memories, expenses, and insights</p>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section">
            <div class="search-container">
                <div class="search-bar">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search destinations, countries, or highlights..." class="search-input">
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn" onclick="toggleYearFilter()">
                        <span>All Years</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <button class="filter-btn" onclick="toggleTypeFilter()">
                        <span>All Types</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <button class="filter-btn more-filters-btn" onclick="toggleMoreFilters()">
                        <i class="fas fa-filter"></i>
                        <span>More</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Trip Cards Grid -->
        <div class="trip-cards-grid">
            <?php foreach ($trip_history as $trip): ?>
            <div class="trip-card">
                <div class="card-image">
                    <img src="<?php echo isset($trip['image']) && !empty($trip['image']) ? $trip['image'] : 'https://picsum.photos/400/300?random=0'; ?>" alt="<?php echo $trip['destination']; ?>" class="trip-image" onerror="this.src='https://via.placeholder.com/400x300/0891b2/ffffff?text=Image+Not+Found'">
                    <div class="trip-type-badge"><?php echo isset($trip['type']) ? ucfirst($trip['type']) : 'Trip'; ?></div>
                    <div class="card-actions">
                        <?php if (isset($trip['is_favorite']) && $trip['is_favorite']): ?>
                        <button class="action-btn favorite-btn">
                            <i class="fas fa-star"></i>
                        </button>
                        <?php endif; ?>
                        <button class="action-btn photos-btn">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="trip-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo $i <= $trip['rating'] ? 'filled' : 'empty'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <div class="card-content">
                    <div class="location-info">
                        <h3 class="destination-name"><?php echo $trip['destination']; ?></h3>
                        <div class="location-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo isset($trip['country']) ? $trip['country'] : (explode(', ', $trip['destination'])[1] ?? 'Destination'); ?></span>
                        </div>
                    </div>
                    
                    <div class="rating-duration">
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <span><?php echo isset($trip['rating']) ? $trip['rating'] : '4.5'; ?>/5</span>
                        </div>
                        <div class="duration"><?php echo $trip['duration']; ?></div>
                    </div>
                    
                    <div class="trip-details">
                        <div class="detail-item">
                            <span class="detail-label">Dates</span>
                            <span class="detail-value"><?php echo $trip['dates']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Photos</span>
                            <span class="detail-value"><?php echo isset($trip['photos']) ? $trip['photos'] : '0'; ?></span>
                        </div>
                    </div>
                    
                    <!-- Activity Breakdown -->
                    <div class="activity-breakdown">
                        <div class="activity-item">
                            <i class="fas fa-plane"></i>
                            <span><?php echo isset($trip['activities']['flights']) ? $trip['activities']['flights'] : '0'; ?></span>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-building"></i>
                            <span><?php echo isset($trip['activities']['hotels']) ? $trip['activities']['hotels'] : '0'; ?></span>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-camera"></i>
                            <span><?php echo isset($trip['activities']['activities']) ? $trip['activities']['activities'] : '0'; ?></span>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-car"></i>
                            <span><?php echo isset($trip['activities']['transport']) ? $trip['activities']['transport'] : '0'; ?></span>
                        </div>
                    </div>
                    
                    <div class="highlights">
                        <?php if (isset($trip['highlights']) && is_array($trip['highlights'])): ?>
                            <?php foreach (array_slice($trip['highlights'], 0, 3) as $highlight): ?>
                            <span class="highlight-tag"><?php echo htmlspecialchars($highlight); ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="highlight-tag"><?php echo isset($trip['type']) ? ucfirst($trip['type']) : 'Trip'; ?></span>
                            <span class="highlight-tag">Completed</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="trip-description">
                        <p>"<?php echo isset($trip['description']) ? $trip['description'] : 'No description available.'; ?>"</p>
                    </div>
                    
                    <!-- Total Cost and Rating -->
                    <div class="trip-financials">
                        <div class="financial-item">
                            <span class="financial-label">Total Cost</span>
                            <span class="financial-value">$<?php echo isset($trip['total_cost']) ? number_format($trip['total_cost']) : '0'; ?></span>
                        </div>
                        <div class="financial-item">
                            <span class="financial-label">Rating</span>
                            <span class="financial-value"><?php echo isset($trip['rating']) ? $trip['rating'] : '0'; ?>/5 stars</span>
                        </div>
                    </div>
                    
                        <div class="card-buttons">
                            <button class="btn-primary" onclick="viewTripDetails(<?php echo $trip['id']; ?>)">View Details</button>
                            <button class="btn-secondary" onclick="shareTrip(<?php echo $trip['id']; ?>)"> Share
                                <i class="fas fa-share"></i>
                            </button>
                            <button class="btn-secondary" onclick="downloadTrip(<?php echo $trip['id']; ?>)"> Download
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script>
        // Profile dropdown toggle
        function toggleProfileMenu() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const profile = document.querySelector('.user-profile');
            
            if (!profile.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // History specific functions
        function toggleYearFilter() {
            alert('Year filter functionality coming soon!');
        }

        function toggleTypeFilter() {
            alert('Type filter functionality coming soon!');
        }

        function toggleMoreFilters() {
            alert('More filters functionality coming soon!');
        }

        function viewTripDetails(tripId) {
            alert('Viewing details for trip ID: ' + tripId + '\nThis feature will show complete trip information.');
        }

        function shareTrip(tripId) {
            if (navigator.share) {
                navigator.share({
                    title: 'My Travel History',
                    text: 'Check out my amazing travel experience!',
                    url: window.location.href
                });
            } else {
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    alert('Trip link copied to clipboard!');
                });
            }
        }

        function downloadTrip(tripId) {
            alert('Downloading trip data for ID: ' + tripId + '\nThis will generate a PDF or export file.');
        }

        // Content tabs functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.content-tab');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Here you would typically show/hide different content based on the tab
                    console.log('Switched to tab:', this.dataset.tab);
                });
            });

            // Search functionality
            const searchInput = document.querySelector('.search-input');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const tripCards = document.querySelectorAll('.trip-card');
                
                tripCards.forEach(card => {
                    const destination = card.querySelector('.trip-destination').textContent.toLowerCase();
                    const description = card.querySelector('.trip-description').textContent.toLowerCase();
                    
                    if (destination.includes(searchTerm) || description.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // Filter buttons functionality
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    console.log('Filter clicked:', this.textContent.trim());
                    // Add filter functionality here
                });
            });
        });
    </script>
</body>
</html>
