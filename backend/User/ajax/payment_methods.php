<?php
session_start();
require_once '../../Config/functions.php';
require_once '../../Config/PayPal.php';

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
if (!rate_limit_check($user_id, 5, 300)) { // 5 operations per 5 minutes
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many payment operations. Please try again later.']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'add') {
    // Add payment method via PayPal
    $result = addPayPalPaymentMethod($user_id, $_POST);
    echo json_encode($result);
} elseif ($action === 'remove') {
    // Remove payment method
    $payment_method_id = $_POST['payment_method_id'] ?? '';
    $result = removePayPalPaymentMethod($user_id, $payment_method_id);
    echo json_encode($result);
} elseif ($action === 'set_primary') {
    // Set primary payment method
    $payment_method_id = $_POST['payment_method_id'] ?? '';
    $result = setPrimaryPaymentMethod($user_id, $payment_method_id);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function addPayPalPaymentMethod($user_id, $data) {
    global $pdo;
    
    // PayPal configuration
    $paypal_client_id = 'EFHK1wNFEWGJT0xfnMDD32TQb7j3nkmtsEJyt5eLUQpN_9EweyylUEJehAMMZT_1hAmyELhwNxbSjPwC';
    $paypal_client_secret = 'YOUR_PAYPAL_CLIENT_SECRET'; // You'll need to get this from PayPal Developer Console
    $paypal_environment = 'sandbox'; // or 'live' for production
    
    $paypal = new PayPal($paypal_client_id, $paypal_client_secret, $paypal_environment);
    
    // Prepare card data for PayPal
    $card_data = [
        'number' => $data['card_number'] ?? '',
        'expiry' => $data['expiry_month'] . $data['expiry_year'],
        'name' => $data['cardholder_name'] ?? '',
        'address_line_1' => $data['address_line_1'] ?? '',
        'city' => $data['city'] ?? '',
        'state' => $data['state'] ?? '',
        'postal_code' => $data['postal_code'] ?? '',
        'country_code' => $data['country_code'] ?? 'US'
    ];
    
    // Create payment method in PayPal
    $paypal_result = $paypal->createPaymentMethod($card_data);
    
    if (!$paypal_result['success']) {
        return $paypal_result;
    }
    
    // Save to database
    try {
        // Check if this is the first payment method (make it primary)
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_payment_methods WHERE user_id = ? AND is_active = 1");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();
        $is_primary = $result['count'] == 0;
        
        $stmt = $pdo->prepare("
            INSERT INTO user_payment_methods (
                user_id, payment_type, card_brand, last_four_digits, 
                expiry_month, expiry_year, cardholder_name, is_primary, payment_token
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $user_id,
            'card',
            $paypal_result['card_brand'],
            $paypal_result['last_four_digits'],
            $paypal_result['expiry_month'],
            $paypal_result['expiry_year'],
            $data['cardholder_name'],
            $is_primary ? 1 : 0,
            $paypal_result['token_id']
        ]);
        
        return [
            'success' => true, 
            'message' => 'Payment method added successfully.',
            'is_primary' => $is_primary
        ];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to save payment method to database.'];
    }
}

function removePayPalPaymentMethod($user_id, $payment_method_id) {
    global $pdo;
    
    try {
        // Get payment token from database
        $stmt = $pdo->prepare("SELECT payment_token FROM user_payment_methods WHERE id = ? AND user_id = ?");
        $stmt->execute([$payment_method_id, $user_id]);
        $payment_token = $stmt->fetchColumn();
        
        if (!$payment_token) {
            return ['success' => false, 'message' => 'Payment method not found.'];
        }
        
        // Delete from PayPal
        $paypal_client_id = 'EFHK1wNFEWGJT0xfnMDD32TQb7j3nkmtsEJyt5eLUQpN_9EweyylUEJehAMMZT_1hAmyELhwNxbSjPwC';
        $paypal_client_secret = 'YOUR_PAYPAL_CLIENT_SECRET'; // You'll need to get this from PayPal Developer Console
        $paypal_environment = 'sandbox';
        
        $paypal = new PayPal($paypal_client_id, $paypal_client_secret, $paypal_environment);
        $paypal_result = $paypal->deletePaymentMethod($payment_token);
        
        // Remove from database
        $stmt = $pdo->prepare("
            UPDATE user_payment_methods 
            SET is_active = 0, updated_at = NOW()
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$payment_method_id, $user_id]);
        
        return ['success' => true, 'message' => 'Payment method removed successfully.'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to remove payment method.'];
    }
}

function setPrimaryPaymentMethod($user_id, $payment_method_id) {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Remove primary status from all payment methods
        $stmt = $pdo->prepare("
            UPDATE user_payment_methods 
            SET is_primary = 0, updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);
        
        // Set new primary payment method
        $stmt = $pdo->prepare("
            UPDATE user_payment_methods 
            SET is_primary = 1, updated_at = NOW()
            WHERE id = ? AND user_id = ? AND is_active = 1
        ");
        $stmt->execute([$payment_method_id, $user_id]);
        
            $pdo->commit();
        return ['success' => true, 'message' => 'Primary payment method updated successfully.'];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'message' => 'Failed to update primary payment method.'];
    }
}
?>
