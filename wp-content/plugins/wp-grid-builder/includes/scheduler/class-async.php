<?php
/**
 * Background process (Modified version)
 *
 * @package WP-Background-Processing
 * https://github.com/A5hleyRich/wp-background-processing
 */

namespace WP_Grid_Builder\Includes\Scheduler;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle async requests.
 *
 * @class WP_Grid_Builder\Includes\Scheduler\Async
 * @since 1.0.0
 */
abstract class Async {

	/**
	 * Action
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @var mixed
	 */
	protected $action = 'wpgb_cron_request';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'wp_ajax_' . $this->action, [ $this, 'maybe_handle' ] );
		add_action( 'wp_ajax_nopriv_' . $this->action, [ $this, 'maybe_handle' ] );

	}

	/**
	 * Set data used during the request
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $data Data.
	 * @return $this
	 */
	public function data( $data ) {

		$this->data = $data;

		return $this;

	}

	/**
	 * Dispatch the async request
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array|WP_Error
	 */
	public function dispatch() {

		$url = add_query_arg( $this->get_query_args(), admin_url( 'admin-ajax.php' ) );

		return wp_remote_post( esc_url_raw( $url ), $this->get_post_args() );

	}

	/**
	 * Get query args
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_query_args() {

		return [
			'action' => $this->action,
			'nonce'  => wp_create_nonce( $this->action ),
		];
	}

	/**
	 * Get post args
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_post_args() {

		$args = [
			'body'      => '',
			'timeout'   => 0.01,
			'blocking'  => false,
			'cookies'   => $_COOKIE,
			'sslverify' => apply_filters( 'https_local_ssl_verify', false ),
		];

		if ( isset( $_SERVER['PHP_AUTH_USER'] ) && isset( $_SERVER['PHP_AUTH_PW'] ) ) {

			$args['headers'] = [
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
				'Authorization' => 'Basic ' . base64_encode( wp_unslash( $_SERVER['PHP_AUTH_USER'] ) . ':' . wp_unslash( $_SERVER['PHP_AUTH_PW'] ) ),
			];
		}

		return $args;

	}

	/**
	 * Maybe handle
	 *
	 * Check for correct nonce and pass to handler.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function maybe_handle() {

		// Don't lock up other requests while processing.
		session_write_close();

		check_ajax_referer( $this->action, 'nonce' );
		$this->handle();

		wp_die();

	}

	/**
	 * Handle
	 *
	 * Override this method to perform any actions required
	 * during the async request.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	abstract protected function handle();
}
