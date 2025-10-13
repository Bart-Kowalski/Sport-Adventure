<?php
/**
 * Design blocks
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$columns = [
	'title'           => __( 'Columns', 'wp-grid-builder' ),
	'description'     => __( 'Display content in multiple columns, with blocks added to each column.', 'wp-grid-builder' ),
	'category'        => 'design_blocks',
	'tagName'         => 'div',
	'attributes'      => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source' => 'columns',
			],
		],
	],
	'controls'        => [
		'panel' => [
			'type'        => 'panel',
			'title'       => __( 'Layout', 'wp-grid-builder' ),
			'initialOpen' => true,
			'fields'      => [
				'columnsNumber' => [
					'type'  => 'range',
					'label' => __( 'Columns', 'wp-grid-builder' ),
					'min'   => 1,
					'max'   => 12,
					'step'  => 1,
				],
				'grid'          => [
					'type'   => 'grid',
					'fields' => [
						'columnXGap' => [
							'type'  => 'number',
							'label' => __( 'Column Gap', 'wp-grid-builder' ),
							'units' => [
								'custom' => [],
								'px'     => [
									'min'  => 0,
									'max'  => 999,
									'step' => 1,
								],
								'em'     => [
									'min'  => 0,
									'max'  => 999,
									'step' => 0.001,
								],
								'rem'    => [
									'min'  => 0,
									'max'  => 999,
									'step' => 0.001,
								],
								'%'      => [
									'min'  => 0,
									'max'  => 100,
									'step' => 0.001,
								],
							],
						],
						'columnYGap' => [
							'type'  => 'number',
							'label' => __( 'Row Gap', 'wp-grid-builder' ),
							'units' => [
								'custom' => [],
								'px'     => [
									'min'  => 0,
									'max'  => 999,
									'step' => 1,
								],
								'em'     => [
									'min'  => 0,
									'max'  => 999,
									'step' => 0.001,
								],
								'rem'    => [
									'min'  => 0,
									'max'  => 999,
									'step' => 0.001,
								],
								'%'      => [
									'min'  => 0,
									'max'  => 100,
									'step' => 0.001,
								],
							],
						],
					],
				],
			],
		],
	],
	'render_callback' => 'wpgb_columns_block',
];

$column = [
	'title'           => __( 'Column', 'wp-grid-builder' ),
	'description'     => __( 'A single column within a columns block.', 'wp-grid-builder' ),
	'category'        => 'design_blocks',
	'parent'          => [ 'wpgb/columns' ],
	'tagName'         => 'div',
	'attributes'      => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source' => 'column',
			],
		],
	],
	'render_callback' => 'wpgb_column_block',
];

$group = [
	'title'           => _x( 'Group', 'block type', 'wp-grid-builder' ),
	'description'     => __( 'Gather blocks in a layout container.', 'wp-grid-builder' ),
	'category'        => 'design_blocks',
	'tagName'         => 'div',
	'attributes'      => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source' => 'group',
			],
		],
	],
	'controls'        => [
		'panel' => [
			'type'        => 'panel',
			'title'       => __( 'Layout', 'wp-grid-builder' ),
			'initialOpen' => true,
			'fields'      => [
				'grid' => [
					'type'   => 'grid',
					'fields' => [
						'groupType'        => [
							'type'    => 'button',
							'hidden'  => true,
							'options' => [
								[
									'value' => '',
									'label' => 'n',
								],
								[
									'value' => 'constrained',
									'label' => 'c',
								],
								[
									'value' => 'flex',
									'label' => 'f',
								],
							],
						],
						'groupOrientation' => [
							'type'           => 'button',
							'label'          => __( 'Direction', 'wp-grid-builder' ),
							'isDeselectable' => true,
							'options'        => [
								[
									'value' => 'row',
									'label' => __( 'Horizontal', 'wp-grid-builder' ),
									'icon'  => 'arrowRight',
								],
								[
									'value' => 'column',
									'label' => __( 'Vertical', 'wp-grid-builder' ),
									'icon'  => 'arrowDown',
								],
							],
						],
						'groupWrap'        => [
							'type'           => 'button',
							'label'          => __( 'Wrap', 'wp-grid-builder' ),
							'isDeselectable' => true,
							'options'        => [
								[
									'value' => 'wrap',
									'label' => __( 'Yes', 'wp-grid-builder' ),
								],
								[
									'value' => 'nowrap',
									'label' => __( 'No', 'wp-grid-builder' ),
								],
							],
						],
						'groupJustify1'    => [
							'name'        => 'groupJustify',
							'type'        => 'select',
							'label'       => __( 'Horizontal', 'wp-grid-builder' ),
							'placeholder' => __( 'Unset', 'wp-grid-builder' ),
							'isClearable' => false,
							'options'     => [
								[
									'value' => 'flex-start',
									'label' => __( 'Left', 'wp-grid-builder' ),
									'icon'  => 'justifyLeft',
								],
								[
									'value' => 'center',
									'label' => __( 'Center', 'wp-grid-builder' ),
									'icon'  => 'justifyCenter',
								],
								[
									'value' => 'flex-end',
									'label' => __( 'Right', 'wp-grid-builder' ),
									'icon'  => 'justifyRight',
								],
								[
									'value' => 'space-between',
									'label' => __( 'Spaced', 'wp-grid-builder' ),
									'icon'  => 'justifySpaceBetween',
								],
							],
							'condition'   => [
								[
									'field'   => 'blockVariation',
									'compare' => '===',
									'value'   => 'group-row',
								],
							],
						],
						'groupAlignment1'  => [
							'name'        => 'groupAlignment',
							'type'        => 'select',
							'label'       => __( 'Vertical', 'wp-grid-builder' ),
							'placeholder' => __( 'Unset', 'wp-grid-builder' ),
							'isClearable' => false,
							'options'     => [
								[
									'value' => 'flex-start',
									'label' => __( 'Top', 'wp-grid-builder' ),
									'icon'  => 'alignTop',
								],
								[
									'value' => 'center',
									'label' => __( 'Middle', 'wp-grid-builder' ),
									'icon'  => 'alignMiddle',
								],
								[
									'value' => 'flex-end',
									'label' => __( 'Bottom', 'wp-grid-builder' ),
									'icon'  => 'alignBottom',
								],
								[
									'value' => 'stretch',
									'label' => __( 'Stretched', 'wp-grid-builder' ),
									'icon'  => 'alignStretch',
								],
							],
							'condition'   => [
								[
									'field'   => 'blockVariation',
									'compare' => '===',
									'value'   => 'group-row',
								],
							],
						],
						'groupAlignment2'  => [
							'name'        => 'groupAlignment',
							'type'        => 'select',
							'label'       => __( 'Horizontal', 'wp-grid-builder' ),
							'placeholder' => __( 'Unset', 'wp-grid-builder' ),
							'isClearable' => false,
							'options'     => [
								[
									'value' => 'flex-start',
									'label' => __( 'Left', 'wp-grid-builder' ),
									'icon'  => 'justifyLeft',
								],
								[
									'value' => 'center',
									'label' => __( 'Center', 'wp-grid-builder' ),
									'icon'  => 'justifyCenter',
								],
								[
									'value' => 'flex-end',
									'label' => __( 'Right', 'wp-grid-builder' ),
									'icon'  => 'justifyRight',
								],
								[
									'value' => 'stretch',
									'label' => __( 'Stretched', 'wp-grid-builder' ),
									'icon'  => 'justifyStretch',
								],
							],
							'condition'   => [
								[
									'field'   => 'blockVariation',
									'compare' => '===',
									'value'   => 'group-stack',
								],
							],
						],
						'groupJustify2'    => [
							'name'        => 'groupJustify',
							'type'        => 'select',
							'label'       => __( 'Vertical', 'wp-grid-builder' ),
							'placeholder' => __( 'Unset', 'wp-grid-builder' ),
							'isClearable' => false,
							'options'     => [
								[
									'value' => 'flex-start',
									'label' => __( 'Top', 'wp-grid-builder' ),
									'icon'  => 'alignTop',
								],
								[
									'value' => 'center',
									'label' => __( 'Middle', 'wp-grid-builder' ),
									'icon'  => 'alignMiddle',
								],
								[
									'value' => 'flex-end',
									'label' => __( 'Bottom', 'wp-grid-builder' ),
									'icon'  => 'alignBottom',
								],
								[
									'value' => 'space-between',
									'label' => __( 'Spaced', 'wp-grid-builder' ),
									'icon'  => 'alignBetween',
								],
							],
							'condition'   => [
								[
									'field'   => 'blockVariation',
									'compare' => '===',
									'value'   => 'group-stack',
								],
							],
						],
						'groupXGap'        => [
							'type'  => 'number',
							'label' => _x( 'Column Gap', 'wp-grid-builder' ),
							'units' => [
								'custom' => [],
								'px'     => [
									'min'  => 0,
									'max'  => 999,
									'step' => 1,
								],
								'em'     => [
									'min'  => 0,
									'max'  => 999,
									'step' => 0.001,
								],
								'rem'    => [
									'min'  => 0,
									'max'  => 999,
									'step' => 0.001,
								],
								'%'      => [
									'min'  => 0,
									'max'  => 100,
									'step' => 0.001,
								],
							],
						],
						'groupYGap'        => [
							'type'  => 'number',
							'label' => _x( 'Row Gap', 'wp-grid-builder' ),
							'units' => [
								'custom' => [],
								'px'     => [
									'min'  => 0,
									'max'  => 999,
									'step' => 1,
								],
								'em'     => [
									'min'  => 0,
									'max'  => 999,
									'step' => 0.001,
								],
								'rem'    => [
									'min'  => 0,
									'max'  => 999,
									'step' => 0.001,
								],
								'%'      => [
									'min'  => 0,
									'max'  => 100,
									'step' => 0.001,
								],
							],
						],
					],
				],
			],
			'condition'   => [
				[
					'field'   => 'blockVariation',
					'compare' => 'IN',
					'value'   => [
						'group-stack',
						'group-row',
					],
				],
			],
		],
	],
	'render_callback' => 'wpgb_group_block',
];

$button = [
	'title'           => __( 'Button', 'wp-grid-builder' ),
	'description'     => __( 'Prompt visitors to take action with a button-style.', 'wp-grid-builder' ),
	'category'        => 'design_blocks',
	'tagName'         => 'div',
	'attributes'      => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source' => 'button',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'color'                      => '#fff',
					'background'                 => '#32373c',
					'padding-top'                => '0.8em',
					'padding-right'              => '1.5em',
					'padding-bottom'             => '0.8em',
					'padding-left'               => '1.5em',
					'border-top-left-radius'     => '999px',
					'border-top-right-radius'    => '999px',
					'border-bottom-right-radius' => '999px',
					'border-bottom-left-radius'  => '999px',
					'font-size'                  => '1em',
					'line-height'                => '1.4',
					'text-align'                 => 'center',
				],
			],
		],
	],
	'controls'        => [
		'text' => [
			'type'   => 'code',
			'hidden' => true,
		],
	],
	'render_callback' => 'wpgb_button_block',
];

return [
	'columns' => $columns,
	'column'  => $column,
	'group'   => $group,
	'button'  => $button,
];
