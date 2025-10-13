<?php
/**
 * Date Facet
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
	'name'     => __( 'Date Picker', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'calendar',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Date',
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
			'legend' => __( 'Date Picker', 'wp-grid-builder' ),
			'fields' => [
				'date_type'        => [
					'type'    => 'button',
					'label'   => __( 'Date Type', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'single',
							'label' => __( 'Single Date', 'wp-grid-builder' ),
						],
						[
							'value' => 'range',
							'label' => __( 'Range of Dates', 'wp-grid-builder' ),
						],
					],
				],
				'clone'            => [
					'type'   => 'clone',
					'fields' => [
						'compare_type',
					],
				],
				'date_format'      => [
					'type'        => 'text',
					'label'       => __( 'Date Format', 'wp-grid-builder' ),
					'placeholder' => 'Y-m-d',
					'help'        => sprintf(
						/* Translators: %s: Flatpickr script. */
						__( 'See available <a href="%s" rel="external noopener noreferrer" target="_blank">formatting tokens</a> of Flatpickr library.', 'wp-grid-builder' ),
						'https://flatpickr.js.org/formatting/'
					),
				],
				'date_placeholder' => [
					'type'        => 'text',
					'label'       => __( 'Input Placeholder', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a placeholder', 'wp-grid-builder' ),
				],
				'date-info'        => [
					'type'    => 'tip',
					'content' => sprintf(
						/* Translators: %1$s: Dat format 1, %2$s: Date format 2 */
						__( 'Please, make sure the dates are stored as <strong>%1$s</strong> or <strong>%2$s</strong> formats.', 'wp-grid-builder' ) . '<br>' .
						__( 'This is required to correctly filter dates from custom fields. Post dates natively use the right format.', 'wp-grid-builder' ),
						'Y-m-d',
						'Y-m-d h:i:s'
					),
				],
			],
		],
	],
];
