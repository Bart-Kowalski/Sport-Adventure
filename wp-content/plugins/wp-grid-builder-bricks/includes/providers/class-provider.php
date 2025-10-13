<?php
/**
 * Provider
 *
 * @package   WP Grid Builder - Bricks
 * @author    Loïc Blascos
 * @copyright 2019-2025 Loïc Blascos
 */

namespace WP_Grid_Builder_Bricks\Includes\Providers;

use WP_Grid_Builder_Bricks\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default provider
 *
 * @class WP_Grid_Builder_Bricks\Includes\Providers\Provider
 * @since 1.0.0
 */
class Provider extends Base {

	use Includes\Helpers;

	/**
	 * Query offset
	 *
	 * @since 1.2.0
	 * @access public
	 * @var object
	 */
	public $offset = 0;

	/**
	 * Element options
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function options() {

		return [
			'itemSelector' => $this->get_selector() . ' > *',
		];
	}

	/**
	 * Inline JS script
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function script() {

		return '';

	}

	/**
	 * Apply hooks for current provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function before_render() {

		if ( ! $this->is_builder() ) {

			$this->main_query();
			$this->single_post();

		}

		add_filter( 'bricks/query/force_loop_index', [ $this, 'override_loop_index' ], PHP_INT_MAX - 10 );
		add_filter( 'bricks/query/no_results_content', [ $this, 'no_results' ], PHP_INT_MAX - 10, 3 );

		foreach ( [ 'posts', 'terms', 'users' ] as $object ) {

			add_filter( 'bricks/' . $object . '/query_vars', [ $this, 'set_element_id' ], PHP_INT_MAX - 10, 3 );
			add_action( 'pre_get_' . $object, [ $this, 'set_query_args' ], PHP_INT_MAX - 10 );

		}

		add_action( 'pre_get_posts', [ $this, 'set_offset' ], PHP_INT_MAX - 8 );

	}

	/**
	 * Remove hooks for current provider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function after_render() {

		foreach ( [ 'posts', 'terms', 'users' ] as $object ) {

			remove_filter( 'bricks/' . $object . '/query_vars', [ $this, 'set_element_id' ], PHP_INT_MAX - 10 );
			remove_action( 'pre_get_' . $object, [ $this, 'set_query_args' ], PHP_INT_MAX - 10 );

		}

		remove_action( 'pre_get_posts', [ $this, 'set_offset' ], PHP_INT_MAX - 8 );

	}

	/**
	 * Override loop index to properly inline CSS.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param integer $override Current loop index.
	 * @return integer
	 */
	public function override_loop_index( $override ) {

		if ( ! method_exists( '\Bricks\Query', 'get_query_object' ) ) {
			return $override;
		}

		$query      = \Bricks\Query::get_query_object();
		$element_id = \Bricks\Query::get_query_element_id();

		if ( 'bricks-element-' . $element_id !== $this->get_id() ) {
			return $override;
		}

		return (int) $query->loop_index + (int) $this->offset;

	}

	/**
	 * Set no results placeholder to properly render results
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string  $content    No results content message.
	 * @param array   $settings   Holds element settings.
	 * @param integer $element_id Holds element id.
	 * @return string
	 */
	public function no_results( $content, $settings, $element_id ) {

		if ( 'bricks-element-' . $element_id !== $this->get_id() ) {
			return $content;
		}

		if ( empty( $content ) ) {
			$content = '<div class="bricks-posts-nothing-found"></div>';
		}

		if ( ! wpgb_doing_ajax() ) {
			$content = preg_replace( '#(.?<\w+\s+\w+.*?)(>)#mi', "$1 data-options=\"{$this->get_options()}\" $2", $content, 1 );
		}

		remove_filter( 'bricks/query/no_results_content', [ $this, 'no_results' ], PHP_INT_MAX - 10, 3 );

		return $content;

	}

	/**
	 * Set query arguments to allow filtering
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array   $query_vars Holds query variables.
	 * @param array   $settings   Holds element settings.
	 * @param integer $element_id Holds element id.
	 * @return array
	 */
	public function set_element_id( $query_vars, $settings, $element_id ) {

		if ( 'bricks-element-' . $element_id !== $this->get_id() ) {
			return $query_vars;
		}

		$query_vars['wpgb_bricks'] = $this->get_id();

		// To prevent issue with Bricks terms counts principle.
		if ( 'bricks/terms/query_vars' === current_filter() && isset( $query_vars['number'] ) ) {

			$query_vars['wpgb_number'] = $query_vars['number'];
			unset( $query_vars['number'] );

		}

		// Unset offset to prevent conflict with pagination.
		if ( isset( $query_vars['offset'] ) ) {

			$query_vars['wpgb_offset'] = $query_vars['offset'];
			unset( $query_vars['offset'] );

		}

		// Set post type to empty if not set.
		if ( empty( $query_vars['post_type'] ) && empty( $settings['query']['post_type'] ) ) {
			$query_vars['post_type'] = '';
		}

		remove_action( current_filter(), [ $this, 'set_element_id' ], PHP_INT_MAX - 10 );

		return $query_vars;

	}

	/**
	 * Set query arguments to allow filtering
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param WP_Query $query The WP_Query instance.
	 * @return array
	 */
	public function set_query_args( $query ) {

		if (
			! isset( $query->query_vars['wpgb_bricks'] ) ||
			$query->query_vars['wpgb_bricks'] !== $this->get_id()
		) {
			return;
		}

		$query->query_vars['wp_grid_builder'] = $this->get_id();

		if ( ! empty( $this->settings['lang'] ) ) {
			$query->query_vars['lang'] = $this->settings['lang'];
		}

		// Set page number from Bricks pagination.
		if ( ! empty( $this->settings['paged'] ) ) {
			$query->query_vars['paged'] = (int) $this->settings['paged'];
		}

		// Restore number query variable.
		if ( isset( $query->query_vars['wpgb_number'] ) ) {
			$query->query_vars['number'] = $query->query_vars['wpgb_number'];
		}

		// Restore offset query variable.
		if ( isset( $query->query_vars['wpgb_offset'] ) ) {
			$query->query_vars['offset'] = $query->query_vars['wpgb_offset'];
		}

		remove_action( current_filter(), [ $this, 'set_query_args' ], PHP_INT_MAX - 10 );

	}

	/**
	 * Restore offset for Bricks pagination.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param WP_Query $query The WP_Query instance.
	 */
	public function set_offset( $query ) {

		if ( $this->get_id() !== $query->get( 'wpgb_bricks' ) ) {
			return;
		}

		$this->offset = $query->get( 'offset', 0 );

		if ( $query->get( 'paged', 1 ) <= 1 ) {
			return;
		}

		$query->set( 'offset', ( $query->get( 'paged', 1 ) - 1 ) * $query->get( 'posts_per_page' ) );

		remove_action( current_filter(), [ $this, 'set_offset' ], PHP_INT_MAX - 8 );

	}

	/**
	 * Simulate main WordPress query
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function main_query() {

		global $wp_query;

		if ( ! wpgb_doing_ajax() ) {
			return;
		}

		if ( empty( $this->settings['main_query'] ) ) {
			return;
		}

		if ( $this->is_main_query() ) {
			$this->settings['main_query']['wp_grid_builder'] = $this->get_id();
		}

		// We override the main query when filtering.
		$wp_query = new \WP_Query( $this->settings['main_query'] ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

	}

	/**
	 * Simulate single post page object
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function single_post() {

		global $post;

		if ( ! wpgb_doing_ajax() ) {
			return;
		}

		if ( ! empty( $this->settings['main_query'] ) ) {
			return;
		}

		$post_id = url_to_postid( wp_get_referer() );

		if ( empty( $post_id ) ) {
			return;
		}

		$post = get_post( $post_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

	}
}
