<?php
namespace Advanced_Themer_Bricks;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__license {
	
    static $prefix = '';

    /**
     * Initialize the updater. Hooked into `init` to work with the
     * wp_version_check cron job, which allows auto-updates.
     */
    public static function brxc_plugin_updater() {
        

        // To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
        $doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
        if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
            return;
        }

        // retrieve our license key from the DB
        $license_key = trim( get_option( 'brxc_license_key' ) );

        // setup the updater
        $edd_updater = new BRXC_SL_Plugin_Updater(
            \BRXC_STORE_URL,
            \BRICKS_ADVANCED_THEMER_PLUGIN_FILE,
            array(
                'version' => \BRICKS_ADVANCED_THEMER_VERSION,
                'license' => $license_key,
                'item_id' => \BRXC_ITEM_ID,
                'author'  => \BRXC_EDD_AUTHOR,
                'beta'    => false,
            )
        );

    }


    /************************************
    * the code below is just a standard
    * options page. Substitute with
    * your own.
    *************************************/

    /**
     * Adds the plugin license page to the admin menu.
     *
     * @return void
     */
    public static function brxc_license_menu() {
        if (!AT__Helpers::return_user_role_check() === true) {
            
            return;
        };
        add_submenu_page(
            'bricks',
            esc_html__( 'AT - License', 'bricks' ),
			esc_html__( 'AT - License', 'bricks' ),
            'manage_options',
            'at-license',
            'Advanced_Themer_Bricks\AT__License::brxc_license_page',
            8
        );
    }

    public static function brxc_license_page() {

        if (!AT__Helpers::return_user_role_check() === true) {
            
            return;
        };

        add_settings_section(
            'brxc_license',
            '',
            'Advanced_Themer_Bricks\AT__License::brxc_license_key_settings_section',
            \BRXC_PLUGIN_LICENSE_PAGE
        );
        add_settings_field(
            'brxc_license_key',
            '',
            'Advanced_Themer_Bricks\AT__License::brxc_license_key_settings_field',
            \BRXC_PLUGIN_LICENSE_PAGE,
            'brxc_license'
        );
        ?>
        <div class="wrap">
            <h2><?php esc_html_e( 'Advanced Themer License' ); ?></h2>
            <form method="post" action="options.php">

                <?php
                do_settings_sections( \BRXC_PLUGIN_LICENSE_PAGE );
                settings_fields( 'brxc_license' );
                submit_button();
                ?>

            </form>
        <?php
    }

    /**
     * Adds content to the settings section.
     *
     * @return void
     */
    public static function brxc_license_key_settings_section() {
        //esc_html_e( 'This is where you enter your license key.' );
    }

    /**
     * Outputs the license key settings field.
     *
     * @return void
     */
    public static function brxc_license_key_settings_field() {
        $license = get_option( 'brxc_license_key' );
        $status  = get_option( 'brxc_license_status' );

        ?>
        <p class="description">
            <strong>Enter your license key.</strong> You can manage your license, update your payment method and view your invoices right from <a href="https://advancedthemer.com/checkout/order-history/">your account.</a>
        </p>
        <?php
        if (isset($status) && $status){
            printf(
                '<input type="password" class="regular-text" id="brxc_license_key" name="brxc_license_key" value="*****************************" />',
                esc_attr( $license )
            );
        } else {
            printf(
                '<input type="password" class="regular-text" id="brxc_license_key" name="brxc_license_key" />',
                esc_attr( $license )
            );
        }
        $button = array(
            'name'  => 'brxc_license_deactivate',
            'label' => __( 'Deactivate License' ),
        );
        if ( 'valid' !== $status ) {
            $button = array(
                'name'  => 'brxc_license_activate',
                'label' => __( 'Activate License' ),
            );
        }
        wp_nonce_field( 'brxc_nonce', 'brxc_nonce' );
        ?>
        <input type="submit" class="button-secondary" name="<?php echo esc_attr( $button['name'] ); ?>" value="<?php echo esc_attr( $button['label'] ); ?>"/>
        <div class="license-status">
            <p>Status: <?php self::brxc_check_license()?></p>
        </div>
        <?php
    }

    /**
     * Registers the license key setting in the options table.
     *
     * @return void
     */
    public static function brxc_register_option() {
        register_setting( 'brxc_license', 'brxc_license_key', 'Advanced_Themer_Bricks\AT__License::brxc_sanitize_license' );
    }

    /**
     * Sanitizes the license key.
     *
     * @param string  $new The license key.
     * @return string
     */
    public static function brxc_sanitize_license( $new ) {
        $old = get_option( 'brxc_license_key' );
        if ( $old && $old !== $new ) {
            delete_option( 'brxc_license_status' ); // new license has been entered, so must reactivate
            delete_option( 'brxc_license_date_created' );
        }

        return sanitize_text_field( $new );
    }

    /**
     * Activates the license key.
     *
     * @return void
     */
    public static function brxc_activate_license() {

        // listen for our activate button to be clicked
        if ( ! isset( $_POST['brxc_license_activate'] ) ) {
            return;
        }

        // run a quick security check
        if ( ! check_admin_referer( 'brxc_nonce', 'brxc_nonce' ) ) {
            return; // get out if we didn't click the Activate button
        }

        // retrieve the license from the database
        $license = trim( get_option( 'brxc_license_key' ) );
        if ( ! $license ) {
            $license = ! empty( $_POST['brxc_license_key'] ) ? sanitize_text_field( $_POST['brxc_license_key'] ) : '';
        }
        if ( ! $license ) {
            return;
        }

        // data to send in our API request
        $api_params = array(
            'edd_action'  => 'activate_license',
            'license'     => $license,
            'item_id'     => \BRXC_ITEM_ID,
            'item_name'   => rawurlencode( \BRXC_ITEM_NAME ), // the name of our product in EDD
            'url'         => home_url(),
            'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
        );

        // Call the custom API.
        $response = wp_remote_post(
            \BRXC_STORE_URL,
            array(
                'timeout'   => 15,
                'sslverify' => false,
                'body'      => $api_params,
            )
        );

        $error_code = 0;
        // Make sure the response came back okay
        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

            if ( is_wp_error( $response ) ) {
                $message = $response->get_error_message();
            } else {
                $response_code = wp_remote_retrieve_response_code( $response );
                $error_code = $response_code;
                
                $message = sprintf(
                    // Translators: %d: HTTP response code
                    __( 'An error occurred (HTTP code %d).', 'edd-sample-plugin' ),
                    $response_code
                );
                
            }
        } else {

            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            if ( false === $license_data->success ) {

                switch ( $license_data->error ) {

                    case 'expired':
                        $message = sprintf(
                            /* translators: the license key expiration date */
                            __( 'Your license key expired on %s.', 'edd-sample-plugin' ),
                            date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                        );
                        break;

                    case 'disabled':
                    case 'revoked':
                        $message = __( 'Your license key has been disabled.', 'edd-sample-plugin' );
                        break;

                    case 'missing':
                        $message = __( 'Invalid license - Key is missing.', 'edd-sample-plugin' );
                        break;
                    case 'missing_url':
                        $message = __( 'Invalid license - Missing URL.', 'edd-sample-plugin' );
                        break;
                    case 'license_not_activable':
                        $message = __( 'Bundle license can\'t be activated.', 'edd-sample-plugin' );
                        break;
                    case 'key_mismatch':
                        $message = __( 'Invalid license - Keys don\'t match.', 'edd-sample-plugin' );
                        break;
                    case 'invalid':
                    case 'site_inactive':
                        $message = __( 'Your license is not active for this URL.', 'edd-sample-plugin' );
                        break;
                    case 'invalid_item_id':
                        $message = sprintf( __( 'This appears to be an invalid license ID for %s.', 'edd-sample-plugin' ), \BRXC_ITEM_NAME );
                        break;
                    case 'item_name_mismatch':
                        $message = sprintf( __( 'This appears to be an invalid license key for %s.', 'edd-sample-plugin' ), \BRXC_ITEM_NAME );
                        break;

                    case 'no_activations_left':
                        $message = __( 'Your license key has reached its activation limit.', 'edd-sample-plugin' );
                        break;

                    default:
                        $message = __( 'An error occurred, please try again.', 'edd-sample-plugin' );
                        break;
                }
            }
        }

            // Check if anything passed on a message constituting a failure
        if ( ! empty( $message ) ) {
            $redirect = add_query_arg(
                array(
                    'page'          => \BRXC_PLUGIN_LICENSE_PAGE,
                    'sl_activation' => 'false',
                    'message'       => rawurlencode( $message ),
                    'nonce'		    => wp_create_nonce( self::$prefix . 'license_nonce' ),
                    'error_code'    => $error_code,
                ),
                admin_url( 'admin.php' )
            );

            wp_safe_redirect( $redirect );
            exit();
        }

        // $license_data->license will be either "valid" or "invalid"
        if ( 'valid' === $license_data->license ) {
            update_option( 'brxc_license_key', $license );
        }
        update_option( 'brxc_license_status', $license_data->license );
        update_option( 'brxc_license_date_created', $license_data->date_created );
        wp_safe_redirect( admin_url( 'admin.php?page=' . \BRXC_PLUGIN_LICENSE_PAGE ) );
        exit();
    }

    /**
     * Deactivates the license key.
     * This will decrease the site count.
     *
     * @return void
     */
    public static function brxc_deactivate_license() {

        // listen for our activate button to be clicked
        if ( isset( $_POST['brxc_license_deactivate'] ) ) {

            // run a quick security check
            if ( ! check_admin_referer( 'brxc_nonce', 'brxc_nonce' ) ) {
                return; // get out if we didn't click the Activate button
            }

            // retrieve the license from the database
            $license = trim( get_option( 'brxc_license_key' ) );

            // data to send in our API request
            $api_params = array(
                'edd_action'  => 'deactivate_license',
                'license'     => $license,
                'item_id'     => \BRXC_ITEM_ID,
                'item_name'   => rawurlencode( \BRXC_ITEM_NAME ), // the name of our product in EDD
                'url'         => home_url(),
                //'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
            );

            // Call the custom API.
            $response = wp_remote_post(
                \BRXC_STORE_URL,
                array(
                    'timeout'   => 15,
                    'sslverify' => false,
                    'body'      => $api_params,
                )
            );

            // make sure the response came back okay
            if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

                $error_code = 0;

                if ( is_wp_error( $response ) ) {
                    $message = $response->get_error_message();
                } else {
                    $response_code = wp_remote_retrieve_response_code( $response );
                    $error_code = $response_code;
                    
                    $message = sprintf(
                        // Translators: %d: HTTP response code
                        __( 'An error occurred (HTTP code %d).', 'edd-sample-plugin' ),
                        $response_code
                    );
                    
                }
                

                $redirect = add_query_arg(
                    array(
                        'page'          => \BRXC_PLUGIN_LICENSE_PAGE,
                        'sl_activation' => 'false',
                        'message'       => rawurlencode( $message ),
                        'nonce'		    => wp_create_nonce( self::$prefix . 'license_nonce' ),
                        'error_code'    => $error_code,
                    ),
                    admin_url( 'admin.php' )
                );

                wp_safe_redirect( $redirect );
                exit();
            }

            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            // $license_data->license will be either "deactivated" or "failed"
            //if ( 'deactivated' === $license_data->license ) {
                delete_option( 'brxc_license_key' );
                delete_option( 'brxc_license_status' );
                delete_option( 'brxc_license_date_created' );
            //}

            wp_safe_redirect( admin_url( 'admin.php?page=' . \BRXC_PLUGIN_LICENSE_PAGE ) );
            exit();

        }
    }

    /**
     * Checks if a license key is still valid.
     * The updater does this for you, so this is only needed if you want
     * to do somemthing custom.
     *
     * @return void
     */
    public static function brxc_check_license() {

        $license = trim( get_option( 'brxc_license_key' ) );

        $api_params = array(
            'edd_action'  => 'check_license',
            'license'     => $license,
            'item_id'     => \BRXC_ITEM_ID,
            'item_name'   => rawurlencode( \BRXC_ITEM_NAME ),
            'url'         => home_url(),
            'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
        );

        // Call the custom API.
        $response = wp_remote_post(
            \BRXC_STORE_URL,
            array(
                'timeout'   => 15,
                'sslverify' => false,
                'body'      => $api_params,
            )
        );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        if ( $license_data && 'valid' === $license_data->license ) {

            $expires = $license_data->expires;

            if ( isset( $expires ) && $expires === 'lifetime' ) {
                echo '<span style="color: var(--bricks-text-success); font-weight: 600;">active</span> (never expires)';
                exit;
            } elseif( isset( $expires ) ) {
                echo '<span style="color: var(--bricks-text-success); font-weight: 600;">active</span> (expires on ' . date("F jS, Y", strtotime($license_data->expires) ). ')';
                exit;
            }
            // this license is still valid
        } else {
            echo '<span style="color: var(--bricks-text-danger); font-weight: 600;">inactive</span>';
            exit;
            // this license is no longer valid
        }
    }

    /**
     * This is a means of catching errors from the activation method above and displaying it to the customer
     */
    public static function brxc_admin_notices() {
        if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) && isset( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], self::$prefix . 'license_nonce' ) ) {
    
            switch ( $_GET['sl_activation'] ) {
    
                case 'false':
                    $message = urldecode( sanitize_text_field( $_GET['message'] ) );
                    $error_code = urldecode( esc_attr( $_GET['error_code'] ) );
                    
                    if ( $error_code === '403' ) {
                        $message .= ' <a href="https://www.wpbeginner.com/wp-tutorials/how-to-fix-the-403-forbidden-error-in-wordpress/" target="_blank" rel="noopener noreferrer">Read this article to fix 403 Forbidden Errors.</a>';
                    }
                    ?>
                    <div class="error">
                        <p><?php echo wp_kses_post( $message ); ?></p>
                    </div>
                    <?php
                    break;
    
                case 'true':
                default:
                    // Developers can put a custom success message here for when activation is successful if they want.
                    break;
    
            }
        }
    }  
}