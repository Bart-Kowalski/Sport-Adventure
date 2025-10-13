<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcClassManagerOverlay';
$prefix_id = 'brxcClassManager';
$prefix_class = 'brxc-class-manager';
// Heading
$modal_heading_title = 'Class Manager';

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
                <div class="brxc-overlay__panel-switcher-wrapper">
                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-class-manager" name="<?php echo esc_attr($prefix_id);?>-switch" class="brxc-input__radio" data-transform="0" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);ADMINBRXC.setClassManager();" checked>
                    <label for="<?php echo esc_attr($prefix_id);?>-class-manager" class="brxc-input__label">Overview</label>
                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-grid" name="<?php echo esc_attr($prefix_id);?>-switch" class="brxc-input__radio" data-transform="calc(-100% - 80px)" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);ADMINBRXC.gridUtilityMount();">
                    <label for="<?php echo esc_attr($prefix_id);?>-grid" class="brxc-input__label">Grid Utility Classes</label>
                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-bulk-actions" name="<?php echo esc_attr($prefix_id);?>-switch" class="brxc-input__radio" data-transform="calc(2 * (-100% - 80px))" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);ADMINBRXC.setClassManagerBulk();">
                    <label for="<?php echo esc_attr($prefix_id);?>-bulk-actions" class="brxc-input__label">Bulk Actions</label>
                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-unused" name="<?php echo esc_attr($prefix_id);?>-switch" class="brxc-input__radio" data-transform="calc(3 * (-100% - 80px))" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);ADMINBRXC.setClassManagerUnused();">
                    <label for="<?php echo esc_attr($prefix_id);?>-unused" class="brxc-input__label">Unused</label>
                </div>
                <div class="brxc-overlay__pannels-wrapper">
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                        <div class="brxc-class-manager__wrapper">
                            <div class="col-1">
                                <div class="brxc-overlay__search-box">
                                    <input type="text" class="class-filter" name="class-search" placeholder="Filter by name" data-type="title" onInput="ADMINBRXC.states.classManagerSearch = this.value;ADMINBRXC.setClassList('global');">
                                    <div class="iso-search-icon">
                                        <i class="bricks-svg ti-search"></i>
                                    </div>
                                    <div class="action">
                                        <div class="iso-icon reset light" data-balloon="Reset Filter" data-balloon-pos="bottom-right" onclick="ADMINBRXC.resetFilter(this);this.parentElement.previousElementSibling.previousElementSibling.value = '';">
                                            <i class="bricks-svg ti-close"></i>
                                        </div>
                                        <div class="iso-icon light" data-balloon="Filter classes that contain styles" data-balloon-pos="bottom-right" onclick="ADMINBRXC.filterClassesByStyle(this);">
                                            <i class="bricks-svg fab fa-css3-alt"></i>
                                        </div>
                                        <div class="iso-icon light" data-balloon="Filter by Active on page" data-balloon-pos="bottom-right" onclick="ADMINBRXC.filterClassesByActive(this);">
                                            <i class="bricks-svg fas fa-toggle-on"></i>
                                        </div>
                                        <div class="iso-icon light" data-balloon="Filter by Locked Status" data-balloon-pos="bottom-right" onclick="ADMINBRXC.filterClassesByStatus(this);">
                                            <i class="bricks-svg fas fa-lock"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="cat-list">
                                    <div id="brxcCatListCanvas" class="brxc-manager__left-menu"></div>
                                </div>
                                <div class="class-list">
                                    <div id="brxcClassListCanvas"></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div id="brxcClassContentCanvas"></div>
                            </div>
                        </div>
                    </div>
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-2">
                         <p data-control="info">The utility classes below create responsive grid layouts across all device sizes. Unlike traditional grid utility classes, these allow you to define the maximum number of columns for wider screens and the minimum column size before the grid adjusts by reducing columns. Instead of relying on breakpoints, these classes function as "clamp" grid classes, dynamically adapting to available space.</p>
                        <table border="0">
                            <thead>
                                <tr>
                                    <th>Class Name</th>
                                    <th>Column Gap</th>
                                    <th>Max N° of Columns</th>
                                    <th>Min Column Width (in pixels)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="brxcGridUlilityCanvas"></tbody>
                        </table>
                        <div class="brxc-overlay__action-btn-wrapper right active">
                            <div class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.gridUtilityAddNewClass()">
                                <span>Add New Class</span>
                            </div>
                        </div>
                    </div>
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-3">
                        <div class="brxc-bulk-actions__wrapper">
                            <div class="col-left">
                                <div id="brxcClassBulkActionList"></div>
                            </div>
                            <div class="col-right">
                                <label class="has-tooltip">
                                    <span>I want to:</span>
                                    <div data-balloon="Select the action you want to apply to the global classes." data-balloon-pos="bottom" data-balloon-length="medium"><i class="fas fa-circle-question"></i></div>
                                </label>
                                <div class="brxc-overlay__panel-inline-btns-wrapper last-line-wrap m-bottom-24">
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-rename" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="rename" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Rename';ADMINBRXC.setClassManagerBulk();" checked>
                                    <label for="<?php echo esc_attr($prefix_id);?>-rename" class="brxc-overlay__panel-inline-btns">Rename</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-duplicate" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="duplicate" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Duplicate';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-duplicate" class="brxc-overlay__panel-inline-btns">Duplicate</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-modify" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="Modify" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Modify';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-modify" class="brxc-overlay__panel-inline-btns">Modify</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-findandreplace" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="FindAndReplace" onClick="ADMINBRXC.states.classManagerBulkActionType = 'FindAndReplace';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-findandreplace" class="brxc-overlay__panel-inline-btns">Find & Replace</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-group" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="Group" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Group';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-group" class="brxc-overlay__panel-inline-btns">Group</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-lock" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="Lock" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Lock';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-lock" class="brxc-overlay__panel-inline-btns">Lock</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-unlock" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="Unlock" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Unlock';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-unlock" class="brxc-overlay__panel-inline-btns">Unlock</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-export" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="export" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Export';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-export" class="brxc-overlay__panel-inline-btns">Export</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-delete" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="delete" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Delete';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-delete" class="brxc-overlay__panel-inline-btns">Delete</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-restore" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="restore" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Restore';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-restore" class="brxc-overlay__panel-inline-btns">Restore</label>
                                    <input type="radio" id="<?php echo esc_attr($prefix_id);?>-permadelete" name="<?php echo esc_attr($prefix_id);?>bulkAction" class="brxc-input__checkbox" value="permadelete" onClick="ADMINBRXC.states.classManagerBulkActionType = 'Permadelete';ADMINBRXC.setClassManagerBulk();">
                                    <label for="<?php echo esc_attr($prefix_id);?>-permadelete" class="brxc-overlay__panel-inline-btns">Permadelete</label>
                                </div>
                                <div id="brxcClassBulkActionCanvas"></div>
                            </div>
                        </div>
                    </div>
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-4">
                        <div class="brxc-unused__wrapper">
                            <div class="col-left">
                                <div id="brxcClassUnusedList"></div>
                            </div>
                            <div class="col-right">
                                <div id="brxcClassUnusedCanvas">
                                    <p data-control="info" style="margin-bottom:20px;">The global classes listed on the left represent all the classes that are not assigned to any element across all your pages, posts, templates, or components (site-wide). Both the Global Classes and CSS Classes controls have been scanned. However, if a global class was added within custom HTML code, non-Bricks pages or posts, or if you assigned a class to an element but haven’t saved the page yet, the scanner may not detect it accurately. Before proceeding, ensure you have a backup of your site.</p>
                                    <div id="brxcFilterCanvas"></div>
                                    <div id="unusedClassesCTA" class="brxc-overlay__action-btn-wrapper right sticky-bottom generate-content active">
                                        <div class="brxc-overlay__action-btn" onclick="ADMINBRXC.unusedClassesReset()">
                                            <span>Reset Filters</span>
                                        </div>
                                        <div class="brxc-overlay__action-btn" onclick="ADMINBRXC.unusedClassesRescan()">
                                            <span>Rescan Unused Classes</span>
                                        </div>
                                        <div class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.unusedClassesTrash()">
                                            <span>Move to Trash</span>
                                        </div>
                                    </div>
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
$brxc_modals['class_manager'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];