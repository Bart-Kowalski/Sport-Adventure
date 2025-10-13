<?php
/**
 * Number Facet
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
	'name'     => __( 'Number Fields', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'hashtag',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Number',
	'preview'  => [
		(object) [
			'facet_value' => 0,
			'facet_name'  => 0,
			'count'       => 1,
		],
		(object) [
			'facet_value' => 100,
			'facet_name'  => 100,
			'count'       => 1,
		],
	],
	'controls' => [
		[
			'type'   => 'clone',
			'fields' => [
				'content_type',
				'source',
				'meta_key_upper',
				'include',
				'exclude',
				'parent',
				'child_of',
				'depth',
			],
		],
		[
			'type'   => 'fieldset',
			'legend' => __( 'Number Fields', 'wp-grid-builder' ),
			'fields' => [
				'number_inputs' => [
					'type'    => 'button',
					'label'   => __( 'Fields to Show', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'range',
							'label' => __( 'Min & Max', 'wp-grid-builder' ),
						],
						[
							'value' => 'min',
							'label' => __( 'Min', 'wp-grid-builder' ),
						],
						[
							'value' => 'max',
							'label' => __( 'Max', 'wp-grid-builder' ),
						],
						[
							'value' => 'exact',
							'label' => __( 'Exact', 'wp-grid-builder' ),
						],
					],
				],
				'clone'         => [
					'type'   => 'clone',
					'fields' => [
						'compare_type',
					],
				],
				'grid'          => [
					'type'   => 'grid',
					'fields' => [
						'min_placeholder'   => [
							'type'      => 'text',
							'label'     => __( 'Min Placeholder', 'wp-grid-builder' ),
							'info'      => __( 'The shortcodes [min] and [max] can be included in the placeholder.', 'wp-grid-builder' ),
							'condition' => [
								'relation' => 'OR',
								[
									'field'   => 'number_inputs',
									'compare' => '==',
									'value'   => 'range',
								],
								[
									'field'   => 'number_inputs',
									'compare' => '==',
									'value'   => 'min',
								],
							],
						],
						'min_label'         => [
							'type'      => 'text',
							'label'     => __( 'Min Label', 'wp-grid-builder' ),
							'condition' => [
								'relation' => 'OR',
								[
									'field'   => 'number_inputs',
									'compare' => '==',
									'value'   => 'range',
								],
								[
									'field'   => 'number_inputs',
									'compare' => '==',
									'value'   => 'min',
								],
							],
						],
						'max_placeholder'   => [
							'type'      => 'text',
							'label'     => __( 'Max Placeholder', 'wp-grid-builder' ),
							'info'      => __( 'The shortcodes [min] and [max] can be included in the placeholder.', 'wp-grid-builder' ),
							'condition' => [
								'relation' => 'OR',
								[
									'field'   => 'number_inputs',
									'compare' => '==',
									'value'   => 'range',
								],
								[
									'field'   => 'number_inputs',
									'compare' => '==',
									'value'   => 'max',
								],
							],
						],
						'max_label'         => [
							'type'      => 'text',
							'label'     => __( 'Max Label', 'wp-grid-builder' ),
							'condition' => [
								'relation' => 'OR',
								[
									'field'   => 'number_inputs',
									'compare' => '==',
									'value'   => 'range',
								],
								[
									'field'   => 'number_inputs',
									'compare' => '==',
									'value'   => 'max',
								],
							],
						],
						'exact_placeholder' => [
							'type'      => 'text',
							'label'     => __( 'Input Placeholder', 'wp-grid-builder' ),
							'info'      => __( 'The shortcodes [min] and [max] can be included in the placeholder.', 'wp-grid-builder' ),
							'condition' => [
								[
									'field'   => 'number_inputs',
									'compare' => '==',
									'value'   => 'exact',
								],
							],
						],
						'exact_label'       => [
							'type'      => 'text',
							'label'     => __( 'Input Label', 'wp-grid-builder' ),
							'condition' => [
								[
									'field'   => 'number_inputs',
									'compare' => '==',
									'value'   => 'exact',
								],
							],
						],
					],
				],
				'submit_label'  => [
					'type'  => 'text',
					'label' => __( 'Submit Button Label', 'wp-grid-builder' ),
					'help'  => __( 'Leave empty to hide submit button.', 'wp-grid-builder' ),
				],
				'number_labels' => [
					'type'  => 'toggle',
					'label' => __( 'Display Labels', 'wp-grid-builder' ),
				],
			],
		],
		[
			'type'          => 'details',
			'summaryOpened' => __( 'Hide advanced settings', 'wp-grid-builder' ),
			'summaryClosed' => __( 'Show advanced settings', 'wp-grid-builder' ),
			'fields'        => [
				[
					'type'   => 'clone',
					'fields' => [
						'values_format',
					],
				],
			],
		],
	],
];
