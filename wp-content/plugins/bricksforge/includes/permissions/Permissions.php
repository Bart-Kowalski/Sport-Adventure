<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Permissions Handler
 */
class Permissions
{

    public function __construct()
    {
        add_filter('bricks/builder/elements', [$this, 'render_elements_by_role']);
        add_action('wp_ajax_create_user_role', [$this, 'create_user_role']);
        add_action('wp_head', [$this, 'hide_tab_area']);
    }

    /**
     * Render Elements By Role
     */
    public function render_elements_by_role($elements)
    {

        if (!is_user_logged_in() || bricks_is_frontend()) {
            return $elements;
        }

        $user = wp_get_current_user();
        $roles = get_option('brf_permissions_roles');
        $rendered_elements = $elements;
        $hide_only = false;

        if (!isset($roles) || empty($roles)) {
            return $elements;
        }

        $deactivated_elements = [];

        foreach ($roles as $role) {
            if (in_array($role->value, $user->roles)) {

                // If $role->permissions->active is not set or false, return $elements
                if (!isset($role->permissions->active) || $role->permissions->active === false) {
                    return $elements;
                }

                if (isset($role->permissions->hideOnly) && $role->permissions->hideOnly == true) {
                    $hide_only = true;
                }

                // Only add elements to $deactivated_elements if they are not active
                $deactivated_elements = array_filter($role->permissions->elements, function ($e) {
                    return $e->active === false;
                });

                // Convert $deactivated_elements to an array of element names
                $deactivated_elements = array_map(function ($e) {
                    return $e->name;
                }, $deactivated_elements);
            }
        };

        if ($hide_only === true) {
            add_action('wp_head', [$this, 'hide_elements']);
            return $elements;
        }

        // Filter $elements to exclude elements that are in $deactivated_elements
        $rendered_elements = array_filter($elements, function ($elem) use ($deactivated_elements) {
            return !in_array($elem, $deactivated_elements);
        });

        return $rendered_elements ? $rendered_elements : $elements;
    }

    /**
     * Create User Role
     */
    public function create_user_role()
    {

        $role = $_REQUEST['role'];

        $role = json_decode(stripslashes($role));

        if (!isset($role) || !$role) {
            die;
        }

        $cap_array = (array) $role->capabilities;

        add_role($role->value, $role->label, $cap_array);

        echo true;

        die;
    }

    /**
     * Only hide the elements
     */
    public function hide_elements()
    {
        $user = wp_get_current_user();
        $roles = get_option('brf_permissions_roles');
        $rendered_elements = array();

        if (!isset($roles) || empty($roles)) {
            return;
        }

        foreach ($roles as $role) {
            if (in_array($role->value, $user->roles)) {
                $role->permissions->elements = array_filter($role->permissions->elements, function ($e) {
                    return $e->active === true;
                });

                $rendered_elements = array_map(function ($element) {
                    return $element->name;
                }, $role->permissions->elements);
            }
        };

        echo "<style>";
        echo "#bricks-panel-elements-categories .category-layout .sortable-wrapper > li {display: none!important}";
        echo "#bricks-panel-elements-categories .category-basic .sortable-wrapper > li {display: none!important}";
        echo "#bricks-panel-elements-categories .category-general .sortable-wrapper > li {display: none!important}";
        echo "#bricks-panel-elements-categories .category-media .sortable-wrapper > li {display: none!important}";
        echo "#bricks-panel-elements-categories .category-wordpress .sortable-wrapper > li {display: none!important}";
        echo "#bricks-panel-elements-categories .category-single .sortable-wrapper > li {display: none!important}";

        foreach ($rendered_elements as $element) {
            echo "#bricks-panel-elements-categories .category-layout li[data-element-name=" . $element . "] {display: block!important}";
            echo "#bricks-panel-elements-categories .category-basic li[data-element-name=" . $element . "] {display: block!important}";
            echo "#bricks-panel-elements-categories .category-general li[data-element-name=" . $element . "] {display: block!important}";
            echo "#bricks-panel-elements-categories .category-media li[data-element-name=" . $element . "] {display: block!important}";
            echo "#bricks-panel-elements-categories .category-wordpress li[data-element-name=" . $element . "] {display: block!important}";
            echo "#bricks-panel-elements-categories .category-single li[data-element-name=" . $element . "] {display: block!important}";
        }
        echo "</style>";
    }

    /**
     * Hide Tab Area
     */
    public function hide_tab_area()
    {
        if (bricks_is_builder()) {
            $role = $this->get_user_role();

            if (!isset($role) || $role == false) {
                return;
            }

            if ($role->permissions->hideTabs === true) {
?>
                <style>
                    #bricks-panel-sticky {
                        display: none;
                    }
                </style>
            <?php
            }
            if ($role->permissions->hideStyleTab === true) {
            ?>
                <style>
                    #bricks-panel-tabs {
                        display: none;
                    }
                </style>
            <?php
            }
            if ($role->permissions->cleanUpToolbar === true) {
            ?>
                <style>
                    #bricks-toolbar li:not(.logo, [data-balloon="Edit in WordPress"], .new-tab, .preview, .save, .elements) {
                        display: none;
                    }
                </style>
            <?php
            }
            if (isset($role->permissions->removeBricksforgePanel) && $role->permissions->removeBricksforgePanel === true) {
            ?>
                <style>
                    #bricks-toolbar li.brf-panel {
                        display: none;
                    }
                </style>
            <?php
            }
            if (isset($role->permissions->removeBricksforgeTerminal) && $role->permissions->removeBricksforgeTerminal === true) {
            ?>
                <style>
                    #bricks-toolbar li.brf-terminal {
                        display: none;
                    }
                </style>
            <?php
            }
            if ($role->permissions->hasCustomBuilderColor === true) {
            ?>
                <style>
                    :root {
                        --builder-bg: <?php echo $role->permissions->builderColorPrimary ?>;
                        --builder-bg-2: <?php echo $role->permissions->builderColorSecondary ?>;
                        --builder-bg-3: <?php echo $role->permissions->builderColorSecondary ?>;
                        --builder-color-accent: <?php echo $role->permissions->builderColorAccent ?>;
                        --builder-color: <?php echo $role->permissions->builderColorText ?>;
                    }

                    #bricks-toolbar .logo {
                        background-color: <?php echo $role->permissions->builderColorAccent ?>;
                    }

                    [data-control=select] li.hover,
                    [data-control=select] li.selected,
                    [data-control=select] li:hover {
                        color: <?php echo $role->permissions->builderColorText ?>;
                    }

                    [data-control=select] li.hover:after,
                    [data-control=select] li.selected:after,
                    [data-control=select] li:hover:after {
                        background-color: <?php echo $role->permissions->builderColorPrimary ?>;
                    }
                </style>
<?php
            }
        }
    }

    /**
     * Check for user role
     */
    public function get_user_role()
    {
        $user = wp_get_current_user();
        $roles = get_option('brf_permissions_roles');
        $user_role = null;

        if (!isset($roles)) {
            return false;
        }

        if ($roles && count($roles) > 0) {
            foreach ($roles as $role) {
                if (in_array($role->value, $user->roles)) {
                    $user_role = $role;
                }
            };
        }

        if (isset($user_role)) {
            return $user_role;
        }
    }
}
