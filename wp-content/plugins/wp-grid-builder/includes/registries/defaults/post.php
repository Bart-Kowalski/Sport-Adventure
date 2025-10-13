<?php
/**
 * Post defaults
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
	// General.
	'post_format'          => '',
	'permalink'            => '',
	'columns'              => '',
	'rows'                 => '',
	'content_background'   => '',
	'overlay_background'   => '',
	'content_color_scheme' => '',
	'overlay_color_scheme' => '',
	// Altenative image.
	'attachment_id'        => '',
	'gallery_ids'          => '',
	// Audio format.
	'mp3_url'              => '',
	'ogg_url'              => '',
	// Video format.
	'mp4_url'              => '',
	'ogv_url'              => '',
	'webm_url'             => '',
	'embed_video_url'      => '',
	'video_ratio'          => '16:9',
	'color'                => '',
	'background'           => '',
];
