<?php

namespace Bricksforge\ProForms\Actions;

class Custom
{
    public $name = "custom";


    public function run($form)
    {

        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $form_id = $form->get_form_id();

        // Extend $form_data with Post ID
        $form_data['postId'] = $post_id;
        $form_data['formId'] = $form_id;
        $form_data['referrer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        // Suppress output to prevent custom actions from breaking the actions flow
        ob_start();

        // Perform custom action with submitted form data
        // https://academy.bricksbuilder.io/article/form-element/#custom-action

        do_action('bricks/form/custom_action', $form); // Legacy

        do_action('bricksforge/pro_forms/custom_action', $form);

        ob_end_clean();
    }
}
