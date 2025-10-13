<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Update_Post
{
    public $name = "update_post";

    public function run($form)
    {
        $forms_helper = new FormsHelper();
        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $dynamic_post_id = $form->get_dynamic_post_id();
        $form_id = $form->get_form_id();

        $post_title = null;
        $post_content = null;
        $post_status = null;
        $post_excerpt = null;
        $post_date = null;
        $post_thumbnail = null;
        $taxonomies = null;
        $allow_only_for_authors = null;
        $allow_only_if_logged_in = null;

        $post_id = isset($form_settings['pro_forms_post_action_post_update_post_id']) ? $form_settings['pro_forms_post_action_post_update_post_id'] : $post_id;
        $post_id = $form->get_form_field_by_id($post_id);

        if (!$post_id && isset($dynamic_post_id) && $dynamic_post_id) {
            $post_id = $dynamic_post_id;
        }

        $post_id = absint($post_id);

        $post_title = isset($form_settings['pro_forms_post_action_post_update_title']) ? $form_settings['pro_forms_post_action_post_update_title'] : $post_title;
        $post_title = $form->get_form_field_by_id($post_title);

        $post_content = isset($form_settings['pro_forms_post_action_post_update_content']) ? $form_settings['pro_forms_post_action_post_update_content'] : $post_content;
        $post_content = $form->get_form_field_by_id($post_content);

        $post_status = isset($form_settings['pro_forms_post_action_post_update_status']) ? $form_settings['pro_forms_post_action_post_update_status'] : $post_status;
        $post_status = $form->get_form_field_by_id($post_status);

        $post_excerpt = isset($form_settings['pro_forms_post_action_post_update_excerpt']) ? $form_settings['pro_forms_post_action_post_update_excerpt'] : $post_excerpt;
        $post_excerpt = $form->get_form_field_by_id($post_excerpt);

        $post_date = isset($form_settings['pro_forms_post_action_post_update_date']) ? $form_settings['pro_forms_post_action_post_update_date'] : $post_date;
        $post_date = $form->get_form_field_by_id($post_date);

        $post_thumbnail = isset($form_settings['pro_forms_post_action_post_update_thumbnail']) ? $form_settings['pro_forms_post_action_post_update_thumbnail'] : $post_thumbnail;

        $allow_only_for_authors = isset($form_settings['pro_forms_post_action_post_update_allow_only_for_post_author']) ? $form_settings['pro_forms_post_action_post_update_allow_only_for_post_author'] : $allow_only_for_authors;
        $allow_only_if_logged_in = isset($form_settings['pro_forms_post_action_post_update_allow_only_if_logged_in']) ? $form_settings['pro_forms_post_action_post_update_allow_only_if_logged_in'] : $allow_only_if_logged_in;

        if ($allow_only_if_logged_in) {
            if (!is_user_logged_in()) {
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

        if ($post_thumbnail) {
            // Handle Thumbnail. Returns the attachment ID
            $post_thumbnail = $form->handle_file($post_thumbnail, $form_settings, $form_files);
        }

        if ($post_date) {
            $post_date = date('Y-m-d H:i:s', strtotime($post_date));
        }

        $taxonomies = isset($form_settings['pro_forms_post_action_post_update_taxonomies']) ? $form_settings['pro_forms_post_action_post_update_taxonomies'] : $taxonomies;
        if (isset($taxonomies)) {
            $temp_taxonomies = [];
            foreach ($taxonomies as $key => $value) {
                $taxonomy_slug = $value['taxonomy'];
                $value['term'] = $form->get_form_field_by_id($value['term']);

                $term_slugs = array_map('trim', explode(',', $value['term']));

                foreach ($term_slugs as $term_slug) {
                    $term_slug = $form->get_form_field_by_id($term_slug);
                    $term = get_term_by('slug', $term_slug, $taxonomy_slug);

                    if (!isset($temp_taxonomies[$taxonomy_slug])) {
                        $temp_taxonomies[$taxonomy_slug] = [];
                    }

                    $temp_taxonomies[$taxonomy_slug][] = $term->term_id;
                }
            }
        }

        $taxonomies = isset($temp_taxonomies) ? $temp_taxonomies : [];

        // Sanitize
        $post_title = sanitize_text_field($post_title);
        $post_content = wp_kses_post($post_content);
        $post_status = sanitize_key($post_status);
        $post_excerpt = sanitize_text_field($post_excerpt);
        $post_date = sanitize_text_field($post_date);

        $post_data = array(
            'ID'           => $post_id,
            'post_title'   => $post_title,
            'post_content' => $post_content,
            'post_status'  => $post_status,
            'post_excerpt' => $post_excerpt,
            'post_date'    => $post_date,
        );

        $post_data = array_filter($post_data);

        $result = wp_update_post($post_data, true);

        if (is_wp_error($result)) {
            $form->set_result(
                [
                    'action'  => $this->name,
                    'type'    => 'error',
                    'message' => $result->get_error_message(),
                ]
            );

            return false;
        }

        // Handle Taxonomies
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy_slug => $term_ids) {
                wp_set_post_terms($post_id, $term_ids, $taxonomy_slug);
            }
        }

        $form->update_live_post_id($post_id);

        if ($post_thumbnail && $post_id) {
            set_post_thumbnail($post_id, $post_thumbnail);
        }

        // Add Action: bricksforge/pro_forms/post_updated
        do_action('bricksforge/pro_forms/post_updated', $post_id);

        $form->set_result(
            [
                'action' => $this->name,
                'type'   => 'success',
            ]
        );

        return $result;
    }
}
