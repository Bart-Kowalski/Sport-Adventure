<?php
/**
 * Assets Interface
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\FrontEnd\Interfaces;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Assets_Interface {

	/**
	 * Register stylesheets and scripts
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register();
}
