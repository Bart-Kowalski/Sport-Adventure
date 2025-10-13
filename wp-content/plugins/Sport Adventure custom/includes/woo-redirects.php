<?php
/**
 * WooCommerce Redirects
 */

defined('ABSPATH') || exit;

function sa_redirect_to_woo_product_url() {
    // Skip if already on a WooCommerce page
    if (is_woocommerce()) {
        return;
    }

    // Get current URL path
    $request_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    
    // Skip if empty path
    if (empty($request_path)) {
        return;
    }

    // Check if product exists with this slug
    $product = get_page_by_path($request_path, OBJECT, 'product');
    
    if ($product) {
        // Get product permalink
        $product_url = get_permalink($product->ID);
        
        // Perform 301 redirect
        wp_redirect($product_url, 301);
        exit;
    }
}
add_action('template_redirect', 'sa_redirect_to_woo_product_url'); 