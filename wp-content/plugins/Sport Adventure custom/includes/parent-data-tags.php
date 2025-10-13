<?php
/**
 * Parent Data Tags for Product Variants
 */

defined('ABSPATH') || exit;

// Register tags
add_filter('bricks/dynamic_tags_list', function($tags) {
    $tags[] = [
        'name' => '{parent_product_title}',
        'label' => 'Parent Product Title',
        'group' => 'Product Parent',
    ];
    
    $tags[] = [
        'name' => '{parent_product_h1}',
        'label' => 'Parent Product H1',
        'group' => 'Product Parent',
    ];
    
    $tags[] = [
        'name' => '{parent_post_terms_lokalizacja:plain}',
        'label' => 'Parent Product Location',
        'group' => 'Product Parent',
    ];
    
    $tags[] = [
        'name' => '{parent_product_desc}',
        'label' => 'Parent Product Description',
        'group' => 'Product Parent',
    ];
    
    $tags[] = [
        'name' => '{variant_title}',
        'label' => 'Variant Title Only',
        'group' => 'Product Variant',
    ];
    
    return $tags;
});

// Render tags and content
add_filter('bricks/dynamic_data/render_content', function($content, $post, $context) {
    if (strpos($content, '{parent_product_') !== false || strpos($content, '{parent_post_terms_') !== false) {
        $variation = wc_get_product(get_the_ID());
        if ($variation && $variation->get_parent_id()) {
            $parent_id = $variation->get_parent_id();
            $parent = wc_get_product($parent_id);
            
            // Replace title
            if (strpos($content, '{parent_product_title}') !== false && $parent) {
                $title = (string) $parent->get_title();
                $content = str_replace('{parent_product_title}', $title ?: '', $content);
            }
            
            // Replace H1
            if (strpos($content, '{parent_product_h1}') !== false) {
                $h1 = (string) get_field('wyprawa__h1', $parent_id);
                $content = str_replace('{parent_product_h1}', $h1 ?: '', $content);
            }
            
            // Replace location
            if (strpos($content, '{parent_post_terms_lokalizacja:plain}') !== false) {
                $terms = get_the_terms($parent_id, 'lokalizacja');
                $location = $terms && !is_wp_error($terms) ? (string) $terms[0]->name : '';
                $content = str_replace('{parent_post_terms_lokalizacja:plain}', $location, $content);
            }
            
            // Replace description
            if (strpos($content, '{parent_product_desc}') !== false) {
                $desc = (string) get_field('wyprawa__skrocony-opis', $parent_id);
                $content = str_replace('{parent_product_desc}', $desc ?: '', $content);
            }
        }
    }
    
    // Replace variant title
    if (strpos($content, '{variant_title}') !== false) {
        $variation = wc_get_product(get_the_ID());
        if ($variation && $variation->is_type('variation')) {
            $parent = wc_get_product($variation->get_parent_id());
            if ($parent) {
                $full_title = $variation->get_name();
                $parent_title = $parent->get_name();
                
                // If the full title starts with the parent title, remove it and the separator
                if (strpos($full_title, $parent_title) === 0) {
                    $variant_title = trim(str_replace($parent_title . ' - ', '', $full_title));
                } else {
                    // If not found, just use the full variation title
                    $variant_title = $full_title;
                }
                
                $content = str_replace('{variant_title}', $variant_title, $content);
            }
        }
    }
    
    return $content;
}, 10, 3);

add_filter('bricks/frontend/render_data', function($content, $post) {
    return apply_filters('bricks/dynamic_data/render_content', $content, $post, 'text');
}, 10, 2); 