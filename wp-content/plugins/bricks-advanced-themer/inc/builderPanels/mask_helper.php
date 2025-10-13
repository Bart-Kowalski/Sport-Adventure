<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcMaskHelperOverlay';
$prefix = 'maskHelper';
$prefix_id = 'maskHelper';
// Heading
$modal_heading_title = 'Mask Helper';
//for loops
$i = 0;

ob_start();

if (!AT__Helpers::is_builder_tweaks_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the button below and make sure that the Extras settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';
} else {
    ?>
    <div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" onmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
        <div class="brxc-overlay__inner brxc-large">
            <div class="brxc-overlay__close-btn" onClick="ADMINBRXC.closeModal(event, event.target, '#<?php echo esc_attr($overlay_id);?>')">
                <i class="bricks-svg ti-close"></i>
            </div>
            <div class="brxc-overlay__inner-wrapper">
                <div class="brxc-overlay__header">
                    <h3 class="brxc-overlay__header-title"><?php echo esc_attr($modal_heading_title);?></h3>

                    <div class="brxc-overlay__resize-icons">
                        <i class="fa-solid fa-window-maximize" onclick="ADMINBRXC.maximizeModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    </div>
                </div>
                <div class="brxc-overlay__error-message-wrapper"></div>
                <div class="brxc-overlay__container no-radius">
                    <div class="brxc-overlay__pannels-wrapper">
                        <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                            <div id="brxcMaskHelperCanvas"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }

$output = ob_get_clean();
$output = preg_replace('/>\s+</s', '><', $output);
$brxc_modals['mask_helper'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];