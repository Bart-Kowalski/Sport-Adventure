<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcFindReplaceModal';
$prefix = 'brxc-findreplace';
// Heading
$modal_heading_title = 'Find & Replace Styles';

ob_start();

if (!AT__Helpers::is_builder_tweaks_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the button below and make sure that the <strong class='accent'>Builder Tweaks</strong> settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';
} else {
?>
<div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" onmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
    <div class="brxc-overlay__inner brxc-medium" style="max-height: 950px;">
        <div class="brxc-overlay__close-btn" onClick="ADMINBRXC.closeModal(event, event.target, '#<?php echo esc_attr($overlay_id);?>')">
            <i class="bricks-svg ti-close"></i>
        </div>
        <div class="brxc-overlay__inner-wrapper">
            <div class="brxc-overlay__header">
                <h3 class="brxc-overlay__header-title"><?php echo esc_attr($modal_heading_title);?></h3>
                <div class="brxc-overlay__resize-icons">
                    <i class="fa-solid fa-window-maximize" onclick="ADMINBRXC.maximizeModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    <i class="ti-layout-sidebar-left" onclick="ADMINBRXC.leftSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    <i class="ti-layout-sidebar-right" onclick="ADMINBRXC.rightSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                </div>
            </div>
            <div class="brxc-overlay__container">
                <div class="brxc-overlay__pannels-wrapper">
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                        <label class="has-tooltip">
                            <span>I want to replace this value:</span>
                            <div data-balloon="Write here the value that you want to replace." data-balloon-pos="bottom" data-balloon-length="medium"><i class="fas fa-circle-question"></i></div>
                        </label>
                        <div class="brxc__text">
                            <input type="text" name="<?php echo esc_attr($prefix);?>Search" id="<?php echo esc_attr($prefix);?>Search" placeholder="Type here the search value">
                        </div>
                        <label class="has-tooltip">
                            <span>With this value:</span>
                            <div data-balloon="Write here the value that will be replacing the search value" data-balloon-pos="bottom" data-balloon-length="medium"><i class="fas fa-circle-question"></i></div>
                        </label>
                        <div class="brxc__text">
                            <input type="text" name="<?php echo esc_attr($prefix);?>Replace" id="<?php echo esc_attr($prefix);?>Replace" placeholder="Type here the replace value">
                        </div>
                        <label class="has-tooltip">
                            <span>Inside the following CSS property</span>
                            <div data-balloon="Select the specific CSS property you want to target. Select 'All Properties' to target any property." data-balloon-pos="bottom" data-balloon-length="medium"><i class="fas fa-circle-question"></i></div>
                        </label>
                        <div class="brxc-select" id="<?php echo esc_attr($prefix);?>propertyOptions" style="z-index:6;">
                            <div class="brxc-select-new rounded hidden">
                                <div class="brxc-select-new__wrapper"></div>
                            </div>
                        </div>
                        <label class="has-tooltip">
                            <span>To the following elements category</span>
                            <div data-balloon="Select the element's category you want to target. Select 'All Categories' to target any category." data-balloon-pos="bottom" data-balloon-length="medium"><i class="fas fa-circle-question"></i></div>
                        </label>
                        <div class="brxc-select" id="<?php echo esc_attr($prefix);?>categoryOptions"  style="z-index:5;">
                            <div class="brxc-select-new rounded hidden">
                                <div class="brxc-select-new__wrapper"></div>
                            </div>
                        </div>
                        <label class="has-tooltip">
                            <span>That are positioned:</span>
                            <div data-balloon="Target the elements based on where they are positioned inside the DOM." data-balloon-pos="bottom" data-balloon-length="medium"><i class="fas fa-circle-question"></i></div>
                        </label>
                        <p class="alert">The global Find & Replace function is targeting the whole page by default. Open Find & Replace inside an element to target specific positions inside the DOM.</p>
                        <div class="brxc-overlay__panel-inline-btns-wrapper m-bottom-24">
                            <input type="radio" id="<?php echo esc_attr($prefix);?>-siblings" name="<?php echo esc_attr($prefix);?>Position" class="brxc-input__checkbox" value="siblings" checked>
                            <label for="<?php echo esc_attr($prefix);?>-siblings" class="brxc-overlay__panel-inline-btns">On the same DOM level (Siblings)</label>
                            <input type="radio" id="<?php echo esc_attr($prefix);?>-children" name="<?php echo esc_attr($prefix);?>Position" class="brxc-input__checkbox" value="children">
                            <label for="<?php echo esc_attr($prefix);?>-children" class="brxc-overlay__panel-inline-btns">As children of the selected element</label>
                            <input type="radio" id="<?php echo esc_attr($prefix);?>-div" name="<?php echo esc_attr($prefix);?>Position" class="brxc-input__checkbox" value="div">
                            <label for="<?php echo esc_attr($prefix);?>-div" class="brxc-overlay__panel-inline-btns">Inside the same parent's DIV</label>
                            <input type="radio" id="<?php echo esc_attr($prefix);?>-block" name="<?php echo esc_attr($prefix);?>Position" class="brxc-input__checkbox" value="block">
                            <label for="<?php echo esc_attr($prefix);?>-block" class="brxc-overlay__panel-inline-btns">Inside the same parent's Block</label>
                            <input type="radio" id="<?php echo esc_attr($prefix);?>-container" name="<?php echo esc_attr($prefix);?>Position" class="brxc-input__checkbox" value="container">
                            <label for="<?php echo esc_attr($prefix);?>-container" class="brxc-overlay__panel-inline-btns">Inside the same parent's Container</label>
                            <input type="radio" id="<?php echo esc_attr($prefix);?>-section" name="<?php echo esc_attr($prefix);?>Position" class="brxc-input__checkbox" value="section">
                            <label for="<?php echo esc_attr($prefix);?>-section" class="brxc-overlay__panel-inline-btns">Inside the same parent's Section</label>
                            <input type="radio" id="<?php echo esc_attr($prefix);?>-page" name="<?php echo esc_attr($prefix);?>Position" class="brxc-input__checkbox" value="page">
                            <label for="<?php echo esc_attr($prefix);?>-page" class="brxc-overlay__panel-inline-btns">On the whole Page</label>
                        </div>
                        <label class="has-tooltip">
                            <span>Including the values inside the Global Classes attached to each element?</span>
                            <div data-balloon="Select NO if you want to replace ID styles only. Set to YES if you want to replace the values inside the Global Classes attached to each element as well." data-balloon-pos="bottom" data-balloon-length="large"><i class="fas fa-circle-question"></i></div>
                        </label>
                        <div class="brxc-overlay__panel-inline-btns-wrapper m-bottom-24">
                            <input type="radio" id="<?php echo esc_attr($prefix);?>-yes" name="<?php echo esc_attr($prefix);?>GlobalClasses" class="brxc-input__checkbox" value="yes">
                            <label for="<?php echo esc_attr($prefix);?>-yes" class="brxc-overlay__panel-inline-btns">Yes</label>
                            <input type="radio" id="<?php echo esc_attr($prefix);?>-no" name="<?php echo esc_attr($prefix);?>GlobalClasses" class="brxc-input__checkbox" value="no" checked>
                            <label for="<?php echo esc_attr($prefix);?>-no" class="brxc-overlay__panel-inline-btns">No</label>
                        </div>
                        <div id="<?php echo esc_attr($prefix);?>ExtendWrapper" class="brxc-overlay__action-btn-wrapper right m-top-16 action-wrapper">
                            <div id="<?php echo esc_attr($prefix);?>ChatMore" class="brxc-overlay__action-btn" onClick="ADMINBRXC.closeModal(event, event.target, '#<?php echo esc_attr($overlay_id);?>')">
                                <span>Cancel</span>
                            </div>
                            <div class="brxc-overlay__action-btn primary" onClick='ADMINBRXC.applyFindAndReplace();'>
                                <span>Find & Replace</span>
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
$brxc_modals['find_replace'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];