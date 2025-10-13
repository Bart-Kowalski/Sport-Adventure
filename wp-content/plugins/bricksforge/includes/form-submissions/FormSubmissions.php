<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Form Submissions Handler
 */
class FormSubmissions
{

    /**
     * Menu Location
     */ private $menu_location = 'toplevel';

    /**
     * Menu Name
     */ private $menu_name = 'Submissions';

    /**
     * Menu Position
     */ private $menu_position = 11;

    /**
     * Menu Permissions
     */ private $menu_permissions = ['administrator'];

    public function __construct()
    {
        $this->init();
    }

    /**
     * Initialize the tool
     */
    public function init()
    {
        if ($this->activated() === true) {
            $this->create_database_table();
            add_action('admin_menu', [$this, 'add_menu']);
            add_action('admin_menu', [$this, 'add_notification_badge'], 100);
        }
    }

    /**
     * Check if the tool is activated
     */
    public function activated()
    {
        return get_option('brf_activated_tools') && in_array(11, get_option('brf_activated_tools'));
    }

    public function create_database_table()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . BRICKSFORGE_SUBMISSIONS_DB_TABLE;

        // Check if the table already exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            return;
        }

        // Define the table structure
        $table_schema = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            post_id int(11) NOT NULL,
            form_id TEXT DEFAULT NULL,
            timestamp datetime NOT NULL,
            fields TEXT DEFAULT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;";

        // Create the table
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($table_schema);

        // Sanitize the table
        $this->sanitize_submission_database_table();
    }

    /**
     * Sanitize the database table
     */
    public function sanitize_submission_database_table()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . BRICKSFORGE_SUBMISSIONS_DB_TABLE;

        $rows = $wpdb->get_results("SELECT * FROM $table_name");

        foreach ($rows as $row) {
            $fields = json_decode($row->fields, true);
            $fields = array_map('sanitize_text_field', $fields);

            if (!is_array($fields)) {
                continue;
            }

            foreach ($fields as $key => $value) {
                if (!is_string($value)) {
                    $fields[$key]['value'] = '';
                } else {
                    $fields[$key]['value'] = sanitize_text_field($value);
                }
            }

            $wpdb->update(
                $table_name,
                array('fields' => json_encode($fields)),
                array('id' => $row->id),
                array('%s'),
                array('%d')
            );
        }
    }

    /**
     * Add the submenu to the Bricks menu
     */
    public function add_menu()
    {
        if (get_option('brf_tool_settings') || !in_array(10, get_option('brf_activated_tools'))) {
            // Get get_option('brf_tool_settings') (object) with the key "id" equal to 10
            $settings = array_filter(get_option('brf_tool_settings'), function ($tool) {
                return $tool->id == 11;
            });

            if (count($settings) > 0) {
                // Get the first item of the array
                $settings = array_shift($settings);

                // Get the menu location
                if (isset($settings->settings->location) && !empty($settings->settings->location)) {
                    $this->menu_location = $settings->settings->location;
                }

                // Get the menu name
                if (isset($settings->settings->menuName) && !empty($settings->settings->menuName)) {
                    $this->menu_name = $settings->settings->menuName;
                }

                // Get the menu position
                if (isset($settings->settings->menuPosition) && !empty($settings->settings->menuPosition)) {
                    $this->menu_position = $settings->settings->menuPosition;
                }

                // Get the menu permissions
                if (isset($settings->settings->menuPermissions) && !empty($settings->settings->menuPermissions)) {
                    $this->menu_permissions = $settings->settings->menuPermissions;
                } else {
                    $this->menu_permissions = ['administrator'];
                }
            }
        }

        $allowed_roles = [];

        if (isset($this->menu_permissions) && !empty($this->menu_permissions)) {
            $allowed_roles = array_intersect($this->menu_permissions, array_keys(get_editable_roles()));
        } else {
            $allowed_roles = ['administrator'];
        }

        global $current_user;
        $current_user_role = $current_user->roles[0];

        if (!in_array($current_user_role, $allowed_roles)) {
            return;
        }

        add_menu_page(
            // Page title
            __($this->menu_name, 'bricksforge'),
            // Menu title
            __($this->menu_name, 'bricksforge'),
            // Capability
            'edit_posts',
            // Menu slug
            'brf-form-submissions',
            // Callback
            [$this, 'bricks_render_submenu'],
            // Icon
            'dashicons-email-alt',
            // Position
            $this->menu_position
        );
    }

    public function bricks_render_submenu()
    {
        echo '<div id="brf-form-submissions-app"></div>';
    }

    /**
     * Add a notification badge to the admin menu item.
     *
     * This method modifies the global $menu array to include a badge
     * for unread submissions.
     */
    public function add_notification_badge()
    {
        // Get the unread submissions count from the option.
        $unread_submissions = get_option('brf_unread_submissions', array());
        $count = (is_array($unread_submissions)) ? count($unread_submissions) : 0;

        if ($count === 0) {
            return;
        }

        global $menu;
        // Loop through the menu to find our custom page.
        foreach ($menu as $key => $menu_item) {
            // The menu slug is stored at index 2.
            if (isset($menu_item[2]) && $menu_item[2] === 'brf-form-submissions') {
                // Append the notification badge HTML.
                $menu[$key][0] .= sprintf(
                    ' <span class="update-plugins count-%1$d"><span class="plugin-count">%1$d</span></span>',
                    esc_html($count)
                );
                break;
            }
        }
    }
}
