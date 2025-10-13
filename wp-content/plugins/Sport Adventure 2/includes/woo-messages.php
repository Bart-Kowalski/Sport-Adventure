<?php
/**
 * WooCommerce Message Customizations
 */

defined('ABSPATH') || exit;

add_filter('gettext', 'sa_custom_woocommerce_messages', 20, 3);

function sa_custom_woocommerce_messages($translated_text, $text, $domain) {
    if ($domain === 'woocommerce') {
        switch ($translated_text) {
            case 'Przepraszamy, ten produkt jest niedostępny.':
                $translated_text = 'Przepraszamy, ta wyprawa jest niedostępna.';
                break;
            case 'Produkt niedostępny':
                $translated_text = 'Wyprawa niedostępna';
                break;
        }
    }
    return $translated_text;
} 