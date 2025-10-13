<?php

namespace Bricksforge\ConditionalLogic\Group;

if (!defined('ABSPATH')) {
    exit;
}

class Post
{

    public static function build_options()
    {
        $options = [];

        // Publication has been longer than
        $options[] = [
            'key'   => 'bricksforge_post_publication_has_been_longer_than',
            'label' => esc_html__('Publishing has been longer than', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    'minutes' => esc_html__('Minutes', 'bricksforge'),
                    'hours' => esc_html__('Hours', 'bricksforge'),
                    'days' => esc_html__('Days', 'bricksforge'),
                    'weeks' => esc_html__('Weeks', 'bricksforge'),
                    'months' => esc_html__('Months', 'bricksforge'),
                    'years' => esc_html__('Years', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Days', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('30', 'bricksforge'),
            ],
        ];

        // Is Parent
        $options[] = [
            'key'   => 'bricksforge_post_is_parent',
            'label' => esc_html__('Is Parent', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'select',
                'options'     =>  [
                    1 => esc_html__('True', 'bricksforge'),
                    0 => esc_html__('False', 'bricksforge'),
                ],
                'placeholder' => esc_html__('True', 'bricksforge'),
            ],
        ];

        // Is Child
        $options[] = [
            'key'   => 'bricksforge_post_is_child',
            'label' => esc_html__('Is Child', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'select',
                'options'     =>  [
                    1 => esc_html__('True', 'bricksforge'),
                    0 => esc_html__('False', 'bricksforge'),
                ],
                'placeholder' => esc_html__('True', 'bricksforge'),
            ],
        ];

        // Is Frontpage
        $options[] = [
            'key'   => 'bricksforge_post_is_front_page',
            'label' => esc_html__('Is Front Page', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'select',
                'options'     =>  [
                    1 => esc_html__('True', 'bricksforge'),
                    0 => esc_html__('False', 'bricksforge'),
                ],
                'placeholder' => esc_html__('True', 'bricksforge'),
            ],
        ];

        // Page Type
        $options[] = [
            'key'   => 'bricksforge_post_page_type',
            'label' => esc_html__('Page Type', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is', 'bricksforge'),
                    '!=' => esc_html__('Is Not', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'select',
                'options'     =>  [
                    'frontpage' => esc_html__('Front Page', 'bricksforge'),
                    'home' => esc_html__('Home', 'bricksforge'),
                    'singular' => esc_html__('Singular', 'bricksforge'),
                    'archive' => esc_html__('Archive', 'bricksforge'),
                    '404' => esc_html__('404', 'bricksforge'),
                    'search' => esc_html__('Search Results', 'bricksforge'),
                    'attachment' => esc_html__('Attachment', 'bricksforge'),
                    'tax_archive' => esc_html__('Taxonomy Archive', 'bricksforge'),
                    'date_archive' => esc_html__('Date Archive', 'bricksforge'),
                    'author_archive' => esc_html__('Author Archive', 'bricksforge'),
                    'custom_post_type' => esc_html__('Custom Post Type', 'bricksforge'),

                    // WooCommerce related
                    'wc_shop' => esc_html__('WC Shop', 'bricksforge'),
                    'wc_product' => esc_html__('WC Product', 'bricksforge'),
                    'wc_product_category' => esc_html__('WC Product Category', 'bricksforge'),
                    'wc_cart' => esc_html__('WC Cart', 'bricksforge'),
                    'wc_checkout' => esc_html__('WC Checkout', 'bricksforge'),
                    'wc_account' => esc_html__('WC Account', 'bricksforge'),
                    'wc_order_received' => esc_html__('WC Order Received', 'bricksforge'),
                    'wc_endpoint' => esc_html__('WC Endpoint (e.g., view order)', 'bricksforge'),
                ],
                'placeholder' => esc_html__('frontpage', 'bricksforge'),
            ],
        ];

        // Post Tag
        $options[] = [
            'key'   => 'bricksforge_post_post_tag',
            'label' => esc_html__('Post Tag', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('Includes', 'bricksforge'),
                    '!=' => esc_html__('Not Includes', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Includes', 'bricksforge'),
            ],
            'value' => [
                'type' => 'text',
                'placeholder' => 'tag-name'
            ],
        ];

        // Post Meta
        $options[] = [
            'key'   => 'bricksforge_post_post_meta',
            'label' => esc_html__('Post Meta', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('Matches', 'bricksforge'),
                    '!=' => esc_html__('Not Matches', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Matches', 'bricksforge'),
            ],
            'value' => [
                'type' => 'text',
                'placeholder' => 'key=value'
            ],
        ];

        // Comments open
        $options[] = [
            'key'   => 'bricksforge_post_comments_open',
            'label' => esc_html__('Comments Open', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('Is', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value' => [
                'type' => 'select',
                'options' => [
                    1 => esc_html__('True', 'bricksforge'),
                    0 => esc_html__('False', 'bricksforge'),
                ],
                'placeholder' => esc_html__('True', 'bricksforge'),
            ],
        ];

        // Pings Open
        $options[] = [
            'key'   => 'bricksforge_post_pings_open',
            'label' => esc_html__('Pings Open', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('Is', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value' => [
                'type' => 'select',
                'options' => [
                    1 => esc_html__('True', 'bricksforge'),
                    0 => esc_html__('False', 'bricksforge'),
                ],
                'placeholder' => esc_html__('True', 'bricksforge'),
            ],
        ];

        // Comment Count
        $options[] = [
            'key'   => 'bricksforge_post_comment_count',
            'label' => esc_html__('Post Comment Count', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                ],
                'placeholder' => esc_html__('>', 'bricksforge'),
            ],
            'value' => [
                'type' => 'text',
                'placeholder' => '0'
            ],
        ];

        // Is Sticky
        $options[] = [
            'key'   => 'bricksforge_post_is_sticky',
            'label' => esc_html__('Is Sticky', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('Is', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value' => [
                'type' => 'select',
                'options' => [
                    1 => esc_html__('True', 'bricksforge'),
                    0 => esc_html__('False', 'bricksforge'),
                ],
                'placeholder' => esc_html__('True', 'bricksforge'),
            ],
        ];

        // Has Excerpt
        $options[] = [
            'key'   => 'bricksforge_post_has_excerpt',
            'label' => esc_html__('Has Excerpt', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('Is', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value' => [
                'type' => 'select',
                'options' => [
                    1 => esc_html__('True', 'bricksforge'),
                    0 => esc_html__('False', 'bricksforge'),
                ],
                'placeholder' => esc_html__('True', 'bricksforge'),
            ],
        ];

        // Has Post Thumbnail
        $options[] = [
            'key'   => 'bricksforge_post_has_post_thumbnail',
            'label' => esc_html__('Has Post Thumbnail', 'bricksforge'),
            'group' => 'group_bricksforge_post',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('Is', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value' => [
                'type' => 'select',
                'options' => [
                    1 => esc_html__('True', 'bricksforge'),
                    0 => esc_html__('False', 'bricksforge'),
                ],
                'placeholder' => esc_html__('True', 'bricksforge'),
            ],
        ];

        return $options;
    }

    public static function result($condition)
    {
        $result = true;

        $compare = isset($condition['compare']) ? $condition['compare'] : '==';
        $user_value = isset($condition['value']) ? \bricks_render_dynamic_data($condition['value']) : '';

        switch ($condition['key']) {
            case 'bricksforge_post_publication_has_been_longer_than':

                $post_date = get_the_date('U');
                $current_date = time();

                switch ($compare) {
                    case 'minutes':
                        $user_value = $user_value * 60;
                        break;
                    case 'hours':
                        $user_value = $user_value * 60 * 60;
                        break;
                    case 'days':
                        $user_value = $user_value * 60 * 60 * 24;
                        break;
                    case 'weeks':
                        $user_value = $user_value * 60 * 60 * 24 * 7;
                        break;
                    case 'months':
                        $user_value = $user_value * 60 * 60 * 24 * 30;
                        break;
                    case 'years':
                        $user_value = $user_value * 60 * 60 * 24 * 365;
                        break;
                    default:
                        break;
                }

                $result = $current_date - $post_date > $user_value;

                break;

            case 'bricksforge_post_is_parent':

                global $post;

                // Check if current post has children
                $children = get_pages([
                    'child_of' => $post->ID
                ]);

                $count = count($children);

                if ($user_value == 1) {
                    $result = $count > 0;
                } else {
                    $result = $count == 0;
                }

                break;

            case 'bricksforge_post_is_child':

                global $post;

                // Check if current post has children
                if ($post->post_parent) {
                    $result = $user_value == 1;
                } else {
                    $result = $user_value == 0;
                }

                break;

            case 'bricksforge_post_is_front_page':

                $result = $user_value == is_front_page();

                break;

            case 'bricksforge_post_page_type':

                switch ($user_value) {
                    case 'frontpage':
                        $result = is_front_page();
                        break;
                    case 'home':
                        $result = is_home();
                        break;
                    case 'singular':
                        $result = is_singular();
                        break;
                    case 'archive':
                        $result = is_archive();
                        break;
                    case '404':
                        $result = is_404();
                        break;
                    case 'search':
                        $result = is_search();
                        break;
                    case 'attachment':
                        $result = is_attachment();
                        break;
                    case 'tax_archive':
                        $result = is_tax();
                        break;
                    case 'date_archive':
                        $result = is_date();
                        break;
                    case 'author_archive':
                        $result = is_author();
                        break;
                    case 'custom_post_type':
                        $post_types = get_post_types(array('public' => true, '_builtin' => false), 'names');
                        $result = in_array(get_post_type(), $post_types);
                        break;

                        // WooCommerce related
                    case 'wc_shop':
                        $result = function_exists('is_shop') && is_shop();
                        break;
                    case 'wc_product':
                        $result = function_exists('is_product') && is_product();
                        break;
                    case 'wc_product_category':
                        $result = function_exists('is_product_category') && is_product_category();
                        break;
                    case 'wc_cart':
                        $result = function_exists('is_cart') && is_cart();
                        break;
                    case 'wc_checkout':
                        $result = function_exists('is_checkout') && is_checkout();
                        break;
                    case 'wc_account':
                        $result = function_exists('is_account_page') && is_account_page();
                        break;
                    case 'wc_order_received':
                        $result = function_exists('is_order_received_page') && is_order_received_page();
                        break;
                    case 'wc_endpoint':
                        $result = function_exists('is_wc_endpoint_url') && is_wc_endpoint_url();
                        break;
                    default:
                        $result = false;
                        break;
                }

                if ($compare == '!=') {
                    $result = !$result;
                }

                break;

            case 'bricksforge_post_post_tag':

                $post_tags = get_the_tags();

                if ($post_tags) {
                    $post_tag_names = array_map(function ($tag) {
                        return $tag->name;
                    }, $post_tags);

                    switch ($compare) {
                        case '==':
                            $result = in_array($user_value, $post_tag_names);
                            break;
                        case '!=':
                            $result = !in_array($user_value, $post_tag_names);
                            break;
                        default:
                            break;
                    }
                } else {
                    $result = false;
                }

                break;

            case 'bricksforge_post_post_meta':
                if (empty($user_value)) {
                    break;
                }

                // User value is key=value. We need to split it into key and value
                $user_value = explode('=', $user_value);
                $user_value_key = $user_value[0];
                $user_value_value = $user_value[1];

                $post_meta = get_post_meta(get_the_ID(), $user_value_key, true);

                switch ($compare) {
                    case '==':
                        $result = $post_meta == $user_value_value;
                        break;
                    case '!=':
                        $result = $post_meta != $user_value_value;
                        break;
                    default:
                        break;
                }

                break;
            case 'bricksforge_post_comments_open':
                $result = $user_value == comments_open();
                break;
            case 'bricksforge_post_pings_open':
                $result = $user_value == pings_open();
                break;

            case 'bricksforge_post_comment_count':
                $post_comment_count = get_comments([
                    'post_id' => get_the_ID(),
                    'count' => true,
                ]);

                switch ($compare) {
                    case '==':
                        $result = $post_comment_count == $user_value;
                        break;
                    case '!=':
                        $result = $post_comment_count != $user_value;
                        break;
                    case '>':
                        $result = $post_comment_count > $user_value;
                        break;
                    case '>=':
                        $result = $post_comment_count >= $user_value;
                        break;
                    case '<':
                        $result = $post_comment_count < $user_value;
                        break;
                    case '<=':
                        $result = $post_comment_count <= $user_value;
                        break;
                    default:
                        break;
                }

                break;
            case 'bricksforge_post_is_sticky':
                $result = $user_value == is_sticky();
                break;
            case 'bricksforge_post_has_excerpt':
                $result = $user_value == has_excerpt();
                break;
            case 'bricksforge_post_has_post_thumbnail':
                $result = $user_value == has_post_thumbnail();
                break;
            default:
                break;
        }

        return $result;
    }
}
