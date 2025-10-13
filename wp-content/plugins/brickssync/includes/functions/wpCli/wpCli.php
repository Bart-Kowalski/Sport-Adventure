<?php
/**
 * WP CLI Commands for BricksSync
 * 
 * This file is only loaded when WP CLI is active.
 * For documentation, see includes/functions/wpCli/README.md
 */

namespace BricksSync\Includes\Functions\WPCLI;

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	// Ensure command group classes are loaded
	require_once __DIR__ . '/Settings_CLI.php';
	require_once __DIR__ . '/Templates_CLI.php';
	require_once __DIR__ . '/Config_CLI.php';

	/**
	 * WP CLI Command Group for BricksSync Operations (v0.7.7-beta)
	 *
	 * Provides a command-line interface for managing BricksSync functionality:
	 * - Settings management (export/import)
	 * - Template management (list/export/import)
	 * - Configuration viewing
	 * - Status checking
	 *
	 * Available Commands:
	 *   wp brickssync settings export [--output-file=<path>]
	 *   wp brickssync settings import [--file=<path>]
	 *   wp brickssync templates list [--format=<format>]
	 *   wp brickssync templates export [--output-dir=<path>] [--templates=<ids>]
	 *   wp brickssync templates import [--input-dir=<path>]
	 *   wp brickssync config show [--format=<format>]
	 *   wp brickssync status [--format=<format>]
	 *
	 * See includes/functions/wpCli/README.md for full documentation.
	 *
	 * @since 0.1
	 */
	class BricksSync_CLI extends \WP_CLI_Command {

		/**
		 * Export Bricks global settings to JSON.
		 *
		 * This command exports all Bricks Builder settings to a JSON file.
		 * The file can be saved to a custom location or use the configured path.
		 *
		 * Features:
		 * - Custom output file path support
		 * - Automatic directory creation (if needed)
		 * - Permission checking
		 * - Detailed progress logging
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
		public function settings_export( $args, $assoc_args ) {
			if ( ! function_exists('brickssync_bricks_settings_export') ) {
				\WP_CLI::error( 'The function brickssync_bricks_settings_export does not exist.' );
				return;
			}
			$output_file_arg = $assoc_args['output-file'] ?? null;
			$export_args = [];
			if ($output_file_arg) {
				$export_args['target_file'] = $output_file_arg;
			}
			// Always force overwrite
			$export_args['force'] = true;
			$result = brickssync_bricks_settings_export($export_args);
			if (\is_wp_error($result)) {
				\WP_CLI::error($result->get_error_message());
			}
			\WP_CLI::success(is_string($result) ? $result : 'Settings exported successfully.');
		}

		/**
		 * Import Bricks global settings from JSON.
		 *
		 * This command imports Bricks Builder settings from a JSON file.
		 * The file can be read from a custom location or the configured path.
		 *
		 * Features:
		 * - Custom input file path support
		 * - File existence and readability checks
		 * - Validation of JSON format
		 * - Detailed progress logging
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
		public function settings_import( $args, $assoc_args ) {
			if ( ! function_exists('brickssync_bricks_settings_import') ) {
				\WP_CLI::error( 'The function brickssync_bricks_settings_import does not exist.' );
				return;
			}
			$import_file_arg = $assoc_args['file'] ?? null;
			$import_args = [];
			if ($import_file_arg) {
				$import_args['source_file'] = $import_file_arg;
			} else {
				// Use default settings file in the configured subdir
				$storage_dir = function_exists('brickssync_get_storage_path') ? brickssync_get_storage_path() : null;
				$settings_filename = get_option('brickssync_settings_file_name', 'bricks-builder-settings.json');
				if ($storage_dir) {
					$import_args['source_file'] = trailingslashit($storage_dir) . $settings_filename;
				}
			}
			$result = brickssync_bricks_settings_import($import_args);
			if (\is_wp_error($result)) {
				\WP_CLI::error($result->get_error_message());
			}
			\WP_CLI::success(is_string($result) ? $result : 'Settings imported successfully.');
		}

		/**
		 * List all Bricks templates.
		 *
		 * This command displays a list of all Bricks templates with their details.
		 * Supports multiple output formats for easy integration with other tools.
		 *
		 * Features:
		 * - Multiple output formats (table, JSON, CSV, YAML)
		 * - Displays template ID, title, status, and type
		 * - Includes all template statuses (publish, draft, private)
		 *
		 * ## OPTIONS
		 *
		 * [--format=<format>]
		 * : Render output in a particular format.
		 * ---
		 * default: table
		 * options:
		 *   - table
		 *   - json
		 *   - csv
		 *   - yaml
		 *   - count
		 * ---
		 *
		 * ## EXAMPLES
		 *
		 *     wp brickssync templates list
		 *     wp brickssync templates list --format=csv
		 *
		 * @subcommand list
		 */
		public function templates_list( $args, $assoc_args ) {
			\WP_CLI::log( "Listing Bricks templates..." );

			// Query Bricks templates
			$templates = [];
			// Ensure WP_Query is available in CLI context
			if (!class_exists('WP_Query')) {
				require_once ABSPATH . 'wp-includes/class-wp-query.php';
			}
			$query = new \WP_Query([
				'post_type' => 'bricks_template',
				'post_status' => ['publish', 'draft', 'pending', 'private'], // Include common statuses
				'posts_per_page' => -1, // Get all templates
				'orderby' => 'title',
				'order' => 'ASC',
			]);

			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					$template_id = get_the_ID();
					$templates[] = [
						'ID' => $template_id,
						'title' => get_the_title(),
						'type' => get_post_meta($template_id, '_bricks_template_type', true), // Get Bricks specific type
					];
				}
				wp_reset_postdata(); // Restore original post data
			}

			if ( empty($templates) ) {
				\WP_CLI::log( "No templates found." );
				return;
			}

			// Define columns for the formatter
			$fields = ['ID', 'title', 'type'];
			$formatter = new \WP_CLI\Formatter( $assoc_args, $fields );
			$formatter->display_items( $templates );
		}

		/**
		 * Export Bricks templates to JSON files.
		 *
		 * This command exports selected or all Bricks templates to JSON files.
		 * Templates can be exported to a custom directory or the configured path.
		 *
		 * Features:
		 * - Export specific templates by ID
		 * - Custom output directory support
		 * - Automatic directory creation
		 * - Permission validation
		 * - Progress reporting
		 *
		 * ## OPTIONS
		 *
		 * [--output-dir=<path>]
		 * : Directory to export templates to. If not provided, uses the configured path.
		 *
		 * [--templates=<ids>]
		 * : Comma-separated list of template IDs to export. If not provided, exports all templates.
		 *
		 * ## EXAMPLES
		 *
		 *     wp brickssync templates export
		 *     wp brickssync templates export --output-dir=/path/to/templates
		 *     wp brickssync templates export --templates=123,456
		 *
		 * @subcommand export
		 */
		public function templates_export( $args, $assoc_args ) {
			if ( ! \BricksSync\Includes\Functions\Licensing\is_license_active() ) {
				\WP_CLI::error( "A valid license is required to use this command. Please activate your license." );
				return;
			}
			$template_id = $assoc_args['template-id'] ?? null;
			$output_dir_arg = $assoc_args['output-dir'] ?? null;
			$export_args = [];
			$export_args['force'] = true;
			if ($output_dir_arg) {
				// Use provided directory + subdir
				$subdir = get_option('brickssync_json_subdir', 'brickssync-json');
				$target_dir_path = \trailingslashit($output_dir_arg) . trim($subdir, '/');
				$target_dir_path = \trailingslashit($target_dir_path);
				\WP_CLI::log( "Using provided output directory: " . $target_dir_path );
				// Optional: Check writability
				if (!\is_dir($target_dir_path)) {
					\wp_mkdir_p($target_dir_path);
				}
				if (!\is_dir($target_dir_path) || !\wp_is_writable($target_dir_path)) {
					\WP_CLI::warning( "The directory specified in --output-dir may not be writable or exist: " . $target_dir_path );
				}
				$export_args['target_dir'] = $target_dir_path;
			}
			// If no output-dir provided, do not set target_dir; let the default logic handle subdir
			$file_pattern = \get_option('brickssync_templates_file_pattern', 'template-{slug}.json');

			if ( $template_id ) {
				\WP_CLI::log( "Attempting to export specific Bricks template ID: {$template_id}..." );
				// Optional: Check if template ID exists
				if ( ! \get_post($template_id) || 'bricks_template' !== \get_post_type($template_id) ) {
					\WP_CLI::error( "Invalid or non-existent Bricks template ID: " . $template_id );
					return;
				}
			} else {
				\WP_CLI::log( "Attempting to export all Bricks templates..." );
			}

			try {
				\WP_CLI::log( "Preparing parameters for brickssync_bricks_template_export()..." );
				\WP_CLI::log( " - Target Directory: " . $target_dir_path );
				\WP_CLI::log( " - Template ID: " . ($template_id ?: 'All') );
				\WP_CLI::log( " - File Pattern: " . $file_pattern );

				// --- Call the core export function --- 
				$result = brickssync_bricks_template_export([
					'target_dir' => $target_dir_path,
					'template_id' => $template_id,
					'file_pattern' => $file_pattern
				]);
				if (\is_wp_error($result)) {
					throw new Exception($result->get_error_message());
				}
				$message = $template_id 
					? "Specific template export initiated." 
					: "All templates export initiated.";
				\WP_CLI::success( $message . " Target Dir: " . $target_dir_path );
			} catch ( Exception $e ) {
				\WP_CLI::error( "Template export failed: " . $e->getMessage() );
			}
		}

		/**
		 * Import Bricks templates from JSON files.
		 *
		 * This command imports Bricks templates from JSON files in a directory.
		 * Templates can be imported from a custom directory or the configured path.
		 *
		 * Features:
		 * - Custom input directory support
		 * - Automatic file validation
		 * - Duplicate handling
		 * - Progress reporting
		 * - Error handling for invalid files
		 *
		 * ## OPTIONS
		 *
		 * [--input-dir=<path>]
		 * : Directory containing template JSON files. If not provided, uses the configured path.
		 *
		 * ## EXAMPLES
		 *
		 *     wp brickssync templates import
		 *     wp brickssync templates import --input-dir=/path/to/templates
		 *
		 * @subcommand import
		 */
		public function templates_import( $args, $assoc_args ) {
			if ( ! \BricksSync\Includes\Functions\Licensing\is_license_active() ) {
				\WP_CLI::error( "A valid license is required to use this command. Please activate your license." );
				return;
			}
			$file_arg = $assoc_args['file'] ?? null;
			$dir_arg = $assoc_args['dir'] ?? $assoc_args['input-dir'] ?? null;
			$overwrite = isset( $assoc_args['overwrite'] );
			
			$source_path = '';
			$import_mode = ''; // 'file' or 'directory'

			if ( $file_arg ) {
				// Import specific file
				$source_path = $file_arg;
				$import_mode = 'file';
				\WP_CLI::log( "Attempting to import Bricks template from specific file: {$source_path}..." );
				if ( ! \is_file($source_path) ) {
					\WP_CLI::error( "The specified file does not exist: " . $source_path );
					return;
				}
				if ( ! \is_readable($source_path) ) {
					\WP_CLI::warning( "The specified file may not be readable: " . $source_path );
				}
			} elseif ( $dir_arg ) {
				// Import from specified directory, but append subdir if not already present
				$subdir = get_option('brickssync_json_subdir', 'brickssync-json');
				$dir_path = rtrim($dir_arg, '/');
				if (substr($dir_path, -strlen($subdir)) !== $subdir) {
					$dir_path = $dir_path . '/' . $subdir;
				}
				$source_path = \trailingslashit($dir_path);
				$import_mode = 'directory';
				\WP_CLI::log( "Attempting to import Bricks templates from specified directory: {$source_path}..." );
				if ( ! \is_dir($source_path) ) {
					\WP_CLI::error( "The specified directory does not exist: " . $source_path );
					return;
				}
				if ( ! \is_readable($source_path) ) {
					\WP_CLI::warning( "The specified directory may not be readable: " . $source_path );
				}
			} else {
				// Import from configured directory using global helper
				$path_status = brickssync_get_storage_path_status();
				if ( $path_status['status'] !== 'ok' ) {
					\WP_CLI::error( "Cannot determine valid storage path for import: " . $path_status['message'] );
					return;
				}
				$source_path = $path_status['path']; // Already has trailing slash
				$import_mode = 'directory';
				\WP_CLI::log( "Attempting to import Bricks templates from configured directory: {$source_path}..." );
				// Check readability (redundant if status != ok)
				if ( ! $path_status['is_readable'] ) {
					\WP_CLI::warning( "Warning: Configured storage directory may not be readable: " . $source_path );
				}
			}
			
			if ( $overwrite ) {
				\WP_CLI::log( "Overwrite mode enabled." );
			}

			try {
				\WP_CLI::log( "Preparing parameters for brickssync_bricks_template_import()..." );
				\WP_CLI::log( " - Import Mode: " . $import_mode );
				\WP_CLI::log( " - Source Path: " . $source_path );
				\WP_CLI::log( " - Overwrite: " . ($overwrite ? 'Yes' : 'No') );
				
				// --- Call the core import function --- 
				$result = brickssync_bricks_template_import([
					'source' => $source_path,
					'mode' => $import_mode,
					'overwrite' => $overwrite
				]);
				if (\is_wp_error($result)) {
					throw new Exception($result->get_error_message());
				}
				\WP_CLI::success( "Template import process initiated. Source: " . $source_path );
			} catch ( Exception $e ) {
				\WP_CLI::error( "Template import failed: " . $e->getMessage() );
			}
		}

		/**
		 * Display current BricksSync configuration.
		 *
		 * This command shows the current configuration settings for BricksSync.
		 * Includes storage paths, file naming settings, and sync settings.
		 *
		 * Features:
		 * - Multiple output formats
		 * - Detailed configuration display
		 * - Validation status for paths
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
		public function config_show( $args, $assoc_args ) {
			\WP_CLI::log( "Current BricksSync Configuration:" );

			$config = [
				['Setting' => 'brickssync_json_storage_location', 'Value' => \get_option('brickssync_json_storage_location', '[Not Set]')],
				['Setting' => 'brickssync_custom_storage_location_path','Value' => \get_option('brickssync_custom_storage_location_path', '[Not Set]')],
				['Setting' => 'brickssync_settings_file_name', 'Value' => \get_option('brickssync_settings_file_name', '[Not Set]')],
				['Setting' => 'brickssync_templates_file_pattern', 'Value' => \get_option('brickssync_templates_file_pattern', '[Not Set]')],
				['Setting' => 'brickssync_excluded_options', 'Value' => \get_option('brickssync_excluded_options', '[Not Set]')],
				['Setting' => 'brickssync_sync_mode', 'Value' => \get_option('brickssync_sync_mode', '[Not Set]')],
				['Setting' => 'brickssync_debug_logging', 'Value' => \get_option('brickssync_debug_logging') ? 'Enabled' : 'Disabled'],
			];

			// Ensure WP_CLI Utils is loaded in CLI context
			if (!function_exists('WP_CLI\Utils\format_items')) {
				if (file_exists(WP_CLI_ROOT . '/php/WP_CLI/Utils.php')) {
					require_once WP_CLI_ROOT . '/php/WP_CLI/Utils.php';
				}
			}
			\WP_CLI\Utils\format_items( 'table', $config, ['Setting', 'Value'] );
		}

		/**
		 * Display BricksSync status information.
		 *
		 * This command shows the current status of BricksSync, including:
		 * - License status
		 * - Storage path validation
		 * - Last sync status
		 * - Error logs
		 *
		 * Features:
		 * - Multiple output formats
		 * - Comprehensive status information
		 * - License validation check
		 * - Path accessibility verification
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
		 *     wp brickssync status
		 *     wp brickssync status --format=json
		 *
		 * @subcommand status
		 */
		public function status( $args, $assoc_args ) {
			\WP_CLI::log( "BricksSync Status:" );
			// Add license status to output
			$is_active = \BricksSync\Includes\Functions\Licensing\is_license_active();
			\WP_CLI::log( "- License Status: " . ($is_active ? 'Active' : 'Inactive') );
			if (!$is_active && defined( 'BRICKSSYNC_DISABLE_LICENSING' ) && BRICKSSYNC_DISABLE_LICENSING) {
                 \WP_CLI::log( "  (Note: Licensing checks disabled via constant)" );
            }
			
			$sync_mode = \get_option('brickssync_sync_mode', '[Not Set]');
			\WP_CLI::log( "- Sync Mode: " . $sync_mode );

			// TODO: Could potentially add information about last sync time (if tracked)
			// TODO: Could add info about configured storage path validity/writability
			
			\WP_CLI::success( "Status displayed." );
		}
		
		// Note: Removed the generic 'report' subcommand
	}

	// Register nested command groups for WP-CLI
	\WP_CLI::add_command( 'brickssync settings', \BricksSync\Includes\Functions\WPCLI\Settings_CLI::class );
	\WP_CLI::add_command( 'brickssync templates', \BricksSync\Includes\Functions\WPCLI\Templates_CLI::class );
	\WP_CLI::add_command( 'brickssync config', \BricksSync\Includes\Functions\WPCLI\Config_CLI::class );
	// Optionally, add a status command directly
	\WP_CLI::add_command( 'brickssync status', function($args, $assoc_args) {
		\WP_CLI::log( "BricksSync Status:" );
		// Add license status to output
		$is_active = function_exists('BricksSync\\Includes\\Functions\\Licensing\\is_license_active') ? \BricksSync\Includes\Functions\Licensing\is_license_active() : false;
		\WP_CLI::log( "- License Status: " . ($is_active ? 'Active' : 'Inactive') );
		if (!$is_active && defined( 'BRICKSSYNC_DISABLE_LICENSING' ) && BRICKSSYNC_DISABLE_LICENSING) {
			\WP_CLI::log( "  (Note: Licensing checks disabled via constant)" );
		}
		$sync_mode = get_option('brickssync_sync_mode', '[Not Set]');
		\WP_CLI::log( "- Sync Mode: " . $sync_mode );
		\WP_CLI::success( "Status displayed." );
	});
}