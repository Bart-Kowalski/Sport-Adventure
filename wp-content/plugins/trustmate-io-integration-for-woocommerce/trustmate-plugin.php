<?php

/**
 * @package TrustMatePlugin
 */

/**
 * Plugin Name: TrustMate.io integration for WooCommerce
 * Plugin URI: https://trustmate.io
 * Description: TrustMate.io integration with auto invitations
 * Version: 1.14.0
 * Author: TrustMate.io dev team
 * License: GPLv2 or later
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

include(__DIR__.'/i18n.php');
include(__DIR__.'/css/register_styles.php');
include(__DIR__.'/api.php');
include(__DIR__.'/install_form.php');
include(__DIR__.'/config_form.php');
include(__DIR__.'/widgets.php');
include(__DIR__.'/embed_scripts.php');

const BASE_URL = 'https://trustmate.io';
const BASE_URL_DEV = 'http://trustmate.test';

const TRUSTMATE_PAGE_CREATE_ACCOUNT = 'create_account';
const TRUSTMATE_PAGE_SETUP_ACCOUNT = 'setup_account';
const TRUSTMATE_PAGE_WIDGETS = 'widgets';
const TRUSTMATE_PAGE_RESET_PLUGIN = 'reset_plugin';

const TRUSTMATE_INV_STATUS_DISABLED = '0';
const TRUSTMATE_INV_STATUS_AFTER_ORDER = '1';
const TRUSTMATE_INV_STATUS_AFTER_PAYMENT = '2';
const TRUSTMATE_INV_STATUS_COMPLETED = '3';

if (isset($_GET['page']) && $_GET['page'] === 'trustmate') {
    wp_register_script('5.2.3_bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js');
    wp_enqueue_script('5.2.3_bootstrap');
    wp_register_style('5.2.3_bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
    wp_enqueue_style('5.2.3_bootstrap');
}

add_action('admin_menu', 'trustmate_create_settings_page');
add_action('wp_footer', 'trustmate_render_widget_alpaca');
add_action('wp_footer', 'trustmate_render_widget_badger2');
add_action('wp_footer', 'trustmate_render_widget_muskrat2');
add_action('wp_footer', 'trustmate_render_widget_bee');
add_action('wp_footer', 'trustmate_render_widget_lemur');
add_action('woocommerce_before_add_to_cart_form', 'trustmate_render_widget_hornet');
add_action('woocommerce_after_shop_loop_item_title', 'trustmate_insert_hornet_wrappers');
add_action('wp_footer', 'trustmate_render_widget_hornets');
add_action('get_footer', 'trustmate_render_widget_chupacabra');
add_action('get_footer', 'trustmate_render_widget_ferret2');
add_action('get_footer', 'trustmate_render_widget_product_ferret2');
add_action('get_footer', 'trustmate_render_widget_hydra');
add_action('get_footer', 'trustmate_render_widget_owl');
add_action('admin_head', 'save_widget_status');
add_action('init', 'plugin_load_textdomain');


function plugin_load_textdomain()
{
    load_plugin_textdomain('trustmate', false, basename(dirname(__FILE__)) . '/languages/');
}

function save_widget_status()
{
    ?>
<script type="text/javascript" >
    jQuery(document).ready(function($) {
        function customAlert(msg) {
            const widgetsWrapper = document.querySelector('.tm-widgets-container');
            const alertBox = document.querySelector('.alert-wrapper');
            if(!alertBox) {
                const alertWrapper = document.createElement('div');
                alertWrapper.classList.add('alert-wrapper')
                alertWrapper.textContent = msg;
                widgetsWrapper.appendChild(alertWrapper)
            }

            const alertBoxHide = setTimeout(() => {
                const alertBox = document.querySelector('.alert-wrapper');
                if(alertBox) {
                    alertBox.remove()
                }
            }, 3000);
        }

        $(".toggle-switch input").click(function(){
            var nonce = $('meta[name="csrf-token"]').attr('content');
            var trigger_id = $(this).attr('id');
            var checkboxValue = $('#'+trigger_id).is(":checked") ? 1 : 0;
            jQuery.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {action: "save_checkbox", checked: checkboxValue, optionName: trigger_id},
                headers: {'X-CSRF-TOKEN': nonce}
            }).done(function(data){
                if ($.parseJSON(data).status == "error") {
                    customAlert(`${$.parseJSON(data).message}`);
                    $('#'+trigger_id).prop('checked', !checkboxValue);
                }
            });
        })
    });
</script>
<?php
}

function save_checkbox()
{
    if (!current_user_can('activate_plugins')) {
        echo json_encode(array(
            "status" => "error",
            "message" => "Access denied, missing 'activate plugins' permission",
        ));

        wp_die();
    }

    trustmate_verify_general_nonce();
    $checkboxValue = sanitize_text_field($_POST['checked']);
    $optionName = sanitize_text_field($_POST['optionName']);

    if (strpos($optionName, 'trustmate_widget_') !== 0) {
        echo json_encode(array(
            "status" => "error",
            "message" => "Invalid option",
        ));

        wp_die();
    }

    update_option($optionName, $checkboxValue);
    trustmate_papi_install();

    echo json_encode(array(
        "status" => "ok",
        "message" => "Widget status saved",
    ));

    wp_die();
}

add_action('wp_ajax_save_checkbox', 'save_checkbox');

function trustmate_create_settings_page()
{
    add_menu_page(
        'TrustMate.io - settings',
        'TrustMate.io',
        'manage_options',
        'trustmate',
        'trustmate_view_dispatcher',
        'dashicons-star-filled',
        100
    );
    add_options_page('TrustMate', 'Plugin Menu', 'manage_options', 'trustmate', 'trustmate_view_dispatcher');
    register_setting('trustmate_basic_settings', 'trustmate_invitations_enabled', array('default' => 1));
    register_setting('trustmate_basic_settings', 'trustmate_account_uuid');
    register_setting('trustmate_basic_settings', 'trustmate_account_language_uuids');
    register_setting('trustmate_basic_settings', 'trustmate_instant_review');
    register_setting('trustmate_basic_settings', 'trustmate_base_url');
    register_setting('trustmate_widget_settings', 'trustmate_widget_gorilla');
    register_setting('trustmate_widget_settings', 'trustmate_widget_hydra');
    register_setting('trustmate_widget_settings', 'trustmate_widget_muskrat');
    register_setting('trustmate_widget_settings', 'trustmate_widget_muskrat2');
    register_setting('trustmate_widget_settings', 'trustmate_widget_bee');
    register_setting('trustmate_widget_settings', 'trustmate_widget_badger');
    register_setting('trustmate_widget_settings', 'trustmate_widget_badger2');
    register_setting('trustmate_widget_settings', 'trustmate_widget_alpaca');
    register_setting('trustmate_widget_settings', 'trustmate_widget_lemur');
    register_setting('trustmate_widget_settings', 'trustmate_widget_chupacabra');
    register_setting('trustmate_widget_settings', 'trustmate_widget_ferret');
    register_setting('trustmate_widget_settings', 'trustmate_widget_ferret2');
    register_setting('trustmate_widget_settings', 'trustmate_widget_product_ferret');
    register_setting('trustmate_widget_settings', 'trustmate_widget_product_ferret2');
    register_setting('trustmate_widget_settings', 'trustmate_widget_owl', array('default' => 1));
    register_setting('trustmate_widget_settings', 'trustmate_widget_hornet');
}

function trustmate_view_dispatcher()
{
    $uuid_set = (bool) get_option('trustmate_account_uuid');
    $action = null;

    if (isset($_GET['action'])) {
        $action = sanitize_text_field($_GET['action']);
    }

    ?><nav class="nav-tab-wrapper">
        <?php if (!$uuid_set): ?>
        <a href="?page=trustmate" class="nav-tab <?php if ($action === null): ?>nav-tab-active<?php endif ?>">
            <?php echo trustmate_tr('Create TrustMate account') ?>
        </a>
        <?php endif ?>
        <a href="?page=trustmate&action=setup_account" class="nav-tab <?php if ($action === TRUSTMATE_PAGE_SETUP_ACCOUNT): ?>nav-tab-active<?php endif ?>">
            <?php echo trustmate_tr('Settings') ?>
        </a>
        <a href="?page=trustmate&action=widgets" class="nav-tab <?php if ($action === TRUSTMATE_PAGE_WIDGETS): ?>nav-tab-active<?php endif ?>">
            <?php echo trustmate_tr('Widgets') ?>
        </a>
    </nav><?php

    if ($action === TRUSTMATE_PAGE_CREATE_ACCOUNT) {
        trustmate_create_account();
        trustmate_papi_install();
        return;
    }

    if ($action === TRUSTMATE_PAGE_SETUP_ACCOUNT) {
        trustmate_render_config_form();
        return;
    }

    if ($action === TRUSTMATE_PAGE_RESET_PLUGIN) {
        update_option('trustmate_account_uuid', null);
        update_option('trustmate_account_language_uuids', null);
        trustmate_render_setup_choice();
        return;
    }

    if ($action === TRUSTMATE_PAGE_WIDGETS) {
        trustmate_render_widgets();
        return;
    }

    if (!$uuid_set) {
        trustmate_render_setup_choice();
        return;
    }

    trustmate_render_config_form();
}

function trustmate_get_api_base_url()
{
    if (get_option('trustmate_base_url')) {
        return get_option('trustmate_base_url');
    }

    if ($_SERVER['HTTP_HOST'] === 'localhost:8000') {
        return BASE_URL_DEV;
    }

    return BASE_URL;
}

function trustmate_create_account()
{
    $params = array(
        'url' => isset($_POST['url']) ? sanitize_text_field($_POST['url']) : null,
        'name' => isset($_POST['name']) ? sanitize_text_field($_POST['name']) : null,
        'email' => isset($_POST['email']) ? sanitize_text_field($_POST['email']) : null,
        'street' => isset($_POST['street']) ? sanitize_text_field($_POST['street']) : null,
        'city' => isset($_POST['city']) ? sanitize_text_field($_POST['city']) : null,
        'zip_code' => isset($_POST['zip_code']) ? sanitize_text_field($_POST['zip_code']) : null,
        'country' => isset($_POST['country']) ? sanitize_text_field($_POST['country']) : null,
        'nip' => isset($_POST['nip']) ? sanitize_text_field($_POST['nip']) : null,
        'source' => 'woocommerce',
    );

    if ($partner_token = get_option('trustmate_partner_token')) {
        $params['partnerToken'] = $partner_token;
    }

    $response = trustmate_api_create_account($params);

    $setup_message_format = "<p><a href='%s' class='tm-button'>%s</a></p>";
    $setup_message = sprintf(
        $setup_message_format,
        add_query_arg('action', TRUSTMATE_PAGE_SETUP_ACCOUNT),
        trustmate_tr('Configure invitations and widgets')
    );

    if (is_wp_error($response)) {
        ?>
        <div class='notice notice-error'>
            <p>
                <?php echo trustmate_tr('Something went wrong. Please try again or contact TrustMate customer service') ?>
            </p>
        </div>
        <?php

        return;
    }

    if ($response['response']['code'] == '200') {
        $result = json_decode($response['body']);
        update_option('trustmate_account_uuid', $result->uuid);

        ?>
            <div class='notice notice-success'>
                <p>
                    <?php echo trustmate_tr('Account created') ?>.
                    <?php echo trustmate_tr('Please check your e-mail to set password for TrustMate account') ?>.
                    <?php echo wp_kses_post($setup_message) ?>
                </p>
            </div>
        <?php

        return;
    }

    if ($response['response']['code'] == '409') {
        ?>
            <div class='notice notice-warning'>
                <p>
                    <?php echo trustmate_tr('Account already exists') ?>
                    <?php echo wp_kses_post($setup_message) ?>
                </p>
            </div>
        <?php

        return;
    }

    if ($response['response']['code'] == '422') {
        ?>
            <div class='notice notice-error'>
                <p>
                    <?php echo trustmate_tr('Invalid data sent') ?>
                    <?php echo wp_kses_post($setup_message) ?>
                </p>
            </div>
        <?php

        return;
    }

    ?>
    <div class='notice notice-error'>
        <p>
            <?php echo trustmate_tr('Unexpected error') ?>
            <?php echo wp_kses_post($setup_message) ?>
        </p>
    </div>
    <?php
}

function trustmate_invitation_after_order($order_id)
{
    if (get_option('trustmate_invitations_enabled') !== TRUSTMATE_INV_STATUS_AFTER_ORDER) {
        return;
    }

    $language = trustmate_get_order_language($order_id);
    trustmate_create_invitation($order_id, $language);
};
add_action('woocommerce_checkout_order_processed', 'trustmate_invitation_after_order');


function trustmate_invitation_after_payment($order_id)
{
    if (get_option('trustmate_invitations_enabled') !== TRUSTMATE_INV_STATUS_AFTER_PAYMENT) {
        return;
    }

    $language = trustmate_get_order_language($order_id);
    trustmate_create_invitation($order_id, $language);
};
add_action('woocommerce_payment_complete', 'trustmate_invitation_after_payment');


function trustmate_invitation_after_order_completed($order_id)
{
    if (get_option('trustmate_invitations_enabled') !== TRUSTMATE_INV_STATUS_COMPLETED) {
        return;
    }

    $language = trustmate_get_order_language($order_id);
    trustmate_create_invitation($order_id, $language);
}
add_action('woocommerce_order_status_completed', 'trustmate_invitation_after_order_completed');


function trustmate_get_order_language($order_id)
{
    if (function_exists('pll_get_post_language')) {
        return pll_get_post_language($order_id);
    }

    if (class_exists('SitePress')) {
        $language_details = apply_filters('wpml_post_language_details', null, $order_id);
        if (isset($language_details['language_code'])) {
            return $language_details['language_code'];
        }
    }

    return null;
}

function trustmate_add_nonce()
{
    $nonce = wp_create_nonce('trustmate_general_nonce');
    echo "<meta name='csrf-token' content='$nonce'>";
}
add_action('admin_head', 'trustmate_add_nonce');


function trustmate_verify_general_nonce()
{
    $nonce = isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? $_SERVER['HTTP_X_CSRF_TOKEN'] : '';
    if (!wp_verify_nonce($nonce, 'trustmate_general_nonce')) {
        wp_die();
    }
}

function trustmate_instant_review($order_id)
{
    $order = wc_get_order($order_id);
    ?>
  <script>
    TRUST_MATE_USER_NAME = '<?php echo $order->get_billing_first_name() ?>';
    TRUST_MATE_USER_EMAIL = '<?php echo $order->get_billing_email() ?>';
    TRUST_MATE_ORDER_NUMBER = '<?php echo $order->get_order_number() ?>';
    TRUST_MATE_COMPANY_UUID = '<?php echo trustmate_get_current_uuid() ?>';
  </script>
  <script defer type="text/javascript" src='<?php echo trustmate_get_api_base_url() ?>/api/invitation/script'></script>
<?php
}

if (get_option('trustmate_instant_review')) {
    add_action('woocommerce_thankyou', 'trustmate_instant_review');
}

add_action('update_option_trustmate_instant_review', function ($old_value, $value, $option) {
    trustmate_update_settings($value);
    trustmate_papi_install();
}, 10, 3);

add_action('update_option_trustmate_invitations_enabled', function ($old_value, $value, $option) {
    if ($old_value != $value) {
        trustmate_papi_install();
    }
}, 10, 3);

add_action('upgrader_process_complete', 'trustmate_on_plugin_update', 20, 3);
function trustmate_on_plugin_update($upgrader_object, $options)
{
    $current_plugin = plugin_basename(__FILE__);

    if ($options['action'] == 'update' && $options['type'] == 'plugin') {
        foreach ($options['plugins'] as $plugin) {
            if ($plugin == $current_plugin) {
                trustmate_papi_install();

                // upgrade widget enabled status
                if (!get_option('trustmate_widget_hydra') && get_option('trustmate_widget_gorilla')) {
                    update_option('trustmate_widget_hydra', 1);
                    update_option('trustmate_widget_gorilla', 0);
                }
                if (!get_option('trustmate_widget_muskrat2') && get_option('trustmate_widget_muskrat')) {
                    update_option('trustmate_widget_muskrat2', 1);
                    update_option('trustmate_widget_muskrat', 0);
                }
                if (!get_option('trustmate_widget_badger2') && get_option('trustmate_widget_badger')) {
                    update_option('trustmate_widget_badger2', 1);
                    update_option('trustmate_widget_badger', 0);
                }
                if (!get_option('trustmate_widget_ferret2') && get_option('trustmate_widget_ferret')) {
                    update_option('trustmate_widget_ferret2', 1);
                    update_option('trustmate_widget_ferret', 0);
                }
                if (!get_option('trustmate_widget_product_ferret2') && get_option('trustmate_widget_product_ferret')) {
                    update_option('trustmate_widget_product_ferret2', 1);
                    update_option('trustmate_widget_product_ferret', 0);
                }
            }
        }
    }
}

function defer_widget_js($html)
{
    if (is_admin()) {
        return $html;
    }

    if (strpos($html, trustmate_get_api_base_url().'/platforms/widget/') !== false) {
        return str_replace('></script>', ' defer></script>', $html);
    }

    return $html;
}
add_filter('script_loader_tag', 'defer_widget_js', 10);

function trustmate_uninstall()
{
    trustmate_papi_uninstall();
}
register_uninstall_hook(__FILE__, 'trustmate_uninstall');
