<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcClassConverterOverlay';
$prefix_id = 'brxcClassConverter';
$prefix_class = 'brxc-class-converter';
// Heading
$modal_heading_title = 'Class Converter';
$default_position = 'sidebar right';
$position = apply_filters( 'at/class_converter/modal_position', $default_position );

// Define a whitelist of valid positions
$valid_positions = array( 'sidebar left', '');

// Check if the value is in the allowed list, otherwise set to a default
if ( ! in_array( $position, $valid_positions, true ) ) {
    $position = $default_position;
}

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
        <div class="brxc-overlay__close-btn" onClick="ADMINBRXC.closeModal(event, event.target, '#<?php echo esc_attr($overlay_id);?>')">
            <i class="bricks-svg ti-close"></i>
        </div>
        <div class="brxc-overlay__inner-wrapper">
            <div class="brxc-overlay__header">
                <h3 class="brxc-overlay__header-title"><?php echo esc_attr($modal_heading_title);?></h3>
                <div class="brxc-overlay__resize-icons">
                    <i class="fa-solid fa-window-maximize <?php echo $position === "" ? "active" : ""?>" onclick="ADMINBRXC.maximizeModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    <i class="ti-layout-sidebar-left <?php echo $position === "sidebar left" ? "active" : ""?>" onclick="ADMINBRXC.leftSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    <i class="ti-layout-sidebar-right <?php echo $position === "sidebar right" ? "active" : ""?>" onclick="ADMINBRXC.rightSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                </div>
            </div>
            <div class="brxc-overlay__error-message-wrapper"></div>
            <div class="brxc-overlay__container">
                <div class="brxc-overlay__pannels-wrapper">
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1" style="padding-top: 0;">
                        <div id="brxcClassConvertCanvas"></div>
                        <div class="brxc-overlay__action-btn-wrapper right m-top-16"> 
                            <div class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.classConverter();"><span>Create Classes</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }

$output = ob_get_clean();
$output = preg_replace('/>\s+</s', '><', $output);
$brxc_modals['class_converter'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];