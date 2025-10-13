<?php
/**
 * Base
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
 * Providers base
 *
 * @class WP_Grid_Builder_Bricks\Includes\Providers\Base
 * @since 1.0.0
 */
abstract class Base {

	/**
	 * Element to handle
	 *
	 * @since 1.0.0
	 * @access public
	 * @var object
	 */
	public $element;

	/**
	 * Widget settings
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $settings;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $element  Holds element attributes.
	 * @param array $settings Holds Request settings.
	 */
	public function __construct( $element, $settings = [] ) {

		$this->element  = $element;
		$this->settings = $settings;

	}

	/**
	 * Handle element before rendering
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function _before_render() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore

		add_filter( 'bricks/element/render_attributes', [ $this, 'inline_options' ], PHP_INT_MAX, 3 );
		add_filter( 'paginate_links', [ $this, 'paginate_links' ], PHP_INT_MAX );

		$this->before_render();

	}

	/**
	 * Handle element after rendering
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function _after_render() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore

		remove_filter( 'bricks/element/render_attributes', [ $this, 'inline_options' ], PHP_INT_MAX, 3 );
		remove_filter( 'paginate_links', [ $this, 'paginate_links' ], PHP_INT_MAX );

		$this->after_render();

	}

	/**
	 * Get element id
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_id() {

		return $this->element->id;

	}

	/**
	 * Get post id
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_post_id() {

		return $this->settings['post_id'];

	}

	/**
	 * Get CSS selector
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_selector() {

		return '[data-options*="' . sanitize_html_class( $this->get_id() ) . '"]';

	}

	/**
	 * Check if this is the main query
	 *
	 * @since 1.1.5
	 * @access public
	 *
	 * @return string
	 */
	public function is_main_query() {

		return (
			! empty( $this->settings['query']['is_archive_main_query'] ) ||
			! empty( $this->settings['is_archive_main_query'] )
		);
	}

	/**
	 * Get JS options
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_options() {

		return esc_attr(
			wp_json_encode(
				wp_parse_args(
					[
						'id'            => $this->get_id(),
						'postId'        => $this->get_post_id(),
						'isMainQuery'   => $this->is_main_query(),
						'isTemplate'    => 'Bricks',
						'customContent' => true,
					],
					$this->options()
				)
			)
		);
	}

	/**
	 * Inline element HTML data attribute options
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array  $attrs  Holds element HTML attributes.
	 * @param string $key    HTML element identifier to render attributes for.
	 * @param object $object Bricks element object .
	 * @return array
	 */
	public function inline_options( $attrs, $key, $object ) {

		if ( empty( $attrs['_root'] ) || 'bricks-element-' . $object->id !== $this->get_id() ) {
			return $attrs;
		}

		$attrs['_root']['data-options'] = $this->get_options();

		return $attrs;

	}

	/**
	 * Add inline javascript code to instantiate element
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function inline_script() {

		return apply_filters(
			'wp_grid_builder_bricks_builder/inline_script',
			(
				'window.addEventListener(\'wpgb.loaded\',function(){' .
				'var template=document.querySelector(' . wp_json_encode( $this->get_selector() ) . ');' .
				'if(template){var wpgb=WP_Grid_Builder.instantiate(template);wpgb.init&&wpgb.init();' . $this->script() . '}});'
			)
		);
	}

	/**
	 * Replace paginated links with current permalink.
	 *
	 * @since 1.0.0
	 *
	 * @param string $link The paginated link URL.
	 * @return string
	 */
	public function paginate_links( $link ) {

		if ( ! wpgb_doing_ajax() ) {
			return $link;
		}

		$query_string = wpgb_get_query_string();

		if ( empty( $query_string ) ) {
			return $link;
		}

		if ( ! empty( $this->settings['permalink'] ) ) {

			$link = str_replace(
				trailingslashit( home_url( '', 'relative' ) ),
				trailingslashit( wp_make_link_relative( $this->settings['permalink'] ) ),
				$link
			);
		}

		$keys = preg_filter( '/^/', '_', array_keys( $query_string ) );
		$keys = array_merge( [ 'wpgb-ajax', 'action' ], $keys );
		$link = str_replace( '#038;', '&', $link );
		$link = remove_query_arg( $keys, $link );

		return $link;

	}

	/**
	 * Get element options to inline
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	abstract protected function options();

	/**
	 * Get element script to localize
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	abstract protected function script();

	/**
	 * Before element render
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	abstract protected function before_render();

	/**
	 * After element render
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	abstract protected function after_render();
}
