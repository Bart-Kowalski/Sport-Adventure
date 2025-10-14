<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include Composer autoloader

use ScssPhp\ScssPhp\Compiler;


class AT__Frontend{
    public static function generate_css_for_global_page_transitions() {
        global $brxc_acf_fields;

        if (is_admin() || !AT__Helpers::is_value($brxc_acf_fields, 'activate_global_page_transition') ) {
            return;
        }
        wp_enqueue_style('brxc-page-transition');

        $custom_css = '';
        $transitions = [
            'old' => '::view-transition-old(root)',
            'new' => '::view-transition-new(root)'
        ];
    
        $properties_map = [
            'keyframes' => 'animation-name',
            'duration' => 'animation-duration',
            'delay' => 'animation-delay',
            'timing' => 'animation-timing-function',
            'fill' => 'animation-fill-mode',
        ];
    
        foreach ($transitions as $key => $selector) {
            $custom_css_part = '';
    
            foreach ($properties_map as $field_suffix => $css_property) {
                $acf_field = "global_page_transition_{$field_suffix}_{$key}";
    
                if (!empty($brxc_acf_fields[$acf_field])) {
                    $value = sanitize_text_field($brxc_acf_fields[$acf_field]);

                    if($field_suffix === 'keyframes'){
                        $value = wp_strip_all_tags($value);
                        $custom_css .= "@keyframes global-page-transition-{$key} {$value}";
                        $custom_css_part .= "{$css_property}:global-page-transition-{$key};";
                    } else {
                        $unit = ($field_suffix === 'duration' || $field_suffix === 'delay') ? 'ms' : '';
                        if($value !== 'default') {
                            $custom_css_part .= "{$css_property}:" . esc_attr($value) . "{$unit};";
                        }
                    }
                }
            }
    
            if ($custom_css_part !== '') {
                $custom_css .= "{$selector} { {$custom_css_part} }";
            }
        }

        if($custom_css !== ''){

            wp_add_inline_style( 'brxc-page-transition',  $custom_css );

        }
    }

    public static function generate_css_for_frontend(){

        global $brxc_acf_fields;

        if(!AT__Helpers::is_array($brxc_acf_fields, 'theme_settings_tabs')) {

            return false;

        }
        
        $custom_css = '';

        //  Light Colors
        if (AT__Helpers::is_global_colors_category_activated() ){
            

            $global_colors = AT__Global_Colors::load_converted_colors_variables_on_frontend();

            if($global_colors && is_array($global_colors) && !empty($global_colors)){
                // @property
                $custom_css .= $global_colors[2];

                // Light Colors
                $custom_css .= ':root,.brxc-light-colors, html[data-theme="dark"] .brxc-reversed-colors, html[data-theme="light"] .brxc-initial-colors{';
                $custom_css .= $global_colors[0];
                $custom_css .= '}';
            }
        }

        // Dark Colors
        if (AT__Helpers::is_global_colors_category_activated() && AT__Helpers::is_value($brxc_acf_fields, 'enable_dark_mode_on_frontend') ){

            $global_colors = AT__Global_Colors::load_converted_colors_variables_on_frontend();

            if($global_colors && is_array($global_colors) && !empty($global_colors)){

                $custom_css .= 'html[data-theme="dark"],.brxc-dark-colors, html[data-theme="light"] .brxc-reversed-colors, html[data-theme="dark"] .brxc-initial-colors{';
                $custom_css .= $global_colors[1];
                $custom_css .= '}';
            
            }

        }

        return $custom_css;

    }

    public static function generate_theme_variables() {
        $variables = [];
    
        // Bricks < 2.0
        if (property_exists(\Bricks\Theme_Styles::class, 'active_settings')) {
            $settings = \Bricks\Theme_Styles::$active_settings;
    
            if (
                AT__Helpers::is_array($settings, 'general') &&
                AT__Helpers::is_array($settings['general'], '_cssVariables')
            ) {
                $variables = $settings['general']['_cssVariables'];
            }
    
        // Bricks ≥ 2.0
        } else {
            $settings_list = \Bricks\Theme_Styles::$settings_by_id ?? [];
    
            foreach ($settings_list as $setting) {
                if (
                    AT__Helpers::is_array($setting, 'general') &&
                    AT__Helpers::is_array($setting['general'], '_cssVariables')
                ) {
                    $variables = array_merge($variables, $setting['general']['_cssVariables']);
                }
            }
        }
    
        if (empty($variables)) {
            return '';
        }
    
        $css = '';
    
        foreach ($variables as $variable) {
            $name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower(trim($variable['name'] ?? '')));
            $value = $variable['value'] ?? '';
    
            if ($name && $value !== '') {
                $css .= "--{$name}: {$value};";
            }
        }
    
        return $css;
    }    


    public static function load_variables_on_frontend() {

        global $brxc_acf_fields;

        // Skip if post has gutenberg blocks
        if(AT__Helpers::has_any_block()){
            return;
        }

        // Don't enqueue inside the builder for Full Access only
        if ((bricks_is_builder() || bricks_is_builder_iframe()) && (class_exists('Bricks\Capabilities') && \Bricks\Capabilities::current_user_has_full_access() === true)){
            return; 
        }

        $custom_css = '';

        $custom_css .= self::generate_css_for_frontend();

        $allowed_css_tags = [
            'color' => [], // Allows <color> tags if needed, but with no attributes
        ];
        
        $sanitized_css = wp_kses( trim($custom_css), $allowed_css_tags );

        if($custom_css !== ''){

            wp_add_inline_style( 'bricks-advanced-themer',  $sanitized_css );

        }

    }

    public static function enqueue_gutenberg_colors_in_iframe(){
        global $brxc_acf_fields;
        
        if ( !AT__Helpers::has_any_block() ){

            return;

        }

        wp_enqueue_style('bricks-advanced-themer');

        $custom_css = '';

        $custom_css .= self::generate_css_for_frontend();

        if ( AT__Helpers::is_global_colors_category_activated() === true && AT__Helpers::is_value($brxc_acf_fields, 'replace_gutenberg_palettes') ){

            $custom_css .= self::bricks_colors_gutenberg();


        }

        $allowed_css_tags = [
            'color' => [], // Allows <color> tags if needed, but with no attributes
        ];
        
        $sanitized_css = wp_kses( trim($custom_css), $allowed_css_tags );

        if($sanitized_css !== ''){

            wp_add_inline_style( 'bricks-advanced-themer', $sanitized_css );

        }
    }

    public static function generate_array_scoped_variables_on_classes(){
        // Get options from the global setting
        $options = get_option('bricks_global_classes', []);

        // Filter options array to remove invalid entries
        $options = array_filter($options, function($class) {
            return isset($class['id'], $class['name']) && AT__Helpers::is_array($class['settings'], '_scopedVariables');
        });

        // If options are not set or not an array, return early
        if (!AT__Helpers::is_array($options)) {
            return false;
        }
        
        // Initialize an empty array to store classes
        $classes_array = [];
    
        // Iterate through each option
        foreach ($options as $class) {
    
            // Initialize an array to store class details
            $item = [
                'id' => esc_attr($class['id']),
                'isClass' => true,
                'selector' => esc_attr($class['name']),
                'settings' => [],
            ];
    
            // Iterate through each scoped variable
            foreach ($class['settings']['_scopedVariables'] as $variable) {
                // Check if required keys are set and not empty
                if (!AT__Helpers::is_value($variable, 'title') || !AT__Helpers::is_value($variable, 'cssVarValue')) {
                    continue;
                }
    
                // Add variable details to the class settings
                $item['settings'][] = [
                    'property' => esc_attr($variable['title']),
                    'value' => esc_attr($variable['cssVarValue']),
                ];
            }
    
            // Add class details to the classes array
            $classes_array[] = $item;
        }

        return $classes_array;
    }
    

    public static function bricks_colors_gutenberg() {

        global $brxc_acf_fields;

        if ( AT__Helpers::is_global_colors_category_activated() === false || !AT__Helpers::is_value($brxc_acf_fields, 'replace_gutenberg_palettes') ){
            return;
        }
    	
        $gutenberg_colors_frontend_css = ".has-text-color{color: var(--brxc-gutenberg-color)}.has-background,.has-background-color,.has-background-dim{background-color: var(--brxc-gutenberg-bg-color)}.has-border,.has-border-color{border-color: var(--brxc-gutenberg-border-color)}";
    	$bricks_palettes = get_option('bricks_color_palette', []);

        foreach( $bricks_palettes as $bricks_palette ) {
            if ( AT__Helpers::is_array($bricks_palette, 'colors') ){
                foreach( $bricks_palette['colors'] as $color ) {

                    $name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower(trim($color['name'])));
                    $final_color = '';

                    foreach(['hex', 'rgb','hsl','raw'] as $format){
                        if( isset($color[$format] )){
                            $final_color = $color[$format];
                        }
                    }
                    $gutenberg_colors_frontend_css .= '[class*="has-' . _wp_to_kebab_case($name) . '-color"]{--brxc-gutenberg-color:' . $final_color . ';}[class*="has-' . _wp_to_kebab_case($name) . '-background-color"]{--brxc-gutenberg-bg-color:' . $final_color . ';}[class*="has-' . _wp_to_kebab_case($name) . '-border-color"]{--brxc-gutenberg-border-color:' . $final_color . ';}';
                }
            }
        }

        $gutenberg_colors_frontend_css = wp_strip_all_tags(trim($gutenberg_colors_frontend_css));

        return $gutenberg_colors_frontend_css;
    
    }

    public static function remove_default_gutenberg_presets() {

        global $brxc_acf_fields;
        
        if ( !AT__Helpers::is_value($brxc_acf_fields, 'remove_default_gutenberg_presets') ){

           return;

        }

        remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
        remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
        remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

    }

    public static function meta_theme_color_tag() {

        // Set control in the page settings
        add_filter( 'builder/settings/page/controls_data', function( $data ) {

            $data['controls']['metaThemeColorSeparator'] = [
                'group'       => 'general',
                'type'        => 'separator',
                'label'       => esc_html__( 'Theme Color', 'bricks' ),
                'description' => esc_html__( 'Add <meta name="theme-color"> to the head of this page.', 'bricks' ),
            ];
            $data['controls']['metaThemeColor'] = [
                'group'       => 'general',
                'type'        => 'color',
                'label'       => esc_html__( 'Meta Theme Color', 'bricks' ),
                'description' => esc_html__( 'The meta tag doesn\'t support CSS variables - choose one of the following format: HEX, RGBA, HSLA.', 'bricks' ),
            ];
           
            return $data;
        } );

        // Add the meta tag
        add_action('bricks_meta_tags', function(){

            global $brxc_acf_fields;

            $color = false;

            // Global Value (ACF)

            if( AT__Helpers::is_value($brxc_acf_fields, 'global_meta_theme_color') ) {

                $color = $brxc_acf_fields['global_meta_theme_color'];

            } 

            // Page Value (Builder)
            $page_data = \bricks\Database::$page_data;

            if(isset($page_data) && isset($page_data['settings']) ){

                $settings = $page_data['settings'];

                if( isset($settings) && isset($settings['metaThemeColor']) ) {

                    if ( isset($settings['metaThemeColor']['rgb'])){
    
                        $color = $settings['metaThemeColor']['rgb'];
        
                    } elseif ( isset($settings['metaThemeColor']['hsl'])){
        
                        $color = $settings['metaThemeColor']['hsl'];
        
                    } elseif( isset($settings['metaThemeColor']['hex'])){
        
                        $color = $settings['metaThemeColor']['hex'];
        
                    }
    
                }
            }

            if(!$color) return;
            
            echo '<meta name="theme-color" content="' . $color . '" />';
            
            return;
        });
    
    }

    private static function filter_active_templates($array, &$unique_values = []) {
        $result = [];
    
        foreach ($array as $key => $value) {
            if (is_int($value) && $value !== 0) {
                // Check if value has already been encountered
                if (!in_array($value, $unique_values, true)) {
                    $unique_values[] = $value;
                    $result[$key] = $value;
                }
            } elseif (is_array($value)) {
                // Recursively filter the nested array
                $nested_result = self::filter_active_templates($value, $unique_values);
                if (!empty($nested_result)) {
                    $result[$key] = $nested_result;
                }
            }
        }
    
        return $result;
    }

    public static function update_mixins_before_element_render(){
        if(is_admin() || !is_singular()) {
            return;
        }
        global $brxc_acf_fields;

        if (!is_array($brxc_acf_fields)) {
            return;
        }

        $is_advancedcss_enabled = $brxc_acf_fields['advanced_css_enable_sass'];
        if(!$is_advancedcss_enabled ) return;
    
        global $post; 
        if ( !$post ) return;
        
        $is_superpowercss_enabled = $brxc_acf_fields['superpowercss-enable-sass'];
        $at_global_settings_arr = get_option('bricks_advanced_themer_builder_settings', []);
        $is_wpcb_installed = false;
        $wpcb_results = [];
        $mixins_content = "";
        $mixins_last_modified = false;

        // AT settings
        if(isset($at_global_settings_arr) && isset($at_global_settings_arr['advanced_css'])){
            // Filter the array for items with type "scssp" and status "1"
            $filtered_items = array_filter($at_global_settings_arr['advanced_css'], function($item) {
                return isset($item['type'], $item['status'], $item['contentSass'], $item['lastModified']) 
                    && $item['type'] === "scssp" 
                    && $item['status'] === "1" 
                    && $item['contentSass'] !== "";
            });
            
            // Extract lastModified values and get the most recent one
            if (!empty($filtered_items)) {
                $last_modified_dates = array_map('intval', array_column($filtered_items, 'lastModified'));
                $mixins_last_modified = max($last_modified_dates);
                $mixins_content .= implode('', array_column($filtered_items, 'contentSass'));
                $mixins_content = stripslashes($mixins_content);
            }
        }

        // If none of the partials/mixins have been modified, return early
        if($mixins_last_modified === false || $mixins_last_modified === ""){
            return;
        }

        // WPCodeBox
        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        if ( is_plugin_active('wpcodebox2/wpcodebox2.php') ) {
            $is_wpcb_installed = true;
            global $wpdb;

            try {
                $table_name = $wpdb->prefix . 'wpcb_snippets';
                
                // Check if table exists first
                $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
                if (!$table_exists) {
                    error_log("WPCodeBox table '$table_name' does not exist");
                    return;
                }

                $query = "SELECT * FROM {$wpdb->prefix}wpcb_snippets WHERE codeType IN ('scssp')";
                $wpcb_results = $wpdb->get_results($query);

                // Check for database errors
                if ($wpdb->last_error) {
                    error_log('WPCodeBox database error: ' . $wpdb->last_error);
                    $wpcb_results = []; // Set to empty array to continue execution
                }

                // Check if results are valid
                if ($wpcb_results === false) {
                    error_log('WPCodeBox query failed: ' . $query);
                    $wpcb_results = [];
                }

            } catch (Exception $e) {
                error_log('WPCodeBox database exception: ' . $e->getMessage());
                $wpcb_results = [];
                $is_wpcb_installed = false;
            }
        }

        if($is_wpcb_installed && ! empty( $wpcb_results )){
            foreach ( $wpcb_results as $row ) {
                $mixins_content .= $row->original_code;
            }
        }

        if($mixins_content === "") return;

        $scss_compiler = new \ScssPhp\ScssPhp\Compiler();


        // Loop through active templates
        add_filter( 'bricks/active_templates', function( $active_templates, $post_id, $content_type ) use ( $mixins_content, $scss_compiler, $mixins_last_modified, $is_superpowercss_enabled ){
            // Get active template id's
            $array = self::filter_active_templates($active_templates);
            $timestamps = [];
            $options = [
                'mixins_content' => $mixins_content,
                'mixins_last_modified' => $mixins_last_modified, 
                'scss_compiler' => $scss_compiler,
            ];

            // run the scss convertion function for each post_meta_id
            foreach($array as $key => $id){
                if(empty($id)){
                    continue;
                }
                
                // Elements Value
                if($is_superpowercss_enabled){
                    if(is_array($id)){
                        foreach($id as $value){
                            if(is_int($value) && $value !== 0){
                                $options['key'] = $key;
                                $options['id'] = $value;
                                $options['sassKey'] = '_cssCustomSass';
                                $options['cssKey'] = '_customCss';
                                $timestamps = self::update_post_meta_scssp($options, $timestamps);
                            }
                        }
                    } else {
                        $options['key'] = $key;
                        $options['id'] = $id;
                        $options['sassKey'] = '_cssCustomSass';
                        $options['cssKey'] = '_cssCustom';
                        $timestamps = self::update_post_meta_scssp($options, $timestamps);
                    }
                }
                //Page Value (Builder)
                $options['key'] = $key . 'PageSettings';
                $options['id'] = $id;
                $options['sassKey'] = 'customSass';
                $options['cssKey'] = 'customCss';
                $options['meta'] = '_bricks_page_settings';
                $options['bricks_content'] = $bricks_post_meta = get_post_meta($id, '_bricks_page_settings');
                $options['at_post_meta'] = get_post_meta($id, '_advanced_themer_settings', true);

                $timestamps = self::compile_scss_in_page_settings($options, $timestamps);

                update_post_meta($id, '_advanced_themer_settings', serialize($timestamps));

            }

            return $active_templates;

        }, 10, 3 );

    }

    private static function update_post_meta_scssp($options, $timestamps){
        extract($options);
        $metas = [
            '_bricks_page_content_2',
            '_bricks_page_header_2',
            '_bricks_page_footer_2',
        ];
        $at_post_meta = get_post_meta($id, '_advanced_themer_settings', true);


        // $last_compiled_sass = false;
        if(isset($at_post_meta) && is_string($at_post_meta)){
            $temp = unserialize($at_post_meta);
            if( isset($temp["lastCompiledSass" . $key]) ){
                $last_compiled_sass = $temp["lastCompiledSass" . $key];
                
            }          
        }

        // Check if lastCompiledSass exists in $at_post_meta and is more recent than $mixins_last_modified
        if ($last_compiled_sass && $last_compiled_sass >= (int)($mixins_last_modified / 1000)) {
            $timestamps['lastCompiledSass' . $key] = $last_compiled_sass;
            return $timestamps;
        }
        // Loop through each meta
        foreach ( $metas as $meta){
            $bricks_content = get_post_meta( $id , $meta );

            // Update AT Settings
            $timestamps['lastCompiledSass' . $key] = time();

            if(!AT__Helpers::is_array($bricks_content) || !AT__Helpers::is_array($bricks_content[0])) {
                continue;
            }

            $options['bricks_content'] = $bricks_content[0];
            $options['meta'] = $meta;
            $options['at_post_meta'] = $at_post_meta;

            self::compile_scss_in_post_meta($options);

        }
        
        return $timestamps;        
    }

    private static function compile_scss_in_post_meta($options){
        extract($options);
        $change_found = false;
        
        if(AT__Helpers::is_array($bricks_content)){
            // Loop through the array and compile Sass to CSS
            foreach ($bricks_content as $key => $element) {
                // Ensure 'settings' is an array before processing
                if (!AT__Helpers::is_array($element, 'settings')) continue;

                // Loop through each setting in the element's settings
                foreach ($element['settings'] as $setting_key => $setting_value) {
                    $key_parts = explode(':', $setting_key);
   
                    // Only process settings that start with _cssCustomSass
                    if ($key_parts[0] !== $sassKey) continue;

                    // Compile the SASS code
                    try {
                        $sass_code = stripslashes($setting_value);
                        $compiled_css = $scss_compiler->compile($mixins_content . $sass_code);
                    } catch (\ParserException $e) {
                        // Handle SCSS compilation error if needed
                        continue; 
                    } catch (\Exception $e) {
                        // Handle SCSS compilation error if needed
                        continue; 
                    }

                    // Rebuild the corresponding _cssCustom key
                    $css_custom_key = $cssKey . (count($key_parts) > 1 ? ':' . implode(':', array_slice($key_parts, 1)) : '');
                    // If the _cssCustom key exists and is different, update it
                    if (isset($element['settings'][$css_custom_key]) && $element['settings'][$css_custom_key] !== $compiled_css) {
                        $element['settings'][$css_custom_key] = $compiled_css;
                        $change_found = true;
                    }
                }

                // Update the modified element back into the array
                $bricks_content[$key] = $element;
            }
        }
        
        if($change_found){
            update_post_meta($id, $meta, $bricks_content);
        }
    }

    private static function compile_scss_in_page_settings($options, $timestamps){
        extract($options);

        $last_compiled_sass = false;
        if(isset($at_post_meta) && is_string($at_post_meta)){
            $temp = unserialize($at_post_meta);

            if( isset($temp["lastCompiledSass" . $key]) ){
                $last_compiled_sass = $temp["lastCompiledSass" . $key];
                
            }         
        }
        $change_found = false;

        // Check if $bricks_post_meta exists and is in the expected format
        if(!AT__Helpers::is_array($bricks_content) || !AT__Helpers::is_array($bricks_content[0])) {
            $timestamps['lastCompiledSass' . $key] = $last_compiled_sass;
            return $timestamps;
        }

        // Check if lastCompiledSass exists in $at_post_meta and is more recent than $mixins_last_modified
        if (!$last_compiled_sass || $last_compiled_sass >= (int)($mixins_last_modified / 1000)) {
            $timestamps['lastCompiledSass' . $key] = $last_compiled_sass;
            return $timestamps;
        }

        $timestamps['lastCompiledSass' . $key] = time();

        foreach($bricks_content[0] as $key => $value){
            $key_parts = explode(':', $key);
            
            // Only process settings that start with sassKey
            if ($key_parts[0] !== $sassKey) continue;

            // Compile the SASS code
            try {
                $value = stripslashes($value);
                $compiled_css = $scss_compiler->compile($mixins_content . $value);
            } catch (\ParserException $e) {
                // Handle SCSS compilation error if needed
                continue; 
            } catch (\Exception $e) {
                // Handle SCSS compilation error if needed
                continue; 
            }

            // Rebuild the corresponding cssKey
            $css_custom_key = $cssKey . (count($key_parts) > 1 ? ':' . implode(':', array_slice($key_parts, 1)) : '');
            // If the _cssCustom key exists and is different, update it
            if (isset($post_meta[0][$css_custom_key]) && $post_meta[0][$css_custom_key] !== $compiled_css) {
                $post_meta[0][$css_custom_key] = $compiled_css;
                $change_found = true;
            }
        }
        
        // Update page settings
        if($change_found){
            update_post_meta($id, $meta, $bricks_content[0]);
        }

        return $timestamps;
    }

    public static function enqueue_advanced_css_files() {
        
        if ( is_admin() ) {
            return;
        }

        self::process_advanced_css_files('wp_enqueue_scripts');
    }

    public static function enqueue_advanced_css_files_gutenberg() {

        self::process_advanced_css_files('enqueue_block_editor_assets');
    }

    private static function process_advanced_css_files($hook) {
        // Get global settings
        $at_global_settings_arr = get_option('bricks_advanced_themer_builder_settings', []);
        
        if (!AT__Helpers::is_array($at_global_settings_arr, 'advanced_css')) {
            return;
        }

        // Iterate through each CSS item
        foreach ($at_global_settings_arr['advanced_css'] as $item) {
            if ( isset($item['category'], $item['typeLabel'], $item['status'], $item['contentCss']) && ($item['category'] === 'custom' || $item['category'] === 'at framework') && $item['typeLabel'] != "recipe" && $item['status'] === '1' && $item['contentCss'] !== '') {
                $filename = 'at-' . $item['id'];

                // Get the upload directory URLs
                $upload_dir = wp_upload_dir();
                $css_url = trailingslashit($upload_dir['baseurl']) . 'advanced-themer/css/' . $filename . '.css';
                $css_path = trailingslashit($upload_dir['basedir']) . 'advanced-themer/css/' . $filename . '.css';

                // Convert the 'priority' from string to integer, default to 10 if not set
                $priority = isset($item['priority']) ? intval($item['priority']) : 10;

                // Enqueue in Gutenberg
                if (isset($item['enqueueGutenberg']) && $item['enqueueGutenberg'] == 1 && $hook === 'enqueue_block_editor_assets') {
                    wp_enqueue_style($filename, $css_url, [], file_exists($css_path) ? filemtime($css_path) : null);
                }

                // Functions don't exist, exit if not available
                if (!function_exists('bricks_is_builder') || !function_exists('bricks_is_builder_iframe')) return;

                // Enqueue only on Frontend and inside the iframe
                if (isset($item['enqueueFrontend']) && $item['enqueueFrontend'] == 1 && 
                    (!bricks_is_builder() || (bricks_is_builder() && bricks_is_builder_iframe()))) {
                    
                    add_action($hook, function() use ($filename, $css_url, $css_path) {
                        wp_enqueue_style($filename, $css_url, [], file_exists($css_path) ? filemtime($css_path) : null);
                    }, $priority);
                }

                // Enqueue only in builder but not inside the iframe
                if (isset($item['enqueueBuilder']) && $item['enqueueBuilder'] == 1 && 
                    bricks_is_builder() && !bricks_is_builder_iframe()) {
                    
                    add_action($hook, function() use ($filename, $css_url, $css_path) {
                        wp_enqueue_style($filename, $css_url, [], file_exists($css_path) ? filemtime($css_path) : null);
                    }, $priority);
                }

                
            }
        }
    }

    private static function page_transition_apply_element_settings($template_id, $metas) {
        $global_css = '';
        $types = [
            "old" => "Old",
            "new" => "New",
        ];
    
        foreach ($metas as $meta) {
            $post_settings = get_post_meta($template_id, $meta);
            if (empty($post_settings)) continue;
    
            foreach ($post_settings as $elements) {
                if (!is_array($elements)) continue;
    
                foreach ($elements as $element) {
                    $settings = $element['settings'] ?? [];
                    
                    if (!empty($settings['pageTransitionName'])) {

                        wp_enqueue_style('brxc-page-transition');

                        foreach ($types as $key => $value) {
                            $animation = '';
                            $keyframe = '';
    
                            // Collect animation properties
                            $animation .= !empty($settings["pageTransitionDuration{$value}"]) ? 'animation-duration:' . $settings["pageTransitionDuration{$value}"] . ';' : '';
                            $animation .= !empty($settings["pageTransitionDelay{$value}"]) ? 'animation-delay:' . $settings["pageTransitionDelay{$value}"] . ';' : '';
                            $animation .= !empty($settings["pageTransitionTiming{$value}"]) ? 'animation-timing-function:' . $settings["pageTransitionTiming{$value}"] . ';' : '';
                            $animation .= !empty($settings["pageTransitionFillMode{$value}"]) ? 'animation-fill-mode:' . $settings["pageTransitionFillMode{$value}"] . ';' : '';
    
                            if (!empty($settings["pageTransitionKeyframe{$value}"])) {
                                $name = 'brxc-page-transition-' . $settings['pageTransitionName'] . '-' . $key;
                                $keyframe = "@keyframes {$name} " . $settings["pageTransitionKeyframe{$value}"];
                                $animation .= "animation-name: {$name};";
                            }
    
                            // Append unique CSS rules
                            if ($animation) {
                                $global_css .= $keyframe ? "{$keyframe} " : '';
                                $global_css .= "::view-transition-{$key}(" . $settings['pageTransitionName'] . "){ {$animation} }";
                            }
                        }
                    }
    
                    // Set root attributes with a single filter application
                    add_filter('bricks/element/set_root_attributes', function ($attributes, $element_data) use ($element, $settings) {
                        if ($element_data->id !== $element['id']) return $attributes;
    
    
                        if (!empty($settings['pageTransitionName'])) {
                            $name = $settings['pageTransitionName'];
                            $attributes['style'] = isset($attributes['style']) ? $attributes['style'] . "view-transition-name:{$name};" : "view-transition-name:{$name};";
                            
                        }
                        return $attributes;
                    }, 10, 2);
                }
            }
        }
    
        // Output the collected CSS
        if ($global_css) {
            wp_add_inline_style('brxc-page-transition', $global_css);
        }
    }
    
    
    

    public static function add_page_transition_css($active_templates, $post_id, $content_type) {
        global $brxc_acf_fields;

        $metas = [
            '_bricks_page_content_2',
            '_bricks_page_header_2',
            '_bricks_page_footer_2',
        ];

        $types = [
            "old" => "Old",
            "new" => "New",
        ];
    
        // Retrieve main page settings
        $content_post_id = $active_templates['content'];
        $page_settings = get_post_meta($content_post_id, "_bricks_page_settings");
  
        // Check if page transition is activated
        if ($brxc_acf_fields['enable_page_transition_page'] && !empty($page_settings[0]["activatePageTransition"])) {
            
            wp_enqueue_style('brxc-page-transition');
            
            // root css
            $settings = $page_settings[0] ?? [];
            $global_css = '';

            foreach ($types as $key => $value) {
                $animation = '';
                $keyframe = '';

                // Collect animation properties
                if (!empty($settings["pageTransitionKeyframe{$value}"])) {
                    $name = 'brxc-page-transition-root-' . $key;
                    $keyframe = "@keyframes {$name} " . $settings["pageTransitionKeyframe{$value}"];
                    $animation .= "animation-name: {$name} !important;";
                }
                $animation .= !empty($settings["pageTransitionDuration{$value}"]) ? 'animation-duration:' . $settings["pageTransitionDuration{$value}"] . ';' : '';
                $animation .= !empty($settings["pageTransitionDelay{$value}"]) ? 'animation-delay:' . $settings["pageTransitionDelay{$value}"] . ';' : '';
                $animation .= !empty($settings["pageTransitionTiming{$value}"]) ? 'animation-timing-function:' . $settings["pageTransitionTiming{$value}"] . ';' : '';
                $animation .= !empty($settings["pageTransitionFillMode{$value}"]) ? 'animation-fill-mode:' . $settings["pageTransitionFillMode{$value}"] . ';' : '';

                // Append unique CSS rules
                if (!empty($animation)) {
                    $global_css .= $keyframe ? "{$keyframe} " : '';
                    $global_css .= "::view-transition-{$key}(root){ {$animation} }";
                }
            }

            // Output the collected CSS
            if ($global_css) {
                wp_add_inline_style('brxc-page-transition', $global_css);
            }
        }

        
        // Process each template ID only once
        if(isset($brxc_acf_fields['enable_page_transition_elements']) && $brxc_acf_fields['enable_page_transition_elements']){
            $unique_templates = array_unique(array_filter($active_templates, 'is_numeric'));
            foreach ($unique_templates as $template_id) {
                if ($template_id > 0) {
                    self::page_transition_apply_element_settings($template_id, $metas);
                }
            }
        }
    
        return $active_templates;
    }

    public static function enqueue_scroll_animation_polyfill(){
        global $brxc_acf_fields;

        if(is_admin() || !AT__Helpers::is_value($brxc_acf_fields, 'scrolling_timeline_polyfill')) {
            return;
        }

        wp_enqueue_script( 'brxc-scroll-timeline');
    }
}