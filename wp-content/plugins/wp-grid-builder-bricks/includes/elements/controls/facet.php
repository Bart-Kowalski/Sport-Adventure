<?php
/**
 * Facet controls
 *
 * @package   WP Grid Builder - Bricks
 * @author    Loïc Blascos
 * @copyright 2019-2025 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

// We only query from Editor.
if ( ! bricks_is_builder() ) {
	return;
}

$this->controls['id'] = [
	'tab'         => 'content',
	'label'       => esc_html__( 'Select a facet', 'wpgb-bricks' ),
	'type'        => 'select',
	'options'     => array_column(
		$wpdb->get_results( "SELECT id, name, type FROM {$wpdb->prefix}wpgb_facets ORDER BY name ASC" ),
		'name',
		'id'
	),
	'inline'      => false,
	'placeholder' => esc_html__( 'None', 'wpgb-bricks' ),
	'multiple'    => false,
	'searchable'  => true,
	'clearable'   => true,
];

$this->controls['grid'] = [
	'tab'         => 'content',
	'label'       => esc_html__( 'Select a grid or element to filter', 'wpgb-bricks' ),
	'type'        => 'select',
	'options'     => array_column(
		$wpdb->get_results( "SELECT id, name, type FROM {$wpdb->prefix}wpgb_grids ORDER BY name" ),
		'name',
		'id'
	),
	'inline'      => false,
	'placeholder' => esc_html__( 'None', 'wpgb-bricks' ),
	'multiple'    => false,
	'searchable'  => true,
	'clearable'   => true,
];

if ( function_exists( 'wpgb_print_facet_style' ) ) {

	$this->controls['style'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Select a style', 'wpgb-bricks' ),
		'type'        => 'select',
		'options'     => array_column(
			$wpdb->get_results( "SELECT id, name FROM {$wpdb->prefix}wpgb_styles ORDER BY name" ),
			'name',
			'id'
		),
		'inline'      => false,
		'placeholder' => esc_html__( 'None', 'wpgb-bricks' ),
		'multiple'    => false,
		'searchable'  => true,
		'clearable'   => true,
	];
}

if ( defined( 'BRICKS_DB_TEMPLATE_SLUG' ) && get_post_type( get_the_ID() ) === BRICKS_DB_TEMPLATE_SLUG ) {

	$this->controls['element_id'] = [
		'tab'            => 'content',
		'label'          => esc_html__( 'Or enter an element ID to filter', 'wpgb-bricks' ),
		'type'           => 'text',
		'inline'         => false,
		'hasDynamicData' => false,
		'placeholder'    => '#brxe-ID',
		'description'    => (
			esc_html__( 'Bricks element ID can be found when editing the element in Bricks and starts with "#brxe-".', 'wpgb-bricks' ) . '<br>' .
			esc_html__( 'It is mainly used to target an element when editing Popup template in Bricks.', 'wpgb-bricks' )
		),
	];
}
