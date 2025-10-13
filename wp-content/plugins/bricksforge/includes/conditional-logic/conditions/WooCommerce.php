<?php

namespace Bricksforge\ConditionalLogic\Group;

if (!defined('ABSPATH')) {
    exit;
}

class WooCommerce
{

    public static function build_options()
    {
        $options = [];

        // Items In Cart
        $options[] = [
            'key'   => 'bricksforge_wc_items_in_cart',
            'label' => esc_html__('Items In Cart (Count)', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                ],
                'placeholder' => esc_html__('>', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('0', 'bricksforge'),
            ],
        ];

        // Product In Cart
        $options[] = [
            'key'   => 'bricksforge_wc_product_in_cart',
            'label' => esc_html__('Product In Cart', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is In Cart', 'bricksforge'),
                    '!=' => esc_html__('Is Not In Cart', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is In Cart', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('Product ID', 'bricksforge'),
            ],
        ];

        // Cart Total
        $options[] = [
            'key'   => 'bricksforge_wc_cart_total',
            'label' => esc_html__('Cart Total', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                ],
                'placeholder' => esc_html__('>', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'text',
                'placeholder' => esc_html__('0', 'bricksforge'),
            ],
        ];

        // Cart Subtotal
        $options[] = [
            'key'   => 'bricksforge_wc_cart_subtotal',
            'label' => esc_html__('Cart Subtotal', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                ],
                'placeholder' => esc_html__('>', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'text',
                'placeholder' => esc_html__('0', 'bricksforge'),
            ],
        ];

        // Product in stock
        $options[] = [
            'key'   => 'bricksforge_wc_product_in_stock',
            'label' => esc_html__('Product In Stock', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is in stock', 'bricksforge'),
                    '!=' => esc_html__('Is not in stock', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is in stock', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('Product ID', 'bricksforge'),
            ],
        ];

        // Product is downloadable
        $options[] = [
            'key'   => 'bricksforge_wc_product_downloadable',
            'label' => esc_html__('Product Downloadable', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is downloadable', 'bricksforge'),
                    '!=' => esc_html__('Is not downloadable', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is downloadable', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('Product ID', 'bricksforge'),
            ],
        ];

        // Product is purchasable
        $options[] = [
            'key'   => 'bricksforge_wc_product_purchasable',
            'label' => esc_html__('Product Purchasable', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is purchasable', 'bricksforge'),
                    '!=' => esc_html__('Is not purchasable', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is purchasable', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('Product ID', 'bricksforge'),
            ],
        ];

        // Product is on sale
        $options[] = [
            'key'   => 'bricksforge_wc_product_on_sale',
            'label' => esc_html__('Product On Sale', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is on sale', 'bricksforge'),
                    '!=' => esc_html__('Is not on sale', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is on sale', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('Product ID', 'bricksforge'),
            ],
        ];

        // Product Is Featured
        $options[] = [
            'key'   => 'bricksforge_wc_product_is_featured',
            'label' => esc_html__('Product Is Featured', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is featured', 'bricksforge'),
                    '!=' => esc_html__('Is not featured', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is featured', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('Product ID', 'bricksforge'),
            ],
        ];

        // Product Type
        $options[] = [
            'key'   => 'bricksforge_wc_product_type',
            'label' => esc_html__('Product Type', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is', 'bricksforge'),
                    '!=' => esc_html__('Is not', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'select',
                'options'     =>  [
                    'simple' => esc_html__('Simple', 'bricksforge'),
                    'grouped' => esc_html__('Grouped', 'bricksforge'),
                    'external' => esc_html__('External/Affiliate', 'bricksforge'),
                    'variable' => esc_html__('Variable', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Simple', 'bricksforge'),
            ],
        ];

        // Product Stock
        $options[] = [
            'key'   => 'bricksforge_wc_product_stock',
            'label' => esc_html__('Product Stock', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'text',
                'placeholder' => esc_html__('1', 'bricksforge'),
            ],
        ];

        // Product Variation Count
        $options[] = [
            'key'   => 'bricksforge_wc_product_variation_count',
            'label' => esc_html__('Product Variation Count', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('1', 'bricksforge'),
            ],
        ];

        // Product Category
        $options[] = [
            'key'   => 'bricksforge_wc_product_category',
            'label' => esc_html__('Product Category', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Includes', 'bricksforge'),
                    '!=' => esc_html__('Does not include', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Includes', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('category_slug', 'bricksforge'),
            ],
        ];

        // Product Tag
        $options[] = [
            'key'   => 'bricksforge_wc_product_tag',
            'label' => esc_html__('Product Tag', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('Includes', 'bricksforge'),
                    '!=' => esc_html__('Does not include', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Includes', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'text',
                'placeholder' => esc_html__('tag_slug', 'bricksforge'),
            ],
        ];

        // Product Price
        $options[] = [
            'key'   => 'bricksforge_wc_product_price',
            'label' => esc_html__('Product Price', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('200', 'bricksforge'),
            ],
        ];

        // Product Rating
        $options[] = [
            'key'   => 'bricksforge_wc_product_rating',
            'label' => esc_html__('Product Rating', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'text',
                'placeholder' => esc_html__('4', 'bricksforge'),
            ],
        ];

        // Product Weight
        $options[] = [
            'key'   => 'bricksforge_wc_product_weight',
            'label' => esc_html__('Product Weight', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'text',
                'placeholder' => esc_html__('200', 'bricksforge'),
            ],
        ];

        // Is Product Page
        $options[] = [
            'key'   => 'bricksforge_wc_is_product_page',
            'label' => esc_html__('Is Product Page', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('==', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'select',
                'options' => [
                    true => esc_html__('True', 'bricksforge'),
                    false => esc_html__('False', 'bricksforge'),
                ],
            ],
        ];

        // Is Shop Page
        $options[] = [
            'key'   => 'bricksforge_wc_is_shop_page',
            'label' => esc_html__('Is Shop Page', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('==', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'select',
                'options' => [
                    true => esc_html__('True', 'bricksforge'),
                    false => esc_html__('False', 'bricksforge'),
                ],
            ],
        ];

        // Is Cart Page
        $options[] = [
            'key'   => 'bricksforge_wc_is_cart_page',
            'label' => esc_html__('Is Cart Page', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('==', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'select',
                'options' => [
                    true => esc_html__('True', 'bricksforge'),
                    false => esc_html__('False', 'bricksforge'),
                ],
            ],
        ];

        // Is Checkout Page
        $options[] = [
            'key'   => 'bricksforge_wc_is_checkout_page',
            'label' => esc_html__('Is Checkout Page', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('==', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'select',
                'options' => [
                    true => esc_html__('True', 'bricksforge'),
                    false => esc_html__('False', 'bricksforge'),
                ],
            ],
        ];

        // Is Account Page
        $options[] = [
            'key'   => 'bricksforge_wc_is_account_page',
            'label' => esc_html__('Is Account Page', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('==', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'select',
                'options' => [
                    true => esc_html__('Yes', 'bricksforge'),
                    false => esc_html__('No', 'bricksforge'),
                ],
            ],
        ];

        // User has purchased product
        $options[] = [
            'key'   => 'bricksforge_wc_user_has_purchased_product',
            'label' => esc_html__('User has purchased product', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Has Purchased', 'bricksforge'),
                    '!=' => esc_html__('Has Not Purchased', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('Product ID', 'bricksforge'),
            ],
        ];

        // User Order Count
        $options[] = [
            'key'   => 'bricksforge_wc_user_order_count',
            'label' => esc_html__('User Order Count', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'text',
                'placeholder' => esc_html__('1', 'bricksforge'),
            ],
        ];

        // User Total Spend
        $options[] = [
            'key'   => 'bricksforge_wc_user_total_spend',
            'label' => esc_html__('User Total Spend', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'text',
                'placeholder' => esc_html__('1', 'bricksforge'),
            ],
        ];

        // User Last Active
        $options[] = [
            'key'   => 'bricksforge_wc_user_last_order_days_ago',
            'label' => esc_html__('User Last Order (Days ago)', 'bricksforge'),
            'group' => 'group_bricksforge_woocommerce',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type' => 'text',
                'placeholder' => esc_html__('1', 'bricksforge'),
            ],
        ];


        return $options;
    }

    public static function result($condition)
    {
        if (!class_exists('WooCommerce')) {
            return true;
        }

        $result = true;

        $compare = isset($condition['compare']) ? $condition['compare'] : '==';
        $user_value = isset($condition['value']) ? \bricks_render_dynamic_data($condition['value']) : '';

        switch ($condition['key']) {
            case 'bricksforge_wc_items_in_cart':
                if (!isset(WC()->cart)) {
                    $result = false;
                    break;
                }

                // If user value is numeric, convert it to integer
                if (is_numeric($user_value)) {
                    $user_value = intval($user_value);
                }

                $cart_contents_count = WC()->cart->get_cart_contents_count();

                // Check for current item number in cart. If matches, save the result
                switch ($compare) {
                    case '==':
                        $result = $cart_contents_count == $user_value;
                        break;
                    case '!=':
                        $result = $cart_contents_count != $user_value;
                        break;
                    case '>=':
                        $result = $cart_contents_count >= $user_value;
                        break;
                    case '<=':
                        $result = $cart_contents_count <= $user_value;
                        break;
                    case '>':
                        $result = $cart_contents_count > $user_value;
                        break;
                    case '<':
                        $result = $cart_contents_count < $user_value;
                        break;
                }

                break;
            case 'bricksforge_wc_cart_total':
                if (!isset(WC()->cart)) {
                    $result = false;
                    break;
                }

                // If user value is numeric, convert it to integer
                if (is_numeric($user_value)) {
                    $user_value = intval($user_value);
                }

                // Get cart total price
                $cart_total = WC()->cart->get_cart_contents_total();

                // Check for current item number in cart. If matches, save the result
                switch ($compare) {
                    case '==':
                        $result = $cart_total == $user_value;
                        break;
                    case '!=':
                        $result = $cart_total != $user_value;
                        break;
                    case '>=':
                        $result = $cart_total >= $user_value;
                        break;
                    case '<=':
                        $result = $cart_total <= $user_value;
                        break;
                    case '>':
                        $result = $cart_total > $user_value;
                        break;
                    case '<':
                        $result = $cart_total < $user_value;
                        break;
                }

                break;

            case 'bricksforge_wc_cart_subtotal':
                if (!isset(WC()->cart)) {
                    $result = false;
                    break;
                }

                // If user value is numeric, convert it to integer
                if (is_numeric($user_value)) {
                    $user_value = intval($user_value);
                }

                // Get cart subtotal price as a float
                $cart_subtotal = WC()->cart->subtotal;

                // Check for current item number in cart. If matches, save the result
                switch ($compare) {
                    case '==':
                        $result = $cart_subtotal == $user_value;
                        break;
                    case '!=':
                        $result = $cart_subtotal != $user_value;
                        break;
                    case '>=':
                        $result = $cart_subtotal >= $user_value;
                        break;
                    case '<=':
                        $result = $cart_subtotal <= $user_value;
                        break;
                    case '>':
                        $result = $cart_subtotal > $user_value;
                        break;
                    case '<':
                        $result = $cart_subtotal < $user_value;
                        break;
                }

                break;
            case 'bricksforge_wc_product_in_cart':

                if (!isset(WC()->cart)) {
                    $result = false;
                    break;
                }

                $cart_contents = WC()->cart->get_cart_contents();

                $product_id = $user_value;

                if (is_numeric($product_id)) {
                    $product_id = intval($product_id);
                }

                $product_in_cart = false;

                foreach ($cart_contents as $cart_item) {
                    if ($cart_item['product_id'] == $product_id) {
                        $product_in_cart = true;
                        break;
                    }
                }

                switch ($compare) {
                    case '==':
                        $result = $product_in_cart;
                        break;
                    case '!=':
                        $result = !$product_in_cart;
                        break;
                }

                break;
            case 'bricksforge_wc_product_in_stock':

                $product_id = $user_value;

                if (is_numeric($product_id)) {
                    $product_id = intval($product_id);
                }

                $product = wc_get_product($product_id);

                if (!$product) {
                    $result = false;
                    break;
                }

                $is_in_stock = $product->is_in_stock();

                switch ($compare) {
                    case '==':
                        $result = $is_in_stock;
                        break;
                    case '!=':
                        $result = !$is_in_stock;
                        break;
                }

                break;
            case 'bricksforge_wc_product_downloadable':

                $product_id = $user_value;

                if (is_numeric($product_id)) {
                    $product_id = intval($product_id);
                }

                $product = wc_get_product($product_id);

                if (!$product) {
                    $result = false;
                    break;
                }

                $is_downloadable = $product->is_downloadable();

                switch ($compare) {
                    case '==':
                        $result = $is_downloadable;
                        break;
                    case '!=':
                        $result = !$is_downloadable;
                        break;
                }

                break;
            case 'bricksforge_wc_product_purchasable':

                $product_id = $user_value;

                if (is_numeric($product_id)) {
                    $product_id = intval($product_id);
                }

                $product = wc_get_product($product_id);

                if (!$product) {
                    $result = false;
                    break;
                }

                $is_purchasable = $product->is_purchasable();

                switch ($compare) {
                    case '==':
                        $result = $is_purchasable;
                        break;
                    case '!=':
                        $result = !$is_purchasable;
                        break;
                }

                break;
            case 'bricksforge_wc_product_on_sale':

                $product_id = $user_value;

                if (is_numeric($product_id)) {
                    $product_id = intval($product_id);
                }

                $product = wc_get_product($product_id);

                if (!$product) {
                    $result = false;
                    break;
                }

                $is_on_sale = $product->is_on_sale();

                switch ($compare) {
                    case '==':
                        $result = $is_on_sale;
                        break;
                    case '!=':
                        $result = !$is_on_sale;
                        break;
                }

                break;
            case 'bricksforge_wc_product_is_featured':

                $product_id = $user_value;

                if (is_numeric($product_id)) {
                    $product_id = intval($product_id);
                }


                $product = wc_get_product($product_id);

                if (!$product) {
                    $result = false;
                    break;
                }


                $is_featured = $product->is_featured();

                switch ($compare) {
                    case '==':
                        $result = $is_featured;
                        break;
                    case '!=':
                        $result = !$is_featured;
                        break;
                }

                break;
            case 'bricksforge_wc_product_type':

                // Product is the current product in the loop
                $product = wc_get_product();

                if (!$product) {
                    $result = false;
                    break;
                }

                $product_type = $product->get_type();

                switch ($compare) {
                    case '==':
                        $result = $product_type == $user_value;
                        break;
                    case '!=':
                        $result = $product_type != $user_value;
                        break;
                }

                break;
            case 'bricksforge_wc_product_variation_count':

                $product = wc_get_product();

                if (!$product) {
                    $result = false;
                    break;
                }

                if (!$product->is_type('variable')) {
                    $result = false;
                    break;
                }

                $variation_count = count($product->get_children());

                if (is_numeric($user_value)) {
                    $user_value = intval($user_value);
                }

                switch ($compare) {
                    case '==':
                        $result = $variation_count == $user_value;
                        break;
                    case '!=':
                        $result = $variation_count != $user_value;
                        break;
                    case '>=':
                        $result = $variation_count >= $user_value;
                        break;
                    case '<=':
                        $result = $variation_count <= $user_value;
                        break;
                    case '>':
                        $result = $variation_count > $user_value;
                        break;
                    case '<':
                        $result = $variation_count < $user_value;
                        break;
                }

                break;
            case 'bricksforge_wc_product_stock':

                $product = wc_get_product();

                if (!$product) {
                    $result = false;
                    break;
                }

                $product_stock = $product->get_stock_quantity();

                if (is_numeric($user_value)) {
                    $user_value = intval($user_value);
                }

                switch ($compare) {
                    case '==':
                        $result = $product_stock == $user_value;
                        break;
                    case '!=':
                        $result = $product_stock != $user_value;
                        break;
                    case '>=':
                        $result = $product_stock >= $user_value;
                        break;
                    case '<=':
                        $result = $product_stock <= $user_value;
                        break;
                    case '>':
                        $result = $product_stock > $user_value;
                        break;
                    case '<':
                        $result = $product_stock < $user_value;
                        break;
                }

                break;
            case 'bricksforge_wc_product_category':

                $product = wc_get_product();

                if (!$product) {
                    $result = false;
                    break;
                }

                $product_categories = $product->get_category_ids();

                $category_slug = $user_value;

                $category = get_term_by('slug', $category_slug, 'product_cat');

                if (!$category && $compare == '==') {
                    $result = false;
                    break;
                }

                $category_id = $category->term_id;

                switch ($compare) {
                    case '==':
                        $result = in_array($category_id, $product_categories);
                        break;
                    case '!=':
                        $result = !in_array($category_id, $product_categories);
                        break;
                }

                break;
            case 'bricksforge_wc_product_tag':

                $product = wc_get_product();

                if (!$product) {
                    $result = false;
                    break;
                }

                $product_tags = $product->get_tag_ids();

                $tag_slug = $user_value;

                $tag = get_term_by('slug', $tag_slug, 'product_tag');

                if (!$tag && $compare == '==') {
                    $result = false;
                    break;
                }

                $tag_id = $tag->term_id;

                switch ($compare) {
                    case '==':
                        $result = in_array($tag_id, $product_tags);
                        break;
                    case '!=':
                        $result = !in_array($tag_id, $product_tags);
                        break;
                }

                break;
            case 'bricksforge_wc_product_price':

                $product = wc_get_product();

                if (!$product) {
                    $result = false;
                    break;
                }

                $product_price = $product->get_price();

                if (is_numeric($user_value)) {
                    $user_value = intval($user_value);
                }

                switch ($compare) {
                    case '==':
                        $result = $product_price == $user_value;
                        break;
                    case '!=':
                        $result = $product_price != $user_value;
                        break;
                    case '>=':
                        $result = $product_price >= $user_value;
                        break;
                    case '<=':
                        $result = $product_price <= $user_value;
                        break;
                    case '>':
                        $result = $product_price > $user_value;
                        break;
                    case '<':
                        $result = $product_price < $user_value;
                        break;
                }

                break;
            case 'bricksforge_wc_product_rating':

                $product = wc_get_product();

                if (!$product) {
                    $result = false;
                    break;
                }

                $product_rating = $product->get_average_rating();

                if (is_numeric($user_value)) {
                    $user_value = intval($user_value);
                }

                switch ($compare) {
                    case '==':
                        $result = $product_rating == $user_value;
                        break;
                    case '!=':
                        $result = $product_rating != $user_value;
                        break;
                    case '>=':
                        $result = $product_rating >= $user_value;
                        break;
                    case '<=':
                        $result = $product_rating <= $user_value;
                        break;
                    case '>':
                        $result = $product_rating > $user_value;
                        break;
                    case '<':
                        $result = $product_rating < $user_value;
                        break;
                }

                break;
            case 'bricksforge_wc_product_weight':

                $product = wc_get_product();

                if (!$product) {
                    $result = false;
                    break;
                }

                $product_weight = $product->get_weight();

                if (is_numeric($user_value)) {
                    $user_value = intval($user_value);
                }

                switch ($compare) {
                    case '==':
                        $result = $product_weight == $user_value;
                        break;
                    case '!=':
                        $result = $product_weight != $user_value;
                        break;
                    case '>=':
                        $result = $product_weight >= $user_value;
                        break;
                    case '<=':
                        $result = $product_weight <= $user_value;
                        break;
                    case '>':
                        $result = $product_weight > $user_value;
                        break;
                    case '<':
                        $result = $product_weight < $user_value;
                        break;
                }

                break;
            case 'bricksforge_wc_is_cart_page':

                $is_cart_page = is_cart();

                if ($user_value == true) {
                    $result = $is_cart_page;
                } else {
                    $result = !$is_cart_page;
                }

                break;
            case 'bricksforge_wc_is_product_page':

                $is_product_page = is_product();

                if ($user_value == true) {
                    $result = $is_product_page;
                } else {
                    $result = !$is_product_page;
                }

                break;
            case 'bricksforge_wc_is_shop_page':

                $is_shop_page = is_shop();

                if ($user_value == true) {
                    $result = $is_shop_page;
                } else {
                    $result = !$is_shop_page;
                }

                break;
            case 'bricksforge_wc_is_checkout_page':

                $is_checkout_page = is_checkout();

                if ($user_value == true) {
                    $result = $is_checkout_page;
                } else {
                    $result = !$is_checkout_page;
                }

                break;
            case 'bricksforge_wc_is_account_page':

                $is_account_page = is_account_page();

                if ($user_value == true) {
                    $result = $is_account_page;
                } else {
                    $result = !$is_account_page;
                }

                break;
            case 'bricksforge_wc_user_has_purchased_product':

                $product_id = $user_value;

                if (is_numeric($product_id)) {
                    $product_id = intval($product_id);
                }

                $product = wc_get_product($product_id);

                if (!$product) {
                    $result = false;
                    break;
                }

                $user = wp_get_current_user();

                $user_id = $user->ID;

                $user_has_purchased = wc_customer_bought_product($user->user_email, $user_id, $product_id);

                switch ($compare) {
                    case '==':
                        $result = $user_has_purchased;
                        break;
                    case '!=':
                        $result = !$user_has_purchased;
                        break;
                }

                break;
            case 'bricksforge_wc_user_order_count':

                $user = wp_get_current_user();

                $user_id = $user->ID;

                $user_order_count = wc_get_customer_order_count($user_id);

                if (is_numeric($user_value)) {
                    $user_value = intval($user_value);
                }

                switch ($compare) {
                    case '==':
                        $result = $user_order_count == $user_value;
                        break;
                    case '!=':
                        $result = $user_order_count != $user_value;
                        break;
                    case '>=':
                        $result = $user_order_count >= $user_value;
                        break;
                    case '<=':
                        $result = $user_order_count <= $user_value;
                        break;
                    case '>':
                        $result = $user_order_count > $user_value;
                        break;
                    case '<':
                        $result = $user_order_count < $user_value;
                        break;
                }

                break;
            case 'bricksforge_wc_user_total_spend':

                $user = wp_get_current_user();

                $user_id = $user->ID;

                $user_total_spend = wc_get_customer_total_spent($user_id);

                if (is_numeric($user_value)) {
                    $user_value = intval($user_value);
                }

                switch ($compare) {
                    case '==':
                        $result = $user_total_spend == $user_value;
                        break;
                    case '!=':
                        $result = $user_total_spend != $user_value;
                        break;
                    case '>=':
                        $result = $user_total_spend >= $user_value;
                        break;
                    case '<=':
                        $result = $user_total_spend <= $user_value;
                        break;
                    case '>':
                        $result = $user_total_spend > $user_value;
                        break;
                    case '<':
                        $result = $user_total_spend < $user_value;
                        break;
                }

                break;
            case 'bricksforge_wc_user_last_order_days_ago':

                $user = wp_get_current_user();

                $user_id = $user->ID;

                $user_last_order = wc_get_customer_last_order($user_id);

                if (!$user_last_order) {
                    $result = false;
                    break;
                }

                $user_last_order_date = $user_last_order->get_date_created();

                $user_last_order_days_ago = (time() - $user_last_order_date->getTimestamp()) / DAY_IN_SECONDS;

                if (is_numeric($user_value)) {
                    $user_value = intval($user_value);
                }

                switch ($compare) {
                    case '==':
                        $result = $user_last_order_days_ago == $user_value;
                        break;
                    case '!=':
                        $result = $user_last_order_days_ago != $user_value;
                        break;
                    case '>=':
                        $result = $user_last_order_days_ago >= $user_value;
                        break;
                    case '<=':
                        $result = $user_last_order_days_ago <= $user_value;
                        break;
                    case '>':
                        $result = $user_last_order_days_ago > $user_value;
                        break;
                    case '<':
                        $result = $user_last_order_days_ago < $user_value;
                        break;
                }

                break;
            default:
                break;
        }


        return $result;
    }
}
