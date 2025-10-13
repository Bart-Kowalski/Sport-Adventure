<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Update_User_Meta
{
    public $name = "update_user_meta";


    public function run($form)
    {
        $forms_helper = new FormsHelper();

        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $form_id = $form->get_form_id();

        $data = $form_settings['pro_forms_post_action_update_user_meta_data'];

        $data = array_map(function ($item) use ($form_fields, $post_id, $form_settings, $form_files, $form) {


            if ($form->is_live_id($item['user_id'])) {
                $item['user_id'] = $form->get_live_user_id();
            } else {
                $item['user_id'] = isset($item['user_id']) && $item['user_id'] ? intval($form->get_form_field_by_id($item['user_id'])) : intval(get_current_user_id());
            }

            return array(
                'id'         => $item['user_id'],
                'key'        => $form->get_form_field_by_id($item['key']),
                'value'        => $form->get_form_field_by_id($item['value']),
                'type'         => isset($item['type']) ? $item['type'] : 'replace',
                'ignore_empty' => isset($item['ignore_empty']) ? $item['ignore_empty'] : false,
                'selector'     => isset($item['selector']) ? bricks_render_dynamic_data($item['selector'], $post_id) : null,
                'number_field' => isset($item['number_field']) ? $form->get_form_field_by_id($item['number_field']) : null,
            );
        }, $data);

        $updated_values = array();

        foreach ($data as $d) {
            $id = $d['id'];
            $key = $d['key'];
            $value = $d['value'];
            $type = $d['type'];
            $ignore_empty = $d['ignore_empty'];
            $selector = $d['selector'];
            $number_field = $d['number_field'];

            if (!isset($key) || !isset($value) || !isset($id)) {
                continue;
            }

            $key = $form->get_form_field_by_id($key);

            if (empty($value) && $ignore_empty) {
                continue;
            }

            $id = absint($id);
            $key = $forms_helper->sanitize_value($key);
            $value = $forms_helper->sanitize_value($value);

            $new_value;
            $current_value = get_user_meta($id, $key, true);

            switch ($type) {
                case 'replace':
                    $new_value = $value;
                    break;
                case 'increment':
                    $new_value = intval($current_value) + 1;
                    break;
                case 'decrement':
                    if (intval($current_value) <= 0) {
                        $new_value = 0;
                    } else {
                        $new_value = intval($current_value) - 1;
                    }
                    break;
                case 'increment_by_number':
                    $number_field = $form->get_form_field_by_id($number_field);
                    $new_value = intval($current_value) + intval($number_field);
                    break;
                case 'decrement_by_number':
                    $number_field = $form->get_form_field_by_id($number_field);

                    if (intval($current_value) <= 0) {
                        $new_value = 0;
                    } else {
                        $new_value = intval($current_value) - intval($number_field);
                    }
                    break;
                case 'add_to_array':
                    // Add the new value to the array
                    if (!is_array($current_value)) {
                        if (!empty(trim($current_value))) {
                            $new_value = array($current_value, $value);
                        } else {
                            $new_value = array($value);
                        }
                    } else {
                        $new_value = array_merge($current_value, array($value));
                    }

                    break;
                case 'remove_from_array':
                    // If the current value is not an array, make it one and remove the new value
                    if (is_array($current_value)) {
                        $new_value = array_diff($current_value, array($value));
                    }

                    break;
                default:
                    $new_value = $value;
                    break;
            }

            $new_value = $forms_helper->sanitize_value($new_value);

            $result = update_user_meta($id, $key, $new_value);

            if (!$result && $current_value != $new_value) {
                $form->set_result(
                    [
                        'action' => $this->name,
                        'type'   => 'error',
                        'message' => __('Error updating user meta field: ', 'bricksforge') . $key,
                    ]
                );
            }

            // Action: bricksforge/pro_forms/user_meta_updated
            do_action('bricksforge/pro_forms/user_meta_updated', $id, $key, $new_value);

            $allow_live_update = $type === 'add_to_array' || $type === 'remove_from_array' ? false : true;

            array_push($updated_values, [
                'selector' => $selector,
                'id'     => $id,
                'key'    => $key,
                'value'     => $new_value,
                'live'     => $allow_live_update,
            ]);
        }

        $form->set_result(
            [
                'action' => $this->name,
                'type'   => 'success',
            ]
        );

        return $updated_values;
    }
}
