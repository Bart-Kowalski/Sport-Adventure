<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcQueryManagerOverlay';
$prefix_id = 'brxcQueryManager';
$prefix_class = 'brxc-query-manager';
// Heading
$modal_heading_title = 'Global Query Manager';

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
                        <div id="queryManagerUI">
                            <div id="queryManagerUI__left">
                                <div class="brxc-overlay__search-box">
                                    <input type="text" class="class-filter" name="class-search" placeholder="Filter by name" data-type="title" oninput="ADMINBRXC.queryManagerStates.search = this.value;ADMINBRXC.queryManagerList();">
                                    <div class="iso-search-icon">
                                        <i class="bricks-svg ti-search"></i>
                                    </div>
                                    <div class="iso-reset light" data-balloon="Reset Filter" data-balloon-pos="bottom-right" onclick="ADMINBRXC.resetQueryFilter(this);">
                                        <i class="bricks-svg ti-close"></i>
                                    </div>
                                </div>
                                <div class="queryManagerUI__cat">
                                    <div id="brxcQueryCatListCanvas"></div>
                                </div>
                                <div class="queryManagerUI__querys">
                                    <ul id="queryManagerUI__list"></ul>
                                    <div class="brxc-class-manager__footer"><input type="text" id="addNewQueryLoop" placeholder="Add a new Query Loop" onkeyup="ADMINBRXC.addNewQueryLoop(event);"></div>
                                </div>
                            </div>
                            <div id="queryManagerUI__panel"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="brxc-overlay__footer">
                <div class="brxc-overlay__footer-wrapper">
                    <a class="brxc-overlay__action-btn secondary" style="margin-left: auto;" onClick="ADMINBRXC.saveQueryManager()"><span>Save & Continue</span></a>
                    <a class="brxc-overlay__action-btn primary" onClick="ADMINBRXC.saveQueryManager();ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');"><span>Save & Close</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }

$output = ob_get_clean();
$output = preg_replace('/>\s+</s', '><', $output);
$brxc_modals['query_manager'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];