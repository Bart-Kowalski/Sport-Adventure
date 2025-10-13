<?php
/**
 * Add Core Query block support
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
 * Handle Core Query block
 *
 * @class WP_Grid_Builder\Includes\Integrations\Blocks\Core_Query
 * @since 2.1.0
 */
class Core_Query extends Base {

	/**
	 * Block name.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @var string
	 */
	public $block_name = 'core/query';

	/**
	 * Handle block
	 *
	 * @since 2.1.0
	 * @access public
	 */
	public function handle_block() {

		add_filter( 'render_block_data', [ $this, 'intercept_child' ], PHP_INT_MAX - 20, 3 );
		add_filter( 'render_block_core/query', [ $this, 'set_classname' ], PHP_INT_MAX - 20, 2 );
		add_filter( 'query_loop_block_query_vars', [ $this, 'set_query_var' ], PHP_INT_MAX - 20, 2 );
		add_filter( 'render_block_core/post-template', [ $this, 'generate_styles' ], PHP_INT_MAX - 20, 3 );

	}
}
