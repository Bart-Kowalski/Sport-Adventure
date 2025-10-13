<?php

namespace Bricksforge\ProForms\Actions;

class Set_Storage_Item
{
    public $name = "set_storage_item";

    public function run($form)
    {
        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $form_id = $form->get_form_id();

        $option_data = $form_settings['pro_forms_post_action_set_storage_item_data'];

        $option_data = array_map(function ($item) use ($post_id) {
            return array(
                'id' => isset($item['id']) ? $item['id'] : null,
                'name'         => isset($item['name']) ? bricks_render_dynamic_data($item['name'], $post_id) : null,
                'value'        => isset($item['value']) ? bricks_render_dynamic_data($item['value'], $post_id) : null,
                'type'         => isset($item['type']) ? $item['type'] : null,
                'selector'     => isset($item['selector']) ? $item['selector'] : null,
                'number_field' => isset($item['number_field']) ? bricks_render_dynamic_data($item['number_field'], $post_id) : null,
            );
        }, $option_data);

        $updated_values = array();

        // Update Option for each $option_data
        foreach ($option_data as $option) {
            $option_name = $option['name'];
            $option_value = $option['value'];
            $option_type = $option['type'];
            $option_selector = $option['selector'];
            $option_number_field = $option['number_field'];

            if (!isset($option_name) || !isset($option_value)) {
                continue;
            }

            // Loop trough the form_data object
            $option_value = $form->get_form_field_by_id($option_value);

            $new_option_value;
            $current_value = 0;

            switch ($option_type) {
                case 'replace':
                    $new_option_value = $option_value;
                    break;
                case 'increment':
                    $new_option_value = 1;
                    break;
                case 'decrement':
                    $new_option_value = 1;
                    break;
                case 'increment_by_number':
                    $option_number_field = $form->get_form_field_by_id($option_number_field);
                    $new_option_value = intval($option_number_field);
                    break;
                case 'decrement_by_number':
                    $option_number_field = $form->get_form_field_by_id($option_number_field);
                    $new_option_value = intval($option_number_field);
                    break;
                case 'add_to_array':
                    $new_option_value = $option_value;
                    break;
                case 'remove_from_array':
                    $new_option_value = $option_value;

                    break;
                default:
                    $new_option_value = $option_value;
                    break;
            }

            $allow_live_update = $option_type === 'add_to_array' || $option_type === 'remove_from_array' ? false : true;

            array_push($updated_values, [
                'name'     => $option_name,
                'value'    => $new_option_value,
                'live'     => $allow_live_update,
                'selector' => $option_selector,
                'type'     => $option_type,
                'data' => $option
            ]);
        }

        $form->set_result(
            [
                'action'  => $this->name,
                'type'    => 'success',
            ]
        );

        return $updated_values;
    }
}
