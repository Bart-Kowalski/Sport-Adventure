<?php
/**
 * Add Core Latest Posts block support
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Integrations\Blocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Core Latest Posts block
 *
 * @class WP_Grid_Builder\Includes\Integrations\Blocks\Core_Latest_Posts
 * @since 2.1.0
 */
class Core_Latest_Posts extends Base {

	/**
	 * Block name.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @var string
	 */
	public $block_name = 'core/latest-posts';

	/**
	 * Holds block query ID.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @var string
	 */
	public $query_id = '';

	/**
	 * Handle block
	 *
	 * @since 2.1.0
	 * @access public
	 */
	public function handle_block() {

		add_filter( 'render_block_data', [ $this, 'intercept_block' ], PHP_INT_MAX - 20, 2 );

	}

	/**
	 * Set a class name to identify the block on the frontend
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param WP_Block $parsed_block  Block instance.
	 * @return array
	 */
	public function intercept_block( $parsed_block ) {

		if ( doing_filter( 'get_the_excerpt' ) ) {
			return $parsed_block;
		}

		if (
			! isset( $parsed_block['blockName'] ) ||
			$this->block_name !== $parsed_block['blockName']
		) {
			return $parsed_block;
		}

		$this->query_id = $parsed_block['attrs']['metadata']['wpgb'] ?? 0;

		if ( empty( $this->query_id ) ) {
			return $parsed_block;
		}

		$classname = sanitize_html_class( $this->query_id );

		if ( ! isset( $parsed_block['attrs']['className'] ) ) {
			$parsed_block['attrs']['className'] = '';
		}

		$parsed_block['attrs']['className'] .= ' ' . $classname;
		$parsed_block['attrs']['className']  = trim( $parsed_block['attrs']['className'] );

		add_action( 'pre_get_posts', [ $this, 'set_query' ] );

		return $parsed_block;

	}

	/**
	 * Set query variable to properly handle filtering
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param \WP_Query $query Holds query instance.
	 */
	public function set_query( $query ) {

		if ( ! empty( $this->query_id ) ) {

			$query->set( 'wp_grid_builder', $this->query_id );
			$this->query_id = '';

		}

		remove_action( 'pre_get_posts', [ $this, 'set_query' ] );

	}
}
