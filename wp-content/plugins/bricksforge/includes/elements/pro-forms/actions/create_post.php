<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Create_Post
{
    public $name = "create_post";

    public function run($form)
    {
        $forms_helper = new FormsHelper();
        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $dynamic_post_id = $form->get_dynamic_post_id();
        $form_id = $form->get_form_id();

        $post_status = null;
        $post_categories = null;
        $post_taxonomies = null;
        $post_title = null;
        $post_content = null;
        $post_excerpt = null;
        $custom_fields = null;
        $post_thumbnail = null;
        $post_author = null;

        $post_status = isset($form_settings['pro_forms_post_action_post_create_post_status']) ? $form_settings['pro_forms_post_action_post_create_post_status'] : 'draft';
        $post_author = isset($form_settings['pro_forms_post_action_post_create_author']) ? $form->get_form_field_by_id($form_settings['pro_forms_post_action_post_create_author']) : null;

        if ($post_author && is_numeric($post_author)) {
            $post_author = intval($post_author);
        }

        $post_categories = isset($form_settings['pro_forms_post_action_post_create_categories']) ? $form_settings['pro_forms_post_action_post_create_categories'] : [];
        $allow_only_if_logged_in = isset($form_settings['pro_forms_post_action_post_create_allow_only_for_logged_in']) ? $form_settings['pro_forms_post_action_post_create_allow_only_for_logged_in'] : null;

        if ($allow_only_if_logged_in) {
            if (!is_user_logged_in()) {
                $form->set_result(
                    [
                        'action'  => $this->name,
                        'type'    => 'error',
                        'message' => 'You must be logged in to create a post.',
                    ]
                );

                return false;
            }
        }

        // Loop trough categories and create an array with only the "category" key
        if (isset($post_categories) && !empty($post_categories)) {
            foreach ($post_categories as $key => $value) {
                $value['category'] = $form->get_form_field_by_id($value['category']);

                // Check if the category string contains a comma
                if (strpos($value['category'], ',') !== false) {
                    // Split the categories by comma
                    $categories = explode(',', $value['category']);

                    $temp_post_cats = [];

                    foreach ($categories as $category) {
                        $category = trim($category); // Remove any whitespace
                        $category = $form->get_form_field_by_id($category);

                        // Get the category id from the category slug
                        $category_id = get_category_by_slug($category)->term_id;

                        $temp_post_cats[] = $category_id;
                    }

                    $post_categories = $temp_post_cats;
                } else {
                    // Process the single category
                    $category = $form->get_form_field_by_id($value['category']);

                    // Get the category id from the category slug
                    $category_id = get_category_by_slug($category)->term_id;

                    // Add the category id to the post_categories array
                    $post_categories[$key] = $category_id;
                }
            }
        }

        // Handle taxonomies
        $post_taxonomies = isset($form_settings['pro_forms_post_action_post_create_taxonomies']) ? $form_settings['pro_forms_post_action_post_create_taxonomies'] : [];

        // Loop through taxonomies and create an array with taxonomy names as keys and arrays of term IDs as values
        $temp_post_taxonomies = [];
        foreach ($post_taxonomies as $key => $value) {
            $taxonomy_slug = $value['taxonomy'];
            $value['term'] = $form->get_form_field_by_id($value['term']);

            $term_slugs = array_map('trim', explode(',', $value['term']));

            foreach ($term_slugs as $term_slug) {
                $term_slug = $form->get_form_field_by_id($term_slug);
                $term = get_term_by('slug', $term_slug, $taxonomy_slug);

                if (!isset($temp_post_taxonomies[$taxonomy_slug])) {
                    $temp_post_taxonomies[$taxonomy_slug] = [];
                }

                $temp_post_taxonomies[$taxonomy_slug][] = $term->term_id;
            }
        }

        $post_taxonomies = $temp_post_taxonomies;

        $post_title = isset($form_settings['pro_forms_post_action_post_create_title']) ? $form_settings['pro_forms_post_action_post_create_title'] : '';
        $post_content = isset($form_settings['pro_forms_post_action_post_create_content']) ? $form_settings['pro_forms_post_action_post_create_content'] : '';
        $post_excerpt = isset($form_settings['pro_forms_post_action_post_create_excerpt']) ? $form_settings['pro_forms_post_action_post_create_excerpt'] : '';
        $post_thumbnail = isset($form_settings['pro_forms_post_action_post_create_thumbnail']) ? $form_settings['pro_forms_post_action_post_create_thumbnail'] : false;

        if ($post_thumbnail) {
            // Handle Thumbnail. Returns the attachment ID
            $post_thumbnail = $form->get_form_field_by_id($post_thumbnail);
        }

        $post_title = $form->get_form_field_by_id($post_title);
        $post_content = $form->get_form_field_by_id($post_content);

        if (isset($form_settings['pro_forms_post_action_post_create_custom_fields'])) {
            foreach ($form_settings['pro_forms_post_action_post_create_custom_fields'] as $custom_field) {
                $custom_field['name'] = $forms_helper->adjust_meta_field_name($custom_field['name']);
                $custom_fields[$custom_field['name']] = isset($custom_field['value']) ? $form->get_form_field_by_id($custom_field['value']) : '';
            }
        }

        $post = array(
            'post_title'    => $post_title ? bricks_render_dynamic_data($post_title) : 'Untitled',
            'post_content'  => $post_content ? bricks_render_dynamic_data($post_content) : '',
            'post_status'   => $post_status,
            'post_type'     => $form_settings['pro_forms_post_action_post_create_pt'] ? $form_settings['pro_forms_post_action_post_create_pt'] : 'post',
            'meta_input'    => $custom_fields ? $custom_fields : array(),
            'post_category' => $post_categories ? $post_categories : array(),
            'post_author'   => $post_author ? $post_author : null,
            'post_excerpt'  => $post_excerpt ? bricks_render_dynamic_data($post_excerpt) : '',
        );

        $result = $post_id = wp_insert_post($post);

        if (!$result) {
            $form->set_result(
                [
                    'action'  => $this->name,
                    'type'    => 'error',
                    'message' => esc_html__('Post could not be created.', 'bricksforge'),
                ]
            );

            return false;
        }

        $form->update_live_post_id($result);

        if ($post_thumbnail) {
            set_post_thumbnail($post_id, $post_thumbnail);
        }

        // Set taxonomy terms for the newly created post
        foreach ($post_taxonomies as $taxonomy => $term_ids) {
            wp_set_object_terms($post_id, $term_ids, $taxonomy);
        }

        // Add Action: bricksforge/pro_forms/post_created
        do_action('bricksforge/pro_forms/post_created', $post_id);

        $form->set_result(
            [
                'action' => $this->name,
                'type'   => 'success',
            ]
        );

        return $post_id;
    }
}
