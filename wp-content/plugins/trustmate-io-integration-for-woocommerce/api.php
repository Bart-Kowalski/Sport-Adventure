<?php

function trustmate_api_create_account($params)
{
    $url = trustmate_get_api_base_url() . '/platforms/register';

    return wp_remote_request($url, array(
        'method' => 'POST',
        'body' => $params,
        'timeout' => 20,
    ));
}

function trustmate_create_invitation($order_id, $language = null)
{
    if (!get_option('trustmate_invitations_enabled')) {
        return;
    }

    $order = wc_get_order($order_id);
    $items = $order->get_items();

    $products_data = array();
    foreach ($items as $item) {
        $product = $item->get_product();
        if ($product && $product->get_id()) {

            $parent = null;
            $category = null;
            if (class_exists('WPSEO_Primary_Term')) {
                $product_id_for_category = $product->get_id();
                if ($product->is_type('variation') && $product->get_parent_id()) {
                    $product_id_for_category = $product->get_parent_id();
                }
                $wpseo_primary_term_id = yoast_get_primary_term_id('product_cat', $product_id_for_category);
                $category_term = get_term($wpseo_primary_term_id);
                $category = is_wp_error($category_term) ? null : $category_term->name;
            }

            if ($product->is_type('variation') && $product->get_parent_id()) {
                $parent = wc_get_product($product->get_parent_id());
            }

            if (!$category) {
                // For variable product try to get category from parent
                if ($parent) {
                    $term_names = wp_get_post_terms($parent->get_id(), 'product_cat', ['fields' => 'names']);
                } else {
                    $term_names = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);
                }

                $category = $term_names ? join(' > ', $term_names) : '';
            }

            $group = $product->get_parent_id();
            // product variant currently doesn't work with wpml (even when WPML is turned off)
            if (class_exists('SitePress')) {
                global $sitepress;
                $group = apply_filters('wpml_object_id', $product->get_id(), 'product', false, $sitepress->get_default_language());
            }

            $thumb = wp_get_attachment_image_src($product->get_image_id(), 'thumbnail');

            $price = null;
            $price = $product->get_price();
            if (!$price && $parent) {
                $price = $parent->get_price();
            }

            $availability = null;
            if ($product->get_stock_quantity() !== null) {
                $availability = $product->get_stock_quantity() > 0 ? 1 : 2;
            } elseif ($parent) {
                $availability = $parent->get_stock_quantity() > 0 ? 1 : 2;
            }
            if (!$availability) {
                switch ($product->get_stock_status()) {
                    case 'instock': $availability = 1;
                        break;
                    case 'outofstock': $availability = 2;
                        break;
                    default: break;
                }
                if (!$availability && $parent) {
                    switch ($parent->get_stock_status()) {
                        case 'instock': $availability = 1;
                            break;
                        case 'outofstock': $availability = 2;
                            break;
                        default: break;
                    }
                }
            }

            $gtin = null;
            if (class_exists('RankMathPro')) {
                $gtin = get_post_meta($parent ? $parent->get_id() : $product->get_id(), '_rank_math_gtin_code', true);
                if (!$gtin) {
                    $gtin = null;
                }
            }

            $products_data[] = array(
                'id' => $product->get_id(),
                'priority' => $product->get_price(),
                'name' => $product->get_name(),
                'product_url' => $product->get_permalink(),
                'image_url' => wp_get_attachment_url($product->get_image_id()),
                'image_thumb_url' => !empty($thumb[0]) ? $thumb[0] : null,
                'category' => $category,
                'group_id' => $group,
                'sku' => $product->get_sku(),
                'gtin' => $gtin,
                'description' => $product->get_short_description() ?: strip_tags($product->get_description()),
                'price' => $price,
                'currency' => get_woocommerce_currency(),
                'availability' => $availability,
            );
        }
    }

    $uuid = get_option('trustmate_account_uuid');
    if ($language_uuids = get_option('trustmate_account_language_uuids')) {
        $language_uuids = json_decode($language_uuids, true);
        if (isset($language_uuids[$language])) {
            $uuid = $language_uuids[$language];
        }
    }

    $invitation_data = array(
        'name' => $order->get_billing_first_name(),
        'email' => $order->get_billing_email(),
        'orderNumber' => $order->get_order_number(),
        'uuid' => $uuid,
        'products' => $products_data,
        'signature' => md5($order->get_billing_email() . $uuid),
        'language' => $language,
        'sourceType' => 'woo',
    );

    if ($order->get_date_created()) {
        $invitation_data['orderCreatedAt'] = $order->get_date_created()->format('Y-m-d H:i:s');
    }

    $response = wp_remote_request(trustmate_get_api_base_url() . '/platforms/invitation', array(
        'method' => 'POST',
        'body' => json_encode($invitation_data),
    ));

    if (is_wp_error($response)) {
        wp_remote_request(trustmate_get_api_base_url() . '/platforms/error', array(
            'method' => 'POST',
            'body' => array(
                'uuid' => $uuid,
                'error' => implode(' ', $response->get_error_messages()),
                'host' => $_SERVER['HTTP_HOST'],
            ),
        ));
    }
}

function trustmate_get_current_uuid()
{
    $uuid = get_option('trustmate_account_uuid');
    if ($language_uuids = get_option('trustmate_account_language_uuids')) {
        $language_uuids = json_decode($language_uuids, true);

        $language = null;
        if (class_exists('SitePress')) {
            $language = apply_filters('wpml_current_language', null);
        }
        elseif (function_exists('pll_current_language')) {
            $language = pll_current_language();
        }

        if (isset($language_uuids[$language])) {
            $uuid = $language_uuids[$language];
        }
    }

    return $uuid;
}

function trustmate_update_settings($instant_reviews)
{
    $url = trustmate_get_api_base_url() . '/platforms/account/' . get_option('trustmate_account_uuid') . '/settings';

    return wp_remote_request($url, array(
        'method' => 'PATCH',
        'body' => json_encode(array('instantReviewActive' => (bool) $instant_reviews)),
        'timeout' => 20,
    ));
}

function trustmate_papi_install()
{
    global $wp_version;

    $invitations_option = get_option('trustmate_invitations_enabled');
    $parsed_url = parse_url(get_site_url());
    $domain = $parsed_url['host'];

    $data = array(
        'action' => 'install',
        'shop_id' => $domain,
        'email' => get_bloginfo('admin_email'),
        'shop_url' => get_site_url(),
        'uuid' => get_option('trustmate_account_uuid'),
        'invitations' => (int) (bool) $invitations_option,
    );

    switch ($invitations_option) {
        case 1:
            $data['create_invitation_on'] = 1; // created
            break;

        case 2:
            $data['create_invitation_on'] = 2; // paid
            break;

        case 3:
            $data['create_invitation_on'] = 4; // completed
            break;
    }

    wp_remote_request(
        trustmate_papi_get_base_url() . '/woo',
        array(
            'method' => 'POST',
            'body' => json_encode($data),
        )
    );

    $additional_info = array(
        'muskrat' => (int) get_option('trustmate_widget_muskrat'),
        'muskrat2' => (int) get_option('trustmate_widget_muskrat2'),
        'bee' => (int) get_option('trustmate_widget_bee'),
        'lemur' => (int) get_option('trustmate_widget_lemur'),
        'product_ferret' => (int) get_option('trustmate_widget_product_ferret'),
        'product_ferret2' => (int) get_option('trustmate_widget_product_ferret2'),
        'hydra' => (int) get_option('trustmate_widget_hydra'),
        'gorilla' => (int) get_option('trustmate_widget_gorilla'),
        'badger' => (int) get_option('trustmate_widget_badger'),
        'badger2' => (int) get_option('trustmate_widget_badger2'),
        'alpaca' => (int) get_option('trustmate_widget_alpaca'),
        'ferret' => (int) get_option('trustmate_widget_ferret'),
        'ferret2' => (int) get_option('trustmate_widget_ferret2'),
        'chupacabra' => (int) get_option('trustmate_widget_chupacabra'),
        'hornet' => (int) get_option('trustmate_widget_hornet'),
        'owl' => (int) get_option('trustmate_widget_owl'),
        'instant_review' => (int) get_option('trustmate_instant_review'),
    );

    $woo_version = 'None';
    $trustmate_version = 'None';
    foreach (get_plugins() as $key => $details) {
        if ($key === 'woocommerce/woocommerce.php') {
            $woo_version = $details['Version'];
        }
        if (strpos($key, 'trustmate-plugin.php') !== false) {
            $trustmate_version = $details['Version'];
        }
        if (
            strpos($details['Name'], 'Rank Math') !== false
            || strpos($details['Name'], 'Yoast SEO') !== false
            || strpos($details['Name'], 'Polylang for WooCommerce') !== false
            || strpos($details['Name'], 'WooCommerce Multilingual & Multicurrency with WPML') !== false
        ) {
            $additional_info[$details['Name']] = 1;
        }
    }

    // metadata
    $data = array(
        'additional_info' => $additional_info,
        'language' => get_bloginfo('language'),
        'platform_language' => 'PHP '.phpversion(),
        'platform' => 'WP '.$wp_version,
        'platform_module' => 'Woo '.$woo_version,
        'trustmate_plugin_version' => $trustmate_version,
    );

    wp_remote_request(
        trustmate_papi_get_base_url() . '/shop_metadata/' . $domain,
        array(
            'method' => 'POST',
            'body' => json_encode($data),
        )
    );
}

function trustmate_papi_uninstall()
{
    $parsed_url = parse_url(get_site_url());
    $domain = $parsed_url['host'];

    $data = array(
        'action' => 'uninstall',
        'shop_id' => $domain,
    );

    wp_remote_request(
        trustmate_papi_get_base_url() . '/woocommerce',
        json_encode($data)
    );
}

function trustmate_papi_get_base_url()
{
    $api_url = trustmate_get_api_base_url();

    if (strpos($api_url, 'trustmate.test') !== false) {
        return 'http://172.17.0.1:8666';
    }

    return str_replace('://', '://papi.', $api_url);
}
