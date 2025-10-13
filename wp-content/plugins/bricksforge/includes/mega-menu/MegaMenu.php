<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Mega Menu Handler
 */
class MegaMenu
{

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->activated() == true) {
            $this->customize_admin_menu();
            $this->customize_bricks_builder();
            add_action('wp_enqueue_scripts', array($this, 'load_assets'), 10);
            add_action('wp_ajax_wpm_megamenu_get_settings', array($this, 'megamenu_get_settings'), 10);
            add_action('wp_ajax_nopriv_wpm_megamenu_get_settings', array($this, 'megamenu_get_settings'), 10);
        }
    }

    public function activated()
    {
        return get_option('brf_activated_tools') && in_array(3, get_option('brf_activated_tools'));
    }

    public function load_assets()
    {
        wp_enqueue_script('bricksforge-elements');

        $output_array = array();
        $header_data = \Bricks\Database::get_template_data('header');

        if (!isset($header_data) || $header_data == false) {
            return;
        }

        // @since 0.9.6
        $filtered = array_filter($header_data, function ($item) {
            return $item['name'] === 'nav-menu' && isset($item['settings']['activateMegaMenu']);
        });

        if (empty($filtered)) {
            return;
        }

        $menu_data = reset($filtered)["settings"];

        if ($menu_data) {
            $options = $menu_data;

            $menuID = isset($options['menu']) ? $options['menu'] : false;

            if ($menuID === false) {
                return;
            }

            $navigation = wp_get_nav_menu_items($menuID);

            if ($navigation) {
                foreach ($navigation as $navItem) {

                    if ($navItem->wpm_megamenu && $navItem->wpm_megamenu != "none") {
                        $output = "<div data-nav-item='menu-item-" . $navItem->ID . "' class='wpm-mega-menu'>";
                        $output .= do_shortcode($navItem->wpm_megamenu);
                        $output .= "</div>";

                        array_push($output_array, $output);
                    }
                }
            }
        }

        $params = array(
            'nonce'             => wp_create_nonce('wp_rest'),
            'apiurl'            => get_rest_url() . "bricksforge/v1/",
            'data'              => $output_array,
            'headerData'        => \Bricks\Database::get_template_data('header'),
            'megaMenuActivated' => get_option('brf_activated_tools') && in_array(3, get_option('brf_activated_tools'))
        );

        wp_localize_script('bricksforge-elements', 'MegaMenuSettings', $params);
    }

    public function wpm_custom_nav_edit_walker($walker, $menu_id)
    {
        require_once 'mega-menu-walker.php';

        return 'Brf_Walker_Nav_Menu';
    }

    public function wpm_custom_nav_update($menu_id, $menu_item_db_id, $args)
    {
        if (is_array($_REQUEST['menu-item-bricks-template'])) {
            $custom_value = $_REQUEST['menu-item-bricks-template'][$menu_item_db_id];
            update_post_meta($menu_item_db_id, '_menu_item_bricks_template', $custom_value);
        }
    }


    public function wpm_bricks_template_nav_item($menu_item)
    {
        $menu_item->wpm_megamenu = get_post_meta($menu_item->ID, '_menu_item_bricks_template', true);
        return $menu_item;
    }

    private function customize_admin_menu()
    {
        add_filter('wp_edit_nav_menu_walker', [$this, 'wpm_custom_nav_edit_walker'], 10, 2);

        // Saves new field navmenu
        add_action('wp_update_nav_menu_item', [$this, 'wpm_custom_nav_update'], 10, 3);


        // Adds value of new field to Navmenu
        add_filter('wp_setup_nav_menu_item', [$this, 'wpm_bricks_template_nav_item']);
    }

    public function megamenu_get_settings()
    {
        if (get_option("wpm_megamenu_settings")) {
            echo json_encode(get_option("wpm_megamenu_settings", true));
        }

        die;
    }

    private function customize_bricks_builder()
    {
        add_filter('bricks/elements/nav-menu/control_groups', function ($control_groups) {
            $control_groups['wpmMegaMenu'] = [
                'tab'   => 'content',
                'title' => esc_html__('Mega Menu', 'textdomain'),
            ];

            return $control_groups;
        });

        /**
         * Add Controls for the new group
         */

        add_filter('bricks/elements/nav-menu/controls', function ($controls) {
            $controls['typeInfo'] = [
                'tab'     => 'content',
                'group'   => 'wpmMegaMenu',
                'content' => esc_html__('Important: Your Nav Menu should have a Container as parent.', 'bricks'),
                'type'    => 'info',
            ];
            $controls['activateMegaMenu'] = [
                'tab'   => 'content',
                'group' => 'wpmMegaMenu',
                'label' => esc_html__('Activate Mega Menu', 'textdomain'),
                'type'  => 'checkbox'
            ];
            $controls['activateMegaMenu'] = [
                'tab'     => 'content',
                'group'   => 'wpmMegaMenu',
                'label'   => esc_html__('Activate Mega Menu', 'bricks'),
                'type'    => 'checkbox',
                'default' => true
            ];
            $controls['megaMenuTrigger'] = [
                'tab'         => 'content',
                'group'       => 'wpmMegaMenu',
                'label'       => esc_html__('Trigger', 'bricks'),
                'type'        => 'select',
                'options'     => [
                    'click' => esc_html__('Click', 'bricks'),
                    'hover' => esc_html__('Hover', 'bricks'),
                ],
                'inline'      => true,
                'placeholder' => esc_html__('Trigger', 'bricks'),
                'default'     => 'hover',
            ];

            $controls['megaMenuFullWidth'] = [
                'tab'     => 'content',
                'group'   => 'wpmMegaMenu',
                'label'   => esc_html__('Full Width', 'bricks'),
                'type'    => 'checkbox',
                'default' => true
            ];

            $controls['megaMenuTopSpacing'] = [
                'tab'     => 'content',
                'group'   => 'wpmMegaMenu',
                'label'   => esc_html__('Top Spacing in Pixel (Without Units)', 'bricks'),
                'type'    => 'number',
                'min'     => 0,
                'step'    => 1,
                'units'   => false,
                'inline'  => true,
                'default' => 0,
            ];

            $controls['megaMenuCloseEvent'] = [
                'tab'     => 'content',
                'group'   => 'wpmMegaMenu',
                'label'   => esc_html__('Close Event', 'bricks'),
                'type'    => 'select',
                'options' => [
                    "click" => 'Close On Click',
                    'hover' => 'Close On Hover'
                ],
                'default' => 'click'
            ];

            $controls['megaMenuCloseSelector'] = [
                'tab'     => 'content',
                'group'   => 'wpmMegaMenu',
                'label'   => esc_html__('Closing Selector', 'bricks'),
                'type'    => 'text',
                'default' => 'main',
                'description' => esc_html__('The selector for an element that triggers the closure of the mega menu. Example: main', 'bricks')
            ];

            $controls['megaMenuCloseWhenLeavingNavItem'] = [
                'tab'     => 'content',
                'group'   => 'wpmMegaMenu',
                'label'   => esc_html__('Close when leaving the nav item or the mega menu', 'bricks'),
                'type'    => 'checkbox',
                'default' => false
            ];

            $controls['megaMenuAnimationDuration'] = [
                'tab'     => 'content',
                'group'   => 'wpmMegaMenu',
                'label'   => esc_html__('Animation Duration in Milliseconds (Without Units)', 'bricks'),
                'type'    => 'number',
                'min'     => 0,
                'step'    => 1,
                'units'   => false,
                'inline'  => true,
                'default' => 150,
            ];

            return $controls;
        });
    }
}
