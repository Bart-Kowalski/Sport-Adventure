<?php
/**
 * Product blocks
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$the_full_price = [
	'title'       => __( 'Full Price', 'wp-grid-builder' ),
	'description' => __( 'Displays regular and sale prices of the product.', 'wp-grid-builder' ),
	'category'    => 'product_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'        => 'product_field',
				'product_field' => 'the_full_price',
				'idle_scheme'   => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1.625em',
					'line-height'  => '1.4',
					'font-weight'  => '400',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_price = [
	'title'       => __( 'Active Price', 'wp-grid-builder' ),
	'description' => __( 'Displays the active price of the product.', 'wp-grid-builder' ),
	'category'    => 'product_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'        => 'product_field',
				'product_field' => 'the_price',
				'idle_scheme'   => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1.625em',
					'line-height'  => '1.4',
					'font-weight'  => '400',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_regular_price = [
	'title'       => __( 'Regular Price', 'wp-grid-builder' ),
	'description' => __( 'Displays the regular price of the product.', 'wp-grid-builder' ),
	'category'    => 'product_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'        => 'product_field',
				'product_field' => 'the_regular_price',
				'idle_scheme'   => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1.625em',
					'line-height'  => '1.4',
					'font-weight'  => '400',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_sale_price = [
	'title'       => __( 'Sale Price', 'wp-grid-builder' ),
	'description' => __( 'Displays the sale price of the product.', 'wp-grid-builder' ),
	'category'    => 'product_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'        => 'product_field',
				'product_field' => 'the_sale_price',
				'idle_scheme'   => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1.625em',
					'line-height'  => '1.4',
					'font-weight'  => '400',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_star_rating = [
	'title'       => __( 'Rating Stars', 'wp-grid-builder' ),
	'description' => __( 'Displays the rating stars (reviews) of the product.', 'wp-grid-builder' ),
	'category'    => 'product_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'        => 'product_field',
				'product_field' => 'the_star_rating',
				'stroke-width'  => 1,
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '2em',
					'line-height'  => '1.4',
					'color'        => '#f7ab13',
					'font-weight'  => '700',
					'height'       => '0.85em',
					'stroke-width' => 1,
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'stroke-width' => [
					'type'  => 'range',
					'label' => __( 'Icon Stroke Width', 'wp-grid-builder' ),
					'min'   => 0.1,
					'max'   => 10,
					'step'  => 0.01,
				],
			],
		],
	],
];

$the_text_rating = [
	'title'       => __( 'Rating Number', 'wp-grid-builder' ),
	'description' => __( 'Displays the average rating number of the product.', 'wp-grid-builder' ),
	'category'    => 'product_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'        => 'product_field',
				'product_field' => 'the_text_rating',
				'idle_scheme'   => 'scheme-2',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '0.8125em',
					'line-height'  => '1.4',
					'font-weight'  => '400',
					'color_scheme' => 'scheme-2',
					'font-style'   => 'italic',
				],
			],
		],
	],
];

$the_on_sale_badge = [
	'title'       => __( 'On Sale Badge', 'wp-grid-builder' ),
	'description' => __( 'Displays a badge if the product is on sale.', 'wp-grid-builder' ),
	'category'    => 'product_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'        => 'product_field',
				'product_field' => 'the_on_sale_badge',
				'idle_scheme'   => 'scheme-1',
				'badge_type'    => 'text',
				'badge_icon'    => 'wpgb/business/discount-2',
				'badge_label'   => 'Sale!',
				'stroke-width'  => 1,
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'                  => '0.8em',
					'line-height'                => '1.4',
					'font-weight'                => '600',
					'color_scheme'               => 'scheme-1',
					'display'                    => 'inline-block',
					'padding-top'                => '0.35em',
					'padding-bottom'             => '0.35em',
					'padding-right'              => '1em',
					'padding-left'               => '1em',
					'border-top-left-radius'     => '0.3em',
					'border-top-right-radius'    => '0.3em',
					'border-bottom-right-radius' => '0.3em',
					'border-bottom-left-radius'  => '0.3em',
					'background'                 => '#f85464',
					'color'                      => '#ffffff',
					'text-transform'             => 'uppercase',
					'stroke-width'               => 1,
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'badge_type'   => [
					'type'    => 'button',
					'label'   => __( 'Badge Type', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'text',
							'label' => __( 'Text', 'wp-grid-builder' ),
						],
						[
							'value' => 'icon',
							'label' => __( 'Icon', 'wp-grid-builder' ),
						],
					],
				],
				'badge_label'  => [
					'type'        => 'text',
					'label'       => __( 'Badge Label', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a label', 'wp-grid-builder' ),
					'condition'   => [
						[
							'field'   => 'badge_type',
							'compare' => '===',
							'value'   => 'text',
						],
					],
				],
				'badge_icon'   => [
					'type'      => 'icon',
					'label'     => __( 'SVG Icon', 'wp-grid-builder' ),
					'condition' => [
						[
							'field'   => 'badge_type',
							'compare' => '===',
							'value'   => 'icon',
						],
					],
				],
				'stroke-width' => [
					'type'      => 'range',
					'label'     => __( 'Icon Stroke Width', 'wp-grid-builder' ),
					'min'       => 0.1,
					'max'       => 10,
					'step'      => 0.01,
					'condition' => [
						[
							'field'   => 'badge_type',
							'compare' => '===',
							'value'   => 'icon',
						],
					],
				],
			],
		],
	],
];

$the_in_stock_badge = [
	'title'       => __( 'In Stock Badge', 'wp-grid-builder' ),
	'description' => __( 'Displays a badge if the product is in stock.', 'wp-grid-builder' ),
	'category'    => 'product_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'        => 'product_field',
				'product_field' => 'the_in_stock_badge',
				'idle_scheme'   => 'scheme-1',
				'badge_label'   => '',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'                  => '0.8em',
					'line-height'                => '1.4',
					'font-weight'                => '600',
					'color_scheme'               => 'scheme-1',
					'display'                    => 'inline-block',
					'padding-top'                => '0.35em',
					'padding-bottom'             => '0.35em',
					'padding-right'              => '1em',
					'padding-left'               => '1em',
					'border-top-left-radius'     => '0.3em',
					'border-top-right-radius'    => '0.3em',
					'border-bottom-right-radius' => '0.3em',
					'border-bottom-left-radius'  => '0.3em',
					'background'                 => '#3ecf8e',
					'color'                      => '#ffffff',
					'text-transform'             => 'uppercase',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'badge_label' => [
					'type'        => 'text',
					'label'       => __( 'Badge Label', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a label', 'wp-grid-builder' ),
				],
			],
		],
	],
];

$the_out_of_stock_badge = [
	'title'       => __( 'Out of Stock Badge', 'wp-grid-builder' ),
	'description' => __( 'Displays a badge if the product is out of stock.', 'wp-grid-builder' ),
	'category'    => 'product_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'        => 'product_field',
				'product_field' => 'the_out_of_stock_badge',
				'idle_scheme'   => 'scheme-1',
				'badge_label'   => '',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'                  => '0.8em',
					'line-height'                => '1.4',
					'font-weight'                => '600',
					'color_scheme'               => 'scheme-1',
					'display'                    => 'inline-block',
					'padding-top'                => '0.35em',
					'padding-bottom'             => '0.35em',
					'padding-right'              => '1em',
					'padding-left'               => '1em',
					'border-top-left-radius'     => '0.3em',
					'border-top-right-radius'    => '0.3em',
					'border-bottom-right-radius' => '0.3em',
					'border-bottom-left-radius'  => '0.3em',
					'background'                 => '#f85464',
					'color'                      => '#ffffff',
					'text-transform'             => 'uppercase',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'badge_label' => [
					'type'        => 'text',
					'label'       => __( 'Badge Label', 'wp-grid-builder' ),
					'placeholder' => __( 'Enter a label', 'wp-grid-builder' ),
				],
			],
		],
	],
];

$the_cart_button = [
	'title'       => __( 'Cart Button', 'wp-grid-builder' ),
	'description' => __( "Displays the product's cart button.", 'wp-grid-builder' ),
	'category'    => 'product_blocks',
	'tagName'     => 'a',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'        => 'product_field',
				'product_field' => 'the_cart_button',
				'idle_scheme'   => 'scheme-1',
				'hover_scheme'  => 'accent-1',
			],
		],
		'style'   => [
			'idle'  => [
				'font-size'                  => '1em',
				'line-height'                => '1.4',
				'font-weight'                => '700',
				'color_scheme'               => 'scheme-1',
				'text-align'                 => 'center',
				'padding-top'                => '0.5em',
				'padding-right'              => '0.5em',
				'padding-bottom'             => '0.5em',
				'padding-left'               => '0.5em',
				'border-top-width'           => '0.125em',
				'border-style'               => 'solid',
				'border-right-width'         => '0.125em',
				'border-bottom-width'        => '0.125em',
				'border-left-width'          => '0.125em',
				'border-top-left-radius'     => '0.3em',
				'border-top-right-radius'    => '0.3em',
				'border-bottom-right-radius' => '0.3em',
				'border-bottom-left-radius'  => '0.3em',
				'text-transform'             => 'uppercase',
				'display'                    => 'block',
				'margin-top'                 => 0,
				'margin-right'               => '5em',
				'margin-left'                => '5em',
				'margin-bottom'              => 0,
			],
			'hover' => [
				'hover_selector' => 'itself',
				'color_scheme'   => 'accent-1',
			],
		],
	],
];

return [
	'the_full_price'         => $the_full_price,
	'the_price'              => $the_price,
	'the_regular_price'      => $the_regular_price,
	'the_sale_price'         => $the_sale_price,
	'the_star_rating'        => $the_star_rating,
	'the_text_rating'        => $the_text_rating,
	'the_on_sale_badge'      => $the_on_sale_badge,
	'the_in_stock_badge'     => $the_in_stock_badge,
	'the_out_of_stock_badge' => $the_out_of_stock_badge,
	'the_cart_button'        => $the_cart_button,
];
