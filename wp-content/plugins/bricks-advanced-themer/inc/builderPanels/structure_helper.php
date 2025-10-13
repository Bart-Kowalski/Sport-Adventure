<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcStructureHelper';
$prefix_id = 'brxcStructureHelper';
$prefix_class = 'brxc-structure-helper';
// Heading
$modal_heading_title = 'Structure Helper';
$modal_heading_link = '/';

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
                <a href="<?php echo esc_attr($modal_heading_link);?>" target="_blank" class="brxc-overlay__header-link">
                    <i class="fa-solid fa-up-right-from-square"></i>
                </a>
            </div>
            <div class="brxc-overlay__error-message-wrapper"></div>
            <div class="brxc-overlay__container">
                <div class="brxc-overlay__panel-switcher-wrapper"></div>
                <div class="brxc-overlay__pannels-wrapper">
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                        <div class="brxc-overlay__pannel-col--left">
                            <ul>
                                <span class="title">General</span>
                                <li data-id="has-custom-id" onclick="ADMINBRXC.shCheckProp(event, '_cssId');">Elements with a Custom ID<div data-balloon="Show Elements with a custom CSS ID. The custom ID is set inside the style tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-custom-class" onclick="ADMINBRXC.shCheckProp(event, '_cssClasses');">Elements with at least one Custom Class<div data-balloon="Show Elements with a Custom Class. The custom class is set in the style tab, as opposed to the global classes which are on top of the content/style switch." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-data-attributes" onclick="ADMINBRXC.shCheckPropArray(event,'_attributes', 0);">Elements with data-attributes<div data-balloon="Show Elements with at least a data-attribute set inside the style tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-link" onclick="ADMINBRXC.shCheckProp(event, 'link');">Elements wrapped in a link<div data-balloon="Show Elements that are wrapped inside anchor tag (link). The <a> tag is set inside the content tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-custom-tag" onclick="ADMINBRXC.shCheckProp(event, 'tag');">Elements with a custom HTML tag<div data-balloon="Show Elements with a Custom HTML tag. The HTML tag is set inside the content tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                            </ul>
                            <ul>
                                <span class="title">ID Styles</span>
                                <li data-id="has-id-styles" onclick="ADMINBRXC.shHasIDStyles(event);">Elements with styles on the ID level<div data-balloon="Show Elements that contain any style on the ID level - as opposed to the styes set on global classes. It's a good practice to keep the ID styles as low as possible." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-custom-css" onclick="ADMINBRXC.shCheckProp(event, '_cssCustom');">Elements with custom CSS styles<div data-balloon="Show Elements that contain ID styles set inside the Custom CSS control" data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-pseudo-styles" onclick="ADMINBRXC.shHasPseudoStyles(event);">Elements with pseudo-class/state styles<div data-balloon="Show Elements that contain any ID style set on a pseudo-class (:after, :before, etc..) or on state (:hover, :focus, etc...)." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-breakpoint-styles" onclick="ADMINBRXC.shHasBreakpointStyles(event);">Elements with styles on Breakpoints<div data-balloon="Show Elements that contain any ID style set on a mobile breakpoints (mobile, tablet, etc...). " data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                            </ul>
                            <ul>
                                <span class="title">Global Classes</span>
                                <li data-id="has-global-class" onclick="ADMINBRXC.shCheckPropArray(event,'_cssGlobalClasses', 0);">Elements with at least one global class<div data-balloon="Show Elements that contain at least one global class. The global classes are on top of the content/style switch of the element." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-more-than-one-global-class" onclick="ADMINBRXC.shCheckPropArray(event,'_cssGlobalClasses', 1);">Elements with more than one global class<div data-balloon="Show Elements that contain more than one global class. The global classes are on top of the content/style switch of the element." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-global-class-with-custom-css" onclick="ADMINBRXC.shCheckCustomCSSinGlobalClasses(event);">Elements with at least one global class that contains Custom CSS<div data-balloon="Show Elements that contain at least one global class with custom css applied to it. The global classes are on top of the content/style switch of the element." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                            </ul>
                            <ul>
                                <span class="title">Interactions</span>
                                <li data-id="has-interaction" onclick="ADMINBRXC.shCheckPropArray(event,'_interactions', 0);">Elements with at least one interaction<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-click-interaction" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"trigger\":\"click\"")'>Elements with a Click interaction<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the trigger set on 'Click'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-hover-interaction" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"trigger\":\"mouseover\"")'>Elements with a Hover interaction<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the trigger set on 'Hover'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-focus-interaction" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"trigger\":\"focus\"")'>Elements with a Focus interaction<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the trigger set on 'Focus'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-blur-interaction" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"trigger\":\"blur\"")'>Elements with a Blur interaction<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the trigger set on 'Blur'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-mouseenter-interaction" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"trigger\":\"mouseenter\"")'>Elements with a Mouse Enter interaction<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the trigger set on 'Mouse Enter'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-mouseleave-interaction" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"trigger\":\"mouseleave\"")'>Elements with a Mouse Leave interaction<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the trigger set on 'Mouse Leave'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-enterview-interaction" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"trigger\":\"enterView\"")'>Elements with a Enter Viewport interaction<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the trigger set on 'Enter Viewport'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-leaveview-interaction" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"trigger\":\"leaveView\"")'>Elements with a Leave Viewport interaction<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the trigger set on 'Leave Viewport'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-ajaxstart-interaction" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"trigger\":\"ajaxStart\"")'>Elements with a Query AJAX loader (Start) interaction<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the trigger set on 'Query AJAX loader (Start)'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-ajaxend-interaction" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"trigger\":\"ajaxEnd\"")'>Elements with a Query AJAX loader (End) interaction<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the trigger set on 'Query AJAX loader (End)'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-custom-trigger" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"target\":\"custom\"")'>Elements targets a CSS Selector<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the target set on 'CSS selector'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-popup-trigger" onclick='ADMINBRXC.shSearchValueInKey(event,"_interactions", "\"target\":\"popup\"")'>Elements targets a popup<div data-balloon="Show Elements with at lease one interaction set inside the interaction tab with the target set on 'Popup'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                            </ul>
                            <ul>
                                <span class="title">Conditions</span>
                                <li data-id="has-condition" onclick="ADMINBRXC.shCheckPropArray(event,'_conditions', 0);">Elements with at least one condition<div data-balloon="Show Elements with at least one condition set inside the condition tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-condition-post" onclick='ADMINBRXC.shSearchValueInKey(event,"_conditions", "\"key\":\"post")'>Elements with a Post condition<div data-balloon="Show Elements with at least one POST condition set inside the condition tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-condition-user" onclick='ADMINBRXC.shSearchValueInKey(event,"_conditions", "\"key\":\"user")'>Elements with a User condition<div data-balloon="Show Elements with at least one USER condition set inside the condition tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-condition-date" onclick='ADMINBRXC.shConditionDateTime(event)'>Elements with a Date & Time condition<div data-balloon="Show Elements with at least one DATE & TIME condition set inside the condition tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-condition-dynamic-data" onclick='ADMINBRXC.shSearchValueInKey(event,"_conditions", "\"key\":\"dynamic_data")'>Elements with a Dynamic Data condition<div data-balloon="Show Elements with at least one DYNAMIC DATA condition set inside the condition tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-condition-browser" onclick='ADMINBRXC.shSearchValueInKey(event,"_conditions", "\"key\":\"browser")'>Elements with a Browser condition<div data-balloon="Show Elements with at least one BROWSER condition set inside the condition tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-condition-os" onclick='ADMINBRXC.shSearchValueInKey(event,"_conditions", "\"key\":\"operating_system")'>Elements with an Operating System condition<div data-balloon="Show Elements with at least one OPERATING SYSTEM condition set inside the condition tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-condition-ref" onclick='ADMINBRXC.shSearchValueInKey(event,"_conditions", "\"key\":\"referer")'>Elements with a Referrer URL condition<div data-balloon="Show Elements with at least one REFERRER URL condition set inside the condition tab." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>

                            </ul>
                            <ul>
                                <span class="title">Query Loops</span>
                                <li data-id="has-query-loop" onclick="ADMINBRXC.shCheckProp(event, 'hasLoop');">Elements use a Query Loop<div data-balloon="Show Elements that contain an active Query Loop." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-query-loop-post" onclick='ADMINBRXC.shSearchValueInKeyObj(event,"query", "\"objectType\":\"post")'>Elements use a Posts Query Loop<div data-balloon="Show Elements that contain an active Query Loop with a post type set on POSTS." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-query-loop-term" onclick='ADMINBRXC.shSearchValueInKeyObj(event,"query", "\"objectType\":\"term")'>Elements use a Terms Query Loop<div data-balloon="Show Elements that contain an active Query Loop with a post type set on TERMS." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-query-loop-user" onclick='ADMINBRXC.shSearchValueInKeyObj(event,"query", "\"objectType\":\"user")'>Elements use a Users Query Loop<div data-balloon="Show Elements that contain an active Query Loop with a post type set on USERS." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-query-loop-acf" onclick='ADMINBRXC.shSearchValueInKeyObj(event,"query", "\"objectType\":\"acf")'>Elements use a ACF Query Loop<div data-balloon="Show Elements that contain an active Query Loop with a post type set on either ACF REPEATER or ACF FLEXIBLE CONTENT." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="has-query-loop-editor" onclick='ADMINBRXC.shSearchValueInKeyObj(event,"query", "\"useQueryEditor\":true")'>Elements use a Custom Code Query Loop<div data-balloon="Show Elements that contain an active Query Loop with the 'Query editor (PHP)' option toggled on." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                            </ul>
                            <ul>
                                <span class="title">Seo</span>
                                <li data-id="non-consecutive-headers" onclick="ADMINBRXC.shNonConsecutiveHeaders(event);">Non-consecutive Headers<div data-balloon="Show Header Elements that contain a non-consecutive HTML tag. All your Header tags should be consecutive: H1 -> H2 -> H3, etc... The option will highlight the headers that skipped a header level: H1 -> H3." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="links-no-rel" onclick="ADMINBRXC.shMissingProp(event,'link','rel');">Elements with links, but with no "rel" attribute<div data-balloon="Show Elements with an anchor link set inside the content tab, but with no value set in the 'Attibute: rel' control." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="links-no-title" onclick="ADMINBRXC.shMissingProp(event,'link','title');">Elements with links, but with no "title" attribute<div data-balloon="Show Elements with an anchor link set inside the content tab, but with no value set in the 'Attibute: title' control." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                            </ul>
                            <ul>
                                <span class="title">Accessibility</span>
                                <li data-id="links-no-aria" onclick="ADMINBRXC.shMissingProp(event,'link','ariaLabel');">Elements with links, but with no aria-label set<div data-balloon="Show Elements with an anchor link set inside the content tab, but with no value set in the 'Attibute: aria-label' control." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="attributes-no-aria" onclick='ADMINBRXC.shSearchValueInKey(event,"_attributes", "\"name\":\"aria-")'>Elements that contain "aria-" in at least one attribute<div data-balloon="Show Elements with at least one data-attribute set that contains the string 'aria-'." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                            </ul>
                            <ul>
                                <span class="title">Performance</span>
                                <li data-id="images-lazy" onclick="ADMINBRXC.shSearchValue(event,'loading','lazy');">Lazy Loading Elements<div data-balloon="Show Image Elements with the 'Loading' control set to LAZY." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                                <li data-id="images-eager" onclick="ADMINBRXC.shSearchValue(event,'loading','eager');">Eager Loading Elements<div data-balloon="Show Image Elements with the 'Loading' control set to EAGER." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                            </ul>
                            <ul>
                                <span class="title">Colors</span>
                                <li data-id="has-static-colors" onclick="ADMINBRXC.shHasStaticColors(event);">Elements with static colors<div data-balloon="Show Elements that contains a static HEX/RGB/HSL color - as opposed to a dynamic color set using a CSS variable." data-balloon-pos="left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></li>
                            </ul>
                        </div>
                        <div class="brxc-overlay__pannel-col--right">
                            <div id="structurPanelList"></div>
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
$brxc_modals['structure_helper'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];