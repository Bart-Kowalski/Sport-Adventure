<?php

class SA_Cart_Calculator {
    private static $instance = null;
    private static $cache = [];
    private static $cart_snapshot = null;
    private static $is_calculating = false;
    private static $cache_key_prefix = 'sa_cart_';
    private static $cache_group = 'sport_adventure';
    private static $cache_expiry = 3600; // 1 hour
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('woocommerce_before_calculate_totals', array($this, 'clear_cache'), 10);
        add_action('woocommerce_cart_loaded_from_session', array($this, 'update_cart_snapshot'), 10);
        add_action('woocommerce_cart_updated', array($this, 'update_cart_snapshot'), 10);
        add_action('woocommerce_applied_coupon', array($this, 'clear_cache'), 10);
        add_action('woocommerce_removed_coupon', array($this, 'clear_cache'), 10);
        add_action('woocommerce_cart_item_removed', array($this, 'clear_cache'), 10);
        add_action('woocommerce_cart_item_restored', array($this, 'clear_cache'), 10);
    }
    
    private function get_cart_hash() {
        $cart = WC()->cart;
        if (!$cart) return '';
        
        // Include cart items, quantities, and applied coupons in the hash
        $hash_data = array(
            'items' => array(),
            'coupons' => $cart->get_applied_coupons()
        );
        
        foreach ($cart->get_cart() as $key => $item) {
            $hash_data['items'][$key] = array(
                'id' => $item['variation_id'],
                'quantity' => $item['quantity'],
                'total' => isset($item['line_total']) ? $item['line_total'] : 0,
                'subtotal' => isset($item['line_subtotal']) ? $item['line_subtotal'] : 0
            );
        }
        
        return md5(serialize($hash_data));
    }
    
    public function clear_cache() {
        self::$cache = [];
        self::$cart_snapshot = null;
        
        // Clear transients for this cart
        $cart_hash = $this->get_cart_hash();
        if ($cart_hash) {
            delete_transient(self::$cache_key_prefix . 'totals_' . $cart_hash);
            delete_transient(self::$cache_key_prefix . 'components_' . $cart_hash);
        }
    }
    
    private function get_cached_value($key, $cart_hash = '') {
        // First check memory cache
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        
        // Then check transient cache if cart hash provided
        if ($cart_hash) {
            $transient_key = self::$cache_key_prefix . $key . '_' . $cart_hash;
            $cached_value = get_transient($transient_key);
            if ($cached_value !== false) {
                self::$cache[$key] = $cached_value;
                return $cached_value;
            }
        }
        
        return null;
    }
    
    private function set_cached_value($key, $value, $cart_hash = '') {
        // Set memory cache
        self::$cache[$key] = $value;
        
        // Set transient cache if cart hash provided
        if ($cart_hash) {
            $transient_key = self::$cache_key_prefix . $key . '_' . $cart_hash;
            set_transient($transient_key, $value, self::$cache_expiry);
        }
    }
    
    public function update_cart_snapshot() {
        if (self::$is_calculating) return;
        self::$is_calculating = true;
        
        $cart = WC()->cart;
        if (!$cart) {
            self::$is_calculating = false;
            return;
        }
        
        $cart_hash = $this->get_cart_hash();
        $cached_snapshot = $this->get_cached_value('snapshot', $cart_hash);
        
        if ($cached_snapshot !== null) {
            self::$cart_snapshot = $cached_snapshot;
            self::$is_calculating = false;
            return;
        }
        
        $snapshot = [];
        foreach ($cart->get_cart() as $key => $item) {
            $snapshot[$key] = [
                'key' => $key,
                'variation_id' => isset($item['variation_id']) ? $item['variation_id'] : 0,
                'quantity' => $item['quantity'],
                'line_total' => isset($item['line_total']) ? $item['line_total'] : 0,
                'line_subtotal' => isset($item['line_subtotal']) ? $item['line_subtotal'] : 0
            ];
        }
        
        self::$cart_snapshot = $snapshot;
        $this->set_cached_value('snapshot', $snapshot, $cart_hash);
        self::$is_calculating = false;
    }
    
    private function get_cart_snapshot() {
        if (self::$cart_snapshot === null) {
            $this->update_cart_snapshot();
        }
        return self::$cart_snapshot;
    }
    
    public function get_item_deposit($cart_item) {
        if (empty($cart_item['variation_id'])) {
            return 0;
        }
        
        $cart_hash = $this->get_cart_hash();
        $cache_key = 'deposit_' . $cart_item['variation_id'] . '_' . $cart_item['quantity'];
        $cached_deposit = $this->get_cached_value($cache_key, $cart_hash);
        
        if ($cached_deposit !== null) {
            return $cached_deposit;
        }
        
        $deposit_handler = sa_deposit_handler();
        $components = $deposit_handler->get_price_components($cart_item['variation_id'], $cart_item['quantity']);
        
        // Store original deposit before discount
        $original_deposit = $components['deposit'];
        $this->set_cached_value($cache_key . '_components', $components, $cart_hash);
        
        // Apply discount if present
        if (!empty($cart_item['line_subtotal']) && $cart_item['line_subtotal'] !== $cart_item['line_total']) {
            $discount_ratio = $cart_item['line_total'] / $cart_item['line_subtotal'];
            $components['deposit'] = round($components['deposit'] * $discount_ratio);
            $this->set_cached_value($cache_key . '_original', $original_deposit, $cart_hash);
        }
        
        $this->set_cached_value($cache_key, $components['deposit'], $cart_hash);
        return $components['deposit'];
    }
    
    public function get_item_components($cart_item) {
        if (empty($cart_item['variation_id'])) {
            return null;
        }
        
        $cart_hash = $this->get_cart_hash();
        $cache_key = 'deposit_' . $cart_item['variation_id'] . '_' . $cart_item['quantity'];
        
        // Force deposit calculation if components not cached
        $cached_components = $this->get_cached_value($cache_key . '_components', $cart_hash);
        if ($cached_components === null) {
            $this->get_item_deposit($cart_item);
            $cached_components = $this->get_cached_value($cache_key . '_components', $cart_hash);
        }
        
        return $cached_components;
    }
    
    public function get_item_original_deposit($cart_item) {
        if (empty($cart_item['variation_id'])) {
            return 0;
        }
        
        $cart_hash = $this->get_cart_hash();
        $cache_key = 'deposit_' . $cart_item['variation_id'] . '_' . $cart_item['quantity'];
        
        // Force deposit calculation if original not cached
        $cached_original = $this->get_cached_value($cache_key . '_original', $cart_hash);
        if ($cached_original === null) {
            $this->get_item_deposit($cart_item);
            $cached_original = $this->get_cached_value($cache_key . '_original', $cart_hash);
        }
        
        return $cached_original ?: $this->get_item_deposit($cart_item);
    }
    
    public function get_item_remaining_payments($cart_item) {
        if (empty($cart_item['variation_id'])) {
            return array();
        }
        
        $cart_hash = $this->get_cart_hash();
        $cache_key = 'remaining_' . $cart_item['variation_id'] . '_' . $cart_item['quantity'];
        $cached_payments = $this->get_cached_value($cache_key, $cart_hash);
        
        if ($cached_payments !== null) {
            return $cached_payments;
        }
        
        $deposit_handler = sa_deposit_handler();
        $payments = $deposit_handler->calculate_remaining_payments($cart_item['variation_id'], $cart_item['quantity']);
        
        $this->set_cached_value($cache_key, $payments, $cart_hash);
        return $payments;
    }
    
    public function get_cart_total_deposit() {
        $cart_hash = $this->get_cart_hash();
        $cache_key = 'total_deposit';
        $cached_total = $this->get_cached_value($cache_key, $cart_hash);
        
        if ($cached_total !== null) {
            return $cached_total;
        }
        
        $total = 0;
        $cart = WC()->cart;
        
        if ($cart) {
            foreach ($cart->get_cart() as $cart_item) {
                $total += $this->get_item_deposit($cart_item);
            }
        }
        
        $this->set_cached_value($cache_key, $total, $cart_hash);
        return $total;
    }
    
    public function get_cart_remaining_payments() {
        $cart_hash = $this->get_cart_hash();
        $cache_key = 'total_remaining';
        $cached_payments = $this->get_cached_value($cache_key, $cart_hash);
        
        if ($cached_payments !== null) {
            return $cached_payments;
        }
        
        $payments = array();
        $cart = WC()->cart;
        
        if ($cart) {
            foreach ($cart->get_cart() as $cart_item) {
                $item_payments = $this->get_item_remaining_payments($cart_item);
                foreach ($item_payments as $payment) {
                    $key = $payment['due'] . '_' . $payment['currency'];
                    if (!isset($payments[$key])) {
                        $payments[$key] = $payment;
                    } else {
                        $payments[$key]['amount'] += $payment['amount'];
                    }
                }
            }
        }
        
        $payments = array_values($payments);
        $this->set_cached_value($cache_key, $payments, $cart_hash);
        return $payments;
    }
    
    public function get_cart_totals() {
        $cart_hash = $this->get_cart_hash();
        $cache_key = 'totals';
        $cached_totals = $this->get_cached_value($cache_key, $cart_hash);
        
        if ($cached_totals !== null) {
            return $cached_totals;
        }
        
        $totals = array(
            'deposit' => $this->get_cart_total_deposit(),
            'remaining_payments' => $this->get_cart_remaining_payments()
        );
        
        $this->set_cached_value($cache_key, $totals, $cart_hash);
        return $totals;
    }
}

function sa_cart_calculator() {
    return SA_Cart_Calculator::get_instance();
} 