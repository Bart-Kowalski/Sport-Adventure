<?php
/**
 * Table of Contents Widget.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Widgets\Table_Of_Contents;

use Frames_Client\Helpers\Flag;
use Frames_Client\Widget_Manager;
use Frames_Client\Widgets\Views\TableOfContentsV2View;

define( 'FRAMES_ENABLE_USE_INTRODUCTION', false );
define( 'FRAMES_ENABLE_RENDER_BUILDER', false );

/**
 * Table of Contents class.
 */
class Table_Of_Contents_Widget extends \Bricks\Element {



	/**
	 * Element properties
	 *
	 * @since 1.0.0
	 * @access public
	 */

	/**
	 * Use predefined element category 'general'.
	 *
	 * @var string
	 */
	public $category     = 'Frames';

	/**
	 * Make sure to prefix your elements.
	 *
	 * @var string
	 */
	public $name         = 'fr-table-of-contents';

	/**
	 * Themify icon font class.
	 *
	 * @var string
	 */
	public $icon         = 'fas fa-list-ol';

	/**
	 * Default CSS selector.
	 *
	 * @var string
	 */
	public $css_selector = '.table-of-contents-wrapper';

	/**
	 * Scripts to be enqueued.
	 *
	 * @var array
	 */
	public $scripts = array( 'table_of_contents_script' );

	/**
	 * Is nestable.
	 *
	 * @var boolean
	 */
	public $nestable = false;

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function get_methods() {
	}

	/**
	 * Get widget label.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget Label.
	 */
	public function get_label() {
		return esc_html__( 'Frames Table of Contents', 'frames' );
	}

	/**
	 * Register widget control groups.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function set_control_groups() {
		/**
		   *  Here you can add your control groups and assign them to different tabs.
		   *  Check this: https://academy.bricksbuilder.io/article/create-your-own-elements/
		   */

		$this->control_groups['frTocSettings'] = array(
			'title' => esc_html__( 'Settings', 'frames' ),
			'tab' => 'content',
		);

		$this->control_groups['frTocAccordionSettings'] = array(
			'title' => esc_html__( 'Accordion Settings', 'frames' ),
			'tab' => 'content',
		);

		$this->control_groups['frTocAccordionHeaderStyling'] = array(
			'title' => esc_html__( 'Header Styling', 'frames' ),
			'tab' => 'content',
		);

		$this->control_groups['frTocAccordionBodyStyling'] = array(
			'title' => esc_html__( 'Table of Contents Styling', 'frames' ),
			'tab' => 'content',
		);

		$this->control_groups['frTocActiveHeadingStyling'] = array(
			'title' => esc_html__( 'Active Heading Styling', 'frames' ),
			'tab' => 'content',
		);
	}

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function set_controls() {
		/**
		 *  Here you can add your controls for your widget.
		 *  Check this: https://academy.bricksbuilder.io/topic/controls/
		 */

		$this->controls['frTocContentSelector'] =
			array(
				'group' => 'frTocSettings',
				'label' => __( 'Content Target (Class or ID)', 'frames' ),
				'type' => 'text',
				'default' => '',
				'inlineEditing' => true,
			);

		$this->controls['frTocHeaderText'] =
			array(
				'group' => 'frTocSettings',
				'label' => __( 'TOC Header Text', 'frames' ),
				'type' => 'text',
				'default' => 'Table of Contents',
				'inlineEditing' => true,
			);

		$this->controls['frTocShowHeadingUpTo'] = array(
			'group' => 'frTocSettings',
			'label' => esc_html__( 'Show Heading up to', 'frames' ),
			'type' => 'select',
			'options' => array(
				'h2'  => 'h2',
				'h3'  => 'h3',
				'h4'  => 'h4',
				'h5'  => 'h5',
				'h6'  => 'h6',
			),
			'inline' => true,
			'default' => 'h3',
		);

		$this->controls['frTocListType'] = array(
			'group' => 'frTocSettings',
			'label' => esc_html__( 'List type', 'frames' ),
			'type' => 'select',
			'options' => array(
				'decimal'  => '1, 2',
				'lower-alpha'  => 'a, b',
				'none'  => 'None',
			),
			'inline' => true,
			'default' => 'decimal',
		);

		$this->controls['frTocSubListType'] = array(
			'group' => 'frTocSettings',
			'label' => esc_html__( 'Sub-list type', 'frames' ),
			'type' => 'select',
			'options' => array(
				'decimal'  => '1, 2',
				'lower-alpha'  => 'a, b',
				'none'  => 'None',
			),
			'inline' => true,
			'default' => 'lower-alpha',
		);

		$this->controls['frTocScrollOffset'] =
			array(
				'group' => 'frTocSettings',
				'label' => __( 'Scroll Offset', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 10,
				'units' => true,
				'step' => 1,
				'inline' => true,
				'default' => '50'
			);

		if ( Flag::is_on( 'FRAMES_FLAG_ENABLE_NEW_TOC' ) ) {
			$this->controls['headerSelector'] = array(
				'group' => 'frTocSettings',
				'info' => __( 'If you are using a Fixed or Sticky Header', 'frames' ),
				'label' => __( 'Header ID or Class Selector', 'frames' ),
				'type' => 'text',
				'default' => '',
			);

			$this->controls['frTocUseBottomOffset'] = array(
				'group' => 'frTocSettings',
				'label' => __( 'Use Bottom Offset', 'frames' ),
				'type' => 'checkbox',
				'inline' => true,
				'default' => false
			);
		}

		if ( Flag::is_on( 'FRAMES_FLAG_ENABLE_NEW_TOC' ) && Flag::is_on( 'FRAMES_ENABLE_USE_INTRODUCTION' ) ) {
			$this->controls['frTocUseIntroduction'] = array(
				'group' => 'frTocSettings',
				'label' => __( 'Use Introduction', 'frames' ),
				'type' => 'checkbox',
				'inline' => true,
				'default' => false
			);
		}

		$this->controls['frTocUseAccordion'] =
			array(
				'group' => 'frTocAccordionSettings',
				'label' => __( 'Use accordion', 'frames' ),
				'type' => 'checkbox',
				'inline' => true,
				'default' => true
			);

		$this->controls['frTocAccordionIsOpen'] =
			array(
				'group' => 'frTocAccordionSettings',
				'label' => __( 'Accordion is open', 'frames' ),
				'type' => 'checkbox',
				'inline' => true,
				'default' => true
			);

		$this->controls['frTocAccHeaderBackgroundColor'] =
			array(
				'group' => 'frTocAccordionHeaderStyling',
				'label' => __( 'Background Color', 'frames' ),
				'type' => 'color',
				'default' => array(
					'rgb' => 'var(--neutral-ultra-dark)',
				),
				'css'   => array(
					array(
						'property' => 'background-color',
						'selector' => '.fr-toc__header',
					),
				),
			);

		$this->controls['frTocAccHeaderTextColor'] =
			array(
				'group' => 'frTocAccordionHeaderStyling',
				'label' => __( 'Text Color', 'frames' ),
				'type' => 'color',
				'default' => array(
					'rgb' => 'var(--white)',
				),
				'css'   => array(
					array(
						'property' => 'color',
						'selector' => '.fr-toc__heading',
					),
				),
			);

		$this->controls['frTocAccHeaderTypography'] =
			array(
				'group' => 'frTocAccordionHeaderStyling',
				'label' => __( 'Typography', 'frames' ),
				'type' => 'typography',
				'css' => array(
					array(
						'property' => 'typography',
						'selector' => '.fr-toc__heading',
					),
				),
				'inline' => true,
			);

		$this->controls['frTocAccHeaderBorder'] =
			array(
				'group' => 'frTocAccordionHeaderStyling',
				'label' => __( 'Border', 'frames' ),
				'type' => 'border',
				'default' => '',
				'inlineEditing' => true,
				'css'   => array(
					array(
						'property' => 'border',
						'selector' => '.fr-toc__header',
					),
				),
			);

		$this->controls['frTocAccHeaderPadding'] =
			array(
				'group' => 'frTocAccordionHeaderStyling',
				'label' => __( 'Padding', 'frames' ),
				'type' => 'dimensions',
				'css' => array(
					array(
						'property' => 'padding',
						'selector' => '.fr-toc__header',
					),
				),
				'default' => array(
					'top' => 'var(--space-xs)',
					'right' => 'var(--space-xs)',
					'bottom' => 'var(--space-xs)',
					'left' => 'var(--space-xs)',
				),
			);

		$this->controls['frTocAccordionArrowIcon'] =
			array(
				'group' => 'frTocAccordionHeaderStyling',
				'label' => __( 'Arrow Icon', 'frames' ),
				'type' => 'icon',
				'default' => array(
					'library' => 'themify',
					'icon' => 'ti-angle-down',
				),
			);

		$this->controls['frTocAccHeaderIconColor'] =
			array(
				'group' => 'frTocAccordionHeaderStyling',
				'label' => __( 'Icon Color', 'frames' ),
				'type' => 'color',
				'default' => array(
					'rgb' => 'var(--base-ultra-light)',
				),
				'css'   => array(
					array(
						'property' => 'color',
						'selector' => '.fr-toc__icon',
					),
				),
			);

		$this->controls['frTocAccHeaderIconBackgroundColor'] =
			array(
				'group' => 'frTocAccordionHeaderStyling',
				'label' => __( 'Icon Background Color', 'frames' ),
				'type' => 'color',
				'default' => array(
					'rgb' => 'rgba(0,0,0,.0)',
				),
				'css'   => array(
					array(
						'property' => 'background-color',
						'selector' => '.fr-toc__icon',
					),
				),
			);

		$this->controls['frTocAccHeaderIconSize'] =
			array(
				'group' => 'frTocAccordionHeaderStyling',
				'label' => __( 'Icon Size', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 99999,
				'step' => 1,
				'units' => false,
				'inline' => true,
				'default' => 'var(--text-m)',
				'css'   => array(
					array(
						'property' => 'font-size',
						'selector' => '.fr-toc__icon',
					),
				),
			);

		$this->controls['frTocAccHeaderIconPadding'] =
			array(
				'group' => 'frTocAccordionHeaderStyling',
				'label' => __( 'Icon Background Size', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 9999999,
				'step' => 1,
				'units' => true,
				'inline' => true,
				'default' => '',
				'css'   => array(
					array(
						'property' => 'width',
						'selector' => '.fr-toc__icon',
					),
				),
			);

		$this->controls['frTocAccHeaderIconBorder'] =
			array(
				'group' => 'frTocAccordionHeaderStyling',
				'label' => __( 'Icon Border', 'frames' ),
				'type' => 'border',
				'default' => '',
				'inlineEditing' => true,
				'css'   => array(
					array(
						'property' => 'border',
						'selector' => '.fr-toc__icon',
					),
				),
			);

		$this->controls['frTocBodyBackgroundColor'] =
			array(
				'group' => 'frTocAccordionBodyStyling',
				'label' => __( 'Background Color', 'frames' ),
				'type' => 'color',
				'default' => array(
					'rgb' => 'var(--neutral-ultra-light)',
				),
				'css'   => array(
					array(
						'property' => 'background-color',
						'selector' => '.fr-toc__list-wrapper',
					),
				),
			);

		$this->controls['frTocBodyPadding'] =
			array(
				'group' => 'frTocAccordionBodyStyling',
				'label' => __( 'Padding', 'frames' ),
				'type' => 'dimensions',
				'css' => array(
					array(
						'property' => 'padding',
						'selector' => '.fr-toc__list-wrapper',
					),
				),
				'default' => array(
					'top' => 'var(--space-xs)',
					'right' => 'var(--space-xs)',
					'bottom' => 'var(--space-xs)',
					'left' => 'var(--space-xs)',
				),
			);

		$this->controls['frTocBodyItemColor'] =
			array(
				'group' => 'frTocAccordionBodyStyling',
				'label' => __( 'Item Color', 'frames' ),
				'type' => 'color',
				'default' => array(
					'rgb' => 'var(--neutral-ultra-dark)',
				),
				'css'   => array(
					array(
						'property' => 'color',
						'selector' => '.fr-toc__list-link',
					),
				),
			);

		$this->controls['frTocBodyItemColorHover'] =
			array(
				'group' => 'frTocAccordionBodyStyling',
				'label' => __( 'Item Color Hover', 'frames' ),
				'type' => 'color',
				'default' => array(
					'rgb' => 'var(--action-hover)',
				),
				'css'   => array(
					array(
						'property' => 'color',
						'selector' => '.fr-toc__list-link:hover',
					),
				),
			);

		$this->controls['frTocBodyItemTypography'] =
			array(
				'group' => 'frTocAccordionBodyStyling',
				'label' => __( 'Item Typography', 'frames' ),
				'type' => 'typography',
				'css' => array(
					array(
						'property' => 'typography',
						'selector' => '.fr-toc__list-link',
					),
				),
				'inline' => true,
			);

		$this->controls['frTocBodyItemPadding'] =
			array(
				'group' => 'frTocAccordionBodyStyling',
				'label' => __( 'Item Padding', 'frames' ),
				'type' => 'dimensions',
				'css' => array(
					array(
						'property' => 'padding',
						'selector' => '.fr-toc__list-link',
					),
				),
				'default' => array(
					'top' => '.5em',
					'right' => '.5em',
					'bottom' => '.5em',
					'left' => '.5em',
				),
			);

		$this->controls['frTocBodyItemGap'] =
			array(
				'group' => 'frTocAccordionBodyStyling',
				'label' => __( 'Gap', 'frames' ),
				'type' => 'number',
				'min' => 0,
				'max' => 9999,
				'step' => 1,
				'units' => true,
				'inline' => true,
				'default' => 'var(--space-xs)',
				'css'   => array(
					array(
						'property' => 'gap',
						'selector' => '.fr-toc__list',
					),
				),
			);

		$this->controls['frTocActiveBackgroundColor'] =
			array(
				'group' => 'frTocActiveHeadingStyling',
				'label' => __( 'Background Color', 'frames' ),
				'type' => 'color',
				'default' => array(
					'rgb' => 'var(--action-trans-20)',
				),
				'css'   => array(
					array(
						'property' => 'background-color',
						'selector' => '.fr-toc__list-link--active',
					),
				),
			);

		$this->controls['typographyfrTocActiveTypography'] =
			array(
				'group' => 'frTocActiveHeadingStyling',
				'label' => __( 'Typography', 'frames' ),
				'type' => 'typography',
				'css' => array(
					array(
						'property' => 'typography',
						'selector' => '.fr-toc__list-link--active',
					),
				),
				'inline' => true,
			);

		$this->controls['frTocActiveBorder'] =
			array(
				'group' => 'frTocActiveHeadingStyling',
				'label' => __( 'Border', 'frames' ),
				'type' => 'border',
				'default' => '',
				'inlineEditing' => true,
				'css'   => array(
					array(
						'property' => 'border',
						'selector' => '.border',
					),
				),
			);
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
		$filename = 'table-of-contents';
		wp_enqueue_style(
			"frames-{$filename}",
			FRAMES_WIDGETS_URL . "/{$filename}/css/{$filename}.css",
			array(),
			filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/css/{$filename}.css" )
		);
		if ( Flag::is_on( 'FRAMES_FLAG_ENABLE_NEW_TOC' ) && Flag::is_on( 'FRAMES_ENABLE_RENDER_BUILDER' ) ) {
			wp_enqueue_script(
				"frames-{$filename}",
				FRAMES_WIDGETS_URL . "/{$filename}/js/{$filename}.js",
				array(),
				filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/js/{$filename}.js" ),
				true
			);
		} else {
			wp_enqueue_script(
				"frames-{$filename}",
				FRAMES_WIDGETS_URL . "/{$filename}/js/{$filename}-legacy.js",
				array(),
				filemtime( FRAMES_WIDGETS_DIR . "/{$filename}/js/{$filename}-legacy.js" ),
				true
			);
		}
		wp_localize_script(
			"frames-{$filename}",
			'frames_toc_obj',
			array(
				'flag_enable_new_toc' => Flag::is_on( 'FRAMES_FLAG_ENABLE_NEW_TOC' ) ? 'true' : 'false',
			)
		);
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
		$this->get_methods();

		$view_settings = array(
			'root_attr'     => $this->render_attributes( '_root' ),
			'content_selector'  => ! empty( $this->settings['frTocContentSelector'] ) ? $this->settings['frTocContentSelector'] : '',
			'header_text'   => ! empty( $this->settings['frTocHeaderText'] ) ? $this->settings['frTocHeaderText'] : '',
			'offset'        => ! empty( $this->settings['frTocScrollOffset'] ) ? $this->settings['frTocScrollOffset'] : '',
			'list_type'     => ! empty( $this->settings['frTocListType'] ) ? $this->settings['frTocListType'] : '',
			'sublist_type'  => ! empty( $this->settings['frTocSubListType'] ) ? $this->settings['frTocSubListType'] : '',
			'show_heading'  => ! empty( $this->settings['frTocShowHeadingUpTo'] ) ? $this->settings['frTocShowHeadingUpTo'] : '',
			'use_accordion' => ! empty( $this->settings['frTocUseAccordion'] ) ? $this->settings['frTocUseAccordion'] : '',
			'accordion_is_open' => ! empty( $this->settings['frTocAccordionIsOpen'] ) ? $this->settings['frTocAccordionIsOpen'] : '',
			'icon'  => ! empty( $this->settings['frTocAccordionArrowIcon'] ) ? self::render_icon( $this->settings['frTocAccordionArrowIcon'] ) : '',
		);

		if ( Flag::is_on( 'FRAMES_FLAG_ENABLE_NEW_TOC' ) && Flag::is_on( 'FRAMES_ENABLE_USE_INTRODUCTION' ) ) {
			$view_settings['use_introduction'] = ! empty( $this->settings['frTocUseIntroduction'] ) ? $this->settings['frTocUseIntroduction'] : '';
		}
		if ( Flag::is_on( 'FRAMES_FLAG_ENABLE_NEW_TOC' ) ) {
			$view_settings['header_selector'] = ! empty( $this->settings['headerSelector'] ) ? $this->settings['headerSelector'] : '';
			$view_settings['use_bottom_offset'] = ! empty( $this->settings['frTocUseBottomOffset'] ) ? $this->settings['frTocUseBottomOffset'] : '';
			$view = new \Frames_Client\Widgets\Views\TableOfContentsV2View( $view_settings );
		} else {
			$view = new \Frames_Client\Widgets\Views\TableOfContentsView( $view_settings );
		}

		echo $view->get_view(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render widget output on the Editor.
	 *
	 * Written in PHP and JS ( Vue Template System ) and used to generate the editor HTML
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public static function render_builder() {
		if ( Flag::is_on( 'FRAMES_FLAG_ENABLE_NEW_TOC' ) && Flag::is_on( 'FRAMES_ENABLE_RENDER_BUILDER' ) ) {
			TableOfContentsV2View::render_builder();
		}
	}
}
