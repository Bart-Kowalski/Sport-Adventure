<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Reset_User_Password
{
    public $name = "reset_user_password";

    public function run($form)
    {
        $forms_helper = new FormsHelper();

        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $form_id = $form->get_form_id();

        $method = $form_settings['resetUserPasswordMethod'];
        $email_field = $form_settings['resetUserPasswordEmail'];
        $email = $form->get_form_field_by_id($email_field);
        $user = get_user_by('email', $email);

        switch ($method) {
            case 'request':
                if ($user) {

                    $result = retrieve_password($user->user_login);

                    if (is_wp_error($result)) {
                        error_log($result->get_error_message());
                    }
                } else {
                    error_log("User with email $email does not exist");
                }
                break;
            case 'update':
                $verify_password_confirmation = isset($form_settings['resetUserPasswordVerifyPasswordConfirmation']) ? $form_settings['resetUserPasswordVerifyPasswordConfirmation'] : false;
                $verify_current_password = isset($form_settings['resetUserPasswordVerifyCurrentPassword']) ? $form_settings['resetUserPasswordVerifyCurrentPassword'] : false;
                $strong_passwords = isset($form_settings['resetUserPasswordAllowOnlyStrongPasswords']) ? $form_settings['resetUserPasswordAllowOnlyStrongPasswords'] : false;

                $current_password = isset($form_settings['resetUserPasswordCurrentPasswordValue']) ? $form_settings['resetUserPasswordCurrentPasswordValue'] : null;
                if ($current_password) {
                    $current_password = $form->get_form_field_by_id($current_password);
                    $current_password = $forms_helper->sanitize_value($current_password);
                }

                $new_password = isset($form_settings['resetUserPasswordNewPassword']) ? $form_settings['resetUserPasswordNewPassword'] : null;
                if ($new_password) {
                    $new_password = $form->get_form_field_by_id($new_password);
                    $new_password = $forms_helper->sanitize_value($new_password);
                }

                $new_password_confirm = isset($form_settings['resetUserPasswordPasswordConfirmationValue']) ? $form_settings['resetUserPasswordPasswordConfirmationValue'] : null;
                if ($new_password_confirm) {
                    $new_password_confirm = $form->get_form_field_by_id($new_password_confirm);
                    $new_password_confirm = $forms_helper->sanitize_value($new_password_confirm);
                }

                $note_enter_new_password = isset($form_settings['resetUserPasswordNotificationNewPassword']) ? $form_settings['resetUserPasswordNotificationNewPassword'] : "Please enter a new password.";
                $note_current_password_incorrect = isset($form_settings['resetUserPasswordNotificationCurrentPasswordIncorrect']) ? $form_settings['resetUserPasswordNotificationCurrentPasswordIncorrect'] : "The current password is incorrect.";
                $note_passwords_do_not_match = isset($form_settings['resetUserPasswordNotificationPasswordsDoNotMatch']) ? $form_settings['resetUserPasswordNotificationPasswordsDoNotMatch'] : "Passwords do not match.";

                if (!isset($new_password) || empty($new_password)) {
                    return wp_send_json_error(array(
                        'message' => __($note_enter_new_password, 'bricks'),
                    ));
                }

                if ($verify_password_confirmation == true) {
                    // Compare passwords
                    if ($new_password != $new_password_confirm) {
                        return wp_send_json_error(array(
                            'message' => __($note_passwords_do_not_match, 'bricks'),
                        ));
                    }
                }

                if ($strong_passwords) {
                    $password_strength = $forms_helper->check_password_strength($new_password);
                    if ($password_strength['score'] < 3) {
                        return wp_send_json_error(array(
                            'message' => implode(" ", $password_strength['reasons']),
                        ));
                    }
                }

                if ($user) {
                    if (isset($verify_current_password) && $verify_current_password == true) {
                        if (wp_check_password($current_password, $user->data->user_pass, $user->ID)) {
                            $this->reset_password($new_password, $user->ID);
                        } else {
                            return wp_send_json_error(array(
                                'message' => __($note_current_password_incorrect, 'bricks'),
                            ));
                        }
                    } else {
                        $this->reset_password($new_password, $user->ID);
                    }
                } else {
                    return wp_send_json_error(array(
                        'message' => __("User not found", 'bricks'),
                    ));
                }

                break;
            default:
                break;
        }

        $form->set_result(
            [
                'action'         => $this->name,
                'type'           => 'success'
            ]
        );
    }

    private function reset_password($new_password, $user_id)
    {
        wp_set_password($new_password, $user_id);
        wp_set_auth_cookie($user_id);
    }
}
