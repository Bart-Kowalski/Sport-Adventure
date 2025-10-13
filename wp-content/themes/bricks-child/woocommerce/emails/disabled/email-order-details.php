<?php
/**
 * Order details table shown in emails.
 */

defined('ABSPATH') || exit;

$text_align = is_rtl() ? 'right' : 'left';

do_action('woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email); ?>

<div class="order-details-wrapper">
    <h2>
        <?php
        if ($sent_to_admin) {
            $before = '<a class="link" href="' . esc_url($order->get_edit_order_url()) . '">';
            $after = '</a>';
        } else {
            $before = '';
            $after = '';
        }
        echo wp_kses_post($before . sprintf(__('[Order #%s]', 'woocommerce') . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format('c'), wc_format_datetime($order->get_date_created())));
        ?>
    </h2>

    <div class="order-items">
        <div class="order-items-header">
            <div class="item-product"><?php esc_html_e('Product', 'woocommerce'); ?></div>
            <div class="item-quantity"><?php esc_html_e('Quantity', 'woocommerce'); ?></div>
            <div class="item-price"><?php esc_html_e('Price', 'woocommerce'); ?></div>
        </div>

        <?php
        echo wc_get_email_order_items(
            $order,
            array(
                'show_sku' => $sent_to_admin,
                'show_image' => false,
                'image_size' => array(32, 32),
                'plain_text' => $plain_text,
                'sent_to_admin' => $sent_to_admin,
            )
        );
        ?>

        <div class="order-totals">
            <?php
            $item_totals = $order->get_order_item_totals();
            if ($item_totals) {
                $i = 0;
                foreach ($item_totals as $total) {
                    $i++;
                    ?>
                    <div class="total-row <?php echo ($i === 1) ? 'first' : ''; ?>">
                        <div class="total-label"><?php echo wp_kses_post($total['label']); ?></div>
                        <div class="total-value"><?php echo wp_kses_post($total['value']); ?></div>
                    </div>
                    <?php
                }
            }
            if ($order->get_customer_note()) {
                ?>
                <div class="customer-note">
                    <div class="note-label"><?php esc_html_e('Note:', 'woocommerce'); ?></div>
                    <div class="note-value"><?php echo wp_kses(nl2br(wptexturize($order->get_customer_note())), array()); ?></div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<style>
    .order-details-wrapper {
        margin-bottom: 40px;
    }
    .order-items {
        background: #ffffff;
        border-radius: 4px;
        overflow: hidden;
    }
    .order-items-header {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 16px;
        padding: 12px 16px;
        background: #f8f8f8;
        font-weight: 600;
    }
    .order-items-header > div {
        text-align: <?php echo $text_align; ?>;
    }
    .order-item {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 16px;
        padding: 16px;
        border-bottom: 1px solid #eee;
    }
    .order-item:last-child {
        border-bottom: none;
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
    .total-row.first {
        border-top-width: 3px;
    }
    .customer-note {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #eee;
    }
    .note-label {
        font-weight: 600;
        margin-bottom: 8px;
    }
    @media screen and (max-width: 600px) {
        .order-items-header {
            display: none;
        }
        .order-item {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .total-row {
            flex-direction: column;
            gap: 4px;
        }
        .total-value {
            font-weight: 600;
        }
    }
</style>

<?php do_action('woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email); ?> 