<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Delete_Post
{
    public $name = "delete_post";

    public function run($form)
    {
        $forms_helper = new FormsHelper();
        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $dynamic_post_id = $form->get_dynamic_post_id();
        $form_id = $form->get_form_id();

        $post_to_delete = isset($form_settings['pro_forms_post_action_post_delete_post_id']) ? $form_settings['pro_forms_post_action_post_delete_post_id'] : '';
        $delete_permanently = isset($form_settings['pro_forms_post_action_post_delete_permanently']) ? $form_settings['pro_forms_post_action_post_delete_permanently'] : false;
        $allow_only_for_authors = isset($form_settings['pro_forms_post_action_post_delete_allow_only_for_post_author']) ? $form_settings['pro_forms_post_action_post_delete_allow_only_for_post_author'] : null;
        $allow_only_if_logged_in = isset($form_settings['pro_forms_post_action_post_delete_allow_only_if_logged_in']) ? $form_settings['pro_forms_post_action_post_delete_allow_only_if_logged_in'] : null;

        if ($allow_only_if_logged_in) {
            if (!is_user_logged_in()) {
                $form->set_result(
                    [
                        'action'  => $this->name,
                        'type'    => 'error',
                        'message' => 'You must be logged in to delete a post.',
                    ]
                );

                return false;
            }
        }

        if ($allow_only_for_authors) {
            $post_author = get_post_field('post_author', $post_id);
            $current_user_id = get_current_user_id();

            if ($post_author != $current_user_id) {
                $form->set_result(
                    [
                        'action'  => $this->name,
                        'type'    => 'error',
                        'message' => __('You are not allowed to update this post.', 'bricksforge'),
                    ]
                );

                return false;
            }
        }

        if (!$post_to_delete) {
            $form->set_result(
                [
                    'action'  => $this->name,
                    'type'    => 'error',
                    'message' => 'Deletion failed. No post ID provided.',
                ]
            );

            return false;
        }

        $post_to_delete = $form->get_form_field_by_id($post_to_delete);

        try {

            if ($delete_permanently) {
                $post_id = wp_delete_post($post_to_delete, true);
            } else {
                $post_id = wp_trash_post($post_to_delete);
            }

            if ($post_id) {
                $form->set_result(
                    [
                        'action'  => $this->name,
                        'type'    => 'success',
                    ]
                );
            } else {
                $form->set_result(
                    [
                        'action'  => $this->name,
                        'type'    => 'error',
                        'message' => 'Deletion failed.',
                    ]
                );

                return false;
            }
        } catch (\Exception $e) {
            $form->set_result(
                [
                    'action'  => $this->name,
                    'type'    => 'error',
                    'message' => $e->getMessage(),
                ]
            );

            return false;
        }

        return $post_id;
    }
}
