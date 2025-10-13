<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcRemoteTemplatesOverlay';
$prefix_id = 'brxcRemoteTemplates';
$prefix_class = 'brxc-remote-templates';
// Heading
$modal_heading_title = 'Quick Remote Templates';

ob_start();

if (!AT__Helpers::is_builder_tweaks_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the button below and make sure that the <strong class='accent'>Builder Tweaks</strong> settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';
} else {
?>
<div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper sidebar left" style="opacity:0;" data-input-target="">
    <div class="brxc-overlay__inner brxc-large" style="min-width: var(--at-remote-template-margin);">
        <div class="brxc-overlay__close-btn" onClick="ADMINBRXC.closeTemplateModal();">
            <i class="bricks-svg ti-close"></i>
        </div>
        <div class="brxc-overlay__inner-wrapper">
            <div class="brxc-overlay__header">
                <h3 class="brxc-overlay__header-title"><?php echo esc_attr($modal_heading_title);?></h3>
            </div>
            <div class="brxc-overlay__error-message-wrapper"></div>
            <div class="brxc-overlay__container no-radius">
                <div class="brxc-overlay__pannels-wrapper">
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1" style="padding: 16px;"> 
                        <div id="brxcRemoteTemplatesUI">
                            <div id="brxcRemoteTemplatesUI__header"></div>
                            <div id="brxcRemoteTemplatesUI__main">
                                <div id="brxcRemoteTemplatesUI__loader">
                                    <div class="bricks-logo-animated"><div class="cube top-left"></div><div class="cube top-right"></div><div class="cube bottom-left"></div><div class="cube bottom-right"></div></div>
                                </div>
                                <div id="brxcRemoteTemplatesUI__left">
                                    <div class="brxcRemoteTemplatesUI__cat brxc-manager__left-menu">
                                        <div id="brxcRemoteTemplatesUI__cat-canvas"></div>
                                    </div>
                                </div>
                                <div id="brxcRemoteTemplatesUI__panel">
                                    <div class="brxc-overlay__search-box">
                                        <input type="text" class="class-filter" name="class-search" placeholder="Filter by name" data-type="title" oninput="ADMINBRXC.remoteTemplatesStates.search = this.value;ADMINBRXC.remoteTemplatePanel();">
                                        <div class="iso-search-icon">
                                            <i class="bricks-svg ti-search"></i>
                                        </div>
                                        <div class="iso-reset light" data-balloon="Reset Filter" data-balloon-pos="bottom-right" onclick="ADMINBRXC.remoteTemplatesStates.search = '';ADMINBRXC.remoteTemplatePanel(this);">
                                            <i class="bricks-svg ti-close"></i>
                                        </div>
                                    </div>
                                    <div id="brxcRemoteTemplatesUI__panel-canvas"></div>
                                </div>
                            </div>
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
$brxc_modals['remote_templates'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];