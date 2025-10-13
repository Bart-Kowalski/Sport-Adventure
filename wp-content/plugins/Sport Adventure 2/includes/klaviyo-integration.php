<?php
/**
 * Klaviyo Integration for Sport Adventure Custom Plugin
 * Handles proper integration with Klaviyo plugin for profile creation
 */

if (!defined('WPINC')) {
    die;
}

/**
 * Enhanced Klaviyo checkbox sync function
 */
function sa_klaviyo_sync_checkbox($order_id) {
    // Check if our custom checkbox is checked
    if (!isset($_POST['kl_newsletter_checkbox']) || $_POST['kl_newsletter_checkbox'] != 1) {
        return;
    }
    
    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }
    
    // Store our custom value
    update_post_meta($order_id, '_kl_newsletter_checkbox', '1');
    
    // Get customer data
    $email = $order->get_billing_email();
    $first_name = $order->get_billing_first_name();
    $last_name = $order->get_billing_last_name();
    
    if (!$email) {
        return;
    }
    
    // Use the most reliable method first
    sa_klaviyo_simple_subscribe($email, $first_name, $last_name, $order);
}

/**
 * Simple and reliable Klaviyo subscription method
 */
function sa_klaviyo_simple_subscribe($email, $first_name, $last_name, $order) {
    // Method 1: Try the most common Klaviyo plugin function
    if (function_exists('klaviyo_subscribe_to_newsletter')) {
        klaviyo_subscribe_to_newsletter($email, $first_name, $last_name);
        return;
    }
    
    // Method 2: Try WooCommerce Klaviyo plugin
    if (function_exists('klaviyo_subscribe_email')) {
        klaviyo_subscribe_email($email, array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'order_id' => $order->get_id()
        ));
        return;
    }
    
    // Method 3: Try to trigger Klaviyo's built-in subscription process
    sa_klaviyo_trigger_subscription($email, $first_name, $last_name, $order);
    
    // Method 4: Use WooCommerce hooks that Klaviyo plugin might be listening to
    do_action('klaviyo_subscribe_customer', $email, array(
        'first_name' => $first_name,
        'last_name' => $last_name,
        'order_id' => $order->get_id()
    ));
    
    // Method 5: Trigger any Klaviyo-related hooks
    do_action('woocommerce_klaviyo_subscribe', $email, $first_name, $last_name, $order);
    
    // Method 6: Last resort - try direct API call with common settings
    sa_klaviyo_fallback_api_call($email, $first_name, $last_name);
}

/**
 * Fallback API call with common Klaviyo settings
 */
function sa_klaviyo_fallback_api_call($email, $first_name, $last_name) {
    // Try to find API key in common locations
    $api_key = get_option('klaviyo_private_api_key') ?: 
               get_option('klaviyo_api_key') ?: 
               get_option('klaviyo_public_api_key');
    
    if (!$api_key) {
        error_log('Klaviyo: No API key found in common locations');
        return;
    }
    
    // Try to find list ID in common locations
    $list_id = get_option('klaviyo_list_id') ?: 
               get_option('klaviyo_newsletter_list_id') ?: 
               get_option('klaviyo_default_list_id');
    
    if (!$list_id) {
        error_log('Klaviyo: No list ID found in common locations');
        return;
    }
    
    // Use simple API v2 call
    $data = array(
        'profiles' => array(
            array(
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                '$source' => 'woocommerce_checkout'
            )
        )
    );
    
    $url = "https://a.klaviyo.com/api/v2/list/{$list_id}/members";
    
    $response = wp_remote_post($url, array(
        'headers' => array(
            'Authorization' => 'Klaviyo-API-Key ' . $api_key,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ),
        'body' => json_encode($data),
        'timeout' => 30
    ));
    
    if (is_wp_error($response)) {
        error_log('Klaviyo API Error: ' . $response->get_error_message());
    } else {
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            error_log('Klaviyo API Error: Response code ' . $response_code . ' - ' . wp_remote_retrieve_body($response));
        } else {
            error_log('Klaviyo: Successfully subscribed ' . $email . ' to list ' . $list_id);
        }
    }
}

/**
 * Trigger Klaviyo's built-in subscription process
 */
function sa_klaviyo_trigger_subscription($email, $first_name, $last_name, $order) {
    // Try to trigger Klaviyo's subscription event
    if (function_exists('klaviyo_track_event')) {
        klaviyo_track_event('Subscribed to List', array(
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'order_id' => $order->get_id()
        ));
    }
    
    // Try to trigger Klaviyo's profile update
    if (function_exists('klaviyo_update_profile')) {
        klaviyo_update_profile($email, array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            '$consent' => array('email'),
            'subscription_status' => 'subscribed'
        ));
    }
    
    // Try to trigger Klaviyo's list subscription
    if (function_exists('klaviyo_subscribe_to_list')) {
        $list_id = get_option('klaviyo_list_id') ?: get_option('klaviyo_newsletter_list_id');
        if ($list_id) {
            klaviyo_subscribe_to_list($list_id, $email, array(
                'first_name' => $first_name,
                'last_name' => $last_name
            ));
        }
    }
}



/**
 * Enhanced JavaScript for checkbox synchronization
 */
function sa_klaviyo_checkbox_sync_script() {
    if (!is_checkout()) {
        return;
    }
    ?>
    <script>
    jQuery(document).ready(function($) {
        var $customCheckbox = $('#kl_newsletter_checkbox');
        
        function syncKlaviyoCheckboxes(isChecked) {
            // Update hidden Klaviyo plugin field if it exists
            var $klaviyoField = $('#kl_newsletter_checkbox_field input[type="checkbox"]');
            if ($klaviyoField.length) {
                $klaviyoField.prop('checked', isChecked);
            }
            
            // Update any other Klaviyo-related fields
            $('input[name*="klaviyo"], input[name*="newsletter"], input[name*="subscribe"]').prop('checked', isChecked);
            
            // Trigger change events to ensure Klaviyo plugin detects the change
            $('input[name*="klaviyo"], input[name*="newsletter"], input[name*="subscribe"]').trigger('change');
            
            // Also trigger any custom events that Klaviyo plugin might be listening to
            $(document).trigger('klaviyo_checkbox_changed', [isChecked]);
        }
        
        // Sync on checkbox change
        $customCheckbox.on('change', function() {
            var isChecked = $(this).is(':checked');
            syncKlaviyoCheckboxes(isChecked);
        });
        
        // Initialize sync on page load
        syncKlaviyoCheckboxes($customCheckbox.is(':checked'));
        
        // Also sync when checkout form updates
        $(document.body).on('updated_checkout', function() {
            syncKlaviyoCheckboxes($customCheckbox.is(':checked'));
        });
    });
    </script>
    <?php
}

// Hook the enhanced functions
// For immediate subscription (any order status):
add_action('woocommerce_checkout_update_order_meta', 'sa_klaviyo_sync_checkbox', 10, 1);

// For subscription only on successful payment, uncomment these lines and comment out the above:
// add_action('woocommerce_payment_complete', 'sa_klaviyo_sync_checkbox', 10, 1);
// add_action('woocommerce_order_status_completed', 'sa_klaviyo_sync_checkbox', 10, 1);
// add_action('woocommerce_order_status_processing', 'sa_klaviyo_sync_checkbox', 10, 1);

add_action('wp_footer', 'sa_klaviyo_checkbox_sync_script');

// Add compatibility with different Klaviyo plugin field names
add_filter('woocommerce_checkout_fields', 'sa_klaviyo_field_compatibility', 100);
function sa_klaviyo_field_compatibility($fields) {
    // Ensure our custom field is properly recognized
    if (isset($fields['billing']['kl_newsletter_checkbox'])) {
        $fields['billing']['kl_newsletter_checkbox']['custom_attributes'] = array(
            'data-klaviyo-field' => 'true'
        );
    }
    
    return $fields;
}

// Add debugging for Klaviyo integration (only in debug mode)
if (sa_is_debug_enabled()) {
    add_action('woocommerce_checkout_update_order_meta', 'sa_klaviyo_debug_log', 5, 1);
    function sa_klaviyo_debug_log($order_id) {
        if (isset($_POST['kl_newsletter_checkbox']) && sa_is_php_debug_enabled()) {
            error_log("Klaviyo Debug: Checkbox value = " . $_POST['kl_newsletter_checkbox']);
        }
    }
} 