<?php
/**
 * Styles
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
 * Handle style core actions
 *
 * @class WP_Grid_Builder\Includes\Core\Styles
 * @since 2.0.0
 */
final class Styles {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'wp_grid_builder/duplicate/styles', [ $this, 'generate_stylesheets' ] );
		add_action( 'wp_grid_builder/delete/styles', [ $this, 'delete_stylesheets' ] );
		add_action( 'wp_grid_builder/import/styles', [ $this, 'generate_stylesheets' ] );
		add_action( 'wp_grid_builder/save/style', [ $this, 'delete_stylesheets' ] );
		add_action( 'wp_grid_builder/save/style', [ $this, 'generate_stylesheets' ] );

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

		$styles = Database::query_results(
			[
				'select' => 'id, css',
				'from'   => 'styles',
				'id'     => (array) $object_ids,
			]
		);

		if ( empty( $styles ) ) {
			return;
		}

		array_map(
			function( $style ) {

				if ( empty( $style['css'] ) ) {
					return;
				}

				$css = str_replace( '.wpgb-style-preview', '.wpgb-style-' . $style['id'], $style['css'] );
				file::put_contents( 'styles', $style['id'] . '.css', $css );

			},
			$styles
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

				File::delete( 'styles', $object_id . '.css' );

				// Delete related grid stylesheets.
				array_map(
					function( $file ) use ( $object_id ) {

						if ( stripos( $file['name'], 'S' . $object_id ) !== false ) {
							File::delete( 'grids', $file['name'] );
						}
					},
					File::get_files( 'grids' )
				);

				// Delete related facet stylesheets.
				array_map(
					function( $file ) use ( $object_id ) {

						if ( stripos( $file['name'], 'S' . $object_id ) !== false ) {
							File::delete( 'facets', $file['name'] );
						}
					},
					File::get_files( 'facets' )
				);
			},
			(array) $object_ids
		);
	}
}
