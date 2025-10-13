<?php
/**
 * WP REST API Fonts route
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle fonts route
 *
 * @class WP_Grid_Builder\Includes\Routes\Fonts
 * @since 2.0.0
 */
final class Fonts extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'fonts',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_fonts' ],
			]
		);
	}

	/**
	 * Get fonts
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return WP_REST_Response
	 */
	public function get_fonts() {

		return $this->ensure_response(
			(array) apply_filters( 'wp_grid_builder/fonts', [] )
		);
	}
}
