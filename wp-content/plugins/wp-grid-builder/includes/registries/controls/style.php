<?php
/**
 * Facet controls
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

$height_units = [
	'custom'  => [
		'min'  => 0,
		'max'  => 100,
		'step' => 0.01,
	],
	'px'      => [
		'min'  => 0,
		'max'  => 999,
		'step' => 1,
	],
	'em'      => [
		'min'  => 0,
		'max'  => 100,
		'step' => 0.01,
	],
	'rem'     => [
		'min'  => 0,
		'max'  => 100,
		'step' => 0.01,
	],
	'%'       => [
		'min'  => 0,
		'max'  => 100,
		'step' => 0.01,
	],
	'vh'      => [
		'min'  => 0,
		'max'  => 100,
		'step' => 0.01,
	],
	'auto'    => [
		'min'  => '',
		'max'  => '',
		'step' => '',
	],
	'none'    => [
		'min'  => '',
		'max'  => '',
		'step' => '',
	],
	'unset'   => [
		'min'  => '',
		'max'  => '',
		'step' => '',
	],
	'initial' => [
		'min'  => '',
		'max'  => '',
		'step' => '',
	],
];

$facet_title = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Title', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'title_padding'    => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-facet-title' => [
					'padding' => '{{value}}',
				],
			],
		],
		'title_margin'     => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-facet-title' => [
					'margin' => '{{value}}',
				],
			],
		],
		'title_borders'    => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'title_border' => [
					'type'      => 'border',
					'label'     => __( 'Borders', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-facet-title' => [
							'border' => '{{value}}',
						],
					],
				],
				'title_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-facet-title' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'title_font'       => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'title_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-facet-title' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'title_background' => [
			'type'      => 'color',
			'label'     => __( 'Background', 'wp-grid-builder' ),
			'gradient'  => true,
			'selectors' => [
				'{{wrapper}} .wpgb-facet-title' => [
					'background' => '{{value}}',
				],
			],
		],
		'title_color'      => [
			'type'      => 'color',
			'label'     => __( 'Text Color', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-facet-title' => [
					'color' => '{{value}}',
				],
			],
		],
	],
];

$listing = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Listing', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'{{facet}}_list_direction'       => [
			'type'      => 'button',
			'label'     => __( 'Layout', 'wp-grid-builder' ),
			'options'   => [
				[
					'label' => _x( 'Vertical', 'Layout control value', 'wp-grid-builder' ),
					'value' => '',
					'icon'  => 'stack',
				],
				[
					'label' => _x( 'Horizontal', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'flex',
					'icon'  => 'row',
				],
				[
					'label' => _x( 'Grid', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'grid',
					'icon'  => 'grid',
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-{{type}}-facet ul'    => [
					'display'        => '{{value}}',
					'flex-wrap'      => 'wrap',
					'flex-direction' => 'row',
					'align-content'  => 'flex-start',
				],
				'{{wrapper}} .wpgb-{{type}}-facet ul li' => [
					'margin' => 0,
				],
			],
		],
		'{{facet}}_list_alignment'       => [
			'type'           => 'button',
			'label'          => __( 'Aligment', 'wp-grid-builder' ),
			'isDeselectable' => true,
			'options'        => [
				[
					'label' => _x( 'Left', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'flex-start',
					'icon'  => 'justifyLeft',
				],
				[
					'label' => _x( 'Center', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'center',
					'icon'  => 'justifyCenter',
				],
				[
					'label' => _x( 'Right', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'flex-end',
					'icon'  => 'justifyRight',
				],
			],
			'selectors'      => [
				'{{wrapper}} .wpgb-{{type}}-facet ul:first-child, {{wrapper}} .wpgb-{{type}}-facet ul:first-child + ul' => [
					'justify-content' => '{{value}}',
				],
			],
			'condition'      => [
				[
					'field'   => '{{facet}}_list_direction',
					'compare' => '===',
					'value'   => 'flex',
				],
			],
		],
		'{{facet}}_list_gaps'            => [
			'type'      => 'grid',
			'fields'    => [
				'{{facet}}_list_row_gap'    => [
					'type'      => 'number',
					'label'     => __( 'Row Gap', 'wp-grid-builder' ),
					'units'     => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{wrapper}} .wpgb-{{type}}-facet .wpgb-hierarchical-list'    => [
							'row-gap' => '{{value}}',
						],
						'{{wrapper}} .wpgb-{{type}}-facet .wpgb-hierarchical-list ul' => [
							'margin-top' => '{{value}}',
						],
					],
				],
				'{{facet}}_list_column_gap' => [
					'type'      => 'number',
					'label'     => __( 'Column Gap', 'wp-grid-builder' ),
					'units'     => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{wrapper}} .wpgb-{{type}}-facet .wpgb-hierarchical-list' => [
							'column-gap' => '{{value}}',
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => '{{facet}}_list_direction',
					'compare' => 'IN',
					'value'   => [ 'flex', 'grid' ],
				],
			],
		],
		'{{facet}}_list_row_gap'         => [
			'type'      => 'range',
			'label'     => __( 'Row Gap', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-{{type}}-facet .wpgb-hierarchical-list'    => [
					'row-gap' => '{{value}}',
				],
				'{{wrapper}} .wpgb-{{type}}-facet .wpgb-hierarchical-list li' => [
					'margin-top' => '{{value}}',
				],
			],
			'condition' => [
				[
					'field'   => '{{facet}}_list_direction',
					'compare' => 'NOT IN',
					'value'   => [ 'flex', 'grid' ],
				],
			],
		],
		'{{facet}}_list_columns_number'  => [
			'type'      => 'range',
			'label'     => __( 'Columns', 'wp-grid-builder' ),
			'min'       => 1,
			'max'       => 99,
			'step'      => 1,
			'selectors' => [
				'{{wrapper}} .wpgb-{{type}}-facet .wpgb-hierarchical-list' => [
					'grid-template-columns' => 'repeat({{value}}, 1fr)',
				],
			],
			'condition' => [
				[
					'field'   => '{{facet}}_list_direction',
					'compare' => '===',
					'value'   => 'grid',
				],
			],
		],
		'{{facet}}_list_children_offset' => [
			'type'      => 'range',
			'label'     => __( 'Children Offset', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-{{type}}-facet .wpgb-hierarchical-list ul' => [
					'margin-left' => '{{value}}',
				],
				'body.rtl {{wrapper}} .wpgb-{{type}}-facet .wpgb-hierarchical-list ul' => [
					'margin-right' => '{{value}}',
				],
			],
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '===',
					'value'   => 'checkbox',
				],
			],
		],
		'{{facet}}_list_height'          => [
			'type'      => 'range',
			'label'     => __( 'Height', 'wp-grid-builder' ),
			'units'     => $height_units,
			'selectors' => [
				'{{wrapper}} .wpgb-{{type}}-facet .wpgb-hierarchical-list:first-child' => [
					'overflow-y' => 'auto',
					'height'     => '{{value}}',
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => '{{facet}}',
		],
	],
];

$inline_listing = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Listing', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'{{facet}}_inline_list_direction'      => [
			'type'      => 'button',
			'label'     => __( 'Layout', 'wp-grid-builder' ),
			'options'   => [
				[
					'label' => _x( 'Vertical', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'flex',
					'icon'  => 'stack',
				],
				[
					'label' => _x( 'Horizontal', 'Layout control value', 'wp-grid-builder' ),
					'value' => '',
					'icon'  => 'row',
				],
				[
					'label' => _x( 'Grid', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'grid',
					'icon'  => 'grid',
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-{{type}}-facet .wpgb-inline-list'    => [
					'display'        => '{{value}}',
					'flex-wrap'      => 'nowrap',
					'flex-direction' => 'column',
				],
				'{{wrapper}} .wpgb-{{type}}-facet .wpgb-inline-list li' => [
					'margin' => 0,
				],
			],
		],
		'{{facet}}_inline_list_alignment'      => [
			'type'           => 'button',
			'label'          => __( 'Aligment', 'wp-grid-builder' ),
			'isDeselectable' => true,
			'options'        => [
				[
					'label' => _x( 'Left', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'flex-start',
					'icon'  => 'justifyLeft',
				],
				[
					'label' => _x( 'Center', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'center',
					'icon'  => 'justifyCenter',
				],
				[
					'label' => _x( 'Right', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'flex-end',
					'icon'  => 'justifyRight',
				],
			],
			'selectors'      => [
				'{{wrapper}} .wpgb-{{type}}-facet ul:first-child, {{wrapper}} .wpgb-{{type}}-facet ul:first-child + ul' => [
					'justify-content' => '{{value}}',
				],
			],
			'condition'      => [
				[
					'field'   => '{{facet}}_inline_list_direction',
					'compare' => 'NOT IN',
					'value'   => [ 'flex', 'grid' ],
				],
			],
		],
		'{{facet}}_inline_list_gaps'           => [
			'type'      => 'grid',
			'fields'    => [
				'{{facet}}_inline_list_row_gap'    => [
					'type'      => 'number',
					'label'     => __( 'Row Gap', 'wp-grid-builder' ),
					'units'     => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{wrapper}} .wpgb-{{type}}-facet .wpgb-inline-list li' => [
							'margin-bottom' => '0',
							'margin-top'    => '0',
						],
						'{{wrapper}} .wpgb-{{type}}-facet .wpgb-inline-list'    => [
							'row-gap' => '{{value}}',
						],
					],
				],
				'{{facet}}_inline_list_column_gap' => [
					'type'      => 'number',
					'label'     => __( 'Column Gap', 'wp-grid-builder' ),
					'units'     => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{wrapper}} .wpgb-{{type}}-facet .wpgb-inline-list'    => [
							'column-gap' => '{{value}}',
						],
						'{{wrapper}} .wpgb-{{type}}-facet .wpgb-inline-list li' => [
							'margin-left'  => '0',
							'margin-right' => '0',
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => '{{facet}}_inline_list_direction',
					'compare' => '!==',
					'value'   => 'flex',
				],
			],
		],
		'{{facet}}_inline_list_row_gap'        => [
			'type'      => 'range',
			'label'     => __( 'Row Gap', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-{{type}}-facet .wpgb-inline-list li' => [
					'margin-bottom' => '0',
					'margin-top'    => '0',
				],
				'{{wrapper}} .wpgb-{{type}}-facet .wpgb-inline-list'    => [
					'row-gap' => '{{value}}',
				],
			],
			'condition' => [
				[
					'field'   => '{{facet}}_inline_list_direction',
					'compare' => '===',
					'value'   => 'flex',
				],
			],
		],
		'{{facet}}_inline_list_columns_number' => [
			'type'      => 'range',
			'label'     => __( 'Columns', 'wp-grid-builder' ),
			'min'       => 1,
			'max'       => 99,
			'step'      => 1,
			'selectors' => [
				'{{wrapper}} .wpgb-{{type}}-facet .wpgb-inline-list' => [
					'grid-template-columns' => 'repeat({{value}}, 1fr)',
				],
			],
			'condition' => [
				[
					'field'   => '{{facet}}_inline_list_direction',
					'compare' => '===',
					'value'   => 'grid',
				],
			],
		],
		'{{facet}}_inline_list_height'         => [
			'type'      => 'range',
			'label'     => __( 'Height', 'wp-grid-builder' ),
			'units'     => $height_units,
			'selectors' => [
				'{{wrapper}} .wpgb-{{type}}-facet .wpgb-inline-list' => [
					'overflow-y' => 'auto',
					'height'     => '{{value}}',
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => '{{facet}}',
		],
	],
];

$checkbox = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Checkbox', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'checkbox_box_size'  => [
			'type'      => 'range',
			'label'     => __( 'Box Size', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 10,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox-control' => [
					'height' => '{{value}}',
					'width'  => '{{value}}',
				],
			],
		],
		'checkbox_tick_size' => [
			'type'      => 'range',
			'label'     => __( 'Tick Size', 'wp-grid-builder' ),
			'min'       => 5,
			'max'       => 50,
			'step'      => 1,
			'suffix'    => 'px',
			'selectors' => [
				'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="mixed"] .wpgb-checkbox-control:before' => [
					'transform' => 'scale(calc({{value}}/12))',
				],
				'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control:after' => [
					'top'       => 'calc(-{{value}}px/12)',
					'transform' => 'rotate(45deg) scale(calc({{value}}/12))',
				],
			],
		],
		'checkbox_borders'   => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'checkbox_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox .wpgb-checkbox-control' => [
							'border' => '{{value}}',
						],
					],
				],
				'checkbox_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox .wpgb-checkbox-control' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'checkbox_shadow'    => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox .wpgb-checkbox-control' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'checkbox_colors'    => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'checkbox_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox .wpgb-checkbox-control' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Pressed', 'wp-grid-builder' ),
					'name'   => 'pressed',
					'fields' => [
						'checkbox_background_pressed' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control' => [
									'background' => '{{value}}',
								],
							],
						],
						'checkbox_border_pressed'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="true"] .wpgb-checkbox-control' => [
									'border-color' => '{{value}}',
								],
							],
						],
						'checkbox_tick_pressed'       => [
							'type'      => 'color',
							'label'     => __( 'Mark Color', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox .wpgb-checkbox-control::before, {{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox .wpgb-checkbox-control::after' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Mixed', 'wp-grid-builder' ),
					'name'   => 'mixed',
					'fields' => [
						'checkbox_background_mixed' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="mixed"] .wpgb-checkbox-control' => [
									'background' => '{{value}}',
								],
							],
						],
						'checkbox_border_mixed'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="mixed"] .wpgb-checkbox-control' => [
									'border-color' => '{{value}}',
								],
							],
						],
						'checkbox_tick_mixed'       => [
							'type'      => 'color',
							'label'     => __( 'Mark Color', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="mixed"] .wpgb-checkbox-control::before, {{wrapper}} .wpgb-checkbox-facet .wpgb-checkbox[aria-pressed="mixed"] .wpgb-checkbox-control::after' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'checkbox',
		],
	],
];

$radio_button = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Radio', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'radio_size'     => [
			'type'      => 'range',
			'label'     => __( 'Radio Size', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 10,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-radio .wpgb-radio-control' => [
					'height' => '{{value}}',
					'width'  => '{{value}}',
				],
			],
		],
		'radio_dot_size' => [
			'type'      => 'range',
			'label'     => __( 'Dot Size', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 2,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-radio[aria-pressed="true"] .wpgb-radio-control:after' => [
					'min-height' => 'calc({{value}} + 6px)',
					'min-width'  => 'calc({{value}} + 6px)',
				],
			],
		],
		'radio_borders'  => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'radio_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-radio-facet .wpgb-radio .wpgb-radio-control' => [
							'border' => '{{value}}',
						],
					],
				],
				'radio_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-radio .wpgb-radio-control, {{wrapper}} .wpgb-radio .wpgb-radio-control:after' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'radio_shadow'   => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-radio-facet .wpgb-radio .wpgb-radio-control' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'radio_colors'   => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'radio_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-radio .wpgb-radio-control' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Pressed', 'wp-grid-builder' ),
					'name'   => 'pressed',
					'fields' => [
						'radio_color_pressed'  => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-radio[aria-pressed="true"] .wpgb-radio-control' => [
									'background' => '{{value}}',
								],
							],
						],
						'radio_border_pressed' => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-radio[aria-pressed="true"] .wpgb-radio-control' => [
									'border-color' => '{{value}}',
								],
							],
						],
						'radio_dot_pressed'    => [
							'type'      => 'color',
							'label'     => __( 'Dot Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-radio .wpgb-radio-control::after' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'radio',
		],
	],
];

$buttons = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Button', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'buttons_padding' => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} [role="button"].wpgb-button' => [
					'padding' => '{{value}}',
				],
			],
		],
		'buttons_margin'  => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} [role="button"].wpgb-button' => [
					'margin' => '{{value}}',
				],
			],
		],
		'buttons_font'    => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'buttons_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} [role="button"].wpgb-button' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'buttons_borders' => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'buttons_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} [role="button"].wpgb-button' => [
							'border' => '{{value}}',
						],
					],
				],
				'buttons_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} [role="button"].wpgb-button' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'buttons_shadow'  => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} [role="button"].wpgb-button' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'buttons_colors'  => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'buttons_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} [role="button"].wpgb-button[aria-pressed="false"]' => [
									'background' => '{{value}}',
								],
							],
						],
						'buttons_color'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} [role="button"].wpgb-button[aria-pressed="false"]' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'buttons_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} [role="button"].wpgb-button:hover:not([aria-pressed=true]):not([tabindex="-1"])' => [
									'background' => '{{value}}',
								],
							],
						],
						'buttons_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} [role="button"].wpgb-button:hover:not([aria-pressed=true]):not([tabindex="-1"])' => [
									'color' => '{{value}}',
								],
							],
						],
						'buttons_border_hover'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} [role="button"].wpgb-button:hover:not([aria-pressed=true]):not([tabindex="-1"])' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Pressed', 'wp-grid-builder' ),
					'name'   => 'pressed',
					'fields' => [
						'buttons_background_pressed' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} [role="button"].wpgb-button[aria-pressed="true"]' => [
									'background' => '{{value}}',
								],
							],
						],
						'buttons_color_pressed'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} [role="button"].wpgb-button[aria-pressed="true"]' => [
									'color' => '{{value}}',
								],
							],
						],
						'buttons_border_pressed'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} [role="button"].wpgb-button[aria-pressed="true"]' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'button', 'selection' ],
		],
	],
];

$stars_icon = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Stars Icon', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'stars_icon_size'         => [
			'type'   => 'grid',
			'fields' => [
				'stars_icon_height' => [
					'type'      => 'number',
					'label'     => __( 'Height', 'wp-grid-builder' ),
					'units'     => [
						'px' => [
							'min'  => 1,
							'max'  => 999,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{wrapper}} .wpgb-rating:not(.wpgb-rating-reset) .wpgb-rating-control' => [
							'height' => '{{value}}',
						],
					],
				],
				'stars_icon_width'  => [
					'type'      => 'number',
					'label'     => __( 'Width', 'wp-grid-builder' ),
					'units'     => [
						'px' => [
							'min'  => 1,
							'max'  => 999,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{wrapper}} .wpgb-rating:not(.wpgb-rating-reset) .wpgb-rating-control' => [
							'width' => '{{value}}',
						],
					],
				],
			],
		],
		'stars_icon_stroke_width' => [
			'type'      => 'range',
			'label'     => __( 'Stroke Width', 'wpgb-map-facet' ),
			'min'       => 0.1,
			'max'       => 10,
			'step'      => 0.01,
			'selectors' => [
				'{{wrapper}} .wpgb-rating:not(.wpgb-rating-reset) .wpgb-rating-svg' => [
					'stroke-width' => '{{value}}',
				],
			],
		],
		'stars_icon_colors'       => [
			'type'      => 'tab-panel',
			'fields'    => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'stars_icon_color' => [
							'type'      => 'color',
							'label'     => __( 'Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-rating .wpgb-rating-svg' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'stars_icon_color_hover' => [
							'type'      => 'color',
							'label'     => __( 'Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-rating:hover:not([tabindex="-1"]) .wpgb-rating-svg' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Pressed', 'wp-grid-builder' ),
					'name'   => 'pressed',
					'fields' => [
						'stars_icon_color_pressed' => [
							'type'      => 'color',
							'label'     => __( 'Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-rating[aria-pressed="true"] .wpgb-rating-svg, {{wrapper}} .wpgb-rating[aria-pressed="true"]:hover .wpgb-rating-svg' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'select_icon',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'rating',
		],
	],
];

$color_swatch = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Swatch', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'color_swatch_size'    => [
			'type'      => 'range',
			'label'     => __( 'Swatch Size', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 5,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-color-control' => [
					'width'  => '{{value}}',
					'height' => '{{value}}',
				],
			],
		],
		'color_swatch_borders' => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'color_swatch_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-color-control:after' => [
							'border' => '{{value}}',
						],
					],
				],
				'color_swatch_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-color-control, {{wrapper}} .wpgb-color-control:after' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'color_swatch_shadow'  => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-color-control' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'color_swatch_colors'  => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'color_swatch_border_color' => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-color:hover .wpgb-color-control:after' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Pressed', 'wp-grid-builder' ),
					'name'   => 'pressed',
					'fields' => [
						'color_swatch_border_color_pressed' => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-color[aria-pressed=true] .wpgb-color-control:after' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'color',
		],
	],
];

$select = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Input', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'select_padding' => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} select.wpgb-select, {{wrapper}} .wpgb-select-placeholder' => [
					'padding' => '{{value}}',
				],
			],
		],
		'select_margin'  => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-select' => [
					'margin' => '{{value}}',
				],
			],
		],
		'select_borders' => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'select_border' => [
					'type'      => 'border',
					'label'     => __( 'Borders', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-select' => [
							'border' => '{{value}}',
						],
						'{{wrapper}} select.wpgb-select + .wpgb-select-controls' => [
							'margin'       => '0 -2px',
							'border-style' => 'solid',
							'border'       => '{{value}}',
							'border-color' => 'transparent',
						],
					],
				],
				'select_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-select' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'select_font'    => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'select_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-select, {{wrapper}} .wpgb-select *' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'select_shadow'  => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-select' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'select_colors'  => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'select_text_color' => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select,' .
								'{{wrapper}} .wpgb-select input::placeholder' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-select' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'select_text_color_hover'   => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select:hover,' .
								'{{wrapper}} .wpgb-select:hover input::placeholder' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_background_hover'   => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-select:hover' => [
									'background' => '{{value}}',
								],
							],
						],
						'select_border_color_hover' => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select:hover' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Focused', 'wp-grid-builder' ),
					'name'   => 'focused',
					'fields' => [
						'select_text_color_focused'   => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select:focus,' .
								'{{wrapper}} .wpgb-select input:focus::placeholder' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_background_focused'   => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-select:focus,' .
								'{{wrapper}} .wpgb-select.wpgb-select-focused' => [
									'background' => '{{value}}',
								],
							],
						],
						'select_border_color_focused' => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select:focus,' .
								'{{wrapper}} .wpgb-select.wpgb-select-focused' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'select', 'per_page', 'sort' ],
		],
	],
];

$select_icon = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Input Icon', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'select_icon'              => [
			'type'      => 'toggle',
			'label'     => __( 'Hide Icon', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-select-controls .wpgb-select-toggle,' .
				'{{wrapper}} .wpgb-select-controls .wpgb-select-separator' => [
					'display' => 'none',
				],
			],
		],
		'select_separator'         => [
			'type'      => 'toggle',
			'label'     => __( 'Hide Separator', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-select .wpgb-select-separator,' .
				'{{wrapper}} select.wpgb-select + .wpgb-select-controls .wpgb-select-separator' => [
					'display' => 'none',
				],
			],
			'condition' => [
				[
					'field'   => 'select_icon',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'select_icon_size'         => [
			'type'      => 'range',
			'label'     => __( 'Icon Size', 'wp-grid-builder' ),
			'min'       => 5,
			'max'       => 50,
			'step'      => 1,
			'suffix'    => 'px',
			'selectors' => [
				'{{wrapper}} .wpgb-select-toggle' => [
					'transform' => 'scale(calc({{value}}/11.6))',
				],
			],
			'condition' => [
				[
					'field'   => 'select_icon',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'select_icon_stroke_width' => [
			'type'      => 'range',
			'label'     => __( 'Stroke Width', 'wpgb-map-facet' ),
			'min'       => 0.1,
			'max'       => 10,
			'step'      => 0.01,
			'selectors' => [
				'{{wrapper}} .wpgb-select-controls .wpgb-select-toggle, {{wrapper}} .wpgb-select-toggle svg' => [
					'stroke-width' => '{{value}}',
				],
			],
			'condition' => [
				[
					'field'   => 'select_icon',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'select_icon_colors'       => [
			'type'      => 'tab-panel',
			'fields'    => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'select_icon_color'      => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select-controls .wpgb-select-toggle' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_separator_color' => [
							'type'      => 'color',
							'label'     => __( 'Separator Color', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-select-controls .wpgb-select-separator,' .
								'{{wrapper}} select.wpgb-select + .wpgb-select-controls .wpgb-select-separator' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'select_icon_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select:hover .wpgb-select-controls .wpgb-select-toggle,' .
								'{{wrapper}} select.wpgb-select:hover + .wpgb-select-controls .wpgb-select-toggle' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_separator_color_hover' => [
							'type'      => 'color',
							'label'     => __( 'Separator Color', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-select:hover .wpgb-select-controls .wpgb-select-separator,' .
								'{{wrapper}} select.wpgb-select:hover + .wpgb-select-controls .wpgb-select-separator' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Focused', 'wp-grid-builder' ),
					'name'   => 'focused',
					'fields' => [
						'select_icon_color_focused'      => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} select.wpgb-select:focus + .wpgb-select-controls .wpgb-select-toggle,' .
								'{{wrapper}} .wpgb-select.wpgb-select-focused .wpgb-select-controls .wpgb-select-toggle' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_separator_color_focused' => [
							'type'      => 'color',
							'label'     => __( 'Separator Color', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} select.wpgb-select:focus + .wpgb-select-controls .wpgb-select-separator,' .
								'{{wrapper}} .wpgb-select.wpgb-select-focused .wpgb-select-controls .wpgb-select-separator' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'select_icon',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'select', 'per_page', 'sort' ],
		],
	],
];

$select_loader = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Input Loader', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'select_loader_tip'   => [
			'type'    => 'tip',
			'content' => __( 'These settings are only applied for the combobox (facet settings).', 'wp-grid-builder' ),
		],
		'select_loader'       => [
			'type'      => 'toggle',
			'label'     => __( 'Hide Loader', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-select-loader' => [
					'display' => 'none',
				],
			],
		],
		'select_loader_size'  => [
			'type'      => 'range',
			'label'     => __( 'Loader Size', 'wp-grid-builder' ),
			'min'       => 10,
			'max'       => 50,
			'step'      => 1,
			'suffix'    => 'px',
			'selectors' => [
				'{{wrapper}} .wpgb-select-loader' => [
					'transform' => 'translateZ(0) scale(calc({{value}}/36))',
				],
			],
			'condition' => [
				[
					'field'   => 'select_loader',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'select_loader_color' => [
			'type'      => 'color',
			'label'     => __( 'Loader Color', 'wp-grid-builder' ),
			'gradient'  => true,
			'selectors' => [
				'{{wrapper}} .wpgb-select-loader span' => [
					'background' => '{{value}}',
				],
			],
			'condition' => [
				[
					'field'   => 'select_loader',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'select' ],
		],
	],
];

$select_values = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Input Value', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'select_values_tip'    => [
			'type'    => 'tip',
			'content' => __( 'These settings are only applied for the combobox (facet settings).', 'wp-grid-builder' ),
		],
		'select_values_colors' => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'select_values_color'             => [
							'type'      => 'color',
							'label'     => __( 'Label Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select .wpgb-select-values .wpgb-select-value' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_values_background'        => [
							'type'      => 'color',
							'label'     => __( 'Label Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-select .wpgb-select-values .wpgb-select-value' => [
									'background' => '{{value}}',
								],
							],
						],
						'select_values_button_color'      => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select .wpgb-select-values .wpgb-select-remove' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_values_button_background' => [
							'type'      => 'color',
							'label'     => __( 'Icon Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-select .wpgb-select-values .wpgb-select-remove' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'select_values_color_hover'        => [
							'type'      => 'color',
							'label'     => __( 'Label Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select .wpgb-select-values .wpgb-select-value:hover' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_values_background_hover'   => [
							'type'      => 'color',
							'label'     => __( 'Label Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-select .wpgb-select-values .wpgb-select-value:hover' => [
									'background' => '{{value}}',
								],
							],
						],
						'select_values_button_color_hover' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select .wpgb-select-values .wpgb-select-remove:hover' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_values_button_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Icon Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-select .wpgb-select-values .wpgb-select-remove:hover' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'select' ],
		],
	],
];

$select_options = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Menu Option', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'select_options_tip'    => [
			'type'    => 'tip',
			'content' => __( 'These settings are only applied for the combobox (facet settings).', 'wp-grid-builder' ),
		],
		'select_options_font'   => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'select_options_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{style}}.wpgb-select-dropdown li' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'select_options_colors' => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'select_options_color'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{style}}.wpgb-select-dropdown li' => [
									'color' => '{{value}}',
								],
								'{{style}}.wpgb-select-dropdown li[aria-disabled="true"]' => [
									'opacity' => '0.5',
								],
							],
						],
						'select_options_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{style}}.wpgb-select-dropdown li' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'select_options_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{style}}.wpgb-select-dropdown li.wpgb-focused:not([aria-selected="true"])' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_options_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{style}}.wpgb-select-dropdown li.wpgb-focused:not([aria-selected="true"])' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Selected', 'wp-grid-builder' ),
					'name'   => 'selected',
					'fields' => [
						'select_options_color_selected' => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{style}}.wpgb-select-dropdown li[aria-selected="true"]' => [
									'color' => '{{value}}',
								],
							],
						],
						'select_options_background_selected' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{style}}.wpgb-select-dropdown li[aria-selected="true"]' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'select', 'per_page', 'sort' ],
		],
	],
];

$select_clear = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Clear Button', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'select_clear_tip'          => [
			'type'    => 'tip',
			'content' => __( 'These settings are only applied for the combobox (facet settings).', 'wp-grid-builder' ),
		],
		'select_clear'              => [
			'type'      => 'toggle',
			'label'     => __( 'Hide Button', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-select-clear' => [
					'display' => 'none',
				],
			],
		],
		'select_clear_size'         => [
			'type'      => 'range',
			'label'     => __( 'Icon Size', 'wp-grid-builder' ),
			'min'       => 5,
			'max'       => 50,
			'step'      => 1,
			'suffix'    => 'px',
			'selectors' => [
				'{{wrapper}} .wpgb-select-clear svg' => [
					'transform' => 'scale(calc({{value}}/9.4))',
				],
			],
			'condition' => [
				[
					'field'   => 'select_clear',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'select_clear_stroke_width' => [
			'type'      => 'range',
			'label'     => __( 'Stroke Width', 'wpgb-map-facet' ),
			'min'       => 0.1,
			'max'       => 10,
			'step'      => 0.01,
			'selectors' => [
				'{{wrapper}} .wpgb-select-clear svg' => [
					'stroke-width' => '{{value}}',
				],
			],
			'condition' => [
				[
					'field'   => 'select_clear',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'select_clear_colors'       => [
			'type'      => 'tab-panel',
			'fields'    => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'select_clear_color' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select-clear' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'select_clear_color_hover' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-select-clear:hover' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'select_clear',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'select', 'per_page', 'sort' ],
		],
	],
];

$selection_clear = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Clear Button', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'selection_clear'        => [
			'type'      => 'toggle',
			'label'     => __( 'Hide Button', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-button-control' => [
					'display' => 'none',
				],
			],
		],
		'selection_clear_size'   => [
			'type'      => 'range',
			'label'     => __( 'Icon Size', 'wp-grid-builder' ),
			'min'       => 5,
			'max'       => 50,
			'step'      => 1,
			'suffix'    => 'px',
			'selectors' => [
				'{{wrapper}} .wpgb-button-control' => [
					'transform' => 'scale(calc({{value}}/11))',
				],
			],
			'condition' => [
				[
					'field'   => 'selection_clear',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'selection_clear_colors' => [
			'type'      => 'tab-panel',
			'fields'    => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'selection_clear_color' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-button-control:before,' .
								'{{wrapper}} .wpgb-button-control:after' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'selection_clear_color_hover' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-button-control:hover:before,' .
								'{{wrapper}} .wpgb-button-control:hover:after' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'selection_clear',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'selection' ],
		],
	],
];

$number_inputs = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Layout', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'number_inputs_direction' => [
			'type'      => 'button',
			'label'     => __( 'Layout', 'wp-grid-builder' ),
			'options'   => [
				[
					'label' => _x( 'Vertical', 'Layout control value', 'wp-grid-builder' ),
					'value' => '',
					'icon'  => 'stack',
				],
				[
					'label' => _x( 'Horizontal', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'flex',
					'icon'  => 'row',
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-number-facet' => [
					'display'     => '{{value}}',
					'align-items' => 'flex-end',
					'gap'         => '12px',
				],
				'{{wrapper}} .wpgb-number-label' => [
					'flex-grow' => 1,
				],
			],
		],
		'number_inputs_gap'       => [
			'type'      => 'range',
			'label'     => __( 'Row Gap', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-number-facet' => [
					'gap' => '{{value}}',
				],
			],
			'condition' => [
				[
					'field'   => 'number_inputs_direction',
					'compare' => '===',
					'value'   => 'flex',
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'number',
		],
	],
];

$input = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Input', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'input_padding' => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} input.wpgb-input' => [
					'padding' => '{{value}}',
				],
			],
		],
		'input_margin'  => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} input.wpgb-input' => [
					'margin' => '{{value}}',
				],
			],
		],
		'input_borders' => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'input_border' => [
					'type'      => 'border',
					'label'     => __( 'Borders', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} input.wpgb-input'   => [
							'border' => '{{value}}',
						],
						'{{wrapper}} .acplt-clear,' .
						'{{wrapper}} .acplt-loader,' .
						'{{wrapper}} .wpgb-locate-button,' .
						'{{wrapper}} .wpgb-clear-button' => [
							'margin'       => '0 -2px',
							'border-style' => 'solid',
							'border'       => '{{value}}',
							'border-color' => 'transparent',
						],
						'{{wrapper}} .wpgb-input-icon'   => [
							'margin'              => '0 8px',
							'border-style'        => 'solid',
							'border'              => '{{value}}',
							'border-color'        => 'transparent',
							'border-top-width'    => 0,
							'border-bottom-width' => 0,
						],
					],
				],
				'input_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} input.wpgb-input' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'input_font'    => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'input_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} input.wpgb-input' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'input_shadow'  => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} input.wpgb-input' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'input_colors'  => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'input_text_color' => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} input.wpgb-input,' .
								'{{wrapper}} input.wpgb-input::placeholder' => [
									'color' => '{{value}}',
								],
							],
						],
						'input_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} input.wpgb-input' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'input_text_color_hover'   => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} input.wpgb-input:hover,' .
								'{{wrapper}} input.wpgb-input:hover::placeholder' => [
									'color' => '{{value}}',
								],
							],
						],
						'input_background_hover'   => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} input.wpgb-input:hover' => [
									'background' => '{{value}}',
								],
							],
						],
						'input_border_color_hover' => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} input.wpgb-input:hover' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Focused', 'wp-grid-builder' ),
					'name'   => 'focused',
					'fields' => [
						'input_text_color_focused'   => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} input.wpgb-input:focus,' .
								'{{wrapper}} input.wpgb-input:focus::placeholder' => [
									'color' => '{{value}}',
								],
							],
						],
						'input_background_focused'   => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} input.wpgb-input:focus' => [
									'background' => '{{value}}',
								],
							],
						],
						'input_border_color_focused' => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} input.wpgb-input:focus' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'date', 'number', 'search', 'autocomplete', 'geolocation' ],
		],
	],
];

$input_icon = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Input Icon', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'input_icon'              => [
			'type'      => 'toggle',
			'label'     => __( 'Hide Icon', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-input-icon' => [
					'display' => 'none',
				],
				'{{wrapper}} .wpgb-input'      => [
					'padding-inline-end' => 'unset',
					'text-indent'        => 'unset',
				],
			],
		],
		'input_icon_size'         => [
			'type'      => 'range',
			'label'     => __( 'Icon Size', 'wp-grid-builder' ),
			'min'       => 5,
			'max'       => 50,
			'step'      => 1,
			'suffix'    => 'px',
			'selectors' => [
				'{{wrapper}} .wpgb-input-icon' => [
					'transform' => 'scale(calc({{value}}/16))',
				],
			],
			'condition' => [
				[
					'field'   => 'input_icon',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'input_icon_stroke_width' => [
			'type'      => 'range',
			'label'     => __( 'Stroke Width', 'wpgb-map-facet' ),
			'min'       => 0.1,
			'max'       => 10,
			'step'      => 0.01,
			'selectors' => [
				'{{wrapper}} .wpgb-input-icon' => [
					'stroke-width' => '{{value}}',
				],
			],
			'condition' => [
				[
					'field'   => 'input_icon',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'input_icon_colors'       => [
			'type'      => 'tab-panel',
			'fields'    => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'input_icon_color' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-input-icon' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'input_icon_color_hover' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} label:hover .wpgb-input-icon' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Focused', 'wp-grid-builder' ),
					'name'   => 'focused',
					'fields' => [
						'select_icon_color_focused' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} label:focus-within .wpgb-input-icon' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'input_icon',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'date', 'search', 'autocomplete', 'geolocation' ],
		],
	],
];

$input_loader = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Input Loader', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'input_loader'       => [
			'type'      => 'toggle',
			'label'     => __( 'Hide Loader', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .acplt-loader' => [
					'display' => 'none',
				],
			],
		],
		'input_loader_size'  => [
			'type'      => 'range',
			'label'     => __( 'Icon Size', 'wp-grid-builder' ),
			'min'       => 5,
			'max'       => 50,
			'step'      => 1,
			'suffix'    => 'px',
			'selectors' => [
				'{{wrapper}} .acplt-loader' => [
					'transform' => 'scale(calc({{value}}/36))',
				],
			],
			'condition' => [
				[
					'field'   => 'input_loader',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'input_loader_color' => [
			'type'      => 'color',
			'label'     => __( 'Icon Color', 'wp-grid-builder' ),
			'gradient'  => true,
			'selectors' => [
				'{{wrapper}} .acplt-loader span' => [
					'background' => '{{value}}',
				],
			],
			'condition' => [
				[
					'field'   => 'input_loader',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'autocomplete', 'geolocation' ],
		],
	],
];

$input_options = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Menu Option', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'input_options_font'   => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'input_options_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{style}}.acplt-menu li' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'input_options_colors' => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'input_options_color'      => [
							'type'      => 'color',
							'label'     => __( 'Option Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{style}}.acplt-menu li' => [
									'color' => '{{value}}',
								],
							],
						],
						'input_options_background' => [
							'type'      => 'color',
							'label'     => __( 'Option Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{style}}.acplt-menu li' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'input_options_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Option Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{style}}.acplt-menu li[aria-selected="true"]' => [
									'color' => '{{value}}',
								],
							],
						],
						'input_options_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Option Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{style}}.acplt-menu li[aria-selected="true"]' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'autocomplete', 'geolocation' ],
		],
	],
];

$input_clear = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Clear Button', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'input_clear'              => [
			'type'      => 'toggle',
			'label'     => __( 'Hide Button', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-clear-button, {{wrapper}} .acplt-clear' => [
					'display' => 'none',
				],
			],
		],
		'input_clear_size'         => [
			'type'      => 'range',
			'label'     => __( 'Icon Size', 'wp-grid-builder' ),
			'min'       => 5,
			'max'       => 50,
			'step'      => 1,
			'suffix'    => 'px',
			'selectors' => [
				'{{wrapper}} .wpgb-clear-button svg, {{wrapper}} .acplt-clear svg' => [
					'transform' => 'scale(calc({{value}}/9.37))',
				],
			],
			'condition' => [
				[
					'field'   => 'input_clear',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'input_clear_stroke_width' => [
			'type'      => 'range',
			'label'     => __( 'Stroke Width', 'wpgb-map-facet' ),
			'min'       => 0.1,
			'max'       => 10,
			'step'      => 0.01,
			'selectors' => [
				'{{wrapper}} .wpgb-clear-button svg, {{wrapper}} .acplt-clear svg' => [
					'stroke-width' => '{{value}}',
				],
			],
			'condition' => [
				[
					'field'   => 'input_clear',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
		'input_clear_colors'       => [
			'type'      => 'tab-panel',
			'fields'    => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'input_clear_color' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-clear-button, {{wrapper}} .acplt-clear' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'input_clear_color_hover' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-clear-button:hover, {{wrapper}} .wpgb-clear-button:focus,' .
								'{{wrapper}} .acplt-clear:focus, {{wrapper}} .acplt-clear:hover' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'input_clear',
					'compare' => '!=',
					'value'   => 1,
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'date', 'search', 'autocomplete', 'geolocation' ],
		],
	],
];

$input_label = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Input label', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'input_label_padding' => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-number-label span' => [
					'padding' => '{{value}}',
				],
			],
		],
		'input_label_font'    => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'input_label_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-number-label span' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'input_label_color'   => [
			'type'      => 'color',
			'label'     => __( 'Text Color', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-number-label span' => [
					'color' => '{{value}}',
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'number' ],
		],
	],
];

$input_submit = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Submit Button', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'input_submit_padding' => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-number-submit' => [
					'padding' => '{{value}}',
				],
			],
		],
		'input_submit_margin'  => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-number-submit' => [
					'margin' => '{{value}}',
				],
			],
		],
		'input_submit_font'    => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'input_submit_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-number-submit' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'input_submit_borders' => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'input_submit_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-number-submit' => [
							'border' => '{{value}}',
						],
					],
				],
				'input_submit_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-number-submit' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'input_submit_shadow'  => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-number-submit' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'input_submit_colors'  => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'input_submit_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-number-submit' => [
									'background' => '{{value}}',
								],
							],
						],
						'input_submit_color'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-number-submit' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'input_submit_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-number-submit:not(:disabled):hover' => [
									'background' => '{{value}}',
								],
							],
						],
						'input_submit_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-number-submit:not(:disabled):hover' => [
									'color' => '{{value}}',
								],
							],
						],
						'input_submit_border_hover'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-number-submit:not(:disabled):hover' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Focused', 'wp-grid-builder' ),
					'name'   => 'focused',
					'fields' => [
						'input_submit_background_focused' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-number-submit:focus' => [
									'background' => '{{value}}',
								],
							],
						],
						'input_submit_color_focused'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-number-submit:focus' => [
									'color' => '{{value}}',
								],
							],
						],
						'input_submit_border_focused'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-number-submit:focus' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'number',
		],
	],
];

$range_track = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Range Track', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'range_track_height'     => [
			'type'      => 'range',
			'label'     => __( 'Height', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-range-slider' => [
					'height' => '{{value}}',
				],
			],
		],
		'range_track_borders'    => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'range_track_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-slider' => [
							'border' => '{{value}}',
						],
					],
				],
				'range_track_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-slider' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'range_track_shadow'     => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-range-slider' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'range_track_background' => [
			'type'      => 'color',
			'label'     => __( 'Background', 'wp-grid-builder' ),
			'gradient'  => true,
			'selectors' => [
				'{{wrapper}} .wpgb-range-slider' => [
					'background' => '{{value}}',
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'range',
		],
	],
];

$range_progress = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Range Progress', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'range_progress_height'     => [
			'type'      => 'range',
			'label'     => __( 'Height', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-range-slider .wpgb-range-progress' => [
					'height' => '{{value}}',
					'top'    => 'calc(50% - ( {{value}} / 2 ) )',
				],
			],
		],
		'range_progress_borders'    => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'range_progress_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-progress' => [
							'border' => '{{value}}',
						],
					],
				],
				'range_progress_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-progress' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'range_progress_shadow'     => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-range-progress' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'range_progress_background' => [
			'type'      => 'color',
			'label'     => __( 'Background', 'wp-grid-builder' ),
			'gradient'  => true,
			'selectors' => [
				'{{wrapper}} .wpgb-range-progress' => [
					'background' => '{{value}}',
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'range',
		],
	],
];

$range_thumb = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Range Thumb', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'range_thumb_height'     => [
			'type'      => 'range',
			'label'     => __( 'Height', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-range-slider .wpgb-range-thumb' => [
					'height' => '{{value}}',
				],
			],
		],
		'range_thumb_width'      => [
			'type'      => 'range',
			'label'     => __( 'Width', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-range-slider .wpgb-range-thumb' => [
					'width' => '{{value}}',
				],
				'{{wrapper}} .wpgb-range-slider .wpgb-range-progress, {{wrapper}} .wpgb-range-slider .wpgb-range-thumbs' => [
					'left'  => 'calc({{value}} / 2)',
					'right' => 'calc({{value}} / 2)',
				],
			],
		],
		'range_thumb_borders'    => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'range_thumb_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-slider .wpgb-range-thumb' => [
							'border' => '{{value}}',
						],
					],
				],
				'range_thumb_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-slider .wpgb-range-thumb' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'range_thumb_shadow'     => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-range-slider .wpgb-range-thumb' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'range_thumb_background' => [
			'type'      => 'color',
			'label'     => __( 'Background', 'wp-grid-builder' ),
			'gradient'  => true,
			'selectors' => [
				'{{wrapper}} .wpgb-range-slider .wpgb-range-thumb' => [
					'background' => '{{value}}',
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'range',
		],
	],
];

$range_values = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Range Values', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'range_values_padding'    => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-range-values' => [
					'padding' => '{{value}}',
				],
			],
		],
		'range_values_margin'     => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-range-values' => [
					'margin' => '{{value}}',
				],
			],
		],
		'range_values_font'       => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'range_values_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-values' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'range_values_borders'    => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'range_values_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-values' => [
							'border' => '{{value}}',
						],
					],
				],
				'range_values_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-values' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'range_values_shadow'     => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-range-values' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'range_values_background' => [
			'type'      => 'color',
			'label'     => __( 'Background', 'wp-grid-builder' ),
			'gradient'  => true,
			'selectors' => [
				'{{wrapper}} .wpgb-range-values' => [
					'background' => '{{value}}',
				],
			],
		],
		'range_values_color'      => [
			'type'      => 'color',
			'label'     => __( 'Text Color', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-range-values' => [
					'color' => '{{value}}',
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'range',
		],
	],
];

$range_reset = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Reset Button', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'range_reset_padding' => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-range-clear' => [
					'padding' => '{{value}}',
				],
			],
		],
		'range_reset_margin'  => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-range-clear' => [
					'margin' => '{{value}}',
				],
			],
		],
		'range_reset_font'    => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'range_reset_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-clear' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'range_reset_borders' => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'range_reset_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-clear' => [
							'border' => '{{value}}',
						],
					],
				],
				'range_reset_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-range-clear' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'range_reset_shadow'  => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-range-clear' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'range_reset_colors'  => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'range_reset_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-range-clear' => [
									'background' => '{{value}}',
								],
							],
						],
						'range_reset_color'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-range-clear' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'range_reset_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-range-clear:not(:disabled):hover' => [
									'background' => '{{value}}',
								],
							],
						],
						'range_reset_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-range-clear:not(:disabled):hover' => [
									'color' => '{{value}}',
								],
							],
						],
						'range_reset_border_hover'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-range-clear:not(:disabled):hover' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Focused', 'wp-grid-builder' ),
					'name'   => 'focus',
					'fields' => [
						'range_reset_background_focused' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-range-clear:focus' => [
									'background' => '{{value}}',
								],
							],
						],
						'range_reset_color_focused'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-range-clear:focus' => [
									'color' => '{{value}}',
								],
							],
						],
						'range_reset_border_focused'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-range-clear:focus' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'range',
		],
	],
];

$choice_label = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Choice Label', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'{{facet}}_choice_label_padding' => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} li .wpgb-{{type}}-label' => [
					'padding' => '{{value}}',
				],
			],
		],
		'{{facet}}_choice_label_margin'  => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} li .wpgb-{{type}}-label' => [
					'margin' => '{{value}}',
				],
			],
		],
		'{{facet}}_choice_label_borders' => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'{{facet}}_choice_label_border' => [
					'type'      => 'border',
					'label'     => __( 'Borders', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} li .wpgb-{{type}}-label' => [
							'border' => '{{value}}',
						],
					],
				],
				'{{facet}}_choice_label_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} li .wpgb-{{type}}-label' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'{{facet}}_choice_label_font'    => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'{{facet}}_choice_label_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} li .wpgb-{{type}}-label' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'{{facet}}_choice_label_colors'  => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'{{facet}}_choice_label_color' => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} li .wpgb-{{type}}-label' => [
									'color' => '{{value}}',
								],
							],
						],
						'{{facet}}_choice_label_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} li .wpgb-{{type}}-label' => [
									'background' => '{{value}}',
								],
								'{{wrapper}} li .wpgb-color-label:after' => [
									'border-top-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'{{facet}}_choice_label_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} [aria-pressed]:hover .wpgb-{{type}}-label' => [
									'color' => '{{value}}',
								],
							],
						],
						'{{facet}}_choice_label_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} [aria-pressed]:hover .wpgb-{{type}}-label' => [
									'background' => '{{value}}',
								],
								'{{wrapper}} [aria-pressed]:hover .wpgb-color-label:after' => [
									'border-top-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Pressed', 'wp-grid-builder' ),
					'name'   => 'pressed',
					'fields' => [
						'{{facet}}_choice_label_color_pressed'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} [aria-pressed="true"] .wpgb-{{type}}-label' => [
									'color' => '{{value}}',
								],
							],
						],
						'{{facet}}_choice_label_background_pressed' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} [aria-pressed="true"] .wpgb-{{type}}-label' => [
									'background' => '{{value}}',
								],
								'{{wrapper}} [aria-pressed="true"] .wpgb-color-label:after' => [
									'border-top-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => '{{facet}}',
		],
	],
];

$choice_counter = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Choice Counter', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'{{facet}}_choice_counter_alignment' => [
			'type'      => 'button',
			'label'     => __( 'Alignment', 'wp-grid-builder' ),
			'options'   => [
				[
					'label' => __( 'Start', 'wp-grid-builder' ),
					'value' => '',
					'icon'  => 'justifyLeft',
				],
				[
					'label' => __( 'End', 'wp-grid-builder' ),
					'value' => 'space-between',
					'icon'  => 'justifyRight',
				],
			],
			'selectors' => [
				'{{wrapper}} li .wpgb-{{type}}-label' => [
					'display'         => 'flex',
					'justify-content' => '{{value}}',
				],
			],
		],
		'{{facet}}_choice_counter_padding'   => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} li .wpgb-{{type}}-label span' => [
					'padding' => '{{value}}',
				],
			],
		],
		'{{facet}}_choice_counter_margin'    => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} li .wpgb-{{type}}-label span' => [
					'margin' => '{{value}}',
				],
			],
		],
		'{{facet}}_choice_counter_borders'   => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'{{facet}}_choice_counter_border' => [
					'type'      => 'border',
					'label'     => __( 'Borders', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} li .wpgb-{{type}}-label span' => [
							'border' => '{{value}}',
						],
					],
				],
				'{{facet}}_choice_counter_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} li .wpgb-{{type}}-label span' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'{{facet}}_choice_counter_font'      => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'{{facet}}_choice_counter_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} li .wpgb-{{type}}-label span' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'{{facet}}_choice_counter_colors'    => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'{{facet}}_choice_counter_color' => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} li .wpgb-{{type}}-label span' => [
									'color' => '{{value}}',
								],
							],
						],
						'{{facet}}_choice_counter_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} li .wpgb-{{type}}-label span' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'{{facet}}_choice_counter_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} [role="button"][aria-pressed]:hover .wpgb-{{type}}-label span' => [
									'color' => '{{value}}',
								],
							],
						],
						'{{facet}}_choice_counter_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} [role="button"][aria-pressed]:hover .wpgb-{{type}}-label span' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Pressed', 'wp-grid-builder' ),
					'name'   => 'pressed',
					'fields' => [
						'{{facet}}_choice_counter_color_pressed' => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} [role="button"][aria-pressed="true"] .wpgb-{{type}}-label span' => [
									'color' => '{{value}}',
								],
							],
						],
						'{{facet}}_choice_counter_background_pressed' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} [role="button"][aria-pressed="true"] .wpgb-{{type}}-label span' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => '{{facet}}',
		],
	],
];

$treeview_button = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Treeview Button', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'treeview_button_size'      => [
			'type'      => 'range',
			'label'     => __( 'Button Size', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 10,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}}  ul li[aria-expanded]:after' => [
					'height' => '{{value}}',
					'width'  => '{{value}}',
				],
			],
		],
		'treeview_button_icon_size' => [
			'type'      => 'range',
			'label'     => __( 'Icon Size', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 5,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{wrapper}} ul li[aria-expanded="true"]:after' => [
					'background-size' => '{{value}} calc({{value}} * 0.2)',
				],
				'{{wrapper}} ul li[aria-expanded="false"]:after' => [
					'background-size' => 'calc({{value}} * 0.2) {{value}}, {{value}} calc({{value}} * 0.2)',
				],
			],
		],
		'treeview_button_margin'    => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} ul li[aria-expanded]:after' => [
					'margin' => '{{value}}',
				],
			],
		],
		'treeview_button_borders'   => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'treeview_button_border' => [
					'type'      => 'border',
					'label'     => __( 'Borders', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} ul li[aria-expanded]:after' => [
							'border' => '{{value}}',
						],
					],
				],
				'treeview_button_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} ul li[aria-expanded]:after' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'treeview_button_shadow'    => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} ul li[aria-expanded]:after' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'treeview_button_colors'    => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Collapsed', 'wp-grid-builder' ),
					'name'   => 'collapsed',
					'fields' => [
						'treeview_button_background_collapsed' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} ul li[aria-expanded="false"]:after' => [
									'background-color' => '{{value}}',
								],
							],
						],
						'treeview_button_color_collapsed' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} ul li[aria-expanded="false"]:after' => [
									'background-image' => 'linear-gradient({{value}}, {{value}}),linear-gradient({{value}}, {{value}})',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Expanded', 'wp-grid-builder' ),
					'name'   => 'expanded',
					'fields' => [
						'treeview_button_background_expanded' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} ul li[aria-expanded="true"]:after' => [
									'background-color' => '{{value}}',
								],
							],
						],
						'treeview_button_color_expanded' => [
							'type'      => 'color',
							'label'     => __( 'Icon Color', 'wp-grid-builder' ),

							'selectors' => [
								'{{wrapper}} ul li[aria-expanded="true"]:after' => [
									'background-image' => 'linear-gradient({{value}}, {{value}}),linear-gradient({{value}}, {{value}})',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'checkbox',
		],
	],
];

$toggle_button = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Toggle Button', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'toggle_button_alignment' => [
			'type'      => 'button',
			'label'     => __( 'Alignment', 'wp-grid-builder' ),
			'options'   => [
				[
					'label' => _x( 'Vertical', 'Layout control value', 'wp-grid-builder' ),
					'value' => '',
					'icon'  => 'justifyLeft',
				],
				[
					'label' => _x( 'Center', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'center',
					'icon'  => 'justifyCenter',
				],
				[
					'label' => _x( 'Right', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'flex-end',
					'icon'  => 'justifyRight',
				],
				[
					'label' => _x( 'Stretch', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'stretch',
					'icon'  => 'justifyStretch',
				],
			],
			'selectors' => [
				'{{wrapper}} .wpgb-toggle-hidden' => [
					'align-self' => '{{value}}',
				],
			],
		],
		'toggle_button_padding'   => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-toggle-hidden' => [
					'padding' => '{{value}}',
				],
			],
		],
		'toggle_button_margin'    => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-toggle-hidden' => [
					'margin' => '{{value}}',
				],
			],
		],
		'toggle_button_borders'   => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'toggle_button_border' => [
					'type'      => 'border',
					'label'     => __( 'Borders', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-toggle-hidden' => [
							'border' => '{{value}}',
						],
					],
				],
				'toggle_button_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-toggle-hidden' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'toggle_button_font'      => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'toggle_button_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-toggle-hidden' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'toggle_button_shadow'    => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-toggle-hidden' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'toggle_button_colors'    => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'toggle_button_color'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-toggle-hidden' => [
									'color' => '{{value}}',
								],
							],
						],
						'toggle_button_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-toggle-hidden' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'toggle_button_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-toggle-hidden:hover' => [
									'color' => '{{value}}',
								],
							],
						],
						'toggle_button_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-toggle-hidden:hover' => [
									'background' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'checkbox', 'radio', 'button', 'hierarchy', 'color' ],
		],
	],
];

$pagination_layout = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Layout', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'pagination_alignment' => [
			'type'           => 'button',
			'label'          => __( 'Aligment', 'wp-grid-builder' ),
			'isDeselectable' => true,
			'options'        => [
				[
					'label' => _x( 'Left', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'flex-start',
					'icon'  => 'justifyLeft',
				],
				[
					'label' => _x( 'Center', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'center',
					'icon'  => 'justifyCenter',
				],
				[
					'label' => _x( 'Right', 'Layout control value', 'wp-grid-builder' ),
					'value' => 'flex-end',
					'icon'  => 'justifyRight',
				],
			],
			'selectors'      => [
				'{{wrapper}} .wpgb-pagination'    => [
					'display'         => 'flex',
					'flex-wrap'       => 'wrap',
					'justify-content' => '{{value}}',
				],
				'{{wrapper}} .wpgb-pagination li' => [
					'margin' => 0,
				],
			],
		],
		'pagination_gaps'      => [
			'type'      => 'grid',
			'fields'    => [
				'pagination_row_gap'    => [
					'type'      => 'number',
					'label'     => __( 'Row Gap', 'wp-grid-builder' ),
					'units'     => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{wrapper}} .wpgb-pagination' => [
							'row-gap' => '{{value}}',
						],
					],
				],
				'pagination_column_gap' => [
					'type'      => 'number',
					'label'     => __( 'Column Gap', 'wp-grid-builder' ),
					'units'     => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{wrapper}} .wpgb-pagination' => [
							'column-gap' => '{{value}}',
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'pagination_alignment',
					'compare' => '!=',
					'value'   => '',
				],
			],
		],
		'pagination_padding'   => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-page' => [
					'height' => 'auto',
				],
				'{{wrapper}} .wpgb-pagination .wpgb-page > *' => [
					'padding' => '{{value}}',
				],
			],
		],
		'pagination_margin'    => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-pagination li' => [
					'margin' => '{{value}}',
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'pagination',
		],
	],
];

$pagination_page = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Pages', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'pagination_page_font'    => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'pagination_page_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-pagination .wpgb-page > *' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'pagination_page_borders' => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'pagination_page_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-pagination .wpgb-page > a' => [
							'border' => '{{value}}',
						],
					],
				],
				'pagination_page_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-pagination .wpgb-page > a' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'pagination_page_shadow'  => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-pagination .wpgb-page > a' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'pagination_page_colors'  => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'pagination_page_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-pagination .wpgb-page > a' => [
									'background' => '{{value}}',
								],
							],
						],
						'pagination_page_color'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-pagination .wpgb-page > a' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'pagination_page_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-pagination .wpgb-page:hover > a' => [
									'background' => '{{value}}',
								],
							],
						],
						'pagination_page_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-pagination .wpgb-page:hover > a' => [
									'color' => '{{value}}',
								],
							],
						],
						'pagination_page_border_hover'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-pagination .wpgb-page:hover > a' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Current', 'wp-grid-builder' ),
					'name'   => 'current',
					'fields' => [
						'pagination_page_background_current' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} .wpgb-pagination .wpgb-page > a[aria-current="true"]' => [
									'background' => '{{value}}',
								],
							],
						],
						'pagination_page_color_current'  => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-pagination .wpgb-page > a[aria-current="true"]' => [
									'color' => '{{value}}',
								],
							],
						],
						'pagination_page_border_current' => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} .wpgb-pagination .wpgb-page > a[aria-current="true"]' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'pagination',
		],
	],
];

$pagination_dots = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Dots', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'pagination_dots_borders'    => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'pagination_dots_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-pagination .wpgb-page > span' => [
							'border' => '{{value}}',
						],
					],
				],
				'pagination_dots_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-pagination .wpgb-page > span' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'pagination_dots_shadow'     => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-pagination .wpgb-page > span' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'pagination_dots_background' => [
			'type'      => 'color',
			'label'     => __( 'Background', 'wp-grid-builder' ),
			'gradient'  => true,
			'selectors' => [
				'{{wrapper}} .wpgb-pagination .wpgb-page > span' => [
					'background' => '{{value}}',
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'pagination',
		],
	],
];

$result_counts = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Content', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'result_counts_padding'    => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-result-count' => [
					'padding' => '{{value}}',
				],
			],
		],
		'result_counts_margin'     => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-result-count' => [
					'margin' => '{{value}}',
				],
			],
		],
		'result_counts_borders'    => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'result_counts_border' => [
					'type'      => 'border',
					'label'     => __( 'Borders', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-result-count' => [
							'border' => '{{value}}',
						],
					],
				],
				'result_counts_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-result-count' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'result_counts_font'       => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'result_counts_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} .wpgb-result-count' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'result_counts_color'      => [
			'type'      => 'color',
			'label'     => __( 'Text Color', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} .wpgb-result-count' => [
					'color' => '{{value}}',
				],
			],
		],
		'result_counts_background' => [
			'type'      => 'color',
			'label'     => __( 'Background', 'wp-grid-builder' ),
			'gradient'  => true,
			'selectors' => [
				'{{wrapper}} .wpgb-result-count' => [
					'background' => '{{value}}',
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'result_count',
		],
	],
];

$button = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Button', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'button_width'   => [
			'type'      => 'range',
			'label'     => __( 'Width', 'wp-grid-builder' ),
			'units'     => [
				'custom' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 0.01,
				],
				'px'     => [
					'min'  => 0,
					'max'  => 999,
					'step' => 1,
				],
				'em'     => [
					'min'  => 0,
					'max'  => 100,
					'step' => 0.01,
				],
				'rem'    => [
					'min'  => 0,
					'max'  => 100,
					'step' => 0.01,
				],
				'%'      => [
					'min'  => 0,
					'max'  => 100,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{wrapper}} button.wpgb-button' => [
					'width' => '{{value}}',
				],
			],
		],
		'button_padding' => [
			'type'      => 'padding',
			'label'     => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} button.wpgb-button' => [
					'padding' => '{{value}}',
				],
			],
		],
		'button_margin'  => [
			'type'      => 'margin',
			'label'     => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} button.wpgb-button' => [
					'margin' => '{{value}}',
				],
			],
		],
		'button_font'    => [
			'type'   => 'popover',
			'label'  => __( 'Typography', 'wp-grid-builder' ),
			'fields' => [
				'button_typography' => [
					'type'      => 'typography',
					'label'     => __( 'Typography', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} button.wpgb-button' => [
							'font' => '{{value}}',
						],
					],
				],
			],
		],
		'button_borders' => [
			'type'   => 'popover',
			'label'  => __( 'Borders', 'wp-grid-builder' ),
			'fields' => [
				'button_border' => [
					'type'      => 'border',
					'label'     => __( 'Border', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} button.wpgb-button' => [
							'border' => '{{value}}',
						],
					],
				],
				'button_radius' => [
					'type'      => 'radius',
					'label'     => __( 'Radius', 'wp-grid-builder' ),
					'selectors' => [
						'{{wrapper}} button.wpgb-button' => [
							'border-radius' => '{{value}}',
						],
					],
				],
			],
		],
		'button_shadow'  => [
			'type'      => 'box-shadow',
			'label'     => __( 'Box Shadow', 'wp-grid-builder' ),
			'selectors' => [
				'{{wrapper}} button.wpgb-button' => [
					'box-shadow' => '{{value}}',
				],
			],
		],
		'button_colors'  => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'button_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-button' => [
									'background' => '{{value}}',
								],
							],
						],
						'button_color'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-button' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'button_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-button:hover:not(:disabled), {{wrapper}} button.wpgb-button:focus' => [
									'background' => '{{value}}',
								],
							],
						],
						'button_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-button:hover:not(:disabled), {{wrapper}} button.wpgb-button:focus' => [
									'color' => '{{value}}',
								],
							],
						],
						'button_border_hover'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-button:hover:not(:disabled), {{wrapper}} button.wpgb-button:focus' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Disabled', 'wp-grid-builder' ),
					'name'   => 'disabled',
					'fields' => [
						'button_background_disabled' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-button:disabled' => [
									'background' => '{{value}}',
								],
							],
						],
						'button_color_disabled'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-button:disabled' => [
									'color' => '{{value}}',
								],
							],
						],
						'button_border_disabled'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-button:disabled' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => 'IN',
			'value'   => [ 'load_more', 'reset', 'apply' ],
		],
	],
];

$load_more = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Load More', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'load_more_colors' => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'load_more_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-load-more' => [
									'background' => '{{value}}',
								],
							],
						],
						'load_more_color'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-load-more' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'load_more_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-load-more:hover:not(:disabled), {{wrapper}} button.wpgb-load-more:focus' => [
									'background' => '{{value}}',
								],
							],
						],
						'load_more_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-load-more:hover:not(:disabled), {{wrapper}} button.wpgb-load-more:focus' => [
									'color' => '{{value}}',
								],
							],
						],
						'load_more_border_hover'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-load-more:hover:not(:disabled), {{wrapper}} button.wpgb-load-more:focus' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Disabled', 'wp-grid-builder' ),
					'name'   => 'disabled',
					'fields' => [
						'load_more_background_disabled' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-load-more:disabled' => [
									'background' => '{{value}}',
								],
							],
						],
						'load_more_color_disabled'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-load-more:disabled' => [
									'color' => '{{value}}',
								],
							],
						],
						'load_more_border_disabled'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-load-more:disabled' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'load_more',
		],
	],
];

$reset_button = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Reset', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'reset_button_colors' => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'reset_button_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-reset' => [
									'background' => '{{value}}',
								],
							],
						],
						'reset_button_color'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-reset' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'reset_button_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-reset:hover:not(:disabled), {{wrapper}} button.wpgb-reset:focus' => [
									'background' => '{{value}}',
								],
							],
						],
						'reset_button_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-reset:hover:not(:disabled), {{wrapper}} button.wpgb-reset:focus' => [
									'color' => '{{value}}',
								],
							],
						],
						'reset_button_border_hover'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-reset:hover:not(:disabled), {{wrapper}} button.wpgb-reset:focus' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Disabled', 'wp-grid-builder' ),
					'name'   => 'disabled',
					'fields' => [
						'reset_button_background_disabled' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-reset:disabled' => [
									'background' => '{{value}}',
								],
							],
						],
						'reset_button_color_disabled'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-reset:disabled' => [
									'color' => '{{value}}',
								],
							],
						],
						'reset_button_border_disabled'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-reset:disabled' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'reset',
		],
	],
];

$apply_button = [
	'type'        => 'panel',
	'group'       => 'facet_styles_panels',
	'title'       => __( 'Apply', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'apply_button_colors' => [
			'type'   => 'tab-panel',
			'fields' => [
				[
					'type'   => 'tab',
					'title'  => __( 'Normal', 'wp-grid-builder' ),
					'name'   => 'normal',
					'fields' => [
						'apply_button_background' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-apply' => [
									'background' => '{{value}}',
								],
							],
						],
						'apply_button_color'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-apply' => [
									'color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Hover', 'wp-grid-builder' ),
					'name'   => 'hover',
					'fields' => [
						'apply_button_background_hover' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-apply:hover:not(:disabled), {{wrapper}} button.wpgb-apply:focus' => [
									'background' => '{{value}}',
								],
							],
						],
						'apply_button_color_hover'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-apply:hover:not(:disabled), {{wrapper}} button.wpgb-apply:focus' => [
									'color' => '{{value}}',
								],
							],
						],
						'apply_button_border_hover'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-apply:hover:not(:disabled), {{wrapper}} button.wpgb-apply:focus' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
				[
					'type'   => 'tab',
					'title'  => __( 'Disabled', 'wp-grid-builder' ),
					'name'   => 'disabled',
					'fields' => [
						'apply_button_background_disabled' => [
							'type'      => 'color',
							'label'     => __( 'Background', 'wp-grid-builder' ),
							'gradient'  => true,
							'selectors' => [
								'{{wrapper}} button.wpgb-apply:disabled' => [
									'background' => '{{value}}',
								],
							],
						],
						'apply_button_color_disabled'      => [
							'type'      => 'color',
							'label'     => __( 'Text Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-apply:disabled' => [
									'color' => '{{value}}',
								],
							],
						],
						'apply_button_border_disabled'     => [
							'type'      => 'color',
							'label'     => __( 'Border Color', 'wp-grid-builder' ),
							'selectors' => [
								'{{wrapper}} button.wpgb-apply:disabled' => [
									'border-color' => '{{value}}',
								],
							],
						],
					],
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'apply',
		],
	],
];

return [
	'name'              => [
		'type'   => 'text',
		'hidden' => true,
	],
	'slug'              => [
		'type'   => 'text',
		'hidden' => true,
	],
	'type'              => [
		'type'   => 'text',
		'hidden' => true,
	],
	'css'               => [
		'type'   => 'code',
		'mode'   => 'css',
		'hidden' => true,
	],
	'google'            => [
		'type'   => 'fonts',
		'hidden' => true,
	],
	'breakpoints'       => [
		'type'   => 'repeater',
		'hidden' => true,
		'fields' => [
			'id'    => [
				'type' => 'text',
			],
			'icon'  => [
				'type' => 'text',
			],
			'label' => [
				'type' => 'text',
			],
			'width' => [
				'type' => 'number',
				'min'  => 1,
				'max'  => 9999,
				'step' => 1,
			],
		],
	],
	'facet_title'       => $facet_title,
	'checkbox_list'     => Helpers::array_replace( '{{facet}}', 'checkbox', $listing ),
	'radio_list'        => Helpers::array_replace( '{{facet}}', 'radio', $listing ),
	'hierarchy_list'    => Helpers::array_replace( '{{facet}}', 'hierarchy', $listing ),
	'rating_list'       => Helpers::array_replace( '{{facet}}', 'rating', $listing ),
	'button_list'       => Helpers::array_replace( '{{facet}}', 'button', $inline_listing ),
	'color_list'        => Helpers::array_replace( '{{facet}}', 'color', $inline_listing ),
	'az_index_list'     => Helpers::array_replace( '{{facet}}', 'az_index', $inline_listing ),
	'selection_list'    => Helpers::array_replace( '{{facet}}', 'selection', $inline_listing ),
	'number_inputs'     => $number_inputs,
	'checkbox'          => $checkbox,
	'radio_button'      => $radio_button,
	'buttons'           => $buttons,
	'stars_icon'        => $stars_icon,
	'color_swatch'      => $color_swatch,
	'select'            => $select,
	'select_icon'       => $select_icon,
	'select_loader'     => $select_loader,
	'select_values'     => $select_values,
	'select_options'    => $select_options,
	'select_clear'      => $select_clear,
	'selection_clear'   => $selection_clear,
	'input'             => $input,
	'input_icon'        => $input_icon,
	'input_loader'      => $input_loader,
	'input_options'     => $input_options,
	'input_clear'       => $input_clear,
	'input_label'       => $input_label,
	'input_submit'      => $input_submit,
	'range_track'       => $range_track,
	'range_progress'    => $range_progress,
	'range_thumb'       => $range_thumb,
	'range_values'      => $range_values,
	'range_reset'       => $range_reset,
	'checkbox_label'    => Helpers::array_replace( '{{facet}}', 'checkbox', $choice_label ),
	'radio_label'       => Helpers::array_replace( '{{facet}}', 'radio', $choice_label ),
	'button_label'      => Helpers::array_replace( '{{facet}}', 'button', $choice_label ),
	'hierarchy_label'   => Helpers::array_replace( '{{facet}}', 'hierarchy', $choice_label ),
	'rating_label'      => Helpers::array_replace( '{{facet}}', 'rating', $choice_label ),
	'color_label'       => Helpers::array_replace( '{{facet}}', 'color', $choice_label ),
	'az_index_label'    => Helpers::array_replace( '{{facet}}', 'az_index', $choice_label ),
	'checkbox_counter'  => Helpers::array_replace( '{{facet}}', 'checkbox', $choice_counter ),
	'radio_counter'     => Helpers::array_replace( '{{facet}}', 'radio', $choice_counter ),
	'button_counter'    => Helpers::array_replace( '{{facet}}', 'button', $choice_counter ),
	'hierarchy_counter' => Helpers::array_replace( '{{facet}}', 'hierarchy', $choice_counter ),
	'rating_counter'    => Helpers::array_replace( '{{facet}}', 'rating', $choice_counter ),
	'color_counter'     => Helpers::array_replace( '{{facet}}', 'color', $choice_counter ),
	'az_index_counter'  => Helpers::array_replace( '{{facet}}', 'az_index', $choice_counter ),
	'treeview_button'   => $treeview_button,
	'toggle_button'     => $toggle_button,
	'pagination_layout' => $pagination_layout,
	'pagination_page'   => $pagination_page,
	'pagination_dots'   => $pagination_dots,
	'result_counts'     => $result_counts,
	'button'            => $button,
	'load_more'         => $load_more,
	'reset_button'      => $reset_button,
	'apply_button'      => $apply_button,
];
