<?php
/**
 * Add toggle to show/hide disabled product variants in WooCommerce admin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue admin scripts for variation toggle
 */
function sa_enqueue_variation_toggle_scripts($hook) {
    // Only load on product edit page
    global $post;
    
    if (('post.php' !== $hook && 'post-new.php' !== $hook) || 
        !isset($post) || 
        'product' !== get_post_type($post->ID)) {
        return;
    }
    
    // Check if it's a variable product
    $product = wc_get_product($post->ID);
    if (!$product || !$product->is_type('variable')) {
        return;
    }
    
    wp_enqueue_script(
        'sa-variation-toggle',
        SPORT_ADVENTURE_CUSTOM_URL . 'assets/js/admin-variation-toggle.js',
        array('jquery', 'wc-admin-meta-boxes'),
        SPORT_ADVENTURE_CUSTOM_VERSION,
        true
    );
    
    wp_enqueue_style(
        'sa-variation-toggle',
        SPORT_ADVENTURE_CUSTOM_URL . 'assets/css/admin-variation-toggle.css',
        array(),
        SPORT_ADVENTURE_CUSTOM_VERSION
    );
    
    wp_localize_script('sa-variation-toggle', 'saVariationToggle', array(
        'hideDisabledText' => __('Ukryj wyłączone', 'sport-adventure-custom'),
        'showDisabledText' => __('Pokaż wyłączone', 'sport-adventure-custom'),
        'hideDisabledTooltip' => __('Ukryj wyłączone warianty, aby zmniejszyć bałagan', 'sport-adventure-custom'),
        'showDisabledTooltip' => __('Pokaż wszystkie warianty, włącznie z wyłączonymi', 'sport-adventure-custom'),
    ));
}
add_action('admin_enqueue_scripts', 'sa_enqueue_variation_toggle_scripts', 20);

