<?php
/**
 * Email Styles
 */

defined('ABSPATH') || exit;

$bg = get_option('woocommerce_email_background_color');
$body = get_option('woocommerce_email_body_background_color');
$text = get_option('woocommerce_email_text_color');
$base = get_option('woocommerce_email_base_color');

// Load colors
$bg = $bg ? $bg : '#f7f7f7';
$body = $body ? $body : '#ffffff';
$text = $text ? $text : '#3c3c3c';
$base = $base ? $base : '#557da1';

?>
body {
    padding: 0;
    margin: 0;
    -webkit-text-size-adjust: none !important;
    width: 100%;
    background-color: <?php echo esc_attr($bg); ?>;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    color: <?php echo esc_attr($text); ?>;
}

#wrapper {
    max-width: 600px;
    margin: 0 auto;
    padding: 70px 0;
    background-color: <?php echo esc_attr($bg); ?>;
}

#template_container {
    background-color: <?php echo esc_attr($body); ?>;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

#template_header {
    background-color: <?php echo esc_attr($base); ?>;
    border-radius: 6px 6px 0 0;
    color: #ffffff;
    padding: 36px 48px;
}

#template_header h1 {
    color: #ffffff;
    margin: 0;
    font-size: 30px;
    font-weight: 300;
    text-shadow: 0 1px 0 <?php echo esc_attr($base); ?>;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}

#template_body {
    background-color: <?php echo esc_attr($body); ?>;
    border-radius: 0 0 6px 6px;
}

#body_content {
    padding: 48px;
}

#body_content_inner {
    color: <?php echo esc_attr($text); ?>;
    font-size: 14px;
    line-height: 150%;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

#template_footer {
    padding: 0 48px 48px;
    text-align: center;
}

h1, h2, h3, h4 {
    color: <?php echo esc_attr($base); ?>;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    margin: 0 0 18px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h1 { font-size: 30px; font-weight: 300; line-height: 150%; }
h2 { font-size: 18px; font-weight: bold; line-height: 130%; }
h3 { font-size: 16px; font-weight: bold; line-height: 130%; }
h4 { font-size: 14px; font-weight: bold; line-height: 130%; }

p {
    margin: 0 0 16px;
}

a {
    color: <?php echo esc_attr($base); ?>;
    text-decoration: underline;
}

img {
    border: none;
    display: inline-block;
    height: auto;
    max-width: 100%;
    outline: none;
}

.address {
    padding: 12px;
    color: <?php echo esc_attr($text); ?>;
    border: 1px solid #e5e3e1;
}

/* Order details styles */
.order-details {
    margin-bottom: 40px;
}

.order-items {
    margin-bottom: 40px;
}

.order-item {
    padding: 16px;
    border-bottom: 1px solid #eee;
    display: flex;
    flex-direction: column;
}

.order-item:last-child {
    border-bottom: none;
}

.item-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.item-meta {
    margin-top: 8px;
    font-size: 0.9em;
    color: #666;
}

.order-totals {
    margin-top: 24px;
    border-top: 2px solid #eee;
    padding-top: 16px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
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
    
    .order-item {
        padding: 12px;
    }
    
    .total-row {
        flex-direction: column;
        gap: 4px;
    }
    
    .item-details {
        gap: 4px;
    }
} 