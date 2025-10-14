<?php
// Filter out translation notices from debug log
if (!defined('WP_DEBUG_LOG')) {
    define('WP_DEBUG_LOG', false);
}
if (!defined('WP_DEBUG_DISPLAY')) {
    define('WP_DEBUG_DISPLAY', false);
}

// Custom error handler to filter translation notices
function sa_custom_error_handler($errno, $errstr, $errfile, $errline) {
    if ($errno === E_NOTICE && strpos($errstr, '_load_textdomain_just_in_time') !== false) {
        return true;
    }
    return false;
}
set_error_handler('sa_custom_error_handler', E_NOTICE);

// Disable error display and reporting
@ini_set('display_errors', 0);
error_reporting(0);

/**
 * Plugin Name: Sport Adventure Custom
 * Plugin URI: https://sportadventure.pl
 * Description: Custom functionality for Sport Adventure website including cart and checkout modifications
 * Version: 1.3.5
 * Author: Sport Adventure
 * Author URI: https://sportadventure.pl
 * Text Domain: sport-adventure-custom
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Disable translation loading notices
add_filter('_load_textdomain_just_in_time_notice', '__return_false');

// Define plugin constants
define('SPORT_ADVENTURE_CUSTOM_VERSION', '1.0.0');
define('SPORT_ADVENTURE_CUSTOM_PATH', plugin_dir_path(__FILE__));
define('SPORT_ADVENTURE_CUSTOM_URL', plugin_dir_url(__FILE__));

// Include required files
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/deposit-handler.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/order-deposit-handler.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/participant-handler.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/woo-custom-data-tags.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/add-to-cart-changes.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/checkout-changes.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/cart-changes.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/class-sa-cart-calculator.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/datalayer.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/klaviyo-integration.php';

// Include new functionality
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/woo-messages.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/woo-dates.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/woo-variants.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/woo-redirects.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/woo-currency.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/woo-archive-status.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/woo-variant-months.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/woo-variant-sync.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/custom-queries.php';
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/parent-data-tags.php';

// Include ACF variation fields - USING SIMPLE VERSION
// require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/acf-variation-fields.php'; // Complex version (has issues)
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/acf-variation-fields-simple.php'; // Simple version - direct save

// Include custom breadcrumbs
require_once SPORT_ADVENTURE_CUSTOM_PATH . 'includes/custom-breadcrumbs.php';



// Handle direct checkout redirects
function sa_handle_direct_checkout_redirect() {
    if (is_admin()) {
        return;
    }

    // Disable ALL WooCommerce redirects
    add_filter('woocommerce_cart_redirect_after_add', '__return_false', 999);
    add_filter('woocommerce_cart_redirect_after_error', '__return_false', 999);
    add_filter('woocommerce_cart_redirect_after_update', '__return_false', 999);
    
    // Don't remove the WooCommerce handler, just add our redirect filter
    // This allows WooCommerce to handle variable products properly

    // For AJAX requests - always add checkout URL to fragments
    add_filter('woocommerce_add_to_cart_fragments', function($fragments) {
        // Add URL to fragments
        $fragments['redirect_url'] = wc_get_checkout_url();
        
        // Also add it as a data attribute to prevent caching issues
        $fragments['.cart-redirect-data'] = sprintf(
            '<div class="cart-redirect-data" style="display:none;" data-redirect-url="%s"></div>',
            esc_attr(wc_get_checkout_url())
        );
        
        return $fragments;
    }, 1);

    // For non-AJAX requests - redirect to checkout
    add_filter('woocommerce_add_to_cart_redirect', function($url) {
        if (isset($_REQUEST['add-to-cart'])) {
            return wc_get_checkout_url();
        }
        return $url;
    }, 1);
}
add_action('init', 'sa_handle_direct_checkout_redirect', 5);



// Remove debug logging actions (these were commented out debug actions)
// No longer needed as we have proper debug settings

// Enqueue cart redirect script
function sa_enqueue_cart_redirect_script() {
    if (!is_admin()) {
        // First enqueue datalayer
        wp_enqueue_script(
            'sa-datalayer',
            SPORT_ADVENTURE_CUSTOM_URL . 'assets/js/datalayer.js',
            array('jquery', 'wc-add-to-cart'),
            SPORT_ADVENTURE_CUSTOM_VERSION,
            true
        );

        // Then enqueue cart redirect (depends on datalayer)
        wp_enqueue_script(
            'sport-adventure-cart-redirect',
            SPORT_ADVENTURE_CUSTOM_URL . 'assets/js/cart-redirect.js',
            array('jquery', 'wc-add-to-cart', 'sa-datalayer'),
            SPORT_ADVENTURE_CUSTOM_VERSION,
            true
        );

        // Localize script with checkout URL
        wp_localize_script('sport-adventure-cart-redirect', 'sa_redirect_params', array(
            'checkout_url' => wc_get_checkout_url()
        ));
    }
}
add_action('wp_enqueue_scripts', 'sa_enqueue_cart_redirect_script', 20);

// Enqueue scripts and styles
function sport_adventure_custom_enqueue_scripts() {
    // Add global styles for both admin and frontend
    wp_enqueue_style(
        'sport-adventure-global',
        SPORT_ADVENTURE_CUSTOM_URL . 'assets/css/global-styles.css',
        array(),
        SPORT_ADVENTURE_CUSTOM_VERSION
    );

    // Add mega menu script
    wp_enqueue_script(
        'sport-adventure-mega-menu',
        SPORT_ADVENTURE_CUSTOM_URL . 'assets/js/mega-menu.js',
        array('jquery'),
        SPORT_ADVENTURE_CUSTOM_VERSION,
        true
    );

    // Cart scripts and styles (load on all pages for cart badge functionality)
    wp_enqueue_script(
        'sport-adventure-cart',
        SPORT_ADVENTURE_CUSTOM_URL . 'assets/js/cart-changes.js',
        array('jquery'),
        SPORT_ADVENTURE_CUSTOM_VERSION,
        true
    );

    // Localize script for cart functionality
    wp_localize_script('sport-adventure-cart', 'wc_add_to_cart_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%')
    ));

    // Checkout page scripts and styles
    if (is_checkout()) {
        wp_enqueue_script(
            'sport-adventure-checkout',
            SPORT_ADVENTURE_CUSTOM_URL . 'assets/js/checkout-changes.js',
            array('jquery', 'wc-checkout'),
            SPORT_ADVENTURE_CUSTOM_VERSION,
            true
        );

        wp_enqueue_style(
            'sport-adventure-checkout',
            SPORT_ADVENTURE_CUSTOM_URL . 'assets/css/checkout-changes.css',
            array(),
            SPORT_ADVENTURE_CUSTOM_VERSION
        );

        // Localize script for checkout
        wp_localize_script('sport-adventure-checkout', 'checkout_data', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'update_order_review_nonce' => wp_create_nonce('update-order-review'),
            'is_checkout' => true,
            'checkout_url' => wc_get_checkout_url()
        ));
    }
}
add_action('wp_enqueue_scripts', 'sport_adventure_custom_enqueue_scripts', 20);

// Enqueue admin styles
function sport_adventure_custom_admin_enqueue_scripts($hook) {
    // Load on WooCommerce order pages
    if ('post.php' === $hook && isset($_GET['post']) && 'shop_order' === get_post_type($_GET['post'])) {
        wp_enqueue_style(
            'sport-adventure-global',
            SPORT_ADVENTURE_CUSTOM_URL . 'assets/css/global-styles.css',
            array(),
            SPORT_ADVENTURE_CUSTOM_VERSION
        );
    }
}
add_action('admin_enqueue_scripts', 'sport_adventure_custom_admin_enqueue_scripts');


// Plugin activation hook
function sport_adventure_custom_activate() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('This plugin requires WooCommerce to be installed and active.');
    }
}
register_activation_hook(__FILE__, 'sport_adventure_custom_activate');

// Plugin deactivation hook
function sport_adventure_custom_deactivate() {
    // Cleanup tasks if needed
}
register_deactivation_hook(__FILE__, 'sport_adventure_custom_deactivate');

// Helper function to check if debug is enabled
function sa_is_debug_enabled() {
    return get_option('sa_debug_enabled', 0);
}

function sa_is_php_debug_enabled() {
    return get_option('sa_php_debug_enabled', 0);
}

function sa_is_js_debug_enabled() {
    return get_option('sa_js_debug_enabled', 0);
}

// Ensure all price displays in the plugin are formatted correctly
// This may include ensuring that the format_price function is used consistently across all files


