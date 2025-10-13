/**
 * Validate checkout fields
 */
public function validate_checkout_fields() {
    try {
        // Get cart items
        $cart = WC()->cart->get_cart();
        
        foreach ($cart as $cart_item_key => $cart_item) {
            $product = $cart_item['data'];
            $product_name = $product->get_name();
            $variation_id = $cart_item['variation_id'];
            $variation = wc_get_product($variation_id);
            $variation_attributes = $variation->get_variation_attributes();
            $termin = isset($variation_attributes['attribute_pa_termin']) ? $variation_attributes['attribute_pa_termin'] : '';
            $product_unique_id = sanitize_title($product_name . '-' . $termin);
            
            // Validation is now handled by participant-handler.php
            // No need for duplicate validation here
        }
    } catch (Exception $e) {
        wc_add_notice($e->getMessage(), 'error');
    }
}

/**
 * Process checkout
 */
public function process_checkout($order_id) {
    try {
        // Additional processing if needed
    } catch (Exception $e) {
        wc_add_notice($e->getMessage(), 'error');
    }
}

// Hook validation method to WooCommerce checkout process
// Note: Validation is handled by participant-handler.php to avoid conflicts
// add_action('woocommerce_checkout_process', [$this, 'validate_checkout_fields']);
add_action('woocommerce_checkout_update_order_meta', [$this, 'process_checkout'], 10, 1);

// ... existing code ... 