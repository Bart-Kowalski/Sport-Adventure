<?php
/**
 * WP REST API Base class
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
 * Handle REST API Routes
 *
 * @class WP_Grid_Builder\Includes\Routes\Base
 * @since 2.0.0
 */
class Base {

	/**
	 * Handle prefix
	 *
	 * @since 2.0.0
	 * @var string
	 */
	private $route_namespace = 'wpgb/v2';

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'rest_api_init', [ $this, 'register_routes' ] );

	}

	/**
	 * Register REST routes
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $route_name Route name.
	 * @param array  $route_args Holds route arguments.
	 */
	public function register( $route_name, $route_args ) {

		// Upgrade a single set to multiple.
		if ( isset( $route_args['callback'] ) ) {
			$route_args = [ $route_args ];
		}

		// Set permission_callback if missing.
		$route_args = array_map(
			function( $args ) {

				if ( empty( $args['permission_callback'] ) ) {
					$args['permission_callback'] = [ $this, 'permission' ];
				}

				return $args;

			},
			$route_args
		);

		register_rest_route(
			$this->route_namespace,
			'/' . $route_name,
			$route_args
		);
	}

	/**
	 * Handle REST API permission callback
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function permission( $request ) {

		if ( ! apply_filters( 'wp_grid_builder/admin_rest_api/permission', true, $request ) || ! Helpers::current_user_can() ) {

			return new \WP_Error(
				'rest_forbidden',
				__( 'You do not have enough permissions to complete the operation.', 'wp-grid-builder' ),
				[ 'status' => $this->authorization_status_code() ]
			);
		}

		return true;

	}

	/**
	 * Setup the proper HTTP status code for authorization.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return integer
	 */
	public function authorization_status_code() {

		$status = 401;

		if ( is_user_logged_in() ) {
			$status = 403;
		}

		return $status;

	}

	/**
	 * Ensure rest response
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param mixed  $data    Response data.
	 * @param string $message Response message.
	 * @param string $code    Response status code.
	 * @return WP_REST_Response
	 */
	public function ensure_response( $data = '', $message = '', $code = 'success' ) {

		if ( is_wp_error( $data ) ) {
			return rest_ensure_response( $data );
		}

		return rest_ensure_response(
			[
				'message' => $message,
				'data'    => $data,
				'code'    => $code,
			]
		);
	}
}
