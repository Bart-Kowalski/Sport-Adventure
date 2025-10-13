<?php
/**
 * Color Facet
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
	'name'     => __( 'Color Picker', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'color',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Color',
	'controls' => [
		[
			'type'   => 'clone',
			'fields' => [
				'content_type',
				'source',
				'include',
				'exclude',
				'include_choices',
				'exclude_choices',
				'parent',
				'child_of',
				'depth',
				'orderby',
				'order',
				'limit',
				'display_limit',
				'show_more_label',
				'show_less_label',
			],
		],
		[
			'type'   => 'fieldset',
			'legend' => __( 'Color Picker', 'wp-grid-builder' ),
			'fields' => [
				'clone' => [
					'type'   => 'clone',
					'fields' => [
						'logic',
						'all_label',
						'show_empty',
						'show_count',
						'current_terms',
						'children',
						'multiple',
					],
				],
			],
		],
		[
			'type'        => 'fieldset',
			'legend'      => __( 'Color Options', 'wp-grid-builder' ),
			'description' => (
				__( 'The following parameters allow you to define colors and/or labels based on existing choice values. The choice value can be found in the url when filtering content by color.', 'wp-grid-builder' )
			),
			'fields'      => [
				'color_options' => [
					'type'     => 'repeater',
					'addLabel' => __( 'Add Option', 'wp-grid-builder' ),
					'rowLabel' => '#%d - {{ color_value }} - {{ background_color }} {{ background_image }}',
					'maxRows'  => 999,
					'fields'   => [
						'grid' => [
							'type'   => 'grid',
							'fields' => [
								'color_value'      => [
									'type'        => 'text',
									'label'       => __( 'Choice Value', 'wp-grid-builder' ),
									'placeholder' => __( 'Enter a value', 'wp-grid-builder' ),
								],
								'color_label'      => [
									'type'        => 'text',
									'label'       => __( 'Choice Label', 'wp-grid-builder' ),
									'placeholder' => __( 'Default', 'wp-grid-builder' ),
								],
								'background_color' => [
									'type'     => 'color',
									'label'    => __( 'Color', 'wp-grid-builder' ),
									'gradient' => true,
								],
								'background_image' => [
									'type'        => 'file',
									'label'       => __( 'Image', 'wp-grid-builder' ),
									'mimeType'    => 'image',
									'placeholder' => __( 'Enter URL', 'wp-grid-builder' ),
								],
							],
						],
					],
				],
				'color_order'   => [
					'type'  => 'toggle',
					'label' => __( 'Color Order', 'wp-grid-builder' ),
					'help'  => __( 'Use the color options above as a custom order.', 'wp-grid-builder' ),
				],
			],
		],
	],
];
