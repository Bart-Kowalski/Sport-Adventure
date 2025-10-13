<?php
namespace BricksSync\Includes\Functions\WPCLI;

use WP_CLI;

/**
 * WP-CLI command group for BricksSync config operations.
 */
class Config_CLI extends \WP_CLI_Command {
    /**
     * Display current BricksSync configuration.
     *
     * ## OPTIONS
     *
     * [--format=<format>]
     * : Output format (table, json, yaml).
     * ---
     * default: table
     * options:
     *   - table
     *   - json
     *   - yaml
     * ---
     *
     * ## EXAMPLES
     *
     *     wp brickssync config show
     *     wp brickssync config show --format=json
     *
     * @subcommand show
     */
    public function show( $args, $assoc_args ) {
        WP_CLI::log( "Current BricksSync Configuration:" );
        $config = [
            ['Setting' => 'brickssync_json_storage_location', 'Value' => get_option('brickssync_json_storage_location', '[Not Set]')],
            ['Setting' => 'brickssync_custom_storage_location_path','Value' => get_option('brickssync_custom_storage_location_path', '[Not Set]')],
            ['Setting' => 'brickssync_settings_file_name', 'Value' => get_option('brickssync_settings_file_name', '[Not Set]')],
            ['Setting' => 'brickssync_templates_file_pattern', 'Value' => get_option('brickssync_templates_file_pattern', '[Not Set]')],
            ['Setting' => 'brickssync_excluded_options', 'Value' => get_option('brickssync_excluded_options', '[Not Set]')],
            ['Setting' => 'brickssync_sync_mode', 'Value' => get_option('brickssync_sync_mode', '[Not Set]')],
            ['Setting' => 'brickssync_debug_logging', 'Value' => get_option('brickssync_debug_logging') ? 'Enabled' : 'Disabled'],
        ];
        if (!function_exists('WP_CLI\Utils\format_items')) {
            if (defined('WP_CLI_ROOT') && file_exists(WP_CLI_ROOT . '/php/WP_CLI/Utils.php')) {
                require_once WP_CLI_ROOT . '/php/WP_CLI/Utils.php';
            }
        }
        \WP_CLI\Utils\format_items( 'table', $config, ['Setting', 'Value'] );
    }
}
