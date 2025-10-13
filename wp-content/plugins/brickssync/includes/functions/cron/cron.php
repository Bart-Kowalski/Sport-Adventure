<?php
/**
 * Cron Job Functions for BricksSync
 *
 * This file handles all cron-related functionality for the BricksSync plugin.
 * It provides functionality for:
 * - Scheduling and managing cron events
 * - Running automated sync operations
 * - Handling license checks
 * - Preventing overlapping executions
 *
 * The file includes functions for:
 * - Scheduling cron events on plugin activation
 * - Unscheduling events on plugin deactivation
 * - Running automated sync tasks
 * - Managing cron schedules
 *
 * @package BricksSync\Includes\Functions\Cron
 * @since 0.1
 */

namespace BricksSync\Includes\Functions\Cron;

// These dependencies might not be needed directly in this file anymore
// use BricksSync\Includes\Functions\Helpers\Licensing;
// use BricksSync\Includes\Classes\SyncOptions;

use BricksSync\Includes\Functions\Debug\brickssync_log;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * License Check Hook Definition
 * 
 * Defines the hook name for daily license checks.
 * This constant is used throughout the plugin for:
 * - Scheduling license checks
 * - Attaching callback functions
 * - Managing cron events
 *
 * @since 0.1
 * @see BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK in brickssync.php
 */
if ( ! defined( 'BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK' ) ) {
	define( 'BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK', 'brickssync_daily_license_check' );
}

/**
 * Custom Cron Schedules
 * 
 * Adds custom intervals to WordPress cron schedules.
 * Currently adds:
 * - 'every_minute' schedule for frequent sync operations
 *
 * @since 0.1
 * @param array $schedules Existing cron schedules
 * @return array Modified cron schedules
 */
add_filter( 'cron_schedules', function ( array $schedules ): array {
	$schedules['every_minute'] = array(
		'interval' => 60,                // 60 seconds
		'display'  => __( 'Every Minute', 'brickssync' ),
	);
	return $schedules;
} );

/**
 * Cron Event Scheduler
 * 
 * Schedules all BricksSync cron events on plugin activation.
 * This function:
 * - Clears existing schedules to prevent conflicts
 * - Schedules minute-based sync checks
 * - Schedules daily license checks
 * - Handles scheduling errors
 *
 * @since 0.1
 * @return void
 */
function brickssync_schedule_cron() {
	// First, try to clear any existing schedule to prevent conflicts
	$timestamp = wp_next_scheduled('brickssync_every_minute_hook');
	if ($timestamp) {
		wp_unschedule_event($timestamp, 'brickssync_every_minute_hook');
	}

	// Schedule the 'every_minute' hook
	$scheduled = wp_schedule_event(time(), 'every_minute', 'brickssync_every_minute_hook');
	if (is_wp_error($scheduled)) {
		\BricksSync\Includes\Functions\Debug\brickssync_log('Failed to schedule every_minute hook: ' . $scheduled->get_error_message(), 'error');
	} else {
		\BricksSync\Includes\Functions\Debug\brickssync_log('Successfully scheduled every_minute hook', 'debug');
	}
	
	// Schedule the daily license check hook if it's defined
	if (defined('BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK')) {
		$timestamp = wp_next_scheduled(BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK);
		if ($timestamp) {
			wp_unschedule_event($timestamp, BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK);
		}
		
		$scheduled = wp_schedule_event(time() + 60, 'daily', BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK);
		if (is_wp_error($scheduled)) {
			\BricksSync\Includes\Functions\Debug\brickssync_log('Failed to schedule daily license check: ' . $scheduled->get_error_message(), 'error');
		} else {
			\BricksSync\Includes\Functions\Debug\brickssync_log('Successfully scheduled daily license check', 'debug');
		}
	}
}

/**
 * Cron Event Unscheduler
 * 
 * Unschedules all BricksSync cron events on plugin deactivation.
 * This function:
 * - Removes minute-based sync checks
 * - Removes daily license checks
 * - Logs deactivation actions
 *
 * @since 0.1
 * @return void
 */
function brickssync_unschedule_cron() {
	// Unschedule the minute hook.
	$timestamp_minute = wp_next_scheduled( 'brickssync_every_minute_hook' );
	if ( $timestamp_minute ) {
		wp_unschedule_event( $timestamp_minute, 'brickssync_every_minute_hook' );
	}

	// Unschedule the daily license check hook if it's defined.
	if ( defined('BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK') ) {
        $timestamp_daily = wp_next_scheduled( BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK );
        if ( $timestamp_daily ) {
            wp_unschedule_event( $timestamp_daily, BRICKSSYNC_DAILY_LICENSE_CHECK_HOOK );
        }
    }

	\BricksSync\Includes\Functions\Debug\brickssync_log('Deactivation hook context: Unscheduled cron jobs.', 'debug');
}

/**
 * Minute Hook Handler
 * 
 * Attaches the main cron task to the 'every_minute' hook.
 * This function:
 * - Logs hook execution
 * - Triggers the main cron task
 *
 * @since 0.1
 */
add_action( 'brickssync_every_minute_hook', function () {
	\BricksSync\Includes\Functions\Debug\brickssync_log('brickssync_every_minute_hook triggered at ' . current_time( 'mysql' ), 'debug');
	// Run the main cron task function.
	brickssync_run_cron();
} );

/**
 * Main Cron Task Executor
 * 
 * Executes the main cron task every minute.
 * This function:
 * - Checks license status
 * - Validates sync mode
 * - Prevents overlapping executions
 * - Runs import/export operations
 * - Handles errors and exceptions
 *
 * Features:
 * - License validation
 * - Sync mode checking
 * - Lock mechanism to prevent overlaps
 * - Error handling and logging
 * - Automatic cleanup
 *
 * @since 0.1
 * @return void
 */
function brickssync_run_cron() {
	// 1. Check license status (using the helper from the main plugin file).
	// Assumes brickssync_is_license_active() is loaded from brickssync.php
	if ( function_exists('brickssync_is_license_active') && ! \brickssync_is_license_active() ) {
		\BricksSync\Includes\Functions\Debug\brickssync_log('Cron task skipped: License is not active.', 'debug');
		return; // Stop if license is not active.
	}
	
	// 2. Get sync mode setting.
	$sync_mode = get_option('brickssync_sync_mode');

	// 3. Proceed only if sync mode is not manual and not empty.
	if ($sync_mode && $sync_mode !== 'manual') {
		\BricksSync\Includes\Functions\Debug\brickssync_log('Cron: Running tasks for sync_mode: ' . $sync_mode, 'debug');

		// 4. Basic locking mechanism using a transient to prevent overlap.
		$lock_transient = 'brickssync_cron_running';
		if (get_transient($lock_transient)) {
			\BricksSync\Includes\Functions\Debug\brickssync_log('Cron task skipped: Lock transient exists (' . $lock_transient . ').', 'debug');
			return; // Exit if another instance seems to be running.
		}
		// Set lock with a 5-minute expiry.
		set_transient($lock_transient, true, MINUTE_IN_SECONDS * 5);

		try {
			// 5. Run import/export operations according to sync mode
			$settings_sync_enabled = get_option('brickssync_settings_sync_enabled', '1') === '1';
			if ($sync_mode === 'automatic') {
				if (function_exists('brickssync_bricks_template_export')) {
					brickssync_bricks_template_export();
				}
				if (function_exists('brickssync_bricks_template_import')) {
					brickssync_bricks_template_import(['overwrite' => true]);
				}
				// SETTINGS EXPORT/IMPORT
				if ($settings_sync_enabled) {
					if (function_exists('brickssync_bricks_settings_export')) {
						$storage_dir = function_exists('BricksSync\Includes\Functions\Storage\brickssync_get_storage_path') ? \BricksSync\Includes\Functions\Storage\brickssync_get_storage_path() : null;
						$settings_filename = get_option('brickssync_settings_file_name', 'bricks-builder-settings.json');
						$args = [];
						if ($storage_dir) {
							$args['target_file'] = trailingslashit($storage_dir) . $settings_filename;
						}
						brickssync_bricks_settings_export($args);
					}
					if (function_exists('brickssync_bricks_settings_import')) {
						$storage_dir = function_exists('BricksSync\Includes\Functions\Storage\brickssync_get_storage_path') ? \BricksSync\Includes\Functions\Storage\brickssync_get_storage_path() : null;
						$settings_filename = get_option('brickssync_settings_file_name', 'bricks-builder-settings.json');
						$args = [];
						if ($storage_dir) {
							$args['source_file'] = trailingslashit($storage_dir) . $settings_filename;
						}
						brickssync_bricks_settings_import($args);
					}
				}
			} elseif ($sync_mode === 'export') {
				if (function_exists('brickssync_bricks_template_export')) {
					brickssync_bricks_template_export($template_args);
				}
				if ($settings_sync_enabled && function_exists('brickssync_bricks_settings_export')) {
					brickssync_bricks_settings_export($settings_args);
				}
			} elseif ($sync_mode === 'import') {
				if (function_exists('brickssync_bricks_template_import')) {
					brickssync_bricks_template_import(array_merge($template_args, ['overwrite' => true]));
				}
				if ($settings_sync_enabled && function_exists('brickssync_bricks_settings_import')) {
					brickssync_bricks_settings_import($settings_args);
				}
			}
		} catch (\Exception $e) {
			// Log any exceptions during execution.
			\BricksSync\Includes\Functions\Debug\brickssync_log('Exception during cron execution: ' . $e->getMessage(), 'error');
			// TODO: Consider more robust error handling/reporting.
		
			// 6. Always release the lock.
			delete_transient($lock_transient);
		}

	} else {
		\BricksSync\Includes\Functions\Debug\brickssync_log('Cron task skipped: sync_mode is manual or not set (' . esc_html($sync_mode) . ').', 'debug');
	}
}