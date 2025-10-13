<?php
/**
 * Dynamic Data
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle dynamic data
 *
 * @class WP_Grid_Builder\Includes\Dynamic_Data
 * @since 2.0.0
 */
final class Dynamic_Data {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_filter( 'wp_grid_builder/dynamic_data', [ $this, 'render' ], 10, 2 );
		add_filter( 'wp_grid_builder/dynamic_tag/render', [ $this, 'post_tags' ], 0, 4 );
		add_filter( 'wp_grid_builder/dynamic_tag/render', [ $this, 'term_tags' ], 0, 4 );
		add_filter( 'wp_grid_builder/dynamic_tag/render', [ $this, 'user_tags' ], 0, 4 );
		add_filter( 'wp_grid_builder/dynamic_tag/render', [ $this, 'metadata_tags' ], 0, 4 );
		add_filter( 'wp_grid_builder/dynamic_tag/render', [ $this, 'current_user_tags' ], 0, 2 );
		add_filter( 'wp_grid_builder/dynamic_tag/render', [ $this, 'date_tags' ], 0, 3 );

	}

	/**
	 * Render dynamic data
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $content Content to parse.
	 * @param string $context Render context.
	 * @return string
	 */
	public function render( $content, $context = '' ) {

		if (
			empty( $content ) ||
			! is_scalar( $content ) ||
			strpos( $content, '{' ) === false
		) {
			return $content;
		}

		preg_match_all( '/{{([\p{L}\w\s.\/:@|,\'()-]+)}}/ui', $content, $matches );

		if ( empty( $matches[1] ) ) {
			return $content;
		}

		foreach ( $matches[1] as $index => $tag ) {

			$args = explode( ':', $tag );
			$tag  = trim( strtolower( array_shift( $args ) ) );

			$replace = apply_filters( 'wp_grid_builder/dynamic_tag/render', $matches[0][ $index ], $tag, $args, $context );

			if ( is_scalar( $replace ) ) {
				$content = str_replace( $matches[0][ $index ], $replace, $content );
			}
		}

		return $content;

	}

	/**
	 * Render post tags
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $content Rendered tag.
	 * @param string $tag     Tag name to render.
	 * @param array  $args    Tag arguments.
	 * @param string $context Data context.
	 * @return string
	 */
	public function post_tags( $content, $tag, $args, $context ) {

		if ( 'card' !== $context ) {
			return $content;
		}

		if (
			false === strpos( $tag, 'post_' ) &&
			false === strpos( $tag, 'post.' )
		) {
			return $content;
		}

		// Support for old tag names.
		$tag = str_replace( 'post.', 'post_', $tag );

		switch ( $tag ) {
			case 'post_id':
				return wpgb_get_the_id();
			case 'post_name':
				return wpgb_get_the_name();
			case 'post_title':
				return wpgb_get_the_title();
			case 'post_author':
				return wpgb_get_the_author();
			case 'post_permalink':
				return wpgb_get_the_permalink();
			case 'post_date':
				return wpgb_get_the_date( ...$args );
			case 'post_modified_date':
				return wpgb_get_the_modified_date( ...$args );
			case 'post_content':
				return wpgb_get_the_content();
			case 'post_excerpt':
				return wpgb_get_the_excerpt( ...$args );
			case 'post_avatar_url':
				return wpgb_get_avatar_url();
			case 'post_status':
				return wpgb_get_post_status_label();
			case 'post_type':
				return wpgb_get_post_type_label();
			case 'post_format':
				return get_post_format_string( wpgb_get_post_format() );
			case 'post_comments_number':
				return wpgb_get_comments_number();
			case 'post_metadata':
				return wpgb_format_metadata( [], wpgb_get_metadata( end( $args ) ) );
		}

		return $content;

	}

	/**
	 * Render term tags
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $content Rendered tag.
	 * @param string $tag     Tag name to render.
	 * @param array  $args    Tag arguments.
	 * @param string $context Data context.
	 * @return string
	 */
	public function term_tags( $content, $tag, $args, $context ) {

		if ( 'card' !== $context ) {
			return $content;
		}

		if (
			0 !== strpos( $tag, 'term_' ) &&
			0 !== strpos( $tag, 'term.' )
		) {
			return $content;
		}

		// Support for old tag names.
		$tag = str_replace( 'term.', 'term_', $tag );

		switch ( $tag ) {
			case 'term_id':
				return wpgb_get_the_term_id();
			case 'term_slug':
				return wpgb_get_term_slug();
			case 'term_name':
				return wpgb_get_term_name();
			case 'term_taxonomy':
				return wpgb_get_term_taxonomy();
			case 'term_parent':
				return wpgb_get_term_parent();
			case 'term_description':
				return wpgb_get_term_description( ...$args );
			case 'term_count':
				return wpgb_get_term_count();
			case 'term_metadata':
				return wpgb_format_metadata( [], wpgb_get_metadata( end( $args ) ) );
		}

		return $content;

	}

	/**
	 * Render user tags
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $content Rendered tag.
	 * @param string $tag     Tag name to render.
	 * @param array  $args    Tag arguments.
	 * @param string $context Data context.
	 * @return string
	 */
	public function user_tags( $content, $tag, $args, $context ) {

		if ( 'card' !== $context ) {
			return $content;
		}

		if (
			0 !== strpos( $tag, 'user_' ) &&
			0 !== strpos( $tag, 'user.' )
		) {
			return $content;
		}

		// Support for old tag names.
		$tag = str_replace( 'user.', 'user_', $tag );

		switch ( $tag ) {
			case 'user_id':
				return wpgb_get_the_user_id();
			case 'user_login':
				return wpgb_get_user_login();
			case 'user_display_name':
				return wpgb_get_user_display_name();
			case 'user_first_name':
				return wpgb_get_user_first_name();
			case 'user_last_name':
				return wpgb_get_user_last_name();
			case 'user_nickname':
				return wpgb_get_user_nickname();
			case 'user_description':
				return wpgb_get_user_description( ...$args );
			case 'user_email':
				return wpgb_get_user_email();
			case 'user_url':
				return wpgb_get_user_url();
			case 'user_roles':
				return wpgb_get_user_roles_translated();
			case 'user_post_count':
				return wpgb_get_user_post_count();
			case 'user_metadata':
				return wpgb_format_metadata( [], wpgb_get_metadata( end( $args ) ) );
		}

		return $content;

	}

	/**
	 * Backward compatibility with old dynamic tags
	 * e.g. {{post.metadata.custom_fieldname}}
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $content Rendered tag.
	 * @param string $tag     Tag name to render.
	 * @param array  $args    Tag arguments.
	 * @param string $context Data context.
	 * @return mixed
	 */
	public function metadata_tags( $content, $tag, $args, $context ) {

		if ( 'card' !== $context ) {
			return $content;
		}

		if (
			0 !== strpos( $tag, 'post.metadata.' ) &&
			0 !== strpos( $tag, 'term.metadata.' ) &&
			0 !== strpos( $tag, 'user.metadata.' )
		) {
			return $content;
		}

		$args = explode( '.', $tag );
		$key  = end( $args );

		if ( empty( $key ) ) {
			return '';
		}

		$meta = wpgb_get_metadata( trim( $key ) );

		if ( null === $meta ) {
			return '';
		}

		return $meta;

	}

	/**
	 * Render current user tags
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $content Rendered tag.
	 * @param string $tag     Tag name to render.
	 * @return string
	 */
	public function current_user_tags( $content, $tag ) {

		if ( 0 !== strpos( $tag, 'current_user_' ) ) {
			return $content;
		}

		$current_user = wp_get_current_user();

		if ( ! isset( $current_user->ID ) ) {
			return '';
		}

		switch ( $tag ) {
			case 'current_user_id':
				return $current_user->ID;
			case 'current_user_login':
				return $current_user->user_login;
			case 'current_user_display_name':
				return $current_user->display_name;
			case 'current_user_first_name':
				return $current_user->user_firstname;
			case 'current_user_last_name':
				return $current_user->user_lastname;
			case 'current_user_email':
				return $current_user->user_email;
			case 'current_user_url':
				return $current_user->user_url;
			case 'current_user_roles':
				return implode( ', ', (array) $current_user->roles );
		}

		return $content;

	}

	/**
	 * Render date tags
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $content Rendered tag.
	 * @param string $tag     Tag name to render.
	 * @param array  $args    Tag arguments.
	 * @return string
	 */
	public function date_tags( $content, $tag, $args ) {

		if ( false === strpos( $tag, 'date_' ) ) {
			return $content;
		}

		$format = get_option( 'date_format' );

		if ( ! empty( $args[0] ) ) {
			$format = trim( implode( ':', $args ) );
		}

		if ( 'timestamp' === strtolower( $format ) ) {
			$format = 'U';
		}

		switch ( $tag ) {
			case 'date_current_utc':
				return date_i18n( $format, false, true );
			case 'date_current_local':
				return date_i18n( $format );
		}

		return $content;

	}
}
