<?php

namespace Bricksforge\ProForms\Actions;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Base
{
    private $settings;
    private $fields;
    private $uploaded_files;
    private $processed_files = [];
    private $file_field_ids = [];
    private $post_id;
    private $dynamic_post_id;
    private $post_context;
    private $form_id;
    private $structure;

    // Live Values
    private $current_proceeding_action;
    private $live_post_id;
    private $live_user_id;
    private $live_pdf_url;
    private $live_pdf_path;
    private $live_pdf_id;

    // Files
    private $processed_file_cache = [];

    public $results;

    public function __construct($form_settings, $form_data, $form_files, $post_id, $form_id, $dynamic_post_id, $form_structure, $post_context)
    {
        $this->settings = $form_settings;
        $this->fields = $form_data;
        $this->uploaded_files = isset($form_files) ? $form_files : [];
        $this->post_id = $post_id;
        $this->form_id = $form_id;
        $this->dynamic_post_id = $dynamic_post_id;
        $this->post_context = $post_context;
        $this->structure = $form_structure;

        $this->handle_file_field_ids();
    }

    public function get_settings()
    {
        return $this->settings;
    }

    public function get_fields()
    {
        return $this->fields;
    }

    public function get_post_id()
    {
        return $this->post_id;
    }

    public function get_dynamic_post_id()
    {
        return $this->dynamic_post_id;
    }

    public function get_post_context()
    {
        return $this->post_context;
    }

    public function get_form_id()
    {
        return $this->form_id;
    }

    public function get_uploaded_files()
    {
        return $this->uploaded_files;
    }

    public function get_file_field_ids()
    {
        return $this->file_field_ids;
    }

    public function get_current_proceeding_action()
    {
        return $this->current_proceeding_action;
    }

    public function get_live_post_id()
    {
        return $this->live_post_id;
    }

    public function get_live_user_id()
    {
        return $this->live_user_id;
    }

    public function get_live_pdf_url()
    {
        return $this->live_pdf_url;
    }

    public function get_live_pdf_path()
    {
        return $this->live_pdf_path;
    }

    public function get_live_pdf_id()
    {
        return $this->live_pdf_id;
    }

    public function get_structure()
    {
        return $this->structure;
    }

    public function update_live_post_id($post_id)
    {
        $this->live_post_id = $post_id;
    }

    public function update_live_user_id($user_id)
    {
        $this->live_user_id = $user_id;
    }

    public function is_live_id($input)
    {
        if (!isset($input) || !$input) {
            return false;
        }

        $live_values = ["{{live_post_id}}", "{{live_user_id}}"];

        $trimmed_input = trim($input);

        if (in_array($trimmed_input, $live_values)) {
            return true;
        }

        return false;
    }

    public function update_live_pdf_url($pdf_path)
    {
        $this->live_pdf_url = $pdf_path;
    }

    public function update_live_pdf_path($pdf_path)
    {
        $this->live_pdf_path = $pdf_path;
    }

    public function update_live_pdf_id($pdf_id)
    {
        $this->live_pdf_id = $pdf_id;
    }

    public function update_proceeding_action($action)
    {
        $this->current_proceeding_action = $action;
    }

    private function handle_file_field_ids()
    {
        foreach ($this->uploaded_files as $key => $value) {
            foreach ($value as $file) {
                $field_id = substr($file['field'], strpos($file['field'], 'form-field-') + strlen('form-field-'));
                $this->file_field_ids[] = $field_id;
            }
        }
    }

    /**
     * Set action result
     *
     * type: success OR danger
     *
     * @param array $result
     * @return void
     */
    public function set_result($result)
    {
        $type = isset($result['type']) ? $result['type'] : 'success';

        // If type is success, add the $settings['successMessage'] as message
        if ($type === 'success') {

            if (isset($result["message"]) && $result["message"]) {
                $result["message"] = $this->get_form_field_by_id($result["message"]);
            } else {
                $result['message'] = isset($this->settings['successMessage']) ? $this->get_form_field_by_id($this->settings['successMessage']) : __('Message successfully sent. We will get back to you as soon as possible.', 'bricksforge');
            }
        }

        $this->results[$type][] = $result;
    }

    public function get_form_field_by_id($id, $form_data = null, $current_post_id = null, $form_settings = null, $form_files = null, $implode_array = true, $force_file_url_output = false, $ignore_files = false, $use_label = false)
    {
        // We render dynamic data for nested tags
        if (isset($this->dynamic_post_id) && $this->dynamic_post_id) {
            $id = bricks_render_dynamic_data($id, $this->dynamic_post_id);
        } else {
            $id = bricks_render_dynamic_data($id, $this->post_id);
        }

        // We replace {{live_post_id}} with the live post ID.
        if ($this->live_post_id) {
            $live_post_id_regex = '/{{live_post_id}}/';
            $id = preg_replace($live_post_id_regex, $this->live_post_id, $id);
        }

        // We replace {{live_user_id}} with the live user ID.
        $live_user_id_regex = '/{{live_user_id}}/';
        if ($this->live_user_id) {
            $id = preg_replace($live_user_id_regex, $this->live_user_id, $id);
        }

        // We replace {{live_pdf_url}} with the live PDF path.
        $live_pdf_url_regex = '/{{live_pdf_url}}/';
        if ($this->live_pdf_url) {
            $id = preg_replace($live_pdf_url_regex, $this->live_pdf_url, $id);
        }

        // We replace {{live_pdf_path}} with the live PDF path.
        $live_pdf_path_regex = '/{{live_pdf_path}}/';
        if ($this->live_pdf_path) {
            $id = preg_replace($live_pdf_path_regex, $this->live_pdf_path, $id);
        }

        // We replace {{live_pdf_id}} with the live PDF id
        $live_pdf_id_regex = '/{{live_pdf_id}}/';
        if ($this->live_pdf_id) {
            $id = preg_replace($live_pdf_id_regex, $this->live_pdf_id, $id);
        }

        // We replace {{all_fields}} with all form fields.
        if ($id == "all_fields" || $id == "{{all_fields}}") {
            return $this->get_all_fields($this->fields);
        }

        // Handle Repeaters
        if (isset($this->fields['brfr'])) {
            $original_text = $id;
            $repeater_implode_array = !strpos($id, ':array');

            // Extract placeholders
            preg_match_all('/{{([^}]+)}}/', $original_text, $matches);

            foreach ($matches[1] as $placeholder) {
                $repeater_id = str_replace([':array', ':implode', ':url'], '', $placeholder);
                $repeater_data = $this->fields['brfr'][$repeater_id] ?? null;

                if ($repeater_data) {
                    $replacer_text = $this->handle_repeater_fields($repeater_data, $this->fields, $repeater_implode_array);

                    if (is_string($replacer_text)) {
                        $original_text = str_replace("{{{$placeholder}}}", $replacer_text, $original_text);
                    }
                }
            }

            if (count($matches[1]) === 0) {
                if (isset($this->fields['brfr'][$id])) {
                    $original_text = $this->handle_repeater_fields($this->fields['brfr'][$id], $this->fields, $repeater_implode_array);
                }
            }

            $id = $original_text; // Update $id with processed repeater fields
        }


        foreach ($this->fields as $key => $value) {

            // form-field-{id}
            $field_id = explode('form-field-', $key);
            $field_id = isset($field_id[1]) ? $field_id[1] : null;

            if (!$field_id) {
                // Its not a Form Field. Continue.
                continue;
            }

            // If the ID has the format {{id}} or {{ id }}, we replace the variables with the values
            if (isset($id) && is_string($id) && strpos($id, '{{') !== false) {

                // If contains :url, we set $force_file_url_output to true
                if (strpos($id, ':url') !== false) {
                    $force_file_url_output = true;

                    // Remove :url from $id
                    $id = str_replace(':url', '', $id);
                }

                // If contains :id, we set $force_file_url_output to false
                if (strpos($id, ':id') !== false) {
                    $force_file_url_output = false;

                    // Remove :id from $id
                    $id = str_replace(':id', '', $id);
                }

                // If contains :implode, we set $implode_array to true
                if (strpos($id, ':implode') !== false) {
                    $implode_array = true;

                    // Remove :implode from $id
                    $id = str_replace(':implode', '', $id);
                }

                // If contains :array, we set $implode_array to false
                if (strpos($id, ':array') !== false) {
                    $implode_array = false;

                    // Remove :array from $id
                    $id = str_replace(':array', '', $id);
                }

                preg_match_all('/{{([^}]+)}}/', $id, $matches);

                foreach ($matches[1] as $match) {
                    $match_use_label = $use_label;


                    // If contains :label, we want to use the label instead of the value
                    if (strpos($match, ':label') !== false) {
                        $match_use_label = true;
                        $match = str_replace(':label', '', $match);
                        $id = str_replace($match . ':label', $match, $id);
                    } elseif (strpos($match, ':value') !== false) {
                        $match_use_label = false;
                        $match = str_replace(':value', '', $match);
                        $id = str_replace($match . ':value', $match, $id);
                    }

                    $value = $this->get_form_field_by_id(trim($match), $form_data, $current_post_id, $form_settings, $form_files, $implode_array, $force_file_url_output, $ignore_files, $match_use_label);

                    // If value is array, we have files. In that case, we replace each of them
                    if (!is_array($value)) {
                        // If the value remains the same, this variable seems to not exist. We return an empty string.
                        if ($value === $match) {
                            $id = str_replace('{{' . $match . '}}', "", $id);
                        } else {
                            $id = str_replace('{{' . $match . '}}', $value, $id);
                        }
                    } else {
                        $id = $match;

                        // Join the array with a comma
                        if ($implode_array & !$this->is_file($id)) {
                            $id = implode(', ', $value);
                        }
                    }
                }
            }

            if ($field_id === $id || $field_id === $id  . '[]') {

                // $field_structure -> $this->structure with field_id == $field_id
                $filtered_structure = array_filter($this->structure, function ($field) use ($field_id) {
                    return $field['field_id'] === $field_id;
                });

                $field_structure = reset($filtered_structure);

                if ($use_label) {
                    $value = $this->get_checkbox_radio_select_label_by_key($field_id, $value, $field_structure);
                }

                $field_name = null;

                if ($field_structure) {
                    $field_name = $field_structure["name"];

                    // Handle Date Format
                    if ($field_name === "date") {
                        $date_format_database = $field_structure["settings"]["dateFormatDatabase"];

                        if (isset($date_format_database) && $date_format_database) {
                            $value = date($date_format_database, strtotime($value));
                        }
                    }
                }

                // Check if there are files in the form data
                if (isset($this->uploaded_files) && !empty($this->uploaded_files) && !$ignore_files) {

                    // If there are files, check if the current field is a file field
                    foreach ($this->uploaded_files as $field => $files) {

                        foreach ($files as $file) {
                            // $field is form-field-my_field. Strip the "form-field-" part concretely
                            $field = substr($file['field'], strpos($file['field'], 'form-field-') + strlen('form-field-'));

                            if ($field === $id) {
                                // Check if the file has already been processed
                                if (isset($this->processed_file_cache[$id])) {
                                    return $this->processed_file_cache[$id];
                                }

                                // If it is a file field, handle this file.
                                $file_url = $this->handle_file($id, $form_settings, $this->uploaded_files, 'url', $force_file_url_output);

                                if ($file_url) {
                                    $this->processed_file_cache[$id] = $file_url;
                                    return $file_url;
                                }
                            }
                        }
                    }
                }

                // Handle base64 strings (for example coming from signature fields)
                if (is_string($value) && strpos($value, 'data:image') !== false) {
                    $base64_string = $value;
                    $base64_location = 'mediaLibrary';

                    if (isset($field_structure) && $field_structure && isset($field_structure["settings"]["uploadLocation"]) && $field_structure["settings"]["uploadLocation"] == "custom" && isset($field_structure["settings"]["uploadDirectory"])) {
                        $base64_location = $field_structure["settings"]["uploadDirectory"];

                        if (!$base64_location) {
                            $base64_location = 'mediaLibrary';
                        }
                    }

                    $base64_image_url = $this->handle_base64_string($base64_string, $force_file_url_output, $base64_location);

                    if ($base64_image_url) {
                        return $base64_image_url;
                    }
                }

                // If $value is an empty array, return empty string
                if (is_array($value) && empty($value)) {
                    return '';
                }

                // If $value is an array, return comma separated values
                if (is_array($value) && $implode_array) {
                    // Remove 0 values
                    $value = array_filter($value, function ($v) {
                        return $v !== '0';
                    });

                    // Re-index the array
                    $value = array_values($value);

                    // If is not empty, return comma separated values
                    if (is_array($value) && !empty($value)) {
                        return implode(', ', bricks_render_dynamic_data($value, $this->post_id));
                    } else {
                        return '';
                    }
                }

                return bricks_render_dynamic_data($value, $this->post_id);
            }
        }

        $result = bricks_render_dynamic_data($id, $this->post_id);

        if (!$result) {
            $result = bricks_render_dynamic_data($id, $this->dynamic_post_id);
        }

        // Reset Processed Files Array
        $this->processed_files = [];

        return $result;
    }

    /**
     * Get the label of a checkbox, radio or select field by the key
     *
     * @param string $key
     * @param string|array $value
     * @param array $structure
     * @return string|array
     */
    public function get_checkbox_radio_select_label_by_key($key, $value, $structure)
    {

        // structure -> children -> field_label
        if (!is_array($value)) {
            $value = [$value];
        }

        $value = array_map(function ($v) use ($key, $structure) {
            $structure_children = $structure['children'];

            // If the structure has a children with field_value == $v, we return the field_label
            if (is_array($structure_children) && !empty($structure_children)) {
                $field_label = reset(array_filter($structure_children, function ($child) use ($v, $key) {
                    return $child['field_id'] === $key && $child['field_value'] === $v;
                }));

                if ($field_label) {
                    return $field_label['field_label'];
                }
            }

            return $v;
        }, $value);

        return $value;
    }

    /**
     * Get the calculation value by the field ID
     *
     * @param string $id
     * @param string $value
     * @return string
     */
    public function get_calculation_value_by_id($id, $value)
    {
        // In the form settings, we search the id and check for calculationValue
        $calculation_value = null;

        $settings = reset(array_filter($this->structure, function ($field) use ($id) {
            return $field['field_id'] === $id;
        }));


        if ($settings) {
            // We check also for the $value
            // If the "children" key contains the value (field_value), we return the calculationValue
            $calculation_value = reset(array_filter($settings['children'], function ($child) use ($value) {
                return $child['settings']['value'] === $value && isset($child['settings']['calculationValue']);
            }));

            if ($calculation_value) {
                return $calculation_value['settings']['calculationValue'];
            }
        }

        return $value;
    }

    public function get_all_fields($form_data)
    {
        $fields = [];

        foreach ($form_data as $key => $value) {
            $field_id = explode('form-field-', $key);
            $field_id = isset($field_id[1]) ? $field_id[1] : null;

            if (!$field_id) {
                // Check if its a repeater. If not, continue
                if ($key !== 'brfr') {
                    continue;
                }

                // The Field ID is the first key of the array
                $field_id = key($value);
            }

            // We ignore base64 strings
            if (is_string($value) && strpos($value, 'data:image') !== false) {
                continue;
            }

            $fields[$field_id] = $value;
        }

        // If there are no fields, return an empty string
        if (empty($fields)) {
            return "";
        }

        // We implode the array. One field per line
        $fields = array_map(function ($value, $key) {
            $field_id = $key;
            $label = $this->get_label_by_key($key);

            if ($label) {
                $key = $label;
            }

            // If we have an repeater field, we handle it
            if (isset($this->fields['brfr']) && isset($this->fields['brfr'][$field_id])) {
                $value = $this->handle_repeater_fields($this->fields['brfr'][$field_id], $this->fields, true);

                if (is_string($value) && $value) {
                    return $key . ': ' . $value;
                }
            }

            // Check for files
            $is_file = is_array($value) && isset($value['file']) && isset($value['url']) && isset($value['type']);

            if ($is_file) {
                $value = $value['url'];
            }

            // If value is an array, join it with a comma
            if (is_array($value)) {
                // We remove 0 values
                $value = array_filter($value, function ($v) {
                    return $v !== '0';
                });

                // If is not empty, return comma separated values
                if (!empty($value)) {
                    $value = implode(', ', $value);
                } else {
                    return '';
                }
            }

            return $key . ': ' . $value;
        }, $fields, array_keys($fields));

        return implode("<br>", $fields);
    }

    public function handle_repeater_fields($repeater_data, $form_data = null, $implode_array = true)
    {

        $delimiter = ' ' . '+++' . ' ';

        if (!$repeater_data || !is_array($repeater_data) || empty($repeater_data)) {
            return [];
        }

        $organized_repeater_data = [];

        // Directly iterate through each item in the repeater data
        foreach ($repeater_data as $item_index => $fields) {

            foreach ($fields as $field_name => $field_value) {

                $is_file = is_array($field_value) && isset($field_value['file']) && isset($field_value['url']) && isset($field_value['type']) && isset($field_value['itemIndex']);

                if ($is_file) {

                    $file_id = $field_value['subFieldId'];
                    $item_index = $field_value['itemIndex'];
                    $file_url = $field_value['url'];

                    $attachment_id = $this->handle_file($file_id, $this->settings, $this->uploaded_files, 'id', false, true, $item_index);

                    if ($attachment_id) {
                        $field_value = $attachment_id;
                    } else {
                        $field_value = $file_url;
                    }
                } else {
                    // If the field value is an array with only one item, we take the first item (checkboxes, for example)
                    if (is_array($field_value) && count($field_value) === 1) {
                        $field_value = $field_value[0];
                    }
                }

                // Organize data by item index and field name
                $organized_repeater_data[$item_index][$field_name] = $field_value;
            }
        }

        // We join the array with a comma
        if ($implode_array) {
            $string_output = array_map(function ($item) {
                // Filter out empty values to avoid entries like "Color: "
                $filtered_item = array_filter($item, function ($value) {
                    return !empty($value);
                });
                // Convert each field into "Label: Value"
                $item_parts = array_map(function ($value, $key) {

                    // If value is an array, join it with a comma
                    if (is_array($value)) {
                        // We remove 0 values
                        $value = array_filter($value, function ($v) {
                            return $v !== '0';
                        });

                        // If is not empty, return comma separated values
                        if (!empty($value)) {
                            $value = implode(', ', $value);
                        } else {
                            return '';
                        }
                    }

                    return $this->get_label_by_key($key) . ': ' . $value; // Capitalize the label for better readability
                }, $filtered_item, array_keys($filtered_item));
                // Join all parts for the current item with a comma
                return implode(', ', $item_parts);
            }, $organized_repeater_data);

            // Join all items with a pipe
            return implode($delimiter, $string_output);
        }

        return json_encode($organized_repeater_data);
    }

    public function get_label_by_key($key)
    {
        $field_labels = isset($_POST['fieldLabels']) ? json_decode(stripslashes($_POST['fieldLabels']), true) : [];

        $matching = $field_labels[$key] ?? $key;
        $matching = $field_labels["brfr_{$key}"] ?? $matching; // Check for repeater labels

        if ($matching) {
            return $matching;
        }

        return "Field";
    }

    public function is_file($id)
    {
        // If the ID is included in the file_field_ids array, return true
        if (in_array($id, $this->file_field_ids)) {
            return true;
        }

        return false;
    }

    /**
     * Handle Thumbnail for different actions. 
     * Return the attachment ID
     * @param $thumbnail
     * @param $form_settings
     * @param $form_files
     * @return string
     * 
     */
    public function handle_file($file_id, $form_settings, $form_files, $format = 'id', $force_url_output = false, $is_repeater = false, $item_index = null)
    {
        // Check if the file has already been processed
        if (isset($this->processed_file_cache[$file_id])) {
            return $this->processed_file_cache[$file_id];
        }

        $uploaded_files = [];
        $file_array = [];

        // Handle Thumbnail
        if ($file_id && isset($form_files) && count($form_files)) {

            foreach ($form_files as $files) {
                foreach ($files as $file) {

                    // Todo: Stop for already proceeded files
                    // Still duplicate uploads. Surely because of the duplicated foreach loop.
                    /* if (in_array($file, $this->processed_files)) {
                        continue;
                    } */

                    $field = substr($file['field'], strpos($file['field'], 'form-field-') + strlen('form-field-'));

                    if ($is_repeater) {
                        $field = $file['subFieldId'];
                    }

                    // $file_id could be {{id}}. In that case, we need to strip the {{ and }} parts
                    if (strpos($file_id, '{{') !== false) {

                        // If contains :url, we set $force_file_url_output to true
                        if (strpos($file_id, ':url') !== false) {
                            $force_url_output = true;

                            // Remove :url from $file_id
                            $file_id = str_replace(':url', '', $file_id);
                        }

                        // If contains :id, we set $force_file_url_output to false
                        if (strpos($file_id, ':id') !== false) {
                            $force_url_output = false;

                            // Remove :url from $file_id
                            $file_id = str_replace(':id', '', $file_id);
                        }

                        $file_id = str_replace('{{', '', $file_id);
                        $file_id = str_replace('}}', '', $file_id);

                        // Remove spaces
                        $file_id = str_replace(' ', '', $file_id);
                    }

                    if ($field === $file_id) {
                        $uploaded_files[] = $file;
                    }
                }
            }
        }

        if ($uploaded_files && count($uploaded_files)) {

            foreach ($uploaded_files as $file) {

                // Handle Initial Files. We dont need to proceed here. We just
                if (isset($file['isInitialFile']) && $file['isInitialFile'] === true) {
                    $initial_file_attachment_id = $file['attachmentId'];
                    $initial_file_url = $file['url'];

                    if ($is_repeater) {
                        $current_proceesing_action = $this->get_current_proceeding_action();

                        switch ($current_proceesing_action) {
                            case 'email':
                                $file_array[] = $initial_file_url;
                                break;
                            default:
                                $file_array[] = $initial_file_attachment_id;
                                break;
                        }
                    } elseif ($format === 'url' && !class_exists('ACF') && !class_exists('RW_Meta_Box') && !class_exists('Jet_Engine')) {
                        $file_array[] = $initial_file_url;
                    } elseif ($format === 'url' && class_exists('Jet_Engine') && !class_exists('RW_Meta_Box') && !class_exists('ACF')) {
                        $file_array[] = $initial_file_url;
                    } elseif ($force_url_output) {
                        $file_array[] = $initial_file_url;
                    } else {
                        $file_array[] = $initial_file_attachment_id;
                    }

                    $this->processed_files[] = $file;
                    continue;
                }


                $file_name = $file['name'];
                $file_path = $file['file'];

                if ($is_repeater && $file['itemIndex'] != $item_index) {
                    continue;
                }

                if (file_exists($file_path)) {

                    $attachment = array(
                        'guid'           => $file['url'],
                        'post_mime_type' => $file['type'],
                        'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_path)),
                        'post_content'   => '',
                        'post_status'    => 'inherit',
                    );

                    $attach_id = wp_insert_attachment($attachment, $file_path);

                    require_once ABSPATH . 'wp-admin/includes/image.php';
                    require_once ABSPATH . 'wp-admin/includes/media.php';

                    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    if ($is_repeater) {
                        $current_proceesing_action = $this->get_current_proceeding_action();

                        switch ($current_proceesing_action) {
                            case 'email':
                                $file_array[] = wp_get_attachment_url($attach_id);
                                break;
                            default:
                                $file_array[] = $attach_id;
                                break;
                        }
                    } elseif ($format === 'url' && !class_exists('ACF') && !class_exists('RW_Meta_Box') && !class_exists('Jet_Engine')) {
                        $file_array[] = wp_get_attachment_url($attach_id);
                    } elseif ($format === 'url' && class_exists('Jet_Engine') && !class_exists('RW_Meta_Box') && !class_exists('ACF')) {
                        $file_array[] = wp_get_attachment_url($attach_id);
                    } elseif ($force_url_output) {
                        $file_array[] = wp_get_attachment_url($attach_id);
                    } else {
                        $file_array[] = $attach_id;
                    }
                }

                $this->processed_files[] = $file;
            }
        }

        // If file arrays count is 1, return the first item
        if (count($file_array) === 1) {
            $this->processed_file_cache[$file_id] = $file_array[0];
            return $file_array[0];
        }

        $this->processed_file_cache[$file_id] = $file_array;

        return $file_array;
    }

    public function handle_base64_string($base64_string, $url = false, $location = "mediaLibrary")
    {
        // Set a maximum file size limit (e.g., 2MB)
        $max_file_size = 2 * 1024 * 1024;

        $is_custom_location = $location != "mediaLibrary";

        // Remove the base64 header and decode the string
        $base64_string = str_replace('data:image/png;base64,', '', $base64_string);
        $base64_string = str_replace(' ', '+', $base64_string);
        $binary_data = base64_decode($base64_string, true);

        // Check if the base64 decoding was successful
        if ($binary_data === false) {
            return new WP_Error('invalid_base64', 'Invalid base64 image data.');
        }

        // Check if the file size exceeds the limit
        if (strlen($binary_data) > $max_file_size) {
            return new WP_Error('file_too_large', 'File size exceeds the maximum allowed limit.');
        }

        // Upload Directory
        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['path'];
        $upload_url = $upload_dir['url'];

        if ($is_custom_location) {
            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['basedir'] . '/' . $location;
            $upload_url = $upload_dir['baseurl'] . '/' . $location;

            // Create the directory if it doesn't exist
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            // Check if the directory was created successfully
            if (!file_exists($upload_path)) {
                return new WP_Error('directory_creation_failed', 'Failed to create the upload directory.');
            }
        }

        // Generate a unique filename
        $file_name = 'signature-' . time() . '.png';
        $file_name = sanitize_file_name($file_name);

        $file_path = $upload_path . '/' . $file_name;

        // Save the binary data to the upload directory
        $file_saved = file_put_contents($file_path, $binary_data);

        // Check if the file was saved successfully
        if ($file_saved === false) {
            return new WP_Error('file_save_failed', 'Failed to save the file to the upload directory.');
        }

        if (!$is_custom_location) {
            $attachment = array(
                'guid' => $upload_url . '/' . $file_name,
                'post_mime_type' => 'image/png',
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_path)),
                'post_content' => '',
                'post_status' => 'inherit',
            );

            $attach_id = wp_insert_attachment($attachment, $file_path);

            if (is_wp_error($attach_id)) {
                // Failed to insert the attachment
                unlink($file_path); // Delete the uploaded file
                return $attach_id;
            }

            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';

            $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
            wp_update_attachment_metadata($attach_id, $attach_data);

            if ($url) {
                return wp_get_attachment_url($attach_id);
            }

            return $attach_id;
        }

        // Custom Location
        return $upload_url . '/' . $file_name;
    }
}
