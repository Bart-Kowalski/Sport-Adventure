<?php
namespace BricksSync\Includes\Functions\WPCLI;

use WP_CLI;
// Ensure the debug logger is available for CLI context
require_once dirname(__DIR__) . '/debug/debug.php';

/**
 * WP-CLI command group for BricksSync settings operations.
 */
class Settings_CLI extends \WP_CLI_Command {
    /**
     * Export Bricks global settings to JSON.
     *
     * ## OPTIONS
     *
     * [--output-file=<path>]
     * : Path to export the JSON file. If not provided, uses the configured path and filename.
     *
     * ## EXAMPLES
     *
     *     wp brickssync settings export
     *     wp brickssync settings export --output-file=/path/to/my-settings.json
     *
     * @subcommand export
     */
    public function export( $args, $assoc_args ) {
        if ( ! function_exists('brickssync_bricks_settings_export') ) {
            WP_CLI::error( 'The function brickssync_bricks_settings_export does not exist.' );
            return;
        }
        $output_file_arg = $assoc_args['output-file'] ?? null;
        $export_args = [];
        if ($output_file_arg) {
            $export_args['target_file'] = $output_file_arg;
        }
        $export_args['force'] = true;
        $result = brickssync_bricks_settings_export($export_args);
        if (\is_wp_error($result)) {
            WP_CLI::error($result->get_error_message());
        }
        WP_CLI::success(is_string($result) ? $result : 'Settings exported successfully.');
    }

    /**
     * Import Bricks global settings from JSON.
     *
     * ## OPTIONS
     *
     * [--file=<path>]
     * : Path to the JSON file to import. If not provided, uses the configured path and filename.
     *
     * ## EXAMPLES
     *
     *     wp brickssync settings import
     *     wp brickssync settings import --file=/path/to/import-settings.json
     *
     * @subcommand import
     */
    public function import( $args, $assoc_args ) {
        if ( ! function_exists('brickssync_bricks_settings_import') ) {
            WP_CLI::error( 'The function brickssync_bricks_settings_import does not exist.' );
            return;
        }
        $import_file_arg = $assoc_args['file'] ?? null;
        $import_args = [];
        if ($import_file_arg) {
            $import_args['source_file'] = $import_file_arg;
        } else {
            $storage_dir = function_exists('brickssync_get_storage_path') ? brickssync_get_storage_path() : null;
            $settings_filename = get_option('brickssync_settings_file_name', 'bricks-builder-settings.json');
            if ($storage_dir) {
                $import_args['source_file'] = trailingslashit($storage_dir) . $settings_filename;
            }
        }
        $result = brickssync_bricks_settings_import($import_args);
        if (\is_wp_error($result)) {
            WP_CLI::error($result->get_error_message());
        }
        WP_CLI::success(is_string($result) ? $result : 'Settings imported successfully.');
    }
}
