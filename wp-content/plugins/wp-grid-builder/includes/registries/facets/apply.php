<?php
/**
 * Apply Facet
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
	'name'     => __( 'Apply', 'wp-grid-builder' ),
	'type'     => 'apply',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Apply',
	'controls' => [
		[
			'type'   => 'fieldset',
			'legend' => __( 'Apply Options', 'wp-grid-builder' ),
			'fields' => [
				'apply_redirect' => [
					'type'    => 'button',
					'label'   => __( 'Action on Click', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => '',
							'label' => __( 'Filter', 'wp-grid-builder' ),
						],
						[
							'value' => 1,
							'label' => __( 'Redirect', 'wp-grid-builder' ),
						],
					],
				],
				'apply_label'    => [
					'type'  => 'text',
					'label' => __( 'Button Label', 'wp-grid-builder' ),
				],
				'apply_excluded' => [
					'type'        => 'select',
					'label'       => __( 'Exclude Facets', 'wp-grid-builder' ),
					'placeholder' => _x( 'None', 'Excluded Facets default value', 'wp-grid-builder' ),
					'multiple'    => true,
					'async'       => 'wpgb/v2/objects?object=facets&orderby=name&order=asc&fields=id,name',
					'condition'   => [
						[
							'field'   => 'apply_redirect',
							'compare' => '!=',
							'value'   => 1,
						],
					],
				],
				'apply_url'      => [
					'type'      => 'url',
					'label'     => __( 'Redirect URL', 'wp-grid-builder' ),
					'condition' => [
						[
							'field'   => 'apply_redirect',
							'compare' => '==',
							'value'   => 1,
						],
					],
				],
				'apply_history'  => (
					wpgb_get_option( 'history' ) ?
					[
						'type'      => 'toggle',
						'label'     => __( 'Browser’s History', 'wp-grid-builder' ),
						'help'      => __( 'Allow history navigation by pushing facet parameters in url when filtering.', 'wp-grid-builder' ),
						'condition' => [
							[
								'field'   => 'apply_redirect',
								'compare' => '==',
								'value'   => 1,
							],
						],
					] :
					[]
				),
			],
		],
	],
];
