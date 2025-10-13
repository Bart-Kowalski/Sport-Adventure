<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

add_action('brf_backup_classes', [new GlobalClasses(true), 'create_global_classes_backup']);

/**
 * Global Classes Handler
 */
class GlobalClasses
{

    public function __construct($passive = false)
    {
        if ($passive) {
            return;
        }

        add_action('wp_enqueue_scripts', [$this, 'load_styles']);
        $this->backup_global_classes();
    }

    public function activated()
    {
        return get_option('brf_global_classes_activated') == true;
    }

    public function load_styles()
    {
        if ($this->activated() === false) {
            return;
        }

        if (bricks_is_frontend() || bricks_is_builder_iframe()) {
            if (!file_exists(BRICKSFORGE_CUSTOM_STYLES_FILE)) {
                if (!is_dir(BRICKSFORGE_CUSTOM_STYLES_DIR)) {
                    mkdir(BRICKSFORGE_CUSTOM_STYLES_DIR, 0755, true);
                }

                touch(BRICKSFORGE_CUSTOM_STYLES_FILE);
                $this->render_custom_styles();
            }

            wp_register_style('bricksforge-custom', BRICKSFORGE_CUSTOM_STYLES_URL, false, time());
            wp_enqueue_style('bricksforge-custom');
        }
    }

    public function render_custom_styles()
    {
        $classes = get_option('brf_global_classes');

        if (!$classes || !count($classes)) {
            return;
        }

        if (class_exists('Bricksforge\Api\Bricksforge')) {
            (new Api\Bricksforge)->render_css_files($classes);
        }
    }

    public function backup_global_classes()
    {

        if (!get_option('brf_activated_tools') || !in_array(10, get_option('brf_activated_tools'))) {
            $this->clear_backup_schedule();
            return;
        }

        if (!get_option('brf_tool_settings')) {
            $this->clear_backup_schedule();
            return;
        }

        // Get get_option('brf_tool_settings') (object) with the key "id" equal to 10
        $backup_settings = array_filter(get_option('brf_tool_settings'), function ($tool) {
            return $tool->id == 10;
        });

        if (count($backup_settings) == 0) {
            $this->clear_backup_schedule();
            return;
        }

        // Reset indexes
        $backup_settings = array_values($backup_settings);
        $settings_backup_interval = isset($backup_settings[0]->settings->backupInterval) && $backup_settings[0]->settings->backupInterval ? $backup_settings[0]->settings->backupInterval : 'daily';

        if ($settings_backup_interval == 'manual') {
            // Remove the scheduled event
            wp_clear_scheduled_hook('brf_backup_classes');
            return;
        }

        if (wp_get_scheduled_event('brf_backup_classes') && wp_get_scheduled_event('brf_backup_classes')->schedule != $settings_backup_interval) {
            wp_clear_scheduled_hook('brf_backup_classes');
        }

        if (!wp_next_scheduled('brf_backup_classes')) {
            wp_schedule_event(time(), $settings_backup_interval, 'brf_backup_classes');
        }
    }

    public function clear_backup_schedule()
    {
        if (wp_get_scheduled_event('brf_backup_classes')) {
            wp_clear_scheduled_hook('brf_backup_classes');
        }
    }

    public function restore_global_classes($backup_id)
    {

        if (!$backup_id) {
            return false;
        }

        $backups = get_option('brf_classes_backups', array());
        $backup = array_filter($backups, function ($backup) use ($backup_id) {
            return $backup['id'] == $backup_id;
        });

        if (count($backup) == 0) {
            return false;
        }

        $backup = array_values($backup);
        $backup = $backup[0];

        update_option('bricks_global_classes', $backup['bricks_global_classes']);
        update_option('bricks_global_classes_locked', $backup['bricks_global_classes_locked']);

        return true;
    }

    /**
     * Create a backup of the global classes
     * @return array The new backup
     * @since 0.9.6
     */
    public function create_global_classes_backup()
    {
        // Get get_option('brf_tool_settings') (object) with the key "id" equal to 10
        $backup_settings = array_filter(get_option('brf_tool_settings'), function ($tool) {
            return $tool->id == 10;
        });

        // Reset indexes
        $backup_settings = array_values($backup_settings);
        $settings_backup_interval = isset($backup_settings[0]->settings->backupInterval) && $backup_settings[0]->settings->backupInterval ? $backup_settings[0]->settings->backupInterval : 'daily';
        $settings_backup_amount = isset($backup_settings[0]->settings->backupAmount) && $backup_settings[0]->settings->backupAmount ? $backup_settings[0]->settings->backupAmount : 5;

        $original_option_1 = get_option('bricks_global_classes');
        $original_option_2 = get_option('bricks_global_classes_locked');
        $backup_count = $settings_backup_amount;
        $all_options = \wp_load_alloptions();
        $existing_backups = get_option('brf_classes_backups', array());

        // Delete oldest backups until the desired backup count is reached
        while (count($existing_backups) >= $backup_count) {
            array_shift($existing_backups);
        }

        // Create a new backup
        $new_backup = array(
            'id' => uniqid(),
            'title' => 'Backup - ' . date_i18n(get_option('date_format') . ' ' . get_option('time_format'), time()),
            'timestamp' => time(),
            'time' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), time(), true),
            'bricks_global_classes' => $original_option_1,
            'bricks_global_classes_locked' => $original_option_2,
            'bricks_version' => BRICKS_VERSION,
        );
        array_push($existing_backups, $new_backup);

        // Save the updated backups list
        update_option('brf_classes_backups', $existing_backups);

        return $new_backup;
    }
}
