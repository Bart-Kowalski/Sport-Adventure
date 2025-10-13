<?php 
/**
 * Register/enqueue custom scripts and styles
 */
add_action( 'wp_enqueue_scripts', function() {
	// Enqueue your files on the canvas & frontend, not the builder panel. Otherwise custom CSS might affect builder)
	if ( ! bricks_is_builder_main() ) {
		wp_enqueue_style( 'bricks-child', get_stylesheet_uri(), ['bricks-frontend'], filemtime( get_stylesheet_directory() . '/style.css' ) );
	}
} );

/**
 * Register custom elements
 */
add_action( 'init', function() {
  $element_files = [
    __DIR__ . '/elements/title.php',
  ];

  foreach ( $element_files as $file ) {
    \Bricks\Elements::register_element( $file );
  }
}, 11 );

/**
 * Add text strings to builder
 */
add_filter( 'bricks/builder/i18n', function( $i18n ) {
  // For element category 'custom'
  $i18n['custom'] = esc_html__( 'Custom', 'bricks' );

  return $i18n;
} );

/**
 * Load text domain for translations
 */
function sport_adventure_load_theme_textdomain() {
    load_theme_textdomain('sport-adventure', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'sport_adventure_load_theme_textdomain');

// Include WooCommerce email customizations
require_once __DIR__ . '/includes/woocommerce-email-customizations.php';


add_filter('_load_textdomain_just_in_time_notice', '__return_false');

add_filter( 'woocommerce_order_button_text', 'custom_checkout_button_text' );
function custom_checkout_button_text() {
    return 'Zapisuję się na wyprawę'; // Twój własny tekst
}
