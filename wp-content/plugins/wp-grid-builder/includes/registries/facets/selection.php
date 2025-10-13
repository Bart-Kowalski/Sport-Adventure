<?php
/**
 * Selection Facet
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
	'name'     => __( 'User Selection', 'wp-grid-builder' ),
	'type'     => 'filter',
	'icon'     => 'userSelection',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Selection',
	'controls' => [
		[
			'type'   => 'fieldset',
			'legend' => __( 'User Selection', 'wp-grid-builder' ),
			'fields' => [
				'selection-tip' => [
					'type'    => 'tip',
					'content' => (
						__( 'User Selection facet outputs a list of all selected filter choices from each filter facet.', 'wp-grid-builder' ) . '<br>' .
						__( 'Users can unset choices of each filter directly from the selection facet.', 'wp-grid-builder' )
					),
				],
			],
		],
	],
];
