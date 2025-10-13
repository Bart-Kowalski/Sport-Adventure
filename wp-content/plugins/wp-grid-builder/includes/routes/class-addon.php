<?php
/**
 * WP REST API Plugin addon routes
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
 * Handle plugin addon routes
 *
 * @class WP_Grid_Builder\Includes\Routes\Addon
 * @since 2.0.0
 */
final class Addon extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'addon',
			[
				[
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'download' ],
					'permission_callback' => [ $this, 'permission_callback' ],
				],
				[
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => [ $this, 'activate' ],
					'permission_callback' => [ $this, 'permission_callback' ],
				],
				[
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'deactivate' ],
					'permission_callback' => [ $this, 'permission_callback' ],
				],
				'args' => [
					'id' => [
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => 'sanitize_title',
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

		if ( ! current_user_can( 'install_plugins' ) ) {

			return new \WP_Error(
				'rest_forbidden',
				__( 'Sorry, you are not allowed to install plugins on this site.', 'wp-grid-builder' ),
				[ 'status' => $this->authorization_status_code() ]
			);
		}

		return true;

	}

	/**
	 * Get addon from registered addons
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param sring $id Addon identifier.
	 * @return array|WP_Error
	 */
	public function get_addon( $id ) {

		$addons = apply_filters( 'wp_grid_builder/addons', [] );

		if ( ! isset( $addons[ $id ]['name'], $addons[ $id ]['slug'] ) ) {

			return new \WP_Error(
				'error',
				__( 'Sorry, this addon does not exist.', 'wp-grid-builder' )
			);
		}

		return $addons[ $id ];

	}

	/**
	 * Get activated plugins
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_plugins() {

		return array_values( get_option( 'active_plugins', [] ) );

	}

	/**
	 * Check if add-on exist
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param sring $slug Plugin slug to check.
	 * @return boolean
	 */
	public function plugin_exists( $slug ) {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return isset( get_plugins()[ $slug ] );

	}

	/**
	 * Bypass active_plugins option cache.
	 * Prevent caching issues between multiple instances when activating plugins with the REST API.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param mixed $pre_option The value to return instead of the option value.
	 * @return mixed
	 */
	public function bypass_cache( $pre_option ) {

		global $wpdb;

		$row = $wpdb->get_row(
			"SELECT option_value FROM $wpdb->options
			WHERE option_name = 'active_plugins'
			LIMIT 1"
		);

		if ( empty( $row->option_value ) ) {
			return $pre_option;
		}

		return maybe_unserialize( $row->option_value );

	}

	/**
	 * Download plugin addon
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function download( $request ) {

		$addon = $this->get_addon( $request->get_param( 'id' ) );

		if ( is_wp_error( $addon ) ) {
			return $this->ensure_response( $addon );
		}

		if ( $this->plugin_exists( $addon['slug'] ) ) {
			return $this->ensure_response();
		}

		$license = ( new License( [ 'name' => $addon['name'] ] ) )->get_update();

		if ( empty( $license->package ) ) {

			return $this->ensure_response(
				false,
				__( 'Sorry, the plugin package was not found.', 'wp-grid-builder' ),
				'error'
			);
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$upgrader = new \WP_Ajax_Upgrader_Skin();
		$upgrader = new \Plugin_Upgrader( $upgrader );

		if ( ! $upgrader->install( $license->package ) ) {

			return $this->ensure_response(
				false,
				sprintf(
					/* translators: %s: Addon name */
					__( 'There was an error installing %s.', 'wp-grid-builder' ),
					$addon['name']
				),
				'error'
			);
		}

		return $this->ensure_response();

	}

	/**
	 * Deactivate plugin addon
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function activate( $request ) {

		add_filter( 'pre_option_active_plugins', [ $this, 'bypass_cache' ] );

		$addon = $this->get_addon( $request->get_param( 'id' ) );

		if ( is_wp_error( $addon ) ) {
			return $this->ensure_response( $addon );
		}

		if ( ! $this->plugin_exists( $addon['slug'] ) ) {
			return $this->ensure_response( $this->get_plugins() );
		}

		if ( is_plugin_active( $addon['slug'] ) ) {
			$activated = true;
		} else {
			$activated = activate_plugin( $addon['slug'] );
		}

		if ( is_wp_error( $activated ) ) {

			return $this->ensure_response(
				$this->get_plugins(),
				sprintf(
					/* translators: %s: Addon name */
					__( 'There was an error activating %s.', 'wp-grid-builder' ),
					$addon['name']
				),
				'error'
			);
		}

		return $this->ensure_response(
			$this->get_plugins(),
			sprintf(
				/* translators: %s: Addon name */
				__( '%s addon has been activated.', 'wp-grid-builder' ),
				$addon['name']
			)
		);
	}

	/**
	 * Deactivate plugin addon
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function deactivate( $request ) {

		$addon = $this->get_addon( $request->get_param( 'id' ) );

		if ( is_wp_error( $addon ) ) {
			return $this->ensure_response( $addon );
		}

		if ( ! $this->plugin_exists( $addon['slug'] ) ) {

			return $this->ensure_response(
				$this->get_plugins(),
				sprintf(
					/* translators: %s: Addon name */
					__( 'An error occurred while deactivating %s.', 'wp-grid-builder' ),
					$addon['name']
				),
				'error'
			);
		}

		deactivate_plugins( $addon['slug'] );

		return $this->ensure_response(
			$this->get_plugins(),
			sprintf(
				/* translators: %s: Addon name */
				__( '%s addon has been deactivated.', 'wp-grid-builder' ),
				$addon['name']
			)
		);
	}
}
