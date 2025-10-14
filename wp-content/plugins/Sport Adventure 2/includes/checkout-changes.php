<?php
// Add AJAX actions
add_action('wp_enqueue_scripts', 'add_checkout_data');
add_action('wp_ajax_update_cart_quantity', 'handle_update_cart_quantity');
add_action('wp_ajax_nopriv_update_cart_quantity', 'handle_update_cart_quantity');

// Add filters for checkout order review
add_filter('woocommerce_cart_item_subtotal', 'custom_review_order_item_subtotal', 100, 3);
add_filter('woocommerce_cart_subtotal', 'custom_review_order_subtotal', 100, 3);
add_filter('woocommerce_order_formatted_line_subtotal', 'custom_review_order_line_subtotal', 100, 3);
add_filter('woocommerce_order_item_subtotal', 'custom_review_order_item_subtotal', 100, 3);

// Add heading before billing fields
add_action('woocommerce_before_checkout_billing_form', 'add_billing_heading');
function add_billing_heading() {
    echo '<h2 class="text--l margin-bottom--m">Dane uczestnika wyprawy</h2>';
}

// Ensure the form element is present
add_action('woocommerce_before_checkout_form', 'ensure_checkout_form_wrapper', 5);
function ensure_checkout_form_wrapper($checkout) {
    // Remove default form tags if they exist
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
    // Don't remove the coupon form anymore
    
    // Don't output form tag - let WooCommerce handle it
    return $checkout;
}

// Customize the coupon form
add_filter('woocommerce_checkout_coupon_message', 'custom_coupon_message');
function custom_coupon_message() {
    return ''; // Return empty string to hide default message
}

// Remove form closing action since we're not adding the form
remove_action('woocommerce_after_checkout_form', 'close_checkout_form_wrapper', 20);

// Move the submit button inside the form
add_action('woocommerce_checkout_order_review', 'reposition_checkout_button', 15);
function reposition_checkout_button() {
    // Remove the default button
    remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
    
    // Add our repositioned button
    add_action('woocommerce_checkout_after_order_review', 'woocommerce_checkout_payment', 20);
}

// Add custom coupon section before payment methods
add_action('woocommerce_review_order_before_payment', 'add_custom_coupon_section');
function add_custom_coupon_section() {
    $applied_coupons = WC()->cart->get_applied_coupons();
    ?>
    <div class="custom-coupon-section" id="custom-coupon-section">
        <?php if (empty($applied_coupons)): ?>
            <button type="button" class="coupon-toggle text--m">Masz kod rabatowy?</button>
            <div class="coupon-form-wrapper">
                <p class="form-row">
                    <label for="custom-coupon-code">Kod rabatowy</label>
                    <span class="woocommerce-input-wrapper">
                        <input type="text" name="custom-coupon-code" id="custom-coupon-code" placeholder="Wpisz kod rabatowy" />
                    </span>
                </p>
                <button type="button" class="button apply-coupon">Zastosuj kod</button>
            </div>
        <?php else: ?>
            <div class="applied-coupons">
                <h4>Zastosowane kody rabatowe:</h4>
                <ul>
                    <?php foreach ($applied_coupons as $coupon_code): ?>
                        <li>
                            <span class="coupon-code"><?php echo esc_html($coupon_code); ?></span>
                            <a href="#" class="remove-coupon" data-coupon="<?php echo esc_attr($coupon_code); ?>">
                                Usuń
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

// Remove all default coupon hooks and messages
add_action('init', 'remove_wc_coupon_hooks');
function remove_wc_coupon_hooks() {
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form');
    remove_action('woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form');
    remove_action('woocommerce_before_checkout_form', array('WC_Checkout', 'checkout_coupon_form'), 10);
    remove_action('woocommerce_after_checkout_form', array('WC_Checkout', 'checkout_coupon_form'));
}

// Disable default coupon form
add_filter('woocommerce_enable_order_notes_field', '__return_false');
add_filter('woocommerce_checkout_show_coupon', '__return_false');

// Remove coupon notice
add_filter('woocommerce_checkout_coupon_message', '__return_empty_string');

// Add custom coupon section to fragments
add_filter('woocommerce_update_order_review_fragments', 'add_custom_coupon_section_fragment');
function add_custom_coupon_section_fragment($fragments) {
    ob_start();
    add_custom_coupon_section();
    $fragments['#custom-coupon-section'] = ob_get_clean();
    return $fragments;
}

// Update the JavaScript validation code
function update_checkout_validation_script() {
    // Only load script and data on checkout page
    if (!is_checkout()) {
        return;
    }

    // Add checkout data for the script
    wp_localize_script('sport-adventure-checkout', 'checkout_data', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'update_order_review_nonce' => wp_create_nonce('update-order-review'),
        'is_checkout' => true // Add flag to indicate we're on checkout page
    ));
}

// Ensure our script runs after WooCommerce's scripts
add_action('wp_footer', 'update_checkout_validation_script', 99);

// Add checkout data for JavaScript and ensure WooCommerce scripts are loaded
function add_checkout_data() {
    // Only load script and data on checkout page
    if (!is_checkout()) {
        return;
    }

    // Ensure WooCommerce checkout script is loaded
    wp_enqueue_script('wc-checkout');
    
    // Add our data
    wp_localize_script('sport-adventure-checkout', 'checkout_data', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'update_order_review_nonce' => wp_create_nonce('update-order-review'),
        'is_checkout' => true // Add flag to indicate we're on checkout page
    ));
}

// Customize checkout fields
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
function custom_override_checkout_fields($fields) {
    // Remove default billing fields we don't need
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);

    // Customize remaining fields
    $fields['billing']['billing_first_name']['label'] = 'Imię';
    $fields['billing']['billing_first_name']['priority'] = 10;

    $fields['billing']['billing_last_name']['label'] = 'Nazwisko';
    $fields['billing']['billing_last_name']['priority'] = 20;

    $fields['billing']['billing_phone']['label'] = 'Numer telefonu';
    $fields['billing']['billing_phone']['priority'] = 30;

    $fields['billing']['billing_email']['label'] = 'Email';
    $fields['billing']['billing_email']['priority'] = 40;

    return $fields;
}

// Handle AJAX quantity updates
function handle_update_cart_quantity() {
    check_ajax_referer('update-order-review', 'security');

    if (!isset($_POST['cart_item_key']) || !isset($_POST['quantity'])) {
        wp_send_json_error('Missing required parameters');
        return;
    }

    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $quantity = (int) $_POST['quantity'];
    
    if (sa_is_php_debug_enabled()) {
        error_log('[SA Debug] Starting quantity update. Cart key: ' . $cart_item_key . ', New quantity: ' . $quantity);
    }
    
    // Get cart instance
    $cart = WC()->cart;
    
    // Validate cart item exists
    $cart_item = $cart->get_cart_item($cart_item_key);
    if (!$cart_item) {
        wp_send_json_error('Invalid cart item');
        return;
    }
    
    try {
        // Update cart quantity
        $cart->set_quantity($cart_item_key, $quantity, true);
        
        // Force cart calculations
        $cart->calculate_totals();
        
        // Get updated fragments
        $fragments = array();

        // Update the entire quantity fields section
        ob_start();
        add_quantity_fields_section(WC()->checkout());
        $fragments['.quantity-fields-section'] = ob_get_clean();
        if (sa_is_php_debug_enabled()) {
            error_log('[SA Debug] Generated quantity fields section fragment');
        }

        // Update the order review section
        ob_start();
        custom_order_review_template();
        $fragments['.woocommerce-checkout-review-order-table'] = ob_get_clean();
        if (sa_is_php_debug_enabled()) {
            error_log('[SA Debug] Generated order review fragment');
        }

        // Update coupon section if exists
        ob_start();
        add_custom_coupon_section();
        $fragments['#custom-coupon-section'] = ob_get_clean();
        if (sa_is_php_debug_enabled()) {
            error_log('[SA Debug] Generated coupon section fragment');
        }

        if (sa_is_php_debug_enabled()) {
            error_log('[SA Debug] Sending response with ' . count($fragments) . ' fragments');
        }
        
        wp_send_json_success(array(
            'fragments' => $fragments,
            'cart_hash' => $cart->get_cart_hash(),
            'debug_info' => array(
                'cart_key' => $cart_item_key,
                'new_quantity' => $quantity,
                'fragment_keys' => array_keys($fragments)
            )
        ));
        
    } catch (Exception $e) {
        if (sa_is_php_debug_enabled()) {
            error_log('[SA Debug] Error updating cart: ' . $e->getMessage());
        }
        wp_send_json_error('Error updating cart: ' . $e->getMessage());
    }
}

// Save custom fields to order item meta
function save_cart_item_custom_meta($item, $cart_item_key, $values, $order) {
    if (empty($values['variation_id'])) return;
    
    $calculator = sa_cart_calculator();
    
    // Save original deposit before any discounts
    $original_deposit = $calculator->get_item_original_deposit($values);
    if ($original_deposit > 0) {
        $item->add_meta_data('_original_deposit', $original_deposit);
    }
    
    // Save deposit components
    $deposit_components = sa_deposit_handler()->get_price_components($values['variation_id'], $values['quantity']);
    if ($deposit_components) {
        $item->add_meta_data('_deposit_components', $deposit_components);
    }
    
    // Save remaining payments
    $remaining_payments = $calculator->get_item_remaining_payments($values);
    if (!empty($remaining_payments)) {
        $item->add_meta_data('_remaining_payments', $remaining_payments);
    }
}
add_action('woocommerce_checkout_create_order_line_item', 'save_cart_item_custom_meta', 10, 4);

// Add quantity field section after billing fields
add_action('woocommerce_after_checkout_billing_form', 'add_quantity_fields_section');
function add_quantity_fields_section($checkout) {
    echo '<div class="quantity-fields-section">';
    
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        $quantity = $cart_item['quantity'];
        $stored_fields = isset($cart_item['quantity_custom_fields']) ? $cart_item['quantity_custom_fields'] : array();

        // Get product name and variation attributes
        $product_name = get_the_title($product->get_parent_id());
        $variation_id = $cart_item['variation_id'];
        $variation = new WC_Product_Variation($variation_id);
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

        // Generate a unique identifier for this product
        $product_unique_id = sanitize_title($product_name . '-' . $variation_name);

        // Add product title section
        echo '<div class="product-section">';
        echo '<div class="product-header">';
        echo '<h2 class="product-title">' . esc_html($product_name) . '</h2>';
        if ($variation_name) {
            echo '<div class="product-variant">' . esc_html($variation_name) . '</div>';
        }
        echo '</div>';

        // Move quantity control to top
        echo '<div class="custom-fields-wrapper" data-cart-key="' . esc_attr($cart_item_key) . '" data-product-id="' . esc_attr($product_unique_id) . '">';
        echo '<div class="quantity-control">';
        echo '<span class="quantity-label">Liczba uczestników: </span>';
        echo '<div class="quantity-input-group">';
        echo '<button type="button" class="quantity-minus" tabindex="0" role="button" aria-label="Zmniejsz ilość"></button>';
        echo '<input type="number" class="quantity-number" value="' . esc_attr($quantity) . '" min="1" max="99" readonly aria-label="Ilość uczestników">';
        echo '<button type="button" class="quantity-plus" tabindex="0" role="button" aria-label="Zwiększ ilość"></button>';
        echo '</div>';
        echo '</div>';

        // Add participant info right after quantity control
        echo '<p class="participant-info">Dane pierwszego uczestnika zostaną pobrane z pól rozliczeniowych powyżej.</p>';

        // Add consent checkboxes for primary user (first participant)
        echo '<div class="primary-participant-consents">';
        echo '<h4>Zgody pierwszego uczestnika</h4>';
        
        // Add "Select all" checkbox for primary participant with dropdown
        echo '<div class="checkbox-wrapper select-all-wrapper">';
        echo '<div class="select-all-header">';
        echo '<label class="checkbox">';
        echo '<input type="checkbox" class="select-all-consents" data-participant-index="0" data-product-id="' . $product_unique_id . '">';
        echo '<span>Zaznacz wszystkie zgody</span>';
        echo '</label>';
        echo '<span class="consent-dropdown-toggle" data-target="primary-consents-' . $product_unique_id . '">Rozwiń <span class="dropdown-arrow">▼</span></span>';
        echo '</div>';
        echo '</div>';
        
        // Add collapsible consent section
        echo '<div class="consent-dropdown-content" id="primary-consents-' . $product_unique_id . '">';
        
        $regulation_url = get_product_regulation_url($product->get_parent_id());
        
        echo '<div class="checkbox-wrapper">';
        echo '<label class="checkbox">';
        echo '<input type="checkbox" name="participant_' . $product_unique_id . '_0_consent_regulations" id="participant_' . $product_unique_id . '_0_consent_regulations" value="1" class="input-checkbox">';
        echo '<span> Zapoznałem/am się i akceptuję <a href="' . esc_url(get_field('wyprawa__regulamin_wyprawy', $product->get_parent_id())) . '" target="_blank">Regulamin wyprawy na ' . esc_html($product_name) . '</a>, <a href="/wp-content/uploads/sa-warunki-uczestnictwa-w-imprezach-turystycznych-2024.pdf" target="_blank">Warunki Uczestnictwa</a> oraz <a href="/wp-content/uploads/sa-polityka-prywatnosci.pdf" target="_blank">Politykę Prywatności</a>.</span>';
        echo '<abbr class="required" title="wymagane">*</abbr></span>';
        echo '</label>';
        echo '</div>';

        echo '<div class="checkbox-wrapper">';
        echo '<label class="checkbox">';
        echo '<input type="checkbox" name="participant_' . $product_unique_id . '_0_consent_marketing" id="participant_' . $product_unique_id . '_0_consent_marketing" value="1" class="input-checkbox">';
        echo '<span> Wyrażam zgodę na otrzymywanie informacji handlowych drogą elektroniczną (e-mail) od Sport Adventure sp. z o.o. (dobrowolna)</span>';
        echo '</label>';
        echo '</div>';

        echo '<div class="checkbox-wrapper">';
        echo '<label class="checkbox">';
        echo '<input type="checkbox" name="participant_' . $product_unique_id . '_0_consent_phone" id="participant_' . $product_unique_id . '_0_consent_phone" value="1" class="input-checkbox">';
        echo '<span> Wyrażam zgodę na kontakt telefoniczny w celach marketingowych przez Sport Adventure sp. z o.o. (dobrowolna)</span>';
        echo '</label>';
        echo '</div>';

        echo '<div class="checkbox-wrapper">';
        echo '<label class="checkbox">';
        echo '<input type="checkbox" name="participant_' . $product_unique_id . '_0_consent_profiling" id="participant_' . $product_unique_id . '_0_consent_profiling" value="1" class="input-checkbox">';
        echo '<span> Wyrażam zgodę na profilowanie moich danych osobowych przez Sport Adventure sp. z o.o. w celu przesyłania mi spersonalizowanych ofert oraz treści marketingowych dopasowanych do moich zainteresowań i preferencji. (dobrowolna)</span>';
        echo '</label>';
        echo '</div>';

        echo '<div class="checkbox-wrapper">';
        echo '<label class="checkbox">';
        echo '<input type="checkbox" name="participant_' . $product_unique_id . '_0_consent_insurance" id="participant_' . $product_unique_id . '_0_consent_insurance" value="1" class="input-checkbox">';
        echo '<span> Jestem zainteresowany/a otrzymaniem informacji o ubezpieczeniu od kosztów rezygnacji. Proszę o kontakt w tej sprawie. (dobrowolna)</span>';
        echo '</label>';
        echo '</div>';
        
        echo '</div>'; // Close consent-dropdown-content
        echo '</div>'; // Close primary-participant-consents

        // Rest of the participant fields
        if ($quantity > 1) {
            echo '<div class="quantity-fields-container">';
            // Start from 1 since index 0 is the billing person
            for ($i = 1; $i < $quantity; $i++) {
                $stored_first_name = isset($stored_fields[$i]['first_name']) ? esc_attr($stored_fields[$i]['first_name']) : '';
                $stored_last_name = isset($stored_fields[$i]['last_name']) ? esc_attr($stored_fields[$i]['last_name']) : '';
                $stored_phone = isset($stored_fields[$i]['phone']) ? esc_attr($stored_fields[$i]['phone']) : '';
                $stored_email = isset($stored_fields[$i]['email']) ? esc_attr($stored_fields[$i]['email']) : '';
                
                echo '<div class="quantity-fields-group woocommerce-billing-fields__field-wrapper">';
                echo '<div class="quantity-person-number">Uczestnik ' . ($i + 1) . '</div>';
                
                // First name field
                echo '<p class="form-row form-row-first validate-required" id="participant_' . $product_unique_id . '_' . $i . '_first_name_field" data-priority="10">';
                echo '<label for="participant_' . $product_unique_id . '_' . $i . '_first_name">Imię<abbr class="required" title="wymagane">*</abbr></label>';
                echo '<span class="woocommerce-input-wrapper">';
                echo '<input type="text" class="input-text custom-field first-name" name="participant_' . $product_unique_id . '_' . $i . '_first_name" id="participant_' . $product_unique_id . '_' . $i . '_first_name" placeholder="" value="' . $stored_first_name . '" data-qty-index="' . $i . '" data-product-id="' . $product_unique_id . '" aria-required="true" autocomplete="given-name">';
                echo '</span>';
                echo '</p>';
                
                // Last name field
                echo '<p class="form-row form-row-last validate-required" id="participant_' . $product_unique_id . '_' . $i . '_last_name_field" data-priority="20">';
                echo '<label for="participant_' . $product_unique_id . '_' . $i . '_last_name">Nazwisko<abbr class="required" title="wymagane">*</abbr></label>';
                echo '<span class="woocommerce-input-wrapper">';
                echo '<input type="text" class="input-text custom-field last-name" name="participant_' . $product_unique_id . '_' . $i . '_last_name" id="participant_' . $product_unique_id . '_' . $i . '_last_name" placeholder="" value="' . $stored_last_name . '" data-qty-index="' . $i . '" data-product-id="' . $product_unique_id . '" aria-required="true" autocomplete="family-name">';
                echo '</span>';
                echo '</p>';
                
                // Phone field
                echo '<p class="form-row form-row-wide validate-required validate-phone" id="participant_' . $product_unique_id . '_' . $i . '_phone_field" data-priority="30">';
                echo '<label for="participant_' . $product_unique_id . '_' . $i . '_phone">Numer telefonu<abbr class="required" title="wymagane">*</abbr></label>';
                echo '<span class="woocommerce-input-wrapper">';
                echo '<input type="tel" class="input-text custom-field phone" name="participant_' . $product_unique_id . '_' . $i . '_phone" id="participant_' . $product_unique_id . '_' . $i . '_phone" placeholder="" value="' . $stored_phone . '" data-qty-index="' . $i . '" data-product-id="' . $product_unique_id . '" aria-required="true" autocomplete="tel">';
                echo '</span>';
                echo '</p>';
                
                // Email field
                echo '<p class="form-row form-row-wide validate-required validate-email" id="participant_' . $product_unique_id . '_' . $i . '_email_field" data-priority="40">';
                echo '<label for="participant_' . $product_unique_id . '_' . $i . '_email">Email<abbr class="required" title="wymagane">*</abbr></label>';
                echo '<span class="woocommerce-input-wrapper">';
                echo '<input type="email" class="input-text custom-field email" name="participant_' . $product_unique_id . '_' . $i . '_email" id="participant_' . $product_unique_id . '_' . $i . '_email" placeholder="" value="' . $stored_email . '" data-qty-index="' . $i . '" data-product-id="' . $product_unique_id . '" aria-required="true" autocomplete="email">';
                echo '</span>';
                echo '</p>';

                // Add consent checkboxes for each participant
                echo '<div class="participant-consents">';
                echo '<h4>Zgody uczestnika ' . ($i + 1) . '</h4>';
                
                // Add "Select all" checkbox for additional participant with dropdown
                echo '<div class="checkbox-wrapper select-all-wrapper">';
                echo '<div class="select-all-header">';
                echo '<label class="checkbox">';
                echo '<input type="checkbox" class="select-all-consents" data-participant-index="' . $i . '" data-product-id="' . $product_unique_id . '">';
                echo '<span>Zaznacz wszystkie zgody</span>';
                echo '</label>';
                echo '<span class="consent-dropdown-toggle" data-target="participant-consents-' . $product_unique_id . '-' . $i . '">Rozwiń <span class="dropdown-arrow">▼</span></span>';
                echo '</div>';
                echo '</div>';
                
                // Add collapsible consent section
                echo '<div class="consent-dropdown-content" id="participant-consents-' . $product_unique_id . '-' . $i . '">';
                
                echo '<div class="checkbox-wrapper">';
                echo '<label class="checkbox">';
                echo '<input type="checkbox" name="participant_' . $product_unique_id . '_' . $i . '_consent_regulations" id="participant_' . $product_unique_id . '_' . $i . '_consent_regulations" value="1" class="input-checkbox">';
                echo '<span> Zapoznałem/am się i akceptuję <a href="' . esc_url(get_field('wyprawa__regulamin_wyprawy', $product->get_parent_id())) . '" target="_blank">Regulamin wyprawy na ' . esc_html($product_name) . '</a>, <a href="/wp-content/uploads/sa-warunki-uczestnictwa-w-imprezach-turystycznych-2024.pdf" target="_blank">Warunki Uczestnictwa</a> oraz <a href="/wp-content/uploads/sa-polityka-prywatnosci.pdf" target="_blank">Politykę Prywatności</a>.</span>';
                echo '</label>';
                echo '</div>';
                
                echo '<div class="checkbox-wrapper">';
                echo '<label class="checkbox">';
                echo '<input type="checkbox" name="participant_' . $product_unique_id . '_' . $i . '_consent_marketing" id="participant_' . $product_unique_id . '_' . $i . '_consent_marketing" value="1" class="input-checkbox">';
                echo '<span> Wyrażam zgodę na otrzymywanie informacji handlowych drogą elektroniczną (e-mail) od Sport Adventure sp. z o.o. (dobrowolna)</span>';
                echo '</label>';
                echo '</div>';

                echo '<div class="checkbox-wrapper">';
                echo '<label class="checkbox">';
                echo '<input type="checkbox" name="participant_' . $product_unique_id . '_' . $i . '_consent_phone" id="participant_' . $product_unique_id . '_' . $i . '_consent_phone" value="1" class="input-checkbox">';
                echo '<span> Wyrażam zgodę na kontakt telefoniczny w celach marketingowych przez Sport Adventure sp. z o.o. (dobrowolna)</span>';
                echo '</label>';
                echo '</div>';

                echo '<div class="checkbox-wrapper">';
                echo '<label class="checkbox">';
                echo '<input type="checkbox" name="participant_' . $product_unique_id . '_' . $i . '_consent_profiling" id="participant_' . $product_unique_id . '_' . $i . '_consent_profiling" value="1" class="input-checkbox">';
                echo '<span> Wyrażam zgodę na profilowanie moich danych osobowych przez Sport Adventure sp. z o.o. w celu przesyłania mi spersonalizowanych ofert oraz treści marketingowych dopasowanych do moich zainteresowań i preferencji. (dobrowolna)</span>';
                echo '</label>';
                echo '</div>';

                echo '<div class="checkbox-wrapper">';
                echo '<label class="checkbox">';
                echo '<input type="checkbox" name="participant_' . $product_unique_id . '_' . $i . '_consent_insurance" id="participant_' . $product_unique_id . '_' . $i . '_consent_insurance" value="1" class="input-checkbox">';
                echo '<span> Jestem zainteresowany/a otrzymaniem informacji o ubezpieczeniu od kosztów rezygnacji. Proszę o kontakt w tej sprawie. (dobrowolna)</span>';
                echo '</label>';
                echo '</div>';
                
                echo '</div>'; // Close consent-dropdown-content
                echo '</div>'; // Close participant-consents
                
                echo '</div>';
            }
            echo '</div>';
        }
        echo '</div>'; // Close custom-fields-wrapper
        echo '</div>'; // Close product-section
    }
    
    echo '</div>'; // Close quantity-fields-section
}

// Remove the original cart item name modification
remove_filter('woocommerce_cart_item_name', 'add_custom_fields_to_cart_items', 10);

// Add remove button to checkout review order table
add_filter('woocommerce_cart_item_name', 'add_remove_button_to_review_order', 10, 3);
function add_remove_button_to_review_order($product_name, $cart_item, $cart_item_key) {
    if (is_checkout()) {
        // Only show remove button if there's more than one item in cart
        if (count(WC()->cart->get_cart()) > 1) {
            $remove_url = wc_get_cart_remove_url($cart_item_key);
            // Return only our HTML without any extra spaces
            return sprintf(
                '<div class="checkout-item-name"><h3>%s</h3><div class="variation-attributes"><div class="variation-attribute"><span class="variation-label">%s</span><span class="variation-value">%s</span></div></div><a href="%s" class="remove-product" data-cart-key="%s" aria-label="%s">%s</a></div>',
                esc_html(get_the_title($cart_item['product_id'])),
                esc_html__('Termin', 'woocommerce'),
                esc_html($cart_item['variation']['attribute_pa_termin']),
                esc_url($remove_url),
                esc_attr($cart_item_key),
                esc_attr__('Usuń ten produkt', 'woocommerce'),
                esc_html__('Usuń', 'woocommerce')
            );
        }
    }
    return $product_name;
}

// Add filter for initial product name display in checkout
add_filter('woocommerce_cart_item_name', 'sa_checkout_modify_review_order_product_name', 10, 3);

// Remove default variation display
add_filter('woocommerce_get_item_data', 'remove_variation_from_checkout', 10, 2);
function remove_variation_from_checkout($item_data, $cart_item) {
    if (is_checkout()) {
        return array();
    }
    return $item_data;
}

// Remove default variation display from order review
add_filter('woocommerce_display_item_meta', 'remove_variation_from_order_review', 10, 3);
function remove_variation_from_order_review($html, $item, $args) {
    if (is_checkout()) {
        return '';
    }
    return $html;
}

// Modify review order product name
function sa_checkout_modify_review_order_product_name($name, $cart_item, $cart_item_key) {
    if (!is_checkout()) {
        return $name;
    }

    $product = $cart_item['data'];
    if (!$product || !is_object($product) || !method_exists($product, 'get_parent_id')) {
        return $name;
    }

    $parent_id = $product->get_parent_id();
    if (!$parent_id) {
        return $name;
    }

    $html = '<div class="checkout-item-name">';
    $html .= '<h3>' . esc_html(get_the_title($parent_id)) . '</h3>';
    
    // Add variation attributes
    $variation_attributes = $product->get_variation_attributes();
    if (!empty($variation_attributes)) {
        foreach ($variation_attributes as $attribute => $value) {
            $taxonomy = str_replace('attribute_', '', $attribute);
            
            // Get proper label based on attribute type
            if ($taxonomy === 'pa_termin') {
                $label = 'Termin';
            } elseif ($taxonomy === 'pa_wersja-zakwaterowania') {
                $label = 'Zakwaterowanie';
            } elseif ($taxonomy === 'pa_wersja-wyzywienia') {
                $label = 'Wyżywienie';
            } elseif ($taxonomy === 'pa_dla-kogo') {
                $label = 'Dla kogo';
            } else {
                // For any other attributes, get the proper label from WooCommerce
                $taxonomy_obj = get_taxonomy($taxonomy);
                $label = $taxonomy_obj ? $taxonomy_obj->labels->singular_name : wc_attribute_label($taxonomy, $product);
            }
            
            // Format the value
            if ($taxonomy === 'pa_termin') {
                $term = get_term_by('slug', $value, $taxonomy);
                $formatted_value = $term ? $term->name : $value;
            } else if (taxonomy_exists($taxonomy)) {
                $term = get_term_by('slug', $value, $taxonomy);
                $formatted_value = $term ? $term->name : $value;
            } else {
                $formatted_value = $value;
            }
            
            $html .= '<div class="variation-line">';
            $html .= esc_html($label) . ': ' . esc_html($formatted_value);
            $html .= '</div>';
        }
    }
    
    // Add fixed price guarantee element
    $html .= '<div class="price-warranty text--s text--600 margin-top--xs"><span class="price-warranty-icon"></span>Gwarancja stałej ceny</div>';
    
    // Add remove button only if more than one item in cart
    if (count(WC()->cart->get_cart()) > 1) {
        $remove_url = wc_get_cart_remove_url($cart_item_key);
        $html .= sprintf(
            '<a href="%s" class="remove-product" data-cart-key="%s" aria-label="%s">%s</a>',
            esc_url($remove_url),
            esc_attr($cart_item_key),
            __('Usuń ten produkt', 'woocommerce'),
            __('Usuń', 'woocommerce')
        );
    }
    
    $html .= '</div>';
    
    return $html;
}

// Hide original variation display
add_filter('woocommerce_cart_item_class', 'add_hide_variation_class', 10, 3);
function add_hide_variation_class($class, $cart_item, $cart_item_key) {
    if (is_checkout()) {
        $class .= ' hide-variation';
    }
    return $class;
}

// Modify cart totals
add_filter('woocommerce_cart_totals_order_total_html', 'modify_cart_total_html', 100);
add_filter('woocommerce_order_formatted_total', 'modify_cart_total_html', 100);
function modify_cart_total_html($total) {
    if (!is_checkout()) {
        return $total;
    }
    
    $calculator = sa_cart_calculator();
    $totals = $calculator->get_cart_totals();
    
    ob_start();
    ?>
    <div class="cart-total-details">
        <div class="deposit-info">
            <span class="deposit-amount"><?php echo number_format($totals['deposit'], 0, ',', '') . ' PLN'; ?></span>
        </div>
        <?php if (!empty($totals['remaining_payments'])): ?>
            <div class="cart-remaining-payment">
                <?php foreach ($totals['remaining_payments'] as $payment): ?>
                    <div><?php echo $payment['due'] . ' płatne ' . number_format($payment['amount'], 0, ',', '') . ' ' . $payment['currency']; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

// Handle subtotal display in review order
function custom_review_order_item_subtotal($subtotal, $cart_item, $cart_item_key) {
    if (empty($cart_item['variation_id'])) return $subtotal;
    
    $calculator = sa_cart_calculator();
    $deposit = $calculator->get_item_deposit($cart_item);
    $components = $calculator->get_item_components($cart_item);
    $remaining_payments = $calculator->get_item_remaining_payments($cart_item);
    
    ob_start();
    ?>
    <div class="cart-subtotal-details" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
        <div class="deposit-info">
            <span class="deposit-label">Łączna zaliczka płatna do 7 dni</span>
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
    <?php
    return ob_get_clean();
}

// Handle cart subtotal in review order
function custom_review_order_subtotal($cart_subtotal, $compound, $cart) {
    if (!is_checkout()) return $cart_subtotal;
    
    $calculator = sa_cart_calculator();
    $totals = $calculator->get_cart_totals();
    return number_format($totals['deposit'], 0, ',', '') . ' PLN';
}

// Handle line subtotal in review order
function custom_review_order_line_subtotal($subtotal, $item, $order) {
    if (!is_checkout()) return $subtotal;
    
    $variation_id = $item->get_variation_id();
    if (!$variation_id) return $subtotal;
    
    $cart_item = array(
        'variation_id' => $variation_id,
        'quantity' => $item->get_quantity(),
        'line_subtotal' => $item->get_subtotal(),
        'line_total' => $item->get_total()
    );
    
    $calculator = sa_cart_calculator();
    $deposit = $calculator->get_item_deposit($cart_item);
    
    return number_format($deposit, 0, ',', '') . ' PLN';
}

// Modify cart totals text
add_filter('gettext', 'modify_cart_totals_text', 20, 3);
function modify_cart_totals_text($translated_text, $text, $domain) {
    if ($domain === 'woocommerce') {
        switch ($text) {
            case 'Cart totals':
                return 'Podsumowanie';
            case 'Total':
                return 'Łączna zaliczka płatna do 7 dni';
        }
    }
    return $translated_text;
}

// Remove header text
add_filter('woocommerce_checkout_cart_item_visible', 'remove_checkout_table_headers', 10, 2);
function remove_checkout_table_headers($visible, $cart_item) {
    return true;
}

// Remove the table header from checkout order review
add_action('woocommerce_review_order_before_cart_contents', 'remove_checkout_table_header', 1);
function remove_checkout_table_header() {
    // No need for inline styles as they are now in the CSS file
}

// Remove duplicate quantity display
add_filter('woocommerce_checkout_cart_item_quantity', '__return_empty_string');

// Remove cart subtotal row from checkout
add_filter('woocommerce_get_order_item_totals', 'remove_cart_subtotal_row', 10, 3);
function remove_cart_subtotal_row($total_rows, $order, $tax_display) {
    if (is_checkout()) {
        unset($total_rows['cart_subtotal']);
    }
    return $total_rows;
}

// Remove the original consent checkboxes action
remove_action('woocommerce_review_order_before_submit', 'woocommerce_checkout_privacy_policy_text', 10);

// Remove the original consent section and terms checkbox
add_action('init', 'remove_original_consent_section');
function remove_original_consent_section() {
    remove_action('woocommerce_review_order_before_submit', array('WC_Checkout', 'checkout_privacy_policy_text'), 10);
    remove_action('woocommerce_review_order_before_submit', array('WC_Checkout', 'checkout_terms_and_conditions_page_content'), 10);
    remove_action('woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20);
    remove_action('woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30);
    
    // Additional removals to ensure terms are completely gone
    remove_action('woocommerce_checkout_before_terms_and_conditions', array('WC_Checkout', 'checkout_terms_and_conditions'));
    remove_action('woocommerce_checkout_after_terms_and_conditions', array('WC_Checkout', 'checkout_terms_and_conditions'));
    remove_action('woocommerce_checkout_terms_and_conditions', array('WC_Checkout', 'checkout_terms_and_conditions'));
    
    // Remove the terms container completely
    remove_action('woocommerce_checkout_before_terms_and_conditions', 'wc_terms_and_conditions');
    remove_action('woocommerce_checkout_after_terms_and_conditions', 'wc_terms_and_conditions');
    remove_action('woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions');
}

// Make terms not required and remove the field completely
add_filter('woocommerce_checkout_fields', 'remove_terms_requirement', 50);
function remove_terms_requirement($fields) {
    // Remove terms field completely
    if (isset($fields['order']['terms'])) {
        unset($fields['order']['terms']);
    }
    
    // Also remove from account fields if present
    if (isset($fields['account']['terms'])) {
        unset($fields['account']['terms']);
    }
    
    return $fields;
}

// Ensure terms are not required in validation
add_filter('woocommerce_checkout_posted_data', 'bypass_terms_validation');
function bypass_terms_validation($data) {
    $data['terms'] = 1;
    return $data;
}

// Remove terms validation
add_filter('woocommerce_checkout_process', 'remove_terms_validation', 0);
function remove_terms_validation() {
    remove_action('woocommerce_checkout_process', array('WC_Checkout', 'validate_posted_data'));
    add_action('woocommerce_checkout_process', 'custom_validate_posted_data');
}

// Custom validation without terms check
function custom_validate_posted_data() {
    $errors = new WP_Error();
    
    // Re-implement WooCommerce validation without terms check
    if (!isset($_POST['woocommerce-process-checkout-nonce']) || !wp_verify_nonce(wp_unslash($_POST['woocommerce-process-checkout-nonce']), 'woocommerce-process_checkout')) {
        $errors->add('security', __('Invalid nonce. Please try again.', 'woocommerce'));
    }
    
    if (WC()->cart->is_empty()) {
        $errors->add('empty-cart', __('Sorry, your session has expired.', 'woocommerce'));
    }
    
    if (!empty($errors->get_error_codes())) {
        foreach ($errors->get_error_messages() as $message) {
            wc_add_notice($message, 'error');
        }
    }
}

// Hide terms and conditions section with CSS
add_action('wp_head', function() {
    if (is_checkout()) {
        // No need for inline styles as they are now in the CSS file
    }
});

// Disable terms and conditions via filter
add_filter('woocommerce_checkout_show_terms', '__return_false');
add_filter('woocommerce_checkout_privacy_policy_text', '__return_empty_string');
add_filter('woocommerce_get_terms_and_conditions_checkbox_text', '__return_empty_string');

// Define the function to get the product regulation URL
function get_product_regulation_url($product_id) {
    // Example logic to retrieve the regulation URL
    // You can customize this based on your requirements
    return '/wp-content/uploads/regulations/' . $product_id . '-regulations.pdf'; // Example URL structure
}

// Add custom error messages
add_filter('woocommerce_add_error', 'customize_error_messages');
function customize_error_messages($error) {
    $translations = array(
        'Billing First name is a required field.' => 'Pole Imię jest wymagane.',
        'Billing Last name is a required field.' => 'Pole Nazwisko jest wymagane.',
        'Billing Phone is a required field.' => 'Pole Telefon jest wymagane.',
        'Billing Email address is a required field.' => 'Pole Email jest wymagane.',
        'Please read and accept the terms and conditions to proceed with your order.' => 'Proszę zaakceptować wszystkie wymagane zgody.'
    );
    
    return isset($translations[$error]) ? $translations[$error] : $error;
}

// Remove unnecessary cart hooks
function remove_cart_totals_extra_stuff() {
    if (is_cart()) {
        remove_action('woocommerce_cart_totals_before_shipping', 'woocommerce_cart_totals_shipping_html');
        remove_action('woocommerce_cart_totals_before_order_total', 'woocommerce_cart_totals_order_total_html');
    }
}
add_action('init', 'remove_cart_totals_extra_stuff');

// Hide unnecessary columns and meta
add_filter('woocommerce_hidden_order_itemmeta', 'hide_order_item_meta');
function hide_order_item_meta($hidden_meta) {
    $hidden_meta[] = '_deposit_amount';
    $hidden_meta[] = '_deposit_components';
    $hidden_meta[] = '_remaining_payments';
    return $hidden_meta;
}

// Add custom styles for order display
add_action('admin_head', 'add_admin_order_styles');
function add_admin_order_styles() {
    // No need for inline styles as they are now in the CSS file
}

// Add proper CSS enqueuing
add_action('wp_enqueue_scripts', 'sa_enqueue_checkout_styles');
function sa_enqueue_checkout_styles() {
    if (is_checkout()) {
        wp_enqueue_style('sa-global-styles', plugins_url('../assets/css/global-styles.css', __FILE__));
    }
}

// Remove default order review template and add our custom one
add_action('init', 'adjust_checkout_template_hooks');
function adjust_checkout_template_hooks() {
    // Remove default WooCommerce order review
    remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10);
}

// Add our custom template
add_action('woocommerce_checkout_order_review', 'custom_order_review_template', 10);

function custom_order_review_template() {
    $cart = WC()->cart;
    if (empty($cart->get_cart())) {
        return;
    }
    ?>
    <div class="woocommerce-checkout-review-order-table">
        <?php foreach ($cart->get_cart() as $cart_item_key => $cart_item): ?>
            <div class="cart_item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                <div class="product-name">
                    <?php echo sa_checkout_modify_review_order_product_name('', $cart_item, $cart_item_key); ?>
                </div>
                <div class="product-total">
                    <?php echo custom_review_order_item_subtotal('', $cart_item, $cart_item_key); ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (count($cart->get_cart()) > 1): ?>
            <div class="order-total">
                <div class="order-total-label">Łączna zaliczka płatna do 7 dni</div>
                <div class="order-total-value">
                    <?php echo modify_cart_total_html(''); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

// Add CSS for the new format
add_action('wp_head', function() {
    if (!is_checkout()) return;
    // No need for inline styles as they are now in the CSS file
});

// Remove default WooCommerce coupon form
add_action('init', 'remove_wc_coupon_form');
function remove_wc_coupon_form() {
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
    remove_action('woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form');
}

// Add account creation and Klaviyo newsletter checkboxes after billing fields
add_action('woocommerce_after_checkout_billing_form', 'add_custom_account_checkbox', 9);
function add_custom_account_checkbox($checkout) {
    ?>
    <div class="custom-account-checkbox">
        <?php if (!is_user_logged_in() && get_option('woocommerce_enable_signup_and_login_from_checkout') === 'yes'): ?>
            <div class="checkbox-wrapper">
                <label class="checkbox">
                    <input type="checkbox" name="createaccount" id="createaccount" value="1" class="input-checkbox">
                    <span>Utwórz konto i zyskaj dostęp do historii zamówień (opcjonalne)</span>
                </label>
            </div>
        <?php endif; ?>
        <div class="checkbox-wrapper">
            <label class="checkbox">
                <input type="checkbox" name="kl_newsletter_checkbox" id="kl_newsletter_checkbox" value="1" class="input-checkbox">
                <span>Chcę uzyskać rabat na kolejne wyprawy (opcjonalne)</span>
            </label>
        </div>
        <div class="checkbox-wrapper">
            <label class="checkbox">
                <input type="checkbox" name="cookie_consent_checkbox" id="cookieConsentCheckbox" value="1" class="input-checkbox">
                <span>Wyrażam zgodę na używanie plików cookie do celów analitycznych i marketingowych (opcjonalne)</span>
            </label>
        </div>
    </div>
    <?php
}

// Remove default account fields section and duplicate fields
add_filter('woocommerce_checkout_fields', 'remove_account_fields', 99);
function remove_account_fields($fields) {
    // Remove account fields
    if (isset($fields['account'])) {
        unset($fields['account']);
    }
    
    // Remove Klaviyo field from billing section since we're showing it in our custom section
    if (isset($fields['billing']['kl_newsletter_checkbox'])) {
        unset($fields['billing']['kl_newsletter_checkbox']);
    }
    
    return $fields;
}

// Hide default account fields and billing newsletter checkbox
add_action('wp_head', 'hide_default_account_fields');
function hide_default_account_fields() {
    if (is_checkout()) {
        ?>
        <style>
            .woocommerce-account-fields,
            #kl_newsletter_checkbox_field {
                display: none !important;
            }
        </style>
        <?php
    }
}

// Klaviyo integration is now handled in includes/klaviyo-integration.php

// Ensure order emails show correct discounted prices
add_filter('woocommerce_order_item_get_formatted_meta_data', 'adjust_order_item_meta_display', 10, 2);
function adjust_order_item_meta_display($formatted_meta, $item) {
    foreach ($formatted_meta as $key => $meta) {
        if ($meta->key === '_deposit_components') {
            // Get the original deposit
            $original_deposit = $item->get_meta('_original_deposit');
            $deposit_components = $item->get_meta('_deposit_components');
            
            if ($original_deposit && $deposit_components) {
                // Calculate actual paid deposit (after discounts)
                $order = $item->get_order();
                $discount_total = $order ? $order->get_total_discount() : 0;
                
                if ($discount_total > 0) {
                    $total_original_deposit = 0;
                    foreach ($order->get_items() as $order_item) {
                        $item_original_deposit = $order_item->get_meta('_original_deposit');
                        if ($item_original_deposit) {
                            $total_original_deposit += floatval($item_original_deposit);
                        }
                    }
                    
                    if ($total_original_deposit > 0) {
                        $discount_ratio = ($total_original_deposit - $discount_total) / $total_original_deposit;
                        $deposit_components['deposit'] = round($original_deposit * $discount_ratio);
                        $meta->value = $deposit_components;
                    }
                }
            }
        }
    }
    
    return $formatted_meta;
}
