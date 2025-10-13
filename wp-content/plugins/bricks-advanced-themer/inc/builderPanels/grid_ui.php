<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcGridUIOverlay';
$prefix_id = 'brxcGridUI';
$prefix_class = 'brxc-grid-ui';
// Heading
$modal_heading_title = 'Grid Builder';

ob_start();

if (!AT__Helpers::is_builder_tweaks_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the button below and make sure that the <strong class='accent'>Builder Tweaks</strong> settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
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
            </div>
            <div class="brxc-overlay__error-message-wrapper"></div>
            <div class="brxc-overlay__container no-radius">
                <div class="brxc-overlay__pannels-wrapper">
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1" style="padding: 32px;">
                        <style id="gridUI__grid-modifications"></style>
                        <style id="gridUI__grid-elements"></style>
                        <div class="gruiUI__main-wrapper">
                            <div class="gridUI__grid-maxi-container">
                                <div id="gridUI-header-wrapper">
                                    <div id="gridUI-notification"></div>
                                    <ul id="gridUI-bp-wrapper"></ul>
                                </div>
                                <div class="gridUI__main-container">
                                    <div class="gridUI__grid-header top"></div>
                                    <div class="gridUI__grid-header left"></div>
                                    <div class="gridUI__grid-header gridUI__add-col__wrapper">
                                        <div class="gridUI__grid-header remove-col" onClick="ADMINBRXC.gridBuilderRemoveColumn();ADMINBRXC.gridBuilderInitPreview();ADMINBRXC.gridBuilderInitParent();ADMINBRXC.gridBuilderInitChild();"><div data-balloon="Remove Column" data-balloon-pos="bottom-right"><i class="ti-minus"></i></div></div>
                                        <div class="gridUI__grid-header add-col" onClick="ADMINBRXC.gridBuilderAddColumn();ADMINBRXC.gridBuilderInitPreview();ADMINBRXC.gridBuilderInitParent();ADMINBRXC.gridBuilderInitChild();"><div data-balloon="Add Column" data-balloon-pos="bottom-right"><i class="ti-plus"></i></div></div>
                                    </div>
                                    <div class="gridUI__grid-header gridUI__add-row__wrapper">
                                        <div class="gridUI__grid-header remove-row" onClick="ADMINBRXC.gridBuilderRemoveRow();ADMINBRXC.gridBuilderInitPreview();ADMINBRXC.gridBuilderInitParent();ADMINBRXC.gridBuilderInitChild();"><div data-balloon="Remove Row" data-balloon-pos="right"><i class="ti-minus"></i></div></div>
                                        <div class="gridUI__grid-header add-row" onClick="ADMINBRXC.gridBuilderAddRow();ADMINBRXC.gridBuilderInitPreview();ADMINBRXC.gridBuilderInitParent();ADMINBRXC.gridBuilderInitChild();"><div data-balloon="Add Row" data-balloon-pos="right"><i class="ti-plus"></i></div></div>
                                    </div>
                                    <div class="gridUI__grid-width handle-right"></div>
                                    <div class="gridUI__grid-width handle-bottom"></div>
                                    <div class="gridUI__grid-wrapper">
                                        <div class="gridUI__grid-container"></div>
                                        <div class="gridUI__grid-container-guide"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="gridUI__input-container">
                                <div id="gridUI__parent-settings"></div>
                                <div id="gridUI__child-settings"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="brxc-overlay__footer">
                <div class="brxc-overlay__footer-wrapper">
                    <a class="brxc-overlay__action-btn danger" onclick="ADMINBRXC.gridBuilderRemoveSetting()"><span>Initialize Grid</span></a>
                    <a class="brxc-overlay__action-btn secondary" onClick="ADMINBRXC.gridBuilderBentoGrid()"><span>Generate Bento Grid</span></a>
                    <a class="brxc-overlay__action-btn secondary" style="margin-left: auto;" onClick="ADMINBRXC.gridBuilderApply()"><span>Apply Grid & Continue</span></a>
                    <a class="brxc-overlay__action-btn primary" onClick="ADMINBRXC.gridBuilderApply();ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');"><span>Apply Grid & Close</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }

$output = ob_get_clean();
$output = preg_replace('/>\s+</s', '><', $output);
$brxc_modals['grid_ui'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];