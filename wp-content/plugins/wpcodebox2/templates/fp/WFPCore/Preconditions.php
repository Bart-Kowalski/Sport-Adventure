<?php

namespace WFPCore;

class Preconditions
{

    public function is_wpcb_request()
    {

		if(!defined('WPCODEBOX2_VERSION')) {
			return false;
		}

        // Detect WPCB request and don't execute snippets
        if (isset($_GET['wpcb2_route'])) {
            if (!function_exists('getallheaders')) {
                function getallheaders()
                {
                    $headers = [];
                    foreach ($_SERVER as $name => $value) {
                        if (substr($name, 0, 5) == 'HTTP_') {
                            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                        }
                    }
                    return $headers;
                }
            }

            $headers = array_change_key_case(getallheaders(), CASE_LOWER);

            $secret = $headers['x-wpcb-secret'];

            if ($this->checkSecretKey($secret)) {
                return true;
            }

        }

        return false;
    }

	function output_autoreload()
	{
		// Output autoReload code if WPCodeBox is installed
		if(class_exists('Wpcb2\Snippet\GlobalCSS')) {
			$autoReload = new \Wpcb2\Snippet\GlobalCSS();
			$autoReload->outputAutoreload();
			$wpcodeboxInstalled = true;
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

	function safe_mode()
	{
		return (( $_SERVER['REQUEST_URI'] === '/wp-admin/tools.php?page=wpcodebox2&safe_mode=1'
			|| $_SERVER['REQUEST_URI'] === '/wp-admin/admin.php?page=wpcodebox2&safe_mode=1' )) || defined( 'WPCB_SAFE_MODE' );
	}
}
