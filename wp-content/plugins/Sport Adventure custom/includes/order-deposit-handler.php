<?php

class SA_Order_Deposit_Handler {
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        // Remove all display actions since we're showing this info in the order items
        remove_action('woocommerce_thankyou', array($this, 'display_order_deposit_info'), 10);
        remove_action('woocommerce_order_details_after_order_table', array($this, 'display_order_deposit_info'), 10);
        remove_action('woocommerce_admin_order_data_after_order_details', array($this, 'display_admin_order_deposit_info'), 10);
        remove_action('woocommerce_email_after_order_table', array($this, 'display_email_order_deposit_info'), 10);
        
        // Save deposit info when order is created
        add_action('woocommerce_checkout_create_order_line_item', array($this, 'save_order_item_deposit_info'), 10, 4);
    }

    /**
     * Save deposit information when order is created
     */
    public function save_order_item_deposit_info($item, $cart_item_key, $values, $order) {
        if (!empty($values['variation_id'])) {
            $deposit_handler = sa_deposit_handler();
            $quantity = $item->get_quantity();
            $variation_id = $values['variation_id'];

            $components = $deposit_handler->get_price_components($variation_id, $quantity);
            $remaining_payments = $deposit_handler->calculate_remaining_payments($variation_id, $quantity);

            // Save deposit info as order item meta
            $item->add_meta_data('_deposit_components', $components);
            $item->add_meta_data('_remaining_payments', $remaining_payments);
            
            // Save the specific currency for this line item
            $currency = isset($components['waluta']) ? $components['waluta'] : 'PLN';
            $item->add_meta_data('_item_currency', $currency);
        }
    }
    
    /**
     * Format bank transfer details for email
     */
    public function get_email_bank_details($order) {
        $output = '';
        
        $output .= '<div class="order-bank-details">';
        $output .= '<h3>' . esc_html__('Numery kont do płatności', 'sport-adventure') . '</h3>';
        
        $output .= '<div class="bank-account-section">';
        $output .= '<p class="bank-account-heading">' . esc_html__('Opłat w złotówkach należy dokonać przelewem na rachunek bankowy:', 'sport-adventure') . '</p>';
        $output .= '<div class="bank-account-number">65 1140 2004 0000 3502 8268 8764</div>';
        $output .= '</div>';
        
        // Get order items to display transfer details
        $items = $order->get_items();
        if (!empty($items)) {
            $item = reset($items); // Get first item
            $item_id = $item->get_id();
            $product_name = $item->get_name();
            $variation_id = $item->get_variation_id();
            
            // Try to get variation date
            $variation = $variation_id ? wc_get_product($variation_id) : null;
            $date_range = '';
            
            if ($variation) {
                $attributes = $variation->get_variation_attributes();
                if (isset($attributes['attribute_pa_termin'])) {
                    $termin = $attributes['attribute_pa_termin'];
                    // Format termin from 01-01-2025 to 01.01.2025
                    $date_range = str_replace('-', '.', $termin);
                }
            }
            
            $transfer_title = $product_name;
            if (!empty($date_range)) {
                $transfer_title .= ' - ' . $date_range;
            }
            
            $output .= '<div class="transfer-details">';
            $output .= '<div class="bank-detail">';
            $output .= '<span class="bank-detail__label">' . esc_html__('Tytuł przelewu:', 'sport-adventure') . '</span>';
            $output .= '<span class="bank-detail__value">' . esc_html($transfer_title) . '</span>';
            $output .= '</div>';
            
            $output .= '<div class="bank-detail">';
            $output .= '<span class="bank-detail__label">' . esc_html__('Kwota:', 'sport-adventure') . '</span>';
            $output .= '<span class="bank-detail__value">' . wp_kses_post($order->get_formatted_order_total()) . '</span>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Display order deposit info in the order received page
     */
    public function display_order_deposit_info($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) return;
        
        $total_deposit = 0;
        $remaining_payments = array();
        
        // Calculate total deposit and collect remaining payments from all order items
        foreach ($order->get_items() as $item_id => $item) {
            $deposit_components = $item->get_meta('_deposit_components');
            if ($deposit_components && is_array($deposit_components)) {
                $total_deposit += $deposit_components['deposit'];
                
                $remaining_payments = $item->get_meta('_remaining_payments');
                if (is_array($remaining_payments)) {
                    foreach ($remaining_payments as $payment) {
                        $all_remaining_payments[] = $payment;
                    }
                }
            }
        }
        
        if ($total_deposit > 0) {
            $deposit_handler = sa_deposit_handler();

            // Check if there are any discounts applied to the order
            $order_discount = $order->get_discount_total();
            $formatted_deposit = $deposit_handler->format_price($total_deposit);
            if ($order_discount > 0) {
                $formatted_deposit .= ' <span class="discount">(-' . number_format($order_discount, 0, ',', ' ') . ' PLN zniżki)</span>';
            }
            ?>
            <div class="order-deposit-info">
                <h3>Informacje o płatnościach</h3>
                <p class="deposit-amount">Łączna zaliczka do zapłaty: <?php echo $formatted_deposit; ?></p>
                <?php if (!empty($all_remaining_payments)): ?>
                    <div class="remaining-payments">
                        <h4>Pozostałe płatności:</h4>
                        <div class="payment-schedule">
                            <?php foreach ($all_remaining_payments as $payment): ?>
                                <p>
                                    <?php echo $payment['due'] . ' płatne ' . 
                                         ($payment['currency'] === 'PLN' 
                                             ? number_format($payment['amount'], 0, '.', '') . ' PLN'
                                             : $deposit_handler->format_price($payment['amount'], $payment['currency'])); ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        }
    }
}

function sa_order_deposit_handler() {
    return SA_Order_Deposit_Handler::get_instance();
}

// Initialize the handler
add_action('init', 'sa_order_deposit_handler'); 