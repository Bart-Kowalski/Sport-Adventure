<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcCodePenImporter';
$prefix = 'cpImporter';
$prefix_id = 'cpImporter';
// Heading
$modal_heading_title = 'Structure Generator';
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
                            <div id="brxcCpImporterCanvas">
                                <div id="brxcCpImporterMain">
                                    <div id="brxcCpImporterEditors">
                                        <div id="brxcCpImporterHTML">
                                            <div class="brxc-title__wrapper">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 124 141.53199999999998" fill="none"><path d="M10.383 126.894L0 0l124 .255-10.979 126.639-50.553 14.638z" fill="#e34f26"></path><path d="M62.468 129.277V12.085l51.064.17-9.106 104.851z" fill="#ef652a"></path><path d="M99.49 41.362l1.446-15.49H22.383l4.34 47.49h54.213L78.81 93.617l-17.362 4.68-17.617-5.106-.936-12.085H27.319l2.128 24.681 32 8.936 32.255-8.936 4.34-48.17H41.107L39.49 41.362z" fill="#fff"></path></svg>
                                                <span class="brxc-title">HTML</span>
                                                <div data-panel="html" data-balloon="Toggle Highlight" data-balloon-pos="left"><i class="fas fa-eye-slash"></i></div>
                                            </div>
                                            
                                            <textarea data-mode="htmlmixed" placeholder="<b-text-basic>This is some text</b-text-basic>"></textarea>
                                        </div>
                                        <div id="brxcCpImporterCSS">
                                            <div class="brxc-title__wrapper">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 124 141.53" fill="none"><path d="M10.383 126.892L0 0l124 .255-10.979 126.637-50.553 14.638z" fill="#1b73ba"></path><path d="M62.468 129.275V12.085l51.064.17-9.106 104.85z" fill="#1c88c7"></path><path d="M100.851 27.064H22.298l2.128 15.318h37.276l-36.68 15.745 2.127 14.808h54.043l-1.958 20.68-18.298 3.575-16.595-4.255-1.277-11.745H27.83l2.042 24.426 32.681 9.106 31.32-9.957 4-47.745H64.765l36.085-14.978z" fill="#fff"></path></svg>
                                                <span class="brxc-title">CSS</span>
                                                <div data-panel="css" data-balloon="Toggle Highlight" data-balloon-pos="left"><i class="fas fa-eye-slash"></i></div>
                                            </div>
                                            <textarea data-mode="css" placeholder="body{background: red;}"></textarea>
                                        </div>
                                        <div id="brxcCpImporterJS">
                                            <div class="brxc-title__wrapper">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 1052 1052"><path fill="#f0db4f" d="M0 0h1052v1052H0z"></path><path d="M965.9 801.1c-7.7-48-39-88.3-131.7-125.9-32.2-14.8-68.1-25.399-78.8-49.8-3.8-14.2-4.3-22.2-1.9-30.8 6.9-27.9 40.2-36.6 66.6-28.6 17 5.7 33.1 18.801 42.8 39.7 45.4-29.399 45.3-29.2 77-49.399-11.6-18-17.8-26.301-25.4-34-27.3-30.5-64.5-46.2-124-45-10.3 1.3-20.699 2.699-31 4-29.699 7.5-58 23.1-74.6 44-49.8 56.5-35.6 155.399 25 196.1 59.7 44.8 147.4 55 158.6 96.9 10.9 51.3-37.699 67.899-86 62-35.6-7.4-55.399-25.5-76.8-58.4-39.399 22.8-39.399 22.8-79.899 46.1 9.6 21 19.699 30.5 35.8 48.7 76.2 77.3 266.899 73.5 301.1-43.5 1.399-4.001 10.6-30.801 3.199-72.101zm-394-317.6h-98.4c0 85-.399 169.4-.399 254.4 0 54.1 2.8 103.7-6 118.9-14.4 29.899-51.7 26.2-68.7 20.399-17.3-8.5-26.1-20.6-36.3-37.699-2.8-4.9-4.9-8.7-5.601-9-26.699 16.3-53.3 32.699-80 49 13.301 27.3 32.9 51 58 66.399 37.5 22.5 87.9 29.4 140.601 17.3 34.3-10 63.899-30.699 79.399-62.199 22.4-41.3 17.6-91.3 17.4-146.6.5-90.2 0-180.4 0-270.9z" fill="#323330"></path></svg>
                                                <span class="brxc-title">JavaScript</span>
                                                <div data-panel="js" data-balloon="Toggle Highlight" data-balloon-pos="left"><i class="fas fa-eye-slash"></i></div>
                                            </div>
                                            <textarea data-mode="javascript" placeholder="console.log('AT FTW!');"></textarea>
                                        </div>
                                    </div>
                                    <div id="brxcCpImporterPreviewContainer">
                                        <div id="brxcCpImporterPreviewIcons"></div>
                                        <div id="brxcCpImporterPreviewWrapper">
                                            <iframe id="brxcCpImporterPreview"></iframe>
                                        </div>
                                    </div>
                                </div>
                                <div id="brxcCpImporterFilterCanvas"></div>
                                <div id="brxcCpImporterAIContainer"></div>
                                <div id="brxcCpImporterLoading">
                                    <div id="brxcCpImporterLoadingContainer">
                                        <div class="bricks-logo-animated"><div class="cube top-left"></div><div class="cube top-right"></div><div class="cube bottom-left"></div><div class="cube bottom-right"></div></div>
                                        <span>Generating the code...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="brxc-overlay__footer">
                    <div class="brxc-overlay__footer-wrapper">
                        <a class="brxc-overlay__action-btn primary" style="margin-left: auto;" onClick="ADMINBRXC.codepenImporter();ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');"><span>Import & Close</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }

$output = ob_get_clean();
$output = preg_replace('/>\s+</s', '><', $output);
$brxc_modals['codepen_importer'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];