<?php
/**
 * Accordion Widget.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Widgets\Accordion;

use \Frames_Client\Widget_Manager;

/**
 * Accordion class.
 */
class Fr_Accordion_Widget extends \Bricks\Element {

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
	public $name = 'fr-accordion';

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	public $icon = 'fa fa-window-maximize';

	// public $css_selector = '.modal-wrapper'; // commented out.

	/**
	 * Widget scripts.
	 *
	 * @var string
	 */
	public $scripts = array( 'accordion_script' );

	/**
	 * Is widget nestable?
	 *
	 * @var string
	 */
	public $nestable = true;

	/**
	 * Get the widget label.
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Frames Accordion', 'frames' );
	}

	/**
	 * Enqueue Scripts and Styles for the widget
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function enqueue_scripts() {
		if ( ! Widget_Manager::is_bricks_frontend() ) {
			return;
		}
		$filename = 'accordion';
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
	 * Register control groups.
	 *
	 * @return void
	 */
	public function set_control_groups() {
		$this->control_groups['settings'] = array(
			'title' => esc_html__( 'Settings', 'frames' ),
			'tab' => 'content',
		);

		// head styling.
		$this->control_groups['head'] = array(
			'title' => esc_html__( 'Head Styling', 'frames' ),
			'tab' => 'content',
		);

		// body styling.
		$this->control_groups['body'] = array(
			'title' => esc_html__( 'Body Styling', 'frames' ),
			'tab' => 'content',
		);

	}

	/**
	 * Get dynamic breakpoints.
	 *
	 * @return array
	 */
	public function get_dynamic_breakpoints() {
		$breakpoints = array();
		foreach ( \Bricks\Breakpoints::$breakpoints as $breakpoint ) {
			$breakpoints[ $breakpoint['key'] ] = $breakpoint['label'];
		}
		return $breakpoints;
	}

	/**
	 * Register controls.
	 *
	 * @return void
	 */
	public function set_controls() {
		$this->controls['toggleAllInBuilder'] = array(
			'label'   => esc_html__( 'Toggle All in Builder', 'frames' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => true,
		);

		// info type control with label "default open values".

		$this->controls['defaultBehavior'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Default behavior', 'frames' ),
			'type' => 'separator',
			'group' => 'settings',
		);

		// First Item Opened.
		$this->controls['firstItemOpened'] = array(
			'label'   => esc_html__( 'First Item Opened', 'frames' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'group'   => 'settings',
		);

		// All Items Expanded.
		$this->controls['allItemsExpanded'] = array(
			'label'   => esc_html__( 'All Items Expanded', 'frames' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'group'   => 'settings',
		);

		// Expanded Class.
		$this->controls['expandedClass'] = array(
			'label'   => esc_html__( 'Expand specific items', 'frames' ),
			'type'    => 'checkbox',
			'group'   => 'settings',
			'inline'  => false,
		);

		// Not expanded item contains current page link .
		$this->controls['expandedCurrentLink'] = array(
			'label'   => esc_html__( "Don't expand items with current Page Link", 'frames' ),
			'type'    => 'checkbox',
			'group'   => 'settings',
			'inline'  => false,
		);

		$this->controls['expandedClassInfo'] = array(
			'tab' => 'content',
			'description' => esc_html__( 'Add class fr-accordion__item--expanded to items you want to expand by default', 'frames' ),
			'type' => 'info',
			'group' => 'settings',
			'required' => array( 'expandedClass', '=', true ),
		);

		// Scroll To Hash.
		$this->controls['scrollToHash'] = array(
			'label'   => esc_html__( 'Scroll To Hash', 'frames' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'group'   => 'settings',
		);

		$this->controls['scrollOffset'] = array(
			'label'   => esc_html__( 'Scroll Offset', 'frames' ),
			'type'    => 'number',
			'group'   => 'settings',
			'description'   => esc_html__( 'Offset in pixels from the top when scrolling', 'frames' ),
			'default' => 0,
			'required'    => array(
				array(
					'scrollToHash',
					'=',
					true
				),
			),
		);

		$this->controls['scrollToHeading'] = array(
			'label'   => esc_html__( 'Scroll To Heading', 'frames' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'group'   => 'settings',
			'default' => true,
			'info'      => esc_html__( 'On toggling a header', 'frames' ),
		);

		$this->controls['scrollToHeadingOn'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Turn on breakpoint', 'frames' ),
			'type'        => 'select',
			'group'   => 'settings',
			'options'     => $this->get_dynamic_breakpoints(),
			'default'     => 'mobile_portrait',
			'required'    => array(
				array(
					'scrollToHeading',
					'=',
					true
				),
			),
		);

		// Close Previous Item.
		$this->controls['closePreviousItem'] = array(
			'label'   => esc_html__( 'Close Previous Item', 'frames' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => true,
			'group'   => 'settings',
		);

		// separator for all items styling control.
		$this->controls['allItemsStyling'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'General styling', 'frames' ),
			'type' => 'separator',
			'group' => 'settings',
		);

		// Show Duration.
		$this->controls['showDuration'] = array(
			'label'       => esc_html__( 'Expand Duration', 'frames' ),
			'type'        => 'number',
			'placeholder' => 300,
			'default'     => 300,
			'group'   => 'settings',
		);

		// gap for class fr-accordion.
		$this->controls['itemGap'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Gap between items', 'frames' ),
			'type'        => 'number',
			'group'       => 'settings',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'gap',
					'selector' => ''
				),
			),
			'placeholder' => '0',
		);

		$this->controls['additionalSettings'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Additional Settings', 'frames' ),
			'type' => 'separator',
			'group' => 'settings',
		);

		// tag control with select with options: div, section, ul, ol.

		$this->controls['tag'] = array(
			'label'   => esc_html__( 'Tag', 'frames' ),
			'type'    => 'select',
			'options' => array(
				'div'     => esc_html__( 'div', 'frames' ),
				'section' => esc_html__( 'section', 'frames' ),
				'ul'      => esc_html__( 'ul', 'frames' ),
				'ol'      => esc_html__( 'ol', 'frames' ),
				'custom'  => esc_html__( 'custom', 'frames' ),
			),
			'inline'  => true,
			'default' => 'ul',
			'group'   => 'settings',
		);

		// customTag.

		$this->controls['customTag'] = array(
			'label'       => esc_html__( 'Custom Tag', 'frames' ),
			'type'        => 'text',
			'group'       => 'settings',
			'required'    => array( 'tag', '=', 'custom' ),
		);

		$this->controls['addFaqSchema'] = array(
			'label'   => esc_html__( 'Add Faq Schema', 'frames' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'group'   => 'settings',
		);

		$this->controls['headPadding'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Padding', 'frames' ),
			'type'        => 'spacing',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'padding',
					'selector' => '.fr-accordion__header'
				),
			),
		);

		// background color property with selector .fr-accordion__header for group head control.
		$this->controls['headBackgroundColor'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Background Color', 'frames' ),
			'type'        => 'color',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'background-color',
					'selector' => '.fr-accordion__header'
				),
			),
		);

		// type typography property font.
		$this->controls['headFont'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Typography', 'frames' ),
			'type'        => 'typography',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'font',
					'selector' => '.fr-accordion__title'
				),
			),
		);

		// type border property border.
		$this->controls['headBorder'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Border', 'frames' ),
			'type'        => 'border',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'border',
					'selector' => '.fr-accordion__header'
				),
			),
		);

		// separator for active header styling control.
		$this->controls['activeHeaderStyling'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Head - Active', 'frames' ),
			'type' => 'separator',
			'group' => 'head',
		);

		$this->controls['activeHeadBackgroundColor'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Background Color', 'frames' ),
			'type'        => 'color',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'background-color',
					'selector' => '.fr-accordion__header[aria-expanded="true"]'
				),
			),
		);

		// active border.
		$this->controls['activeHeadBorder'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Border', 'frames' ),
			'type'        => 'border',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'border',
					'selector' => '.fr-accordion__header[aria-expanded="true"]'
				),
			),
		);

		$this->controls['activeHeadFont'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Typography', 'frames' ),
			'type'        => 'typography',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'font',
					'selector' => '.fr-accordion__header[aria-expanded="true"] .fr-accordion__title'
				),
			),
		);

		// icon default styling separator.
		$this->controls['iconDefaultStyling'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Icon - Default', 'frames' ),
			'type' => 'separator',
			'group' => 'head',
		);

		// icon default color.
		$this->controls['iconDefaultColor'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Color', 'frames' ),
			'type'        => 'color',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'fill',
					'selector' => '.fr-accordion__icon'
				),
				array(
					'property' => 'color',
					'selector' => '.fr-accordion__icon'
				),
			),
		);

		// background color for icon wrapper.
		$this->controls['iconDefaultBackgroundColor'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Background Color', 'frames' ),
			'type'        => 'color',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'background-color',
					'selector' => '.fr-accordion__icon-wrapper'
				),
			),
		);

		// border for icon wrapper.
		$this->controls['iconDefaultBorder'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Border', 'frames' ),
			'type'        => 'border',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'border',
					'selector' => '.fr-accordion__icon-wrapper'
				),
			),
		);

		// iconSize type for icon type iconSize default var(--text-l).
		$this->controls['iconDefaultSize'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Size', 'frames' ),
			'type'        => 'number',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'font-size',
					'selector' => '.fr-accordion__icon'
				),
			),
		);

		// width for icon wrapper default 2rem.
		$this->controls['iconDefaultWidth'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Wrapper Size', 'frames' ),
			'type'        => 'number',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'min-width',
					'selector' => '.fr-accordion__icon-wrapper'
				),
			),
		);

		// active icon styling separator.
		$this->controls['iconActiveStyling'] = array(
			'tab' => 'content',
			'label' => esc_html__( 'Icon - Active', 'frames' ),
			'type' => 'separator',
			'group' => 'head',
		);

		// icon active color.
		$this->controls['iconActiveColor'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Color', 'frames' ),
			'type'        => 'color',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'color',
					'selector' => '.fr-accordion__header[aria-expanded="true"] .fr-accordion__icon'
				),
				array(
					'property' => 'fill',
					'selector' => '.fr-accordion__header[aria-expanded="true"] .fr-accordion__icon'
				),
			),
		);

		// background color for icon wrapper.
		$this->controls['iconActiveBackgroundColor'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Background Color', 'frames' ),
			'type'        => 'color',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'background-color',
					'selector' => '.fr-accordion__header[aria-expanded="true"] .fr-accordion__icon-wrapper'
				),
			),
		);

		$this->controls['iconActiveBorder'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Border', 'frames' ),
			'type'        => 'border',
			'group'       => 'head',
			'css'         => array(
				array(
					'property' => 'border',
					'selector' => '.fr-accordion__header[aria-expanded="true"] .fr-accordion__icon-wrapper'
				),
			),
		);

		$this->controls['iconActiveTransform'] = array(
			'tab'         => 'content',
			'group'       => 'head',
			'label'       => esc_html__( 'Transform', 'frames' ),
			'type'              => 'transform',
			'default'     => array(
				'rotateZ' => '180',
			),
			'css'         => array(
				array(
					'property' => 'transform',
					'selector' => '.fr-accordion__icon--flipped'
				),
			),
		);

		// padding for body for body settings default var(--space-m) selector: fr-accordion__content-wrapper.
		$this->controls['bodyPadding'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Padding', 'frames' ),
			'type'        => 'spacing',
			'group'       => 'body',
			'css'         => array(
				array(
					'property' => 'padding',
					'selector' => '.fr-accordion__content-wrapper'
				),
			),
		);

		// background color for body.
		$this->controls['bodyBackgroundColor'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Background Color', 'frames' ),
			'type'        => 'color',
			'group'       => 'body',
			'css'         => array(
				array(
					'property' => 'background-color',
					'selector' => '.fr-accordion__content-wrapper'
				),
			),
		);

		// border for body.
		$this->controls['bodyBorder'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Border', 'frames' ),
			'type'        => 'border',
			'group'       => 'body',
			'css'         => array(
				array(
					'property' => 'border',
					'selector' => '.fr-accordion__content-wrapper'
				),
			),
		);

		// typography for body.
		$this->controls['bodyTypography'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Typography', 'frames' ),
			'type'        => 'typography',
			'group'       => 'body',
			'css'         => array(
				array(
					'property' => 'typography',
					'selector' => '.fr-accordion__content-wrapper'
				),
			),
		);

	}

	/**
	 * Get nestable item.
	 *
	 * @return array
	 */
	public function get_nestable_item() {
		return array(
			'name'     => 'block',
			'label'    => esc_html__( 'Item', 'frames' ),
			'settings' => array(
				'tag' => 'li'
			),
			'children' => array(
				array(
					'name'     => 'div',
					'label'    => esc_html__( 'Header', 'frames' ),
					'settings' => array(
						'_hidden' => array(
							'_cssClasses' => 'fr-accordion__header',
						),
					),
					'children' => array(
						array(
							'name'     => 'heading',
							'label'    => esc_html__( 'Title', 'frames' ),
							'settings' => array(
								'tag' => 'h3',
								'text' => esc_html__( 'Accordion', 'frames' ) . ' {item_index}',
								'_hidden' => array(
									'_cssClasses' => 'fr-accordion__title',
								),
							),
						),
						array(
							'name'     => 'div',
							'label'    => esc_html__( 'Icon Wrapper', 'frames' ),
							'settings' => array(
								'_hidden' => array(
									'_cssClasses' => 'fr-accordion__icon-wrapper',
								),
								'tag' => 'span',
							),
							'children' => array(
								array(
									'name'     => 'icon',
									'label'    => esc_html__( 'Icon', 'frames' ),
									'settings' => array(
										'icon' => array(
											'icon'    => 'ion-ios-arrow-down',
											'library' => 'ionicons',
										),
										'_hidden' => array(
											'_cssClasses' => 'fr-accordion__icon fill',
										),
									),
								),
							),
						),

					),
				),
				array(
					'name'     => 'div',
					'label'    => esc_html__( 'Body', 'frames' ),
					'settings' => array(
						'_hidden' => array(
							'_cssClasses' => 'fr-accordion__body',
						),
					),
					'children' => array(
						array(
							'name'     => 'div',
							'label'    => esc_html__( 'Content Wrapper', 'frames' ),
							'settings' => array(
								'_hidden' => array(
									'_cssClasses' => 'fr-accordion__content-wrapper',
								),
							),
							'children' => array(
								array(
									'name'     => 'text',
									'label'    => esc_html__( 'Content', 'frames' ),
									'settings' => array(
										'text' => esc_html__( 'Content of the accordion', 'frames' ),
									),
								),
							),
						),
					),
				),
			),
		);
	}

	/**
	 * Get the nestable children.
	 *
	 * @return array
	 */
	public function get_nestable_children() {
		$children = array();

		for ( $i = 0; $i < 2; $i++ ) {
			$item = $this->get_nestable_item();

			// Replace {item_index} with $index.
			$item = json_encode( $item );
			$item = str_replace( '{item_index}', $i + 1, $item );
			$item = json_decode( $item, true );
			$children[] = $item;
		}

		return $children;
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
		$settings = $this->settings;

		$firstItemOpened = isset( $settings['firstItemOpened'] );
		$allItemsExpanded = isset( $settings['allItemsExpanded'] );
		$expandedClass = isset( $settings['expandedClass'] );
		$expandedCurrentLink = isset( $settings['expandedCurrentLink'] );
		$scrollToHash = isset( $settings['scrollToHash'] );
		$closePreviousItem = isset( $settings['closePreviousItem'] );
		$showDuration = ! empty( $settings['showDuration'] ) ? $settings['showDuration'] : 300;
		$tag = isset( $settings['tag'] ) ? esc_html( $settings['tag'] ) : 'ul';
		$customTag = isset( $settings['customTag'] ) ? esc_html( $settings['customTag'] ) : 'div';
		$faqSchema = isset( $settings['addFaqSchema'] );
		$toggleAllInBuilder = isset( $settings['toggleAllInBuilder'] );
		$scrollToHeading = isset( $settings['scrollToHeading'] );
		$scrollToHeadingOn = isset( $settings['scrollToHeadingOn'] ) ? esc_html( $settings['scrollToHeadingOn'] ) : 'mobile_portrait';
		$scrollOffset = isset( $settings['scrollOffset'] ) ? intval( $settings['scrollOffset'] ) : 0;

		$accordion_options = array(
			'firstItemOpened'   => $firstItemOpened,
			'allItemsExpanded'  => $allItemsExpanded,
			'expandedClass'     => $expandedClass,
			'expandedCurrentLink' => $expandedCurrentLink,
			'scrollToHash'      => $scrollToHash,
			'closePreviousItem' => $closePreviousItem,
			'showDuration'      => $showDuration,
			'faqSchema'         => $faqSchema,
			'scrollOffset'      => $scrollOffset
		);

		if ( $firstItemOpened && $allItemsExpanded ) {
			$firstItemOpened = false;
		}

		if ( $scrollToHeading ) {

			$breakpoints = array();

			foreach ( \Bricks\Breakpoints::$breakpoints as $breakpoint ) {
				$breakpoints[] = $breakpoint;
			}

			$accordion_options['scrollToHeading'] = $scrollToHeading;
			foreach ( $breakpoints as $breakpoint ) {
				if ( $breakpoint['key'] == $scrollToHeadingOn ) {
						$scrollToHeadingOn = $breakpoint['width'];
						break;
				}
			}

			$accordion_options['scrollToHeadingOn'] = $scrollToHeadingOn;
		}

		if ( is_array( $accordion_options ) ) {
			$accordion_options = wp_json_encode( $accordion_options );
		}

		$accordion_options = str_replace( array( "\r", "\n", ' ' ), '', $accordion_options );

		if ( method_exists( '\Bricks\Query', 'is_any_looping' ) ) {

			$query_id = \Bricks\Query::is_any_looping();

			if ( $query_id ) {
				$this->set_attribute( '_root', 'data-id', $this->id . '_' . \Bricks\Query::get_loop_index() );
			} else {
				$this->set_attribute( '_root', 'data-id', $this->id );
			}
		}

		if ( ! Widget_Manager::is_bricks_frontend() ) {
			$this->set_attribute( '_root', 'data-fr-builder', 'true' );
		}

		if ( 'custom' === $tag ) {
			$tag = $customTag;
		}

		$this->set_attribute( '_root', 'class', 'fr-accordion' );
		$this->set_attribute( '_root', 'data-fr-accordion-options', trim( $accordion_options ) );

		$output = '<' . $tag . " {$this->render_attributes('_root')}>";
		$output .= \Bricks\Frontend::render_children( $this );
		$output .= '</' . $tag . '>'; // _root.

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render widget output in the builder.
	 *
	 * @return void
	 */
	public static function render_builder() {  ?>
		<script type="text/x-template" id="tmpl-bricks-element-fr-accordion">
		<component
			class="fr-accordion in-builder"
			:class="{ 'fr-accordion--toggle-all': settings.allItemsExpanded || settings.toggleAllInBuilder, 'fr-accordion--first-item-opened': settings.firstItemOpened }"
		>
			<bricks-element-children :element="element" />
		</component>
		</script>

		<?php
	}
}
