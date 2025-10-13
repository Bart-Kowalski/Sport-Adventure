<?php

namespace Bricksforge\ProForms\Actions;

class Update_User
{
    public $name = "update_user";

    public function run($form)
    {

        $form_settings = $form->get_settings();
        $form_fields = $form->get_fields();

        $to_change = [];

        $user_id = isset($form_settings['updateUserId']) ? $form->get_form_field_by_id($form_settings['updateUserId']) : '';

        // If there is no user id, we can't update the user
        if (!$user_id) {
            $form->set_result(
                [
                    'action' => $this->name,
                    'type'   => 'error',
                    'message' => __('User ID is required', 'bricksforge'),
                ]
            );
            return;
        }

        $user_email = isset($form_settings['updateUserEmail']) ? $form->get_form_field_by_id($form_settings['updateUserEmail']) : '';
        $only_if_logged_in_user = isset($form_settings['updateUserOnlyIfLoggedInUser']) ? $form_settings['updateUserOnlyIfLoggedInUser'] : false;

        // We check if the user is the logged in user
        if ($only_if_logged_in_user) {
            $current_user_id = get_current_user_id();

            if ($user_id != $current_user_id) {
                $form->set_result(
                    [
                        'action' => $this->name,
                        'type'   => 'error',
                        'message' => __('User ID is not the logged in user', 'bricksforge'),
                    ]
                );
                return;
            }
        }

        if ($user_email) {
            $to_change["user_email"] = $user_email;
        }

        // We loop through the to_change array and update the user
        foreach ($to_change as $key => $value) {
            wp_update_user(array('ID' => $user_id, $key => $value));
        }

        // Action: bricksforge/pro_forms/user_updated
        do_action('bricksforge/pro_forms/user_updated', $user_id, $to_change);

        $form->set_result(
            [
                'action' => $this->name,
                'type'   => 'success',
            ]
        );
    }
}
