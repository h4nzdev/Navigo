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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Plan - NaviGo</title>
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="Plan your next adventure with NaviGo. Create detailed itineraries, find the best deals, and organize your perfect trip.">
    <meta name="theme-color" content="#0891b2" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NaviGo Plan">
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
    <link rel="stylesheet" href="../css/Plan.css?v=<?php echo time(); ?>">
    
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
                <a href="History.php" class="nav-link history">
                    <i class="fas fa-clock"></i>
                    <span>History</span>
                </a>
                <a href="Plan.php" class="nav-link plan active">
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
        <div class="plan-page-header">
            <div class="page-title-section">
                <h1 class="page-title">Plan Your Trip</h1>
                <p class="page-subtitle">Find the best flights, hotels, and activities for your journey</p>
            </div>
            <button class="back-to-dashboard-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </button>
        </div>
        
        <!-- Flight Search Card -->
        <div class="flight-search-card">
            <div class="card-header">
                <i class="fas fa-plane"></i>
                <h2>Flight Search</h2>
            </div>
            
            <!-- Trip Type Selection -->
            <div class="trip-type-selection">
                <button class="trip-type-btn active" data-type="round-trip">
                    Round Trip
                </button>
                <button class="trip-type-btn" data-type="one-way">
                    One Way
                </button>
                <button class="trip-type-btn" data-type="multi-city">
                    Multi-City
                </button>
            </div>
            
            <!-- Flight Details -->
            <div class="flight-details">
                <h3>Flight Details</h3>
                <div class="flight-inputs">
                    <div class="input-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" placeholder="Departure city" class="flight-input">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" placeholder="Destination city" class="flight-input">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="text" placeholder="mm/dd/yyyy" class="flight-input">
                    </div>
                </div>
            </div>
            
            <!-- Return Flight Details -->
            <div class="return-flight-details">
                <h3>Return Flight</h3>
                <div class="flight-inputs">
                    <div class="input-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" placeholder="Return from" class="flight-input">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" placeholder="Return to" class="flight-input">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="text" placeholder="mm/dd/yyyy" class="flight-input">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <select class="flight-input">
                            <option>1 Traveler</option>
                            <option>2 Travelers</option>
                            <option>3 Travelers</option>
                            <option>4+ Travelers</option>
                        </select>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="input-group">
                        <select class="flight-input">
                            <option>Economy</option>
                            <option>Premium Economy</option>
                            <option>Business</option>
                            <option>First Class</option>
                        </select>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
            
                <button class="search-flights-btn" onclick="searchFlights()">
                    <i class="fas fa-plane"></i>
                    Search Flights
                </button>
        </div>
        
        <!-- Additional Services -->
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h3>Hotels & Accommodations</h3>
                <p>Find the perfect place to stay</p>
                    <button class="service-btn" onclick="searchHotels()">
                        Search Hotels
                        <i class="fas fa-arrow-right"></i>
                    </button>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-car"></i>
                </div>
                <h3>Car Rentals</h3>
                <p>Get around with ease</p>
                    <button class="service-btn" onclick="rentCar()">
                        Rent a Car
                        <i class="fas fa-arrow-right"></i>
                    </button>
            </div>
        </div>
        
        <!-- Popular Destinations -->
        <div class="popular-destinations">
            <h2>Popular Destinations</h2>
            <div class="destinations-grid">
                    <div class="destination-tag" onclick="selectDestination('New York')">New York</div>
                    <div class="destination-tag" onclick="selectDestination('Paris')">Paris</div>
                    <div class="destination-tag" onclick="selectDestination('Tokyo')">Tokyo</div>
                    <div class="destination-tag" onclick="selectDestination('London')">London</div>
                    <div class="destination-tag" onclick="selectDestination('Dubai')">Dubai</div>
                    <div class="destination-tag" onclick="selectDestination('Sydney')">Sydney</div>
                    <div class="destination-tag" onclick="selectDestination('Barcelona')">Barcelona</div>
                    <div class="destination-tag" onclick="selectDestination('Amsterdam')">Amsterdam</div>
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

        // Trip type selection functionality
        // Plan specific functions
        function searchFlights() {
            const departure = document.querySelector('input[placeholder="Departure city"]').value;
            const destination = document.querySelector('input[placeholder="Destination city"]').value;
            const date = document.querySelector('input[placeholder="mm/dd/yyyy"]').value;
            
            if (!departure || !destination || !date) {
                alert('Please fill in all required fields to search for flights.');
                return;
            }
            
            alert(`Searching for flights from ${departure} to ${destination} on ${date}...\nThis feature will connect to flight booking APIs.`);
        }

        function searchHotels() {
            alert('Searching for hotels...\nThis feature will connect to hotel booking APIs.');
        }

        function rentCar() {
            alert('Searching for car rentals...\nThis feature will connect to car rental APIs.');
        }

        function selectDestination(destination) {
            // Pre-fill the destination field
            const destinationInput = document.querySelector('input[placeholder="Destination city"]');
            if (destinationInput) {
                destinationInput.value = destination;
            }
            alert(`Selected destination: ${destination}\nThis will help pre-fill your search.`);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tripTypeBtns = document.querySelectorAll('.trip-type-btn');
            
            tripTypeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    tripTypeBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                });
            });

            // Check for destination parameter from URL
            const urlParams = new URLSearchParams(window.location.search);
            const destination = urlParams.get('destination');
            if (destination) {
                const destinationInput = document.querySelector('input[placeholder="Destination city"]');
                if (destinationInput) {
                    destinationInput.value = decodeURIComponent(destination);
                }
            }
        });
    </script>
</body>
</html>
