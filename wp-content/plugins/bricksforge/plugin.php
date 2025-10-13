<?php

/*
Plugin Name: Bricksforge
Plugin URI: https://www.bricksforge.io
Description: A powerful set of tools to extend the Bricks Builder functionality.
Version: 3.0.1
Author: Bricksforge
Author URI: https://www.bricksforge.io
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: bricksforge
Domain Path: /languages
*/

/**
 * Copyright (c) 2025 Daniele De Rosa – Bricksforge. All rights reserved.
 */
/*
██████╗ ██████╗ ██╗ ██████╗██╗  ██╗███████╗███████╗ ██████╗ ██████╗  ██████╗ ███████╗
██╔══██╗██╔══██╗██║██╔════╝██║ ██╔╝██╔════╝██╔════╝██╔═══██╗██╔══██╗██╔════╝ ██╔════╝
██████╔╝██████╔╝██║██║     █████╔╝ ███████╗█████╗  ██║   ██║██████╔╝██║  ███╗█████╗  
██╔══██╗██╔══██╗██║██║     ██╔═██╗ ╚════██║██╔══╝  ██║   ██║██╔══██╗██║   ██║██╔══╝  
██████╔╝██║  ██║██║╚██████╗██║  ██╗███████║██║     ╚██████╔╝██║  ██║╚██████╔╝███████╗
╚═════╝ ╚═╝  ╚═╝╚═╝ ╚═════╝╚═╝  ╚═╝╚══════╝╚═╝      ╚═════╝ ╚═╝  ╚═╝ ╚═════╝ ╚══════╝
*/

// don't call the file directly
if (!defined('ABSPATH'))
    exit;

$theme = wp_get_theme();
if ('Bricks' != $theme->name && 'Bricks' != $theme->parent_theme) {
    return;
}

/**
 * Bricksforge class
 *
 * @class Bricksforge The class that holds the entire Bricksforge plugin
 */

if (!class_exists('Bricksforge')) {

    final class Bricksforge
    {

        /**
         * Plugin version
         *
         * @var string
         */
        public $version = '3.0.1';

        /**
         * Holds various class instances
         *
         * @var array
         */
        private $container = array();

        /**
         * Constructor for the Bricksforge class
         *
         * Sets up all the appropriate hooks and actions
         * within our plugin.
         */
        public function __construct()
        {
            $this->define_constants();
            $this->autoloader();

            register_activation_hook(__FILE__, array($this, 'activate'));
            register_deactivation_hook(__FILE__, array($this, 'deactivate'));

            add_action('plugins_loaded', array($this, 'init_plugin'));
        }

        public function autoloader()
        {
            spl_autoload_register(function ($class_name) {
                $namespace = 'Bricksforge\ProForms\Actions';
                if (strpos($class_name, $namespace) === 0) {
                    $class_name = str_replace($namespace . '\\', '', $class_name);
                    $file = __DIR__ . '/includes/elements/pro-forms/actions/' . strtolower($class_name) . '.php';
                    if (file_exists($file)) {
                        require_once $file;
                    }
                }
            });
        }

        /**
         * Initializes the Bricksforge() class
         *
         * Checks for an existing Bricksforge() instance
         * and if it doesn't find one, creates it.
         */
        public static function init()
        {
            static $instance = false;

            if (!$instance) {
                $instance = new Bricksforge();
            }

            return $instance;
        }

        /**
         * Magic getter to bypass referencing plugin.
         *
         * @param $prop
         *
         * @return mixed
         */
        public function __get($prop)
        {
            if (array_key_exists($prop, $this->container)) {
                return $this->container[$prop];
            }

            return $this->{$prop};
        }

        /**
         * Magic isset to bypass referencing plugin.
         *
         * @param $prop
         *
         * @return mixed
         */
        public function __isset($prop)
        {
            return isset($this->{$prop}) || isset($this->container[$prop]);
        }

        /**
         * Define the constants
         *
         * @return void
         */
        public function define_constants()
        {
            define('BRICKSFORGE_VERSION', $this->version);
            define('BRICKSFORGE_FILE', __FILE__);
            define('BRICKSFORGE_PATH', dirname(BRICKSFORGE_FILE));
            define('BRICKSFORGE_INCLUDES', BRICKSFORGE_PATH . '/includes');
            define('BRICKSFORGE_URL', plugins_url('', BRICKSFORGE_FILE));
            define('BRICKSFORGE_ASSETS', BRICKSFORGE_URL . '/assets');
            define('BRICKSFORGE_VENDOR', BRICKSFORGE_INCLUDES . '/vendor');
            define('BRICKSFORGE_ELEMENTS_ROOT_PATH', BRICKSFORGE_URL . '/includes/elements');
            define('BRICKSFORGE_BRICKS_ELEMENT_PREFIX', 'brxe-');
            define('BRICKSFORGE_SUBMISSIONS_DB_TABLE', 'bricksforge_submissions');
            define('BRICKSFORGE_UPLOADS_DIR', wp_upload_dir()['basedir'] . '/bricksforge/');
            define('BRICKSFORGE_CUSTOM_STYLES_DIR', BRICKSFORGE_UPLOADS_DIR . 'classes/');
            define('BRICKSFORGE_CUSTOM_STYLES_FILE', BRICKSFORGE_CUSTOM_STYLES_DIR . 'custom.css');
            define('BRICKSFORGE_CUSTOM_STYLES_URL', wp_upload_dir()['baseurl'] . '/bricksforge/classes/custom.css');
            define('BRICKSFORGE_SECRET_KEY', 'd8L7*s9T@u6X#a2M4&vH6$jS8$nK3%pG1');
            define('BRICKSFORGE_WC_CART_ITEM_KEY', 'brf_cart_item_key');
        }

        /**
         * Load the plugin after all plugis are loaded
         *
         * @return void
         */
        public function init_plugin()
        {
            $this->includes();
            $this->init_hooks();
        }

        /**
         * Placeholder for activation function
         *
         * Nothing being called here yet.
         */
        public function activate()
        {

            $installed = get_option('bricksforge_installed');

            if (!$installed) {
                update_option('bricksforge_installed', time());
            }

            update_option('bricksforge_version', BRICKSFORGE_VERSION);
        }

        /**
         * Placeholder for deactivation function
         *
         * Nothing being called here yet.
         */
        public function deactivate() {}

        /**
         * Include the required files
         *
         * @return void
         */
        public function includes()
        {

            require_once BRICKSFORGE_INCLUDES . '/Functions.php';
            require_once BRICKSFORGE_INCLUDES . '/Filters.php';
            require_once BRICKSFORGE_INCLUDES . '/update-checker/plugin-update-checker.php';
            require_once BRICKSFORGE_INCLUDES . '/Assets.php';
            require_once BRICKSFORGE_INCLUDES . '/Api.php';
            require_once BRICKSFORGE_INCLUDES . '/helper/ElementsHelper.php';
            require_once BRICKSFORGE_INCLUDES . '/permissions/Permissions.php';

            if ($this->is_module_active("animations")) {
                require_once BRICKSFORGE_INCLUDES . '/animations/Animations.php';
            }

            if ($this->is_module_active("global-classes")) {
                require_once BRICKSFORGE_INCLUDES . '/global-classes/GlobalClasses.php';
            }

            if ($this->is_module_active("conditional-logic")) {
                require_once BRICKSFORGE_INCLUDES . '/conditional-logic/ConditionalLogic.php';
            }

            if ($this->is_module_active("elements")) {
                require_once BRICKSFORGE_INCLUDES . '/elements/Elements.php';
            }

            if ($this->is_module_active("popups")) {
                require_once BRICKSFORGE_INCLUDES . '/popups/Popups.php';
            }

            if ($this->is_module_active("mega-menu")) {
                require_once BRICKSFORGE_INCLUDES . '/mega-menu/MegaMenu.php';
            }

            if ($this->is_module_active("backend-designer")) {
                require_once BRICKSFORGE_INCLUDES . '/backend-designer/BackendDesigner.php';
            }

            if ($this->is_module_active("form-submissions")) {
                require_once BRICKSFORGE_INCLUDES . '/form-submissions/FormSubmissions.php';
            }

            if ($this->is_module_active("dynamic-data")) {
                require_once BRICKSFORGE_INCLUDES . '/dynamic-data/DynamicData.php';
            }

            if ($this->is_module_active("email-designer")) {
                require_once BRICKSFORGE_INCLUDES . '/email-designer/EmailDesigner.php';
            }

            if ($this->is_module_active("ai")) {
                require_once BRICKSFORGE_INCLUDES . '/ai/AI.php';
            }

            if ($this->is_module_active("parallax")) {
                require_once BRICKSFORGE_INCLUDES . '/parallax/Parallax.php';
            }

            if ($this->is_module_active("page-transitions")) {
                require_once BRICKSFORGE_INCLUDES . '/page-transitions/PageTransitions.php';
            }

            if ($this->is_request('admin')) {
                require_once BRICKSFORGE_INCLUDES . '/Admin.php';
                require_once BRICKSFORGE_INCLUDES . '/whitelabel/WhiteLabel.php';
            }

            if ($this->is_request('frontend')) {
                require_once BRICKSFORGE_INCLUDES . '/Frontend.php';

                if ($this->is_module_active("woocommerce")) {
                    require_once BRICKSFORGE_INCLUDES . '/woocommerce/WooCommerce.php';
                }

                // Maintenance
                if ($this->is_module_active("maintenance")) {
                    require_once BRICKSFORGE_INCLUDES . '/maintenance/Maintenance.php';
                }
            }

            if ($this->is_request('ajax')) {
                // require_once BRICKSFORGE_INCLUDES . '/class-ajax.php';
            }

            if ($this->is_module_active("admin-pages")) {
                require_once BRICKSFORGE_INCLUDES . '/admin-pages/AdminPages.php';
            }

            if (get_option('brf_settings')) {
                $bricksforge_update_checker = Puc_v4_Factory::buildUpdateChecker(
                    'https://update-server.codepa.de/?action=get_metadata&slug=bricksforge',
                    __FILE__,
                    'bricksforge'
                );

                $bricksforge_update_checker->addFilter('pre_inject_update', function ($query_args) {

                    // add_query_arg to $query_args->download_url
                    $query_args->download_url = add_query_arg(
                        array(
                            'lk' => get_option('brf_settings') ? get_option('brf_settings') : 'empty',
                        ),
                        $query_args->download_url
                    );

                    return $query_args;
                });
            }
        }

        /**
         * Initialize the hooks
         *
         * @return void
         */
        public function init_hooks()
        {

            $this->init_classes_before_wp_init();
            add_action('init', array($this, 'init_classes_after_wp_init'), 20);

            // Localize our plugin
            add_action('init', array($this, 'localization_setup'));
        }

        /**
         * Instantiate the required classes before WP Init
         *
         * @return void
         */
        public function init_classes_before_wp_init()
        {

            if ($this->is_request('admin')) {
                $this->container['admin'][] = new Bricksforge\Permissions();
            }

            if ($this->is_request('frontend')) {

                if ($this->is_module_active("dynamic-data")) {
                    $this->container['frontend'][] = new Bricksforge\DynamicData();
                }

                if ($this->is_module_active("woocommerce")) {
                    $this->container['frontend'][] = new Bricksforge\WooCommerce();
                }

                if ($this->is_module_active("conditional-logic")) {
                    $this->container['frontend'][] = new Bricksforge\ConditionalLogic();
                }

                if ($this->is_module_active("parallax")) {
                    $this->container['frontend'][] = new Bricksforge\Parallax();
                }

                $this->container['frontend'][] = new Bricksforge\Permissions();
            }

            if ($this->is_request('builder')) {
                $this->container['builder'][] = new Bricksforge\Permissions();

                if ($this->is_module_active("dynamic-data")) {
                    $this->container['builder'][] = new Bricksforge\DynamicData();
                }
            }

            if (!isset($this->container['emaildesigner']) && $this->is_module_active("email-designer")) {
                $this->container['emaildesigner'] = [Bricksforge\EmailDesigner::get_instance()];
            }
        }

        /**
         * Instantiate the required classes
         *
         * @return void
         */
        public function init_classes_after_wp_init()
        {
            /**
             * Return if Bricks is not loaded
             * @since 0.9.2
             */
            if (!function_exists('bricks_is_builder')) {
                return;
            }

            if ($this->is_request('admin')) {

                if (!class_exists('Bricksforge\Admin')) {
                    return;
                }

                $this->container['admin'][] = new Bricksforge\Admin();
                $this->container['admin'][] = new Bricksforge\Permissions();

                if ($this->is_module_active("animations")) {
                    $this->container['admin'][] = new Bricksforge\Animations();
                }

                if ($this->is_module_active("global-classes")) {
                    $this->container['admin'][] = new Bricksforge\GlobalClasses();
                }

                if ($this->is_module_active("mega-menu")) {
                    $this->container['admin'][] = new Bricksforge\MegaMenu();
                }

                if ($this->is_module_active("backend-designer")) {
                    $this->container['admin'][] = new Bricksforge\BackendDesigner();
                }

                if ($this->is_module_active("form-submissions")) {
                    $this->container['admin'][] = new Bricksforge\FormSubmissions();
                }

                if ($this->is_module_active("popups")) {
                    $this->container['admin'][] = new Bricksforge\Popups();
                }

                $this->container['admin'][] = new Bricksforge\WhiteLabel();
            }

            if ($this->is_request('builder')) {
                $this->container['builder'][] = new Bricksforge\Permissions();

                if ($this->is_module_active("animations")) {
                    $this->container['builder'][] = new Bricksforge\Animations();
                }

                if ($this->is_module_active("global-classes")) {
                    $this->container['builder'][] = new Bricksforge\GlobalClasses();
                }

                if ($this->is_module_active("elements")) {
                    $this->container['builder'][] = new Bricksforge\Elements();
                }

                if ($this->is_module_active("ai")) {
                    $this->container['builder'][] = new Bricksforge\AI();
                }

                if ($this->is_module_active("parallax")) {
                    $this->container['frontend'][] = new Bricksforge\Parallax();
                }
            }

            if ($this->is_request('frontend')) {
                $this->container['frontend'][] = new Bricksforge\Frontend();

                if ($this->is_module_active("animations")) {
                    $this->container['frontend'][] = new Bricksforge\Animations();
                }

                if ($this->is_module_active("global-classes")) {
                    $this->container['frontend'][] = new Bricksforge\GlobalClasses();
                }

                if ($this->is_module_active("elements")) {
                    $this->container['frontend'][] = new Bricksforge\Elements();
                }

                if ($this->is_module_active("popups")) {
                    $this->container['frontend'][] = new Bricksforge\Popups();
                }

                if ($this->is_module_active("backend-designer")) {
                    $this->container['frontend'][] = new Bricksforge\BackendDesigner();
                }

                if ($this->is_module_active("mega-menu")) {
                    $this->container['frontend'][] = new Bricksforge\MegaMenu();
                }

                if ($this->is_module_active("maintenance")) {
                    $this->container['frontend'][] = new Bricksforge\Maintenance();
                }
                if ($this->is_module_active("page-transitions")) {
                    $this->container['frontend'][] = new Bricksforge\PageTransitions();
                }
            }

            if ($this->is_request('ajax')) {
                // $this->container['ajax'] =  new Bricksforge\Ajax();
            }



            $this->container['api'] = new Bricksforge\Api();
            $this->container['assets'] = new Bricksforge\Assets();
            $this->container['helper'][] = new Bricksforge\Helper\ElementsHelper();

            if ($this->is_module_active("admin-pages")) {
                $this->container['admin'][] = new Bricksforge\AdminPages();
            }
        }


        /**
         * Initialize plugin for localization
         *
         * @uses load_plugin_textdomain()
         */
        public function localization_setup()
        {
            load_plugin_textdomain('bricksforge', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        /**
         * What type of request is this?
         *
         * @param  string $type admin, ajax, cron or frontend.
         *
         * @return bool
         */
        private function is_request($type)
        {
            switch ($type) {
                case 'admin':
                    return is_admin();

                case 'builder':
                    // @since 0.9.1
                    if (!function_exists('bricks_is_builder')) {
                        return is_admin();
                    }

                    return bricks_is_builder();

                case 'ajax':
                    return defined('DOING_AJAX');

                case 'rest':
                    return defined('REST_REQUEST');

                case 'cron':
                    return defined('DOING_CRON');

                case 'frontend':
                    return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
            }
        }
        private function is_module_active($module)
        {
            switch ($module) {
                case 'animations':
                    return get_option('brf_activated_tools') && in_array(1, get_option('brf_activated_tools'));
                case 'global-classes':
                    if (get_option('brf_global_classes_activated') == true) {
                        return true;
                    }

                    if (get_option('brf_activated_tools') && in_array(10, get_option('brf_activated_tools'))) {
                        return true;
                    }

                    return false;
                case 'conditional-logic':
                    return get_option('brf_activated_tools') && in_array(2, get_option('brf_activated_tools'));
                case 'elements':
                    return get_option('brf_activated_elements') != false;
                case 'popups':
                    return get_option('brf_popups') != false;
                case 'mega-menu':
                    return get_option('brf_activated_tools') && in_array(3, get_option('brf_activated_tools'));
                case 'backend-designer':
                    return get_option('brf_activated_tools') && in_array(9, get_option('brf_activated_tools'));
                case 'form-submissions':
                    return get_option('brf_activated_tools') && in_array(11, get_option('brf_activated_tools'));
                case 'dynamic-data':
                    return get_option('brf_activated_tools') && in_array(12, get_option('brf_activated_tools'));
                case 'email-designer':
                    return get_option('brf_activated_tools') && in_array(13, get_option('brf_activated_tools'));
                case 'ai':
                    return get_option('brf_activated_tools') && in_array(14, get_option('brf_activated_tools'));
                case 'maintenance':
                    if (!get_option('brf_activated_tools') || !in_array(4, get_option('brf_activated_tools')) || !get_option('brf_maintenance')) {
                        return false;
                    }

                    $settings = get_option('brf_maintenance');
                    if (!$settings[0] || $settings[0]->isActivated == false) {
                        return false;
                    }

                    return true;
                case 'woocommerce':
                    if (class_exists('WooCommerce')) {
                        return true;
                    }

                    return false;
                case 'parallax':
                    return get_option('brf_activated_tools') && in_array(15, get_option('brf_activated_tools'));
                case 'page-transitions':
                    return get_option('brf_activated_tools') && in_array(16, get_option('brf_activated_tools'));
                case 'admin-pages':
                    return get_option('brf_activated_tools') && in_array(17, get_option('brf_activated_tools'));
                default:
                    return false;
            }
        }
    }

    $bricksforge = Bricksforge::init();
}
