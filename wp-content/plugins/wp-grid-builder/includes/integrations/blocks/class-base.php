<?php
/**
 * Block core class
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
 * Handle Core Query block
 *
 * @class WP_Grid_Builder\Includes\Integrations\Blocks\Base
 * @since 2.1.0
 */
abstract class Base {

	/**
	 * Constructor
	 *
	 * @since 2.1.0
	 * @access public
	 */
	public function __construct() {

		$this->handle_block();
		add_filter( 'wp_grid_builder/block/providers', [ $this, 'block_provider' ] );

	}

	/**
	 * Add Post Template provider
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param array $providers Holds providers.
	 * @return array
	 */
	public function block_provider( $providers ) {

		return array_merge(
			[ $this->block_name ],
			$providers
		);
	}

	/**
	 * Retrieve query ID
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param object $block Holds block.
	 * @return array
	 */
	public function get_query_id( $block ) {

		return (
			$block->parsed_block['attrs']['metadata']['wpgb'] ??
			$block->parsed_block['attrs']['metadata']['wpgb_child'] ??
			0
		);
	}

	/**
	 * Set a custom attribute on child block to handle filtering correctly
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param WP_Block $parsed_block Block instance.
	 * @param WP_Block $source_block Block instance.
	 * @param WP_Block $parent_block Block instance.
	 * @return array
	 */
	public function intercept_child( $parsed_block, $source_block, $parent_block ) {

		if ( doing_filter( 'get_the_excerpt' ) ) {
			return $parsed_block;
		}

		$direct_child   = ( $parent_block->parsed_block['blockName'] ?? '' ) === $this->block_name;
		$indirect_child = $parent_block->parsed_block['attrs']['metadata']['wpgb_child'] ?? false;

		// If it is not a child of the block.
		if ( ! $direct_child && ! $indirect_child ) {
			return $parsed_block;
		}

		$query_id = $this->get_query_id( $parent_block );

		if ( empty( $query_id ) ) {
			return $parsed_block;
		}

		// We set the query block ID on each child of the block to properly match the templates.
		$parsed_block['attrs']['metadata']['wpgb_child'] = $query_id;

		return $parsed_block;

	}

	/**
	 * Set a class name to identify the block on the frontend
	 *
	 * @since 2.1.0
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

		return $tags->get_updated_html();

	}

	/**
	 * Set query variable to properly handle filtering
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param array    $query Array containing parameters for `WP_Query` as parsed by the block context.
	 * @param WP_Block $block Block instance.
	 * @return array
	 */
	public function set_query_var( $query, $block ) {

		if ( isset( $query['wp_grid_builder'] ) ) {
			return $query;
		}

		$query_id = $this->get_query_id( $block );

		if ( empty( $query_id ) ) {
			return $query;
		}

		$query['wp_grid_builder'] = $query_id;

		return $query;

	}

	/**
	 * Generates missing template styles if results are fewer than expected.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param string   $block_content Block content.
	 * @param WP_Block $parsed_block  Block instance.
	 * @return string
	 */
	public function generate_styles( $block_content, $parsed_block ) {

		if ( wpgb_doing_ajax() ) {
			return $block_content;
		}

		$query_id = $parsed_block['attrs']['metadata']['wpgb_child'] ?? 0;

		if ( empty( $query_id ) ) {
			return $block_content;
		}

		$found  = wpgb_get_found_objects();
		$query  = wpgb_get_filtered_query_vars();
		$number = (int) ( $query['number'] ?? $found );
		$offset = (int) ( $query['offset'] ?? 0 );

		// If filtered posts equal or exceed the number per page.
		if ( $found - $offset >= $number ) {
			return $block_content;
		}

		// To prevent an infinite loop during block rendering.
		$parsed_block['attrs']['metadata']['wpgb_child'] = false;
		// Render the block without filtering to generate all markups and styles.
		render_block( $parsed_block );

		return $block_content;

	}
}
