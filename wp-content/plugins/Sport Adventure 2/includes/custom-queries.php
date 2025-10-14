<?php
/**
 * Custom Queries for Bricks Builder
 */

defined('ABSPATH') || exit;

/* Register all query types */
if (!function_exists('sa_custom_wyprawy_query_controls')) {
    add_filter('bricks/setup/control_options', 'sa_custom_wyprawy_query_controls');
    function sa_custom_wyprawy_query_controls($control_options) {
        // Homepage queries
        $control_options['queryTypes']['homepage_products_query'] = esc_html__('Homepage Products (6, featured first)');
        $control_options['queryTypes']['homepage_all_query'] = esc_html__('Homepage All (6)');
        
        // Archive queries
        $control_options['queryTypes']['all_wyprawy_query'] = esc_html__('Wszystkie wyprawy (featured first)');
        $control_options['queryTypes']['wyprawy_2025_query'] = esc_html__('Wyprawy 2025-2026 (featured first)');
        $control_options['queryTypes']['wyprawy_polska_query'] = esc_html__('Wyprawy w Polsce (featured first)');
        $control_options['queryTypes']['wyprawy_zagranica_query'] = esc_html__('Wyprawy za granicą (featured first)');
        
        // Timeline queries
        $control_options['queryTypes']['custom_months_query'] = esc_html__('Timeline Months');
        $control_options['queryTypes']['custom_variants_query'] = esc_html__('Timeline Variants');
        $control_options['queryTypes']['unique_variants_query'] = esc_html__('Unique Variants');
        $control_options['queryTypes']['unique_variants_all_status_query'] = esc_html__('Unique Variants - All Status');
        
        // Archive queries
        $control_options['queryTypes']['archived_products_query'] = esc_html__('Archiwum Products');
        
        return $control_options;
    }
}

/* Run queries when selected */
if (!function_exists('sa_run_custom_queries')) {
    add_filter('bricks/query/run', 'sa_run_custom_queries', 10, 2);
    function sa_run_custom_queries($results, $query_obj) {
        global $current_month_term_id;
        static $cached_results = array();
        static $cached_months = null;
        
        $featured_term = get_term_by('name', 'featured', 'product_visibility');
        
        // Base arguments for all queries
        $base_args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC'
        );
        

        // Add Bricks query filters
        if (isset($query_obj->settings['tax_query'])) {
            $base_args['tax_query'] = $query_obj->settings['tax_query'];
        }
        if (isset($query_obj->settings['posts_per_page'])) {
            $base_args['posts_per_page'] = $query_obj->settings['posts_per_page'];
        }
        if (isset($query_obj->settings['offset'])) {
            $base_args['offset'] = $query_obj->settings['offset'];
        }
        

        // Homepage featured query (6 products, featured first)
        if ($query_obj->object_type === 'homepage_products_query') {
            $products = sa_get_filtered_products($base_args, $featured_term);
            return array_slice($products, 0, 6);
        }

        // Homepage all query (6 products)
        if ($query_obj->object_type === 'homepage_all_query') {
            $args = array_merge($base_args, array(
                'posts_per_page' => 6
            ));
            return get_posts($args);
        }

        // All Wyprawy Query
        if ($query_obj->object_type === 'all_wyprawy_query') {
            // FORCE status to publish - absolute override
            $base_args['post_status'] = 'publish';
            
            // ALWAYS log what we're doing for debugging
            $debug_mode = current_user_can('manage_options');
            
            if ($debug_mode) {
                error_log('=== ALL WYPRAWY QUERY START ===');
                error_log('Query Object Type: ' . $query_obj->object_type);
                error_log('Base Args BEFORE get_filtered: ' . print_r($base_args, true));
            }
            
            // Get filtered products
            $results = sa_get_filtered_products($base_args, $featured_term);
            
            if ($debug_mode) {
                error_log('Results BEFORE filtering: ' . count($results));
                error_log('Checking each product status:');
                foreach ($results as $idx => $product) {
                    $status = get_post_status($product->ID);
                    error_log(sprintf('  [%d] ID: %d | Status: %s | Title: %s', 
                        $idx,
                        $product->ID, 
                        $status,
                        $product->post_title
                    ));
                    if ($status !== 'publish') {
                        error_log('    ^^^ WARNING: NON-PUBLISH PRODUCT FOUND!');
                    }
                }
            }
            
            // Extra safety: Filter out any non-publish products
            $results = array_filter($results, function($product) {
                $status = get_post_status($product->ID);
                return $status === 'publish';
            });
            
            // Re-index array after filtering
            $results = array_values($results);
            
            if ($debug_mode) {
                error_log('Results AFTER filtering: ' . count($results));
                error_log('=== ALL WYPRAWY QUERY END ===');
            }
            
            return $results;
        }

        // Wyprawy 2025 Query
        if ($query_obj->object_type === 'wyprawy_2025_query') {
            global $wpdb;
            
            // First get all variant IDs from 2025 and 2026
            $variant_ids = $wpdb->get_col($wpdb->prepare("
                SELECT DISTINCT tr.object_id
                FROM {$wpdb->term_relationships} tr
                INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
                INNER JOIN {$wpdb->termmeta} tm ON t.term_id = tm.term_id
                WHERE tt.taxonomy = 'miesiace'
                AND tm.meta_key = 'rok'
                AND tm.meta_value IN (%s, %s)
            ", '2025', '2026'));
            
            if (!empty($variant_ids)) {
                // Get parent product IDs from these variants
                $parent_ids = $wpdb->get_col("
                    SELECT DISTINCT post_parent 
                    FROM {$wpdb->posts} 
                    WHERE ID IN (" . implode(',', $variant_ids) . ")
                    AND post_parent > 0
                ");
                
                if (!empty($parent_ids)) {
                    $args = array_merge($base_args, array(
                        'post__in' => $parent_ids,
                    ));
                    return sa_get_filtered_products($args, $featured_term);
                }
            }
            return array();
        }

        // Wyprawy w Polsce Query
        if ($query_obj->object_type === 'wyprawy_polska_query') {
            $polska_query = array(
                array(
                    'taxonomy' => 'lokalizacja',
                    'field'    => 'name',
                    'terms'    => 'Polska'
                )
            );
            
            $args = array_merge($base_args, array(
                'tax_query' => isset($base_args['tax_query']) 
                    ? array_merge($base_args['tax_query'], $polska_query)
                    : $polska_query
            ));
            return sa_get_filtered_products($args, $featured_term);
        }

        // Wyprawy za granicą Query
        if ($query_obj->object_type === 'wyprawy_zagranica_query') {
            $zagranica_query = array(
                array(
                    'taxonomy' => 'lokalizacja',
                    'field'    => 'name',
                    'terms'    => 'Polska',
                    'operator' => 'NOT IN'
                )
            );
            
            $args = array_merge($base_args, array(
                'tax_query' => isset($base_args['tax_query'])
                    ? array_merge($base_args['tax_query'], $zagranica_query)
                    : $zagranica_query
            ));
            return sa_get_filtered_products($args, $featured_term);
        }

        // Handle months query
        if ($query_obj->object_type === 'custom_months_query') {
            // Return cached months if available
            if ($cached_months !== null) {
                return $cached_months;
            }
            
            $args = array(
                'taxonomy' => 'miesiace',
                'hide_empty' => true,
                'orderby' => 'meta_value_num',
                'meta_key' => 'calculated_number',
                'order' => 'ASC',
                'number' => 64,
                'meta_query' => array(
                    array(
                        'key' => 'rok',
                        'value' => array('2025', '2026'),
                        'compare' => 'IN',
                        'type' => 'NUMERIC'
                    )
                )
            );
            
            // Get terms that have published products
            global $wpdb;
            $terms = get_terms($args);
            $filtered_terms = array();
            
            // Get current month and year
            $current_month = (int)date('n'); // 1-12
            $current_year = (int)date('Y');
            
            foreach ($terms as $term) {
                // Get the month number for this term
                $month_number = (int)get_term_meta($term->term_id, 'numer_miesiaca', true);
                $year = (int)get_term_meta($term->term_id, 'rok', true);
                
                // Skip if month is in the past
                if ($year < $current_year || ($year === $current_year && $month_number < $current_month)) {
                    continue;
                }
                
                // Check if there are published variants with this taxonomy
                $has_published = $wpdb->get_var($wpdb->prepare("
                    SELECT COUNT(*)
                    FROM {$wpdb->term_relationships} tr
                    INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    INNER JOIN {$wpdb->posts} p ON tr.object_id = p.ID
                    INNER JOIN {$wpdb->posts} parent ON p.post_parent = parent.ID
                    WHERE tt.term_id = %d
                    AND tt.taxonomy = 'miesiace'
                    AND p.post_type = 'product_variation'
                    AND p.post_status IN ('publish', 'private')
                    AND parent.post_status = 'publish'
                ", $term->term_id));
                
                if ($has_published > 0) {
                    $filtered_terms[] = $term;
                }
            }
            
            $cached_months = $filtered_terms;
            return $filtered_terms;
        }
        
        // Handle variants query
        if ($query_obj->object_type === 'custom_variants_query') {
            // Return cached results if available
            $cache_key = 'variants_' . $current_month_term_id;
            if (isset($cached_results[$cache_key])) {
                return $cached_results[$cache_key];
            }
            
            global $wpdb;
            
            // Build the tax query condition if month is selected
            $tax_join = '';
            $tax_where = '';
            if (!empty($current_month_term_id)) {
                $tax_join = "
                    INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
                    INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                ";
                $tax_where = $wpdb->prepare("AND tt.taxonomy = 'miesiace' AND tt.term_id = %d", $current_month_term_id);
            }
            
            // Query to get variants using the same approach as unique_variants_query
            $query = "
                WITH RankedVariants AS (
                    SELECT 
                        p.ID,
                        p.post_parent,
                        pm_start.meta_value as start_date,
                        p.post_excerpt as variant_description,
                        wc_attrs.meta_value as wc_attributes,
                        CAST(COALESCE(pm_price.meta_value, '999999') AS DECIMAL(10,2)) as price,
                        ROW_NUMBER() OVER (
                            PARTITION BY p.post_parent, pm_start.meta_value 
                            ORDER BY 
                                CASE 
                                    WHEN wc_attrs.meta_value LIKE '%Standard%' THEN 0 
                                    WHEN wc_attrs.meta_value LIKE '%Premium%' THEN 1 
                                    ELSE 2 
                                END,
                                p.menu_order ASC
                        ) as rn
                    FROM {$wpdb->posts} p
                    LEFT JOIN {$wpdb->postmeta} pm_start ON p.ID = pm_start.post_id 
                        AND pm_start.meta_key = 'wyprawa-termin__data-poczatkowa'
                    LEFT JOIN {$wpdb->postmeta} wc_attrs ON p.ID = wc_attrs.post_id 
                        AND wc_attrs.meta_key = '_product_attributes'
                    LEFT JOIN {$wpdb->postmeta} pm_price ON p.ID = pm_price.post_id 
                        AND pm_price.meta_key = '_price'
                    LEFT JOIN {$wpdb->postmeta} pm_stock_status ON p.ID = pm_stock_status.post_id 
                        AND pm_stock_status.meta_key = '_stock_status'
                    LEFT JOIN {$wpdb->postmeta} pm_stock ON p.ID = pm_stock.post_id 
                        AND pm_stock.meta_key = '_stock'
                    {$tax_join}
                    WHERE p.post_type = 'product_variation'
                    AND p.post_status IN ('publish', 'private')
                    {$tax_where}
                    AND (
                        pm_stock_status.meta_value = 'instock' 
                        OR (pm_stock_status.meta_value = 'outofstock' AND CAST(COALESCE(pm_stock.meta_value, '0') AS SIGNED) > 0)
                        OR pm_stock_status.meta_value IS NULL
                    )
                )
                SELECT 
                    rv.ID,
                    rv.start_date,
                    rv.variant_description,
                    rv.post_parent as product_id,
                    rv.wc_attributes,
                    rv.price
                FROM RankedVariants rv
                WHERE rv.rn = 1
                AND EXISTS (
                    SELECT 1 FROM {$wpdb->posts} parent
                    WHERE parent.ID = rv.post_parent
                    AND parent.post_status = 'publish'
                )
                ORDER BY COALESCE(rv.start_date, rv.variant_description, rv.ID) ASC
            ";
            
            $variants = $wpdb->get_results($query);
            
            // Transform results into post objects
            $posts = array();
            foreach ($variants as $variant) {
                $post = get_post($variant->ID);
                if ($post) {
                    // Add the variant metadata
                    $post->start_date = $variant->start_date;
                    $post->variant_description = $variant->variant_description;
                    $posts[] = $post;
                }
            }
            
            // Cache the results
            $cached_results[$cache_key] = $posts;
            
            return $posts;
        }

        // Handle unique variants query
        if ($query_obj->object_type === 'unique_variants_query') {
            global $wpdb;
            
            // Get the current product ID
            $product_id = get_the_ID();
            if (!$product_id) {
                return array();
            }
            
            // Get default attributes for the product
            $default_attributes = get_post_meta($product_id, '_default_attributes', true);
            $default_variant_id = null;
            
            // Find default variant ID efficiently if default attributes exist
            if ($default_attributes && is_array($default_attributes)) {
                foreach ($default_attributes as $attr_name => $default_value) {
                    $meta_key = 'attribute_' . $attr_name;
                    $variant_query = $wpdb->prepare("
                        SELECT p.ID 
                        FROM {$wpdb->posts} p
                        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                        WHERE p.post_parent = %d
                        AND p.post_type = 'product_variation'
                        AND p.post_status = 'publish'
                        AND pm.meta_key = %s
                        AND pm.meta_value = %s
                        LIMIT 1
                    ", $product_id, $meta_key, $default_value);
                    
                    $found_variant = $wpdb->get_var($variant_query);
                    if ($found_variant) {
                        $default_variant_id = $found_variant;
                        break; // Found a match, use it
                    }
                }
            }
            
            // Simple query - get unique variants by date in admin order
            $query = $wpdb->prepare("
                WITH RankedVariants AS (
                    SELECT 
                        p.ID,
                        pm_start.meta_value as start_date,
                        pm_end.meta_value as end_date,
                        p.post_excerpt as variant_description,
                        p.post_parent as product_id,
                        CAST(COALESCE(pm_price.meta_value, '999999') AS DECIMAL(10,2)) as price,
                        p.menu_order,
                        ROW_NUMBER() OVER (
                            PARTITION BY pm_start.meta_value 
                            ORDER BY 
                                CASE WHEN p.ID = %s THEN 0 ELSE 1 END,
                                p.menu_order ASC,
                                p.ID ASC
                        ) as rn
                    FROM {$wpdb->posts} p
                    LEFT JOIN {$wpdb->postmeta} pm_start ON p.ID = pm_start.post_id 
                        AND pm_start.meta_key = 'wyprawa-termin__data-poczatkowa'
                    LEFT JOIN {$wpdb->postmeta} pm_end ON p.ID = pm_end.post_id 
                        AND pm_end.meta_key = 'wyprawa-termin__data-koncowa'
                    LEFT JOIN {$wpdb->postmeta} pm_price ON p.ID = pm_price.post_id 
                        AND pm_price.meta_key = 'wyprawa-termin__cena-nie-liczac-lotow'
                    LEFT JOIN {$wpdb->postmeta} pm_stock_status ON p.ID = pm_stock_status.post_id 
                        AND pm_stock_status.meta_key = '_stock_status'
                    LEFT JOIN {$wpdb->postmeta} pm_stock ON p.ID = pm_stock.post_id 
                        AND pm_stock.meta_key = '_stock'
                    WHERE p.post_parent = %d
                    AND p.post_type = 'product_variation'
                    AND p.post_status = 'publish'
                    AND (
                        pm_stock_status.meta_value = 'instock' 
                        OR (pm_stock_status.meta_value = 'outofstock' AND CAST(COALESCE(pm_stock.meta_value, '0') AS SIGNED) > 0)
                        OR pm_stock_status.meta_value IS NULL
                    )
                )
                SELECT rv.ID, rv.start_date, rv.end_date, rv.variant_description, rv.product_id, rv.price, rv.menu_order
                FROM RankedVariants rv
                WHERE rv.rn = 1
                ORDER BY 
                    CASE WHEN rv.ID = %s THEN 0 ELSE 1 END,
                    COALESCE(rv.start_date, 'zzz') ASC
            ", $default_variant_id ?: 0, $product_id, $default_variant_id ?: 0);
            
            $variants = $wpdb->get_results($query);
            
            // Transform results into post objects
            $posts = array();
            $seen_dates = array();
            
            foreach ($variants as $variant) {
                $post = get_post($variant->ID);
                if ($post && !isset($seen_dates[$variant->start_date])) {
                    // Add the variant metadata
                    $post->start_date = $variant->start_date;
                    $post->end_date = $variant->end_date;
                    $post->variant_description = $variant->variant_description;
                    $post->debug_price = $variant->price;
                    
                    // Add debug info to post excerpt
                    $post->post_excerpt = sprintf(
                        '[DEBUG] Price: %s | ID: %s | %s',
                        $variant->price,
                        $variant->ID,
                        $post->post_excerpt
                    );
                    
                    $posts[] = $post;
                    $seen_dates[$variant->start_date] = true;
                }
            }
            
            return $posts;
        }

        // Handle archived products query
        if ($query_obj->object_type === 'archived_products_query') {
            $args = array_merge($base_args, array(
                'posts_per_page' => isset($query_obj->settings['posts_per_page']) ? $query_obj->settings['posts_per_page'] : 64
            ));
            
            // Override the post_status to archiwum
            $args['post_status'] = 'archiwum';
            
            return get_posts($args);
        }

        // Handle unique variants all status query (specifically for archived products)
        if ($query_obj->object_type === 'unique_variants_all_status_query') {
            global $wpdb;
            
            // Get the current product ID
            $product_id = get_the_ID();
            if (!$product_id) {
                return array();
            }
            
            // Get the current product's status to ensure it's archived
            $current_product = get_post($product_id);
            if ($current_product->post_status !== 'archiwum') {
                return array(); // Return empty if not an archived product
            }
            
            // Get default attributes for the product
            $default_attributes = get_post_meta($product_id, '_default_attributes', true);
            $default_variant_id = null;
            
            // Find default variant ID efficiently if default attributes exist
            if ($default_attributes && is_array($default_attributes)) {
                foreach ($default_attributes as $attr_name => $default_value) {
                    $meta_key = 'attribute_' . $attr_name;
                    $variant_query = $wpdb->prepare("
                        SELECT p.ID 
                        FROM {$wpdb->posts} p
                        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                        WHERE p.post_parent = %d
                        AND p.post_type = 'product_variation'
                        AND p.post_status = 'publish'
                        AND pm.meta_key = %s
                        AND pm.meta_value = %s
                        AND EXISTS (
                            SELECT 1 FROM {$wpdb->posts} parent
                            WHERE parent.ID = p.post_parent
                            AND parent.post_status = 'archiwum'
                        )
                        LIMIT 1
                    ", $product_id, $meta_key, $default_value);
                    
                    $found_variant = $wpdb->get_var($variant_query);
                    if ($found_variant) {
                        $default_variant_id = $found_variant;
                        break; // Found a match, use it
                    }
                }
            }
            
            // Simple query - get unique variants by date in admin order for archived products
            $query = $wpdb->prepare("
                WITH RankedVariants AS (
                    SELECT 
                        p.ID,
                        pm_start.meta_value as start_date,
                        pm_end.meta_value as end_date,
                        p.post_excerpt as variant_description,
                        p.post_parent as product_id,
                        CAST(COALESCE(pm_price.meta_value, '999999') AS DECIMAL(10,2)) as price,
                        p.menu_order,
                        ROW_NUMBER() OVER (
                            PARTITION BY pm_start.meta_value 
                            ORDER BY 
                                CASE WHEN p.ID = %s THEN 0 ELSE 1 END,
                                p.menu_order ASC,
                                p.ID ASC
                        ) as rn
                    FROM {$wpdb->posts} p
                    LEFT JOIN {$wpdb->postmeta} pm_start ON p.ID = pm_start.post_id 
                        AND pm_start.meta_key = 'wyprawa-termin__data-poczatkowa'
                    LEFT JOIN {$wpdb->postmeta} pm_end ON p.ID = pm_end.post_id 
                        AND pm_end.meta_key = 'wyprawa-termin__data-koncowa'
                    LEFT JOIN {$wpdb->postmeta} pm_price ON p.ID = pm_price.post_id 
                        AND pm_price.meta_key = 'wyprawa-termin__cena-nie-liczac-lotow'
                    LEFT JOIN {$wpdb->postmeta} pm_stock_status ON p.ID = pm_stock_status.post_id 
                        AND pm_stock_status.meta_key = '_stock_status'
                    LEFT JOIN {$wpdb->postmeta} pm_stock ON p.ID = pm_stock.post_id 
                        AND pm_stock.meta_key = '_stock'
                    WHERE p.post_parent = %d
                    AND p.post_type = 'product_variation'
                    AND p.post_status = 'publish'
                    AND EXISTS (
                        SELECT 1 FROM {$wpdb->posts} parent
                        WHERE parent.ID = p.post_parent
                        AND parent.post_status = 'archiwum'
                    )
                    AND (
                        pm_stock_status.meta_value = 'instock' 
                        OR (pm_stock_status.meta_value = 'outofstock' AND CAST(COALESCE(pm_stock.meta_value, '0') AS SIGNED) > 0)
                        OR pm_stock_status.meta_value IS NULL
                    )
                )
                SELECT rv.ID, rv.start_date, rv.end_date, rv.variant_description, rv.product_id, rv.price, rv.menu_order
                FROM RankedVariants rv
                WHERE rv.rn = 1
                ORDER BY 
                    CASE WHEN rv.ID = %s THEN 0 ELSE 1 END,
                    COALESCE(rv.start_date, 'zzz') ASC
            ", $default_variant_id ?: 0, $product_id, $default_variant_id ?: 0);
            
            $variants = $wpdb->get_results($query);
            
            // Transform results into post objects
            $posts = array();
            $seen_dates = array();
            
            foreach ($variants as $variant) {
                $post = get_post($variant->ID);
                if ($post && !isset($seen_dates[$variant->start_date])) {
                    // Add the variant metadata
                    $post->start_date = $variant->start_date;
                    $post->end_date = $variant->end_date;
                    $post->variant_description = $variant->variant_description;
                    $post->debug_price = $variant->price;
                    
                    // Add debug info to post excerpt
                    $post->post_excerpt = sprintf(
                        '[DEBUG] Price: %s | ID: %s | Archived Product | %s',
                        $variant->price,
                        $variant->ID,
                        $post->post_excerpt
                    );
                    
                    $posts[] = $post;
                    $seen_dates[$variant->start_date] = true;
                }
            }
            
            return $posts;
        }
        
        return $results;
    }
}

/* Helper function to get filtered products with featured first */
if (!function_exists('sa_get_filtered_products')) {
    function sa_get_filtered_products($args, $featured_term) {
        $debug_mode = current_user_can('manage_options');
        
        if ($debug_mode) {
            error_log('  >> sa_get_filtered_products called');
            error_log('  >> Input args: ' . print_r($args, true));
        }
        
        // FORCE post_status to publish if not set or if it's wrong
        $args['post_status'] = 'publish';
        
        // Get featured products first
        $args_featured = array_merge($args, array(
            'posts_per_page' => -1,
            'tax_query'      => array_merge(
                isset($args['tax_query']) ? (array)$args['tax_query'] : array(),
                array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'term_id',
                        'terms'    => $featured_term->term_id,
                    )
                )
            )
        ));

        // Get non-featured products
        $args_normal = array_merge($args, array(
            'posts_per_page' => -1,
            'tax_query'      => array_merge(
                isset($args['tax_query']) ? (array)$args['tax_query'] : array(),
                array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'term_id',
                        'terms'    => $featured_term->term_id,
                        'operator' => 'NOT IN',
                    )
                )
            )
        ));

        if ($debug_mode) {
            error_log('  >> Featured query args: ' . print_r($args_featured, true));
            error_log('  >> Normal query args: ' . print_r($args_normal, true));
        }

        // Run both queries
        $featured_products = get_posts($args_featured);
        $normal_products = get_posts($args_normal);

        if ($debug_mode) {
            error_log('  >> Featured products count: ' . count($featured_products));
            error_log('  >> Normal products count: ' . count($normal_products));
        }

        // Combine results
        return array_merge($featured_products, $normal_products);
    }
}

/* Setup loop objects */
if (!function_exists('sa_setup_custom_loop_objects')) {
    add_filter('bricks/query/loop_object', 'sa_setup_custom_loop_objects', 10, 3);
    function sa_setup_custom_loop_objects($loop_object, $loop_key, $query_obj) {
        global $current_month_term_id;
        
        // For months query, store the current term ID
        if ($query_obj->object_type === 'custom_months_query') {
            if (is_object($loop_object) && isset($loop_object->term_id)) {
                $current_month_term_id = $loop_object->term_id;
            }
            return $loop_object;
        }
        
        // For variants query, setup post data
        if ($query_obj->object_type === 'custom_variants_query' || 
            $query_obj->object_type === 'unique_variants_query' ||
            $query_obj->object_type === 'unique_variants_all_status_query') {
            global $post;
            $post = get_post($loop_object);
            setup_postdata($post);
        }
        
        return $loop_object;
    }
}

/* Helper function to display variant information */
if (!function_exists('sa_display_variant_info')) {
    function sa_display_variant_info($post) {
        if (!$post) return '';
        
        // Get start and end dates from post meta
        $start_date = get_post_meta($post->ID, 'wyprawa-termin__data-poczatkowa', true);
        $end_date = get_post_meta($post->ID, 'wyprawa-termin__data-koncowa', true);
        
        // If we have both dates, format them
        if ($start_date && $end_date) {
            $start = DateTime::createFromFormat('Ymd', $start_date);
            $end = DateTime::createFromFormat('Ymd', $end_date);
            if ($start && $end) {
                return $start->format('d.m.Y') . ' - ' . $end->format('d.m.Y');
            }
        }
        
        // Fallback to variant description
        return trim(preg_replace('/termin:\s*/i', '', $post->variant_description));
    }
}

/* Add dynamic data tag for variant info */
add_filter('bricks/dynamic_tags_list', function($tags) {
    $tags[] = [
        'name' => '{variant_info}',
        'label' => 'Variant Info',
        'group' => 'Custom'
    ];
    return $tags;
});

add_filter('bricks/dynamic_data/render_tag', function($tag, $post, $context) {
    if ($tag !== '{variant_info}') {
        return $tag;
    }
    return sa_display_variant_info($post);
}, 10, 3); 