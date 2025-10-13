<?php
/**
 * WP REST API Plugin options routes
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

use WP_Grid_Builder\Includes\File;
use WP_Grid_Builder\Includes\Settings\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle plugin options routes
 *
 * @class WP_Grid_Builder\Includes\Routes\Options
 * @since 2.0.0
 */
final class Options extends Base {

	/**
	 * Register custom routes
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'options',
			[
				[
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => [ $this, 'query' ],
				],
				[
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'save' ],
					'permission_callback' => [ $this, 'permission_callback' ],
					'args'                => [
						'options' => [
							'type'     => 'object',
							'required' => true,
						],
					],
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
				__( 'Sorry, you are not allowed to manage plugin settings on this site.', 'wp-grid-builder' ),
				[ 'status' => $this->authorization_status_code() ]
			);
		}

		return true;

	}

	/**
	 * Get plugin options
	 *
	 * @since 2.0.0
	 *
	 * @return WP_REST_Response
	 */
	public function query() {

		return $this->ensure_response( wpgb_get_options() );

	}

	/**
	 * Save plugin options
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function save( $request ) {

		$old_options = get_option( WPGB_SLUG . '_global_settings' );
		$new_options = ( new Settings() )->sanitize( 'options', $request->get_param( 'options' ) );
		$new_options = wp_parse_args( $new_options, $old_options );

		update_option( WPGB_SLUG . '_global_settings', $new_options );
		File::delete( 'grids' );
		File::delete( 'facets' );

		return $this->ensure_response( true, __( 'Settings Saved.', 'wp-grid-builder' ) );

	}
}
