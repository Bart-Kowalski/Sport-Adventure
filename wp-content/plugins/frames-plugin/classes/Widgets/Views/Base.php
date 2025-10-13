<?php
/**
 * Base View class.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Widgets\Views;

/**
 * Base View Class
 */
class Base {
	/**
	 * Settings for the view
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Default settings for the Widget
	 *
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * Initialize
	 *
	 * @param array $settings Array with the settings for the view.
	 */
	public function __construct( array $settings = array() ) {
		$this->settings = wp_parse_args( $settings, array_merge( $this->defaults, array( 'root_attr' => '' ) ) );
	}

	/**
	 * Get the output of the view
	 *
	 * @return string
	 */
	public function get_view() {
		ob_start();

		$this->render();

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/**
	 * Generate the HTML
	 *
	 * @return void
	 */
	protected function render() {}
}
