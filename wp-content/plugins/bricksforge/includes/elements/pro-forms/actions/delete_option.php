<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Delete_Option
{
    public $name = "delete_option";


    public function run($form)
    {

        $form_settings = $form->get_settings();
        $forms_helper = new FormsHelper();
        $sensitive_options = $forms_helper->get_sensitive_options();

        $option_data = $form_settings['pro_forms_post_action_option_delete_option_data'];

        $option_data = array_map(function ($item) {
            return array(
                'name' => bricks_render_dynamic_data($item['name']),
            );
        }, $option_data);

        // Delete Option for each $option_data
        foreach ($option_data as $option) {
            $option_name = $option['name'];

            if (!isset($option_name)) {
                continue;
            }

            if (in_array($option_name, $sensitive_options)) {
                error_log('Not allowed to delete this option: ' . $option_name);
                wp_send_json_error([
                    'message' => esc_html__('Not allowed to delete this option', 'bricksforge'),
                    'type'    => 'error',
                    'action'  => $this->name,
                ]);
                continue;
            }

            $result = delete_option($option_name);

            if (!$result) {
                $form->set_result(
                    [
                        'action' => $this->name,
                        'type'   => 'error',
                        'message' => esc_html__('Option could not be deleted.', 'bricksforge'),
                    ]
                );
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
