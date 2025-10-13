<?php
namespace BricksSync\Includes\Functions\WPCLI;

use WP_CLI;

/**
 * WP-CLI command group for BricksSync template operations.
 */
class Templates_CLI extends \WP_CLI_Command {
    /**
     * List all Bricks templates.
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
    public function list( $args, $assoc_args ) {
        WP_CLI::log( "Listing Bricks templates..." );
        $templates = [];
        if (!class_exists('WP_Query')) {
            require_once ABSPATH . 'wp-includes/class-wp-query.php';
        }
        $query = new \WP_Query([
            'post_type' => 'bricks_template',
            'post_status' => ['publish', 'draft', 'pending', 'private'],
            'posts_per_page' => -1,
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
                    'type' => get_post_meta($template_id, '_bricks_template_type', true),
                ];
            }
            wp_reset_postdata();
        }
        if ( empty($templates) ) {
            WP_CLI::log( "No templates found." );
            return;
        }
        $fields = ['ID', 'title', 'type'];
        $formatter = new \WP_CLI\Formatter( $assoc_args, $fields );
        $formatter->display_items( $templates );
    }

    /**
     * Export Bricks templates to JSON files.
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
    public function export( $args, $assoc_args ) {
        $target_dir_path = $assoc_args['output-dir'] ?? null;
        $template_id = $assoc_args['templates'] ?? null;
        $file_pattern = get_option('brickssync_templates_file_pattern', 'template-{ID}.json');
        $export_args = [];
        if ($target_dir_path) {
            $export_args['target_dir'] = $target_dir_path;
        }
        if ($template_id) {
            $export_args['template_id'] = $template_id;
        }
        $export_args['file_pattern'] = $file_pattern;
        $result = brickssync_bricks_template_export($export_args);
        if (\is_wp_error($result)) {
            throw new \Exception($result->get_error_message());
        }
        $message = $template_id 
            ? "Specific template export initiated." 
            : "All templates export initiated.";
        WP_CLI::success( $message . " Target Dir: " . ($target_dir_path ?: '[default]') );
    }

    /**
     * Import Bricks templates from JSON files.
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
    public function import( $args, $assoc_args ) {
        $source_path = $assoc_args['input-dir'] ?? null;
        $import_mode = 'directory';
        $overwrite = true;
        if (!$source_path) {
            $storage_dir = function_exists('brickssync_get_storage_path') ? brickssync_get_storage_path() : null;
            if ($storage_dir) {
                $source_path = $storage_dir;
            }
        }
        WP_CLI::log( " - Source Path: " . $source_path );
        WP_CLI::log( " - Overwrite: " . ($overwrite ? 'Yes' : 'No') );
        $result = brickssync_bricks_template_import([
            'source' => $source_path,
            'mode' => $import_mode,
            'overwrite' => $overwrite
        ]);
        if (\is_wp_error($result)) {
            throw new \Exception($result->get_error_message());
        }
        WP_CLI::success( "Template import process initiated. Source: " . $source_path );
    }
}
