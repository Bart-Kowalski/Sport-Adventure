<?php
/**
 * Bricks providers
 *
 * @package   WP Grid Builder - Bricks
 * @author    Loïc Blascos
 * @copyright 2019-2025 Loïc Blascos
 */

namespace WP_Grid_Builder_Bricks\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Providers
 *
 * @class WP_Grid_Builder_Bricks\Includes\Providers
 * @since 1.0.0
 */
final class Providers extends Observer {

	/**
	 * Holds default Bricks providers
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $defaults = [
		'div'                  => __NAMESPACE__ . '\Providers\Div',
		'block'                => __NAMESPACE__ . '\Providers\Block',
		'posts'                => __NAMESPACE__ . '\Providers\Posts',
		'container'            => __NAMESPACE__ . '\Providers\Container',
		'woocommerce-products' => __NAMESPACE__ . '\Providers\Products',
	];

	/**
	 * Holds rendered post ids
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $post_ids = [];

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		parent::__construct();

		add_filter( 'wp_grid_builder_bricks/providers', [ $this, 'register' ] );
		add_filter( 'wp_grid_builder_bricks/data_post_id', [ $this, 'get_post_ids' ] );
		add_filter( 'bricks/builder/data_post_id', [ $this, 'get_post_ids' ] );

	}

	/**
	 * Add default providers
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $providers Holds providers.
	 * @return array
	 */
	public function register( $providers ) {

		return array_merge( $this->defaults, $providers );

	}

	/**
	 * Get rendered post IDs
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param integer $post_id Holds current post ID.
	 * @return integer
	 */
	public function get_post_ids( $post_id ) {

		if ( ! empty( $post_id ) && ! in_array( (int) $post_id, $this->post_ids, true ) ) {
			$this->post_ids[] = (int) $post_id;
		}

		return $post_id;

	}

	/**
	 * Handle provider before render
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $name Provider name.
	 * @param array  $attr Provider attributes.
	 * @return boolean
	 */
	public function maybe_handle( $name, $attr ) {

		$element = $this->get_element( $name, $attr );

		if ( ! $element ) {
			return false;
		}

		$instance = $this->instantiate( $name, $element );

		if ( ! $instance ) {
			return false;
		}

		$instance->_before_render();

		return true;

	}

	/**
	 * Handle provider after render
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $name Provider name.
	 * @param array  $attr Provider attributes.
	 * @return boolean
	 */
	public function maybe_render( $name, $attr ) {

		$element = $this->get_element( $name, $attr );

		if ( ! $element ) {
			return false;
		}

		$instance = Container::get( $element->id );

		if ( ! $instance ) {
			return false;
		}

		$instance->_after_render();

		return true;

	}

	/**
	 * Get element attributes
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $name Element name.
	 * @param array  $attr Element attributes.
	 * @return false|object
	 */
	public function get_element( $name, $attr ) {

		if ( ! $this->is_provider( $name ) ) {
			return false;
		}

		if ( ! isset( $attr->settings, $attr->id ) ) {
			return false;
		}

		$attr->settings['post_id'] = $this->post_ids;

		return (object) [
			'id'       => 'bricks-element-' . $attr->id,
			'ignore'   => isset( $attr->ignore ),
			'settings' => $attr->settings,
		];
	}

	/**
	 * Get provider name
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $name Provider name.
	 * @return false|string
	 */
	public function is_provider( $name ) {

		$providers = apply_filters( 'wp_grid_builder_bricks/providers', [] );

		if ( ! isset( $providers[ $name ] ) || ! class_exists( $providers[ $name ] ) ) {
			return false;
		}

		return $name;

	}

	/**
	 * Instantiate provider
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $provider Provider name.
	 * @param array  $element  Provider element.
	 * @return false|Container
	 */
	public function instantiate( $provider, $element ) {

		if ( ! apply_filters( 'wp_grid_builder_bricks/handle_element', false, $element->id, $element->ignore ) ) {
			return false;
		}

		$providers = apply_filters( 'wp_grid_builder_bricks/providers', [] );

		return Container::add( $element->id, new $providers[ $provider ]( $element, $element->settings ) );

	}
}
