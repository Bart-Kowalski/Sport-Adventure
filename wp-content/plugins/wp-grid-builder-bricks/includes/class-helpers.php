<?php
/**
 * Helpers
 *
 * @package   WP Grid Builder - Bricks
 * @author    Loïc Blascos
 * @copyright 2019-2025 Loïc Blascos
 */

namespace WP_Grid_Builder_Bricks\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helpers
 *
 * @trait WP_Grid_Builder_Bricks\Includes\Helpers
 * @since 1.0.0
 */
trait Helpers {

	/**
	 * Whether it is the builder (Ajax requests)
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	final public function is_builder() {

		if ( bricks_is_builder() ) {
			return true;
		}

		$path = wp_parse_url( wp_get_referer() );

		if ( empty( $path['query'] ) ) {
			return false;
		}

		wp_parse_str( $path['query'], $output );

		if ( ! isset( $output['bricks'] ) ) {
			return false;
		}

		return 'run' === $output['bricks'];

	}

	/**
	 * Get bricks data from post ID
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param integer $post_id Post ID of the template to retrieve.
	 * @return array
	 */
	final public function get_bricks_data( $post_id ) {

		if ( empty( $post_id ) ) {
			return [];
		}

		if (
			! defined( 'BRICKS_DB_PAGE_HEADER' ) ||
			! defined( 'BRICKS_DB_PAGE_CONTENT' ) ||
			! defined( 'BRICKS_DB_PAGE_FOOTER' )
		) {
			return [];
		}

		return array_filter(
			array_merge(
				(array) get_post_meta( $post_id, BRICKS_DB_PAGE_HEADER, true ),
				(array) get_post_meta( $post_id, BRICKS_DB_PAGE_CONTENT, true ),
				(array) get_post_meta( $post_id, BRICKS_DB_PAGE_FOOTER, true )
			)
		);
	}
}
