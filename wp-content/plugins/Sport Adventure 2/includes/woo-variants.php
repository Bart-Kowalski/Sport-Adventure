<?php
/**
 * WooCommerce Date Field Formatting
 * 
 * Handles date field formatting for ACF date picker fields in WooCommerce products.
 * This file only contains date formatting logic - ACF variation field functionality
 * is handled by acf-variation-fields.php
 */

defined('ABSPATH') || exit;

// Format dates in admin columns
add_filter('acf/format_value/type=date_picker', function($value, $post_id, $field) {
    if ($value && strpos(get_post_type($post_id), 'product') !== false) {
        // First try Ymd format (storage format)
        $date = DateTime::createFromFormat('Ymd', $value);
        if (!$date) {
            // Try d.m.Y format
            $date = DateTime::createFromFormat('d.m.Y', $value);
        }
        if (!$date) {
            // Try d/m/Y format
            $date = DateTime::createFromFormat('d/m/Y', $value);
        }
        if ($date) {
            // Return in the format specified by ACF field settings
            $return_format = isset($field['return_format']) ? $field['return_format'] : 'd/m/Y';
            return $date->format($return_format);
        }
    }
    return $value;
}, 20, 3);

// OLD ACF IMPLEMENTATION REMOVED - Now using acf-variation-fields.php

// Add a filter to ensure dates are loaded correctly
add_filter('acf/load_value/type=date_picker', function($value, $post_id, $field) {
    if ($value && strpos(get_post_type($post_id), 'product') !== false) {
        // Try to parse the date from Ymd format
        $date = DateTime::createFromFormat('Ymd', $value);
        if ($date) {
            // Return in the format specified by ACF field settings
            $return_format = isset($field['return_format']) ? $field['return_format'] : 'd/m/Y';
            return $date->format($return_format);
        }
        
        // Try other formats if Ymd fails
        $formats_to_try = ['d.m.Y', 'd/m/Y', 'Y-m-d', 'd-m-Y'];
        foreach ($formats_to_try as $format) {
            $date = DateTime::createFromFormat($format, $value);
            if ($date) {
                $return_format = isset($field['return_format']) ? $field['return_format'] : 'd/m/Y';
                return $date->format($return_format);
            }
        }
    }
    return $value;
}, 10, 3);

// NOTE: Bricks Builder integration and variation data exposure 
// are now handled by acf-variation-fields.php 