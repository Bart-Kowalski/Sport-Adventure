<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Builder{
    public static function setup_query_controls( $control_options ) {
        $get_option = get_option('bricks_advanced_themer_builder_settings', []);

        if (!AT__Helpers::is_array($get_option, 'query_manager') ) {
            return $control_options;
        }

        $options = $get_option['query_manager'];

        foreach ($options as $settings){
            if (isset($settings['id']) && isset($settings['title'])) {
                $control_options['queryTypes'][$settings['id']] = esc_html__( $settings['title'] );
            }
        }

        return $control_options;

    }

    public static function maybe_run_new_queries( $results, $query_obj ) {

        if (!class_exists('Bricks\Query') || !class_exists('Bricks\Helpers') || !method_exists('Bricks\Helpers', 'code_execution_enabled') || ! \Bricks\Helpers::code_execution_enabled()) {
			return [];
		}
        $get_option = get_option('bricks_advanced_themer_builder_settings', []);
    
        if (!AT__Helpers::is_array($get_option, 'query_manager')) {
            return $results;
        }
    
        $options = $get_option['query_manager'];
    
        foreach ($options as $settings){
            if (isset($settings['id']) && isset($settings['args'])) {
                if ($query_obj->object_type === $settings['id']) {
                    
                    $php_query_raw = bricks_render_dynamic_data( $settings['args'] );
                    $query_vars['posts_per_page'] = get_option( 'posts_per_page' );

                    $execute_user_code = function () use ( $php_query_raw ) {
                        $user_result = null; // Initialize a variable to capture the result of user code
        
                        // Capture user code output using output buffering
                        ob_start();
                        $user_result = eval( $php_query_raw ); // Execute the user code
                        ob_get_clean(); // Get the captured output
        
                        return $user_result; // Return the user code result
                    };
        
                    ob_start();
        
                    // Prepare & set error reporting
                    $error_reporting = error_reporting( E_ALL );
                    $display_errors  = ini_get( 'display_errors' );
                    ini_set( 'display_errors', 1 );
        
                    try {
                        $php_query = $execute_user_code();
                    } catch ( \Exception $error ) {
                        echo 'Exception: ' . $error->getMessage();
                        return $results;
                    } catch ( \ParseError $error ) {
                        echo 'ParseError: ' . $error->getMessage();
                        return $results;
                    } catch ( \Error $error ) {
                        echo 'Error: ' . $error->getMessage();
                        return $results;
                    }
        
                    // Reset error reporting
                    ini_set( 'display_errors', $display_errors );
                    error_reporting( $error_reporting );
        
                    // @see https://www.php.net/manual/en/function.eval.php
                    if ( version_compare( PHP_VERSION, '7', '<' ) && $php_query === false || ! empty( $error ) ) {
                        ob_end_clean();
                    } else {
                        ob_get_clean();
                    }

                    if ( ! empty( $php_query ) && is_array( $php_query ) ) {
                        $query_vars          = array_merge( $query_vars, $php_query );
                        $query_vars['paged'] = \Bricks\Query::get_paged_query_var( $query_vars );
                    }
        
                    $posts_query = new \WP_Query( $query_vars  );
        
                    $results = $posts_query->posts;
                }
            }
        }
        
        return $results;
    }
    
    
    public static function setup_post_data( $loop_object, $loop_key, $query_obj ) {
        $get_option = get_option('bricks_advanced_themer_builder_settings', []);

        if (!AT__Helpers::is_array($get_option, 'query_manager')) {
            return $loop_object;
        }

        $options = $get_option['query_manager'];

        foreach ($options as $settings){
            if (isset($settings['id'])) {
                if ($query_obj->object_type === $settings['id']) {
                    global $post;

                    if (isset($loop_object)) {
                        $post = get_post($loop_object);
                        setup_postdata($post);
                    }
                }
            }
        }

        return $loop_object;
    }

    public static function populate_imported_classes(){
        if( !function_exists('bricks_is_builder') || ! bricks_is_builder() || !function_exists('bricks_is_builder_iframe') || bricks_is_builder_iframe() || !\Bricks\Capabilities::current_user_has_full_access() === true) {
            return;
        }

        $brxc_imported_classes = self::populate_class_importer();
        wp_localize_script( 'brxc-builder', 'brxcImportedClasses', $brxc_imported_classes );
    }
    public static function populate_class_importer(){

        $total_classes = [];
        if ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) :
            while ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) : the_row();
                if ( have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) :
                    while ( have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) : the_row();

                        $id_stylesheet = get_sub_field('field_63b4bd5c16ac2', 'bricks-advanced-themer' );

                        $is_url = get_sub_field('field_6406649wdr55cx', 'bricks-advanced-themer' );

                        $file = $is_url ? get_sub_field('field_63b4bd5drd51x', 'bricks-advanced-themer' ) : get_sub_field('field_63b4bdf216ac7', 'bricks-advanced-themer' );

                        $classes = AT__Class_Importer::extract_selectors_from_css($file);

                        if (AT__Helpers::is_array($classes) ) {

                            foreach ( $classes as $class) {
            
                                $total_classes[] = str_replace(['.', '#'],'', esc_attr($class));
            
                            }

                        }


                    endwhile;

                endif;

            endwhile;

        endif;

        // Filter to add class: UNDOCUMENTED
        $value = '';
        $imported_classes_from_filter = apply_filters( 'at/imported_classes/import_classes', $value );
        if(AT__Helpers::is_array($imported_classes_from_filter) ){
            $classes = array_unique($imported_classes_from_filter);
            foreach ( $classes as $class ) {
                if(AT__Helpers::is_value($class) && is_string($class)){
                    $total_classes[] = esc_attr($class);
                }
            }
        }
        return $total_classes;
    }

    public static function add_modal_after_body_wrapper() {

        if (!class_exists('Bricks\Capabilities')) {

            return;
        }

        global $brxc_acf_fields;

        if( !function_exists('bricks_is_builder') || ! bricks_is_builder() || !function_exists('bricks_is_builder_iframe') || bricks_is_builder_iframe() || !\Bricks\Capabilities::current_user_has_full_access() === true) return;

        $css = '';

        if(AT__Helpers::is_builder_tweaks_category_activated() && AT__Helpers::in_array('pseudo-shortcut', $brxc_acf_fields, 'elements_shortcut_icons') ){
        // Show Open in new tab Icon
        $css .= '#bricks-panel #bricks-panel-element:not(.instance,.property) #bricks-panel-header{
            gap: 2px;
            padding-top: var(--builder-spacing);
        }
        #bricks-panel #bricks-panel-element:not(.instance,.property) #bricks-panel-header .actions,
        #bricks-structure #bricks-panel-header .actions{
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(24px, 1fr));
            justify-content: space-between;
            width: 100%;
            gap: 5px;
            margin-bottom: 22px;
        }
        #bricks-structure #bricks-panel-header .actions{
            margin-bottom: 16px;
        }
        #bricks-panel #bricks-panel-element:not(.instance,.property) #bricks-panel-header .actions li,
        #bricks-structure #bricks-panel-header .actions li {
           border-radius: var(--builder-border-radius);
           background-color: var(--builder-bg-2);
           min-width: 24px;
           width: 100%;
        }
        #bricks-panel #bricks-panel-element:not(.instance,.property) #bricks-panel-header .actions li:nth-of-type(1):after,
        #bricks-panel #bricks-panel-element:not(.instance,.property) #bricks-panel-header .actions li:nth-of-type(2):after,
        #bricks-panel #bricks-panel-element:not(.instance,.property) #bricks-panel-header .actions li:nth-of-type(3):after{
            right: unset;
            left: 0;
        }
        #bricks-panel #bricks-panel-element:not(.instance,.property) #bricks-panel-header input,
        #bricks-structure #bricks-panel-header input {
            height: auto;
            line-height: var(--builder-input-height);
        }
        #bricks-panel #bricks-panel-element:not(.instance,.property) #bricks-panel-header .actions,
        #bricks-structure #bricks-panel-header .actions {
            flex-wrap: wrap;
        }
        #bricks-panel #bricks-panel-element:not(.instance,.property) #bricks-panel-header .actions li.brxc-header-icon__before svg {
            transform: rotate(90deg);
            scale: 1.1;
         }
         
         #bricks-panel #bricks-panel-element:not(.instance,.property) #bricks-panel-header .actions li.brxc-header-icon__after svg {
            transform: rotate(-90deg);
            scale: 1.1;
         }';
        }
        

        wp_add_inline_style('bricks-advanced-themer-builder', $css, 'after');
        

        $option = get_option('bricks_advanced_themer_builder_settings', []);

        // Grid Guides
        if(isset($option['gridGuide']) ){
            $grid_guide_output = "JSON.parse('" . json_encode($option['gridGuide']) . "')";
        } else {
            $grid_guide_output = "false";
        }

        // Right Shortcuts
        if(isset($option['rightShortcuts']) ){
            $right_shortcuts_output = "JSON.parse('" . json_encode($option['rightShortcuts']) . "')";
        } else if($brxc_acf_fields['elements_shortcut_icons']){
            $right_shortcuts_output = "JSON.parse('" . json_encode($brxc_acf_fields['create_elements_shortcuts']) . "')";
        } else {
            $right_shortcuts_output = json_encode([]);
        }
        // Query Manager
        if(isset($option['query_manager']) ){
            $query_manager_output = json_encode($option['query_manager']);
        } else {
            $query_manager_output = json_encode([]);
        }

        // Query Manager Cats
        if(isset($option['query_manager_cats']) ){
            $query_manager_cats_output = json_encode($option['query_manager_cats']);
        } else {
            $query_manager_cats_output = json_encode([]);
        }

        // Full Access
        if(isset($option['full_access']) ){
            $full_access_output = json_encode($option['full_access']);
        } else {
            $full_access_output = '{}';
        }

        // Custom Components
        if(isset($option['custom_components_elements']) ){
            $custom_components_elements_output = json_encode($option['custom_components_elements']);
        } else {
            $custom_components_elements_output = json_encode([]);
        }


        if(isset($option['custom_components_categories']) ){
            $custom_components_categories_output = json_encode($option['custom_components_categories']);
        } else {
            $custom_components_categories_output = json_encode([]);
        }

        // Timezone
        $timezone_string = get_option('timezone_string'); // Get the timezone string like 'America/New_York'
        if (!$timezone_string) {
            // Fallback if timezone_string is not set (returns offset like -5 for UTC-5)
            $timezone_offset = get_option('gmt_offset');
            $timezone_string = timezone_name_from_abbr('', $timezone_offset * 3600, 0);
        }

        // Builder Settings
        $builder_settings_output = isset($option['builderSettings']) ? stripslashes($option['builderSettings']) : '{}';

        wp_add_inline_script('bricks-builder', preg_replace( '/\s+/', '', "window.addEventListener('DOMContentLoaded', () => {
            ADMINBRXC.globalSettings.timezone = '" . $timezone_string . "';
            ADMINBRXC.globalSettings.placeholderImg = '" . BRICKS_ADVANCED_THEMER_URL . "assets/img/placeholder-image.png';
            ADMINBRXC.globalSettings.transparencyCheckboard = '" . BRICKS_ADVANCED_THEMER_URL . "assets/img/transparency-checkboard.png';
            ADMINBRXC.globalSettings.generalCats.gridGuide = " . $grid_guide_output . ";
            ADMINBRXC.globalSettings.generalCats.rightShortcuts = " .$right_shortcuts_output . ";
            ADMINBRXC.globalSettings.generalCats.globalColorsPrefix = '" . $brxc_acf_fields['color_prefix'] . "';
            ADMINBRXC.globalSettings.generalCats.globalColorsDarkMode = " . json_encode((bool)$brxc_acf_fields['enable_dark_mode_on_frontend']) . ";
            ADMINBRXC.globalSettings.generalCats.cssVariables = JSON.parse('" . json_encode($brxc_acf_fields['css_variables_general']) . "');
            ADMINBRXC.globalSettings.shortcutsTabs = JSON.parse('" . json_encode($brxc_acf_fields['enable_tabs_icons']) . "');
            ADMINBRXC.globalSettings.shortcutsIcons = JSON.parse('" . json_encode($brxc_acf_fields['enable_shortcuts_icons']) . "');
            ADMINBRXC.globalSettings.elementShortcutIcons = JSON.parse('" . json_encode($brxc_acf_fields['elements_shortcut_icons']) . "');
            ADMINBRXC.globalSettings.superPowerCSSEnableSass = '" . $brxc_acf_fields['superpowercss-enable-sass'] . "';
            ADMINBRXC.globalSettings.defaultElementFeatures = JSON.parse('" . json_encode($brxc_acf_fields['custom_default_settings']) . "');
            ADMINBRXC.globalSettings.openPlainClassByDefault = '" .$brxc_acf_fields['open_plain_classes_by_default']. "';
            ADMINBRXC.globalSettings.classFeatures = JSON.parse('" . json_encode($brxc_acf_fields['class_features']) . "');
            ADMINBRXC.globalSettings.classFeatures.lockIdWithClasses = '" .$brxc_acf_fields['lock_id_styles_with_classes'] . "';
            ADMINBRXC.globalSettings.classFeatures.variablePickerType = '" .$brxc_acf_fields['variable_picker_type']. "';
            ADMINBRXC.globalSettings.autoFormatFunctions = JSON.parse('" . json_encode($brxc_acf_fields['autoformat_control_values']) . "');
            ADMINBRXC.globalSettings.classFeatures.advancedCSSEnableSass = '" . $brxc_acf_fields['advanced_css_enable_sass'] . "';
            ADMINBRXC.globalSettings.classFeatures.advancedCSSCommunityRecipes  = '" . $brxc_acf_fields['advanced_css_community_recipes'] . "';
            ADMINBRXC.globalSettings.elementFeatures = JSON.parse('" . json_encode($brxc_acf_fields['element_features']) . "');
            ADMINBRXC.globalSettings.themeSettingsTabs = JSON.parse('" . json_encode($brxc_acf_fields['theme_settings_tabs']) . "');
            ADMINBRXC.globalSettings.saveUXSettingsToDB = " . json_encode((bool)$brxc_acf_fields['save_ux_settings_in_db']) . ";
            ADMINBRXC.globalSettings.builderSettings = '" . $builder_settings_output . "';
            ADMINBRXC.globalSettings.loremIpsumtype = '" . $brxc_acf_fields['lorem_type'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.options = JSON.parse('" . json_encode($brxc_acf_fields['keyboard_sc_options']) . "');
            ADMINBRXC.globalSettings.keyboardShortcuts.quickSearch = '" . $brxc_acf_fields['keyboard_sc_enable_quick_search'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.gridGuides = '" . $brxc_acf_fields['keyboard_sc_enable_grid_guides'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.xMode = '" . $brxc_acf_fields['keyboard_sc_enable_xmode'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.contrastChecker = '" . $brxc_acf_fields['keyboard_sc_enable_constrast_checker'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.darkmode = '" . $brxc_acf_fields['keyboard_sc_enable_darkmode'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.cssStylesheets = '" . $brxc_acf_fields['keyboard_sc_enable_css_stylesheets'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.resources = '" . $brxc_acf_fields['keyboard_sc_enable_resources'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.openai = '" . $brxc_acf_fields['keyboard_sc_enable_openai'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.brickslabs = '" . $brxc_acf_fields['keyboard_sc_enable_brickslabs'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.colorManager = '" . $brxc_acf_fields['keyboard_sc_enable_color_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.classManager = '" . $brxc_acf_fields['keyboard_sc_enable_class_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.variableManager = '" . $brxc_acf_fields['keyboard_sc_enable_variable_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.queryLoopManager = '" . $brxc_acf_fields['keyboard_sc_enable_query_loop_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.promptManager = '" . $brxc_acf_fields['keyboard_sc_enable_prompt_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.structureHelper = '" . $brxc_acf_fields['keyboard_sc_enable_structure_helper'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.findAndReplace = '" . $brxc_acf_fields['keyboard_sc_enable_find_and_replace'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.plainClasses = '" . $brxc_acf_fields['keyboard_sc_enable_plain_classes'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.nestedElemenets = '" . $brxc_acf_fields['keyboard_sc_nested_elements'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.codepenConverter = '" . $brxc_acf_fields['keyboard_sc_codepen_converter'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.remoteTemplate = '" . $brxc_acf_fields['keyboard_sc_remote_template']. "';
            ADMINBRXC.globalSettings.keyboardShortcuts.ATFramework = '" . $brxc_acf_fields['keyboard_sc_at_framework']. "';
            ADMINBRXC.globalSettings.isAIApiKeyEmpty = '" . $brxc_acf_fields['openai_api_key'] . "';
            ADMINBRXC.globalSettings.defaultAIModel = '" . $brxc_acf_fields['default_api_model'] . "';
            ") . 
            "ADMINBRXC.globalSettings.generalCats.queryManager = " . $query_manager_output . ";
            ADMINBRXC.globalSettings.generalCats.queryManagerCats = " . $query_manager_cats_output . ";
            ADMINBRXC.globalSettings.generalCats.fullAccess = " . $full_access_output . ";
            ADMINBRXC.globalSettings.customComponentsCategories = " . $custom_components_categories_output . ";
            ADMINBRXC.globalSettings.customComponentsElements = " . $custom_components_elements_output . ";
            ADMINBRXC.globalSettings.customDummyContent = `" . $brxc_acf_fields['custom_dummy_content'] . "`;
            })", 'after');

        require_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builder_modal.php';
    }
    
    // Create the AJAX function
    public static function openai_ajax_function() {
        // Verify the nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
    
        // Get the data from the wp_option table
        $my_option = get_option( 'bricks-advanced-themer__brxc_ai_api_key_skip_export' );
        $ciphering = "AES-128-CTR";
        $options = 0;
        $decryption_iv = 'UrsV9aENFT*IRfhr';
        $decryption_key = "#34x*R8zmVK^IFG4#a4B3BVYIb";
        $value = openssl_decrypt ($my_option, $ciphering, $decryption_key, $options, $decryption_iv);
    
        // Return the data as JSON
        wp_send_json( $value );
    }
    
    public static function openai_save_image_to_media_library() {
        // Verify the nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
    
        if (!current_user_can('edit_posts')) { 

            wp_send_json_error('You do not have permission to save images.'); 

        } 
        $base64_img= $_POST['image_url'];

        if(!$base64_img){
            wp_send_json_error('Could not retrieve image data.');
        }

        $title = 'ai-image-' . AT__Helpers::generate_unique_string( 6 );
        $upload_dir  = wp_upload_dir();
        $upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

        $img             = str_replace( 'data:image/png;base64,', '', $base64_img );
        $img             = str_replace( ' ', '+', $img );
        $decoded         = base64_decode( $img );
        $filename        = $title . '.png';
        $file_type       = 'image/png';
        $hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

        // Save the image in the uploads directory.
        $upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );
        $target_file = trailingslashit($upload_dir['path']) . $hashed_filename;

        $attachment = array(
            'post_mime_type' => $file_type,
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
            'post_content'   => '',
            'post_status'    => 'inherit',
            'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
        );

        $attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );
        $attachment_data = wp_generate_attachment_metadata($attach_id, $target_file);
        wp_update_attachment_metadata($attach_id, $attachment_data);
        wp_send_json_success('Image saved successfully.'); 

    }

    private static function repositionArrayElement(array &$array, $key, int $order): void{
        if(($a = array_search($key, array_keys($array))) === false){
            throw new \Exception("The {$key} cannot be found in the given array.");
        }
        $p1 = array_splice($array, $a, 1);
        $p2 = array_splice($array, 0, $order);
        $array = array_merge($p2, $p1, $array);
    }

    public static function set_custom_default_values_in_builder(){

        global $brxc_acf_fields;

        $settings = $brxc_acf_fields['custom_default_settings'] ?? [];

        if (!class_exists('Bricks\Elements') || !AT__Helpers::is_builder_tweaks_category_activated() ) {
            return;
        }

        $elements = \Bricks\Elements::$elements;

        // SuperPower CSS
        if(AT__Helpers::in_array("superpower-custom-css", $brxc_acf_fields, 'element_features')){
            foreach($elements as $element){
                $element = $element['name'];
            
                add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
                    global $brxc_acf_fields;

                    $label = $brxc_acf_fields['superpowercss-enable-sass'] ? esc_html__('SuperPower CSS', 'bricks' ) . '<span class="highlight">SASS</span>' : esc_html__('SuperPower CSS', 'bricks');
        
                    $controls['_cssSuperPowerCSS'] = [
                        'tab'         => 'style',
                        'group'       => '_css',
                        'label'       => $label,
                        'type'        => 'textarea',
                        'pasteStyles' => true,
                        'css'         => [],
                        'hasDynamicData' => false,
                        'description' => esc_html__( 'Use "%root%" to target the element wrapper.', 'bricks' ) 
                                        . '<br /><br /><u>' . esc_html__('Shortcuts', 'bricks' ) . '</u><br />' 
                                        . '<strong>' . esc_html__('r + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rh + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:hover', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rb + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%::before', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('ra + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%::after', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rf + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:focus', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rcf + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:first-child', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rcl + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:last-child', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rc + argument + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:nth-child({argument})', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rtf + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:first-of-type', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rtl + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:last-of-type', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rt + argument + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:nth-of-type({argument})', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('q + width + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {width}) {}', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('Q + width + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {width}) { %root% {} }', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('q + c + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {current viewport width}) {}', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('Q + c + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {current viewport width}) { %root% {} }', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('CMD + SHIFT + 7', 'bricks') . '</strong>' . esc_html__(' => comment/uncomment the selected code', 'bricks' ) . '<br /><br />'
                                        . esc_html__('Replacing "r" by "R" (capitilized letter) will add the brackets and place the cursor inside of them.' , 'bricks' ) . '<br /><br />',
                        'placeholder' => "Write your CSS here.",
                    ];

                    return $controls;
                });
            }
        }

        // Custom values

        if (AT__Helpers::is_array($settings) ){
            // Page Settings - Admin Notes
            if( in_array("notes",  $settings) ){
                add_filter( 'builder/settings/page/controls_data', function( $data ) {
                    $data['controlGroups']['pageNotes'] = [
                        'title' => 'Notes',
                        'fullAccess' => true, 
                    ];
                    $data['controls']['adminNotes'] = [
                        'group'       => 'pageNotes',
                        'label'    => esc_html__( 'Page Notes for Admins' ),
                        'type'  => 'repeater',
                        'instructions' => 'test',
                        'fields' => [
                            'title' => [
                                'label' => esc_html__( 'Label', 'bricks' ),
                                'type' => 'text',
                                'hasDynamicData' => false,
                            ],
                            'cssVarValue' => [
                                'label' => esc_html__( 'Admin Note', 'bricks' ),
                                'type'     => 'textarea',
                                'hasDynamicData' => false,
                                'placeholder'    => esc_html__( 'Write some Admin notes here...', 'bricks' ),
                            ],
                        ],
                        'fullAccess' => true,
                    ];
                
                    return $data;
                } );
            }

            // Page Transition
            if( AT__Helpers::is_value($brxc_acf_fields, 'enable_page_transition_page') ){
                add_filter( 'builder/settings/page/controls_data', function( $data ) {
                    $data['controlGroups']['pageTransition'] = [
                        'title' => 'Page Transition',
                        'fullAccess' => true, 
                    ];
                    $data['controls']['activatePageTransition'] = [
                        'group'      => 'pageTransition',
                        'label'      => esc_html__( 'Activate Page Transition' ),
                        'type'       => 'checkbox',
                        'inline'     => true,
                        'fullAccess' => true,
                    ];
                    $data['controls']['pageTransitionSeperatorOld'] = [
                        'group'    => 'pageTransition',
                        'label' => esc_html__( 'Root Animation - Old', 'bricks' ),
                        'type'  => 'separator',
                        'description' => esc_html__( 'Add custom animation settings to the "old" snapshot of this page when loading.' ),
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                    $data['controls']['pageTransitionDurationOld'] = [
                        'group'    => 'pageTransition',
                        'label'    => esc_html__( 'Animation Duration' ),
                        'type'     => 'text',
                        'inline'   => true,
                        'units' => true,
                        'placeholder' => esc_html__( '300ms', 'bricks' ),
                        'fullAccess' => true,
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                    $data['controls']['pageTransitionDelayOld'] = [
                        'group'    => 'pageTransition',
                        'label'    => esc_html__( 'Animation Delay' ),
                        'type'     => 'text',
                        'inline'   => true,
                        'units' => true,
                        'placeholder' => esc_html__( '0ms', 'bricks' ),
                        'fullAccess' => true,
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                    $data['controls']['pageTransitionTimingOld'] = [
                        'group'    => 'pageTransition',
                        'label'    => esc_html__( 'Animation Timing Function' ),
                        'type'     => 'text',
                        'inline'   => true,
                        'placeholder' => esc_html__( 'ease-in-out', 'bricks' ),
                        'fullAccess' => true,
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                    $data['controls']['pageTransitionFillModeOld'] = [
                        'group'    => 'pageTransition',
                        'label'    => esc_html__( 'Animation Fill Mode' ),
                        'type'     => 'select',
                        'options'  => [
                            'none' => esc_html__( 'None' ),
                            'forwards' => esc_html__( 'Forwards' ),
                            'backwards' => esc_html__( 'Backwards' ),
                            'both' => esc_html__( 'Both' ),
                        ],
                        'inline'   => true,
                        'fullAccess' => true,
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                    $data['controls']['pageTransitionKeyframeOld'] = [
                        'group'    => 'pageTransition',
                        'label'    => esc_html__( 'Custom Keyframe' ),
                        'type'     => 'code',
                        'mode'     => 'css',
                        'hasVariables' => true,
			            'pasteStyles'  => true,
                        'placeholder' => esc_html__( '{
    0% { opacity: 0; }
    100% { opacity: 1; }
}', 'bricks' ),
                        'fullAccess' => true,
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                    $data['controls']['pageTransitionSeperatorNew'] = [
                        'group'    => 'pageTransition',
                        'label' => esc_html__( 'Root Animation - New', 'bricks' ),
                        'type'  => 'separator',
                        'description' => esc_html__( 'Add custom animation settings to the new DOM of this page when loading.' ),
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                    $data['controls']['pageTransitionDurationNew'] = [
                        'group'    => 'pageTransition',
                        'label'    => esc_html__( 'Animation Duration' ),
                        'type'     => 'text',
                        'inline'   => true,
                        'units' => true,
                        'placeholder' => esc_html__( '300ms', 'bricks' ),
                        'fullAccess' => true,
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                    $data['controls']['pageTransitionDelayNew'] = [
                        'group'    => 'pageTransition',
                        'label'    => esc_html__( 'Animation Delay' ),
                        'type'     => 'text',
                        'inline'   => true,
                        'units' => true,
                        'placeholder' => esc_html__( '0ms', 'bricks' ),
                        'fullAccess' => true,
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                    $data['controls']['pageTransitionTimingNew'] = [
                        'group'    => 'pageTransition',
                        'label'    => esc_html__( 'Animation Timing Function' ),
                        'type'     => 'text',
                        'inline'   => true,
                        'placeholder' => esc_html__( 'ease-in-out', 'bricks' ),
                        'fullAccess' => true,
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                    $data['controls']['pageTransitionFillModeNew'] = [
                        'group'    => 'pageTransition',
                        'label'    => esc_html__( 'Animation Fill Mode' ),
                        'type'     => 'select',
                        'options'  => [
                            'none' => esc_html__( 'None' ),
                            'forwards' => esc_html__( 'Forwards' ),
                            'backwards' => esc_html__( 'Backwards' ),
                            'both' => esc_html__( 'Both' ),
                        ],
                        'inline'   => true,
                        'fullAccess' => true,
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                    $data['controls']['pageTransitionKeyframeNew'] = [
                        'group'    => 'pageTransition',
                        'label'    => esc_html__( 'Custom Keyframe' ),
                        'type'     => 'code',
                        'mode'     => 'css',
                        'hasVariables' => true,
			            'pasteStyles'  => true,
                        'placeholder' => esc_html__( '{
    0% { opacity: 0; }
    100% { opacity: 1; }
}', 'bricks' ),
                        'fullAccess' => true,
                        'required'    => [ 'activatePageTransition', '=', true ],
                    ];
                
                    return $data;
                } );
            }

            // Basic Text: p as default HTML Tag
            if( in_array("text-basic-p",  $settings) ){
                add_filter( 'bricks/elements/text-basic/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['tag']['default'] = "p";
                    return $controls;
                } );
            }
            // Image: figure as default HTML Tag
            if( in_array("image-figure",  $settings) ){
                add_filter( 'bricks/elements/image/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['tag']['default'] = "figure";
                    return $controls;
                } );
            }
            // Image: caption off
            if( in_array("image-caption-off",  $settings) ){
                add_filter( 'bricks/elements/image/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['caption']['default'] = 'none';
                    return $controls;
                } );
            }
            // Button: button as default HTML Tag
            if( in_array("button-button",  $settings) ){
                add_filter( 'bricks/elements/button/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['tag']['default'] = 'button';
                    return $controls;
                } );
            }

            // Add fields to all elements

            $settings = $brxc_acf_fields['custom_default_settings'];
            foreach($elements as $element){
                $element = $element['name'];

                
                add_filter( 'bricks/elements/' . $element . '/control_groups', function( $control_groups ) use ( &$settings ) {
                    global $brxc_acf_fields;
                    //$settings = $brxc_acf_fields['custom_default_settings'];

                    if(in_array("filter-tab",  $settings) && !in_array('remove-style-controls',  $settings) ) {
                        $control_groups['_filter'] = [
                            'tab'      => 'style',
                            'title'    => esc_html__( 'Filters / Transitions', 'Bricks' ),
                        ];

                        self::repositionArrayElement($control_groups, "_filter", array_search('_css', array_keys($control_groups)));
                    }
                    if(in_array("animation-tab",  $settings) ){
                        $control_groups['_animation'] = [
                            'tab'      => 'style',
                            'title'    => esc_html__( 'Animations', 'Bricks' ),
                            'fullAccess' => true,
                        ];
                        self::repositionArrayElement($control_groups, "_animation", array_search('_css', array_keys($control_groups)));
                    } 

                    if(in_array("classes-tab",  $settings) ){
                        $control_groups['_classes'] = [
                            'tab'      => 'style',
                            'title'    => esc_html__( 'Classes / ID', 'Bricks' ),
                        ];

                        self::repositionArrayElement($control_groups, "_classes", array_search('_css', array_keys($control_groups)) + 1);    
                    }

                    if(in_array("notes",  $settings) ) {
                        $control_groups['notes'] = [
                            'tab'      => 'content',
                            'title'    => esc_html__( 'Notes', 'Bricks' ),
                            'fullAccess' => true,
                        ];   
                    }

                    if( in_array("generated-code",  $settings ) )  {
                        $control_groups['_generated-code'] = [
                            'tab'      => 'style',
                            'title'    => esc_html__( 'Generated Code', 'Bricks' ),
                            'fullAccess' => true,
                        ];   
                    }

                    if(AT__Helpers::is_value($brxc_acf_fields, 'enable_page_transition_elements')  ) {
                        $control_groups['_pageTransition'] = [
                            'tab'      => 'style',
                            'title'    => esc_html__( 'Page Transition', 'Bricks' ),
                            'fullAccess' => true,
                        ];   
                    }
                    
                    return $control_groups;
                }, 10, 1 );
            
                add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) use ( $settings, $brxc_acf_fields ) {

                    if(in_array("background-clip",  $settings) ){
                        $controls['_backgroundClip'] = [
                            'tab'      => 'style',
                            'group'    => '_background',
                            'label'    => esc_html__( 'Background clip' ),
                            'type'     => 'select',
                            'options'  => [
                                'border-box' => esc_html__( 'border-box', 'bricks' ),
                                'content-box' => esc_html__( 'content-box', 'bricks' ),
                                'padding-box' => esc_html__( 'padding-box', 'bricks' ),
                                'text' => esc_html__( 'text', 'bricks' ),
                            ],
                            'css'      => [
                                [
                                    'property' => '-webkit-background-clip',
                                    'selector' => '',
                                ],
                            ]
                        ];

                        self::repositionArrayElement($controls, "_backgroundClip", array_search('_background', array_keys($controls)) + 1);
                    }

                    if(in_array("white-space",  $settings) ){
                        $controls['_whiteSpace'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'White space' ),
                            'type'     => 'select',
                            'options'  => [
                                'normal' => esc_html__( 'normal', 'bricks' ),
                                'nowrap' => esc_html__( 'nowrap', 'bricks' ),
                                'pre' => esc_html__( 'pre', 'bricks' ),
                                'pre-line' => esc_html__( 'pre-line', 'bricks' ),
                                'pre-wrap' => esc_html__( 'pre-wrap', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'white-space',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        self::repositionArrayElement($controls, "_whiteSpace", array_search('_overflow', array_keys($controls)) + 1);
                    }

                    if(in_array("content-visibility",  $settings) ){
                        $controls['_contentVisibility'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Content visibility' ),
                            'type'     => 'select',
                            'options'  => [
                                'auto' => esc_html__( 'auto', 'bricks' ),
                                'hidden' => esc_html__( 'hidden', 'bricks' ),
                                'visible' => esc_html__( 'visible', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'content-visibility',
                                    'selector' => '',
                                ],
                            ],
                        ];
                        

                        $controls['_containIntrinsicSize'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Contain intrinsic size' ),
                            'type'     => 'number',
                            'units'    => true,
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'contain-intrinsic-size',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        self::repositionArrayElement($controls, "_contentVisibility", array_search('_overflow', array_keys($controls)));
                        self::repositionArrayElement($controls, "_containIntrinsicSize", array_search('_contentVisibility', array_keys($controls)) + 1);
                    }

                    if(in_array("overflow-dropdown",  $settings) ){
                        $controls['_overflow']['type'] = 'select';
                        $controls['_overflow']['options']  = [
                                'auto' => esc_html__( 'auto', 'bricks' ),
                                'clip' => esc_html__( 'clip', 'bricks' ),
                                'hidden' => esc_html__( 'hidden', 'bricks' ),
                                'overlay' => esc_html__( 'overlay', 'bricks' ),
                                'revert' => esc_html__( 'revert', 'bricks' ),
                                'scroll' => esc_html__( 'scroll', 'bricks' ),
                                'visible' => esc_html__( 'visible', 'bricks' ),
                        ];
                    }



                    if(in_array("break",  $settings) ){
                        $css_values = [
                            'always' => esc_html__( 'always', 'bricks' ),
                            'auto' => esc_html__( 'auto', 'bricks' ),
                            'avoid' => esc_html__( 'avoid', 'bricks' ),
                            'avoid-column' => esc_html__( 'avoid-column', 'bricks' ),
                            'avoid-page' => esc_html__( 'avoid-page', 'bricks' ),
                            'avoid-region' => esc_html__( 'avoid-region', 'bricks' ),
                            'column' => esc_html__( 'column', 'bricks' ),
                            'left' => esc_html__( 'left', 'bricks' ),
                            'page' => esc_html__( 'page', 'bricks' ),
                            'recto' => esc_html__( 'recto', 'bricks' ),
                            'region' => esc_html__( 'region', 'bricks' ),
                            'right' => esc_html__( 'right', 'bricks' ),
                            'verso' => esc_html__( 'verso', 'bricks' ),
                        ];

                        $controls['_breakBefore'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Break before' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => $css_values,
                            'css'      => [
                                [
                                    'property' => 'break-before',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        $controls['_breakInside'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Break inside' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => $css_values,
                            'css'      => [
                                [
                                    'property' => 'break-inside',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        $controls['_breakAfter'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Break after' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => $css_values,
                            'css'      => [
                                [
                                    'property' => 'break-after',
                                    'selector' => '',
                                ],
                            ],
                        ];


                        self::repositionArrayElement($controls, "_breakBefore", array_search('_pointerEvents', array_keys($controls)) + 1 );
                        self::repositionArrayElement($controls, "_breakInside", array_search('_breakBefore', array_keys($controls)) + 1 );
                        self::repositionArrayElement($controls, "_breakAfter", array_search('_breakInside', array_keys($controls)) + 1 );
                    }
                    if(in_array("filter-tab",  $settings) ){
                        $controls['_cssFilters']['group'] = '_filter';
                        $controls['_cssTransition']['group'] = '_filter';
                    }

                    if(in_array("classes-tab",  $settings) ){
                        $controls['_cssClasses']['group'] = '_classes';
                        $controls['_cssId']['group'] = '_classes';
                    }

                    if(in_array("transform",  $settings) ){
                        $controls['_transform']['description'] = false;
                        $controls['_transformOrigin']['description'] = false;
                        $controls['_transformStyle'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Transform style' ),
                            'type'     => 'select',
                            'options'  => [
                                'flat' => esc_html__( 'flat', 'bricks' ),
                                'preserve-3d' => esc_html__( 'preserve-3d', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'transform-style',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        $controls['_transformBox'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Transform box' ),
                            'type'     => 'select',
                            'options'  => [
                                'border-box' => esc_html__( 'border-box', 'bricks' ),
                                'content-box' => esc_html__( 'content-box', 'bricks' ),
                                'fill-box' => esc_html__( 'fill-box', 'bricks' ),
                                'stroke-box' => esc_html__( 'stroke-box', 'bricks' ),
                                'view-box' => esc_html__( 'view-box', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'transform-box',
                                    'selector' => '',
                                ],
                            ],
                        ];
                        $controls['_perspective'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Perspective' ),
                            'type'     => 'number',
                            'units'    => true,
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'perspective',
                                    'selector' => '',
                                ],
                            ],
                        ];
                        $controls['_perspectiveOrigin'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Perspective origin' ),
                            'type'     => 'text',
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'perspective-origin',
                                    'selector' => '',
                                ],
                            ],
                            'hasDynamicData' => false,
                            'placeholder'    => esc_html__( 'Center', 'bricks' ),
                        ];

                        $controls['_backfaceVisibility'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Backface visibility' ),
                            'type'     => 'select',
                            'options'  => [
                                'hidden' => esc_html__( 'hidden', 'bricks' ),
                                'visible' => esc_html__( 'visible', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'backface-visibility',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        self::repositionArrayElement($controls, "_transformStyle", array_search('_transformOrigin', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_transformBox", array_search('_transformStyle', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_perspective", array_search('_transformBox', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_perspectiveOrigin", array_search('_perspective', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_backfaceVisibility", array_search('_perspectiveOrigin', array_keys($controls)) + 1);
                    }

                    if(in_array("logical-properties",  $settings) ){
                        $remove_directional_properties = $brxc_acf_fields['replace_directional_properties'];
                        
                        $controls['_marginLogical'] = [
                            'tab' => 'style',
                            'group' => '_layout',
                            'label' => esc_html__( 'Margin (Logical)', 'bricks' ),
                            'type'  => 'spacing',
                            'css' => [
                            [
                                'property' => 'margin',
                                'selector' => '',
                            ]
                            ],
                            'directions' => [
                            'block-start' => esc_html__( 'Block Start', 'bricks' ),
                            'inline-end' => esc_html__( 'Inline End', 'bricks' ),
                            'block-end'  => esc_html__( 'Block End', 'bricks' ),
                            'inline-start'    => esc_html__( 'Inline Start', 'bricks' ),
                            ],
                        ];

                        self::repositionArrayElement($controls, "_marginLogical", array_search('_margin', array_keys($controls)) + 1);
                    
                        if ($remove_directional_properties) {
                            unset($controls['_margin']);
                        }

                        $controls['_paddingLogical'] = [
                            'tab' => 'style',
                            'group' => '_layout',
                            'label' => esc_html__( 'Padding (Logical)', 'bricks' ),
                            'type'  => 'spacing',
                            'css' => [
                              [
                                'property' => 'padding',
                                'selector' => '',
                              ]
                            ],
                            'directions' => [
                              'block-start' => esc_html__( 'Block Start', 'bricks' ),
                              'inline-end' => esc_html__( 'Inline End', 'bricks' ),
                              'block-end'  => esc_html__( 'Block End', 'bricks' ),
                              'inline-start'    => esc_html__( 'Inline Start', 'bricks' ),
                            ],
                        ];

                        self::repositionArrayElement($controls, "_paddingLogical", array_search('_padding', array_keys($controls)) + 1);
                        if ($remove_directional_properties) {
                        unset($controls['_padding']);
                        }

                        $controls['_inlineSize'] = [
                            'tab'   => 'style',
                            'group' => '_layout',
                            'label' => esc_html__( 'Inline Size', 'bricks' ),
                            'type'  => 'number',
                            'units' => true,
                            'css'   => [
                                [
                                    'property' => 'inline-size',
                                    'selector' => '',
                                ],
                            ],
                            'info' => 'Replace width',
                        ];

                        self::repositionArrayElement($controls, "_inlineSize", array_search('_width', array_keys($controls)) + 1);
                        if ($remove_directional_properties) {
                            unset($controls['_width']);
                        }
                
                        $controls['_inlineSizeMin'] = [
                            'tab'   => 'style',
                            'group' => '_layout',
                            'label' => esc_html__( 'Min. Inline Size', 'bricks' ),
                            'type'  => 'number',
                            'units' => true,
                            'css'   => [
                                [
                                    'property' => 'min-inline-size',
                                    'selector' => '',
                                ],
                            ],
                            'info' => 'Replace min-width',
                        ];

                        self::repositionArrayElement($controls, "_inlineSizeMin", array_search('_widthMin', array_keys($controls)) + 1);
                        if ($remove_directional_properties) {
                            unset($controls['_widthMin']);
                        }
                
                        $controls['_inlineSizeMax'] = [
                            'tab'   => 'style',
                            'group' => '_layout',
                            'label' => esc_html__( 'Max. Inline Size', 'bricks' ),
                            'type'  => 'number',
                            'units' => true,
                            'css'   => [
                                [
                                    'property' => 'max-inline-size',
                                    'selector' => '',
                                ],
                            ],
                            'info' => 'Replace max-width',
                        ];

                        self::repositionArrayElement($controls, "_inlineSizeMax", array_search('_widthMax', array_keys($controls)) + 1);
                        if ($remove_directional_properties) {
                            unset($controls['_widthMax']);
                        }
                
                        $controls['_blockSize'] = [
                            'tab'   => 'style',
                            'group' => '_layout',
                            'label' => esc_html__( 'Block Size', 'bricks' ),
                            'type'  => 'number',
                            'units' => true,
                            'css'   => [
                                [
                                    'property' => 'block-size',
                                ],
                            ],
                            'info' => 'Replace height',
                        ];

                        self::repositionArrayElement($controls, "_blockSize", array_search('_height', array_keys($controls)) + 1);
                        if ($remove_directional_properties) {
                            unset($controls['_height']);
                        }
                
                        $controls['_blockSizeMin'] = [
                            'tab'   => 'style',
                            'group' => '_layout',
                            'label' => esc_html__( 'Min. Block Size', 'bricks' ),
                            'type'  => 'number',
                            'units' => true,
                            'css'   => [
                                [
                                    'property' => 'min-block-size',
                                ],
                            ],
                            'info' => 'Replace min-height',
                        ];

                        self::repositionArrayElement($controls, "_blockSizeMin", array_search('_heightMin', array_keys($controls)) + 1);
                        if ($remove_directional_properties) {
                            unset($controls['_heightMin']);
                        }
                
                        $controls['_blockSizeMax'] = [
                            'tab'   => 'style',
                            'group' => '_layout',
                            'label' => esc_html__( 'Max. Block Size', 'bricks' ),
                            'type'  => 'number',
                            'units' => true,
                            'css'   => [
                                [
                                    'property' => 'max-block-size',
                                ],
                            ],
                            'info' => 'Replace max-height',
                        ];

                        self::repositionArrayElement($controls, "_blockSizeMax", array_search('_heightMax', array_keys($controls)) + 1);
                        if ($remove_directional_properties) {
                            unset($controls['_heightMax']);
                        }

                        $controls['_insetLogical'] = [
                            'tab' => 'style',
                            'group' => '_layout',
                            'label' => esc_html__( 'Inset (Logical)', 'bricks' ),
                            'type'  => 'spacing',
                            'css' => [
                              [
                                'property' => 'inset',
                                'selector' => '',
                              ]
                            ],
                            'directions' => [
                              'block-start' => esc_html__( 'Block Start', 'bricks' ),
                              'inline-end' => esc_html__( 'Inline End', 'bricks' ),
                              'block-end'  => esc_html__( 'Block End', 'bricks' ),
                              'inline-start'    => esc_html__( 'Inline Start', 'bricks' ),
                            ],
                          ];

                        self::repositionArrayElement($controls, "_insetLogical", array_search('_position', array_keys($controls)) + 1);
                        if ($remove_directional_properties) {
                            unset($controls['_top']);
                            unset($controls['_bottom']);
                            unset($controls['_right']);
                            unset($controls['_left']);
                        }

                        // Borders
                        $controls['_borderWidthLogical'] = [
                            'tab' => 'style',
                            'group' => '_border',
                            'label' => esc_html__( 'Border Width (Logical)', 'bricks' ),
                            'type'  => 'spacing',
                            'css' => [
                              [
                                'property' => 'border',
                                'selector' => '',
                              ]
                            ],
                            'directions' => [
                              'block-start-width' => esc_html__( 'Block Start', 'bricks' ),
                              'inline-end-width' => esc_html__( 'Inline End', 'bricks' ),
                              'block-end-width'  => esc_html__( 'Block End', 'bricks' ),
                              'inline-start-width'    => esc_html__( 'Inline Start', 'bricks' ),
                            ],
                            'placeholder' => false,
                          ];

                        $controls['_borderStyle'] = [
                        'tab' => 'style',
                        'group' => '_border',
                        'label' => esc_html__( 'Border Style', 'bricks' ),
                        'type'  => 'select',
                        'css' => [
                            [
                            'property' => 'border-style',
                            'selector' => '',
                            ]
                        ],
                        'options' => [
                            'none' => esc_html__( 'None', 'bricks' ),
                            'hidden' => esc_html__( 'Hidden', 'bricks' ),
                            'solid' => esc_html__( 'Solid', 'bricks' ),
                            'dotted' => esc_html__( 'Dotted', 'bricks' ),
                            'dashed' => esc_html__( 'Dashed', 'bricks' ),
                            'double' => esc_html__( 'Double', 'bricks' ),
                            'groove' => esc_html__( 'Groove', 'bricks' ),
                            'ridge' => esc_html__( 'Ridge', 'bricks' ),
                            'inset' => esc_html__( 'Inset', 'bricks' ),
                            'outset' => esc_html__( 'Outset', 'bricks' ),
                        ],
                        'inline' => true,                          
                        'placeholder' => false,
                        ];

                        $controls['_borderColor'] = [
                            'tab' => 'style',
                            'group' => '_border',
                            'label' => esc_html__( 'Border Color', 'bricks' ),
                            'type'  => 'color',
                            'css' => [
                                [
                                'property' => 'border-color',
                                'selector' => '',
                                ]
                            ],
                        ];

                        $controls['_borderRadiusLogical'] = [
                            'tab' => 'style',
                            'group' => '_border',
                            'label' => esc_html__( 'Border Radius (Logical)', 'bricks' ),
                            'type'  => 'spacing',
                            'css' => [
                              [
                                'property' => 'border',
                                'selector' => '',
                              ]
                            ],
                            'directions' => [
                              'start-start-radius' => esc_html__( 'Block Start', 'bricks' ),
                              'start-end-radius' => esc_html__( 'Inline End', 'bricks' ),
                              'end-start-radius'  => esc_html__( 'Block End', 'bricks' ),
                              'end-end-radius'    => esc_html__( 'Inline Start', 'bricks' ),
                            ],
                            'placeholder' => false,
                          ];

                        self::repositionArrayElement($controls, "_borderRadiusLogical", array_search('_border', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_borderColor", array_search('_border', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_borderStyle", array_search('_border', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_borderWidthLogical", array_search('_border', array_keys($controls)) + 1);
                        if ($remove_directional_properties) {
                            unset($controls['_border']);
                        }
                    }

                    if(in_array("css-filters",  $settings) ){
                        $filter_group = in_array("filter-tab",  $settings) ? '_filter' : '_css';
                        $controls['_backdropFilter'] = [
                            'tab'      => 'style',
                            'group'    => $filter_group,
                            'label'    => esc_html__( 'Backdrop filter' ),
                            'type'     => 'text',
                            'css'      => [
                                [
                                    'property' => 'backdrop-filter',
                                    'selector' => '',
                                ],
                                [
                                    'property' => '-webkit-backdrop-filter',
                                    'selector' => '',
                                ],
                            ],
                            'hasDynamicData' => false,
                            'placeholder'    => esc_html__( 'None', 'bricks' ),
                        ];

                        self::repositionArrayElement($controls, "_backdropFilter", array_search('_cssFilters', array_keys($controls)) + 1);
                    }

                    // Notes

                    if( in_array("notes",  $settings) ){
                        $controls['adminNotes'] = [
                            'tab'      => 'content',
                            'group'    => 'notes',
                            'label'    => esc_html__( 'Admin Notes' ),
                            'type'     => 'textarea',
                            'hasDynamicData' => false,
                            'placeholder'    => esc_html__( 'Write some Admin notes here...', 'bricks' ),
                            'fullAccess' => true,
                        ];
   
                        $controls['editorNotes'] = [
                            'tab'      => 'content',
                            'group'    => 'notes',
                            'label'    => esc_html__( 'Editor Notes' ),
                            'type'     => 'textarea',
                            'hasDynamicData' => false,
                            'placeholder'    => esc_html__( 'Write some Editor notes here...', 'bricks' ),
                            'fullAccess' => true,
                        ];
                    }

                    // Generated Code

                    if( in_array("generated-code",  $settings ) ){
                        $controls['_generatedCSS'] = [
                            'tab'      => 'style',
                            'group'    => '_generated-code',
                            'label'    => esc_html__( 'Generated CSS (readonly)' ),
                            'type'     => 'textarea',
                            'hasDynamicData' => false,
                            'fullAccess' => true,
                        ];
                    
                        $controls['_generatedHTML'] = [
                            'tab'      => 'style',
                            'group'    => '_generated-code',
                            'label'    => esc_html__( 'Generated HTML' ),
                            'type'     => 'textarea',
                            'hasDynamicData' => false,
                            'fullAccess' => true,
                        ];
                        $controls['_generatedHTMLApply'] = [
                            'tab'      => 'style',
                            'group'    => '_generated-code',
                            'label'    => esc_html__( 'Parse HTML' ),
                            'type'     => 'apply',
                            'reload'   => false,
                            'fullAccess' => true,
                        ];
                    }

                    // Page Transition

                    if( AT__Helpers::is_value($brxc_acf_fields, 'enable_page_transition_elements') ){
                        $controls['pageTransitionName'] = [
                            'group'    => '_pageTransition',
                            'label'    => esc_html__( 'Transition Name' ),
                            'type'     => 'text',
                            'placeholder' => esc_html__( 'my-transition', 'bricks' ),
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['pageTransitionNameInfo'] = [
                            'type'     => 'info',
                            'group'    => '_pageTransition',
                            'content'  => esc_html__( 'The Transition Name must be unique across the entire page. Use the same Transition Name for both the origin and target elements. If you\'re running a transition inside a query loop, be sure to append the post ID to the Transition Name to prevent conflicts.', 'bricks' ),
                        ];
                        $controls['pageTransitionSeperatorOld'] = [
                            'tab'   => 'content',
                            'group'    => '_pageTransition',
                            'label' => esc_html__( 'Old', 'bricks' ),
                            'description' => esc_html__( 'Add custom animation settings to the "old" snapshot of this page when loading. (optional)', 'bricks' ),
                            'type'  => 'separator',
                        ];
                        $controls['pageTransitionDurationOld'] = [
                            'group'    => '_pageTransition',
                            'label'    => esc_html__( 'Animation Duration' ),
                            'type'     => 'text',
                            'inline'   => true,
                            'units' => true,
                            'placeholder' => esc_html__( '300ms', 'bricks' ),
                            'fullAccess' => true,
                        ];
                        $controls['pageTransitionDelayOld'] = [
                            'group'    => '_pageTransition',
                            'label'    => esc_html__( 'Animation Delay' ),
                            'type'     => 'text',
                            'inline'   => true,
                            'units' => true,
                            'placeholder' => esc_html__( '0ms', 'bricks' ),
                            'fullAccess' => true,
                        ];
                        $controls['pageTransitionTimingOld'] = [
                            'group'    => '_pageTransition',
                            'label'    => esc_html__( 'Animation Timing Function' ),
                            'type'     => 'text',
                            'inline'   => true,
                            'placeholder' => esc_html__( 'ease-in-out', 'bricks' ),
                            'fullAccess' => true,
                        ];
                        $controls['pageTransitionFillModeOld'] = [
                            'group'    => '_pageTransition',
                            'label'    => esc_html__( 'Animation Fill Mode' ),
                            'type'     => 'select',
                            'options'  => [
                                'none' => esc_html__( 'None' ),
                                'forwards' => esc_html__( 'Forwards' ),
                                'backwards' => esc_html__( 'Backwards' ),
                                'both' => esc_html__( 'Both' ),
                            ],
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['pageTransitionKeyframeOld'] = [
                            'group'    => '_pageTransition',
                            'label'    => esc_html__( 'Custom Keyframe' ),
                            'type'     => 'code',
                            'mode'     => 'css',
                            'hasVariables' => true,
                            'pasteStyles'  => true,
                            'placeholder' => esc_html__( '{
    0% { opacity: 0; }
    100% { opacity: 1; }
}', 'bricks' ),
                            'fullAccess' => true,
                        ];
                        $controls['pageTransitionSeperatorNew'] = [
                            'tab'   => 'content',
                            'group'    => '_pageTransition',
                            'label' => esc_html__( 'New', 'bricks' ),
                            'description' => esc_html__( 'Add custom animation settings to the new DOM of this page when loading. (optional)', 'bricks' ),
                            'type'  => 'separator',
                        ];
                        $controls['pageTransitionDurationNew'] = [
                            'group'    => '_pageTransition',
                            'label'    => esc_html__( 'Animation Duration' ),
                            'type'     => 'text',
                            'inline'   => true,
                            'units' => true,
                            'placeholder' => esc_html__( '300ms', 'bricks' ),
                            'fullAccess' => true,
                        ];
                        $controls['pageTransitionDelayNew'] = [
                            'group'    => '_pageTransition',
                            'label'    => esc_html__( 'Animation Delay' ),
                            'type'     => 'text',
                            'inline'   => true,
                            'units' => true,
                            'placeholder' => esc_html__( '0ms', 'bricks' ),
                            'fullAccess' => true,
                        ];
                        $controls['pageTransitionTimingNew'] = [
                            'group'    => '_pageTransition',
                            'label'    => esc_html__( 'Animation Timing Function' ),
                            'type'     => 'text',
                            'inline'   => true,
                            'placeholder' => esc_html__( 'ease-in-out', 'bricks' ),
                            'fullAccess' => true,
                        ];
                        $controls['pageTransitionFillModeNew'] = [
                            'group'    => '_pageTransition',
                            'label'    => esc_html__( 'Animation Fill Mode' ),
                            'type'     => 'select',
                            'options'  => [
                                'none' => esc_html__( 'None' ),
                                'forwards' => esc_html__( 'Forwards' ),
                                'backwards' => esc_html__( 'Backwards' ),
                                'both' => esc_html__( 'Both' ),
                            ],
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['pageTransitionKeyframeNew'] = [
                            'group'    => '_pageTransition',
                            'label'    => esc_html__( 'Custom Keyframe' ),
                            'type'     => 'code',
                            'mode'     => 'css',
                            'hasVariables' => true,
                            'pasteStyles'  => true,
                            'placeholder' => esc_html__( '{
    0% { opacity: 0; }
    100% { opacity: 1; }
}', 'bricks' ),
                            'fullAccess' => true,
                        ];
                    }

                    if(in_array("animation-tab",  $settings) ){
                        $controls['infoAttributes'] = [
                            'tab'     => 'style',
                            'group'   => '_animation',
                            'content' => esc_html__( 'Both animation-name and animation-duration are required for the animation to function correctly.', 'bricks' ),
                            'type'    => 'info',
                        ];

                        $controls['_atAnimationName'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'label'    => esc_html__( 'Animation Name' ),
                            'type'     => 'text',
                            'css'   => [
                                [
                                    'property' => 'animation-name',
                                ],
                            ],
                            'inline'   => true,
                            'placeholder' => 'myAnimation',
                            'fullAccess' => true,
                        ];

                        $controls['_atAnimationDuration'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'label'    => esc_html__( 'Animation Duration' ),
                            'type'     => 'text',
                            'css'   => [
                                [
                                    'property' => 'animation-duration',
                                ],
                            ],
                            'placeholder' => '0ms',
                            'unit' => 'ms',
                            'units' => true,
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['_atAnimationDirection'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'label'    => esc_html__( 'Animation Direction' ),
                            'type'     => 'select',
                            'options'  => [
                                'normal' => 'normal',
                                'reverse' => 'reverse',
                                'alternate' => 'alternate',
                                'alternate-reverse' => 'alternate-reverse'
                            ],
                            'css'   => [
                                [
                                    'property' => 'animation-direction',
                                ],
                            ],
                            'placeholder' => 'normal',
                            'add' => true,
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['_atAnimationDelay'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'label'    => esc_html__( 'Animation Delay' ),
                            'type'     => 'text',
                            'css'   => [
                                [
                                    'property' => 'animation-delay',
                                ],
                            ],
                            'placeholder' => '0ms',
                            'unit' => 'ms',
                            'units' => true,
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['_atAnimationFillMode'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'label'    => esc_html__( 'Animation Fill Mode' ),
                            'type'     => 'select',
                            'options'  => [
                                'none' => 'none',
                                'forwards' => 'forwards',
                                'backwards'  => 'backwards',
                                'both'  => 'both'
                            ],
                            'css'   => [
                                [
                                    'property' => 'animation-fill-mode',
                                ],
                            ],
                            'placeholder' => 'none',
                            'add' => true,
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['_atAnimationIterationCount'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'label'    => esc_html__( 'Animation Iteration Count' ),
                            'type'     => 'number',
                            'css'   => [
                                [
                                    'property' => 'animation-iteration-count',
                                ],
                            ],
                            'placeholder' => '1',
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['_atAnimationPlayState'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'label'    => esc_html__( 'Animation Play State' ),
                            'type'     => 'select',
                            'options'  => [
                                'running' => 'running',
                                'paused'  => 'paused'
                            ],
                            'css'   => [
                                [
                                    'property' => 'animation-play-state',
                                ],
                            ],
                            'placeholder' => 'running',
                            'add' => true,
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['_atAnimationTimingFunction'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'label'    => esc_html__( 'Animation Timing Function' ),
                            'type'     => 'select',
                            'options'  => [
                                'ease' => 'ease',
                                'ease-in' => 'ease-in',
                                'ease-out' => 'ease-out',
                                'ease-in-out' => 'ease-in-out',
                                'linear' => 'linear',
                                'step-start' => 'step-start',
                                'step-end' => 'step-end'
                            ],
                            'css'   => [
                                [
                                    'property' => 'animation-timing-function',
                                ],
                            ],
                            'placeholder' => 'ease',
                            'add' => true,
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['atAnimationTimelineSep'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'type'  => 'separator',
                            'label' => esc_html__( 'Animation Timeline', 'bricks' ),
                            'description' => sprintf(
                                esc_html__( 'The following controls are still experimentals, but they won\'t break your layout on unsupported browsers. More info on %s', 'bricks' ),
                                '<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/animation-timeline" target="_blank">https://developer.mozilla.org/en-US/docs/Web/CSS/animation-timeline.</a>'
                            ),
                            'fullAccess' => true,
                        ];
                        $controls['_atAnimationTimeline'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'label'    => esc_html__( 'Animation Timeline' ),
                            'type'     => 'select',
                            'options'  => [
                                'none' => 'none',
                                'auto' => 'auto',
                                'view()' => 'view()',
                                'scroll()' => 'scroll()'
                            ],
                            'css'   => [
                                [
                                    'property' => 'animation-timeline',
                                ],
                            ],
                            'placeholder' => 'none',
                            'add' => true,
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['_atAnimationRangeStart'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'label'    => esc_html__( 'Animation Range Start' ),
                            'type'     => 'select',
                            'options'  => [
                                'none' => 'normal',
                                'cover' => 'cover',
                                'contain' => 'contain',
                                'entry' => 'entry',
                                'exit' => 'exit',
                                'entry-crossing' => 'entry-crossing',
                                'exit-crossing' => 'exit-crossing'
                            ],
                            'css'   => [
                                [
                                    'property' => 'animation-range-start',
                                ],
                            ],
                            'placeholder' => 'normal',
                            'add' => true,
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                        $controls['_atAnimationRangeEnd'] = [
                            'tab'     => 'style',
                            'group'    => '_animation',
                            'label'    => esc_html__( 'Animation Range End' ),
                            'type'     => 'select',
                            'options'  => [
                                'none' => 'normal',
                                'cover' => 'cover',
                                'contain' => 'contain',
                                'entry' => 'entry',
                                'exit' => 'exit',
                                'entry-crossing' => 'entry-crossing',
                                'exit-crossing' => 'exit-crossing'
                            ],
                            'css'   => [
                                [
                                    'property' => 'animation-range-end',
                                ],
                            ],
                            'placeholder' => 'normal',
                            'add' => true,
                            'inline'   => true,
                            'fullAccess' => true,
                        ];
                    
                    }

                    // Combobox
                    if(in_array("combobox",  $settings) ) {
                        foreach ($controls as $key => $control) {
                            if (isset($control['type']) && $control['type'] === "select") {
                                $controls[$key]['add'] = true;
                            }
                        }
                    }
                    
                    return $controls;
                }, 10, 1 );


                
                
                // Target Containers only
                if( in_array("column-count",  $settings) && ($element == 'div' || $element == 'block' || $element == 'container' || $element == 'section') ){
                    add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) use ( &$settings ) {
                        $controls['_columnCount'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column count' ),
                            'type'     => 'number',
                            'units'    => false,
                            'css'      => [
                                [
                                    'property' => 'column-count',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];

                        $controls['_columnCountColumnGap'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column gap', 'bricks' ),
                            'type'     => 'number',
                            'units'    => true,
                            'css'      => [
                                [
                                    'property' => 'column-gap',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];
                        $controls['_columnFill'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column fill','bricks' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => [
                                'auto' => 'auto',
                                'balance' => 'balance',
                                'balance-all' => 'balance-all',
                            ],
                            'css'      => [
                                [
                                    'property' => 'column-fill',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];
                        $controls['_columnWidth'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column width', 'bricks' ),
                            'type'     => 'number',
                            'units'    => true,
                            'css'      => [
                                [
                                    'property' => 'column-width',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];

                        self::repositionArrayElement($controls, "_columnCount", array_search('_display', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_columnCountColumnGap", array_search('_columnCount', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_columnFill", array_search('_columnCountColumnGap', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_columnWidth", array_search('_columnFill', array_keys($controls)) + 1);

                        // Combobox
                        if (in_array("combobox",  $settings)) {
                            foreach ($controls as $key => $control) {
                                if (isset($control['type']) && $control['type'] === "select") {
                                    $controls[$key]['add'] = true; // Modify the original array
                                }
                            }
                        }
                        return $controls;
                    } );
                }
                if( AT__Helpers::is_builder_tweaks_category_activated() && ($element == 'div' || $element == 'block' || $element == 'container' || $element == 'section') ){
                    add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
                        $controls['classConverterSeparator'] = [
                            'type'  => 'separator',
                            'label' => esc_html__( 'Class Converter', 'bricks' ),
                            'description' => esc_html__( 'When enabled, the class converter will process this element and their children as a standalone component with specific settings (basename, delimiter, convertion settings, etc...)', 'bricks' ),
                            'fullAccess' => true,
                        ];
                        $controls['classConverterComponent'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Set element as a root component' ),
                            'type'  => 'checkbox',
                            'fullAccess' => true,
                        ];

                        return $controls;
                    });
                }
            }
        }
    }

    public static function disable_style_controls() {
        global $brxc_acf_fields;
        
        if( !AT__Helpers::is_array( $brxc_acf_fields, 'custom_default_settings' ) ) {
            return;
        }
        $settings = $brxc_acf_fields['custom_default_settings'];

        if (!in_array('remove-style-controls', $settings)) {
            return;
        }
    
        $elements = \Bricks\Elements::$elements;
    
        foreach ($elements as $element) {
            $element_name = $element['name'];
    
            // Filter controls and store them
            add_filter('bricks/elements/' . $element_name . '/controls', function ($controls) use ($element_name, &$stored_controls) {
                $excluded = ['_cssCustom','_cssSuperPowerCSS','_cssId','_cssClasses','_attributes','_generatedCSS','_generatedHTML','_generatedHTMLApply'];
    
                foreach ($controls as $key => $control) {
                    // Remove controls with "css" property (except excluded ones)
                    if (isset($control['css']) && !in_array($key, $excluded, true)) {
                        unset($controls[$key]);
                        continue;
                    }

                    $condition1 = (isset($control['tab']) && $control['tab'] === 'style') || (isset($control['group']) && $control['group'] === "_layout"); 
                    $condition2 = !in_array($key, $excluded, true); // Remove "info" and "separator" controls if their tab is "style"
    
                    if ($condition1 && $condition2) {
                        unset($controls[$key]);
                    }
                }
    
                return $controls;
            }, 999); // High priority to ensure controls are fully registered
        }
    }

    public static function set_full_access_to_all_elements(){
         
        if(!class_exists('Bricks\Elements') || !AT__Helpers::is_strict_editor_view_category_activated() || !function_exists('bricks_is_builder') || !bricks_is_builder()){
            return;
        }

        global $brxc_acf_fields;

        if(!AT__Helpers::in_array('disable-all-controls', $brxc_acf_fields, 'strict_editor_view_tweaks')){
            return;
        }
    
        $elements = \Bricks\Elements::$elements;

        if( !AT__Helpers::is_array($elements) ){
            return;
        }

        foreach($elements as $element){
            $element = $element['name'];
        
            add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
                foreach($controls as $property => $value){
                    if((!isset($value['tab']) || $value['tab'] !== "style") && isset($value['type']) && $value['type'] !== 'separator' ){
                        $controls[$property]['fullAccess'] = true;
                    }
                }
                return $controls;
            });
            
        }
    }

    public static function set_full_access_settings(){

        if(!function_exists('bricks_is_builder') || !bricks_is_builder()){
            return;
        }
 
        $settings = get_option('bricks_advanced_themer_builder_settings', []);

        if(!AT__Helpers::is_array($settings, 'full_access') ){
            return;
        }

        foreach($settings['full_access'] as $element => $arr){
            if( !AT__Helpers::is_array($arr) ){
                continue;
            }
            foreach($arr as $property => $value){

                add_filter( 'bricks/elements/' . $element . '/controls', function( $controls) use ($property, $value){
                    if( !isset($controls[$property]) ){
                        return $controls;
                    }
                    if($value === "true"){
                        $controls[$property]['fullAccess'] = true;
                    } else {
                        $controls[$property]['fullAccess'] = false;
                    }

                    return $controls;

                });
            }
        }
    }

    public static function get_var_query_ajax_function(){
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }

        // Verify Class exists
        if(!class_exists('Bricks\Query')){
            die( 'Invalid Class' );
        }

        $settings = $_POST['settings'];
        $element_id = $_POST['element_id'];
    
        $query_vars = \Bricks\Query::prepare_query_vars_from_settings($settings, $element_id);
        wp_send_json_success($query_vars);

    }
} 
