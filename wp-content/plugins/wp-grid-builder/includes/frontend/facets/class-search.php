<?php
/**
 * Search facet
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
 * Search
 *
 * @class WP_Grid_Builder\Includes\FrontEnd\Facets\Search
 * @since 1.0.0
 */
class Search {

	/**
	 * Render facet
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $facet Holds facet settings.
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
			</label>%6$s',
			esc_html( $label ),
			esc_attr( $facet['slug'] ),
			esc_attr( $facet['search_placeholder'] ),
			esc_attr( $value ),
			$this->search_icon(),
			$this->clear_button()
		);

		$output  = '<div class="wpgb-search-facet">';
		$output .= $input;
		$output .= '</div>';

		return apply_filters( 'wp_grid_builder/facet/search', $output, $facet );

	}

	/**
	 * Search icon
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Search icon.
	 */
	public function search_icon() {

		$output  = '<svg class="wpgb-input-icon" viewBox="0 0 24 24" height="16" width="16" aria-hidden="true" focusable="false">';
		$output .= '<path d="M17.5 17.5 23 23Zm-16-7a9 9 0 1 1 9 9 9 9 0 0 1-9-9Z"/>';
		$output .= '</svg>';

		return $output;

	}

	/**
	 * Clear button
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Clear button.
	 */
	public function clear_button() {

		$output  = '<button type="button" class="wpgb-clear-button" hidden>';
		$output .= '<span class="wpgb-sr-only">' . esc_html__( 'Clear', 'wp-grid-builder' ) . '</span>';
		$output .= '<svg viewBox="0 0 24 24" height="24" width="24" aria-hidden="true" focusable="false">';
		$output .= '<path d="m12 12-4.25 4.75L12.001 12 7.75 7.25 12.001 12l4.249-4.75L12 12l4.25 4.75Z"/>';
		$output .= '</svg>';
		$output .= '</button>';

		return $output;

	}

	/**
	 * Query object ids
	 *
	 * @since 1.1.9 Add post_status in search query.
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $facet Holds facet settings.
	 * @return array Holds queried facet object ids.
	 */
	public function query_objects( $facet ) {

		$object = wpgb_get_queried_object_type();
		$search = $this->get_facet_value( $facet );
		$number = $facet['search_number'];

		switch ( $object ) {
			case 'post':
				$query_vars = wpgb_get_unfiltered_query_vars();
				$query['s'] = $search;
				$query['post_type'] = isset( $query_vars['post_type'] ) ? $query_vars['post_type'] : 'any';
				$query['post_status'] = isset( $query_vars['post_status'] ) ? $query_vars['post_status'] : 'any';
				$query['search_columns'] = isset( $facet['search_post_columns'] ) ? $facet['search_post_columns'] : [];
				$query = apply_filters( 'wp_grid_builder/facet/search_query_args', $query, $facet );
				return Helpers::get_post_ids( $query, $number );
			case 'term':
				$query['search'] = $search;
				$query = apply_filters( 'wp_grid_builder/facet/search_query_args', $query, $facet );
				return Helpers::get_term_ids( $query, $number );
			case 'user':
				$query['search'] = '*' . trim( $search ) . '*';
				$query['search_columns'] = isset( $facet['search_user_columns'] ) ? $facet['search_user_columns'] : [];
				$query = apply_filters( 'wp_grid_builder/facet/search_query_args', $query, $facet );
				return Helpers::get_user_ids( $query, $number );
		}
	}

	/**
	 * Get string to search.
	 *
	 * @since 1.0.0
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

	/**
	 * Query vars
	 *
	 * @since 1.1.5
	 * @access public
	 *
	 * @param array $facet Holds facet settings.
	 * @param array $query_vars Holds query vars.
	 * @return array Holds query vars to override.
	 */
	public function query_vars( $facet, $query_vars ) {

		if ( ! $facet['search_relevancy'] || ! empty( $query_vars['orderby'] ) ) {
			return;
		}

		return [
			'orderby' => 'post__in',
			'order'   => '',
		];
	}
}
