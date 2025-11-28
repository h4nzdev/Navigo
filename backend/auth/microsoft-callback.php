<?php
session_start();
require_once '../Config/Database.php';
require_once '../Config/functions.php';

// Handle Microsoft OAuth callback
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $clientId = 'YOUR_MICROSOFT_CLIENT_ID'; // Replace with your actual Microsoft Client ID
    $clientSecret = 'YOUR_MICROSOFT_CLIENT_SECRET'; // Replace with your actual Microsoft Client Secret
    $redirectUri = 'http://localhost/Navigo/auth/microsoft-callback.php';
    
    // Exchange code for access token
    $tokenUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
    $postData = [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'code' => $code,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $redirectUri,
        'scope' => 'openid email profile'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $tokenData = json_decode($response, true);
    
    if (isset($tokenData['access_token'])) {
        // Get user info from Microsoft
        $userInfoUrl = 'https://graph.microsoft.com/v1.0/me';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $tokenData['access_token']]);
        
        $userInfo = json_decode(curl_exec($ch), true);
        curl_close($ch);
        
        if (isset($userInfo['mail'])) {
            // Check if user exists in database
            $user = get_user_by_email($userInfo['mail']);
            
            if (!$user) {
                // Create new user account
                $first_name = $userInfo['givenName'] ?? 'Microsoft';
                $last_name = $userInfo['surname'] ?? 'User';
                $email = $userInfo['mail'];
                
                // Generate a random password for OAuth users
                $password = bin2hex(random_bytes(16));
                
                $user_id = register_user($first_name, $last_name, $email, $password);
                
                if ($user_id) {
                    $user = get_user_by_id($user_id);
                }
            }
            
            if ($user) {
                // Log the user in
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = 'personal';
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                
                // Close the popup and redirect parent window
                echo '<script>
                    if (window.opener) {
                        window.opener.location.href = "../User/user_landingpage.php";
                        window.close();
                    } else {
                        window.location.href = "../User/user_landingpage.php";
                    }
                </script>';
                exit();
            }
        }
    }
}

// If we get here, there was an error
echo '<script>
    if (window.opener) {
        window.opener.showNotification("Microsoft sign-in failed. Please try again.", "error");
        window.close();
    } else {
        alert("Microsoft sign-in failed. Please try again.");
        window.location.href = "../Login/user_login.php";
    }
</script>';
?>
