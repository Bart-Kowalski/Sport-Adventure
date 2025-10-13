<?php
/**
 * Email Order Items
 */

defined('ABSPATH') || exit;

$text_align = is_rtl() ? 'right' : 'left';

foreach ($items as $item_id => $item) :
    $product = $item->get_product();
    $sku = '';
    $purchase_note = '';
    $image = '';

    if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
        continue;
    }

    if (is_object($product)) {
        $sku = $product->get_sku();
        $purchase_note = $product->get_purchase_note();
        $image = $product->get_image($image_size);
    }
    ?>
    <div class="order-item">
        <div class="item-product">
            <?php
            // Show title/image etc.
            if ($show_image) {
                echo wp_kses_post(apply_filters('woocommerce_order_item_thumbnail', $image, $item));
            }

            // Product name
            echo wp_kses_post(apply_filters('woocommerce_order_item_name', $item->get_name(), $item, false));

            // SKU
            if ($show_sku && $sku) {
                echo wp_kses_post(' (#' . $sku . ')');
            }

            // Allow other plugins to add additional product information here
            do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text);

            wc_display_item_meta(
                $item,
                array(
                    'label_before' => '<div class="item-meta-label">',
                    'label_after' => ':</div><div class="item-meta-value">',
                    'wrapper_class' => 'item-meta',
                )
            );

            // Allow other plugins to add additional product information here
            do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text);
            ?>
        </div>
        <div class="item-quantity">
            <?php
            $qty = $item->get_quantity();
            $refunded_qty = $order->get_qty_refunded_for_item($item_id);

            if ($refunded_qty) {
                $qty_display = '<del>' . esc_html($qty) . '</del> <ins>' . esc_html($qty - ($refunded_qty * -1)) . '</ins>';
            } else {
                $qty_display = esc_html($qty);
            }
            echo wp_kses_post(apply_filters('woocommerce_email_order_item_quantity', $qty_display, $item));
            ?>
        </div>
        <div class="item-price">
            <?php echo wp_kses_post($order->get_formatted_line_subtotal($item)); ?>
        </div>
    </div>

    <?php if ($show_purchase_note && $purchase_note) : ?>
        <div class="product-purchase-note">
            <?php echo wpautop(do_shortcode(wp_kses_post($purchase_note))); ?>
        </div>
    <?php endif; ?>

<?php endforeach; ?>

<style>
    .item-meta {
        margin-top: 8px;
        font-size: 0.9em;
        color: #666;
    }
    .item-meta-label {
        display: inline;
        font-weight: 600;
    }
    .item-meta-value {
        display: inline;
        margin-left: 4px;
    }
    .product-purchase-note {
        margin-top: 8px;
        padding: 8px;
        background: #f8f8f8;
        border-radius: 4px;
        font-size: 0.9em;
        color: #666;
    }
</style> 