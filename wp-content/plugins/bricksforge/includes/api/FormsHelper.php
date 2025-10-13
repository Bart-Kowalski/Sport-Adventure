<?php

namespace Bricksforge\Api;

if (!defined('ABSPATH')) {
    exit;
}

class FormsHelper
{
    public $utils;

    /**
     * Constructor
     */

    public function __construct()
    {
        $this->utils = new \Bricksforge\Api\Utils();
    }

    public function check_password_strength($password)
    {
        $score = 0;
        $reasons = array();

        // Check length
        if (strlen($password) < 8) {
            $score = 0;
            $reasons[] = "Password should be at least 8 characters long.";
        } else {
            $score++;
        }

        // Check uppercase and lowercase letters
        if (!preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password)) {
            $score = 0;
            $reasons[] = "Password should include both uppercase and lowercase letters.";
        } else {
            $score++;
        }

        // Check numbers
        if (!preg_match('/[0-9]/', $password)) {
            $score = 0;
            $reasons[] = "Password should include at least one number.";
        } else {
            $score++;
        }

        // Check special characters
        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
            $score = 0;
            $reasons[] = "Password should include at least one special character.";
        } else {
            $score++;
        }

        // Check for common patterns
        $common_patterns = array(
            'password',
            '123456',
            'qwerty',
            'admin',
            'letmein',
            'welcome',
            'football',
        );
        if (in_array(strtolower($password), $common_patterns)) {
            $score = 0;
            $reasons[] = "Password is too common or easily guessable.";
        } else {
            $score++;
        }

        return array(
            'score' => $score,
            'reasons' => $reasons,
        );
    }

    public function handle_conditions($conditions, $condition_relation, $form_data, $structured, $structured_key)
    {

        if (!isset($condition_relation) || empty($condition_relation)) {
            $condition_relation = "and";
        }

        $result = [];

        foreach ($conditions as $condition) {
            $condition_type = isset($condition['condition']) ? $condition['condition'] : 'form_field'; // form_field, storage_item
            $condition_value = isset($condition['value']) ? $condition['value'] : null; // The field ID or the storage item ID
            $condition_operator = isset($condition['operator']) ? $condition['operator'] : '=='; // ==, !=, >, <, >=, <=, contains, not_contains, starts_with, ends_with, empty, not_empty
            $condition_value2 = isset($condition['value2']) ? $condition['value2'] : null; // The value to compare to
            $condition_data_type = isset($condition['type']) ? $condition['type'] : 'string'; // string, number, array

            $condition_result = $this->handle_condition($condition_type, $condition_value, $condition_operator, $condition_value2, $condition_data_type, $form_data);

            if ($structured && isset($structured_key) && isset($condition[$structured_key])) {
                $result[$condition[$structured_key]] = $condition_result;
            } else {
                $result[] = $condition_result;
            }
        }

        if ($structured) {
            return $result;
        } else {
            if ($condition_relation === 'or') {
                $result = in_array(true, $result);
            } else {
                $result = !in_array(false, $result);
            }
        }

        return $result;
    }

    public function handle_condition($condition_type, $condition_value, $condition_operator, $condition_value2, $condition_data_type, $form_data)
    {
        $result = false;
        $field_value = false;

        if ($condition_type === 'form_field') {
            $field_value = $this->get_form_field_by_id($condition_value, $form_data);
        }

        // Convert types based on condition_data_type
        if ($condition_data_type === 'number') {
            // Ensure numeric values for comparison
            $field_value = is_numeric($field_value) ? floatval($field_value) : 0;
            $condition_value2 = is_numeric($condition_value2) ? floatval($condition_value2) : 0;
        } elseif ($condition_data_type === 'array') {
            // Convert string representations to arrays if needed
            $field_value = is_array($field_value) ? $field_value : array($field_value);
            $condition_value2 = is_array($condition_value2) ? $condition_value2 : array($condition_value2);
        } else {
            // Default to string comparison
            $field_value = strval($field_value);
            $condition_value2 = strval($condition_value2);
        }

        switch ($condition_operator) {
            case '==':
                $result = $condition_data_type === 'array' ?
                    count(array_diff($field_value, $condition_value2)) === 0 && count(array_diff($condition_value2, $field_value)) === 0 :
                    $field_value == $condition_value2;
                break;
            case '!=':
                $result = $condition_data_type === 'array' ?
                    count(array_diff($field_value, $condition_value2)) !== 0 || count(array_diff($condition_value2, $field_value)) !== 0 :
                    $field_value != $condition_value2;
                break;
            case '>':
                $result = $condition_data_type === 'array' ?
                    count($field_value) > count($condition_value2) :
                    $field_value > $condition_value2;
                break;
            case '<':
                $result = $condition_data_type === 'array' ?
                    count($field_value) < count($condition_value2) :
                    $field_value < $condition_value2;
                break;
            case '>=':
                $result = $condition_data_type === 'array' ?
                    count($field_value) >= count($condition_value2) :
                    $field_value >= $condition_value2;
                break;
            case '<=':
                $result = $condition_data_type === 'array' ?
                    count($field_value) <= count($condition_value2) :
                    $field_value <= $condition_value2;
                break;
            case 'contains':
                $result = $condition_data_type === 'array' ?
                    count(array_intersect($field_value, array($condition_value2))) > 0 :
                    strpos($field_value, $condition_value2) !== false;
                break;
            case 'not_contains':
                $result = $condition_data_type === 'array' ?
                    count(array_intersect($field_value, array($condition_value2))) === 0 :
                    strpos($field_value, $condition_value2) === false;
                break;
            case 'starts_with':
                $result = $condition_data_type === 'array' ?
                    reset($field_value) === $condition_value2 :
                    strpos($field_value, $condition_value2) === 0;
                break;
            case 'ends_with':
                $result = $condition_data_type === 'array' ?
                    end($field_value) === $condition_value2 :
                    substr($field_value, -strlen($condition_value2)) === $condition_value2;
                break;
            case 'empty':
                $result = empty($field_value);
                break;
            case 'not_empty':
                $result = !empty($field_value);
                break;
            default:
                $result = true;
                break;
        }

        return $result;
    }

    public static function handle_conditionally_selected($settings, $value, $attribute = "checked")
    {
        $selected = '';

        $settings_key = $attribute == "selected" ? "conditionallySelected" : "conditionallyChecked";
        $settings_key_if = $attribute == "selected" ? "selectedIf" : "checkedIf";
        $settings_key_value = $attribute == "selected" ? "selectedIfValue" : "checkedIfValue";
        $settings_key_post_id = $attribute == "selected" ? "selectedIfPostId" : "checkedIfPostId";
        $settings_key_taxonomy = $attribute == "selected" ? "selectedIfTaxonomy" : "checkedIfTaxonomy";


        if (isset($settings[$settings_key]) && isset($settings[$settings_key_if])) {
            switch ($settings[$settings_key_if]) {
                case 'value':
                    $selected = isset($settings[$settings_key_value]) && $value === bricks_render_dynamic_data(bricks_render_dynamic_data($settings[$settings_key_value])) ? 'checked' : '';
                    break;
                case 'value_contains':
                    $selected = isset($settings[$settings_key_value]) && strpos($value, bricks_render_dynamic_data(bricks_render_dynamic_data($settings[$settings_key_value]))) !== false ? 'checked' : '';
                    break;
                case 'contains_array':
                    $settings_value = bricks_render_dynamic_data(bricks_render_dynamic_data($settings[$settings_key_value]));

                    // Try to decode if it's a JSON string
                    if (is_string($settings_value) && $decoded = json_decode($settings_value, true)) {
                        $settings_value = $decoded;
                    }

                    // If still a string, check for comma-separated values
                    if (is_string($settings_value)) {
                        if (strpos($settings_value, ',') !== false) {
                            $settings_value = array_map('trim', explode(',', $settings_value));
                        } else {
                            $settings_value = [$settings_value];
                        }
                    }

                    // If array has numeric keys, extract the values
                    if (is_array($settings_value) && array_keys($settings_value) === range(0, count($settings_value) - 1)) {
                        $settings_value = array_values($settings_value);
                    }

                    // If still not an array, wrap the value in an array
                    if (!is_array($settings_value)) {
                        $settings_value = [$settings_value];
                    }

                    // Handle the comparison value similarly
                    if (is_string($value)) {
                        if (strpos($value, ',') !== false) {
                            $value = array_map('trim', explode(',', $value));
                        } else {
                            $value = [$value];
                        }
                    } elseif (!is_array($value)) {
                        $value = [$value];
                    }

                    // If comparison value has numeric keys, extract the values
                    if (is_array($value) && array_keys($value) === range(0, count($value) - 1)) {
                        $value = array_values($value);
                    }

                    // Check if any value matches any settings value
                    $selected = count(array_intersect($value, $settings_value)) > 0 ? 'checked' : '';
                    break;
                case 'taxonomy':
                    $post_id = isset($settings[$settings_key_post_id]) && $settings[$settings_key_post_id] ? bricks_render_dynamic_data($settings[$settings_key_post_id]) : false;
                    $taxonomy = isset($settings[$settings_key_taxonomy]) && $settings[$settings_key_taxonomy] ? bricks_render_dynamic_data($settings[$settings_key_taxonomy]) : false;

                    if (!$post_id || !$taxonomy) {
                        break;
                    }

                    $terms = wp_get_post_terms($post_id, $taxonomy);

                    if (is_wp_error($terms)) {
                        break;
                    }

                    foreach ($terms as $term) {
                        if ($term->slug === $value) {
                            $selected = 'checked';
                            break;
                        }
                    }
                    break;
            }
        }

        return $selected;
    }


    public function get_variation_price($product_id, $custom_fields, $form_data)
    {
        // Check if product is variation type
        $product = wc_get_product($product_id);

        // If no product is found, return false
        if (!$product) {
            return false;
        }

        if (!$product->is_type('variable')) {
            return false;
        }

        if (empty($custom_fields)) {
            return false;
        }

        // For each custom field['value'], call $this->get_form_field_by_id($field['id'], $form_data). Use arraymap
        $custom_fields = array_map(function ($item) use ($form_data) {
            $item['value'] = $this->get_form_field_by_id($item['value'], $form_data);
            return $item;
        }, $custom_fields);

        $variation_id = $this->find_matching_variation_id($product_id, $custom_fields);

        if (!$variation_id) {
            return false;
        }

        $variation = wc_get_product($variation_id);
        $price = $variation->get_price();

        // Be sure to match WooCommerce price format with a native WooCommerce function
        $price = wc_format_decimal($price, wc_get_price_decimals());
        $price = wc_price($price);

        return $price;
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

    public function handle_turnstile($form_settings, $form_data, $turnstile_result)
    {
        $key = $this->get_turnstile_secret();

        if (!$key) {
            return true;
        }

        // Get the Turnstile response from the client-side form
        $turnstile_response = $turnstile_result;

        if (!$turnstile_response || empty($turnstile_response)) {
            return false;
        }

        // Verify the Turnstile response with a server-side request
        return $this->verify_turnstile_response($turnstile_response, $key);
    }

    public function get_turnstile_secret()
    {
        $turnstile_settings = array_values(array_filter(get_option('brf_activated_elements'), function ($tool) {
            return $tool->id == 5;
        }));

        if (count($turnstile_settings) === 0) {
            return false;
        }

        $turnstile_settings = $turnstile_settings[0];

        if (!isset($turnstile_settings->settings->useTurnstile) || $turnstile_settings->settings->useTurnstile !== true) {
            return false;
        }

        if (empty($turnstile_settings->settings->turnstileSecret)) {
            return false;
        }

        $decrypted_secret = $this->utils->decrypt($turnstile_settings->settings->turnstileSecret);

        return $decrypted_secret;
    }

    public function verify_turnstile_response($turnstile_response, $secret)
    {
        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $data = [
            'secret' => $secret,
            'response' => $turnstile_response
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $result = json_decode($response);

        curl_close($ch);

        return $result && $result->success;
    }

    public function handle_hcaptcha($form_settings, $form_data, $captcha_result)
    {
        $key = $this->get_hcaptcha_key();

        if (!$key) {
            return true;
        }

        // Get the hCaptcha response from the client-side form
        $hcaptcha_response = $captcha_result;

        if (!$hcaptcha_response || empty($hcaptcha_response)) {
            return false;
        }

        // Verify the hCaptcha response with a server-side request
        return $this->verify_hcaptcha_response($hcaptcha_response, $key);
    }

    public function verify_hcaptcha_response($hcaptcha_response, $secret)
    {
        $url = 'https://hcaptcha.com/siteverify';
        $data = [
            'secret' => $secret,
            'response' => $hcaptcha_response
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $result = json_decode($response);

        curl_close($ch);

        return $result && $result->success;
    }

    public function get_hcaptcha_key()
    {
        $hcaptcha_settings = array_values(array_filter(get_option('brf_activated_elements'), function ($tool) {
            return $tool->id == 5;
        }));

        if (count($hcaptcha_settings) === 0) {
            return false;
        }

        $hcaptcha_settings = $hcaptcha_settings[0];

        if (!$hcaptcha_settings->settings->useHCaptcha) {
            return false;
        }

        if (empty($hcaptcha_settings->settings->hCaptchaSecret)) {
            return false;
        }

        $decrypted_secret = $this->utils->decrypt($hcaptcha_settings->settings->hCaptchaSecret);

        return $decrypted_secret;
    }

    private function process_children($children, $post_id, &$form_fields, $post_context)
    {

        foreach ($children as $c) {
            $child = \Bricks\Helpers::get_element_data($post_id, $c);

            $ignored_names = array("brf-pro-forms-field-previous", "brf-pro-forms-field-next", "brf-pro-forms-field-submit-button", "brf-pro-forms-field-turnstile", "brf-pro-forms-field-summary-button", "brf-pro-forms-field-conditional-wrapper");

            // If the name not contains brf-pro-forms, we can skip this child
            if (strpos($child['element']['name'], 'brf-pro-forms-field') === false) {
                // If the child has its own children, process those too
                if (isset($child['element']['children']) && !empty($child['element']['children'])) {
                    $this->process_children($child['element']['children'], $post_id, $form_fields, $post_context);
                }

                continue;
            } else {
                // If the child has its own children, process those too
                if (isset($child['element']['children']) && !empty($child['element']['children'])) {
                    $this->process_children($child['element']['children'], $post_id, $form_fields, $post_context);
                }
            }

            $label = isset($child['element']['settings']['label']) ? $child['element']['settings']['label'] : '';
            $id = isset($child['element']['settings']['id']) ? $child['element']['settings']['id'] : null;

            $field = array(
                'label' => $label,
                'id' => $id,
            );

            if (!in_array($child['element']['name'], $ignored_names)) {
                array_push($form_fields, $field);
            }
        }
    }

    public function is_form_mutated($original_structure, $payload)
    {
        $mutated = false;

        $select_fields = array_filter($original_structure, function ($field) {
            return $field['name'] === 'select';
        });

        $checkbox_fields = array_filter($original_structure, function ($field) {
            return $field['name'] === 'checkbox-wrapper';
        });

        $radio_fields = array_filter($original_structure, function ($field) {
            return $field['name'] === 'radio-wrapper';
        });

        $text_fields = array_filter($original_structure, function ($field) {
            return $field['name'] === 'text';
        });

        // Handle select fields
        if (count($select_fields) > 0) {
            foreach ($select_fields as $field) {
                $field_id = $field['field_id'];
                $options = $field['children'];

                $option_values = array_map(function ($option) {
                    return $option['field_value'];
                }, $options);

                // If this field id exists in the payload, we need to check if the options have changed
                if (isset($payload["form-field-" . $field_id])) {
                    // We check if the value is one of the option values
                    $value = $payload["form-field-" . $field_id];

                    if (!in_array($value, $option_values)) {
                        return true;
                    }
                }
            }
        }

        // Handle checkbox fields
        if (count($checkbox_fields) > 0) {
            foreach ($checkbox_fields as $field) {
                $field_id = $field['field_id'];
                $values = array_map(function ($option) {
                    return $option['field_value'];
                }, $field['children']);

                // If this field id exists in the payload, we need to check if the options have changed
                if (isset($payload["form-field-" . $field_id])) {
                    // We check if the value is one of the option values
                    $value = $payload["form-field-" . $field_id];

                    // If value is array, we need to check if the values have changed
                    if (is_array($value)) {
                        // Does the array contain any values that are not in the original values?
                        // (We ignore values that have 0 (not checked))
                        foreach ($value as $v) {
                            if ($v != 0 && !in_array($v, $values)) {
                                return true;
                            }
                        }
                    } else {
                        if ($value != 0 && !in_array($value, $values)) {
                            return true;
                        }
                    }
                }
            }
        }

        // Handle radio fields
        if (count($radio_fields) > 0) {
            foreach ($radio_fields as $field) {
                $field_id = $field['field_id'];
                $values = array_map(function ($option) {
                    return $option['field_value'];
                }, $field['children']);

                // If this field id exists in the payload, we need to check if the options have changed
                if (isset($payload["form-field-" . $field_id])) {
                    // We check if the value is one of the option values
                    $value = $payload["form-field-" . $field_id];

                    if (is_array($value)) {
                        if (!in_array($value[0], $values)) {
                            return true;
                        }
                    } else {
                        if (!in_array($value, $values)) {
                            return true;
                        }
                    }
                }
            }
        }

        return $mutated;
    }

    private function process_children_all($children, $post_id, &$form_fields)
    {

        foreach ($children as $c) {
            $child = \Bricks\Helpers::get_element_data($post_id, $c);

            // Initialize an array to hold the structured children
            $structured_children = [];

            // If the child has its own children, recursively process them
            if (isset($child['element']['children']) && !empty($child['element']['children'])) {
                $this->process_children_all($child['element']['children'], $post_id, $structured_children);
            }

            $element_name = isset($child['element']['name']) ? $child['element']['name'] : '';
            $element_id = isset($child['element']['id']) ? $child['element']['id'] : '';
            $field_label = isset($child['element']['settings']['label']) ? $child['element']['settings']['label'] : '';
            $field_value = isset($child['element']['settings']['value']) ? $child['element']['settings']['value'] : '';
            $field_id = isset($child['element']['settings']['id']) ? $child['element']['settings']['id'] : null;

            // For Checkboxes, Radios and Select Fields
            $field_id = $this->get_parent_field_id_if_needed($field_id, $element_id, $post_id);

            // Now, instead of just including the IDs of the children, include their structured data
            // If the element name contains "brf-pro-forms-field", we use only the last part of the name
            if (strpos($element_name, 'brf-pro-forms-field-') !== false) {
                $element_name = explode('brf-pro-forms-field-', $element_name);
                $element_name = end($element_name);
            }

            $field = array(
                'name' => $element_name,
                'field_id' => $field_id,
                'field_label' => $field_label,
                'field_value' => $field_value,
                'settings' => $child['element']['settings'],
                'children' => $structured_children // Here we assign the structured children instead of their IDs
            );

            array_push($form_fields, $field);
        }
    }

    public function get_parent_field_id_if_needed($field_id, $element_id, $post_id)
    {
        if (!class_exists('\Bricks\Helpers')) {
            return $field_id;
        }

        $element = \Bricks\Helpers::get_element_data($post_id, $element_id);

        if (isset($element['element']['parent'])) {
            $parent_id = $element['element']['parent'];
            $parent_element = \Bricks\Helpers::get_element_data($post_id, $parent_id);

            $elements = ["brf-pro-forms-field-checkbox-wrapper", "brf-pro-forms-field-radio-wrapper", "brf-pro-forms-field-select"];

            if (isset($parent_element['element']['name']) && in_array($parent_element['element']['name'], $elements) && isset($parent_element['element']['settings']['id'])) {
                $field_id = $parent_element['element']['settings']['id'];
            }
        }

        return $field_id;
    }

    public function build_form_structure($post_id, $form_id)
    {
        // As result, we need a JSON object with the form structure
        $form_structure = [];

        $form = \Bricks\Helpers::get_element_data($post_id, $form_id);
        $form_children = $form['element']['children'];

        if (isset($form_children) && !empty($form_children)) {
            $this->process_children_all($form_children, $post_id, $form_structure);
        }

        return $form_structure;
    }

    public function get_form_fields_from_ids($form_settings, $form_data, $post_id, $form_id, $post_context)
    {
        $form_fields = array();

        $form = \Bricks\Helpers::get_element_data($post_id, $form_id);
        $form_children = $form['element']['children'];

        // If $form_settings['fields'] not exists or is empty
        if (!isset($form_settings['fields']) || empty($form_settings['fields'])) {

            // Is using Nestable Pro Forms here. We need to collect the Pro Forms Children
            if (isset($form_children) && !empty($form_children)) {
                $this->process_children($form_children, $post_id, $form_fields, $post_context);
            }

            return $form_fields;
        }

        foreach ($form_data as $field_id => $field_value) {
            // Remove "form-field-" prefix from field ID
            $clean_field_id = str_replace('form-field-', '', $field_id);

            // If $field_id contains [] (i.e. it's an array), remove the array brackets
            if (strpos($clean_field_id, '[') !== false) {
                $clean_field_id = str_replace(['[', ']'], '', $clean_field_id);
            }

            // Check whether field ID is included in $form_settings['fields']['id']
            $field = array_filter($form_settings['fields'], function ($field) use ($clean_field_id) {
                return $field['id'] === $clean_field_id;
            });

            // If field is found, add it to $form_fields
            if (count($field) > 0) {
                $field = array_values($field)[0];

                // If $field_value is array, separata by comma. otherwise, just add the value
                if (is_array($field_value)) {
                    $field['value'] = implode(', ', $field_value);
                } else {
                    $field['value'] = $field_value;
                }

                array_push($form_fields, $field);
            }
        }

        return $form_fields;
    }

    public function update_acf_field($field_name, $value, $post_id)
    {
        $field_object = get_field_object($field_name, $post_id);
        $return_format = "string";

        if (isset($field_object['return_format'])) {
            $return_format = $field_object['return_format'];
        }

        if ($return_format === "array") {

            if (is_string($value) && strpos($value, ',') !== false) {
                $value = explode(',', $value);

                // We remove empty spaces for each item in the array
                $value = array_map('trim', $value);
            }
        }

        update_field($field_name, $value, $post_id);
    }

    public function update_metabox_field($field_name, $value, $post_id, $force_array = false)
    {

        $field_settings = rwmb_get_field_settings($field_name, [], $post_id);
        $field_type = "";
        $is_media = false;

        if (isset($field_settings) && isset($field_settings['type'])) {
            $field_type = $field_settings['type'];
        }

        // If the type includes "file", "image" or "video", $is_media is true
        if (strpos($field_type, 'file') !== false || strpos($field_type, 'image') !== false || strpos($field_type, 'video') !== false) {
            $is_media = true;
        }

        // If $value is an array and any children is also an array, we need to flatten the array and add the nested arrays to the parent array
        if (is_array($value)) {

            if ($is_media) {
                // We flatten the array and create an entry for each item
                foreach ($value as $key => $sub_value) {
                    if (is_array($sub_value)) {
                        foreach ($sub_value as $sub_key => $sub_sub_value) {
                            $value[] = $sub_sub_value;
                        }
                        unset($value[$key]);
                    }
                }

                foreach ($value as $field) {
                    add_post_meta($post_id, $field_name, $field, false);
                }
            } else {
                // Otherwise, we stringify the array and add it as a single entry
                if (!$force_array) {
                    $value = implode('', $value);
                }

                rwmb_set_meta($post_id, $field_name, $value);
            }
        } else {
            if ($force_array) {
                $value = array($value);
            }

            rwmb_set_meta($post_id, $field_name, $value);
        }
    }

    public function adjust_meta_field_name($field_name)
    {
        // If Field Name contains acf_ prefix, remove it
        $field_name = str_replace('acf_', '', $field_name);

        return $field_name;
    }

    public function get_form_field_by_id($id, $form_data, $current_post_id = null, $form_settings = null, $form_files = null, $implode_array = true, $force_file_url_output = false)
    {

        // We render dynamic data for nested tags
        $id = bricks_render_dynamic_data($id, $current_post_id);

        foreach ($form_data as $key => $value) {

            $form_id = explode('form-field-', $key);
            $form_id = isset($form_id[1]) ? $form_id[1] : null;

            // If the ID has the format {{id}} or {{ id }}, we replace the variables with the values
            if (isset($id) && strpos($id, '{{') !== false) {
                preg_match_all('/{{([^}]+)}}/', $id, $matches);

                foreach ($matches[1] as $match) {
                    $value = $this->get_form_field_by_id(trim($match), $form_data, $current_post_id);

                    // If value is array, we have files. In that case, we replace each of them
                    if (!is_array($value)) {
                        // If the value remains the same, this variable seems to not exist. We return an empty string.
                        if ($value === $match) {
                            $id = str_replace('{{' . $match . '}}', "", $id);
                        } else {
                            $id = str_replace('{{' . $match . '}}', $value, $id);
                        }
                    } else {
                        $id = $match;
                    }
                }
            }

            if ($form_id === $id || $form_id === $id  . '[]') {

                // Check if there are files in the form data
                if (isset($form_files) && !empty($form_files)) {

                    // If there are files, check if the current field is a file field
                    foreach ($form_files as $file) {

                        // If the file contains _, [0] is the id

                        if (strpos($file['field'], '_') !== false) {
                            $file['field'] = substr($file['field'], 0, strrpos($file['field'], '_'));
                        }

                        if ($file['field'] === $id) {
                            // If it is a file field, handle this file
                            $file_url = $this->handle_file($id, $form_settings, $form_files, 'url', $force_file_url_output);

                            if ($file_url) {
                                return $file_url;
                            }
                        }
                    }
                }

                // If $value is an empty array, return empty string
                if (is_array($value) && empty($value)) {
                    return '';
                }

                // If $value is an array, return comma separated values
                if (is_array($value) && $implode_array) {
                    return implode(', ', bricks_render_dynamic_data($value, $current_post_id));
                }

                return bricks_render_dynamic_data($value, $current_post_id);
            }
        }

        // If $value is an empty array, return empty string
        return bricks_render_dynamic_data($id, $current_post_id);
    }

    public function render_dynamic_formular_data($formula, $form_data, $field_settings)
    {
        $formula = bricks_render_dynamic_data($formula);

        // Find each word wrapped by {}. For each field, we need the value and replace it with the value returned by get_form_field_by_id()
        preg_match_all('/{([^}]+)}/', $formula, $matches);

        foreach ($matches[1] as $match) {
            $field_value = $this->get_form_field_by_id($match, $form_data);

            // If the field value contains a comma, we sum up the values
            if (strpos($field_value, ',') !== false) {
                $field_value = array_sum(explode(',', $field_value));
            }

            if (isset($field_value) && $field_value !== "" && is_numeric($field_value)) {
                $formula = str_replace('{' . $match . '}', $field_value, $formula);
            } else {
                if (isset($field_settings['setEmptyToZero']) && $field_settings['setEmptyToZero']) {
                    $formula = str_replace('{' . $match . '}', 0, $formula);
                }
            }
        }

        return $formula;
    }

    public function process_repeater_values($item, $form)
    {
        $field_name = $form->get_form_field_by_id($item['name']);
        $field_value = $form->get_form_field_by_id($item['value']);

        $is_repeater = isset($item['is_repeater']) && $item['is_repeater'] && isset($item['repeater_values']) && !empty($item['repeater_values']);

        if ($is_repeater) {
            $repeater_values = $item['repeater_values'];

            $repeater_values = array_map(function ($item) use ($form) {
                return $this->process_repeater_values($item, $form);
            }, $repeater_values);

            $field_value = array_reduce($repeater_values, function ($carry, $item) use ($form) {
                $field_name = $item['name'];
                $field_value = $item['value'];

                $carry[$field_name] = $field_value;

                return $carry;
            }, []);

            $field_value = array($field_value);
        }

        return array(
            'name' => $field_name,
            'value' => $field_value
        );
    }

    public function get_repeater_rows($post_id, $post_meta_name)
    {
        if (!isset($post_meta_name)) {
            return;
        }

        $is_acf = class_exists('ACF') && !class_exists('RWMB_Core');
        $is_metabox = class_exists('RWMB_Core');
        $is_jetengine = class_exists('Jet_Engine');
        $is_acpt = class_exists('ACPT');

        $rows = null;

        if ($is_acf && function_exists("get_field")) {
            $rows = get_field($post_meta_name, $post_id);
        }

        if ($is_metabox && function_exists("rwmb_meta")) {
            $rows = rwmb_meta($post_meta_name, ['object_type' => 'post'], $post_id);
        }

        if ($is_acpt && function_exists("get_acpt_meta_field_value")) {
            $rows = get_acpt_meta_field_value([
                'post_id' => $post_id,
                'field_name' => $post_meta_name
            ]);
        }

        if ($is_jetengine) {
            $rows = get_post_meta($post_id, $post_meta_name, true);
        }

        return $rows;
    }

    public function add_repeater_rows($post_meta_name, $repeater_field_data, $post_id, $source, $box_name)
    {
        if (!isset($post_meta_name) || !isset($repeater_field_data)) {
            return;
        }

        // Check if every key of $repeater_field_data is empty
        $is_empty = true;
        foreach ($repeater_field_data as $item) {
            foreach ($item as $value) {
                if (!empty($value)) {
                    $is_empty = false;
                    break 2; // Exit both loops if a non-empty value is found
                }
            }
        }

        if ($is_empty) {
            return;
        }

        $is_acf = $source == "acf" || (class_exists('ACF') && !class_exists('RWMB_Core') && !class_exists('Jet_Engine') && !class_exists('ACPT'));
        $is_metabox = $source == "metabox" || (class_exists('RWMB_Core'));
        $is_jetengine = $source == "jetengine" || class_exists('Jet_Engine');
        $is_acpt = $source == "acpt" || class_exists('ACPT');

        if ($is_acf && function_exists("add_row")) {
            foreach ($repeater_field_data as $row) {
                add_row($post_meta_name, $row, $post_id);
            }
        }

        if ($is_metabox && function_exists("rwmb_set_meta")) {
            $meta_value = rwmb_meta($post_meta_name, '', $post_id);

            if (!is_array($meta_value)) {
                $meta_value = array();
            }

            foreach ($repeater_field_data as $row) {
                $meta_value[] = $row;
            }

            rwmb_set_meta($post_id, $post_meta_name, $meta_value);
        }

        if ($is_jetengine) {
            $meta_value = get_post_meta($post_id, $post_meta_name, true);

            if (!is_array($meta_value)) {
                $meta_value = array();
            }

            foreach ($repeater_field_data as $row) {
                $meta_value[] = $row;
            }

            update_post_meta($post_id, $post_meta_name, $meta_value);
        }

        if ($is_acpt && function_exists("save_acpt_meta_field_value")) {
            $current_rows = get_acpt_field([
                "post_id" => $post_id,
                "box_name" => $box_name,
                "field_name" => $post_meta_name
            ]);

            if (!is_array($current_rows)) {
                $current_rows = array();
            }

            foreach ($repeater_field_data as $row) {
                $current_rows[] = $row;
            }

            save_acpt_meta_field_value([
                'post_id' => $post_id,
                'box_name' => $box_name,
                'field_name' => $post_meta_name,
                "value" => $current_rows
            ]);
        }
    }

    public function update_repeater_rows($post_meta_name, $repeater_field_data, $post_id, $source, $box_name)
    {
        if (!isset($post_meta_name) || !isset($repeater_field_data)) {
            return;
        }

        $is_acf = $source == "acf" || (class_exists('ACF') && !class_exists('RWMB_Core') && !class_exists('Jet_Engine') && !class_exists('ACPT'));
        $is_metabox = $source == "metabox" || (class_exists('RWMB_Core'));
        $is_jetengine = $source == "jetengine" || class_exists('Jet_Engine');
        $is_acpt = $source == "acpt" || class_exists('ACPT');

        // Check if $repeat_field_data is an array
        if (!is_array($repeater_field_data)) {
            return;
        }

        if ($is_acf && function_exists("update_field")) {
            update_field($post_meta_name, $repeater_field_data, $post_id);
        }

        if ($is_metabox && function_exists("rwmb_set_meta")) {
            rwmb_set_meta($post_id, $post_meta_name, $repeater_field_data);
        }

        if ($is_jetengine) {
            update_post_meta($post_id, $post_meta_name, $repeater_field_data);
        }

        if ($is_acpt && function_exists("save_acpt_meta_field_value")) {
            // We first empty the array, as overwriting it is causing issues currently.
            save_acpt_meta_field_value([
                'post_id' => $post_id,
                'box_name' => $box_name,
                'field_name' => $post_meta_name,
                "value" => []
            ]);
            save_acpt_meta_field_value([
                'post_id' => $post_id,
                'box_name' => $box_name,
                'field_name' => $post_meta_name,
                "value" => $repeater_field_data
            ]);
        }
    }

    public function update_repeater_field($post_meta_name, $new_post_meta_value, $post_id, $repeater_row_number, $repeater_row_fields, $repeater_action, $sub_row_name, $sub_row_number, $repeater_box_name)
    {

        if (!isset($post_meta_name) || !isset($repeater_row_fields)) {
            return;
        }

        $is_acf = class_exists('ACF') && !class_exists('RWMB_Core');
        $is_metabox = class_exists('RWMB_Core');
        $is_jetengine = class_exists('Jet_Engine');
        $is_acpt = class_exists('ACPT');

        $sub_row_args = null;

        $repeater_row_number = intval($repeater_row_number);

        $meta_value = null; // Stores the current meta value if needed

        if (strpos($repeater_action, 'sub_row') !== false && strpos($sub_row_name, '.') !== false) {
            $sub_row_name = explode('.', $sub_row_name);
            $sub_row_args = array_merge([$post_meta_name, $repeater_row_number], $sub_row_name);
        }

        if ($is_acf && function_exists("update_row")) {
            switch ($repeater_action) {
                case 'add_row':
                    add_row($post_meta_name, $repeater_row_fields, $post_id);
                    break;
                case 'update_row':
                    update_row($post_meta_name, $repeater_row_number, $repeater_row_fields, $post_id);
                    break;
                case 'remove_row':
                    delete_row($post_meta_name, $repeater_row_number, $post_id);
                    break;
                case 'add_sub_row':
                    add_sub_row($sub_row_args, $repeater_row_fields, $post_id);
                    break;
                case 'update_sub_row':
                    update_sub_row($sub_row_args, $sub_row_number, $repeater_row_fields, $post_id);
                    break;
                case 'remove_sub_row':
                    delete_sub_row($sub_row_args, $sub_row_number, $post_id);
                    break;
            }
        }

        if ($is_metabox && function_exists("rwmb_set_meta")) {
            $meta_value = rwmb_meta($post_meta_name, ['object_type' => 'post'], $post_id);

            switch ($repeater_action) {
                case 'add_row':
                    $meta_value[] = $repeater_row_fields;
                    break;
                case 'update_row':
                    $meta_value[$repeater_row_number] = $repeater_row_fields;
                    break;
                case 'remove_row':
                    unset($meta_value[$repeater_row_number]);
                    break;
                case 'add_sub_row':
                    $meta_value = $this->handle_deep_sub_rows("add", $meta_value, $sub_row_name, $repeater_row_fields, $repeater_row_number, $sub_row_number);
                    rwmb_set_meta($post_id, $post_meta_name, $meta_value);
                    break;
                case 'update_sub_row':
                    $meta_value = $this->handle_deep_sub_rows("update", $meta_value, $sub_row_name, $repeater_row_fields, $repeater_row_number, $sub_row_number);
                    rwmb_set_meta($post_id, $post_meta_name, $meta_value);
                    break;
                case 'remove_sub_row':
                    $meta_value = $this->handle_deep_sub_rows("remove", $meta_value, $sub_row_name, $repeater_row_fields, $repeater_row_number, $sub_row_number);
                    rwmb_set_meta($post_id, $post_meta_name, $meta_value);
                    break;
            }

            rwmb_set_meta($post_id, $post_meta_name, $meta_value);
        }

        if ($is_acpt && function_exists("add_acpt_meta_field_row_value")) {
            switch ($repeater_action) {
                case 'add_row':
                    add_acpt_meta_field_row_value([
                        'post_id' => $post_id,
                        'box_name' => $repeater_box_name,
                        'field_name' => $post_meta_name,
                        "value" => $repeater_row_fields
                    ]);
                    break;
                case 'update_row':
                    edit_acpt_meta_field_row_value([
                        'post_id' => $post_id,
                        'box_name' => $repeater_box_name,
                        'field_name' => $post_meta_name,
                        'index' => $repeater_row_number,
                        "value" => $repeater_row_fields
                    ]);
                    break;
                case 'remove_row':
                    // Todo: There are some issues with delete_acpt_meta_field_row_value() currently. We do this later.
                    break;

                    /* delete_acpt_meta_field_row_value([
                        'post_id' => $post_id,
                        'box_name' => $repeater_box_name,
                        'field_name' => $post_meta_name,
                        "index" => 2
                    ]);

                    break; */
            }
        }

        if ($is_jetengine) {
            $meta_value = get_post_meta($post_id, $post_meta_name, true);

            switch ($repeater_action) {
                case 'add_row':
                    $meta_value["item-" . count($meta_value)] = $repeater_row_fields;

                    update_post_meta($post_id, $post_meta_name, $meta_value);
                    break;
                case 'update_row':
                    $meta_value["item-" . $repeater_row_number] = $repeater_row_fields;

                    update_post_meta($post_id, $post_meta_name, $meta_value);
                    break;
                case 'remove_row':
                    unset($meta_value["item-" . $repeater_row_number]);

                    update_post_meta($post_id, $post_meta_name, $meta_value);
                    break;
            }
        }
    }

    function handle_deep_sub_rows($action = "add", $current_value = null, $keys = null, $new_row_fields = null, $repeater_row_number = null, $sub_row_number = null)
    {
        if (!isset($current_value[$repeater_row_number])) {
            return $current_value;
            //$current_value[$repeater_row_number] = array();
        }

        $base = &$current_value[$repeater_row_number];

        foreach ($keys as $depth => $key) {
            // Check if we are at the last key
            if ($depth === count($keys) - 1) {
                // If we are at the last key, we need to append or set the new_row_fields
                if (!isset($base[$key])) {
                    $base[$key] = array(); // Initialize if not available
                }

                switch ($action) {
                    case "add":
                        $base[$key][] = $new_row_fields;
                        break;
                    case "update":
                        $base[$key][$sub_row_number] = $new_row_fields;
                        break;
                    case "remove":
                        unset($base[$key][$sub_row_number]);
                        break;
                }
            } else {
                // If it's not the last key, navigate or create the nested array
                if (!isset($base[$key]) || !is_array($base[$key])) {
                    $base[$key] = array(); // Initialize the array if it does not exist
                }
                $base = &$base[$key]; // Move the reference deeper
            }
        }

        return $current_value;
    }

    public function sanitize_value($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $sub_value) {
                $value[$key] = $this->sanitize_value($sub_value);
            }
        } elseif (is_numeric($value)) {
            $value = preg_replace('/[^0-9,.]/', '', $value);
        } else {
            $value = wp_kses_post($value);
        }
        return $value;
    }

    public function get_sensitive_options()
    {
        $sensitive_options = [
            // Core WordPress options
            'siteurl',
            'home',
            'blogname',
            'blogdescription',
            'admin_email',
            'default_role',
            'uploads_use_yearmonth_folders',
            'auth_key',
            'secure_auth_key',
            'logged_in_key',
            'nonce_key',
            'auth_salt',
            'secure_auth_salt',
            'logged_in_salt',
            'nonce_salt',
            'permalink_structure',
            'category_base',
            'tag_base',
            'cron',
            'db_version',
            'rss_language',
            'template',
            'stylesheet',
            'current_theme',
            'sidebars_widgets',
            'rewrite_rules',
            'users_can_register',
            'default_category',
            'timezone_string',
            'date_format',
            'time_format',
            'posts_per_page',
            'thread_comments',
            'require_name_email',

            // Multisite options
            'site_admins',
            'allowedthemes',
            'active_plugins',
            'active_sitewide_plugins',
            'site_active_plugins',
            'blog_public',
            'upload_space_check_disabled',
            'fileupload_maxk',

            // WooCommerce
            'woocommerce_store_address',
            'woocommerce_store_email',
            'woocommerce_tax_settings',
            'woocommerce_payment_gateways',
            'woocommerce_shipping_settings',
            'woocommerce_db_version',
            'woocommerce_currency',
            'woocommerce_enable_guest_checkout',

            // SEO Plugins
            '_yoast_wpseo_meta',
            'wpseo_titles',
            'rank_math_options_general',
            'rank_math_redirections',

            // Caching Plugins
            'wp_cache_enabled',
            'wp_cache_mod_rewrite',
            'w3tc_config',

            // Security Plugins
            'wordfence_api_key',
            'wordfence_waf_config',
            'wordfence_scan_schedule',
            'itsec_global',
            'itsec_lockouts',
            'itsec_ban',

        ];

        return $sensitive_options;
    }

    public function initial_sanitization($form_settings, $form_data, $field_ids, $post_id)
    {
        if (!isset($form_data)) {
            return $form_data;
        }

        $valid_ids = [];
        $processed_ids = [];

        // Sanitize Form Fields
        if (isset($form_settings['fields'])) {
            foreach ($form_settings['fields'] as $field) {
                if (isset($field['stripHTML']) && $field['stripHTML'] === true) {
                    $field_id = $field['id'];
                    $form_data['form-field-' . $field_id] = wp_strip_all_tags($form_data['form-field-' . $field_id]);
                }

                if (!empty($field['id'])) {
                    // Get & set 'id' from custom 'name' (e.g.: 'post-{post_id} to 'form-field-{{field_id}}')
                    if (!empty($field['name'])) {
                        $field_name = bricks_render_dynamic_data($field['name'], $_POST['postId']);
                        if (isset($form_data[$field_name])) {
                            $field_value = $form_data[$field_name];
                            $form_data["form-field-{$field['id']}"] = $field_value;
                        }
                    }

                    $valid_ids[] = $field['id'];
                }
            }
        }

        // Retrieve original ID for each form data
        if (isset($field_ids) && !empty($field_ids)) {
            foreach ($field_ids as $custom_id => $original_id) {
                if (isset($form_data["form-field-{$custom_id}"])) {
                    $settings = \Bricks\Helpers::get_element_settings($post_id, $original_id);

                    if (isset($settings) && isset($settings['stripHTML']) && $settings['stripHTML'] == true) {
                        $form_data["form-field-{$custom_id}"] = wp_strip_all_tags($form_data["form-field-{$custom_id}"]);
                    }
                }

                // Add valid ID to $valid_ids
                $valid_ids[] = $original_id;
                $valid_ids[] = $custom_id;
            }
        }

        // Validate Form Fields
        foreach (array_keys($form_data) as $key) {
            // Check if submitted form field ID is valid
            if (strpos($key, 'form-field-') === 0) {
                $field_id = str_replace('form-field-', '', $key);

                // Skip: Field ID has already been processed (e.g.: HTML duplicated)
                if (in_array($field_id, $processed_ids)) {
                    // Reject the submission as potentially malicious
                    wp_send_json_error([
                        'message' => esc_html__('An error occurred, please try again later.', 'bricksforge'),
                    ]);
                }

                // Add field ID to list of processed IDs
                $processed_ids[] = $field_id;

                if (!in_array($field_id, $valid_ids)) {
                    // Reject the submission as potentially malicious
                    wp_send_json_error([
                        'message' => esc_html__('An error occurred, please try again later.', 'bricksforge'),
                    ]);
                }
            }
        }

        return $form_data;
    }

    public function validate($field_ids, $form_data, $post_id, $hidden_fields, $fields_to_validate)
    {
        $validation_rules = [];
        $validation_errors = [];

        if (isset($field_ids) && !empty($field_ids)) {
            foreach ($field_ids as $custom_id => $original_id) {

                if (isset($form_data["form-field-{$custom_id}"]) || in_array($custom_id, $fields_to_validate)) {
                    // If $custom_id is included in $hidden_fields, skip validation
                    if (isset($hidden_fields)) {
                        if (is_array($hidden_fields) && in_array(trim($custom_id), $hidden_fields)) {
                            continue;
                        }
                    }

                    // If the original ID contains [], we need to remove it
                    if (strpos($original_id, '[') !== false) {
                        $original_id = str_replace(['[', ']'], '', $original_id);
                    }

                    $settings = \Bricks\Helpers::get_element_settings($post_id, $original_id);

                    $label = isset($settings['label']) ? $settings['label'] : 'Field';

                    if (isset($settings) && isset($settings['validation'])) {
                        $validation_rules["form-field-{$custom_id}"] = [$settings['validation'], $label];
                    }
                }
            }
        }

        foreach ($validation_rules as $field => $rules) {
            $label = $rules[1];

            foreach ($rules[0] as $rule) {
                $type = $rule['type'];

                switch ($type) {
                    case 'required':
                        if (!isset($form_data[$field])) {
                            $validation_errors[] = [
                                'field' => $field,
                                'message' => isset($rule['message']) && $rule['message'] ? $rule['message'] : __("{$label} is required.", 'bricksforge'),
                            ];

                            break;
                        }
                        if (empty(str_replace(' ', '', $form_data[$field]))) {
                            $validation_errors[] = [
                                'field' => $field,
                                'message' => isset($rule['message']) && $rule['message'] ? $rule['message'] : __("{$label} is required.", 'bricksforge'),
                            ];

                            break;
                        }

                        // Handle Checkboxes and Radio Buttons
                        if (is_array($form_data[$field])) {
                            $empty = true;
                            foreach ($form_data[$field] as $value) {
                                if (!empty($value)) {
                                    $empty = false;
                                    break;
                                }
                            }

                            if ($empty) {
                                $validation_errors[] = [
                                    'field' => $field,
                                    'message' => isset($rule['message']) && $rule['message'] ? $rule['message'] : __("{$label} is required.", 'bricksforge'),
                                ];
                            }
                        }


                        break;
                    case 'email':
                        if (!empty($form_data[$field]) && !is_email($form_data[$field])) {
                            $validation_errors[] = [
                                'field' => $field,
                                'message' => isset($rule['message']) && $rule['message'] ? $rule['message'] : __("{$label} is not a valid email address.", 'bricksforge'),
                            ];
                        }
                        break;
                    case 'number':
                        if (!empty($form_data[$field]) && !is_numeric($form_data[$field])) {
                            $validation_errors[] = [
                                'field' => $field,
                                'message' => isset($rule['message']) && $rule['message'] ? $rule['message'] : __("{$label} is not a valid number.", 'bricksforge'),
                            ];
                        }
                        break;
                    case 'url':
                        if (!empty($form_data[$field]) && !filter_var($form_data[$field], FILTER_VALIDATE_URL)) {
                            $validation_errors[] = [
                                'field' => $field,
                                'message' => isset($rule['message']) && $rule['message'] ? $rule['message'] : __("{$label} is not a valid URL.", 'bricksforge'),
                            ];
                        }
                        break;
                    case 'minChars':
                        if (!isset($rule['charsCount'])) {
                            break;
                        }

                        $rule['charsCount'] = $this->get_form_field_by_id($rule['charsCount'], $form_data);

                        if (isset($form_data[$field]) && !empty($form_data[$field]) && strlen($form_data[$field]) < $rule['charsCount']) {
                            $validation_errors[] = [
                                'field' => $field,
                                'message' => isset($rule['message']) && $rule['message'] ? $rule['message'] : __("{$label} must be at least {$rule['charsCount']} characters long.", 'bricksforge'),
                            ];
                        }
                        break;
                    case 'maxChars':
                        if (!isset($rule['charsCount'])) {
                            break;
                        }

                        $rule['charsCount'] = $this->get_form_field_by_id($rule['charsCount'], $form_data);

                        if (isset($form_data[$field]) && !empty($form_data[$field]) && strlen($form_data[$field]) > $rule['charsCount']) {
                            $validation_errors[] = [
                                'field' => $field,
                                'message' => isset($rule['message']) && $rule['message'] ? $rule['message'] : __("{$label} must be at most {$rule['charsCount']} characters long.", 'bricksforge'),
                            ];
                        }
                        break;
                    case 'value':
                        if (!isset($rule['value'])) {
                            break;
                        }

                        $rule['value'] = $this->get_form_field_by_id($rule['value'], $form_data);

                        if (isset($form_data[$field]) && !empty($form_data[$field]) && $form_data[$field] !== $rule['value']) {
                            $validation_errors[] = [
                                'field' => $field,
                                'message' => isset($rule['message']) && $rule['message'] ? $rule['message'] : __($label . ' must be equal to ' . $rule['value'], 'bricksforge'),
                            ];
                        }
                        break;
                    case 'custom':
                        // Custom is Regex
                        if (!isset($rule['regex'])) {
                            break;
                        }

                        $rule['regex'] = $this->get_form_field_by_id($rule['regex'], $form_data);

                        if (isset($form_data[$field])) {
                            $regex = '/' . $rule['regex'] . '/';
                            if (!preg_match($regex, $form_data[$field])) {
                                $validation_errors[] = [
                                    'field' => $field,
                                    'message' => isset($rule['message']) && $rule['message'] ? $rule['message'] : __("{$label} is not valid.", 'bricksforge'),
                                ];
                            }
                        }
                        break;
                }
            }
        }

        // If there are no validation rules or no validation errors, return true
        if (empty($validation_rules) || empty($validation_errors)) {
            return true;
        }

        return $validation_errors;
    }

    function shunting_yard($infix)
    {
        $infix = trim($infix);

        $output_queue = [];
        $operator_stack = [];
        $precedence = ['+' => 1, '-' => 1, '*' => 2, '/' => 2];

        // Change the regular expression to handle spaces between negative sign and number
        $tokens = preg_split('/\s*([\+\-\*\/\(\)])\s*/', ' ' . $infix, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $prevToken = '';
        foreach ($tokens as $key => $token) {
            // Handle negative numbers
            if ($token === '-' && (($key === 0) || in_array($prevToken, ['+', '-', '*', '/', '(']))) {
                $next_token = array_shift($tokens);
                $token = $token . $next_token;
            }

            if (is_numeric($token)) {
                $output_queue[] = $token;
            } elseif (in_array($token, ['+', '-', '*', '/'])) {
                while (!empty($operator_stack) && isset($precedence[end($operator_stack)]) && $precedence[end($operator_stack)] >= $precedence[$token]) {
                    $output_queue[] = array_pop($operator_stack);
                }
                $operator_stack[] = $token;
            } elseif ($token == '(') {
                $operator_stack[] = $token;
            } elseif ($token == ')') {
                while (!empty($operator_stack) && end($operator_stack) != '(') {
                    $output_queue[] = array_pop($operator_stack);
                }
                if (!empty($operator_stack) && end($operator_stack) == '(') {
                    array_pop($operator_stack);
                } else {
                    return "Mismatched parentheses in the formula.";
                }
            } else {
                return "Invalid character in the formula.";
            }

            $prevToken = $token;
        }

        while (!empty($operator_stack)) {
            if (end($operator_stack) == '(' || end($operator_stack) == ')') {
                return "Mismatched parentheses in the formula.";
            }
            $output_queue[] = array_pop($operator_stack);
        }

        return $output_queue;
    }

    function evaluate_postfix($postfix)
    {
        $stack = [];

        foreach ($postfix as $token) {
            if (is_numeric($token)) {
                array_push($stack, $token);
            } elseif (in_array($token, ['+', '-', '*', '/'])) {
                if (count($stack) < 2) {
                    throw new InvalidArgumentException("Invalid formula structure.");
                }
                $num2 = array_pop($stack);
                $num1 = array_pop($stack);

                switch ($token) {
                    case '+':
                        array_push($stack, $num1 + $num2);
                        break;
                    case '-':
                        array_push($stack, $num1 - $num2);
                        break;
                    case '*':
                        array_push($stack, $num1 * $num2);
                        break;
                    case '/':
                        if ($num2 == 0) {
                            throw new InvalidArgumentException("Division by zero.");
                        }
                        array_push($stack, $num1 / $num2);
                        break;
                }
            }
        }

        if (count($stack) != 1) {
            throw new InvalidArgumentException("Invalid formula structure.");
        }

        return array_pop($stack);
    }

    public function calculate_formula($formula, $form_data, $field_settings)
    {
        $formula = $this->render_dynamic_formular_data($formula, $form_data, $field_settings);

        $postfix = $this->shunting_yard($formula);

        if (is_string($postfix)) { // Check if the returned value is an error message
            return $postfix;
        }

        $result = $this->evaluate_postfix($postfix);

        if (is_string($result)) { // Check if the returned value is an error message
            return $result;
        }

        if (isset($field_settings['roundValue']) && $field_settings['roundValue']) {
            $result = round($result);
        }

        if (isset($field_settings['hasCurrencyFormat']) && $field_settings['hasCurrencyFormat']) {
            $result = number_format($result, 2, '.', '');
        }

        return $result;
    }

    /**
     * Handle Thumbnail for different actions. 
     * Return the attachment ID
     * @param $thumbnail
     * @param $form_settings
     * @param $form_files
     * @return string
     * 
     */
    public function handle_file($file, $form_settings, $form_files, $format = 'id', $force_url_output = false)
    {
        $uploaded_file = $file;
        $file_array = [];

        // Handle Thumbnail
        if ($uploaded_file && isset($form_files) && count($form_files)) {

            $uploaded_file = array_filter($form_files, function ($item) use ($uploaded_file) {

                // If $item['field'] contains _
                if (strpos($item['field'], '_') !== false) {
                    $field_id = substr($item['field'], 0, strrpos($item['field'], '_'));
                    return $uploaded_file === $field_id;
                }

                return $uploaded_file === $item['field'];
            });

            // Reset index of array
            $uploaded_file = array_values($uploaded_file);

            if ($uploaded_file && count($uploaded_file)) {

                foreach ($uploaded_file as $file) {
                    $file_name = $file['name'];

                    $file_path = BRICKSFORGE_UPLOADS_DIR . 'temp/' . $file_name;

                    if (file_exists($file_path)) {
                        // Read the content of the temporary file
                        $file_content = file_get_contents($file_path);

                        // Use wp_upload_bits() to create a copy of the file in the WordPress uploads directory
                        $uploaded_file = wp_upload_bits($file_name, null, $file_content);

                        if (!$uploaded_file['error']) {
                            $file_path = $uploaded_file['file']; // Update the file path to the new file in the WordPress uploads directory
                            $file_type = wp_check_filetype(basename($file_path), null);

                            $attachment = array(
                                'guid'           => $uploaded_file['url'], // Use the URL of the new file in the WordPress uploads directory
                                'post_mime_type' => $file_type['type'],
                                'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_path)),
                                'post_content'   => '',
                                'post_status'    => 'inherit',
                            );

                            $attach_id = wp_insert_attachment($attachment, $file_path);

                            require_once ABSPATH . 'wp-admin/includes/image.php';
                            require_once ABSPATH . 'wp-admin/includes/media.php';

                            $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
                            wp_update_attachment_metadata($attach_id, $attach_data);

                            if ($format === 'url' && !class_exists('ACF') && !class_exists('RW_Meta_Box') && !class_exists('Jet_Engine')) {
                                $file_array[] = wp_get_attachment_url($attach_id);
                            } elseif ($format === 'url' && class_exists('Jet_Engine')) {
                                $file_array[] = wp_get_attachment_url($attach_id);
                            } elseif ($force_url_output) {
                                $file_array[] = wp_get_attachment_url($attach_id);
                            } else {
                                $file_array[] = $attach_id;
                            }
                        }
                    }
                }
            }
        }

        // If file arrays count is 1, return the first item
        if (count($file_array) === 1) {
            return $file_array[0];
        }

        return $file_array;
    }
}
