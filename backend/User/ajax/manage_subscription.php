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
if (!rate_limit_check($user_id, 3, 300)) { // 3 operations per 5 minutes
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many subscription operations. Please try again later.']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'change_plan') {
    $result = changeSubscriptionPlan($user_id, $_POST);
    echo json_encode($result);
} elseif ($action === 'cancel_subscription') {
    $result = cancelSubscription($user_id, $_POST);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function changeSubscriptionPlan($user_id, $data) {
    global $pdo;
    
    $plan_type = $data['plan_type'] ?? '';
    $valid_plans = ['free', 'premium', 'business'];
    
    if (!in_array($plan_type, $valid_plans)) {
        return ['success' => false, 'message' => 'Invalid plan type'];
    }
    
    try {
        $pdo->beginTransaction();
        
        // Get current subscription
        $stmt = $pdo->prepare("
            SELECT us.*, sp.plan_name, sp.price, sp.billing_cycle
            FROM user_subscriptions us
            JOIN subscription_plans sp ON us.plan_id = sp.id
            WHERE us.user_id = ? AND us.status = 'active'
        ");
        $stmt->execute([$user_id]);
        $current_subscription = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$current_subscription) {
            return ['success' => false, 'message' => 'No active subscription found'];
        }
        
        // Get new plan details
        $stmt = $pdo->prepare("
            SELECT id, plan_name, price, billing_cycle
            FROM subscription_plans
            WHERE plan_type = ?
        ");
        $stmt->execute([$plan_type]);
        $new_plan = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$new_plan) {
            return ['success' => false, 'message' => 'Plan not found'];
        }
        
        // If changing to free plan, cancel current subscription
        if ($plan_type === 'free') {
            $stmt = $pdo->prepare("
                UPDATE user_subscriptions 
                SET status = 'cancelled', cancelled_at = NOW(), updated_at = NOW()
                WHERE user_id = ? AND status = 'active'
            ");
            $stmt->execute([$user_id]);
            
            $pdo->commit();
            return ['success' => true, 'message' => 'Subscription cancelled and downgraded to free plan'];
        }
        
        // For paid plans, update the subscription
        $stmt = $pdo->prepare("
            UPDATE user_subscriptions 
            SET plan_id = ?, updated_at = NOW()
            WHERE user_id = ? AND status = 'active'
        ");
        $stmt->execute([$new_plan['id'], $user_id]);
        
        // Log the plan change
        $stmt = $pdo->prepare("
            INSERT INTO subscription_changes (user_id, old_plan_id, new_plan_id, change_reason, created_at)
            VALUES (?, ?, ?, 'user_requested', NOW())
        ");
        $stmt->execute([$user_id, $current_subscription['plan_id'], $new_plan['id']]);
        
        $pdo->commit();
        return ['success' => true, 'message' => 'Plan changed successfully to ' . $new_plan['plan_name']];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'message' => 'Failed to change plan: ' . $e->getMessage()];
    }
}

function cancelSubscription($user_id, $data) {
    global $pdo;
    
    $reason = $data['reason'] ?? '';
    $feedback = $data['feedback'] ?? '';
    
    try {
        $pdo->beginTransaction();
        
        // Get current subscription
        $stmt = $pdo->prepare("
            SELECT us.*, sp.plan_name
            FROM user_subscriptions us
            JOIN subscription_plans sp ON us.plan_id = sp.id
            WHERE us.user_id = ? AND us.status = 'active'
        ");
        $stmt->execute([$user_id]);
        $subscription = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$subscription) {
            return ['success' => false, 'message' => 'No active subscription found'];
        }
        
        // Cancel subscription
        $stmt = $pdo->prepare("
            UPDATE user_subscriptions 
            SET status = 'cancelled', cancelled_at = NOW(), updated_at = NOW()
            WHERE user_id = ? AND status = 'active'
        ");
        $stmt->execute([$user_id]);
        
        // Log cancellation reason
        $stmt = $pdo->prepare("
            INSERT INTO subscription_cancellations (user_id, reason, feedback, cancelled_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$user_id, $reason, $feedback]);
        
        // If using PayPal, cancel the subscription there too
        if ($subscription['payment_provider'] === 'paypal' && !empty($subscription['external_subscription_id'])) {
            $paypal_client_id = 'EFHK1wNFEWGJT0xfnMDD32TQb7j3nkmtsEJyt5eLUQpN_9EweyylUEJehAMMZT_1hAmyELhwNxbSjPwC';
            $paypal_client_secret = 'YOUR_PAYPAL_CLIENT_SECRET';
            $paypal_environment = 'sandbox';
            
            $paypal = new PayPal($paypal_client_id, $paypal_client_secret, $paypal_environment);
            $paypal_result = $paypal->cancelSubscription($subscription['external_subscription_id']);
            
            if (!$paypal_result['success']) {
                // Log the error but don't fail the cancellation
                error_log('PayPal cancellation failed: ' . $paypal_result['message']);
            }
        }
        
        $pdo->commit();
        return ['success' => true, 'message' => 'Subscription cancelled successfully'];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'message' => 'Failed to cancel subscription: ' . $e->getMessage()];
    }
}
?>