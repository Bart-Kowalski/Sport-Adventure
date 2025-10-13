<?php
/**
 * WP REST API Plugin license
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

use WP_Grid_Builder\Includes\License;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle plugin license routes
 *
 * @class WP_Grid_Builder\Includes\Routes\Plugin
 * @since 2.0.0
 */
final class Plugin extends Base {

	/**
	 * Register custom routes
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'plugin',
			[
				[
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'activate' ],
					'permission_callback' => [ $this, 'permission_callback' ],
					'args'                => [
						'email' => [
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_email',
						],
						'key'   => [
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'wp_filter_nohtml_kses',
						],
					],
				],
				[
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'deactivate' ],
					'permission_callback' => [ $this, 'permission_callback' ],
				],
				[
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => [ $this, 'update' ],
				],
			]
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
	public function permission_callback( $request ) {

		if ( ! current_user_can( 'manage_options' ) ) {

			return new \WP_Error(
				'rest_forbidden',
				__( 'Sorry, you are not allowed to manage plugin license on this site.', 'wp-grid-builder' ),
				[ 'status' => $this->authorization_status_code() ]
			);
		}

		return true;

	}

	/**
	 * Get plugin license
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_license() {

		return array_intersect_key(
			get_option( WPGB_SLUG . '_plugin_info', [] ),
			array_flip( [ 'license_limit', 'license_type', 'site_count', 'is_local', 'expires', 'addons' ] )
		);
	}

	/**
	 * Activate plugin license
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function activate( $request ) {

		$params   = $request->get_params();
		$response = ( new License() )->activate( $params['key'], $params['email'] );

		if ( is_wp_error( $response ) ) {
			return $this->ensure_response( $response );
		}

		return $this->ensure_response(
			$this->get_license(),
			__( 'License activated.', 'wp-grid-builder' )
		);
	}

	/**
	 * Deactivate plugin license
	 *
	 * @since 2.0.0
	 *
	 * @return WP_REST_Response
	 */
	public function deactivate() {

		$response = ( new License() )->deactivate();

		if ( is_wp_error( $response ) ) {
			return $this->ensure_response( $response );
		}

		return $this->ensure_response(
			$this->get_license(),
			__( 'License deactivated.', 'wp-grid-builder' )
		);
	}

	/**
	 * Update plugin license
	 *
	 * @since 2.0.0
	 *
	 * @return WP_ErrorWP_REST_Response
	 */
	public function update() {

		$response = get_transient( WPGB_SLUG . '_plugin_status' );

		// We allow an update every minute.
		if ( false === $response ) {

			$response = ( new License() )->get_status();
			set_transient( WPGB_SLUG . '_plugin_status', true, 60 );

		}

		if ( is_wp_error( $response ) ) {
			return $this->ensure_response( $response );
		}

		return $this->ensure_response(
			$this->get_license(),
			__( 'License updated.', 'wp-grid-builder' )
		);
	}
}
