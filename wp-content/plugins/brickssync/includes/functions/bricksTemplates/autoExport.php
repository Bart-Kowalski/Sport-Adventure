<?php
/**
 * Auto Export Bricks Template on Save
 *
 * Exports a Bricks template to JSON when it is saved, if sync_mode is 'automatic' or 'export'.
 *
 * @since 0.1
 */

add_action('save_post_bricks_template', function($post_id, $post, $update) {
    if (!function_exists('BricksSync\\Includes\\Functions\\Debug\\brickssync_log')) {
        require_once dirname(__DIR__, 2) . '/debug/debug.php';
    }
    \BricksSync\Includes\Functions\Debug\brickssync_log("Triggered save_post_bricks_template for post_id=$post_id", 'debug', ['post_id' => $post_id, 'update' => $update]);
    // Only run for actual updates, not autosaves or revisions
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        \BricksSync\Includes\Functions\Debug\brickssync_log("Aborted: DOING_AUTOSAVE is set for post_id=$post_id", 'debug');
        return;
    }
    if (wp_is_post_revision($post_id)) {
        \BricksSync\Includes\Functions\Debug\brickssync_log("Aborted: post_id=$post_id is a revision", 'debug');
        return;
    }
    if (wp_is_post_autosave($post_id)) {
        \BricksSync\Includes\Functions\Debug\brickssync_log("Aborted: post_id=$post_id is an autosave", 'debug');
        return;
    }
    if (empty($post) || $post->post_type !== 'bricks_template') {
        \BricksSync\Includes\Functions\Debug\brickssync_log("Aborted: post is empty or not bricks_template for post_id=$post_id", 'debug');
        return;
    }

    // Get sync mode from effective config (network/group/site override aware)
    if (function_exists('is_multisite') && is_multisite() && !is_network_admin()) {
        if (!function_exists('brickssync_get_effective_site_config')) {
            require_once dirname(__DIR__, 2) . '/functions/network_config.php';
        }
        $site_id = get_current_blog_id();
        $eff = brickssync_get_effective_site_config($site_id);
        $sync_mode = $eff['sync_mode'] ?? get_option('brickssync_sync_mode');
        \BricksSync\Includes\Functions\Debug\brickssync_log("Sync mode (effective, multisite) for post_id=$post_id is $sync_mode", 'debug', ['effective_config' => $eff]);
    } else {
        $sync_mode = get_option('brickssync_sync_mode');
        \BricksSync\Includes\Functions\Debug\brickssync_log("Sync mode (single site) for post_id=$post_id is $sync_mode", 'debug');
    }
    if (!in_array($sync_mode, ['automatic', 'export'])) {
        \BricksSync\Includes\Functions\Debug\brickssync_log("Aborted: sync_mode is not automatic or export for post_id=$post_id", 'debug');
        return;
    }

    if (!function_exists('brickssync_bricks_template_export')) {
        \BricksSync\Includes\Functions\Debug\brickssync_log("Aborted: brickssync_bricks_template_export function does not exist for post_id=$post_id", 'error');
        return;
    }

    \BricksSync\Includes\Functions\Debug\brickssync_log("Calling brickssync_bricks_template_export for post_id=$post_id", 'info');
    // Export this template only
    brickssync_bricks_template_export(['template_id' => $post_id]);
}, 10, 3);
