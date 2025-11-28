<?php
session_start();
require_once '../../Config/functions.php';
require_once '../../Config/TOTP.php';

// Check if user is logged in
if (!is_logged_in() || get_user_type() !== 'personal') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check CSRF token
if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

// Check rate limiting
$user_id = get_current_user_id();
if (!rate_limit_check($user_id, 3, 300)) { // 3 attempts per 5 minutes
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many 2FA attempts. Please try again later.']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'enable') {
    $result = enable_2fa($user_id);
    echo json_encode($result);
} elseif ($action === 'disable') {
    $result = disable_2fa($user_id);
    echo json_encode($result);
} elseif ($action === 'verify') {
    $code = $_POST['code'] ?? '';
    $result = verify_2fa_code($user_id, $code);
    echo json_encode($result);
} elseif ($action === 'get_qr') {
    $result = get_2fa_qr_code($user_id);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function enable_2fa($user_id) {
    global $pdo;
    
    // Generate secret key for TOTP
    $secret = TOTP::generateSecret();
    $backup_codes = generate_backup_codes();
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO user_security_settings (user_id, two_factor_secret, two_factor_backup_codes)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE
                two_factor_secret = VALUES(two_factor_secret),
                two_factor_backup_codes = VALUES(two_factor_backup_codes),
                updated_at = NOW()
        ");
        
        $stmt->execute([$user_id, $secret, json_encode($backup_codes)]);
        
        return [
            'success' => true, 
            'secret' => $secret, 
            'backup_codes' => $backup_codes,
            'message' => '2FA setup initiated. Please scan QR code and verify.'
        ];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to enable 2FA.'];
    }
}

function verify_2fa_code($user_id, $code) {
    global $pdo;
    
    // Get user's 2FA secret
    $stmt = $pdo->prepare("SELECT two_factor_secret FROM user_security_settings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $secret = $stmt->fetchColumn();
    
    if (!$secret) {
        return ['success' => false, 'message' => '2FA not set up for this user.'];
    }
    
    // Verify TOTP code
    $totp = new TOTP($secret);
    if ($totp->verify($code)) {
        // Enable 2FA
        $stmt = $pdo->prepare("
            UPDATE user_security_settings 
            SET two_factor_enabled = 1, updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);
        
        return ['success' => true, 'message' => '2FA enabled successfully.'];
    } else {
        return ['success' => false, 'message' => 'Invalid verification code.'];
    }
}

function get_2fa_qr_code($user_id) {
    global $pdo;
    
    // Get user's 2FA secret
    $stmt = $pdo->prepare("SELECT two_factor_secret FROM user_security_settings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $secret = $stmt->fetchColumn();
    
    if (!$secret) {
        return ['success' => false, 'message' => '2FA not set up for this user.'];
    }
    
    // Get user info
    $user = get_user_by_id($user_id);
    $issuer = 'NaviGo';
    $account = $user['email'];
    
    $totp = new TOTP($secret);
    $qr_url = $totp->getQRCodeUrl($issuer, $account);
    $qr_code_url = QRCode::generate($qr_url);
    
    return [
        'success' => true,
        'qr_code_url' => $qr_code_url,
        'secret' => $secret,
        'manual_entry_key' => $secret
    ];
}

function disable_2fa($user_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            UPDATE user_security_settings 
            SET two_factor_enabled = 0, two_factor_secret = NULL, two_factor_backup_codes = NULL, updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);
        
        return ['success' => true, 'message' => '2FA disabled successfully.'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to disable 2FA.'];
    }
}
?>