<?php
/**
 * Customer refunded order email
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_email_header', $email_heading, $email);
?>

<div class="email-content">
    <p><?php printf(esc_html__('Hi %s,', 'woocommerce'), esc_html($order->get_billing_first_name())); ?></p>
    <?php if ($partial_refund) : ?>
        <p><?php esc_html_e('Your order has been partially refunded.', 'woocommerce'); ?></p>
    <?php else : ?>
        <p><?php esc_html_e('Your order has been fully refunded.', 'woocommerce'); ?></p>
    <?php endif; ?>
</div>

<?php
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);
?>

<div class="email-footer">
    <p><?php esc_html_e('We hope to see you again soon.', 'woocommerce'); ?></p>
</div>

<?php
do_action('woocommerce_email_footer', $email); 