<?php
/**
 * Frames Plugin class file.
 *
 * @package Frames_Client
 */

namespace Frames_Client;

use Frames_Client\UI\Settings_Page\Plugin_Updater;
use Frames_Client\Helpers\Logger;
use Frames_Client\Traits\Singleton;
use Frames_Client\UI\Settings_Page\Settings_Page;

/**
 * Plugin class.
 */
class Plugin {

	use Singleton;

	/**
	 * All of the instances.
	 *
	 * @var array
	 */
	private $components = array();

	/**
	 * Option name for locking the plugin during the database upgrade process
	 *
	 * @var string.
	 */
	public const FRAMES_DATABASE_UPGRADE_LOCK_OPTION = 'frames_client_database_upgrade_lock';

	/**
	 * Option name for locking the plugin during the plugin deletion process.
	 *
	 * @var string.
	 */
	public const FRAMES_DATABASE_DELETE_LOCK_OPTION = 'frames_client_database_delete_lock';

	/**
	 * Initialize the Plugin.
	 */
	public function init() {
		// (de)activation hooks.
		register_activation_hook( FRAMES_PLUGIN_FILE, array( $this, 'activate_plugin' ) );
		register_deactivation_hook( FRAMES_PLUGIN_FILE, array( $this, 'deactivate_plugin' ) );
		$this->components['logger'] = new Logger();
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$is_acss_active = is_plugin_active( 'automaticcss-plugin/automaticcss-plugin.php' );
		$acss_plugin_data = defined( 'ACSS_PLUGIN_FILE' ) ? get_plugin_data( ACSS_PLUGIN_FILE, true, false ) : array();
		// admin hooks.
		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'maybe_update_plugin' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( FRAMES_PLUGIN_FILE ), array( $this, 'add_action_links' ) );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
			if ( ! $is_acss_active ) {
				add_action( 'admin_notices', array( $this, 'acss_disabled_notice' ) );
			} else if ( $acss_plugin_data && version_compare( $acss_plugin_data['Version'], '2.7.1-rc-1', '<' ) ) {
				add_action( 'admin_notices', array( $this, 'acss_update_needed_2_7_1' ) );
			}
		}
		$this->components['widgets'] = Widget_Manager::get_instance()->init();
		Logger::log( sprintf( "[%s]\n%s - Plugin version %s - requested by %s", gmdate( 'd-M-Y H:i:s' ), __METHOD__, self::get_plugin_version(), Logger::get_redacted_uri() ) );
		Logger::log( sprintf( '%s: is_acss_active: %s', __METHOD__, ( $is_acss_active ? 'true' : 'false' ) ) );
		$this->components['settings_page'] = Settings_Page::get_instance()->init();
		$this->components['frames_updater'] = Plugin_Updater::get_instance()->init();
	}

	/**
	 * Handle the plugin's activation
	 */
	public function activate_plugin() {
		do_action( 'frames_client/plugin/activate/start' );
		// possibly other stuff...
		do_action( 'frames_client/plugin/activate/end' );
	}

	/**
	 * Handle plugin's deactivation by (maybe) cleaning up after ourselves
	 *
	 * @return void
	 */
	public function deactivate_plugin() {
		do_action( 'frames_client/plugin/deactivate/start' );
		// possibly other stuff...
		do_action( 'frames_client/plugin/deactivate/end' );
	}

	/**
	 * Handle the plugin's update, if current version and last db saved version don't match.
	 *
	 * All the hooks in the upgrader_* family are not suitable because they will run the code
	 * from before the update was carried over while the files and directories have been updated.
	 * That means if your upgrader_* hook calls a function/method/namespace that is no longer
	 * present in the new code, that's going to cause a fatal error.
	 *
	 * @return void
	 */
	public function maybe_update_plugin() {
		// STEP: Check if the plugin is locked during the database upgrade process.
		$lock = get_option( self::FRAMES_DATABASE_UPGRADE_LOCK_OPTION, false );
		Logger::log( sprintf( '%s: starting with lock = %b', __METHOD__, $lock ) );
		if ( $lock ) {
			// We're already running the upgrade process.
			Logger::log( sprintf( '%s: upgrade process already running, skipping', __METHOD__ ) );
			return;
		}
		// STEP: set the lock.
		update_option( self::FRAMES_DATABASE_UPGRADE_LOCK_OPTION, true );
		// STEP: run the updates.
		$plugin_version = $this->get_plugin_version();
		$db_version = get_option( 'frames_client_db_version' );
		if ( false === $db_version ) {
			// This is a new installation or someone deleted the option.
			Logger::log( sprintf( '%s: new installation or option deleted, skipping updates.', __METHOD__ ) );
		} else if ( $plugin_version !== $db_version ) {
			Logger::log(
				sprintf(
					'%s: db_version (%s) differs from plugin_version (%s) => running updates.',
					__METHOD__,
					$db_version,
					$plugin_version
				)
			);
			// run updates.
			do_action( 'frames_client/plugin/update/start', $plugin_version, $db_version );
			// possibly other stuff...
			do_action( 'frames_client/plugin/update/end', $plugin_version, $db_version );
			Logger::log( sprintf( '%s: updates done.', __METHOD__ ) );
		}
		// STEP: update the db_version.
		update_option( 'frames_client_db_version', $plugin_version );
		// STEP: remove the lock.
		update_option( self::FRAMES_DATABASE_UPGRADE_LOCK_OPTION, false );
		Logger::log( sprintf( '%s: done', __METHOD__ ) );
	}

	/**
	 * Delete plugin's data.
	 *
	 * @return void
	 */
	public function delete_plugin_data() {
		// STEP: check the lock.
		$lock = get_option( self::FRAMES_DATABASE_DELETE_LOCK_OPTION, false );
		Logger::log( sprintf( '%s: starting with lock = %b', __METHOD__, $lock ) );
		if ( $lock ) {
			// We're already running the upgrade process.
			Logger::log( sprintf( '%s: upgrade process already running, skipping', __METHOD__ ) );
			return;
		}
		// STEP: set the lock.
		update_option( self::FRAMES_DATABASE_DELETE_LOCK_OPTION, true );
		// STEP: delete the data.
		do_action( 'frames_client/plugin/delete/start' );
		// possibly other stuff...
		do_action( 'frames_client/plugin/delete/end' );
		delete_option( self::FRAMES_DATABASE_UPGRADE_LOCK_OPTION );
		// STEP: remove the lock.
		delete_option( self::FRAMES_DATABASE_DELETE_LOCK_OPTION );
		Logger::log( sprintf( '%s: done', __METHOD__ ) );
	}

	/**
	 * Enqueue admin scripts & styles.
	 *
	 * @param string $hook The current admin page.
	 * @return void
	 */
	public function admin_enqueue_assets( $hook ) {
		$stylesheets = apply_filters( 'frames_admin_stylesheets', array() );
		foreach ( $stylesheets as $stylesheet => $options ) {
			if (
				! array_key_exists( 'hook', $options )
				|| ( is_string( $options['hook'] ) && $hook === $options['hook'] )
				|| ( is_array( $options['hook'] ) && in_array( $hook, $options['hook'] ) )
			) {
				$file = isset( $options['filename'] ) ? FRAMES_ASSETS_URL . $options['filename'] : $options['url'];
				$version = isset( $options['filename'] ) ? filemtime( FRAMES_ASSETS_DIR . $options['filename'] ) : $options['version'];
				wp_enqueue_style(
					$stylesheet,
					$file,
					$options['dependency'],
					$version,
					'all'
				);
			}
		}
		$scripts = apply_filters( 'frames_admin_scripts', array() );
		foreach ( $scripts as $script => $options ) {
			if (
				! array_key_exists( 'hook', $options )
				|| ( is_string( $options['hook'] ) && $hook === $options['hook'] )
				|| ( is_array( $options['hook'] ) && in_array( $hook, $options['hook'] ) )
			) {
				$file = isset( $options['filename'] ) ? FRAMES_ASSETS_URL . $options['filename'] : $options['url'];
				$version = isset( $options['filename'] ) ? filemtime( FRAMES_ASSETS_DIR . $options['filename'] ) : $options['version'];
				wp_enqueue_script(
					$script,
					$file,
					$options['dependency'],
					$version,
					true
				);
				if ( ! empty( $options['localize'] ) && ! empty( $options['localize']['name'] ) && ! empty( $options['localize']['options'] ) ) {
					wp_localize_script( $script, $options['localize']['name'], $options['localize']['options'] );
				}
			}
		}
	}

		/**
		 * Add action links to the plugin's row in the plugins list.
		 *
		 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		 * @param array $actions The current links.
		 * @return array The links with the new ones added.
		 */
	public function add_action_links( $actions ) {
		$links = array(
			'<a href="' . admin_url( 'admin.php?page=frames&tab=license' ) . '">License</a>',
		);
		$actions = array_merge( $links, $actions );
		return $actions;
	}

	/**
	 * Add a link to the plugin's row in the plugins list.
	 *
	 * @param array  $plugin_meta The current links.
	 * @param string $plugin_file The plugin file.
	 * @return array The links with the new ones added.
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		$frames_plugin_file = plugin_basename( FRAMES_PLUGIN_FILE );
		if ( $plugin_file === $frames_plugin_file ) {
			$links = array(
				'guide' => '<a href="https://community.automaticcss.com/s/frames/" target="_blank">User Guide</a>',
				'faq'   => '<a href="https://community.automaticcss.com/c/faqs-00d937/" target="_blank">FAQs</a>'
			);
			$plugin_meta = array_merge( $plugin_meta, $links );
		}
		return $plugin_meta;
	}

	/**
	 * Warn the user if the Automatic.css plugin is disabled.
	 *
	 * @return void
	 */
	public function acss_disabled_notice() {
		$class = 'notice notice-error';
		$message = __( 'Automatic.css is disabled! Frames will not work correctly. Please activate the Automatic.css plugin', 'frames' );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

	/**
	 * Warn the user if the Automatic.css plugin needs updating.
	 *
	 * @return void
	 */
	public function acss_update_needed_2_7_1() {
		$class = 'notice notice-error';
		$message = __( 'Please update Automatic.css to version 2.7.1 or later in order for the Color Scheme component to work.', 'frames' );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

	/**
	 * Get the plugin's Version
	 *
	 * @return string
	 */
	public static function get_plugin_version() {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_data = get_plugin_data( FRAMES_PLUGIN_FILE, true, false );
		$version = $plugin_data['Version'];
		return $version;
	}

	/**
	 * Get the plugin's Author
	 *
	 * @return string
	 */
	public static function get_plugin_author() {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_data = get_plugin_data( FRAMES_PLUGIN_FILE, true, false );
		$author = $plugin_data['Author'];
		return $author;
	}

	/**
	 * Get the directory where we store the dynamic CSS files.
	 * If it doesn't exist, create it.
	 *
	 * This was added to support plugins like S3 Offload that alter the uploads_dir.
	 *
	 * @since 1.4.1
	 * @return string
	 */
	public static function get_dynamic_css_dir() {
		$wp_upload_dir = wp_upload_dir();
		$acss_uploads_dir = trailingslashit( $wp_upload_dir['basedir'] ) . 'frames';
		if ( ! file_exists( $acss_uploads_dir ) ) {
			wp_mkdir_p( $acss_uploads_dir );
		}
		return $acss_uploads_dir;
	}

	/**
	 * Get the URL where we store the dynamic CSS files.
	 *
	 * This was added to support plugins like S3 Offload that alter the uploads_dir.
	 *
	 * @since 1.4.1
	 * @return string
	 */
	public static function get_dynamic_css_url() {
		$wp_upload_dir = wp_upload_dir();
		return trailingslashit( set_url_scheme( $wp_upload_dir['baseurl'] ) ) . 'frames';
	}
}
