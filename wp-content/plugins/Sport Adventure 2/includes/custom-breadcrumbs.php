<?php
/**
 * Custom Breadcrumbs for Sport Adventure
 * 
 * Handles breadcrumb logic based on product categories:
 * - Polish trips: Strona główna >> Wszystkie w Polsce >> Nazwa wyprawy
 * - Foreign trips: Strona główna >> Wszystkie zagraniczne >> Nazwa wyprawy  
 * - Other trips (including non-published): Strona główna >> Wszystkie wyprawy >> Nazwa wyprawy
 */

defined('ABSPATH') || exit;

/**
 * Custom breadcrumb handler for WooCommerce products
 */
if (!function_exists('sa_custom_woocommerce_breadcrumbs')) {
    add_filter('woocommerce_get_breadcrumb', 'sa_custom_woocommerce_breadcrumbs', 10, 2);
    
    function sa_custom_woocommerce_breadcrumbs($crumbs, $breadcrumb_obj) {
        // Only modify breadcrumbs for single product pages
        if (!is_product()) {
            return $crumbs;
        }
        
        global $post;
        if (!$post || $post->post_type !== 'product') {
            return $crumbs;
        }
        
        // Get the product
        $product = wc_get_product($post->ID);
        if (!$product) {
            return $crumbs;
        }
        
        // Get the product's location terms
        $location_terms = wp_get_post_terms($post->ID, 'lokalizacja', array('fields' => 'names'));
        $is_polish_trip = in_array('Polska', $location_terms);
        
        // Get product status
        $product_status = $post->post_status;
        
        // Debug logging (only if debug is enabled)
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('SA Breadcrumbs Debug - Product ID: ' . $post->ID);
            error_log('SA Breadcrumbs Debug - Location terms: ' . print_r($location_terms, true));
            error_log('SA Breadcrumbs Debug - Is Polish trip: ' . ($is_polish_trip ? 'Yes' : 'No'));
            error_log('SA Breadcrumbs Debug - Product status: ' . $product_status);
        }
        
        // Build custom breadcrumbs
        $custom_crumbs = array();
        
        // Home link
        $custom_crumbs[] = array(
            esc_html__('Strona główna', 'sport-adventure-custom'),
            home_url('/')
        );
        
        // Determine the category page based on trip type and status
        if ($is_polish_trip) {
            // Polish trips
            $custom_crumbs[] = array(
                esc_html__('Wszystkie w Polsce', 'sport-adventure-custom'),
                home_url('/wyprawy-w-polsce/')
            );
        } elseif ($product_status === 'publish') {
            // Foreign trips (published)
            $custom_crumbs[] = array(
                esc_html__('Wszystkie zagraniczne', 'sport-adventure-custom'),
                home_url('/wyprawy-zagraniczne/')
            );
        } else {
            // Other trips (including non-published statuses)
            $custom_crumbs[] = array(
                esc_html__('Wszystkie wyprawy', 'sport-adventure-custom'),
                home_url('/wszystkie-wyprawy/')
            );
        }
        
        // Product name (current page)
        $custom_crumbs[] = array(
            get_the_title($post->ID),
            ''
        );
        
        return $custom_crumbs;
    }
}

/**
 * Custom breadcrumb handler for Bricks Extras breadcrumbs element
 * This integrates with the Bricks Extras plugin's breadcrumb element
 */
if (!function_exists('sa_custom_bricks_extras_breadcrumbs')) {
    // Hook into Bricks Extras breadcrumb filter
    add_filter('bricksextras/breadcrumbs/', 'sa_custom_bricks_extras_breadcrumbs', 10, 1);
    
    function sa_custom_bricks_extras_breadcrumbs($breadcrumb_items) {
        // Only modify breadcrumbs for single product pages
        if (!is_product()) {
            return $breadcrumb_items;
        }
        
        global $post;
        if (!$post || $post->post_type !== 'product') {
            return $breadcrumb_items;
        }
        
        // Get the product's location terms
        $location_terms = wp_get_post_terms($post->ID, 'lokalizacja', array('fields' => 'names'));
        $is_polish_trip = in_array('Polska', $location_terms);
        
        // Get product status
        $product_status = $post->post_status;
        
        // Debug logging (only if debug is enabled)
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('SA Bricks Extras Breadcrumbs Debug - Product ID: ' . $post->ID);
            error_log('SA Bricks Extras Breadcrumbs Debug - Location terms: ' . print_r($location_terms, true));
            error_log('SA Bricks Extras Breadcrumbs Debug - Is Polish trip: ' . ($is_polish_trip ? 'Yes' : 'No'));
            error_log('SA Bricks Extras Breadcrumbs Debug - Product status: ' . $product_status);
        }
        
        // Build custom breadcrumb items as HTML strings (Bricks Extras format)
        $custom_items = array();
        
        // Home link (position 1)
        $home_url = home_url('/');
        $home_label = esc_html__('Strona główna', 'sport-adventure-custom');
        $custom_items[] = sprintf(
            '<a href="%s" itemprop="item"><span itemtype="%s" itemprop="name"><span>%s</span></span></a>',
            esc_url($home_url),
            esc_url($home_url),
            $home_label
        );
        
        // Determine the category page based on trip type and status (position 2)
        if ($is_polish_trip) {
            // Polish trips
            $category_url = home_url('/wyprawy-w-polsce/');
            $category_label = esc_html__('Wszystkie w Polsce', 'sport-adventure-custom');
        } elseif ($product_status === 'publish') {
            // Foreign trips (published)
            $category_url = home_url('/wyprawy-zagraniczne/');
            $category_label = esc_html__('Wszystkie zagraniczne', 'sport-adventure-custom');
        } else {
            // Other trips (including non-published statuses)
            $category_url = home_url('/wszystkie-wyprawy/');
            $category_label = esc_html__('Wszystkie wyprawy', 'sport-adventure-custom');
        }
        
        $custom_items[] = sprintf(
            '<a href="%s" itemprop="item"><span itemtype="%s" itemprop="name"><span>%s</span></span></a>',
            esc_url($category_url),
            esc_url($category_url),
            $category_label
        );
        
        // Product name (current page, position 3, no link)
        $product_title = get_the_title($post->ID);
        $custom_items[] = sprintf(
            '<span itemprop="name">%s</span>',
            esc_html($product_title)
        );
        
        return $custom_items;
    }
}

/**
 * Fallback for standard Bricks breadcrumbs element
 */
if (!function_exists('sa_custom_bricks_breadcrumbs')) {
    add_filter('bricks/breadcrumbs/items', 'sa_custom_bricks_breadcrumbs', 10, 1);
    
    function sa_custom_bricks_breadcrumbs($breadcrumb_items) {
        // Only modify breadcrumbs for single product pages
        if (!is_product()) {
            return $breadcrumb_items;
        }
        
        global $post;
        if (!$post || $post->post_type !== 'product') {
            return $breadcrumb_items;
        }
        
        // Get the product's location terms
        $location_terms = wp_get_post_terms($post->ID, 'lokalizacja', array('fields' => 'names'));
        $is_polish_trip = in_array('Polska', $location_terms);
        
        // Get product status
        $product_status = $post->post_status;
        
        // Build custom breadcrumb items
        $custom_items = array();
        
        // Home link
        $home_url = home_url('/');
        $home_label = esc_html__('Strona główna', 'sport-adventure-custom');
        $custom_items[] = sprintf('<a class="item" href="%s">%s</a>', esc_url($home_url), $home_label);
        
        // Determine the category page based on trip type and status
        if ($is_polish_trip) {
            // Polish trips
            $category_url = home_url('/wyprawy-w-polsce/');
            $category_label = esc_html__('Wszystkie w Polsce', 'sport-adventure-custom');
        } elseif ($product_status === 'publish') {
            // Foreign trips (published)
            $category_url = home_url('/wyprawy-zagraniczne/');
            $category_label = esc_html__('Wszystkie zagraniczne', 'sport-adventure-custom');
        } else {
            // Other trips (including non-published statuses)
            $category_url = home_url('/wszystkie-wyprawy/');
            $category_label = esc_html__('Wszystkie wyprawy', 'sport-adventure-custom');
        }
        
        $custom_items[] = sprintf('<a class="item" href="%s">%s</a>', esc_url($category_url), $category_label);
        
        // Product name (current page)
        $product_title = get_the_title($post->ID);
        $custom_items[] = sprintf('<span class="item" aria-current="page">%s</span>', esc_html($product_title));
        
        return $custom_items;
    }
}

/**
 * Add custom breadcrumb separator for consistency
 */
if (!function_exists('sa_custom_breadcrumb_separator')) {
    add_filter('woocommerce_breadcrumb_defaults', 'sa_custom_breadcrumb_separator', 20);
    
    function sa_custom_breadcrumb_separator($defaults) {
        $defaults['delimiter'] = '<span class="separator"> >> </span>';
        return $defaults;
    }
}

/**
 * Add custom breadcrumb separator for Bricks breadcrumbs
 */
if (!function_exists('sa_custom_bricks_breadcrumb_separator')) {
    add_filter('bricks/breadcrumbs/separator', 'sa_custom_bricks_breadcrumb_separator', 20);
    
    function sa_custom_bricks_breadcrumb_separator($separator) {
        return '<span class="separator"> >> </span>';
    }
}
