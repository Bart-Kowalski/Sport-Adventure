<?php
/**
 * Facet element
 *
 * @package   WP Grid Builder - Bricks
 * @author    Loïc Blascos
 * @copyright 2019-2025 Loïc Blascos
 */

namespace WP_Grid_Builder_Bricks\Elements;

use WP_Grid_Builder_Bricks\Includes\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Facet element
 *
 * @class WP_Grid_Builder_Bricks\Elements\Facet
 * @since 1.0.0
 */
final class Facet extends \Bricks\Element {

	use Helpers;

	/**
	 * Category slug
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $category = 'WP Grid Builder';

	/**
	 * Element name
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $name = 'wpgb-facet';

	/**
	 * Element icon
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $icon = 'ti-filter';

	/**
	 * Element CSS selector
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $css_selector = '.wpgb-facet';

	/**
	 * Nestable element
	 *
	 * @since 1.0.0
	 * @access public
	 * @var boolean
	 */
	public $nestable = false;

	/**
	 * Element label
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_label() {

		return esc_html__( 'Facet', 'wpgb-bricks' );

	}

	/**
	 * Element controls
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_controls() {

		include WPGB_BRICKS_PATH . 'includes/elements/controls/facet.php';

	}

	/**
	 * Get grid ID
	 *
	 * @since 1.0.2
	 * @access public
	 *
	 * @return string
	 */
	public function get_grid_id() {

		if ( empty( $this->settings['element_id'] ) ) {
			return ! empty( $this->settings['grid'] ) ? $this->settings['grid'] : '';
		}

		return 'bricks-element-' . str_replace( 'brxe-', '', str_replace( '#', '', $this->settings['element_id'] ) );

	}

	/**
	 * Element render
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render() {

		$grid_id  = $this->get_grid_id();
		$is_empty = empty( $this->settings['id'] ) || empty( $grid_id );

		if ( ! $this->is_builder() && $is_empty ) {
			return;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div ' . $this->render_attributes( '_root' ) . '>';
		if ( ! $is_empty ) {

			wpgb_render_facet(
				[
					'id'    => $this->settings['id'],
					'grid'  => $grid_id,
					'style' => ! empty( $this->settings['style'] ) ? $this->settings['style'] : '',
				]
			);

			if ( $this->is_builder() && function_exists( 'wpgb_print_facet_style' ) ) {

				wpgb_print_facet_style();
				wpgb_print_facet_fonts();

			}
		}
		echo '</div>';

	}
}
