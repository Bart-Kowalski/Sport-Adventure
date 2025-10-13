<?php
/**
 * WooCommerce Currency Customization
 */

defined('ABSPATH') || exit;

// Change WooCommerce currency symbol from zł to PLN
add_filter('woocommerce_currency_symbol', 'sa_change_currency_symbol', 10, 2);

function sa_change_currency_symbol($currency_symbol, $currency) {
    if ($currency === 'PLN') {
        return 'PLN';
    }
    return $currency_symbol;
} 