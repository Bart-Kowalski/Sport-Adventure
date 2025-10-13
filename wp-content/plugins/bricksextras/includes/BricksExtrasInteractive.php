<?php

namespace BricksExtras;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'BricksExtrasInteractive' ) ) {
	return;
}

class BricksExtrasInteractive {

		public static function init() {

				/* apply interactive effects to container element */
				$interactive_elements = [
						'container',
						'div',
						'block',
				];

				$native_elements = [
					// Layout
					'container',
					'section', // @since 1.5
					'block', // @since 1.5
					'div', // @since 1.5
		
					// Basic
					'heading',
					'text-basic', // @since 1.3.6
					'text',
					'button',
					'icon',
					'image',
					'video',
		
					// General
					'divider',
					'icon-box',
					'social-icons', // @since 1.4 (Label: Icon List)
					'list',
					'accordion',
					'accordion-nested',
					'tabs',
					'tabs-nested', // @since 1.5
					'form',
					'map',
					'alert',
					'animated-typing',
					'countdown',
					'counter',
					'pricing-tables',
					'progress-bar',
					'pie-chart',
					'team-members',
					'testimonials',
					'html',
					'code',
					'template',
					'logo',
					'facebook-page',
		
					// Media
					'image-gallery',
					'audio',
					'carousel',
					'slider',
					'slider-nested',
					'svg',
		
					// WordPress
					'wordpress',
					'posts',
					'pagination',
					'nav-menu',
					'sidebar',
					'search',
					'shortcode',
		
					// Single
					'post-title',
					'post-excerpt',
					'post-meta',
					'post-content',
					'post-sharing',
					'related-posts',
					'post-author',
					'post-comments',
					'post-taxonomy',
					'post-navigation',
				];

				foreach ( $native_elements as $native_element ) {
						add_filter( "bricks/elements/$native_element/control_groups", array( __CLASS__, 'interactive_effects_group' ) );
						add_filter( "bricks/elements/$native_element/controls", array( __CLASS__, 'interactive_effects_controls' ) );
				}

				foreach ( $native_elements as $native_element ) {
					add_filter( "bricks/elements/$native_element/control_groups", array( __CLASS__, 'tooltip_group' ) );
					add_filter( "bricks/elements/$native_element/controls", array( __CLASS__, 'tooltip_controls' ) );
			}

				$current_theme = wp_get_theme();

				if ( $current_theme->exists() && $current_theme->parent() ) { // if child theme is active
					$parent_theme = $current_theme->parent();

					if ( ! empty( $parent_theme ) ) $parent_version = $parent_theme->Version;

					// if the parent theme version is 1.4.0.2 or 1.5-beta
					if ( $parent_version === '1.4.0.2' || $parent_version === '1.5-beta' ) {
						add_filter( 'bricks/element/render_attributes', array( __CLASS__, 'interactive_effects_attributes_old' ), 10, 4 );
					} else {
						add_filter( 'bricks/element/render_attributes', array( __CLASS__, 'render_attributes' ), 10, 3 );
					}
				} elseif ( ! empty( $current_theme ) ) { // if Bricks is active
					$current_version = $current_theme->Version;
					if ( $current_version === '1.4.0.2' || $current_version === '1.5-beta' ) {
						add_filter( 'bricks/element/render_attributes', array( __CLASS__, 'interactive_effects_attributes_old' ), 10, 4 );
					} else {
						add_filter( 'bricks/element/render_attributes', array( __CLASS__, 'render_attributes' ), 10, 3 );
					}
				}

				// add_filter( 'bricks/element/render_attributes', array( __CLASS__, 'interactive_effects_attributes' ), 10, 3 );

		}

		/* Interactive group added to container elements */

		public static function interactive_effects_group( $control_groups ) {
				$control_groups['x_interactive'] = [
						'tab'      => 'style', // or 'style'
						'title'    => esc_html__( 'Interactive', 'bricksextras' ),
				];
				return $control_groups;
		}

		public static function tooltip_group( $control_groups ) {
			$control_groups['x_tooltip_group'] = [
					'tab'      => 'style', // or 'style'
					'title'    => esc_html__( 'Tooltip', 'bricksextras' ),
			];
			return $control_groups;
	}

		
		public static function interactive_effects_controls( $controls ) {

				$controls['x_interactive_sep'] = [
					'tab'   => 'content',
					'group'	=> 'x_interactive',
					'type'  => 'info',
					'description' => esc_html__( 'Element interactions are not visible when editing in the builder, view on the front end after applying.', 'bricksextras' ),
			];



				/* Parallax effect controls */

				$controls['x_parallax'] = [
						'tab'      => 'content',
						'group'    => 'x_interactive',
						'label'    => esc_html__( 'Parallax scroll effect', 'bricksextras' ),
						'type'     => 'checkbox'
				];

				$controls['x_parallax_content'] = [
					'tab'   => 'content',
					'group'	=> 'x_interactive',
					'type'  => 'separator',
					'label' => esc_html__( 'Scroll speeds', 'bricks' ),
					'required' => ['x_parallax', '=', true],
						'description' => esc_html__('A negative value will make it move slower than regular scrolling, and a positive value will make it move faster. We recommend keeping the speed between -5 and +5', 'bricks')
				];

				$controls['scrollSpeedDefault'] = [
					'tab' => 'content',
					'label' => esc_html__( 'Default (all devices)', 'bricks' ),
					'group'    => 'x_interactive',
					'type' => 'number',
					'min' => -10,
					'max' => 10,
					'inline' => true,
					'required' => ['x_parallax', '=', true],
				];

				$controls['scrollSpeedDesktop'] = [
						'tab' => 'content',
						'label' => esc_html__( 'Desktop ( >= 992px )', 'bricks' ),
						'group'    => 'x_interactive',
						'type' => 'number',
						'min' => -10,
						'max' => 10,
						'inline' => true,
						'required' => ['x_parallax', '=', true],
					];

					$controls['scrollSpeedTablet'] = [
						'tab' => 'content',
						'label' => esc_html__( 'Tablet (768px - 991px)', 'bricks' ),
						'group'    => 'x_interactive',
						'type' => 'number',
						'min' => -10,
						'max' => 10,
						'inline' => true,
						'required' => ['x_parallax', '=', true],
					];

					$controls['scrollSpeedMobile'] = [
						'tab' => 'content',
						'label' => esc_html__( 'Mobile landscape (479px - 767px)', 'bricks' ),
						'group'    => 'x_interactive',
						'type' => 'number',
						'min' => -10,
						'max' => 10,
						'inline' => true,
						'required' => ['x_parallax', '=', true],
					];

					$controls['scrollSpeedMobilePortrait'] = [
						'tab' => 'content',
						'label' => esc_html__( 'Mobile portrait ( <= 478px)', 'bricks' ),
						'group'    => 'x_interactive',
						'type' => 'number',
						'min' => -10,
						'max' => 10,
						'inline' => true,
						'placeholder' => esc_html__( '0', 'bricks' ),
						'required' => ['x_parallax', '=', true],
					];

					$controls['x_parallax_sep'] = [
						'tab'   => 'content',
						'group'	=> 'x_interactive',
						'type'  => 'separator',
					];


				/* Floating effect controls */

				$controls['x_floating'] = [
						'tab'      => 'content',
						'group'    => 'x_interactive',
						'label'    => esc_html__( 'Floating effect', 'bricksextras' ),
						'type'     => 'checkbox'
				];


				$controls['x_floating_direction'] = [
						'tab'         => 'content',
						'label'       => esc_html__( 'Floating direction', 'bricks' ),
						'group'    => 'x_interactive',
						'type'        => 'select',
						'options'     => [
							'horizontal' => esc_html__( 'Horizontal', 'bricks' ),
							'vertical' => esc_html__( 'Vertical', 'bricks' ),
						],
						'inline'      => true,
						'placeholder'   => esc_html__( 'vertical', 'bricks' ),
						'required' => ['x_floating', '=', true],
					];

					$controls['x_floating_duration'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Duration (ms)', 'extras' ),
						'group'    => 'x_interactive',
						'type' => 'text',
						'hasDynamicData' => false,
						'inline'  => true,
			'css' => [
								[
									'property' => '--x-floating-duration',
								],
							],
							'placeholder' => '6000ms',
			'required' => ['x_floating', '=', true],
			];

					$controls['x_floating_distance'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Distance', 'extras' ),
						'group'    => 'x_interactive',
						'inline'      => true,
			'type' => 'number',
						'css' => [
								[
									'property' => '--x-floating-distance',
								],
							],
			'units' => [
								'px' => [
									'min' => 1,
									'max' => 200,
									'step' => 1,
								],
							],
			'required' => ['x_floating', '=', true],
						'placeholder' => '-20px',
						
			];

					$controls['x_floating_delay'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Delay (ms)', 'extras' ),
						'inline'      => true,
						'css' => [
								[
									'property' => '--x-floating-delay',
								],
							],
						'group'    => 'x_interactive',
			'type' => 'text',
						'hasDynamicData' => false,
						'placeholder' => '0ms',
			'required' => ['x_floating', '=', true],
			];

			$controls['x_floating_sep'] = [
				'tab'   => 'content',
				'group'	=> 'x_interactive',
				'type'  => 'separator',
			];


				/* Tilt effect controls */

				$controls['x_tilt'] = [
						'tab'      => 'content',
						'group'    => 'x_interactive',
						'label'    => esc_html__( 'Tilt hover effect', 'bricksextras' ),
						'type'     => 'checkbox'
				];


				$controls['x_tilt_max'] = [
					'tab' => 'content',
					'label' => esc_html__( 'Max rotation (deg)', 'bricks' ),
					'group'    => 'x_interactive',
					'type' => 'number',
					'inline' => true,
					'required' => ['x_tilt', '=', true],
					'placeholder' => '35'
				];

				$controls['x_tilt_start_x'] = [
					'tab' => 'content',
					'label' => esc_html__( 'Start rotate X (deg)', 'bricks' ),
					'group'    => 'x_interactive',
					'type' => 'number',
					'inline' => true,
					'required' => ['x_tilt', '=', true],
					'placeholder' => '0'
				];

				$controls['x_tilt_start_y'] = [
					'tab' => 'content',
					'label' => esc_html__( 'Start rotate Y (deg)', 'bricks' ),
					'group'    => 'x_interactive',
					'type' => 'number',
					'inline' => true,
					'required' => ['x_tilt', '=', true],
					'placeholder' => '0'
				];

				$controls['x_tilt_scale'] = [
					'tab' => 'content',
					'label' => esc_html__( 'Scale', 'bricks' ),
					'group'    => 'x_interactive',
					'type' => 'number',
					'inline' => true,
					'required' => ['x_tilt', '=', true],
					'placeholder' => '1'
				];

				$controls['x_tilt_speed'] = [
					'tab' => 'content',
					'label' => esc_html__( 'Speed (ms)', 'bricks' ),
					'group'    => 'x_interactive',
					'type' => 'number',
					'inline' => true,
					'required' => ['x_tilt', '=', true],
					'placeholder' => '300'
				];

				$controls['x_tilt_perspective'] = [
					'tab' => 'content',
					'label' => esc_html__( 'Perspective', 'bricks' ),
					'group'    => 'x_interactive',
					'type' => 'number',
					'inline' => true,
					'required' => ['x_tilt', '=', true],
					'placeholder' => '1000'
				];


				$controls['x_tilt_glare'] = [
					'tab'      => 'content',
					'group'    => 'x_interactive',
					'label'    => esc_html__( 'Add glare', 'bricksextras' ),
					'type'     => 'checkbox',
					'required' => ['x_tilt', '=', true],
			];

			$controls['x_tilt_max_glare'] = [
				'tab' => 'content',
				'label' => esc_html__( 'Max glare', 'bricks' ),
				'group'    => 'x_interactive',
				'type' => 'number',
				'inline' => true,
				'required' => ['x_tilt', '=', true],
				'placeholder' => '1'
			];

			$controls['x_tilt_color'] = [
				'tab' => 'content',
				'label' => esc_html__( 'Glare color', 'bricks' ),
				'group'    => 'x_interactive',
				'type' => 'color',
				'css'   => [
					[
						'property' => '--tilt-color',
						'selector' => '.js-tilt-glare-inner'
					],
					[
						'property' => 'background-image',
						'selector' => '.js-tilt-glare-inner',
						'value'    => 'linear-gradient(0deg, rgba(255, 255, 255, 0) 0%, var(--tilt-color) 100%)!important',
					],
				],
				'required' => ['x_tilt', '=', true],
			];

			$controls['x_tilt_breakpoint'] = [
				'tab' => 'content',
				'label' => esc_html__( 'Disable tilt effect at.. (device width px)', 'bricks' ),
				'group'    => 'x_interactive',
				'type' => 'number',
				'inline' => true,
				'required' => ['x_tilt', '=', true],
				
			];

			return $controls;
		}

		public static function tooltip_controls( $controls ) {

			/* Tooltip  controls */

			$controls['x_tooltips'] = [
				'tab'      => 'content',
				'group'    => 'x_tooltip_group',
				'label'    => esc_html__( 'Tooltip content', 'bricksextras' ),
				'type'     => 'checkbox'
			];

			$controls['x_tooltip_content'] = [
				'tab'      => 'content',
				'group'    => 'x_tooltip_group',
				'description'    => esc_html__( 'For use with the popover/tooltip element (enable Dynamic tooltip text)', 'bricksextras' ),
				'type' => 'textarea',
				// 'readonly' => true, // Default: false
				'rows' => 10, // Default: 5
				'spellcheck' => true, // Default: false
				'inlineEditing' => true,
				'required' => ['x_tooltips', '=', true],
			];

		
			return $controls;
			
		}

		public static function render_attributes( $attributes, $key, $element ) {

			if ( bricks_is_frontend() ) {

				if ( isset( $element->settings['x_parallax'] ) && $element->settings['x_parallax'] == true ) {
						
						wp_enqueue_script( 'x-parallax', BRICKSEXTRAS_URL . 'components/assets/js/parallax.js', '', '1.0.0', true );

						if ( isset ( $element->settings['scrollSpeedDefault'] ) ) {
								$attributes[ $key ]['data-rellax-speed'] = esc_attr( $element->settings['scrollSpeedDefault'] );
						} else {
								$attributes[ $key ]['data-rellax-speed'] = '0';
						}

						if ( isset( $element->settings['scrollSpeedDesktop'] ) ) {
								$attributes[ $key ]['data-rellax-desktop-speed'] = esc_attr( $element->settings['scrollSpeedDesktop'] );
						} 

						if ( isset ( $element->settings['scrollSpeedTablet'] ) ) {
								$attributes[ $key ]['data-rellax-tablet-speed'] = esc_attr( $element->settings['scrollSpeedTablet'] );
						}
		
						if ( isset ( $element->settings['scrollSpeedMobile'] ) ) {
								$attributes[ $key ]['data-rellax-mobile-speed'] = esc_attr( $element->settings['scrollSpeedMobile'] );
						}
		
						if ( isset ( $element->settings['scrollSpeedMobilePortrait'] ) ) {
								$attributes[ $key ]['data-rellax-xs-speed'] = esc_attr( $element->settings['scrollSpeedMobilePortrait'] );
						} else {
								$attributes[ $key ]['data-rellax-xs-speed'] = '0';
						}
						
				}

				if ( isset( $element->settings['x_floating'] ) && $element->settings['x_floating'] == true  ) {

						wp_enqueue_style( 'x-floating', BRICKSEXTRAS_URL . 'components/assets/css/floating.css', [], '1.0.0' );

						$attributes[ $key ]['data-x-floating'] = '';

						if ( isset( $element->settings['x_floating_direction'] ) && 'horizontal' === $element->settings['x_floating_direction'] ) {
								$attributes[ $key ]['data-x-floating'] = 'horizontal';
						} 

				}

				if ( isset( $element->settings['x_tilt'] ) && $element->settings['x_tilt'] == true ) {

					wp_enqueue_script( 'x-tilt', BRICKSEXTRAS_URL . 'components/assets/js/' . \BricksExtras\Helpers::maybeMinifyScripts('tilt') . '.js', '', '1.0.1', true );

					
					$x_tilt_config = [];

					$x_tilt_config['config'] = [];

					if ( isset( $element->settings['x_tilt_max'] ) ) {
						$x_tilt_config['config'] += [ "max" => intval( $element->settings['x_tilt_max'] ) ];
					}

					if ( isset( $element->settings['x_tilt_scale'] ) ) {
						$x_tilt_config['config'] += [ "scale" => floatval( $element->settings['x_tilt_scale'] ) ];
					}

					if ( isset( $element->settings['x_tilt_start_x'] ) ) {
						$x_tilt_config['config'] += [ "startX" => intval( $element->settings['x_tilt_start_x'] ) ];
					}

					if ( isset( $element->settings['x_tilt_start_y'] ) ) {
						$x_tilt_config['config'] += [ "startY" => intval( $element->settings['x_tilt_start_y'] ) ];
					}

					if ( isset( $element->settings['x_tilt_speed'] ) ) {
						$x_tilt_config['config'] += [ "speed" => intval( $element->settings['x_tilt_speed'] ) ];
					}

					if ( isset( $element->settings['x_tilt_perspective'] ) ) {
						$x_tilt_config['config'] += [ "perspective" => intval( $element->settings['x_tilt_perspective'] ) ];
					}

					if ( isset( $element->settings['x_tilt_glare'] ) ) {

						$x_tilt_config['config'] += [ 
							"glare" => true,
							"max-glare" => isset( $element->settings['x_tilt_max_glare'] ) ? floatval( $element->settings['x_tilt_max_glare'] ) : '1'
						];

					}

					if ( isset( $element->settings['x_tilt_breakpoint'] ) ) {
						$x_tilt_config['breakpoint'] = intval( $element->settings['x_tilt_breakpoint'] );
					}
					

					$attributes[ $key ]['data-x-tilt'] = wp_json_encode( $x_tilt_config );

				}

				if ( isset( $element->settings['x_tooltips'] ) && $element->settings['x_tooltips'] == true ) {

					if ( !empty( $element->settings['x_tooltip_content'] ) ) {
						$attributes[ $key ]['data-tippy-content'] = esc_attr__( $element->settings['x_tooltip_content'] );
					}

				}

			}
				
			return $attributes;
				
		}




		/* legacy function older versions of bricks */
		public static function interactive_effects_attributes_old( $attributes, $key, $settings, $name ) {

			if ( bricks_is_frontend() ) {
	  
			  if ( isset( $settings['x_parallax'] ) && $settings['x_parallax'] == true ) {
				  
				  wp_enqueue_script( 'x-parallax', BRICKSEXTRAS_URL . 'components/assets/js/parallax.js', '', '1.0.0', true );
	  
				  if ( isset ( $settings['scrollSpeedDefault'] ) ) {
					  $attributes[ $key ]['data-rellax-speed'] = esc_attr( $settings['scrollSpeedDefault'] );
				  } else {
					  $attributes[ $key ]['data-rellax-speed'] = '0';
				  }
	  
				  if ( isset( $settings['scrollSpeedDesktop'] ) ) {
					  $attributes[ $key ]['data-rellax-desktop-speed'] = esc_attr( $settings['scrollSpeedDesktop'] );
				  } 
	  
				  if ( isset ( $settings['scrollSpeedTablet'] ) ) {
					  $attributes[ $key ]['data-rellax-tablet-speed'] = esc_attr( $settings['scrollSpeedTablet'] );
				  }
		  
				  if ( isset ( $settings['scrollSpeedMobile'] ) ) {
					  $attributes[ $key ]['data-rellax-mobile-speed'] = esc_attr( $settings['scrollSpeedMobile'] );
				  }
		  
				  if ( isset ( $settings['scrollSpeedMobilePortrait'] ) ) {
					  $attributes[ $key ]['data-rellax-xs-speed'] = esc_attr( $settings['scrollSpeedMobilePortrait'] );
				  } else {
					  $attributes[ $key ]['data-rellax-xs-speed'] = '0';
				  }
				  
			  }
	  
			  if ( isset( $settings['x_floating'] ) && $settings['x_floating'] == true  ) {
	  
				  wp_enqueue_style( 'x-floating', BRICKSEXTRAS_URL . 'components/assets/css/floating.css', [], '1.0.0' );
	  
				  $attributes[ $key ]['data-x-floating'] = '';
	  
				  if ( isset( $settings['x_floating_direction'] ) && 'horizontal' === $settings['x_floating_direction'] ) {
					  $attributes[ $key ]['data-x-floating'] = 'horizontal';
				  } 
	  
			  }
	  
			  if ( isset( $settings['x_tilt'] ) && $settings['x_tilt'] == true ) {
	  
				wp_enqueue_script( 'x-tilt', BRICKSEXTRAS_URL . 'components/assets/js/tilt.min.js', '', '1.0.0', true );
	  
				
				$x_tilt_config = [];
	  
				$x_tilt_config['config'] = [];
	  
				if ( isset( $settings['x_tilt_max'] ) ) {
				  $x_tilt_config['config'] += [ "max" => intval( $settings['x_tilt_max'] ) ];
				}
	  
				if ( isset( $settings['x_tilt_scale'] ) ) {
				  $x_tilt_config['config'] += [ "scale" => intval( $settings['x_tilt_scale'] ) ];
				}
	  
				if ( isset( $settings['x_tilt_start_x'] ) ) {
				  $x_tilt_config['config'] += [ "startX" => intval( $settings['x_tilt_start_x'] ) ];
				}
	  
				if ( isset( $settings['x_tilt_start_y'] ) ) {
				  $x_tilt_config['config'] += [ "startY" => intval( $settings['x_tilt_start_y'] ) ];
				}
	  
				if ( isset( $settings['x_tilt_speed'] ) ) {
				  $x_tilt_config['config'] += [ "speed" => intval( $settings['x_tilt_speed'] ) ];
				}
	  
				if ( isset( $settings['x_tilt_perspective'] ) ) {
				  $x_tilt_config['config'] += [ "perspective" => intval( $settings['x_tilt_perspective'] ) ];
				}
	  
				if ( isset( $settings['x_tilt_scale'] ) && $settings['x_tilt_glare'] == true ) {
	  
				  $x_tilt_config['config'] += [ 
					"glare" => true,
					"max-glare" => isset( $settings['x_tilt_scale'] ) ? floatval( $settings['x_tilt_max_glare'] ) : '1'
				  ];
	  
				}
	  
				if ( isset( $settings['x_tilt_breakpoint'] ) ) {
				  $x_tilt_config['breakpoint'] = intval( $settings['x_tilt_breakpoint'] );
				}
				
	  
				$attributes[ $key ]['data-x-tilt'] = wp_json_encode( $x_tilt_config );
	  
			  }
	  
			}
	  
			  
			return $attributes;
			  
		  }
		
		

}