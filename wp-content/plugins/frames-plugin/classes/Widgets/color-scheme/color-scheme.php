<?php
/**
 * Color Scheme Widget.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Widgets\Frames_Color_Scheme;

use Automatic_CSS\Model\Database_Settings;
use \Frames_Client\Widget_Manager;

/**
 * Color Scheme class.
 */
class Fr_Color_Scheme_Widget extends \Bricks\Element {


	/**
	 * Widget category.
	 *
	 * @var string
	 */
	public $category = 'Frames';

	/**
	 * Widget name.
	 *
	 * @var string
	 */
	public $name = 'fr-color-scheme';

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	public $icon = 'fas fa-adjust';

	/**
	 * Widget scripts.
	 *
	 * @var string
	 */
	public $scripts = array( 'color_scheme_script' );

	/**
	 * Is widget nestable?
	 *
	 * @var string
	 */
	public $nestable = false;

	/**
	 * Get the widget label.
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Frames Color Scheme', 'frames' );
	}

	/**
	 * Enqueue Scripts and Styles for the widget
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function enqueue_scripts() {
		 $filename = 'color-scheme';
		wp_enqueue_style(
			"frames-{$filename}",
			FRAMES_WIDGETS_URL . "/{$filename}/css/{$filename}.css",
			array(),
			filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/css/{$filename}.css" )
		);
		wp_enqueue_script(
			"frames-{$filename}",
			FRAMES_WIDGETS_URL . "/{$filename}/js/{$filename}.js",
			array(),
			filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/js/{$filename}.js" ),
			true
		);
	}

	/**
	 * Get the post types.
	 *
	 * @return string[]|WP_Post_Type[]
	 */
	public function getAllCpts() {
		$args = array(
			'public'   => true,
			'_builtin' => false
		);

		$output = 'names'; // names or objects, note names is the default.
		$operator = 'and'; // 'and' or 'or'.

		$post_types = get_post_types( $args, $output, $operator );

		return $post_types;
	}




	/**
	 * Register widget control groups.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function set_control_groups() {
		// empty for now.
	}


	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function set_controls() {
		$this->controls['mode'] = array(
			'label'   => esc_html__( 'Mode', 'frames' ),
			'type'    => 'select',
			'options' => array(
				'simple'     => esc_html__( 'Simple', 'frames' ),
				'toggle'     => esc_html__( 'Toggle', 'frames' ),
			),
			'inline'  => true,
			'default' => 'toggle',
		);

		// Toggle.

		$this->controls['typeSeparatorToggle'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Toggle', 'bricks' ),
			'type' => 'separator',
			'required' => array( 'mode', '=', 'toggle' )
		);

		$this->controls['labelType'] = array(
			'label'   => esc_html__( 'Use', 'frames' ),
			'type'    => 'select',
			'options' => array(
				'icons'     => esc_html__( 'Icons', 'frames' ),
				'text' => esc_html__( 'Text Labels', 'frames' ),
			),
			'inline'  => true,
			'default' => 'icons',
			'required' => array( 'mode', '=', 'toggle' )
		);

		$this->controls['toggleIconPlacement'] = array(
			'label'   => esc_html__( 'Icons Placement', 'frames' ),
			'type'    => 'select',
			'options' => array(
				'inside'     => esc_html__( 'Inside', 'frames' ),
				'outside' => esc_html__( 'Outside', 'frames' ),
			),
			'inline'  => true,
			'default' => 'inside',
			'required' => array(
				array( 'mode', '=', 'toggle' ),
				array( 'labelType', '=', 'icons' )
			)
		);

		$this->controls['toggleWidth'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Width', 'bricks' ),
			'type' => 'number',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-toggle-width',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'toggle' )
		);

		$this->controls['toggleSpacer'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Offset', 'bricks' ),
			'type' => 'number',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-toggle-spacer',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'toggle' )
		);

		$this->controls['toggleRoundness'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Roundness', 'bricks' ),
			'type' => 'number',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-toggle-roundness',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'toggle' )
		);

		$this->controls['toggleTypography'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Typography', 'bricks' ),
			'type' => 'typography',
			'css' => array(
				array(
					'property' => 'font',
					'selector' => '.fr-color-scheme__text-label'
				)
			),
			'required' => array( 'mode', '=', 'toggle' )
		);

		$this->controls['mainSeparatorToggle'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Main', 'bricks' ),
			'type' => 'separator',
			'required' => array( 'mode', '=', 'toggle' )
		);

		// Toggle Main.

		$this->controls['mainIcon'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Main Icon', 'bricks' ),
			'type' => 'icon',
			'default'  => array(
				'library' => 'fontawesomeSolid',
				'icon'    => 'fas fa-moon',
			),
			'css' => array(
				array(
					'selector' => '.icon-svg', // Use to target SVG file.
				),
			),
			'required' => array(
				array( 'mode', '=', 'toggle' ),
				array( 'labelType', '=', 'icons' )
			)
		);

		$this->controls['toggleMainIconColor'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Icon Color', 'bricks' ),
			'type' => 'color',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-toggle-icon-color',
					'selector' => ''
				)
			),
			'required' => array(
				array( 'mode', '=', 'toggle' ),
				array( 'labelType', '=', 'icons' )
			)
		);

		$this->controls['toggleMainText'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Label', 'bricks' ),
			'type' => 'text',
			'required' => array(
				array( 'mode', '=', 'toggle' ),
				array( 'labelType', '=', 'text' )
			),
			'inline' => true,
		);

		$this->controls['toggleMainBackgroundColor'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Background Color', 'bricks' ),
			'type' => 'color',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-toggle-background-color',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'toggle' )
		);

		$this->controls['toggleMainBallColor'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Ball Color', 'bricks' ),
			'type' => 'color',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-toggle-ball-color',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'toggle' )
		);

		// Toggle Alt.

		$this->controls['altSeparatorToggle'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Alt', 'bricks' ),
			'type' => 'separator',
			'required' => array( 'mode', '=', 'toggle' )
		);

		$this->controls['altIcon'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Alt Icon', 'bricks' ),
			'type' => 'icon',
			'default'  => array(
				'library' => 'fontawesomeSolid',
				'icon'    => 'fas fa-sun',
			),
			'css' => array(
				array(
					'selector' => '.icon-svg', // Use to target SVG file.
				),
			),
			'required' => array(
				array( 'mode', '=', 'toggle' ),
				array( 'labelType', '=', 'icons' )
			)
		);

		$this->controls['toggleMainIconColorAlt'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Icon Color', 'bricks' ),
			'type' => 'color',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-toggle-icon-color-alt',
					'selector' => ''
				)
			),
			'required' => array(
				array( 'mode', '=', 'toggle' ),
				array( 'labelType', '=', 'icons' )
			)
		);

		$this->controls['toggleAltText'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Label', 'bricks' ),
			'type' => 'text',
			'required' => array(
				array( 'mode', '=', 'toggle' ),
				array( 'labelType', '=', 'text' )
			),
			'inline' => true,
		);

		$this->controls['toggleAltTextColor'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Labels Color', 'bricks' ),
			'type' => 'color',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-text-label-color-alt',
					'selector' => ''
				)
			),
			'required' => array(
				array( 'mode', '=', 'toggle' ),
				array( 'labelType', '=', 'text' )
			),
		);

		$this->controls['toggleMainBackgroundColorAlt'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Background Color', 'bricks' ),
			'type' => 'color',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-toggle-background-color-alt',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'toggle' )
		);

		$this->controls['toggleMainBallColorAlt'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Ball Color', 'bricks' ),
			'type' => 'color',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-toggle-ball-color-alt',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'toggle' )
		);

		// Simple.

		$this->controls['typeSeparatorSimple'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Simple Icon', 'bricks' ),
			'type' => 'separator',
			'required' => array( 'mode', '=', 'simple' )
		);

		$this->controls['simpleWidth'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Width', 'bricks' ),
			'type' => 'number',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-simple-size',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'simple' )
		);

		$this->controls['simplePadding'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Padding', 'bricks' ),
			'type' => 'dimensions',
			'css' => array(
				array(
					'property' => 'padding',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'simple' )
		);

		$this->controls['simpleBorder'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Border', 'bricks' ),
			'type' => 'border',
			'css' => array(
				array(
					'property' => 'border',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'simple' )
		);

		$this->controls['mainSeparator'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Main', 'bricks' ),
			'type' => 'separator',
			'required' => array( 'mode', '=', 'simple' )
		);

		$this->controls['mainIconSimple'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Icon', 'bricks' ),
			'type' => 'svg',
			'info' => esc_html__( 'Support for ONLY svg files', 'bricks' ),
			'required' => array( 'mode', '=', 'simple' )
		);

		$this->controls['simpleBackgroundColorMain'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Background Color', 'bricks' ),
			'type' => 'color',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-simple-icon-background-color',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'simple' )
		);

		$this->controls['mainIconColorSimple'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Main Icon Color', 'bricks' ),
			'type' => 'color',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-simple-icon-color',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'simple' )
		);

		$this->controls['altSeparator'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Alt', 'bricks' ),
			'type' => 'separator',
			'required' => array( 'mode', '=', 'simple' )
		);

		$this->controls['altIconSimple'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Icon', 'bricks' ),
			'info' => esc_html__( 'Support for ONLY svg files', 'bricks' ),
			'type' => 'svg',
			'required' => array( 'mode', '=', 'simple' )
		);

		$this->controls['simpleBackgroundColorAlt'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Background Color', 'bricks' ),
			'type' => 'color',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-simple-icon-background-color-alt',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'simple' )
		);

		$this->controls['altIconColorSimple'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Alt Icon Color', 'bricks' ),
			'type' => 'color',
			'css' => array(
				array(
					'property' => '--fr-color-scheme-simple-icon-color-alt',
					'selector' => ''
				)
			),
			'required' => array( 'mode', '=', 'simple' )
		);

		// Toggle Accessibility.

		$this->controls['accessibilitySeparatorToggle'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Accesibility', 'bricks' ),
			'type' => 'separator'
		);

		$this->controls['ariaLabel'] = array(
			'tab' => 'content',
			'label' => __( 'Accessible Description', 'frames' ),
			'type' => 'text',
			'default' => 'Switch color scheme',
			'inlineEditing' => true
		);
	}

	/**
	 * Get all posts from Bricks Template.
	 *
	 * @return array
	 */
	public function getAllPostsFromBricksTemplate() {
		$args = array(
			'post_type' => 'bricks_template',
			'post_status' => 'publish',
			'posts_per_page' => -1, // This will retrieve all posts.
		);

		$query = new \WP_Query( $args );

		$posts = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$posts[] = get_post();
			}
			wp_reset_postdata(); // Reset the global post object after the custom query.
		}

		return $posts;
	}


	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function render() {
		$model = Database_Settings::get_instance();
		$settings = $this->settings;

		$mode = ! empty( $settings['mode'] ) ? $settings['mode'] : 'simple';
		$toggle_icon_placement = ! empty( $settings['toggleIconPlacement'] ) ? $settings['toggleIconPlacement'] : 'inside';
		$label_type = ! empty( $settings['labelType'] ) ? $settings['labelType'] : 'icons';

		$main_text = ! empty( $settings['toggleMainText'] ) ? $settings['toggleMainText'] : '';
		$alt_text = ! empty( $settings['toggleAltText'] ) ? $settings['toggleAltText'] : '';

		$main_icon = ! empty( $this->settings['mainIcon'] ) ? self::render_icon( $this->settings['mainIcon'] ) : false;
		$alt_icon = ! empty( $this->settings['altIcon'] ) ? self::render_icon( $this->settings['altIcon'] ) : false;
		$ariaLabel = ! empty( $settings['ariaLabel'] ) ? $settings['ariaLabel'] : $this->controls['ariaLabel']['default'];

		// if (isset($this->settings['mainIconSimple']['url'])) {
		// echo file_get_contents(esc_url($this->settings['mainIconSimple']['url']));
		// } else {
		// esc_html_e('No SVG selected.', 'bricks');
		// } // .

		$simpleMainSvg = ! empty( $this->settings['mainIconSimple']['url'] ) ? file_get_contents( esc_url( $this->settings['mainIconSimple']['url'] ) ) : false;
		$simpleAltSvg = ! empty( $this->settings['altIconSimple']['url'] ) ? file_get_contents( esc_url( $this->settings['altIconSimple']['url'] ) ) : false;
		$tag = 'simple' === $mode ? 'button' : 'div';

		if ( $simpleMainSvg ) {
			preg_match( '/<path[^>]+d="([^"]+)"/', $simpleMainSvg, $matches );
			isset( $matches[1] ) ? $simpleMainSvg = $matches[1] : $simpleMainSvg = false;
		}

		if ( $simpleAltSvg ) {
			preg_match( '/<path[^>]+d="([^"]+)"/', $simpleAltSvg, $matches );
			isset( $matches[1] ) ? $simpleAltSvg = $matches[1] : $simpleAltSvg = false;
		}

		$options = array(
			'mode' => $mode,
			'mainIconPath' => false,
			'altIconPath' => false,
			'labelType' => $label_type,
			'websiteScheme' => $model->get_var( 'website-color-scheme' ),
		);

		if ( 'simple' === $mode ) {
			$filename = 'color-scheme';
			wp_enqueue_script(
				"frames-{$filename}-flubber",
				FRAMES_WIDGETS_URL . "/{$filename}/js/flubber.min.js",
				array(),
				filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/js/flubber.min.js" ),
				true
			);
		}

		if ( $simpleMainSvg ) {
			$options['mainIconPath'] = $simpleMainSvg;
		}

		if ( $simpleAltSvg ) {
			$options['altIconPath'] = $simpleAltSvg;
		}

		if ( is_array( $options ) ) {
			$options = wp_json_encode( $options );
		}

		$options = str_replace( array( "\r", "\n" ), '', $options );

		$this->set_attribute( '_root', 'class', 'fr-color-scheme' );

		$this->set_attribute( '_root', 'data-fr-color-scheme-options', trim( $options ) );

		if ( 'simple' === $mode ) {
			$this->set_attribute( '_root', 'class', 'fr-color-scheme--simple' );
			$this->set_attribute( '_root', 'data-acss-color-scheme', 'toggle' );
			$this->set_attribute( '_root', 'aria-label', $ariaLabel );
		} elseif ( 'toggle' === $mode ) {
			$this->set_attribute( '_root', 'class', 'fr-color-scheme--toggle' );
		}

		$output = '<' . $tag . ' ' . $this->render_attributes( '_root' ) . '>';
		// $output .= $this->render_attributes('_root') . '>';

		if ( 'simple' === $mode ) {
			$output .= '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"></svg>';
		}

		if ( 'toggle' === $mode ) {

			if ( 'outside' === $toggle_icon_placement || 'text' === $label_type ) {
				$output .= '<div class="fr-color-scheme__toggle-wrapper">';
				if ( 'icons' === $label_type ) {
					$output .= '<span class="fr-color-scheme__main-icon fr-color-scheme__icon">' . $main_icon . '</span>';
				} elseif ( 'text' === $label_type ) {
					$output .= '<span data-acss-color-scheme="main" class="fr-color-scheme__text-label-main fr-color-scheme__text-label">' . $main_text . '</span>';
				}
			}
			$output .= ' <input type="checkbox" class="fr-color-scheme__checkbox" id="checkbox" data-acss-color-scheme="toggle" aria-label="' . $ariaLabel . '">';

			$output .= '<label for="checkbox" class="fr-color-scheme__checkbox-label">';

			if ( 'inside' === $toggle_icon_placement ) {
				$output .= '<span class="fr-color-scheme__main-icon fr-color-scheme__icon">' . $main_icon . '</span>';
				$output .= '<span class="fr-color-scheme__alt-icon fr-color-scheme__icon">' . $alt_icon . '</span>';
			}

			$output .= '<span class="ball"></span>';
			$output .= '</label>';

			if ( 'outside' === $toggle_icon_placement || 'text' === $label_type ) {
				if ( 'icons' === $label_type ) {
					$output .= '<span class="fr-color-scheme__main-icon fr-color-scheme__icon">' . $alt_icon . '</span>';
				} elseif ( 'text' === $label_type ) {
					$output .= '<span data-acss-color-scheme="alt"  class="fr-color-scheme__text-label-alt fr-color-scheme__text-label">' . $alt_text . '</span>';
				}
				$output .= '</div>';
			}
		}

		$output .= '</' . $tag . '>';

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
}
