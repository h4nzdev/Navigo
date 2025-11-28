<?php
session_start();
require_once '../Config/Database.php';
require_once '../Config/functions.php';

// Check if business is logged in
if (!is_logged_in() || get_user_type() !== 'business') {
    header('Location: ../Login/business_login.php');
    exit();
}

$business_id = get_current_user_id();
$business = get_business_by_id($business_id);
$business_name = $business['business_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Dashboard</title>
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="../Assets/Images/icons/icon-48x48.png">
    <link rel="shortcut icon" href="../Assets/Images/icons/icon-48x48.png">
    <link rel="apple-touch-icon" href="../Assets/Images/icons/icon-180x180.png">
    
    <!-- Theme Colors -->
    <meta name="theme-color" content="#1EA7FF" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
    
    <!-- External Resources -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/afed286f48.js" crossorigin="anonymous"></script>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/business_dashboard.css?v=<?php echo time(); ?>">
    <script src="../js/dark-mode.js"></script>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="top-nav">
        <div class="nav-left">
            <a href="../Business/business_dashboard.php" class="navi-logo">
                <img src="../Assets/Images/NaviGo_Logo.png" alt="NaviGo Logo" class="logo-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="logo-fallback" style="display: none;">
                    <div class="logo-icon">
                        <i class="fas fa-plane"></i>
                    </div>
                </div>
            </a>
            <div class="portal-badge">Partner Portal</div>
        </div>
        
        <div class="nav-center">
            <div class="dashboard-title">
                <i class="fas fa-chart-bar"></i>
                <span>Dashboard</span>
            </div>
        </div>
        
        <div class="nav-right">
            <button class="nav-icon dark-mode-toggle">
                <i class="fas fa-moon"></i>
            </button>
            <button class="nav-icon">
                <i class="fas fa-cog"></i>
            </button>
            <button class="nav-icon notification-btn">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>
            <div class="business-dropdown">
                <div class="profile-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="business-info">
                    <span class="business-name"><?php echo htmlspecialchars($business_name); ?></span>
                    <span class="partner-badge">Free Partner</span>
                </div>
                <i class="fas fa-chevron-down"></i>
                
                <!-- Business Profile Dropdown -->
                <div class="business-menu" id="businessMenu">
        <div class="business-header">
            <div class="business-avatar">
                <i class="fas fa-building"></i>
            </div>
            <div class="business-info">
                <h3>Your Business</h3>
                <p>Airlines Partner</p>
                <span class="status-badge active">Active</span>
            </div>
        </div>
        
        <div class="business-menu-items">
            <a href="#" class="menu-item">
                <i class="fas fa-users"></i>
                <span>Account Management</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-credit-card"></i>
                <span>Billing & Payments</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-crown"></i>
                <span>Subscription Plans</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Partner Settings</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-question-circle"></i>
                <span>Help & Support</span>
            </a>
        </div>
        
        <div class="menu-divider"></div>
        <a href="../Login/logout.php" class="menu-item logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Sign out</span>
        </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Business Header Card -->
        <div class="business-header-card">
            <div class="business-profile">
                <div class="profile-logo">
                    <img src="../Assets/Images/NaviGo_Logo.png" alt="Profile Logo" class="profile-logo-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="profile-logo-fallback" style="display: none;">
                        <i class="fas fa-plane"></i>
                    </div>
                </div>
                <div class="business-details">
                    <h1><?php echo htmlspecialchars($business_name); ?></h1>
                    <div class="partner-badge">Standard Partner</div>
                    <p><i class="fas fa-suitcase"></i> Travel Services • Partner since 2025</p>
                </div>
            </div>
            <div class="business-actions">
                <button class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Add Services
                </button>
                <button class="btn-secondary">
                    <i class="fas fa-eye"></i>
                    View Listings
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <i class="fas fa-chart-bar"></i>
                    <i class="fas fa-arrow-up-right"></i>
                </div>
                <div class="stat-content">
                    <h3>TOTAL REQUESTS</h3>
                    <div class="stat-value">4</div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        +15% this week
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <i class="fas fa-clock"></i>
                    <i class="fas fa-arrow-up-right"></i>
                </div>
                <div class="stat-content">
                    <h3>PENDING</h3>
                    <div class="stat-value">2</div>
                    <div class="stat-detail">Avg response: 1.2h</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <i class="fas fa-user-check"></i>
                    <i class="fas fa-arrow-up-right"></i>
                </div>
                <div class="stat-content">
                    <h3>ACCEPTED</h3>
                    <div class="stat-value">1</div>
                    <div class="stat-trend positive">92% acceptance rate</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <i class="fas fa-dollar-sign"></i>
                    <i class="fas fa-arrow-up-right"></i>
                </div>
                <div class="stat-content">
                    <h3>REVENUE (MTD)</h3>
                    <div class="stat-value">₱248,650</div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        +24% vs last month
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Tabs -->
        <div class="content-tabs">
            <button class="tab-btn active" onclick="switchTab('requests')">
                <i class="fas fa-chart-bar"></i>
                Requests
            </button>
            <button class="tab-btn" onclick="switchTab('analytics')">
                <i class="fas fa-clock"></i>
                Analytics
            </button>
            <button class="tab-btn" onclick="switchTab('settings')">
                <i class="fas fa-cog"></i>
                Settings
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="search-filter-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search customers, booking IDs, or routes...">
            </div>
            <div class="filter-dropdown">
                <i class="fas fa-filter"></i>
                <span>All Requests</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>

        <!-- Requests Content -->
        <div id="requests-content" class="tab-content active">
            <div class="requests-list">
                <!-- Request Card 1 -->
                <div class="request-card">
                    <div class="request-header">
                        <div class="request-info">
                            <i class="fas fa-plane"></i>
                            <span class="request-id">Request #BR-001</span>
                        </div>
                        <div class="request-badges">
                            <span class="priority-badge high">HIGH</span>
                            <span class="status-badge pending">PENDING</span>
                            <span class="time-badge">
                                <i class="fas fa-clock"></i>
                                2h left
                            </span>
                        </div>
                    </div>
                    
                    <div class="request-body">
                        <div class="customer-info">
                            <div class="customer-avatar">AMR</div>
                            <div class="customer-details">
                                <div class="customer-name">
                                    <span>Anna Mae Regis</span>
                                    <span class="vip-badge">
                                        <i class="fas fa-star"></i>
                                        VIP
                                    </span>
                                </div>
                                <div class="customer-email">anna.regis@email.com</div>
                            </div>
                        </div>
                        
                        <div class="flight-details">
                            <div class="flight-route">
                                <strong>ROUTE:</strong> New York (JFK) → Paris (CDG)
                            </div>
                            <div class="flight-info">
                                <strong>DETAILS:</strong> 2 passengers • Business
                            </div>
                            <div class="price-info">
                                <strong>REQUESTED PRICE:</strong> 
                                <span class="price">₱2,400</span>
                                <span class="market-badge">15% below market</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="request-actions">
                        <button class="btn-accept">
                            <i class="fas fa-check"></i>
                            Accept Request
                        </button>
                        <button class="btn-negotiate">
                            <i class="fas fa-comments"></i>
                            Negotiate
                        </button>
                        <button class="btn-decline">
                            <i class="fas fa-times"></i>
                            Decline
                        </button>
                    </div>
                    
                    <div class="request-meta">
                        <span><i class="fas fa-clock"></i> Received 2 hours ago</span>
                        <span><i class="fas fa-user"></i> Customer since 2025</span>
                        <span><i class="fas fa-map-marker-alt"></i> New York, USA</span>
                    </div>
                </div>

                <!-- Request Card 2 -->
                <div class="request-card">
                    <div class="request-header">
                        <div class="request-info">
                            <i class="fas fa-plane"></i>
                            <span class="request-id">Request #BR-002</span>
                        </div>
                        <div class="request-badges">
                            <span class="priority-badge medium">MEDIUM</span>
                            <span class="status-badge pending">PENDING</span>
                            <span class="time-badge">
                                <i class="fas fa-clock"></i>
                                2h left
                            </span>
                        </div>
                    </div>
                    
                    <div class="request-body">
                        <div class="customer-info">
                            <div class="customer-avatar">JS</div>
                            <div class="customer-details">
                                <div class="customer-name">
                                    <span>John Smith</span>
                                    <span class="vip-badge">
                                        <i class="fas fa-star"></i>
                                        VIP
                                    </span>
                                </div>
                                <div class="customer-email">john.smith@email.com</div>
                            </div>
                        </div>
                        
                        <div class="flight-details">
                            <div class="flight-route">
                                <strong>ROUTE:</strong> Los Angeles (LAX) → London (LHR)
                            </div>
                            <div class="flight-info">
                                <strong>DETAILS:</strong> 1 passengers • Premium Economy
                            </div>
                            <div class="price-info">
                                <strong>REQUESTED PRICE:</strong> 
                                <span class="price">₱1,800</span>
                                <span class="market-badge">15% below market</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="request-actions">
                        <button class="btn-accept">
                            <i class="fas fa-check"></i>
                            Accept Request
                        </button>
                        <button class="btn-negotiate">
                            <i class="fas fa-comments"></i>
                            Negotiate
                        </button>
                        <button class="btn-decline">
                            <i class="fas fa-times"></i>
                            Decline
                        </button>
                    </div>
                    
                    <div class="request-meta">
                        <span><i class="fas fa-clock"></i> Received 4 hours ago</span>
                        <span><i class="fas fa-user"></i> Customer since 2025</span>
                        <span><i class="fas fa-map-marker-alt"></i> New York, USA</span>
                    </div>
                </div>

                <!-- Request Card 3 -->
                <div class="request-card">
                    <div class="request-header">
                        <div class="request-info">
                            <i class="fas fa-plane"></i>
                            <span class="request-id">Request #BR-003</span>
                        </div>
                        <div class="request-badges">
                            <span class="priority-badge low">LOW</span>
                            <span class="status-badge negotiating">NEGOTIATING</span>
                        </div>
                    </div>
                    
                    <div class="request-body">
                        <div class="customer-info">
                            <div class="customer-avatar">SJ</div>
                            <div class="customer-details">
                                <div class="customer-name">
                                    <span>Sarah Johnson</span>
                                    <span class="vip-badge">
                                        <i class="fas fa-star"></i>
                                        VIP
                                    </span>
                                </div>
                                <div class="customer-email">sarah.j@email.com</div>
                            </div>
                        </div>
                        
                        <div class="flight-details">
                            <div class="flight-route">
                                <strong>ROUTE:</strong> London (LHR) → Tokyo (NRT)
                            </div>
                            <div class="flight-info">
                                <strong>DETAILS:</strong> 1 passengers • Economy
                            </div>
                            <div class="price-info">
                                <strong>REQUESTED PRICE:</strong> 
                                <span class="price">₱850</span>
                                <span class="market-badge">15% below market</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="request-meta">
                        <span><i class="fas fa-clock"></i> Received 1 day ago</span>
                        <span><i class="fas fa-user"></i> Customer since 2025</span>
                        <span><i class="fas fa-map-marker-alt"></i> New York, USA</span>
                    </div>
                </div>

                <!-- Request Card 4 -->
                <div class="request-card">
                    <div class="request-header">
                        <div class="request-info">
                            <i class="fas fa-plane"></i>
                            <span class="request-id">Request #BR-004</span>
                        </div>
                        <div class="request-badges">
                            <span class="priority-badge high">HIGH</span>
                            <span class="status-badge accepted">ACCEPTED</span>
                        </div>
                    </div>
                    
                    <div class="request-body">
                        <div class="customer-info">
                            <div class="customer-avatar">MC</div>
                            <div class="customer-details">
                                <div class="customer-name">
                                    <span>Michael Chen</span>
                                    <span class="vip-badge">
                                        <i class="fas fa-star"></i>
                                        VIP
                                    </span>
                                </div>
                                <div class="customer-email">m.chen@email.com</div>
                            </div>
                        </div>
                        
                        <div class="flight-details">
                            <div class="flight-route">
                                <strong>ROUTE:</strong> San Francisco (SFO) → Amsterdam (AMS)
                            </div>
                            <div class="flight-info">
                                <strong>DETAILS:</strong> 3 passengers • Business
                            </div>
                            <div class="price-info">
                                <strong>REQUESTED PRICE:</strong> 
                                <span class="price">₱4,200</span>
                                <span class="market-badge">15% below market</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="request-actions">
                        <button class="btn-view">
                            <i class="fas fa-file-alt"></i>
                            View Booking
                        </button>
                        <button class="btn-contact">
                            <i class="fas fa-user"></i>
                            Contact Customer
                        </button>
                    </div>
                    
                    <div class="request-meta">
                        <span><i class="fas fa-clock"></i> Received 2 days ago</span>
                        <span><i class="fas fa-user"></i> Customer since 2025</span>
                        <span><i class="fas fa-map-marker-alt"></i> New York, USA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Content -->
        <div id="analytics-content" class="tab-content">
            <div class="analytics-placeholder">
                <h2>Analytics Dashboard</h2>
                <p>Detailed analytics and reporting features coming soon.</p>
            </div>
        </div>

        <!-- Settings Content -->
        <div id="settings-content" class="tab-content">
            <div class="settings-placeholder">
                <h2>Partner Settings</h2>
                <p>Configure your business settings and preferences.</p>
            </div>
        </div>
    </main>

    <script>
        // Business menu toggle
        function toggleBusinessMenu() {
            const menu = document.getElementById('businessMenu');
            if (menu) {
                menu.classList.toggle('show');
            }
        }

        // Ensure the dropdown is clickable
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.business-dropdown');
            if (dropdown) {
                dropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleBusinessMenu();
                });
            }
        });

        // Close business menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('businessMenu');
            const dropdown = document.querySelector('.business-dropdown');
            
            if (menu && dropdown && !dropdown.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.remove('show');
            }
        });


        // Tab switching
        function switchTab(tabName) {
            // Remove active class from all tabs and content
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab
            event.target.classList.add('active');
            
            // Show corresponding content
            document.getElementById(tabName + '-content').classList.add('active');
        }

        // Dark mode toggle
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const icon = document.querySelector('.nav-icon i');
            if (document.body.classList.contains('dark-mode')) {
                icon.className = 'fas fa-sun';
            } else {
                icon.className = 'fas fa-moon';
            }
        }

        // Initialize dark mode
        document.addEventListener('DOMContentLoaded', function() {
            const savedMode = localStorage.getItem('navigo-dark-mode');
            if (savedMode === 'true') {
                document.body.classList.add('dark-mode');
                const icon = document.querySelector('.nav-icon i');
                icon.className = 'fas fa-sun';
            }
        });
    </script>
</body>
</html>