<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WhiteLabel Handler
 */
class WhiteLabel
{

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->activated() === true) {
            add_action('admin_enqueue_scripts', [$this, 'load_wp_media_files'], 11);
            $this->apply_white_label();
        }
    }

    public function activated()
    {
        return true;
    }

    public function load_wp_media_files()
    {
        wp_enqueue_media();
    }

    public function apply_white_label()
    {
        $settings = get_option('brf_whitelabel');

        // Get current backend page
        $current_page = admin_url(sprintf('admin.php?%s', http_build_query($_GET)));

        // If not contains page=bricksforge, we don't apply the white label
        if (strpos($current_page, 'page=bricksforge') === false) {
            return;
        }

        if (!$settings || !is_array($settings) || !isset($settings[0]) || !is_object($settings[0]) || !isset($settings[0]->themeOverrides->common->primaryColor) || $settings[0]->themeOverrides->common->primaryColor == '#ffda46') {
            return;
        }

        $settings = $settings[0];

        $color = $settings->themeOverrides->common->primaryColor;

        if (!$color) {
            return;
        }

        echo "<style>
           body.bricks_page_bricksforge * {
                --p-button-text-primary-color: {$color};
                --p-toggleswitch-checked-background: {$color};
                --p-toggleswitch-focus-ring-color: {$color};
                --p-toggleswitch-handle-checked-hover-color: {$color};
                --p-toggleswitch-handle-checked-color: {$color};
                --p-toggleswitch-checked-hover-background: {$color};
                --p-button-primary-background: {$color};
                --p-button-primary-hover-background: {$color};
                --p-button-primary-active-background: {$color};
                --p-button-primary-focus-ring-color: {$color};
                --p-button-primary-border-color: {$color};
                --p-inputtext-focus-border-color: {$color};
                --brfPrimaryColor: {$color};
                --p-bricksforge-400: {$color};
                --p-bricksforge-600: {$color};
            }
        </style>";
    }
}
