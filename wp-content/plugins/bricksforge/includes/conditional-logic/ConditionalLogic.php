<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

use Bricksforge\ConditionalLogic\Group\General as GeneralGroup;
use Bricksforge\ConditionalLogic\Group\Post as PostGroup;
use Bricksforge\ConditionalLogic\Group\User as UserGroup;
use Bricksforge\ConditionalLogic\Group\Date as DateGroup;
use Bricksforge\ConditionalLogic\Group\Location as LocationGroup;
use Bricksforge\ConditionalLogic\Group\WooCommerce as WooCommerceGroup;

/**
 * Global Classes Handler
 */
class ConditionalLogic
{
    private $condition_keys = [
        'bricksforge_post_date', 'bricksforge_wc_items_in_cart', 'bricksforge_wc_cart_total', 'bricksforge_wc_cart_subtotal', 'bricksforge_wc_product_in_cart', 'bricksforge_wc_product_in_stock', 'bricksforge_wc_product_downloadable', 'bricksforge_wc_product_on_sale',
        'bricksforge_wc_product_purchasable', 'bricksforge_wc_product_is_featured', 'bricksforge_wc_product_type', 'bricksforge_wc_product_variation_count', 'bricksforge_wc_product_category',
        'bricksforge_wc_product_price', 'bricksforge_wc_is_cart_page', 'bricksforge_wc_is_checkout_page', 'bricksforge_wc_is_account_page', 'bricksforge_wc_is_shop_page', 'bricksforge_wc_is_product_page',
        'bricksforge_wc_user_has_purchased_product', 'bricksforge_wc_user_order_count', 'bricksforge_wc_user_total_spend', 'bricksforge_user_registered_days_ago', 'bricksforge_wc_product_rating', 'bricksforge_wc_product_weight',
        'bricksforge_wc_product_stock', 'bricksforge_wc_product_tag', 'bricksforge_wc_user_last_order_days_ago', 'bricksforge_location_browser_language', 'bricksforge_location_country', 'bricksforge_location_current_language', 'bricksforge_general_number_of_search_results',
        'bricksforge_general_body_class_includes', 'bricksforge_general_current_day', 'bricksforge_general_current_month', 'bricksforge_general_current_year', 'bricksforge_general_publication_has_been_longer_than', 'bricksforge_general_is_parent', 'bricksforge_general_is_child',
        'bricksforge_general_loop_index', 'bricksforge_general_is_front_page', 'bricksforge_general_page_type', 'bricksforge_general_looped_element_id', 'bricksforge_general_post_tag', 'bricksforge_general_plugin_is_active', 'bricksforge_general_post_meta', 'bricksforge_user_meta',
        'bricksforge_user_comment_count', 'bricksforge_user_has_profile_picture', 'bricksforge_user_post_count', 'bricksforge_post_comments_open', 'bricksforge_post_pings_open', 'bricksforge_post_comment_count',
        'bricksforge_post_is_sticky', 'bricksforge_post_has_excerpt', 'bricksforge_post_has_post_thumbnail', 'bricksforge_general_is_multisite', 'bricksforge_general_is_multisite_main', 'bricksforge_general_is_multisite_subdomain'
    ];

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->activated() === true) {
            require_once __DIR__ . '/conditions/Load.php';

            add_filter('bricks/conditions/groups', [$this, 'add_condition_groups']);
            add_filter('bricks/conditions/options', [$this, 'add_condition_options']);
            add_filter('bricks/conditions/result', [$this, 'run_condition_result'], 10, 3);
        }
    }

    public function activated()
    {
        return get_option('brf_activated_tools') && in_array(2, get_option('brf_activated_tools'));
    }

    public function add_condition_groups($groups)
    {

        $groups[] = [
            'name'  => 'group_bricksforge_general',
            'label' => esc_html__('Bricksforge – General', 'bricksforge'),
        ];

        $groups[] = [
            'name'  => 'group_bricksforge_post',
            'label' => esc_html__('Bricksforge – Post', 'bricksforge'),
        ];

        $groups[] = [
            'name'  => 'group_bricksforge_user',
            'label' => esc_html__('Bricksforge – User', 'bricksforge'),
        ];

        $groups[] = [
            'name'  => 'group_bricksforge_date',
            'label' => esc_html__('Bricksforge – Date', 'bricksforge'),
        ];

        $groups[] = [
            'name'  => 'group_bricksforge_location',
            'label' => esc_html__('Bricksforge – Location', 'bricksforge'),
        ];

        if (class_exists('WooCommerce')) {
            $groups[] = [
                'name'  => 'group_bricksforge_woocommerce',
                'label' => esc_html__('Bricksforge – WooCommerce', 'bricksforge'),
            ];
        }

        return $groups;
    }

    public function add_condition_options($options)
    {
        // General
        array_push($options, ...GeneralGroup::build_options());

        // Post
        array_push($options, ...PostGroup::build_options());

        // Date
        array_push($options, ...DateGroup::build_options());

        // User
        array_push($options, ...UserGroup::build_options());

        // Location
        array_push($options, ...LocationGroup::build_options());

        // WooCommerce
        if (class_exists('WooCommerce')) {
            array_push($options, ...WooCommerceGroup::build_options());
        }

        return $options;
    }

    function run_condition_result($result, $condition_key, $condition)
    {
        // If the condition key is not in array $this->condition_keys, return the result
        if (!in_array($condition_key, $this->condition_keys)) {
            return $result;
        }

        $is_general_group = strpos($condition_key, 'bricksforge_general_') !== false;
        $is_post_group = strpos($condition_key, 'bricksforge_post_') !== false;
        $is_user_group = strpos($condition_key, 'bricksforge_user_') !== false;
        $is_date_group = strpos($condition_key, 'bricksforge_date_') !== false;
        $is_location_group = strpos($condition_key, 'bricksforge_location_') !== false;
        $is_woocommerce_group = strpos($condition_key, 'bricksforge_wc_') !== false;

        $group = '';

        if ($is_general_group) {
            $group = 'general';
        } elseif ($is_post_group) {
            $group = 'post';
        } elseif ($is_user_group) {
            $group = 'user';
        } elseif ($is_date_group) {
            $group = 'date';
        } elseif ($is_woocommerce_group) {
            $group = 'woocommerce';
        } else if ($is_location_group) {
            $group = 'location';
        }

        switch ($group) {
                // Post
            case 'general':
                $result = GeneralGroup::result($condition);
                break;
                // Post
            case 'post':
                $result = PostGroup::result($condition);
                break;
                // User
            case 'user':
                $result = UserGroup::result($condition);
                break;
                // Date
            case 'date':
                $result = DateGroup::result($condition);
                break;
                // Location
            case 'location':
                $result = LocationGroup::result($condition);
                break;
                // WooCommerce
            case 'woocommerce':
                $result = WooCommerceGroup::result($condition);
                break;
            default:
                $result = true;
                break;
        }

        return $result;
    }
}
