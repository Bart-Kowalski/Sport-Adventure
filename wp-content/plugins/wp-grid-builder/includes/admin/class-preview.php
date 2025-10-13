<?php
/**
 * Preview
 *
 * @package   WP Grid Builder
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 */

namespace WP_Grid_Builder\Includes\Admin;

use WP_Grid_Builder\Includes\Helpers;
use WP_Grid_Builder\Includes\Settings\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Preview plugin content in iframe
 *
 * @class WP_Grid_Builder\Includes\Admin\Preview
 * @since 2.0.0
 */
final class Preview {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'admin_post_' . WPGB_SLUG . '_preview_iframe', [ $this, 'handle' ] );
		add_action( 'wp_grid_builder/card/wrapper_start', [ $this, 'edit_button' ] );

	}

	/**
	 * Handle preview
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function handle() {

		$this->dequeue();
		$this->enqueue();

		// print_emoji_styles has been deprecated since WP 6.4, but is still used as a fallback solution.
		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		if (
			! Helpers::current_user_can() ||
			check_ajax_referer( WPGB_SLUG . '_preview_iframe', 'nonce', false ) === false
		) {

			$this->error();
			exit;

		}

		$this->render();
		exit;

	}

	/**
	 * Render iframe
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function render() {

		$settings = $this->get_settings();

		?>
		<!DOCTYPE HTML>
		<html <?php language_attributes(); ?> class="wpgb-preview-iframe">
			<head>
				<title>Iframe - Preview</title>
				<?php
					do_action( 'admin_print_styles' );
					do_action( 'admin_print_scripts' );
				?>
			</head>
			<body <?php echo ( is_rtl() ? 'class="rtl"' : '' ); ?>>
				<?php
					add_action( 'pre_get_posts', [ $this, 'sticky_posts' ] );
					wpgb_render_grid( $settings );
					remove_action( 'pre_get_posts', [ $this, 'sticky_posts' ] );
				?>
				<footer>
					<script>
						<?php echo "/* <![CDATA[ */\n"; ?>
						<?php echo 'var wpgb_preview_settings = ' . wp_json_encode( $settings ); ?>
						<?php echo "\n/* ]]> */"; ?>
					</script>
					<?php
						wpgb_enqueue_styles();
						wpgb_enqueue_scripts();
						do_action( 'admin_print_footer_scripts' );
					?>
				</footer>
			</body>
		</html>
		<?php
	}

	/**
	 * Render error
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function error() {

		?>
		<!DOCTYPE HTML>
		<html <?php language_attributes(); ?> class="wpgb-preview-iframe">
			<head>
				<title>Iframe - Preview</title>
				<?php
					do_action( 'admin_print_styles' );
					do_action( 'admin_print_scripts' );
				?>
			</head>
			<body <?php echo ( is_rtl() ? 'class="rtl"' : '' ); ?>>
				<div class="wpgb-preview-iframe__error">
					<h1 class="wpgb-preview-iframe__error-message"><?php esc_html_e( 'Sorry, an error occurred while rendering preview.', 'wp-grid-builder' ); ?></h1>
				</div>
				<footer>
					<?php
						do_action( 'admin_print_footer_scripts' );
					?>
				</footer>
			</body>
		</html>
		<?php
	}

	/**
	 * Get grid data
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_settings() {

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$request = wp_unslash( $_POST );

		if ( ! isset( $request['settings'], $request['object'] ) ) {
			return [];
		}

		$settings = $request['settings'];

		if ( is_numeric( $settings ) ) {

			return [
				'id'           => (int) $settings,
				'is_preview'   => true,
				'inline_style' => true,
			];
		}

		$settings = json_decode( $settings, true );

		if ( empty( $settings['id'] ) ) {
			return [];
		}

		$object_id = is_numeric( $settings['id'] ) ? (int) $settings['id'] : 'preview';
		$settings  = ( new Settings() )->sanitize( (string) $request['object'], $settings );

		return wp_parse_args(
			[
				'id'           => $object_id,
				'is_dynamic'   => true,
				'is_preview'   => true,
				'inline_style' => true,
			],
			$settings
		);
	}

	/**
	 * Dequeue all scripts/styles to prevent any conflict
	 * Enqueued 3rd party scripts and styles should be empty at this stage.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function dequeue() {

		global $wp_scripts, $wp_styles;

		if ( isset( $wp_scripts->queue ) ) {

			foreach ( $wp_scripts->queue as $handle ) {

				// We preserve jquery script.
				if ( 'jquery' !== $handle ) {
					wp_scripts()->remove( $handle );
				}
			}
		}

		if ( isset( $wp_styles->queue ) ) {

			foreach ( $wp_styles->queue as $handle ) {
				wp_styles()->remove( $handle );
			}
		}
	}

	/**
	 * Enqueue scripts/styles
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function enqueue() {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( WPGB_SLUG . '-preview', WPGB_URL . 'admin/js/preview.js', [], WPGB_VERSION, true );
		wp_enqueue_style( WPGB_SLUG . '-preview', WPGB_URL . 'admin/css/preview' . ( is_rtl() ? '-rtl' : '' ) . '.css', [], WPGB_VERSION );

	}

	/**
	 * Add card header
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function edit_button() {

		if ( ! Helpers::current_user_can() || ! wpgb_is_preview() ) {
			return;
		}

		$object_id   = wpgb_get_the_id();
		$object_type = wpgb_get_object_type();
		$metadata    = get_metadata( $object_type, $object_id, '_wpgb', true );

		printf(
			'<button type="button" class="wpgb-preview-iframe__edit-button" data-id="%d" data-object="%s" data-metadata="%s">
				<span>%s</span>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
					<path d="m19 7-3-3-8.5 8.5-1 4 4-1L19 7Zm-7 11.5H5V20h7v-1.5Z"></path>
				</svg>
			</button>',
			esc_attr( $object_id ),
			esc_attr( $object_type ),
			esc_attr( wp_json_encode( $metadata ) ),
			esc_attr( __( 'Edit', 'wp-grid-builder' ) )
		);
	}

	/**
	 * Force sticky posts in preview
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param \WP_Query $query Holds query instance.
	 */
	public function sticky_posts( $query ) {

		if ( ! empty( $query->get( 'wp_grid_builder' ) ) ) {
			$query->is_home = true;
		}
	}
}
