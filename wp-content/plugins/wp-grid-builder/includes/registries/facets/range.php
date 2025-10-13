<?php
/**
 * Range Facet
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
	'name'     => __( 'Range Slider', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'range',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Range',
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
			'legend' => __( 'Range Slider', 'wp-grid-builder' ),
			'fields' => [
				'clone'       => [
					'type'   => 'clone',
					'fields' => [
						'compare_type',
					],
				],
				'reset_range' => [
					'type'  => 'text',
					'label' => __( 'Reset Label', 'wp-grid-builder' ),
					'help'  => __( 'Leave empty to hide the reset button.', 'wp-grid-builder' ),
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
