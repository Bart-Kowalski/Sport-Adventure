<?php
/**
 * Rating Facet
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
	'name'     => __( 'Rating Picker', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'starHalf',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Rating',
	'preview'  => [
		(object) [
			'facet_name'  => 5,
			'facet_value' => 5,
			'count'       => 32,
		],
		(object) [
			'facet_name'  => 4,
			'facet_value' => 4,
			'count'       => 20,
		],
		(object) [
			'facet_name'  => 3,
			'facet_value' => 3,
			'count'       => 11,
		],
		(object) [
			'facet_name'  => 2,
			'facet_value' => 2,
			'count'       => 6,
		],
		(object) [
			'facet_name'  => 1,
			'facet_value' => 1,
			'count'       => 0,
		],
	],
	'controls' => [
		[
			'type'   => 'clone',
			'fields' => [
				'content_type',
				'source',
				'include',
				'exclude',
				'parent',
				'child_of',
				'depth',
			],
		],
		[
			'type'   => 'fieldset',
			'legend' => __( 'Rating Picker', 'wp-grid-builder' ),
			'fields' => [
				'clone' => [
					'type'   => 'clone',
					'fields' => [
						'all_label',
						'show_empty',
						'show_count',
						'children',
					],
				],
			],
		],
	],
];
