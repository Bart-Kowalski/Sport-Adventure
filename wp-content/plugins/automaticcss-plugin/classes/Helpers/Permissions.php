<?php
/**
 * Automatic.css Permissions helper file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Helpers;

use Automatic_CSS\Services\BuilderContext;

/**
 * Automatic.css WordPress helper class
 */
class Permissions {
	public const ACSS_FULL_ACCESS_CAPABILITY = 'manage_options';

	/**
	 * Check if user has acss and builder acdess
	 *
	 * @return bool
	 */
	public static function current_user_has_full_access() {
		return (bool) self::current_user_has_acss_access() && self::current_user_has_builder_access();
	}

	/**
	 * Check if user has acss access
	 *
	 * @return bool
	 */
	public static function current_user_has_acss_access() {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		if ( is_multisite() && is_super_admin() ) {
			return true;
		}

		if ( current_user_can( self::ACSS_FULL_ACCESS_CAPABILITY ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if user has builder access
	 *
	 * @return bool
	 */
	public static function current_user_has_builder_access() {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		$builder_context = new BuilderContext();
		if ( $builder_context->is_builder_active( 'bricks' ) ) {
			return (bool) class_exists( '\Bricks\Capabilities' ) && \Bricks\Capabilities::current_user_has_full_access();
		}

		return true;
	}
}
