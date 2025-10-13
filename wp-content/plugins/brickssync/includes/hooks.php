<?php
/**
 * BricksSync Hooks
 *
 * Registers WordPress hooks and actions used throughout the plugin.
 *
 * @package BricksSync\Includes
 * @since 0.1
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use BricksSync\Includes\Functions\Licensing\daily_license_validation_cron;

/**
 * Plugin Activation/Deactivation Hooks
 * 
 * These hooks handle:
 * - Scheduling cron jobs on activation
 * - Unscheduling cron jobs on deactivation
 * 
 * @since 0.1
 */
register_deactivation_hook(BRICKSSYNC_PLUGIN_FILE, 'Brickssync\\Includes\\Functions\\Cron\\brickssync_unschedule_cron');
register_activation_hook(BRICKSSYNC_PLUGIN_FILE, 'Brickssync\\Includes\\Functions\\Cron\\brickssync_schedule_cron');

/**
 * Admin Menu Hook
 * 
 * Adds the BricksSync menu item to the WordPress admin.
 * Priority 20 ensures it runs after the Bricks menu is created.
 * 
 * @since 0.1
 */
add_action('admin_menu', 'Brickssync\\Includes\\Functions\\Admin\\brickssync_add_admin_page', 20);

/**
 * Form Handler Hook
 * 
 * Processes form submissions from the BricksSync admin pages.
 * Runs on admin_init to handle actions before headers are sent.
 * 
 * @since 0.1
 */
add_action('admin_init', 'Brickssync\\Includes\\Functions\\Admin\\brickssync_handle_tab_actions');

/**
 * License Validation Cron Hook
 * 
 * Runs the daily license validation check.
 * Ensures the license remains active and valid.
 * 
 * @since 0.1
 */
add_action(BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK, __NAMESPACE__ . '\\daily_license_validation_cron'); 