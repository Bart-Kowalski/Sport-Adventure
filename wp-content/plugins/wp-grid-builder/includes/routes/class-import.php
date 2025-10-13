<?php
/**
 * WP REST API Import routes
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

use WP_Grid_Builder\Includes\Database;
use WP_Grid_Builder\Includes\Helpers;
use WP_Grid_Builder\Includes\Settings\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle import routes
 *
 * @class WP_Grid_Builder\Includes\Routes\Import
 * @since 2.0.0
 */
final class Import extends Base {

	/**
	 * Register custom routes
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'import',
			[
				[
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => [ $this, 'get_demos' ],
				],
				[
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'import' ],
					'permission_callback' => [ $this, 'permission_callback' ],
					'args'                => [
						'content' => [
							'type'       => 'object',
							'required'   => true,
							'properties' => [
								'cards'    => [
									'type' => 'array',
								],
								'facets'   => [
									'type' => 'array',
								],
								'grids'    => [
									'type' => 'array',
								],
								'settings' => [
									'type' => 'object',
								],
							],
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

		if ( ! current_user_can( 'import' ) ) {

			return new \WP_Error(
				'rest_forbidden',
				__( 'Sorry, you are not allowed to import content into this site.', 'wp-grid-builder' ),
				[ 'status' => $this->authorization_status_code() ]
			);
		}

		return true;

	}

	/**
	 * Get demos content (grids, cards, facets)
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function get_demos( $request ) {

		$types   = $request->get_param( 'types' );
		$content = Helpers::file_get_contents( 'admin/json/demos.json' );
		$content = json_decode( $content, true );
		$content = apply_filters( 'wp_grid_builder/demos', $content );

		return $this->ensure_response( $content );

	}

	/**
	 * Import content (grids, cards, facets and settings)
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function import( $request ) {

		$objects  = [];
		$imported = 0;
		$settings = new Settings();
		$content  = $request->get_param( 'content' );
		$allowed  = [ 'grids', 'cards', 'facets', 'styles' ];

		if ( empty( $content ) ) {

			return $this->ensure_response(
				false,
				__( 'Sorry, no content could be imported.', 'wp-grid-builder' ),
				'error'
			);
		}

		foreach ( $content as $object => $items ) {

			if ( ! in_array( $object, $allowed, true ) || ! is_array( $items ) ) {
				continue;
			}

			$objects[ $object ] = [];

			foreach ( $items as $item ) {

				$data = Helpers::maybe_json_decode( $item );
				$data = $settings->sanitize( rtrim( $object, 's' ), $data );

				if ( empty( $data ) ) {
					continue;
				}

				if ( ! empty( $item['id'] ) ) {
					$data['id'] = (int) $item['id'];
				}

				$objects[ $object ][] = Database::import_row( $object, Helpers::maybe_json_encode( $data ) );
				++$imported;

			}

			do_action( 'wp_grid_builder/import/' . $object, $objects[ $object ] );

		}

		$objects['settings'] = $this->import_settings( $content, $imported );

		return $this->ensure_response(
			array_filter( $objects ),
			sprintf(
				/* translators: %d: number of imported items */
				_n( '%d item has been imported.', '%d items have been imported.', $imported, 'wp-grid-builder' ),
				$imported
			)
		);
	}

	/**
	 * Import settings
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param object  $content  Holds content to import.
	 * @param integer $imported Holds number of imported items.
	 * @return array
	 */
	public function import_settings( $content, &$imported ) {

		if ( empty( $content['settings'] ) ) {
			return [];
		}

		$settings = reset( $content['settings'] );

		if ( ! is_array( $settings ) ) {
			return [];
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			unset( $settings['uninstall'] );
		}

		$old_options = get_option( WPGB_SLUG . '_global_settings' );
		$new_options = ( new Settings() )->sanitize( 'options', $settings );
		$new_options = wp_parse_args( $new_options, $old_options );

		update_option( WPGB_SLUG . '_global_settings', $new_options );
		++$imported;

		return $new_options;

	}
}
