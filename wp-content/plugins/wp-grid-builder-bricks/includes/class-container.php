<?php
/**
 * Container
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
 * Container
 *
 * @class WP_Grid_Builder_Bricks\Includes\Container
 * @since 1.0.0
 */
final class Container {

	/**
	 * Holds container instances
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array
	 */
	private static $instances = [];

	/**
	 * Add container instance
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string   $id       Instance id.
	 * @param Instance $instance Instance.
	 * @return false|Instance
	 */
	public static function add( $id, $instance ) {

		if ( self::has( $id ) ) {
			return self::get( $id );
		}

		self::$instances[ $id ] = $instance;

		return $instance;

	}

	/**
	 * Get container instance
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id Instance id.
	 * @return false|Instance
	 */
	public static function get( $id ) {

		if ( ! self::has( $id ) ) {
			return false;
		}

		return self::$instances[ $id ];

	}

	/**
	 * Get all container instances
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function all() {

		return self::$instances;

	}

	/**
	 * Delete container instance
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id Instance id.
	 * @return boolean
	 */
	public static function delete( $id ) {

		if ( ! self::has( $id ) ) {
			return false;
		}

		unset( self::$instances[ $id ] );

		return true;

	}

	/**
	 * Whether it has container instance
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id Instance id.
	 * @return boolean
	 */
	public static function has( $id ) {

		return isset( self::$instances[ $id ] );

	}
}
