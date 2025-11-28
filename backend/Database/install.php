<?php
/**
 * NaviGo Database Installation Script
 * Run this script to set up the database for the first time
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'navigo_db';

// Check if database already exists
try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '$database'");
    $db_exists = $stmt->rowCount() > 0;
    
    if ($db_exists) {
        echo "<h2>Database '$database' already exists!</h2>";
        echo "<p>Do you want to:</p>";
        echo "<a href='?action=reinstall' style='background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Reinstall (WARNING: This will delete all data)</a>";
        echo "<a href='?action=update' style='background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Update Schema Only</a>";
        echo "<a href='../Index.php' style='background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Homepage</a>";
    } else {
        echo "<h2>Installing NaviGo Database...</h2>";
        install_database();
    }
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reinstall':
            reinstall_database();
            break;
        case 'update':
            update_database();
            break;
    }
}

function install_database() {
    global $host, $username, $password, $database;
    
    try {
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Read SQL file
        $sql = file_get_contents('navigo_database.sql');
        
        if ($sql === false) {
            throw new Exception("Could not read SQL file");
        }
        
        // Split SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        $success_count = 0;
        $error_count = 0;
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                try {
                    $pdo->exec($statement);
                    $success_count++;
                } catch(PDOException $e) {
                    $error_count++;
                    echo "<p style='color: red;'>Error executing statement: " . $e->getMessage() . "</p>";
                }
            }
        }
        
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>Installation Complete!</h3>";
        echo "<p>Successfully executed: $success_count statements</p>";
        if ($error_count > 0) {
            echo "<p style='color: #721c24;'>Errors encountered: $error_count statements</p>";
        }
        echo "</div>";
        
        // Test connection
        test_connection();
        
    } catch(Exception $e) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>Installation Failed!</h3>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
        echo "</div>";
    }
}

function reinstall_database() {
    global $host, $username, $password, $database;
    
    try {
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Drop database if exists
        $pdo->exec("DROP DATABASE IF EXISTS `$database`");
        echo "<p>Database dropped successfully.</p>";
        
        // Install fresh
        install_database();
        
    } catch(Exception $e) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>Reinstallation Failed!</h3>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
        echo "</div>";
    }
}

function update_database() {
    // For future updates - add migration logic here
    echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>Database Update</h3>";
    echo "<p>No updates available at this time.</p>";
    echo "</div>";
}

function test_connection() {
    global $host, $username, $password, $database;
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Test queries
        $tables = ['users', 'businesses', 'itineraries', 'bookings'];
        $table_status = [];
        
        foreach ($tables as $table) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $result = $stmt->fetch();
            $table_status[$table] = $result['count'];
        }
        
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>Database Connection Test</h3>";
        echo "<p><i class='fas fa-check-circle'></i> Connection successful!</p>";
        echo "<h4>Table Status:</h4>";
        echo "<ul>";
        foreach ($table_status as $table => $count) {
            echo "<li>$table: $count records</li>";
        }
        echo "</ul>";
        echo "</div>";
        
        // Show sample data
        show_sample_data($pdo);
        
    } catch(PDOException $e) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>Connection Test Failed!</h3>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
        echo "</div>";
    }
}

function show_sample_data($pdo) {
    try {
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>Sample Data</h3>";
        
        // Show sample users
        $stmt = $pdo->query("SELECT first_name, last_name, email FROM users LIMIT 3");
        $users = $stmt->fetchAll();
        
        if ($users) {
            echo "<h4>Sample Users:</h4>";
            echo "<ul>";
            foreach ($users as $user) {
                echo "<li>{$user['first_name']} {$user['last_name']} ({$user['email']})</li>";
            }
            echo "</ul>";
        }
        
        // Show sample businesses
        $stmt = $pdo->query("SELECT business_name, business_type, email FROM businesses LIMIT 3");
        $businesses = $stmt->fetchAll();
        
        if ($businesses) {
            echo "<h4>Sample Businesses:</h4>";
            echo "<ul>";
            foreach ($businesses as $business) {
                echo "<li>{$business['business_name']} ({$business['business_type']}) - {$business['email']}</li>";
            }
            echo "</ul>";
        }
        
        echo "</div>";
        
    } catch(PDOException $e) {
        echo "<p>Could not fetch sample data: " . $e->getMessage() . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NaviGo Database Installation</title>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/afed286f48.js" crossorigin="anonymous"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f8f9fa;
        }
        h1, h2, h3 {
            color: #2c3e50;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            font-weight: bold;
        }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-primary { background: #3498db; color: white; }
        .btn-success { background: #27ae60; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 NaviGo Database Installation</h1>
        <p>This script will set up the database for your NaviGo travel planning application.</p>
        
        <div class="warning">
            <h3><i class="fas fa-exclamation-triangle"></i> Important Notes:</h3>
            <ul>
                <li>Make sure your MySQL server is running</li>
                <li>Default configuration uses: host=localhost, username=root, password=(empty)</li>
                <li>If you need to change these settings, edit the variables at the top of this file</li>
                <li>This will create a database named 'navigo_db'</li>
            </ul>
        </div>
        
        <h2>Next Steps:</h2>
        <ol>
            <li>Run this installation script</li>
            <li>Test the database connection</li>
            <li>Update your application configuration if needed</li>
            <li>Start using your NaviGo application!</li>
        </ol>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="?install=1" class="btn btn-primary">Install Database</a>
            <a href="../Index.php" class="btn btn-success">Go to Homepage</a>
        </div>
    </div>
</body>
</html>
