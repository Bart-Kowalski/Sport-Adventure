<?php
/**
 * Add WC Product Collection block support
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Integrations\Blocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle WC Product Collection block
 *
 * @class WP_Grid_Builder\Includes\Integrations\Blocks\WC_Product_Collection
 * @since 2.1.0
 */
class WC_Product_Collection extends Base {

	/**
	 * Block name.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @var string
	 */
	public $block_name = 'woocommerce/product-collection';

	/**
	 * Handle block
	 *
	 * @since 2.1.0
	 * @access public
	 */
	public function handle_block() {

		add_filter( 'render_block_data', [ $this, 'intercept_child' ], PHP_INT_MAX - 20, 3 );
		add_filter( 'render_block_woocommerce/product-collection', [ $this, 'set_classname' ], PHP_INT_MAX - 20, 2 );
		add_filter( 'render_block_woocommerce/product-template', [ $this, 'generate_styles' ], PHP_INT_MAX - 20, 3 );

	}
}
