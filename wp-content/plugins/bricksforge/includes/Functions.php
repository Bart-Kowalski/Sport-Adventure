<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Register AJAX actions
add_action('wp_ajax_bricksforge_send_mail', 'bricksforge_send_mail');
add_action('wp_ajax_nopriv_bricksforge_send_mail', 'bricksforge_send_mail');
add_action('wp_ajax_bricksforge_update_option', 'bricksforge_update_option');
add_action('wp_ajax_nopriv_bricksforge_update_option', 'bricksforge_update_option');
add_action('wp_ajax_bricksforge_delete_option', 'bricksforge_delete_option');
add_action('wp_ajax_nopriv_bricksforge_delete_option', 'bricksforge_delete_option');
add_action('wp_ajax_bricksforge_get_calculation_hash', 'bricksforge_get_calculation_hash');
add_action('wp_ajax_nopriv_bricksforge_get_calculation_hash', 'bricksforge_get_calculation_hash');

// From nonce regeneration (@since 2.1.8)
add_action('wp_ajax_bricksforge_regenerate_nonce', 'bricksforge_regenerate_nonce');
add_action('wp_ajax_nopriv_bricksforge_regenerate_nonce', 'bricksforge_regenerate_nonce');

function bricksforge_send_mail($template_id = null, $to = null, $subject = null, $message = null, $headers = '', $attachments = array())
{
    // If we're not ready yet, return.
    if (!did_action('wp') && !wp_doing_ajax()) {
        return;
    }

    // We allow to call this function only if the current user can edit posts OR if the site owner allows to send emails to anyone (own risk) setting the global variable BRICKSFORGE_ALLOW_UNAUTHENTICATED_AJAX_EMAILS to true
    if (!current_user_can('edit_posts') && (!defined('BRICKSFORGE_ALLOW_UNAUTHENTICATED_AJAX_EMAILS') || !BRICKSFORGE_ALLOW_UNAUTHENTICATED_AJAX_EMAILS)) {
        return;
    }

    $confirmed_ajax_call = wp_doing_ajax() && $_POST['nonce'];

    // Check if is an ajax call
    if ($confirmed_ajax_call) {

        if (!isset($_POST['nonce'])) {
            return;
        }

        // Check the nonce
        if (!wp_verify_nonce($_POST['nonce'], 'bricksforge_ajax')) {
            return;
        }

        $template_id = isset($_POST['template']) ? $_POST['template'] : null;
        $to          = isset($_POST['to']) ? $_POST['to'] : get_option('admin_email');
        $subject     = isset($_POST['subject']) ? $_POST['subject'] : "";
        $message     = isset($_POST['message']) ? $_POST['message'] : "";
        $attachments = isset($_POST['attachments']) ? $_POST['attachments'] : array();
    }

    // We prepend the template id to the message as ###BRFTEMPLATEID:ID###
    $message = "###BRFTEMPLATEID:{$template_id}###" . $message;

    // Prepare the email data
    $email_data = array(
        'to'          => $to,
        'subject'     => $subject,
        'message'     => $message,
        'headers'     => $headers,
        'attachments' => $attachments,
    );

    // Send the email using wp_mail function
    $result = wp_mail($email_data['to'], $email_data['subject'], $email_data['message'], $email_data['headers'], $email_data['attachments']);

    if ($confirmed_ajax_call) {
        // Return a response
        wp_send_json_success(array('message' => 'Email sent successfully'));

        wp_die();
    }

    return $result;
}

function bricksforge_update_option()
{
    // If we're not ready yet, return.
    if (!wp_doing_ajax()) {
        return;
    }

    if (!isset($_POST['nonce'])) {
        return;
    }

    // Check the nonce
    if (!wp_verify_nonce($_POST['nonce'], 'bricksforge_ajax')) {
        return;
    }

    // Check for user permissions
    if (!current_user_can('manage_options')) {
        return;
    }

    $option_name  = isset($_POST['option_name']) ? $_POST['option_name'] : false;
    $option_value = isset($_POST['option_value']) ? $_POST['option_value'] : false;

    try {
        if ($option_name && $option_value) {
            $result = update_option($option_name, $option_value);

            // Return a response
            wp_send_json_success(array('message' => get_option($option_name)));
        }
    } catch (Exception $e) {
        // Log the error message
        error_log($e->getMessage());
    }

    wp_die();
}

function bricksforge_delete_option()
{
    // If we're not ready yet, return.
    if (!wp_doing_ajax()) {
        return;
    }

    if (!isset($_POST['nonce'])) {
        return;
    }

    // Check the nonce
    if (!wp_verify_nonce($_POST['nonce'], 'bricksforge_ajax')) {
        return;
    }

    // Check for user permissions
    if (!current_user_can('manage_options')) {
        return;
    }

    $option_name = isset($_POST['option_name']) ? $_POST['option_name'] : false;

    try {
        if ($option_name) {
            $result = delete_option($option_name);

            // Return a response
            wp_send_json_success(array('message' => "Option deleted"));
        }
    } catch (Exception $e) {
        // Log the error message
        error_log($e->getMessage());
    }

    wp_die();
}

function bricksforge_get_calculation_hash()
{
    // If we're not ready yet, return.
    if (!wp_doing_ajax()) {
        return;
    }

    if (!isset($_POST['nonce'])) {
        return;
    }

    // Check the nonce
    if (!wp_verify_nonce($_POST['nonce'], 'bricksforge_ajax')) {
        return;
    }

    $calculation_result = isset($_POST['result']) ? $_POST['result'] : false;

    try {
        if ($calculation_result) {
            // We build a new hash with the BRICKSFORGE_SECRET_KEY
            $calculation_result = hash_hmac('sha256', $calculation_result, BRICKSFORGE_SECRET_KEY);

            // Return a response
            wp_send_json_success(array('hash' => $calculation_result));
        }
    } catch (Exception $e) {
        // Log the error message
        error_log($e->getMessage());
    }

    wp_die();
}

/**
 * Form elmeent: Regenerate nonce
 *
 * @since 1.9.6
 */
function bricksforge_regenerate_nonce()
{
    $nonce = wp_create_nonce('wp_rest');

    // Return a response
    wp_send_json_success(array('nonce' => $nonce));

    wp_die();
}
