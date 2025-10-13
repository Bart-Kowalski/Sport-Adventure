<?php
/**
 * Sort Facet
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

use WP_Grid_Builder\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$orderby_options = apply_filters(
	'wp_grid_builder/facet/sort_options',
	[
		__( 'Post Field', 'wp-grid-builder' ) ?: 'Post Field' => [
			'ID'            => __( 'Post ID', 'wp-grid-builder' ),
			'title'         => __( 'Post Title', 'wp-grid-builder' ),
			'post_name'     => __( 'Post Name', 'wp-grid-builder' ),
			'author'        => __( 'Post Author', 'wp-grid-builder' ),
			'date'          => __( 'Post Date', 'wp-grid-builder' ),
			'modified'      => __( 'Post Modified Date', 'wp-grid-builder' ),
			'comment_count' => __( 'Post Comments Count', 'wp-grid-builder' ),
			'post__in'      => __( 'Included Posts', 'wp-grid-builder' ),
			'menu_order'    => __( 'Menu Order', 'wp-grid-builder' ),
			'rand'          => __( 'Random Order', 'wp-grid-builder' ),
		],
		__( 'User Field', 'wp-grid-builder' ) ?: 'User Field' => [
			'display_name'    => __( 'User Display Name', 'wp-grid-builder' ),
			'user_name'       => __( 'User Name', 'wp-grid-builder' ),
			'user_login'      => __( 'User Login', 'wp-grid-builder' ),
			'user_nicename'   => __( 'User Nicename', 'wp-grid-builder' ),
			'user_email'      => __( 'User Email', 'wp-grid-builder' ),
			'user_url'        => __( 'User Url', 'wp-grid-builder' ),
			'user_registered' => __( 'User Registered Date', 'wp-grid-builder' ),
			'post_count'      => __( 'User Posts Count', 'wp-grid-builder' ),
			'user__in'        => __( 'Included Users', 'wp-grid-builder' ),
		],
		__( 'Term Field', 'wp-grid-builder' ) ?: 'Term Field' => [
			'term_id'     => __( 'Term ID', 'wp-grid-builder' ),
			'name'        => __( 'Term Name', 'wp-grid-builder' ),
			'slug'        => __( 'Term Slug', 'wp-grid-builder' ),
			'description' => __( 'Term Description', 'wp-grid-builder' ),
			'parent'      => __( 'Term Parent', 'wp-grid-builder' ),
			'term_order'  => __( 'Term Order', 'wp-grid-builder' ),
			'term_group'  => __( 'Term Group', 'wp-grid-builder' ),
			'count'       => __( 'Term Posts Count', 'wp-grid-builder' ),
			'term__in'    => __( 'Included terms', 'wp-grid-builder' ),
		],
		__( 'Custom Field', 'wp-grid-builder' ) ?: 'Custom Field' => [
			'meta_value'     => __( 'Custom Field', 'wp-grid-builder' ),
			'meta_value_num' => __( 'Numeric Custom Field', 'wp-grid-builder' ),
		],
	]
);

return [
	'name'     => __( 'Sort', 'wp-grid-builder' ),
	'type'     => 'sort',
	'class'    => 'WP_Grid_Builder\Includes\FrontEnd\Facets\Sort',
	'controls' => [
		[
			'type'        => 'fieldset',
			'legend'      => __( 'Sort Options', 'wp-grid-builder' ),
			'description' => __( 'The first option serves as a placeholder. It corresponds to the unsorted state and therefore to the order set for the grid/content to be filtered.', 'wp-grid-builder' ),
			'fields'      => [
				'sort_options' => [
					'type'     => 'repeater',
					'addLabel' => __( 'Add Option', 'wp-grid-builder' ),
					'rowLabel' => '#%d {{ label || ' . __( 'Label', 'wp-grid-builder' ) . '}} - {{ orderby || ' . __( 'None', 'wp-grid-builder' ) . '}} ({{ order || ' . __( 'DESC', 'wp-grid-builder' ) . ' }})',
					'minRows'  => 1,
					'maxRows'  => 20,
					'fields'   => [
						'label'    => [
							'type'        => 'text',
							'label'       => __( 'Option Label', 'wp-grid-builder' ),
							'placeholder' => __( 'Enter a label', 'wp-grid-builder' ),
						],
						'grid'     => [
							'type'   => 'grid',
							'fields' => [
								'orderby' => [
									'type'         => 'select',
									'label'        => __( 'Order By', 'wp-grid-builder' ),
									'placeholder'  => __( 'None', 'wp-grid-builder' ),
									'isSearchable' => true,
									'options'      => array_map(
										function( $label, $values ) {

											return [
												'label'   => $label,
												'options' => Helpers::format_options( $values ),
											];
										},
										array_keys( $orderby_options ),
										$orderby_options
									),
								],
								'order'   => [
									'type'    => 'button',
									'label'   => __( 'Order', 'wp-grid-builder' ),
									'options' => [
										[
											'value' => 'desc',
											'label' => __( 'Descending', 'wp-grid-builder' ),
											'icon'  => 'arrowDown',
										],
										[
											'value' => 'asc',
											'label' => __( 'Ascending', 'wp-grid-builder' ),
											'icon'  => 'arrowUp',
										],
									],
								],
							],
						],
						'meta_key' => [
							'type'        => 'select',
							'label'       => __( 'Custom Field', 'wp-grid-builder' ),
							'placeholder' => __( 'Enter a field name', 'wp-grid-builder' ),
							'async'       => [
								'wpgb/v2/metadata?object=registered',
								'wpgb/v2/metadata?object=post',
								'wpgb/v2/metadata?object=term',
								'wpgb/v2/metadata?object=user',
							],
							'condition'   => [
								[
									'field'   => 'orderby',
									'compare' => 'CONTAINS',
									'value'   => 'meta_value',
								],
							],
						],
					],
				],
			],
		],
		[
			'type'   => 'clone',
			'fields' => [
				'combobox',
			],
		],
	],
];
