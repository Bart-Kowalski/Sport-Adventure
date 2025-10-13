<?php

namespace BricksExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'BricksExtrasHelpers' ) ) {
	require_once 'BricksExtrasHelpers.php';
}

class HeaderExtras {

	public function init() {

		/* header */
		add_filter( 'bricks/header/attributes', [ $this, 'extras_header_attributes' ]);
		add_filter( 'builder/settings/page/controls_data', [ $this, 'extras_page_options' ]);
		add_filter( 'builder/settings/template/controls_data', [ $this, 'extras_template_options' ]);
		add_action( 'wp_enqueue_scripts', [ $this, 'extras_header' ]);

	}

	function extras_page_options($data) {

		if ( null != \Bricks\Templates::get_template_type() && 'content' != \Bricks\Templates::get_template_type() ) {
			return $data;
		}

		return $this->extras_header_builder_options($data);
	
	}

	function extras_template_options($data) {

		if ( \Bricks\Templates::get_template_type() === 'footer' || 
			 \Bricks\Templates::get_template_type() === 'popup') {
			return $data;
		}

		return $this->extras_header_builder_options($data);
	}

	public static function extras_header_builder_options($data) {

		$data['controlGroups']['xHeaderGroup'] = array(
			'title' => 'Header Extras',
		);

		$data['controls']['xOverlayHeaderSep'] = array(
			'group' => 'xHeaderGroup',
			'type'  => 'separator',
			'label' => esc_html__( 'Overlay Header', 'bricks' ),
			'description' => esc_html__( 'Will position the header over the top of the first section and remove header row background colors', 'bricks' ),
		);

		$breakpointOptions = [];

		foreach ( \Bricks\Breakpoints::$breakpoints as $breakpoint ) {
			$breakpointOptions[$breakpoint['width']] = $breakpoint['label'] . ' ( > ' . ( intval($breakpoint['width']) + 1 ) . 'px )';
		}

		$breakpointOptions['none'] = 'None';
		$breakpointOptions['always'] = 'Always';


		$data['controls']['xOverlayHeader'] = array(
			'group' => 'xHeaderGroup',
			'type' => 'select',
			'label' => esc_html__( 'Overlay header above..', 'bricks' ),
			'options' => $breakpointOptions,
			'placeholder' => 'Default'
		);

		

		/* sticky header */ 

		$data['controls']['xHeaderZindex'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Z-index*', 'bricks' ),
			'type' => 'number',
			'group' => 'xHeaderGroup',
			'min' => 0,
			'max' => 1000,
			'inline' => true,
			//'placeholder' => esc_html__( '99', 'bricksextras' ),
		  ];

		$data['controls']['xStickyHeaderSep'] = array(
			'group' => 'xHeaderGroup',
			'type'  => 'separator',
			'label' => esc_html__( 'Sticky Header', 'bricks' ),
			'description' => esc_html__( 'Hide the sticky header after scrolling a certain distance')
		);

		$data['controls']['xStickyHeaderScroll'] = array(
			'group' => 'xHeaderGroup',
			'type' => 'select',
			'inline' => true,
			'small' => true,
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'label' => esc_html__( 'Sticky header on scroll', 'bricks' ),
		 );

		 $data['controls']['xStickyHeaderAbove'] = array(
			'group' => 'xHeaderGroup',
			'type' => 'select',
			'label' => esc_html__( 'Sticky header above..', 'bricks' ),
			'options' => $breakpointOptions,
			'placeholder' => 'None',
			'required' => ['xStickyHeaderScroll', '=', 'true'],
		);

		 $data['controls']['xStickyHeaderScrollDistance'] = array(
			'group' => 'xHeaderGroup',
			'type' => 'number',
			'label' => esc_html__( 'Scroll distance (px)', 'bricks' ),
			'info' => esc_html__( 'Distance scrolled down the page before header becomes sticky', 'bricks' ),
			'required' => ['xStickyHeaderScroll', '=', 'true'],
		 );

		 $data['controls']['xStickyHeaderFadeDuration'] = array(
			'group' => 'xHeaderGroup',
			'type' => 'number',
			'units' => true,
			'label' => esc_html__( 'Header transition duration (ms)', 'bricks' ),
			'required' => ['xStickyHeaderScroll', '=', 'true'],
		 );

		 $data['controls']['xStickyHeaderTransitionDuration'] = array(
			'group' => 'xHeaderGroup',
			'type' => 'number',
			'units' => true,
			'label' => esc_html__( 'Header row transition duration (ms)', 'bricks' ),
			'required' => ['xStickyHeaderScroll', '=', 'true'],
		 );

		 $data['controls']['xStickyHeaderZindex'] = array(
			'group' => 'xHeaderGroup',
			'type' => 'number',
			'units' => true,
			'label' => esc_html__( 'Sticky z-index', 'bricks' ),
			'required' => ['xStickyHeaderScroll', '=', 'true'],
			'placeholder' => '999'
		 );



		 $data['controls']['xHideHeaderSep'] = array(
			'group' => 'xHeaderGroup',
			'type'  => 'separator',
			'label' => esc_html__( 'Hide Header', 'bricks' ),
			'description' => esc_html__( 'Hide the sticky header after scrolling a certain distance', 'bricks' ),
			//'required' => ['xStickyHeaderScroll', '=', 'true'],
		);

		 $data['controls']['xStickyHeaderHide'] = array(
			'group' => 'xHeaderGroup',
			'type' => 'select',
			'inline' => true,
			'small' => true,
			'options' => [
				'true' => esc_html__( 'Enable', 'bricks' ),
				'false' => esc_html__( 'Disable', 'bricks' ),
			],
			'label' => esc_html__( 'Hide header after scrolling', 'bricks' ),
			//'required' => ['xStickyHeaderScroll', '=', 'true'],
		 );

		 $data['controls']['xStickyHeaderHideEffect'] = array(
			'group' => 'xHeaderGroup',
			'type' => 'select',
			'inline' => true,
			'small' => true,
			'options' => [
				'fade' => esc_html__( 'Fade', 'bricks' ),
				'slideUp' => esc_html__( 'Slide up', 'bricks' ),
			],
			'label' => esc_html__( 'Hide header effect', 'bricks' ),
			'required' => [
				//['xStickyHeaderScroll', '=', 'true'],
				['xStickyHeaderHide', '=', 'true']
			],
		 );

		 $data['controls']['xStickyHeaderHideDistance'] = array(
			'group' => 'xHeaderGroup',
			'type' => 'number',
			'label' => esc_html__( 'Scroll distance (px)', 'bricks' ),
			'info' => esc_html__( 'Distance scrolled down the page before header hides', 'bricks' ),
			'required' => [
				//['xStickyHeaderScroll', '=', 'true'],
				['xStickyHeaderHide', '=', 'true']
			],
		 );

		 $data['controls']['xStickyHeaderHideTollerance'] = array(
			'group' => 'xHeaderGroup',
			'type' => 'number',
			'label' => esc_html__( 'Tollerance (px)', 'bricks' ),
			'required' => [
				//['xStickyHeaderScroll', '=', 'true'],
				['xStickyHeaderHide', '=', 'true']
			],
		 );

		 $data['controls']['xOverlayHeaderApply'] = [
			'group' => 'xHeaderGroup',
			'type' => 'apply',
			'reload' => true,
			'label' => esc_html__( 'Apply header settings', 'bricks' ),
		  ];

		return $data;

	}

	public function extras_header() {

		/* overlay header */

		$breakpoint = !!Helpers::getCurrentTemplateSetting('xOverlayHeader') ? Helpers::getCurrentTemplateSetting('xOverlayHeader') : 'none';
		$animationTransition = !!Helpers::getCurrentTemplateSetting('xStickyHeaderTransitionDuration') ? intval( Helpers::getCurrentTemplateSetting('xStickyHeaderTransitionDuration') ) . 'ms' : '0';
		$fadeTransition = !!Helpers::getCurrentTemplateSetting('xStickyHeaderFadeDuration') ? intval( Helpers::getCurrentTemplateSetting('xStickyHeaderFadeDuration') ) . 'ms' : '0';
		$xHeaderZindex = !!Helpers::getCurrentTemplateSetting('xHeaderZindex') ? esc_attr( Helpers::getCurrentTemplateSetting('xHeaderZindex') ) : '';
		$xStickyHeaderZindex = !!Helpers::getCurrentTemplateSetting('xStickyHeaderZindex') ? esc_attr( Helpers::getCurrentTemplateSetting('xStickyHeaderZindex') ) : '999';

		$headerStyles = "
			#brx-header {
				--x-header-transition: " . $animationTransition . ";
				--x-header-fade-transition: " . $fadeTransition . ";
			}

			#brx-header.x-header_sticky-active {
				z-index: " . $xStickyHeaderZindex . ";
			}
		";

		if ('' != $xHeaderZindex) {
			$headerStyles .= "
				#brx-header {
					z-index: " . $xHeaderZindex . ";
				}
			";
		}

		wp_add_inline_style( 'bricks-frontend', $headerStyles );

		$overlayHeaderStyles = "

			#brx-header {
				background: transparent;
				left: 0;
				right: 0;
				position: absolute;
				top: 0;
				--x-overlay-header-background: transparent;
				--x-header-position: overlay;
			}

			#brx-header[data-x-scroll='0'] {
				position: fixed;
			}

			#brx-header:not(.scrolling):not(.x-header_sticky-active) > .brxe-xheaderrow:not([data-x-sticky-active*=true]) {
				background: var(--x-overlay-header-background)!important;
			}

			#brx-header:not(.scrolling):not(.x-header_sticky-active) > .brxe-section {
				background: none!important;
			}

			.admin-bar #brx-header {
				top: var(--wp-admin--admin-bar--height);
			}

			#brx-header:not(.scrolling):not(.x-header_sticky-active) div.brxe-xheaderrow[data-x-overlay=hide]{
				display: none;
			}

			#brx-header .brxe-xheaderrow[data-x-overlay=show] {
				display: flex;
			}
		
		";

		/* apply at breakpoint */

		if ( !!$breakpoint && 'none' !== $breakpoint ) {

			$overlayHeaderCss = "";
			$overlayHeaderCss .= 'always' !== $breakpoint ? " @media only screen and (min-width: " . (intval( $breakpoint ) + 1) . "px)  { " : "";
			$overlayHeaderCss .= $overlayHeaderStyles;
			$overlayHeaderCss .= 'always' !== $breakpoint ? "}" : "";

			wp_add_inline_style( 'bricks-frontend', $overlayHeaderCss );

		}

		if ( 'true' === Helpers::getCurrentTemplateSetting('xStickyHeaderScroll') ) {
			wp_enqueue_style( 'x-sticky-header', BRICKSEXTRAS_URL . 'components/assets/css/stickyheader.css', [], '1.0.0' );
		}

		/* support boxed layouts */

		if ( 'true' === Helpers::getCurrentTemplateSetting('xStickyHeaderScroll') || ( !!Helpers::getCurrentTemplateSetting('xOverlayHeader') && 'none' !== Helpers::getCurrentTemplateSetting('xOverlayHeader') ) ) {

			$boxed = false;
			$headerWidth = '';

			if ( !empty( \Bricks\Theme_Styles::$active_settings['general']['siteLayout'] ) ) {
				if ('boxed' ===  \Bricks\Theme_Styles::$active_settings['general']['siteLayout']) {
					$boxed = true;
				}
			}

			if ( !empty( \Bricks\Database::$page_settings['siteLayout'] ) ) {
				if ('boxed' === \Bricks\Database::$page_settings['siteLayout'] ) {
					$boxed = true;
				} elseif ('wide' === \Bricks\Database::$page_settings['siteLayout'] ) {
					$boxed = false;
				}
			}

			if ( !empty( \Bricks\Theme_Styles::$active_settings['general']['siteLayoutBoxedMaxWidth'] ) ) {
				$headerWidth = \Bricks\Theme_Styles::$active_settings['general']['siteLayoutBoxedMaxWidth'];
			}

			if ( !empty( \Bricks\Database::$page_settings['siteLayoutBoxedMaxWidth'] ) ) {
				$headerWidth = \Bricks\Database::$page_settings['siteLayoutBoxedMaxWidth'];
			}

			if ( is_numeric( $headerWidth ) ) {
				$headerWidth = $headerWidth . 'px';
			}
				
			if ($boxed) {

				echo '<style>
				.brx-boxed #brx-header,
				.brx-boxed #brx-header.x-header_sticky-active {
					left: auto;
					right: auto;
					max-width: ' . esc_attr( $headerWidth ) . ';
				}
				</style>';

			}


		}


	}


	public function extras_header_attributes( $attributes ) {

		/* sticky header */

		if ( 'true' === Helpers::getCurrentTemplateSetting('xStickyHeaderScroll') ) {

			// Add sticky header classes
			if ( isset( $attributes['class'] ) && is_array( $attributes['class'] ) ) { 
				$attributes['class'][] = 'x-header_sticky';
			} else {
				$attributes['class'] = ['x-header_sticky'];
			}

			$attributes['data-x-scroll'] = Helpers::getCurrentTemplateSetting('xStickyHeaderScrollDistance') ? esc_attr( Helpers::getCurrentTemplateSetting('xStickyHeaderScrollDistance') ) : 0;
			$attributes['data-x-break'] = Helpers::getCurrentTemplateSetting('xStickyHeaderAbove') ? intval( Helpers::getCurrentTemplateSetting('xStickyHeaderAbove') ) + 1 : 'none';
			$attributes['data-x-hide-effect'] = Helpers::getCurrentTemplateSetting('xStickyHeaderHideEffect') ? esc_attr( Helpers::getCurrentTemplateSetting('xStickyHeaderHideEffect') ) : 'slideUp';

			/* hide header */

			if ( 'true' === Helpers::getCurrentTemplateSetting('xStickyHeaderHide') ) {

				$attributes['data-x-header-hide'] = Helpers::getCurrentTemplateSetting('xStickyHeaderHideDistance') ? esc_attr( Helpers::getCurrentTemplateSetting('xStickyHeaderHideDistance') ) : 0;
				$attributes['data-x-header-tollerance'] = Helpers::getCurrentTemplateSetting('xStickyHeaderHideTollerance') ? esc_attr( Helpers::getCurrentTemplateSetting('xStickyHeaderHideTollerance') ) : 0;
				wp_enqueue_script( 'x-hide-header', BRICKSEXTRAS_URL . 'components/assets/js/' . Helpers::maybeMinifyScripts('hideheader') . '.js', '', '1.0.0', true );

			}

			wp_enqueue_script( 'x-sticky-header', BRICKSEXTRAS_URL . 'components/assets/js/' . Helpers::maybeMinifyScripts('header') . '.js', '', '1.0.3', true );

		 }

		 $overlayHeaderAttr = !!Helpers::getCurrentTemplateSetting('xOverlayHeader') ? Helpers::getCurrentTemplateSetting('xOverlayHeader') : 'none';

		 if ( is_numeric($overlayHeaderAttr) ) {
			$overlayHeaderAttr = $overlayHeaderAttr + 1;
		 }

		 $attributes['data-x-overlay'] = esc_attr( $overlayHeaderAttr );
		
		 return $attributes;

	}

}
