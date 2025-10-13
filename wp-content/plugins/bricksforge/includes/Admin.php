<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin Pages Handler
 */
class Admin
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'admin_menu']);
        add_filter('plugin_action_links_bricksforge/plugin.php', [$this, 'add_plugin_links']);
        wp_enqueue_style('bricksforge-style');
        $this->render_conditionals();
    }

    public function render_conditionals()
    {

        if (get_option('brf_activated_tools') && in_array(0, get_option('brf_activated_tools'))) {
            wp_enqueue_script('bricksforge-font-uploader');
            add_filter('upload_mimes', [$this, 'upload_mimes']);
        }
    }

    /**
     * Register our menu page
     *
     * @return void
     */
    public function admin_menu()
    {
        global $submenu;

        $capability = 'manage_options';
        $slug = 'bricksforge';

        $whitelabel = get_option('brf_whitelabel');
        $whitelabel = $whitelabel != false && is_array($whitelabel) ? $whitelabel[0] : false;
        $menu_title = isset($whitelabel->menuTitle) && $whitelabel->menuTitle ? $whitelabel->menuTitle : 'Bricksforge';

        $hook = add_submenu_page('bricks', __($menu_title, 'bricksforge'), __($menu_title, 'bricksforge'), $capability, $slug, [$this, 'plugin_page']);

        // Bricksforge Admin Page
        add_action('load-' . $hook, [$this, 'init_hooks']);

        // Submissions if activated
        if (get_option('brf_activated_tools') && in_array(11, get_option('brf_activated_tools'))) {
            add_action('load-bricks_page_brf-form-submissions', [$this, 'init_hooks']);
            add_action('load-toplevel_page_brf-form-submissions', [$this, 'init_hooks']);
        }
    }

    /**
     * Add Plugin Links
     *
     *  @return void
     */
    public function add_plugin_links($links)
    {
        $links = array_merge(
            array(
                '<a style="color: #555; font-weight: 600" href="' . esc_url(admin_url('/admin.php?page=bricksforge')) . '">' . __('Settings', 'bricksforge') . '</a>',
                '<a style="color: #555; font-weight: 600" href="https://forum.bricksforge.io" target="_blank">' . __('Forum', 'bricksforge') . '</a>',
                '<a style="color: #555; font-weight: 600" href="https://bricksforge.io/documentation" target="_blank">' . __('Documentation', 'bricksforge') . '</a>'
            ),
            $links
        );

        return $links;
    }

    /**
     * Initialize our hooks for the admin page
     *
     * @return void
     */
    public function init_hooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Load scripts and styles for the app
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        $lk = get_option('brf_settings');
        if ($lk && class_exists('\Bricksforge\Api\Utils')) {
            $utils = new \Bricksforge\Api\Utils();
            $lk = $utils->encrypt($lk);
        }

        global $wp_roles;

        $args = array(
            'siteurl'                   => get_option('siteurl'),
            'ajaxurl'                   => admin_url('admin-ajax.php'),
            'nonce'                     => wp_create_nonce('wp_rest'),
            'pluginurl'                 => BRICKSFORGE_URL,
            'pluginVersion'             => BRICKSFORGE_VERSION,
            'apiurl'                    => get_rest_url() . "bricksforge/v1/",
            'bricksElements'            => \Bricks\Elements::$elements,
            'globalClasses'             => get_option('bricks_global_classes'),
            'globalClassesLocked'       => get_option('bricks_global_classes_locked'),
            'brfGlobalClassesActivated' => get_option('brf_global_classes_activated'),
            'brfActivatedTools'         => get_option('brf_activated_tools'),
            'brfActivatedElements'      => get_option('brf_activated_elements'),
            'breakpoints'               => \Bricks\Breakpoints::$breakpoints,
            'pages'                    => query_posts(["post_type" => ["page", "bricks_template"], 'posts_per_page' => 200]),
            'ptPage'                    => query_posts(["post_type" => "page", 'posts_per_page' => -1]),
            'templates'                 => get_posts(['post_type' => 'bricks_template', 'post_status' => 'publish', 'numberposts' => -1, 'posts_per_page' => -1]),
            'popups'                    => get_option('brf_popups'),
            'permissions'               => get_option('brf_permissions_roles'),
            'maintenance'               => get_option('brf_maintenance'),
            'whiteLabel'                => get_option('brf_whitelabel'),
            'panelActivated'            => get_option('brf_activated_tools') && in_array(6, get_option('brf_activated_tools')),
            'settings'                  => $lk,
            'adminEmail'               => get_option('admin_email'),
            'isWoocommerceActive'       => class_exists('WooCommerce'),
            'terminalCommands' => get_option('brf_terminal_commands') ? get_option('brf_terminal_commands') : [],
            'locale'                    => get_locale(),
            'userRoles'                 => $wp_roles->roles,
        );

        wp_enqueue_style('bricksforge-admin');
        wp_localize_script('bricksforge-admin', 'BRFVARS', $args);

        wp_enqueue_script('bricksforge-admin');
    }

    /**
     * Render our admin page
     *
     * @return void
     */
    public function plugin_page()
    {
        echo '<div class="wrap"><div id="bricksforge-admin-app"></div></div>';
    }

    public function upload_mimes($mime_types)
    {
        $font_mime_types = [
            // 'eot'   => 'font/eot', // <IE9 only (if specified, it must be listed first)
            'woff2' => 'font/woff2',
            'woff'  => 'font/woff',
            'ttf'   => 'font/ttf',
        ];

        if (\Bricks\Capabilities::current_user_can_use_builder()) {
            foreach ($font_mime_types as $type => $mime) {
                if (!isset($mime_types[$type])) {
                    $mime_types[$type] = $mime;
                }
            }
        }

        return $mime_types;
    }
}
