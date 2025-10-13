<?php
/**
 * ACF Variation Fields - Production Implementation
 * 
 * Provides seamless integration of ACF fields with WooCommerce product variations.
 * 
 * Features:
 * - Native ACF field rendering and saving
 * - Support for all ACF field types (text, date, file, relationship, etc.)
 * - Bricks Builder integration
 * - Frontend variation data exposure
 * 
 * @package Sport Adventure Custom
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

class SA_ACF_Variation_Fields {
    
    public function __construct() {
        add_action('init', [$this, 'init']);
    }
    
    /**
     * Initialize the ACF variation fields system
     */
    public function init() {
        // Only proceed if ACF is active
        if (!function_exists('acf_get_field_groups')) {
            return;
        }
        
        // Hook into rendering and saving with higher priority
        add_action('woocommerce_product_after_variable_attributes', [$this, 'render_variation_fields'], 5, 3);
        add_action('woocommerce_save_product_variation', [$this, 'save_variation_fields'], 10, 2);
        
        // Frontend integration
        add_filter('woocommerce_available_variation', [$this, 'add_variation_acf_data'], 10, 3);
        
        // Bricks Builder integration
        add_filter('bricks/query/loop_object', [$this, 'add_acf_to_loop_object'], 10, 1);
        add_filter('bricks/query/run', [$this, 'enable_variation_queries'], 10, 2);
        
        // Add admin scripts for ACF field initialization
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        
    }
    
    /**
     * Render ACF fields in variation edit screen
     */
    public function render_variation_fields($loop_index, $variation_data, $variation_post) {
        try {
            // Validate variation post
            if (!$variation_post || !is_object($variation_post) || empty($variation_post->ID)) {
                echo '<div class="acf-variation-fields-error">Invalid variation post</div>';
                return;
            }
            
            // Get field groups - prioritize field group 165 (Wyprawa termin)
            $field_groups = acf_get_field_groups(['post_type' => 'product_variation']);
            
            // Filter to only show field group 165 if it exists
            $target_group = null;
            foreach ($field_groups as $group) {
                if ($group['ID'] == 165) {
                    $target_group = $group;
                    break;
                }
            }
            
            // If field group 165 not found, use all groups
            if (!$target_group) {
                if (empty($field_groups) || !is_array($field_groups)) {
                    return;
                }
                $target_group = $field_groups[0]; // Use first available group
            }
            
            $field_groups = [$target_group]; // Only process the target group
            
            // Ensure ACF assets are loaded
            if (function_exists('acf_enqueue_scripts')) {
                acf_enqueue_scripts();
            }
            
            // Set up ACF form data for this variation
            if (function_exists('acf_form_data')) {
                try {
                    acf_form_data([
                        'post_id' => $variation_post->ID,
                        'nonce' => 'edit',
                    ]);
                } catch (Exception $e) {
                    // Continue without ACF form data if it fails
                }
            }
            
            // Ensure ACF field values are loaded
            if (function_exists('acf_setup_meta')) {
                $fields_data = get_fields($variation_post->ID);
                // Only setup meta if we have valid field data
                if ($fields_data && is_array($fields_data)) {
                    acf_setup_meta($fields_data, $variation_post->ID, true);
                }
            }
            
            // Add wrapper with proper ACF classes and data attributes
            echo '<div class="acf-variation-fields" data-variation-id="' . esc_attr($variation_post->ID) . '" style="position: relative; z-index: 1;">';
            
            // Render fields for each field group
            foreach ($field_groups as $field_group) {
                // Ensure field group is valid
                if (!is_array($field_group) || empty($field_group['ID'])) {
                    echo '<div class="acf-field-error">Invalid field group</div>';
                    continue;
                }
                
                $fields = acf_get_fields($field_group);
                if (empty($fields) || !is_array($fields)) {
                    continue;
                }
                
                echo '<div class="acf-fields -left">';
                
                // Add memory and time limits for field rendering to prevent 500 errors
                $old_memory_limit = ini_get('memory_limit');
                $old_time_limit = ini_get('max_execution_time');
                ini_set('memory_limit', '512M');
                set_time_limit(30);
                
                try {
                    acf_render_fields($fields, $variation_post->ID, 'div', 'label');
                } catch (Exception $e) {
                    echo '<div class="acf-field-error">Error rendering fields: ' . esc_html($e->getMessage()) . '</div>';
                } catch (Error $e) {
                    echo '<div class="acf-field-error">Fatal error rendering fields: ' . esc_html($e->getMessage()) . '</div>';
                }
                
                // Restore original limits
                ini_set('memory_limit', $old_memory_limit);
                set_time_limit($old_time_limit);
                
                echo '</div>';
            }
            
            echo '</div>';
            
            // Add link to full variation edit screen
            $edit_link = get_edit_post_link($variation_post->ID);
            if ($edit_link) {
                echo '<p><a href="' . esc_url($edit_link) . '" target="_blank" class="button button-small">' . 
                     esc_html__('Edit variation (full screen)', 'sport-adventure-custom') . '</a></p>';
            }
            
        } catch (Exception $e) {
            echo '<div class="acf-variation-fields-error">Error rendering ACF fields: ' . esc_html($e->getMessage()) . '</div>';
        } catch (Error $e) {
            echo '<div class="acf-variation-fields-error">Fatal error rendering ACF fields: ' . esc_html($e->getMessage()) . '</div>';
        }
    }
    
    /**
     * Save ACF fields when variation is saved
     */
    public function save_variation_fields($variation_id, $loop_index) {
        // Use ACF's built-in save function
        if (function_exists('acf_save_post')) {
            try {
                acf_save_post($variation_id);
            } catch (Exception $e) {
                // Log error silently
            }
        }
    }
    
    /**
     * Add ACF data to variation data for frontend
     */
    public function add_variation_acf_data($variation_data, $product, $variation) {
        if (!function_exists('get_field_objects')) {
            return $variation_data;
        }
        
        $variation_id = $variation->get_id();
        $field_objects = get_field_objects($variation_id);
        
        if (!$field_objects || !is_array($field_objects)) {
            return $variation_data;
        }
        
        // Add ACF fields to variation data
        $acf_data = [];
        foreach ($field_objects as $field_name => $field_object) {
            $acf_data[$field_name] = $field_object['value'];
        }
        
        $variation_data['acf'] = $acf_data;
        
        return $variation_data;
    }
    
    /**
     * Add ACF fields to Bricks loop objects
     */
    public function add_acf_to_loop_object($loop_object) {
        if (!is_a($loop_object, 'WC_Product_Variation') && !is_a($loop_object, 'WC_Product')) {
            return $loop_object;
        }
        
        $object_id = $loop_object->get_id();
        
        // Get ACF fields
        $fields = get_fields($object_id);
        if ($fields) {
            foreach ($fields as $key => $value) {
                $loop_object->$key = $value;
            }
        }
        
        // If it's a variation, also get parent product fields
        if (is_a($loop_object, 'WC_Product_Variation')) {
            $parent_id = $loop_object->get_parent_id();
            $parent_fields = get_fields($parent_id);
            if ($parent_fields) {
                foreach ($parent_fields as $key => $value) {
                    // Only add parent fields if they don't exist in variation
                    if (!isset($loop_object->$key)) {
                        $loop_object->$key = $value;
                    }
                }
            }
        }
        
        return $loop_object;
    }
    
    /**
     * Enable variation queries in Bricks
     */
    public function enable_variation_queries($query_vars, $query_object) {
        if ($query_object->object_type === 'woocommerce' && 
            isset($query_vars['post_type']) && 
            $query_vars['post_type'] === 'product') {
            $query_vars['post_type'] = ['product', 'product_variation'];
        }
        return $query_vars;
    }
    
    /**
     * Enqueue admin scripts for ACF field initialization
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on product edit pages
        if (!in_array($hook, ['post.php', 'post-new.php'])) {
            return;
        }
        
        global $post;
        if (!$post || $post->post_type !== 'product') {
            return;
        }
        
        // Enqueue ACF scripts if not already loaded
        if (function_exists('acf_enqueue_scripts')) {
            acf_enqueue_scripts();
        }
        
        // Enqueue our custom JavaScript file
        wp_enqueue_script(
            'sa-acf-variation-fields',
            plugin_dir_url(__FILE__) . '../assets/js/acf-variation-fields.js',
            ['jquery', 'acf-input'],
            '1.0.0',
            true
        );
    }
    
    
}

// Initialize the ACF variation fields system
new SA_ACF_Variation_Fields();
