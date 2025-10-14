<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

//
// Count number of wariants (terminy)
// 

// Register the tag
if (!function_exists('add_number_of_variants_tag')) {
    add_filter( 'bricks/dynamic_tags_list', 'add_number_of_variants_tag' );
    function add_number_of_variants_tag( $tags ) {
        $tags[] = [
            'name'  => '{number_of_variants}',
            'label' => 'Number of Variants',
            'group' => 'Custom',
        ];
        return $tags;
    }
}

//
// Count number of unique variants (terminy)
//

// Register the tag
if (!function_exists('add_unique_variants_count_tag')) {
    add_filter( 'bricks/dynamic_tags_list', 'add_unique_variants_count_tag' );
    function add_unique_variants_count_tag( $tags ) {
        $tags[] = [
            'name'  => '{unique_variants_count}',
            'label' => 'Unique Variants Count',
            'group' => 'Custom',
        ];
        return $tags;
    }
}

// Handle the tag rendering
if (!function_exists('get_unique_variants_count_value')) {
    add_filter( 'bricks/dynamic_data/render_tag', 'get_unique_variants_count_value', 20, 3 );
    function get_unique_variants_count_value( $tag, $post, $context = 'text' ) {
        if ( $tag !== '{unique_variants_count}' ) {
            return $tag;
        }

        // Get term ID from context if we're in a term loop
        $term_id = null;
        if (isset($context) && is_object($context) && isset($context->term_id)) {
            $term_id = $context->term_id;
        }

        if (!$term_id) {
            $term_id = get_queried_object_id();
        }

        if (!$term_id) return '0';

        global $wpdb;
        
        // Count unique combinations of start date and parent product
        $query = $wpdb->prepare("
            SELECT COUNT(DISTINCT CONCAT(COALESCE(pm_start.meta_value, 'special'), '_', p.post_parent)) as count
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm_start ON p.ID = pm_start.post_id 
                AND pm_start.meta_key = 'wyprawa-termin__data-poczatkowa'
            LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
            LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            LEFT JOIN {$wpdb->postmeta} pm_stock_status ON p.ID = pm_stock_status.post_id 
                AND pm_stock_status.meta_key = '_stock_status'
            LEFT JOIN {$wpdb->postmeta} pm_stock ON p.ID = pm_stock.post_id 
                AND pm_stock.meta_key = '_stock'
            LEFT JOIN {$wpdb->postmeta} pm_manage_stock ON p.ID = pm_manage_stock.post_id 
                AND pm_manage_stock.meta_key = '_manage_stock'
            WHERE tt.term_id = %d
            AND tt.taxonomy = 'miesiace'
            AND p.post_type = 'product_variation'
            AND p.post_status = 'publish'
            AND (
                pm_stock_status.meta_value = 'instock' 
                OR pm_manage_stock.meta_value = 'yes'
                OR pm_stock_status.meta_value IS NULL
            )
        ", $term_id);

        $count = $wpdb->get_var($query);
        return format_variants_count((int)$count);
    }
}

// Handle content rendering
if (!function_exists('render_unique_variants_count')) {
    add_filter( 'bricks/dynamic_data/render_content', 'render_unique_variants_count', 20, 3 );
    add_filter( 'bricks/frontend/render_data', 'render_unique_variants_count', 20, 2 );
    function render_unique_variants_count( $content, $post, $context = 'text' ) {
        if ( strpos( $content, '{unique_variants_count}' ) === false ) {
            return $content;
        }

        // Get term ID from context if we're in a term loop
        $term_id = null;
        if (isset($context) && is_object($context) && isset($context->term_id)) {
            $term_id = $context->term_id;
        }

        if (!$term_id) {
            $term_id = get_queried_object_id();
        }

        if (!$term_id) {
            $content = str_replace( '{unique_variants_count}', '0', $content );
            return $content;
        }

        global $wpdb;
        
        // Count unique combinations of start date and parent product
        $query = $wpdb->prepare("
            SELECT COUNT(DISTINCT CONCAT(COALESCE(pm_start.meta_value, 'special'), '_', p.post_parent)) as count
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm_start ON p.ID = pm_start.post_id 
                AND pm_start.meta_key = 'wyprawa-termin__data-poczatkowa'
            LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
            LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            LEFT JOIN {$wpdb->postmeta} pm_stock_status ON p.ID = pm_stock_status.post_id 
                AND pm_stock_status.meta_key = '_stock_status'
            LEFT JOIN {$wpdb->postmeta} pm_stock ON p.ID = pm_stock.post_id 
                AND pm_stock.meta_key = '_stock'
            LEFT JOIN {$wpdb->postmeta} pm_manage_stock ON p.ID = pm_manage_stock.post_id 
                AND pm_manage_stock.meta_key = '_manage_stock'
            WHERE tt.term_id = %d
            AND tt.taxonomy = 'miesiace'
            AND p.post_type = 'product_variation'
            AND p.post_status = 'publish'
            AND (
                pm_stock_status.meta_value = 'instock' 
                OR pm_manage_stock.meta_value = 'yes'
                OR pm_stock_status.meta_value IS NULL
            )
        ", $term_id);

        $count = $wpdb->get_var($query);
        $content = str_replace( '{unique_variants_count}', format_variants_count((int)$count), $content );
        return $content;
    }
}

// Handle the tag rendering
if (!function_exists('get_variants_count_value')) {
    add_filter( 'bricks/dynamic_data/render_tag', 'get_variants_count_value', 20, 3 );
    function get_variants_count_value( $tag, $post, $context = 'text' ) {
        if ( $tag !== '{number_of_variants}' ) {
            return $tag;
        }

        $count = get_published_variants_count($post->ID);
        return format_variants_count($count);
    }
}

// Handle content rendering
if (!function_exists('render_variants_count')) {
    add_filter( 'bricks/dynamic_data/render_content', 'render_variants_count', 20, 3 );
    add_filter( 'bricks/frontend/render_data', 'render_variants_count', 20, 2 );
    function render_variants_count( $content, $post, $context = 'text' ) {
        if ( strpos( $content, '{number_of_variants}' ) === false ) {
            return $content;
        }

        $count = get_published_variants_count($post->ID);
        $content = str_replace( '{number_of_variants}', format_variants_count($count), $content );

        return $content;
    }
}

// Helper function to format the count according to Polish grammar rules
if (!function_exists('format_variants_count')) {
    function format_variants_count($count) {
        if ($count == 0) {
            return "Lista zainteresowanych";
        } elseif ($count == 1) {
            return "1 termin";
        } elseif ($count >= 2 && $count <= 4) {
            return $count . " terminy";
        } else {
            return $count . " terminów";
        }
    }
}

// Function to get count of published WooCommerce product variations
if (!function_exists('get_published_variants_count')) {
    function get_published_variants_count($post_id) {
        global $wpdb;
        
        // Get count of unique date combinations excluding manually unavailable variants
        $count = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(DISTINCT pm_start.meta_value)
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm_start ON p.ID = pm_start.post_id AND pm_start.meta_key = 'wyprawa-termin__data-poczatkowa'
            LEFT JOIN {$wpdb->postmeta} pm_stock_status ON p.ID = pm_stock_status.post_id 
                AND pm_stock_status.meta_key = '_stock_status'
            LEFT JOIN {$wpdb->postmeta} pm_stock ON p.ID = pm_stock.post_id 
                AND pm_stock.meta_key = '_stock'
            LEFT JOIN {$wpdb->postmeta} pm_manage_stock ON p.ID = pm_manage_stock.post_id 
                AND pm_manage_stock.meta_key = '_manage_stock'
            WHERE p.post_parent = %d
            AND p.post_type = 'product_variation'
            AND p.post_status = 'publish'
            AND pm_start.meta_value IS NOT NULL
            AND (
                pm_stock_status.meta_value = 'instock' 
                OR pm_manage_stock.meta_value = 'yes'
                OR pm_stock_status.meta_value IS NULL
            )
        ", $post_id));
        
        return (int)$count;
    }
}


//
//
// Closest date

// Register the tag
if (!function_exists('add_closest_trip_date_tag')) {
    add_filter('bricks/dynamic_tags_list', 'add_closest_trip_date_tag');
    function add_closest_trip_date_tag($tags) {
        $tags[] = [
            'name'  => '{closest_trip_date}',
            'label' => 'Closest Trip Date',
            'group' => 'Custom',
        ];
        return $tags;
    }
}

// Handle the tag rendering
if (!function_exists('get_closest_trip_date_value')) {
    add_filter('bricks/dynamic_data/render_tag', 'get_closest_trip_date_value', 20, 3);
    function get_closest_trip_date_value($tag, $post, $context = 'text') {
        if ($tag !== '{closest_trip_date}') {
            return $tag;
        }

        // Get the correct post ID (handle both product and variation contexts)
        $post_id = null;
        if (is_object($post) && isset($post->ID)) {
            $post_id = $post->ID;
        } elseif (is_numeric($post)) {
            $post_id = $post;
        }

        if (!$post_id) {
            return '';
        }

        // Get product to ensure we have the parent product ID
        $product = wc_get_product($post_id);
        if (!$product) {
            return '';
        }

        // If this is a variation, get its parent
        if ($product->is_type('variation')) {
            $post_id = $product->get_parent_id();
        }

        // Get the cached closest trip date
        $closest_date = get_post_meta($post_id, '_cached_closest_trip_date', true);
        
        // If no cached date, trigger an update and return empty for now
        if (empty($closest_date)) {
            wp_schedule_single_event(time(), 'sa_update_product_closest_date', array($post_id));
            return '';
        }

        return $closest_date;
    }
}

// Handle content rendering
if (!function_exists('render_closest_trip_date')) {
    add_filter('bricks/dynamic_data/render_content', 'render_closest_trip_date', 20, 3);
    add_filter('bricks/frontend/render_data', 'render_closest_trip_date', 20, 2);
    function render_closest_trip_date($content, $post, $context = 'text') {
        if (strpos($content, '{closest_trip_date}') === false) {
            return $content;
        }

        $content = str_replace('{closest_trip_date}', get_closest_trip_date_value('{closest_trip_date}', $post, $context), $content);
        return $content;
    }
}

// Function to update the closest trip date meta
if (!function_exists('sa_update_product_closest_date')) {
    function sa_update_product_closest_date($product_id) {
        global $wpdb;
        
        $today = date('Ymd');
        
        // Get the closest future trip date in a single query
        $closest_date = $wpdb->get_row($wpdb->prepare("
            SELECT 
                MIN(pm_start.meta_value) as start_date,
                pm_end.meta_value as end_date
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm_start ON p.ID = pm_start.post_id 
                AND pm_start.meta_key = 'wyprawa-termin__data-poczatkowa'
            LEFT JOIN {$wpdb->postmeta} pm_end ON p.ID = pm_end.post_id 
                AND pm_end.meta_key = 'wyprawa-termin__data-koncowa'
            WHERE p.post_parent = %d
            AND p.post_type = 'product_variation'
            AND p.post_status = 'publish'
            AND pm_start.meta_value >= %s
            GROUP BY pm_end.meta_value
            ORDER BY pm_start.meta_value ASC
            LIMIT 1
        ", $product_id, $today));

        if ($closest_date) {
            $start = DateTime::createFromFormat('Ymd', $closest_date->start_date);
            $formatted_date = $start ? $start->format('d.m.Y') : '';
            
            if ($closest_date->end_date) {
                $end = DateTime::createFromFormat('Ymd', $closest_date->end_date);
                if ($end) {
                    $formatted_date .= '-' . $end->format('d.m.Y');
                }
            }
            
            update_post_meta($product_id, '_cached_closest_trip_date', $formatted_date);
        } else {
            delete_post_meta($product_id, '_cached_closest_trip_date');
        }
    }
}

// Register the cron hook
add_action('sa_update_product_closest_date', 'sa_update_product_closest_date');

// Schedule daily update of all products' closest dates
if (!function_exists('sa_schedule_closest_dates_update')) {
    function sa_schedule_closest_dates_update() {
        if (!wp_next_scheduled('sa_update_all_products_closest_dates')) {
            wp_schedule_event(time(), 'daily', 'sa_update_all_products_closest_dates');
        }
    }
}
add_action('wp', 'sa_schedule_closest_dates_update');

// Function to update all products
if (!function_exists('sa_update_all_products_closest_dates')) {
    function sa_update_all_products_closest_dates() {
        global $wpdb;
        
        // FIX: Check if any admin is currently editing products to prevent race conditions
        $active_edits = $wpdb->get_var("
            SELECT COUNT(*) FROM {$wpdb->options}
            WHERE option_name LIKE '_edit_lock'
            AND option_value > " . (time() - 150) . "
        ");
        
        if ($active_edits > 0) {
            // Get retry count to prevent infinite postponement
            $retry_count = get_transient('sa_cron_retry_count');
            $retry_count = $retry_count ? intval($retry_count) : 0;
            
            // Max 3 retries (30 min, 60 min, 90 min), then run anyway
            if ($retry_count < 3) {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log("SA Cron: Postponing closest date update - {$active_edits} active product edits detected (retry {$retry_count}/3)");
                }
                
                set_transient('sa_cron_retry_count', $retry_count + 1, 7200); // 2 hours expiry
                wp_schedule_single_event(time() + 1800, 'sa_update_all_products_closest_dates'); // Retry in 30 min
                return;
            } else {
                // Max retries reached, run anyway but log warning
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log("SA Cron: Running closest date update despite {$active_edits} active edits (max retries reached)");
                }
                delete_transient('sa_cron_retry_count');
            }
        } else {
            // No active edits, clear retry counter
            delete_transient('sa_cron_retry_count');
        }
        
        // Get all variable products
        $product_ids = $wpdb->get_col("
            SELECT ID FROM {$wpdb->posts}
            WHERE post_type = 'product'
            AND post_status = 'publish'
        ");
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("SA Cron: Starting closest date update for " . count($product_ids) . " products");
        }
        
        foreach ($product_ids as $product_id) {
            sa_update_product_closest_date($product_id);
        }
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("SA Cron: Completed closest date update");
        }
    }
}
add_action('sa_update_all_products_closest_dates', 'sa_update_all_products_closest_dates');

// Update closest date when variations are saved
if (!function_exists('sa_update_closest_date_on_variation_save')) {
    function sa_update_closest_date_on_variation_save($variation_id, $i) {
        // FIX: Skip if this is a Quick Edit action (no ACF data being edited)
        if (isset($_POST['action']) && $_POST['action'] === 'inline-save') {
            return;
        }
        
        // FIX: Skip if no ACF data in the save context
        if (!isset($_POST['acf']) || empty($_POST['acf'])) {
            return;
        }
        
        // FIX: Verify ACF date was actually saved before updating parent
        $acf_date = get_post_meta($variation_id, 'wyprawa-termin__data-poczatkowa', true);
        if (empty($acf_date)) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log("SA Warning: No ACF date found for variation {$variation_id}, skipping closest date update");
            }
            return;
        }
        
        $variation = wc_get_product($variation_id);
        if ($variation && $variation->get_parent_id()) {
            sa_update_product_closest_date($variation->get_parent_id());
        }
    }
}
// FIX: Changed priority from 10 to 15 to ensure ACF save completes first (priority 5)
add_action('woocommerce_save_product_variation', 'sa_update_closest_date_on_variation_save', 15, 2);


//
//
// Lowest price
// 

// Register the tag
add_filter( 'bricks/dynamic_tags_list', 'add_lowest_price_tag' );
function add_lowest_price_tag( $tags ) {
    $tags[] = [
        'name'  => '{lowest_price}',
        'label' => 'Lowest Price (without flights)',
        'group' => 'Custom',
    ];
    return $tags;
}

// Handle the tag rendering
add_filter( 'bricks/dynamic_data/render_tag', 'get_lowest_price_value', 20, 3 );
function get_lowest_price_value( $tag, $post, $context = 'text' ) {
    if ( $tag !== '{lowest_price}' ) {
        return $tag;
    }

    return get_lowest_price($post->ID);
}

// Handle content rendering
add_filter( 'bricks/dynamic_data/render_content', 'render_lowest_price', 20, 3 );
add_filter( 'bricks/frontend/render_data', 'render_lowest_price', 20, 2 );
function render_lowest_price( $content, $post, $context = 'text' ) {
    if ( strpos( $content, '{lowest_price}' ) === false ) {
        return $content;
    }

    $content = str_replace( '{lowest_price}', get_lowest_price($post->ID), $content );
    return $content;
}

function get_lowest_price($product_id) {
    global $wpdb;
    try {
        $query = $wpdb->prepare("
            SELECT 
                pm_price.meta_value as acf_price, 
                pm_woo_price.meta_value as woo_price,
                pm_currency.meta_value as currency
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm_price ON p.ID = pm_price.post_id AND pm_price.meta_key = 'wyprawa-termin__cena-nie-liczac-lotow'
            LEFT JOIN {$wpdb->postmeta} pm_woo_price ON p.ID = pm_woo_price.post_id AND pm_woo_price.meta_key = '_price'
            LEFT JOIN {$wpdb->postmeta} pm_currency ON p.ID = pm_currency.post_id AND pm_currency.meta_key = 'wyprawa-termin__waluta'
            WHERE p.post_parent = %d AND p.post_type = 'product_variation' AND p.post_status = 'publish'
        ", $product_id);

        $results = $wpdb->get_results($query);
        if (empty($results)) return 'Brak ceny';

        // Get currency from the first non-empty currency field, or default to 'PLN'
        $currency = '';
        foreach ($results as $result) {
            if (!empty($result->currency)) {
                $currency = trim($result->currency);
                break;
            }
        }
        if (empty($currency)) {
            $currency = 'PLN';
        }

        // Process ACF prices
        $valid_acf_prices = [];
        foreach ($results as $result) {
            if (!empty($result->acf_price)) {
                $clean_price = preg_replace('/[^0-9]/', '', $result->acf_price);
                if (!empty($clean_price) && is_numeric($clean_price)) {
                    $valid_acf_prices[] = (int)$clean_price;
                }
            }
        }

        if (!empty($valid_acf_prices)) {
            $min_price = min($valid_acf_prices);
            return count(array_unique($valid_acf_prices)) === 1 
                ? number_format($min_price, 0, ',', '') . ' ' . $currency
                : 'Od ' . number_format($min_price, 0, ',', '') . ' ' . $currency;
        }

        // Fall back to WooCommerce prices
        $valid_woo_prices = array_filter(array_map(function($result) {
            return !empty($result->woo_price) ? (float)$result->woo_price : null;
        }, $results));

        if (empty($valid_woo_prices)) return 'Brak ceny';

        $min_woo_price = min($valid_woo_prices);
        return count(array_unique($valid_woo_prices)) === 1 
            ? number_format($min_woo_price, 0, ',', '') . ' ' . $currency
            : 'Od ' . number_format($min_woo_price, 0, ',', '') . ' ' . $currency;
    } catch (Exception $e) {
        return '';
    }
}


//
// Check flight price
//

// Register the tag
add_filter( 'bricks/dynamic_tags_list', function( $tags ) {
    $tags[] = [
        'name'  => '{flight_price_text}',
        'label' => 'Flight Price Text',
        'group' => 'Custom',
    ];
    return $tags;
});

// Handle the tag rendering
add_filter( 'bricks/dynamic_data/render_tag', function( $tag, $post, $context = 'text' ) {
    if ( $tag !== '{flight_price_text}' ) {
        return $tag;
    }

    // Check if product is in "Wyprawy w Polsce" category
    if (has_term('Polska', 'lokalizacja', $post->ID)) {
        return '';
    }

    global $wpdb;
    $exists = $wpdb->get_var($wpdb->prepare("
        SELECT EXISTS (
            SELECT 1 FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE p.post_parent = %d
            AND p.post_status = 'publish'
            AND pm.meta_key = 'wyprawa-termin__cena-lotu'
            AND pm.meta_value != ''
            AND pm.meta_value IS NOT NULL
            LIMIT 1
        )
    ", $post->ID));

    return $exists ? '+ stała cena biletów lotniczych' : '';
}, 20, 3);

// Handle content rendering
add_filter( 'bricks/dynamic_data/render_content', function( $content, $post, $context = 'text' ) {
    if ( strpos( $content, '{flight_price_text}' ) === false ) {
        return $content;
    }

    // Check if product is in "Wyprawy w Polsce" category
    if (has_term('Polska', 'lokalizacja', $post->ID)) {
        return str_replace( '{flight_price_text}', '', $content );
    }

    global $wpdb;
    $exists = $wpdb->get_var($wpdb->prepare("
        SELECT EXISTS (
            SELECT 1 FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE p.post_parent = %d
            AND p.post_status = 'publish'
            AND pm.meta_key = 'wyprawa-termin__cena-lotu'
            AND pm.meta_value != ''
            AND pm.meta_value IS NOT NULL
            LIMIT 1
        )
    ", $post->ID));

    return str_replace( '{flight_price_text}', $exists ? '+ stała cena biletów lotniczych' : '', $content );
}, 20, 3);

add_filter( 'bricks/frontend/render_data', function( $content, $post ) {
    if ( strpos( $content, '{flight_price_text}' ) === false ) {
        return $content;
    }

    // Check if product is in "Wyprawy w Polsce" category
    if (has_term('Polska', 'lokalizacja', $post->ID)) {
        return str_replace( '{flight_price_text}', '', $content );
    }

    global $wpdb;
    $exists = $wpdb->get_var($wpdb->prepare("
        SELECT EXISTS (
            SELECT 1 FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE p.post_parent = %d
            AND p.post_status = 'publish'
            AND pm.meta_key = 'wyprawa-termin__cena-lotu'
            AND pm.meta_value != ''
            AND pm.meta_value IS NOT NULL
            LIMIT 1
        )
    ", $post->ID));

    return str_replace( '{flight_price_text}', $exists ? '+ stała cena biletów lotniczych' : '', $content );
}, 20, 2);

function get_variant_display_text($variation_id) {
    $start_date = get_field('wyprawa-termin__data-poczatkowa', $variation_id);
    $end_date = get_field('wyprawa-termin__data-koncowa', $variation_id);
    
    // If both dates exist, show date range
    if ($start_date && $end_date) {
        $start_formatted = date('d.m.y', strtotime(str_replace('/', '-', $start_date)));
        $end_formatted = date('d.m.y', strtotime(str_replace('/', '-', $end_date)));
        return $start_formatted . ' - ' . $end_formatted;
    }
    
    // Fallback to variant name
    $variation = wc_get_product($variation_id);
    if ($variation) {
        $name = $variation->get_description();
        // Remove "termin:" prefix if exists
        return trim(preg_replace('/termin:\s*/i', '', $name));
    }
    
    return '';
}

// Template code for displaying variants
function display_product_variants() {
    global $wpdb;
    $product_id = get_queried_object_id();
    
    // First check if there's only one variant
    $single_variant_query = $wpdb->prepare("
        SELECT p.ID, p.post_excerpt
        FROM {$wpdb->posts} p
        WHERE p.post_parent = %d
        AND p.post_type = 'product_variation'
        AND p.post_status = 'publish'
    ", $product_id);

    $variants = $wpdb->get_results($single_variant_query);
    
    // If there's exactly one variant, show its name
    if (count($variants) === 1 && !empty($variants[0]->post_excerpt)) {
        echo '<ul class="product-termins">';
        echo '<li>' . esc_html(trim(preg_replace('/termin:\s*/i', '', $variants[0]->post_excerpt))) . '</li>';
        echo '</ul>';
        return;
    }
    
    // For multiple variants, check for dates first
    $query = $wpdb->prepare("
        SELECT DISTINCT 
            MIN(p.ID) as ID,
            MIN(pm_start.meta_value) as start_date,
            MIN(pm_end.meta_value) as end_date
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm_start ON p.ID = pm_start.post_id 
            AND pm_start.meta_key = 'wyprawa-termin__data-poczatkowa'
        LEFT JOIN {$wpdb->postmeta} pm_end ON p.ID = pm_end.post_id 
            AND pm_end.meta_key = 'wyprawa-termin__data-koncowa'
        WHERE p.post_parent = %d
        AND p.post_type = 'product_variation'
        AND p.post_status = 'publish'
        AND pm_start.meta_value IS NOT NULL
        GROUP BY pm_start.meta_value, pm_end.meta_value
        ORDER BY pm_start.meta_value ASC
    ", $product_id);

    $variations = $wpdb->get_results($query);
    
    // If we found variations with dates, display them
    if (!empty($variations)) {
        echo '<ul class="product-termins">';
        foreach ($variations as $var) {
            if (!empty($var->start_date) && !empty($var->end_date)) {
                $start_formatted = date('d.m.Y', strtotime(str_replace('/', '-', $var->start_date)));
                $end_formatted = date('d.m.Y', strtotime(str_replace('/', '-', $var->end_date)));
                echo '<li>' . esc_html($start_formatted . ' - ' . $end_formatted) . '</li>';
            }
        }
        echo '</ul>';
        return;
    }
    
    // If we got here, show all variant names
    if (count($variants) > 1) {
        echo '<ul class="product-termins">';
        foreach ($variants as $var) {
            $name = trim(preg_replace('/termin:\s*/i', '', $var->post_excerpt));
            if (!empty($name)) {
                echo '<li>' . esc_html($name) . '</li>';
            }
        }
        echo '</ul>';
    }
}


//
// WooCommerce Price Without Decimals
//

// Function to display price in Bricks
add_filter('bricks/dynamic_data/render_tag', 'render_woo_price_no_decimals', 10, 3);
function render_woo_price_no_decimals($tag, $post, $context) {
    if ($tag !== '{woo_price_no_decimals}') {
        return $tag;
    }
    
    // Handle both product and variation IDs
    $product = null;
    
    // If we're in a query loop
    if (is_object($post) && isset($post->ID)) {
        if ($post->post_type === 'product_variation') {
            $product = wc_get_product($post->ID);
        } else {
            $product = wc_get_product($post);
        }
    } 
    // If we have a numeric ID
    elseif (is_numeric($post)) {
        $product = wc_get_product($post);
    }
    // If we're in Bricks loop
    elseif (isset($context) && is_object($context) && method_exists($context, 'get_query_object')) {
        $query_object = $context->get_query_object();
        if ($query_object) {
            $product = wc_get_product($query_object);
        }
    }
    
    if (!$product) {
        return '';
    }

    // Get price based on product type
    $price = '';
    if ($product->is_type('variation')) {
        $price = $product->get_price();
    } else {
        $price = $product->get_regular_price();
        if (empty($price)) {
            $price = $product->get_price();
        }
    }

    if (empty($price)) {
        return '';
    }

    return number_format((float)$price, 0, ',', '') . ' PLN';
}

// Register the tag
add_filter('bricks/dynamic_tags_list', 'register_woo_price_no_decimals_tag');
function register_woo_price_no_decimals_tag($tags) {
    $tags[] = [
        'name'  => '{woo_price_no_decimals}',
        'label' => 'WooCommerce Price No Decimals',
        'group' => 'Custom'
    ];
    return $tags;
}

// Add content rendering support
add_filter('bricks/dynamic_data/render_content', 'render_woo_price_no_decimals_content', 20, 3);
function render_woo_price_no_decimals_content($content, $post, $context = 'text') {
    if (strpos($content, '{woo_price_no_decimals}') === false) {
        return $content;
    }
    
    $price = render_woo_price_no_decimals('{woo_price_no_decimals}', $post, $context);
    return str_replace('{woo_price_no_decimals}', $price, $content);
}

//
// Difficulty Level Tag
//

// Register the tag
if (!function_exists('add_difficulty_level_info_tag')) {
    add_filter('bricks/dynamic_tags_list', 'add_difficulty_level_info_tag');
    function add_difficulty_level_info_tag($tags) {
        $tags[] = [
            'name'  => '{difficulty_level_info}',
            'label' => 'Poziom Trudności Info',
            'group' => 'Custom',
        ];
        return $tags;
    }
}

// Handle the tag rendering
if (!function_exists('get_difficulty_level_info_value')) {
    add_filter('bricks/dynamic_data/render_tag', 'get_difficulty_level_info_value', 20, 3);
    function get_difficulty_level_info_value($tag, $post, $context = 'text') {
        if ($tag !== '{difficulty_level_info}') {
            return $tag;
        }
        
        $difficulty_level = get_field('wyprawa__poziom-trudnosci', $post->ID);
        if (!$difficulty_level) return '';

        // Extract just the number from the string
        $level_number = intval($difficulty_level);

        $difficulty_info = [
            1 => [
                'title' => '1 Dla każdego',
                'desc'  => 'Nie musisz mieć specjalnego przygotowania, wystarczy chęć do aktywnego spędzania czasu.',
                'example' => 'Jeśli lubisz długie spacery i lekką aktywność, poradzisz sobie bez problemu.'
            ],
            2 => [
                'title' => '2 Dla początkujących',
                'desc'  => 'Wyprawa z umiarkowaną aktywnością, wymagająca podstawowej kondycji.',
                'example' => 'Jeśli bez problemu przechodzisz około 10 km i możesz być aktywny przez kilka godzin, ta wyprawa jest dla Ciebie.'
            ],
            3 => [
                'title' => '3 Dla aktywnych',
                'desc'  => 'Wymagana dobra kondycja i przyzwyczajenie do regularnego wysiłku, ale bez ekstremalnych wyzwań.',
                'example' => 'Jeśli przejście około 15 km w terenie górskim i 5+ godzin aktywności fizycznej nie sprawia Ci trudności, poradzisz sobie bez problemu.'
            ],
            4 => [
                'title' => '4 Dla wytrwałych',
                'desc'  => 'Intensywna wyprawa, wymagająca dobrej kondycji i wytrzymałości.',
                'example' => 'Jeśli regularnie uprawiasz sport, pokonujesz duże dystanse i dobrze radzisz sobie w trudnym terenie, to opcja dla Ciebie.'
            ],
            5 => [
                'title' => '5 Dla poszukujących wyzwań',
                'desc'  => 'Ekstremalne warunki, długotrwały wysiłek i wysokie wymagania kondycyjne.',
                'example' => 'Jeśli masz doświadczenie w wyprawach wysokogórskich, biegach ultra czy wielodniowych trekkingach, podejmiesz to wyzwanie.'
            ]
        ];

        if (!isset($difficulty_info[$level_number])) return '';

        $info = $difficulty_info[$level_number];
        
        return sprintf(
            '<div class="difficulty-level-info">
                <p class="difficulty-title text--m text--700">%s</p>
                <p class="difficulty-desc margin-bottom--xs">%s</p>
                <p class="difficulty-example">%s</p>
            </div>',
            $info['title'],
            $info['desc'],
            $info['example']  // Added the example text to be rendered
        );
    }
}

// Handle content rendering for tag
if (!function_exists('render_difficulty_level_info')) {
    add_filter('bricks/dynamic_data/render_content', 'render_difficulty_level_info', 20, 3);
    add_filter('bricks/frontend/render_data', 'render_difficulty_level_info', 20, 2);
    function render_difficulty_level_info($content, $post, $context = 'text') {
        if (strpos($content, '{difficulty_level_info}') === false) {
            return $content;
        }

        $difficulty_info = get_difficulty_level_info_value('{difficulty_level_info}', $post, $context);
        return str_replace('{difficulty_level_info}', $difficulty_info, $content);
    }
}

//
// Płatne do 60 dni tag
//

// Register the tag
if (!function_exists('add_platne_do_60_dni_tag')) {
    add_filter('bricks/dynamic_tags_list', 'add_platne_do_60_dni_tag');
    function add_platne_do_60_dni_tag($tags) {
        $tags[] = [
            'name'  => '{platne_do_60_dni}',
            'label' => 'Płatne do 60 dni',
            'group' => 'Custom',
        ];
        return $tags;
    }
}

// Handle the tag rendering
if (!function_exists('get_platne_do_60_dni_value')) {
    add_filter('bricks/dynamic_data/render_tag', 'get_platne_do_60_dni_value', 20, 3);
    function get_platne_do_60_dni_value($tag, $post, $context = 'text') {
        if ($tag !== '{platne_do_60_dni}') {
            return $tag;
        }

        // Get the correct post ID
        $post_id = null;
        if (is_object($post) && isset($post->ID)) {
            $post_id = $post->ID;
        } elseif (is_numeric($post)) {
            $post_id = $post;
        } elseif (isset($context) && is_object($context) && method_exists($context, 'get_query_object')) {
            $query_object = $context->get_query_object();
            if ($query_object) {
                $post_id = $query_object->ID;
            }
        }

        if (!$post_id) {
            $post_id = get_the_ID();
        }

        // Get the product object to ensure we have the parent product
        $product = wc_get_product($post_id);
        if (!$product) {
            return '';
        }

        // If this is a variation, get its parent
        if ($product->is_type('variation')) {
            $post_id = $product->get_parent_id();
            $product = wc_get_product($post_id);
        }

        global $wpdb;
        
        // Get all variations with flight prices
        $variations = $wpdb->get_results($wpdb->prepare("
            SELECT p.ID, p.post_status, pm.meta_value as flight_price
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = 'wyprawa-termin__cena-lotu'
            WHERE p.post_parent = %d
            AND p.post_type = 'product_variation'
            AND p.post_status = 'publish'
        ", $post_id));
        
        if (empty($variations)) {
            return '';
        }

        // Find variation with lowest total price
        $selected_var = null;
        $lowest_total = PHP_FLOAT_MAX;
        
        foreach ($variations as $var) {
            $var_acf_price = !empty($var->acf_price) ? floatval($var->acf_price) : 0;
            $var_flight_price = !empty($var->flight_price) ? floatval($var->flight_price) : 0;
            $total_price = $var_acf_price + $var_flight_price;
            
            if ($total_price > 0 && $total_price < $lowest_total) {
                $lowest_total = $total_price;
                $selected_var = $var;
            }
        }

        if (!$selected_var) {
            return '';
        }

        $output = '';
        $currency = !empty($selected_var->currency) ? trim($selected_var->currency) : 'PLN';
        $woo_price = !empty($selected_var->woo_price) ? floatval($selected_var->woo_price) : 0;
        $acf_price = !empty($selected_var->acf_price) ? floatval($selected_var->acf_price) : 0;
        $flight_price = !empty($selected_var->flight_price) ? floatval($selected_var->flight_price) : 0;

        // Handle Polish currency (PLN) cases
        if ($currency === 'PLN') {
            // Case 1: Polish trips (no flight price)
            if ($flight_price <= 0) {
                $remaining = $acf_price - $woo_price;
                if ($remaining > 0) {
                    return sprintf('do 30 dni przed wyprawą płatne %s PLN', number_format($remaining, 0, ',', ''));
                }
                return '';
            }
            
            // Case 2: International trips with flights
            $output = '';
            
            // Main trip payment (90 days) - always full acf_price
            $output = sprintf('do 90 dni przed wyprawą płatne %s PLN', number_format($acf_price, 0, ',', ''));
            
            // Flight payment (60 days) - flight price minus deposit
            $flight_remaining = $flight_price - $woo_price;
            if ($flight_remaining > 0) {
                $output .= '<br>';
                $output .= sprintf('do 60 dni przed wyprawą płatne %s PLN', number_format($flight_remaining, 0, ',', ''));
            }
            
            return $output;
        }
        
        // Handle other currencies
        if ($flight_price > 0) {
            if ($acf_price > 0) {
                $output .= sprintf('do 90 dni przed wyprawą płatne %s %s', number_format($acf_price, 0, ',', ''), $currency);
            }
            
            $remaining = $flight_price - $woo_price;
            if ($remaining > 0) {
                if (!empty($output)) {
                    $output .= '<br>';
                }
                $output .= sprintf('do 60 dni przed wyprawą płatne %s PLN', number_format($remaining, 0, ',', ''));
            }
        } else {
            $remaining = $acf_price - $woo_price;
            if ($remaining > 0) {
                $output = sprintf('do 60 dni przed wyprawą płatne %s %s', number_format($remaining, 0, ',', ''), $currency);
            }
        }

        return $output;
    }
}

// Handle content rendering
if (!function_exists('render_platne_do_60_dni')) {
    add_filter('bricks/dynamic_data/render_content', 'render_platne_do_60_dni', 20, 3);
    add_filter('bricks/frontend/render_data', 'render_platne_do_60_dni', 20, 2);
    function render_platne_do_60_dni($content, $post, $context = 'text') {
        if (strpos($content, '{platne_do_60_dni}') === false) {
            return $content;
        }

        $text = get_platne_do_60_dni_value('{platne_do_60_dni}', $post, $context);
        return str_replace('{platne_do_60_dni}', $text, $content);
    }
}

//
// Wpłaty Tag
//

// Register the tag
if (!function_exists('add_wplaty_tag')) {
    add_filter('bricks/dynamic_tags_list', 'add_wplaty_tag');
    function add_wplaty_tag($tags) {
        $tags[] = [
            'name'  => '{wplaty}',
            'label' => 'Wpłaty Info',
            'group' => 'Custom',
        ];
        return $tags;
    }
}

// Handle the tag rendering
if (!function_exists('get_wplaty_value')) {
    add_filter('bricks/dynamic_data/render_tag', 'get_wplaty_value', 20, 3);
    function get_wplaty_value($tag, $post, $context = 'text') {
        if ($tag !== '{wplaty}') {
            return $tag;
        }

        // Get the correct variation ID
        $variation_id = null;
        if (is_object($post) && isset($post->ID)) {
            $variation_id = $post->ID;
        } elseif (is_numeric($post)) {
            $variation_id = $post;
        } elseif (isset($context) && is_object($context) && method_exists($context, 'get_query_object')) {
            $query_object = $context->get_query_object();
            if ($query_object) {
                $variation_id = $query_object->ID;
            }
        }

        if (!$variation_id) {
            return '';
        }

        global $wpdb;
        
        // Get the current variation's prices
        $variation = $wpdb->get_row($wpdb->prepare("
            SELECT 
                   pm_flight.meta_value as flight_price,
                   pm_price.meta_value as acf_price,
                   pm_currency.meta_value as currency,
                   wm.meta_value as woo_price
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm_flight ON p.ID = pm_flight.post_id 
                AND pm_flight.meta_key = 'wyprawa-termin__cena-lotu'
            LEFT JOIN {$wpdb->postmeta} pm_price ON p.ID = pm_price.post_id 
                AND pm_price.meta_key = 'wyprawa-termin__cena-nie-liczac-lotow'
            LEFT JOIN {$wpdb->postmeta} pm_currency ON p.ID = pm_currency.post_id 
                AND pm_currency.meta_key = 'wyprawa-termin__waluta'
            LEFT JOIN {$wpdb->postmeta} wm ON p.ID = wm.post_id 
                AND wm.meta_key = '_price'
            WHERE p.ID = %d
            AND p.post_type = 'product_variation'
            AND p.post_status = 'publish'
        ", $variation_id));

        if (!$variation) {
            return '';
        }

        $output = '';
        $currency = !empty($variation->currency) ? trim($variation->currency) : 'PLN';
        $woo_price = !empty($variation->woo_price) ? floatval($variation->woo_price) : 0;
        $acf_price = !empty($variation->acf_price) ? floatval($variation->acf_price) : 0;
        $flight_price = !empty($variation->flight_price) ? floatval($variation->flight_price) : 0;

        // Handle Polish currency (PLN) cases
        if ($currency === 'PLN') {
            // Case 1: Polish trips (no flight price)
            if ($flight_price <= 0) {
                $remaining = $acf_price - $woo_price;
                if ($remaining > 0) {
                    return sprintf('do 30 dni przed wyprawą płatne %s PLN', number_format($remaining, 0, ',', ''));
                }
                return '';
            }
            
            // Case 2: International trips with flights
            $output = '';
            
            // Main trip payment (90 days) - always full acf_price
            $output = sprintf('do 90 dni przed wyprawą płatne %s PLN', number_format($acf_price, 0, ',', ''));
            
            // Flight payment (60 days) - flight price minus deposit
            $flight_remaining = $flight_price - $woo_price;
            if ($flight_remaining > 0) {
                $output .= '<br>';
                $output .= sprintf('do 60 dni przed wyprawą płatne %s PLN', number_format($flight_remaining, 0, ',', ''));
            }
            
            return $output;
        }
        
        // Handle other currencies
        if ($flight_price > 0) {
            if ($acf_price > 0) {
                $output .= sprintf('do 90 dni przed wyprawą płatne %s %s', number_format($acf_price, 0, ',', ''), $currency);
            }
            
            $remaining = $flight_price - $woo_price;
            if ($remaining > 0) {
                if (!empty($output)) {
                    $output .= '<br>';
                }
                $output .= sprintf('do 60 dni przed wyprawą płatne %s PLN', number_format($remaining, 0, ',', ''));
            }
        } else {
            $remaining = $acf_price - $woo_price;
            if ($remaining > 0) {
                $output = sprintf('do 60 dni przed wyprawą płatne %s %s', number_format($remaining, 0, ',', ''), $currency);
            }
        }

        return $output;
    }
}

// Handle content rendering
if (!function_exists('render_wplaty')) {
    add_filter('bricks/dynamic_data/render_content', 'render_wplaty', 20, 3);
    add_filter('bricks/frontend/render_data', 'render_wplaty', 20, 2);
    function render_wplaty($content, $post, $context = 'text') {
        if (strpos($content, '{wplaty}') === false) {
            return $content;
        }

        $text = get_wplaty_value('{wplaty}', $post, $context);
        return str_replace('{wplaty}', $text, $content);
    }
}

// Add custom data tags for WooCommerce
add_action('woocommerce_init', 'register_custom_data_tags');

function register_custom_data_tags() {
    // Register custom data tags for variations
    add_filter('woocommerce_available_variation', 'add_custom_fields_to_variations', 10, 3);
}

function add_custom_fields_to_variations($variation_data, $product, $variation) {
    // Get ACF fields for the variation
    $variation_data['acf_fields'] = array(
        'wyprawa-termin__cena-nie-liczac-lotow' => get_field('wyprawa-termin__cena-nie-liczac-lotow', $variation->get_id()),
        'wyprawa-termin__cena-lotu' => get_field('wyprawa-termin__cena-lotu', $variation->get_id()),
        'wyprawa-termin__waluta' => get_field('wyprawa-termin__waluta', $variation->get_id()),
        'wyprawa-termin__termin-platnosci-zaliczki' => get_field('wyprawa-termin__termin-platnosci-zaliczki', $variation->get_id())
    );

    return $variation_data;
}

// Format date display in variation dropdown
add_filter('woocommerce_variation_option_name', 'format_variation_option_name');
function format_variation_option_name($name) {
    // Check if the name matches our date format (DD-MM-YYYY)
    if (preg_match('/^\d{2}-\d{2}-\d{4}/', $name)) {
        // Convert dashes to dots for display
        return str_replace('-', '.', $name);
    }
    return $name;
}

// Add custom data to cart item
add_filter('woocommerce_add_cart_item_data', 'add_custom_cart_item_data', 10, 3);
function add_custom_cart_item_data($cart_item_data, $product_id, $variation_id) {
    // Add custom ACF fields to cart item data
    if ($variation_id) {
        $acf_fields = array(
            'wyprawa-termin__cena-nie-liczac-lotow' => get_field('wyprawa-termin__cena-nie-liczac-lotow', $variation_id),
            'wyprawa-termin__cena-lotu' => get_field('wyprawa-termin__cena-lotu', $variation_id),
            'wyprawa-termin__waluta' => get_field('wyprawa-termin__waluta', $variation_id),
            'wyprawa-termin__termin-platnosci-zaliczki' => get_field('wyprawa-termin__termin-platnosci-zaliczki', $variation_id)
        );
        $cart_item_data['acf_fields'] = $acf_fields;
    }
    return $cart_item_data;
}

// Display custom fields in cart
function display_custom_item_data($item_data, $cart_item) {
    if (isset($cart_item['acf_fields'])) {
        $waluta = $cart_item['acf_fields']['wyprawa-termin__waluta'] ?: 'PLN';
        
        if (isset($cart_item['acf_fields']['wyprawa-termin__cena-nie-liczac-lotow']) && $cart_item['acf_fields']['wyprawa-termin__cena-nie-liczac-lotow'] > 0) {
            $item_data[] = array(
                'key' => 'Cena wycieczki',
                'value' => number_format($cart_item['acf_fields']['wyprawa-termin__cena-nie-liczac-lotow'], 0, '.', '') . ' ' . $waluta
            );
        }
    }
    return $item_data;
}

// Save custom order item meta
function save_custom_order_item_meta($item, $cart_item_key, $values, $order) {
    if (isset($values['acf_fields'])) {
        foreach ($values['acf_fields'] as $key => $value) {
            if (!empty($value)) {
                $meta_key = str_replace('wyprawa-termin__', '', $key);
                $item->add_meta_data($meta_key, $value);
            }
        }
        
        // Save the currency explicitly for order display
        if (isset($values['acf_fields']['wyprawa-termin__waluta']) && !empty($values['acf_fields']['wyprawa-termin__waluta'])) {
            $item->add_meta_data('_item_currency', $values['acf_fields']['wyprawa-termin__waluta']);
        }
    }
}

// Add custom data to variation prices
add_filter('woocommerce_available_variation', 'add_custom_variation_price_data', 20, 3);
function add_custom_variation_price_data($variation_data, $product, $variation) {
    $cena_bez_lotow = floatval(get_field('wyprawa-termin__cena-nie-liczac-lotow', $variation->get_id()));
    $cena_lotu = floatval(get_field('wyprawa-termin__cena-lotu', $variation->get_id()));
    $waluta = get_field('wyprawa-termin__waluta', $variation->get_id()) ?: 'PLN';
    
    // Add custom price data
    $variation_data['custom_price_data'] = array(
        'cena_bez_lotow' => $cena_bez_lotow,
        'cena_lotu' => $cena_lotu,
        'waluta' => $waluta,
        'total_price' => $cena_bez_lotow + $cena_lotu
    );
    
    return $variation_data;
}

// Format termin attribute display
add_filter('woocommerce_attribute_label', 'custom_attribute_label', 10, 3);
function custom_attribute_label($label, $name, $product) {
    if ($name === 'pa_termin') {
        return 'Termin';
    }
    return $label;
}

// Format termin attribute value
add_filter('woocommerce_attribute', 'format_termin_attribute_value', 10, 3);
function format_termin_attribute_value($value, $attribute, $values) {
    if ($attribute->get_name() === 'pa_termin') {
        return str_replace('-', '.', $value);
    }
    return $value;
}

//
// Additional Price Text Tag
//

// Register the tag
if (!function_exists('add_additional_price_text_tag')) {
    add_filter( 'bricks/dynamic_tags_list', 'add_additional_price_text_tag' );
    function add_additional_price_text_tag( $tags ) {
        $tags[] = [
            'name'  => '{additional_price_text}',
            'label' => 'Additional Price Text',
            'group' => 'Custom',
        ];
        return $tags;
    }
}

// Handle the tag rendering
if (!function_exists('get_additional_price_text_value')) {
    add_filter( 'bricks/dynamic_data/render_tag', 'get_additional_price_text_value', 20, 3 );
    function get_additional_price_text_value( $tag, $post, $context = 'text' ) {
        if ( $tag !== '{additional_price_text}' ) {
            return $tag;
        }

        // Get post ID and ensure we have the parent product ID
        $post_id = null;
        if (isset($context) && is_object($context) && isset($context->ID)) {
            $post_id = $context->ID;
        }
        if (!$post_id && isset($post->ID)) {
            $post_id = $post->ID;
        }
        if (!$post_id) {
            $post_id = get_queried_object_id();
        }
        if (!$post_id) return '';

        // Get the product object
        $product = wc_get_product($post_id);
        if (!$product) return '';

        // Ensure we have the parent product
        $parent_id = $product->is_type('variation') ? $product->get_parent_id() : $post_id;
        $parent_product = wc_get_product($parent_id);
        if (!$parent_product) return '';

        // Get all variations
        $variations = $parent_product->get_available_variations();
        if (empty($variations)) return '';

        // Get accommodation attribute if it exists
        $attribute_prices = [];
        $currency = 'PLN';

        foreach ($variations as $variation) {
            $variation_obj = wc_get_product($variation['variation_id']);
            if (!$variation_obj) continue;

            // Get price for this variation
            $price = floatval(get_field('wyprawa-termin__cena-nie-liczac-lotow', $variation['variation_id']));
            $var_currency = get_field('wyprawa-termin__waluta', $variation['variation_id']);
            if ($var_currency) $currency = $var_currency;
            
            if ($price > 0) {
                // Create a key from all attributes that affect price
                $price_key = [];
                foreach ($variation['attributes'] as $attr_key => $attr_value) {
                    // Skip date/termin attributes
                    if (strpos($attr_key, 'termin') === false) {
                        $taxonomy = str_replace('attribute_', '', $attr_key);
                        
                        // Simply use the attribute name as shown in variant picker
                        if ($taxonomy === 'pa_wersja-zakwaterowania') {
                            $attribute_label = 'Wersja zakwaterowania';
                        } else {
                            // For any other attributes, use their default display name
                            $attribute_label = str_replace('pa_', '', ucfirst(str_replace('-', ' ', $taxonomy)));
                        }
                        
                        // Get proper value label
                        if (taxonomy_exists($taxonomy)) {
                            $term = get_term_by('slug', $attr_value, $taxonomy);
                            $formatted_value = $term ? $term->name : $attr_value;
                        } else {
                            // For custom product attributes
                            $formatted_value = $attr_value;
                        }
                        
                        // Format: "Attribute Label: Value"
                        $price_key[] = $attribute_label . ': ' . $formatted_value;
                    }
                }
                
                if (!empty($price_key)) {
                    $attribute_prices[implode(' | ', $price_key)] = $price;
                }
            }
        }

        // If no price variations or all prices are the same, return single price
        if (empty($attribute_prices) || count(array_unique($attribute_prices)) === 1) {
            $price = reset($attribute_prices);
            return $price ? number_format($price, 0, ',', ' ') . ' ' . $currency : '';
        }

        // Sort prices from lowest to highest
        asort($attribute_prices);

        // Format output
        $output = '';
        foreach ($attribute_prices as $attr_combo => $price) {
            $output .= sprintf(
                '<span class="text--m">%s</span><br>%s %s<br><br>',
                $attr_combo,
                number_format($price, 0, ',', ' '),
                $currency
            );
        }

        return rtrim($output, '<br>');
    }
}

// Handle content rendering
if (!function_exists('render_additional_price_text')) {
    add_filter( 'bricks/dynamic_data/render_content', 'render_additional_price_text', 20, 3 );
    add_filter( 'bricks/frontend/render_data', 'render_additional_price_text', 20, 2 );
    function render_additional_price_text( $content, $post, $context = 'text' ) {
        if ( strpos( $content, '{additional_price_text}' ) === false ) {
            return $content;
        }

        $text = get_additional_price_text_value('{additional_price_text}', $post, $context);
        return str_replace( '{additional_price_text}', $text, $content );
    }
}

//
// Lowest ACF Price Tag
//

// Register the tag
if (!function_exists('add_lowest_acf_price_tag')) {
    add_filter('bricks/dynamic_tags_list', 'add_lowest_acf_price_tag');
    function add_lowest_acf_price_tag($tags) {
        $tags[] = [
            'name'  => '{lowest_acf_price}',
            'label' => 'Lowest ACF Price',
            'group' => 'Custom',
        ];
        return $tags;
    }
}

// Handle the tag rendering
if (!function_exists('get_lowest_acf_price_value')) {
    add_filter('bricks/dynamic_data/render_tag', 'get_lowest_acf_price_value', 20, 3);
    function get_lowest_acf_price_value($tag, $post, $context = 'text') {
        if ($tag !== '{lowest_acf_price}') {
            return $tag;
        }

        return get_lowest_acf_price($post->ID);
    }
}

// Handle content rendering
if (!function_exists('render_lowest_acf_price')) {
    add_filter('bricks/dynamic_data/render_content', 'render_lowest_acf_price', 20, 3);
    add_filter('bricks/frontend/render_data', 'render_lowest_acf_price', 20, 2);
    function render_lowest_acf_price($content, $post, $context = 'text') {
        if (strpos($content, '{lowest_acf_price}') === false) {
            return $content;
        }

        $content = str_replace('{lowest_acf_price}', get_lowest_acf_price($post->ID), $content);
        return $content;
    }
}

function get_lowest_acf_price($product_id) {
    global $wpdb;
    
    // Get the lowest price from variants using a single optimized query
    $query = $wpdb->prepare("
        SELECT MIN(CAST(pm.meta_value AS DECIMAL(10,2))) as min_price
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id 
            AND pm.meta_key = 'wyprawa-termin__cena-nie-liczac-lotow'
        WHERE p.post_parent = %d
        AND p.post_type = 'product_variation'
        AND p.post_status = 'publish'
        AND pm.meta_value IS NOT NULL
        AND pm.meta_value != ''
    ", $product_id);

    $min_price = $wpdb->get_var($query);
    
    if ($min_price === null) {
        return '';
    }

    return number_format($min_price, 0, ',', '');
}

//
// Variant Currency Tag
//

// Register the tag
if (!function_exists('add_variant_currency_tag')) {
    add_filter('bricks/dynamic_tags_list', 'add_variant_currency_tag');
    function add_variant_currency_tag($tags) {
        $tags[] = [
            'name'  => '{variant_currency}',
            'label' => 'Variant Currency',
            'group' => 'Custom',
        ];
        return $tags;
    }
}

// Handle the tag rendering
if (!function_exists('get_variant_currency_value')) {
    add_filter('bricks/dynamic_data/render_tag', 'get_variant_currency_value', 20, 3);
    function get_variant_currency_value($tag, $post, $context = 'text') {
        if ($tag !== '{variant_currency}') {
            return $tag;
        }

        // Get post ID from various contexts
        $post_id = null;
        if (isset($context) && is_object($context) && isset($context->ID)) {
            $post_id = $context->ID;
        }

        if (!$post_id && isset($post->ID)) {
            $post_id = $post->ID;
        }

        if (!$post_id) {
            $post_id = get_queried_object_id();
        }

        if (!$post_id) return 'PLN';

        // Get the first variation ID
        global $wpdb;
        $variation_id = $wpdb->get_var($wpdb->prepare("
            SELECT ID
            FROM {$wpdb->posts}
            WHERE post_parent = %d
            AND post_type = 'product_variation'
            AND post_status = 'publish'
            LIMIT 1
        ", $post_id));

        if (!$variation_id) return 'PLN';

        // Use ACF's get_field function to get the currency
        $currency = get_field('wyprawa-termin__waluta', $variation_id);
        
        // Return currency or default to PLN if not found
        return !empty($currency) ? trim($currency) : 'PLN';
    }
}

// Handle content rendering
if (!function_exists('render_variant_currency')) {
    add_filter('bricks/dynamic_data/render_content', 'render_variant_currency', 20, 3);
    add_filter('bricks/frontend/render_data', 'render_variant_currency', 20, 2);
    function render_variant_currency($content, $post, $context = 'text') {
        if (strpos($content, '{variant_currency}') === false) {
            return $content;
        }

        $currency = get_variant_currency_value('{variant_currency}', $post, $context);
        return str_replace('{variant_currency}', $currency, $content);
    }
}

// Register the termin variants count tag
if (!function_exists('add_termin_variants_count_tag')) {
    add_filter('bricks/dynamic_tags_list', 'add_termin_variants_count_tag');
    function add_termin_variants_count_tag($tags) {
        $tags[] = [
            'name'  => '{termin_variants_count}',
            'label' => 'Termin Variants Count',
            'group' => 'Custom',
        ];
        return $tags;
    }
}

// Handle the tag rendering
if (!function_exists('get_termin_variants_count_value')) {
    add_filter('bricks/dynamic_data/render_tag', 'get_termin_variants_count_value', 20, 3);
    function get_termin_variants_count_value($tag, $post, $context = 'text') {
        if ($tag !== '{termin_variants_count}') {
            return $tag;
        }
        
        // Get post ID from various contexts
        $post_id = null;
        if (isset($context) && is_object($context) && isset($context->ID)) {
            $post_id = $context->ID;
        }

        if (!$post_id && isset($post->ID)) {
            $post_id = $post->ID;
        }

        if (!$post_id) {
            $post_id = get_queried_object_id();
        }

        if (!$post_id) return '0';
        
        $product = wc_get_product($post_id);
        if (!$product || !$product->is_type('variable')) {
            return '0';
        }
        
        // Get all variations
        $variations = $product->get_available_variations();
        if (empty($variations)) {
            return '0';
        }
        
        // Get unique termin values, excluding variants with 0 stock
        $unique_termins = array();
        foreach ($variations as $variation) {
            if (isset($variation['attributes']['attribute_pa_termin'])) {
                // Check if this variation has stock available
                $variation_obj = wc_get_product($variation['variation_id']);
                if ($variation_obj && $variation_obj->is_in_stock()) {
                    $unique_termins[$variation['attributes']['attribute_pa_termin']] = 1;
                }
            }
        }
        
        // Return count of unique termins
        return (string)count($unique_termins);
    }
}

// Handle content rendering
if (!function_exists('render_termin_variants_count')) {
    add_filter('bricks/dynamic_data/render_content', 'render_termin_variants_count', 20, 3);
    add_filter('bricks/frontend/render_data', 'render_termin_variants_count', 20, 2);
    function render_termin_variants_count($content, $post, $context = 'text') {
        if (strpos($content, '{termin_variants_count}') === false) {
            return $content;
        }

        $count = get_termin_variants_count_value('{termin_variants_count}', $post, $context);
        return str_replace('{termin_variants_count}', $count, $content);
    }
}