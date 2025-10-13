<?php
/**
 * Result Count Facet
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
	'name'     => __( 'Result Counts', 'wp-grid-builder' ),
	'type'     => 'load',
	'icon'     => 'formatListNumbered',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Result_Count',
	'controls' => [
		[
			'type'   => 'fieldset',
			'legend' => __( 'Result Counts', 'wp-grid-builder' ),
			'fields' => [
				'result_count_singular' => [
					'type'        => 'text',
					'label'       => __( 'Singular Message', 'wp-grid-builder' ),
					'placeholder' => __( 'e.g.: [from] - [to] of [total] post', 'wp-grid-builder' ),
					'help'        => __( 'The following shortcodes [from], [to], and [total] can be included in the text.', 'wp-grid-builder' ),
				],
				'result_count_plural'   => [
					'type'        => 'text',
					'label'       => __( 'Plural Message', 'wp-grid-builder' ),
					'placeholder' => __( 'e.g.: [from] - [to] of [total] posts', 'wp-grid-builder' ),
					'help'        => __( 'The following shortcodes [from], [to], and [total] can be included in the text.', 'wp-grid-builder' ),
				],
			],
		],
	],
];
