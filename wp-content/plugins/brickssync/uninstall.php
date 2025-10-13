<?php
/**
 * BricksSync Uninstall Script
 *
 * Cleans up plugin data and options when BricksSync is uninstalled from WordPress.
 *
 * @package BricksSync
 * @since 0.1
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Remove plugin options and custom data here. Example:
// delete_option('brickssync_some_option');

// Delete plugin options
$options = [
    'brickssync_json_storage_location',
    'brickssync_custom_storage_location_path',
    'brickssync_debug_logging',
    'brickssync_license_key',
    'brickssync_license_status',
    'brickssync_license_expiry',
];

foreach ($options as $option) {
    delete_option($option);
}

// Clear any scheduled cron events
wp_clear_scheduled_hook('brickssync_daily_license_check');

// Remove any transients
delete_transient('brickssync_license_status_cache');

// Clean up uploads directory if it exists
$upload_dir = wp_upload_dir();
$brickssync_dir = trailingslashit($upload_dir['basedir']) . 'brickssync';
if (is_dir($brickssync_dir)) {
    // Recursively remove the directory and its contents
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($brickssync_dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $fileinfo) {
        if ($fileinfo->isDir()) {
            rmdir($fileinfo->getRealPath());
        } else {
            unlink($fileinfo->getRealPath());
        }
    }
    rmdir($brickssync_dir);
}