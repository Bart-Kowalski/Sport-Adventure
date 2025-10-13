<?php

namespace Bricksforge\ConditionalLogic\Group;

if (!defined('ABSPATH')) {
    exit;
}

class User
{

    public static function build_options()
    {
        $options = [];

        // User Meta
        $options[] = [
            'key'   => 'bricksforge_user_meta',
            'label' => esc_html__('User Meta', 'bricksforge'),
            'group' => 'group_bricksforge_user',
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

        // Comment Count
        $options[] = [
            'key'   => 'bricksforge_user_comment_count',
            'label' => esc_html__('User Comment Count', 'bricksforge'),
            'group' => 'group_bricksforge_user',
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

        // Has Profile Picture
        $options[] = [
            'key'   => 'bricksforge_user_has_profile_picture',
            'label' => esc_html__('Has Profile Picture', 'bricksforge'),
            'group' => 'group_bricksforge_user',
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
            ],
        ];

        // Post Count
        $options[] = [
            'key'   => 'bricksforge_user_post_count',
            'label' => esc_html__('Post Count', 'bricksforge'),
            'group' => 'group_bricksforge_user',
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

        return $options;
    }

    public static function result($condition)
    {
        $result = true;

        $compare = isset($condition['compare']) ? $condition['compare'] : '==';
        $user_value = isset($condition['value']) ? \bricks_render_dynamic_data($condition['value']) : '';

        switch ($condition['key']) {
            case 'bricksforge_user_meta':

                if (empty($user_value)) {
                    break;
                }

                // User value is key=value. We need to split it into key and value
                $user_value = explode('=', $user_value);
                $user_value_key = $user_value[0];
                $user_value_value = $user_value[1];

                $user_meta = get_user_meta(get_current_user_id(), $user_value_key, true);

                switch ($compare) {
                    case '==':
                        $result = $user_meta == $user_value_value;
                        break;
                    case '!=':
                        $result = $user_meta != $user_value_value;
                        break;
                    default:
                        break;
                }

                break;
            case 'bricksforge_user_comment_count':
                $user_comment_count = get_comments([
                    'user_id' => get_current_user_id(),
                    'count' => true,
                ]);

                switch ($compare) {
                    case '==':
                        $result = $user_comment_count == $user_value;
                        break;
                    case '!=':
                        $result = $user_comment_count != $user_value;
                        break;
                    case '>':
                        $result = $user_comment_count > $user_value;
                        break;
                    case '>=':
                        $result = $user_comment_count >= $user_value;
                        break;
                    case '<':
                        $result = $user_comment_count < $user_value;
                        break;
                    case '<=':
                        $result = $user_comment_count <= $user_value;
                        break;
                    default:
                        break;
                }

                break;
            case 'bricksforge_user_has_profile_picture':
                $user = get_user_by('id', get_current_user_id());
                $user_has_profile_picture = get_avatar_url($user->ID);

                switch ($compare) {
                    case '==':
                        $result = $user_has_profile_picture == $user_value;
                        break;
                    default:
                        break;
                }

                break;
            case 'bricksforge_user_post_count':
                $user_post_count = count_user_posts(get_current_user_id());

                switch ($compare) {
                    case '==':
                        $result = $user_post_count == $user_value;
                        break;
                    case '!=':
                        $result = $user_post_count != $user_value;
                        break;
                    case '>':
                        $result = $user_post_count > $user_value;
                        break;
                    case '>=':
                        $result = $user_post_count >= $user_value;
                        break;
                    case '<':
                        $result = $user_post_count < $user_value;
                        break;
                    case '<=':
                        $result = $user_post_count <= $user_value;
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
