<?php
/**
 *
 * @package   Advanced Themer for Bricks
 * @author    Maxime Beguin
 * @copyright 2022 Maxime Beguin
 * @license   GPL-2.0-or-later
 *
 * Plugin Name: Advanced Themer for Bricks
 * Description: Advanced Themer levels up your efficiency in building websites with Bricks thanks to dozens of productivity hacks designed to facilitate your development process.
 * Plugin URI:  https://advancedthemer.com/
 * Author:      Maxime Beguin
 * Author URI:  https://advancedthemer.com/
 * Created:     12.04.2023
 * Version:     3.3.7
 * Text Domain: bricks-advanced-themer
 * Domain Path: /lang
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Copyright (C) 2022 Maxime Beguin
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses/>.
 */

namespace Advanced_Themer_Bricks;

// Exit if accessed directly
defined('ABSPATH') || exit;

// Prevent multiple instances
if (defined('BRICKS_ADVANCED_THEMER_PATH')) {
    return;
}

/**
 * Main Plugin Class
 */
final class BricksAdvancedThemer {
    /**
     * @var BricksAdvancedThemer Single instance of the class
     */
    private static $instance = null;
    private static $initialized = false;

    /**
     * @var string Plugin version
     */
    public $version = '3.3.7';

    /**
     * @var string Plugin file path
     */
    public $file;

    /**
     * @var string Plugin directory path
     */
    public $dir;

    /**
     * @var string Plugin URL
     */
    public $url;

    /**
     * @var array Core classes to load
     */
    private $classes = [
        'BRXC_SL_Plugin_Updater' => 'classes/EDD_SL_Plugin_Updater.php',
        'AT__license'           => 'classes/license.php',
        'AT__Helpers'           => 'classes/helpers.php',
        'AT__ACF'               => 'classes/acf.php',
        'AT__Admin'             => 'classes/admin.php',
        'AT__Global_Colors'     => 'classes/global_colors.php',
        'AT__Class_Importer'    => 'classes/class_importer.php',
        'AT__Frontend'          => 'classes/frontend.php',
        'AT__Builder'           => 'classes/builder.php',
        'AT__Ajax'              => 'classes/ajax.php',
        'AT__Conversion'        => 'classes/conversion.php',
        'AT__Framework'         => 'classes/framework.php',
    ];

    /**
     * Main Plugin Instance
     *
     * Ensures only one instance of the plugin is loaded
     *
     * @return BricksAdvancedThemer
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$initialized = true;
            return self::$instance;
        } else if (self::$initialized) {
            // Another initialization attempt detected after already initialized
            self::$instance->duplicate_instance_notice();
            self::$instance->deactivate_plugin();
        }
        
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->define_constants();
        $this->setup_paths();
        
        // Check Bricks theme dependency before continuing
        if (!$this->check_bricks_dependency()) {
            return;
        }
        
        $this->load_classes();
        $this->load_vendor();
        $this->load_startup();
    }

    /**
     * Define plugin constants
     */
    private function define_constants() {
        define( 'BRICKS_ADVANCED_THEMER_PLUGIN_FILE', __FILE__);
        define( 'BRICKS_ADVANCED_THEMER_VERSION', $this->version);
        define( 'BRICKS_ADVANCED_THEMER_PLUGIN_BASENAME', plugin_basename(__FILE__));
        define( 'BRICKS_ADVANCED_THEMER_PATH', plugin_dir_path( __FILE__ ) );
        define( 'BRICKS_ADVANCED_THEMER_URL', plugin_dir_url( __FILE__  ) );
        define( 'BRICKS_ADMIN_SLUG', 'advanced-themer' );
        define( 'BRXC_STORE_URL', 'https://advancedthemer.com/' );
        define( 'BRXC_ITEM_ID', 14 );
        define( 'BRXC_ITEM_NAME',  'Advanced Themer for Bricks' );
        define( 'BRXC_EDD_AUTHOR', 'Maxime Beguin' );
        define( 'BRXC_EDD_PLUGINVERSION',  BRICKS_ADVANCED_THEMER_VERSION );
        define( 'BRXC_PLUGIN_LICENSE_PAGE', 'at-license');
    }

    /**
     * Setup plugin paths
     */
    private function setup_paths() {
        $this->file = BRICKS_ADVANCED_THEMER_PLUGIN_FILE;
        $this->dir = plugin_dir_path($this->file);
        $this->url = plugin_dir_url($this->file);
    }

    /**
     * Check if Bricks theme is active
     * 
     * @return bool Whether Bricks theme is active
     */
    private function check_bricks_dependency() {
        if (!function_exists('wp_get_theme')) {
            $this->deactivate_plugin();
            $this->display_theme_missing_notice();
            return false;
        }

        $theme = wp_get_theme();
        if ('Bricks' != $theme->name && 'Bricks' != $theme->parent_theme) {
            $this->deactivate_plugin();
            $this->display_theme_missing_notice();
            return false;
        }

        return true;
    }

    /**
     * Load all required classes
     */
    private function load_classes() {
        foreach ($this->classes as $class => $path) {
            $full_class = __NAMESPACE__ . '\\' . $class;
            if (!class_exists($full_class)) {
                $full_path = $this->dir . $path;
                if (file_exists($full_path)) {
                    require_once $full_path;
                } else {
                    // Log missing file
                    error_log("Advanced Themer: Class file not found: {$full_path}");
                }
            }
        }
    }

    /**
     * Load vendor dependencies
     */
    private function load_vendor() {
        $vendor_path = $this->dir . 'vendor/autoload.php';
        if (file_exists($vendor_path)) {
            require_once $vendor_path;
        } else {
            error_log("Advanced Themer: Vendor autoload not found");
        }
    }

    /**
     * Load startup file
     */
    private function load_startup() {
        $startup_path = $this->dir . 'start.php';
        if (file_exists($startup_path)) {
            require_once $startup_path;
        } else {
            error_log("Advanced Themer: Startup file not found");
        }
    }

    /**
     * Deactivate the plugin
     */
    public function deactivate_plugin() {
        add_action('admin_init', function() {
            $plugin = plugin_basename(__FILE__);
            if (is_plugin_active($plugin)) {
                deactivate_plugins($plugin);
            }
        });
    }

    /**
     * Display missing theme notice
     */
    private function display_theme_missing_notice() {
        add_action('admin_notices', function() {
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php esc_html_e('Advanced Themer could not be activated because the Bricks theme hasn\'t been found. Please install and activate Bricks before using Advanced Themer.', 'bricks-advanced-themer'); ?></p>
            </div>
            <?php
        });
    }

    public function duplicate_instance_notice() {
        add_action('admin_notices', function() {
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php esc_html_e('Error: Multiple instances of Advanced Themer detected. The plugin has not been activated to prevent conflicts.', 'bricks-advanced-themer'); ?></p>
            </div>
            <?php
        });
    }
}

// Initialize the plugin
BricksAdvancedThemer::instance();