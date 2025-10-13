<?php
/**
 * Observer
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
 * Observer
 *
 * @class WP_Grid_Builder_Bricks\Includes\Observer
 * @since 1.0.0
 */
class Observer {

	/**
	 * Holds Bricks element to handle
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var boolean|object
	 */
	protected $handled = false;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_filter( 'bricks/element/render', [ $this, 'before_render' ], 10, 2 );
		add_filter( 'bricks/element/render', [ $this, 'after_render' ], 9, 2 );
		add_filter( 'bricks/frontend/render_data', [ $this, 'after_content' ] );
		add_filter( 'bricks/assets/generate_css_from_element', [ $this, 'before_generate_css' ], 10, 2 );
		add_filter( 'bricks/element/render', [ $this, 'after_generate_css' ], 9, 2 );

	}

	/**
	 * Before to render element
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param boolean $render  Whether to render element.
	 * @param array   $element Holds element to render.
	 * @return boolean
	 */
	public function before_render( $render, $element ) {

		if ( bricks_is_builder() && ! wpgb_doing_ajax() ) {
			return $render;
		}

		if ( empty( $element->name ) ) {
			return $render;
		}

		if ( $this->maybe_handle( $element->name, $element ) ) {
			$this->handled = $element;
		}

		return $render;

	}

	/**
	 * After to render previous element
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param boolean $render  Whether to render element.
	 * @param array   $element  Holds element to render.
	 * @return boolean
	 */
	public function after_render( $render, $element ) {

		if ( bricks_is_builder() && ! wpgb_doing_ajax() ) {
			return $render;
		}

		if ( empty( $this->handled ) || $element->id === $this->handled->id ) {
			return $render;
		}

		if ( empty( $this->handled->ignore ) && $this->maybe_render( $this->handled->name, $this->handled ) ) {
			$this->handled = false;
		}

		return $render;

	}

	/**
	 * After to render page content
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $content Rendered content.
	 * @return string
	 */
	public function after_content( $content ) {

		if ( bricks_is_builder() && ! wpgb_doing_ajax() ) {
			return $content;
		}

		if ( empty( $this->handled ) ) {
			return $content;
		}

		if ( $this->maybe_render( $this->handled->name, $this->handled ) && empty( $this->handled->ignore ) ) {
			$this->handled = false;
		}

		return $content;

	}

	/**
	 * Before to generate dynamic CSS
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param array $content Content.
	 * @param array $element Holds element to render.
	 * @return boolean
	 */
	public function before_generate_css( $content, $element ) {

		if ( bricks_is_builder() && ! wpgb_doing_ajax() ) {
			return $content;
		}

		if ( empty( $element['id'] ) ) {
			return $content;
		}

		if ( ! empty( $this->handled ) && $element['id'] === $this->handled->id ) {
			return $content;
		}

		$element = (object) wp_parse_args(
			[ 'ignore' => true ],
			$element
		);

		if ( $this->maybe_handle( $element->name, $element ) ) {
			$this->handled = $element;
		}

		return $content;

	}

	/**
	 * After to generate dynamic CSS
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param array $content Content.
	 * @param array $element Holds element to render.
	 * @return boolean
	 */
	public function after_generate_css( $content, $element ) {

		if ( ! empty( $this->handled->ignore ) && $element->id === $this->handled->id ) {
			Container::delete( 'bricks-element-' . $element->id );
		}

		return $content;

	}
}
