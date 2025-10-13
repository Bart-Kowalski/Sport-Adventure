<?php

namespace Wpcb2\Service;


class SecretKey
{

    public function generateKey() {

        if(defined('NONCE_SALT')) {
            return sha1(NONCE_SALT);
        } else {
            if(function_exists('wp_get_environment_type') && wp_get_environment_type() === 'local') {
                return sha1('LOCAL_STRING');
            } else {
                return '';
            }
        }


    }

    public function checkSecretKey($secret) {

        if(defined('NONCE_SALT')) {
            return sha1(NONCE_SALT) === $secret;
        } else {
            if(function_exists('wp_get_environment_type') && wp_get_environment_type() === 'local') {
                return sha1('LOCAL_STRING') === $secret;
            } else {
                return false;
            }
        }
    }
}
