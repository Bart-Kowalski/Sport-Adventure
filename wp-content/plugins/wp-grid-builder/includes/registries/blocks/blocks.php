<?php
/**
 * Blocks
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array_merge(
	include_once WPGB_PATH . '/includes/registries/blocks/design.php',
	include_once WPGB_PATH . '/includes/registries/blocks/common.php',
	include_once WPGB_PATH . '/includes/registries/blocks/post.php',
	include_once WPGB_PATH . '/includes/registries/blocks/product.php',
	include_once WPGB_PATH . '/includes/registries/blocks/term.php',
	include_once WPGB_PATH . '/includes/registries/blocks/user.php'
);
