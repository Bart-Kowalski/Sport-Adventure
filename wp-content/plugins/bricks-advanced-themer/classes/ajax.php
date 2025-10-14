<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Ajax{
    private static function add_non_duplicate_entries($arr1, $arr2, $property) {
        if (empty($arr1) || !is_array($arr1)) {
            return $arr2;
        }
    
        $existingIds = array_column($arr2, $property);
        
        foreach ($arr1 as $objectA) {
            $idA = $objectA[$property] ?? null;
            
            if ($idA !== null && !in_array($idA, $existingIds, true)) {
                $arr2[] = $objectA;
                $existingIds[] = $idA; // Keep the IDs array in sync
            }
        }
        
        return $arr2;
    }

    public static function export_advanced_options_callback() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }
        $checked_data = $_POST['checked_data'];

        if(!is_array($checked_data)){
            return;
        }

        $json_data = array();
        $response = [];
        global $wpdb;

        // AT Settings
        if(in_array('at-theme-settings', $checked_data)){
            $option_data = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer%' AND option_name NOT LIKE '%_variables_repeater%' AND option_name NOT LIKE '%_skip_export' AND option_name NOT LIKE '%\_api\_%'");
            $found = false;
            if(AT__Helpers::is_array($option_data)){
                $json_data['at-settings'] = [];
                foreach ($option_data as $row) {
                    $json_data['at-settings'][$row->option_name] = maybe_unserialize($row->option_value);
                    $found = true;
                }
            }

            if($found){
                $response[] = 'Theme Settings';
            }
        }

        // AT Builder Settings
        $at_settings_builder = get_option('bricks_advanced_themer_builder_settings', []);

        if(in_array('at-ux-settings', $checked_data) && isset($at_settings_builder['builderSettings'])){
            $json_data['at-ux-settings'] = stripslashes($at_settings_builder['builderSettings']);
            $response[] = 'Builder UX Settings';
        }

        if(in_array('at-grid-guides', $checked_data) && isset($at_settings_builder['gridGuide'])){
            $json_data['at-grid-guides'] = $at_settings_builder['gridGuide'];
            $response[] = 'Grid Guides Settings';
        }
        if(in_array('at-right-shortcuts', $checked_data) && isset($at_settings_builder['rightShortcuts'])){
            $json_data['at-right-shortcuts'] = $at_settings_builder['rightShortcuts'];
            $response[] = 'Right Shortcuts Settings';
        }
        if(in_array('at-strict-editor', $checked_data) && isset($at_settings_builder['full_access'])){
            $json_data['at-strict-editor'] = $at_settings_builder['full_access'];
            $response[] = 'Strict Editor Settings';
        }
        if(in_array('at-nested-elements', $checked_data) && isset($at_settings_builder['custom_components_elements'])){
            $json_data['at-nested-elements'] = $at_settings_builder['custom_components_elements'];
            $json_data['at-nested-elements-categories'] = $at_settings_builder['custom_components_categories'];
            $response[] = 'Nested Elements Library';
        }
        if(in_array('at-query-manager', $checked_data) && isset($at_settings_builder['query_manager'])){
            $json_data['at-query-manager'] = $at_settings_builder['query_manager'];
            $json_data['at-query-manager-categories'] = $at_settings_builder['query_manager_cats'];
            $response[] = 'Query Manager Settings';
        }
        if(in_array('at-prompt-manager', $checked_data) && isset($at_settings_builder['prompt_manager'])){
            $json_data['at-prompt-manager'] = $at_settings_builder['prompt_manager'];
            $response[] = 'Prompt Manager Settings';
        }

        // Advanced CSS
        if( AT__Helpers::is_array($at_settings_builder, 'advanced_css') ) {
            $advanced_css = $at_settings_builder['advanced_css'];
            $json_data['at-advanced-css'] = [];
            $found_global = false;
            $found_child = false;
            $found_custom = false;
            $found_framework = false;
            foreach($advanced_css as $item){
                // Global CSS
                if(in_array('at-advanced-css-global', $checked_data) && $item['id'] === "at-global-css"){
                    $json_data['at-advanced-css']["global"] = $item;
                    $found_global = true;
                }

                // Child CSS
                if(in_array('at-advanced-css-child', $checked_data) && $item['id'] === "at-child-css"){
                    $json_data['at-advanced-css']["child"] = $item;
                    $found_child = true;
                }

                // Custom CSS & partials
                if(in_array('at-advanced-css-custom', $checked_data) && ((isset($item['category']) && $item['category'] === "custom") || $item['id'] === "at-mixins" || $item['id'] === "at-partials") ){
                    if( !AT__Helpers::is_array($json_data['at-advanced-css'], "custom") ){
                        $json_data['at-advanced-css']["custom"] = [];
                    }

                    $json_data['at-advanced-css']["custom"][] = $item;
                    $found_custom = true;
                }

                if(in_array('at-advanced-css-framework', $checked_data) && isset($item['category']) && $item['category'] === "at framework" ){
                    if( !AT__Helpers::is_array($json_data['at-advanced-css'], "framework") ){
                        $json_data['at-advanced-css']["framework"] = [];
                    }

                    $json_data['at-advanced-css']["framework"][] = $item;
                    $found_framework = true;
                }
            }
            if($found_global){
                $response[] = 'Advanced CSS - Global';
            }
            if($found_child){
                $response[] = 'Advanced CSS - Child Theme';
            }
            if($found_custom){
                $response[] = 'Advanced CSS - Partials & Custom Stylesheets/Recipes';
            }
            if($found_framework){
                $response[] = 'Advanced CSS - AT Framework';
            }
        }
        

        // Bricks Settings
        if(in_array('bricks-settings', $checked_data)){
            $bricks_settings = get_option( 'bricks_global_settings' );
            if( AT__Helpers::is_array($bricks_settings) ) {
                $json_data['bricks-settings'] = $bricks_settings;
                $response[] = 'Global Bricks Settings';
            } 
        }
        // Global Variables
        if(in_array('global-variables', $checked_data)){
            $global_variables = get_option( 'bricks_global_variables' );
            if( AT__Helpers::is_array($global_variables) ) {
                $json_data['global-variables'] = $global_variables;
                $response[] = 'Global CSS Variables';
            }

            $global_variables_categories = get_option( 'bricks_global_variables_categories' );
            if( AT__Helpers::is_array($global_variables_categories) ) {
                $json_data['global-variables-categories'] = $global_variables_categories;
                $response[] = 'Global CSS Variables - Categories';
            }
        }

        // Global Colors
        if(in_array('global-colors', $checked_data)){
            $palette_arr = get_option( 'bricks_color_palette' );
            if( AT__Helpers::is_array($palette_arr) ) {
                $json_data['global-colors'] = $palette_arr;
                $response[] = 'Global Colors';
            } 
        }

        // Components
        if(in_array('components', $checked_data)){
            $components_arr = get_option( 'bricks_components' );
            if( AT__Helpers::is_array($components_arr) ) {
                $json_data['components'] = $components_arr;
                $response[] = 'Components';
            } 
        }

        // Global Classes
        if(in_array('global-classes', $checked_data)){
            $global_classes = get_option( 'bricks_global_classes' );
            if( AT__Helpers::is_array($global_classes) ) {
                $json_data['global-classes'] = $global_classes;
                $response[] = 'Global Classes';
            }
            $global_classes_categories = get_option( 'bricks_global_classes_categories' );
            if( AT__Helpers::is_array($global_classes_categories) ) {
                $json_data['global-classes-categories'] = $global_classes_categories;
                $response[] = 'Global Classes - Categories';
            }

            $global_classes_locked = get_option( 'bricks_global_classes_locked' );
            if( AT__Helpers::is_array($global_classes_locked) ) {
                $json_data['global-classes-locked'] = $global_classes_locked;
                $response[] = 'Global Classes - Locked list';
            }
        }

        // Pseudo Classes
        if(in_array('pseudo-classes', $checked_data)){
            $pseudo_classes = get_option( 'bricks_global_pseudo_classes' );
            if( AT__Helpers::is_array($pseudo_classes) ) {
                $json_data['pseudo-classes'] = $pseudo_classes;
                $response[] = 'Global Pseudo Classes';
            } 
        }

        // Breakpoints
        if(in_array('breakpoints', $checked_data)){
            $breakpoints = get_option( 'bricks_breakpoints' );
            if( AT__Helpers::is_array($breakpoints) ) {
                $json_data['breakpoints'] = $breakpoints;
                $response[] = 'Global Breakpoints';
            } 
        }

        // Theme Styles
        if(in_array('theme-styles', $checked_data)){
            $theme_styles = get_option( 'bricks_theme_styles' );
            if( AT__Helpers::is_array($theme_styles) ) {
                $json_data['theme-styles'] = $theme_styles;
                $response[] = 'Theme Styles';
            } 
        }

        $success_data = [
            "json_data" => $json_data,
            "success_data" => $response,
        ];

        wp_send_json_success($success_data);
        
        wp_die(); // Required for AJAX callback 

    } 

    public static function import_advanced_options_callback() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_send_json_error("Invalid nonce, please refresh the page and try again.");
            wp_die();
        }

            
        if ( ! isset( $_FILES['file']['tmp_name'] ) ) { 
            wp_send_json_error( 'File not uploaded.' ); 
        } 

        $temp_path = $_FILES['file']['tmp_name']; 
        $checked_data = $_POST['checked_data'];
        $overwrite = $_POST['overwrite'];
        $response = [];
        

        if ($checked_data === null) {
            wp_send_json_error('Invalid checked data.');
        }

        $json_file = AT__Helpers::read_file_contents($temp_path);

        if ($json_file !== false){

            $data = json_decode($json_file, true);

            if ($data === null) {
                wp_send_json_error('Invalid JSON file.');
            }

            global $wpdb;

            // AT Settings
            $pos = strpos($checked_data, 'at-theme-settings');
            if( $pos && AT__Helpers::is_array($data, 'at-settings') ){

                // Theme Settings
                
                $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer%' AND option_name NOT LIKE '%_variables_repeater%'");
        
                foreach ($data['at-settings'] as $option_name => $option_value) {
                    if (is_array($option_value)) {
                        $option_value = maybe_serialize($option_value);
                    }

                    $wpdb->insert($wpdb->options, array('option_name' => $option_name, 'option_value' => $option_value));

                }
                $response[] = 'Theme Settings';
            }

            // AT Builder Settings
            $at_settings_builder = get_option('bricks_advanced_themer_builder_settings', []);

            // Builder UX Settings
            $pos = strpos($checked_data, 'at-ux-settings');
            if ($pos !== false) {

                $at_settings_builder['builderSettings'] = !empty($data['at-ux-settings']) ? stripslashes($data['at-ux-settings']) : '';
                $response[] = 'Builder UX Settings';
            }

            // Grid Guide
            $pos = strpos($checked_data, 'at-grid-guides');
            if ($pos !== false && AT__Helpers::is_array($data, 'at-grid-guides')) {

                $at_settings_builder['gridGuide'] = $overwrite || empty($at_settings_builder['gridGuide'] ?? []) ? [] : $at_settings_builder['gridGuide'];
                $at_settings_builder['gridGuide'] = self::add_non_duplicate_entries($data['at-grid-guides'], $at_settings_builder['gridGuide'], 'device');
                $response[] = 'Grid Guides Settings';
            }

            // Right Shortcuts
            $pos = strpos($checked_data, 'at-right-shortcuts');
            if ($pos !== false && AT__Helpers::is_array($data, 'at-right-shortcuts')) {

                $at_settings_builder['rightShortcuts'] = $overwrite || empty($at_settings_builder['rightShortcuts'] ?? []) ? [] : $at_settings_builder['rightShortcuts'];
                $response[] = 'Right Shortcuts Settings';
            }

            // Strict Editor Settings
            $pos = strpos($checked_data, 'at-strict-editor');
            if( $pos !== false && AT__Helpers::is_array($data['at-strict-editor']) ){
                
                $strict_editor = $at_settings_builder['full_access'];

                // overwrite by default
                $strict_editor = $data['at-strict-editor'];

                if(is_array($strict_editor) && !empty($strict_editor)){
                    $at_settings_builder['full_access'] = $strict_editor;
                    $response[] = 'Strict Editor Settings';
                }
            }

            // Nested Elements Library
            $pos = strpos($checked_data, 'at-nested-elements');
            if ($pos !== false && AT__Helpers::is_array($data, 'at-nested-elements') && AT__Helpers::is_array($data, 'at-nested-elements-categories') ) {

                $at_settings_builder['custom_components_elements'] = $overwrite || empty($at_settings_builder['custom_components_elements'] ?? []) ? [] : $at_settings_builder['custom_components_elements'];
                $at_settings_builder['custom_components_categories'] = $overwrite || empty($at_settings_builder['custom_components_categories'] ?? []) ? [] : $at_settings_builder['custom_components_categories'];
                $at_settings_builder['custom_components_elements'] = self::add_non_duplicate_entries($data['at-nested-elements'], $at_settings_builder['custom_components_elements'], 'id');
                $at_settings_builder['custom_components_categories'] = self::add_non_duplicate_entries($data['at-nested-elements-categories'], $at_settings_builder['custom_components_categories'], 'id');
                $response[] = 'Nested Elements Library';
            }

            // Query Manager
            $pos = strpos($checked_data, 'at-query-manager');
            if ($pos !== false && AT__Helpers::is_array($data, 'at-query-manager') && AT__Helpers::is_array($data, 'at-query-manager-categories') ) {

                $at_settings_builder['query_manager'] = $overwrite || empty($at_settings_builder['query_manager'] ?? []) ? [] : $at_settings_builder['query_manager'];
                $at_settings_builder['query_manager_cats'] = $overwrite || empty($at_settings_builder['query_manager_cats'] ?? []) ? [] : $at_settings_builder['query_manager_cats'];
                $at_settings_builder['query_manager'] = self::add_non_duplicate_entries($data['at-query-manager'], $at_settings_builder['query_manager'], 'id');
                $at_settings_builder['query_manager_cats'] = self::add_non_duplicate_entries($data['at-query-manager-categories'], $at_settings_builder['query_manager_cats'], 'id');
                $response[] = 'Query Manager Settings';
            }

            // Prompt Manager
            $pos = strpos($checked_data, 'at-prompt-manager');
            if ($pos !== false && AT__Helpers::is_array($data, 'at-prompt-manager') && AT__Helpers::is_array($data, 'at-prompt-manager-categories') ) {

                $at_settings_builder['prompt_manager'] = $overwrite || empty($at_settings_builder['prompt_manager'] ?? []) ? [] : $at_settings_builder['prompt_manager'];;
                $at_settings_builder['prompt_manager'] = self::add_non_duplicate_entries($data['at-prompt-manager'], $at_settings_builder['prompt_manager'], 'id');
                $response[] = 'Prompt Manager Settings';
            }

            // Advanced CSS
            if (AT__Helpers::is_array($data, 'at-advanced-css')) {
                $at_settings_builder['advanced_css'] = $at_settings_builder['advanced_css'] ?? [];
            
                // Helper function to handle item processing
                $processItem = function($item, $overwrite, &$settingsArray) {
                    $foundKey = array_search($item['id'], array_column($settingsArray, 'id'));
            
                    if ($foundKey === false) {
                        $settingsArray[] = $item;
                    } elseif ($overwrite) {
                        $settingsArray[$foundKey] = $item;
                    }
                };

                $found_global = false;
                $found_child = false;
                $found_custom = false;
                $found_framework = false;
            
                foreach ($data['at-advanced-css'] as $key => $category) {
                    // Global CSS
                    if (strpos($checked_data, 'at-advanced-css-global') && $key === "global"){
                        if(AT__Helpers::is_array($category)){
                            if( isset($category['id']) && $category['id'] === "at-global-css") {
                                $category['hasChanged'] = true;
                                $processItem($category, $overwrite, $at_settings_builder['advanced_css']);
                                $found_global = true;
                            }
                        }
                    }
            
                    // Child CSS
                    if (strpos($checked_data, 'at-advanced-css-child') && $key === "child"){
                        if(AT__Helpers::is_array($category)){
                            if( isset($category['id']) && $category['id'] === "at-child-css") {
                                $category['hasChanged'] = true;
                                $processItem($category, $overwrite, $at_settings_builder['advanced_css']);
                                $found_child = true;
                            }
                            
                        }
                    }
            
                    // Custom and Partial CSS
                    if (strpos($checked_data, 'at-advanced-css-custom') && $key === "custom"){
                        if(AT__Helpers::is_array($category)){
                            foreach($category as $item){
                                if (
                                    strpos($checked_data, 'at-advanced-css-custom') && 
                                    ( 
                                        (isset($item['category']) && $item['category'] === "custom") || 
                                        in_array($item['id'], ["at-mixins", "at-partials"])
                                    ) 
                                ){
                                        $item["hasChanged"] = true;
                                        $processItem($item, $overwrite, $at_settings_builder['advanced_css']);
                                        $found_custom = true;
                                }
                            }
                        }
                    }

                    // Framework
                    if (strpos($checked_data, 'at-advanced-css-framework') && $key === "framework"){
                        if(AT__Helpers::is_array($category)){
                            foreach($category as $item){
                                if (
                                    strpos($checked_data, 'at-advanced-css-framework') && 
                                    isset($item['category']) && $item['category'] === "at framework"
                                ){
                                        $item["hasChanged"] = true;
                                        $processItem($item, $overwrite, $at_settings_builder['advanced_css']);
                                        $found_framework = true;
                                }
                            }
                        }
                    }
                }

                if($found_global){
                    $response[] = 'Advanced CSS - Global';
                }
                if($found_child){
                    $response[] = 'Advanced CSS - Child Theme';
                }
                if($found_custom){
                    $response[] = 'Advanced CSS - Partials & Custom Stylesheets/Recipes';
                }
                if($found_framework){
                    $response[] = 'Advanced CSS - AT Framework';
                }
                if($found_global || $found_child || $found_custom || $found_framework ) {
                    self::save_advanced_css_data($at_settings_builder['advanced_css']);
                }
            }

            // Update Option
            if(!empty($at_settings_builder)){
                update_option('bricks_advanced_themer_builder_settings', $at_settings_builder);
            }

            // Bricks Settings
            $pos = strpos($checked_data, 'bricks-settings');
            if ($pos !== false && AT__Helpers::is_array($data, 'bricks-settings')) {

                update_option('bricks_global_settings', $data['bricks-settings']);
                $response[] = 'Global Bricks Settings';
            }

            // Global Variables
            $pos = strpos($checked_data, 'global-variables');
            if ($pos !== false && AT__Helpers::is_array($data, 'global-variables')) {

                $global_variables = get_option('bricks_global_variables', []);
                $global_variables = $overwrite || empty($global_variables ?? []) ? [] : $global_variables;
                $global_variables = self::add_non_duplicate_entries($data['global-variables'], $global_variables, 'id');

                if (!empty($global_variables)) {
                    update_option('bricks_global_variables', $global_variables);
                    $response[] = 'Global CSS Variables';
                }
            }

            // Global Variables Categories
            if ($pos !== false && AT__Helpers::is_array($data, 'global-variables-categories')) {

                $global_variables_categories = get_option('bricks_global_variables_categories', []);
                $global_variables_categories = $overwrite || empty($global_variables_categories ?? []) ? [] : $global_variables_categories;
                $global_variables_categories = self::add_non_duplicate_entries($data['global-variables-categories'], $global_variables_categories, 'id');

                if (!empty($global_variables_categories)) {
                    update_option('bricks_global_variables_categories', $global_variables_categories);
                    $response[] = 'Global CSS Variables - Categories';
                }
            }

            // Global Colors
            $pos = strpos($checked_data, 'global-colors');
            if ($pos !== false && AT__Helpers::is_array($data, 'global-colors')) {

                $global_colors = get_option('bricks_color_palette', []);
                $global_colors = $overwrite || empty($global_colors ?? []) ? [] : $global_colors;
                $global_colors = self::add_non_duplicate_entries($data['global-colors'], $global_colors, 'id');

                if (!empty($global_colors)) {
                    update_option('bricks_color_palette', $global_colors);
                    $response[] = 'Global Colors';
                }
            }

            // Components
            $pos = strpos($checked_data, 'components');
            if ($pos !== false && AT__Helpers::is_array($data, 'components')) {

                $components = get_option('bricks_components', []);
                $components = $overwrite || empty($components ?? []) ? [] : $components;
                $components = self::add_non_duplicate_entries($data['components'], $components, 'id');

                if (!empty($components)) {
                    update_option('bricks_components', $components);
                    $response[] = 'Components';
                }
            }


            // Global Classes
            $pos = strpos($checked_data, 'global-classes');
            if ($pos !== false && AT__Helpers::is_array($data, 'global-classes')) {

                $global_classes = get_option('bricks_global_classes', []);
                $global_classes = $overwrite || empty($global_classes ?? []) ? [] : $global_classes;
                $global_classes = self::add_non_duplicate_entries($data['global-classes'], $global_classes, 'name');

                if (!empty($global_classes)) {
                    update_option('bricks_global_classes', $global_classes);
                    $response[] = 'Global Classes';
                }
            }

            // Global Classes Categories
            if ($pos !== false && AT__Helpers::is_array($data, 'global-classes-categories')) {

                $global_classes_categories = get_option('bricks_global_classes_categories', []);
                $global_classes_categories = $overwrite || empty($global_classes_categories ?? []) ? [] : $global_classes_categories;
                $global_classes_categories = self::add_non_duplicate_entries($data['global-classes-categories'], $global_classes_categories, 'id');

                if (!empty($global_classes_categories)) {
                    update_option('bricks_global_classes_categories', $global_classes_categories);
                    $response[] = 'Global Classes - Categories';
                }
            }
            
            // Global Classes Locked
            $pos = strpos($checked_data, 'global-classes-locked');
            if ($pos !== false && AT__Helpers::is_array($data, 'global-classes-locked')) {

                $global_classes_locked = get_option('bricks_global_classes_locked', []);
                $global_classes_locked = $overwrite || empty($global_classes_locked ?? []) ? [] : $global_classes_locked;

                foreach ($data['global-classes-locked'] as $item) {
                    if (!in_array($item, $global_classes_locked, true)) {
                        $global_classes_locked[] = $item;
                    }
                }

                if (!empty($global_classes_locked)) {
                    update_option('bricks_global_pseudo_classes', $global_classes_locked);
                    $response[] = 'Global Classes - Locked list';
                }
            }

            // Global Pseudo Classes
            $pos = strpos($checked_data, 'pseudo-classes');
            if ($pos !== false && AT__Helpers::is_array($data, 'pseudo-classes')) {

                $pseudo_classes = get_option('bricks_global_pseudo_classes', []);
                $pseudo_classes = $overwrite || empty($pseudo_classes ?? []) ? [] : $pseudo_classes;

                foreach ($data['pseudo-classes'] as $item) {
                    if (!in_array($item, $pseudo_classes, true)) {
                        $pseudo_classes[] = $item;
                    }
                }

                if (!empty($pseudo_classes)) {
                    update_option('bricks_global_pseudo_classes', $pseudo_classes);
                    $response[] = 'Global Pseudo Classes';
                }
            }

            // Breakpoints
            $pos = strpos($checked_data, 'breakpoints');
            if ($pos !== false && AT__Helpers::is_array($data, 'breakpoints')) {

                $breakpoints = get_option('bricks_breakpoints', []);
                $breakpoints = $overwrite || empty($breakpoints ?? []) ? [] : $breakpoints;
                $breakpoints = self::add_non_duplicate_entries($data['breakpoints'], $breakpoints, 'key');

                if (!empty($breakpoints)) {
                    update_option('bricks_breakpoints', $breakpoints);
                    $response[] = 'Global Breakpoints';
                }
            }


            // Theme Styles
            $pos = strpos($checked_data, 'theme-styles');
            if( $pos && AT__Helpers::is_array($data, 'theme-styles') ){
                
                $theme_styles = get_option('bricks_theme_styles', []);

                foreach ($data['theme-styles'] as $objectA => $valueA) {
                    $nameA = $objectA;
                    $foundThemeStyle = false;
                
                    // Check if the object with the same name exists in arrayB
                    foreach ($theme_styles as $objectB => $valueB) {
                        $nameB = $objectB;
                        if ($nameA === $nameB) {
                            $foundThemeStyle = true;
                            break;
                        }
                    }
                
                    // If the object with the same name was not found in arrayB, add it
                    if (!$foundThemeStyle) {
                        $theme_styles[$objectA] = $valueA;
                    }
                }

                if( AT__Helpers::is_array($theme_styles) ){
                    update_option('bricks_theme_styles', $theme_styles);
                    $response[] = 'Theme Styles';
                }
                
            }

            if (function_exists('wp_cache_flush')) {
                wp_cache_flush();
            }

            wp_send_json_success($response);
        } else {
            wp_send_json_error('Unable to read the JSON file.');
        }


        wp_die(); // Required for AJAX callback 
    }

    private static function reset_unset_settings($keys, &$builder) {
        foreach ($keys as $key) {
            if (isset($builder[$key])) {
                unset($builder[$key]);
            }
        }
    }
    
    private static function reset_delete_options($options, $checked_data, &$response) {
        foreach ($options as $option) {
            if (in_array($option['key'], $checked_data)) {
                delete_option($option['name']);
                $response[] = $option['label'];
            }
        }
    }

    public static function reset_advanced_options_callback() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }
        
        $checked_data = $_POST['checked_data'];

        if(!is_array($checked_data)){
            return;
        }

        $response = [];
        global $wpdb;


        // AT Settings
        if (in_array('at-theme-settings', $checked_data)) {
            $option_data = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer%' AND option_name NOT LIKE '%_variables_repeater%'");
            $found = false;
            // Delete options
            foreach ($option_data as $option) {
                delete_option($option->option_name);
                $found = true;
            }

            if($found){
                $response[] = 'Theme Settings';
            }
        }

        // Builder Settings
        $at_settings_builder = get_option('bricks_advanced_themer_builder_settings', []);

        // Grid Guide, Strict Editor, Nested Elements Library, Query Manager

        if (in_array('at-ux-settings', $checked_data) && isset($at_settings_builder['builderSettings'])) {
            unset($at_settings_builder['builderSettings']);
            $response[] = 'Builder UX Settings';
        }
        if (in_array('at-local-storage', $checked_data)) {
            $response[] = 'Local Storage';
        }

        if (in_array('at-grid-guides', $checked_data) && isset($at_settings_builder['gridGuide'])) {
            unset($at_settings_builder['gridGuide']);
            $response[] = 'Grid Guide Settings';
        }

        if (in_array('at-right-shortcuts', $checked_data) && isset($at_settings_builder['rightShortcuts'])) {
            unset($at_settings_builder['rightShortcuts']);
            $response[] = 'Right Shortcuts Settings';
        }

        if (in_array('at-strict-editor', $checked_data) && isset($at_settings_builder['full_access'])) {
            unset($at_settings_builder['full_access']);
            $response[] = 'Strict Editor Settings';
        }

        if (in_array('at-nested-elements', $checked_data)) {
            self::reset_unset_settings(['custom_components_elements', 'custom_components_categories'], $at_settings_builder, $response, 'Nested Elements Library');
        }

        if (in_array('at-query-manager', $checked_data)) {
            self::reset_unset_settings(['query_manager', 'query_manager_cats'], $at_settings_builder, $response, 'Query Manager');
        }

        if (in_array('at-prompt-manager', $checked_data)) {
            self::reset_unset_settings(['prompt_manager', 'prompt_manager_cats'], $at_settings_builder, $response, 'Prompt Manager');
        }

        // Advanced CSS
        $at_settings_builder['advanced_css'] = $at_settings_builder['advanced_css'] ?? [];
        $found_global = false;
        $found_child = false;
        $found_custom = false;
        $found_framework = false;
        foreach ($at_settings_builder['advanced_css'] as $key => $item) {
            if (in_array('at-advanced-css-global', $checked_data) && $item['id'] === "at-global-css" && isset($at_settings_builder[$key]) ) {
                unset($at_settings_builder['advanced_css'][$key]);
                $found_global = true;
            }

            if (in_array('at-advanced-css-child', $checked_data) && $item['id'] === "at-child-css" && isset($at_settings_builder[$key]) ) {
                unset($at_settings_builder['advanced_css'][$key]);
                $found_child = true;
            }

            if (in_array('at-advanced-css-custom', $checked_data) && 
                ( 
                    ( isset($item['category']) && $item['category'] === "custom" ) ||
                    ( isset($item['category']) && $item['category'] === "at framework" ) ||
                    in_array($item['id'], ["at-mixins", "at-partials"])
                ) 
            ) {
                unset($at_settings_builder['advanced_css'][$key]);
                $found_custom = true;
            }

            if (in_array('at-advanced-css-framework', $checked_data) && isset($item['category']) && $item['category'] === "at framework") {
                unset($at_settings_builder['advanced_css'][$key]);
                $found_framework = true;
            }

        }

        if($found_global){
            $response[] = 'Advanced CSS - Global';
        }
        if($found_child){
            $response[] = 'Advanced CSS - Child Theme';
        }
        if($found_custom){
            $response[] = 'Advanced CSS - Partials & Custom Stylesheets/Recipes';
        }

        if($found_framework){
            $response[] = 'Advanced CSS - AT Framework';
        }

        // Update Option
        if (!empty($at_settings_builder)) {
            update_option('bricks_advanced_themer_builder_settings', $at_settings_builder);
        }

        // Define options to delete
        $options_to_delete = [
            ['key' => 'global-variables', 'label' => 'Global Variables', 'name' => 'bricks_global_variables'],
            ['key' => 'global-variables', 'label' => 'Global Variables - Categories', 'name' => 'bricks_global_variables_categories'],
            ['key' => 'global-colors', 'label' => 'Global Colors', 'name' => 'bricks_color_palette'],
            ['key' => 'components', 'label' => 'Components', 'name' => 'bricks_components'],
            ['key' => 'global-classes', 'label' => 'Global Classes', 'name' => 'bricks_global_classes'],
            ['key' => 'global-classes', 'label' => 'Global Classes - Categories', 'name' => 'bricks_global_classes_categories'],
            ['key' => 'global-classes', 'label' => 'Global Classes - Locked list', 'name' => 'bricks_global_classes_locked'],
            ['key' => 'breakpoints', 'label' => 'Global Breakpoints', 'name' => 'bricks_breakpoints'],
            ['key' => 'pseudo-classes', 'label' => 'Global Pseudo Classes', 'name' => 'bricks_global_pseudo_classes'],
            ['key' => 'bricks-settings', 'label' => 'Global Bricks Settings', 'name' => 'bricks_global_settings'],
            ['key' => 'theme-styles', 'label' => 'Theme Styles', 'name' => 'bricks_theme_styles'],
        ];

        // Delete options
        self::reset_delete_options($options_to_delete, $checked_data, $response);

        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }

        wp_send_json_success($response);
        
        wp_die(); // Required for AJAX callback 

    }

    private static function loop_elements_and_convert_values($post_ids, $logical = true){
        foreach ($post_ids as $post_id) {
            $post_type = get_post_type($post_id);
            $post_meta_key = null;
            $elements = null;

            // Get content type (header, content, footer) & elements
            if ($post_type === 'bricks_template') {
                $elements = get_post_meta($post_id, '_bricks_page_header_2', true);

                if ($elements) {
                    $post_meta_key = '_bricks_page_header_2';
                } else {
                    $elements = get_post_meta($post_id, '_bricks_page_footer_2', true);

                    if ($elements) {
                        $post_meta_key = '_bricks_page_footer_2';
                    }
                }
            }

            // No 'header' or 'footer' data: Check for 'content' post meta
            if (!$elements) {
                $elements = get_post_meta($post_id, '_bricks_page_content_2', true);

                if ($elements) {
                    $post_meta_key = '_bricks_page_content_2';
                }
            }

            if ($elements && $post_meta_key) {
                foreach ($elements as &$element) {
                    if($logical){
                        $element['settings'] = AT__Conversion::convert_settings_to_logical_properties($element['settings']);
                    } else {
                        $element['settings'] = AT__Conversion::convert_settings_to_directional_properties($element['settings']);
                    }
                }
                unset($element);

                // Update the post meta with the converted elements
                update_post_meta($post_id, $post_meta_key, $elements);
            }
        }
    } 

    public static function convert_to_logical_properties_callback(){
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }
        
        $checked_data = $_POST['checked_data'];
        $logical = $_POST['logical'] ? json_decode($_POST['logical']) : true;

        if(!is_array($checked_data)){
            return;
        }

        $response = [];
        
        // Elements
        if (in_array('elements', $checked_data)) {
            $post_ids = \Bricks\Helpers::get_all_bricks_post_ids();
            self::loop_elements_and_convert_values($post_ids, $logical);
            $response[] = 'Elements in Pages/Posts';
        }

        // Templates
        if (in_array('templates', $checked_data)) {
            $post_ids = \Bricks\Templates::get_all_template_ids();
            self::loop_elements_and_convert_values($post_ids, $logical);
            $response[] = 'Elements in Templates';
        }

        // Global Classes
        if(in_array('global-classes', $checked_data)){
            $global_classes = get_option('bricks_global_classes', [] );
            foreach($global_classes as &$class){
                if($logical){
                    $class['settings'] = AT__Conversion::convert_settings_to_logical_properties($class['settings']);
                } else {
                    $class['settings'] = AT__Conversion::convert_settings_to_directional_properties($class['settings']);
                }
            }
            unset($class);
            update_option( 'bricks_global_classes', $global_classes);
            
            $response[] = 'Global Classes';
        }

        // Components
        if (in_array('components', $checked_data)) {
            $components = get_option('bricks_components', [] );

            foreach ($components as &$component) {
                if (isset($component['elements']) && is_array($component['elements'])) {
                    foreach ($component['elements'] as &$element) {
                        if($logical){
                            $element['settings'] = AT__Conversion::convert_settings_to_logical_properties($element['settings']);
                        } else {
                            $element['settings'] = AT__Conversion::convert_settings_to_directional_properties($element['settings']);
                        }
                    }
                    unset($element);
                }
            }
            unset($component);
            update_option( 'bricks_components', $components);

            $response[] = 'Components';
        }

        wp_send_json_success($response);
    }

    public static function convert_to_css_grid_utility_classes_callback() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
    
        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }
    
        $checked_data = isset($_POST['checked_data']) ? (array) $_POST['checked_data'] : [];
    
        if (empty($checked_data)) {
            wp_send_json_error('No items selected.');
        }

        if (!in_array('global-classes', $checked_data, true)) {
            return;
        }

        $response = [];

        try {
            AT__Conversion::convert_grid_utility_classes_function();
            $response[] = 'Global Classes';
        } catch (\Exception $e) {
            wp_send_json_error([
                'error' => 'Conversion failed.',
                'message' => $e->getMessage(),
            ]);
        }

    
        if (empty($response)) {
            wp_send_json_error('No valid conversion options were processed.');
        }
    
        wp_send_json_success($response);
    }
    
    
    private static function get_used_classes_from_post_ids($post_ids) {
        $all_post_details = [];
    
        foreach ($post_ids as $post_id) {
            $post_type = get_post_type($post_id);
            $post_meta_key = null;
            $elements = null;
    
            // Get content type (header, content, footer) & elements
            if ($post_type === 'bricks_template') {
                $elements = get_post_meta($post_id, '_bricks_page_header_2', true);
                $post_meta_key = $elements ? '_bricks_page_header_2' : null;
    
                if (!$elements) {
                    $elements = get_post_meta($post_id, '_bricks_page_footer_2', true);
                    $post_meta_key = $elements ? '_bricks_page_footer_2' : null;
                }
            }
    
            // No 'header' or 'footer' data: Check for 'content' post meta
            if (!$elements) {
                $elements = get_post_meta($post_id, '_bricks_page_content_2', true);
                $post_meta_key = $elements ? '_bricks_page_content_2' : null;
            }
    
            if ($elements && $post_meta_key) {
                $post_global_classes = [];
                $post_css_classes = [];
                foreach ($elements as $element) {
                    if (isset($element['settings'])) {
                        $settings = $element["settings"];
    
                        if (!empty($settings["_cssGlobalClasses"])) {
                            $post_global_classes = array_merge($post_global_classes, $settings["_cssGlobalClasses"]);
                        }
    
                        if (!empty($settings["_cssClasses"])) {
                            $cssClasses = explode(" ", $settings["_cssClasses"]);
                            $post_css_classes = array_merge($post_css_classes, $cssClasses);
                        }
                    }
                }
                $post_details = [
                    "post_id" => $post_id,
                    "post_type" => $post_type,
                    "post_slug" => get_post_field('post_name', $post_id),
                    "post_title" => get_post_field('post_title', $post_id),
                    "post_url" => get_post_permalink($post_id),
                    "classes" => [
                        "global" => array_values(array_unique($post_global_classes)),
                        "css" => array_values(array_unique($post_css_classes))
                    ]
                ];
                $all_post_details[] = $post_details;
            }
        }
    
        return $all_post_details;
    }

    private static function get_used_classes_from_components() {
        $components = get_option('bricks_components', [] );
        $components_details = [];
        // Loop through all components
        foreach ($components as $component) {
            $has_class = false;
            $global_classes = [];
            $css_classes = [];
            if (isset($component['elements']) && is_array($component['elements'])) {
                foreach ($component['elements'] as $element) {
                    if (isset($element['settings'])) {
                        $settings = $element["settings"];

                        // Global Classes
                        if (!empty($settings["_cssGlobalClasses"])) {
                            $has_class = true;
                            $temp_global_classes = $settings["_cssGlobalClasses"];
                            $global_classes = array_merge($global_classes, $temp_global_classes);
                        }

                        // CSS Classes
                        if (!empty($settings["_cssClasses"])) {
                            $has_class = true;
                            $temp_css_classes = explode(" ", $settings["_cssClasses"]);
                            $css_classes = array_merge($css_classes, $temp_css_classes);
                        }
                    }
                }
            }
            if($has_class){
                $components_details[] = [
                    "component_id" => $component["id"],
                    "classes" => [
                        "global" => array_values(array_unique($global_classes)),
                        "css" => array_values(array_unique($css_classes))
                    ]
                ];
            }
        }
        return $components_details;
    }
    

    public static function get_used_classes_callback(){
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'openai_ajax_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }

        $global_classes = [];
        $css_classes = [];
        
        // Elements & Templates
        $page_ids = \Bricks\Helpers::get_all_bricks_post_ids();
        $template_ids = \Bricks\Templates::get_all_template_ids();
        $post_ids = array_merge($page_ids, $template_ids);
        
        $post_details = self::get_used_classes_from_post_ids($post_ids);
        $component_details = self::get_used_classes_from_components();
        
        
        $response = [
            "posts" => $post_details,
            "components" => $component_details,
        ];


        wp_send_json_success($response);
    }

	public static function save_full_access_ajax_function(){

        if (!current_user_can('manage_options') ) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
     
        $option = get_option('bricks_advanced_themer_builder_settings', []);
		$fullAccess = isset($_POST['fullAccess']) ? $_POST['fullAccess'] : false;

		if($fullAccess === false){
			wp_send_json_error('Data error');
		}

        $data = $fullAccess;
        $option['full_access'] = $data;
        update_option('bricks_advanced_themer_builder_settings', $option);

        wp_send_json_success($option);
    }

    public static function save_custom_components_ajax_function() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
    
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'openai_ajax_nonce')) {
            die('Invalid nonce');
        }
    
        // Get existing options
        $option = get_option('bricks_advanced_themer_builder_settings', []);
        
        // Get posted data
        $customComponentsElements = isset($_POST['customComponentsElements']) ? stripslashes_deep($_POST['customComponentsElements']) : false;
        $customComponentsCategories = isset($_POST['customComponentsCategories']) ? stripslashes_deep($_POST['customComponentsCategories']) : false;
    
        // Validate data
        if ($customComponentsElements === false || $customComponentsCategories === false) {
            wp_send_json_error('Data error');
        }
    
        // Decode JSON data
        $customComponentsElements = json_decode($customComponentsElements, true);
        $customComponentsCategories = json_decode($customComponentsCategories, true);
    
        // Update options
        $option['custom_components_elements'] = $customComponentsElements;
        $option['custom_components_categories'] = $customComponentsCategories;
        
        // Save updated options
        update_option('bricks_advanced_themer_builder_settings', $option);
    
        wp_send_json_success($option);
    }

    /**
     * Save the content to the child theme's style.css file
     */
    private static function save_child_theme_css($css_content) {
        // Initialize the WordPress filesystem
        global $wp_filesystem;
        
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            WP_Filesystem();
        }
    
        // Get the child theme directory
        $child_theme_path = get_stylesheet_directory(); // Child theme directory path
    
        // Define the full path to the style.css file
        $style_css_path = $child_theme_path . '/style.css';
    
        // Check if the file exists and is writable
        if ($wp_filesystem->exists($style_css_path) && $wp_filesystem->is_writable($style_css_path)) {

            // Remove slashes for single quotes
            $css_content = stripslashes($css_content);

            // Write the new content to style.css
            if ($wp_filesystem->put_contents($style_css_path, $css_content, FS_CHMOD_FILE)) {
                return true; // File updated successfully
            } else {
                return false; // Failed to write to file
            }
        } else {
            return false; // File does not exist or is not writable
        }
    }
    private static function save_css_to_uploads($id, $css_content) {
        // Ensure the ID and CSS content are valid strings
        if (empty($id)) {
            return new \WP_Error('invalid_data', 'Invalid ID.');
        }
    
        // Get the upload directory
        $upload_dir = wp_upload_dir();
        $css_dir = trailingslashit($upload_dir['basedir']) . 'advanced-themer/css/';
        
        // Use the WordPress filesystem API
        global $wp_filesystem;
        
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            WP_Filesystem();
        }
    
        // Check if filesystem was initialized correctly
        if (!WP_Filesystem()) {
            return new WP_Error('filesystem_error', 'Could not initialize the filesystem.');
        }
    
        // Create the directory if it doesn't exist
        if (!wp_mkdir_p($css_dir)) {
            return new WP_Error('mkdir_failed', 'Failed to create the necessary directories.');
        }
    
        // Set the file path
        $file_path = $css_dir . 'at-' . sanitize_file_name($id) . '.css';
    
        // Remove existing file if it exists
        if ($wp_filesystem->exists($file_path)) {
            $wp_filesystem->delete($file_path);
        }
    
        // Write the new CSS file
        if (!$wp_filesystem->put_contents($file_path,  AT__Helpers::sanitize_css_content($css_content), FS_CHMOD_FILE)) {
            return new WP_Error('file_write_error', 'Failed to write the CSS file.');
        }
    
        return $file_path; // Return the path of the saved file
    }
    
    private static function remove_css_from_uploads($id) {
        // Ensure the ID is valid
        if (empty($id)) {
            return new \WP_Error('invalid_data', 'Invalid ID.');
        }
    
        // Get the upload directory
        $upload_dir = wp_upload_dir();
        $css_dir = trailingslashit($upload_dir['basedir']) . 'advanced-themer/css/';
        
        // Use the WordPress filesystem API
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            WP_Filesystem();
        }
    
        // Check if filesystem was initialized correctly
        if (!WP_Filesystem()) {
            return new \WP_Error('filesystem_error', 'Could not initialize the filesystem.');
        }
    
        // Set the file path
        $file_path = $css_dir . 'at-' . sanitize_file_name($id) . '.css';

        // Check if the file exists, and then delete it
        if ($wp_filesystem->exists($file_path)) {
            if ($wp_filesystem->delete($file_path)) {
                return true; // Successfully deleted
            } else {
                return new \WP_Error('delete_failed', 'Failed to delete the CSS file.');
            }
        }
    }

    private static function filter_non_ajax_elements($elements) {
        $filtered_elements = array_filter($elements, function($element) {
            return isset($element['saveMethod']) && $element['saveMethod'] == 'ajax';
        });

        return array_values($filtered_elements);
    }

    private static function filter_wpcodebox_elements($elements) {
        $filtered_elements = array_filter($elements, function($element) {
            return isset($element['category']) && strpos($element['category'], 'wpcodebox') === 0;
        });

        return array_values($filtered_elements);
    }

    private static function save_advanced_css_data($advanced_css) {
        $option = get_option('bricks_global_settings');
        $at_option = get_option('bricks_advanced_themer_builder_settings', []);
        
        $items_to_save = self::filter_non_ajax_elements($advanced_css);
    
        foreach ($items_to_save as $key => $item) {
            // Remove item
            if (isset($item['removed']) && $item['removed'] == true) {
                unset($items_to_save[$key]);
                if (!isset($item['typeLabel']) || $item['typeLabel'] != "recipe") {
                    self::remove_css_from_uploads($item['id']);
                }
                continue;
            }
    
            // Global CSS
            if ($item['id'] === "at-global-css" && isset($item['hasChanged']) && $item['hasChanged'] == true) {
                $option['customCss'] = $item['contentCss'];
                update_option('bricks_global_settings', $option);
                continue;
            }
    
            // Child theme CSS
            if ($item['id'] === "at-child-css" && isset($item['hasChanged']) && $item['hasChanged'] == true) {
                $result = self::save_child_theme_css($item['contentCss']);
                if (!$result) {
                    return new WP_Error('save_failed', 'Failed to update child theme style.css');
                }
                continue;
            }
    
            // Custom or AT Framework files
            if (
                in_array($item['category'], ['custom', 'at framework']) &&
                isset($item['hasChanged'], $item['typeLabel'], $item['contentCss']) &&
                $item['hasChanged'] == true &&
                $item['typeLabel'] != "recipe" &&
                !empty($item['contentCss'])
            ) {
                self::save_css_to_uploads($item['id'], $item['contentCss']);
            }
        }
    
        $at_option['advanced_css'] = $items_to_save;
        update_option('bricks_advanced_themer_builder_settings', $at_option);
    
        // WPCodeBox
        $wpcodebox_items = self::filter_wpcodebox_elements($advanced_css);
        if (AT__Helpers::is_array($wpcodebox_items)) {
            if (
                class_exists('\Wpcb2\Api\Api') &&
                class_exists('\Wpcb2\Repository\SnippetRepository') &&
                class_exists('\Wpcb2\Service\SnippetToResponseMapper')
            ) {
                $wpcbApi = new \Wpcb2\Api\Api();
                foreach ($wpcodebox_items as $key => $item) {
                    if (isset($item['removed']) && $item['removed'] == true) {
                        $wpcbApi->deleteSnippet($item['id']);
                        continue;
                    }
    
                    if (isset($item['hasChanged']) && $item['hasChanged'] == true) {
                        $type = $item['type'] === 'css' ? 'contentCss' : 'contentSass';
                        $fieldsMap = [
                            $type => 'code',
                            'codeType' => ['value' => $type],
                            'label' => 'title',
                            'message' => 'description',
                            'status' => 'enabled',
                        ];
    
                        $snippetRepository = new \Wpcb2\Repository\SnippetRepository();
                        $postMapper = new \Wpcb2\Service\SnippetToResponseMapper();
    
                        $internalSnippet = $snippetRepository->getSnippet($item['id']);
                        $data = $postMapper->mapSnippetToResponse($internalSnippet);
    
                        foreach ($fieldsMap as $itemKey => $dataKey) {
                            if (isset($item[$itemKey])) {
                                $data[$dataKey] = stripslashes($item[$itemKey]);
                            }
                        }
    
                        try {
                            $wpcbApi->updateSnippet($item['id'], $data);
                            if (isset($data['enabled'])) {
                                $snippetRepository->updateSnippet($item['id'], [
                                    'enabled' => (int)$data['enabled']
                                ]);
                            }
                        } catch (\Exception $e) {
                            return new WP_Error('wpcodebox_update_error', $e->getMessage());
                        }
                    }
                }
            } else {
                return new WP_Error('wpcodebox_missing_classes', 'WPCodeBox Classes not found! Upgrade WPCodeBox!');
            }
        }
    
        return true;
    }

    public static function save_advanced_css_ajax_function() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
    
        if (!wp_verify_nonce($_POST['nonce'], 'openai_ajax_nonce')) {
            die('Invalid nonce');
        }
    
        $advanced_css = $_POST['advanced_css'] ?? [];
        $result = self::save_advanced_css_data($advanced_css);
    
        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }
    
        wp_send_json_success('CSS saved successfully.');
    }

    public static function generated_html_ajax_function() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
    
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'openai_ajax_nonce')) {
            die('Invalid nonce');
        }
    
        // Get existing options
        $element = $_POST['element'];
        $html = \Bricks\Frontend::render_element($element);
    
        wp_send_json_success($html);
    }   

    public static function generated_html_multiple_elements_ajax_function() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
    
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'openai_ajax_nonce')) {
            die('Invalid nonce');
        }
    
        // Get existing options
        $elements = $_POST['elements'];
        $html = \Bricks\Frontend::render_data($elements, 'content');
    
        wp_send_json_success($html);
    } 

    public static function get_remote_templates_data_ajax_function(){
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
    
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'openai_ajax_nonce')) {
            die('Invalid nonce');
        }
        $data = \Bricks\Templates::get_remote_templates_data();
    
        wp_send_json_success($data);
    }

    public static function get_templates_data_ajax_function(){
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
    
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'openai_ajax_nonce')) {
            die('Invalid nonce');
        }
        $data = \Bricks\Templates::get_templates();
    
        wp_send_json_success($data);
    }

    public static function convert_template_data_ajax_function(){
        // Check if user has permission
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
            return;
        }
    
        // Check if nonce is set and valid
        if (!isset($_POST['atnonce']) || !wp_verify_nonce($_POST['atnonce'], 'openai_ajax_nonce')) {
            wp_send_json_error('Invalid nonce');
            return;
        }
    
        $bricksTemplates = new \Bricks\Templates();
        $data = $bricksTemplates->convert_template();

        if (is_wp_error($data)) {
            wp_send_json_error($data->get_error_message());
            return;
        }

        //wp_send_json_success($data);
    }

    public static function save_template_data_ajax_function(){
        // Check if user has permission
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
            return;
        }
    
        // Check if nonce is set and valid
        if (!isset($_POST['atnonce']) || !wp_verify_nonce($_POST['atnonce'], 'openai_ajax_nonce')) {
            wp_send_json_error('Invalid nonce');
            return;
        }
    
        $bricksTemplates = new \Bricks\Templates();
        $data = $bricksTemplates->save_template();

        if (is_wp_error($data )) {
            wp_send_json_error($data->get_error_message());
            return;
        }

        //wp_send_json_success($data);
    }

    public static function save_grid_guide_ajax_function(){

        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
        $option = get_option('bricks_advanced_themer_builder_settings', []);
        $option['gridGuide'] = $_POST['grid'];
        update_option('bricks_advanced_themer_builder_settings', $option);

        wp_send_json_success($option);
    }

    public static function save_grid_utility_classes_ajax_function(){
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
        $option = get_option('bricks_advanced_themer_builder_settings', []);
        $option['grid_utility_classes'] = $_POST['gridClasses'];
        update_option('bricks_advanced_themer_builder_settings', $option);

        wp_send_json_success($option);
    }

    public static function save_right_shortcuts_ajax_function(){

        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
        $option = get_option('bricks_advanced_themer_builder_settings', []);
        $option['rightShortcuts'] = $_POST['shortcuts'];
        update_option('bricks_advanced_themer_builder_settings', $option);

        wp_send_json_success($option);
    }

    public static function save_query_manager_ajax_function(){

        if (!current_user_can('manage_options') ) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
     
        $option = get_option('bricks_advanced_themer_builder_settings', []);
        $post = $_POST['query_manager'] ?? [];
        $cats = $_POST['query_manager_cats'] ?? [];

        if (AT__Helpers::is_array($post)) {
            foreach ($post as &$item) {
                $item['args'] = stripslashes($item['args']);
                $item['description'] = stripslashes($item['description']);
            }
            // Remove the reference to avoid potential issues
            unset($item);
        }
        $option['query_manager'] = $post;
        $option['query_manager_cats'] = $cats;
        update_option('bricks_advanced_themer_builder_settings', $option);

        // wp_send_json_success($option);
    }

    public static function save_prompt_manager_ajax_function(){
        if (!current_user_can('manage_options') ) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
     
        $option = get_option('bricks_advanced_themer_builder_settings', []);
        $post = $_POST['prompt_manager'] ?? [];

        if (AT__Helpers::is_array($post)) {
            $post = array_map(function ($item) {
                return [
                    'label' => stripslashes($item['label']),
                    'prompt' => stripslashes($item['prompt']),
                ] + $item; // Merge with other keys in the original item if needed
            }, $post);
        }
        $option['prompt_manager'] = $post;
        update_option('bricks_advanced_themer_builder_settings', $option);

        // wp_send_json_success($option);
    }

    public static function save_builder_settings_ajax_function(){
        if (!current_user_can('manage_options') ) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
     
        $option = get_option('bricks_advanced_themer_builder_settings', []);
        $post = $_POST['local_storage'] ?? '';
        $option['builderSettings'] = $post;

        update_option('bricks_advanced_themer_builder_settings', $option);

        wp_send_json_success($option);
    }

    public static function get_framework_values(){
        if (!current_user_can('manage_options') ) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
        //wp_send_json_success();
        wp_send_json_success(AT__Framework::$at_framework);
    }
    
    public static function convert_hide_remove_settings_callback(){
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }
        
        $checked_data = $_POST['checked_data'];

        if(!is_array($checked_data)){
            return;
        }

        $response = [];
        
        // Elements
        $post_ids = \Bricks\Helpers::get_all_bricks_post_ids();
        self::loop_elements_and_convert_to_hide_remove($post_ids);
        $response[] = 'Elements in Pages/Posts';

        // Templates
        $post_ids = \Bricks\Templates::get_all_template_ids();
        self::loop_elements_and_convert_to_hide_remove($post_ids);
        $response[] = 'Elements in Templates';

        wp_send_json_success($response);
    }

    private static function loop_elements_and_convert_to_hide_remove($post_ids){
        foreach ($post_ids as $post_id) {
            $post_type = get_post_type($post_id);
            $post_meta_key = null;
            $elements = null;

            // Get content type (header, content, footer) & elements
            if ($post_type === 'bricks_template') {
                $elements = get_post_meta($post_id, '_bricks_page_header_2', true);

                if ($elements) {
                    $post_meta_key = '_bricks_page_header_2';
                } else {
                    $elements = get_post_meta($post_id, '_bricks_page_footer_2', true);

                    if ($elements) {
                        $post_meta_key = '_bricks_page_footer_2';
                    }
                }
            }

            // No 'header' or 'footer' data: Check for 'content' post meta
            if (!$elements) {
                $elements = get_post_meta($post_id, '_bricks_page_content_2', true);

                if ($elements) {
                    $post_meta_key = '_bricks_page_content_2';
                }
            }

            if ($elements && $post_meta_key) {
                foreach ($elements as &$element) {
                    $element['settings'] = AT__Conversion::convert_settings_to_hide_remove_properties($element['settings']);
                }
                unset($element);

                // Update the post meta with the converted elements
                update_post_meta($post_id, $post_meta_key, $elements);
            }
        }
    } 
}