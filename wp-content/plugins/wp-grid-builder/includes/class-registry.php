<?php
/**
 * Handle registries
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register
 *
 * @class WP_Grid_Builder\Includes\Registry
 * @since 2.0.0
 */
final class Registry {

	/**
	 * Registry base
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $base = 'includes/registries/%s.php';

	/**
	 * Registry prefix
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $prefix = 'wp_grid_builder/';

	/**
	 * Holds registered data.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $data = [];

	/**
	 * Holds hooks.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $entries = [
		'addons',
		'animations',
		'block_categories',
		'blocks',
		'conditions',
		'dynamic_tags',
		'facets',
		'fonts',
		'icons',
		'loaders',
		'controls/card',
		'controls/facet',
		'controls/grid',
		'controls/options',
		'controls/post',
		'controls/style',
		'controls/styles',
		'controls/term',
		'defaults/card',
		'defaults/facet',
		'defaults/grid',
		'defaults/options',
		'defaults/post',
		'panels/facet',
		'panels/grid',
		'panels/options',
		'panels/post',
		'tabs/facet',
		'tabs/grid',
		'tabs/options',
		'tabs/post',
	];

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		foreach ( $this->entries as $entry ) {
			add_filter( $this->prefix . $entry, [ $this, 'register' ], 0 );
		}
	}

	/**
	 * Register an entry on the fly
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array $return Holds registered objects.
	 * @return array
	 */
	public function register( $return = [] ) {

		$entry = str_replace( [ $this->prefix, '_' ], [ '', '-' ], current_filter() );

		if ( ! isset( $this->data[ $entry ] ) ) {

			$file_path = count( explode( '/', $entry ) ) > 1;
			$file_path = $file_path ? $entry : $entry . '/' . $entry;
			$file_path = sprintf( WPGB_PATH . $this->base, $file_path );

			if ( file_exists( $file_path ) ) {
				$this->data[ $entry ] = include_once $file_path;
			}
		}

		if ( empty( $this->data[ $entry ] ) ) {
			$this->data[ $entry ] = [];
		}

		return array_merge( $return, $this->data[ $entry ] );

	}
}
