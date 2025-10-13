<?php
/**
 * Network Admin Tab: Licensing (SureCart-powered)
 *
 * Allows global license activation for BricksSync on multisite networks.
 * Uses network options and the SureCart SDK for licensing actions.
 *
 * @package BricksSync\Admin\Tabs
 * @since 0.8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Ensure licensing helpers are loaded for mask_license_key
if (!function_exists('BricksSync\Includes\Functions\Licensing\mask_license_key')) {
    include_once dirname(__DIR__) . '/includes/functions/licensing/licensing.php';
}


// Helper to get/set/delete license key and activation ID in network options
function brickssync_get_network_license_data() {
    return [
        'key' => get_site_option('brickssync_license_key', ''),
        'activation_id' => get_site_option('brickssync_activation_id', ''),
    ];
}
function brickssync_set_network_license_data($key, $activation_id) {
    update_site_option('brickssync_license_key', $key);
    update_site_option('brickssync_activation_id', $activation_id);
}
function brickssync_delete_network_license_data() {
    delete_site_option('brickssync_license_key');
    delete_site_option('brickssync_activation_id');
}

// Ensure SureCart Client global is available
if ( ! isset( $GLOBALS['brickssync_surecart_client'] ) || ! is_object( $GLOBALS['brickssync_surecart_client'] ) ) {
    echo '<div class="notice notice-error"><p>' . esc_html__( 'BricksSync Error: SureCart Licensing Client not initialized. Licensing features unavailable.', 'brickssync' ) . '</p></div>';
    return;
}

$client = $GLOBALS['brickssync_surecart_client'];
$license_data = brickssync_get_network_license_data();
$license_key = $license_data['key'];
$activation_id = $license_data['activation_id'];
$status_msg = '';
$status_class = 'error';
$is_active = false;
$license_info = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['brickssync_network_license_action'])) {
    check_admin_referer('brickssync_network_license');
    $action = sanitize_text_field($_POST['brickssync_network_license_action']);
    if ($action === 'activate') {
        $key = sanitize_text_field($_POST['brickssync_license_key'] ?? '');
        if ($key) {
            try {
                $license = $client->license()->retrieve($key);
                if (is_wp_error($license)) throw new Exception($license->get_error_message());
                $activation_result = $client->license()->activate($key);
                if (is_wp_error($activation_result)) throw new Exception($activation_result->get_error_message());
                $activation_id = $client->settings()->activation_id;
                brickssync_set_network_license_data($key, $activation_id);
                $license_key = $key;
                $status_msg = __('License activated network-wide!', 'brickssync');
                $status_class = 'success';
            } catch (Exception $e) {
                $status_msg = __('Activation failed: ', 'brickssync') . $e->getMessage();
            }
        } else {
            $status_msg = __('Please enter a license key.', 'brickssync');
        }
    } elseif ($action === 'deactivate') {
        try {
            $result = $client->license()->deactivate();
            if (is_wp_error($result)) throw new Exception($result->get_error_message());
            brickssync_delete_network_license_data();
            $license_key = '';
            $activation_id = '';
            $status_msg = __('License deactivated network-wide.', 'brickssync');
            $status_class = 'success';
        } catch (Exception $e) {
            $status_msg = __('Deactivation failed: ', 'brickssync') . $e->getMessage();
        }
    }
    // Refresh license data after action
    $license_data = brickssync_get_network_license_data();
    $license_key = $license_data['key'];
    $activation_id = $license_data['activation_id'];
}

// Check license status
if (!empty($license_key) && !empty($activation_id)) {
    try {
        $activation = $client->activation()->get($activation_id);
        if (!is_wp_error($activation) && !empty($activation->id)) {
            $is_active = true;
            $license_info = $client->license()->retrieve($license_key);
        }
    } catch (Exception $e) {
        $is_active = false;
    }
}
?>
<?php
// --- BricksSync Network License Tab (Styled like normal licensing tab) ---
if (!defined('ABSPATH')) exit;

// Ensure licensing helpers are loaded for mask_license_key
if (!function_exists('BricksSync\Includes\Functions\Licensing\mask_license_key')) {
    include_once dirname(__DIR__) . '/includes/functions/licensing/licensing.php';
}


// Prepare status and notices
$notice_html = '';
if (!empty($status_msg)) {
    $notice_html .= '<div class="notice notice-' . esc_attr($status_class) . ' is-dismissible"><p>' . esc_html($status_msg) . '</p></div>';
}

// --- Main Card ---
echo '<div class="brickssync-license-card" style="max-width:540px;margin-top:30px;background:#fff;border:1px solid #e5e5e5;border-radius:8px;padding:32px 36px 28px 36px;box-shadow:0 2px 8px rgba(0,0,0,0.03);">';
    echo '<h2 style="margin-top:0;margin-bottom:18px;font-size:1.55em;">' . esc_html__('BricksSync Network License', 'brickssync') . '</h2>';
    echo $notice_html;
    echo '<form method="post" style="margin-bottom:0;">';
    wp_nonce_field('brickssync_network_license');
    if ($is_active && $license_info) {
        echo '<div style="margin-bottom:16px;">';
        if (isset($license_info->expires_at)) {
            $expiry_date = empty($license_info->expires_at) ? __('Never', 'brickssync') : date_i18n(get_option('date_format'), strtotime($license_info->expires_at));
            echo '<p style="margin:0 0 6px 0;"><strong>' . esc_html__('Expires:', 'brickssync') . '</strong> ' . esc_html($expiry_date) . '</p>';
        }
        if (isset($license_info->max_sites)) {
            echo '<p style="margin:0 0 6px 0;"><strong>' . esc_html__('Activations:', 'brickssync') . '</strong> ' . (($license_info->max_sites == 9999 || $license_info->max_sites === null) ? esc_html__('Unlimited', 'brickssync') : esc_html(($license_info->current_sites ?? '?') . ' / ' . $license_info->max_sites)) . '</p>';
        }
        echo '<p style="margin:0 0 12px 0;color:green;font-weight:bold;">' . esc_html__('License is active for this network.', 'brickssync') . '</p>';
        echo '</div>';
        echo '<button type="submit" name="brickssync_network_license_action" value="deactivate" class="button button-secondary">' . esc_html__('Deactivate License', 'brickssync') . '</button>';
    } else {
        echo '<input type="text" name="brickssync_license_key" placeholder="' . esc_attr__('Enter license key', 'brickssync') . '" style="width:350px;max-width:100%;margin-bottom:12px;" />';
        echo '<button type="submit" name="brickssync_network_license_action" value="activate" class="button button-primary" style="margin-left:8px;">' . esc_html__('Activate License', 'brickssync') . '</button>';
    }
    echo '</form>';
echo '</div>';
