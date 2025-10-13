<?php

namespace Bricksforge\Api;

if (!defined('ABSPATH')) {
    exit;
}

use WP_REST_Controller;

include_once(BRICKSFORGE_PATH . '/includes/api/FormsHelper.php');

/**
 * REST_API Handler
 */
class Bricksforge extends WP_REST_Controller
{
    private $forms_helper;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->namespace = 'bricksforge/v1';
        $this->forms_helper = new FormsHelper();
    }

    /**
     * Register the routes
     *
     * @return void
     */
    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/get_shortcode_content',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'get_shortcode_content'),
                    'permission_callback' => array($this, 'allowed'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/get_user_roles',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_user_roles'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/get_permissions_roles',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_permissions_roles'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/save_permissions_roles',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'save_permissions_roles'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/remove_user_role',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'remove_user_role'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/get_global_classes',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_global_classes'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/save_global_classes',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'save_global_classes'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/save_tools',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'save_tools'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/save_elements',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'save_elements'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/save_popups',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'save_popups'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/save_maintenance',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'save_maintenance'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/save_whitelabel',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'save_whitelabel'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/save_panel',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'save_panel'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/save_settings',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'save_settings'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/save_option',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'save_option'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/get_option',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_option'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/reset_to_default',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'reset_to_default'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/export_settings',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'export_settings'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/import_settings',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'import_settings'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/form_submit',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array(new \Bricksforge\ProForms\Actions\Init, 'form_submit'),
                    'permission_callback' => array($this, 'allowed'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/render_form_data',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'render_form_data'),
                    'permission_callback' => array($this, 'allowed'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/create_global_classes_backup',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'create_global_classes_backup'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/restore_global_classes_backup',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'restore_global_classes_backup'),
                    'permission_callback' => array($this, 'allow_permission'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/submissions',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_submissions'),
                    'permission_callback' => array($this, 'allow_permission_lite'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/delete_submissions',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'delete_submissions'),
                    'permission_callback' => array($this, 'allow_permission_lite'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/delete_submissions_table',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'delete_submissions_table'),
                    'permission_callback' => array($this, 'allow_permission_lite'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/get_submissions_display_settings',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_submissions_display_settings'),
                    'permission_callback' => array($this, 'allow_permission_lite'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/get_unread_submissions',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_unread_submissions'),
                    'permission_callback' => array($this, 'allow_permission_lite'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/get_form_title',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_form_title_by_id'),
                    'permission_callback' => array($this, 'allow_permission_lite'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/render_dynamic_data',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'render_dynamic_data'),
                    'permission_callback' => array($this, 'allowed'),
                )
            )
        );

        // Register rest root for an openai api call
        register_rest_route(
            $this->namespace,
            '/ai',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'ai_task'),
                    'permission_callback' => array($this, 'allow_permission_lite'),
                )
            )
        );

        // Form File Uploads
        register_rest_route(
            $this->namespace,
            '/form_file_upload',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'form_file_upload'),
                    'permission_callback' => array($this, 'allowed'),
                )
            )
        );
        register_rest_route(
            $this->namespace,
            '/form_file_load_initial_files',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'form_file_load_initial_files'),
                    'permission_callback' => array($this, 'allowed'),
                )
            )
        );
    }

    /**
     * Get Shortcode Content
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_shortcode_content($request)
    {
        $response = $request->get_body();

        if (!$response) {
            return;
        }

        $output = "";

        $template_id = $response;

        /**
         * Workaround: Bricks currently does not load styles if the template is loaded outside.
         */
        $elements = get_post_meta($template_id, BRICKS_DB_PAGE_CONTENT, true); #
        $inline_css = \Bricks\Templates::generate_inline_css($template_id, $elements);

        // NOTE: Not the perfect solution – but currently the way to go.
        $output .= "<style id=\"bricks-inline-css-template-{$template_id}\">{$inline_css}</style>";
        $output .= \Bricks\Frontend::render_data($elements);

        return json_encode($output);
    }

    /**
     * Get User Roles
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_user_roles($request)
    {
        global $wp_roles;

        $all_roles = $wp_roles->roles;
        $editable_roles = apply_filters('editable_roles', $all_roles);

        $response = rest_ensure_response($editable_roles);

        return $response;
    }

    /**
     * Get Permission Roles
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_permissions_roles($request)
    {
        $response = rest_ensure_response(get_option('brf_permissions_roles'));
        return $response;
    }

    /**
     * Save Permission Roles
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function save_permissions_roles($request)
    {
        global $wp_roles;

        $data = $request->get_body();

        $roles = json_decode($data);


        if (!$roles || !is_array($roles)) {
            return false;
        }

        update_option('brf_permissions_roles', $roles, 'no');

        return true;
    }

    /**
     * Removes a custom User Role
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function remove_user_role($request)
    {
        $role = $request->get_body();
        if ($role != 'administrator' && $role->value != 'editor' && $role->value != 'author' && $role->value != 'contributor' && $role->value != 'subscriber') {
            remove_role(json_decode($role));
        }
        return true;
    }

    /**
     * Get Global Classes
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_global_classes($request)
    {
        $response = rest_ensure_response(get_option('brf_global_classes'));
        return $response;
    }

    /**
     * Save Global Classes
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function save_global_classes($request)
    {

        $data = $request->get_body();
        $categories = json_decode($data)[0];
        $activated = json_decode($data)[1];
        $excluded_classes = isset(json_decode($data)[2]) ? json_decode($data)[2] : [];

        if (!isset($categories)) {
            return false;
        }

        if (count($categories) === 0) {
            update_option('brf_global_classes', $categories, "no");
            return false;
        }

        if (!isset($activated)) {
            return false;
        }

        if (!is_array($categories)) {
            return false;
        }

        update_option('brf_global_classes_activated', $activated);

        update_option('brf_global_classes_excluded', $excluded_classes);

        update_option('brf_global_classes', $categories, "no");

        $global_classes = get_option('bricks_global_classes') ? get_option('bricks_global_classes') : [];
        $global_classes_locked = get_option('bricks_global_classes_locked') ? get_option('bricks_global_classes_locked') : [];

        foreach ($categories as $category) {

            foreach ($category->classes as $class) {
                if (($key = array_search($class, $global_classes_locked)) !== false) {
                    unset($global_classes_locked[$key]);
                }

                foreach ($global_classes as $key => $row) {
                    if (isset($row["source"]) && $row["source"] === 'bricksforge') {
                        unset($global_classes[$key]);
                    }
                }
            }
        }

        $global_classes = array_values($global_classes);
        $global_classes_locked = array_values($global_classes_locked);

        foreach ($categories as $category) {

            $is_active = isset($category->active) ? $category->active : true;

            if (!$is_active) {
                continue;
            }

            if ($category->classes && !empty($category->classes)) {

                // Stop here if global classes are not activated
                if (!get_option('brf_global_classes_activated') || get_option('brf_global_classes_activated') == false) {
                    update_option('bricks_global_classes', $global_classes);
                    update_option('bricks_global_classes_locked', $global_classes_locked);
                    return false;
                }

                foreach ($category->classes as $class) {
                    array_push($global_classes, [
                        "id"       => $class,
                        "name"     => $class,
                        "settings" => array(),
                        "source"   => "bricksforge"
                    ]);

                    array_push($global_classes_locked, $class);
                }
            }
        }
        $global_classes = array_map("unserialize", array_unique(array_map("serialize", $global_classes)));
        $global_classes_locked = array_unique($global_classes_locked);
        $global_classes = array_values($global_classes);
        $global_classes_locked = array_values($global_classes_locked);

        update_option('bricks_global_classes', $global_classes);
        update_option('bricks_global_classes_locked', $global_classes_locked);

        $success = $this->render_css_files($categories);

        return $success === true;
    }

    /**
     * Save Tools
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function save_tools($request)
    {
        global $wp_roles;

        $data = $request->get_body();

        $tools = json_decode($data);

        if (!isset($tools) || !is_array($tools)) {
            return false;
        }

        update_option('brf_activated_tools', $tools);

        return true;
    }

    /**
     * Save Elements
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function save_elements($request)
    {
        global $wp_roles;

        $data = $request->get_body();

        $elements = json_decode($data);

        if (!isset($elements) || !is_array($elements)) {
            return false;
        }

        $elements = $this->handle_element_settings($elements);

        update_option('brf_activated_elements', $elements);

        return true;
    }

    /**
     * Save Popups
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function save_popups($request)
    {
        global $wp_roles;

        $data = $request->get_body();

        $elements = json_decode($data);

        if (is_null($elements) || !is_array($elements)) {
            return false;
        }

        update_option('brf_popups', $elements);

        return true;
    }

    /**
     * Save Maintenance Settings
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function save_maintenance($request)
    {
        global $wp_roles;

        $data = $request->get_body();

        $settings = json_decode($data);

        if (!$settings || !is_array($settings)) {
            return false;
        }

        update_option('brf_maintenance', $settings);

        return true;
    }

    /**
     * Save White Label Settings
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function save_whitelabel($request)
    {
        global $wp_roles;

        $data = $request->get_body();

        $settings = json_decode($data);

        if (!$settings || !is_array($settings)) {
            return false;
        }

        update_option('brf_whitelabel', $settings);

        return true;
    }

    /**
     * Save Panel Settings
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function save_panel($request)
    {
        global $wp_roles;

        $data = $request->get_body();

        $settings = json_decode($data);

        if (is_null($settings) || !is_array($settings)) {
            return false;
        }

        update_option('brf_panel', $settings, "no");

        return true;
    }

    /**
     * Save Settings
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function save_settings($request)
    {
        $data = $request->get_body();

        $settings = json_decode($data);

        update_option('brf_settings', $settings);

        return $settings;
    }

    /**
     * Save Option
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function save_option($request)
    {
        // Retrieve data from the body of the request
        $data = json_decode($request->get_body());

        // Check if we have valid data and enough elements for key-value pair
        if (is_array($data) && count($data) >= 2) {
            // Sanitize the data
            $key = sanitize_text_field($data[0]);
            $value = $data[1];
            $autoload = isset($data[2]) && $data[2] === 'no' ? "no" : "yes";

            // If key is "brf_tool_settings", we need some encryption for sensitive data
            if ($key === 'brf_tool_settings') {
                $value = $this->handle_tool_settings($value);
            }

            // Update the option
            $update_status = update_option($key, $value, $autoload);

            // Check if update was successful
            if (!$update_status) {
                return rest_ensure_response('No update processed');
            }
        } else {
            error_log("Invalid data received");
            return new \WP_Error('invalid_data', 'Invalid data received', array('status' => 400));
        }

        return rest_ensure_response('Option successfully updated');
    }

    private function handle_tool_settings($value)
    {
        $new_value = $value;

        $keys_to_encrypt = [
            14 => 'apiKey',  // Key ID => Key field name
        ];

        foreach ($keys_to_encrypt as $key_id => $key_field) {
            $current_key = null;
            $encrypted_key = null;

            foreach ($value as $item) {
                if ($item->id === $key_id) {
                    $current_key = isset($item->settings->$key_field) ? $item->settings->$key_field : null;
                    break;
                }
            }

            if ($current_key) {
                $stored_value = get_option('brf_tool_settings');

                $stored_key = null;

                if (is_array($stored_value)) {
                    foreach ($stored_value as $item) {
                        if ($item->id === $key_id) {
                            $stored_key = isset($item->settings->$key_field) ? $item->settings->$key_field : null;
                            break;
                        }
                    }
                }

                if ($stored_key === $current_key) {
                    // Key has not changed, no need to re-encrypt
                    $encrypted_key = $current_key;
                }

                if (!$encrypted_key) {
                    $encrypted_key = $this->encryptIfKeyChanged($current_key, $stored_key);
                }
            }

            // Update the encrypted key in the $new_value array
            foreach ($new_value as &$item) {
                if ($item->id === $key_id && isset($item->settings->$key_field)) {
                    $item->settings->$key_field = $encrypted_key;
                    break;
                }
            }
        }

        return $new_value;
    }

    private function handle_element_settings($value)
    {
        $new_value = $value;

        $keys_to_encrypt = [
            5 => ['hCaptchaKey', 'hCaptchaSecret', 'turnstileKey', 'turnstileSecret'],
            1 => ['kitID']
        ];

        foreach ($keys_to_encrypt as $key_id => $key_fields) {
            $keyFieldUpdated = false;

            foreach ($key_fields as $key_field) {
                $current_key = null;
                $encrypted_key = null;

                foreach ($value as $item) {
                    if ($item->id === $key_id) {
                        $current_key = isset($item->settings->$key_field) ? $item->settings->$key_field : null;
                        break;
                    }
                }

                if ($current_key) {
                    $stored_value = get_option('brf_activated_elements');

                    $stored_key = null;

                    if (is_array($stored_value)) {
                        foreach ($stored_value as $item) {
                            if ($item->id === $key_id) {
                                $stored_key = isset($item->settings->$key_field) ? $item->settings->$key_field : null;
                                break;
                            }
                        }
                    }

                    if ($stored_key === $current_key) {
                        // Key has not changed, no need to re-encrypt
                        $encrypted_key = $current_key;
                    }

                    if (!$encrypted_key) {
                        $encrypted_key = $this->encryptIfKeyChanged($current_key, $stored_key);
                    }
                }

                // Update the encrypted key in the $new_value array
                foreach ($new_value as $index => $item) {
                    if ($item->id === $key_id && isset($item->settings->$key_field)) {
                        $new_value[$index]->settings->$key_field = $encrypted_key;
                        $keyFieldUpdated = true;
                    }
                }
            }

            if (!$keyFieldUpdated) {
                // If none of the key fields were updated, reset the encrypted values
                foreach ($new_value as $index => $item) {
                    if ($item->id === $key_id) {
                        foreach ($key_fields as $key_field) {
                            $new_value[$index]->settings->$key_field = null;
                        }
                    }
                }
            }
        }

        return $new_value;
    }


    private function encryptIfKeyChanged($current_key, $stored_key)
    {
        if ($current_key !== $stored_key) {
            $utils = new \Bricksforge\Api\Utils();
            return $utils->encrypt($current_key);
        }

        return $current_key;
    }

    /**
     * Get Option
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_option($request)
    {
        $key = $request->get_param('_key');

        return get_option($key);
    }

    /**
     * Reset to default
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function reset_to_default($request)
    {
        delete_option('brf_permissions_roles');
        delete_option('brf_global_classes_activated');
        delete_option('brf_global_classes_excluded');
        delete_option('brf_global_classes');
        delete_option('brf_activated_tools');
        delete_option('brf_activated_elements');
        delete_option('brf_popups');
        delete_option('brf_maintenance');
        delete_option('brf_whitelabel');
        delete_option('brf_panel');
        delete_option('brf_panel_nodes');
        delete_option('brf_tool_settings');
        delete_option('brf_terminal_history');
        delete_option('brf_backend_designer');
        delete_option('brf_unread_submissions');
        delete_option('brf_email_designer_data');
        delete_option('brf_email_designer_themes');
        delete_option('brf_page_transitions');
    }

    /**
     * Export all settings
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function export_settings()
    {
        $current_time = current_time('Y-m-d-H-i-s');
        $options = array(
            'brf_permissions_roles'        => get_option('brf_permissions_roles'),
            'brf_global_classes_activated' => get_option('brf_global_classes_activated'),
            'brf_global_classes_excluded'  => get_option('brf_global_classes_excluded'),
            'brf_global_classes'           => get_option('brf_global_classes'),
            'brf_activated_tools'          => get_option('brf_activated_tools'),
            'brf_activated_elements'       => get_option('brf_activated_elements'),
            'brf_popups'                   => get_option('brf_popups'),
            'brf_maintenance'              => get_option('brf_maintenance'),
            'brf_whitelabel'               => get_option('brf_whitelabel'),
            'brf_panel'                    => get_option('brf_panel'),
            'brf_panel_nodes'              => get_option('brf_panel_nodes'),
            'brf_tool_settings'            => get_option('brf_tool_settings'),
            'brf_terminal_history'         => get_option('brf_terminal_history'),
            'brf_backend_designer'         => get_option('brf_backend_designer'),
            'brf_email_designer_data'      => get_option('brf_email_designer_data'),
            'brf_email_designer_themes'    => get_option('brf_email_designer_themes'),
            'brf_page_transitions'         => get_option('brf_page_transitions'),

        );
        $json_data = json_encode($options);
        $file_name = 'bricksforge' . $current_time . '.json';

        // Set the appropriate headers
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Content-Length: ' . strlen($json_data));

        // Send the file to the client
        echo $json_data;
        exit;
    }

    /**
     * Import settings
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function import_settings($request)
    {
        $settings = json_decode($request->get_body());

        if (is_null($settings) || !is_object($settings)) {
            return false;
        }

        update_option('brf_permissions_roles', $settings->brf_permissions_roles, 'no');
        update_option('brf_global_classes_activated', $settings->brf_global_classes_activated, 'no');
        update_option('brf_global_classes_excluded', $settings->brf_global_classes_excluded, 'no');
        update_option('brf_global_classes', $settings->brf_global_classes, 'no');
        update_option('brf_activated_tools', $settings->brf_activated_tools, 'no');
        update_option('brf_activated_elements', $settings->brf_activated_elements, 'no');
        update_option('brf_popups', $settings->brf_popups, 'no');
        update_option('brf_maintenance', $settings->brf_maintenance, 'no');
        update_option('brf_whitelabel', $settings->brf_whitelabel, 'no');
        update_option('brf_panel', $settings->brf_panel, 'no');
        update_option('brf_panel_nodes', $settings->brf_panel_nodes, 'no');
        update_option('brf_tool_settings', $settings->brf_tool_settings, 'no');
        update_option('brf_terminal_history', $settings->brf_terminal_history, 'no');
        update_option('brf_backend_designer', $settings->brf_backend_designer, 'no');
        update_option('brf_email_designer_data', $settings->brf_email_designer_data, 'no');
        update_option('brf_email_designer_themes', $settings->brf_email_designer_themes, 'no');
        update_option('brf_page_transitions', $settings->brf_page_transitions, 'no');

        return true;
    }

    public function get_submissions_display_settings()
    {
        return get_option('brf_submissions_display_settings');
    }

    public function get_unread_submissions()
    {
        return get_option('brf_unread_submissions');
    }

    /**
     * Checks if a given request has access to read the data.
     *
     * @param  WP_REST_Request $request Full details about the request.
     *
     * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
     */
    public function allow_permission($request)
    {
        $nonce = $request->get_header('X-WP-Nonce');
        return is_user_logged_in() && wp_verify_nonce($nonce, 'wp_rest') && current_user_can('manage_options');
    }

    public function allow_permission_lite($request)
    {
        $nonce = $request->get_header('X-WP-Nonce');
        return is_user_logged_in() && wp_verify_nonce($nonce, 'wp_rest') && current_user_can('edit_posts');
    }

    /**
     * Allow Permission
     *
     * @param  WP_REST_Request $request Full details about the request.
     *
     * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
     */
    public function allowed($request)
    {
        $nonce = $request->get_header('X-WP-Nonce');
        return wp_verify_nonce($nonce, 'wp_rest');
    }

    public function clear_temp_directory()
    {
        $temp_directory = BRICKSFORGE_UPLOADS_DIR . 'temp/';

        if (is_dir($temp_directory)) {
            $files = glob($temp_directory . '*', GLOB_MARK);
            $time_now = time(); // Aktuelle Zeit

            foreach ($files as $file) {
                if (is_writable($file)) {
                    $file_mod_time = filemtime($file);
                    $age = $time_now - $file_mod_time;

                    // If that file is older than 1 hour, delete it
                    if ($age > 3600) {
                        unlink($file);
                    }
                }
            }
        }
    }

    public function render_form_data($request)
    {
        $form_settings = \Bricks\Helpers::get_element_settings($request->get_param('postId'), $request->get_param('formId'));

        if (!isset($form_settings) || empty($form_settings)) {
            return false;
        }

        $form_actions = isset($form_settings['actions']) ? $form_settings['actions'] : null;
        $form_data = $request->get_body_params();
        $post_id = $request->get_param('postId');
        $dynamic_post_id = $request->get_param('dynamicPostId');

        $submit_conditions = array();
        $submissions_count = null;
        $wc_data = (object)[];

        $submitButtonHasCondition = isset($form_settings['submitButtonHasCondition']) && $form_settings['submitButtonHasCondition'] === true;
        $has_wc_add_to_cart_action = class_exists('WooCommerce') && isset($form_actions) && is_array($form_actions) && in_array('wc_add_to_cart', $form_actions);

        if ($submitButtonHasCondition) {
            foreach ($form_settings["submitButtonConditions"] as $condition) {
                $value1 = bricks_render_dynamic_data($condition['submitButtonConditionValue'], $dynamic_post_id ? $dynamic_post_id : $post_id);
                $value1 = $this->forms_helper->get_form_field_by_id($value1, $form_data);

                $value2 = bricks_render_dynamic_data($condition['submitButtonConditionValue2'], $dynamic_post_id ? $dynamic_post_id : $post_id);
                $value2 = $this->forms_helper->get_form_field_by_id($value2, $form_data);

                $submit_conditions[] = [
                    'condition' => $condition['submitButtonCondition'],
                    'operator'  => $condition['submitButtonConditionOperator'],
                    'dataType'  => isset($condition['submitButtonConditionType']) ? $condition['submitButtonConditionType'] : null,
                    'value'     => $value1,
                    'value2'    => $value2,
                    'post_id'   => isset($condition['submitButtonConditionPostId']) ? bricks_render_dynamic_data($condition['submitButtonConditionPostId'], $dynamic_post_id ? $dynamic_post_id : $post_id) : null,
                ];

                switch ($condition['submitButtonCondition']) {
                    case 'option':
                        $submit_conditions[count($submit_conditions) - 1]['data'] = get_option($condition['submitButtonConditionValue']);
                        break;
                    case 'post_meta':
                        $s_post_id = $this->forms_helper->get_form_field_by_id($submit_conditions[count($submit_conditions) - 1]['post_id'], $form_data);
                        $submit_conditions[count($submit_conditions) - 1]['data'] = get_post_meta($s_post_id, $condition['submitButtonConditionValue'], true);
                        break;
                    case 'storage_item':
                        $submit_conditions[count($submit_conditions) - 1]['data'] = get_option($condition['submitButtonConditionValue']);
                        break;
                    case 'form_field':
                        $fieldValue = $form_data["form-field-" . $condition['submitButtonConditionValue']];
                        $submit_conditions[count($submit_conditions) - 1]['data'] = isset($fieldValue) ? $fieldValue : $form_data["form-field-" . $condition['submitButtonConditionValue'] . '[]'];
                        break;
                    case 'submission_count_reached':
                        global $wpdb;
                        $table_name = $wpdb->prefix . BRICKSFORGE_SUBMISSIONS_DB_TABLE;
                        $form_id = sanitize_text_field($request->get_param('formId'));
                        $submissions_count = $wpdb->get_var(
                            $wpdb->prepare(
                                "SELECT COUNT(*) FROM $table_name WHERE form_id = %s",
                                $form_id
                            )
                        );
                        break;
                    case 'submission_field':
                        global $wpdb;
                        $table_name = $wpdb->prefix . BRICKSFORGE_SUBMISSIONS_DB_TABLE;
                        $form_id = sanitize_text_field($request->get_param('formId'));
                        $submissions = $wpdb->get_results(
                            $wpdb->prepare(
                                "SELECT fields FROM $table_name WHERE form_id = %s",
                                $form_id
                            )
                        );

                        if (!empty($submissions)) {
                            $submissions = json_decode(json_encode($submissions), true);

                            foreach ($submissions as $submission) {
                                $submission = json_decode($submission['fields'], true);

                                foreach ($submission['fields'] as $submissionField) {
                                    if ($submissionField['id'] == $condition['submitButtonConditionValue'] && $submissionField['value'] == $condition['submitButtonConditionValue2']) {
                                        $submit_conditions[count($submit_conditions) - 1]['data'][] = $submissionField;
                                    }
                                }
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        if (isset($form_settings['submitButtonConditionsAlternativeText']) && !empty($form_settings['submitButtonConditionsAlternativeText'])) {
            $form_settings['submitButtonConditionsAlternativeText'] = bricks_render_dynamic_data($form_settings['submitButtonConditionsAlternativeText'], $dynamic_post_id ? $dynamic_post_id : $post_id);
        }

        // If uses add to cart, pass the current variant price to the form data
        if ($has_wc_add_to_cart_action) {
            $product = isset($form_settings['pro_forms_post_action_add_to_cart_product']) ? $form_settings['pro_forms_post_action_add_to_cart_product'] : null;
            $product_id = isset($form_settings['pro_forms_post_action_add_to_cart_product_id']) ? $form_settings['pro_forms_post_action_add_to_cart_product_id'] : null;

            if ($product !== 'custom') {
                $product_id = $product;
            } else {
                $product_id = $this->forms_helper->get_form_field_by_id($product_id, $form_data);
            }

            $custom_fields = isset($form_settings['pro_forms_post_action_add_to_cart_custom_fields']) ? $form_settings['pro_forms_post_action_add_to_cart_custom_fields'] : null;

            $wc_data->variation_price = $this->forms_helper->get_variation_price($product_id, $custom_fields, $form_data);
        }

        $output = [
            "postID"           => $dynamic_post_id ? $dynamic_post_id : $post_id,
            "formID"           => $request->get_param('formId'),
        ];

        if ($submitButtonHasCondition === true) {
            $output["submitConditions"] = $submit_conditions;
            $output['submitConditionsData'] = [
                'relation' => $form_settings['submitButtonConditionsRelation'],
                "submissionsCount" => $submissions_count ? $submissions_count : null,
                "submissionsMax" => isset($form_settings['submission_max']) ? $form_settings['submission_max'] : null,
                "submitButtonConditionAction" => $form_settings['submitButtonConditionAction'],
                "submitButtonConditionsAlternativeText" => isset($form_settings['submitButtonConditionsAlternativeText']) ? $form_settings['submitButtonConditionsAlternativeText'] : null,
                "submitButtonText" => isset($form_settings['submitButtonText']) ? $form_settings['submitButtonText'] : null,
            ];
        }

        if ($has_wc_add_to_cart_action === true) {
            $output["wc"] = $wc_data;
        }

        return $output;
    }

    public function form_file_upload()
    {
        require_once(ABSPATH . 'wp-admin/includes/file.php');

        $file_data = $_FILES;

        $allowed_file_types = isset($_POST['allowed_file_types']) ? $_POST['allowed_file_types'] : array('image/jpeg', 'image/png', 'image/webp', 'image/gif', 'application/pdf', 'video/mp4', 'audio/mpeg');
        $max_file_size = isset($_POST['max_file_size']) ? ($_POST['max_file_size'] * 1024 * 1024) : 20971520; // 20 MB

        // If file type is a string, we need to split it into an array (by comma)
        if (is_string($allowed_file_types)) {
            // Remove . from the file types
            $allowed_file_types = str_replace('.', '', $allowed_file_types);
            $allowed_file_types = explode(',', $allowed_file_types);
        }

        // We map the file types to convert mp4 to video/mp4
        $allowed_file_types =  array_map(function ($type) {
            $type = strtolower($type);

            if ($type === 'mp4' || $type === 'mov' || $type === 'avi' || $type === 'wmv') {
                return 'video/' . $type;
            }

            if ($type === 'mp3' || $type === 'wav' || $type === 'ogg') {
                return 'audio/' . $type;
            }

            if ($type === 'pdf' || $type === "zip") {
                return 'application/' . $type;
            }

            if ($type === 'jpg' || $type === 'jpeg' || $type === 'png' || $type === 'gif' || $type === 'webp') {
                return 'image/' . $type;
            }

            return $type;
        }, $allowed_file_types);

        $uploaded_files = [];

        // Iterate over each file element in $_FILES
        foreach ($file_data as $key => $files) {
            // Handle multiple files for each field
            foreach ($files['name'] as $index => $name) {
                $file = array(
                    'name'     => $files['name'][$index],
                    'type'     => $files['type'][$index],
                    'tmp_name' => $files['tmp_name'][$index],
                    'error'    => $files['error'][$index],
                    'size'     => $files['size'][$index]
                );

                // Check if the file was uploaded successfully without errors
                if ($file['error'] === 0) {
                    // Check the file type
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $file_type = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);

                    // Define allowed file types
                    $allowed_types = $allowed_file_types;
                    $allowed_types = apply_filters('bricksforge/pro_forms/allowed_file_types', $allowed_types);

                    // Skip the file if the type is not allowed
                    if (!is_array($allowed_types) || !in_array($file_type, $allowed_types)) {
                        wp_send_json_error('File type not allowed');
                        continue;
                    }

                    // Check the file size
                    $file_size = filesize($file['tmp_name']);
                    $max_size = $max_file_size;
                    $max_size = apply_filters('bricksforge/pro_forms/max_file_size', $max_size);

                    // Skip the file if it is too large
                    if ($file_size > $max_size) {
                        wp_send_json_error('File size exceeds the maximum allowed size');
                        continue;
                    }

                    // We use wp_handle_upload to move the file to the destination path
                    $uploaded_file = wp_handle_upload($file, array('test_form' => false));

                    if (is_wp_error($uploaded_file)) {
                        wp_send_json_error($uploaded_file->get_error_message());
                    }

                    // We add the filename to the $uploaded_file. We need to strip the path from the filename
                    $original_filename = $file['name'];

                    if ($original_filename) {
                        $uploaded_file['originalFilename'] = $original_filename;
                    }

                    $original_filesize = $file['size'];
                    if ($original_filesize) {
                        $uploaded_file['originalFilesize'] = $original_filesize;
                    }

                    $this->clean_up_temp_files();

                    $temp_files = get_option('brf_temp_files', array());
                    $temp_files[] = $uploaded_file['file'];

                    update_option('brf_temp_files', $temp_files);

                    $uploaded_files[] = $uploaded_file;

                    // Return the uploaded files
                    wp_send_json_success($uploaded_files);
                }
            }
        }

        // Check if any files were uploaded
        if (empty($uploaded_files)) {
            wp_send_json_error('No files uploaded');
        }

        // Return the uploaded files
        wp_send_json_success($uploaded_files);
    }

    public function form_file_load_initial_files()
    {
        $file = $_POST['file'];

        if (!isset($file)) {
            wp_send_json_error('No file provided');
        }

        // Retrieve attachment ID from the file URL
        $attachment_id = attachment_url_to_postid($file);

        if (!$attachment_id) {
            wp_send_json_error('Invalid file URL');
        }

        // Get the file path and mime type
        $attachment_path = get_attached_file($attachment_id);
        $attachment_type = mime_content_type($attachment_path);
        $attachment_size = filesize($attachment_path);

        // Get file name
        $attachment_name = basename($attachment_path);

        // Read the file content
        $file_content = file_get_contents($attachment_path);

        // Create the file object
        $file_object = [
            'name' => $attachment_name,
            'type' => $attachment_type,
            'content' => base64_encode($file_content), // Encode content to base64
            'size' => $attachment_size
        ];

        // Send the file object as a JSON response
        wp_send_json_success($file_object);
    }

    public function clean_up_temp_files()
    {
        $temp_files = get_option('brf_temp_files', array());

        // Get the current time once to avoid repeated calls
        $current_time = time();

        // Use array_filter to remove old files
        $temp_files = array_filter($temp_files, function ($file) use ($current_time) {
            // 5 hours
            $time = 18000;

            if (file_exists($file) && filemtime($file) < $current_time - $time) {
                // Try to unlink and check for success
                if (@unlink($file)) {
                    return false; // Remove this file from the array
                } else {
                    // Handle error (e.g., log it)
                    error_log("Failed to delete temp file: $file");
                }
            }
            return true; // Keep this file in the array
        });

        update_option('brf_temp_files', $temp_files);
    }

    /**
     * Get Global Classes Backups
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function create_global_classes_backup()
    {
        // Include GlobalClasses.php
        include_once(BRICKSFORGE_PATH . '/includes/global-classes/GlobalClasses.php');

        $global_classes = new \Bricksforge\GlobalClasses();

        $new_backup = $global_classes->create_global_classes_backup();

        return $new_backup;
    }

    public function restore_global_classes_backup($request)
    {

        // Get request param
        $params = $request->get_params();

        $backup_id = $params['id'];

        if (!$backup_id) {
            return false;
        }

        // Include GlobalClasses.php
        include_once(BRICKSFORGE_PATH . '/includes/global-classes/GlobalClasses.php');

        $global_classes = new \Bricksforge\GlobalClasses();

        $result = $global_classes->restore_global_classes(json_decode($backup_id));

        return $result;
    }

    public function get_submissions($request)
    {
        $page = 1;
        $per_page = 9999999;
        $search = '';
        $order_by = 'id';
        $order = 'DESC';
        $form_id = $request->get_param('formId') ? $request->get_param('formId') : null;

        global $wpdb;
        $table_name = $wpdb->prefix . BRICKSFORGE_SUBMISSIONS_DB_TABLE;

        // Calculate offset based on current page and items per page
        $offset = ($page - 1) * $per_page;

        // Build the SQL query. Join with wp_posts to get the post title
        $sql = "SELECT s.*, p.post_title FROM $table_name s LEFT JOIN {$wpdb->posts} p ON s.post_id = p.ID";

        // Add form_id filter if provided
        if (!is_null($form_id)) {
            $sql .= " WHERE s.form_id = %s";
        }

        // Add search query if provided
        if (!empty($search)) {
            $sql .= " AND s.fields LIKE '%" . $wpdb->esc_like($search) . "%'";
        }

        // Add sorting criteria
        $sql .= " ORDER BY s.$order_by $order";

        // Add limit and offset
        $sql .= " LIMIT $per_page OFFSET $offset";

        // Prepare the SQL statement
        $prepared = $wpdb->prepare($sql, $form_id);

        // Execute the query
        $results = $wpdb->get_results($prepared);

        return $results;
    }

    public function delete_submissions($request)
    {
        $request = $request->get_body();

        $request = json_decode($request);

        if (!isset($request->submissions) || empty($request->submissions)) {
            return false;
        }

        // Array of submissions
        $submissions = $request->submissions;

        if (!is_array($submissions)) {
            return false;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . BRICKSFORGE_SUBMISSIONS_DB_TABLE;

        // Sanitize and prepare the array of IDs for the query
        $ids = array_map('intval', $submissions);
        $ids = array_filter($ids);
        $ids = implode(',', $ids);

        if (empty($ids)) {
            return false;
        }

        $sql = $wpdb->prepare("DELETE FROM $table_name WHERE id IN ($ids)");

        $wpdb->query($sql);

        return true;
    }

    public function delete_submissions_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . BRICKSFORGE_SUBMISSIONS_DB_TABLE;

        // Delete all data in the table
        $wpdb->query("TRUNCATE TABLE `$table_name`");

        // Drop the table
        $wpdb->query("DROP TABLE IF EXISTS `$table_name`");

        if (get_option('brf_submissions_display_settings')) {
            delete_option('brf_submissions_display_settings');
        }

        return true;
    }

    public function get_form_title_by_id($request)
    {
        $form_settings = \Bricks\Helpers::get_element_settings($request->get_param('postId'), $request->get_param('formId'));

        if (!isset($form_settings) || empty($form_settings)) {
            return false;
        }

        if (isset($form_settings['submission_form_title']) && !empty($form_settings['submission_form_title'])) {
            return $form_settings['submission_form_title'];
        }

        return false;
    }

    /**
     * AI
     * @return void
     * @since 0.9.9
     */
    public function ai_task($request)
    {
        $body = $request->get_body();
        $body = json_decode($body);
        $prompt = $body->prompt;

        if (!$prompt) {
            return false;
        }

        $ai = new \Bricksforge\AI();
        $result = $ai->run($prompt);

        return $result;
    }

    /**
     * Render CSS files for the global classes
     */
    public function render_css_files($categories)
    {
        clearstatcache();

        // Create file structure if not exists
        if (!file_exists(BRICKSFORGE_CUSTOM_STYLES_FILE)) {
            if (!is_dir(BRICKSFORGE_CUSTOM_STYLES_DIR)) {
                mkdir(BRICKSFORGE_CUSTOM_STYLES_DIR, 0755, true);
            }

            touch(BRICKSFORGE_CUSTOM_STYLES_FILE);
        }

        if (!$categories || empty($categories)) {
            return false;
        }

        // Reset the file
        file_put_contents(BRICKSFORGE_CUSTOM_STYLES_FILE, ' ');

        $css_content = file_get_contents(BRICKSFORGE_CUSTOM_STYLES_FILE);

        $pattern = '/(?:[\.]{1})([a-zA-Z_]+[\w_]*)(?:[\s\.\,\{\>#\:]{0})/im';

        foreach ($categories as $category) {

            if (isset($category->active) && $category->active == false) {
                continue;
            }

            $prefix = $category->prefix;
            if (is_null($prefix)) {
                $css_content .= PHP_EOL . $category->code;
            } else {
                $category->code = preg_replace($pattern, '.' . $prefix . '-${1}', $category->code);
                $css_content .= PHP_EOL . $category->code;
            }
        }

        $result = file_put_contents(BRICKSFORGE_CUSTOM_STYLES_FILE, $css_content);

        return $result;
    }

    public function sanitize_value($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $sub_value) {
                $value[$key] = sanitize_value($sub_value);
            }
        } elseif (is_numeric($value)) {
            $value = preg_replace('/[^0-9]/', '', $value);
        } else {
            $value = sanitize_text_field($value);
        }
        return $value;
    }

    public function render_dynamic_data($request)
    {
        $value = $request->get_param('_value');
        $post_id = $request->get_param('_post_id');

        if (empty($value)) {
            return false;
        }

        if (empty($post_id)) {
            $post_id = null;
        }

        return bricks_render_dynamic_data($value, $post_id);
    }
}
