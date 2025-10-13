<?php
/**
 * Block categories
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	[
		'slug'  => 'design_blocks',
		'title' => __( 'Design', 'wp-grid-builder' ),
	],
	[
		'slug'  => 'common_blocks',
		'title' => __( 'Common', 'wp-grid-builder' ),
	],
	[
		'slug'  => 'post_blocks',
		'title' => __( 'Post', 'wp-grid-builder' ),
	],
	[
		'slug'  => 'term_blocks',
		'title' => __( 'Term', 'wp-grid-builder' ),
	],
	[
		'slug'  => 'user_blocks',
		'title' => __( 'User', 'wp-grid-builder' ),
	],
	(
		class_exists( 'WooCommerce' ) || class_exists( 'Easy_Digital_Downloads' ) ?
		[
			'slug'  => 'product_blocks',
			'title' => __( 'Product', 'wp-grid-builder' ),
		] : []
	),
];
