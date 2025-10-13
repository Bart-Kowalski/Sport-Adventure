<?php
/**
 * Base
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\FrontEnd\Sources;

use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base class.
 *
 * @class WP_Grid_Builder\Includes\FrontEnd\Sources\Base
 * @since 2.0.0
 */
class Base {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {}

	/**
	 * Set orderby and order clauses
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function orderby_clauses() {

		// If no default orderby value selected in the repeater.
		$this->query_args['order'] = $this->settings->order;

		$clauses = $this->parse_orderby( $this->settings->order_clauses );

		// Backward compatibility for grids saved in versions lower than v2.0.0.
		if ( empty( $clauses['orderby'] ) ) {

			if ( ! empty( $this->settings->orderby ) ) {
				$this->query_args['orderby'] = implode( ' ', (array) $this->settings->orderby );
			}

			if (
				isset( $this->query_args['orderby'] ) &&
				false !== strpos( $this->query_args['orderby'], 'meta_value' )
			) {
				$this->query_args['meta_key'] = $this->settings->meta_key;
			}

			return;

		}

		if ( empty( $clauses['orderby'] ) ) {
			return;
		}

		$this->query_args['order']      = '';
		$this->query_args['orderby']    = $clauses['orderby'];
		$this->query_args['meta_query'] = $clauses['meta_query'];

	}

	/**
	 * Parse orderby arguments
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $clauses Holds orderby clauses.
	 * @return array
	 */
	public function parse_orderby( $clauses ) {

		$orderby_clauses   = [];
		$metaquery_clauses = [];

		foreach ( (array) $clauses as $clause ) {

			if ( empty( $clause['orderby'] ) ) {
				continue;
			}

			$orderby = $clause['orderby'];
			$is_meta = in_array( $orderby, [ 'meta_value', 'meta_value_num' ], true );

			if ( $is_meta && empty( $clause['meta_key'] ) ) {
				continue;
			}

			if ( $is_meta ) {

				$metatype = 'meta_value_num' === $orderby ? 'NUMERIC' : '';
				$orderby  = $clause['meta_key'];

				$metaquery_clauses[ $orderby ] = [
					'key'     => $orderby,
					'type'    => $metatype,
					'compare' => 'EXISTS',
				];
			}

			$orderby_clauses[ $orderby ] = ! empty( $clause['order'] ) ? $clause['order'] : 'DESC';

		}

		return [
			'orderby'    => $orderby_clauses,
			'meta_query' => $metaquery_clauses,
		];
	}

	/**
	 * Set meta_query clause
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function meta_query_clauses() {

		$clauses = $this->settings->meta_query_clauses;

		// Backward compatibility for grids saved in versions lower than v2.0.0.
		if ( empty( $clauses ) ) {
			$clauses = $this->settings->meta_query;
		}

		if ( empty( $clauses ) ) {
			return;
		}

		$meta_query = $this->parse_meta_query( $clauses );

		// We keep orderby meta_query clauses if they exist.
		if ( ! empty( $this->query_args['meta_query'] ) ) {
			$this->query_args['meta_query'] = [ $this->query_args['meta_query'], $meta_query ];
		} else {
			$this->query_args['meta_query'] = $meta_query;
		}
	}

	/**
	 * Parse meta_query arguments
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $meta_query Holds meta_query args.
	 * @return array
	 */
	public function parse_meta_query( $meta_query ) {

		return array_map(
			function( $clause ) {

				$date_type  = [ 'DATE', 'DATETIME', 'TIME' ];
				$array_type = [ 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN' ];

				if (
					isset( $clause['value'], $clause['type'] ) &&
					in_array( $clause['type'], $date_type, true )
				) {

					$clause['value'] = explode( ' ', $clause['value'] );
					$clause['value'] = array_map( 'date', $clause['value'] );
					$clause['value'] = implode( ' ', $clause['value'] );

				}

				if (
					isset( $clause['value'], $clause['compare'] ) &&
					in_array( $clause['compare'], $array_type, true )
				) {

					// Set at least 2 values to match wpdb::prepare placeholders.
					$clause['value'] = explode( ',', $clause['value'] );
					$clause['value'] = array_pad( $clause['value'], 2, '' );

				} elseif ( is_array( $clause ) ) {
					$clause = $this->parse_meta_query( $clause );
				}

				return $clause;

			},
			(array) $meta_query
		);
	}

	/**
	 * Set tax_query clause
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function tax_query_clauses() {

		$clauses = $this->settings->tax_query_clauses;

		// Backward compatibility for grids saved in versions lower than v2.0.0.
		if ( empty( $clauses ) && ! empty( $this->settings->tax_query ) ) {

			$clauses = array_map(
				function( $term_id ) {

					return [
						'terms'            => (array) $term_id,
						'operator'         => $this->settings->tax_query_operator,
						'include_children' => (bool) $this->settings->tax_query_children,
					];
				},
				(array) $this->settings->tax_query
			);

			$clauses['relation'] = $this->settings->tax_query_relation;

		}

		if ( empty( $clauses ) ) {
			return;
		}

		$this->query_args['tax_query'] = $this->parse_tax_query( (array) $clauses );

	}

	/**
	 * Parse tax_query arguments
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $clauses Holds tax_query args.
	 * @return array
	 */
	public function parse_tax_query( $clauses ) {

		return array_filter(
			array_map(
				function( $clause ) {

					if ( ! is_array( $clause ) ) {
						return $clause;
					}

					if (
						! isset( $clause['terms'] ) &&
						! isset( $clause['operator'] ) &&
						! isset( $clause['include_children'] )
					) {
						return $this->parse_tax_query( $clause );
					}

					if ( empty( $clause['terms'] ) ) {
						return [];
					}

					$clause['field'] = 'term_taxonomy_id';

					if ( empty( $clause['include_children'] ) ) {
						return $clause;
					}

					$term = get_term( current( (array) $clause['terms'] ) );

					if ( is_wp_error( $term ) || empty( $term ) ) {
						return $clause;
					}

					$clause['taxonomy'] = $term->taxonomy;

					return $clause;

				},
				(array) $clauses
			)
		);
	}

	/**
	 * Get metadata
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function get_metadata() {

		$meta = array_map( 'current', (array) get_metadata( $this->post->object_type, $this->post->ID ) );
		$meta = array_map( 'maybe_unserialize', $meta );

		if ( isset( $meta[ '_' . WPGB_SLUG ] ) ) {

			$meta[ WPGB_SLUG ] = $meta[ '_' . WPGB_SLUG ];
			unset( $meta[ '_' . WPGB_SLUG ] );

		} else {
			$meta[ WPGB_SLUG ] = [];
		}

		$meta[ WPGB_SLUG ] = wp_parse_args(
			$meta[ WPGB_SLUG ],
			apply_filters( 'wp_grid_builder/defaults/post', [] )
		);

		$this->post->metadata = $meta;

	}

	/**
	 * Get media
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_media() {

		// Reset media.
		$this->post->post_media = null;

		switch ( $this->post->post_format ) {
			case 'gallery':
				return $this->get_gallery_media();
			case 'video':
				return $this->get_video_media();
			case 'audio':
				return $this->get_audio_media();
		}

		return [];

	}

	/**
	 * Get gallery format
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_gallery_media() {

		if ( empty( $this->post->metadata[ WPGB_SLUG ]['gallery_ids'] ) ) {
			return [];
		}

		return [
			'sources' => $this->post->metadata[ WPGB_SLUG ]['gallery_ids'],
		];
	}

	/**
	 * Get audio media
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_audio_media() {

		$sources = [
			$this->post->metadata[ WPGB_SLUG ]['mp3_url'],
			$this->post->metadata[ WPGB_SLUG ]['ogg_url'],
		];

		$sources = array_filter( $sources );

		if ( empty( $sources ) ) {
			return [];
		}

		return [
			'type'    => 'hosted',
			'sources' => $sources,
		];
	}

	/**
	 * Get video media
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_video_media() {

		$sources = [
			$this->post->metadata[ WPGB_SLUG ]['mp4_url'],
			$this->post->metadata[ WPGB_SLUG ]['ogv_url'],
			$this->post->metadata[ WPGB_SLUG ]['webm_url'],
		];

		$sources = array_filter( $sources );

		if ( ! empty( $sources ) ) {

			return [
				'type'    => 'hosted',
				'sources' => $sources,
			];
		}

		$embed = $this->post->metadata[ WPGB_SLUG ]['embed_video_url'];

		if ( empty( $embed ) ) {
			return [];
		}

		$providers = Helpers::get_embed_providers();

		foreach ( $providers as $provider => $media ) {

			if ( ! preg_match( $provider, $embed, $match ) ) {
				continue;
			}

			return [
				'type'    => 'embedded',
				'sources' => [
					'provider' => $media,
					'url'      => $match[0],
					'id'       => $match[1],
				],
			];
		}
	}
}
