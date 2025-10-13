<?php
/**
 * Cards
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Core;

use WP_Grid_Builder\Includes\File;
use WP_Grid_Builder\Includes\Database;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle card core actions
 *
 * @class WP_Grid_Builder\Includes\Core\Cards
 * @since 2.0.0
 */
final class Cards {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'wp_grid_builder/duplicate/cards', [ $this, 'generate_stylesheets' ] );
		add_action( 'wp_grid_builder/delete/cards', [ $this, 'delete_stylesheets' ] );
		add_action( 'wp_grid_builder/import/cards', [ $this, 'generate_stylesheets' ] );
		add_action( 'wp_grid_builder/save/card', [ $this, 'delete_stylesheets' ] );
		add_action( 'wp_grid_builder/save/card', [ $this, 'generate_stylesheets' ] );

	}

	/**
	 * Generate stylesheets
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $object_ids Holds object ids to process.
	 */
	public function generate_stylesheets( $object_ids ) {

		$cards = Database::query_results(
			[
				'select' => 'id, css',
				'from'   => 'cards',
				'id'     => (array) $object_ids,
			]
		);

		if ( empty( $cards ) ) {
			return;
		}

		array_map(
			function( $card ) {

				if ( empty( $card['css'] ) ) {
					return;
				}

				$css = str_replace( '.wpgb-card-preview', '.wpgb-card-' . $card['id'], $card['css'] );
				file::put_contents( 'cards', $card['id'] . '.css', $css );

			},
			$cards
		);
	}

	/**
	 * Delete stylesheets
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param integer $object_ids Hold object ids to process.
	 */
	public function delete_stylesheets( $object_ids ) {

		array_map(
			function( $object_id ) {

				File::delete( 'cards', $object_id . '.css' );

				// Delete related stylesheets.
				array_map(
					function( $file ) use ( $object_id ) {

						if ( stripos( $file['name'], 'C' . $object_id ) !== false ) {
							File::delete( 'grids', $file['name'] );
						}
					},
					File::get_files( 'grids' )
				);
			},
			(array) $object_ids
		);
	}
}
