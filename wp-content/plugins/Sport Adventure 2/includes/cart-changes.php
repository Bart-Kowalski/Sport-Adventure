<?php

// Initialize cart changes early
add_action('plugins_loaded', 'cart_initialize_changes', 5);
function cart_initialize_changes() {
    // Add custom price display to cart items
    add_filter('woocommerce_cart_item_price', 'cart_custom_item_price', 1000, 3);
    add_filter('woocommerce_cart_item_subtotal', 'cart_custom_item_subtotal', 1000, 3);
    
    // DO NOT remove WooCommerce's default price display anymore
    // remove_filter('woocommerce_cart_item_price', 'wc_price', 10);
    // remove_filter('woocommerce_cart_item_subtotal', 'wc_price', 10);
    
    // Only modify variation display, don't completely remove it
    add_filter('woocommerce_get_item_data', 'modify_cart_item_data', 999);
    add_filter('woocommerce_display_item_meta', 'modify_cart_item_meta', 999);
    
    // Add custom attribute labels
    add_filter('woocommerce_attribute_label', 'custom_attribute_labels', 10, 3);
    
    // Add our styles
    add_action('wp_head', 'cart_custom_styles', 1000);
}

// Modify cart item data instead of removing it
function modify_cart_item_data($item_data, $cart_item = null) {
    // Only modify if we're not on the cart page
    if (!is_cart()) {
        return $item_data;
    }
    return array();
}

// Modify cart item meta instead of removing it
function modify_cart_item_meta($html, $item = null, $args = array()) {
    // Only modify if we're not on the cart page
    if (!is_cart()) {
        return $html;
    }
    return '';
}

// Add visual debugging only when WP_DEBUG is true
add_action('woocommerce_before_cart', 'cart_debug_display');
function cart_debug_display() {
    if (!current_user_can('administrator') || !defined('WP_DEBUG') || !WP_DEBUG) return;
    ?>
    <div class="cart-debug-info">
        <p><strong>Debug Info:</strong></p>
        <ul>
            <li>Cart Changes Plugin Active</li>
            <li>Filters Registered: <?php echo has_filter('woocommerce_cart_item_price', 'cart_custom_item_price') ? 'Yes' : 'No'; ?></li>
            <li>Default Price Filter Removed: <?php echo !has_filter('woocommerce_cart_item_price', 'wc_price') ? 'Yes' : 'No'; ?></li>
        </ul>
    </div>
    <?php
}

function cart_custom_item_price($price_html, $cart_item, $cart_item_key) {
    if (empty($cart_item['variation_id'])) return $price_html;
    
    $calculator = sa_cart_calculator();
    $deposit = $calculator->get_item_deposit($cart_item);
    
    ob_start();
    ?>
    <div class="cart-item-price-info">
        <div class="deposit-info">
            <span class="deposit-amount"><?php echo number_format($deposit, 0, ',', '') . ' PLN'; ?></span>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function cart_custom_item_subtotal($subtotal, $cart_item, $cart_item_key) {
    if (empty($cart_item['variation_id'])) return $subtotal;
    
    $calculator = sa_cart_calculator();
    $deposit = $calculator->get_item_deposit($cart_item);
    $remaining_payments = $calculator->get_item_remaining_payments($cart_item);
    
    ob_start();
    ?>
    <div class="cart-item-price-info">
        <div class="cart-subtotal-details">
            <div class="deposit-info">
                <span class="deposit-label">Zaliczka do zapłaty</span>
                <span class="deposit-amount"><?php echo number_format($deposit, 0, ',', '') . ' PLN'; ?></span>
            </div>
            <?php if (!empty($remaining_payments)): ?>
                <div class="cart-remaining-payment">
                    <?php foreach ($remaining_payments as $payment): ?>
                        <div>
                            <span class="payment-text"><?php echo $payment['due'] . ' płatne'; ?></span>
                            <span class="payment-amount"><?php echo number_format($payment['amount'], 0, ',', '') . ' ' . $payment['currency']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Modify cart totals
add_filter('woocommerce_cart_totals_order_total_html', 'cart_modify_total_html', 100);
function cart_modify_total_html($total) {
    if (!is_cart()) return $total;
    
    static $is_calculating = false;
    if ($is_calculating) return $total;
    
    $is_calculating = true;
    
    $calculator = sa_cart_calculator();
    $total_deposit = $calculator->get_cart_total_deposit();
    $remaining_payments = $calculator->get_cart_remaining_payments();
    
    ob_start();
    ?>
    <div class="cart-total-details">
        <div class="deposit-info">
            <span class="deposit-label">Zaliczka do zapłaty</span>
            <span class="deposit-amount"><?php echo number_format($total_deposit, 0, ',', '') . ' PLN'; ?></span>
        </div>
        <?php if (!empty($remaining_payments)): ?>
            <div class="cart-remaining-payment">
                <?php foreach ($remaining_payments as $payment): ?>
                    <div>
                        <span class="payment-text"><?php echo $payment['due'] . ' płatne'; ?></span>
                        <span class="payment-amount"><?php echo number_format($payment['amount'], 0, ',', '') . ' ' . $payment['currency']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    
    $is_calculating = false;
    return ob_get_clean();
}

// Add cart styles
add_action('wp_head', 'cart_custom_styles', 1000);
function cart_custom_styles() {
    if (!is_cart()) return;
    ?>
    <style>
        /* Cart table styles */
        .woocommerce-cart-form__contents {
            border: none !important;
            margin: 0 0 24px !important;
            border-radius: 8px !important;
            background: none !important;
            padding: 0 !important;
        }

        /* Hide table headers */
        .woocommerce-cart-form__contents thead {
            display: none !important;
        }

        /* Product info styling */
        .woocommerce-cart-form__contents td {
            padding: var(--space-m) 8px !important;
            vertical-align: top !important;
            border-top: 1px solid var(--neutral-trans-40) !important;
        }

        .woocommerce-cart-form__contents tr:first-child td {
            border-top: none !important;
        }

        /* Product thumbnail */
        .woocommerce-cart-form__contents .product-thumbnail {
            width: 80px !important;
            padding-right: 0 !important;
        }

        .woocommerce-cart-form__contents .product-thumbnail img {
            width: 80px !important;
            height: 80px !important;
            border-radius: var(--radius-xs) !important;
            border: 1px solid var(--neutral-trans-40) !important;
            object-fit: cover !important;
        }

        /* Product name styling */
        .woocommerce-cart-form__contents .product-name {
            padding-left: 16px !important;
            position: relative !important;
            padding-right: 80px !important;
        }

        .woocommerce-cart-form__contents .product-name::before {
            content: 'Produkt:' !important;
            display: block !important;
            color: #666 !important;
            font-size: 14px !important;
            margin-bottom: 4px !important;
            font-weight: normal !important;
        }

        .woocommerce-cart-form__contents .product-name a {
            font-weight: 600 !important;
            color: #333 !important;
            text-decoration: none !important;
            font-size: 16px !important;
            display: block !important;
            margin-bottom: 16px !important;
        }

        .woocommerce-cart-form__contents .variation {
            margin-top: 0 !important;
            font-size: 14px !important;
        }

        .woocommerce-cart-form__contents .variation dt {
            float: left !important;
            clear: both !important;
            color: #666 !important;
            padding-right: 8px !important;
            margin-bottom: 8px !important;
        }

        /* Custom labels for variation */
        .woocommerce-cart-form__contents .variation dt.variation-pa_termin::before {
            content: 'Termin: ' !important;
        }

        .woocommerce-cart-form__contents .variation dt.variation-pa_zakwaterowanie::before {
            content: 'Wersja zakwaterowania: ' !important;
        }

        .woocommerce-cart-form__contents .variation dd {
            color: #333 !important;
            margin-bottom: 8px !important;
        }

        .woocommerce-cart-form__contents .variation dd p {
            margin: 0 !important;
            display: inline !important;
        }

        /* Remove button */
        .woocommerce-cart-form__contents .product-remove {
            position: absolute !important;
            top: var(--space-m) !important;
            right: 8px !important;
            width: auto !important;
            padding: 0 !important;
        }

        .woocommerce-cart-form__contents .product-remove a {
            color: #e2401c !important;
            text-decoration: none !important;
            border: 1px solid #e2401c !important;
            padding: 4px 12px !important;
            border-radius: 4px !important;
            font-size: 13px !important;
            line-height: 1.4 !important;
            transition: all 0.2s ease !important;
            width: auto !important;
            height: auto !important;
            display: inline-block !important;
            background: #fff !important;
        }

        .woocommerce-cart-form__contents .product-remove a:hover {
            background: #e2401c !important;
            color: #fff !important;
        }

        /* Quantity input styling */
        .woocommerce-cart-form__contents .product-quantity::before {
            content: 'Ilość:' !important;
            display: block !important;
            color: #666 !important;
            font-size: 14px !important;
            margin-bottom: 4px !important;
            font-weight: normal !important;
        }

        .woocommerce-cart-form__contents .quantity {
            display: flex !important;
            align-items: center !important;
            background: #f0f0f0 !important;
            border-radius: 4px !important;
            overflow: hidden !important;
            width: 120px !important;
            margin: 0 !important;
        }

        .woocommerce-cart-form__contents .quantity input {
            background: #f0f0f0 !important;
            border: none !important;
            padding: 8px 0 !important;
            width: 40px !important;
            text-align: center !important;
            font-size: 15px !important;
            -moz-appearance: textfield !important;
            margin: 0 !important;
            height: 36px !important;
            min-height: 36px !important;
            color: #333 !important;
        }

        .woocommerce-cart-form__contents .quantity input::-webkit-outer-spin-button,
        .woocommerce-cart-form__contents .quantity input::-webkit-inner-spin-button {
            -webkit-appearance: none !important;
            margin: 0 !important;
        }

        .woocommerce-cart-form__contents .quantity .action {
            width: 40px !important;
            height: 36px !important;
            background: #f0f0f0 !important;
            border: none !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            color: #333 !important;
            font-size: 18px !important;
            font-weight: 400 !important;
            transition: background-color 0.2s !important;
        }

        .woocommerce-cart-form__contents .quantity .action:hover {
            background: #e0e0e0 !important;
        }

        /* Price info styling */
        .woocommerce-cart-form__contents .product-subtotal::before {
            content: 'Płatności:' !important;
            display: block !important;
            color: #666 !important;
            font-size: 14px !important;
            margin-bottom: 4px !important;
            font-weight: normal !important;
        }

        .cart-item-price-info {
            text-align: left !important;
        }

        .cart-subtotal-details {
            background: none !important;
            border: none !important;
            padding: 0 !important;
        }

        .deposit-info {
            margin-bottom: 12px !important;
        }

        .deposit-label {
            display: block !important;
            font-weight: 600 !important;
            color: #333 !important;
            font-size: 14px !important;
            margin-bottom: 4px !important;
        }

        .deposit-amount {
            font-size: 18px !important;
            font-weight: 600 !important;
            color: #333 !important;
            display: block !important;
        }

        .cart-remaining-payment {
            font-size: 14px !important;
            color: #666 !important;
            line-height: 1.5 !important;
        }

        .cart-remaining-payment > div {
            margin-bottom: 4px !important;
        }

        .cart-remaining-payment > div:last-child {
            margin-bottom: 0 !important;
        }

        /* Hide original price display */
        .product-price {
            display: none !important;
        }

        /* Currency symbol spacing */
        .woocommerce-Price-currencySymbol {
            margin-left: 4px !important;
        }

        /* Cart totals styling */
        .cart_totals {
            background: #f8f8f8 !important;
            padding: 20px !important;
            border-radius: 8px !important;
            margin-top: 32px !important;
        }

        .cart_totals h2 {
            font-size: 18px !important;
            margin-bottom: 16px !important;
            font-weight: 600 !important;
            color: #333 !important;
        }

        .cart_totals table {
            margin: 0 !important;
        }

        .cart_totals th,
        .cart_totals td {
            padding: 8px 0 !important;
            border: none !important;
        }

        .cart_totals .order-total {
            border-top: 1px solid rgba(0,0,0,0.1) !important;
            padding-top: 16px !important;
            margin-top: 8px !important;
        }

        .cart_totals .order-total th {
            font-size: 16px !important;
            font-weight: 600 !important;
            color: #333 !important;
        }

        .cart_totals .order-total td {
            text-align: right !important;
        }

        .cart_totals .order-total .amount {
            font-size: 20px !important;
            font-weight: 600 !important;
            color: #333 !important;
        }

        /* Hide cart subtotal */
        .cart_totals .cart-subtotal {
            display: none !important;
        }

        /* Proceed to checkout button */
        .wc-proceed-to-checkout {
            margin-top: 16px !important;
        }

        .checkout-button {
            width: 100% !important;
            text-align: center !important;
            padding: 12px 24px !important;
            font-size: 16px !important;
            font-weight: 600 !important;
            position: relative !important;
            transition: all 0.2s ease !important;
        }

        .checkout-button.loading {
            color: transparent !important;
            pointer-events: none !important;
        }

        .checkout-button.loading::after {
            content: '' !important;
            position: absolute !important;
            left: 50% !important;
            top: 50% !important;
            width: 20px !important;
            height: 20px !important;
            margin: -10px 0 0 -10px !important;
            border: 2px solid rgba(255,255,255,0.3) !important;
            border-top-color: #fff !important;
            border-radius: 50% !important;
            animation: checkout-spinner 0.6s linear infinite !important;
        }

        @keyframes checkout-spinner {
            to {
                transform: rotate(360deg) !important;
            }
        }

        @media (max-width: 991px) {
            .woocommerce-cart-form__contents td {
                padding: var(--space-xs) 8px !important;
            }

            .woocommerce-cart-form__contents .product-remove {
                top: var(--space-xs) !important;
            }

            .cart_totals {
                margin-top: 24px !important;
            }
        }

        /* Place Order Button Loading State */
        #place_order {
            position: relative !important;
            transition: all 0.2s ease !important;
        }

        #place_order.loading {
            color: transparent !important;
            pointer-events: none !important;
        }

        #place_order.loading::after {
            content: '' !important;
            position: absolute !important;
            left: 50% !important;
            top: 50% !important;
            width: 20px !important;
            height: 20px !important;
            margin: -10px 0 0 -10px !important;
            border: 2px solid rgba(255,255,255,0.3) !important;
            border-top-color: #fff !important;
            border-radius: 50% !important;
            animation: submit-spinner 0.6s linear infinite !important;
        }

        @keyframes submit-spinner {
            to {
                transform: rotate(360deg) !important;
            }
        }

        /* Product name wrapper */
        .product-name-wrapper {
            margin-bottom: 8px !important;
        }
        
        .product-name-wrapper h3 {
            font-size: 18px !important;
            margin: 0 0 8px !important;
            color: #333 !important;
        }
        
        /* Variation attributes */
        .variation-attributes {
            font-size: 14px !important;
            color: #666 !important;
        }
        
        .variation-attribute {
            margin-bottom: 4px !important;
        }
        
        .variation-attribute:last-child {
            margin-bottom: 0 !important;
        }
        
        .variation-label {
            font-weight: normal !important;
        }
        
        .variation-value {
            margin-left: 4px !important;
        }

        /* New styles for variation lines */
        .variation-line {
            margin-bottom: 4px !important;
            font-size: 14px !important;
            color: #666 !important;
            line-height: 1.5 !important;
        }
        
        .variation-line:last-child {
            margin-bottom: 0 !important;
        }
        
        /* Remove old variation styles */
        .variation-attributes,
        .variation-attribute,
        .variation-label,
        .variation-value {
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Product name wrapper adjustments */
        .product-name-wrapper h3,
        .checkout-item-name h3 {
            font-size: 18px !important;
            margin: 0 0 12px !important;
            color: #333 !important;
            font-weight: 600 !important;
        }
        
        /* Remove button positioning */
        .remove-product {
            display: inline-block !important;
            margin-top: 12px !important;
            color: #e2401c !important;
            text-decoration: none !important;
            font-size: 13px !important;
        }

        /* Hide default variation display */
        .woocommerce-cart-form__contents .variation,
        .woocommerce-cart-form__contents dl.variation {
            display: none !important;
        }
        
        /* Ensure our custom variation display looks good */
        .product-name-wrapper {
            margin-bottom: 0 !important;
        }
        
        .variation-line {
            color: #666 !important;
            font-size: 14px !important;
            margin-bottom: 4px !important;
        }
        
        .variation-line:last-child {
            margin-bottom: 0 !important;
        }
    </style>
    <script>
    jQuery(document).ready(function($) {
        // Handle checkout button loading state
        $('.checkout-button').on('click', function(e) {
            $(this).addClass('loading');
        });

        // Handle order submit button loading state
        var form = $('form.checkout');
        if (form.length) {
            form.on('checkout_place_order', function() {
                $('#place_order').addClass('loading');
                return true;
            });

            // Reset loading state if validation fails
            $(document.body).on('checkout_error', function() {
                $('#place_order').removeClass('loading');
            });
        }
    });
    </script>
    <?php
}

// Modify cart subtotal
add_filter('woocommerce_cart_subtotal', 'cart_modify_subtotal', 100, 3);
function cart_modify_subtotal($cart_subtotal, $compound, $cart) {
    if (!is_cart()) return $cart_subtotal;
    
    $calculator = sa_cart_calculator();
    $total_deposit = $calculator->get_cart_total_deposit();
    
    return number_format($total_deposit, 0, ',', '') . ' PLN';
}

// Remove decimals from prices
add_filter('wc_price_args', function($args) {
    $args['decimals'] = 0;
    return $args;
}, 100);

// Modify cart totals text
add_filter('gettext', 'cart_modify_totals_text', 20, 3);
function cart_modify_totals_text($translated_text, $text, $domain) {
    if ($domain === 'woocommerce' && is_cart()) {
        switch ($text) {
            case 'Subtotal':
                return 'Kwota';
            case 'Total':
                return 'Łącznie';
            case 'Cart totals':
                return 'Podsumowanie koszyka';
        }
    }
    return $translated_text;
}

// Remove unnecessary cart hooks - MODIFIED to keep essential functionality
function cart_remove_extra_hooks() {
    // Only remove non-essential hooks
    remove_action('woocommerce_cart_totals_before_shipping', 'woocommerce_cart_totals_shipping_html');
    remove_action('woocommerce_cart_totals_before_order_total', 'woocommerce_cart_totals_order_total_html');
}
add_action('init', 'cart_remove_extra_hooks');

// Validate cart items before they are added
function validate_cart_item($passed, $product_id, $quantity, $variation_id = '', $variations = array()) {
    // Only allow variation products (since this is an adventure booking site)
    if (empty($variation_id)) {
        error_log('Attempted to add non-variation product to cart: ' . $product_id);
        wc_add_notice('Nieprawidłowy produkt.', 'error');
        return false;
    }

    // Ensure the product is a valid adventure product
    $product = wc_get_product($product_id);
    if (!$product || $product->get_type() !== 'variable') {
        error_log('Invalid product type attempted to be added to cart: ' . $product_id);
        wc_add_notice('Nieprawidłowy typ produktu.', 'error');
        return false;
    }

    return $passed;
}
add_filter('woocommerce_add_to_cart_validation', 'validate_cart_item', 10, 5);

// Clean cart items periodically to remove any invalid items
function clean_cart_items($cart_contents) {
    if (empty($cart_contents)) {
        return $cart_contents;
    }

    foreach ($cart_contents as $cart_item_key => $cart_item) {
        // Check if this is a valid product
        if (empty($cart_item['variation_id']) || empty($cart_item['product_id'])) {
            error_log('Removing invalid cart item: ' . print_r($cart_item, true));
            unset($cart_contents[$cart_item_key]);
            continue;
        }

        // Verify the product still exists and is purchasable
        $product = wc_get_product($cart_item['product_id']);
        if (!$product || !$product->is_purchasable()) {
            error_log('Removing non-purchasable product from cart: ' . $cart_item['product_id']);
            unset($cart_contents[$cart_item_key]);
            continue;
        }

        // Verify the variation exists
        $variation = wc_get_product($cart_item['variation_id']);
        if (!$variation) {
            error_log('Removing invalid variation from cart: ' . $cart_item['variation_id']);
            unset($cart_contents[$cart_item_key]);
        }
    }

    return $cart_contents;
}
add_filter('woocommerce_get_cart_contents', 'clean_cart_items', 20);

// Add cart item name filter
add_filter('woocommerce_cart_item_name', 'cart_custom_item_name', 10, 3);
function cart_custom_item_name($name, $cart_item, $cart_item_key) {
    if (!is_cart() || empty($cart_item['variation_id'])) {
        return $name;
    }

    $product = $cart_item['data'];
    if (!$product || !is_object($product)) {
        return $name;
    }

    $html = '<div class="product-name-wrapper">';
    $html .= '<h3>' . get_the_title($product->get_parent_id()) . '</h3>';
    
    // Get all product attributes
    $attributes = $product->get_attributes();
    if (!empty($attributes)) {
        foreach ($attributes as $attribute_name => $attribute_value) {
            // Get the attribute taxonomy name
            $taxonomy = str_replace('attribute_', '', $attribute_name);
            
            // Get the attribute label (name)
            $attribute_label = wc_attribute_label($taxonomy, $product);
            
            // Get the term name for the value
            if (taxonomy_exists($taxonomy)) {
                $term = get_term_by('slug', $attribute_value, $taxonomy);
                if ($term && !is_wp_error($term)) {
                    $value = $term->name;
                } else {
                    $value = $attribute_value;
                }
            } else {
                $value = $attribute_value;
            }
            
            if (!empty($value)) {
                $html .= '<div class="variation-line">';
                $html .= esc_html($attribute_label) . ': ' . esc_html($value);
                $html .= '</div>';
            }
        }
    }
    
    $html .= '</div>';
    
    // Remove the default variation display
    remove_filter('woocommerce_get_item_data', 'wc_display_item_meta', 10);
    
    return $html;
}

// Custom attribute labels
function custom_attribute_labels($label, $name, $product = '') {
    // Convert attribute name to taxonomy if needed
    $taxonomy = 0 === strpos($name, 'pa_') ? $name : 'pa_' . $name;
    
    // Define custom labels
    $custom_labels = array(
        'pa_termin' => 'Termin',
        'pa_wersja-zakwaterowania' => 'Zakwaterowanie',
        'pa_wersja-wyzywienia' => 'Wyżywienie',
        'pa_dla-kogo' => 'Dla kogo',
        // Add more custom labels as needed
    );
    
    // Return custom label if exists, otherwise return original
    return isset($custom_labels[$taxonomy]) ? $custom_labels[$taxonomy] : $label;
}

// Add AJAX endpoints
add_action('wc_ajax_get_cart_data', 'get_cart_data_ajax');
add_action('wc_ajax_get_cart_item_data', 'get_cart_item_data_ajax');
add_action('wp_ajax_get_cart_count', 'get_cart_count_ajax');
add_action('wp_ajax_nopriv_get_cart_count', 'get_cart_count_ajax');

function get_cart_data_ajax() {
    // Get cart data
    $cart = WC()->cart;
    $items = array();

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        $items[] = array(
            'product_id' => $cart_item['product_id'],
            'variation_id' => $cart_item['variation_id'],
            'product_name' => $product->get_name(),
            'price' => $product->get_price(),
            'quantity' => $cart_item['quantity'],
            'line_total' => $cart_item['line_total'],
            'category' => strip_tags(wc_get_product_category_list($cart_item['product_id']))
        );
    }

    wp_send_json_success(array(
        'items' => $items,
        'currency' => get_woocommerce_currency(),
        'total' => $cart->get_cart_contents_total()
    ));
}

function get_cart_item_data_ajax() {
    $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field($_POST['cart_item_key']) : '';
    
    if (empty($cart_item_key)) {
        wp_send_json_error('No cart item key provided');
        return;
    }

    $cart = WC()->cart;
    $cart_item = $cart->get_cart_item($cart_item_key);
    
    if (!$cart_item) {
        wp_send_json_error('Cart item not found');
        return;
    }

    $product = $cart_item['data'];
    
    wp_send_json_success(array(
        'data' => array(
            'product_id' => $cart_item['product_id'],
            'variation_id' => $cart_item['variation_id'],
            'product_name' => $product->get_name(),
            'price' => $product->get_price(),
            'quantity' => $cart_item['quantity'],
            'line_total' => $cart_item['line_total'],
            'currency' => get_woocommerce_currency(),
            'category' => strip_tags(wc_get_product_category_list($cart_item['product_id']))
        )
    ));
}

// Get cart count for badge display
function get_cart_count_ajax() {
    if (!function_exists('WC')) {
        wp_send_json_error('WooCommerce not available');
        return;
    }

    $cart = WC()->cart;
    $cart_count = $cart ? $cart->get_cart_contents_count() : 0;
    
    wp_send_json_success(array(
        'count' => $cart_count
    ));
}
