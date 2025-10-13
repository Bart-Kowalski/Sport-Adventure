<?php
/**
 * Container element
 *
 * @package   WP Grid Builder - Bricks
 * @author    Loïc Blascos
 * @copyright 2019-2025 Loïc Blascos
 */

namespace WP_Grid_Builder_Bricks\Includes\Providers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post Grid module
 *
 * @class WP_Grid_Builder_Bricks\Includes\Providers\Container
 * @since 1.0.0
 */
final class Container extends Provider {

	/**
	 * Module options
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function options() {

		return array_merge(
			parent::options(),
			[
				'itemSelector' => '.brxe-container, .bricks-posts-nothing-found',
				'element'      => 'container',
			]
		);
	}
}
