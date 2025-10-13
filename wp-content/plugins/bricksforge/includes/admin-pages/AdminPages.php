<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Global Classes Handler
 */
class AdminPages
{
    private $instances = [];

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->activated() === true) {
            $this->prepare();

            if (is_admin()) {
                $this->instances = get_option('brf_admin_pages') ? get_option('brf_admin_pages') : [];
                add_action('admin_menu', [$this, 'create_admin_pages']);
                add_action('admin_head', [$this, 'enqueue_scripts']);
            }
        }
    }

    public function enqueue_scripts($hook_suffix)
    {
        wp_enqueue_style('bricksforge-admin-pages', BRICKSFORGE_ASSETS . '/css/backend-designer/admin-pages.css');
    }

    public function prepare()
    {
        $contains_brf_ap = isset($_GET['page']) && strpos($_GET['page'], 'brf-ap-') !== false;

        if (is_user_logged_in() && ((isset($_GET['backend']) && $_GET['backend'] == 'true') || $contains_brf_ap)) {
            add_filter('body_class', function ($classes) {
                $classes[] = 'brf-backend-view brf-backend-view__admin-page';
                return $classes;
            });

            add_filter('show_admin_bar', function ($show) {
                return false;
            });
        }
    }

    public function create_admin_pages()
    {
        // For each $this->instances (which contains id, name, roles and template), we want to create a wordpress Admin Page
        foreach ($this->instances as $instance) {
            if (!$this->is_allowed($instance)) {
                continue;
            }

            $this->create_admin_page($instance);
        }
    }

    public function is_allowed($instance)
    {
        if (!isset($instance->template) || empty($instance->template)) {
            return false;
        }

        $allowed_roles = $instance->role;
        $current_user = wp_get_current_user();
        $current_user_roles = $current_user->roles;

        if (!isset($allowed_roles) || empty($allowed_roles) || !is_array($allowed_roles)) {
            return true;
        }

        foreach ($allowed_roles as $role) {
            // We remove spaces and make it lowercase
            $role = strtolower(str_replace(' ', '', $role));

            if (in_array($role, $current_user_roles)) {
                return true;
            }
        }

        return false;
    }

    public function create_admin_page($instance)
    {
        $slug = $this->generate_slug($instance->name);
        $type = $instance->type;

        if ($type == "subMenuPage") {
            $parent = $instance->parent;

            if (!isset($parent)) {
                return;
            }

            // Create a wordpress admin submenu page
            add_submenu_page(
                $parent,
                $instance->name,
                $instance->name,
                'read',
                $slug,
                function () use ($instance) {
                    $this->render_admin_page($instance);
                }
            );
        } else {
            // Create a wordpress admin menu page
            add_menu_page(
                $instance->name,
                $instance->name,
                'read',
                $slug,
                function () use ($instance) {
                    $this->render_admin_page($instance);
                },
                $instance->icon,
                $instance->position
            );
        }
    }

    public function render_admin_page($instance)
    {
        if (!isset($instance->template)) {
            return;
        }

        $template = $instance->template;
        $template = intval($template);
        $link_behavior = isset($instance->linkBehavior) ? $instance->linkBehavior : 'parent';

        $url = add_query_arg('backend', 'true', get_permalink($template));

        echo '<iframe class="brf-admin-pages-iframe" src="' . $url . '" width="100%" height="100%"></iframe>';

        // Handle Link Behavior
        echo '
        <script>
        var iframe = document.querySelector("iframe.brf-admin-pages-iframe");

        function handleLinkClick(e) {
            var targetLink = e.target.tagName === "A" ? e.target : e.target.closest("a");
            if (targetLink) {
                e.preventDefault();
                const url = new URL(targetLink.href);
                if ("' . $link_behavior . '" == "parent") {
                    window.open(url.href, "_parent");
                } else {
                    url.searchParams.append("backend", "true");
                    iframe.contentWindow.location = url.toString();
                }
            }
        }

        iframe.contentWindow.addEventListener("click", handleLinkClick);

        if ("' . $link_behavior . '" != "parent") {
            iframe.addEventListener("load", function () {
                iframe.contentWindow.document.body.addEventListener("click", handleLinkClick);
            });
        }
        </script>';
    }

    public function generate_slug($name)
    {
        // My New Page -> my-new-page
        $slug = "brf-ap-" . strtolower($name);
        $slug = str_replace(' ', '-', $slug);

        return $slug;
    }

    public function activated()
    {
        return get_option('brf_activated_tools') && in_array(17, get_option('brf_activated_tools'));
    }
}
