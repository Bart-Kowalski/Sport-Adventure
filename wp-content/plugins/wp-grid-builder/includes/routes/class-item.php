<?php
/**
 * WP REST API Item routes
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

use WP_Grid_Builder\Includes\Helpers;
use WP_Grid_Builder\Includes\Database;
use WP_Grid_Builder\Includes\Settings\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle item routes
 *
 * @class WP_Grid_Builder\Includes\Routes\Item
 * @since 2.0.0
 */
final class Item extends Base {

	/**
	 * Register custom routes
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'item/(?<id>[\d]+)',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'query' ],
				'args'     => [
					'object' => [
						'type'     => 'string',
						'required' => true,
						'enum'     => [ 'grids', 'cards', 'facets', 'styles' ],
					],
				],
			]
		);

		$this->register(
			'item',
			[
				'methods'  => \WP_REST_Server::CREATABLE,
				'callback' => [ $this, 'save' ],
				'args'     => [
					'object' => [
						'type'     => 'string',
						'required' => true,
						'enum'     => [ 'grids', 'cards', 'facets', 'styles' ],
					],
					'data'   => [
						'type'     => 'object',
						'required' => true,
					],
					'id'     => [
						'type'              => 'integer',
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
				],
			]
		);
	}

	/**
	 * Query item
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function query( $request ) {

		$params = $request->get_params();

		try {

			$item = Database::query_row(
				[
					'from' => $params['object'],
					'id'   => $params['id'],
				]
			);
		} catch ( \Exception $error ) {
			return $this->ensure_response( false, $error->getMessage(), 'error' );
		}

		if ( empty( $item ) ) {
			return $this->ensure_response( (object) [] );
		}

		return $this->ensure_response( Helpers::maybe_json_decode( $item ) );

	}

	/**
	 * Save item
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function save( $request ) {

		$params = $request->get_params();
		$object = $params['object'];
		$item   = rtrim( $object, 's' );
		$data   = $params['data'];
		$id     = $params['id'];

		if ( ! isset( $data['settings'] ) ) {
			return $this->ensure_response( false, __( 'Sorry, an unknown error occurred.', 'wp-grid-builder' ), 'error' );
		}

		if ( empty( $data['name'] ) ) {
			return $this->ensure_response( false, __( 'Please, enter a name to save the settings.', 'wp-grid-builder' ), 'error' );
		}

		do_action( 'wp_grid_builder/before_save/' . $item, $id, $data );

		try {

			$data = ( new Settings() )->sanitize( $item, $data );
			$id   = Database::save_row( $object, Helpers::maybe_json_encode( $data ), $id );

		} catch ( \Exception $error ) {
			return $this->ensure_response( false, $error->getMessage(), 'error' );
		}

		do_action( 'wp_grid_builder/save/' . $item, $id, $data );

		return $this->ensure_response( $id, __( 'Settings saved.', 'wp-grid-builder' ) );

	}
}
