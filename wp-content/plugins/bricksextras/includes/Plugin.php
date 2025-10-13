<?php

namespace BricksExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'BricksExtrasUpdater' ) ) {
	// load our custom updater.
	require_once 'BricksExtrasUpdater.php';
}

if ( ! class_exists( 'BricksExtrasLicense' ) ) {
	require_once 'BricksExtrasLicense.php';
}

if ( ! class_exists( 'SettingsPage' ) ) {
	require_once 'SettingsPage.php';
}

if ( ! class_exists( 'ConditionsPage' ) ) {
	require_once 'ConditionsPage.php';
}

if ( ! class_exists( 'MiscPage' ) ) {
	require_once 'MiscPage.php';
}

if ( ! class_exists( 'ChangelogPage' ) ) {
	require_once 'ChangelogPage.php';
}

class Plugin {

	const PREFIX    = 'bricksextras_';
	const TITLE     = 'BricksExtras';
	const VERSION   = '1.5.3';
	const STORE_URL = 'https://bricksextras.com';
	const ITEM_ID   = 367;

	public function __construct() {
		/* Runs when the plugin is activated */
		add_action( 'activate_' . BRICKSEXTRAS_BASE, array( __CLASS__, 'activate' ), 10, 2 );

		/* needs to run early, on the plugins_loaded to be picked up by Bricks */
		add_action( 'plugins_loaded', array( __CLASS__, 'conditions_init' ), 1 );

		/* Fires after WordPress has finished loading but before any headers are sent */
		add_action( 'init', array( __CLASS__, 'bricksextras_init' ), 30 );

		/* EDD licenseing activation form */
		BricksExtrasLicense::init( self::PREFIX, self::TITLE, self::STORE_URL, self::ITEM_ID );

		/* Add admin menu item */
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ), 11 );

		/* Add text strings to builder */
		add_filter( 'bricks/builder/i18n', [ __CLASS__, 'x_i18n' ]);

		/* Add settings link to plugin page */
		add_filter( 'plugin_action_links_' . BRICKSEXTRAS_BASE, array( __CLASS__, 'settings_link' ) );

		/* Setup the plugin updater */
		add_action( 'init', [ __CLASS__, 'plugin_updater' ], 0 );

		/* admin setting styles */
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_settings_styles'] );

		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'builder_styles'] );

		if ( get_option( 'bricksextras_lottie' ) ) {	
			add_filter( 'upload_mimes', [ __CLASS__, 'upload_mimes_json' ] );
		};
		
		/* Clean database on uninstall */
		register_uninstall_hook( BRICKSEXTRAS_BASE, [ __CLASS__, 'clean_db_on_uninstall' ] );

		
	
	}

	/* Add text strings to builder */
	public static function x_i18n( $i18n ) {
	
		// For element category 'extras'
		$i18n['extras'] = esc_html__( 'Extras', 'bricks' );

		return $i18n;
	
	}

	/* Runs when the plugin is activated */
	public static function activate( $plugin ) {
		if ( ! function_exists( 'bricks_is_builder' ) ) {
			die( '<p>\'Bricks builder\' must be installed and activated, in order to activate \'' . self::TITLE . '\'</p>' );
		}
	}

	/* Add admin menu item */
	public static function admin_menu() {

		if ( ! class_exists('\Bricks\Capabilities') ) {
			return;
		}

		// Return: Current user has no access to Bricks
		if ( \Bricks\Capabilities::current_user_has_no_access() ) {
			return;
		}

		global $menu;
		$menu_exists = false;

		foreach ( $menu as $item ) {
			if ( array_search( 'bricks', $item ) !== false ) {
				$menu_exists = true;
				break;
			}
		}

		add_submenu_page( 'bricks', self::TITLE, self::TITLE, 'manage_options', self::PREFIX . 'menu', array( __CLASS__, 'menu_item' ) );
	}

	/* Add settings link to plugin page */
	public static function settings_link( $links ) {
		$url = esc_url(
			add_query_arg(
				'page',
				self::PREFIX . 'menu',
				get_admin_url() . 'admin.php'
			)
		);

		// Create the link.
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';

		// Adds the link to the beginning of the array.
		array_unshift(
			$links,
			$settings_link
		);

		return $links;
	}

	/* Setup the plugin updater */
	public static function plugin_updater() {

		// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
		$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
		if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
			return;
		}

		// retrieve our license key from the DB.
		$license_key = trim( get_option( self::PREFIX . 'license_key' ) );

		// setup the updater.
		$edd_updater = new BricksExtrasUpdater(
			self::STORE_URL,
			__FILE__,
			array(
				'version'   => self::VERSION, // current version number
				'license'   => $license_key, // license key (used get_option above to retrieve from DB)
				'item_id'   => self::ITEM_ID, // ID of the product
				'item_name' => self::TITLE,
				'author'    => 'BricksExtras', // author of this plugin
				'url'       => home_url(),
				'beta'      => false,
			)
		);
	}

	/* Clean database on uninstall */
	public static function clean_db_on_uninstall() {
		foreach ( wp_load_alloptions() as $option => $value ) {
			if ( strpos( $option, 'bricksextras_' ) === 0 ) {
				delete_option( $option );
			}
		}
	}

	/* Settings page styles */
	public static function admin_settings_styles($hook_suffix) {
		wp_enqueue_style( 'x_admin_css', BRICKSEXTRAS_URL . 'includes/css/admin.css', false, '1.0.0' );
	}

	public static function builder_styles() {

		if ( !function_exists( 'bricks_is_builder' ) ) {
			return;
		}

		if ( bricks_is_builder() && ! bricks_is_builder_main() ) {
			wp_enqueue_style( 'x_builder_css', BRICKSEXTRAS_URL . 'includes/css/builder.css', false, '1.0.2' );
		} 

		if ( bricks_is_builder_main() ) {
			wp_enqueue_style( 'x_builder_ui_css', BRICKSEXTRAS_URL . 'includes/css/builder-ui.css', false, '1.0.0' );
		}

		if ( bricks_is_builder_iframe() ) {
			wp_enqueue_script( 'x-lottie', BRICKSEXTRAS_URL . 'components/assets/js/lottie.min.js', '', '5.9.6', true );
			wp_enqueue_script( 'x-lottie-interactivity', BRICKSEXTRAS_URL . 'components/assets/js/lottieinteractivity.min.js', '', '1.6.1', true );
			wp_enqueue_script( 'x-lottie-init', BRICKSEXTRAS_URL . 'components/assets/js/lottie.js', '', '1.0.0', true );

			wp_enqueue_script( 'x-frontend', BRICKSEXTRAS_URL . 'components/assets/js/frontend.js', '', '1.0.0', true );
			wp_enqueue_script( 'x-iframe', BRICKSEXTRAS_URL . 'includes/js/iframe.js', '', '1.0.1', true );
		}
		
	}

	

	/* Settings page tabs */
	public static function menu_item() {
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : false;
		?>
		<div class="wrap bricksextras-settings">
			<h1>BricksExtras Settings</h1>
			<br>
			<h2 class="nav-tab-wrapper">
				<a href="?page=<?php echo self::PREFIX . 'menu'; ?>&amp;tab=settings" class="nav-tab<?php echo ( $tab === false || $tab == 'settings' ) ? ' nav-tab-active' : ''; ?>">Elements</a>
				<a href="?page=<?php echo self::PREFIX . 'menu'; ?>&amp;tab=conditions" class="nav-tab<?php echo $tab == 'conditions' ? ' nav-tab-active' : ''; ?>">Conditions</a>
				<a href="?page=<?php echo self::PREFIX . 'menu'; ?>&amp;tab=misc" class="nav-tab<?php echo $tab == 'misc' ? ' nav-tab-active' : ''; ?>">Misc</a>
				<a href="?page=<?php echo self::PREFIX . 'menu'; ?>&amp;tab=license" class="nav-tab<?php echo $tab == 'license' ? ' nav-tab-active' : ''; ?>">License</a>
				<a href="?page=<?php echo self::PREFIX . 'menu'; ?>&amp;tab=changelog" class="nav-tab<?php echo $tab == 'changelog' ? ' nav-tab-active' : ''; ?>">Changelog</a>
			</h2>
			<div class="bricks-admin-wrapper">
			<?php
			if ( $tab === 'license' ) {
				BricksExtrasLicense::license_page();
			} elseif ( 'changelog' === $tab ) {
				ChangelogPage::init();
			} elseif ( 'conditions' === $tab ) {
				ConditionsPage::init( self::PREFIX, self::TITLE, self::VERSION );
			} elseif ( 'misc' === $tab ) {
				MiscPage::init( self::PREFIX, self::TITLE, self::VERSION );
			} else {
				SettingsPage::init( self::PREFIX, self::TITLE, self::VERSION );
			}
			?>
		</div></div>
		<?php
	}

	/* allow JSON inside media libary for lottie*/
	public static function upload_mimes_json( $mimes ) {
		if( current_user_can('administrator') ) {
			$mimes['json'] = 'application/json';
		}
		return $mimes;
	}

	/* Fires after WordPress has finished loading but before any headers are sent */
	public static function bricksextras_init() {
		
		if ( ! class_exists( 'BricksExtrasMain' ) ) {
			require_once 'BricksExtrasMain.php';
		}

		$BricksExtras = new BricksExtrasMain( self::PREFIX, self::VERSION );

	}

	public static function conditions_init() {

		if ( ! class_exists( 'BricksExtrasConditions' ) ) {
			require_once 'BricksExtrasConditions.php';
			(new BricksExtrasConditions())->init( self::PREFIX );
		}

	}

	
	
}