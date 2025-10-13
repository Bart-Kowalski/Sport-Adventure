<?php
/**
 * Add Kadence Blocks Query Loop block support
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
 * Handle Kadence Query block
 *
 * @class WP_Grid_Builder\Includes\Integrations\Blocks\Kadence_Query
 * @since 2.1.5
 */
class Kadence_Query extends Base {

	/**
	 * Block name.
	 *
	 * @since 2.1.5
	 * @access public
	 *
	 * @var string
	 */
	public $block_name = 'kadence/query';

	/**
	 * Holds block query ID.
	 *
	 * @since 2.1.5
	 * @access public
	 *
	 * @var string
	 */
	public $query_id = '';

	/**
	 * Handle block
	 *
	 * @since 2.1.5
	 * @access public
	 */
	public function handle_block() {

		add_filter( 'render_block_data', [ $this, 'intercept_block' ], PHP_INT_MAX - 20, 3 );
		add_filter( 'kadence_blocks_pro_query_loop_query_vars', [ $this, 'set_query' ], PHP_INT_MAX - 20 );
		add_filter( 'render_block_kadence/query', [ $this, 'set_classname' ], PHP_INT_MAX - 20, 2 );

	}

	/**
	 * Set a class name to identify the block on the frontend
	 *
	 * @since 2.1.5
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

		return $parsed_block;

	}

	/**
	 * Set query variable to properly handle filtering
	 *
	 * @since 2.1.5
	 * @access public
	 *
	 * @param \WP_Query $query Holds query instance.
	 */
	public function set_query( $query ) {

		if ( isset( $query['wp_grid_builder'] ) ) {
			return $query;
		}

		if ( ! empty( $this->query_id ) ) {
			$query['wp_grid_builder'] = $this->query_id;
		}

		$this->query_id = '';

		return $query;

	}
}
