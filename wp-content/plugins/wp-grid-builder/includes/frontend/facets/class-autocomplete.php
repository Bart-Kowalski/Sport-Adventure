<?php
/**
 * Autocomplete facet
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\FrontEnd\Facets;

use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Autocomplete
 *
 * @class WP_Grid_Builder\Includes\FrontEnd\Facets\Autocomplete
 * @since 1.3.0
 */
class Autocomplete {

	/**
	 * Render facet
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @param array $facet  Holds facet settings.
	 * @return string Facet markup.
	 */
	public function render_facet( $facet ) {

		$label = $facet['title'] ?: __( 'Search content', 'wp-grid-builder' );
		$value = $this->get_facet_value( $facet );
		$input = sprintf(
			'<label>
				<span class="wpgb-sr-only">%1$s</span>
				<input class="wpgb-input" type="search" name="%2$s" placeholder="%3$s" value="%4$s" autocomplete="off">
				%5$s
			</label>',
			esc_html( $label ),
			esc_attr( $facet['slug'] ),
			esc_attr( $facet['acplt_placeholder'] ),
			esc_attr( $value ),
			$this->search_icon()
		);

		$output  = '<div class="wpgb-autocomplete-facet">';
		$output .= $input;
		$output .= '</div>';

		return apply_filters( 'wp_grid_builder/facet/autocomplete', $output, $facet );

	}

	/**
	 * Search icon
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Select icon.
	 */
	public function search_icon() {

		$output  = '<svg class="wpgb-input-icon" viewBox="0 0 24 24" height="16" width="16" aria-hidden="true" focusable="false">';
		$output .= '<path d="M17.5 17.5 23 23Zm-16-7a9 9 0 1 1 9 9 9 9 0 0 1-9-9Z"/>';
		$output .= '</svg>';

		return $output;

	}

	/**
	 * Query object ids
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @param array $facet Holds facet settings.
	 * @return array Holds queried facet object ids.
	 */
	public function query_objects( $facet ) {

		global $wpdb;

		// Because we escape HTML and decode entities on search.
		$search_value = $this->get_facet_value( $facet );
		$search_value = wp_specialchars_decode( $search_value, ENT_QUOTES );
		$like_clause  = $this->parse_search( $search_value );

		return $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT object_id
				FROM {$wpdb->prefix}wpgb_index
				WHERE slug = %s
				AND $like_clause", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$facet['slug']
			)
		);
	}

	/**
	 * Parse search string
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @param string $string Searched string to parse.
	 * @return array Search clauses
	 */
	public function parse_search( $string ) {

		global $wpdb;

		$terms = Helpers::split_into_words( $string );
		$terms = array_map(
			function( $term ) use ( $wpdb ) {
				return $wpdb->prepare( 'facet_name LIKE %s', '%' . $wpdb->esc_like( $term ) . '%' );
			},
			array_unique( $terms )
		);

		return implode( ' AND ', $terms );

	}


	/**
	 * Get string to search.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @param array $facet Holds facet settings.
	 * @return string Selected facet value.
	 */
	public function get_facet_value( $facet ) {

		// Revert array to string.
		$value = (array) $facet['selected'];
		$value = implode( ',', $value );

		return $value;

	}
}
