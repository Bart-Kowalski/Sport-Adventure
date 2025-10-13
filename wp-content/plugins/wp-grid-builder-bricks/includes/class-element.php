<?php
/**
 * Element
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
 * Element
 *
 * @class WP_Grid_Builder_Bricks\Includes\Element
 * @since 1.0.0
 */
final class Element {

	use Helpers;

	/**
	 * Holds template attributes
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var array
	 */
	protected $template = [];

	/**
	 * Holds elements to render
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var array
	 */
	protected $elements = [];

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $template Holds template attributes.
	 */
	public function __construct( $template ) {

		$this->template = $template;

	}

	/**
	 * Parse Bricks elements to match the one to be filtered
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_content() {

		if ( empty( $this->template['post_id'] ) ) {
			return '';
		}

		// If we render facets in the editor.
		if ( ! empty( $this->template['elements'] ) ) {

			return $this->get_elements(
				$this->template['elements'],
				$this->template['post_id']
			);
		}

		foreach ( (array) $this->template['post_id'] as $post_id ) {

			$content = $this->get_elements(
				$this->get_bricks_data( $post_id ),
				$post_id
			);

			if ( ! empty( $content ) ) {
				return $content;
			}
		}

		return '';

	}

	/**
	 * Get content from Bricks
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array   $elements Holds elements to render.
	 * @param integer $post_id  Template post ID.
	 * @return string
	 */
	public function get_elements( $elements, $post_id ) {

		$this->elements = $elements;

		foreach ( $this->elements as $element ) {

			$content = $this->get_element( $element, $post_id );

			if ( ! empty( $content ) ) {
				return $content;
			}
		}

		return '';

	}

	/**
	 * Get filterable element
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array   $element Holds element attributes.
	 * @param integer $post_id Template post ID.
	 * @return array
	 */
	public function get_element( $element, $post_id ) {

		if ( ! isset( $element['id'], $element['settings'] ) ) {
			return '';
		}

		if ( 'bricks-element-' . $element['id'] !== $this->template['id'] ) {
			return '';
		}

		$assets_exists = method_exists( '\Bricks\Assets', 'generate_css_from_elements' );
		$popups_exists = class_exists( '\Bricks\Popups' );

		if ( class_exists( '\Bricks\Popups' ) ) {

			\Bricks\Database::$page_data['preview_or_post_id'] = $post_id;
			\Bricks\Database::$active_templates = [
				'header'              => 0,
				'footer'              => 0,
				'content'             => 0,
				'section'             => 0,
				'archive'             => 0,
				'error'               => 0,
				'search'              => 0,
				'popup'               => [],
				'password_protection' => 0,
			];
		}

		// We setup element to filter to handle it correctly.
		apply_filters( 'wp_grid_builder_bricks/setup_element', $this->template['id'] );
		// Remove query loop trail.
		add_filter( 'bricks/render_query_loop_trail', '__return_false' );

		$element['settings'] = array_merge( $element['settings'], $this->template );

		// Render only the element without parent context.
		unset( $element['parent'] );

		$elements = array_merge( [ $element ], $this->get_children( $element ) );

		// Generate CSS from elements with a unique name.
		if ( $assets_exists ) {
			\Bricks\Assets::generate_css_from_elements( $elements, 'wpgb' );
		}

		// Not properly rendered because of bricks_is_builder_call() that returns true for any Ajax or REST API request.
		$element = \Bricks\Frontend::render_data( array_filter( $elements ) );
		$popups  = '';
		$styles  = '';

		// Add popup HTML.
		if ( $popups_exists ) {
			$popups = \Bricks\Popups::$looping_popup_html;
		}

		// Generate CSS once rendered.
		if ( $assets_exists ) {

			$styles  = ! empty( \Bricks\Assets::$inline_css['wpgb'] ) ? \Bricks\Assets::$inline_css['wpgb'] : '';
			$styles .= \Bricks\Assets::$inline_css_dynamic_data;
			$styles  = ! empty( $styles ) ? '<style>' . $styles . '</style>' : '';

		}

		return $element . $popups . $styles;

	}

	/**
	 * Get children element from parent element
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $parent Holds parent element attributes.
	 * @return array
	 */
	public function get_children( $parent ) {

		$children = [];

		if ( empty( $parent['children'] ) ) {
			return $children;
		}

		foreach ( $this->elements as $element ) {

			if ( in_array( $element['id'], $parent['children'], true ) ) {

				$children[] = $element;
				$children   = array_merge( $children, $this->get_children( $element ) );

			}
		}

		return $children;

	}
}
