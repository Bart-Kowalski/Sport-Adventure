<?php
/**
 * Pagination Facet
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
	'name'     => __( 'Pagination', 'wp-grid-builder' ),
	'type'     => 'load',
	'icon'     => 'pagination',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Pagination',
	'controls' => [
		[
			'type'   => 'fieldset',
			'legend' => __( 'Pagination', 'wp-grid-builder' ),
			'fields' => [
				'pagination'           => [
					'type'    => 'button',
					'label'   => __( 'Pagination Type', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'numbered',
							'label' => _x( 'Numbered', 'Pagination type', 'wp-grid-builder' ),
						],
						[
							'value' => 'prev_next',
							'label' => __( 'Navigation Buttons', 'wp-grid-builder' ),
						],
					],
				],
				'grid1'                => [
					'type'   => 'grid',
					'fields' => [
						'prev_text' => [
							'type'        => 'text',
							'label'       => __( 'Prev Button Label', 'wp-grid-builder' ),
							'placeholder' => __( 'Enter a label', 'wp-grid-builder' ),
						],
						'next_text' => [
							'type'        => 'text',
							'label'       => __( 'Next Button Label', 'wp-grid-builder' ),
							'placeholder' => __( 'Enter a label', 'wp-grid-builder' ),
						],
					],
				],
				'dots_page'            => [
					'type'        => 'text',
					'label'       => __( 'Dots Page Label', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a label', 'wp-grid-builder' ),
					'condition'   => [
						[
							'field'   => 'show_all',
							'compare' => '!=',
							'value'   => 1,
						],
						[
							'field'   => 'pagination',
							'compare' => '!==',
							'value'   => 'prev_next',
						],
					],
				],
				'grid2'                => [
					'type'   => 'grid',
					'fields' => [
						'mid_size' => [
							'type'      => 'number',
							'label'     => __( 'Middle Pages Size', 'wp-grid-builder' ),
							'info'      => __( 'How many numbers to either side of current page, but not including current page.', 'wp-grid-builder' ),
							'min'       => 1,
							'max'       => 9999,
							'step'      => 1,
							'condition' => [
								[
									'field'   => 'show_all',
									'compare' => '!=',
									'value'   => 1,
								],
								[
									'field'   => 'pagination',
									'compare' => '!==',
									'value'   => 'prev_next',
								],
							],
						],
						'end_size' => [
							'type'      => 'number',
							'label'     => __( 'End Pages Size', 'wp-grid-builder' ),
							'info'      => __( 'How many numbers on either the start and the end pagination edges.', 'wp-grid-builder' ),
							'min'       => 1,
							'max'       => 9999,
							'step'      => 1,
							'condition' => [
								[
									'field'   => 'show_all',
									'compare' => '!=',
									'value'   => 1,
								],
								[
									'field'   => 'pagination',
									'compare' => '!==',
									'value'   => 'prev_next',
								],
							],
						],
					],
				],
				'show_all'             => [
					'type'      => 'toggle',
					'label'     => __( 'All Page Numbers', 'wp-grid-builder' ),
					'help'      => __( 'Show all of the pages instead of a short list of the pages near the current page.', 'wp-grid-builder' ),
					'condition' => [
						[
							'field'   => 'pagination',
							'compare' => '!==',
							'value'   => 'prev_next',
						],
					],
				],
				'scroll_to_top'        => [
					'type'  => 'toggle',
					'label' => __( 'Scroll to Top', 'wp-grid-builder' ),
				],
				'scroll_to_top_offset' => [
					'type'      => 'number',
					'label'     => __( 'Scroll Offset', 'wp-grid-builder' ),
					'min'       => -9999,
					'max'       => 9999,
					'step'      => 1,
					'suffix'    => 'px',
					'condition' => [
						[
							'field'   => 'scroll_to_top',
							'compare' => '==',
							'value'   => 1,
						],
					],
				],
			],
		],
	],
];
