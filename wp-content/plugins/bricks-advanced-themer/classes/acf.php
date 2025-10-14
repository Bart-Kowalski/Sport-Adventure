<?php
namespace Advanced_Themer_Bricks;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__ACF{

    public static function acf_get_role(){

        global $brxc_acf_fields;
        $permissions = $brxc_acf_fields['user_role_permissions'] ?? ['administrator'];

        return $permissions;
    }

    private static function check_nested_acf_fields( $post_data, &$errors, $parent_labels = array() ) {
        foreach ( $post_data as $field_key => $value ) {
            $field = get_field_object( $field_key );
            if ( $field ) {

                if ( is_array( $value ) && ( $field['type'] == 'group' || $field['type'] == 'repeater' ) ) {
                    $new_parent_labels = array_merge( $parent_labels, array( $field['label'] ) );
                    self::check_nested_acf_fields( $value, $errors, $new_parent_labels );
                } else if ( empty( $value )  ) {
                    $full_label = implode( ' -> ', array_merge( $parent_labels, array( $field['label'] ) ) );
                    $errors[] = $full_label;
                    
                }
            }
        }
    }

    public static function create_advanced_themer_option_page() {

        if( !function_exists( 'acf_add_options_sub_page' )) {
            return;
        }

        acf_add_options_sub_page(
            array(
                'page_title'    => __( 'Theme Settings' ),
                'menu_title'    => __( 'AT - Theme Settings' ),
                'menu_slug'     => 'bricks-advanced-themer',
                'parent'        => 'bricks',
                'capability'    => 'edit_posts',
                'redirect'      => false,
                'position'      => '98',
                'update_button' => __('Save Settings', 'acf'),
                'post_id' => 'bricks-advanced-themer',
            )
        );    
    }

    // Get a list of editable user roles
    private static function get_editable_roles() {

        $all_roles = wp_roles()->roles;
	    $editable_roles = apply_filters( 'editable_roles', $all_roles );
    
        return $editable_roles;

    }

    // Return a list of all the public post types on the site
    private static function return_array_all_post_types() {

        $args = array(
            'public'   => true,
        );
        
        $output = 'names';
        $operator = 'and';
        $post_types = get_post_types( $args, $output, $operator );

        return $post_types;

    }

    public static function load_user_roles_inside_select_field( $field ){

        $roles = self::get_editable_roles();

        if ( !$roles || !is_array( $roles ) ){
            return;
        }

        $field['choices'] = [];
      
        foreach ( $roles as $role ) {
            $field['choices'][strtolower( $role['name'] )] = $role['name'];
        }

        return $field;
    }

    public static function load_human_readable_text_value($value, $post_id, $field) {


        if ($value === false || $value === '') {

            $value = 'This is just placeholder text. We will change this out later. It’s just meant to fill space until your content is ready.
Don’t be alarmed, this is just here to fill up space since your finalized copy isn’t ready yet.
Once we have your content finalized, we’ll replace this placeholder text with your real content.
Sometimes it’s nice to put in text just to get an idea of how text will fill in a space on your website.
Traditionally our industry has used Lorem Ipsum, which is placeholder text written in Latin.
 Unfortunately, not everyone is familiar with Lorem Ipsum and that can lead to confusion.
I can’t tell you how many times clients have asked me why their website is in another language.
There are other placeholder text alternatives like Hipster Ipsum, Zombie Ipsum, Bacon Ipsum, and many more.
While often hilarious, these placeholder passages can also lead to much of the same confusion.
If you’re curious, this is Website Ipsum. It was specifically developed for the use on development websites.
Other than being less confusing than other Ipsum’s, Website Ipsum is also formatted in patterns more similar to how real copy is formatted on the web today.';

        }
    
        return $value;
    }

    public static function change_flexible_layout_no_value_msg( $no_value_message, $field) {
        if($field['key'] !== 'field_63dd12891d1d9') return $no_value_message = __('Click the "%s" button below to start creating your layout','acf');

        $no_value_message = __('Click the "%s" button below to start creating your own CSS variables','acf');

        return $no_value_message;
    }

    public static function load_openai_password($value, $post_id, $field) {


        if (isset($value) && !empty($value) && $value) {
            $ciphering = "AES-128-CTR";
            $options = 0;
            $decryption_iv = 'UrsV9aENFT*IRfhr';
            $decryption_key = "#34x*R8zmVK^IFG4#a4B3BVYIb";
            $value = openssl_decrypt ($value, $ciphering, $decryption_key, $options, $decryption_iv);
        }
        
        return $value;
    }

    public static function save_openai_password(){

        if(!function_exists('get_current_screen') ) return;

        $screen = get_current_screen();

        if (!$screen || (strpos($screen->id, "bricks-advanced-themer") == false) )  return;

        // Check if a specific value was updated.
        if( isset($_POST['acf']['field_63dd51rkj633r']['field_64018efb660fb']) && !empty($_POST['acf']['field_63dd51rkj633r']['field_64018efb660fb'])) {
            $ciphering = "AES-128-CTR";
            $options = 0;
            $encryption_iv = 'UrsV9aENFT*IRfhr';
            $encryption_key = "#34x*R8zmVK^IFG4#a4B3BVYIb";
            $_POST['acf']['field_63dd51rkj633r']['field_64018efb660fb'] = openssl_encrypt($_POST['acf']['field_63dd51rkj633r']['field_64018efb660fb'], $ciphering, $encryption_key, $options, $encryption_iv);
        }
    }

    // ACF fields from Option Page
    public static function load_global_acf_variable() {
        global $brxc_acf_fields, $wpdb;
    
        $brxc_acf_fields = [];
        $option_name = 'bricks-advanced-themer%';
    
        $acf_data = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT option_name, option_value 
                 FROM {$wpdb->options} 
                 WHERE option_name LIKE %s",
                $option_name
            ),
            ARRAY_A
        );
        
        if ($acf_data) {
            foreach ($acf_data as &$field) {
                $key = str_replace('options_', '', $field['option_name']);
                $field['option_value'] = maybe_unserialize($field['option_value']);
            }
        }
        
        /** Setting Group **/
        self::load_acf_group_fields('field_63daa58ccc209', [
            // Advanced Themer Tab
            [
                'key' => 'theme_settings_tabs',
                'acf' => 'brxc_theme_settings_tabs',
                'default' => array(
                    'global-colors',
                    'css-variables',
                    'classes-and-styles',
                    'builder-tweaks',
                    'strict-editor-view',
                    'ai',
                    'extras',
                    'admin-bar',
                ),
                'type' => 'array'
            ],
            [
                'key' => 'save_ux_settings_in_db',
                'acf' => 'brxc_save_ux_settings_in_db',
                'default' => true,
                'type' => 'true_false'
            ],
            // Page Transition
            [
                'key' => 'activate_global_page_transition',
                'acf' => 'brxc_activate_page_transitions_globally',
                'default' => false,
                'type' => 'true_false'
            ],
            [
                'key' => 'enable_page_transition_page',
                'acf' => 'brxc_activate_page_transitions_page',
                'default' => true,
                'type' => 'true_false'
            ],
            [
                'key' => 'enable_page_transition_elements',
                'acf' => 'brxc_activate_page_transitions_elements',
                'default' => true,
                'type' => 'true_false'
            ],
            [
                'key' => 'global_page_transition_duration_old',
                'acf' => 'brxc_page_transition_animation_duration_old',
                'default' => '',
                'type' => 'string',
            ],
            [
                'key' => 'global_page_transition_delay_old',
                'acf' => 'brxc_page_transition_animation_delay_old',
                'default' => '',
                'type' => 'string',
            ],
            [
                'key' => 'global_page_transition_timing_old',
                'acf' => 'brxc_page_transition_animation_timing_function_old',
                'default' => '',
                'type' => 'string',
            ],
            [
                'key' => 'global_page_transition_fill_old',
                'acf' => 'brxc_page_transition_animation_fill_mode_old',
                'default' => 'default',
                'type' => 'string',
            ],
            [
                'key' => 'global_page_transition_keyframes_old',
                'acf' => 'brxc_page_transition_custom_keyframes_old',
                'default' => '',
                'type' => 'string',
            ],
            [
                'key' => 'global_page_transition_duration_new',
                'acf' => 'brxc_page_transition_animation_duration_new',
                'default' => '',
                'type' => 'string',
            ],
            [
                'key' => 'global_page_transition_delay_new',
                'acf' => 'brxc_page_transition_animation_delay_new',
                'default' => '',
                'type' => 'string',
            ],
            [
                'key' => 'global_page_transition_timing_new',
                'acf' => 'brxc_page_transition_animation_timing_function_new',
                'default' => '',
                'type' => 'string',
            ],
            [
                'key' => 'global_page_transition_fill_new',
                'acf' => 'brxc_page_transition_animation_fill_mode_new',
                'default' => 'default',
                'type' => 'string',
            ],
            [
                'key' => 'global_page_transition_keyframes_new',
                'acf' => 'brxc_page_transition_custom_keyframes_new',
                'default' => '',
                'type' => 'string',
            ],
            [
                'key' => 'disable_bricks_elements_on_server',
                'acf' => 'brxc_disable_bricks_elements_on_server',
                'default' => false,
                'type' => 'true_false'
            ],
            // Permissions
            [
                'key' => 'user_role_permissions',
                'acf' => 'brxc_user_role_permissions',
                'default' => ['administrator'],
                'type' => 'array'
            ],
            [
                'key' => 'file_upload_format_permissions',
                'acf' => 'brxc_file_upload_format_permissions',
                'default' => array(
                    'css',
                ),
                'type' => 'array'
            ],
            // Misc
            [
                'key' => 'remove_acf_menu',
                'acf' => 'brxc_disable_acf_menu_item',
                'default' => true,
                'type' => 'true_false'
            ]
        ], $acf_data);
    
        /** Global Colors Group **/
        self::load_acf_group_fields('field_63dd51rtyue5e', [
            [
                'key' => 'color_prefix',
                'acf' => 'brxc_variable_prefix_global-colors',
                'default' => '',
                'type' => 'string',
            ],
            [
                'key' => 'enable_dark_mode_on_frontend',
                'acf' => 'brxc_enable_dark_mode_on_frontend',
                'default' => false,
                'type' => 'true_false'
            ],
            [
                'key' => 'force_default_color_scheme',
                'acf' => 'brxc_styles_force_default_color_scheme',
                'default' => 'auto',
                'type' => 'string',
            ],
            [
                'key' => 'replace_gutenberg_palettes',
                'acf' => 'brxc_enable_gutenberg_sync',
                'default' => false,
                'type' => 'true_false'
            ],
            [
                'key' => 'remove_default_gutenberg_presets',
                'acf' => 'brxc_remove_default_gutenberg_presets',
                'default' => false,
                'type' => 'true_false'
            ],
            [
                'key' => 'global_meta_theme_color',
                'acf' => 'brxc_global_meta_theme_color',
                'default' => '',
                'type' => 'string',
            ]
        ], $acf_data);
        
        /** CSS Variables Group **/
        self::load_acf_group_fields('field_6445ab9f3d498', [
            [
                'key' => 'css_variables_general',
                'acf' => 'brxc_enable_css_variables_features',
                'default' => [],
                'type' => 'array'
            ],
            [
                'key' => 'global_prefix',
                'acf' => 'brxc_global_prefix',
                'default' => '',
                'type' => 'string',
            ],
            [
                'key' => 'base_font',
                'acf' => 'brxc_base_font_size',
                'default' => "10",
                'type' => 'string',
            ],
            [
                'key' => 'min_vw',
                'acf' => 'brxc_min_vw',
                'default' => "360",
                'type' => 'string',
            ],
            [
                'key' => 'max_vw',
                'acf' => 'brxc_max_vw',
                'default' => "1600",
                'type' => 'string',
            ],
            [
                'key' => 'clamp_unit',
                'acf' => 'brxc_clamp_unit',
                'default' => 'vw',
                'type' => 'string',
            ],
            [
                'key' => 'theme_var_position',
                'acf' => 'brxc_theme_variables_position',
                'default' => 'head',
                'type' => 'string',
            ],
            [
                'key' => 'theme_var_priority',
                'acf' => 'brxc_theme_variables_priority',
                'default' => "9999",
                'type' => 'string',
            ]
        ], $acf_data);

        /** Builder Tweaks Group **/
        self::load_acf_group_fields('field_63daa58w1b209', [
            [
                'key' => 'create_elements_shortcuts',
                'acf' => 'brxc_elements_shortcuts',
                'default' => array(
                    'section',
                    'container',
                    'block',
                    'div',
                    'heading',
                    'text-basic',
                    'button',
                    'icon',
                    'image',
                    'code',
                    'template',
                    'nested-elements',
                ),
                'type' => 'array'
            ],
        
            // Classes & Styles
            [
                'key' => 'class_features',
                'acf' => 'brxc_builder_tweaks_for_classes',
                'default' => array(
                    'reorder-classes',
                    'disable-id-styles',
                    'variable-picker',
                    'autocomplete-variable',
                    'autocomplete-variable-preview-hover',
                    'count-classes',
                    'color-preview',
                    'class-preview',
                    'locked-class-indicator',
                    'focus-on-first-class',
                    'sync-label',
                    'autoformat-field-values'
                ),
                'type' => 'array'
            ],
            [
                'key' => 'lock_id_styles_with_classes',
                'acf' => 'brxc_lock_id_styles_with_one_global_class',
                'default' => true,
                'type' => 'true_false'
            ],
            [
                'key' => 'variable_picker_type',
                'acf' => 'brxc_variable_picker_type',
                'default' => 'icon',
                'type' => 'string',
            ],
            [
                'key' => 'autoformat_control_values',
                'acf' => 'brxc_autoformat_controls',
                'default' => array(
                    'clamp',
                    'calc',
                    'min',
                    'max',
                    'var',
                    'close-var-bracket',
                    'px-to-rem'
                ),
                'type' => 'array'
            ],
            [
                'key' => 'advanced_css_enable_sass',
                'acf' => 'brxc_sass_integration_advanced_css',
                'default' => false,
                'type' => 'true_false'
            ],
            [
                'key' => 'advanced_css_community_recipes',
                'acf' => 'brxc_community_recipes_advanced_css',
                'default' => true,
                'type' => 'true_false'
            ],
        
            // Elements
            [
                'key' => 'element_features',
                'acf' => 'brxc_builder_tweaks_for_elements',
                'default' => array(
                    'lorem-ipsum',
                    'close-accordion-tabs',
                    'disable-borders-boxshadows',
                    'resize-elements-icons',
                    'superpower-custom-css',
                    'increase-field-size',
                    'class-icons-reveal-on-hover',
                    'expand-spacing',
                    'sync-heading-label',
                    'grid-builder',
                    'copy-interactions-conditions',
                    'box-shadow-generator',
                    'text-wrapper',
                    'focus-point',
                    'mask-helper',
                    'dynamic-data-modal',
                ),
                'type' => 'array'
            ],
            [
                'key' => 'tab_icons_offset',
                'acf' => 'brxc_shortcuts_top_offset',
                'default' => "185",
                'type' => 'string',
            ],
            [
                'key' => 'enable_tabs_icons',
                'acf' => 'brxc_enable_shortcuts_tabs',
                'default' => array(
                    'content',
                    'layout',
                    'typography',
                    'background',
                    'border',
                    'gradient',
                    'shapes',
                    'transform',
                    'filter',
                    'animation',
                    'css',
                    'classes',
                    'attributes',
                    'generated-code',
                    'pageTransition'
                ),
                'type' => 'array'
            ],
            [
                'key' => 'lorem_type',
                'acf' => 'brxc_lorem_type',
                'default' => 'lorem',
                'type' => 'string',
            ],
            [
                'key' => 'custom_dummy_content',
                'acf' => 'brxc_custom_dummy_content',
                'default' => 'This is just placeholder text. We will change this out later. It’s just meant to fill space until your content is ready.
Don’t be alarmed, this is just here to fill up space since your finalized copy isn’t ready yet.
Once we have your content finalized, we’ll replace this placeholder text with your real content.
Sometimes it’s nice to put in text just to get an idea of how text will fill in a space on your website.
Traditionally our industry has used Lorem Ipsum, which is placeholder text written in Latin.
 Unfortunately, not everyone is familiar with Lorem Ipsum and that can lead to confusion.
I can’t tell you how many times clients have asked me why their website is in another language.
There are other placeholder text alternatives like Hipster Ipsum, Zombie Ipsum, Bacon Ipsum, and many more.
While often hilarious, these placeholder passages can also lead to much of the same confusion.
If you’re curious, this is Website Ipsum. It was specifically developed for the use on development websites.
Other than being less confusing than other Ipsum’s, Website Ipsum is also formatted in patterns more similar to how real copy is formatted on the web today.',
                'type' => 'string',
            ],
            [
                'key' => 'enable_shortcuts_icons',
                'acf' => 'brxc_enable_shortcuts_icons',
                'default' => array(
                    'hover',
                    'before',
                    'after',
                ),
                'type' => 'array'
            ],
            [
                'key' => 'open_plain_classes_by_default',
                'acf' => 'brxc_open_plain_class_by_default',
                'default' => false,
                'type' => 'true_false'
            ],
            [
                'key' => 'superpowercss-enable-sass',
                'acf' => 'brxc_sass_integration',
                'default' => false,
                'type' => 'true_false'
            ],
            [
                'key' => 'elements_shortcut_icons',
                'acf' => 'brxc_builder_tweaks_shortcuts_icons',
                'default' => array(
                    'class-contextual-menu',
                    //'tabs-shortcuts',
                    'pseudo-shortcut',
                    'css-shortcut',
                    'parent-shortcut',
                    'style-overview-shortcut',
                    'class-manager-shortcut',
                    'plain-classes',
                    'export-styles-to-class'
                ),
                'type' => 'array'
            ],
            [
                'key' => 'custom_default_settings',
                'acf' => 'brxc_builder_default_custom_settings',
                'default' => array(
                    'text-basic-p',
                    'heading-textarea',
                    'filter-tab',
                    'classes-tab',
                    'overflow-dropdown',
                    'notes',
                    'generated-code',
                ),
                'type' => 'array'
            ],
            [
                'key' => 'replace_directional_properties',
                'acf' => 'brxc_replace_directional_properties',
                'default' => false,
                'type' => 'true_false'
            ],
            [
                'key' => 'default_floating_bar',
                'acf' => 'brxc_default_floating_bar',
                'default' => true,
                'type' => 'true_false'
            ],
            [
                'key' => 'scrolling_timeline_polyfill',
                'acf' => 'brxc_scrolling_timeline_polyfill',
                'default' => false,
                'type' => 'true_false'
            ],
        
            // Keyboard Shortcuts
            [
                'key' => 'keyboard_sc_options',
                'acf' => 'brxc_keyboard_shortcuts_type',
                'default' => array(
                    'move-element',
                    'open-at-modal',
                ),
                'type' => 'array'
            ],
            [
                'key' => 'keyboard_sc_enable_quick_search',
                'acf' => 'brxc_shortcut_quick_search',
                'default' => 'f',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_grid_guides',
                'acf' => 'brxc_shortcut_grid_guides',
                'default' => 'i',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_xmode',
                'acf' => 'brxc_shortcut_xmode',
                'default' => 'j',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_constrast_checker',
                'acf' => 'brxc_shortcut_contrast_checker',
                'default' => 'k',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_darkmode',
                'acf' => 'brxc_shortcut_darkmode',
                'default' => 'z',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_css_stylesheets',
                'acf' => 'brxc_shortcut_stylesheet',
                'default' => 'l',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_resources',
                'acf' => 'brxc_shortcut_resources',
                'default' => 'x',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_openai',
                'acf' => 'brxc_shortcut_openai',
                'default' => 'o',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_brickslabs',
                'acf' => 'brxc_shortcut_brickslabs',
                'default' => 'n',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_color_manager',
                'acf' => 'brxc_shortcut_color_manager',
                'default' => 'm',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_class_manager',
                'acf' => 'brxc_shortcut_class_manager',
                'default' => ',',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_variable_manager',
                'acf' => 'brxc_shortcut_variable_manager',
                'default' => 'v',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_query_loop_manager',
                'acf' => 'brxc_shortcut_query_loop_manager',
                'default' => 'g',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_prompt_manager',
                'acf' => 'brxc_shortcut_prompt_manager',
                'default' => 'a',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_structure_helper',
                'acf' => 'brxc_shortcut_structure_helper',
                'default' => 'h',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_find_and_replace',
                'acf' => 'brxc_shortcut_find_and_replace',
                'default' => 'f',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_enable_plain_classes',
                'acf' => 'brxc_shortcut_plain_classes',
                'default' => 'p',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_nested_elements',
                'acf' => 'brxc_shortcut_nested_elements',
                'default' => 'e',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_codepen_converter',
                'acf' => 'brxc_shortcut_codepen_converter',
                'default' => 'c',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_remote_template',
                'acf' => 'brxc_shortcut_remote_template',
                'default' => 't',
                'type' => 'string',
            ],
            [
                'key' => 'keyboard_sc_at_framework',
                'acf' => 'brxc_shortcut_at_framework',
                'default' => 'u',
                'type' => 'string',
            ]
        ], $acf_data);

        /** Strict Editor **/
        self::load_acf_group_fields('field_63dd51rddtr57', [
            // White Label
            [
                'key' => 'change_logo_img',
                'acf' => 'brxc_change_logo_img_skip_export',
                'default' => false,
                'type' => 'string',
            ],
            [
                'key' => 'change_accent_color',
                'acf' => 'brxc_change_accent_color',
                'default' => '#ffd64f',
                'type' => 'string',
            ],

            // Toolbar
            [
                'key' => 'disable_toolbar_icons',
                'acf' => 'brxc_disable_toolbar_icons',
                'default' => array(
                    'help',
                    'pages',
                    'revisions',
                    'class-manager',
                    'command-palette',
                    'settings',
                    'breakpoints',
                    'dimensions',
                    'undo-redo',
                    'edit',
                    'new-tab',
                    'preview',
                ),
                'type' => 'array',
            ],

            // Elements
            [
                'key' => 'strict_editor_view_tweaks',
                'acf' => 'brxc_strict_editor_view_tweaks',
                'default' => array(
                    'disable-all-controls',
                    'hide-id-class',
                    'hide-dynamic-data',
                    'hide-text-toolbar',
                    'hide-structure-panel',
                    'reduce-left-panel-visibility',
                    //'disable-header-footer-edit-button-on-hover',
                    //'remove-template-settings-links',
                ),
                'type' => 'array',
            ],
            [
                'key' => 'strict_editor_view_custom_css',
                'acf' => 'brxc_strict_editor_custom_css',
                'default' => '',
                'type' => 'string',
            ]
        ], $acf_data);

        /** AI Group **/
        self::load_acf_group_fields('field_63dd51rkj633r', [
            // General
            [
                'key' => 'openai_api_key',
                'acf' => 'brxc_ai_api_key_skip_export',
                'default' => '',
                'type' => 'string',
            ],
            [
                'key' => 'default_api_model',
                'acf' => 'brxc_default_ai_model',
                'default' => 'gpt-4o',
                'type' => 'string',
            ],
            [
                'key' => 'ai_tone_of_voice',
                'acf' => 'brxc_ai_tons_of_voice',
                'default' => 'Authoritative
Conversational
Casual
Enthusiastic
Formal
Frank
Friendly
Funny
Humorous
Informative
Irreverent
Matter-of-fact
Passionate
Playful
Professional
Provocative
Respectful
Sarcastic
Smart
Sympathetic
Trustworthy
Witty',

                'type' => 'string',
            ]
        ], $acf_data);

        /** No ACF values **/
        $brxc_acf_fields['tone_of_voice'] = preg_split("/\r\n|\n|\r/", $brxc_acf_fields['ai_tone_of_voice'] ?? '');
        $ai_models = ['gpt-4o','gpt-4o-mini','gpt-4-turbo', 'gpt-4', 'gpt-4-32k', 'gpt-3.5-turbo', 'gpt-3.5-turbo-16k'];
        $valueToRemove = $brxc_acf_fields['default_api_model'] ?? 'gpt-4o';

        $indexToRemove = array_search($valueToRemove, $ai_models);
        if ($indexToRemove !== false) {
            unset($ai_models[$indexToRemove]);
            array_unshift($ai_models, $valueToRemove);
        }
        $brxc_acf_fields['ai_models']['completion'] = $ai_models;
        $brxc_acf_fields['ai_models']['edit'] = $ai_models;
        $brxc_acf_fields['ai_models']['code'] = $ai_models;
    }
    
    private static function load_acf_group_fields($group_key, $fields_map, $acf_data) {
        global $brxc_acf_fields;
    
        // Validate inputs early
        if (empty($group_key) || empty($fields_map) || !is_array($fields_map) || empty($acf_data)) {
            return; // Exit early if data is insufficient or invalid
        }
    
        foreach ($fields_map as $field_row) {
            $key = $field_row['key'];
            $acf_key = $field_row['acf'];
            $default = $field_row['default'];
            $type = isset($field_row['type']) ? $field_row['type'] : false;
    
            $matched = false;
            foreach ($acf_data as $row) {
                if (($row["option_name"] ?? null) === 'bricks-advanced-themer__' . $acf_key) {
                    $optionValue = $row["option_value"] ?? null;
                    
                    switch ($type) {
                        case "array":
                            // empty
                            if(is_string($optionValue) && $optionValue === ""){
                                $brxc_acf_fields[$key] = [];
                                break;
                            }

                            // value/default
                            $brxc_acf_fields[$key] = is_array($optionValue) ? $optionValue : $default;
                            break;
            
                        case "true_false":
                            $brxc_acf_fields[$key] = ($optionValue === "1" || $optionValue === 1 || $optionValue === true)
                                ? true
                                : (($optionValue === "0" || $optionValue === 0 || $optionValue === false)
                                    ? false
                                    : $default);
                            break;
            
                        case "string":
                            $brxc_acf_fields[$key] = $optionValue ?? $default;
                            break;
            
                        default:
                            $brxc_acf_fields[$key] = $optionValue ?: $default;
                    }
            
                    $matched = true;
                    break;
                }
            }
    
            if (!$matched && !isset($brxc_acf_fields[$key])) {
                $brxc_acf_fields[$key] = $default;
            }
        }
    }

    public static function remove_acf_menu() {

        global $brxc_acf_fields;
        if ( AT__Helpers::is_value($brxc_acf_fields, 'remove_acf_menu') ) {
            add_filter('acf/settings/show_admin', '__return_false');

        }
    }

    //Enqueue admin ACF Scripts
    public static function acf_admin_enqueue_scripts() {

        if( !is_user_logged_in() ) {

            return;

        }

        wp_enqueue_style( 'brxc_acf_admin', \BRICKS_ADVANCED_THEMER_URL . 'assets/css/acf-admin.css', false, filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/acf-admin.css') );
        wp_enqueue_script( 'brxc_acf_admin', \BRICKS_ADVANCED_THEMER_URL . 'assets/js/acf-admin.js', false, filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/acf-admin.js') );
        $nonce = wp_create_nonce('export_advanced_options_nonce');
        wp_localize_script('brxc_acf_admin', 'exportOptions', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $nonce,
        ));

    }

    public static function acf_settings_fields() {
    

        if( function_exists('acf_add_local_field_group') ):

            $default_dummy_content = 'This is just placeholder text. We will change this out later. It’s just meant to fill space until your content is ready.
Don’t be alarmed, this is just here to fill up space since your finalized copy isn’t ready yet.
Once we have your content finalized, we’ll replace this placeholder text with your real content.
Sometimes it’s nice to put in text just to get an idea of how text will fill in a space on your website.
Traditionally our industry has used Lorem Ipsum, which is placeholder text written in Latin.
 Unfortunately, not everyone is familiar with Lorem Ipsum and that can lead to confusion.
I can’t tell you how many times clients have asked me why their website is in another language.
There are other placeholder text alternatives like Hipster Ipsum, Zombie Ipsum, Bacon Ipsum, and many more.
While often hilarious, these placeholder passages can also lead to much of the same confusion.
If you’re curious, this is Website Ipsum. It was specifically developed for the use on development websites.
Other than being less confusing than other Ipsum’s, Website Ipsum is also formatted in patterns more similar to how real copy is formatted on the web today.';

            acf_add_local_field_group(array(
                'key' => 'group_638315a281bf1',
                'title' => 'Option Page',
                'fields' => array(
                    array(
                        'key' => 'field_63a6feit47c8b4',
                        'label' => 'Global Settings',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63daa58ccc209',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_23df5h7bvgxib6',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cffpl53',
                                'label' => 'Settings Instruction',
                                'name' => 'brxc_settings_global_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Global Settings</h3>Customize your own experience! Choose the tabs/categories you want to enable inside Advanced Themer, enable the custom elements inside the builder, set the correct permissions in the plugin, and import/export your theme settings. These are only some of the options available in the global settings section.',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_645s9g7tddfj2',
                                'label' => 'Customize the functions included in Advanced Themer',
                                'name' => 'brxc_theme_settings_tabs',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable/Disable any of the following settings. Once disabled, the corresponding function will be completely disabled on both the backend and the frontend',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'global-colors' => '<span>Global Colors. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Enable this option to activate advanced functions related to the Bricks Color palettes."></a></span></span>',
                                    'css-variables' => '<span>CSS Variables.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Enable this option to create Theme CSS variables or import your own CSS Variable framework."></a></span></span>',
                                    'classes-and-styles' => '<span>Class Importer.</span>',
                                    'builder-tweaks' => '<span>Builder Tweaks.</span>',
                                    'strict-editor-view' => '<span>Strict Editor View. </span>',
                                    'ai' => '<span>AI.</span>',
                                    'extras' => '<span>Extras.</span>',
                                    'admin-bar' => '<span>Admin Bar.</span>',
                                ),
                                'default_value' => array(
                                    'global-colors',
                                    'css-variables',
                                    'classes-and-styles',
                                    'builder-tweaks',
                                    'strict-editor-view',
                                    'ai',
                                    'extras',
                                    'admin-bar',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63a876544fccb',
                                'label' => 'Save UX Settings in Database',
                                'name' => 'brxc_save_ux_settings_in_db',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Enable this option to store Advanced Themer’s UX-related settings in the database. This ensures all users share the same UX configuration sitewide, and it remains consistent even after clearing the browser cache.<br><br>When disabled, settings are saved only in the browser’s localStorage. This allows each user to have personalized UX preferences, reduces database load, and keeps the settings local to their device.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 1,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_23df5h7bffsz52',
                                'label' => 'Page Transitions ',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cejevp6',
                                'label' => 'Settings Instruction',
                                'name' => 'brxc_settings_page_transitions',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Page Transitions <span class="new-feature">EXPERIMENTAL</span></h3><p>Advanced Themer offers you the ability to add page transitions to your site in few clicks! These animations can be set on a global level - affecting all the pages of your website - or on a page level. Inside the page level, you can also enable the ability to transition specific elements.</p><div class="helpful-links">This feature leverages the new View Transition API which is still considered experimental and <a href="https://caniuse.com/mdn-css_at-rules_view-transition" target="_blank">not yet fully supported by all the browsers</a>. While this feature won\'t break your layout on unsupported browsers, if you\'re afraid to offer a different user experience to your visitors based on their browser, it\'s suggested to keep this feature off.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63a876555fch8',
                                'label' => 'Activate Page Transitions Globally',
                                'name' => 'brxc_activate_page_transitions_globally',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Check this box if you want to apply a page transition animation on all the pages of your website. <strong>This will impact your site globally.</strong>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_63a8765drd51h',
                                'label' => 'Enable Page Transitions options inside the Page settings',
                                'name' => 'brxc_activate_page_transitions_page',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Check this box if you want to enable the page transition options inside the page settings of the builder, so you can set unique page transitions.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 1,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_63a8765ssxzl1',
                                'label' => 'Enable Page Transitions options inside each Element settings',
                                'name' => 'brxc_activate_page_transitions_elements',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Check this box if you want to enable the page transition options for specific elements inside the builder.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 1,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_6schh1cddssn9',
                                'label' => 'Settings Instruction',
                                'name' => 'brxc_settings_page_transitions_old',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message page-transition-subtitle big-title',
                                    'id' => '',
                                ),
                                'message' => '<span>Animation - <strong>Old page</strong></span><br><p class="description">The following animations apply on the old document. See it as the "Exit" transition.</p>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63a843dfrwsp4',
                                'label' => 'Animation Duration',
                                'name' => 'brxc_page_transition_animation_duration_old',
                                'aria-label' => '',
                                'type' => 'number',
                                'instructions' => 'Choose the duration of the animation (in milliseconds).<br><strong>The default duration is set by the browser.</strong>',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'base-font no-border-top',
                                    'id' => '',
                                ),
                                'min' => '',
                                'max' => '',
                                'placeholder' => '300',
                                'step' => 1,
                                'prepend' => '',
                                'append' => 'ms',
                            ),
                            array(
                                'key' => 'field_63a843dfwsit7',
                                'label' => 'Animation Delay',
                                'name' => 'brxc_page_transition_animation_delay_old',
                                'aria-label' => '',
                                'type' => 'number',
                                'instructions' => 'Choose the delay of the animation (in milliseconds).<br><strong>The default delay is 0ms.</strong>',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'base-font',
                                    'id' => '',
                                ),
                                'min' => '',
                                'max' => '',
                                'placeholder' => '0',
                                'step' => 1,
                                'prepend' => '',
                                'append' => 'ms',
                            ),
                            array(
                                'key' => 'field_639570d22oocv',
                                'label' => 'Animation Timing Function',
                                'name' => 'brxc_page_transition_animation_timing_function_old',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => 'Choose the timing function of the animation.<br><strong>The default is usually ease-in-out.</strong>',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => 'ease-in-out',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_6399a28rrpl59',
                                'label' => 'Animation Fill Mode',
                                'name' => 'brxc_page_transition_animation_fill_mode_old',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Choose the fill mode of the animation.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'frontend-theme-select',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'default' => 'Default',
                                    'none' => 'None',
                                    'forwards' => 'Forwards',
                                    'backwards' => 'Backwards',
                                    'both' => 'Both',
                                ),
                                'default_value' => 'default',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                            ),
                            array(
                                'key' => 'field_63882c3ddxxa5',
                                'label' => 'Custom Keyframes',
                                'name' => 'brxc_page_transition_custom_keyframes_old',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => 'Write here your custom CSS keyframes animation.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'maxlength' => '',
                                'rows' => '',
                                'placeholder' => '{
    0% { opacity: 0; }
    100% { opacity: 1; }
}',
                                'new_lines' => '',
                            ),
                            array(
                                'key' => 'field_6schh1cwwetr5',
                                'label' => 'Settings Instruction',
                                'name' => 'brxc_settings_page_transitions_new',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message page-transition-subtitle big-title',
                                    'id' => '',
                                ),
                                'message' => '<span>Animation - <strong>New page</strong></span><br><p class="description">The following animations apply on the newly loaded document.</p>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63a843dolo798',
                                'label' => 'Animation Duration',
                                'name' => 'brxc_page_transition_animation_duration_new',
                                'aria-label' => '',
                                'type' => 'number',
                                'instructions' => 'Choose the duration of the animation (in milliseconds).<br><strong>The default duration is set by the browser.</strong>',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'base-font no-border-top',
                                    'id' => '',
                                ),
                                'min' => '',
                                'max' => '',
                                'placeholder' => '300',
                                'step' => 1,
                                'prepend' => '',
                                'append' => 'ms',
                            ),
                            array(
                                'key' => 'field_63a843dygufj8',
                                'label' => 'Animation Delay',
                                'name' => 'brxc_page_transition_animation_delay_new',
                                'aria-label' => '',
                                'type' => 'number',
                                'instructions' => 'Choose the delay of the animation (in milliseconds).<br><strong>The default delay is 0ms.</strong>',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'base-font',
                                    'id' => '',
                                ),
                                'min' => '',
                                'max' => '',
                                'placeholder' => '0',
                                'step' => 1,
                                'prepend' => '',
                                'append' => 'ms',
                            ),
                            array(
                                'key' => 'field_639570d98rdik',
                                'label' => 'Animation Timing Function',
                                'name' => 'brxc_page_transition_animation_timing_function_new',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => 'Choose the timing function of the animation.<br><strong>The default is usually ease-in-out.</strong>',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => 'ease-in-out',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_6399a28derp51',
                                'label' => 'Animation Fill Mode',
                                'name' => 'brxc_page_transition_animation_fill_mode_new',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Choose the fill mode of the animation.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'frontend-theme-select',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'default' => 'Default',
                                    'none' => 'None',
                                    'forwards' => 'Forwards',
                                    'backwards' => 'Backwards',
                                    'both' => 'Both',
                                ),
                                'default_value' => 'default',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                            ),
                            array(
                                'key' => 'field_63882c344dws9',
                                'label' => 'Custom Keyframes',
                                'name' => 'brxc_page_transition_custom_keyframes_new',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => 'Write here your custom CSS keyframes animation.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a876555fch8',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'maxlength' => '',
                                'rows' => '',
                                'placeholder' => '{
    0% { opacity: 0; }
    100% { opacity: 1; }
}',
                                'new_lines' => '',
                            ),
                            array(
                                'key' => 'field_23der44tyexib6',
                                'label' => 'Permissions',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6388e73289b6a',
                                'label' => 'User Roles Permissions',
                                'name' => 'brxc_user_role_permissions',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select which roles should have access to your theme settings.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'checkbox-3-col',
                                    'id' => '',
                                ),
                                'return_format' => '',
                                'allow_custom' => 0,
                                'layout' => '',
                                'toggle' => 0,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_638tt5f119b6a',
                                'label' => 'File Upload Format Permissions',
                                'name' => 'brxc_file_upload_format_permissions',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select the following file upload format to upload inside the Media Library',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'css' => 'CSS',
                                    'json' => 'JSON',
                                ),
                                'default_value' => array(
                                    'css',
                                ),
                                'return_format' => '',
                                'allow_custom' => 0,
                                'layout' => '',
                                'toggle' => 0,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_36gssp598yexib6',
                                'label' => 'Miscellaneous',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_63a8765e6ceed',
                                'label' => 'Disable the "ACF" menu item in your Dashboard',
                                'name' => 'brxc_disable_acf_menu_item',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'If for some reason you prefer to hide the ACF menu item from your Dashboard, use this toggle. Note that if you have ACF PRO installed, this option will be ignored and the ACF menu will be visible.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 1,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_63ab55f50e545',
                                'label' => 'Remove all data when uninstalling the plugin ',
                                'name' => 'brxc_remove_data_uninstall',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Check this toggle if you want to erase all the data from your database when uninstalling the plugin. This includes all your theme options, your color palettes, and your license.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_36gd99ldwwp58b6',
                                'label' => 'Import/Export ',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6sdtt8p9p89db',
                                'label' => 'Export Instruction',
                                'name' => 'brxc_export_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Export Theme Settings </h3>By clicking the following button, you\'ll download a JSON file with all your theme settings options that can be imported on any site using Advanced Themer. All the options that are related to a file upload (like the Framework import, class importer, resources, etc...) will be skipped from the export and need to be updated manually.<br><br><strong>&#9888; Make sure to save your current settings before the export - unsaved settings won\'t be exported.</strong>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63aabb0frfm12',
                                'label' => 'Choose the Data you want to export. ',
                                'name' => 'brxc_export_data',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Check the data you want to export.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'at-theme-settings' => '<span>AT - Theme Settings  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The AT Theme Settings are all the options set inside this page (Theme Settings). It doesn\'t include the native Bricks Settings, the license key, and other settings that aren\'t saved through the Theme Settings page."></a></span>',
                                    'at-ux-settings' => '<span>AT - Builder UX Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export all the UX settings set inside the Builder (AT Main menu, Structure Menu, etc...)."></a></span>',
                                    'at-grid-guides' => '<span>AT - Grid Guides Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Grid Guides settings are all the data relative to the grid guides set inside builder."></a></span>',
                                    'at-right-shortcuts' => '<span>AT - Right Shortcuts <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export the list of elements you use as right shortcuts inside the builder."></a></span>',
                                    'at-strict-editor' => '<span>AT - Strict Editor Settings  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export all the settings relative to the Strict Editor View set inside the builder."></a></span>',
                                    'at-nested-elements' => '<span>AT - Nested Elements Library <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export all the custom nested elements set inside the builder."></a></span>',
                                    'at-query-manager' => '<span>AT - Query Manager Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export all the data set inside the Global Query Manager."></a></span>',
                                    'at-prompt-manager' => '<span>AT - AI Prompt Manager Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export all the data set inside the AI Prompt Manager."></a></span>',
                                    'at-advanced-css-global' => '<span>AT - Advanced CSS (Global CSS) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export the global.css code set inside Advanced CSS."></a></span>',
                                    'at-advanced-css-child' => '<span>AT - Advanced CSS (Child Theme CSS) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export the child.css code set inside Advanced CSS."></a></span>',
                                    'at-advanced-css-framework' => '<span>AT - Advanced CSS (AT Framework) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export all the both the globals and the overrides stylesheets from the AT framework."></a></span>',
                                    'at-advanced-css-custom' => '<span>AT - Advanced CSS (Partials, Custom Stylesheets & Recipes) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export all the custom stylesheet, partials and recipes created inside Advanced CSS."></a></span>',
                                    'bricks-settings' => '<span>Bricks - Settings  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export all the Bricks settings set inside the backend - Bricks - Settings."></a></span>',
                                    'global-colors' => '<span>Bricks - Color Palettes <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Color Palettes are all the palettes set inside the Bricks builder. They include both the palettes generated by the core settings of Bricks and by the AT\'Color Manager (including light/dark/shades versions)."></a></span>',
                                    'global-classes' => '<span>Bricks - Global Classes <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global Classes are all the classes set inside the core Bricks builder - including the Global Classes Categories."></a></span>',
                                    'global-variables' => '<span>Bricks - Global CSS Variables <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global CSS variables are all the variables set inside the Builder through the Variable Manager. They don\'t include all the variables set as Theme CSS variables - these ones are includes inside the Theme Styles. It does include the Global CSS Variable Categories."></a></span>',
                                    'components' => '<span>Bricks - Components <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The components are all the native Bricks components available since v1.12."></a></span>',
                                    'pseudo-classes' => '<span>Bricks - Pseudo Classes  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export the list of pseudo-classes you created inside the builder."></a></span>',
                                    'theme-styles' => '<span>Bricks - Theme Styles <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Theme Styles are all the data set inside the Bricks builder - Settings - Theme Styles. It also includes the Theme variables set on each Theme Style."></a></span>',
                                    'breakpoints' => '<span>Bricks - Breakpoints Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Export all the breakpoints settings set inside the builder - including custom ones."></a></span>',
                                ),
                                'default_value' => array(
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_64439293865db',
                                'label' => 'Export Settings',
                                'name' => 'brxc_export_theme_settings',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ The exported settings can be imported on websites with <strong>Advanced Themer 3.0 (or newer)</strong> installed.</div><br><a id="brxcExportSettings" href="#" class="button button-primary button-large">Export Settings</a>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6sdtrr8evh9db',
                                'label' => 'Import Instruction',
                                'name' => 'brxc_import_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Import Theme Settings </h3>To import the theme settings, select the JSON file you previously exported and click on Import Theme settings. This action will potentially reset all your current options, and load the exported ones. <strong>The operation can\'t be undone, so before going ahead, make sure to backup and export your actual settings.</strong><br><br><strong><div class="helpful-links">&#9888; In case you don\'t see any changes after the import process, make sure to empty your browser/server caches.</strong></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63aabb0ddfe51',
                                'label' => 'Choose the Data you want to import. ',
                                'name' => 'brxc_import_data',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Check the data you want to import.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'at-theme-settings' => '<span>AT - Theme Settings  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The AT Theme Settings are all the options set inside this page (Theme Settings). It doesn\'t include the native Bricks Settings, the license key, and other settings that aren\'t saved through the Theme Settings page."></a></span>',
                                    'at-ux-settings' => '<span>AT - Builder UX Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import all the UX settings set inside the Builder (AT Main menu, Structure Menu, etc...)."></a></span>',
                                    'at-grid-guides' => '<span>AT - Grid Guides Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Grid Guides settings are all the data relative to the grid guides set inside builder."></a></span>',
                                    'at-right-shortcuts' => '<span>AT - Right Shortcuts <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import the list of elements you use as right shortcuts inside the builder."></a></span>',
                                    'at-strict-editor' => '<span>AT - Strict Editor Settings  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import all the settings relative to the Strict Editor View set inside the builder."></a></span>',
                                    'at-nested-elements' => '<span>AT - Nested Elements Library <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import all the custom nested elements set inside the builder."></a></span>',
                                    'at-query-manager' => '<span>AT - Query Manager Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import all the data set inside the Global Query Manager."></a></span>',
                                    'at-prompt-manager' => '<span>AT - AI Prompt Manager Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import all the data set inside the AI Prompt Manager."></a></span>',
                                    'at-advanced-css-global' => '<span>AT - Advanced CSS (Global CSS) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import the global.css code set inside Advanced CSS."></a></span>',
                                    'at-advanced-css-child' => '<span>AT - Advanced CSS (Child Theme CSS) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import the child.css code set inside Advanced CSS."></a></span>',
                                    'at-advanced-css-framework' => '<span>AT - Advanced CSS (AT Framework) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import all the both the globals and the overrides stylesheets from the AT framework."></a></span>',
                                    'at-advanced-css-custom' => '<span>AT - Advanced CSS (Partials, Custom Stylesheets & Recipes) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import all the custom stylesheet, partials and recipes created inside Advanced CSS."></a></span>',
                                    'bricks-settings' => '<span>Bricks - Settings  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import all the Bricks settings set inside the backend - Bricks - Settings."></a></span>',
                                    'global-colors' => '<span>Bricks - Color Palettes <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Color Palettes are all the palettes set inside the Bricks builder. They include both the palettes generated by the core settings of Bricks and by the AT\'Color Manager (including light/dark/shades versions)."></a></span>',
                                    'global-classes' => '<span>Bricks - Global Classes <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global Classes are all the classes set inside the core Bricks builder - including the Global Classes Categories."></a></span>',
                                    'global-variables' => '<span>Bricks - Global CSS Variables <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global CSS variables are all the variables set inside the Builder through the Variable Manager. They don\'t include all the variables set as Theme CSS variables - these ones are includes inside the Theme Styles. It does include the Global CSS Variable Categories."></a></span>',
                                    'components' => '<span>Bricks - Components <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The components are all the native Bricks components available since v1.12."></a></span>',
                                    'pseudo-classes' => '<span>Bricks - Pseudo Classes  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import the list of pseudo-classes you created inside the builder."></a></span>',
                                    'theme-styles' => '<span>Bricks - Theme Styles <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Theme Styles are all the data set inside the Bricks builder - Settings - Theme Styles. It also includes the Theme variables set on each Theme Style."></a></span>',
                                    'breakpoints' => '<span>Bricks - Breakpoints Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import all the breakpoints settings set inside the builder - including custom ones."></a></span>',
                                ),
                                'default_value' => array(
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63a8765grg452',
                                'label' => 'Overwrite Existing Settings',
                                'name' => 'brxc_import_data_overwrite',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'If this option is enabled, existing selected data on the website (such as global classes, theme styles, color palettes, etc...) will be removed before the import. <strong>Use this option wisely!</strong>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_6445f4r7x85db',
                                'label' => 'Import Settings',
                                'name' => 'brxc_import_theme_settings',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ Make sure to import settings from a backup made using <strong>Advanced Themer version 3.0 or newer</strong>. Importing a JSON exported by a previous version of Advanced Themer won\'t work as expected.</div><br><div id="brxcImportWrapper"></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_36gd99fi8wp58b6',
                                'label' => 'Reset Settings ',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6sdggik5r89db',
                                'label' => 'Reset Instruction',
                                'name' => 'brxc_reset_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Reset Settings</h3>By clicking the following button, you\'ll delete the selected options from your database. It\'s recommended to backup your database before proceeding to the theme reset.<br><br><strong>&#9888; The operation can\'t be undone.</strong>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63aabb0rrtrwx',
                                'label' => 'Choose the Data you want to reset. ',
                                'name' => 'brxc_reset_data',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Check the data you want to reset.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'at-theme-settings' => '<span>AT - Theme Settings  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The AT Theme Settings are all the options set inside this page (Theme Settings). It doesn\'t include the native Bricks Settings, the license key, and other settings that aren\'t saved through the Theme Settings page."></a></span>',
                                    'at-ux-settings' => '<span>AT - Builder UX Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset all the UX settings set inside the Builder from the Database (AT Main menu, Structure Menu, etc...)."></a></span>',
                                    'at-local-storage' => '<span>AT - Local Storage <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Remove all the local storages used by Advanced THemer."></a></span>',
                                    'at-grid-guides' => '<span>AT - Grid Guides Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Grid Guides settings are all the data relative to the grid guides set inside builder."></a></span>',
                                    'at-right-shortcuts' => '<span>AT - Right Shortcuts <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset the list of elements you use as right shortcuts inside the builder."></a></span>',
                                    'at-strict-editor' => '<span>AT - Strict Editor Settings  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset all the settings relative to the Strict Editor View set inside the builder."></a></span>',
                                    'at-nested-elements' => '<span>AT - Nested Elements Library <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset all the custom nested elements set inside the builder."></a></span>',
                                    'at-query-manager' => '<span>AT - Query Manager Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset all the data set inside the Global Query Manager."></a></span>',
                                    'at-prompt-manager' => '<span>AT - AI Prompt Manager Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset all the data set inside the Prompt Manager."></a></span>',
                                    'at-advanced-css-global' => '<span>AT - Advanced CSS (Global CSS) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset the global.css code set inside Advanced CSS."></a></span>',
                                    'at-advanced-css-child' => '<span>AT - Advanced CSS (Child Theme CSS) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset the child.css code set inside Advanced CSS."></a></span>',
                                    'at-advanced-css-framework' => '<span>AT - Advanced CSS (AT Framework) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset both the globals and the overrides stylesheets from the AT framework."></a></span>',
                                    'at-advanced-css-custom' => '<span>AT - Advanced CSS (Partials, Custom Stylesheets & Recipes) <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset all the custom stylesheet, partials and recipes created inside Advanced CSS."></a></span>',
                                    'bricks-settings' => '<span>Bricks - Settings  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset all the Bricks settings set inside the backend - Bricks - Settings."></a></span>',
                                    'global-colors' => '<span>Bricks - Color Palettes <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Color Palettes are all the palettes set inside the Bricks builder. They include both the palettes generated by the core settings of Bricks and by the AT\'Color Manager (including light/dark/shades versions)."></a></span>',
                                    'global-classes' => '<span>Bricks - Global Classes <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global Classes are all the classes set inside the core Bricks builder - including the Global Classes Categories."></a></span>',
                                    'global-variables' => '<span>Bricks - Global CSS Variables <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global CSS variables are all the variables set inside the Builder through the Variable Manager. They don\'t include all the variables set as Theme CSS variables - these ones are includes inside the Theme Styles. It does include the Global CSS Variable Categories."></a></span>',
                                    'components' => '<span>Bricks - Components <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The components are all the native Bricks components available since v1.12."></a></span>',
                                    'pseudo-classes' => '<span>Bricks - Pseudo Classes  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset the list of pseudo-classes you created inside the builder."></a></span>',
                                    'theme-styles' => '<span>Bricks - Theme Styles <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Theme Styles are all the data set inside the Bricks builder - Settings - Theme Styles. It also includes the Theme variables set on each Theme Style."></a></span>',
                                    'breakpoints' => '<span>Bricks - Breakpoints Settings <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Reset all the breakpoints settings set inside the builder - including custom ones."></a></span>',
                                ),
                                'default_value' => array(
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_6445f4rccdxv5',
                                'label' => 'Reset message CSS Variables',
                                'name' => 'brxc_reset_attention_message_css_variables',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63aabb0rrtrwx',
                                            'operator' => '==',
                                            'value' => 'global-variables',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top no-padding margin-top-5',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ You are about to <strong>delete ALL the Global CSS Variables</strong> - including the ones created outside AT - from your server. This action can\'t be restored, unless you have a backup of your settings.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6445f4rhdifn5',
                                'label' => 'Reset message Color Palettes',
                                'name' => 'brxc_reset_attention_message_color_palettes',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63aabb0rrtrwx',
                                            'operator' => '==',
                                            'value' => 'global-colors',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top no-padding margin-top-5',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ You are about to <strong>delete ALL the color palettes</strong> - including the ones created outside AT - from your server. This action can\'t be restored, unless you have a backup of your settings.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6445f4rddix5s',
                                'label' => 'Reset message Global Classes',
                                'name' => 'brxc_reset_attention_message_global_classes',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63aabb0rrtrwx',
                                            'operator' => '==',
                                            'value' => 'global-classes',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top no-padding margin-top-5',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ You are about to <strong>delete ALL the global classes</strong> - including the ones created outside AT - from your server. This action can\'t be restored, unless you have a backup of your settings.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6445f4rrtrwz5',
                                'label' => 'Reset message Theme Styles',
                                'name' => 'brxc_reset_attention_message_theme_styles',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63aabb0rrtrwx',
                                            'operator' => '==',
                                            'value' => 'theme-styles',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top no-padding margin-top-5',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ You are about to <strong>delete ALL the theme styles</strong> - including the ones created outside AT - from your server. This action can\'t be restored, unless you have a backup of your settings.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_64dd55fr6j5db',
                                'label' => 'Reset Theme Settings',
                                'name' => 'brxc_reset_theme_settings',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top margin-top-20',
                                    'id' => '',
                                ),
                                'message' => '<a id="brxcResetSettings" href="#" class="button button-primary button-large">Reset Settings</a>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_36gd99fi8fhfvis',
                                'label' => 'Converters',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6395700ddfr41',
                                'label' => 'Global Logical Properties Converter',
                                'name' => 'brxc_convert_to_logical_properties',
                                'aria-label' => '',
                                'conditional_logic' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select the global elements you want to convert to logical properties and click on the "Convert Now" button.',
                                'required' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'padding-bottom-10 vertical-field checkbox-4-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'elements' => '<span>Elements in Page/Posts</span>',
                                    'templates' => '<span>Elements in Templates</span>',
                                    'global-classes' => '<span>Global Classes</span>',
                                    'components' => '<span>Components</span>',
                                ),
                                'default_value' => array(
                                    'elements',
                                    'templates',
                                    'global-classes',
                                    'components'

                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 0,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_6443929rrft81',
                                'label' => '',
                                'name' => 'brxc_convert_logical_settings',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => '',
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-border-top vertical-field btn-container',
                                    'id' => '',
                                ),
                                'message' => '<a id="brxcConvertToDirectional" href="#" class="button button-secondary button-large">Restore Directional Properties</a><a id="brxcConvertToLogical" href="#" class="button button-primary button-large">Convert Now</a>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6395700dfdcxw',
                                'label' => 'Responsive CSS Grid Utility Classes Converter',
                                'name' => 'brxc_convert_to_css_grid_utility_classes',
                                'aria-label' => '',
                                'conditional_logic' => '',
                                'type' => 'checkbox',
                                'instructions' => 'The responsive CSS Grid utility classes were previously managed in the Theme Settings, but as of version 3.1, they’ve been fully migrated to the Class Manager in Advanced Themer. If the automatic migration didn’t work as expected, you can manually run the conversion below to ensure all grid utility classes are correctly moved and available inside the Class Manager.',
                                'required' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'padding-bottom-10 vertical-field',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'global-classes' => '<span>Move your CSS Grid utility classes from the old Theme Settings to the new Class Manager</span>',
                                    
                                ),
                                'default_value' => '',
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 0,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_6443929disjch',
                                'label' => '',
                                'name' => 'brxc_convert_css_grid_utility_classes',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_6395700dfdcxw',
                                            'operator' => '==',
                                            'value' => 'global-classes',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-border-top vertical-field',
                                    'id' => '',
                                ),
                                'message' => '<a id="brxcConvertCSSGridUtilityClasses" href="#" class="button button-primary button-large">Convert Now</a>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6395700decxpl',
                                'label' => 'Hide/Remove Element Settings Converter',
                                'name' => 'brxc_convert_hide_remove_settings',
                                'aria-label' => '',
                                'conditional_logic' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Bricks 2.0 now includes a native Hide/Remove Element feature. To migrate your existing settings from Advanced Themer to Bricks\' built-in functionality, use the converter below.',
                                'required' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'padding-bottom-10 vertical-field',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'hide-remove' => '<span>Convert the Hide/Remove AT settings to vanilla Bricks for all elements/templates</span>',
                                    
                                ),
                                'default_value' => '',
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 0,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_6443929kkgimn',
                                'label' => '',
                                'name' => 'brxc_convert_hide_remove_settings_btn',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_6395700decxpl',
                                            'operator' => '==',
                                            'value' => 'hide-remove',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-border-top vertical-field',
                                    'id' => '',
                                ),
                                'message' => '<a id="brxcConvertHideRemoveSettings" href="#" class="button button-primary button-large">Convert Now</a>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_63d8cb5hh41vc',
                        'label' => 'Global Colors',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'global-colors',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63dd51rtyue5e',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84d4dc52',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cftf81c',
                                'label' => 'Global Colors Instruction',
                                'name' => 'brxc_global_colors_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Global Colors </h3>Manage your global colors inside the builder with ease! All the colors created with the color manager are assigned to a reusable CSS variable. Create shades & scales in few clicks. Define light & dark colors in no time.<div class="helpful-links"><span>ⓘ helpful links: </span><a href="https://advancedthemer.com/category/colors/" target="_blank">Official website</a></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63a8765565dhd',
                                'label' => 'Enable Dark Mode on frontend',
                                'name' => 'brxc_enable_dark_mode_on_frontend',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Enable this option if you want to enqueue the dark color variables on frontend.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_63d651dfufnaq',
                                'label' => 'Force a specific default color sheme',
                                'name' => 'brxc_styles_force_default_color_scheme',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'If you wish to enforce a particular default color scheme on the frontend, please choose either "Dark Mode" or "Light Mode" from the dropdown below. Keep in mind that users can override this selection by manually choosing a different color scheme using a darkmode toggle or button.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a8765565dhd',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'auto' => 'Auto - based on OS preferences',
                                    'light' => 'Light Mode',
                                    'dark' => 'Dark Mode',
                                ),
                                'default_value' => 'auto',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_640660a191382',
                                'label' => 'Global Meta Theme Color',
                                'name' => 'brxc_global_meta_theme_color',
                                'aria-label' => '',
                                'type' => 'color_picker',
                                'instructions' => 'Choose a Global Color for the meta name="theme-color". See <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta/name/theme-color" target="_blank">https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta/name/theme-color</a>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'enable_opacity' => 1,
                                'return_format' => 'string',
                            ),
                            array(
                                'key' => 'field_36gd99l63yexib6',
                                'label' => 'Gutenberg Settings',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_63b3dc8b9484d',
                                'label' => 'Replace Gutenberg Color Palettes',
                                'name' => 'brxc_enable_gutenberg_sync',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'When this option is checked, your Bricks color palettes and Gutenberg color palettes will be synched. Uncheck this option if you don\'t plan to use your custom color palettes with Gutenberg.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_63b3ddc49484f',
                                'label' => 'Remove Default Gutenberg Presets',
                                'name' => 'brxc_remove_default_gutenberg_presets',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'When this option is checked, the default Gutenberg presets\' CSS variables (like --wp--preset--color--black) won\'t be loaded on the frontend anymore.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_63a6a4fg8ec8b6',
                        'label' => 'CSS Variables',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'css-variables',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_6445ab9f3d498',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84llg132',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cded887',
                                'label' => 'CSS Variables Instruction',
                                'name' => 'brxc_css_variables_global_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>CSS Variables</h3>Manage your CSS variables with ease thanks to our in-built CSS Variables GUI. Create fluid and responsive typography / spacing / border / width scales in few clicks! Since version 2.6, all the CSS variables are managed inside the Builder through the Variable Manager.<br><div class="helpful-links"><span>ⓘ helpful links: </span><a href="https://advancedthemer.com/category/fluid-variables/" target="_blank">Official website</a></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ), 
                            array(
                                'key' => 'field_641aferwtt57v',
                                'label' => 'Enable CSS Variables Features',
                                'name' => 'brxc_enable_css_variables_features',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Choose which variables you want to use. Disabling a feature will also apply on the frontend.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => [
                                    'import-framework' => '<span>Import Framework. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The import function let you upload your existing variable\'s labels and integrate them inside the builder functions (such as the variable picker)"></a></span>',
                'theme-variables' => '<span>Theme Variables. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The theme variables are CSS variables attached to a specific theme style. They are managed inside the builder through the variable manager."></a></span>'
                                ],
                                'default_value' => array(),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63a8rrtg15268',
                                'label' => 'Import Framework',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferwtt57v',
                                            'operator' => '==',
                                            'value' => 'import-framework',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6sdwsdwz111db',
                                'label' => 'Import Framework Instruction',
                                'name' => 'brxc_import_framework_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Import your own CSS Variable Framework</h3>In this section, you can upload your own CSS Variable Framework. To do so, just set a label and select the JSON file that contains your categories and variable values. In order to work correctly, you need to follow the same semantic as <a href="' . \BRICKS_ADVANCED_THEMER_URL . 'assets/json/example-framework.json" target="_blank">this example</a>. If you\'re not allowed to upload JSON files to the Media Library, go to the <strong>Settings tab -> Permissions -> toggle on the JSON option.</strong>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6399a28440091',
                                'label' => 'Choose how to import your Framework',
                                'name' => 'brxc_how_to_import_framework',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Choose between importing the JSON from an external file or from the database. The latter is useful if your website is password protected, or if your server limits the access to external files.' ,
                                'required' => 0,
                                'conditional_logic' => '',
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'frontend-theme-select',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'json' => 'External JSON',
                                    'database' => 'From the database',
                                ),
                                'default_value' => 'External JSON',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_63bdedscc0k3l',
                                'label' => 'Label',
                                'name' => 'brxc_import_framework_database_label',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => 'This value will be used as the toggle text of the Variable Pickr.',
                                'required' => 1,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_6399a28440091',
                                            'operator' => '==',
                                            'value' => 'database',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '100',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_64065d4ffp9c6',
                                'label' => 'Paste the JSON object',
                                'name' => 'brxc_import_framework_database',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => 'Insert here a valid JSON object with your categories labels and variables names. Make sure to follow the exact same data structure shown in the placeholder.',
                                'required' => 1,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_6399a28440091',
                                            'operator' => '==',
                                            'value' => 'database',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placeholder' => '{
    "Category Example 1":[
        "test-var-1",
        "test-var-2",
        "test-var-3"
    ],
    "Category Example 2":[
        "test-var-4",
        "test-var-5",
        "test-var-6"
    ],
    "Category Example 3":[
        "test-var-7",
        "test-var-8",
        "test-var-9"
    ]
}',
                                'default_value' => '',
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63b4600putac1',
                                'label' => 'Import your Variable Framework',
                                'name' => 'brxc_import_framework_repeater_skip_export',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_6399a28440091',
                                            'operator' => '==',
                                            'value' => 'json',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'class-importer-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add a new CSS Variable Framework',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63bdeds216ac3',
                                        'label' => 'Label',
                                        'name' => 'brxc_import_framework_label_skip_export',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '100',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b466yy8pac1',
                                    ),
                                    array(
                                        'key' => 'field_6334dcx216ac7',
                                        'label' => 'JSON file',
                                        'name' => 'brxc_import_framework_file_skip_export',
                                        'aria-label' => '',
                                        'type' => 'file',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '100',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'return_format' => 'url',
                                        'library' => 'all',
                                        'min_size' => '',
                                        'max_size' => '',
                                        'mime_types' => 'json',
                                        'parent_repeater' => 'field_63b466yy8pac1',
                                    ),
                                ),
                            ),
                            array(
                                'key' => 'field_63a8rrt561gbn',
                                'label' => 'Theme Variables',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferwtt57v',
                                            'operator' => '==',
                                            'value' => 'theme-variables',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6sdwsdwftg691',
                                'label' => 'Theme Variables Instruction',
                                'name' => 'brxc_theme_variables_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Theme Variables</h3>The theme variables are CSS variables attached to a specific theme style. They are managed directly inside the builder through the Variable Manager. The theme variables have more specificity compared to the global variables: it means you can easily override any global variable by a theme variable. Since the theme variables are integrated inside each specific theme style, it results that you can set different variable values for different theme styles. The theme variables are imported/exported alongside with the theme style settings (using the Bricks core function inside the builder).</br></br>The theme variables require Advanced Themer to be activated in order for the variables to be correctly enqueued on your website. So, if you plan to use Advanced Themer only for the builder tweaks and plan to disable the plugin after the build, it\'s not recommended to use it as it could potentially break your design.</br><div class="helpful-links">As a general rule, use the global variables for values that will hardly change from one site to another, and can be set one time as a variable blueprint or framework. Use the theme variables for variables that are highly design-dependent.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6f5o9q1dr5gcv',
                                'label' => 'Enqueue in',
                                'name' => 'brxc_theme_variables_position',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Select the position in the DOM where the variables should be output. Choosing "Head" will prevent any Flash of Unstyled Content (FOUC), while choosing "Footer" will ensure that the theme variables override any variables initialized in the head.',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'head' => 'Head',
                                    'footer' => 'Footer',
                                ),
                                'default_value' => 'head',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_63b49e5fefk54',
                                'label' => 'Set a Priority',
                                'name' => 'brxc_theme_variables_priority',
                                'aria-label' => '',
                                'type' => 'number',
                                'instructions' => 'The higher the priority you choose, the deeper the style tag will be positioned within the Head/Footer.',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 9999,
                                'min' => 2,
                                'max' => 9999,
                                'placeholder' => '',
                                'step' => 1,
                                'prepend' => '',
                                'append' => '',
                            ),

                        ),
                    ),
                    array(
                        'key' => 'field_63bf7z2w1b209',
                        'label' => 'Class Importer',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'classes-and-styles',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63b59j871b209',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84ddhg51',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6sdw522vr89db',
                                'label' => 'Class Importer Instruction',
                                'name' => 'brxc_class_importer_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Import your classes from a CSS file </h3>In the following repeater, you can add/edit/remove your imported Stylesheets. Each row requires a label and a CSS file attached. The version field is optional. Once saved, the CSS file will be automatically enqueued to your website and all the classes in it will be parsed and added inside the Builder.<br><div class="helpful-links"><strong>Attention:</strong> If you remove one or multiple classes inside your CSS file - or remove an entire CSS file from the repeater - the correspong classes will be automatically removed from the global classes list and from all the elements that are actually using them.</div><br>If you\'re not allowed to upload CSS files to the Media Library, go to the <strong>Settings tab -> Permissions -> toggle on the CSS option.</strong>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63b4bd5c16ac1',
                                'label' => 'Import your Stylesheets',
                                'name' => 'brxc_class_importer_repeater_skip_export',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'class-importer-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => 'field_63b48c6f1b20b',
                                'button_label' => 'Add a new CSS file',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63b4bd5c16ac2',
                                        'label' => 'ID',
                                        'name' => 'brxc_class_importer_id_skip_export',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => 'hidden',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b4bd5c16ac1',
                                    ),
                                    array(
                                        'key' => 'field_63b4bd5c16ac3',
                                        'label' => 'Label',
                                        'name' => 'brxc_class_importer_label_skip_export',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '70',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b4bd5c16ac1',
                                    ),
                                    array(
                                        'key' => 'field_6406649wff5c12',
                                        'label' => 'Enqueue the CSS',
                                        'name' => 'brxc_class_importer_enqueue_skip_export',
                                        'aria-label' => '',
                                        'type' => 'true_false',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '30',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'message' => '',
                                        'default_value' => '1',
                                        'ui_on_text' => '',
                                        'ui_off_text' => '',
                                        'ui' => 1,
                                    ),
                                    array(
                                        'key' => 'field_6f5o9q1d14dd1',
                                        'label' => 'Enqueue in',
                                        'name' => 'brxc_class_importer_position_skip_export',
                                        'aria-label' => '',
                                        'type' => 'select',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => array(
                                            array(
                                                array(
                                                    'field' => 'field_6406649wff5c12',
                                                    'operator' => '==',
                                                    'value' => '1',
                                                ),
                                            ),
                                        ),
                                        'wrapper' => array(
                                            'width' => '40',
                                            'class' => 'frontend-theme-select',
                                            'id' => '',
                                        ),
                                        'choices' => array(
                                            'head' => 'Head',
                                            'footer' => 'Footer',
                                        ),
                                        'default_value' => 'head',
                                        'return_format' => 'value',
                                        'multiple' => 0,
                                        'allow_null' => 0,
                                        'ui' => 0,
                                        'ajax' => 0,
                                        'placeholder' => '',
                                    ),
                                    array(
                                        'key' => 'field_6f8v4s1x4a5ff',
                                        'label' => 'Priority',
                                        'name' => 'brxc_class_importer_priority_skip_export',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => array(
                                            array(
                                                array(
                                                    'field' => 'field_6406649wff5c12',
                                                    'operator' => '==',
                                                    'value' => '1',
                                                ),
                                            ),
                                        ),
                                        'wrapper' => array(
                                            'width' => '30',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => 10,
                                        'min' => '',
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 1,
                                        'prepend' => '',
                                        'append' => '',
                                    ),
                                    array(
                                        'key' => 'field_63b4bd5c16ac4',
                                        'label' => 'Version',
                                        'name' => 'brxc_class_importer_version_skip_export',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => array(
                                            array(
                                                array(
                                                    'field' => 'field_6406649wff5c12',
                                                    'operator' => '==',
                                                    'value' => '1',
                                                ),
                                            ),
                                        ),
                                        'wrapper' => array(
                                            'width' => '30',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '1.0.0',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b4bd5c16ac1',
                                    ),
                                    array(
                                        'key' => 'field_6406649wdr55cx',
                                        'label' => 'Use external URL',
                                        'name' => 'brxc_class_importer_use_url_skip_export',
                                        'aria-label' => '',
                                        'type' => 'true_false',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '30',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'message' => '',
                                        'default_value' => '0',
                                        'ui_on_text' => '',
                                        'ui_off_text' => '',
                                        'ui' => 1,
                                    ),
                                    array(
                                        'key' => 'field_63b4bdf216ac7',
                                        'label' => 'CSS file',
                                        'name' => 'brxc_class_importer_file_skip_export',
                                        'aria-label' => '',
                                        'type' => 'file',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => array(
                                            array(
                                                array(
                                                    'field' => 'field_6406649wdr55cx',
                                                    'operator' => '==',
                                                    'value' => '0',
                                                ),
                                            ),
                                        ),
                                        'wrapper' => array(
                                            'width' => '70',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'return_format' => 'url',
                                        'library' => 'all',
                                        'min_size' => '',
                                        'max_size' => '',
                                        'mime_types' => 'css',
                                        'parent_repeater' => 'field_63b4bd5c16ac1',
                                    ),
                                    array(
                                        'key' => 'field_63b4bd5drd51x',
                                        'label' => 'External URL',
                                        'name' => 'brxc_class_importer_url_skip_export',
                                        'aria-label' => '',
                                        'type' => 'url',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => array(
                                            array(
                                                array(
                                                    'field' => 'field_6406649wdr55cx',
                                                    'operator' => '==',
                                                    'value' => '1',
                                                ),
                                            ),
                                        ),
                                        'wrapper' => array(
                                            'width' => '70',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b4bd5c16ac1',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_63eb7ad55853d',
                        'label' => 'Builder Tweaks',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'builder-tweaks',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63daa58w1b209',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_234hdghl7c8b6',
                                'label' => 'Classes & Styles',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_64074j8de4756',
                                'label' => 'Classes and Styles Builder Tweaks',
                                'name' => 'brxc_builder_tweaks_for_classes',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable/Disable any of the following builder tweaks related to classes and styles. <a href="https://advancedthemer.com/category/styles-classes/" target="_blank">Learn more about the builder tweaks for classes & styles</a>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col big-title',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'clean-deleted-classes' => '<span>Cleanup deleted global classes from the elements.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option if you want to automatically remove the class ID of all the deleted global classes attached to your elements."></a></span>',
                                    'reorder-classes' => '<span>Reorder the global classes alphabetically. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option if you want your global classes reordered alphabetically inside the Builder."></a></span>',
                                    'group-classes-by-lock-status' => '<span>Group Classes by Lock Status. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option if you want your global classes reordered by Lock Status (locked classes first) inside the Builder."></a></span>',
                                    'disable-id-styles' => '<span>Lock Styles on element ID level. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option if you want to lock the ability to style your elements on an ID level. In order to style your elements, you\'ll need to either create/activate a class or click on lock icon to unlock the style tab."></a></span>',
                                    'variable-picker' => '<span>CSS Variables Picker <span class="improved-feature">IMPROVED</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the relevant style fields inside the Bricks builder. When clicked on it, the script will open a modal where you can pick the CSS variable of your choice."></a></span>',
                                    'variable-color-picker'  => '<span>Color Variables Picker <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll enable a new right-click event on all the color inputs inside the builder. This action will open a color variable picker where you can visually select the color to apply to your control."></a></span>',
                                    'autocomplete-variable' => '<span>Suggestions Dropdown for CSS Variables. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a dropdown will show up at the bottom of each field when typing with the suggestion list of all the matching CSS variables."></a></span>',
                                    'autocomplete-variable-preview-hover' => '<span>Autocomplete Suggestions on Hover. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, hovering a CSS variable inside the suggestion dropdown will temporarily apply it the field in order to preview the value inside the builder iframe."></a></span>',
                                    'count-classes' => '<span>Count Classes & Navigation. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new counter will show up next to the class name that indicates the number of times the class is used on the page. Clicking on the counter will scroll the page to each element that is using the active class."></a></span>',
                                    'color-preview' => '<span>Color Preview on hover.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked and the color grid of any element is open, hovering on each color will temporarily apply the color to the element. This is a great way to preview your colors inside the builder."></a></span>',
                                    'class-preview' => '<span>Class Preview on hover.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked and the class dropdown of any element is open, hovering on each class will temporarily apply the class to the element. This is a great way to preview the impact of a class to your elements inside the builder."></a></span>',
                                    'breakpoint-indicator' => '<span>Breakpoint Indicator. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new small device icon next to each group that has style set on different breakpoint inside the style tab."></a></span>',
                                    'locked-class-indicator' => '<span>Locked Class Indicator. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the locked classes will appear with red background inside the builder. The unlocked ones will be displayed with a green background."></a></span>',
                                    'focus-on-first-class'  => '<span>Auto-focus on the First Unlocked Class.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the first unlocked class of the selected element will be enabled instead of the ID style level."></a></span>',
                                    'sync-label'  => '<span>Sync Element\'s label with the first Global Class name. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, as soon as you add a global class to an element, it will synch the elements label based on the class name."></a></span>',
                                    'autoformat-field-values' => '<span>Autoformat your control values. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, AT will autoformat your control values with CSS functions such as: var(), calc(), clamp(), min(), max() and PX to REM converter (as soon as you unfocus the control)."></a></span>',
        
                                ),
                                'default_value' => array(
                                    'reorder-classes',
                                    'disable-id-styles',
                                    'variable-picker',
                                    'autocomplete-variable',
                                    'autocomplete-variable-preview-hover',
                                    'count-classes',
                                    'color-preview',
                                    'class-preview',
                                    'breakpoint-indicator',
                                    'locked-class-indicator',
                                    'focus-on-first-class',
                                    'sync-label',
                                    'autoformat-field-values'
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63a843ddsxzp5',
                                'label' => 'Lock ID Styles on elements with Classes ',
                                'name' => 'brxc_lock_id_styles_with_one_global_class',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Toggle this option if you want lock the styles of elements that have at least one unlocked Global Class attached. Elements without any Global Class (or with only locked classes) will be unlocked by default.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074j8de4756',
                                            'operator' => '==',
                                            'value' => 'disable-id-styles',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 1,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_63d651ddhdbxm',
                                'label' => 'Variable Picker Event ',
                                'name' => 'brxc_variable_picker_type',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Choose to either override the native Bricks Variable icon or use the right-click event (or both!) to open the Variable Picker.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074j8de4756',
                                            'operator' => '==',
                                            'value' => 'variable-picker',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'icon' => 'Bricks Icon',
                                    'mouse' => 'Right-click',
                                    'both' => 'Both',
                                ),
                                'default_value' => 'icon',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_6426ffgdf59xp',
                                'label' => 'Autoformat your controls with the following CSS functions',
                                'name' => 'brxc_autoformat_controls',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select the functions that you want to apply on your control values',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074j8de4756',
                                            'operator' => '==',
                                            'value' => 'autoformat-field-values',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'clamp' => '<span>clamp() <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to create clamp functions on the fly: \'min|max|minViewport|maxViewport\'. Example: typing \'14|24|400|1600\' creates a clamp function where the value will be 14px on 400px screens and 24px on 1600px screens (converted in REM). The viewport values are optionals."></a></span>',
                                    'calc' => '<span>calc() <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to create calc functions on the fly: \'a (operator) b\'. Example: typing \'var(--test) * 2\' creates the following output \'calc(var(--test) * 2)\'. There are 4 valid operators: \'+ - * /\'. It\'s import to leave a space before and after the operator in order to trigger the script correctly."></a></span>',
                                    'min' => '<span>min() <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to create min functions on the fly: \'a ' . htmlspecialchars('&lt;',ENT_QUOTES, 'UTF-8') . ' b\'. Example: typing \'10rem ' . htmlspecialchars('&lt;',ENT_QUOTES, 'UTF-8') . ' 50vw\' creates the following output \'min(10rem,50vw)\'. It\'s important to leave a space before and after the operator in order to trigger the script correctly."></a></span>',
                                    'max' => '<span>max() <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to create max functions on the fly: \'a ' . htmlspecialchars('&gt;',ENT_QUOTES, 'UTF-8') . ' b\'. Example: typing \'12rem ' . htmlspecialchars('&gt;',ENT_QUOTES, 'UTF-8') . ' 25vw\' creates the following output \'max(12rem,25vw)\'. It\'s important to leave a space before and after the operator in order to trigger the script correctly."></a></span>',
                                    'var' => '<span>var() <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to create var functions on the fly: \'--variable\'. Example: typing \'--gap\' creates the following var function \'var(--gap)\'."></a></span>',
                                    'close-var-bracket' => '<span>Close variable brackets. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Enable this function to automatically close your variable\'s brackets. Typing \'var(--test\' will output \'var(--test)\'."></a></span>',
                                    'px-to-rem' => '<span>Pixel to Rem converter. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to convert your pixel values in REM: \'r:pixelValue\'. Example: typing \'r:10\' or \'r:10px\' will output \'1rem\' if your base font-size in Bricks is set to 62.5%"></a></span>',
                                ),
                                'default_value' => array(
                                    'clamp',
                                    'calc',
                                    'min',
                                    'max',
                                    'var',
                                    'close-var-bracket',
                                    'px-to-rem'
                                ),
                                'return_format' => '',
                                'allow_custom' => 0,
                                'layout' => '',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cudcosh',
                                'label' => 'Advanced CSS Instruction',
                                'name' => 'advanced_css_global_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message separation big-title',
                                    'id' => '',
                                ),
                                'message' => '<strong>Advanced CSS</strong> Editor<br><p class="description">Advanced CSS is a powerful CSS editor integrated inside the Bricks builder. It comes with many improvements compared to the native CSS editor of bricks.</p>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ), 
                            array(
                                'key' => 'field_63a843dddwxp5',
                                'label' => 'SASS Integration',
                                'name' => 'brxc_sass_integration_advanced_css',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Toggle this option if you are willing to write SASS codes inside Advanced CSS. This option may require extensive CPU and eventually slowdown the builder.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ), 
                            array(
                                'key' => 'field_63a843dhdhxow',
                                'label' => 'Load Community Recipes',
                                'name' => 'brxc_community_recipes_advanced_css',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Toggle this option if you are willing to import the community recipes inside Advanced CSS and SuperPowerCSS.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 1,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ), 
                            array(
                                'key' => 'field_234jj85lpc8b6',
                                'label' => 'Elements',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_64074ge58dfj2',
                                'label' => 'Elements Builder Tweaks',
                                'name' => 'brxc_builder_tweaks_for_elements',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable/Disable any of the following builder tweaks related to the elements. <a href="https://advancedthemer.com/category/builder-tweaks/" target="_blank">Learn more about the general builder tweaks</a>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col big-title',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'lorem-ipsum' => '<span>Enable Lorem Ipsum Generator.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the relevant text/textarea fields inside the Bricks builder. When clicked on it, the script will automatically generate dummy content for that specific field."></a></span>',
                                    'diable-pin-on-elements' => '<span>Disable the PIN Icon on the elements list. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, The PIN icon inside the Elements List will be hidden."></a></span>',
                                    'close-accordion-tabs' => '<span>Close all open Style accordions by default. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, all the tabs of the Style panel will be closed by default. This allows you to avoid closing the layout tab continuously when styling an element."></a></span>',
                                    'disable-borders-boxshadows' => '<span>Disable element\'s outline when styling Borders and Box-shadow. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the green outline that surrounds the active element will be removed to consent you to easily style both borders and box-shadows."></a></span>',
                                    'resize-elements-icons' => '<span>Elements Columns & Collapse/Expand. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see new icons on the top-right of the global elements panel that will allow you to control the grid\'s column number and to collapse/expand the different categories."></a></span>',
                                    'superpower-custom-css' => '<span>Superpower the Custom CSS control.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the custom css controls will integrate new functionalities such as: match brackets, auto-indent, search function, css variable autocomplete, etc..."></a></span>',
                                    'increase-field-size' => '<span>Increase the Text Controls Size. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the text control fields (where you add your custom values) will be increased from 30% to 50% and leave more room to write css variables and advanced CSS functions."></a></span>',
                                    'class-icons-reveal-on-hover' => '<span>Reveal Class Icons on Hover. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the icons that stand inside the Class input will be hidden by default, and visible when hovered or on focus."></a></span>',
                                    'expand-spacing' => '<span>Expand Spacing Controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new expand icon will be visible next to the spacing controls in the builder and will allow you to resize the input to easily type and see CSS variables."></a></span>',
                                    'link-spacing' => '<span>Persistant Link Spacing Controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, changing the link type on a spacing control (unlinked, opposites or all) will keep the value persistant for all similar controls - even after builder reload."></a></span>',
                                    'sync-heading-label' => '<span>Sync Heading Text with Element Label. <span class="new-feature">NEW</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When enabled, any text you enter in the heading text control will automatically sync with the active element\'s label."></a></span>',
                                    'color-default-raw' => '<span>Color Popup set to RAW and displayed as a LIST. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the Bricks color popup will be automatically  set on RAW mode and displayed as a LIST instead of the grid."></a></span>',
                                    'grid-builder' => '<span>Grid Builder.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new icon will be visible next to the display control in the builder as soon as you select GRID as the display option of your container."></a></span>',
                                    'copy-interactions-conditions' => '<span>Copy/Paste Interactions & Conditions. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you will see new icons inside the interactions/conditions panels to copy and paste your settings from one element to another."></a></span>',
                                    'box-shadow-generator' => '<span>Box-shadow Generator.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new icon will be visible inside the box-shadow control. Clicking on it will open the box-shadow modal where you generate complex box-shadows or apply one of the ready-made preset."></a></span>',
                                    'text-wrapper' => '<span>Advanced Text Wrapper. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, new options will appear inside the Basic Text/Heading element. These options allow you to easily wrap/unwrap your selected content inside custom HTML tags, and add custom properties such as global classes, styles, href, etc..."></a></span>',
                                    'focus-point' => '<span>Focus Point.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This option will add a new icon next to the background-position and the object-position controls. Clicking on it will open a new modal where you can set the exact focus point of your images."></a></span>',
                                    'mask-helper' => '<span>Mask Helper.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This option will add a new icon next to the mask controls. Clicking on it will open a new modal where you can preview all the existing masks included in Bricks on your image."></a></span>',
                                    'dynamic-data-modal' => '<span>Dynamic Data Modal.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This option will replace the core Bricks dynamic data dropdown by a fullscreen filterable modal"></a></span>',
                                ),
                                'default_value' => array(
                                    'lorem-ipsum',
                                    'close-accordion-tabs',
                                    'disable-borders-boxshadows',
                                    'resize-elements-icons',
                                    'superpower-custom-css',
                                    'increase-field-size',
                                    'class-icons-reveal-on-hover',
                                    'expand-spacing',
                                    'sync-heading-label',
                                    'grid-builder',
                                    'copy-interactions-conditions',
                                    'box-shadow-generator',
                                    'text-wrapper',
                                    'focus-point',
                                    'mask-helper',
                                    'dynamic-data-modal',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63d651ddc5a6f',
                                'label' => 'Lorem Ipsum Generator - Type of dummy content',
                                'name' => 'brxc_lorem_type',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Choose between the classic Latin Lorem Ipsum text or your own Custom Dummy Content',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074ge58dfj2',
                                            'operator' => '==',
                                            'value' => 'lorem-ipsum',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'lorem' => 'Lorem Ipsum',
                                    'human' => 'Custom Dummy Content',
                                ),
                                'default_value' => 'lorem',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_63882c3ffbgc1',
                                'label' => 'Lorem Ipsum Generator - Custom Dummy Content',
                                'name' => 'brxc_custom_dummy_content',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => 'Type here the custom dummy content (1 line per sentence). The default human-readable Website Ipsum text has been created by <a href="https://websiteipsum.com/" target="_blank">Kyle Van Deusen</a>',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63d651ddc5a6f',
                                            'operator' => '==',
                                            'value' => 'human',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => $default_dummy_content,
                                'maxlength' => '',
                                'rows' => '',
                                'placeholder' => $default_dummy_content,
                                'new_lines' => '',
                            ),
                            array(
                                'key' => 'field_63a843dhfhx13',
                                'label' => 'SuperPowerCSS - SASS Integration',
                                'name' => 'brxc_sass_integration',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Toggle this option if you are willing to write SASS codes inside SuperPowerCSS. This option may require extensive CPU and eventually slowdown the builder.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074ge58dfj2',
                                            'operator' => '==',
                                            'value' => 'superpower-custom-css',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_64074geddhxir',
                                'label' => 'Elements Icon Shortcuts',
                                'name' => 'brxc_builder_tweaks_shortcuts_icons',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Manage the icon shortcuts inside the element view. <span style="font-weight:800">All the LEGACY shortcuts are included inside the Class Contextual Menu</span>: up to you if you want to add a dedicated icon to you elements (but it\'s not necessary if you have the Class Contextual Menu enabled).',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col big-title separation',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'class-contextual-menu' => '<span>Class Contextual Menu. <span class="new-feature">RECOMMENDED</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, a new icon will show up next to the class input. When clicked on the icon, a new menu will be displayed with tons of improvements for your classes and styles."></a></span>',
                                    'pseudo-shortcut' => '<span>Pseudo-Elements. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, new icon shortcuts will display next to the Condtions and Interactions icons."></a></span>',
                                    'css-shortcut' => '<span>Element CSS Shortcut. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the left panel of each element. Clicking on this icon open the CSS tab of the current element/class."></a></span>',
                                    'parent-shortcut' => '<span>Go to Parent. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the left panel of each element. Clicking on this icon will activate the parent element."></a></span>',
                                    'modified-mode' => '<span>Modified Mode. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the left panel of each element. Clicking on this icon will activate the modified mode: only the controls that have modified values will be visible within the builder."></a></span>',
                                    'style-overview-shortcut' => '<span>Style Overview Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the left panel. Clicking on this icon will open the Style Overview panel with the current element/class settings opened. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'class-manager-shortcut' => '<span>Class Manager Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the left panel when a global class is active. Clicking on this icon will open the Class Manager with the current class settings opened. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'extend-classes' => '<span>Extend Global Classes and Styles Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This feature will consent you to extend the classes & styles from an element to his parents/children. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'find-and-replace' => '<span>Find & Replace Styles Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This feature will consent you to replace any style value assigned to any element inside the builder. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'plain-classes' => '<span>Plain Classes Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new icon will show up next to the element\'s class field. When you click on it, a popup window will appear where you can type the classes you want to add/remove in bulk. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'export-styles-to-class' => '<span>Export ID Styles to Classes Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new export icon will show up next to the element\'s class field. When you\'ll click on it, you\'ll be able to insert a class name and export all your ID styles to it. Note that you can also import the ID styles if a class is activated. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'clone-class' => '<span>Clone Class Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new clone icon will show up once you activate a class. Once clicked, an input will be visibile with the current class name prefiled. Quickly change the name of the class and save it. All the styles will be copied to the new class. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'copy-class-to-clipboard' => '<span>Copy Class to Clipboard Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new clone icon will show up once you activate a class. Once clicked, the active class\'s name will be copied to the clipboard. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',

                                ),
                                'default_value' => array(
                                    'class-contextual-menu',
                                    //'tabs-shortcuts',
                                    'pseudo-shortcut',
                                    'css-shortcut',
                                    'parent-shortcut',
                                    'modified-mode',
                                    'style-overview-shortcut',
                                    'class-manager-shortcut',
                                    'plain-classes',
                                    'export-styles-to-class'
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_6420a42b78413',
                                'label' => 'Pseudo Elements Shortcuts',
                                'name' => 'brxc_enable_shortcuts_icons',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select the shortcut icons you want to display inside each element panel. This will create an icon for each status to quickly activate/deactivate your pseudo-classes when styling an element inside the Builder',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074geddhxir',
                                            'operator' => '==',
                                            'value' => 'pseudo-shortcut',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'hover' => 'hover',
                                    'before' => 'before',
                                    'after' => 'after',
                                    'active' => 'active',
                                    'focus' => 'focus',
                                ),
                                'default_value' => array(
                                    'hover',
                                    'before',
                                    'after',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63a843d474fd8',
                                'label' => 'Plain Classes - Set as Default Global Class Picker',
                                'name' => 'brxc_open_plain_class_by_default',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Enabling this option will make the Plain Classes modal the default view when interacting with the Bricks Global Class input. Instead of the native dropdown, the Plain Classes modal will appear.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074geddhxir',
                                            'operator' => '==',
                                            'value' => 'plain-classes',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ), 
                            array(
                                'key' => 'field_64074gedhc99o',
                                'label' => 'Elements Custom Settings',
                                'name' => 'brxc_builder_default_custom_settings',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Change the default settings of a selected number of elements inside the builder or add new properties to the builder. <strong>The orange settings require AT to be installed in order to work correctly - it\'s not safe to enable them if you plan to disable AT once you finished building this site.</strong>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col big-title separation',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'text-basic-p' => '<span>Set "p" as the default HTML tag for Basic Text. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to set the default HTML tag of all the basic text elements as a paragraph (p)."></a></span>',
                                    'image-figure' => '<span>Set "figure" as the default HTML tag for Images. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to set the default HTML tag of all the image elements as a figure."></a></span>',
                                    'image-caption-off' => '<span>Set caption as "No caption" for Images. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to set No caption as the default caption value for all the image elements."></a></span>',
                                    'button-button' => '<span>Set "button" as the default HTML tag for Buttons. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to set the default HTML tag of all the button elements as a button."></a></span>',
                                    'heading-textarea' => '<span>Set text input as a textarea for Headings. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to set text input of all the heading elements as a textarea."></a></span>',
                                    'filter-tab' => '<span>New Filters / Transitions Tab. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to create a new accordion called \'Filters / Transitions\' in the style tab of each element."></a></span>',
                                    'classes-tab' => '<span>New Classes / ID Tab. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to to create a new accordion called \'Classes / ID\' in the style tab of each element.."></a></span>',
                                    'overflow-dropdown' => '<span>Set the Overflow control as a dropdown. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to transform the overflow control in a dropdown control with predefined values."></a></span>',
                                    'notes' => '<span>Admin/Editor Notes<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the ability to add notes to any Bricks Element."></a></span>',
                                    'generated-code' => '<span>Generated Code <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the ability to see the generated CSS & HTML code of any Bricks element."></a></span>',
                                    'combobox' => '<span>Transform all "select" controls into Comboboxes <span class="new-feature">NEW</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add a search/edit input on top of all the select control dropdowns (Comboboxes)"></a></span>',
                                    'animation-tab' => '<span class="attention-text">New "Animation" Tab & Controls.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add all animation controls - including the new experimental animation-timeline feature for native CSS scrolling animations."></a></span>',
                                    'background-clip'=> '<span class="attention-text">New "background-clip" control. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the background-clip control to your background setting options."></a></span>',
                                    'white-space' => '<span class="attention-text">New "white-space" control. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the white-space property to your style layout settings."></a></span>',
                                    'content-visibility' => '<span class="attention-text">New "content-visibility" & "contain-intrinsic-size" controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the content-visibility and the contain-intrinsic-size properties to your style layout settings."></a></span>',
                                    'column-count' => '<span class="attention-text">New "column-count" controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the column properties (count, gap, fill, width) when selecting display block to an element."></a></span>',
                                    'break' => '<span class="attention-text">New "break" controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the break settings (break-before, break-inside, break-after) to your style layout settings."></a></span>',
                                    'transform' => '<span class="attention-text">New "transform" & "perspective" controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add advanced transform properties (style, box, perspective, perspective-origin, backface-visibility) to your transform settings."></a></span>',
                                    'css-filters' => '<span class="attention-text">New "backdrop-filter" controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the backdrop-filter property to your Filters settings."></a></span>',
                                    'logical-properties' => '<span class="attention-text">Logical Properties.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This option allows you to add all logical properties controls"></a></span>',
                                    'remove-style-controls' => '<span class="danger-text">Remove All Styling Controls. <span class="new-feature">NEW</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This option allows you to remove all the styling controls in the builder. Toggle this option if you plan to use 100% custom CSS."></a></span>',
                                    
                                ),
                                'default_value' => array(
                                    'text-basic-p',
                                    'heading-textarea',
                                    'filter-tab',
                                    'classes-tab',
                                    'overflow-dropdown',
                                    'notes',
                                    'generated-code',
                                    'combobox',

                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_6395700ff8f8c',
                                'label' => 'Animation Controls - Scroll/View Timeline Polyfill',
                                'name' => 'brxc_scrolling_timeline_polyfill',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'To ensure that the new scrolling animation-timeline features work seamlessly across all browsers, enable this option.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074gedhc99o',
                                            'operator' => '==',
                                            'value' => 'animation-tab',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_6395700sxsmoh',
                                'label' => 'Logical Properties - Remove Directional Properties',
                                'name' => 'brxc_replace_directional_properties',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'If this field is checked, all the directional properties inside the Style tab will be removed.<div class="danger-links">⚠ The values applied to the existing directional controls won\'t apply anymore until they get converted.</div>',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074gedhc99o',
                                            'operator' => '==',
                                            'value' => 'logical-properties',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ), 
                            array(
                                'key' => 'field_23df21t6y9c8b6',
                                'label' => 'Keyboard Shortcuts',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_641af4few523',
                                'label' => 'Keyboard Shortcuts',
                                'name' => 'brxc_keyboard_shortcuts_type',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable the following keyboard shortcuts inside the builder. Use CTRL + CMD for Mac users - CTRL + SHIFT for windows users.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col big-title',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'move-element' => '<span>Move Elements inside the structure panel. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, you\'ll be able to move the elements inside the structure panel using the key SHIFT + ARROW."></a></span>',
                                    'open-at-modal' => '<span>Open AT\'s modals/functions. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use keyboard shortcuts to open any Advanced Themer\'s modals and general functions."></a></span>',
                                ),
                                'default_value' => array(
                                    'move-element',
                                    'open-at-modal',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63dba4f555d93',
                                'label' => 'Enable Quick Search ',
                                'name' => 'brxc_shortcut_quick_search',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'f',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63dba4f8f5056',
                                'label' => 'Enable/Disable Grid Guides',
                                'name' => 'brxc_shortcut_grid_guides',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'i',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63dba4b8f5055',
                                'label' => 'Enable/Disable X-Mode',
                                'name' => 'brxc_shortcut_xmode',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'j',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63dba510f5057',
                                'label' => 'Enable/Disable Contrast Checker',
                                'name' => 'brxc_shortcut_contrast_checker',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'k',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63dba543f5058',
                                'label' => 'Enable/Disable Darkmode',
                                'name' => 'brxc_shortcut_darkmode',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'z',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63dba55ff5059',
                                'label' => 'Open the Advanced CSS Modal',
                                'name' => 'brxc_shortcut_stylesheet',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'l',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63dba59df505a',
                                'label' => 'Open the Resources Modal',
                                'name' => 'brxc_shortcut_resources',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'x',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_6418f83d91c38',
                                'label' => 'Open the OpenAI Assistant Modal',
                                'name' => 'brxc_shortcut_openai',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'o',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54fe1c38',
                                'label' => 'Open the BricksLabs Modal',
                                'name' => 'brxc_shortcut_brickslabs',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'n',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ttrkc0',
                                'label' => 'Open the Color Manager',
                                'name' => 'brxc_shortcut_color_manager',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'm',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ddxbo8',
                                'label' => 'Open the Class Manager',
                                'name' => 'brxc_shortcut_class_manager',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => ',',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54drd5pl',
                                'label' => 'Open the Variable Manager',
                                'name' => 'brxc_shortcut_variable_manager',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'v',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ffrdl1',
                                'label' => 'Open the Query Loop Manager',
                                'name' => 'brxc_shortcut_query_loop_manager',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'g',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt5455v8c',
                                'label' => 'Open the AI Prompt Manager',
                                'name' => 'brxc_shortcut_prompt_manager',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'a',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ggbg85',
                                'label' => 'Open the Structure Helper',
                                'name' => 'brxc_shortcut_structure_helper',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'h',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54drwc51',
                                'label' => 'Open Find & Replace',
                                'name' => 'brxc_shortcut_find_and_replace',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'f',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ppin6d',
                                'label' => 'Open Plain Classes',
                                'name' => 'brxc_shortcut_plain_classes',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'p',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ffuc5x',
                                'label' => 'Open Nested Elements Library',
                                'name' => 'brxc_shortcut_nested_elements',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'e',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ssdxo5',
                                'label' => 'Open Structure Generator',
                                'name' => 'brxc_shortcut_codepen_converter',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'c',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ssfaw7',
                                'label' => 'Open Quick Remote Template ',
                                'name' => 'brxc_shortcut_remote_template',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 't',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54drdpnm',
                                'label' => 'Open AT Framework',
                                'name' => 'brxc_shortcut_at_framework',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'u',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),

                        ),
                    ),
                    array(
                        'key' => 'field_63d8cb5wweq55',
                        'label' => 'Strict Editor View',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'strict-editor-view',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63dd51rddtr57',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84ppo63m',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1ctrtr98',
                                'label' => 'Strict Editor View Instruction',
                                'name' => 'brxc_stric_editor_view_global_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Strict Editor View</h3>Strict Editor View limits access to style controls in the builder for your clients, while also introducing several enhancements that improve the overall Bricks experience for non-technical users. <br><br><strong>Please note: the settings below apply to all editor roles (as opposed to full-access roles), regardless of whether you’re using the custom capabilities introduced in Bricks 2.0.</strong><br><div class="helpful-links"><span>ⓘ helpful links: </span><a href="https://advancedthemer.com/category/strict-editor-view/" target="_blank">Official website</a></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),   
                            array(
                                'key' => 'field_64066003f4140',
                                'label' => 'Change Tobbar Logo Image',
                                'name' => 'brxc_change_logo_img_skip_export',
                                'aria-label' => '',
                                'type' => 'image',
                                'instructions' => 'Switch the default Bricks logo to yours inside the Editor View.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'return_format' => 'string',
                                'library' => 'all',
                                'min_width' => '',
                                'min_height' => '',
                                'min_size' => '',
                                'max_width' => '',
                                'max_height' => '',
                                'max_size' => '',
                                'mime_types' => '',
                                'preview_size' => 'medium',
                            ),
                            array(
                                'key' => 'field_640660aee91e4',
                                'label' => 'Change Accent Color',
                                'name' => 'brxc_change_accent_color',
                                'aria-label' => '',
                                'type' => 'color_picker',
                                'instructions' => 'Personalize the accent color of the Editor Mode to match your brand\'s color guidelines.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '#ffd64f',
                                'enable_opacity' => 0,
                                'return_format' => 'string',
                            ),
                            array(
                                'key' => 'field_64065d4de47ca',
                                'label' => 'Disable Toolbar Icons',
                                'name' => 'brxc_disable_toolbar_icons',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Click on any of the following icons to hide them from the Strict Editor View\'s Toolbar.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-5-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'logo' => 'Logo',
                                    'pages' => 'Pages',
                                    'command-palette' => 'Command Palette',
                                    'breakpoints' => 'Breakpoints',
                                    'dimensions' => 'Dimensions',
                                    'undo-redo' => 'Undo / Redo',
                                    'edit' => 'Edit with WordPress',
                                    'new-tab' => 'View on Frontend',
                                    'preview' => 'Preview',
                                ),
                                'default_value' => array(
                                    'help',
                                    'pages',
                                    'revisions',
                                    'class-manager',
                                    'settings',
                                    'breakpoints',
                                    'dimensions',
                                    'undo-redo' => 'Undo / Redo',
                                    'edit',
                                    'new-tab',
                                    'preview',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_64065d4ttv4z2',
                                'label' => 'Builder Tweaks',
                                'name' => 'brxc_strict_editor_view_tweaks',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable/Disable any of the following Strict Editor View\'s builder tweaks.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'hide-quick-access-bar' => '<span>Hide Quick Access Bar in the Element Panel.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the quick access bar that allows to switch between style tabs is hidden."></a></span>',
                                    'hide-element-panel-header' => '<span>Hide Header in the Element Panel.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the elements header within the element panel is hidden."></a></span>',
                                    'hide-id-class' => '<span>Hide ID/Class control in the Element Panel.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the control that shows the ID/Class of each element is hidden (inside the element window)."></a></span>',
                                    'disable-all-controls' => '<span class="danger-text">Disable All controls in the Element Panel by default.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, all the controls for each element are disabled in the Editor View. You will need to manually enable the controls you want to show to the editors inside the Builder using the Strict Editor Settings tweak (AT Main Menu - Strict Editor Settings) in the admin builder view."></a></span>',
                                    'hide-dynamic-data' => '<span>Hide Dynamic Data trigger in the Element Panel. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the dynamic data icon won\'t show up inside the controls that allow dynamic data."></a></span>',
                                    'hide-element-search-box' => '<span>Hide Search Box in the Element Panel.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the search box at the bottom of the element panel is hidden."></a></span>',
                                    'hide-structure-panel' => '<span>Hide Structure panel.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the right structure panel will be hidden and the preview window will take all the available space in the builder."></a></span>',
                                    'hide-element-states' => '<span>Hide Elements States in the Structure Panel.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the elements states (icons on the right of each element) will be hidden in the Structure Panel."></a></span>',
                                    'hide-preview-element-actions' => '<span>Hide Elements Actions within the Preview Iframe.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the elements actions (icons on the bottom of each element) will be hidden in the Preview Iframe."></a></span>',
                                    'hide-text-toolbar' => '<span>Hide Text/Heading Toolbar in the Preview Iframe.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the text toolbar will be hidden when clicking on texts inside the preview window."></a></span>',
                                    'disable-contextual-menu' => '<span>Disable Contextual Menu\'s.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, right-clicking within the Preview Iframe or within the Structure Panel won\'t trigger the corresponding Contextual Menu."></a></span>',
                                    'custom-css' => '<span>Inject Custom CSS.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, you\'ll be able to add Custom CSS that will only apply inside the Builder Editor view."></a></span>',

                                ),
                                'default_value' => array(
                                    'hide-quick-access-bar',
                                    'hide-element-panel-header',
                                    'hide-id-class',
                                    'hide-dynamic-data',
                                    'hide-element-search-box',
                                    'hide-structure-panel',
                                    'hide-element-states',
                                    'hide-preview-element-actions',
                                    'hide-text-toolbar',
                                    'disable-contextual-menu'
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_64065d455dvt2',
                                'label' => 'Custom CSS ',
                                'name' => 'brxc_strict_editor_custom_css',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => 'The following CSS is being applied inside the Strict Editor View Builder. You can add/delete/modify your own CSS rules by modifying the following code.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64065d4ttv4z2',
                                            'operator' => '==',
                                            'value' => 'custom-css',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field textarea-100',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_63d8cb5tut4gg',
                        'label' => 'AI',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'ai',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63dd51rkj633r',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84fun798',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1c565gtc',
                                'label' => 'AI Instruction',
                                'name' => 'brxc_ai_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>AI Integration</h3>In this section, you can enable the OpenAI intregration inside the Bricks builder (create AI generated text, images, codes, etc...). Make sure to insert a valid OpenAI API Key.<br><div class="helpful-links"><span>ⓘ helpful links: </span><a href="https://advancedthemer.com/category/ai-integration/" target="_blank">Official website</a></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_64018efb660fb',
                                'label' => 'OpenAI API KEY',
                                'name' => 'brxc_ai_api_key_skip_export',
                                'aria-label' => '',
                                'type' => 'password',
                                'instructions' => 'Insert here your OpenAI API key that you can find in your <a href="https://platform.openai.com/account/api-keys" target="_blank">OpenAI account</a>. The key will be stored in your database using a 128-bit AES encryption method.<br><strong>This field is mandatory if you plan to use the AI integration.</strong>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_6399a28frf471',
                                'label' => 'Default AI model. ',
                                'name' => 'brxc_default_ai_model',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Choose the default AI model from the following list',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'frontend-theme-select',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'gpt-5' => 'gpt-5',
                                    'gpt-5-mini' => 'gpt-5-mini',
                                    'gpt-5-nano' => 'gpt-5-nano',
                                    'gpt-4.1' => 'gpt-4.1',
                                    'gpt-4.1-mini' => 'gpt-4.1-mini',
                                    'gpt-4.1-nano' => 'gpt-4.1-nano',
                                    'o4-mini' => 'o4-mini',
                                    'o3' => 'o3',
                                    'o3-mini' => 'o3-mini',
                                    'o1' => 'o1',
                                    'o1-pro' => 'o1-pro',
                                    'gpt-4o' => 'gpt-4o',
                                    'gpt-4o-mini' => 'gpt-4o-mini',
                                    'gpt-4' => 'gpt-4',
                                    'gpt-4-turbo' => 'gpt-4-turbo',
                                    'gpt-3.5-turbo' => 'gpt-3.5-turbo',
                                ),
                                'default_value' => 'gpt-5-mini',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_64e487ajsie19',
                                'label' => 'Tones of Voice',
                                'name' => 'brxc_ai_tons_of_voice',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => 'Set the list of the predefined tones of voice inside the prompt\'s advanced options',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 'Authoritative
Conversational
Casual
Enthusiastic
Formal
Frank
Friendly
Funny
Humorous
Informative
Irreverent
Matter-of-fact
Passionate
Playful
Professional
Provocative
Respectful
Sarcastic
Smart
Sympathetic
Trustworthy
Witty',
                                'maxlength' => '',
                                'rows' => '',
                                'placeholder' => '',
                                'new_lines' => '',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_63d8cb54c801e',
                        'label' => 'Extras',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'extras',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63dd51rw1b209',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84u7c8b6',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cdrt527',
                                'label' => 'Extras Instruction',
                                'name' => 'brxc_extras_global_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Extras</h3>Inside the extras section, you\'ll find nice-to-have features that don\'t fit in any of the previous categories and enhance your overall Bricks experience.',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63466fft58sb6',
                                'label' => 'Resources',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cdr89db',
                                'label' => 'Resource Instruction',
                                'name' => 'brxc_resource_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Resources Panel</h3>In the following repeater, you can add/edit/remove the images added inside the Resources Panel. Each row requires a category label and an image gallery. Once saved, the gallery will be accessible inside the Resource Panel, on the right side of the Builder toolbar.',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63d8cb65c801f',
                                'label' => 'Resources',
                                'name' => 'brxc_resources_repeater_skip_export',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'class-importer-repeater resources',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add a Gallery',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63d8cbb7c8020',
                                        'label' => 'Category',
                                        'name' => 'brxc_resources_category_skip_export',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63d8cb65c801f',
                                    ),
                                    array(
                                        'key' => 'field_63d8cbd8c8021',
                                        'label' => 'Gallery',
                                        'name' => 'brxc_resources_gallery_skip_export',
                                        'aria-label' => '',
                                        'type' => 'gallery',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'return_format' => 'array',
                                        'library' => 'all',
                                        'min' => 1,
                                        'max' => '',
                                        'min_width' => '',
                                        'min_height' => '',
                                        'min_size' => '',
                                        'max_width' => '',
                                        'max_height' => '',
                                        'max_size' => '',
                                        'mime_types' => '',
                                        'insert' => 'append',
                                        'preview_size' => 'medium',
                                        'parent_repeater' => 'field_63d8cb65c801f',
                                    ),
                                    array(
                                        'key' => 'field_63882c3fhfc55',
                                        'label' => 'Notes',
                                        'name' => 'brxc_resources_notes_skip_export',
                                        'aria-label' => '',
                                        'type' => 'textarea',
                                        'instructions' => 'Type here any note you want to show inside the Resources Modal.',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'maxlength' => '',
                                        'rows' => '',
                                        'placeholder' => '',
                                        'new_lines' => '',
                                    ),
                                ),
                            ),
                            array(
                                'key' => 'field_63466fffft45b',
                                'label' => 'BricksLabs',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6scht85f999db',
                                'label' => 'BricksLabs Instruction',
                                'name' => 'brxc_bricklabs_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>BricksLabs Panel</h3>The BricksLabs feed is activated on the builder. Just click on the "lab" icon inside the builder\'s topbar to see the last articles published on Bricklabs and filter your results by any given keyword.',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'bricks-advanced-themer',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
                'show_in_rest' => 0,
            ));
            
            endif;			
    }

}