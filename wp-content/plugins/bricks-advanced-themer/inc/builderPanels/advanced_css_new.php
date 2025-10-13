<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcCSSOverlay';
$prefix = 'global-code-openai';
// Heading
$modal_heading_title = 'Advanced CSS';
$default_position = '';
$position = apply_filters( 'at/advanced_css/modal_position', $default_position );

// Define a whitelist of valid positions
$valid_positions = array( 'sidebar left', 'sidebar right');

// Check if the value is in the allowed list, otherwise set to a default
if ( ! in_array( $position, $valid_positions, true ) ) {
    $position = $default_position;
}

//for loops
$i = 0;

// PAGE
$page_id = get_the_ID();
$page_settings_arr = get_post_meta($page_id, '_bricks_page_settings', true);
$page_custom_css = isset($page_settings_arr) && isset($page_settings_arr['customCss']) ? stripslashes($page_settings_arr['customCss']) : '';
$page_custom_sass = isset($page_settings_arr) && isset($page_settings_arr['customSass']) ? stripslashes($page_settings_arr['customSass']) : '';

// GLOBAL
$global_settings_arr = get_option("bricks_global_settings");
$global_custom_css = isset($global_settings_arr) && isset($global_settings_arr['customCss']) ? stripslashes($global_settings_arr['customCss']) : '';
$at_global_settings_arr = get_option('bricks_advanced_themer_builder_settings');
$global_custom_sass = "";
$global_last_modified = "";
$global_last_modified_by = "";

// MIXINS & PARTIALS
$mixins_content = "";
$partials_content = "";
$mixins_last_modified = "";
$mixins_last_modified_by = "";
$partials_last_modified = "";
$partials_last_modified_by = "";
if(AT__Helpers::is_array($at_global_settings_arr, 'advanced_css')){
    foreach($at_global_settings_arr['advanced_css'] as $item){
        if($item['id'] == "at-global-css" && isset($item['contentSass']) && $item['contentSass'] !== "" ){
            $global_custom_sass = stripslashes($item['contentSass']);
            $global_last_modified = isset($item['lastModified']) ? (int) $item['lastModified'] : "";
            $global_last_modified_by = isset($item['lastModifiedBy']) ? $item['lastModifiedBy'] : "";
        }

        if($item['id'] == "at-mixins" && isset($item['contentSass']) && $item['contentSass'] !== "" ){
            $mixins_content = stripslashes($item['contentSass']);
            $mixins_last_modified = isset($item['lastModified']) ? (int) $item['lastModified'] : "";
            $mixins_last_modified_by = isset($item['lastModifiedBy']) ? $item['lastModifiedBy'] : "";
        }

        if($item['id'] == "at-partials" && isset($item['contentSass']) && $item['contentSass'] !== "" ){
            $partials_content = stripslashes($item['contentSass']);
            $partials_last_modified = isset($item['lastModified']) ? (int) $item['lastModified'] : "";
            $partials_last_modified_by = isset($item['lastModifiedBy']) ? $item['lastModifiedBy'] : "";
        }
    }
}

// DATA
$data = [];
if($brxc_acf_fields['advanced_css_enable_sass']){
    $data = [
        "type" => "scss",
        "query" => "'css', 'scss', 'scssp'",
    ];
} else {
    $data = [
        "type" => "css",
        "query" => "'css', 'scss', 'scssp'",
    ];
}
$type_label = [
    "css" => "css",
    "scss" => "scss",
    "scssp" => "partial",
];

// Array
$elements = array(
    array(
        "id" => "at-page-css",
        "file" => false,
        "label" => esc_html("page." . $data['type']),
        "type" => esc_html($data['type']),
        "typeLabel" => esc_html($type_label[$data['type']]),
        "category" => "default",
        "scope" => "page",
        "message" => "The following CSS codes will apply on the current page only.",
        "readOnly" => false,
        "contentCss" => AT__Helpers::sanitize_css_string($page_custom_css),
        "contentSass" => AT__Helpers::sanitize_css_string($page_custom_sass),
        "order" => 0,
        "default" => true,
        "saveMethod" => "builder",
        "toggleActive" => false,
    ),
    array(
        "id" => "at-global-css",
        "file" => false,
        "label" => esc_html("global." . $data['type']),
        "type" => esc_html($data['type']),
        "typeLabel" => esc_html($type_label[$data['type']]),
        "category" => "default",
        "scope" => "global",
        "message" => "The following CSS codes apply on all the pages of your website.",
        "readOnly" => false,
        "contentCss" => AT__Helpers::sanitize_css_string($global_custom_css),
        "contentSass" => AT__Helpers::sanitize_css_string($global_custom_sass),
        "order" => 1,
        "default" => true,
        "saveMethod" => "ajax",
        "toggleActive" => false,
        "lastModified" => $global_last_modified,
        "lastModifiedBy" => esc_html($global_last_modified_by),
    ),
);
if($brxc_acf_fields['advanced_css_enable_sass']){
    $elements[] = array(
        "id" => "at-mixins",
        "file" => false,
        "label" => "_mixins.scss",
        "type" => "scssp",
        "typeLabel" => "partial",
        "category" => "default",
        "scope" => "global",
        "message" => "Declare your mixins here - they can be used in each file without using @import.",
        "readOnly" => false,
        "contentSass" => AT__Helpers::sanitize_css_string($mixins_content),
        "order" => 2,
        "default" => true,
        "status" => 1,
        "saveMethod" => "ajax",
        "toggleActive" => false,
        "lastModified" => $mixins_last_modified,
        "lastModifiedBy" => esc_html($mixins_last_modified_by),
    );
    $elements[] = array(
        "id" => "at-partials",
        "file" => false,
        "label" => "_partials.scss",
        "type" => "scssp",
        "typeLabel" => "partial",
        "category" => "default",
        "scope" => "global",
        "message" => "The following CSS codes won't be printed as it, but the values can be used inside all the other stylesheets. Values applied to Elements & Global classes will be updated once you save your Advanced CSS settings.",
        "readOnly" => false,
        "contentSass" => AT__Helpers::sanitize_css_string($partials_content),
        "order" => 3,
        "default" => true,
        "status" => 1,
        "saveMethod" => "ajax",
        "toggleActive" => false,
        "lastModified" => $partials_last_modified,
        "lastModifiedBy" => esc_html($partials_last_modified_by),
    );
}


// Child Theme
if ( get_template_directory() !== get_stylesheet_directory() ){
    $file = get_stylesheet_directory() . '/style.css';
    $elements[] = [
        "id" => "at-child-css",
        "file" => $file,
        "label" => "child.css",
        "type" => "css",
        "typeLabel" => "css",
        "category" => "default",
        "scope" => "global",
        "message" => "The following CSS code is applied to your child theme. Don't modify the initial commented code, otherwise you'll break your child theme.",
        "readOnly" => false,
        "contentCss" => AT__Helpers::sanitize_css_string(stripslashes(AT__Helpers::read_file_contents($file))),
        "order" => 4,
        "default" => true,
        "saveMethod" => "ajax",
        "toggleActive" => false,
    ];
}

// Imported CSS
if ( have_rows('field_63b59j871b209', 'bricks-advanced-themer' ) ):
    $i = 5;
    while( have_rows('field_63b59j871b209', 'bricks-advanced-themer' ) ) : the_row();
        if (have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) :
            while ( have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) :
                the_row();
                $label = get_sub_field('field_63b4bd5c16ac3', 'bricks-advanced-themer' );
                $filename = str_replace(' ', '-', strtolower($label));
                $file = get_sub_field('field_63b4bdf216ac7', 'bricks-advanced-themer' );
                $file_content = stripslashes(file_get_contents($file));
                $elements[] = [
                    "id" => esc_html("imported_css_" . $filename),
                    "file" => esc_html($file),
                    "label" => esc_html($filename . ".css"),
                    "type" => "css",
                    "typeLabel" => "css",
                    "category" => "default",
                    "scope" => "global",
                    "message" => "The following stylesheet has been uploaded inside the theme settings > css classes > class importer. This file is read-only.",
                    "readOnly" => true,
                    "contentCss" => AT__Helpers::sanitize_css_string($file_content),
                    "order" => $i,
                    "default" => true,
                    "saveMethod" => false,
                    "toggleActive" => false,
                ];
            endwhile;
            $i++;
        endif;
    endwhile;
endif;

// UNDOCUMENTED: deactivate WCPB integration
$activate_wpcb = apply_filters( 'at/advanced_css/enable_wpcb_integration', true);
if ( class_exists('\Wpcb2\Api\Api') && $activate_wpcb) {
    global $wpdb;

    // Prepare the SQL query to retrieve all rows from the table
    $query = "SELECT * FROM {$wpdb->prefix}wpcb_snippets WHERE codeType IN ({$data['query']})";

    // Execute the query and get the results
    $results = $wpdb->get_results($query);

    // Prepare the SQL query to retrieve all rows from the table
    $query = "SELECT * FROM {$wpdb->prefix}wpcb_folders";

    // Execute the query and get the results
    $results_folders = $wpdb->get_results($query);

    // Check if there are results
    if ( AT__Helpers::is_array( $results ) ) {
        foreach ( $results as $row ) {
            $status =  $row->enabled === "1" || $row->codeType === "scssp" ? 1 : 0;
            $category = "wpcodebox";
            $active_toggle = $row->codeType === 'scssp' ? false : true;
            if($row->folderId !== 0){
                if(AT__Helpers::is_array($results_folders)){
                    foreach($results_folders as $folder){
                        if($folder->id === $row->folderId ){
                            $category = "wpcodebox - " . $folder->name;
                        }
                    }
                }
            }

            $readOnly = !$brxc_acf_fields['advanced_css_enable_sass'] && $row->codeType !== "css" ? true : false;
            $elements[] = [
                "id" => esc_html($row->id),
                "file" => false,
                "label" => esc_html($row->title),
                "type" => esc_html($row->codeType),
                "typeLabel" => esc_html($type_label[$row->codeType]),
                "category" => esc_html($category),
                "scope" => "global",
                "message" => esc_html($row->description),
                "readOnly" => $readOnly,
                "contentCss" => AT__Helpers::sanitize_css_string(stripslashes($row->code)),
                "contentSass" => AT__Helpers::sanitize_css_string(stripslashes($row->original_code)),
                "order" => esc_html($row->snippet_order),
                "status" => esc_html(intval($status)),
                "priority" => esc_html($row->priority),
                "saveMethod" => "wpcb",
                "toggleActive" => esc_html($active_toggle),
                "enqueueFrontend" => 1,
            ];
        }
    }
}

//ACSS RECIPES
if (class_exists('\Automatic_CSS\API') && method_exists('\Automatic_CSS\API', 'get_all_recipes')) {
    $recipes = \Automatic_CSS\API::get_all_recipes();
    if(AT__Helpers::is_array($recipes)){
        foreach ($recipes as $key => $value){
            $elements[] = [
                "id" => esc_html('acss_recipe_' . $key),
                "file" => false,
                "label" => esc_html($key),
                "type" => "recipe",
                "typeLabel" => "recipe",
                "category" => "acss recipes",
                "message" => "The ACSS recipe can't be modified and are set as read-only",
                "readOnly" => true,
                "contentCss" => AT__Helpers::sanitize_css_string(stripslashes($value)),
                "status" => 1,
                "saveMethod" => false,
                "toggleActive" => false,
                "default" => true,
            ];
        }
    }
}

// PHP Filter
$php_recipes = apply_filters('at/advanced_css/recipes', []);
if(AT__Helpers::is_array($php_recipes)){
    foreach ($php_recipes as $key => $value){
        $elements[] = [
            "id" => esc_html('php_recipe_' . $key),
            "file" => false,
            "label" => esc_html($key),
            "type" => "recipe",
            "typeLabel" => "recipe",
            "category" => "recipes from php filter",
            "message" => "The PHP recipes can't be modified from here and are set as read-only",
            "readOnly" => true,
            "contentCss" => AT__Helpers::sanitize_css_string(stripslashes($value)),
            "status" => 1,
            "saveMethod" => false,
            "toggleActive" => false,
            "default" => true,
        ];
    }
}


// Custom 
if(AT__Helpers::is_array($at_global_settings_arr, 'advanced_css')){
    foreach($at_global_settings_arr['advanced_css'] as $item){
        // Recipes
        if($item['typeLabel'] == "recipe"){
            $contentCss = isset($item['contentCss']) && $item['contentCss'] !== "" ? stripslashes($item['contentCss']) : '';
            $elements[] = [
                "id" => esc_html($item['id']),
                "file" => false,
                "label" => esc_html($item['label']),
                "type" => "css",
                "typeLabel" => "recipe",
                "category" => esc_html($item['category'] ?? "custom"),
                "message" => esc_html($item['message']),
                "readOnly" => false,
                "contentCss" => AT__Helpers::sanitize_css_string(stripslashes($contentCss)),
                "status" => $item['status'],
                "saveMethod" => "ajax",
                "toggleActive" => true,
                "at_framework" => esc_html($item['at_framework'] ?? false),
                "at_version" => esc_html($item['at_version'] ?? false),
            ];
        // Custom StyleSheets
        } elseif ($item['category'] == "custom" || $item['category'] == "at framework") {
            $contentCss = isset($item['contentCss']) && $item['contentCss'] !== "" ? stripslashes($item['contentCss']) : '';
            $contentSass = isset($item['contentSass']) && $item['contentSass'] !== "" ? stripslashes($item['contentSass']) : '';
            $readOnly = !$brxc_acf_fields['advanced_css_enable_sass'] && $item['type'] !== "css" ? true : false;
            $last_modified = isset($item['lastModified']) ? (int) $item['lastModified'] : "";
            $last_modified_by = isset($item['lastModifiedBy']) ? $item['lastModifiedBy'] : "";
            $obj = [
                "id" => esc_html($item['id']),
                "file" => false,
                "label" => esc_html($item['label']),
                "type" => esc_html($item['type']),
                "typeLabel" => esc_html($type_label[$item['type']]),
                "category" => $item['category'],
                "scope" => "global",
                "message" => esc_html($item['message']),
                "readOnly" => esc_html($readOnly),
                "contentCss" => AT__Helpers::sanitize_css_string($contentCss),
                "contentSass" => AT__Helpers::sanitize_css_string($contentSass),
                "order" => $i,
                "status" => esc_html($item['status']),
                "priority" => esc_html($item['priority']),
                "saveMethod" => "ajax",
                "toggleActive" => true,
                "enqueueFrontend" => esc_html($item['enqueueFrontend']),
                "enqueueBuilder" => esc_html($item['enqueueBuilder']),
                "enqueueGutenberg" => esc_html($item['enqueueGutenberg']),
                "lastModified" => $last_modified,
                "lastModifiedBy" => esc_html($last_modified_by),
            ];
            if(isset($item['at_framework'])) {
                $obj['at_framework'] = true;
            }
            if(isset($item['at_version'])) {
                $obj['at_version'] = $item['at_version'];
            }
            $elements[] = $obj;
        }

        
    }
}

// Pass the $elements array to the JavaScript file
wp_localize_script( 'brxc-builder', 'brxcAdvancedCSSDefault', $elements );

ob_start();

if (!AT__Helpers::is_builder_tweaks_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the button below and make sure that the <strong class='accent'>Builder Tweaks</strong> settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';
} else {
?>
    <div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper <?php echo esc_attr($position)?>" style="opacity:0" data-input-target="" onmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
        <div class="brxc-overlay__inner brxc-large">
            <div class="brxc-overlay__close-btn" onClick="ADMINBRXC.destroySassInstances();ADMINBRXC.disableSelectorPicker();ADMINBRXC.closeModal(event, event.target, '#<?php echo esc_attr($overlay_id);?>')">
                <i class="bricks-svg ti-close"></i>
            </div>
            <div class="brxc-overlay__inner-wrapper">
                <div class="brxc-overlay__header">
                    <h3 class="brxc-overlay__header-title"><?php echo esc_attr($modal_heading_title);?></h3>
                    <div class="brxc-overlay__resize-icons">
                    <i class="fa-solid fa-window-maximize <?php echo $position === "" ? "active" : ""?>" onclick="ADMINBRXC.maximizeModal(this, '#<?php echo esc_attr($overlay_id);?>');ADMINBRXC.advancedCSSInit()"></i>
                    <i class="ti-layout-sidebar-left <?php echo $position === "sidebar left" ? "active" : ""?>" onclick="ADMINBRXC.leftSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');ADMINBRXC.advancedCSSInit()"></i>
                    <i class="ti-layout-sidebar-right <?php echo $position === "sidebar left" ? "" : ""?>" onclick="ADMINBRXC.rightSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');ADMINBRXC.advancedCSSInit()"></i>
                    </div>
                </div>
                <div class="brxc-overlay__error-message-wrapper"></div>
                <div class="brxc-overlay__container no-radius">
                    <div class="brxc-overlay__pannels-wrapper">
                        <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                            <div id="advancedCSSUI">
                                <div id="advancedCSSUI__loader">
                                    <div class="bricks-logo-animated"><div class="cube top-left"></div><div class="cube top-right"></div><div class="cube bottom-left"></div><div class="cube bottom-right"></div></div>
                                </div>
                                <div id="advancedCSSUI__header"></div>
                                <div id="advancedCSSUI__body">
                                    <div id="advancedCSSUI__left">
                                        <div class="advancedCSSUI__cat">
                                            <div class="brxc-overlay__search-box">
                                                <input type="text" class="class-filter" name="class-search" placeholder="Search..." data-type="title" value="" oninput="ADMINBRXC.advancedCSSStates.search = this.value;ADMINBRXC.advancedCSSCat();">
                                                <div class="iso-search-icon">
                                                    <i class="bricks-svg ti-search"></i>
                                                </div>
                                                <div class="iso-reset" data-balloon="Reset Filter" data-balloon-pos="bottom-right" onclick="ADMINBRXC.advancedCSSStates.search = '';ADMINBRXC.advancedCSSCat();this.previousElementSibling.previousElementSibling.value = '';">
                                                    <i class="bricks-svg ti-close"></i>
                                                </div>
                                            </div>
                                            <div id="brxcAdvancedCSSCatListCanvas" class="brxc-manager__left-menu"></div>
                                        </div>
                                    </div>
                                    <div id="advancedCSSUI__panel"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="brxc-overlay__footer">
                    <div class="brxc-overlay__footer-wrapper">
                        <a class="brxc-overlay__action-btn secondary" style="margin-right: auto;" onClick="ADMINBRXC.regenerateAdvancedCSS(this)"><span>Recompile & Regenerate all CSS files</span></a>
                        <a class="brxc-overlay__action-btn secondary" onClick="ADMINBRXC.saveAdvancedCSS(this)"><span>Save & Continue</span></a>
                        <a class="brxc-overlay__action-btn primary" onClick="ADMINBRXC.disableSelectorPicker();ADMINBRXC.saveAdvancedCSS(this);ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');"><span>Save & Close</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }

$output = ob_get_clean();
$output = preg_replace('/>\s+</s', '><', $output);
$brxc_modals['advanced_css'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];
