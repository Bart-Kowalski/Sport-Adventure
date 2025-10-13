<?php
/**
 * Grids
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Core;

use WP_Grid_Builder\Includes\File;
use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle grid core actions
 *
 * @class WP_Grid_Builder\Includes\Core\Grids
 * @since 2.0.0
 */
final class Grids {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'wp_grid_builder/delete/grids', [ $this, 'delete_stylesheets' ] );
		add_action( 'wp_grid_builder/save/grid', [ $this, 'delete_stylesheets' ] );

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

				// Delete related stylesheets.
				array_map(
					function( $file ) use ( $object_id ) {

						if ( stripos( $file['name'], 'G' . $object_id ) !== false ) {
							File::delete( 'grids', $file['name'] );
						}

					},
					File::get_files( 'grids' )
				);

				// Delete related transients.
				Helpers::delete_transient( 'G' . $object_id );

			},
			(array) $object_ids
		);
	}
}
