<?php
/**
 * WooCommerce Email Customizations
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Override WooCommerce email template with our custom one
 * HIGH PRIORITY to override any other template files
 */
function sa_override_woocommerce_email_template($located, $template_name, $args, $template_path, $default_path) {
    // Only override emails with order data
    $order_templates = [
        'emails/customer-processing-order.php',
        'emails/customer-completed-order.php',
        'emails/customer-on-hold-order.php',
        'emails/customer-invoice.php',
        'emails/admin-new-order.php',
    ];

    $header_template = 'emails/email-header.php';
    $footer_template = 'emails/email-footer.php';
    
    // Override header template
    if ($template_name === $header_template) {
        return __DIR__ . '/templates/email-header.php';
    }
    
    // Leave footer template as is
    if ($template_name === $footer_template) {
        return WC()->plugin_path() . '/templates/' . $footer_template;
    }
    
    // Override order templates with our custom one
    if (in_array($template_name, $order_templates)) {
        // Make sure we have an order object in the args
        if (isset($args['order']) && is_a($args['order'], 'WC_Order')) {
            $custom_template = __DIR__ . '/templates/custom-order-email.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
    }

    return $located;
}
// Use very high priority to override any other templates
add_filter('wc_get_template', 'sa_override_woocommerce_email_template', 9999, 5);

/**
 * Disable WooCommerce template parts
 * We're handling everything in our custom template
 */
function sa_disable_woocommerce_email_templates() {
    // Remove all hooks from these actions as we handle them in our custom template
    remove_all_actions('woocommerce_email_order_details');
    remove_all_actions('woocommerce_email_order_meta');
    remove_all_actions('woocommerce_email_customer_details');
    remove_all_actions('woocommerce_email_after_order_table');
    remove_all_actions('woocommerce_email_before_order_table');
    remove_all_actions('woocommerce_email_billing_details');
    remove_all_actions('woocommerce_email_shipping_details');
    remove_all_actions('woocommerce_get_order_item_totals');
}
add_action('init', 'sa_disable_woocommerce_email_templates', 1);

/**
 * Format price display in emails
 */
function sa_custom_price_format($format) {
    return '%1$s %2$s';  // Ensures space before currency
}
add_filter('woocommerce_price_format', 'sa_custom_price_format', 100);

/**
 * Remove decimals from prices in emails
 */
function sa_remove_price_decimals($args) {
    $args['decimals'] = 0;
    return $args;
}
add_filter('wc_price_args', 'sa_remove_price_decimals', 100);

/**
 * Add custom styles to email
 */
function sa_custom_woocommerce_email_styles($css) {
    // Add our custom styles - including media queries in the same block
    $custom_css = '
        body { 
            background-color: #f7f7f7; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            margin: 0;
            padding: 0;
        }
        #wrapper { 
            max-width: 600px; 
            margin: 0 auto; 
            padding: 40px 20px; 
            background-color: #f7f7f7;
        }
        #template_container { 
            background-color: #fff; 
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            margin: 0 auto;
        }
        #template_header { 
            background-color: #474747; 
            padding: 36px 48px; 
            border-radius: 8px 8px 0 0; 
            text-align: center;
            color: #fff;
        }
        #template_header h1 {
            color: #fff;
            font-size: 24px;
            margin: 0 0 10px;
            text-align: left;
            line-height: 150%;
            font-weight: 300;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        #template_body { 
            padding: 48px; 
            background: #fff;
            border-radius: 0 0 6px 6px;
        }
        #body_content {
            padding: 0;
        }
        #body_content_inner {
            color: #252525;
            font-size: 14px;
            line-height: 150%;
            text-align: left;
        }
        h1, h2, h3, h4 { 
            margin: 0 0 16px; 
            color: #333; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            text-align: left;
            line-height: 130%;
        }
        h2 {
            font-size: 18px;
            font-weight: bold;
        }
        h3 {
            font-size: 16px;
            font-weight: bold;
        }
        p {
            margin: 0 0 16px;
        }
        .order-success-message {
            margin-bottom: 30px;
        }
        .order-success-message h2 {
            margin-bottom: 10px;
        }
        .order-bank-details {
            margin-bottom: 30px;
            background: #f8fbff;
            padding: 24px;
            border-radius: 8px;
            border: 1px solid rgba(0, 114, 237, 0.1);
        }
        .bank-account-number {
            background: rgba(0, 114, 237, 0.05);
            padding: 16px;
            border-radius: 8px;
            margin: 12px 0;
            font-size: 18px;
            color: #0072ed;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .bank-detail {
            margin-bottom: 10px;
        }
        .bank-detail__label {
            font-weight: 600;
            margin-right: 10px;
            color: #0072ed;
        }
        .order-item {
            margin: 20px 0;
            background: #f8f8f8;
            border-radius: 8px;
            padding: 20px;
        }
        .order-item__title {
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 10px;
            color: #333;
        }
        .order-item__variant {
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
        }
        .order-item__detail {
            margin: 0 0 10px;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 10px;
        }
        .order-item__label {
            font-weight: 600;
            color: #666;
            min-width: 200px;
        }
        .order-item__section-title {
            font-size: 18px;
            font-weight: 600;
            margin: 20px 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .participant {
            margin: 0 0 20px;
            padding: 15px;
            background: #fff;
            border-radius: 6px;
        }
        .participant__title {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 15px;
            color: #333;
        }
        .participant__field {
            margin: 0 0 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .participant__label {
            font-weight: 600;
            color: #666;
            min-width: 100px;
        }
        .order-totals {
            margin: 30px 0;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
        }
        .order-totals__header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .order-totals__detail {
            margin: 0 0 10px;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 10px;
        }
        .order-totals__label {
            font-weight: 600;
            color: #666;
            min-width: 200px;
        }
        .order-totals__amount {
            font-size: 18px;
            font-weight: 600;
        }
        #template_footer {
            padding: 0 48px 48px;
            text-align: center;
        }
        
        @media screen and (max-width: 600px) {
            #wrapper {
                padding: 20px 10px;
            }
            #template_container {
                width: 100% !important;
            }
            #header_wrapper {
                padding: 27px 36px !important;
                font-size: 24px;
            }
            #body_content {
                padding: 24px;
            }
            #body_content_inner {
                font-size: 14px !important;
            }
        }
        
        @media (max-width: 768px) {
            .order-item {
                padding: 15px;
            }
            .order-item__title {
                font-size: 18px;
            }
            .order-item__detail {
                flex-direction: column;
                gap: 5px;
            }
            .order-item__label {
                min-width: auto;
            }
            .participant {
                padding: 12px;
            }
            .order-totals {
                padding: 15px;
            }
            .order-totals__detail {
                flex-direction: column;
                gap: 5px;
            }
            .order-totals__label {
                min-width: auto;
            }
        }
        
        /* Coupon Styles */
        .order-coupons {
            margin: 20px 0;
            background: #f0f9ff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid rgba(0, 114, 237, 0.1);
        }
        .order-coupons__header h3 {
            color: #0072ed;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .order-coupons__detail {
            margin: 10px 0;
            padding: 8px 12px;
            background: rgba(0, 114, 237, 0.05);
            border-radius: 4px;
        }
        .order-coupons__label {
            font-weight: 600;
            color: #333;
            margin-right: 8px;
        }
        .order-coupons__code {
            background: #fff;
            padding: 2px 8px;
            border-radius: 4px;
            color: #0072ed;
            font-weight: 600;
            margin-right: 8px;
        }
        .order-coupons__amount {
            color: #28a745;
            font-weight: 500;
        }
        
        /* Enhanced Order Totals Styles */
        .order-totals__detail--total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
            font-weight: 700;
        }
        .order-totals__amount {
            font-weight: 600;
        }';
    
    // Clean up any existing style tags in the CSS
    $css = preg_replace('/<style[^>]*>.*?<\/style>/s', '', $css);
    $custom_css = preg_replace('/<style[^>]*>.*?<\/style>/s', '', $custom_css);
    
    // Return combined styles without any style tags
    return strip_tags($css . $custom_css);
}
add_filter('woocommerce_email_styles', 'sa_custom_woocommerce_email_styles', 999);

/**
 * Make sure we create the templates directory if it doesn't exist
 */
function sa_ensure_email_templates_directory() {
    $template_dir = __DIR__ . '/templates';
    if (!file_exists($template_dir)) {
        mkdir($template_dir, 0755, true);
    }
}
add_action('init', 'sa_ensure_email_templates_directory');

/**
 * Customize email subject and heading
 * Uses the correct filter for customizing email settings
 */
function sa_customize_email_subject_and_heading($emails) {
    // Get all the email IDs we want to customize
    $modify_emails = [
        'customer_processing_order',
        'customer_completed_order',
        'customer_on_hold_order',
        'customer_invoice'
    ];
    
    // Loop through all available email objects
    foreach ($emails as $email_id => $email) {
        // Check if this is one of our target emails
        if (in_array($email_id, $modify_emails)) {
            // Set Polish subject and heading
            switch ($email_id) {
                case 'customer_processing_order':
                    $emails[$email_id]->settings['subject'] = 'Dziękujemy za zamówienie';
                    $emails[$email_id]->settings['heading'] = 'Dziękujemy za zamówienie';
                    break;
                case 'customer_completed_order':
                    $emails[$email_id]->settings['subject'] = 'Twoje zamówienie zostało zrealizowane';
                    $emails[$email_id]->settings['heading'] = 'Zamówienie zrealizowane';
                    break;
                case 'customer_on_hold_order':
                    $emails[$email_id]->settings['subject'] = 'Twoje zamówienie oczekuje na płatność';
                    $emails[$email_id]->settings['heading'] = 'Dziękujemy za zamówienie';
                    break;
                case 'customer_invoice':
                    $emails[$email_id]->settings['subject'] = 'Faktura do zamówienia';
                    $emails[$email_id]->settings['heading'] = 'Faktura';
                    break;
            }
        }
    }
    
    return $emails;
}
add_filter('woocommerce_email_classes', 'sa_customize_email_subject_and_heading', 99);

/**
 * Remove unwanted style hooks
 */
function sa_remove_unwanted_email_style_hooks() {
    // Remove default WooCommerce email styles
    remove_action('woocommerce_email_header', 'woocommerce_email_header');
    remove_action('woocommerce_email_styles', 'woocommerce_email_styles');
    
    // Remove any other hooks that might add styles
    remove_all_actions('woocommerce_email_styles');
    
    // Add our custom styles back with highest priority
    add_filter('woocommerce_email_styles', 'sa_custom_woocommerce_email_styles', 999);
}
add_action('init', 'sa_remove_unwanted_email_style_hooks', 1); 