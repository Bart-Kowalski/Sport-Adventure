<?php
/**
 * Debug Helper Functions
 *
 * This file contains functions for debug logging and error tracking in BricksSync.
 * It provides functionality for:
 * - Logging debug messages and errors
 * - Managing debug log files
 * - Retrieving log contents
 * - Clearing log files
 *
 * The file includes functions for:
 * - Writing log entries with context
 * - Managing log file locations
 * - Retrieving log contents
 * - Clearing log files
 *
 * @package BricksSync\Includes\Functions\Debug
 * @since 0.1
 */

namespace BricksSync\Includes\Functions\Debug;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Debug Logger
 * 
 * Logs debug messages if debug logging is enabled.
 * This function:
 * - Checks if debug logging is enabled
 * - Formats log entries with timestamp and type
 * - Includes context data if provided
 * - Writes to WordPress error log
 *
 * Features:
 * - Multiple log types (debug, info, warning, error)
 * - Timestamp inclusion
 * - Context data support
 * - Conditional logging
 *
 * @since 0.1
 * @param string $message The message to log
 * @param string $type The type of message (debug, info, warning, error)
 * @param array|null $context Additional context data to log
 * @return void
 */
function brickssync_log($message, $type = 'debug', $context = null) {
    if (is_multisite()) {
        if (!get_site_option('brickssync_debug_logging', false)) {
            return;
        }
    } else {
        if (!get_option('brickssync_debug_logging', false)) {
            return;
        }
    }

    $log_entry = sprintf(
        '[BricksSync %s] [%s] %s',
        strtoupper($type),
        current_time('Y-m-d H:i:s'),
        $message
    );

    if ($context !== null) {
        $log_entry .= "\nContext: " . wp_json_encode($context, JSON_PRETTY_PRINT);
    }

    error_log($log_entry);
}

/**
 * Debug Log Path Manager
 * 
 * Gets the path to the debug log file.
 * This function:
 * - Checks configured storage path
 * - Falls back to uploads directory if needed
 * - Creates log directory if missing
 * - Returns log file path
 *
 * Features:
 * - Storage path validation
 * - Fallback path handling
 * - Directory creation
 * - Path resolution
 *
 * @since 0.1
 * @return string|null Path to the debug log file or null if not found
 */
function brickssync_get_debug_log_path() {
    // Get the configured storage path status
    $storage_status = \Brickssync\Includes\Functions\Storage\brickssync_get_storage_path_status();
    
    // If storage path is not configured or not writable, use uploads directory as fallback
    if ($storage_status['status'] !== 'ok' || !$storage_status['is_writable']) {
        $upload_dir = wp_upload_dir();
        $log_dir = trailingslashit($upload_dir['basedir']) . 'brickssync/logs';
    } else {
        $log_dir = trailingslashit($storage_status['path']) . 'logs';
    }
    
    $log_file = $log_dir . '/debug.log';

    if (!file_exists($log_dir)) {
        wp_mkdir_p($log_dir);
    }

    return $log_file;
}

/**
 * Debug Log Cleaner
 * 
 * Clears the contents of the debug log file.
 * This function:
 * - Gets log file path
 * - Checks file writability
 * - Clears file contents
 * - Returns operation status
 *
 * Features:
 * - File existence check
 * - Permission validation
 * - Safe file clearing
 * - Status reporting
 *
 * @since 0.1
 * @return bool True if log was cleared, false otherwise
 */
function brickssync_clear_debug_log() {
    $log_file = brickssync_get_debug_log_path();
    if ($log_file && is_writable($log_file)) {
        return file_put_contents($log_file, '') !== false;
    }
    return false;
}

/**
 * Debug Log Reader
 * 
 * Gets the contents of the debug log file.
 * This function:
 * - Retrieves log file path
 * - Handles partial log reading
 * - Manages file access
 * - Returns log contents
 *
 * Features:
 * - Line-based reading
 * - Efficient file handling
 * - Error handling
 * - Memory optimization
 *
 * @since 0.1
 * @param int $lines Number of lines to return (0 for all)
 * @return string|null Log contents or null if file not found
 */
function brickssync_get_debug_log($lines = 0) {
    $log_file = brickssync_get_debug_log_path();
    if (!$log_file || !is_readable($log_file)) {
        return null;
    }

    if ($lines > 0) {
        // Get only the specified number of lines
        $file = new \SplFileObject($log_file, 'r');
        $file->seek(PHP_INT_MAX);
        $total_lines = $file->key();
        
        $start_line = max(0, $total_lines - $lines);
        $log_contents = '';
        
        $file->seek($start_line);
        while (!$file->eof()) {
            $log_contents .= $file->fgets();
        }
        
        return $log_contents;
    }

    return file_get_contents($log_file);
} 