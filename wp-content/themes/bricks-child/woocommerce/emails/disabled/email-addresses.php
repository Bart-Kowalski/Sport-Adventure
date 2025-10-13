<?php
/**
 * Email Addresses
 */

if (!defined('ABSPATH')) {
    exit;
}

$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();
?>

<div class="addresses">
    <?php if ($address) : ?>
        <div class="address-section billing-address">
            <h2 class="address-title"><?php esc_html_e('Billing address', 'woocommerce'); ?></h2>
            <div class="address">
                <?php echo wp_kses_post($address); ?>
                <?php if ($order->get_billing_phone()) : ?>
                    <br/><?php echo wc_make_phone_clickable($order->get_billing_phone()); ?>
                <?php endif; ?>
                <?php if ($order->get_billing_email()) : ?>
                    <br/><?php echo esc_html($order->get_billing_email()); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($shipping) : ?>
        <div class="address-section shipping-address">
            <h2 class="address-title"><?php esc_html_e('Shipping address', 'woocommerce'); ?></h2>
            <div class="address">
                <?php echo wp_kses_post($shipping); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .addresses {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        padding: 0;
        margin: 0 0 40px;
    }
    
    .address-section {
        flex: 1;
        min-width: 250px;
    }
    
    .address-title {
        margin: 0 0 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
    }
    
    .address {
        padding: 12px;
        background: #f8f8f8;
        border-radius: 4px;
    }
    
    @media screen and (max-width: 600px) {
        .addresses {
            flex-direction: column;
            gap: 16px;
        }
        
        .address-section {
            width: 100%;
        }
    }
</style> 