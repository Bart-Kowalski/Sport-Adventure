<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Maintenance Handler
 */
class Maintenance
{

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->activated() === true) {
            add_filter('template_redirect', [$this, 'configure']);
        }
    }

    public function activated()
    {

        if (!get_option('brf_activated_tools') || !in_array(4, get_option('brf_activated_tools')) || !get_option('brf_maintenance')) {
            return false;
        }

        $settings = get_option('brf_maintenance');
        if (!$settings[0] || $settings[0]->isActivated == false) {
            return false;
        }

        return true;
    }

    public function configure()
    {
        $settings = get_option('brf_maintenance');
        $settings = $settings[0];
        $excluded_roles = isset($settings->exclude) ? $settings->exclude : false;
        $status_code = isset($settings->statusCode) ? $settings->statusCode : 302;
        $allowed = true;

        if (!isset($settings->page)) {
            return;
        }

        $page = $settings->page;

        /**
         * Check for password
         * @since 0.9.3
         */
        if (isset($_COOKIE["brf-unlock-maintenance"])) {
            return;
        }

        if (isset($settings->unlockWithPassword) && $settings->unlockWithPassword && isset($settings->password) && $settings->password) {
            $found_password = isset($_GET['password']) && $_GET['password'] ? $_GET['password'] : false;

            if ($found_password) {
                if ($found_password === $settings->password) {
                    setcookie("brf-unlock-maintenance", true, strtotime('+365 days'), '/');
                    return;
                }
            }
        }

        $is_logged_in = is_user_logged_in();

        if (!$is_logged_in || $excluded_roles === false) {
            $this->redirect($page, $status_code);
            return;
        }

        $user = wp_get_current_user();
        foreach ($excluded_roles as $role) {
            if (in_array($role, $user->roles)) {
                $allowed = false;
            }
        }

        if ($allowed === true) {
            if (!is_page($settings->page)) {
                wp_redirect(get_page_link($settings->page), $status_code);
                exit();
            }
        }
    }

    public function redirect($page, $status_code)
    {
        if (!is_page($page)) {
            wp_redirect(get_page_link($page), $status_code);
            exit();
        }
    }
}
