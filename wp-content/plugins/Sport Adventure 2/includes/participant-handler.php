<?php

class SA_Participant_Handler {
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        // Add shortcode for thank you page
        add_shortcode('sa_order_confirmation', array($this, 'render_order_confirmation'));
        
        // Remove duplicate actions
        remove_action('woocommerce_admin_order_data_after_order_details', array($this, 'display_admin_order_deposit_info'), 10);
        remove_action('woocommerce_thankyou', array($this, 'display_order_deposit_info'), 10);
        remove_action('woocommerce_order_details_after_order_table', array($this, 'display_order_deposit_info'), 10);
        remove_action('woocommerce_email_after_order_table', array($this, 'display_email_order_deposit_info'), 10);

        // Save participant data when order is created
        add_action('woocommerce_checkout_create_order_line_item', array($this, 'save_participant_data'), 10, 4);

        // Display participant data
        add_action('woocommerce_after_order_itemmeta', array($this, 'display_admin_participant_data'), 10, 2);
        add_action('woocommerce_order_item_meta_end', array($this, 'display_participant_data'), 10, 3);
        add_action('woocommerce_email_order_item_meta', array($this, 'display_email_participant_data'), 10, 3);
        add_action('woocommerce_email_after_order_table', array($this, 'display_email_bank_details'), 10, 4);

        // Add validation hooks
        add_action('woocommerce_checkout_process', array($this, 'validate_checkout_fields'));
        add_filter('woocommerce_add_error', array($this, 'customize_error_messages'), 10, 1);

        // Add filter to format order item meta display
        add_filter('woocommerce_order_item_display_meta_key', array($this, 'format_order_item_meta_key'), 10, 3);

        // Add coupon display to order confirmation
        add_action('woocommerce_order_details_after_order_table', 'display_coupon_information', 9);
    }

    /**
     * Format order item meta key display
     */
    public function format_order_item_meta_key($display_key, $meta, $item) {
        $translations = array(
            '_cena_lotu' => 'Cena lotu',
            '_cena_bez_lotow' => 'Cena bez lotów',
            '_waluta' => 'Waluta',
            '_item_currency' => 'Waluta',
            '_participant_' => 'Uczestnik ',
            '_deposit_amount' => '',
            '_deposit_components' => ''
        );

        foreach ($translations as $key => $translation) {
            if (strpos($meta->key, $key) === 0) {
                if ($key === '_participant_') {
                    $participant_num = str_replace('_participant_', '', $meta->key);
                    return 'Uczestnik ' . $participant_num;
                }
                if ($key === '_deposit_amount' || $key === '_deposit_components') {
                    return '';
                }
                return $translation;
            }
        }

        return $display_key;
    }

    /**
     * Save participant data
     */
    public function save_participant_data($item, $cart_item_key, $values, $order) {
        if (empty($values['variation_id'])) return;

        $product = $item->get_product();
        $product_name = get_the_title($product->get_parent_id());
        $variation_attributes = $product->get_variation_attributes();
        $termin = isset($variation_attributes['attribute_pa_termin']) ? $variation_attributes['attribute_pa_termin'] : '';
        $product_unique_id = sanitize_title($product_name . '-' . $termin);
        
        $quantity = $item->get_quantity();
        
        // Save participant data
        for ($i = 0; $i < $quantity; $i++) {
            $prefix = "participant_{$product_unique_id}_{$i}";
            
            if ($i === 0) {
                // Use billing info for first participant
                $participant_data = array(
                    'data' => array(
                        'first_name' => $order->get_billing_first_name(),
                        'last_name' => $order->get_billing_last_name(),
                        'phone' => $order->get_billing_phone(),
                        'email' => $order->get_billing_email()
                    ),
                    'consents' => array(
                        'regulations' => isset($_POST["{$prefix}_consent_regulations"]),
                        'marketing' => isset($_POST["{$prefix}_consent_marketing"]),
                        'phone' => isset($_POST["{$prefix}_consent_phone"]),
                        'profiling' => isset($_POST["{$prefix}_consent_profiling"]),
                        'insurance' => isset($_POST["{$prefix}_consent_insurance"])
                    )
                );
            } else {
                // Get participant data from POST for additional participants
                $participant_data = array(
                    'data' => array(
                        'first_name' => isset($_POST["{$prefix}_first_name"]) ? sanitize_text_field($_POST["{$prefix}_first_name"]) : '',
                        'last_name' => isset($_POST["{$prefix}_last_name"]) ? sanitize_text_field($_POST["{$prefix}_last_name"]) : '',
                        'phone' => isset($_POST["{$prefix}_phone"]) ? sanitize_text_field($_POST["{$prefix}_phone"]) : '',
                        'email' => isset($_POST["{$prefix}_email"]) ? sanitize_email($_POST["{$prefix}_email"]) : ''
                    ),
                    'consents' => array(
                        'regulations' => isset($_POST["{$prefix}_consent_regulations"]),
                        'marketing' => isset($_POST["{$prefix}_consent_marketing"]),
                        'phone' => isset($_POST["{$prefix}_consent_phone"]),
                        'profiling' => isset($_POST["{$prefix}_consent_profiling"]),
                        'insurance' => isset($_POST["{$prefix}_consent_insurance"])
                    )
                );
            }
            
            // Save as order item meta
            $item->add_meta_data("_participant_" . ($i + 1), $participant_data);
        }

        // Save price components
        $deposit_handler = sa_deposit_handler();
        $components = $deposit_handler->get_price_components($values['variation_id'], $quantity);
        $item->add_meta_data('_deposit_components', $components);
    }

    /**
     * Display participant data on frontend order views
     */
    public function display_participant_data($item_id, $item, $order) {
        // Check if we're on the My Account page
        $is_my_account = is_account_page() && !is_checkout();
        
        if ($is_my_account) {
            // For My Account page, show simplified view
            if ($item === reset($order->get_items())) {
                // Order summary grid at the top
                echo '<div class="order-summary-grid">';
                echo '<div><span class="order-summary-label">Data:</span><span class="order-summary-value">' . $order->get_date_created()->date_i18n('d.m.Y') . '</span></div>';
                echo '<div><span class="order-summary-label">Metoda płatności:</span><span class="order-summary-value">' . $order->get_payment_method_title() . '</span></div>';
                echo '<div><span class="order-summary-label">Email:</span><span class="order-summary-value">' . $order->get_billing_email() . '</span></div>';
                echo '<div><span class="order-summary-label">Telefon:</span><span class="order-summary-value">' . $order->get_billing_phone() . '</span></div>';
                echo '<div><span class="order-summary-label">Łączna zaliczka do zapłaty:</span><span class="order-summary-value">' . $order->get_formatted_order_total() . '</span></div>';
                echo '</div>';

                // Add bank account information if payment method is BACS
                if ($order->get_payment_method() === 'bacs') {
                    // Get currencies needed for this order
                    $currencies_needed = array();
                    foreach ($order->get_items() as $order_item) {
                        $product = $order_item->get_product();
                        if ($product && $product->is_type('variation')) {
                            $currency = get_field('wyprawa-termin__waluta', $product->get_id());
                            if ($currency) {
                                $currencies_needed[] = strtoupper(trim($currency));
                            }
                        }
                    }
                    $currencies_needed = array_unique($currencies_needed);

                    echo '<div class="order-bank-details">';
                    echo '<h3>Numery kont do płatności</h3>';

                    // Always show PLN account
                    $pln_account = get_field('numer-konta__pln', 'option');
                    if ($pln_account) {
                        echo '<div class="bank-account-section">';
                        echo '<p class="bank-account-heading">Opłat w złotówkach należy dokonać przelewem na rachunek bankowy:</p>';
                        echo '<div class="bank-account-number">' . esc_html($pln_account) . '</div>';
                        echo '</div>';
                    }

                    // Show USD account only if needed
                    if (in_array('USD', $currencies_needed)) {
                        $usd_account = get_field('numer-konta__usd', 'option');
                        if ($usd_account) {
                            echo '<div class="bank-account-section">';
                            echo '<p class="bank-account-heading">Opłat w dolarach amerykańskich należy dokonać przelewem na rachunek bankowy:</p>';
                            echo '<div class="bank-account-number">' . esc_html($usd_account) . '</div>';
                            echo '</div>';
                        }
                    }

                    echo '<div class="bank-detail">';
                    echo '<span class="bank-detail__label">Tytuł przelewu:</span>';
                    echo '<span class="bank-detail__value">' . esc_html($this->format_transfer_title($order, reset($order->get_items()))) . '</span>';
                    echo '</div>';
                    echo '</div>';
                }
            }

            // Get deposit components
            $deposit_components = $item->get_meta('_deposit_components');
            
            // Show remaining payments if they exist
            if ($deposit_components && is_array($deposit_components)) {
                if (isset($deposit_components['cena_bez_lotow']) && $deposit_components['cena_bez_lotow'] > 0) {
                    $main_trip_remaining = ($deposit_components['cena_bez_lotow'] * $item->get_quantity());
                    if ($deposit_components['deposit'] > 0) {
                        $main_trip_remaining -= $deposit_components['deposit'];
                    }
                    if ($main_trip_remaining > 0) {
                        echo '<div class="remaining-payment-info">';
                        echo '<p>90 dni przed wyprawą płatne: ' . number_format($main_trip_remaining, 0, '.', '') . ' PLN</p>';
                        echo '</div>';
                    }
                }
            }
            
            // Show product and participant data
            $product = $item->get_product();
            $variant = $this->get_formatted_variant($product);
            
            echo '<div class="order-item">';
            echo '<h3 class="order-item__title">' . esc_html($product->get_name()) . '</h3>';
            if ($variant) {
                echo '<div class="order-item__variant">';
                foreach ($product->get_variation_attributes() as $attribute_name => $attribute_value) {
                    $taxonomy = str_replace('attribute_', '', $attribute_name);
                    
                    if ($taxonomy === 'pa_termin') {
                        $label = 'Termin';
                        $formatted_value = preg_replace('/(\d{2})-(\d{2})-(\d{4})-(\d{2})-(\d{2})-(\d{4})/', '$1.$2.$3 - $4.$5.$6', $attribute_value);
                    } else {
                        $label = wc_attribute_label($taxonomy, $product);
                        if (taxonomy_exists($taxonomy)) {
                            $term = get_term_by('slug', $attribute_value, $taxonomy);
                            $formatted_value = $term ? $term->name : $attribute_value;
                        } else {
                            $formatted_value = $attribute_value;
                        }
                    }
                    
                    echo '<strong>' . esc_html($label) . ':</strong> ' . esc_html($formatted_value) . '<br>';
                }
                echo '</div>';
            }
            
            // Show participant data
            for ($i = 1; $i <= $item->get_quantity(); $i++) {
                $participant = $item->get_meta('_participant_' . $i);
                if (!$participant || !is_array($participant)) continue;

                $data = $participant['data'];
                $consents = $participant['consents'];

                echo '<div class="participant">';
                echo '<h4 class="participant__title">Uczestnik ' . $i . '</h4>';
                echo '<div class="participant__details">';
                echo '<p class="participant__field"><span class="participant__label">Imię:</span> ' . esc_html($data['first_name']) . '</p>';
                echo '<p class="participant__field"><span class="participant__label">Nazwisko:</span> ' . esc_html($data['last_name']) . '</p>';
                echo '<p class="participant__field"><span class="participant__label">Telefon:</span> ' . esc_html($data['phone']) . '</p>';
                echo '<p class="participant__field"><span class="participant__label">Email:</span> ' . esc_html($data['email']) . '</p>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            
            return;
        }
        
        // For thank you page, show full view
        if ($item === reset($order->get_items())) {
            echo '<div class="custom-order-details">';
            
            // Success message
            echo '<div class="order-success-message">';
            echo '<svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>';
            echo '<h2>Dziękujemy! Twoje zamówienie zostało przyjęte.</h2>';
            echo '</div>';

            // Bank transfer details if applicable
            if ($order->get_payment_method() === 'bacs') {
                echo '<div class="order-bank-details">';
                echo '<h3>Numery kont do płatności</h3>';
                
                // Get currencies needed for this order
                $currencies_needed = array();
                foreach ($order->get_items() as $order_item) {
                    $product = $order_item->get_product();
                    if ($product && $product->is_type('variation')) {
                        $currency = get_field('wyprawa-termin__waluta', $product->get_id());
                        if ($currency) {
                            $currencies_needed[] = strtoupper(trim($currency));
                        }
                    }
                }
                $currencies_needed = array_unique($currencies_needed);

                // Always show PLN account
                $pln_account = get_field('numer-konta__pln', 'option');
                if ($pln_account) {
                    echo '<div class="bank-account-section">';
                    echo '<p class="bank-account-heading">Opłat w złotówkach należy dokonać przelewem na rachunek bankowy:</p>';
                    echo '<div class="bank-account-number">' . esc_html($pln_account) . '</div>';
                    echo '</div>';
                }

                // Show USD account only if needed
                if (in_array('USD', $currencies_needed)) {
                    $usd_account = get_field('numer-konta__usd', 'option');
                    if ($usd_account) {
                        echo '<div class="bank-account-section">';
                        echo '<p class="bank-account-heading">Opłat w dolarach amerykańskich należy dokonać przelewem na rachunek bankowy:</p>';
                        echo '<div class="bank-account-number">' . esc_html($usd_account) . '</div>';
                        echo '</div>';
                    }
                }

                echo '<div class="bank-detail">';
                echo '<span class="bank-detail__label">Tytuł przelewu:</span>';
                echo '<span class="bank-detail__value">' . esc_html($this->format_transfer_title($order, reset($order->get_items()))) . '</span>';
                echo '</div>';
                echo '</div>';
            }

            echo '<h2 class="order-details-title">Szczegóły zamówienia</h2>';
        }

        // Output the formatted participant data
        echo $this->get_formatted_participant_data($item);

        // If this is the last item, output the order totals and close the wrapper
        if ($item === end($order->get_items())) {
            echo $this->get_formatted_order_totals($order);
            echo '</div>'; // Close custom-order-details
        }
    }

    /**
     * Format order totals
     */
    private function get_formatted_order_totals($order) {
        $output = '<div class="order-totals">';
        
        // Order number and date
        $output .= '<div class="order-totals__header">';
        $output .= '<p class="order-totals__detail"><span class="order-totals__label">Numer zamówienia:</span> ' . $order->get_order_number() . '</p>';
        $output .= '<p class="order-totals__detail"><span class="order-totals__label">Data:</span> ' . $order->get_date_created()->date_i18n('d.m.Y') . '</p>';
        $output .= '<p class="order-totals__detail"><span class="order-totals__label">Metoda płatności:</span> ' . $order->get_payment_method_title() . '</p>';
        
        // Add billing details
        $output .= '<p class="order-totals__detail"><span class="order-totals__label">Email:</span> ' . $order->get_billing_email() . '</p>';
        $output .= '<p class="order-totals__detail"><span class="order-totals__label">Telefon:</span> ' . $order->get_billing_phone() . '</p>';
        $output .= '</div>';

        // Total amount
        $output .= '<div class="order-totals__amount">';
        $output .= '<p class="order-totals__detail"><span class="order-totals__label">Łączna zaliczka do zapłaty:</span> ' . $order->get_formatted_order_total() . '</p>';
        $output .= '</div>';

        $output .= '</div>';

        return $output;
    }

    /**
     * Format variant display
     */
    private function get_formatted_variant($product) {
        if (!$product) return '';
        
        $variation_attributes = $product->get_variation_attributes();
        if (empty($variation_attributes)) return '';
        
        $output = array();
        foreach ($variation_attributes as $attribute_name => $attribute_value) {
            $taxonomy = str_replace('attribute_', '', $attribute_name);
            
            // Get proper label based on attribute type
            if ($taxonomy === 'pa_termin') {
                $label = 'Termin';
                $formatted_value = preg_replace('/(\d{2})-(\d{2})-(\d{4})-(\d{2})-(\d{2})-(\d{4})/', '$1.$2.$3 - $4.$5.$6', $attribute_value);
            } else {
                // For all other attributes, get the proper label from taxonomy
                $label = wc_attribute_label($taxonomy, $product);
                // Get term name if it's a taxonomy
                if (taxonomy_exists($taxonomy)) {
                    $term = get_term_by('slug', $attribute_value, $taxonomy);
                    $formatted_value = $term ? $term->name : $attribute_value;
                } else {
                    $formatted_value = $attribute_value;
                }
            }
            
            $output[] = $label . ': ' . $formatted_value;
        }
        
        return implode("\n", $output);
    }

    /**
     * Display participant data in admin
     */
    public function display_admin_participant_data($item_id, $item) {
        if (!is_a($item, 'WC_Order_Item_Product')) return;
        
        $product = $item->get_product();
        $variant = $this->get_formatted_variant($product);
        
        echo '<div class="order-item-details">';
        echo '<strong>' . esc_html($product->get_name()) . '</strong><br>';
        if ($variant) {
            echo nl2br(esc_html($variant)) . '<br>';
        }
        
        echo $this->get_formatted_participant_data($item, true);
        echo '</div>';
    }

    /**
     * Display participant data in emails
     */
    public function display_email_participant_data($item_id, $item, $is_admin) {
        if (!is_a($item, 'WC_Order_Item_Product')) return;
        
        $product = $item->get_product();
        $variant = $this->get_formatted_variant($product);
        $quantity = $item->get_quantity();
        $deposit_components = $item->get_meta('_deposit_components');
        
        // Product name and variant
        echo '<br><strong>' . esc_html($product->get_name()) . '</strong><br>';
        if ($variant) {
            echo 'Termin: ' . esc_html($variant) . '<br>';
        }
        
        // Basic order info
        echo 'Liczba uczestników: ' . $quantity . '<br>';
        
        // Price info with currency handling
        if ($deposit_components && is_array($deposit_components)) {
            // Determine discount applied to this line item (subtotal before coupons vs total after coupons)
            $line_subtotal   = $item->get_subtotal();
            $line_total      = $item->get_total();
            $line_discount   = max(0, $line_subtotal - $line_total); // total discount for this item (all participants)
            $discount_each   = $quantity > 0 ? $line_discount / $quantity : 0;

            // Calculate prices after discount
            $price_after_discount_per_person = max(0, $product->get_price() - $discount_each);
            $deposit_after_discount_total    = max(0, $deposit_components['deposit'] - $line_discount);

            // Format display values
            $formatted_price   = $this->format_price_display($price_after_discount_per_person);
            $formatted_deposit = $this->format_price_display($deposit_after_discount_total);

            // Append discount note when applicable
            if ($discount_each > 0) {
                $formatted_price .= ' <span class="discount">(-' . number_format($discount_each, 0, ',', ' ') . ' PLN zniżki)</span>';
            }
            if ($line_discount > 0) {
                $formatted_deposit .= ' <span class="discount">(-' . number_format($line_discount, 0, ',', ' ') . ' PLN zniżki)</span>';
            }

            echo 'Cena za osobę (opłacona zaliczka): ' . $formatted_price . '<br>';
            echo 'Łącznie opłacona zaliczka: ' . $formatted_deposit . '<br>';
            
            $currency = isset($deposit_components['waluta']) ? $deposit_components['waluta'] : 'PLN';
            
            if (isset($deposit_components['cena_bez_lotow'])) {
                $total_trip_price = $deposit_components['cena_bez_lotow']; // Already a total for all participants
                echo 'Łączna cena wyprawy: ' . $this->format_price_display($total_trip_price, $currency) . '<br>';
                
                // Flight price is always in PLN
                if (isset($deposit_components['cena_lotu']) && $deposit_components['cena_lotu'] > 0) {
                    $total_flight_price = $deposit_components['cena_lotu']; // Already a total for all participants
                    echo 'Cena lotu: ' . $this->format_price_display($total_flight_price) . '<br>';
                }
            }
            
            // Show remaining payments with proper currency
            $deposit_handler = sa_deposit_handler();
            $remaining_payments = $deposit_handler->calculate_adjusted_remaining_payments($item);
            if (!empty($remaining_payments)) {
                echo '<br><strong>Pozostałe płatności:</strong><br>';
                foreach ($remaining_payments as $payment) {
                    $payment_currency = isset($payment['currency']) ? $payment['currency'] : 'PLN';
                    $payment_amount = isset($payment['amount']) ? $payment['amount'] : 0;
                    $payment_due = isset($payment['due']) ? $payment['due'] : '';
                    
                    echo $payment_due . ' płatne ' . $this->format_price_display($payment_amount, $payment_currency) . '<br>';
                }
            }
        }
        
        echo '<br>';
        
        // Participant data
        for ($i = 1; $i <= $quantity; $i++) {
            $participant = $item->get_meta('_participant_' . $i);
            if (!$participant || !is_array($participant)) continue;

            $data = $participant['data'];
            
            echo '<strong>Uczestnik ' . $i . '</strong><br>';
            echo 'Imię: ' . esc_html($data['first_name']) . '<br>';
            echo 'Nazwisko: ' . esc_html($data['last_name']) . '<br>';
            echo 'Telefon: ' . esc_html($data['phone']) . '<br>';
            echo 'Email: ' . esc_html($data['email']) . '<br><br>';
        }
    }

    /**
     * Format price display
     */
    private function format_price_display($amount, $currency = 'PLN') {
        return number_format($amount, 0, ',', ' ') . ' ' . $currency;
    }

    /**
     * Format participant data for display
     */
    private function get_formatted_participant_data($item, $is_admin = false) {
        if (!is_a($item, 'WC_Order_Item_Product')) return '';
        
        $output = '';
        $product = $item->get_product();
        $quantity = $item->get_quantity();
        $deposit_components = $item->get_meta('_deposit_components');

        // Start with product summary
        $output .= '<div class="order-item">';
        
        // Product header
        $output .= '<div class="order-item__summary">';
        $output .= '<h3 class="order-item__title">' . esc_html($product->get_name()) . '</h3>';
        
        // Variant info (dates)
        $variant = $this->get_formatted_variant($product);
        if ($variant) {
            $output .= '<div class="order-item__variant">' . wp_kses_post($variant) . '</div>';
        }
        
        // Price and payment details
        $output .= '<div class="order-item__content">';
        $output .= '<p class="order-item__detail"><span class="order-item__label">Liczba uczestników:</span> ' . $quantity . '</p>';
        
        if ($deposit_components && is_array($deposit_components)) {
            // Determine discount applied to this line item (subtotal before coupons vs total after coupons)
            $line_subtotal   = $item->get_subtotal();
            $line_total      = $item->get_total();
            $line_discount   = max(0, $line_subtotal - $line_total); // total discount for this item (all participants)
            $discount_each   = $quantity > 0 ? $line_discount / $quantity : 0;

            // Calculate prices after discount
            $price_after_discount_per_person = max(0, $product->get_price() - $discount_each);
            $deposit_after_discount_total    = max(0, $deposit_components['deposit'] - $line_discount);

            // Format display values
            $formatted_price   = $this->format_price_display($price_after_discount_per_person);
            $formatted_deposit = $this->format_price_display($deposit_after_discount_total);

            // Append discount note when applicable
            if ($discount_each > 0) {
                $formatted_price .= ' <span class="discount">(-' . number_format($discount_each, 0, ',', ' ') . ' PLN zniżki)</span>';
            }
            if ($line_discount > 0) {
                $formatted_deposit .= ' <span class="discount">(-' . number_format($line_discount, 0, ',', ' ') . ' PLN zniżki)</span>';
            }

            $output .= '<p class="order-item__detail"><span class="order-item__label">Cena za osobę (opłacona zaliczka):</span> ' . $formatted_price . '</p>';
            $output .= '<p class="order-item__detail"><span class="order-item__label">Łącznie opłacona zaliczka:</span> ' . $formatted_deposit . '</p>';
            
            // Get the currency from deposit components
            $currency = isset($deposit_components['waluta']) ? $deposit_components['waluta'] : 'PLN';
            
            if (isset($deposit_components['cena_bez_lotow'])) {
                // Show original trip price (these are already per-person values in the components)
                $total_trip_price = $deposit_components['cena_bez_lotow']; // Already a total for all participants
                $output .= '<p class="order-item__detail"><span class="order-item__label">Łączna cena wyprawy:</span> ' . 
                    $this->format_price_display($total_trip_price, $currency) . '</p>';
                
                // Flight price is always in PLN (also per-person in components)
                if (isset($deposit_components['cena_lotu']) && $deposit_components['cena_lotu'] > 0) {
                    $total_flight_price = $deposit_components['cena_lotu']; // Already a total for all participants
                    $output .= '<p class="order-item__detail"><span class="order-item__label">Cena lotu:</span> ' . 
                        $this->format_price_display($total_flight_price) . '</p>';
                }
            }
            
            // Show remaining payments with proper currency
            $deposit_handler = sa_deposit_handler();
            $remaining_payments = $deposit_handler->calculate_adjusted_remaining_payments($item);
            if (!empty($remaining_payments)) {
                $output .= '<h4 class="order-item__section-title">Pozostałe płatności:</h4>';
                
                // Sort payments chronologically (30, 60, 90)
                usort($remaining_payments, function($a, $b) {
                    preg_match('/(\d+)/', $a['due'], $matches_a);
                    $days_a = isset($matches_a[1]) ? (int)$matches_a[1] : 0;
                    
                    preg_match('/(\d+)/', $b['due'], $matches_b);
                    $days_b = isset($matches_b[1]) ? (int)$matches_b[1] : 0;
                    
                    return $days_a - $days_b;
                });
                
                foreach ($remaining_payments as $payment) {
                    $payment_currency = isset($payment['currency']) ? $payment['currency'] : 'PLN';
                    $payment_amount = isset($payment['amount']) ? $payment['amount'] : 0;
                    $payment_due = isset($payment['due']) ? $payment['due'] : '';
                    
                    $output .= '<p class="order-item__detail">' . $payment_due . ' płatne: ' . 
                        $this->format_price_display($payment_amount, $payment_currency) . '</p>';
                }
            }
        }
        $output .= '</div>'; // Close order-item__content
        $output .= '</div>'; // Close order-item__summary

        // Participant details
        $output .= '<div class="order-item__participants">';
        $output .= '<h4 class="order-item__section-title">Dane uczestników</h4>';

        for ($i = 1; $i <= $quantity; $i++) {
            $participant = $item->get_meta('_participant_' . $i);
            if (!$participant || !is_array($participant) || !isset($participant['data'])) continue;

            $data = $participant['data'];
            $consents = isset($participant['consents']) ? $participant['consents'] : array();

            $output .= '<div class="participant">';
            $output .= '<h4 class="participant__title">Uczestnik ' . $i . '</h4>';
            $output .= '<div class="participant__details">';
            $output .= '<p class="participant__field"><span class="participant__label">Imię:</span> ' . esc_html($data['first_name']) . '</p>';
            $output .= '<p class="participant__field"><span class="participant__label">Nazwisko:</span> ' . esc_html($data['last_name']) . '</p>';
            $output .= '<p class="participant__field"><span class="participant__label">Telefon:</span> ' . esc_html($data['phone']) . '</p>';
            $output .= '<p class="participant__field"><span class="participant__label">Email:</span> ' . esc_html($data['email']) . '</p>';
            $output .= '</div>';

            // Show consents for admin view or if specifically requested
            if ($is_admin) {
                $output .= '<div class="participant__consents">';
                $output .= '<h5 class="participant__consents-title">Zgody:</h5>';
                $output .= '<p class="participant__consent">- Regulamin: ' . (isset($consents['regulations']) && $consents['regulations'] ? 'Tak' : 'Nie') . '</p>';
                $output .= '<p class="participant__consent">- Marketing email: ' . (isset($consents['marketing']) && $consents['marketing'] ? 'Tak' : 'Nie') . '</p>';
                $output .= '<p class="participant__consent">- Marketing telefon: ' . (isset($consents['phone']) && $consents['phone'] ? 'Tak' : 'Nie') . '</p>';
                $output .= '<p class="participant__consent">- Profilowanie: ' . (isset($consents['profiling']) && $consents['profiling'] ? 'Tak' : 'Nie') . '</p>';
                $output .= '<p class="participant__consent">- Ubezpieczenie: ' . (isset($consents['insurance']) && $consents['insurance'] ? 'Tak' : 'Nie') . '</p>';
                $output .= '</div>';
            }

            $output .= '</div>'; // Close participant
        }
        
        $output .= '</div>'; // Close order-item__participants
        $output .= '</div>'; // Close order-item

        return $output;
    }

    /**
     * Validate checkout fields
     */
    public function validate_checkout_fields() {
        try {
            $cart = WC()->cart;
            if (!$cart) {
                return;
            }
            
            foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                $product = $cart_item['data'];
                if (!$product || !is_object($product)) {
                    continue;
                }

                $product_name = get_the_title($product->get_parent_id());
                $variation_id = isset($cart_item['variation_id']) ? $cart_item['variation_id'] : '';
                $variation = wc_get_product($variation_id);
                
                if (!$variation) {
                    continue;
                }
                
                $variation_attributes = $variation->get_variation_attributes();
                $variation_name = '';
                
                if (!empty($variation_attributes)) {
                    foreach ($variation_attributes as $attribute => $value) {
                        $taxonomy = str_replace('attribute_', '', $attribute);
                        if ($taxonomy === 'pa_termin') {
                            $term = get_term_by('slug', $value, $taxonomy);
                            $variation_name = $term ? $term->name : $value;
                        }
                    }
                }
                
                // Generate a unique identifier for this product to match checkout-changes.php
                $product_unique_id = sanitize_title($product_name . '-' . $variation_name);
                
                // Get the actual quantity from cart item
                $quantity = isset($cart_item['quantity']) ? intval($cart_item['quantity']) : 0;
                
                // Validate primary participant consents (only regulations is required)
                $prefix = "participant_{$product_unique_id}_0";
                $regulations_field = "{$prefix}_consent_regulations";
                
                if (!isset($_POST[$regulations_field])) {
                    wc_add_notice(__('Pierwszy uczestnik: Proszę zaakceptować regulamin i warunki uczestnictwa', 'sport-adventure'), 'error');
                }

                // Validate additional participants if quantity > 1
                if ($quantity > 1) {
                    for ($i = 1; $i < $quantity; $i++) {
                        $prefix = "participant_{$product_unique_id}_{$i}";
                        $participant_number = $i + 1;
                        
                        // Check required fields
                        $required_fields = [
                            'first_name' => __('Imię', 'sport-adventure'),
                            'last_name' => __('Nazwisko', 'sport-adventure'),
                            'phone' => __('Telefon', 'sport-adventure'),
                            'email' => __('Email', 'sport-adventure')
                        ];

                        foreach ($required_fields as $field => $label) {
                            $field_name = "{$prefix}_{$field}";
                            
                            if (!isset($_POST[$field_name]) || empty(trim($_POST[$field_name]))) {
                                wc_add_notice(
                                    sprintf(__('Uczestnik %d: Pole %s jest wymagane', 'sport-adventure'), 
                                    $participant_number, $label), 
                                    'error'
                                );
                            }
                        }

                        // Check regulations consent
                        $regulations_field = "{$prefix}_consent_regulations";
                        
                        if (!isset($_POST[$regulations_field])) {
                            wc_add_notice(
                                sprintf(__('Uczestnik %d: Proszę zaakceptować regulamin i warunki uczestnictwa', 'sport-adventure'), 
                                $participant_number),
                                'error'
                            );
                        }
                    }
                }
            }
            
        } catch (Exception $e) {
            wc_add_notice($e->getMessage(), 'error');
        }
    }

    /**
     * Customize error messages
     */
    public function customize_error_messages($error) {
        $translations = array(
            'Pierwszy uczestnik musi zaakceptować zgodę regulaminu i warunków uczestnictwa' => 'Pierwszy uczestnik musi zaakceptować regulamin i warunki uczestnictwa',
            'Pierwszy uczestnik musi zaakceptować zgodę marketingową' => 'Pierwszy uczestnik musi zaakceptować zgodę marketingową',
            'Pierwszy uczestnik musi zaakceptować zgodę ubezpieczenia' => 'Pierwszy uczestnik musi zaakceptować zgodę ubezpieczenia',
            'Pole %s jest wymagane dla uczestnika %d' => 'Pole %s jest wymagane dla uczestnika %d',
            'Nieprawidłowy format adresu email dla uczestnika %d' => 'Nieprawidłowy format adresu email dla uczestnika %d',
            'Nieprawidłowy format numeru telefonu dla uczestnika %d' => 'Nieprawidłowy format numeru telefonu dla uczestnika %d',
            'Uczestnik %d musi zaakceptować zgodę %s' => 'Uczestnik %d musi zaakceptować zgodę %s',
            'Wystąpił błąd podczas walidacji danych uczestników.' => 'Wystąpił błąd podczas walidacji danych uczestników.',
            // WooCommerce default messages
            'Billing First name is a required field.' => 'Pole Imię jest wymagane.',
            'Billing Last name is a required field.' => 'Pole Nazwisko jest wymagane.',
            'Billing Phone is a required field.' => 'Pole Telefon jest wymagane.',
            'Billing Email address is a required field.' => 'Pole Email jest wymagane.',
            'Please read and accept the terms and conditions to proceed with your order.' => 'Proszę zaakceptować wszystkie wymagane zgody.'
        );

        foreach ($translations as $en => $pl) {
            if (strpos($error, $en) !== false) {
                return str_replace($en, $pl, $error);
            }
        }

        return $error;
    }

    /**
     * Render order confirmation page
     */
    public function render_order_confirmation($atts) {
        // Get order ID from URL
        $order_id = absint(get_query_var('order-received'));
        if (!$order_id) return '';

        // Get order
        $order = wc_get_order($order_id);
        if (!$order) return '';

        // Check currencies used in order items
        $currencies_needed = array();
        foreach ($order->get_items() as $item) {
            $product = $item->get_product();
            if ($product && $product->is_type('variation')) {
                $currency = get_field('wyprawa-termin__waluta', $product->get_id());
                if ($currency) {
                    $currencies_needed[] = strtoupper(trim($currency));
                }
            }
        }
        $currencies_needed = array_unique($currencies_needed);

        ob_start();
        ?>
        <div class="sa-order-confirmation">
            <div class="order-success-message">
                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                <div class="order-success-content">
                    <h2>Dziękujemy! Twoje zamówienie zostało przyjęte.</h2>
                    <p>Na Twój adres email wysłaliśmy potwierdzenie zamówienia.</p>
                </div>
            </div>

            <?php if ($order->get_payment_method() === 'bacs'): ?>
            <div class="order-bank-details">
                <h3>Numery kont do płatności</h3>
                
                <?php 
                // Always show PLN account
                $pln_account = get_field('numer-konta__pln', 'option');
                if ($pln_account): 
                ?>
                <div class="bank-account-section">
                    <p class="bank-account-heading">Opłat w złotówkach należy dokonać przelewem na rachunek bankowy:</p>
                    <div class="bank-account-number"><?php echo esc_html($pln_account); ?></div>
                </div>
                <?php endif; ?>

                <?php 
                // Show USD account only if needed
                if (in_array('USD', $currencies_needed)):
                    $usd_account = get_field('numer-konta__usd', 'option');
                    if ($usd_account):
                ?>
                <div class="bank-account-section">
                    <p class="bank-account-heading">Opłat w dolarach amerykańskich należy dokonać przelewem na rachunek bankowy:</p>
                    <div class="bank-account-number"><?php echo esc_html($usd_account); ?></div>
                </div>
                <?php endif; endif; ?>

                <div class="bank-detail">
                    <span class="bank-detail__label">Tytuł przelewu:</span>
                    <span class="bank-detail__value"><?php echo esc_html($this->format_transfer_title($order, reset($order->get_items()))); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <div class="order-details">
                <h2 class="order-details__title">Szczegóły zamówienia</h2>
                
                <?php foreach ($order->get_items() as $item_id => $item): ?>
                    <?php echo $this->get_formatted_participant_data($item); ?>
                <?php endforeach; ?>

                <div class="order-summary">
                    <h3 class="order-summary__title">Podsumowanie zamówienia</h3>
                    <div class="order-summary__grid">
                        <div class="order-summary__detail">
                            <span class="order-summary__label">Numer zamówienia:</span>
                            <span class="order-summary__value"><?php echo $order->get_order_number(); ?></span>
                        </div>
                        <div class="order-summary__detail">
                            <span class="order-summary__label">Data:</span>
                            <span class="order-summary__value"><?php echo $order->get_date_created()->date_i18n('d.m.Y'); ?></span>
                        </div>
                        <div class="order-summary__detail">
                            <span class="order-summary__label">Metoda płatności:</span>
                            <span class="order-summary__value"><?php echo $order->get_payment_method_title(); ?></span>
                        </div>
                        <div class="order-summary__detail">
                            <span class="order-summary__label">Email:</span>
                            <span class="order-summary__value"><?php echo $order->get_billing_email(); ?></span>
                        </div>
                        <div class="order-summary__detail">
                            <span class="order-summary__label">Telefon:</span>
                            <span class="order-summary__value"><?php echo $order->get_billing_phone(); ?></span>
                        </div>
                        <div class="order-summary__detail order-summary__detail--total">
                            <span class="order-summary__label">Łączna zaliczka do zapłaty:</span>
                            <span class="order-summary__value"><?php echo $order->get_formatted_order_total(); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Format transfer title
     */
    private function format_transfer_title($order, $item) {
        $product = $item->get_product();
        $product_name = get_the_title($product->get_parent_id());
        
        // Get variation attributes
        $variation_attributes = $product->get_variation_attributes();
        $termin = isset($variation_attributes['attribute_pa_termin']) ? $variation_attributes['attribute_pa_termin'] : '';
        
        // Get billing name
        $billing_name = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
        
        // Format the date and create title
        if ($termin) {
            $formatted_date = preg_replace('/(\d{2})-(\d{2})-(\d{4})-(\d{2})-(\d{2})-(\d{4})/', '$1.$2.$3 - $4.$5.$6', $termin);
            return $product_name . ' - ' . $formatted_date . ' - ' . $billing_name;
        }
        
        return $product_name . ' - ' . $billing_name;
    }

    /**
     * Display bank account details in emails
     */
    public function display_email_bank_details($order, $sent_to_admin, $plain_text, $email) {
        if ($order->get_payment_method() !== 'bacs') return;

        // Get currencies needed for this order
        $currencies_needed = array();
        foreach ($order->get_items() as $item) {
            $product = $item->get_product();
            if ($product && $product->is_type('variation')) {
                $currency = get_field('wyprawa-termin__waluta', $product->get_id());
                if ($currency) {
                    $currencies_needed[] = strtoupper(trim($currency));
                }
            }
        }
        $currencies_needed = array_unique($currencies_needed);

        echo '<h2>Dane do przelewu</h2>';
        
        // Always show PLN account
        $pln_account = get_field('numer-konta__pln', 'option');
        if ($pln_account) {
            echo '<p><strong>Rachunek bankowy do opłat w złotówkach:</strong><br>';
            echo esc_html($pln_account) . '</p>';
        }

        // Show USD account only if needed
        if (in_array('USD', $currencies_needed)) {
            $usd_account = get_field('numer-konta__usd', 'option');
            if ($usd_account) {
                echo '<p><strong>Rachunek bankowy do opłat w dolarach amerykańskich:</strong><br>';
                echo esc_html($usd_account) . '</p>';
            }
        }

        // Get first item for transfer title
        $items = $order->get_items();
        $first_item = reset($items);
        if ($first_item) {
            echo '<p><strong>Tytuł przelewu:</strong> ' . esc_html($this->format_transfer_title($order, $first_item)) . '</p>';
        }
    }

    /**
     * Format participant data for email display
     */
    public function get_formatted_email_participant_data($item) {
        $output = '';
        $item_id = $item->get_id();
        $quantity = $item->get_quantity();
        
        if ($quantity > 0) {
            $output .= '<div class="order-item__participants">';
            $output .= '<h4 class="order-item__section-title">' . esc_html__('Dane uczestników', 'sport-adventure') . '</h4>';
            
            for ($i = 1; $i <= $quantity; $i++) {
                $participant = $item->get_meta('_participant_' . $i);
                if (!$participant || !is_array($participant) || !isset($participant['data'])) continue;
                
                $data = $participant['data'];
                
                $output .= '<div class="participant">';
                $output .= '<h4 class="participant__title">' . sprintf(esc_html__('Uczestnik %d', 'sport-adventure'), $i) . '</h4>';
                $output .= '<div class="participant__details">';
                
                if (isset($data['first_name'])) {
                    $output .= '<p class="participant__field"><span class="participant__label">' . esc_html__('Imię:', 'sport-adventure') . '</span> ' . esc_html($data['first_name']) . '</p>';
                }
                
                if (isset($data['last_name'])) {
                    $output .= '<p class="participant__field"><span class="participant__label">' . esc_html__('Nazwisko:', 'sport-adventure') . '</span> ' . esc_html($data['last_name']) . '</p>';
                }
                
                if (isset($data['phone'])) {
                    $output .= '<p class="participant__field"><span class="participant__label">' . esc_html__('Telefon:', 'sport-adventure') . '</span> ' . esc_html($data['phone']) . '</p>';
                }
                
                if (isset($data['email'])) {
                    $output .= '<p class="participant__field"><span class="participant__label">' . esc_html__('Email:', 'sport-adventure') . '</span> ' . esc_html($data['email']) . '</p>';
                }
                
                $output .= '</div>';
                $output .= '</div>';
            }
            
            $output .= '</div>';
        }
        
        return $output;
    }
}

function sa_participant_handler() {
    return SA_Participant_Handler::get_instance();
}

// Initialize the handler
add_action('init', 'sa_participant_handler');

// Add coupon display to order confirmation
add_action('woocommerce_order_details_after_order_table', 'display_coupon_information', 9);
function display_coupon_information($order) {
    $coupons = $order->get_coupon_codes();
    if (empty($coupons)) return;
    
    echo '<div class="order-coupon-information">';
    echo '<h3>Zastosowane kody rabatowe:</h3>';
    echo '<ul class="order-coupon-list">';
    
    foreach ($coupons as $coupon_code) {
        $coupon = new WC_Coupon($coupon_code);
        $discount = $coupon->get_amount();
        $discount_type = $coupon->get_discount_type();
        
        echo '<li class="order-coupon-item">';
        echo '<span class="coupon-code">' . esc_html($coupon_code) . '</span>';
        if ($discount_type === 'percent') {
            echo ' - ' . esc_html($discount) . '% zniżki';
        } else {
            echo ' - ' . wc_price($discount) . ' zniżki';
        }
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
    
    // Add styles for coupon display
    ?>
    <style>
        .order-coupon-information {
            margin: 20px 0;
            padding: 15px;
            background: #f8f8f8;
            border-radius: 4px;
        }
        .order-coupon-information h3 {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .order-coupon-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .order-coupon-item {
            margin-bottom: 5px;
        }
        .coupon-code {
            font-weight: bold;
            background: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            border: 1px solid #ddd;
        }
    </style>
    <?php
} 