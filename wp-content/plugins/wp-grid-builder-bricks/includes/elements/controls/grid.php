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
	'label'       => esc_html__( 'Select a grid', 'wpgb-bricks' ),
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

$this->controls['is_main_query'] = [
	'tab'     => 'content',
	'label'   => esc_html__( 'Archive Template', 'wpgb-bricks' ),
	'type'    => 'checkbox',
	'inline'  => false,
	'small'   => true,
	'default' => false,
];
