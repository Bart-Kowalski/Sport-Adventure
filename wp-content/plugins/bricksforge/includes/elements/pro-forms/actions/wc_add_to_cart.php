<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Wc_Add_To_Cart
{
    public $name = "wc_add_to_cart";
    private $forms_helper;

    public function run($form)
    {
        $this->forms_helper = new FormsHelper();

        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $form_id = $form->get_form_id();
        $form_structure = $form->get_structure();

        // If WooCommerce is not active, return
        if (!class_exists('WooCommerce')) {
            $form->set_result(array(
                'action' => $this->name,
                'type'    => 'error',
                'message' => __('The product could not be added to the cart', 'bricksforge')
            ));
            return;
        }

        include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
        include_once WC_ABSPATH . 'includes/class-wc-cart.php';

        if (is_null(\WC()->cart)) {
            wc_load_cart();
        }

        $product = isset($form_settings['pro_forms_post_action_add_to_cart_product']) ? $form_settings['pro_forms_post_action_add_to_cart_product'] : null;
        $product_id = isset($form_settings['pro_forms_post_action_add_to_cart_product_id']) ? $form_settings['pro_forms_post_action_add_to_cart_product_id'] : null;
        $quantity = isset($form_settings['pro_forms_post_action_add_to_cart_quantity']) ? $form_settings['pro_forms_post_action_add_to_cart_quantity'] : 1;
        $price = isset($form_settings['pro_forms_post_action_add_to_cart_price']) ? $form_settings['pro_forms_post_action_add_to_cart_price'] : false;
        $is_total_price = isset($form_settings['pro_forms_post_action_add_to_cart_is_total_price']) ? $form_settings['pro_forms_post_action_add_to_cart_is_total_price'] : false;
        $consider_variations = isset($form_settings['pro_forms_post_action_add_to_cart_consider_variations']) ? $form_settings['pro_forms_post_action_add_to_cart_consider_variations'] : null;
        $custom_fields = isset($form_settings['pro_forms_post_action_add_to_cart_custom_fields']) ? $form_settings['pro_forms_post_action_add_to_cart_custom_fields'] : [];

        if (empty($product) && empty($product_id)) {
            $form->set_result(array(
                'action' => $this->name,
                'type'    => 'error',
                'message' => __('The product could not be added to the cart', 'bricksforge')
            ));
            return;
        }

        if (empty($quantity)) {
            $form->set_result(array(
                'action' => $this->name,
                'type'    => 'error',
                'message' => __('The product could not be added to the cart', 'bricksforge')
            ));
            return;
        }

        if ($product !== 'custom') {
            $product_id = $product;
        } else {
            $product_id = $form->get_form_field_by_id($product_id);
        }

        $quantity = $form->get_form_field_by_id($quantity);

        if ($price) {
            $price_is_variable = false;

            // If $price is not numeric, $price_is_variable is true
            if (strpos($price, '{{') !== false) {
                $price_is_variable = true;
            }

            $price = $form->get_form_field_by_id($price);

            // Check if form ha been mutated
            $is_mutated = $this->forms_helper->is_form_mutated($form_structure, $form_fields);
            if ($is_mutated) {
                $form->set_result(array(
                    'action' => $this->name,
                    'type'    => 'error',
                    'message' => __('The product could not be added to the cart', 'bricksforge')
                ));
                return;
            }

            // If form_settings['fields'] contains a field['type'] == 'calculation', we need to re-calculate the price
            $validate = $this->wc_add_to_cart_validate($form_settings, $form_fields, $form, $incoming_price = $price, $post_id, $price_is_variable);

            if ($validate[0] === false) {
                $form->set_result(array(
                    'action' => $this->name,
                    'type'    => 'error',
                    'message' => __('The product could not be added to the cart', 'bricksforge')
                ));
                return;
            }

            // If validate[1] is not null
            if ($validate[1] !== null) {
                $price = $validate[1];
            }

            // The price should match the format of the WooCommerce price
            $price = wc_format_decimal($price, wc_get_price_decimals());
            $price = floatval($price);

            // If the quantity is > 1, we need to divide the price by the quantity
            if ($quantity > 1 && $is_total_price === true) {
                $price = $price / $quantity;
            }
        }

        // Custom Fields are an array. We need to loop trough them and get the values
        if (!empty($custom_fields)) {
            $custom_fields = array_map(function ($item) use ($form) {
                $item['label'] = $form->get_form_field_by_id($item['label']);
                $item['value'] = $form->get_form_field_by_id($item['value']);
                return $item;
            }, $custom_fields);
        }

        // Convert formats
        $product_id = intval($product_id);
        $quantity = intval($quantity);

        $product = wc_get_product($product_id);
        $is_variable_product = $product->is_type('variable');

        // Access the cart object from $woocommerce
        $cart = \WC()->cart;

        // Set Session
        $cart->set_session();

        // Generate a unique cart item key
        $unique_cart_item_key = uniqid();

        // Set the cart item meta data
        $cart_item_data = array(
            'brf_product_id' => $product_id,
            BRICKSFORGE_WC_CART_ITEM_KEY => $unique_cart_item_key,
            'brf_custom_fields' => []
        );

        if ($price) {
            $cart_item_data['brf_custom_price'] = $price;
        }

        // For each custom field, add it to the cart item data in the format like the 'brf_color' above
        foreach ($custom_fields as $custom_field) {
            // Build a key from the label and add 'brf' as prefix. Example: Product Color should be 'brf_product_color'
            $cf_key = 'brf_' . strtolower(str_replace(' ', '_', $custom_field['label']));

            // Add the field to 'brf_custom_fields' array of the cart item data
            $cart_item_data['brf_custom_fields'][$cf_key] = [
                'label' => $custom_field['label'],
                'value' => $custom_field['value']
            ];
        }

        // Handle Variable Products
        if ($is_variable_product === true) {
            // Find the matching variation ID if applicable
            if (!empty($cart_item_data['brf_custom_fields']) && $consider_variations === true) {
                $variation_id = $this->find_matching_variation_id($product_id, $cart_item_data['brf_custom_fields']);

                if (!$variation_id) {

                    $form->set_result(array(
                        'action' => $this->name,
                        'type'    => 'error',
                        'message' => __('The product could not be added to the cart', 'bricksforge')
                    ));
                }

                $cart_item_data['variation_id'] = $variation_id;

                // Update Price
                $variation = wc_get_product($variation_id);

                if ($variation) {
                    $price = $variation->get_price();
                    $cart_item_data['brf_custom_price'] = $price;
                }
            } else {
                $form->set_result(array(
                    'action' => $this->name,
                    'type'    => 'error',
                    'message' => __('The product could not be added to the cart', 'bricksforge')
                ));
            }
        }

        $cart_item_key = $cart->add_to_cart($product_id, $quantity, 0, array(), $cart_item_data);

        if (!$cart_item_key) {
            $form->set_result(array(
                'action' => $this->name,
                'type'    => 'error',
                'message' => __('The product could not be added to the cart', 'bricksforge')
            ));
        }

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        // Store the unique cart item key in the session array
        $stored_unique_keys = \WC()->session->get(BRICKSFORGE_WC_CART_ITEM_KEY, array());

        // If its a string, convert it to an array
        if (is_string($stored_unique_keys)) {
            $stored_unique_keys = array($stored_unique_keys);
        }

        $stored_unique_keys[$cart_item_key] = $unique_cart_item_key;

        \WC()->session->set(BRICKSFORGE_WC_CART_ITEM_KEY, $stored_unique_keys);

        $stored_custom_fields = WC()->session->get('brf_custom_fields', array());
        $stored_custom_fields[$cart_item_key] = $cart_item_data['brf_custom_fields'];
        WC()->session->set('brf_custom_fields', $stored_custom_fields);

        $form->set_result(
            [
                'action' => $this->name,
                'type'   => 'success',
            ]
        );

        return true;
    }

    private function check_hash($incoming_price, $form_data)
    {
        $allow = false;

        $hashes = isset($form_data['calculationHashes']) ? $form_data['calculationHashes'] : [];

        if (empty($hashes)) {
            return false;
        }

        $hashes = json_decode($hashes, true);
        $incoming_price_hash = hash_hmac('sha256', $incoming_price, BRICKSFORGE_SECRET_KEY);

        foreach ($hashes as $hash) {
            if ($hash === $incoming_price_hash) {
                $allow = true;
                break;
            }
        }

        return $allow;
    }

    private function wc_add_to_cart_validate($form_settings, $form_data, $form, $incoming_price, $post_id, $price_is_variable)
    {
        $passed = true;
        $price = null;

        $check_hashes = $this->check_hash($incoming_price, $form_data);

        if (!$check_hashes && $price_is_variable) {
            $passed = false;
        }

        if (!empty($form_settings['fields'])) {
            $fields = $form_settings['fields'];

            foreach ($fields as $key => $field) {
                if ($field['type'] == 'calculation') {
                    $formula = bricks_render_dynamic_data($field['formula']);
                    $result = $this->forms_helper->calculate_formula($formula, $form_data, $field);

                    if ($result !== null && is_numeric($result)) {
                        $empty_message = isset($field['emptyMessage']) ? $field['emptyMessage'] : '';
                        $price = $result;
                    }
                }

                // Type select, radio, checkbox
                if ($field['type'] == 'select' || $field['type'] == 'radio' || $field['type'] == 'checkbox') {
                    // Check if the field value is available in the field options
                    $field_value = $form->get_form_field_by_id($field['id'], $form_data);

                    // Field Options syntax: 5|S\n10|M\n15|L
                    $field_options = $field['options'];
                    $field_options = explode("\n", $field_options);

                    // Check if $field_options is an array and contains the field_value. But we need to check for the value before the pipe
                    $field_options = array_map(function ($item) {
                        $item = explode('|', $item);
                        return $item[0];
                    }, $field_options);

                    if (!in_array($field_value, $field_options)) {
                        $passed = false;
                    }
                }
            }
        }

        // We validate the incoming price, if the price is a variable
        if ($price_is_variable) {
            $passed = $this->validate_calculation($incoming_price, $form_settings, $post_id, $form);
        }

        return [$passed, $price];
    }

    private function validate_calculation($incoming_price, $form_settings, $post_id, $form)
    {
        if (!isset($incoming_price) || !isset($form_settings)) {
            return false;
        }

        $price_field = isset($form_settings['pro_forms_post_action_add_to_cart_price']) ? $form_settings['pro_forms_post_action_add_to_cart_price'] : false;

        if (!$price_field) {
            return false;
        }

        // If the price field contains {{ and }}, we remove them
        if (strpos($price_field, '{{') !== false && strpos($price_field, '}}') !== false) {
            $price_field = str_replace('{{', '', $price_field);
            $price_field = str_replace('}}', '', $price_field);
        }

        $field_ids = $_POST['fieldIds'];

        if (!isset($field_ids) || empty($field_ids)) {
            return false;
        }

        $field_ids = stripslashes($field_ids);
        $field_ids = json_decode($field_ids, true);

        $element_id = isset($field_ids[$price_field]) ? $field_ids[$price_field] : null;

        if (!isset($element_id) || empty($element_id)) {
            return false;
        }

        $price_field_element = \Bricks\Helpers::get_element_settings($post_id, $element_id);

        if (!isset($price_field_element) || empty($price_field_element)) {
            return false;
        }

        $formula = $price_field_element['formula'];

        $empty_to_zero = isset($price_field_element['setEmptyToZero']) ? $price_field_element['setEmptyToZero'] : false;

        // In the formular, we need to replace each {variable} with the actual value ($form->get_form_field_by_id($variable_id))
        $formula = $this->replace_formula_variables($formula, $form, $empty_to_zero);

        if (!isset($formula) || empty($formula)) {
            return false;
        }

        // Encode the formula
        $formula = urlencode($formula);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.bricksforge.io/v1/helpers/calculate?formula={$formula}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "X-Brf-Token: " . BRICKSFORGE_SECRET_KEY,
                "X-Brf-Key: " . get_option('brf_settings'),
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        // If the response is false, its not valid
        if (!$response) {
            return false;
        }

        // If the result is not numeric, its not valid
        if (!is_numeric($response)) {
            return false;
        }

        // If the incoming price is not numeric (like 15,503), we need to convert it to a float, like 15.503
        if (!is_numeric($incoming_price)) {
            $incoming_price = str_replace(',', '.', $incoming_price);
        }

        $response = floatval($response);

        $raw_incoming_values = isset($_POST['calculationFieldRawValues']) ? $_POST['calculationFieldRawValues'] : null;

        if (isset($raw_incoming_values) && !empty($raw_incoming_values)) {
            $raw_incoming_values = stripslashes($raw_incoming_values);
            $raw_incoming_values = json_decode($raw_incoming_values, true);

            if (isset($raw_incoming_values[$element_id]) && $incoming_price !== $raw_incoming_values[$element_id]) {
                $incoming_price = $raw_incoming_values[$element_id];
            }
        }

        $incoming_price = floatval($incoming_price);

        // We compare the response with the incoming price. If they are not the same, its not valid
        if ($response !== $incoming_price) {
            return false;
        }

        return true;
    }

    private function replace_formula_variables($formula, $form, $empty_to_zero = false)
    {
        $formula = bricks_render_dynamic_data($formula);
        $form_data = $form->get_fields();

        // Find each word wrapped by {}. For each field, we need the value and replace it with the value returned by get_form_field_by_id()
        preg_match_all('/{([^}]+)}/', $formula, $matches);

        foreach ($matches[1] as $match) {
            $field_value = $form->get_form_field_by_id($match, $form_data);

            // Remove commas from numbers like 1,000.00 -> 1000.00
            if (strpos($field_value, ',') !== false) {
                $field_value = str_replace(',', '', $field_value);
            }

            $calculation_value = $form->get_calculation_value_by_id($match, $field_value);

            if ($calculation_value !== null) {
                $field_value = $calculation_value;
            }

            // If the field value contains a comma, we sum up the values
            if (strpos($field_value, ',') !== false) {
                $field_value = array_sum(explode(',', $field_value));
            }

            if (isset($field_value) && $field_value !== "" && is_numeric($field_value)) {
                $formula = str_replace('{' . $match . '}', $field_value, $formula);
            } else {
                if ($empty_to_zero) {
                    $formula = str_replace('{' . $match . '}', '0', $formula);
                }
            }
        }

        return $formula;
    }


    private function find_matching_variation_id($product_id, $custom_fields)
    {

        $product = wc_get_product(intval($product_id));

        if (!$product) {
            return 0;
        }

        $variations = $product->get_available_variations();

        foreach ($variations as $variation) {
            $variation_attributes = $variation['attributes'];
            $match = true;

            foreach ($custom_fields as $custom_field) {
                $attribute_key = 'attribute_' . sanitize_title($custom_field['label']);
                $attribute_value = $custom_field['value'];

                if (!isset($variation_attributes[$attribute_key]) || $variation_attributes[$attribute_key] !== $attribute_value) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                return $variation['variation_id'];
            }
        }

        return 0; // Return 0 if no matching variation is found
    }
}
