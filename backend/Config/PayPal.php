<?php
/**
 * PayPal Integration Class
 * Handles PayPal payment methods and transactions
 */

class PayPal {
    private $client_id;
    private $client_secret;
    private $environment; // 'sandbox' or 'live'
    private $base_url;
    
    public function __construct($client_id, $client_secret, $environment = 'sandbox') {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->environment = $environment;
        $this->base_url = $environment === 'live' 
            ? 'https://api.paypal.com' 
            : 'https://api.sandbox.paypal.com';
    }
    
    /**
     * Get access token from PayPal
     */
    public function getAccessToken() {
        $url = $this->base_url . '/v1/oauth2/token';
        
        $data = 'grant_type=client_credentials';
        $headers = [
            'Accept: application/json',
            'Accept-Language: en_US',
            'Authorization: Basic ' . base64_encode($this->client_id . ':' . $this->client_secret),
            'Content-Type: application/x-www-form-urlencoded'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200) {
            $data = json_decode($response, true);
            return $data['access_token'];
        }
        
        return false;
    }
    
    /**
     * Create a payment method token
     */
    public function createPaymentMethod($card_data) {
        $access_token = $this->getAccessToken();
        if (!$access_token) {
            return ['success' => false, 'message' => 'Failed to get PayPal access token'];
        }
        
        $url = $this->base_url . '/v1/vault/payment-tokens';
        
        $payload = [
            'payment_source' => [
                'card' => [
                    'number' => $card_data['number'],
                    'expiry' => $card_data['expiry'],
                    'name' => $card_data['name'],
                    'billing_address' => [
                        'address_line_1' => $card_data['address_line_1'] ?? '',
                        'admin_area_2' => $card_data['city'] ?? '',
                        'admin_area_1' => $card_data['state'] ?? '',
                        'postal_code' => $card_data['postal_code'] ?? '',
                        'country_code' => $card_data['country_code'] ?? 'US'
                    ]
                ]
            ]
        ];
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'PayPal-Request-Id: ' . uniqid()
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 201) {
            $data = json_decode($response, true);
            return [
                'success' => true,
                'token_id' => $data['id'],
                'card_brand' => $data['payment_source']['card']['brand'] ?? 'Unknown',
                'last_four_digits' => substr($card_data['number'], -4),
                'expiry_month' => substr($card_data['expiry'], 0, 2),
                'expiry_year' => '20' . substr($card_data['expiry'], -2)
            ];
        }
        
        $error_data = json_decode($response, true);
        return [
            'success' => false,
            'message' => $error_data['message'] ?? 'Failed to create payment method'
        ];
    }
    
    /**
     * Delete a payment method
     */
    public function deletePaymentMethod($token_id) {
        $access_token = $this->getAccessToken();
        if (!$access_token) {
            return ['success' => false, 'message' => 'Failed to get PayPal access token'];
        }
        
        $url = $this->base_url . '/v1/vault/payment-tokens/' . $token_id;
        
        $headers = [
            'Authorization: Bearer ' . $access_token
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 204) {
            return ['success' => true, 'message' => 'Payment method deleted successfully'];
        }
        
        return ['success' => false, 'message' => 'Failed to delete payment method'];
    }
    
    /**
     * Create a subscription
     */
    public function createSubscription($plan_id, $payment_method_id) {
        $access_token = $this->getAccessToken();
        if (!$access_token) {
            return ['success' => false, 'message' => 'Failed to get PayPal access token'];
        }
        
        $url = $this->base_url . '/v1/billing/subscriptions';
        
        $payload = [
            'plan_id' => $plan_id,
            'subscriber' => [
                'payment_source' => [
                    'token' => [
                        'id' => $payment_method_id,
                        'type' => 'BILLING_AGREEMENT'
                    ]
                ]
            ]
        ];
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'PayPal-Request-Id: ' . uniqid()
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 201) {
            $data = json_decode($response, true);
            return [
                'success' => true,
                'subscription_id' => $data['id'],
                'status' => $data['status']
            ];
        }
        
        $error_data = json_decode($response, true);
        return [
            'success' => false,
            'message' => $error_data['message'] ?? 'Failed to create subscription'
        ];
    }
    
    /**
     * Cancel a subscription
     */
    public function cancelSubscription($subscription_id) {
        $access_token = $this->getAccessToken();
        if (!$access_token) {
            return ['success' => false, 'message' => 'Failed to get PayPal access token'];
        }
        
        $url = $this->base_url . '/v1/billing/subscriptions/' . $subscription_id . '/cancel';
        
        $payload = [
            'reason' => 'User requested cancellation'
        ];
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 204) {
            return ['success' => true, 'message' => 'Subscription cancelled successfully'];
        }
        
        return ['success' => false, 'message' => 'Failed to cancel subscription'];
    }
}
?>
