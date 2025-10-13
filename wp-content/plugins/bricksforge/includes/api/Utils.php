<?php

namespace Bricksforge\Api;

if (!defined('ABSPATH')) {
    exit;
}

class Utils
{

    public function encrypt($string)
    {
        $method = 'aes-256-cbc';
        $secretKey = BRICKSFORGE_SECRET_KEY;
        $ivLength = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted_string = openssl_encrypt($string, $method, $secretKey, OPENSSL_RAW_DATA, $iv);
        $encrypted_string = base64_encode($encrypted_string . '::' . $iv);

        return $encrypted_string;
    }

    public function decrypt($encrypted_string)
    {
        $method = 'aes-256-cbc';
        $secretKey = BRICKSFORGE_SECRET_KEY;

        $parts = explode('::', base64_decode($encrypted_string), 2);

        if (count($parts) < 2) {
            return;
        }

        list($encrypted_data, $iv) = $parts;

        $decrypted_string = openssl_decrypt($encrypted_data, $method, $secretKey, OPENSSL_RAW_DATA, $iv);

        return $decrypted_string;
    }
}
