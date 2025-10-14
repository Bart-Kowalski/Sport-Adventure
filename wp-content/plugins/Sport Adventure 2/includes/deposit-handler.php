<?php

class SA_Deposit_Handler {
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        // Empty constructor
    }

    /**
     * Get deposit amount for a variation
     */
    public function get_deposit_amount($variation_id, $quantity = 1) {
        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: get_deposit_amount for variation $variation_id with quantity $quantity");
        }
        
        $product = wc_get_product($variation_id);
        if (!$product) return 0;

        $amount = $product->get_price();
        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Raw deposit amount from product: $amount");
        }
        
        $final_amount = $amount * $quantity;
        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Final deposit amount: $amount * $quantity = $final_amount");
        }
        return $final_amount;
    }

    /**
     * Get all price components for a variation
     */
    public function get_price_components($variation_id, $quantity = 1) {
        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: get_price_components for variation $variation_id with quantity $quantity");
        }

        // Get all ACF fields in one query
        $fields = get_fields($variation_id);
        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Raw ACF fields: " . print_r($fields, true));
        }
        
        // Get base components (for quantity=1)
        $components = array(
            'cena_bez_lotow' => floatval($fields['wyprawa-termin__cena-nie-liczac-lotow'] ?? 0),
            'cena_lotu' => floatval($fields['wyprawa-termin__cena-lotu'] ?? 0),
            'waluta' => $fields['wyprawa-termin__waluta'] ?: 'PLN',
            'deposit' => $this->get_deposit_amount($variation_id, 1), // Get for quantity 1
            'deposit_currency' => 'PLN' // Deposit is always in PLN
        );
        
        $components['total_price'] = $components['cena_bez_lotow'] + $components['cena_lotu'];
        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Base components before quantity: " . print_r($components, true));
        }
        
        // Apply quantity
        $components['deposit'] *= $quantity;
        $components['cena_bez_lotow'] *= $quantity;
        $components['cena_lotu'] *= $quantity;
        $components['total_price'] *= $quantity;
        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Final components after quantity: " . print_r($components, true));
        }
        
        return $components;
    }

    /**
     * Calculate remaining payments
     */
    public function calculate_remaining_payments($variation_id, $quantity = 1) {
        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Starting calculate_remaining_payments for variation $variation_id with quantity $quantity");
        }

        // Get base components (quantity=1)
        $components = $this->get_price_components($variation_id, 1);
        $remaining_payments = array();

        // Convert values to match appropriate currency
        $cena_bez_lotow = $components['cena_bez_lotow'];
        $cena_lotu = $components['cena_lotu'];
        $waluta = $components['waluta'];
        $deposit = $components['deposit']; // Always in PLN

        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Working with values (q=1): cena_lotu=$cena_lotu, deposit=$deposit");
        }

        if ($waluta === 'PLN') {
            // For Polish trips
            
            // 30 days payment - Polish trips without flights or remainder
            if ($cena_lotu <= 0) {
                $remaining_30_days = $cena_bez_lotow - $deposit;
                if ($remaining_30_days > 0) {
                    $remaining_payments[] = array(
                        'amount' => $remaining_30_days * $quantity,
                        'currency' => 'PLN',
                        'due' => '30 dni przed wyprawą'
                    );
                }
            }
            
            // 60 days payment - Flight costs for Polish trips with flights
            if ($cena_lotu > 0) {
                // For flight payment, we just take the total flight cost minus the deposit paid
                $flight_remaining = ($cena_lotu * $quantity) - ($deposit * $quantity);
                if (sa_is_php_debug_enabled()) {
                    error_log("DEBUG: Flight remaining: $flight_remaining = ($cena_lotu * $quantity) - ($deposit * $quantity)");
                }
                
                if ($flight_remaining > 0) {
                    $remaining_payments[] = array(
                        'amount' => $flight_remaining,
                        'currency' => 'PLN',
                        'due' => '60 dni przed wyprawą'
                    );
                }
            }
            
            // 90 days payment - Main trip cost for Polish trips with flights
            if ($cena_lotu > 0) {
                $main_trip_remaining = $cena_bez_lotow;
                if ($deposit > $cena_lotu) {
                    $main_trip_remaining -= ($deposit - $cena_lotu);
                }

                if ($main_trip_remaining > 0) {
                    $remaining_payments[] = array(
                        'amount' => $main_trip_remaining * $quantity,
                        'currency' => 'PLN',
                        'due' => '90 dni przed wyprawą'
                    );
                }
            }
        } else {
            // For international trips (non-PLN currency like USD)
            
            // Always add the main trip cost in the original currency (e.g. USD)
            if ($cena_bez_lotow > 0) {
                $remaining_payments[] = array(
                    'amount' => $cena_bez_lotow * $quantity,
                    'currency' => $waluta,
                    'due' => '90 dni przed wyprawą'
                );
            }
            
            // Flight cost handling (always in PLN)
            if ($cena_lotu > 0) {
                // For flight payment, we just take the total flight cost minus the deposit paid
                $flight_remaining = ($cena_lotu * $quantity) - ($deposit * $quantity);
                if (sa_is_php_debug_enabled()) {
                    error_log("DEBUG: Flight remaining: $flight_remaining = ($cena_lotu * $quantity) - ($deposit * $quantity)");
                }
                
                if ($flight_remaining > 0) {
                    $remaining_payments[] = array(
                        'amount' => $flight_remaining,
                        'currency' => 'PLN',
                        'due' => '60 dni przed wyprawą'
                    );
                }
            }
        }

        // Sort the payments chronologically (30, 60, 90)
        usort($remaining_payments, function($a, $b) {
            preg_match('/(\d+)/', $a['due'], $matches_a);
            $days_a = isset($matches_a[1]) ? (int)$matches_a[1] : 0;
            
            preg_match('/(\d+)/', $b['due'], $matches_b);
            $days_b = isset($matches_b[1]) ? (int)$matches_b[1] : 0;
            
            return $days_a - $days_b; // Sort in ascending order
        });

        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Final remaining payments: " . print_r($remaining_payments, true));
        }
        return $remaining_payments;
    }

    /**
     * Calculate remaining payments adjusted for order discounts
     */
    public function calculate_adjusted_remaining_payments($order_item) {
        if (!is_a($order_item, 'WC_Order_Item_Product')) {
            return array();
        }

        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Starting calculate_adjusted_remaining_payments for order item " . $order_item->get_id());
        }

        $remaining_payments = $order_item->get_meta('_remaining_payments');
        if (!$remaining_payments || !is_array($remaining_payments)) {
            if (sa_is_php_debug_enabled()) {
                error_log("DEBUG: No remaining payments found in order item meta");
            }
            return array();
        }

        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Original remaining payments: " . print_r($remaining_payments, true));
        }

        // Check if order has any discounts applied
        $order = $order_item->get_order();
        if (!$order) {
            if (sa_is_php_debug_enabled()) {
                error_log("DEBUG: No order found for item");
            }
            return $remaining_payments;
        }

        $order_discount = $order->get_discount_total();
        if ($order_discount <= 0) {
            if (sa_is_php_debug_enabled()) {
                error_log("DEBUG: No order discount found");
            }
            return $remaining_payments;
        }

        // Get original and discounted deposit amounts
        $line_subtotal = $order_item->get_subtotal();
        $line_total = $order_item->get_total();
        $line_discount = max(0, $line_subtotal - $line_total);
        
        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Order item values: subtotal=$line_subtotal, total=$line_total, discount=$line_discount");
        }
        
        if ($line_discount <= 0) {
            if (sa_is_php_debug_enabled()) {
                error_log("DEBUG: No line item discount");
            }
            return $remaining_payments;
        }

        // Adjust remaining payments by re-adding the part of the discount that was
        // already applied to the deposit
        $adjusted_payments = array();
        $discount_added = false;
        foreach ($remaining_payments as $payment) {
            $adjusted_payment = $payment;
            if (sa_is_php_debug_enabled()) {
                error_log("DEBUG: Processing payment: " . print_r($payment, true));
            }

            if (!$discount_added && $payment['currency'] === 'PLN') {
                $old_amount = $adjusted_payment['amount'];
                $adjusted_payment['amount'] = max(0, $payment['amount'] + $line_discount);
                if (sa_is_php_debug_enabled()) {
                    error_log("DEBUG: Adjusted PLN payment: $old_amount + $line_discount = " . $adjusted_payment['amount']);
                }
                $discount_added = true;
            }

            // Only include payment if amount > 0
            if ($adjusted_payment['amount'] > 0) {
                $adjusted_payments[] = $adjusted_payment;
            }
        }

        if (sa_is_php_debug_enabled()) {
            error_log("DEBUG: Final adjusted payments: " . print_r($adjusted_payments, true));
        }
        return $adjusted_payments;
    }

    /**
     * Format price for display
     */
    public function format_price($amount, $currency = 'PLN') {
        if ($currency === 'PLN') {
            return number_format($amount, 0, ',', '') . ' PLN';
        }
        return number_format($amount, 2, '.', ',') . ' ' . $currency;
    }

    /**
     * Format price for email
     */
    public function format_email_price($amount, $currency = 'PLN') {
        if ($currency === 'PLN') {
            return number_format($amount, 0, '.', '') . ' PLN';
        }
        return number_format($amount, 2, '.', ',') . ' ' . $currency;
    }

    /**
     * Get email-friendly HTML for deposit information
     */
    public function get_email_deposit_html($variation_id, $quantity = 1) {
        $components = $this->get_price_components($variation_id, $quantity);
        $remaining_payments = $this->calculate_remaining_payments($variation_id, $quantity);

        $output = '';
        // Price breakdown
        if ($components['deposit'] > 0) {
            $output .= '<p class="order-item__detail"><span class="order-item__label">' . esc_html__('Cena za osobę (opłacona zaliczka):', 'sport-adventure') . '</span> ' . 
                $this->format_email_price($components['deposit'] / $quantity, $components['deposit_currency']) . '</p>';
                
            $output .= '<p class="order-item__detail"><span class="order-item__label">' . esc_html__('Łącznie opłacona zaliczka:', 'sport-adventure') . '</span> ' . 
                $this->format_email_price($components['deposit'], $components['deposit_currency']) . '</p>';
        }
        
        if ($components['cena_bez_lotow'] > 0) {
            $output .= '<p class="order-item__detail"><span class="order-item__label">' . esc_html__('Łączna cena wyprawy:', 'sport-adventure') . '</span> ' . 
                $this->format_email_price($components['cena_bez_lotow'], $components['waluta']) . '</p>';
        }
        
        if ($components['cena_lotu'] > 0) {
            $output .= '<p class="order-item__detail"><span class="order-item__label">' . esc_html__('Cena lotu:', 'sport-adventure') . '</span> ' . 
                $this->format_email_price($components['cena_lotu'], 'PLN') . '</p>';
        }
        
        // Remaining payments
        if (!empty($remaining_payments)) {
            $output .= '<h4 class="order-item__section-title">' . esc_html__('Pozostałe płatności:', 'sport-adventure') . '</h4>';
            
            foreach ($remaining_payments as $payment) {
                $output .= '<p class="order-item__detail">' . 
                    esc_html($payment['due'] . ' płatne: ' . $this->format_email_price($payment['amount'], $payment['currency'])) . 
                    '</p>';
            }
        }
        
        return $output;
    }

    /**
     * Get HTML for deposit display
     */
    public function get_deposit_html($variation_id, $quantity = 1) {
        $components = $this->get_price_components($variation_id, $quantity);
        $remaining_payments = $this->calculate_remaining_payments($variation_id, $quantity);

        ob_start();
        ?>
        <div class="deposit-info">
            <span class="deposit-label">Łączna zaliczka płatna do 7 dni</span>
            <span class="deposit-amount"><?php echo $this->format_price($components['deposit']); ?></span>
        </div>
        <?php if (!empty($remaining_payments)): ?>
            <div class="remaining-payment">
                <?php foreach ($remaining_payments as $payment): ?>
                    <div><?php echo $payment['due'] . ' płatne ' . $this->format_price($payment['amount'], $payment['currency']); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif;
        return ob_get_clean();
    }
}

// Initialize the handler
function sa_deposit_handler() {
    return SA_Deposit_Handler::get_instance();
} 