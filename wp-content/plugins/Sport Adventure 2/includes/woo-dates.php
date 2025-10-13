<?php
/**
 * WooCommerce Date Handling
 */

defined('ABSPATH') || exit;

// Store dates as timestamps
add_filter('acf/update_value/name=wyprawa__najblizszy-termin', function($value) {
    if ($value) {
        return strtotime($value);  // Store as timestamp
    }
    return $value;
});

// Display dates in human-readable format
add_filter('acf/format_value/name=wyprawa__najblizszy-termin', function($value) {
    if ($value && is_numeric($value)) {
        return date('d.m.Y', $value);  // Convert back to readable format for display
    }
    return $value;
}); 