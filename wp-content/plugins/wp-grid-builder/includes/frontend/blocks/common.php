<?php
/**
 * Common blocks
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Paragraph block
 *
 * @since 2.0.0
 *
 * @param array $block  Holds block args.
 * @param array $action Holds action args.
 */
function wpgb_paragraph_block( $block = [], $action = [] ) {

	if ( empty( $block['text'] ) ) {
		return;
	}

	wpgb_block_start( $block, $action );
		echo wp_kses_post(
			do_shortcode(
				apply_filters( 'wp_grid_builder/dynamic_data', $block['text'], 'card' )
			)
		);
	wpgb_block_end( $block, $action );

}

/**
 * Display SVG icon block
 *
 * @since 1.0.0
 *
 * @param array $block  Holds block args.
 * @param array $action Holds action args.
 */
function wpgb_svg_icon_block( $block = [], $action = [] ) {

	if ( ! isset( $block['svg_name'] ) || ! $block['svg_name'] ) {
		$block['svg_name'] = 'wpgb/animals/bug';
	}

	wpgb_block_start( $block, $action );
		wpgb_svg_icon( $block['svg_name'] );
	wpgb_block_end( $block, $action );

}

/**
 * Display media button block
 *
 * @since 1.0.0
 *
 * @param array $block Holds block args.
 */
function wpgb_media_button_block( $block = [] ) {

	if ( ! wpgb_has_post_media() ) {
		return;
	}

	$format = wpgb_get_media_format();
	$block  = wp_parse_args(
		$block,
		[
			'lightbox_icon' => 'wpgb/user-interface/add',
			'play_icon'     => 'wpgb/multimedia/button-play-2',
		]
	);

	if ( 'audio' === $format || 'video' === $format ) {
		$icon = $block['play_icon'];
	} else {
		$icon = $block['lightbox_icon'];
	}

	$block['name'] .= ' wpgb-card-media-button';

	wpgb_block_start( $block, null );
		wpgb_svg_icon( $icon );
	wpgb_block_end( $block, null );

}

/**
 * Display social share icon
 *
 * @since 1.0.0
 *
 * @param array $block Holds block args.
 */
function wpgb_social_share_block( $block = [] ) {

	if ( empty( $block['social_network'] ) ) {
		$block['social_network'] = 'facebook';
	}

	$type = $block['social_network'];

	$media = [
		'blogger'     => 'https://www.blogger.com/blog_this.pyra?t&u=%1$s&n=%2$s',
		'buffer'      => 'https://bufferapp.com/add?url=%1$s&title=%2$s',
		'email'       => 'mailto:?subject=%2$s&body=%3$s %1$s',
		'evernote'    => 'https://www.evernote.com/clip.action?url=%1$s&title=%2$s',
		'google-plus' => 'https://plus.google.com/share?url=%1$s',
		'facebook'    => 'https://www.facebook.com/sharer.php?u=%1$s&t=%2$s',
		'linkedin'    => 'https://www.linkedin.com/shareArticle?url=%1$s&mini=true&title=%2$s',
		'pinterest'   => 'https://pinterest.com/pin/create/button/?url=%1$s&description=%2$s&media=%4$s',
		'reddit'      => 'https://www.reddit.com/submit?url=%1$s&title=%2$s',
		'tumblr'      => 'https://www.tumblr.com/share?v=3&u=%1$s&t=%2$s',
		'twitter'     => 'https://twitter.com/share?url=%1$s&text=%2$s',
		'vkontakte'   => 'https://vk.com/share.php?url=%1$s',
		'whatsapp'    => 'https://api.whatsapp.com/send?text=%2$s %1$s',
	];

	if ( ! isset( $media[ $type ] ) ) {
		return;
	}

	$thumb   = '';
	$excerpt = '';

	// Get post title.
	$title = wpgb_get_the_title();
	$title = html_entity_decode( $title, ENT_QUOTES, 'UTF-8' );
	$title = wp_strip_all_tags( $title );

	// Get excerpt content for email mainly.
	if ( strpos( $media[ $type ], '%3$s' ) !== false ) {

		$excerpt = wpgb_get_the_excerpt( 35, '...' );
		$excerpt = html_entity_decode( $excerpt, ENT_QUOTES, 'UTF-8' );
		$excerpt = wp_strip_all_tags( $excerpt );

	}

	// Get featured thumbnail url.
	if ( strpos( $media[ $type ], '%4$s' ) !== false ) {
		$thumb = wpgb_get_the_post_thumbnail_url();
	}

	// Get post link.
	$link = wpgb_get_the_permalink();
	$link = empty( $link ) ? home_url( add_query_arg( null, null ) ) : $link;

	// Build shared url.
	$shared = sprintf(
		$media[ $type ],
		rawurlencode( $link ),
		rawurlencode( $title ),
		rawurlencode( $excerpt ),
		rawurlencode( $thumb )
	);

	// Handle aria label for accessibility.
	if ( 'email' === $type ) {
		$label = esc_html__( 'share by email', 'wp-grid-builder' );
	} else {

		$name = str_replace( '-', ' ', $type );
		/* translators: %s: Social media type (Facebook, twitter, etc...) */
		$label = sprintf( esc_html__( 'share on %s', 'wp-grid-builder' ), ucwords( $name ) );

	}

	$class = wpgb_get_block_class( $block );

	printf(
		'<a class="%s wpgb-share-button" href="%s" rel="external noopener noreferrer" target="_blank" aria-label="%s">',
		esc_attr( $class ),
		esc_url( $shared ),
		esc_attr( $label )
	);

	wpgb_svg_icon( 'wpgb/social-media/' . $block['social_network'] );

	echo '</a>';

}

/**
 * Display the post meta-data key value
 *
 * @since 1.1.9 Support for repearter field (ACF) date and number formats.
 * @since 1.0.0
 *
 * @param array $block  Holds block args.
 * @param array $action Holds action args.
 */
function wpgb_metadata( $block = [], $action = [] ) {

	if ( empty( $block['meta_key'] ) ) {
		return;
	}

	$block   = wpgb_normalize_metadata_settings( $block );
	$meta    = wpgb_get_metadata( $block['meta_key'] );
	$meta    = wpgb_format_metadata( $block, $meta );
	$content = wp_kses_post( $meta );

	if ( is_null( $meta ) || '' === $meta || false === $meta ) {
		return;
	}

	wpgb_block_start( $block, $action );
		echo esc_html( $block['meta_prefix'] );
		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo esc_html( $block['meta_suffix'] );
	wpgb_block_end( $block, $action );

}

/**
 * Display raw content block (HTML)
 *
 * @since 1.2.3 Escape raw content before rendering shortcodees
 * @since 1.0.3 Prevent rendering content in overview panel
 * @since 1.0.0
 *
 * @param array $block  Holds block args.
 * @param array $action Holds action args.
 */
function wpgb_raw_content_block( $block = [], $action = [] ) {

	$content = '';

	if ( ! empty( $block['raw_content'] ) ) {

		// We escape before to apply do_shortcode (content is also sanitized on input).
		// It allows to preserve shortcodes markup from 3rd party plugins.
		$content = apply_filters( 'wp_grid_builder/dynamic_data', $block['raw_content'], 'card' );
		$content = wp_kses_post( $content );
		$content = do_shortcode( $content );

		// If HTML tags in the content make sure to be W3C compliant.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags
		if ( strip_tags( $content ) !== $content ) {
			$block['tag'] = 'div';
		}
	}

	wpgb_block_start( $block, $action );
		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	wpgb_block_end( $block, $action );

}
