<?php
/**
 * Loader template
 *
 * This template can be overridden by copying it to yourtheme/wp-grid-builder/templates/loader.php.
 *
 * Template files can change and you will need to copy the new files to your theme to
 * maintain compatibility.
 *
 * @package   wp-grid-builder/templates
 * @author    Loïc Blascos
 * @copyright 2019-2024 Loïc Blascos
 * @version   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = wpgb_get_grid_settings();

if ( ! $settings->loader && ! empty( $settings->loader_type ) ) {
	return;
}

$loaders = apply_filters( 'wp_grid_builder/loaders', [] );

if ( empty( $loaders[ $settings->loader_type ]['html'] ) ) {
	return;
}

?>
<div class="wpgb-loader">
	<div class="<?php echo sanitize_html_class( $settings->loader_type ); ?>">
		<?php echo wp_kses_post( $loaders[ $settings->loader_type ]['html'] ); ?>
	</div>
</div>
<?php
