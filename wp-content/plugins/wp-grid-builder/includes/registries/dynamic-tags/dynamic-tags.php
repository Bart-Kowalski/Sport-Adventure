<?php
/**
 * Dynamic tags
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
	'post'         => [
		'label'   => __( 'Post Data', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card' ],
				'value'   => '{{post_id}}',
				'label'   => __( 'Post ID', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_name}}',
				'label'   => __( 'Post Name', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_title}}',
				'label'   => __( 'Post Title', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_author}}',
				'label'   => __( 'Post Author', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_permalink}}',
				'label'   => __( 'Post Permalink', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_date}}',
				'label'   => __( 'Post Date', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_modified_date}}',
				'label'   => __( 'Post Modified Date', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_content}}',
				'label'   => __( 'Post Content', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_excerpt}}',
				'label'   => __( 'Post Excerpt', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_status}}',
				'label'   => __( 'Post Status', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_type}}',
				'label'   => __( 'Post Type', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_format}}',
				'label'   => __( 'Post Format', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_comments_number}}',
				'label'   => __( 'Post Comments Count', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{post_metadata}}',
				'label'   => __( 'Post Custom Field', 'wp-grid-builder' ),
			],
		],
	],
	'term'         => [
		'label'   => __( 'Term Data', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card' ],
				'value'   => '{{term_id}}',
				'label'   => __( 'Term ID', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{term_slug}}',
				'label'   => __( 'Term Slug', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{term_name}}',
				'label'   => __( 'Term Name', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{term_taxonomy}}',
				'label'   => __( 'Term Taxonomy', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{term_parent}}',
				'label'   => __( 'Term Parent', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{term_description}}',
				'label'   => __( 'Term Description', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{term_count}}',
				'label'   => __( 'Term Posts Count', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{term_metadata}}',
				'label'   => __( 'Term Custom Field', 'wp-grid-builder' ),
			],
		],
	],
	'user'         => [
		'label'   => __( 'User Data', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card' ],
				'value'   => '{{user_id}}',
				'label'   => __( 'User ID', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{user_login}}',
				'label'   => __( 'Username', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{user_display_name}}',
				'label'   => __( 'User Display Name', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{user_first_name}}',
				'label'   => __( 'User First Name', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{user_last_name}}',
				'label'   => __( 'User Last Name', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{user_nickname}}',
				'label'   => __( 'User Nickname', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{user_description}}',
				'label'   => __( 'User Biography', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{user_email}}',
				'label'   => __( 'User Email', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{user_url}}',
				'label'   => __( 'User Website', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{user_roles}}',
				'label'   => __( 'User Roles', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{user_post_count}}',
				'label'   => __( 'User Posts Count', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card' ],
				'value'   => '{{user_metadata}}',
				'label'   => __( 'User Custom Field', 'wp-grid-builder' ),
			],
		],
	],
	'current_user' => [
		'label'   => __( 'Current User', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card', 'facet' ],
				'value'   => '{{current_user_id}}',
				'label'   => __( 'Current User ID', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => '{{current_user_login}}',
				'label'   => __( 'Current Username', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => '{{current_user_display_name}}',
				'label'   => __( 'Current User Display Name', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => '{{current_user_first_name}}',
				'label'   => __( 'Current User First Name', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => '{{current_user_last_name}}',
				'label'   => __( 'Current User Last Name', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => '{{current_user_email}}',
				'label'   => __( 'Current User Email', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => '{{current_user_url}}',
				'label'   => __( 'Current User Website', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => '{{current_user_roles}}',
				'label'   => __( 'Current User Roles', 'wp-grid-builder' ),
			],
		],
	],
	'date'         => [
		'label'   => __( 'Date', 'wp-grid-builder' ),
		'options' => [
			[
				'context' => [ 'card', 'facet' ],
				'value'   => '{{date_current_utc}}',
				'label'   => __( 'Current Date (UTC)', 'wp-grid-builder' ),
			],
			[
				'context' => [ 'card', 'facet' ],
				'value'   => '{{date_current_local}}',
				'label'   => __( 'Current Date (Local)', 'wp-grid-builder' ),
			],
		],
	],
];
