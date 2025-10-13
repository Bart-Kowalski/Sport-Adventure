<?php
/**
 * Card controls
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$card_general = [
	'type'   => 'panel',
	'fields' => [
		'name'        => [
			'type'        => 'text',
			'label'       => __( 'Card Name', 'wp-grid-builder' ),
			'placeholder' => __( 'Enter a card name', 'wp-grid-builder' ),
		],
		'card_width'  => [
			'type'  => 'range',
			'label' => __( 'Preview Width', 'wp-grid-builder' ),
			'units' => [
				'px' => [
					'min'  => 300,
					'max'  => 1200,
					'step' => 1,
				],
			],
		],
		'responsive'  => [
			'type'  => 'toggle',
			'label' => __( 'Responsive Font', 'wp-grid-builder' ),
		],
		'breakpoints' => [
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
	],
];

$card_layout = [
	'type'        => 'panel',
	'initialOpen' => true,
	'title'       => __( 'Layout', 'wp-grid-builder' ),
	'fields'      => [
		'type'             => [
			'type'        => 'button',
			'label'       => __( 'Type', 'wp-grid-builder' ),
			'placeholder' => __( 'Unset', 'wp-grid-builder' ),
			'options'     => [
				[
					'value'  => 'masonry',
					'label'  => __( 'Masonry', 'wp-grid-builder' ),
					'icon'   => 'masonry',
					'inline' => true,
				],
				[
					'value'  => 'metro',
					'label'  => __( 'Metro / Justified', 'wp-grid-builder' ),
					'icon'   => 'metro',
					'inline' => true,
				],
			],
		],
		'card_layout'      => [
			'type'        => 'button',
			'label'       => __( 'Alignment', 'wp-grid-builder' ),
			'placeholder' => __( 'Unset', 'wp-grid-builder' ),
			'options'     => [
				[
					'value' => 'vertical',
					'label' => __( 'Vertical', 'wp-grid-builder' ),
					'icon'  => 'arrowDown',
				],
				[
					'value' => 'horizontal',
					'label' => __( 'Horizontal', 'wp-grid-builder' ),
					'icon'  => 'arrowRight',
				],
			],
			'condition'   => [
				[
					'field'   => 'type',
					'compare' => '!==',
					'value'   => 'metro',
				],
			],
		],
		'switch_layout'    => [
			'type'      => 'range',
			'label'     => __( 'Switch to Vertical At', 'wp-grid-builder' ),
			'help'      => __( 'Changes the alignment to vertical at a browser width. Set 0 to not change the alignment.', 'wp-grid-builder' ),
			'units'     => [
				'px' => [
					'min'  => 0,
					'max'  => 2560,
					'step' => 1,
				],
			],
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '!==',
					'value'   => 'metro',
				],
				[
					'field'   => 'card_layout',
					'compare' => '===',
					'value'   => 'horizontal',
				],
			],
		],
		// Version used when editing the card.
		'version'          => [
			'type'   => 'text',
			'hidden' => true,
		],
		// Fallback.
		'content_position' => [
			'type'   => 'button',
			'hidden' => true,
		],
	],
];

$card_blocks = [
	'type'        => 'panel',
	'initialOpen' => true,
	'title'       => __( 'Blocks', 'wp-grid-builder' ),
	'fields'      => [
		'display_header' => [
			'type'      => 'toggle',
			'label'     => __( 'Card Header', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '!==',
					'value'   => 'metro',
				],
				[
					'field'   => 'card_layout',
					'compare' => '!==',
					'value'   => 'horizontal',
				],
			],
		],
		'display_media'  => [
			'type'      => 'toggle',
			'label'     => __( 'Card Media', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '!==',
					'value'   => 'metro',
				],
				[
					'field'   => 'card_layout',
					'compare' => '!==',
					'value'   => 'horizontal',
				],
			],
		],
		'display_body'   => [
			'type'      => 'toggle',
			'label'     => __( 'Card Body', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'card_layout',
					'compare' => '!==',
					'value'   => 'horizontal',
				],
			],
		],
		'display_footer' => [
			'type'  => 'toggle',
			'label' => __( 'Card Footer', 'wp-grid-builder' ),
		],
	],
	'condition'   => [
		[
			'field'   => 'type',
			'compare' => '!==',
			'value'   => 'metro',
		],
	],
];

$card_thumbnail = [
	'type'        => 'panel',
	'title'       => __( 'Thumbnail', 'wp-grid-builder' ),
	'initialOpen' => true,
	'fields'      => [
		'media_position'  => [
			'type'         => 'button',
			'label'        => __( 'Position', 'wp-grid-builder' ),
			'defaultValue' => 'left',
			'options'      => [
				[
					'value' => 'left',
					'label' => __( 'Left', 'wp-grid-builder' ),
					'icon'  => 'justifyLeft',
				],
				[
					'value' => 'right',
					'label' => __( 'Right', 'wp-grid-builder' ),
					'icon'  => 'justifyRight',
				],
			],
			'condition'    => [
				[
					'field'   => 'type',
					'compare' => '!==',
					'value'   => 'metro',
				],
				[
					'field'   => 'card_layout',
					'compare' => '===',
					'value'   => 'horizontal',
				],
			],
		],
		'media_width'     => [
			'type'      => 'range',
			'label'     => __( 'Width', 'wp-grid-builder' ),
			'units'     => [
				'%' => [
					'min'  => 10,
					'max'  => 90,
					'step' => 0.1,
				],
			],
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '!==',
					'value'   => 'metro',
				],
				[
					'field'   => 'card_layout',
					'compare' => '===',
					'value'   => 'horizontal',
				],
			],
		],
		'display_overlay' => [
			'type'      => 'toggle',
			'label'     => __( 'Overlay', 'wp-grid-builder' ),
			'condition' => [
				'relation' => 'OR',
				[
					'field'   => 'display_media',
					'compare' => '==',
					'value'   => true,
				],
				[
					'field'   => 'card_layout',
					'compare' => '===',
					'value'   => 'horizontal',
				],
				[
					'field'   => 'type',
					'compare' => '===',
					'value'   => 'metro',
				],
			],
		],
		'flex_media'      => [
			'type'      => 'toggle',
			'label'     => __( 'Auto Height', 'wp-grid-builder' ),
			'help'      => __( 'Adjusts thumbnail height to the blocks added in the media content.', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'type',
					'compare' => '!==',
					'value'   => 'metro',
				],
				[
					'field'   => 'display_media',
					'compare' => '==',
					'value'   => true,
				],
			],
		],
	],
	'condition'   => [
		'relation' => 'OR',
		[
			'field'   => 'display_media',
			'compare' => '==',
			'value'   => true,
		],
		[
			'field'   => 'card_layout',
			'compare' => '===',
			'value'   => 'horizontal',
		],
		[
			'field'   => 'type',
			'compare' => '===',
			'value'   => 'metro',
		],
	],
];

$block_settings_content = array_map(
	function() {

		return [
			'type'   => 'text',
			'hidden' => true,
		];
	},
	array_flip( [ 'name', 'source', 'post_field', 'product_field', 'map_block', 'learndash_block', 'user_field', 'term_field', 'block_name', 'idle_scheme', 'hover_scheme' ] )
);

$block_settings_interaction = [
	'type'        => 'panel',
	'title'       => __( 'Interaction', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'action_type'     => [
			'type'        => 'select',
			'label'       => __( 'On Click', 'wp-grid-builder' ),
			'placeholder' => __( 'None', 'wp-grid-builder' ),
			'options'     => [
				[
					'value' => 'link',
					'label' => __( 'Redirect To', 'wp-grid-builder' ),
				],
				[
					'value' => 'open_media',
					'label' => __( 'Open/Play Media', 'wp-grid-builder' ),
				],
			],
		],
		'link_url'        => [
			'type'         => 'select',
			'label'        => __( 'Link To', 'wp-grid-builder' ),
			'isClearable'  => false,
			'defaultValue' => 'single_post',
			'options'      => [
				[
					'value' => 'single_post',
					'label' => __( 'Single Page (post, user or term)', 'wp-grid-builder' ),
				],
				[
					'value' => 'author_page',
					'label' => __( 'Author Archive Page', 'wp-grid-builder' ),
				],
				[
					'value' => 'metadata',
					'label' => 'Custom Field',
				],
				[
					'value' => 'custom_url',
					'label' => __( 'Custom URL', 'wp-grid-builder' ),
				],
			],
			'condition'    => [
				[
					'field'   => 'action_type',
					'compare' => '===',
					'value'   => 'link',
				],
			],
		],
		'custom_url'      => [
			'type'        => 'url',
			'label'       => __( 'Custom URL', 'wp-grid-builder' ),
			'dynamicData' => 'card',
			'condition'   => [
				[
					'field'   => 'action_type',
					'compare' => '===',
					'value'   => 'link',
				],
				[
					'field'   => 'link_url',
					'compare' => '===',
					'value'   => 'custom_url',
				],
			],
		],
		'meta_key'        => [
			'type'      => 'select',
			'label'     => __( 'Custom Field', 'wp-grid-builder' ),
			'async'     => [
				'wpgb/v2/metadata?object=registered',
				'wpgb/v2/metadata?object=post',
				'wpgb/v2/metadata?object=term',
				'wpgb/v2/metadata?object=user',
			],
			'condition' => [
				[
					'field'   => 'action_type',
					'compare' => '===',
					'value'   => 'link',
				],
				[
					'field'   => 'link_url',
					'compare' => '===',
					'value'   => 'metadata',
				],
			],
		],
		'link_target'     => [
			'type'      => 'toggle',
			'label'     => __( 'Open in new tab', 'wp-grid-builder' ),
			'options'   => [
				[
					'value' => '_self',
					'label' => __( 'Same Window', 'wp-grid-builder' ),
				],
				[
					'value' => '_blank',
					'label' => __( 'New Window', 'wp-grid-builder' ),
				],
			],
			'condition' => [
				[
					'field'   => 'action_type',
					'compare' => '===',
					'value'   => 'link',
				],
			],
		],
		// To save values "_self" and "_blank".
		'link_target_2'   => [
			'name'   => 'link_target',
			'type'   => 'text',
			'hidden' => true,
		],
		'link_attributes' => [
			'type'      => 'popover',
			'label'     => __( 'Link Attributes', 'wp-grid-builder' ),
			'fields'    => [
				'link_aria_label' => [
					'type'        => 'text',
					'label'       => __( 'Aria Label', 'wp-grid-builder' ),
					'dynamicData' => 'card',
				],
				'link_rel'        => [
					'type'    => 'checkbox',
					'label'   => __( 'Rel Attributes', 'wp-grid-builder' ),
					'columns' => 2,
					'options' => [
						[
							'value' => 'nofollow',
							'label' => 'nofollow',
						],
						[
							'value' => 'noreferrer',
							'label' => 'noreferrer',
						],
						[
							'value' => 'noopener',
							'label' => 'noopener',
						],
						[
							'value' => 'external',
							'label' => 'external',
						],
						[
							'value' => 'alternate',
							'label' => 'alternate',
						],
						[
							'value' => 'bookmark',
							'label' => 'bookmark',
						],
						[
							'value' => 'author',
							'label' => 'author',
						],
						[
							'value' => 'license',
							'label' => 'license',
						],
						[
							'value' => 'search',
							'label' => 'search',
						],
						[
							'value' => 'help',
							'label' => 'help',
						],
						[
							'value' => 'next',
							'label' => 'next',
						],
						[
							'value' => 'prev',
							'label' => 'prev',
						],
						[
							'value' => 'tag',
							'label' => 'tag',
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'action_type',
					'compare' => '===',
					'value'   => 'link',
				],
			],
		],
	],
	'condition'   => [
		'relation' => 'OR',
		[
			'field'   => 'blockName',
			'compare' => 'NOT IN',
			'value'   => [
				'wpgb/media-button-block',
				'wpgb/social-share-block',
				'wpgb/the-terms',
			],
		],
		[
			[
				'field'   => 'blockName',
				'compare' => '===',
				'value'   => 'wpgb/the-terms',
			],
			[
				'field'   => 'term_link',
				'compare' => '!=',
				'value'   => 1,
			],
		],
	],
];

$block_settings_conditions = [
	'type'        => 'panel',
	'title'       => __( 'Conditions', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'tip'        => [
			'type'    => 'tip',
			'content' => __( 'Render the block if the following logical conditions are met.', 'wp-grid-builder' ),
		],
		'conditions' => [
			'type'    => 'condition',
			'context' => 'card',
		],
	],
];

$block_settings_advanced = [
	'type'        => 'panel',
	'title'       => __( 'Advanced', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'class' => [
			'type'  => 'text',
			'label' => __( 'Additional CSS class(es)', 'wp-grid-builder' ),
			'help'  => __( 'Separate multiple classes with spaces.', 'wp-grid-builder' ),
		],
		'tag'   => [
			'type'        => 'select',
			'label'       => __( 'HTML Element', 'wp-grid-builder' ),
			'placeholder' => __( 'Auto', 'wp-grid-builder' ),
			'options'     => [
				[
					'value' => 'div',
					'label' => '<div>',
				],
				[
					'value' => 'span',
					'label' => '<span>',
				],
				[
					'value' => 'p',
					'label' => '<p>',
				],
				[
					'value' => 'h2',
					'label' => '<h2>',
				],
				[
					'value' => 'h3',
					'label' => '<h3>',
				],
				[
					'value' => 'h4',
					'label' => '<h4>',
				],
				[
					'value' => 'h5',
					'label' => '<h5>',
				],
				[
					'value' => 'h6',
					'label' => '<h6>',
				],
			],
			'condition'   => [
				[
					'field'   => 'blockName',
					'compare' => 'NOT CONTAINS',
					'value'   => 'wpgb/card-',
				],
			],
		],
	],
];

$block_styles_selectors = [
	'hover_selector'  => [
		'type'   => 'text',
		'hidden' => true,
	],
	'parent_selector' => [
		'type'   => 'text',
		'hidden' => true,
	],
];

$block_styles_layout = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Layout', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'position' => [
			'type'      => 'button',
			'label'     => __( 'Position', 'wp-grid-builder' ),
			'options'   => [
				[
					'value' => '',
					'label' => __( 'Default', 'wp-grid-builder' ),
				],
				[
					'value' => 'relative',
					'label' => _x( 'Relative', 'CSS position', 'wp-grid-builder' ),
				],
				[
					'value' => 'absolute',
					'label' => _x( 'Absolute', 'CSS position', 'wp-grid-builder' ),
				],
			],
			'condition' => [
				[
					'field'   => 'blockName',
					'compare' => 'NOT CONTAINS',
					'value'   => 'wpgb/card-',
				],
			],
		],
		'display'  => [
			'type'        => 'select',
			'label'       => __( 'Display', 'wp-grid-builder' ),
			'placeholder' => __( 'Default', 'wp-grid-builder' ),
			'options'     => [
				[
					'value' => 'none',
					'label' => __( 'None', 'wp-grid-builder' ),
				],
				[
					'value' => 'block',
					'label' => __( 'Block', 'wp-grid-builder' ),
				],
				[
					'value' => 'flex',
					'label' => __( 'Flex', 'wp-grid-builder' ),
				],
				[
					'value' => 'inline-flex',
					'label' => __( 'Inline Flex', 'wp-grid-builder' ),
				],
				[
					'value' => 'inline',
					'label' => __( 'Inline', 'wp-grid-builder' ),
				],
				[
					'value' => 'inline-block',
					'label' => __( 'Inline Block', 'wp-grid-builder' ),
				],
			],
			'condition'   => [
				[
					'field'   => 'blockName',
					'compare' => 'NOT CONTAINS',
					'value'   => 'wpgb/card-',
				],
				[
					'field'   => 'blockName',
					'compare' => '!==',
					'value'   => 'wpgb/group',
				],
				[
					'field'   => 'blockName',
					'compare' => '!==',
					'value'   => 'wpgb/columns',
				],
			],
		],
		'display2' => [
			'type'        => 'select',
			'name'        => 'display',
			'label'       => __( 'Display', 'wp-grid-builder' ),
			'placeholder' => __( 'Default', 'wp-grid-builder' ),
			'options'     => [
				[
					'value' => 'initial',
					'label' => __( 'Initial', 'wp-grid-builder' ),
				],
				[
					'value' => 'none',
					'label' => __( 'None', 'wp-grid-builder' ),
				],
			],
			'condition'   => [
				'relation' => 'OR',
				[
					'field'   => 'blockName',
					'compare' => 'CONTAINS',
					'value'   => 'wpgb/card-',
				],
				[
					'field'   => 'blockName',
					'compare' => '===',
					'value'   => 'wpgb/group',
				],
				[
					'field'   => 'blockName',
					'compare' => '===',
					'value'   => 'wpgb/columns',
				],
			],
		],
		'flex'     => [
			'type'      => 'grid',
			'fields'    => [
				'justify-content' => [
					'type'        => 'select',
					'label'       => __( 'Justification', 'wp-grid-builder' ),
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
					],
				],
				'align-items'     => [
					'type'        => 'select',
					'label'       => __( 'Alignment', 'wp-grid-builder' ),
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
					],
				],
			],
			'condition' => [
				[
					'field'   => 'display',
					'compare' => 'IN',
					'value'   => [ 'flex', 'inline-flex' ],
				],
			],
		],
		'grid'     => [
			'type'      => 'grid',
			'fields'    => [
				'vertical-align' => [
					'type'        => 'select',
					'label'       => __( 'Alignment', 'wp-grid-builder' ),
					'placeholder' => __( 'Default', 'wp-grid-builder' ),
					'options'     => [
						[
							'value' => 'top',
							'label' => __( 'Top', 'wp-grid-builder' ),
						],
						[
							'value' => 'middle',
							'label' => __( 'Middle', 'wp-grid-builder' ),
						],
						[
							'value' => 'bottom',
							'label' => __( 'Bottom', 'wp-grid-builder' ),
						],
					],
				],
				'float'          => [
					'type'        => 'select',
					'label'       => __( 'Float', 'wp-grid-builder' ),
					'placeholder' => __( 'Default', 'wp-grid-builder' ),
					'options'     => [
						[
							'value' => 'left',
							'label' => __( 'Left', 'wp-grid-builder' ),
						],
						[
							'value' => 'right',
							'label' => __( 'Right', 'wp-grid-builder' ),
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'display',
					'compare' => '===',
					'value'   => 'inline-block',
				],
				[
					'field'   => 'blockName',
					'compare' => 'NOT CONTAINS',
					'value'   => 'wpgb/card-',
				],
			],
		],
		'inset'    => [
			'type'      => 'box',
			'label'     => __( 'Offset', 'wp-grid-builder' ),
			'units'     => [
				'custom' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 0.01,
				],
				'px'     => [
					'min'  => -999,
					'max'  => 999,
					'step' => 1,
				],
				'em'     => [
					'min'  => -999,
					'max'  => 100,
					'step' => 0.001,
				],
				'rem'    => [
					'min'  => -999,
					'max'  => 100,
					'step' => 0.001,
				],
				'%'      => [
					'min'  => -999,
					'max'  => 100,
					'step' => 0.001,
				],
			],
			'condition' => [
				[
					'field'   => 'position',
					'compare' => '===',
					'value'   => 'absolute',
				],
				[
					'field'   => 'display',
					'compare' => '!==',
					'value'   => 'none',
				],
				[
					'field'   => 'blockName',
					'compare' => 'NOT CONTAINS',
					'value'   => 'wpgb/card-',
				],
			],
		],
		// Fallbacks.
		'top'      => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'right'    => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'bottom'   => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'left'     => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'clear'    => [
			'type'   => 'text',
			'hidden' => true,
			'units'  => [],
		],
	],
];

$sizing_units = [
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

$block_styles_sizing = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Sizing', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'widths'  => [
			'type'      => 'row',
			'className' => 'wpgb-components-row__icon',
			'fields'    => [
				'width'     => [
					'type'  => 'number',
					'label' => __( 'Width', 'wp-grid-builder' ),
					'icon'  => 'width',
					'units' => $sizing_units,
				],
				'min-width' => [
					'type'  => 'number',
					'label' => (
						'<span class="wpgb-components-base-control__label-light">' .
							__( 'Min', 'wp-grid-builder' ) .
						'</span>' .
						'<span class="wpgb-components-visually-hidden">' .
							__( 'Width', 'wp-grid-builder' ) .
						'</span>'
					),
					'units' => $sizing_units,
				],
				'max-width' => [
					'type'  => 'number',
					'label' => (
						'<span class="wpgb-components-base-control__label-light">' .
							__( 'Max', 'wp-grid-builder' ) .
						'</span>' .
						'<span class="wpgb-components-visually-hidden">' .
							__( 'Width', 'wp-grid-builder' ) .
						'</span>'
					),
					'units' => $sizing_units,
				],
			],
		],
		'heights' => [
			'type'      => 'row',
			'className' => 'wpgb-components-row__icon',
			'fields'    => [
				'height'     => [
					'type'  => 'number',
					'label' => __( 'Height', 'wp-grid-builder' ),
					'icon'  => 'height',
					'units' => $sizing_units,
				],
				'min-height' => [
					'type'  => 'number',
					'label' => (
						'<span class="wpgb-components-base-control__label-light">' .
							__( 'Min', 'wp-grid-builder' ) .
						'</span>' .
						'<span class="wpgb-components-visually-hidden">' .
							__( 'Height', 'wp-grid-builder' ) .
						'</span>'
					),
					'units' => $sizing_units,
				],
				'max-height' => [
					'type'  => 'number',
					'label' => (
						'<span class="wpgb-components-base-control__label-light">' .
							__( 'Max', 'wp-grid-builder' ) .
						'</span>' .
						'<span class="wpgb-components-visually-hidden">' .
							__( 'Height', 'wp-grid-builder' ) .
						'</span>'
					),
					'units' => $sizing_units,
				],
			],
		],
	],
	'condition'   => [
		[
			'field'   => 'blockName',
			'compare' => 'NOT CONTAINS',
			'value'   => 'wpgb/card-',
		],
	],
];

$block_styles_spacing = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Spacing', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => array_merge(
		[
			'padding' => [
				'type'  => 'padding',
				'label' => _x( 'Padding', 'CSS padding', 'wp-grid-builder' ),
			],
			'margin'  => [
				'type'  => 'margin',
				'label' => _x( 'Margin', 'CSS margin', 'wp-grid-builder' ),
			],
		],
		// Fallbacks.
		array_map(
			function() {

				return [
					'type'   => 'number',
					'hidden' => true,
					'units'  => [],
				];
			},
			array_flip( [ 'margin-top', 'margin-right', 'margin-bottom', 'margin-left', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left' ] )
		)
	),
];

$block_styles_borders = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Borders', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => array_merge(
		[
			'tip'           => [
				'type'    => 'tip',
				'content' => sprintf(
					/* translators: %s: Overflow panel url */
					__( 'Radius generally need <a href="%s">overflow</a> property to be set to <b>hidden</b>.', 'wp-grid-builder' ),
					'#wpgb-panel=visibility'
				),
			],
			'borders'       => [
				'type'         => 'border',
				'colorSchemes' => true,
			],
			'border-radius' => [
				'type'  => 'radius',
				'label' => __( 'Radius', 'wp-grid-builder' ),
			],
		],
		// Fallbacks.
		[
			'border-color' => [
				'type'   => 'color',
				'hidden' => true,
			],
			'border-style' => [
				'type'   => 'text',
				'hidden' => true,
			],
		],
		// Fallbacks.
		array_map(
			function() {

				return [
					'type'   => 'number',
					'hidden' => true,
					'units'  => [],
				];
			},
			array_flip(
				[
					'border-top-left-radius',
					'border-top-right-radius',
					'border-bottom-right-radius',
					'border-bottom-left-radius',
					'border-top-width',
					'border-left-width',
					'border-right-width',
					'border-bottom-width',
				]
			)
		)
	),
];

$block_styles_outline = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Outline', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'row1' => [
			'type'    => 'row',
			'columns' => 2,
			'fields'  => [
				'outline-color' => [
					'type'         => 'color',
					'label'        => __( 'Color', 'wp-grid-builder' ),
					'placeholder'  => __( 'Default', 'wp-grid-builder' ),
					'colorSchemes' => true,
				],
				'outline-style' => [
					'type'         => 'select',
					'label'        => __( 'Style', 'wp-grid-builder' ),
					'placeholder'  => __( 'Unset', 'wp-grid-builder' ),
					'isSearchable' => true,
					'options'      => [
						[
							'value' => 'none',
							'label' => __( 'None', 'wp-grid-builder' ),
						],
						[
							'value' => 'solid',
							'label' => __( 'Solid', 'wp-grid-builder' ),
						],
						[
							'value' => 'dotted',
							'label' => __( 'Dotted', 'wp-grid-builder' ),
						],
						[
							'value' => 'dashed',
							'label' => __( 'Dashed', 'wp-grid-builder' ),
						],
						[
							'value' => 'double',
							'label' => __( 'Double', 'wp-grid-builder' ),
						],
						[
							'value' => 'groove',
							'label' => __( 'Groove', 'wp-grid-builder' ),
						],
						[
							'value' => 'ridge',
							'label' => __( 'Ridge', 'wp-grid-builder' ),
						],
						[
							'value' => 'inset',
							'label' => __( 'Inset', 'wp-grid-builder' ),
						],
						[
							'value' => 'outset',
							'label' => __( 'Outset', 'wp-grid-builder' ),
						],
						[
							'value' => 'hidden',
							'label' => __( 'Hidden', 'wp-grid-builder' ),
						],
					],
				],
			],
		],
		'row2' => [
			'type'    => 'row',
			'columns' => 2,
			'fields'  => [
				'outline-width'  => [
					'type'  => 'number',
					'label' => __( 'Width', 'wp-grid-builder' ),
					'units' => [
						'px'  => [
							'min'  => 0,
							'max'  => 999,
							'step' => 1,
						],
						'em'  => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.01,
						],
						'rem' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.01,
						],
						'vw'  => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.01,
						],
						'vh'  => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.01,
						],
					],
				],
				'outline-offset' => [
					'type'  => 'number',
					'label' => __( 'Offset', 'wp-grid-builder' ),
					'units' => [
						'px'  => [
							'min'  => 0,
							'max'  => 999,
							'step' => 1,
						],
						'em'  => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.01,
						],
						'rem' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.01,
						],
						'vw'  => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.01,
						],
						'vh'  => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.01,
						],
					],
				],
			],
		],
	],
];

$block_styles_typography = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Typography', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'typography'      => [
			'type' => 'typography',
		],
		// Fallbacks.
		'google'          => [
			'type'   => 'fonts',
			'hidden' => true,
		],
		'font-family'     => [
			'type'   => 'text',
			'hidden' => true,
		],
		'font-weight'     => [
			'type'   => 'text',
			'hidden' => true,
		],
		'font-style'      => [
			'type'   => 'text',
			'hidden' => true,
		],
		'text-decoration' => [
			'type'   => 'text',
			'hidden' => true,
		],
		'text-transform'  => [
			'type'   => 'text',
			'hidden' => true,
		],
		'text-align'      => [
			'type'   => 'text',
			'hidden' => true,
		],
		'font-size'       => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'line-height'     => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],

		],
		'letter-spacing'  => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
	],
];

$block_styles_colors = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Colors', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'color'        => [
			'type'         => 'color',
			'label'        => __( 'Text', 'wp-grid-builder' ),
			'colorSchemes' => true,
		],
		'background'   => [
			'type'         => 'color',
			'label'        => __( 'Background', 'wp-grid-builder' ),
			'gradient'     => true,
			'colorSchemes' => true,
		],
		'css_variable' => [
			'type'   => 'toggle',
			'hidden' => true,
		],
		// Fallback.
		'color_scheme' => [
			'type'   => 'text',
			'hidden' => true,
		],
	],
];

$block_styles_background = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Background', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'background-url'    => [
			'type'     => 'file',
			'label'    => __( 'Image URL', 'wp-grid-builder' ),
			'mimeType' => [ 'image' ],
		],
		'background-size'   => [
			'type'           => 'button',
			'label'          => __( 'Size', 'wp-grid-builder' ),
			'placeholder'    => __( 'Default', 'wp-grid-builder' ),
			'isDeselectable' => true,
			'options'        => [
				[
					'value' => 'cover',
					'label' => __( 'Cover', 'wp-grid-builder' ),
				],
				[
					'value' => 'contain',
					'label' => __( 'Contain', 'wp-grid-builder' ),
				],
				[
					'value' => 'custom',
					'label' => _x( 'Custom', 'Background size', 'wp-grid-builder' ),
				],
			],
		],
		'row1'              => [
			'type'      => 'row',
			'fields'    => [
				'background-size-width'  => [
					'type'  => 'number',
					'label' => __( 'Width', 'wp-grid-builder' ),
					'units' => [
						'px'  => [
							'min'  => -999,
							'max'  => 999,
							'step' => 1,
						],
						'em'  => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
						'rem' => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
						'%'   => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
					],
				],
				'background-size-height' => [
					'type'  => 'number',
					'label' => __( 'Height', 'wp-grid-builder' ),
					'units' => [
						'px'  => [
							'min'  => -999,
							'max'  => 999,
							'step' => 1,
						],
						'em'  => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
						'rem' => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
						'%'   => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
					],
				],
			],
			'condition' => [
				[
					'field'   => 'background-size',
					'compare' => '===',
					'value'   => 'custom',
				],
			],
		],
		'background-repeat' => [
			'type'         => 'select',
			'label'        => __( 'Repeat', 'wp-grid-builder' ),
			'placeholder'  => __( 'Default', 'wp-grid-builder' ),
			'isSearchable' => true,
			'options'      => [
				[
					'value' => 'unset',
					'label' => _x( 'Unset', 'CSS background repeat', 'wp-grid-builder' ),
				],
				[
					'value' => 'round',
					'label' => _x( 'Round', 'CSS background repeat', 'wp-grid-builder' ),
				],
				[
					'value' => 'space',
					'label' => _x( 'Space', 'CSS background repeat', 'wp-grid-builder' ),
				],
				[
					'value' => 'repeat',
					'label' => _x( 'Repeat', 'CSS background repeat', 'wp-grid-builder' ),
				],
				[
					'value' => 'repeat-x',
					'label' => _x( 'Repeat Y', 'CSS background repeat', 'wp-grid-builder' ),
				],
				[
					'value' => 'repeat-y',
					'label' => _x( 'Repeat X', 'CSS background repeat', 'wp-grid-builder' ),
				],
				[
					'value' => 'no-repeat',
					'label' => _x( 'No Repeat', 'CSS background repeat', 'wp-grid-builder' ),
				],
			],
		],
		'row2'              => [
			'type'   => 'row',
			'fields' => [
				'background-position-x' => [
					'type'  => 'number',
					'label' => __( 'Position X', 'wp-grid-builder' ),
					'units' => [
						'px'  => [
							'min'  => -999,
							'max'  => 999,
							'step' => 1,
						],
						'em'  => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
						'rem' => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
						'%'   => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
					],
				],
				'background-position-y' => [
					'type'  => 'number',
					'label' => __( 'Position Y', 'wp-grid-builder' ),
					'units' => [
						'px'  => [
							'min'  => -999,
							'max'  => 999,
							'step' => 1,
						],
						'em'  => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
						'rem' => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
						'%'   => [
							'min'  => -999,
							'max'  => 999,
							'step' => 0.01,
						],
					],
				],
			],
		],
		// Fallback.
		'background-image'  => [
			'type'   => 'text',
			'hidden' => true,
		],
	],
];

$block_styles_shadows = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Shadows', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'box-shadows'            => [
			'type'  => 'box-shadow',
			'label' => __( 'Box Shadow', 'wp-grid-builder' ),
		],
		'text-shadows'           => [
			'type'  => 'text-shadow',
			'label' => __( 'Text Shadow', 'wp-grid-builder' ),
		],
		// Fallbacks.
		'box-shadow'             => [
			'type'   => 'text',
			'hidden' => true,
		],
		'box-shadow-type'        => [
			'type'   => 'text',
			'hidden' => true,
		],
		'box-shadow-color'       => [
			'type'   => 'color',
			'hidden' => true,
		],
		'box-shadow-horizontal'  => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'box-shadow-vertical'    => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'box-shadow-spread'      => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'box-shadow-blur'        => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'text-shadow'            => [
			'type'   => 'text',
			'hidden' => true,
		],
		'text-shadow-color'      => [
			'type'   => 'color',
			'hidden' => true,
		],
		'text-shadow-horizontal' => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'text-shadow-vertical'   => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'text-shadow-spread'     => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
		'text-shadow-blur'       => [
			'type'   => 'number',
			'hidden' => true,
			'units'  => [],
		],
	],
];

$block_styles_filters = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Filters', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'tip'            => [
			'type'    => 'tip',
			'content' => __( 'Following settings are only applied in preview to prevent conflicts.', 'wp-grid-builder' ),
		],
		'grid'           => [
			'type'    => 'grid',
			'columns' => 2,
			'fields'  => [
				'filter-hue-rotate' => [
					'type'     => 'number',
					'label'    => __( 'Hue Rotate', 'wp-grid-builder' ),
					'icon'     => '<span class="wpgb-css-filter-icon wpgb-hue-rotate-filter"></span>',
					'iconSize' => 18,
					'units'    => [
						'deg' => [
							'min'  => 0,
							'max'  => 360,
							'step' => 1,
						],
					],
				],
				'filter-saturate'   => [
					'type'     => 'number',
					'label'    => __( 'Saturation', 'wp-grid-builder' ),
					'icon'     => '<span class="wpgb-css-filter-icon wpgb-saturate-filter"></span>',
					'iconSize' => 18,
					'units'    => [
						'%' => [
							'min'  => 0,
							'max'  => 200,
							'step' => 1,
						],
					],
				],
				'filter-brightness' => [
					'type'     => 'number',
					'label'    => __( 'Brightness', 'wp-grid-builder' ),
					'icon'     => '<span class="wpgb-css-filter-icon wpgb-brightness-filter"></span>',
					'iconSize' => 18,
					'units'    => [
						'%' => [
							'min'  => 0,
							'max'  => 200,
							'step' => 1,
						],
					],
				],
				'filter-contrast'   => [
					'type'     => 'number',
					'label'    => __( 'Contrast', 'wp-grid-builder' ),
					'icon'     => '<span class="wpgb-css-filter-icon wpgb-contrast-filter"></span>',
					'iconSize' => 18,
					'units'    => [
						'%' => [
							'min'  => 0,
							'max'  => 200,
							'step' => 1,
						],
					],
				],
				'filter-grayscale'  => [
					'type'     => 'number',
					'label'    => __( 'Grayscale', 'wp-grid-builder' ),
					'icon'     => '<span class="wpgb-css-filter-icon wpgb-grayscale-filter"></span>',
					'iconSize' => 18,
					'units'    => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
				],
				'filter-sepia'      => [
					'type'     => 'number',
					'label'    => __( 'Sepia', 'wp-grid-builder' ),
					'icon'     => '<span class="wpgb-css-filter-icon wpgb-sepia-filter"></span>',
					'iconSize' => 18,
					'units'    => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
				],
				'filter-invert'     => [
					'type'     => 'number',
					'label'    => __( 'Invert', 'wp-grid-builder' ),
					'icon'     => '<span class="wpgb-css-filter-icon wpgb-invert-filter"></span>',
					'iconSize' => 18,
					'units'    => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
				],
				'filter-blur'       => [
					'type'     => 'number',
					'label'    => __( 'Blur', 'wp-grid-builder' ),
					'icon'     => '<span class="wpgb-css-filter-icon wpgb-blur-filter"></span>',
					'iconSize' => 18,
					'units'    => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 0.01,
						],
					],
				],
			],
		],
		'mix-blend-mode' => [
			'type'         => 'select',
			'label'        => __( 'Blend Mode', 'wp-grid-builder' ),
			'placeholder'  => __( 'Default', 'wp-grid-builder' ),
			'isSearchable' => true,
			'options'      => [
				[
					'value' => 'unset',
					'label' => __( 'Unset', 'wp-grid-builder' ),
				],
				[
					'value' => 'normal',
					'label' => __( 'Normal', 'wp-grid-builder' ),
				],
				[
					'value' => 'multiply',
					'label' => __( 'Multiply', 'wp-grid-builder' ),
				],
				[
					'value' => 'screen',
					'label' => __( 'Screen', 'wp-grid-builder' ),
				],
				[
					'value' => 'overlay',
					'label' => __( 'Overlay', 'wp-grid-builder' ),
				],
				[
					'value' => 'darken',
					'label' => __( 'Darken', 'wp-grid-builder' ),
				],
				[
					'value' => 'lighten',
					'label' => __( 'Lighten', 'wp-grid-builder' ),
				],
				[
					'value' => 'color-dodge',
					'label' => __( 'Color Dodge', 'wp-grid-builder' ),
				],
				[
					'value' => 'color-burn',
					'label' => __( 'Color Burn', 'wp-grid-builder' ),
				],
				[
					'value' => 'hard-light',
					'label' => __( 'Hard Light', 'wp-grid-builder' ),
				],
				[
					'value' => 'soft-light',
					'label' => __( 'Soft Light', 'wp-grid-builder' ),
				],
				[
					'value' => 'difference',
					'label' => __( 'Difference', 'wp-grid-builder' ),
				],
				[
					'value' => 'exclusion',
					'label' => __( 'Exclusion', 'wp-grid-builder' ),
				],
				[
					'value' => 'hue',
					'label' => __( 'Hue', 'wp-grid-builder' ),
				],
				[
					'value' => 'saturation',
					'label' => __( 'Saturation', 'wp-grid-builder' ),
				],
				[
					'value' => 'color',
					'label' => __( 'Color', 'wp-grid-builder' ),
				],
				[
					'value' => 'luminosity',
					'label' => __( 'Luminosity', 'wp-grid-builder' ),
				],
			],
		],
		// Fallback.
		'filter'         => [
			'hidden' => true,
		],
	],
];

$block_styles_visibility = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Visibility', 'wp-grid-builder' ),
	'className'   => 'wpgb-components-panel__visibility',
	'clearButton' => true,
	'fields'      => [
		'tip'    => [
			'type'    => 'tip',
			'content' => __( 'Following settings are only applied in preview to prevent conflicts.', 'wp-grid-builder' ),
		],
		'grid'   => [
			'type'   => 'grid',
			'fields' => [
				'overflow'   => [
					'type'        => 'select',
					'label'       => __( 'Overflow', 'wp-grid-builder' ),
					'placeholder' => __( 'Default', 'wp-grid-builder' ),
					'options'     => [
						[
							'value' => 'auto',
							'label' => _x( 'Auto', 'CSS overflow', 'wp-grid-builder' ),
						],
						[
							'value' => 'unset',
							'label' => _x( 'Unset', 'CSS overflow', 'wp-grid-builder' ),
						],
						[
							'value' => 'scroll',
							'label' => _x( 'Scroll', 'CSS overflow', 'wp-grid-builder' ),
						],
						[
							'value' => 'visible',
							'label' => _x( 'Visible', 'CSS overflow', 'wp-grid-builder' ),
						],
						[
							'value' => 'hidden',
							'label' => _x( 'Hidden', 'CSS overflow', 'wp-grid-builder' ),
						],
					],
				],
				'z-index'    => [
					'type'  => 'number',
					'label' => __( 'Z-index', 'wp-grid-builder' ),
					'min'   => -100,
					'max'   => 100,
					'step'  => 1,
				],
				'visibility' => [
					'type'        => 'select',
					'label'       => __( 'Visibility', 'wp-grid-builder' ),
					'placeholder' => __( 'Default', 'wp-grid-builder' ),
					'options'     => [
						[
							'value' => 'unset',
							'label' => _x( 'Unset', 'wp-grid-builder' ),
						],
						[
							'value' => 'visible',
							'label' => _x( 'Visible', 'CSS visibility', 'wp-grid-builder' ),
						],
						[
							'value' => 'hidden',
							'label' => _x( 'Hidden', 'CSS visibility', 'wp-grid-builder' ),
						],
					],
				],
				'opacity'    => [
					'type'  => 'number',
					'label' => __( 'Opacity', 'wp-grid-builder' ),
					'min'   => 0,
					'max'   => 1,
					'step'  => 0.01,
				],
			],
		],
		'cursor' => [
			'type'         => 'select',
			'label'        => __( 'Cursor', 'wp-grid-builder' ),
			'isSearchable' => true,
			'options'      => [
				[
					'label'   => __( 'General', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'auto',
							'label' => 'auto',
						],
						[
							'value' => 'default',
							'label' => 'default',
						],
						[
							'value' => 'none',
							'label' => 'none',
						],
					],
				],
				[
					'label'   => __( 'Link & Status', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'pointer',
							'label' => 'pointer',
						],
						[
							'value' => 'context-menu',
							'label' => 'context-menu',
						],
						[
							'value' => 'help',
							'label' => 'help',
						],
						[
							'value' => 'progress',
							'label' => 'progress',
						],
						[
							'value' => 'wait',
							'label' => 'wait',
						],
					],
				],
				[
					'label'   => __( 'Selection', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'cell',
							'label' => 'cell',
						],
						[
							'value' => 'crosshair',
							'label' => 'crosshair',
						],
						[
							'value' => 'text',
							'label' => 'text',
						],
						[
							'value' => 'vertical-text',
							'label' => 'vertical-text',
						],
					],
				],
				[
					'label'   => __( 'Drag & Drop', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'alias',
							'label' => 'alias',
						],
						[
							'value' => 'copy',
							'label' => 'copy',
						],
						[
							'value' => 'move',
							'label' => 'move',
						],
						[
							'value' => 'no-drop',
							'label' => 'no-drop',
						],
						[
							'value' => 'no-allowed',
							'label' => 'no-allowed',
						],
						[
							'value' => 'grab',
							'label' => 'grab',
						],
						[
							'value' => 'grabbing',
							'label' => 'grabbing',
						],
					],
				],
				[
					'label'   => __( 'Zoom', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'zoom-in',
							'label' => 'zoom-in',
						],
						[
							'value' => 'zoom-out',
							'label' => 'zoom-out',
						],
					],
				],
				[
					'label'   => __( 'Resize', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'col-resize',
							'label' => 'col-resize',
						],
						[
							'value' => 'row-resize',
							'label' => 'row-resize',
						],
						[
							'value' => 'n-resize',
							'label' => 'n-resize',
						],
						[
							'value' => 'e-resize',
							'label' => 'e-resize',
						],
						[
							'value' => 's-resize',
							'label' => 's-resize',
						],
						[
							'value' => 'w-resize',
							'label' => 'w-resize',
						],
						[
							'value' => 'ne-resize',
							'label' => 'ne-resize',
						],
						[
							'value' => 'nw-resize',
							'label' => 'nw-resize',
						],
						[
							'value' => 'se-resize',
							'label' => 'se-resize',
						],
						[
							'value' => 'sw-resize',
							'label' => 'sw-resize',
						],
						[
							'value' => 'ew-resize',
							'label' => 'ew-resize',
						],
						[
							'value' => 'ns-resize',
							'label' => 'ns-resize',
						],
						[
							'value' => 'nesw-resize',
							'label' => 'nesw-resize',
						],
						[
							'value' => 'nwse-resize',
							'label' => 'nwse-resize',
						],
						[
							'value' => 'all-scroll',
							'label' => 'all-scroll',
						],
					],
				],
			],
		],
	],
];

$block_styles_custom_css = [
	'type'        => 'panel',
	'group'       => 'block_styles_panels',
	'title'       => __( 'Custom CSS', 'wp-grid-builder' ),
	'clearButton' => true,
	'fields'      => [
		'custom_css'        => [
			'type'        => 'code',
			'label'       => __( 'CSS Declarations', 'wp-grid-builder' ),
			'mode'        => 'css',
			'placeholder' => (
				__( 'CSS declarations only:', 'wp-grid-builder' ) . "\n\n" .
				"margin: 0 auto;\n" .
				"padding: 10px 20px;\n" .
				"color: green;\n"
			),
		],
		'custom_css_before' => [
			'type'        => 'code',
			'label'       => __( '::before CSS Declarations', 'wp-grid-builder' ),
			'mode'        => 'css',
			'placeholder' => (
				__( 'CSS declarations only:', 'wp-grid-builder' ) . "\n\n" .
				"content: \"before\";\n" .
				"position: absolute;\n" .
				"top: 0;\n" .
				"left: 50%;\n"
			),
		],
		'custom_css_after'  => [
			'type'        => 'code',
			'label'       => __( '::after CSS Declarations', 'wp-grid-builder' ),
			'mode'        => 'css',
			'placeholder' => (
				__( 'CSS declarations only:', 'wp-grid-builder' ) . "\n\n" .
				"content: \"after\";\n" .
				"position: absolute;\n" .
				"bottom: 0;\n" .
				"left: 50%;\n"
			),
		],
	],
];

$animations_units = [
	'px'  => [
		'min'  => -999,
		'max'  => 999,
		'step' => 1,
	],
	'em'  => [
		'min'  => -999,
		'max'  => 999,
		'step' => 0.01,
	],
	'rem' => [
		'min'  => -999,
		'max'  => 999,
		'step' => 0.01,
	],
	'%'   => [
		'min'  => -999,
		'max'  => 999,
		'step' => 0.01,
	],
];

$block_animations_type = [
	'type'        => 'panel',
	'clearButton' => true,
	'fields'      => [
		'tip'          => [
			'type'    => 'tip',
			'content' => __( 'Animations allow you to reveal or hide a block on mouse over.', 'wp-grid-builder' ),
		],
		'presets'      => [
			'type'         => 'select',
			'label'        => __( 'Animation', 'wp-grid-builder' ),
			'placeholder'  => _x( 'Unset', 'CSS animation', 'wp-grid-builder' ),
			'isSearchable' => true,
			'options'      => [
				[
					'value' => 'none',
					'label' => _x( 'None', 'CSS animation', 'wp-grid-builder' ),
				],
				[
					'value' => 'fade',
					'label' => __( 'Fade', 'wp-grid-builder' ),
				],
				[
					'value' => 'from-left',
					'label' => __( 'From Left', 'wp-grid-builder' ),
				],
				[
					'value' => 'from-right',
					'label' => __( 'From Right', 'wp-grid-builder' ),
				],
				[
					'value' => 'from-top',
					'label' => __( 'From Top', 'wp-grid-builder' ),
				],
				[
					'value' => 'from-bottom',
					'label' => __( 'From Bottom', 'wp-grid-builder' ),
				],
				[
					'value' => 'zoom-out',
					'label' => __( 'Zoom Out', 'wp-grid-builder' ),
				],
				[
					'value' => 'zoom-in',
					'label' => __( 'Zoom In', 'wp-grid-builder' ),
				],
				[
					'value' => 'fold-up',
					'label' => __( 'Fold Up', 'wp-grid-builder' ),
				],
				[
					'value' => 'flip',
					'label' => __( 'Flip', 'wp-grid-builder' ),
				],
				[
					'value' => 'rotate',
					'label' => __( 'Rotate', 'wp-grid-builder' ),
				],
				[
					'value' => 'bounce',
					'label' => __( 'Bounce', 'wp-grid-builder' ),
				],
				[
					'value' => 'custom',
					'label' => __( 'Custom', 'wp-grid-builder' ),
				],
			],
		],
		'animate-from' => [
			'type'   => 'row',
			'fields' => [
				'selector' => [
					'type'         => 'select',
					'label'        => __( 'Animate From', 'wp-grid-builder' ),
					'isClearable'  => false,
					'defaultValue' => 'parent',
					'options'      => [
						[
							'value' => 'parent',
							'label' => __( 'Parent', 'wp-grid-builder' ),
						],
						[
							'value' => 'card',
							'label' => __( 'Card', 'wp-grid-builder' ),
						],
					],
				],
				'opacity'  => [
					'type'  => 'number',
					'label' => __( 'Start Opacity', 'wp-grid-builder' ),
					'min'   => 0,
					'max'   => 1,
					'step'  => 0.01,
				],
			],
		],
		'reverse'      => [
			'type'  => 'toggle',
			'label' => __( 'Reverse Animation', 'wp-grid-builder' ),
		],
	],
];

$block_animations_transition = [
	'type'        => 'panel',
	'title'       => __( 'CSS Transition', 'wp-grid-builder' ),
	'initialOpen' => true,
	'clearButton' => true,
	'fields'      => [
		'transition-easing'     => [
			'type'         => 'select',
			'label'        => __( 'Easing', 'wp-grid-builder' ),
			'placeholder'  => _x( 'Unset', 'CSS transition easing', 'wp-grid-builder' ),
			'isSearchable' => true,
			'options'      => [
				[
					'value' => 'custom',
					'label' => _x( 'Custom', 'CSS transition easing', 'wp-grid-builder' ),
				],
				[
					'value' => 'ease',
					'label' => 'Ease',
				],
				[
					'value' => 'linear',
					'label' => 'Linear',
				],
				[
					'value' => 'ease-in',
					'label' => 'Ease In',
				],
				[
					'value' => 'ease-out',
					'label' => 'Ease Out',
				],
				[
					'value' => 'ease-in-out',
					'label' => 'Ease In Out',
				],
				[
					'value' => 'cubic-bezier(0.550, 0.055, 0.675, 0.190)',
					'label' => 'Ease In Cubic',
				],
				[
					'value' => 'cubic-bezier(0.215, 0.610, 0.355, 1.000)',
					'label' => 'Ease Out Cubic',
				],
				[
					'value' => 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
					'label' => 'Ease In Out Cubic',
				],
				[
					'value' => 'cubic-bezier(0.600, 0.040, 0.980, 0.335)',
					'label' => 'Ease In Circ',
				],
				[
					'value' => 'cubic-bezier(0.075, 0.820, 0.165, 1.000)',
					'label' => 'Ease Out Circ',
				],
				[
					'value' => 'cubic-bezier(0.785, 0.135, 0.150, 0.860)',
					'label' => 'Ease In Out Circ',
				],
				[
					'value' => 'cubic-bezier(0.950, 0.050, 0.795, 0.035)',
					'label' => 'Ease In Expo',
				],
				[
					'value' => 'cubic-bezier(0.190, 1.000, 0.220, 1.000)',
					'label' => 'Ease Out Expo',
				],
				[
					'value' => 'cubic-bezier(1.000, 0.000, 0.000, 1.000)',
					'label' => 'Ease In Out Expo',
				],
				[
					'value' => 'cubic-bezier(0.550, 0.085, 0.680, 0.530)',
					'label' => 'Ease In Quad',
				],
				[
					'value' => 'cubic-bezier(0.250, 0.460, 0.450, 0.940)',
					'label' => 'Ease Out Quad',
				],
				[
					'value' => 'cubic-bezier(0.455, 0.030, 0.515, 0.955)',
					'label' => 'Ease In Out Quad',
				],
				[
					'value' => 'cubic-bezier(0.895, 0.030, 0.685, 0.220)',
					'label' => 'Ease In Quart',
				],
				[
					'value' => 'cubic-bezier(0.165, 0.840, 0.440, 1.000)',
					'label' => 'Ease Out Quart',
				],
				[
					'value' => 'cubic-bezier(0.770, 0.000, 0.175, 1.000)',
					'label' => 'Ease In Out Quart',
				],
				[
					'value' => 'cubic-bezier(0.755, 0.050, 0.855, 0.060)',
					'label' => 'Ease In Quint',
				],
				[
					'value' => 'cubic-bezier(0.230, 1.000, 0.320, 1.000)',
					'label' => 'Ease Out Quint',
				],
				[
					'value' => 'cubic-bezier(0.860, 0.000, 0.070, 1.000)',
					'label' => 'Ease In Out Quint',
				],
				[
					'value' => 'cubic-bezier(0.470, 0.000, 0.745, 0.715)',
					'label' => 'Ease In Sine',
				],
				[
					'value' => 'cubic-bezier(0.390, 0.575, 0.565, 1.000)',
					'label' => 'Ease Out Sine',
				],
				[
					'value' => 'cubic-bezier(0.445, 0.050, 0.550, 0.950)',
					'label' => 'Ease In Out Sine',
				],
				[
					'value' => 'cubic-bezier(0.600, -0.280, 0.735, 0.045)',
					'label' => 'Ease In Back',
				],
				[
					'value' => 'cubic-bezier(0.175,  0.885, 0.320, 1.275)',
					'label' => 'Ease Out Back',
				],
				[
					'value' => 'cubic-bezier(0.680, -0.550, 0.265, 1.550)',
					'label' => 'Ease In Out Back',
				],
			],
		],
		'cubic-bezier-function' => [
			'type'      => 'text',
			'label'     => __( 'Cubic Bezier', 'wp-grid-builder' ),
			'condition' => [
				[
					'field'   => 'transition-easing',
					'compare' => '===',
					'value'   => 'custom',
				],
			],
		],
		'row'                   => [
			'type'   => 'row',
			'fields' => [
				'transition-duration' => [
					'type'  => 'number',
					'label' => __( 'Duration', 'wp-grid-builder' ),
					'units' => [
						'ms' => [
							'min'  => 0,
							'max'  => 5000,
							'step' => 1,
						],
					],
				],
				'transition-delay'    => [
					'type'  => 'number',
					'label' => __( 'Delay', 'wp-grid-builder' ),
					'units' => [
						'ms' => [
							'min'  => 0,
							'max'  => 5000,
							'step' => 1,
						],
					],
				],
			],
		],
	],
];

$block_animations_transform = [
	'type'        => 'panel',
	'title'       => __( 'CSS Transform', 'wp-grid-builder' ),
	'initialOpen' => true,
	'clearButton' => true,
	'condition'   => [
		[
			'field'   => 'presets',
			'compare' => '!==',
			'value'   => '',
		],
		[
			'field'   => 'presets',
			'compare' => '!==',
			'value'   => 'none',
		],
		[
			'field'   => 'presets',
			'compare' => 'EXISTS',
		],
	],
	'fields'      => [
		'translate'                  => [
			'type'   => 'popover',
			'label'  => __( 'Translate', 'wp-grid-builder' ),
			'fields' => [
				'translateX' => [
					'type'  => 'range',
					'label' => __( 'Translate X', 'wp-grid-builder' ),
					'units' => $animations_units,
				],
				'translateY' => [
					'type'  => 'range',
					'label' => __( 'Translate Y', 'wp-grid-builder' ),
					'units' => $animations_units,
				],
				'translateZ' => [
					'type'  => 'range',
					'label' => __( 'Translate Z', 'wp-grid-builder' ),
					'units' => $animations_units,
				],
			],
		],
		'rotate'                     => [
			'type'   => 'popover',
			'label'  => __( 'Rotate', 'wp-grid-builder' ),
			'fields' => [
				'rotateX' => [
					'type'  => 'range',
					'label' => __( 'Rotate X', 'wp-grid-builder' ),
					'units' => [
						'deg' => [
							'min'  => -360,
							'max'  => 360,
							'step' => 1,
						],
					],
				],
				'rotateY' => [
					'type'  => 'range',
					'label' => __( 'Rotate Y', 'wp-grid-builder' ),
					'units' => [
						'deg' => [
							'min'  => -360,
							'max'  => 360,
							'step' => 1,
						],
					],
				],
				'rotateZ' => [
					'type'  => 'range',
					'label' => __( 'Rotate Z', 'wp-grid-builder' ),
					'units' => [
						'deg' => [
							'min'  => -360,
							'max'  => 360,
							'step' => 1,
						],
					],
				],
			],
		],
		'scale'                      => [
			'type'   => 'popover',
			'label'  => __( 'Scale', 'wp-grid-builder' ),
			'fields' => [
				'scaleX' => [
					'type'  => 'range',
					'label' => __( 'Scale X', 'wp-grid-builder' ),
					'min'   => -10,
					'max'   => 10,
					'step'  => 0.01,
				],
				'scaleY' => [
					'type'  => 'range',
					'label' => __( 'Scale Y', 'wp-grid-builder' ),
					'min'   => -10,
					'max'   => 10,
					'step'  => 0.01,
				],
				'scaleZ' => [
					'type'  => 'range',
					'label' => __( 'Scale Z', 'wp-grid-builder' ),
					'min'   => -10,
					'max'   => 10,
					'step'  => 0.01,
				],
			],
		],
		'skew'                       => [
			'type'   => 'popover',
			'label'  => __( 'Skew', 'wp-grid-builder' ),
			'fields' => [
				'skewX' => [
					'type'  => 'range',
					'label' => __( 'Skew X', 'wp-grid-builder' ),
					'units' => [
						'deg' => [
							'min'  => -360,
							'max'  => 360,
							'step' => 1,
						],
					],
				],
				'skewY' => [
					'type'  => 'range',
					'label' => __( 'Skew Y', 'wp-grid-builder' ),
					'units' => [
						'deg' => [
							'min'  => -360,
							'max'  => 360,
							'step' => 1,
						],
					],
				],
			],
		],
		'origin'                     => [
			'type'   => 'popover',
			'label'  => __( 'Origin', 'wp-grid-builder' ),
			'help'   => sprintf(
				// translators: 1: opening anchor tag 2: opening anchor tag 3: closing anchor tag.
				__( 'Learn more about %1$sCSS transform%3$s and %2$sCSS transform-origin%3$s.', 'wp-grid-builder' ),
				'<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/transform" target="_blank">',
				'<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/transform-origin" target="_blank">',
				'</a>'
			),
			'fields' => [
				'transform-origin-x'    => [
					'type'  => 'range',
					'label' => __( 'Origin X', 'wp-grid-builder' ),
					'units' => $animations_units,
				],
				'transform-origin-y'    => [
					'type'  => 'range',
					'label' => __( 'Origin Y', 'wp-grid-builder' ),
					'units' => $animations_units,
				],
				'transform-origin-z'    => [
					'type'  => 'range',
					'label' => __( 'Origin Z', 'wp-grid-builder' ),
					'units' => $animations_units,
				],
				'transform-perspective' => [
					'type'  => 'range',
					'label' => __( 'Perspective', 'wp-grid-builder' ),
					'units' => [
						'px'  => [
							'min'  => 0,
							'max'  => 9999,
							'step' => 1,
						],
						'em'  => [
							'min'  => 0,
							'max'  => 9999,
							'step' => 0.01,
						],
						'rem' => [
							'min'  => 0,
							'max'  => 9999,
							'step' => 0.01,
						],
					],
				],
			],
		],
		// Fallbacks.
		'transition-timing-function' => [
			'type'   => 'text',
			'hidden' => true,
		],
		'transform-origin'           => [
			'type'   => 'text',
			'hidden' => true,
		],
		'transform'                  => [
			'type'   => 'text',
			'hidden' => true,
		],
	],
];

$css = [
	'css'        => [
		'type'   => 'code',
		'mode'   => 'css',
		'hidden' => true,
	],
	'global_css' => [
		'type'        => 'code',
		'mode'        => 'css',
		'label'       => __( 'Global CSS code', 'wp-grid-builder' ),
		'className'   => 'wpgb-components-code-control__card-css',
		'hideLabel'   => true,
		'autofocus'   => true,
		'placeholder' => (
			"\n.wpgb-block-1 {\n" .
			"    margin: 0 auto;\n" .
			"    padding: 10px 20px;\n" .
			"    color: green; \n" .
			'}'
		),
	],
];

return [
	'card'   => [
		'general'   => $card_general,
		'layout'    => $card_layout,
		'blocks'    => $card_blocks,
		'thumbnail' => $card_thumbnail,
	],
	'block'  => [
		'settings'   => [
			'content'     => $block_settings_content,
			'interaction' => $block_settings_interaction,
			'conditions'  => $block_settings_conditions,
			'advanced'    => $block_settings_advanced,
		],
		'styles'     => [
			'selectors'  => $block_styles_selectors,
			'layout'     => $block_styles_layout,
			'sizing'     => $block_styles_sizing,
			'spacing'    => $block_styles_spacing,
			'borders'    => $block_styles_borders,
			'outline'    => $block_styles_outline,
			'typography' => $block_styles_typography,
			'colors'     => $block_styles_colors,
			'background' => $block_styles_background,
			'shadows'    => $block_styles_shadows,
			'filters'    => $block_styles_filters,
			'visibility' => $block_styles_visibility,
			'custom_css' => $block_styles_custom_css,
		],
		'animations' => [
			'type'       => $block_animations_type,
			'transition' => $block_animations_transition,
			'transform'  => $block_animations_transform,
		],
	],
	'css'    => $css,
	'blocks' => array_filter(
		array_map(
			function( $block ) {

				if ( empty( $block['controls'] ) ) {
					return [];
				}

				return $block['controls'];

			},
			apply_filters( 'wp_grid_builder/blocks', [] )
		)
	),
];
