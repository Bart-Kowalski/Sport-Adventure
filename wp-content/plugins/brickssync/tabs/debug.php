<?php
if (!is_network_admin()) {
    // Get debug status from network option
    $debug_enabled = get_site_option('brickssync_debug_logging', false);
    echo '<div class="brickssync-license-card" style="max-width:540px;margin-top:30px;background:#fff;border:1px solid #e5e5e5;border-radius:8px;padding:32px 36px 28px 36px;box-shadow:0 2px 8px rgba(0,0,0,0.03);">';
    echo '<h2 style="margin-top:0;margin-bottom:18px;font-size:1.55em;">' . esc_html__('BricksSync Debug Status', 'brickssync') . '</h2>';
    echo '<p style="margin:0 0 12px 0;"><strong>' . esc_html__('Debug logging is', 'brickssync') . '</strong> ' . ($debug_enabled ? '<span style="color:green;font-weight:bold;">' . esc_html__('ENABLED', 'brickssync') . '</span>' : '<span style="color:#b00;font-weight:bold;">' . esc_html__('DISABLED', 'brickssync') . '</span>') . '</p>';
    echo '<p>' . esc_html__('Debug logs and settings are managed globally by the Network Administrator.', 'brickssync') . '</p>';
    echo '<p style="margin-top:1em; color:#555;">' . esc_html__('To view or change debug settings, go to the BricksSync settings in the Network Admin.', 'brickssync') . '</p>';
    echo '</div>';
    return;
}
/**
 * Admin Tab: Debug & Logging
 *
 * Handles the BricksSync plugin's debug and logging interface in the WordPress admin.
 * Provides UI for:
 * - Enabling/disabling debug logging
 * - Viewing WordPress debug log contents
 * - Clearing debug logs
 * - Real-time log updates via AJAX
 *
 * Uses AltForms for form handling.
 *
 * @package BricksSync\Admin\Tabs
 * @since 0.1
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Debug Settings Initialization
 *
 * Retrieves current debug logging status and log file contents.
 */
if (is_multisite() && is_network_admin()) {
    $debug_logging_enabled = get_site_option('brickssync_debug_logging', false);
} else {
    $debug_logging_enabled = get_option('brickssync_debug_logging', false);
}
$wp_debug_log = WP_CONTENT_DIR . '/debug.log';
$log_contents = file_exists($wp_debug_log) ? file_get_contents($wp_debug_log) : '';

/**
 * Debug Form Setup
 *
 * Creates and configures the debug settings form using AltForms.
 * The form includes:
 * - Debug logging toggle checkbox
 * - Description of logging functionality
 */
$debug_form = new AltForms('debug_settings_form');
$debug_form->input_checkbox(
    'brickssync_debug_logging',
    __('Enable debug logging', 'brickssync'),
    1,
    $debug_logging_enabled
);
$debug_form->content('<p class="description">' . __('When enabled, BricksSync will write detailed logs to the WordPress debug log (wp-content/debug.log).', 'brickssync') . '</p>');

/**
 * Log Clearing Handler
 *
 * Processes the log clearing request:
 * - Verifies nonce for security
 * - Attempts to clear the log file
 * - Updates success/error messages
 * - Resets log contents if successful
 */
if (isset($_POST['clear_log']) && check_admin_referer('brickssync_clear_log')) {
    if (file_exists($wp_debug_log)) {
        if (file_put_contents($wp_debug_log, '') !== false) {
            $debug_form->success_message(__('Debug log cleared successfully.', 'brickssync'));
            $log_contents = '';
        } else {
            $debug_form->error_message(__('Failed to clear debug log.', 'brickssync'));
        }
    }
}

// Handle the form but don't render it yet
$debug_form->on_submit(function($submitted_data) use (&$debug_logging_enabled, $debug_form) {
    $enabled = !empty($submitted_data['brickssync_debug_logging']) ? 1 : 0;
    if (is_multisite() && is_network_admin()) {
        update_site_option('brickssync_debug_logging', $enabled);
        $debug_logging_enabled = get_site_option('brickssync_debug_logging', false);
    } else {
        update_option('brickssync_debug_logging', $enabled);
        $debug_logging_enabled = get_option('brickssync_debug_logging', false);
    }
    $debug_form->success_message(__('Debug logging setting updated.', 'brickssync'));
});
$debug_form->handle();
// Patch: Force checkbox to reflect network value in network admin
if (is_multisite() && is_network_admin()) {
    if (method_exists($debug_form, 'set_field_value')) {
        $debug_form->set_field_value('brickssync_debug_logging', get_site_option('brickssync_debug_logging', false));
    } else {
        // Fallback: forcibly update the site option as well for UI consistency
        update_option('brickssync_debug_logging', get_site_option('brickssync_debug_logging', false));
    }
}
?>

<div class="wrap">
    <h2 style="margin-top:0;">Debug &amp; Logging</h2>
    <p style="max-width:700px; color:#555;">Enable debug logging to track BricksSync operations, or view and clear the WordPress debug log below. Use this for troubleshooting or support.</p>
    <div style="background: #fffbe5; border: 1px solid #ffe066; padding: 12px 18px; border-radius: 6px; margin-bottom: 18px; max-width: 700px;">
        <strong>Note:</strong> To use the log viewer, you must enable WordPress debug logging.<br>
        Add or edit the following lines in your <code>wp-config.php</code> file (located in your WordPress root directory):
        <pre style="background: #f8f9fa; border: 1px solid #eee; padding: 8px 12px; border-radius: 4px;">define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);</pre>
        After saving, WordPress will log errors and plugin debug output to <code>wp-content/debug.log</code>.
        <br><br>
        <em>Tip: For production sites, remember to turn off debugging when not needed.</em>
    </div>
    <div class="brickssync-section" style="border:1px solid #e5e5e5; border-radius:8px; padding:20px; margin-bottom:28px; background:#fafbfc;">
        <?php $debug_form->render(); ?>
        <hr>
        <h3>WordPress Debug Log Viewer</h3>
        <form method="post" style="margin-bottom: 20px;">
            <?php wp_nonce_field('brickssync_clear_log'); ?>
            <input type="hidden" name="clear_log" value="1">
            <input type="submit" class="button" value="<?php esc_attr_e('Clear Log', 'brickssync'); ?>">
        </form>
        <div class="brickssync-log-viewer" id="brickssync-log-viewer">
            <?php if ($log_contents): ?>
                <pre><?php echo esc_html($log_contents); ?></pre>
            <?php else: ?>
                <p><?php _e('No debug logs available. Make sure WP_DEBUG_LOG is enabled in wp-config.php.', 'brickssync'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/**
 * Log Viewer Styling
 *
 * Styles for the debug log viewer:
 * - Monospace font for better readability
 * - Scrollable container with fixed height
 * - Pre-formatted text with word wrapping
 * - Light background with border
 */
.brickssync-log-viewer {
    background: #f5f5f5;
    border: 1px solid #ddd;
    padding: 10px;
    max-height: 400px;
    overflow-y: auto;
    font-family: monospace;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.brickssync-section { margin-bottom: 32px; }
</style>

<script>
/**
 * Real-time Log Updates
 *
 * Implements real-time log viewing functionality:
 * - Auto-scrolling when at bottom
 * - Periodic updates (every 500ms)
 * - AJAX-based content fetching
 * - Error handling and state management
 */
jQuery(document).ready(function($) {
    let lastLogSize = 0;
    let isScrolledToBottom = true;
    const logViewer = $('#brickssync-log-viewer');
    const pre = logViewer.find('pre');
    let isUpdating = false;
    
    // Check if user is scrolled to bottom
    logViewer.on('scroll', function() {
        isScrolledToBottom = Math.abs($(this)[0].scrollHeight - $(this).scrollTop() - $(this).outerHeight()) < 1;
    });
    
    // Function to update log content
    function updateLog() {
        if (isUpdating) return; // Prevent multiple simultaneous updates
        
        isUpdating = true;
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'brickssync_get_debug_log',
                _ajax_nonce: '<?php echo wp_create_nonce('brickssync_get_debug_log'); ?>'
            },
            success: function(response) {
                if (response.success && response.data) {
                    pre.text(response.data);
                    if (isScrolledToBottom) {
                        logViewer.scrollTop(logViewer[0].scrollHeight);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching debug log:', error);
            },
            complete: function() {
                isUpdating = false;
            }
        });
    }
    
    // Update log every 500ms (half a second)
    setInterval(updateLog, 500);
    
    // Initial update
    updateLog();
});
</script> 