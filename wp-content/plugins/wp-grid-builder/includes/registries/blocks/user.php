<?php
/**
 * User blocks
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$the_user_id = [
	'title'       => __( 'User ID', 'wp-grid-builder' ),
	'description' => __( 'Displays the ID of the user.', 'wp-grid-builder' ),
	'category'    => 'user_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'user_field',
				'post_field'  => 'the_user_id',
				'idle_scheme' => 'scheme-2',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-2',
				],
			],
		],
	],
];

$the_user_login = [
	'title'       => __( 'Username', 'wp-grid-builder' ),
	'description' => __( 'Displays the username of the user.', 'wp-grid-builder' ),
	'category'    => 'user_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'user_field',
				'user_field'  => 'the_user_login',
				'idle_scheme' => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_user_display_name = [
	'title'       => __( 'User Display Name', 'wp-grid-builder' ),
	'description' => __( 'Displays the display name of the user.', 'wp-grid-builder' ),
	'category'    => 'user_blocks',
	'tagName'     => 'h3',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'       => 'user_field',
				'user_field'   => 'the_user_display_name',
				'idle_scheme'  => 'scheme-1',
				'hover_scheme' => 'accent-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle'  => [
					'font-size'      => '1.625em',
					'line-height'    => '1.4',
					'font-weight'    => '700',
					'color_scheme'   => 'scheme-1',
					'margin-top'     => 0,
					'margin-right'   => 0,
					'margin-bottom'  => 0,
					'margin-left'    => 0,
					'padding-left'   => 0,
					'padding-bottom' => 0,
					'padding-right'  => 0,
					'padding-top'    => 0,
				],
				'hover' => [
					'hover_selector' => 'itself',
					'color_scheme'   => 'accent-1',
				],
			],
		],
	],
];

$the_user_first_name = [
	'title'       => __( 'User First Name', 'wp-grid-builder' ),
	'description' => __( 'Displays the first name of the user.', 'wp-grid-builder' ),
	'category'    => 'user_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'user_field',
				'user_field'  => 'the_user_first_name',
				'idle_scheme' => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_user_last_name = [
	'title'       => __( 'User Last Name', 'wp-grid-builder' ),
	'description' => __( 'Displays the last name of the user.', 'wp-grid-builder' ),
	'category'    => 'user_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'user_field',
				'user_field'  => 'the_user_last_name',
				'idle_scheme' => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_user_nickname = [
	'title'       => __( 'User Nickname', 'wp-grid-builder' ),
	'description' => __( 'Displays the nickname of the user.', 'wp-grid-builder' ),
	'category'    => 'user_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'user_field',
				'user_field'  => 'the_user_nickname',
				'idle_scheme' => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_user_description = [
	'title'       => __( 'User Biography', 'wp-grid-builder' ),
	'description' => __( 'Displays the biography of the user.', 'wp-grid-builder' ),
	'category'    => 'user_blocks',
	'tagName'     => 'p',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'         => 'user_field',
				'user_field'     => 'the_user_description',
				'idle_scheme'    => 'scheme-2',
				'excerpt_length' => 35,
				'excerpt_suffix' => '...',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1.125em',
					'line-height'  => '1.6',
					'font-weight'  => '300',
					'color_scheme' => 'scheme-2',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'excerpt_suffix' => [
					'type'  => 'text',
					'label' => __( 'Excerpt Suffix', 'wp-grid-builder' ),
				],
				'excerpt_length' => [
					'type'  => 'range',
					'label' => __( 'Excerpt Length', 'wp-grid-builder' ),
					'help'  => __( 'Set the length to -1 to display the full excerpt.', 'wp-grid-builder' ),
					'min'   => -1,
					'max'   => 999,
					'step'  => 1,
				],
			],
		],
	],
];

$the_user_email = [
	'title'       => __( 'User Email', 'wp-grid-builder' ),
	'description' => __( 'Displays the email address of the user.', 'wp-grid-builder' ),
	'category'    => 'user_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'user_field',
				'user_field'  => 'the_user_email',
				'idle_scheme' => 'accent-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'       => '0.8125em',
					'line-height'     => '1.4',
					'font-weight'     => '700',
					'color_scheme'    => 'accent-1',
					'text-decoration' => 'underline',
					'font-style'      => 'italic',
				],
			],
		],
	],
];

$the_user_url = [
	'title'       => __( 'User Website', 'wp-grid-builder' ),
	'description' => __( 'Displays the website Url of the user.', 'wp-grid-builder' ),
	'category'    => 'user_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'       => 'user_field',
				'user_field'   => 'the_user_url',
				'idle_scheme'  => 'accent-1',
				'website_text' => '',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'       => '0.8125em',
					'line-height'     => '1.4',
					'font-weight'     => '700',
					'color_scheme'    => 'accent-1',
					'text-decoration' => 'underline',
					'font-style'      => 'italic',
				],
			],
		],
	],
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'website_text' => [
					'type'  => 'text',
					'label' => __( 'Website Text', 'wp-grid-builder' ),
				],
			],
		],
	],
];

$the_user_roles = [
	'title'       => __( 'User Roles', 'wp-grid-builder' ),
	'description' => __( 'Displays the roles of the user.', 'wp-grid-builder' ),
	'category'    => 'user_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'      => 'user_field',
				'user_field'  => 'the_user_roles',
				'idle_scheme' => 'scheme-1',
			],
		],
		'style'   => [
			'type'    => 'object',
			'default' => [
				'idle' => [
					'font-size'    => '1em',
					'line-height'  => '1.4',
					'font-weight'  => '600',
					'color_scheme' => 'scheme-1',
				],
			],
		],
	],
];

$the_user_post_count = [
	'title'       => __( 'User Post Count', 'wp-grid-builder' ),
	'description' => __( 'Displays the number of matching user posts.', 'wp-grid-builder' ),
	'category'    => 'user_blocks',
	'tagName'     => 'div',
	'attributes'  => [
		'content' => [
			'type'    => 'object',
			'default' => [
				'source'       => 'user_field',
				'user_field'   => 'the_user_post_count',
				'idle_scheme'  => 'scheme-2',
				'count_format' => 'text',
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
	'controls'    => [
		'panel' => [
			'type'   => 'panel',
			'fields' => [
				'count_format' => [
					'type'    => 'button',
					'label'   => __( 'Format Type', 'wp-grid-builder' ),
					'options' => [
						[
							'value' => 'text',
							'label' => __( 'Text', 'wp-grid-builder' ),
						],
						[
							'value' => 'number',
							'label' => __( 'Number', 'wp-grid-builder' ),
						],
					],
				],
			],
		],
	],
];

return [
	'the_user_id'           => $the_user_id,
	'the_user_login'        => $the_user_login,
	'the_user_display_name' => $the_user_display_name,
	'the_user_first_name'   => $the_user_first_name,
	'the_user_last_name'    => $the_user_last_name,
	'the_user_nickname'     => $the_user_nickname,
	'the_user_description'  => $the_user_description,
	'the_user_email'        => $the_user_email,
	'the_user_url'          => $the_user_url,
	'the_user_roles'        => $the_user_roles,
	'the_user_post_count'   => $the_user_post_count,
];
