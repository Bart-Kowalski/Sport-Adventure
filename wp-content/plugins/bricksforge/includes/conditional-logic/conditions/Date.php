<?php

namespace Bricksforge\ConditionalLogic\Group;

if (!defined('ABSPATH')) {
    exit;
}

class Date
{

    public static function build_options()
    {
        $options = [];

        // Current Day
        $options[] = [
            'key'   => 'bricksforge_date_current_day',
            'label' => esc_html__('Current Day', 'bricksforge'),
            'group' => 'group_bricksforge_date',
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
                'options'     =>  array_combine(range(1, 31), range(1, 31)),
                'placeholder' => esc_html__('1', 'bricksforge'),
            ],
        ];

        // Current Month
        $options[] = [
            'key'   => 'bricksforge_date_current_month',
            'label' => esc_html__('Current Month', 'bricksforge'),
            'group' => 'group_bricksforge_date',
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
                    1 => esc_html__('January', 'bricksforge'),
                    2 => esc_html__('February', 'bricksforge'),
                    3 => esc_html__('March', 'bricksforge'),
                    4 => esc_html__('April', 'bricksforge'),
                    5 => esc_html__('May', 'bricksforge'),
                    6 => esc_html__('June', 'bricksforge'),
                    7 => esc_html__('July', 'bricksforge'),
                    8 => esc_html__('August', 'bricksforge'),
                    9 => esc_html__('September', 'bricksforge'),
                    10 => esc_html__('October', 'bricksforge'),
                    11 => esc_html__('November', 'bricksforge'),
                    12 => esc_html__('December', 'bricksforge'),
                ],
                'placeholder' => esc_html__('1', 'bricksforge'),
            ],
        ];

        // Current Year
        $options[] = [
            'key'   => 'bricksforge_date_current_year',
            'label' => esc_html__('Current Year', 'bricksforge'),
            'group' => 'group_bricksforge_date',
            'compare' => [
                'type'        => 'select',
                'options'     =>  [
                    '==' => esc_html__('Is', 'bricksforge'),
                    '!=' => esc_html__('Is Not', 'bricksforge'),
                ],
                'placeholder' => esc_html__('Is', 'bricksforge'),
            ],
            'value'   => [
                'type'        => 'text',
                'placeholder' => esc_html__('2023', 'bricksforge'),
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
            case 'bricksforge_date_current_day':

                $current_day = date('j');

                switch ($compare) {
                    case '==':
                        $result = $current_day == $user_value;
                        break;
                    case '!=':
                        $result = $current_day != $user_value;
                        break;
                    default:
                        break;
                }

                break;
            case 'bricksforge_date_current_month':

                $current_month = date('n');

                switch ($compare) {
                    case '==':
                        $result = $current_month == $user_value;
                        break;
                    case '!=':
                        $result = $current_month != $user_value;
                        break;
                    default:
                        break;
                }

                break;
            case 'bricksforge_date_current_year':

                $current_year = date('Y');

                switch ($compare) {
                    case '==':
                        $result = $current_year == $user_value;
                        break;
                    case '!=':
                        $result = $current_year != $user_value;
                        break;
                    default:
                        break;
                }

                break;
            default:
                break;
        }

        return $result;
    }
}
