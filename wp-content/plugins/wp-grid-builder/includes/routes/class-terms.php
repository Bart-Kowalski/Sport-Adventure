<?php
/**
 * WP REST API Terms route
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\includes\Routes;

use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle terms route
 *
 * @class WP_Grid_Builder\Includes\Routes\Terms
 * @since 2.0.0
 */
final class Terms extends Base {

	/**
	 * Register custom route
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function register_routes() {

		$this->register(
			'terms',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_terms' ],
				'args'     => [
					'include' => [
						'type'              => 'array',
						'default'           => [],
						'sanitize_callback' => 'wp_parse_id_list',
					],
					'search'  => [
						'type'              => 'string',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'field'   => [
						'type'    => 'string',
						'default' => 'term_id',
						'enum'    => [ 'term_id', 'term_taxonomy_id' ],
					],
					'lang'    => [
						'type'              => 'string',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
	}

	/**
	 * Search for WordPress terms
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function get_terms( $request ) {

		$params = $request->get_params();

		return $this->ensure_response(
			$this->query(
				[
					'lang'             => $params['lang'],
					'number'           => count( $params['include'] ) ?: 20,
					'taxonomy'         => array_keys( Helpers::get_taxonomies() ),
					'include'          => 'term_taxonomy_id' !== $params['field'] ? $params['include'] : [],
					'term_taxonomy_id' => 'term_taxonomy_id' === $params['field'] ? $params['include'] : [],
					'search'           => trim( $params['search'] ),
					'hide_empty'       => false,
					'orderby'          => 'name',
					'order'            => 'ASC',
				],
				$params['field']
			)
		);
	}

	/**
	 * Query terms
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array  $query_args  Holds WP_Term_Query arguments.
	 * @param string $field Holds Taxonomy term field to return as value.
	 * @return array
	 */
	public function query( $query_args, $field ) {

		$terms = [];
		$query = new \WP_Term_Query( $query_args );

		if ( empty( $query->terms ) ) {
			return $terms;
		}

		foreach ( $query->terms as $term ) {

			if ( ! isset( $terms[ $term->taxonomy ] ) ) {

				$taxonomy = get_taxonomy( $term->taxonomy );

				$terms[ $term->taxonomy ] = [
					'label'   => false === $taxonomy ? $term->taxonomy : $taxonomy->labels->name,
					'options' => [],
				];
			}

			$terms[ $term->taxonomy ]['options'][] = [
				'value' => isset( $term->{ $field } ) ? $term->{ $field } : $term->term_id,
				'label' => sprintf(
					/* translators: %s: option name, %d: option count */
					__( '%1$s (#%2$d)', 'wp-grid-builder' ),
					html_entity_decode( wp_strip_all_tags( $term->name ?: $term->slug ), ENT_QUOTES, get_bloginfo( 'charset' ) ),
					$term->term_id
				),
			];
		}

		return $terms;

	}
}
