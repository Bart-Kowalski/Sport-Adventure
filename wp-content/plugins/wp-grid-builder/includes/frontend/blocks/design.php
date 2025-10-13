<?php
/**
 * Design blocks
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Columns block
 *
 * @since 2.0.0
 *
 * @param array $block  Holds block args.
 * @param array $action Holds action args.
 */
function wpgb_columns_block( $block = [], $action = [] ) {

	if ( empty( $block['blocks'] ) ) {
		return;
	}

	wpgb_block_start( $block, $action );
		wpgb_render_blocks( $block );
	wpgb_block_end( $block, $action );

}

/**
 * Column block
 *
 * @since 2.0.0
 *
 * @param array $block  Holds block args.
 * @param array $action Holds action args.
 */
function wpgb_column_block( $block = [], $action = [] ) {

	wpgb_block_start( $block, $action );
		wpgb_render_blocks( $block );
	wpgb_block_end( $block, $action );

}

/**
 * Group block
 *
 * @since 2.0.0
 *
 * @param array $block  Holds block args.
 * @param array $action Holds action args.
 */
function wpgb_group_block( $block = [], $action = [] ) {

	wpgb_block_start( $block, $action );
		wpgb_render_blocks( $block );
	wpgb_block_end( $block, $action );

}

/**
 * Button block
 *
 * @since 2.0.0
 *
 * @param array $block  Holds block args.
 * @param array $action Holds action args.
 */
function wpgb_button_block( $block = [], $action = [] ) {

	if ( empty( $block['text'] ) ) {
		return;
	}

	wpgb_block_start( $block, $action );
		echo wp_kses_post(
			do_shortcode(
				apply_filters( 'wp_grid_builder/dynamic_data', $block['text'], 'card' )
			)
		);
	wpgb_block_end( $block, $action );

}
