<?php
/**
 * Simple TOTP (Time-based One-Time Password) Implementation
 * Compatible with Google Authenticator and other TOTP apps
 */

class TOTP {
    private $secret;
    private $digits = 6;
    private $period = 30;
    
    public function __construct($secret, $digits = 6, $period = 30) {
        $this->secret = $secret;
        $this->digits = $digits;
        $this->period = $period;
    }
    
    /**
     * Generate TOTP code for current time
     */
    public function generate() {
        $time = floor(time() / $this->period);
        return $this->generateHOTP($time);
    }
    
    /**
     * Generate HOTP (HMAC-based One-Time Password)
     */
    private function generateHOTP($counter) {
        $secret = $this->base32Decode($this->secret);
        $time = pack('N*', 0) . pack('N*', $counter);
        $hash = hash_hmac('sha1', $time, $secret, true);
        $offset = ord($hash[19]) & 0xf;
        $code = (
            ((ord($hash[$offset + 0]) & 0x7f) << 24) |
            ((ord($hash[$offset + 1]) & 0xff) << 16) |
            ((ord($hash[$offset + 2]) & 0xff) << 8) |
            (ord($hash[$offset + 3]) & 0xff)
        ) % pow(10, $this->digits);
        
        return str_pad($code, $this->digits, '0', STR_PAD_LEFT);
    }
    
    /**
     * Verify TOTP code
     */
    public function verify($code, $window = 1) {
        $time = floor(time() / $this->period);
        
        for ($i = -$window; $i <= $window; $i++) {
            if ($this->generateHOTP($time + $i) === $code) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Generate QR code URL for Google Authenticator
     */
    public function getQRCodeUrl($issuer, $account) {
        $secret = $this->secret;
        $issuer = urlencode($issuer);
        $account = urlencode($account);
        
        return "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}";
    }
    
    /**
     * Base32 decode
     */
    private function base32Decode($data) {
        $data = strtoupper($data);
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $output = '';
        $v = 0;
        $vbits = 0;
        
        for ($i = 0, $j = strlen($data); $i < $j; $i++) {
            $v <<= 5;
            if (($pos = strpos($alphabet, $data[$i])) !== false) {
                $v += $pos;
                $vbits += 5;
                
                if ($vbits >= 8) {
                    $output .= chr(($v >> ($vbits - 8)) & 255);
                    $vbits -= 8;
                }
            }
        }
        
        return $output;
    }
    
    /**
     * Generate random secret
     */
    public static function generateSecret($length = 20) {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        
        for ($i = 0; $i < $length; $i++) {
            $secret .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        
        return $secret;
    }
}

/**
 * QR Code Generator (Simple implementation)
 */
class QRCode {
    public static function generate($data, $size = 200) {
        // For production, you'd want to use a proper QR code library
        // This is a simple implementation that creates a QR code URL
        $encoded_data = urlencode($data);
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encoded_data}";
    }
}
?>
