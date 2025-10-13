<?php
/**
 * WP REST API Plugin cache route
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle plugin cache route
 *
 * @class WP_Grid_Builder\Includes\Routes\Cache
 * @since 2.0.0
 */
final class Cache extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'cache',
			[
				'methods'  => \WP_REST_Server::DELETABLE,
				'callback' => [ $this, 'clear' ],
			]
		);
	}

	/**
	 * Clear plugin cache
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return WP_REST_Response
	 */
	public function clear() {

		Helpers::delete_transient();

		return $this->ensure_response( true, __( 'Cache cleared.', 'wp-grid-builder' ) );

	}
}
