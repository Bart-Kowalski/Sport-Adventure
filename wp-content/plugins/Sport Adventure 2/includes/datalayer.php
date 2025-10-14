<?php
/**
 * DataLayer PHP Implementation
 * Pushes product data from PHP to JavaScript
 */

if (!defined('WPINC')) {
    die;
}

// Debug function
function sa_debug_to_console($data) {
    if (sa_is_php_debug_enabled()) {
        echo '<script>';
        echo 'console.log("PHP Debug:", ' . wp_json_encode($data) . ');';
        echo '</script>';
    }
}

/**
 * Get trip difficulty level (1-5) from ACF field
 */
function sa_get_trip_difficulty($product_id) {
    if (!function_exists('get_field')) return null;
    
    $difficulty = get_field('wyprawa__poziom-trudnosci', $product_id);
    if (!$difficulty) return null;

    // Extract first number from the difficulty string
    if (preg_match('/^(\d)/', $difficulty, $matches)) {
        return (int) $matches[1];
    }
    
    return null;
}

/**
 * Get trip dates from product variations with proper formatting
 */
function sa_get_trip_dates($product) {
    if (!$product) return array();

    $dates = array();

    // If this is a variation, get its specific date
    if ($product->is_type('variation')) {
        $date = $product->get_attribute('pa_termin');
        if ($date) {
            // First handle any text in parentheses by preserving it
            $additional_text = '';
            if (preg_match('/\((.*?)\)/', $date, $matches)) {
                $additional_text = ' (' . $matches[1] . ')';
                $date = preg_replace('/\s*\(.*?\)/', '', $date);
            }
            
            // Convert dots to dashes for standardization
            $date = str_replace('.', '-', $date);
            
            // Format the date range while preserving the dash between dates
            if (preg_match('/(\d{2}-\d{2}-\d{4})-(\d{2}-\d{2}-\d{4})/', $date, $matches)) {
                // Convert the individual dates to dot format but keep the dash between them
                $start_date = str_replace('-', '.', $matches[1]);
                $end_date = str_replace('-', '.', $matches[2]);
                $date = $start_date . ' - ' . $end_date . $additional_text;
                $dates[] = $date;
            } else {
                // If it's a single date or different format, just convert to dots
                $date = str_replace('-', '.', $date) . $additional_text;
                $dates[] = $date;
            }
        }
        return $dates;
    }

    // For variable products, get all variation dates
    if ($product->is_type('variable')) {
        $variations = $product->get_available_variations();
        foreach ($variations as $variation) {
            if (isset($variation['attributes']['attribute_pa_termin'])) {
                $date = $variation['attributes']['attribute_pa_termin'];
                
                // First handle any text in parentheses by preserving it
                $additional_text = '';
                if (preg_match('/\((.*?)\)/', $date, $matches)) {
                    $additional_text = ' (' . $matches[1] . ')';
                    $date = preg_replace('/\s*\(.*?\)/', '', $date);
                }
                
                // Convert dots to dashes for standardization
                $date = str_replace('.', '-', $date);
                
                // Format the date range while preserving the dash between dates
                if (preg_match('/(\d{2}-\d{2}-\d{4})-(\d{2}-\d{2}-\d{4})/', $date, $matches)) {
                    // Convert the individual dates to dot format but keep the dash between them
                    $start_date = str_replace('-', '.', $matches[1]);
                    $end_date = str_replace('-', '.', $matches[2]);
                    $date = $start_date . ' - ' . $end_date . $additional_text;
                    $dates[] = $date;
                } else {
                    // If it's a single date or different format, just convert to dots
                    $date = str_replace('-', '.', $date) . $additional_text;
                    $dates[] = $date;
                }
            }
        }
    }

    return array_unique($dates);
}

/**
 * Get standardized product data for data layer events
 * 
 * @param WC_Product $product Product object
 * @param array $variation_data Optional variation data
 * @param array $acf_fields Optional ACF fields
 * @return array Standardized product data
 */
function sa_get_standardized_product_data($product, $variation_data = null, $acf_fields = null) {
    if (!$product) return null;

    // Get parent product for variations to get base data
    $parent_product = null;
    $base_product = $product;
    if ($product->is_type('variation')) {
        $parent_product = wc_get_product($product->get_parent_id());
        $base_product = $parent_product; // Use parent for base data
    }

    // Get base product data
    $data = array(
        'item_id' => $base_product->get_id(), // Always use parent product ID as item_id
        'item_name' => $base_product->get_name(), // Always use base product name without variant
        'price' => 0, // Will be set to ACF total price
        'currency' => 'PLN', // Default currency
        'reservation_deposit' => floatval($product->get_price()), // WooCommerce single price
        'trip_dates' => array(),
        'type' => $base_product->get_type() // Add product type for JavaScript detection
    );

    // For variable products, add variant_id when a variation is selected
    if ($product->is_type('variation')) {
        // For variations, add variant_id while keeping item_id as parent product ID
        $data['variant_id'] = $product->get_id(); // Variation ID
        $data['item_id'] = $product->get_parent_id(); // Parent product ID (consistent)
    } elseif ($base_product->is_type('variable')) {
        // For variable parent products, item_id is already the parent product ID
        // variant_id will be null/undefined until a specific variation is selected
    }

    // Get trip dates based on product type
    if ($product->is_type('variation')) {
        // For variations, just get its own termin
        $termin = $product->get_attribute('pa_termin');
        if ($termin) {
            $data['trip_dates'] = array($termin);
        }
    } elseif ($base_product->is_type('variable')) {
        // For variable products, get all variation dates (only when not in cart context)
        $variations = $base_product->get_available_variations();
        $trip_dates = array();
        foreach ($variations as $variation) {
            if (isset($variation['attributes']['attribute_pa_termin'])) {
                $trip_dates[] = $variation['attributes']['attribute_pa_termin'];
            }
        }
        $data['trip_dates'] = array_values(array_unique($trip_dates));
    }

    // Get price and currency from ACF fields
    if ($acf_fields === null) {
        if ($product->is_type('variation')) {
            // For variations, get ACF fields from parent first
            $acf_fields = get_fields($product->get_parent_id());
            if (empty($acf_fields['wyprawa-termin__cena-nie-liczac-lotow'])) {
                // If parent has no price, try variation
                $acf_fields = get_fields($product->get_id());
            }
        } else if ($product->is_type('variable')) {
            // For variable products, get fields from first variation if main product has no price
            $acf_fields = get_fields($product->get_id());
            if (empty($acf_fields['wyprawa-termin__cena-nie-liczac-lotow'])) {
                $variations = $product->get_available_variations();
                if (!empty($variations)) {
                    $first_variation = wc_get_product($variations[0]['variation_id']);
                    if ($first_variation) {
                        $variation_fields = get_fields($first_variation->get_id());
                        if (!empty($variation_fields['wyprawa-termin__cena-nie-liczac-lotow'])) {
                            $acf_fields = $variation_fields;
                        }
                    }
                }
            }
        } else {
            $acf_fields = get_fields($product->get_id());
        }
    }

    if ($acf_fields) {
        // Get total price from ACF
        if (!empty($acf_fields['wyprawa-termin__cena-nie-liczac-lotow'])) {
            $data['price'] = floatval($acf_fields['wyprawa-termin__cena-nie-liczac-lotow']);
        }

        // Get currency
        if (!empty($acf_fields['wyprawa-termin__waluta'])) {
            $data['currency'] = $acf_fields['wyprawa-termin__waluta'];
        }

        // Get flight cost (prefer wyprawa-termin__cena-lotu for variations, or first variation for variable products)
        if ($product->is_type('variation')) {
            $flight_cost = get_field('wyprawa-termin__cena-lotu', $product->get_id());
            $data['flight_cost'] = ($flight_cost !== false && $flight_cost !== '' && $flight_cost !== null) ? floatval($flight_cost) : null;
        } else if ($product->is_type('variable')) {
            $variations = $product->get_available_variations();
            if (!empty($variations)) {
                $first_variation_id = $variations[0]['variation_id'];
                $flight_cost = get_field('wyprawa-termin__cena-lotu', $first_variation_id);
                $data['flight_cost'] = ($flight_cost !== false && $flight_cost !== '' && $flight_cost !== null) ? floatval($flight_cost) : null;
            } else {
                $data['flight_cost'] = null;
            }
        } else if (!empty($acf_fields['wyprawa-termin__cena-przelotu'])) {
            $data['flight_cost'] = floatval($acf_fields['wyprawa-termin__cena-przelotu']);
        } else {
            $data['flight_cost'] = null;
        }
        // Set flight_currency always to PLN
        $data['flight_currency'] = 'PLN';
    }

    // Get categories
    $categories = array();
    $category_ids = $base_product->get_category_ids();
    if (!empty($category_ids)) {
        foreach ($category_ids as $cat_id) {
            $term = get_term($cat_id, 'product_cat');
            if ($term && !is_wp_error($term)) {
                $categories[] = $term->name;
            }
        }
    }
    $data['item_category'] = !empty($categories) ? $categories[0] : '';
    $data['item_category2'] = isset($categories[1]) ? $categories[1] : '';
    $data['product_categories'] = $categories;

    // Get product tags
    $tags = array();
    $product_tags = get_the_terms($base_product->get_id(), 'product_tag');
    if ($product_tags && !is_wp_error($product_tags)) {
        foreach ($product_tags as $tag) {
            $tags[] = $tag->name;
        }
    }
    $data['product_tags'] = $tags;

    // Get destination
    $destination_terms = get_the_terms($base_product->get_id(), 'destynacja');
    if ($destination_terms && !is_wp_error($destination_terms)) {
        $data['destination'] = $destination_terms[0]->name;
    }

    // Get trip difficulty
    $difficulty = get_field('wyprawa__poziom-trudnosci', $base_product->get_id());
    if ($difficulty) {
        $data['trip_difficulty'] = intval($difficulty);
    }

    return $data;
}

/**
 * Enqueue the data layer scripts in correct order
 */
function sa_enqueue_datalayer() {
    // First enqueue jQuery as a dependency
    wp_enqueue_script('jquery');

    // Then enqueue the config
    wp_enqueue_script(
        'sa-datalayer-config',
        plugins_url('assets/js/datalayer-config.js', dirname(__FILE__)),
        ['jquery'],
        time(), // Force no cache during debug
        true
    );

    // Then enqueue the core class
    wp_enqueue_script(
        'sa-datalayer-core',
        plugins_url('assets/js/datalayer-core.js', dirname(__FILE__)),
        ['jquery', 'sa-datalayer-config'],
        time(),
        true
    );

    // Then enqueue the main implementation
    wp_enqueue_script(
        'sa-datalayer',
        plugins_url('assets/js/datalayer.js', dirname(__FILE__)),
        ['jquery', 'sa-datalayer-config', 'sa-datalayer-core', 'wc-cart', 'wc-add-to-cart'],
        time(),
        true
    );

    // Localize script with necessary data
    wp_localize_script('sa-datalayer', 'sa_datalayer_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'checkout_url' => wc_get_checkout_url()
    ));
}
add_action('wp_enqueue_scripts', 'sa_enqueue_datalayer', 10);

/**
 * Initialize data layer in header
 */
function sa_initialize_datalayer() {
    // Set debug mode based on plugin settings
    $debug_mode = sa_is_debug_enabled();
    $js_debug_mode = sa_is_js_debug_enabled();
    
    ?>
    <script>
        window.dataLayer = window.dataLayer || [];
        window.saDataLayer = {
            debug: <?php echo $debug_mode ? 'true' : 'false'; ?>
        };
        
        // Update DATALAYER_CONFIG debug settings
        if (window.DATALAYER_CONFIG) {
            window.DATALAYER_CONFIG.debug.enabled = <?php echo $js_debug_mode ? 'true' : 'false'; ?>;
        }
    </script>
    <?php
}
add_action('wp_head', 'sa_initialize_datalayer', 1);

/**
 * Push product data to the data layer
 */
function sa_push_product_data() {
    sa_debug_to_console('Checking if product page...');
    
    $data_layer = array();

    // If we're on cart or checkout page, add cart items data
    if (is_cart() || is_checkout()) {
        $cart = WC()->cart;
        if ($cart) {
            $cart_items = array();
            foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                $product = $cart_item['data'];
                if (!$product) continue;

                // Use variation object and ACF fields if available
                $variation_id = isset($cart_item['variation_id']) ? $cart_item['variation_id'] : 0;
                $variation = $variation_id ? wc_get_product($variation_id) : null;
                $acf_fields = isset($cart_item['acf_fields']) ? $cart_item['acf_fields'] : null;

                $item_data = sa_get_standardized_product_data(
                    $variation ? $variation : $product,
                    $variation ? $variation : null,
                    $acf_fields
                );

                if ($item_data) {
                    $item_data['quantity'] = $cart_item['quantity'];
                    $cart_items[$cart_item_key] = $item_data;
                }
            }
            $data_layer['cart_items'] = $cart_items;
            $data_layer['page_type'] = is_cart() ? 'cart' : 'checkout';
        }
    }
    
    // If we're on a product page, add product data
    if (function_exists('is_product') && is_product()) {
        sa_debug_to_console('Is product page, getting product...');
        
        global $product;
        
        // If global $product is not set, try getting it from the post
        if (!$product) {
            global $post;
            if ($post) {
                $product = wc_get_product($post);
            }
        }

        if (!$product) {
            sa_debug_to_console('No product found!');
            return;
        }

        sa_debug_to_console('Got product: ' . $product->get_name());

        // Cache product data
        $cache_key = 'sa_product_data_' . $product->get_id();
        $product_data = wp_cache_get($cache_key);
        if (false === $product_data) {
            $product_data = sa_get_standardized_product_data($product);
            
            // For variable products, add variation information
            if ($product->is_type('variable')) {
                $variations = $product->get_available_variations();
                $product_data['variations'] = array();
                
                foreach ($variations as $variation) {
                    $variation_data = array(
                        'variation_id' => $variation['variation_id'],
                        'attributes' => $variation['attributes'],
                        'price' => $variation['display_price'],
                        'is_in_stock' => $variation['is_in_stock']
                    );
                    $product_data['variations'][] = $variation_data;
                }
            }
            
            wp_cache_set($cache_key, $product_data, '', 3600);
        }

        sa_debug_to_console('Got product data');

        $data_layer['product'] = $product_data;
        $data_layer['page_type'] = 'product';
        $data_layer['currency'] = get_woocommerce_currency();
    }

    if (!empty($data_layer)) {
        ?>
        <script>
            window.saDataLayer = <?php echo wp_json_encode($data_layer); ?>;
            console.log('saDataLayer initialized with:', window.saDataLayer);
        </script>
        <?php
    }
}
add_action('wp_head', 'sa_push_product_data', 2);

/**
 * Handle authentication events
 */
function sa_handle_auth_events() {
    // Empty function - keeping for backwards compatibility
}

/**
 * Output pending auth events
 */
function sa_output_pending_auth_events() {
    // Empty function - keeping for backwards compatibility
}

// Remove auth event hooks
// add_action('init', 'sa_handle_auth_events', 1);
// add_action('wp_head', 'sa_output_pending_auth_events', 0);
// add_action('admin_head', 'sa_output_pending_auth_events', 0);

/**
 * Simplified purchase tracking implementation
 */
function sa_output_purchase_event() {
    // Only run on order received page
    if (!is_wc_endpoint_url('order-received')) {
        return;
    }

    // Get order ID from URL
    global $wp;
    $order_id = absint($wp->query_vars['order-received']);
    if (!$order_id) {
        return;
    }

    // Get order
    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }

    // Skip if already tracked
    if ($order->get_meta('_datalayer_purchase_tracked')) {
        return;
    }

    $items = array();
    $total_value = 0;
    $currency = null;

    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        if (!$product) continue;

        // Get variation ID if this is a variation
        $variation_id = $item->get_variation_id();
        if ($variation_id) {
            // Use the variation object for proper data
            $variation = wc_get_product($variation_id);
            $item_data = sa_get_standardized_product_data($variation);
        } else {
            // Use the product object for simple products
            $item_data = sa_get_standardized_product_data($product);
        }
        
        if ($item_data) {
            // Add quantity
            $item_data['quantity'] = $item->get_quantity();
            
            // Add variant from trip dates
            if (!empty($item_data['trip_dates'])) {
                $item_data['variant'] = $item_data['trip_dates'][0];
            }

            // Calculate value using WooCommerce price (reservation_deposit) × quantity
            $total_value += $item_data['reservation_deposit'] * $item_data['quantity'];
            
            // Set currency if not set
            if (!$currency) {
                $currency = $item_data['currency'];
            }

            $items[] = $item_data;
        }
    }

    if (!empty($items)) {
        $purchase_data = array(
            'event' => 'purchase',
            'ecommerce' => array(
                'transaction_id' => $order->get_order_number(),
                'value' => $total_value,
                'currency' => $currency ?: $order->get_currency(),
                'items' => $items,
                'payment_method' => $order->get_payment_method_title()
            )
        );

        // Add debug data
        $debug_data = array(
            'order_id' => $order_id,
            'status' => $order->get_status(),
            'payment_method' => $order->get_payment_method(),
            'payment_method_title' => $order->get_payment_method_title(),
            'total' => $order->get_total(),
            'currency' => $order->get_currency(),
            'items_count' => count($order->get_items()),
            'created_via' => $order->get_created_via(),
            'date_created' => $order->get_date_created()->format('Y-m-d H:i:s'),
            'customer_id' => $order->get_customer_id(),
            'items_data' => $items // Include items data for debugging
        );

        ?>
        <script>
            console.log('Purchase Event Debug:', <?php echo json_encode($debug_data); ?>);
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({ ecommerce: null }); // Clear previous ecommerce
            window.dataLayer.push(<?php echo json_encode($purchase_data); ?>);
            console.log('DataLayer: Purchase event pushed', <?php echo json_encode($purchase_data); ?>);
        </script>
        <?php

        // Mark as tracked
        $order->update_meta_data('_datalayer_purchase_tracked', true);
        $order->save();
    }
}

// Add to wp_head with high priority to ensure dataLayer is available
add_action('wp_head', 'sa_output_purchase_event', 1);

/**
 * Handle WooCommerce events
 */
function sa_handle_woo_events() {
    // Empty function - begin_checkout is now handled in JavaScript
}
add_action('init', 'sa_handle_woo_events');

/**
 * Handle WSForm submissions
 */
function sa_handle_wsform_submit($form_object) {
    // Get form label
    $form_label = strtolower($form_object->form_object->label);
    
    // Handle product info requests only
    if (strpos($form_label, 'wlasna-wyprawa') !== false || strpos($form_label, 'pytanie-do-lidera') !== false) {
        $queryType = $form_label;
        ?>
        <script>
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                'event': 'product_info_request',
                'query_type': '<?php echo esc_js($queryType); ?>'
            });
        </script>
        <?php
    }
}
add_action('wsf_submit_success', 'sa_handle_wsform_submit');

/**
 * AJAX handler for getting cart data
 */
function sa_get_cart_data() {
    $cart = WC()->cart;
    if (!$cart) {
        wp_send_json_error();
        return;
    }

    $items = array();
    $total = 0;
    $currency = 'PLN';

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        if (!$product) continue;

        // Use variation object and ACF fields if available
        $variation_id = isset($cart_item['variation_id']) ? $cart_item['variation_id'] : 0;
        $variation = $variation_id ? wc_get_product($variation_id) : null;
        $acf_fields = isset($cart_item['acf_fields']) ? $cart_item['acf_fields'] : null;

        $item = sa_get_standardized_product_data(
            $variation ? $variation : $product,
            $variation ? $variation : null,
            $acf_fields
        );

        if (!$item) continue;

        // Add quantity from cart
        $item['quantity'] = $cart_item['quantity'];
        // Add to total using ACF price
        $total += $item['price'] * $item['quantity'];
        // Use the currency from the first item (assuming all items have same currency)
        if ($currency === 'PLN' && isset($item['currency'])) {
            $currency = $item['currency'];
        }
        $items[] = $item;
    }

    wp_send_json_success(array(
        'items' => $items,
        'total' => $total,
        'currency' => $currency
    ));
}
add_action('wp_ajax_sa_get_cart_data', 'sa_get_cart_data');
add_action('wp_ajax_nopriv_sa_get_cart_data', 'sa_get_cart_data');

// AJAX handler for getting order ID from key
function sa_get_order_id_from_key() {
    if (!isset($_POST['order_key'])) {
        wp_send_json_error('No order key provided');
        return;
    }

    $order_key = sanitize_text_field($_POST['order_key']);
    
    // Get order ID from key
    global $wpdb;
    $order_id = $wpdb->get_var($wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} 
        WHERE meta_key = '_order_key' 
        AND meta_value = %s",
        $order_key
    ));

    if ($order_id) {
        wp_send_json_success($order_id);
    } else {
        wp_send_json_error('Order not found');
    }
}
add_action('wp_ajax_sa_get_order_id_from_key', 'sa_get_order_id_from_key');
add_action('wp_ajax_nopriv_sa_get_order_id_from_key', 'sa_get_order_id_from_key');
