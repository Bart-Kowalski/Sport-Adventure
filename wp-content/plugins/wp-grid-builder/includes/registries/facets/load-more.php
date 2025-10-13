<?php
/**
 * Load More Facet
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
	'name'     => __( 'Load More', 'wp-grid-builder' ),
	'type'     => 'load',
	'icon'     => 'loadMore',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Load_More',
	'controls' => [
		[
			'type'   => 'fieldset',
			'legend' => __( 'Load More', 'wp-grid-builder' ),
			'fields' => [
				'load_posts_number' => [
					'type'  => 'number',
					'label' => __( 'Number to Load More', 'wp-grid-builder' ),
					'min'   => 0,
					'max'   => 100,
					'step'  => 1,
				],
				'load_more_event'   => [
					'type'    => 'button',
					'label'   => __( 'Trigger Loading on', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'onclick',
							'label' => __( 'Click', 'wp-grid-builder' ),
						],
						[
							'value' => 'onscroll',
							'label' => __( 'Scroll', 'wp-grid-builder' ),
						],
					],
				],
				'grid'              => [
					'type'   => 'grid',
					'fields' => [
						'load_more_text' => [
							'type'        => 'text',
							'label'       => __( 'Button Label', 'wp-grid-builder' ),
							'placeholder' => __( 'Enter a label', 'wp-grid-builder' ),
							'info'        => __( '[number] shortcode allows to display the number of remaining results.', 'wp-grid-builder' ),
						],
						'loading_text'   => [
							'type'        => 'text',
							'label'       => __( 'Loading Message', 'wp-grid-builder' ),
							'placeholder' => __( 'Enter a Message', 'wp-grid-builder' ),
						],
					],
				],
				'load_more_remain'  => [
					'type'  => 'toggle',
					'label' => __( 'Show Remaining Results', 'wp-grid-builder' ),
					'help'  => __( 'Displays the number of remaining results in the load more button.', 'wp-grid-builder' ),
				],
			],
		],
	],
];
