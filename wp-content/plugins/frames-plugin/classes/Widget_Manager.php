<?php
/**
 * Frames Widget Manager class file.
 *
 * @package Frames_Client
 */

namespace Frames_Client;

use Frames_Client\Helpers\Logger;
use Frames_Client\Traits\Singleton;

/**
 * Widget Manager class.
 */
final class Widget_Manager {

	use Singleton;

	/**
	 * Array of widgets.
	 *
	 * @var array
	 */
	private $widgets = array(
		/**
		 * 'widget-name' => array(
		 *   'option' => 'option-name',
		 *   'label' => 'Widget Label'
		 * )
		 */
		'table-of-contents' => array(
			'option' => 'option-frames-toc-widget',
			'label' => 'Table of Contents'
		),
		'table-of-contents-v2' => array(
			'option' => 'option-frames-toc-widget-v2',
			'label' => 'Use new Table of Contents ( Beta )'
		),
		'modal'             => array(
			'option' => 'option-frames-modal-widget',
			'label' => 'Modal'
		),
		'trigger'           => array(
			'option' => 'option-frames-trigger-widget',
			'label' => 'Trigger'
		),
		'slider'            => array(
			'option' => 'option-frames-slider-widget',
			'label' => 'Slider'
		),
		'slider-controls'   => array(
			'option' => 'option-frames-slider-controls-widget',
			'label' => 'Slider Controls'
		),
		'accordion'         => array(
			'option' => 'option-frames-accordion-widget',
			'label' => 'Accordion'
		),
		'tabs'              => array(
			'option' => 'option-frames-tabs-widget',
			'label' => 'Tabs'
		),
		'switch'            => array(
			'option' => 'option-frames-switch-widget',
			'label' => 'Switch'
		),
		'color-scheme'        => array(
			'option' => 'option-frames-color-scheme-widget',
			'label' => 'Color Scheme'
		),
		'notes'             => array(
			'option' => 'option-frames-notes-widget',
			'label' => 'Notes'
		),
	);

	/**
	 * Default states for widgets.
	 *
	 * @var array
	 */
	private $default_widgets_states = array(
		'notes' => 'on',
	);

	/**
	 * Initialize the Widgets.
	 *
	 * @return Widget_Manager
	 */
	public function init() {
		add_action( 'init', array( $this, 'init_widgets' ), 11 );
		if ( is_plugin_active( 'automaticcss-plugin/automaticcss-plugin.php' ) ) {
			$acss_version = \Automatic_CSS\Plugin::get_plugin_version();
			if ( version_compare( $acss_version, '3.0', '<' ) ) {
				add_filter( 'acss/config/variables.json', array( $this, 'inject_acss_variables' ) );
				add_filter( 'acss/config/ui.json', array( $this, 'inject_acss_ui_elements' ) );
			} else {
				add_filter( 'acss/config/ui.json/after_load', array( $this, 'inject_acss_30_settings' ) );
			}
		}
		return $this;
	}

	/**
	 * Inject a toggle variable to enable / disable each widget into the ACSS dashboard.
	 *
	 * @param array $config The config options.
	 * @return array
	 */
	public function inject_acss_30_settings( $config ) {
		$frames_components = &$this->get_acss_30_settings( $config, 'frames-components' );
		if ( is_array( $frames_components ) ) {
			// STEP: loop through the widgets and add the settings.
			foreach ( $this->widgets as $widget_name => $widget_options ) {
				$option = $widget_options['option'];
				$label = $widget_options['label'];
				$default = $this->default_widgets_states[ $widget_name ] ?? 'off'; // todo Add a test for this widgets default state.
				$new_settings = array(
					'id' => $option,
					'title' => $label,
					'type' => 'toggle',
					'default' => $default,
					'style' => '',
				);
				// STEP: search for an existing setting and merge the new settings over the existing ones.
				$setting_already_exists = false;
				foreach ( $frames_components as $key => $value ) {
					if ( isset( $frames_components[ $key ]['id'] ) && $option === $frames_components[ $key ]['id'] ) {
						$setting_already_exists = true;
						$frames_components[ $key ] = array_merge( $frames_components[ $key ], $new_settings );
						break;
					}
				}
				// STEP: add the new settings if it doesn't exist.
				if ( ! $setting_already_exists ) {
					$frames_components[] = $new_settings;
				}
			}
		}
		$frames_options = &$this->get_acss_30_settings( $config, 'frames-options' );
		if ( is_array( $frames_options ) ) {
			$frames_options_page = array(
				'id' => 'option-frames-own-admin-page',
				'title' => 'Give Frames its own admin page?',
				'type' => 'toggle',
				'default' => 'off',
			);
			$frames_options[] = $frames_options_page;
		}
		return $config;
	}

	/**
	 * Find the frames-components content in the ACSS 3.0 settings.
	 *
	 * @param array  $config The config options.
	 * @param string $config_id The config ID to search for.
	 * @return array|null
	 */
	private function &get_acss_30_settings( &$config, $config_id ) {
		$var_config = &$config['content'];
		// This is the structure we're looking for inside of $var_config:
		// $var_config['id' = 'frames]['content']['id' = 'frames-components']['content'].
		// STEP: find the frames-components content.
		foreach ( $var_config as &$item ) {
			if ( isset( $item['id'] ) && 'frames' === $item['id'] ) {
				$sub_array = &$item['content'] ?? null;
				if ( is_array( $sub_array ) ) {
					foreach ( $sub_array as &$sub_item ) {
						if ( isset( $sub_item['id'] ) && $config_id === $sub_item['id'] ) {
							$frames_content = &$sub_item['content'] ?? null;
							if ( is_array( $frames_content ) ) {
								return $frames_content;
							}
						}
					}
				}
			}
		}
		return null;
	}

	/**
	 * Inject a toggle variable to enable / disable each widget into the ACSS dashboard.
	 *
	 * @param array $config The config options.
	 * @return array
	 */
	public function inject_acss_variables( $config ) {
		$var_config = &$config['variables'];
		$options_to_add = $this->widgets;
		$options_to_add[] = array(
			'option' => 'option-frames-own-admin-page',
			'label' => 'Give Frames its own admin page?'
		);
		foreach ( $options_to_add as $widget_name => $widget_options ) {
			$option = $widget_options['option'];
			if ( empty( $var_config[ $option ] ) ) {
				$var_config[ $option ] = array();
			}
			$new_settings = array(
				'type' => 'toggle',
				'valueon' => 'on',
				'valueoff' => 'off',
				'default' => 'off'
			);
			$var_config[ $option ] = array_merge( $var_config[ $option ], $new_settings );
		}
		return $config;
	}

	/**
	 * Inject a UI toggle for each widget into the ACSS dashboard.
	 *
	 * @param array $config The config options.
	 * @return array
	 */
	public function inject_acss_ui_elements( $config ) {
		$frames_config = &$config['tabs']['frames']['content']['frames-components']['content'];
		foreach ( $this->widgets as $widget_name => $widget_options ) {
			$option = $widget_options['option'];
			if ( empty( $frames_config[ $option ] ) ) {
				$frames_config[ $option ] = array();
			}
			$component_name = ucwords( str_replace( '-', ' ', $widget_name ) );
			$label = $widget_options['label'];
			$new_settings = array(
				'type' => 'variable',
				'title' => $label,
				'tooltip' => "Load the Frames {$component_name} Widget."
			);
			$frames_config[ $option ] = array_merge( $frames_config[ $option ], $new_settings );
		}
		$frames_additional = &$config['tabs']['frames']['content']['additional-frames-options']['content'];
		$frames_options_page = array(
			'type' => 'variable',
			'title' => 'Give Frames its own admin page?',
			'tooltip' => 'Create a dedicated admin page for Frames.'
		);
		$frames_additional['option-frames-own-admin-page'] = $frames_options_page;
		return $config;
	}

	/**
	 * Initialize the Widgets.
	 *
	 * @return void
	 */
	public function init_widgets() {
		Logger::log( sprintf( '%s: Initializing widgets', __METHOD__ ) );
		if ( empty( $this->widgets ) || ! is_array( $this->widgets ) || ! class_exists( '\Bricks\Elements' ) ) {
			Logger::log( sprintf( '%s: No widgets to initialize', __METHOD__ ) );
			return;
		}
		if ( ! class_exists( '\Automatic_CSS\Model\Database_Settings' ) ) {
			Logger::log( sprintf( '%s: Automatic CSS database class not found', __METHOD__ ) );
			return;
		}
		$acss_database = \Automatic_CSS\Model\Database_Settings::get_instance();
		foreach ( $this->widgets as $widget_name => $widget_options ) {
			$option = $widget_options['option'];
			$setting = $acss_database->get_var( $option );

			if ( ( null !== $setting && 'on' === $setting ) || ( array_key_exists( $widget_name, $this->default_widgets_states ) && 'off' !== $setting ) ) {
				if ( 'table-of-contents-v2' === $widget_name && ! defined( 'FRAMES_FLAG_ENABLE_NEW_TOC' ) ) {
					// Special case: set the 'FRAMES_FLAG_ENABLE_NEW_TOC' feature flag to true.
					Logger::log( sprintf( '%s: Enabling new Table of Contents', __METHOD__ ) );
					define( 'FRAMES_FLAG_ENABLE_NEW_TOC', true );
					continue;
				}
				$element_file = FRAMES_WIDGETS_DIR . "/{$widget_name}/{$widget_name}.php";
				if ( file_exists( $element_file ) ) {
					Logger::log( sprintf( '%s: Registering element %s', __METHOD__, $widget_name ) );
					\Bricks\Elements::register_element( $element_file );
				} else {
					Logger::log( sprintf( '%s: Element file %s does not exist', __METHOD__, $element_file ) );
				}
			}
		}
	}

	/**
	 * Helper functions to control the output of the widget.
	 *
	 * @param array  $settings The settings array.
	 * @param string $control The control name.
	 * @param string $outputTrue The output if the control is true.
	 * @param string $outputFalse The output if the control is false.
	 * @since 1.2.0
	 */
	public static function ifControlIsTrue( $settings, $control, $outputTrue, $outputFalse ) { // phpcs:ignore
		if ( isset( $settings[ str_replace( ' ', '', $control ) ] ) && true === $settings[ str_replace( ' ', '', $control ) ] ) {
			echo $outputTrue; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo $outputFalse; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	public static function ifControlIsEmpty( $settings, $control, $outputTrue, $outputFalse ) { // phpcs:ignore
		if ( isset( $settings[ str_replace( ' ', '', $control ) ] ) && '' === $settings[ str_replace( ' ', '', $control ) ] ) {
			echo $outputTrue; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo $outputFalse; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Undocumented function
	 *
	 * @param array  $settings The settings array.
	 * @param string $control The control name.
	 * @return string
	 * @since 1.2.0
	 */
	public static function control( $settings, $control ) {
		return isset( $settings[ str_replace( ' ', '', $control ) ] ) ? wp_kses_post( $settings[ str_replace( ' ', '', $control ) ] ) : '';
	}

	/**
	 * Takes a string of HTML attributes and merges them with an array of attributes.
	 *
	 * @param string $render_attributes The root attributes.
	 * @param array  $new_attributes The new attributes.
	 * @return string
	 */
	public static function render_attributes( $render_attributes, $new_attributes = array() ) {
		// Logger::log( sprintf( '%s: attributes=%s', __METHOD__, print_r( $render_attributes, true ) ) ); // TODO: remove.
		$attributes = array();
		// STEP: parse the attributes in an array.
		preg_match_all( '/([\w-]+)\s*=\s*"([^"]*)"/', $render_attributes, $matches, PREG_SET_ORDER );
		foreach ( $matches as $match ) {
			$attributes[ $match[1] ] = explode( ' ', $match[2] );
		}
		// STEP: merge the new attributes.
		$attributes = array_merge_recursive( $attributes, $new_attributes );
		// Logger::log( sprintf( '%s: attributes=%s', __METHOD__, print_r( $attributes, true ) ) ); // TODO: remove.
		// STEP: build the new attributes string.
		$render_attributes = '';
		foreach ( $attributes as $attribute_key => $attribute_value ) {
			if ( is_array( $attribute_value ) ) {
				$attribute_value = implode( ' ', array_unique( $attribute_value ) );
			}
			$render_attributes .= sprintf( ' %s="%s"', esc_attr( $attribute_key ), esc_attr( $attribute_value ) );
		}
		// Logger::log( sprintf( '%s: render_attributes=%s', __METHOD__, $render_attributes ) ); // TODO: remove.
		return $render_attributes;
	}

	/**
	 * Check if we're loading the frontend or the builder iframe.
	 *
	 * @return boolean
	 */
	public static function is_bricks_frontend() {
		if ( ! function_exists( 'bricks_is_frontend' ) || ! function_exists( 'bricks_is_builder_iframe' ) ) {
			Logger::log( sprintf( '%s: Bricks functions not found', __METHOD__ ) );
			return false;
		}
		return bricks_is_frontend() || bricks_is_builder_iframe();
	}

	/**
	 * Check if we're loading the builder.
	 * Condition to be used to load scripts only in the builder.
	 * Using builder_is_iframe will block from accessing __VUE_APP__
	 *
	 * @return boolean
	 */
	public static function is_bricks_builder() {
		if ( ! function_exists( 'bricks_is_builder' ) ) {
			Logger::log( sprintf( '%s: bricks_is_builder function not found', __METHOD__ ) );
			return false;
		}
		return bricks_is_builder();
	}

}
