<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Create_Submission
{
    public $name = "create_submission";

    public function run($form)
    {
        $forms_helper = new FormsHelper();

        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $form_id = $form->get_form_id();
        $post_context = $form->get_post_context();

        global $wpdb;

        $form_fields = $forms_helper->get_form_fields_from_ids($form_settings, $form_fields, $post_id, $form_id, $post_context);

        if (isset($form_settings['submission_prevent_duplicates']) && $form_settings['submission_prevent_duplicates']) {
            $is_duplicate = $this->check_for_duplicates($form_settings, $form_fields, $form_id, $form);
            if ($is_duplicate[0] === true) {
                return [
                    'status'  => 'duplicate',
                    'message' => $is_duplicate[1]
                ];
            }
        }

        if (isset($form_settings['submission_max']) && !empty($form_settings['submission_max'])) {
            $max_submissions = intval(sanitize_text_field($form_settings['submission_max']));

            $table_name = $wpdb->prefix . BRICKSFORGE_SUBMISSIONS_DB_TABLE;

            $form_id = sanitize_text_field($form_id);

            $submissions_count = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM $table_name WHERE form_id = %s",
                    $form_id
                )
            );

            if ($submissions_count >= $max_submissions) {
                return "Maximum submissions reached";
                $form->set_result(array(
                    'action' => $this->name,
                    'type'    => 'error',
                    'message' => 'Maximum submissions reached'
                ));
            }
        }

        $submission_data = array();
        $submission_data['fields'] = array();

        foreach ($form_fields as $field) {
            $field_value = $form->get_form_field_by_id($field['id'], null, null, null, null, true, true);
            $field_label = isset($field['label']) ? bricks_render_dynamic_data($field['label'], $post_id) : '';

            // If value is still equal to $field['id'], the value is empty
            if ($field_value === $field['id']) {
                $field_value = '';
            }

            // If the value is empty, skip this field
            if (empty($field_value)) {
                continue;
            }

            array_push(
                $submission_data['fields'],
                array(
                    'label' => $field_label,
                    'value' => $field_value,
                    'id'    => $field['id']
                )
            );
        }

        $submission_data['post_id'] = $post_id;
        $submission_data['form_id'] = $form_id;

        // Convert submission data to JSON
        $submission_json = json_encode($submission_data);

        // Insert submission data into database
        global $wpdb;
        $table_name = $wpdb->prefix . BRICKSFORGE_SUBMISSIONS_DB_TABLE;
        $result = $wpdb->insert(
            $table_name,
            array(
                'form_id'   => $form_id,
                'post_id'   => $post_id,
                'timestamp' => current_time('mysql'),
                'fields'    => $submission_json
            )
        );

        if ($result !== false) {
            $submission_data['id'] = $wpdb->insert_id;
        }

        // Action: 'bricksforge/pro_forms/submission_created'
        do_action('bricksforge/pro_forms/submission_created', $submission_data);

        // Handle Unread Submissions
        $unread_submissions = get_option("brf_unread_submissions", array());
        array_push($unread_submissions, $wpdb->insert_id);
        update_option("brf_unread_submissions", $unread_submissions);

        $form->set_result(array(
            'action' => $this->name,
            'type'    => 'success'
        ));

        return $submission_data;
    }

    public function check_for_duplicates($form_settings, $form_data, $form_id, $form)
    {
        $is_duplicate = [false, ''];
        $notice = "";
        $data_to_check = $form_settings['submission_prevent_duplicates_data'];

        if (!isset($data_to_check) || empty($data_to_check)) {
            return false;
        }

        foreach ($data_to_check as $data) {
            $field_id = $data['field'];

            // Remove {{ and }} from the field id
            if (strpos($field_id, '{{') !== false) {
                $field_id = str_replace('{{', '', $field_id);
                $field_id = str_replace('}}', '', $field_id);
            }

            $notice = $data['notice'] ? $data['notice'] : 'Error';

            $field_data = $form->get_form_field_by_id($field_id);

            if (!$field_data) {
                continue;
            }

            global $wpdb;

            $table_name = $wpdb->prefix . BRICKSFORGE_SUBMISSIONS_DB_TABLE;

            $submissions = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT fields FROM $table_name WHERE form_id = %s",
                    $form_id
                )
            );

            if (empty($submissions)) {
                continue;
            }

            $submissions = json_decode(json_encode($submissions), true);

            foreach ($submissions as $submission) {
                $submission = json_decode($submission['fields'], true);

                foreach ($submission['fields'] as $submission) {
                    if ($field_data && $submission['id'] == $field_id && $submission['value'] == $field_data) {
                        $is_duplicate = [true, $notice];
                    }
                }
            }
        }

        return $is_duplicate;
    }
}
