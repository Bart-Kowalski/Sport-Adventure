<?php
/**
 * Automatic.css Etch class file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Framework\Platforms;

use Automatic_CSS\Framework\Base;
use Automatic_CSS\Traits\Builder as Builder_Trait;

/**
 * Automatic.css Etch class.
 */
class Etch extends Base implements Platform, Builder {

	use Builder_Trait {
		in_builder_context as in_builder_context_common;
		in_preview_context as in_preview_context_common;
		in_frontend_context as in_frontend_context_common;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->builder_prefix = 'etch'; // for the Builder trait.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_builder_assets' ) );
		add_filter( 'etch/preview/additional_stylesheets', array( $this, 'enqueue_preview_assets' ) );
		add_filter( 'automaticcss_framework_variables', array( $this, 'inject_scss_enabler_option' ) );
	}

	/**
	 * Is the Etch platform active?
	 *
	 * @return boolean
	 */
	public static function is_active() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return is_plugin_active( 'etch/etch.php' );
	}

	/**
	 * Get the version of Etch.
	 *
	 * @return string
	 */
	public static function get_version() {
		if ( ! self::is_active() ) {
			return '';
		}
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/etch/etch.php' );
		$version = $plugin_data['Version'];
		return $version;
	}

	/**
	 * Are we in Etch's builder context?
	 * That means we're in the builder, but not in the preview's iframe.
	 *
	 * @return bool
	 */
	public static function is_builder_context() {
		$is_builder = (bool) filter_input( INPUT_GET, 'etch' );
		$is_preview = self::is_preview_context();
		return $is_builder && ! $is_preview;
	}

	/**
	 * Are we in Etch's iframe context?
	 * That means we're in NOT in the builder, just in the preview's iframe.
	 *
	 * @return bool
	 */
	public static function is_preview_context() {
		// The iframe used by Etch doesn't have its own URL, so we can't detect it here.
		// Tried did_filter( 'etch/preview/additional_stylesheets' ) as well, but it's not working.
		return false;
	}

	/**
	 * Are we in Etch's frontend context?
	 * That means we're in neither in the builder nor in the preview's iframe.
	 *
	 * @return bool
	 */
	public static function is_frontend_context() {
		return ! is_admin() && ! self::is_builder_context() && ! self::is_preview_context();
	}

	/**
	 * Enqueue the builder assets.
	 */
	public function enqueue_builder_assets() {
		if ( self::is_builder_context() ) {
			$this->in_builder_context_common();
		} else if ( self::is_frontend_context() ) {
			$this->in_frontend_context_common();
		}
	}

	/**
	 * Enqueue the preview assets.
	 *
	 * @param array $additional_stylesheets The stylesheets to add to Etch's preview.
	 * @return array The (possibly modified) stylesheets to add to Etch's preview.
	 */
	public function enqueue_preview_assets( $additional_stylesheets ) {
		// No self::is_preview_context() because it doesn't work with Etch.
		// We trust that the action is only called when we're in the preview.
		$this->in_preview_context_common();
		return apply_filters( 'acss/etch/additional_stylesheets', $additional_stylesheets );
	}

	/**
	 * Inject an SCSS variable in the CSS generation process to enable this module.
	 *
	 * @param array $variables The values for the framework's variables.
	 * @return array
	 */
	public function inject_scss_enabler_option( $variables ) {
		$variables['option-etch'] = 'on';
		return $variables;
	}

}
