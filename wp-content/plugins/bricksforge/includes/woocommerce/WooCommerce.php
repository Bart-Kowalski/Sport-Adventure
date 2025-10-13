<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

class WooCommerce
{
    private $cart;

    public function __construct()
    {
        $emaildesigner_activated = get_option('brf_activated_tools') && in_array(13, get_option('brf_activated_tools'));
        $pro_forms_activated = get_option('brf_activated_elements') && is_array(get_option('brf_activated_elements')) && array_values(array_filter(get_option('brf_activated_elements'), function ($tool) {
            return $tool->id == 5;
        }));

        if (!$emaildesigner_activated && !$pro_forms_activated) {
            return;
        }

        add_action('woocommerce_before_calculate_totals', [$this, 'adjust_price'], 10, 1);

        // Show Custom Fields in Checkout
        add_filter('woocommerce_cart_item_name', [$this, 'display_custom_fields_on_checkout'], 10, 3);

        // Save Custom Fields
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'save_custom_fields_as_order_item_meta'], 10, 4);

        // Show Custom Fields in Cart
        add_filter('woocommerce_after_cart_item_name', [$this, 'display_cart_item_options'], 10, 2);

        // Display updated cart item price
        add_filter('woocommerce_cart_item_price', [$this, 'update_cart_item_price_display'], 10, 3);

        // Display updated cart subtotal in mini-cart
        add_filter('woocommerce_cart_subtotal', [$this, 'update_mini_cart_subtotal_display'], 10, 1);
    }

    public function adjust_price($cart_object)
    {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        $stored_unique_keys = \WC()->session->get(BRICKSFORGE_WC_CART_ITEM_KEY, array());

        if (empty($stored_unique_keys)) {
            return;
        }

        foreach ($cart_object->get_cart() as $cart_item_key => $cart_item) {
            if (
                isset($cart_item['brf_custom_price'], $cart_item['brf_product_id'], $cart_item[BRICKSFORGE_WC_CART_ITEM_KEY]) &&
                is_numeric($cart_item['brf_custom_price']) &&
                is_int($cart_item['brf_product_id']) &&
                $cart_item['brf_product_id'] > 0 &&
                isset($stored_unique_keys[$cart_item_key]) &&
                $stored_unique_keys[$cart_item_key] === $cart_item[BRICKSFORGE_WC_CART_ITEM_KEY]
            ) {
                $custom_price = (float) $cart_item['brf_custom_price'];
                $cart_item['data']->set_price($custom_price);
            }
        }
    }

    public function display_cart_item_options($cart_item, $cart_item_key)
    {
        if (empty($cart_item['brf_custom_fields'])) {
            return;
        }

        $custom_fields = $cart_item['brf_custom_fields'];

        echo '<div class="brf-product-meta">';
        $values = array();
        foreach ($custom_fields as $custom_field) {
            $name = $custom_field['label'];
            $value = $custom_field['value'];
            $values[] = $name . ': ' . $value;
        }
        echo '<small>' . implode(', ', $values) . '</small>';
        echo '</div>';
    }

    public function update_cart_item_price_display($price_html, $cart_item, $cart_item_key)
    {
        if (isset($cart_item['brf_custom_price']) && $cart_item['brf_custom_price'] !== '') {
            $custom_price = (float) $cart_item['brf_custom_price'];
            $price_html = wc_price($custom_price);
        }
        return $price_html;
    }

    public function update_mini_cart_subtotal_display($subtotal)
    {
        $cart = WC()->cart;

        // Get WooCommerce tax display setting
        $tax_display = get_option('woocommerce_tax_display_shop');

        if ($tax_display === 'incl') {
            // Display the subtotal including tax
            $subtotal = $cart->get_subtotal() + $cart->get_subtotal_tax();
        } elseif ($tax_display === 'excl') {
            // Display the subtotal excluding tax
            $subtotal = $cart->get_subtotal();
        }

        return wc_price($subtotal);
    }

    public function save_custom_fields_as_order_item_meta($item, $cart_item_key, $values, $order)
    {
        if (!empty($values['brf_custom_fields'])) {
            $custom_fields = $values['brf_custom_fields'];

            foreach ($custom_fields as $custom_field_key => $custom_field) {
                $custom_field_label = $custom_field['label'];
                $custom_field_value = $custom_field['value'];

                // Save the custom field as order item meta
                $item->add_meta_data($custom_field_label, $custom_field_value);
            }
        }
    }

    public function display_custom_fields_on_checkout($product_name, $cart_item, $cart_item_key)
    {
        // If not on the checkout page, exit
        if (!is_checkout()) {
            return $product_name;
        }

        $stored_custom_fields = WC()->session->get('brf_custom_fields', array());

        if (empty($stored_custom_fields)) {
            return $product_name;
        }

        if (isset($stored_custom_fields[$cart_item_key])) {
            $custom_fields = $stored_custom_fields[$cart_item_key];
            $first_field = true;

            foreach ($custom_fields as $custom_field_key => $custom_field) {
                $label = $custom_field['label'];
                $value = $custom_field['value'];

                if ($first_field) {
                    $product_name .= '<br>'; // Add line break before the first field
                    $first_field = false;
                } else {
                    $product_name .= ', '; // Add comma separator for multiple fields
                }

                $product_name .= sprintf(
                    '<small><strong>%s: </strong>%s</small>',
                    esc_html($label),
                    esc_html($value)
                );
            }
        }

        return $product_name;
    }
}
