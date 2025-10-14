<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Helpers{
    /**
     * Check if the specified post or the current post in the loop has any block.
     *
     * @param int|WP_Post $post Optional post ID or post object. If not provided, the current post in the loop is used.
     * @return bool True if any block is found, false otherwise.
     */
    
    public static function is_array( $variable, $key = null ) {
        if ($key !== null) {
            if (is_array($variable) && array_key_exists($key, $variable)) {
                return !empty($variable[$key]) && is_array($variable[$key]);
            }
    
            if (is_object($variable) && property_exists($variable, $key)) {
                return !empty($variable->$key) && is_array($variable->$key);
            }
    
            return false;
        }
    
        return isset($variable) && !empty($variable) && is_array($variable);
    }

    public static function in_array( $value, $arr, $key = null ) {
        if ($key !== null) {
            if (is_array($arr) && array_key_exists($key, $arr)) {
                return !empty($arr[$key]) && is_array($arr[$key]) && in_array($value, $arr[$key]);
            }
    
            return false;
        }
        return isset($arr) && !empty($arr) && is_array($arr) && in_array($value, $arr);
    }

    public static function is_value( $variable, $key = null ) {
        if ($key !== null) {
            if (is_array($variable) && array_key_exists($key, $variable)) {
                return !empty($variable[$key]);
            }
    
            if (is_object($variable) && property_exists($variable, $key)) {
                return !empty($variable->$key);
            }
    
            return false;
        }
    
        return isset($variable) && !empty($variable);
    }



    public static function has_any_block( $post = null ) {
        // Get the current post ID in the loop if not provided.
        $post = $post ? get_post( $post ) : get_post();

        // Make sure we have a valid post.
        if ( ! $post ) {
            return false;
        }

        // Get all blocks present in the content.
        $blocks = parse_blocks( $post->post_content );

        // Check if there are any blocks.
        return ! empty( $blocks );
    }

    // Check the current user role and return TRUE/FALSE based on the user permission set in the option page
    public static function return_user_role_check(){
        if( !is_user_logged_in() ) {

            return false;

        }
        global $brxc_acf_fields;

        $disabled_roles = AT__ACF::acf_get_role();

        $current_user = wp_get_current_user(); 


        if( !$current_user ) {

            return false;

        }

        $user_roles = ( array ) $current_user->roles;

        if( !$user_roles || !is_array( $user_roles ) ) {

            return false;

        }

        if( !$disabled_roles && in_array('administrator', $user_roles)) {

            return true;

        } 

        if( !$disabled_roles ) {

            return false;

        }


        $intersection = array_intersect( $disabled_roles, $user_roles );

        foreach( $user_roles as $role ){

            if ( $role == 'administrator' || in_array( $role, $intersection ) ) {

                return true; // return true when the current user role MATCHES the disable roles list

            }

        }
        
        return false;// return false when the current user role DOESN'T MATCHES the disable roles list


    }

    public static function register_bricks_elements() {
        global $brxc_acf_fields;

        if (!class_exists('Bricks\Elements')) {
            return;
        }

        $element_files = [
            plugin_dir_path( \BRICKS_ADVANCED_THEMER_PLUGIN_FILE ) . 'elements/darkmode-toggle.php',
            plugin_dir_path( \BRICKS_ADVANCED_THEMER_PLUGIN_FILE ) . 'elements/darkmode-button.php',
            plugin_dir_path( \BRICKS_ADVANCED_THEMER_PLUGIN_FILE ) . 'elements/darkmode-toggle-nestable.php',
            plugin_dir_path( \BRICKS_ADVANCED_THEMER_PLUGIN_FILE ) . 'elements/darkmode-button-nestable.php',
        ];
    
        if(AT__Helpers::is_global_colors_category_activated() && isset($brxc_acf_fields['enable_dark_mode_on_frontend']) && $brxc_acf_fields['enable_dark_mode_on_frontend'] !== false){
            add_action('wp_enqueue_scripts', function(){
                wp_enqueue_script( 'brxc-darkmode-local-storage' );
            });
        }
        
        foreach ( $element_files as $file ) {
            \Bricks\Elements::register_element( $file );
        }
    }

    public static function generate_unique_string( $length ) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';

        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {

            $randomString .= $characters[rand( 0, $charactersLength - 1 )];

        }

        return $randomString;

    }

    public static function add_upload_mimes($types){

        global $brxc_acf_fields;

        if (AT__Helpers::return_user_role_check() !== true || !isset($brxc_acf_fields['file_upload_format_permissions']) || empty($brxc_acf_fields['file_upload_format_permissions']) || !is_array($brxc_acf_fields['file_upload_format_permissions'])) {

            return $types;

        }

        if(in_array('json', $brxc_acf_fields['file_upload_format_permissions'])){

            $types['json'] = 'text/application';

        }

        if(in_array('css', $brxc_acf_fields['file_upload_format_permissions'])){

            $types['css'] = 'text/css';
            
        }

	    return $types;
    }


    public static function read_file_contents($url) {

        $file_contents = null;

        try {
            // FILE GET CONTENT METHOD 1
            $file_contents = @file_get_contents($url);

            // FILE GET CONTENT METHOD 2
            if (!$file_contents) {
                $context = stream_context_create(
                    array(
                        "http" => array(
                            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                        ),
                        "ssl"  => array(
                            "verify_peer"       =>  false,
                            "verify_peer_name"  =>  false,
                        ),
                    )
                );

                $file_contents = @file_get_contents($url, false, $context);
            }

            // CURL METHOD
            if (!$file_contents && function_exists('curl_init')) {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36');

                $file_contents = curl_exec($ch);

                curl_close($ch);
            }

            // WP REMOTE GET METHOD
            if (!$file_contents) {
                $response = wp_remote_get($url);

                if (wp_remote_retrieve_response_code($response) == '200') {
                    $file_contents = wp_remote_retrieve_body($response);
                }
            }

        } catch (Exception $e) {
            echo 'There was an error fetching the data. Please contact Advanced Themer\'s support. Error: ',  $e->getMessage(), "\n";
            return false;
        }

        if (isset($file_contents) && !empty($file_contents) && $file_contents !== false && !is_wp_error($file_contents)) {

            return $file_contents;

        } else {

            echo "There was an error fetching the data. Please contact Advanced Themer's support.";

            return false;
        }
    }

    /*--------------------------------------
    Global Colors
    --------------------------------------*/

    public static function is_global_colors_category_activated(){

        global $brxc_acf_fields;

        if(isset($brxc_acf_fields['theme_settings_tabs']) && !empty($brxc_acf_fields['theme_settings_tabs']) && is_array($brxc_acf_fields['theme_settings_tabs'])  && in_array('global-colors', $brxc_acf_fields['theme_settings_tabs'])) {

            return true;

        }

        return false;
    }

    /*--------------------------------------
    CSS Variables
    --------------------------------------*/

    public static function is_css_variables_category_activated(){

        global $brxc_acf_fields;

        if(isset($brxc_acf_fields['theme_settings_tabs']) && !empty($brxc_acf_fields['theme_settings_tabs']) && is_array($brxc_acf_fields['theme_settings_tabs'])  && in_array('css-variables', $brxc_acf_fields['theme_settings_tabs'])) {

            return true;

        }

        return false;
    }

    // Import Framework

    public static function is_import_framework_tab_activated(){

        $is_category_activated = self::is_css_variables_category_activated();

        if(!$is_category_activated) return false;

        global $brxc_acf_fields;

        if(isset($brxc_acf_fields['css_variables_general']) && !empty($brxc_acf_fields['css_variables_general']) && is_array($brxc_acf_fields['css_variables_general'])  && in_array('import-framework', $brxc_acf_fields['css_variables_general'])) {

            return true;

        }

        return false;

    }

    // Theme Variables

    public static function is_theme_variables_tab_activated(){

        $is_category_activated = self::is_css_variables_category_activated();

        if(!$is_category_activated) return false;

        global $brxc_acf_fields;

        if(isset($brxc_acf_fields['css_variables_general']) && !empty($brxc_acf_fields['css_variables_general']) && is_array($brxc_acf_fields['css_variables_general'])  && in_array('theme-variables', $brxc_acf_fields['css_variables_general'])) {

            return true;

        }

        return false;

    }


    /*--------------------------------------
    Classes & Styles
    --------------------------------------*/

    public static function is_classes_and_styles_category_activated(){

        global $brxc_acf_fields;

        if(isset($brxc_acf_fields['theme_settings_tabs']) && !empty($brxc_acf_fields['theme_settings_tabs']) && is_array($brxc_acf_fields['theme_settings_tabs'])  && in_array('classes-and-styles', $brxc_acf_fields['theme_settings_tabs'])) {

            return true;

        }

        return false;
    }

    // Grids

    public static function is_grids_tab_activated(){

        $is_category_activated = self::is_classes_and_styles_category_activated();

        if(!$is_category_activated) return false;

        global $brxc_acf_fields;

        if(isset($brxc_acf_fields['classes_and_styles_general']) && !empty($brxc_acf_fields['classes_and_styles_general']) && is_array($brxc_acf_fields['classes_and_styles_general'])  && in_array('grids', $brxc_acf_fields['classes_and_styles_general'])) {
            return true;
        }

        return false;

    }

    /*--------------------------------------
    Builder Tweaks
    --------------------------------------*/

    public static function is_builder_tweaks_category_activated(){

        global $brxc_acf_fields;

        if(isset($brxc_acf_fields['theme_settings_tabs']) && !empty($brxc_acf_fields['theme_settings_tabs']) && is_array($brxc_acf_fields['theme_settings_tabs'])  && in_array('builder-tweaks', $brxc_acf_fields['theme_settings_tabs'])) {

            return true;

        }

        return false;
    }


    /*--------------------------------------
    Strict Editor View
    --------------------------------------*/

    public static function is_strict_editor_view_category_activated(){

        global $brxc_acf_fields;

        if(isset($brxc_acf_fields['theme_settings_tabs']) && !empty($brxc_acf_fields['theme_settings_tabs']) && is_array($brxc_acf_fields['theme_settings_tabs'])  && in_array('strict-editor-view', $brxc_acf_fields['theme_settings_tabs'])) {

            return true;

        }

        return false;
    }


    /*--------------------------------------
    AI
    --------------------------------------*/

    public static function is_ai_category_activated(){

        global $brxc_acf_fields;

        if(isset($brxc_acf_fields['theme_settings_tabs']) && !empty($brxc_acf_fields['theme_settings_tabs']) && is_array($brxc_acf_fields['theme_settings_tabs'])  && in_array('ai', $brxc_acf_fields['theme_settings_tabs'])) {

            return true;

        }

        return false;
    }

    /*--------------------------------------
    Extras
    --------------------------------------*/

    public static function is_extras_category_activated(){

        global $brxc_acf_fields;

        if(isset($brxc_acf_fields['theme_settings_tabs']) && !empty($brxc_acf_fields['theme_settings_tabs']) && is_array($brxc_acf_fields['theme_settings_tabs'])  && in_array('extras', $brxc_acf_fields['theme_settings_tabs'])) {

            return true;

        }

        return false;
    }

    /*--------------------------------------
    Misc
    --------------------------------------*/

    public static function sanitize_css_content($css_content) {
        // Strip out anything that looks like HTML or JavaScript
        $css_content = wp_strip_all_tags($css_content);
    
        // Remove any JavaScript or other malicious code patterns like "expression" or "url(javascript:...)" in CSS
        $css_content = preg_replace('/(expression|javascript\s*\:)/i', '', $css_content);
    
        // Optionally remove comments (this prevents malicious code hiding in CSS comments)
        $css_content = preg_replace('/\/\*.*?\*\//s', '', $css_content);

        // Remove Slashes (single quotes)
        $css_content = stripslashes($css_content);
    
        return $css_content;
    }
    public static function sanitize_css_string($css) {
        // Remove <script>...</script> blocks
        $css = preg_replace('#<script\b[^>]*>(.*?)</script>#is', '', $css);
    
        // Remove PHP code blocks
        $css = preg_replace('/<\?(php|=)?(.*?)\?>/is', '', $css);
    
        // Remove dangerous CSS expressions
        $css = preg_replace('/expression\s*\(.*?\)/i', '', $css);
        $css = preg_replace('/url\s*\(\s*["\']?\s*javascript\s*:[^"\')]*["\']?\s*\)/i', '', $css);
        $css = preg_replace('/url\s*\(\s*["\']?\s*vbscript\s*:[^"\')]*["\']?\s*\)/i', '', $css);
    
        // Remove inline javascript: and vbscript: styles
        $css = preg_replace('/(?:javascript|vbscript)\s*:/i', '', $css);
    

        // Optionally trim to clean up surrounding whitespace
        return trim($css);
    }

    public static function format_shadow_strings($objs) {
        $result = []; // Initialize the result array
        
        // Check if $objs is an array
        if (!is_array($objs)) {
            return $result; // Return an empty array if $objs is not an array
        }
    
        foreach ($objs as $group) {
            if (!is_array($group)) {
                continue; // Skip this iteration if $group is not an array
            }
    
            $shadows = []; // Initialize an array to hold shadow strings for the current group
            
            foreach ($group as $shadow) {
                // Check if $shadow is an associative array and has 'rgb' key
                if (!is_array($shadow) || !isset($shadow['rgb'])) {
                    continue; // Skip this iteration if $shadow is not an array or missing 'rgb'
                }
    
                // Use null coalescing operator to provide defaults
                $rgb = $shadow['rgb'] ?? '0,0,0'; // Default to black if rgb is not set
                $alpha = (isset($shadow['alpha']) && is_numeric($shadow['alpha'])) ? number_format($shadow['alpha'], 2) : '1.00'; // Default alpha to 1.00
                $x = (isset($shadow['x']) && is_numeric($shadow['x'])) ? $shadow['x'] : 0; // Default x to 0
                $y = (isset($shadow['y']) && is_numeric($shadow['y'])) ? $shadow['y'] : 0; // Default y to 0
                $blur = (isset($shadow['blur']) && is_numeric($shadow['blur'])) ? $shadow['blur'] : 0; // Default blur to 0
                $spread = (isset($shadow['spread']) && is_numeric($shadow['spread'])) ? $shadow['spread'] : 0; // Default spread to 0
                
                $rgba = "rgba({$rgb}, {$alpha})"; // Create the RGBA string
                $shadowString = "{$rgba} {$x}px {$y}px {$blur}px {$spread}px"; // Create the shadow string
                $shadows[] = $shadowString; // Add the formatted shadow string to the shadows array
            }
    
            // Only add to result if shadows array is not empty
            if (!empty($shadows)) {
                $result[] = implode(', ', $shadows); // Join the shadow strings and add to the result array
            }
        }
    
        return $result; // Return the final result array
    }    

}
