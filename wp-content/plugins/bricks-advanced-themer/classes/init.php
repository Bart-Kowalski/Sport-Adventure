<?php
namespace Advanced_Themer_Bricks;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Init {
    public static function init_hooks() {
        /*--------------------------------------
        Misc
        --------------------------------------*/

        add_action('admin_notices', 'Advanced_Themer_Bricks\AT__Admin::check_theme_version'); 

        /*--------------------------------------
        Plugin Page
        --------------------------------------*/
        
        add_filter( 'plugin_action_links_bricks-advanced-themer/bricks-advanced-themer.php', 'Advanced_Themer_Bricks\AT__Admin::add_plugin_links' );
        
        /*--------------------------------------
        Add ACF PRO
        --------------------------------------*/

        add_action( 'admin_init', 'Advanced_Themer_Bricks\AT__ACF::acf_get_role');
        //add_action( 'acf/init', 'Advanced_Themer_Bricks\AT__ACF::acf_color_palettes_fields');
        add_action( 'acf/init', 'Advanced_Themer_Bricks\AT__ACF::acf_settings_fields');
        
        /*--------------------------------------
        EDD Updated
        --------------------------------------*/
        
        add_action( 'init', 'Advanced_Themer_Bricks\AT__License::brxc_plugin_updater' );
        add_action( 'admin_init', 'Advanced_Themer_Bricks\AT__License::brxc_register_option' );
        add_action( 'admin_init', 'Advanced_Themer_Bricks\AT__License::brxc_activate_license' );
        add_action( 'admin_init', 'Advanced_Themer_Bricks\AT__License::brxc_deactivate_license' );
        add_action( 'admin_notices', 'Advanced_Themer_Bricks\AT__License::brxc_admin_notices' );
        
        /*--------------------------------------
        Option Page Menu
        --------------------------------------*/
        add_action( 'acf/init', 'Advanced_Themer_Bricks\AT__ACF::create_advanced_themer_option_page');
        add_action( 'admin_menu', 'Advanced_Themer_Bricks\AT__License::brxc_license_menu',9999);
        
        /*--------------------------------------
        Menu
        --------------------------------------*/

        add_filter( 'admin_menu', 'Advanced_Themer_Bricks\AT__Admin::remove_theme_settings_from_bricks_menu', 999);

        /*--------------------------------------
        Variables & Classes
        --------------------------------------*/
        add_action( 'init', 'Advanced_Themer_Bricks\AT__Conversion::convert_clamp_settings', 20);
        add_action( 'init', 'Advanced_Themer_Bricks\AT__Conversion::convert_grid_utility_classes', 25);
        add_action( 'init', 'Advanced_Themer_Bricks\AT__Conversion::convert_global_colors_prefix', 35);
        add_action( 'init', 'Advanced_Themer_Bricks\AT__ACF::load_global_acf_variable', 2 );
        
        /*--------------------------------------
        Load Custom data in ACF fields
        --------------------------------------*/
        add_filter( 'acf/load_field/key=field_6388e73289b6a', 'Advanced_Themer_Bricks\AT__ACF::load_user_roles_inside_select_field');
        add_filter( 'acf/load_value/key=field_63882c3ffbgc1', 'Advanced_Themer_Bricks\AT__ACF::load_human_readable_text_value', 10, 3);
        add_filter( 'acf/load_value/key=field_64018efb660fb', 'Advanced_Themer_Bricks\AT__ACF::load_openai_password', 10, 3);
        add_action( 'acf/save_post', 'Advanced_Themer_Bricks\AT__ACF::save_openai_password', 5, 1 );
        add_action( 'acf/fields/flexible_content/no_value_message', 'Advanced_Themer_Bricks\AT__ACF::change_flexible_layout_no_value_msg', 10, 3);

        
        /*--------------------------------------
        Register / Enqueue Styles
        --------------------------------------*/
        
        add_action( 'init', 'Advanced_Themer_Bricks\AT__Admin::register_scripts' );
        add_action( 'wp_enqueue_scripts', 'Advanced_Themer_Bricks\AT__Admin::enqueue_theme_variables');
        add_action( 'admin_enqueue_scripts', 'Advanced_Themer_Bricks\AT__Admin::admin_enqueue_scripts' );
        add_action( 'get_footer', 'Advanced_Themer_Bricks\AT__Admin::enqueue_builder_scripts' );
        add_action( 'get_footer', 'Advanced_Themer_Bricks\AT__Admin::enqueue_builder_scripts_strict_editor' );
        add_action( 'acf/input/admin_enqueue_scripts', 'Advanced_Themer_Bricks\AT__ACF::acf_admin_enqueue_scripts' );
        
        /*--------------------------------------
        Global Colors & Variables
        --------------------------------------*/
        add_action( 'init', 'Advanced_Themer_Bricks\AT__Global_Colors::force_default_color_scheme');
        add_action( 'init', 'Advanced_Themer_Bricks\AT__Global_Colors::theme_support_load_gutenberg_colors', 30 );
        add_action( 'enqueue_block_assets', 'Advanced_Themer_Bricks\AT__Frontend::enqueue_gutenberg_colors_in_iframe'  );
        add_action( 'init', 'Advanced_Themer_Bricks\AT__Frontend::remove_default_gutenberg_presets' );
        
        add_action( 'wp_enqueue_scripts', 'Advanced_Themer_Bricks\AT__Frontend::load_variables_on_frontend', 2, 5 );
        add_action( 'admin_enqueue_scripts', 'Advanced_Themer_Bricks\AT__Admin::load_variables_on_backend' );
        add_action( 'init', 'Advanced_Themer_Bricks\AT__Frontend::meta_theme_color_tag');
        
        /*--------------------------------------
        Add Admin Bar Menu Item
        --------------------------------------*/
        
        add_action( 'admin_bar_menu', 'Advanced_Themer_Bricks\AT__Admin::add_admin_bar_menu', 100);

        /*--------------------------------------
        Add Admin Bar Menu Item
        --------------------------------------*/
        add_action( 'init', 'Advanced_Themer_Bricks\AT__Builder::populate_imported_classes' );
        add_action( 'wp_footer', 'Advanced_Themer_Bricks\AT__Builder::add_modal_after_body_wrapper' );

        /*--------------------------------------
        Register New Bricks Elements
        --------------------------------------*/
        
        add_action( 'init',  'Advanced_Themer_Bricks\AT__Helpers::register_bricks_elements', 11 );
  
        /*--------------------------------------
        Class Importer
        --------------------------------------*/

        add_action( 'init', 'Advanced_Themer_Bricks\AT__Class_Importer::enqueue_uploaded_css' );

        /*--------------------------------------
        Global Query Loops
        --------------------------------------*/

        add_filter( 'bricks/setup/control_options', 'Advanced_Themer_Bricks\AT__Builder::setup_query_controls');
        add_filter( 'bricks/query/run', 'Advanced_Themer_Bricks\AT__Builder::maybe_run_new_queries', 10, 2);
        add_filter( 'bricks/query/loop_object', 'Advanced_Themer_Bricks\AT__Builder::setup_post_data', 10, 3);

        /*--------------------------------------
        SASS
        --------------------------------------*/

        add_action( 'wp',  'Advanced_Themer_Bricks\AT__frontend::update_mixins_before_element_render', 10 );
        add_action( 'init', 'Advanced_Themer_Bricks\AT__frontend::enqueue_advanced_css_files', 10 );
        add_action( 'enqueue_block_editor_assets', 'Advanced_Themer_Bricks\AT__frontend::enqueue_advanced_css_files_gutenberg' );
        
        /*--------------------------------------
        AJAX Request
        --------------------------------------*/

        add_action( 'wp_ajax_openai_ajax_function', 'Advanced_Themer_Bricks\AT__Builder::openai_ajax_function' );
        add_action( 'wp_ajax_nopriv_openai_ajax_function', 'Advanced_Themer_Bricks\AT__Builder::openai_ajax_function' );
        add_action( 'wp_ajax_openai_save_image_to_media_library', 'Advanced_Themer_Bricks\AT__Builder::openai_save_image_to_media_library' );
        add_action( 'wp_ajax_nopriv_openai_save_image_to_media_library', 'Advanced_Themer_Bricks\AT__Builder::openai_save_image_to_media_library' );
        add_action( 'wp_ajax_convert_to_logical_properties', 'Advanced_Themer_Bricks\AT__Ajax::convert_to_logical_properties_callback');
        add_action( 'wp_ajax_norpiv_convert_to_logical_properties', 'Advanced_Themer_Bricks\AT__Ajax::convert_to_logical_properties_callback');
        add_action( 'wp_ajax_convert_to_css_grid_utility_classes', 'Advanced_Themer_Bricks\AT__Ajax::convert_to_css_grid_utility_classes_callback');
        add_action( 'wp_ajax_norpiv_convert_to_css_grid_utility_classes', 'Advanced_Themer_Bricks\AT__Ajax::convert_to_css_grid_utility_classes_callback');
        add_action( 'wp_ajax_convert_hide_remove_settings', 'Advanced_Themer_Bricks\AT__Ajax::convert_hide_remove_settings_callback');
        add_action( 'wp_ajax_norpiv_convert_hide_remove_settings', 'Advanced_Themer_Bricks\AT__Ajax::convert_hide_remove_settings_callback');
        add_action( 'wp_ajax_reset_advanced_options', 'Advanced_Themer_Bricks\AT__Ajax::reset_advanced_options_callback');
        add_action( 'wp_ajax_norpiv_reset_advanced_options', 'Advanced_Themer_Bricks\AT__Ajax::reset_advanced_options_callback');
        add_action( 'wp_ajax_export_advanced_options', 'Advanced_Themer_Bricks\AT__Ajax::export_advanced_options_callback');
        add_action( 'wp_ajax_norpiv_export_advanced_options', 'Advanced_Themer_Bricks\AT__Ajax::export_advanced_options_callback');
        add_action( 'wp_ajax_import_advanced_options', 'Advanced_Themer_Bricks\AT__Ajax::import_advanced_options_callback');
        add_action( 'wp_ajax_norpiv_import_advanced_options', 'Advanced_Themer_Bricks\AT__Ajax::import_advanced_options_callback');
        add_action( 'wp_ajax_save_advanced_css_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_advanced_css_ajax_function' );
        add_action( 'wp_ajax_nopriv_save_advanced_css_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_advanced_css_ajax_function' );
        add_action( 'wp_ajax_save_grid_guide_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_grid_guide_ajax_function' );
        add_action( 'wp_ajax_nopriv_save_grid_guide_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_grid_guide_ajax_function' );
        add_action( 'wp_ajax_save_grid_utility_classes_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_grid_utility_classes_ajax_function' );
        add_action( 'wp_ajax_nopriv_save_grid_utility_classes_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_grid_utility_classes_ajax_function' );
        add_action( 'wp_ajax_save_right_shortcuts_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_right_shortcuts_ajax_function' );
        add_action( 'wp_ajax_nopriv_save_right_shortcuts_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_right_shortcuts_ajax_function' );
        add_action( 'wp_ajax_save_query_manager_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_query_manager_ajax_function' );
        add_action( 'wp_ajax_nopriv_save_query_manager_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_query_manager_ajax_function' );
        add_action( 'wp_ajax_save_prompt_manager_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_prompt_manager_ajax_function' );
        add_action( 'wp_ajax_nopriv_save_prompt_manager_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_prompt_manager_ajax_function' );
        add_action( 'wp_ajax_save_builder_settings_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_builder_settings_ajax_function' );
        add_action( 'wp_ajax_nopriv_save_builder_settings_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_builder_settings_ajax_function' );
        add_action( 'wp_ajax_save_full_access_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_full_access_ajax_function' );
        add_action( 'wp_ajax_nopriv_save_full_access_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_full_access_ajax_function' );
        add_action( 'wp_ajax_get_var_query_ajax_function', 'Advanced_Themer_Bricks\AT__Builder::get_var_query_ajax_function');
        add_action( 'wp_ajax_nopriv_get_var_query_ajax_function', 'Advanced_Themer_Bricks\AT__Builder::get_var_query_ajax_function');
        add_action( 'wp_ajax_save_custom_components_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_custom_components_ajax_function' );
        add_action( 'wp_ajax_nopriv_save_custom_components_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_custom_components_ajax_function' );
        add_action( 'wp_ajax_generated_html_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::generated_html_ajax_function' );
        add_action( 'wp_ajax__nopriv_generated_html_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::generated_html_ajax_function' );
        add_action( 'wp_ajax_generated_html_multiple_elements_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::generated_html_multiple_elements_ajax_function' );
        add_action( 'wp_ajax__nopriv_generated_html_multiple_elements_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::generated_html_multiple_elements_ajax_function' );
        add_action( 'wp_ajax_get_remote_templates_data_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::get_remote_templates_data_ajax_function' );
        add_action( 'wp_ajax__nopriv_get_remote_templates_data_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::get_remote_templates_data_ajax_function' );
        add_action( 'wp_ajax_get_templates_data_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::get_templates_data_ajax_function' );
        add_action( 'wp_ajax__nopriv_get_templates_data_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::get_templates_data_ajax_function' );
        add_action( 'wp_ajax_convert_template_data_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::convert_template_data_ajax_function' );
        add_action( 'wp_ajax__nopriv_convert_template_data_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::convert_template_data_ajax_function' );
        add_action( 'wp_ajax_save_template_data_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_template_data_ajax_function' );
        add_action( 'wp_ajax__nopriv_save_template_data_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::save_template_data_ajax_function' );
        add_action( 'wp_ajax_get_used_classes_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::get_used_classes_callback');
        add_action( 'wp_ajax_norpiv_get_used_classes_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::get_used_classes_callback');
        add_action( 'wp_ajax_get_framework_values_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::get_framework_values');
        add_action( 'wp_ajax_norpiv_get_framework_values_ajax_function', 'Advanced_Themer_Bricks\AT__Ajax::get_framework_values');

        /*--------------------------------------
        Strict Editor View
        --------------------------------------*/
        add_filter( 'init', 'Advanced_Themer_Bricks\AT__Builder::set_full_access_to_all_elements', 997);
        add_filter( 'init', 'Advanced_Themer_Bricks\AT__Builder::set_full_access_settings', 998);
        // add_filter( 'admin_menu', 'Advanced_Themer_Bricks\AT__Admin::remove_templates_from_menu', 999 );
        // add_action( 'wp_before_admin_bar_render', 'Advanced_Themer_Bricks\AT__Admin::remove_templates_from_toolbar', 999); 
        add_filter( 'bricks/acf/filter_field_groups' , function($groups){
            foreach($groups as $key => $value){
                if($value['key'] === 'group_638315a281bf1' || $value['key'] === 'group_6389e81fa2085'){
                    unset($groups[$key]);
                }
            }
            return $groups;
        });

        /*--------------------------------------
        Allow JSON upload
        --------------------------------------*/
        add_filter( 'upload_mimes', 'Advanced_Themer_Bricks\AT__Helpers::add_upload_mimes' );

        /*--------------------------------------
        Page Transition
        --------------------------------------*/
        add_filter( 'init', 'Advanced_Themer_Bricks\AT__Frontend::generate_css_for_global_page_transitions', 10, 3);
        add_filter( 'bricks/active_templates', 'Advanced_Themer_Bricks\AT__Frontend::add_page_transition_css', 10, 3);

        /*--------------------------------------
        Scrolling Animation
        --------------------------------------*/
        add_filter( 'init', 'Advanced_Themer_Bricks\AT__Frontend::enqueue_scroll_animation_polyfill', 10);

        /*--------------------------------------
        Custom default Values in builder
        --------------------------------------*/
        add_filter( 'init', 'Advanced_Themer_Bricks\AT__Builder::set_custom_default_values_in_builder', 998);
        add_filter( 'init', 'Advanced_Themer_Bricks\AT__Builder::disable_style_controls', 999);
        //add_filter( 'init', 'Advanced_Themer_Bricks\AT__Builder::disable_bricks_elements');
        //add_filter( 'init', 'Advanced_Themer_Bricks\AT__Builder::remove_elements_from_frontend');


        /*--------------------------------------
        ATF
        --------------------------------------*/
        add_filter( 'bricks/element/render_attributes', 'Advanced_Themer_Bricks\AT__Framework::manage_stagger_class', 10, 3);
    }
    
}