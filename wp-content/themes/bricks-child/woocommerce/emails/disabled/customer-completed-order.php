<?php
/**
 * Customer completed order email
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_email_header', $email_heading, $email);
?>

<div class="email-content">
    <p><?php printf(esc_html__('Hi %s,', 'woocommerce'), esc_html($order->get_billing_first_name())); ?></p>
    <p><?php esc_html_e('We have finished processing your order.', 'woocommerce'); ?></p>
</div>

<?php
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);
?>

<div class="email-footer">
    <p><?php esc_html_e('Thanks for shopping with us.', 'woocommerce'); ?></p>
</div>

<?php
do_action('woocommerce_email_footer', $email); 