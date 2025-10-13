<?php
/**
 * Add GenerateBlocks Query block support
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
 * Handle GenerateBlocks Query block
 *
 * @class WP_Grid_Builder\Includes\Integrations\Blocks\Generateblocks_Query
 * @since 2.2.0
 */
class Generateblocks_Query extends Base {

	/**
	 * Block name.
	 *
	 * @since 2.2.0
	 * @access public
	 *
	 * @var string
	 */
	public $block_name = 'generateblocks/query';

	/**
	 * Handle block
	 *
	 * @since 2.2.0
	 * @access public
	 */
	public function handle_block() {

		add_filter( 'generateblocks_query_loop_args', [ $this, 'set_query' ], PHP_INT_MAX - 20, 2 );
		add_filter( 'render_block_generateblocks/query', [ $this, 'set_classname' ], PHP_INT_MAX - 20, 2 );

	}

	/**
	 * Set a class name to identify the block on the frontend
	 *
	 * @since 2.2.0
	 * @access public
	 *
	 * @param string   $block_content Block content.
	 * @param WP_Block $parsed_block  Block instance.
	 * @return string
	 */
	public function set_classname( $block_content, $parsed_block ) {

		if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
			return $block_content;
		}

		$query_id = $parsed_block['attrs']['metadata']['wpgb'] ?? 0;

		if ( empty( $query_id ) ) {
			return $block_content;
		}

		$tags = new \WP_HTML_Tag_Processor( $block_content );

		$tags->next_tag();
		$tags->add_class( sanitize_html_class( $query_id ) );
		$tags->set_attribute(
			'data-options',
			wp_json_encode(
				[
					'block'        => $this->block_name,
					'itemSelector' => '.' . sanitize_html_class( $query_id ) . ' [class*="gb-looper-"] > *',
				]
			)
		);

		return $tags->get_updated_html();

	}

	/**
	 * Set query variable to properly handle filtering
	 *
	 * @since 2.2.0
	 * @access public
	 *
	 * @param array $query      Holds query arguments.
	 * @param array $attributes Holds block attributes.
	 * @return array
	 */
	public function set_query( $query, $attributes ) {

		if ( isset( $query['wp_grid_builder'] ) ) {
			return $query;
		}

		$query_id = $attributes['metadata']['wpgb'] ?? 0;

		if ( ! empty( $query_id ) ) {
			$query['wp_grid_builder'] = $query_id;
		}

		return $query;

	}
}
