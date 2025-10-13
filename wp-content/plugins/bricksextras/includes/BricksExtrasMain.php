<?php

namespace BricksExtras;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'BricksExtrasMain' ) ) {
	return;
}

if ( ! class_exists( 'BricksExtrasInteractive' ) ) {
	require_once 'BricksExtrasInteractive.php';
}

if ( ! class_exists( 'BricksExtrasInteractions' ) ) {
	require_once 'BricksExtrasInteractions.php';
}

if ( ! class_exists( 'BricksExtrasHeaderExtras' ) ) {
	require_once 'BricksExtrasHeaderExtras.php';
}

if ( ! class_exists( 'BricksExtrasHelpers' ) ) {
	require_once 'BricksExtrasHelpers.php';
}

if ( ! class_exists( 'BricksExtrasFavorite' ) ) {
	require_once 'BricksExtrasFavorite.php';
}

if ( ! class_exists( 'BricksExtrasProviders' ) ) {
	require_once 'Providers/BricksExtrasProviders.php';
	require_once 'Providers/Provider_Extras.php';
}

global $bricksExtrasElementCSSAdded;
$bricksExtrasElementCSSAdded = [];

class BricksExtrasMain {
	public $elements = array();
	public $prefix = '';
	public $version= '';
	public $conditions = '';
	public $memberConditions = '';
	public $wcConditions = '';
	public $miscOptions = '';

	/**
	 * Main class constructor
	 */
	public function __construct( $prefix, $version ) {
		

		$this->prefix = $prefix;
		$this->version = $version;

		$this->set_files();
		$this->set_member_conditions();
		$this->set_wc_conditions();
		$this->set_misc_options();
		$this->set_conditions();

		

		add_action( 'admin_init', array( $this, 'register_options' ) );

		add_action( $this->prefix . 'form_options', array( $this, 'options_form' ) );
		add_action( $this->prefix . 'conditions_form_options', array( $this, 'conditions_form_options' ) );
		add_action( $this->prefix . 'member_conditions_form_options', array( $this, 'member_conditions_form_options' ) );
		add_action( $this->prefix . 'wc_conditions_form_options', array( $this, 'wc_conditions_form_options' ) );
		add_action( $this->prefix . 'misc_form_options', array( $this, 'misc_form_options' ) );


		if ( !class_exists("\Bricks\Elements") ) { 
			return;
		}

		$this->load_files();

		if ( bricks_is_frontend() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'conditional_default_CSS' ) );

			// Interactions
			if ( get_option( $this->prefix . 'interactions') ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'interactions_script' ), 1 );
			}

			// frontend Scripts
			add_action( 'bricks_after_footer', array( $this, 'frontend_scripts' ), 200 );
		}

		if ( bricks_is_builder_main() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'builder_scripts' ) );
		}

		if ( bricks_is_builder_iframe() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'iframe_scripts' ) );
			add_filter( 'bricks/body/attributes', array( $this, 'proslider_builder_init' ) );
		}

		if ( get_option( $this->prefix . 'header_row') ) {
			(new HeaderExtras())->init();
		}

		if ( ! class_exists( 'WPGridBuilderStyler' ) && get_option( $this->prefix . 'wpgb_facets') ) {
			require_once 'WPGridBuilderStyler.php';
			(new WPGridBuilderStyler())->init();
		}

		if ( get_option( $this->prefix . 'favorite') ) {
			(new Favorite())->init();
		}

	}

	function sanitize_enable( $enable ) {

		if ( is_numeric( $enable ) && intval( $enable ) === 1 ) {
			return 1;
		}

		return 0; // default
	}

	function set_files() {
		$this->elements = [
			'back_to_top' => [
				'title' => 'Back to Top',
				'file_name' => 'x-back-to-top',
				'docs_slug' => 'back-to-top'
			],
			'before_after_image' => [
				'title' => 'Before / After Image',
				'file_name' => 'x-before-after-image',
				'docs_slug' => 'before-after-image'
			],
			'burger_trigger' => [
				'title' => 'Burger Trigger',
				'file_name' => 'x-burger-trigger',
				'docs_slug' => 'burger-trigger'
			],
			'content_switcher' =>[
				'title' => 'Content Switcher',
				'file_name' => 'x-content-switcher',
				'docs_slug' => 'content-switcher'
			],
			'content_timeline' => [
				'title' => 'Content Timeline',
				'file_name' => 'x-content-timeline',
				'docs_slug' => 'content-timeline'
			],
			'copy_to_clipboard' => [
				'title' => 'Copy to Clipboard',
				'file_name' => 'x-copy-to-clipboard',
				'docs_slug' => 'copy-to-clipboard'
			],
			'dynamic_chart' => [
				'title' => 'Dynamic Chart',
				'file_name' => 'x-dynamic-chart',
				'docs_slug' => 'dynamic-chart'
			],
			'dynamic_lightbox' => [
				'title' => 'Dynamic Lightbox',
				'file_name' => 'x-dynamic-lightbox',
				'docs_slug' => 'dynamic-lightbox'
			],
			
			'dynamic_table' => [
				'title' => 'Dynamic Table',
				'file_name' => 'x-dynamic-table',
				'docs_slug' => 'dynamic-table'
			],
			
			/*
			'dynamic_map' => [
				'title' => 'Dynamic Map',
				'file_name' => 'x-dynamic-map',
				'docs_slug' => 'dynamic-map'
			],
			*/
			
			'favorite' =>[
				'title' => 'Favorites / Wishlist',
				'file_name' => 'x-favorite',
				'docs_slug' => 'favorites-wishlist'
			],
			
			'fluent_form' => [
				'title' => 'Fluent Form',
				'file_name' => 'x-fluent-form',
				'docs_slug' => 'fluent-form'
			],
			'header_row' => [
				'title' => 'Header Extras / Rows',
				'file_name' => 'x-header-row',
				'docs_slug' => 'header-extras'
			],
			'notification_bar' =>[
				'title' => 'Header Notification Bar',
				'file_name' => 'x-notification-bar',
				'docs_slug' => 'header-notification-bar'
			],
			
			'header_search' => [
				'title' => 'Header Search',
				'file_name' => 'x-header-search',
				'docs_slug' => 'header-search'
			],
			'image_hotspots' => [
				'title' => 'Image Hotspots',
				'file_name' => 'x-image-hotspots',
				'docs_slug' => 'image-hotspots'
			],
			'interactive_cursor' => [
				'title' => 'Interactive Cursor',
				'file_name' => 'x-interactive-cursor',
				'docs_slug' => 'interactive-cursor'
			],
			'lottie' =>[
				'title' => 'Lottie',
				'file_name' => 'x-lottie',
				'docs_slug' => 'lottie',
			],
			
			'pro_modal' =>[
				'title' => 'Modal (template)',
				'file_name' => 'x-pro-modal',
				'docs_slug' => 'modal'
			],
			'pro_modal_nestable' =>[
				'title' => 'Modal',
				'file_name' => 'x-pro-modal-nestable',
				'docs_slug' => 'modal',
				'stylesheet' => 'promodal'
			],
			'nestable_table' =>[
				'title' => 'Nestable Table',
				'file_name' => 'x-nestable-table',
				'docs_slug' => 'nestable-table',
			],
			'offcanvas' =>[
				'title' => 'Offcanvas (template)',
				'file_name' => 'x-offcanvas',
				'docs_slug' => 'offcanvas'
			],
			'offcanvas_nestable' =>[
				'title' => 'Offcanvas',
				'file_name' => 'x-offcanvas-nestable',
				'docs_slug' => 'offcanvas',
				'stylesheet' => 'offcanvas'
			],
			/*
			'media_player' =>[
				'title' => 'Media Player',
				'file_name' => 'x-media-player',
				'docs_slug' => 'media-player',
			],
			'media_control' =>[
				'title' => 'Media Control',
				'file_name' => 'x-media-control',
				'docs_slug' => 'media-player',
				'stylesheet' => false,
			],
			'media_playlist' =>[
				'title' => 'Media Playlist',
				'file_name' => 'x-media-playlist',
				'docs_slug' => 'media-player',
			],
			*/
			'popover' =>[
				'title' => 'Popover / Tooltips',
				'file_name' => 'x-popover',
				'docs_slug' => 'popovers-tooltips'
			],
			'pro_accordion' =>[
				'title' => 'Pro Accordion',
				'file_name' => 'x-pro-accordion',
				'docs_slug' => 'pro-accordion'
			],
			'pro_alert' =>[
				'title' => 'Pro Alert',
				'file_name' => 'x-pro-alert',
				'docs_slug' => 'pro-alert'
			],
			'countdown' =>[
				'title' => 'Pro Countdown',
				'file_name' => 'x-countdown',
				'docs_slug' => 'countdown'
			],
			'pro_slider' =>[
				'title' => 'Pro Slider',
				'file_name' => 'x-pro-slider',
				'docs_slug' => 'pro-slider'
			],
			'pro_slider_control' =>[
				'title' => 'Pro Slider Control',
				'file_name' => 'x-pro-slider-control',
				'docs_slug' => 'pro-slider',
			],
			'pro_slider_gallery' =>[
				'title' => 'Pro Slider Gallery',
				'file_name' => 'x-pro-slider-gallery',
				'docs_slug' => 'pro-slider',
				'stylesheet' => false
			],
			'pro_tabs' => [
				'title' => 'Pro Tabs',
				'file_name' => 'x-tabs',
				'docs_slug' => 'pro-tabs'
			],
			'reading_progress_bar' =>[
				'title' => 'Reading Progress Bar',
				'file_name' => 'x-reading-progress-bar',
				'docs_slug' => 'reading-progress-bar'
			],
			'read_more_less' =>[
				'title' => 'Read More / Less',
				'file_name' => 'x-read-more-less',
				'docs_slug' => 'read-more-less'
			],
			'shortcode_wrapper' =>[
				'title' => 'Shortcode Wrapper',
				'file_name' => 'x-shortcode-wrapper',
				'docs_slug' => 'shortcode-wrapper',
				'stylesheet' => false
			],
			'breadcrumbs' => [
				'title' => 'Site Breadcrumbs',
				'file_name' => 'x-breadcrumbs',
				'docs_slug' => 'site-breadcrumbs'
			],
			'slide_menu' =>[
				'title' => 'Slide menu',
				'file_name' => 'x-slide-menu',
				'docs_slug' => 'slide-menu'
			],
			'social_share' =>[
				'title' => 'Social Share',
				'file_name' => 'x-social-share',
				'docs_slug' => 'social-share'
			],
			'star_rating' =>[
				'title' => 'Star Rating',
				'file_name' => 'x-star-rating',
				'docs_slug' => 'star-rating'
			],
			'table_of_contents' =>[
				'title' => 'Table of Contents',
				'file_name' => 'x-table-of-contents',
				'docs_slug' => 'table-of-contents'
			],
			'toggle_switch' =>[
				'title' => 'Toggle Switch',
				'file_name' => 'x-toggle-switch',
				'docs_slug' => 'toggle-switch'
			],
			'wpgb_facets' =>[
				'title' => 'WPGB Facet Styler',
				'file_name' => 'x-wpgb-facet-styler',
				'docs_slug' => 'wpgb-facet-styler',
				'element' => false,
			],
			'ws_forms' =>[
				'title' => 'WS Form',
				'file_name' => 'x-ws-forms',
				'docs_slug' => 'ws-forms'
			],
			
		];

		

	}

	function set_conditions() {
		$this->conditions = Helpers::getExtrasConditions('general');
	}

	function set_member_conditions() {
		$this->memberConditions = Helpers::getExtrasConditions('member');
	}
	
	function set_wc_conditions() {
		$this->wcConditions = Helpers::getExtrasConditions('wc');
	}

	function set_misc_options() {
		$this->miscOptions = Helpers::getExtrasMiscOptions();
	}

	function register_options() {

		/* elements/features  */
		foreach ( $this->elements as $key => $element ) {

				add_option( $this->prefix . $key, 0 );
				register_setting( $this->prefix . 'settings', $this->prefix . $key, array( $this, 'sanitize_enable' ) );
			
		}

		/* conditons */
		foreach ( $this->conditions as $key => $condition ) {

			add_option( $this->prefix . $key, 0 );
			register_setting( $this->prefix . 'conditions_settings', $this->prefix . $key, array( $this, 'sanitize_enable' ) );
		
		}

		/* member conditons */
		foreach ( $this->memberConditions as $key => $memberCondition ) {

			add_option( $this->prefix . $key, 0 );
			register_setting( $this->prefix . 'conditions_settings', $this->prefix . $key, array( $this, 'sanitize_enable' ) );
		
		}
		
		/* WooCommerce conditons */
		foreach ( $this->wcConditions as $key => $wcCondition ) {

			add_option( $this->prefix . $key, 0 );
			register_setting( $this->prefix . 'conditions_settings', $this->prefix . $key, array( $this, 'sanitize_enable' ) );
		
		}

		/* Misc features */
		foreach ( $this->miscOptions as $key => $miscOption ) {

			add_option( $this->prefix . $key, 0 );
			register_setting( $this->prefix . 'misc_options', $this->prefix . $key, array( $this, 'sanitize_enable' ) );
		
		}

		
	}

	function options_form() {

		foreach ( $this->elements as $key => $element ) {  ?>
			
			<tr valign="top"<?php echo get_option( $this->prefix . $key ) === '1' ? ' class="active"' : ' class="inactive"'; ?>>
				<th class="check-column">
					<input id="<?php echo $this->prefix . $key; ?>" name="<?php echo $this->prefix . $key; ?>" type="checkbox" value="1" <?php checked( get_option( $this->prefix . $key ), 1 ); ?> />
				</th>
				<td class="plugin-title column-primary">
					<?php echo '<strong>' . $element['title'] . '</strong>'; ?>
				</td>
				<th class="doc-link-th" style="text-align: end; padding-right: 10px;">
					<?php echo '<p class="doc-link"><a target="_blank" href="https://bricksextras.com/docs/' . $element['docs_slug'] . '">Doc</a></p>'; ?>
				</th>
			</tr>
			<?php 
		}

	}

	function conditions_form_options() {

		foreach ( $this->conditions as $key => $condition ) {  ?>
			
			<tr valign="top"<?php echo get_option( $this->prefix . $key ) === '1' ? ' class="active"' : ' class="inactive"'; ?>>
				<th class="check-column">
					<input id="<?php echo $this->prefix . $key; ?>" name="<?php echo $this->prefix . $key; ?>" type="checkbox" value="1" <?php checked( get_option( $this->prefix . $key ), 1 ); ?> />
				</th>
				<td class="plugin-title column-primary">
					<?php echo '<strong>' . $condition['title'] . '</strong>'; ?>
				</td>
				<th class="doc-link-th" style="text-align: end; padding-right: 10px;">
					<?php echo '<p class="doc-link"><a target="_blank" href="https://bricksextras.com/docs/' . $condition['docs_slug'] . '">Doc</a></p>'; ?>
				</th>
			</tr>
			<?php 
		}

	}

	function member_conditions_form_options() {

		foreach ( $this->memberConditions as $key => $memberCondition ) {  ?>
			
			<tr valign="top"<?php echo get_option( $this->prefix . $key ) === '1' ? ' class="active"' : ' class="inactive"'; ?>>
				<th class="check-column">
					<input id="<?php echo $this->prefix . $key; ?>" name="<?php echo $this->prefix . $key; ?>" type="checkbox" value="1" <?php checked( get_option( $this->prefix . $key ), 1 ); ?> />
				</th>
				<td class="plugin-title column-primary">
					<?php echo '<strong>' . $memberCondition['title'] . '</strong>'; ?>
				</td>
				<th class="doc-link-th" style="text-align: end; padding-right: 10px;">
					<?php echo '<p class="doc-link"><a target="_blank" href="https://bricksextras.com/docs/' . $memberCondition['docs_slug'] . '/">Doc</a></p>'; ?>
				</th>
			</tr>
			<?php 
		}

	}
	
	function wc_conditions_form_options() {

		foreach ( $this->wcConditions as $key => $wcCondition ) {  ?>
			
			<tr valign="top"<?php echo get_option( $this->prefix . $key ) === '1' ? ' class="active"' : ' class="inactive"'; ?>>
				<th class="check-column">
					<input id="<?php echo $this->prefix . $key; ?>" name="<?php echo $this->prefix . $key; ?>" type="checkbox" value="1" <?php checked( get_option( $this->prefix . $key ), 1 ); ?> />
				</th>
				<td class="plugin-title column-primary">
					<?php echo '<strong>' . $wcCondition['title'] . '</strong>'; ?>
				</td>
				<th class="doc-link-th" style="text-align: end; padding-right: 10px;">
					<?php echo '<p class="doc-link"><a target="_blank" href="https://bricksextras.com/docs/' . $wcCondition['docs_slug'] . '">Doc</a></p>'; ?>
				</th>
			</tr>
			<?php 
		}

	}

	function misc_form_options() {

		foreach ( $this->miscOptions as $key => $miscOption ) {  ?>
			
			<tr valign="top"<?php echo get_option( $this->prefix . $key ) === '1' ? ' class="active"' : ' class="inactive"'; ?>>
				<th class="check-column">
					<input id="<?php echo $this->prefix . $key; ?>" name="<?php echo $this->prefix . $key; ?>" type="checkbox" value="1" <?php checked( get_option( $this->prefix . $key ), 1 ); ?> />
				</th>
				<td class="plugin-title column-primary">
					<?php echo '<strong>' . $miscOption['title'] . '</strong>'; ?>
					<?php echo isset( $miscOption['description'] ) ? $miscOption['description'] : '' ?>
				</td>
				<th class="doc-link-th" style="text-align: end; padding-right: 10px;">
					<?php echo '<p class="doc-link"><a target="_blank" href="https://bricksextras.com/docs/' . $miscOption['docs_slug'] . '">Doc</a></p>'; ?>
				</th>
			</tr>
			<?php 
		}

	}

	function load_files() {

		$element_dir = BRICKSEXTRAS_PATH . 'components/classes/';

		foreach ( $this->elements as $key => $element ) {
			
			if ( 0 === intval( get_option( $this->prefix . $key, 0 ) ) ) {
				continue;
			}

			if ( isset( $element['element'] ) ) {
				continue;
			}

			$file = $element_dir . $element['file_name'] . '.php';
			$name = str_replace( '-', '', $element['file_name'] );
			$class_name = str_replace( '-', '_', $element['file_name'] );
			$class_name = ucwords( $class_name, '_' );

			/* Example output for each element for Bricks' register_element() function ..

				$file = BRICKSEXTRAS_PATH . 'components/classes/x-burger-trigger.php';
				$name = xburgertrigger ( this is the $name from the element class, no dashes )
				$class_name = X_Burger_Trigger ( the Class of the element, always capitalized, underscore not dashes )

			*/

			// Register all elements in builder & frontend 
			\Bricks\Elements::register_element( $file, $name, $class_name );

			/* include styles in builder */
			if ( bricks_is_builder_iframe() ) {

				if ( !isset( $element['stylesheet'] ) ) {
					$element['stylesheet'] = true;
				}

				if ( true === $element['stylesheet'] ) {
					$stylesheet = ltrim( str_replace( '-', '', $element['file_name'] ) , "x");
				} else if ( false !== $element['stylesheet'] ) {
					$stylesheet = $element['stylesheet'];
				}

				if ( !!$element['stylesheet'] ) {

					if ('xmodalnestable' === $name) {
						$handle = 'x-modal';
					} else if ('xoffcanvasnestable' === $name) {
						$handle = 'x-offcanvas';
					} else {
						$handle = $element['file_name'];
					}

					wp_enqueue_style( $element['file_name'], BRICKSEXTRAS_URL . 'components/assets/css/' . $stylesheet . '.css', [], $this->version );

				}

			}

		}

		BricksExtrasInteractive::init();

		if ( get_option( $this->prefix . 'interactions') ) {
			BricksExtrasInteractions::init($this->prefix);
		}

		if ( get_option( $this->prefix . 'query_loop_extras') ) {
			if ( ! class_exists( 'BricksExtrasQueryLoop' ) ) {
				require_once 'BricksExtrasQueryLoop.php';
			}
	
			(new BricksExtrasQueryLoop())->init($this->prefix);
		}

		

		$providers = [
			'extras'
		];

		if ( class_exists("\Bricks\Integrations\Dynamic_Data\Providers\Base") ) {
			BricksExtrasProviders::register($providers);
		}

		if ( ! class_exists( 'BricksExtrasHelpers' ) ) {
			require_once BRICKSEXTRAS_PATH . '/includes/BricksExtrasHelpers.php';
		}
		
	}


	function conditional_default_CSS( $version ) {	

			
			global $bricksExtrasElementCSSAdded;
			$bricksExtrasElementCSSAdded['version'] = $this->version;

			$elementsOnPageArray = \BricksExtras\Helpers::getElementsOnPage();

			$elementsOnPageNames = array_column($elementsOnPageArray, 'name');
				
					// find all the active elements
					foreach ( $this->elements as $key => $element ) {

						if ( 0 === intval( get_option( $this->prefix . $key, 0 ) ) ) {
							continue;
						}

						$name = str_replace( '-', '', $element['file_name'] );
		
						$bricksExtrasElementCSSAdded[$name] = false;

						// check if our elements are being used on the page
						if ( in_array( $name, $elementsOnPageNames ) )  {

							// enqueue neccessary styles in head for that page

							if ( !isset( $element['stylesheet'] ) ) {
								$element['stylesheet'] = true;
							}

							if ( true === $element['stylesheet'] ) {
								$stylesheet = ltrim( str_replace( '-', '', $element['file_name'] ) , "x");
							} else if ( false !== $element['stylesheet'] ) {
								$stylesheet = $element['stylesheet'];
							}

							if ( !!$element['stylesheet'] ) {

								$bricksExtrasElementCSSAdded[$name] = true;
						
								// make sure the new versions of modal/offcanvas use the same CSS as the older ones.
								if ('xpromodalnestable' === $name) {
									$handle = 'x-modal';
								} else if ('xoffcanvasnestable' === $name) {
									$handle = 'x-offcanvas';
								} else {
									$handle = $element['file_name'];
								}

								if ('xproslider' === $name) {
									wp_enqueue_style( 'bricks-splide' );
								}

								if ( \Bricks\Database::get_setting( 'cssLoading' ) === 'file' || 'xwsforms' === $name ) {
									wp_enqueue_style( $handle, BRICKSEXTRAS_URL . 'components/assets/css/' . $stylesheet . '.css', [], $this->version );
								} else {
									$minified_css = false;

									/* check for bricks v1.8.1 - Bricks changed helper function from get_file_contents to file_get_contents */
									if ( method_exists( '\Bricks\Helpers', 'get_file_contents' ) ) {
										$minified_css = \Bricks\Assets::minify_css( \Bricks\Helpers::get_file_contents( BRICKSEXTRAS_URL . 'components/assets/css/' . $stylesheet . '.css' ) );
									} else if ( method_exists( '\Bricks\Helpers', 'file_get_contents' ) ) {
										$minified_css = \Bricks\Assets::minify_css( \Bricks\Helpers::file_get_contents( BRICKSEXTRAS_PATH . 'components/assets/css/' . $stylesheet . '.css' ) );
									} 
									if ( $minified_css ) {
										wp_add_inline_style( 'bricks-frontend', $minified_css );
									} else {
										wp_enqueue_style( $handle, BRICKSEXTRAS_URL . 'components/assets/css/' . $stylesheet . '.css', [], $this->version );
									}
								}

							}

						}

				}

	}


	function interactions_script() {
		wp_enqueue_script( 'x-interactions', BRICKSEXTRAS_URL . 'includes/js/interactions.min.js', ['bricks-scripts'], $this->version, true );
	}

	function frontend_scripts() {

		/* WPGridbuilder facet support */
		if ( defined( 'WPGB_VERSION' ) ) {

			$elementsOnPageArray = \BricksExtras\Helpers::getElementsOnPage();
			$elementsOnPageNames = array_column($elementsOnPageArray, 'name');

			if ( in_array("wpgb-facet", $elementsOnPageNames) ) {

				if ( get_option( $this->prefix . 'wpgb_facets') ) {
					wp_enqueue_style(  'wpgb-facets-extras', BRICKSEXTRAS_URL . 'components/assets/css/wpgb-facets.css',['wpgb-facets'], $this->version );
				}

				wp_enqueue_script( 'wpgb-extras', BRICKSEXTRAS_URL . 'includes/js/wpgb-extras.js', ['wpgb-facets'], $this->version, true );
			}
		}

		/* Jet Smart Filters support */
		if ( class_exists( 'Jet_Smart_Filters' ) ) {
			wp_enqueue_script( 'jsf-extras', BRICKSEXTRAS_URL . 'includes/js/jsf-extras.js', ['jet-smart-filters'], $this->version, true );
		}

		/* Piotnet Grid Filters support */
		if ( class_exists( 'piotnetgrid' ) ) {
			wp_enqueue_script( 'piotnet-extras', BRICKSEXTRAS_URL . 'includes/js/piotnet-extras.js', ['piotnetgrid-script'], $this->version, true );
		}
		

	}

	function builder_scripts() {

		if ( get_option( $this->prefix . 'x_ray') ) {
			wp_enqueue_script( 'bricks-editor', BRICKSEXTRAS_URL . 'includes/js/editor.js', [], '1.0.0', true );
		}

	}

	function iframe_scripts() {

		if ( get_option( $this->prefix . 'wpgb_facets') ) {
			wp_enqueue_style(  'wpgb-facets-extras', BRICKSEXTRAS_URL . 'components/assets/css/wpgb-facets.css',[], $this->version );
		}

	}

	function boxed_header_styles() {

			$boxed = false;
			$headerWidth = '';

			if ( !empty( \Bricks\Database::$page_settings['siteLayout'] ) && !empty( \Bricks\Database::$page_settings['siteLayoutBoxedMaxWidth'] ) ) {
				if ('boxed' === \Bricks\Database::$page_settings['siteLayout'] ) {
					$boxed = true;
				}
				$headerWidth = \Bricks\Database::$page_settings['siteLayoutBoxedMaxWidth'];
			}
			
			elseif ( !empty( \Bricks\Theme_Styles::$active_settings['general']['siteLayout'] ) && !empty( \Bricks\Theme_Styles::$active_settings['general']['siteLayoutBoxedMaxWidth'] ) ) {
				if ('boxed' ===  \Bricks\Theme_Styles::$active_settings['general']['siteLayout']) {
					$boxed = true;
				}
				$headerWidth = \Bricks\Theme_Styles::$active_settings['general']['siteLayoutBoxedMaxWidth'];
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
					max-width: ' . $headerWidth . ';
				}
				</style>';

			}
	}


	function proslider_builder_init ( $attributes ) {

		$initProSlider = false;

		/* allow for init if prefered */
		$initProSlider = apply_filters( 'bricksextras/proslider/builderinit', $initProSlider );

		if ( $initProSlider ) {
			$attributes['data-x-proslider-init'] = 'true';
		}

		return $attributes;

	}



}