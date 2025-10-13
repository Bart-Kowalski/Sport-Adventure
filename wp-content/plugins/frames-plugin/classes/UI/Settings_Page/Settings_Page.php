<?php
/**
 * Frames Settings_Page UI file.
 *
 * @package Frames_Client
 */

namespace Frames_Client\UI\Settings_Page;

use Frames_Client\Helpers\Logger;
use Frames_Client\Traits\Singleton;

/**
 * Settings_Page UI class.
 */
class Settings_Page {

	use Singleton;

	/**
	 * Capability needed to operate the plugin
	 *
	 * @var string
	 */
	private $capability = 'manage_options';

	/**
	 * Initialize the Settings_Page class
	 *
	 * @return Settings_Page
	 */
	public function init() {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		}
		return $this;
	}

	/**
	 * Render the plugin's settings page
	 *
	 * @return void
	 */
	public function render() {
		$tab_get = filter_input( INPUT_GET, 'tab' );
		$tab = null === $tab_get ? false : sanitize_text_field( $tab_get );
		?>
		<div class="wrap frames-wrapper">
			<h1>Welcome to the Frames settings page</h1>

			<nav class="nav-tab-wrapper">
				<a href="?page=frames&tab=welcome" class="nav-tab<?php echo ( false === $tab || 'welcome' === $tab ) ? ' nav-tab-active' : ''; ?>">Welcome</a>
				<a href="?page=frames&tab=license" class="nav-tab<?php echo 'license' === $tab ? ' nav-tab-active' : ''; ?>">License</a>
				</nav>

			<div class="tab-content">
		<?php
		switch ( $tab ) :
			case 'license':
				$plugin_updater = Plugin_Updater::get_instance();
				$plugin_updater->settings_page();
				break;
			case 'welcome':
			default:
				Welcome::settings_page();
				break;
			endswitch;
		?>
			</div>
		</div> <!-- .frames-wrapper -->
		<?php
	}

	/**
	 * Enqueue admin styles
	 */
	public function enqueue_admin_styles() {
		wp_enqueue_style(
			'frames-admin',
			FRAMES_CLASSES_URL . '/UI/Settings_Page/css/frames-settings-page.css',
			array(),
			filemtime( FRAMES_CLASSES_DIR . '/UI/Settings_Page/css/frames-settings-page.css' )
		);
	}

	/**
	 * Add the plugin's settings page to the menu
	 *
	 * @return void
	 */
	public function add_plugin_page() {
		Logger::log( 'Adding plugin page' );
		if ( ! class_exists( '\Automatic_CSS\Model\Database_Settings' ) ) {
			Logger::log( sprintf( '%s: Automatic CSS database class not found', __METHOD__ ) );
			return;
		}
		$acss_database = \Automatic_CSS\Model\Database_Settings::get_instance();
		$frames_own_admin_page = 'on' === $acss_database->get_var( 'option-frames-own-admin-page' ) ?? false;
		$admin_position = $acss_database->get_var( 'admin-link-position' ) ?? 90;
		if ( $frames_own_admin_page ) {
			add_menu_page(
				'Frames', // page_title.
				'Frames', // menu_title.
				$this->capability, // capability.
				'frames', // menu_slug.
				array( $this, 'render' ), // function.
				'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4gPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2aWV3Qm94PSIwIDAgOTguODkgOTguODkiPjxkZWZzPjxzdHlsZT4uY2xzLTF7ZmlsbDp1cmwoI2xpbmVhci1ncmFkaWVudCk7fS5jbHMtMntmaWxsOiNmZjFkMjU7fS5jbHMtMywuY2xzLTR7bWl4LWJsZW5kLW1vZGU6b3ZlcmxheTt9LmNscy01e2ZpbGw6dXJsKCNyYWRpYWwtZ3JhZGllbnQtMik7fS5jbHMtNHtmaWxsOnVybCgjcmFkaWFsLWdyYWRpZW50KTt9LmNscy02e2ZpbGw6dXJsKCNsaW5lYXItZ3JhZGllbnQtOCk7fS5jbHMtN3tmaWxsOnVybCgjbGluZWFyLWdyYWRpZW50LTkpO30uY2xzLTh7ZmlsbDp1cmwoI2xpbmVhci1ncmFkaWVudC0zKTt9LmNscy05e2ZpbGw6dXJsKCNsaW5lYXItZ3JhZGllbnQtNCk7fS5jbHMtMTB7ZmlsbDp1cmwoI2xpbmVhci1ncmFkaWVudC0yKTt9LmNscy0xMXtmaWxsOnVybCgjbGluZWFyLWdyYWRpZW50LTYpO30uY2xzLTEye2ZpbGw6dXJsKCNsaW5lYXItZ3JhZGllbnQtNyk7fS5jbHMtMTN7ZmlsbDp1cmwoI2xpbmVhci1ncmFkaWVudC01KTt9LmNscy0xNHtmaWxsOnVybCgjbGluZWFyLWdyYWRpZW50LTEwKTt9LmNscy0xNXtmaWxsOnVybCgjbGluZWFyLWdyYWRpZW50LTExKTt9LmNscy0xNntpc29sYXRpb246aXNvbGF0ZTt9LmNscy0xN3tvcGFjaXR5Oi41O30uY2xzLTE3LC5jbHMtMTgsLmNscy0xOSwuY2xzLTIwLC5jbHMtMjF7ZmlsbDojYjJhZmFkO30uY2xzLTE4e29wYWNpdHk6Ljc7fS5jbHMtMTl7b3BhY2l0eTouMzt9LmNscy0yMHtvcGFjaXR5Oi40O30uY2xzLTIxe29wYWNpdHk6LjM1O308L3N0eWxlPjxsaW5lYXJHcmFkaWVudCBpZD0ibGluZWFyLWdyYWRpZW50IiB4MT0iNzkuNjUiIHkxPSI0NS4xNSIgeDI9IjY1LjQ2IiB5Mj0iMjQuMDgiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj48c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiNiYzE1MjIiPjwvc3RvcD48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNiYzE1MjIiIHN0b3Atb3BhY2l0eT0iMCI+PC9zdG9wPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJsaW5lYXItZ3JhZGllbnQtMiIgeDE9IjQ0LjM0IiB5MT0iMTEuODciIHgyPSI1MS42NiIgeTI9IjM3LjM4IiB4bGluazpocmVmPSIjbGluZWFyLWdyYWRpZW50Ij48L2xpbmVhckdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCBpZD0ibGluZWFyLWdyYWRpZW50LTMiIHgxPSIzMy4yNiIgeTE9IjQwLjc5IiB4Mj0iMjUuNTgiIHkyPSI1OS4yOCIgeGxpbms6aHJlZj0iI2xpbmVhci1ncmFkaWVudCI+PC9saW5lYXJHcmFkaWVudD48bGluZWFyR3JhZGllbnQgaWQ9ImxpbmVhci1ncmFkaWVudC00IiB4MT0iNTguMDkiIHkxPSI1Mi4zOCIgeDI9IjM2LjI0IiB5Mj0iNDQuOTUiIHhsaW5rOmhyZWY9IiNsaW5lYXItZ3JhZGllbnQiPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJsaW5lYXItZ3JhZGllbnQtNSIgeDE9IjM2LjExIiB5MT0iNTEuNzQiIHgyPSIyNi4zNSIgeTI9Ijc4LjIxIiB4bGluazpocmVmPSIjbGluZWFyLWdyYWRpZW50Ij48L2xpbmVhckdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCBpZD0ibGluZWFyLWdyYWRpZW50LTYiIHgxPSI5LjQ0IiB5MT0iMzkuMTgiIHgyPSI0NC4zMyIgeTI9IjIyLjc3IiB4bGluazpocmVmPSIjbGluZWFyLWdyYWRpZW50Ij48L2xpbmVhckdyYWRpZW50PjxyYWRpYWxHcmFkaWVudCBpZD0icmFkaWFsLWdyYWRpZW50IiBjeD0iNTcuODMiIGN5PSI1OS4xNCIgZng9IjU3LjgzIiBmeT0iNTkuMTQiIHI9IjEuNjkiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj48c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiNmZmYiPjwvc3RvcD48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNmZmYiPjwvc3RvcD48L3JhZGlhbEdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCBpZD0ibGluZWFyLWdyYWRpZW50LTciIHgxPSI3OCIgeTE9IjI5LjY3IiB4Mj0iOTcuNzgiIHkyPSIyOS42NyIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2ZmZiI+PC9zdG9wPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2ZmZiIgc3RvcC1vcGFjaXR5PSIwIj48L3N0b3A+PC9saW5lYXJHcmFkaWVudD48bGluZWFyR3JhZGllbnQgaWQ9ImxpbmVhci1ncmFkaWVudC04IiB4MT0iMzkuNTYiIHgyPSI1OS4zMyIgeGxpbms6aHJlZj0iI2xpbmVhci1ncmFkaWVudC03Ij48L2xpbmVhckdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCBpZD0ibGluZWFyLWdyYWRpZW50LTkiIHgxPSIxOS43OCIgeTE9IjQ5LjQ0IiB4Mj0iMzkuNTYiIHkyPSI0OS40NCIgeGxpbms6aHJlZj0iI2xpbmVhci1ncmFkaWVudC03Ij48L2xpbmVhckdyYWRpZW50PjxyYWRpYWxHcmFkaWVudCBpZD0icmFkaWFsLWdyYWRpZW50LTIiIGN4PSI0OS40NCIgY3k9IjQ5LjQ0IiBmeD0iNDkuNDQiIGZ5PSI0OS40NCIgcj0iMTcuNTciIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj48c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiNmZmYiPjwvc3RvcD48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNmZmYiIHN0b3Atb3BhY2l0eT0iMCI+PC9zdG9wPjwvcmFkaWFsR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJsaW5lYXItZ3JhZGllbnQtMTAiIHgxPSIxOS43OCIgeTE9IjY5LjIyIiB4Mj0iMzkuNTYiIHkyPSI2OS4yMiIgeGxpbms6aHJlZj0iI2xpbmVhci1ncmFkaWVudC03Ij48L2xpbmVhckdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCBpZD0ibGluZWFyLWdyYWRpZW50LTExIiB4MT0iMTkuNzgiIHgyPSIzOS41NiIgeGxpbms6aHJlZj0iI2xpbmVhci1ncmFkaWVudC03Ij48L2xpbmVhckdyYWRpZW50PjwvZGVmcz48ZyBjbGFzcz0iY2xzLTE2Ij48ZyBpZD0iTGF5ZXJfMiI+PGcgaWQ9IkxheWVyXzEtMiI+PGc+PGc+PHJlY3QgY2xhc3M9ImNscy0yMSIgeT0iMTkuNzgiIHdpZHRoPSIxOS43OCIgaGVpZ2h0PSIxOS43NyI+PC9yZWN0PjxyZWN0IGNsYXNzPSJjbHMtMTkiIHk9IjM5LjU1IiB3aWR0aD0iMTkuNzgiIGhlaWdodD0iMTkuNzgiPjwvcmVjdD48cmVjdCBjbGFzcz0iY2xzLTE4IiB5PSI1OS4zMyIgd2lkdGg9IjE5Ljc4IiBoZWlnaHQ9IjE5Ljc4Ij48L3JlY3Q+PHJlY3QgY2xhc3M9ImNscy0yMCIgeD0iMCIgeT0iNzkuMTEiIHdpZHRoPSIxOS43OCIgaGVpZ2h0PSIxOS43OCI+PC9yZWN0PjxyZWN0IGNsYXNzPSJjbHMtMTciIHg9IjE5Ljc4IiB5PSI3OS4xMSIgd2lkdGg9IjE5Ljc4IiBoZWlnaHQ9IjE5Ljc4Ij48L3JlY3Q+PHJlY3QgY2xhc3M9ImNscy0xNyIgeD0iMTkuNzgiIHk9IjAiIHdpZHRoPSIxOS43OCIgaGVpZ2h0PSIxOS43OCI+PC9yZWN0PjxyZWN0IGNsYXNzPSJjbHMtMjAiIHg9IjM5LjU2IiB5PSIwIiB3aWR0aD0iMTkuNzgiIGhlaWdodD0iMTkuNzgiPjwvcmVjdD48cmVjdCBjbGFzcz0iY2xzLTE5IiB4PSI1OS4zMyIgeT0iMCIgd2lkdGg9IjE5Ljc4IiBoZWlnaHQ9IjE5Ljc4Ij48L3JlY3Q+PHJlY3QgY2xhc3M9ImNscy0xNyIgeD0iNzkuMTEiIHdpZHRoPSIxOS43OCIgaGVpZ2h0PSIxOS43OCI+PC9yZWN0PjxyZWN0IGNsYXNzPSJjbHMtMjEiIHg9Ijc5LjExIiB5PSIxOS43OCIgd2lkdGg9IjE5Ljc3IiBoZWlnaHQ9IjE5Ljc3Ij48L3JlY3Q+PHJlY3QgY2xhc3M9ImNscy0xOCIgeD0iNTkuMzMiIHk9IjM5LjU1IiB3aWR0aD0iMTkuNzgiIGhlaWdodD0iMTkuNzgiPjwvcmVjdD48cmVjdCBjbGFzcz0iY2xzLTIxIiB4PSIzOS41NiIgeT0iNTkuMzMiIHdpZHRoPSIxOS43OCIgaGVpZ2h0PSIxOS43OCI+PC9yZWN0PjwvZz48Zz48cmVjdCBjbGFzcz0iY2xzLTIiIHg9IjM5LjU2IiB5PSIxOS43OCIgd2lkdGg9IjE5Ljc4IiBoZWlnaHQ9IjE5Ljc3Ij48L3JlY3Q+PHJlY3QgY2xhc3M9ImNscy0yIiB4PSIxOS43OCIgeT0iMTkuNzgiIHdpZHRoPSIxOS43OCIgaGVpZ2h0PSIxOS43NyI+PC9yZWN0PjxyZWN0IGNsYXNzPSJjbHMtMiIgeD0iNTkuMzMiIHk9IjE5Ljc4IiB3aWR0aD0iMTkuNzgiIGhlaWdodD0iMTkuNzciPjwvcmVjdD48cmVjdCBjbGFzcz0iY2xzLTIiIHg9IjE5Ljc4IiB5PSIzOS41NSIgd2lkdGg9IjE5Ljc4IiBoZWlnaHQ9IjE5Ljc4Ij48L3JlY3Q+PHJlY3QgY2xhc3M9ImNscy0yIiB4PSIzOS41NiIgeT0iMzkuNTUiIHdpZHRoPSIxOS43OCIgaGVpZ2h0PSIxOS43OCI+PC9yZWN0PjxyZWN0IGNsYXNzPSJjbHMtMiIgeD0iMTkuNzgiIHk9IjU5LjMzIiB3aWR0aD0iMTkuNzgiIGhlaWdodD0iMTkuNzgiPjwvcmVjdD48L2c+PGc+PHJlY3QgY2xhc3M9ImNscy0xIiB4PSI1OS4zMyIgeT0iMTkuNzgiIHdpZHRoPSIxOS43OCIgaGVpZ2h0PSIxOS43NyI+PC9yZWN0PjxyZWN0IGNsYXNzPSJjbHMtMTAiIHg9IjM5LjU2IiB5PSIxOS43OCIgd2lkdGg9IjE5Ljc4IiBoZWlnaHQ9IjE5Ljc3Ij48L3JlY3Q+PHJlY3QgY2xhc3M9ImNscy04IiB4PSIxOS43OCIgeT0iMzkuNTUiIHdpZHRoPSIxOS43OCIgaGVpZ2h0PSIxOS43OCI+PC9yZWN0PjxyZWN0IGNsYXNzPSJjbHMtOSIgeD0iMzkuNTYiIHk9IjM5LjU1IiB3aWR0aD0iMTkuNzgiIGhlaWdodD0iMTkuNzgiPjwvcmVjdD48cmVjdCBjbGFzcz0iY2xzLTEzIiB4PSIxOS43OCIgeT0iNTkuMzMiIHdpZHRoPSIxOS43OCIgaGVpZ2h0PSIxOS43OCI+PC9yZWN0PjxyZWN0IGNsYXNzPSJjbHMtMTEiIHg9IjE5Ljc4IiB5PSIxOS43OCIgd2lkdGg9IjE5Ljc4IiBoZWlnaHQ9IjE5Ljc3Ij48L3JlY3Q+PC9nPjxyZWN0IGNsYXNzPSJjbHMtNCIgeD0iNTcuNjMiIHk9IjU5LjMzIiB3aWR0aD0iMS43IiBoZWlnaHQ9Ii4wMyI+PC9yZWN0PjxnIGNsYXNzPSJjbHMtMyI+PHBhdGggY2xhc3M9ImNscy0xMiIgZD0iTTc4LjExLDIwLjc4djE3Ljc3aC0xNy43OFYyMC43OGgxNy43OG0xLTFoLTE5Ljc4djE5Ljc3aDE5Ljc4VjE5Ljc4aDBaIj48L3BhdGg+PHBhdGggY2xhc3M9ImNscy02IiBkPSJNNTguMzMsMjAuNzh2MTcuNzdoLTE3Ljc4VjIwLjc4aDE3Ljc4bTEtMWgtMTkuNzh2MTkuNzdoMTkuNzhWMTkuNzhoMFoiPjwvcGF0aD48cGF0aCBjbGFzcz0iY2xzLTciIGQ9Ik0zOC41Niw0MC41NXYxNy43OEgyMC43OHYtMTcuNzhoMTcuNzhtMS0xSDE5Ljc4djE5Ljc4aDE5Ljc4di0xOS43OGgwWiI+PC9wYXRoPjxwYXRoIGNsYXNzPSJjbHMtNSIgZD0iTTU4LjMzLDQwLjU1djE3Ljc4aC0xNy43OHYtMTcuNzhoMTcuNzhtMS0xaC0xOS43OHYxOS43OGgxOS43OHYtMTkuNzhoMFoiPjwvcGF0aD48cGF0aCBjbGFzcz0iY2xzLTE0IiBkPSJNMzguNTYsNjAuMzN2MTcuNzhIMjAuNzh2LTE3Ljc4aDE3Ljc4bTEtMUgxOS43OHYxOS43OGgxOS43OHYtMTkuNzhoMFoiPjwvcGF0aD48cGF0aCBjbGFzcz0iY2xzLTE1IiBkPSJNMzguNTYsMjAuNzh2MTcuNzdIMjAuNzhWMjAuNzhoMTcuNzhtMS0xSDE5Ljc4djE5Ljc3aDE5Ljc4VjE5Ljc4aDBaIj48L3BhdGg+PC9nPjwvZz48L2c+PC9nPjwvZz48L3N2Zz4g', // icon_url.
				$admin_position // position.
			);
		} else {
			add_submenu_page(
				'automatic-css', // parent slug.
				__( 'Frames' ), // page title.
				__( 'Frames' ), // menu title.
				$this->capability, // capability.
				'frames', // page slug.
				array( $this, 'render' ) // callback.
			);
		}
	}

}
