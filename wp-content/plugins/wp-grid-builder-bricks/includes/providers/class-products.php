<?php
/**
 * WooCommerce Products element
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
 * @class WP_Grid_Builder_Bricks\Includes\Providers\Products
 * @since 1.0.0
 */
final class Products extends Provider {

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
				'itemSelector' => 'ul.products.woocommerce > li',
				'element'      => 'woocommerce-products',
			]
		);
	}

	/**
	 * Apply hooks for current provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function before_render() {

		add_action( 'woocommerce_no_products_found', [ $this, 'no_products_found' ] );

		parent::before_render();

	}

	/**
	 * Remove hooks for current provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function after_render() {

		remove_action( 'woocommerce_no_products_found', [ $this, 'no_products_found' ] );

		parent::after_render();

	}

	/**
	 * Set no results placeholder to properly render results
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function no_products_found() {

		$content = ob_get_clean();

		if ( empty( $content ) ) {
			$content = '<div hidden></div>';
		}

		if ( ! wpgb_doing_ajax() ) {
			$content = preg_replace( '#(.?<\w+\s+\w+.*?)(>)#mi', "$1 data-options=\"{$this->get_options()}\" $2", $content, 1 );
		}

		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
}
