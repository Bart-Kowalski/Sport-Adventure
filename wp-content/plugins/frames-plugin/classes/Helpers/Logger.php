<?php
/**
 * Frames Logger file.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Helpers;

use Frames_Client\Plugin;

/**
 * Frames Logger class.
 */
class Logger {

	/**
	 * Undocumented function
	 *
	 * @param boolean $enabled Enable or disable debugging.
	 */
	public function __construct( $enabled = false ) {
		if ( ! defined( 'FRAMES_DEBUG_LOG' ) ) {
			define( 'FRAMES_DEBUG_LOG', (bool) $enabled );
		}
	}

	/**
	 * Log whatever is being passed as parameter.
	 *
	 * @param mixed $what What to log.
	 * @return void
	 */
	public static function log( $what ) {
		if ( ! defined( 'FRAMES_DEBUG_LOG' ) || true !== FRAMES_DEBUG_LOG ) {
			return;
		}
		$message = is_array( $what ) || is_object( $what ) ? print_r( $what, true ) : $what;
		$debug_dir = Plugin::get_dynamic_css_dir();
		if ( ! file_exists( $debug_dir ) ) {
			wp_mkdir_p( $debug_dir );
		}
		$debug_file = $debug_dir . '/debug.log';
		$error_log = defined( 'WP_DEBUG' ) ? (bool) WP_DEBUG : false;
		if ( is_writable( $debug_file ) || ( ! file_exists( $debug_file ) && is_writable( $debug_dir ) ) ) {
			// either the file exists and is writable, or it doesn't exist but the directory is writable.
			$message .= "\n";
			file_put_contents( $debug_file, $message, FILE_APPEND );
			$error_log = false; // don't error_log this.
		}
		if ( $error_log ) {
			error_log( $message );
		}
	}

	/**
	 * Get the requested URI with sensitive data redacted.
	 *
	 * @return string
	 */
	public static function get_redacted_uri() {
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? filter_var( wp_unslash( $_SERVER['REQUEST_URI'] ), FILTER_SANITIZE_URL ) : '';
		$params_to_redact = array( 'username', 'user', 'password', 'pass', 'nonce' );
		$params_to_redact_regex = implode( '|', $params_to_redact );
		$redacted_uri = preg_replace( '/(\?|&)(' . $params_to_redact_regex . ')=([^&]+)/i', '$1$2=[redacted]', $uri );
		return $redacted_uri;
	}

}
