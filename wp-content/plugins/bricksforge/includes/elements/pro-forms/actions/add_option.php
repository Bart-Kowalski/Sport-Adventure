<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Add_Option
{
    public $name = "add_option";


    public function run($form)
    {

        $forms_helper = new FormsHelper();
        $form_settings = $form->get_settings();
        $sensitive_options = $forms_helper->get_sensitive_options();

        $option_data = $form_settings['pro_forms_post_action_option_add_option_data'];

        $option_data = array_map(function ($item) {
            return array(
                'name'  => isset($item['name']) ? bricks_render_dynamic_data($item['name']) : '',
                'value' => isset($item['value']) ? bricks_render_dynamic_data($item['value']) : '',
            );
        }, $option_data);

        // Add Option for each $option_data
        foreach ($option_data as $option) {
            $option_name = $option['name'];
            $option_value = $option['value'];

            if (!isset($option_name) || !isset($option_value)) {
                continue;
            }

            if (in_array($option_name, $sensitive_options)) {
                error_log("Not allowed to add this option: " . $option_name);
                wp_send_json_error([
                    'message' => esc_html__('Not allowed to add this option', 'bricksforge'),
                    'type'    => 'error',
                    'action'  => $this->name,
                ]);
                continue;
            }

            $option_name = $form->get_form_field_by_id($option_name);
            $option_value = $form->get_form_field_by_id($option_value);

            $option_value = $forms_helper->sanitize_value($option_value);

            $result = add_option($option_name, $option_value);

            if (!$result) {

                // Check if already exists. If so, show a different error message
                if (get_option($option_name)) {
                    $form->set_result(
                        [
                            'action'  => $this->name,
                            'type'    => 'error',
                            'message' => esc_html__('Option already exists.', 'bricksforge'),
                        ]
                    );

                    return false;
                }

                $form->set_result(
                    [
                        'action'  => $this->name,
                        'type'    => 'error',
                        'message' => esc_html__('Option could not be added.', 'bricksforge'),
                    ]
                );

                return false;
            }
        }

        $form->set_result(
            [
                'action' => $this->name,
                'type'   => 'success',
            ]
        );

        return true;
    }
}
