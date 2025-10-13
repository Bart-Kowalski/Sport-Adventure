<?php
/**
 * Cron Job Functions
 *
 * This file handles the scheduling and execution of periodic tasks for BricksSync.
 * Currently manages:
 * - Daily license validation checks
 *
 * The cron jobs are scheduled on plugin activation and unscheduled on deactivation.
 * License validation runs daily to ensure the license remains active.
 *
 * @package BricksSync
 * @since 0.1
 */

use BricksSync\Includes\Functions\Licensing\daily_license_validation_cron;

/**
 * Schedule the daily license validation cron job.
 * 
 * This action is hooked to the plugin activation event.
 * Ensures the license status is checked regularly.
 *
 * @since 0.1
 * @return void
 */
add_action(BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK, __NAMESPACE__ . '\\daily_license_validation_cron'); 