<?php

namespace Bricksforge\ConditionalLogic\Group;

if (!defined('ABSPATH')) {
    exit;
}

class General
{

    public static function build_options()
    {
        $options = [];

        // Number of Search Results
        $options[] = [
            'key'   => 'bricksforge_general_number_of_search_results',
            'label' => esc_html__('Number of Search Results', 'bricksforge'),
            'group' => 'group_bricksforge_general',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                ],
                'placeholder' => esc_html__('>', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('1', 'bricksforge'),
            ],
        ];

        // Loop Index
        $options[] = [
            'key'   => 'bricksforge_general_loop_index',
            'label' => esc_html__('Loop Index', 'bricksforge'),
            'group' => 'group_bricksforge_general',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                    '>' => esc_html__('>', 'bricksforge'),
                    '>=' => esc_html__('>=', 'bricksforge'),
                    '<' => esc_html__('<', 'bricksforge'),
                    '<=' => esc_html__('<=', 'bricksforge'),
                ],
                'placeholder' => esc_html__('>', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('0', 'bricksforge'),
            ],
        ];

        // Looped Element ID
        $options[] = [
            'key'   => 'bricksforge_general_looped_element_id',
            'label' => esc_html__('Loop Element ID', 'bricksforge'),
            'group' => 'group_bricksforge_general',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('==', 'bricksforge'),
                    '!=' => esc_html__('!=', 'bricksforge'),
                ],
                'placeholder' => esc_html__('==', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('adjjtg', 'bricksforge'),
            ],
        ];

        // Body Class Contains
        $options[] = [
            'key'   => 'bricksforge_general_body_class_includes',
            'label' => esc_html__('Body Class', 'bricksforge'),
            'group' => 'group_bricksforge_general',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Includes', 'bricksforge'),
                    '!=' => esc_html__('Not Includes', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Includes', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('class-name', 'bricksforge'),
            ],
        ];

        // Plugin Is Active
        $options[] = [
            'key'   => 'bricksforge_general_plugin_is_active',
            'label' => esc_html__('Plugin Is Active', 'bricksforge'),
            'group' => 'group_bricksforge_general',
            'compare' => [
                'type' => 'select',
                'options' => [
                    '==' => esc_html__('Is Active', 'bricksforge'),
                    '!=' => esc_html__('Is Not Active', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is Active', 'bricksforge'),
            ],
            'value' => [
                'type' => 'text',
                'placeholder' => 'plugin-name/plugin-name.php'
            ],
        ];

        // Is Multisite
        $options[] = [
            'key'   => 'bricksforge_general_is_multisite',
            'label' => esc_html__('Is Multisite', 'bricksforge'),
            'group' => 'group_bricksforge_general',
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

        // Is Main Site
        $options[] = [
            'key'   => 'bricksforge_general_is_multisite_main',
            'label' => esc_html__('Is Multisite Main', 'bricksforge'),
            'group' => 'group_bricksforge_general',
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

        // Is Subdomain Site
        $options[] = [
            'key'   => 'bricksforge_general_is_multisite_subdomain',
            'label' => esc_html__('Is Multisite Subdomain', 'bricksforge'),
            'group' => 'group_bricksforge_general',
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

        return $options;
    }

    public static function result($condition)
    {
        $result = true;

        $compare = isset($condition['compare']) ? $condition['compare'] : '==';
        $user_value = isset($condition['value']) ? \bricks_render_dynamic_data($condition['value']) : '';

        switch ($condition['key']) {
            case 'bricksforge_general_number_of_search_results':

                global $wp_query;
                $count = $wp_query->found_posts;

                switch ($compare) {
                    case '==':
                        $result = $count == $user_value;
                        break;
                    case '!=':
                        $result = $count != $user_value;
                        break;
                    case '>':
                        $result = $count > $user_value;
                        break;
                    case '>=':
                        $result = $count >= $user_value;
                        break;
                    case '<':
                        $result = $count < $user_value;
                        break;
                    case '<=':
                        $result = $count <= $user_value;
                        break;
                    default:
                        break;
                }

                break;

            case 'bricksforge_general_loop_index':

                // If Bricks class not exists, break
                if (!class_exists('\Bricks\Query')) {
                    break;
                }

                $query_object = \Bricks\Query::get_query_object();
                $loop_index = 0;

                if ($query_object) {
                    $loop_index = $query_object::get_loop_index();
                    $loop_index = intval($loop_index);
                }

                switch ($compare) {
                    case '==':
                        $result = $loop_index == $user_value;
                        break;
                    case '!=':
                        $result = $loop_index != $user_value;
                        break;
                    case '>':
                        $result = $loop_index > $user_value;
                        break;
                    case '>=':
                        $result = $loop_index >= $user_value;
                        break;
                    case '<':
                        $result = $loop_index < $user_value;
                        break;
                    case '<=':
                        $result = $loop_index <= $user_value;
                        break;
                    default:
                        break;
                }

                break;

            case 'bricksforge_general_looped_element_id':

                // If Bricks class not exists, break
                if (!class_exists('\Bricks\Query')) {
                    break;
                }

                $looped_element_id = \Bricks\Query::get_query_element_id();

                switch ($compare) {
                    case '==':
                        $result = $looped_element_id == $user_value;
                        break;
                    case '!=':
                        $result = $looped_element_id != $user_value;
                        break;
                    default:
                        break;
                }

                break;

            case 'bricksforge_general_body_class_includes':

                $body_classes = get_body_class();

                switch ($compare) {
                    case '==':
                        $result = in_array($user_value, $body_classes);
                        break;
                    case '!=':
                        $result = !in_array($user_value, $body_classes);
                        break;
                    default:
                        break;
                }

                break;

            case 'bricksforge_general_plugin_is_active':

                $plugins = get_option('active_plugins');

                switch ($compare) {
                    case '==':
                        $result = in_array($user_value, $plugins);
                        break;
                    case '!=':
                        $result = !in_array($user_value, $plugins);
                        break;
                    default:
                        break;
                }

                break;
            case 'bricksforge_general_is_multisite':
                $result = is_multisite() == $user_value;
                break;
            case 'bricksforge_general_is_multisite_main':
                $result = is_main_site() == $user_value;
                break;
            case 'bricksforge_general_is_multisite_subdomain':
                $result = is_subdomain_install() == $user_value;
                break;
            default:
                break;
        }

        return $result;
    }
}
