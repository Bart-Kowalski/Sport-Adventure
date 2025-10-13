<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcopenAIOverlay';
$prefix = 'openai';
// Heading
$modal_heading_title = 'OpenAI Assistant';
//for loop
$i = 0;

ob_start();

if (!AT__Helpers::is_ai_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the button below and make sure that the <strong class='accent'>AI</strong> settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';

} elseif (!isset($brxc_acf_fields['openai_api_key']) || $brxc_acf_fields['openai_api_key'] === '1') {
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "OpenAI API key not found";
    $error_desc = "Did you correctly insert your OpenAI API key? Click on the button below and make sure that the AI settings are correcly filled inside <strong>AI > OpenAI API KEY</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';

} else {

?>
<div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" oonmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
    <div class="brxc-overlay__inner brxc-medium">
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
            <div class="brxc-overlay__container">
                <div class="brxc-overlay__panel-switcher-wrapper">
                    <input type="radio" id="<?php echo esc_attr($prefix);?>-completion" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="0" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);" checked>
                    <label for="<?php echo esc_attr($prefix);?>-completion" class="brxc-input__label">Completion / Chat</label>
                    <input type="radio" id="<?php echo esc_attr($prefix);?>-edit" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="calc(-100% - 80px)" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                    <label for="<?php echo esc_attr($prefix);?>-edit" class="brxc-input__label">Edit</label>
                    <input type="radio" id="<?php echo esc_attr($prefix);?>-history" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="calc(2 * (-100% - 80px))" onClick="ADMINBRXC.mounAIHistory('<?php echo esc_attr($prefix);?>', '#<?php echo esc_attr($overlay_id);?>');ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                    <label for="<?php echo esc_attr($prefix);?>-history" class="brxc-input__label" style="margin-left: auto;">History</label>
                </div>
                <div class="brxc-overlay__pannels-wrapper">
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1 completion accordion v1">
                    <?php 
                    $pannel = '.brxc-overlay__pannel-1.completion';
                    $type = 'Chat';
                    $custom_tone = true;
                    $include_tones = true;
                    ?>
                        <div class="brxc-field__wrapper">
                            <label class="brxc-input__label">User Prompt <span class="brxc__light">(Required)</span></label>
                            <?php include \BRICKS_ADVANCED_THEMER_PATH . '/inc/components/openai_no_reset.php';?>
                            <textarea name="<?php echo esc_attr($prefix);?>-prompt-text" id="<?php echo esc_attr($prefix);?>PromptText" class="<?php echo esc_attr($prefix);?>-prompt-text reset-value-on-reset message user" placeholder="Type your prompt text here..." cols="30" rows="3"></textarea>
                        </div>
                        <?php 
                        include \BRICKS_ADVANCED_THEMER_PATH . '/inc/components/openai_advanced_options.php';
                        ?>
                        <div id="<?php echo esc_attr($prefix);?>GenerateContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-16 generate-content active">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>GenerateContentWrapper'))"><span>Reset</span></div>
                            <div class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.getAIResponse('<?php echo esc_attr($prefix);?>',this,true,'#<?php echo esc_attr($overlay_id);?>', document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-tones]:checked'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>System').value, parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature').value).toFixed(1), parseInt(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>MaxTokens').value), parseInt(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Choices').value), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TopP').value).toFixed(2), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Presence').value).toFixed(1), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Frequency').value).toFixed(1), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-models]:checked').value);"><span>Generate Content</span></div>
                        </div>
                        <div id="<?php echo esc_attr($prefix);?>InsertContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-16 action-wrapper">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>GenerateContentWrapper'))">
                                <span>Reset</span>
                            </div>
                            <div id="<?php echo esc_attr($prefix);?>ChatMore" class="brxc-overlay__action-btn" onclick="ADMINBRXC.chatMoreAIResponse('<?php echo esc_attr($prefix);?>', true, '#<?php echo esc_attr($overlay_id);?>')">
                                <span>Chat More</span>
                            </div>
                            <div class="brxc-overlay__action-btn primary" onClick='ADMINBRXC.copytoClipboard(this,document.querySelector("#<?php echo esc_attr($overlay_id);?> input[name=openai-results]:checked + label .message.assistant").textContent,"Content Copied!", "Copy Selected to Clipboard");'>
                                <span>Copy Selected to Clipboard</span>
                            </div>
                        </div>
                    </div>
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-2 edit accordion v1">
                    <?php 
                    $pannel = '.brxc-overlay__pannel-2.edit';
                    $type = 'Edit';
                    $custom_tone = false;
                    $include_tones = true;
                    ?>
                        <div class="brxc-field__wrapper">
                            <label class="brxc-input__label">User Prompt <span class="brxc__light">(Required)</span></label>
                            <?php include \BRICKS_ADVANCED_THEMER_PATH . '/inc/components/openai_no_reset.php';?>
                            <textarea name="<?php echo esc_attr($prefix);?>-prompt-text" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Text" class="<?php echo esc_attr($prefix);?>-prompt-text reset-value-on-reset message user" placeholder="Type your prompt text here..." cols="30" rows="3"></textarea>
                        </div>
                        <div class="brxc-field__wrapper">
                            <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Instruction" class="brxc-input__label">Instructions <span class="brxc__light">(Required)</span></label>
                            <?php include \BRICKS_ADVANCED_THEMER_PATH . '/inc/components/openai_no_reset.php';?>
                            <textarea name="<?php echo esc_attr($prefix);?>-prompt-text" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Instruction" class="<?php echo esc_attr($prefix);?>-prompt-text reset-value-on-reset message instruction" placeholder="Type your instructions here..." cols="30" rows="3"></textarea>
                        </div>
                        <?php 
                        include \BRICKS_ADVANCED_THEMER_PATH . '/inc/components/openai_advanced_options.php';
                        ?>
                        <div id="<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-16 generate-content active">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper'))">
                                <span>Reset</span>
                            </div>
                            <div class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.getEditAIResponse('<?php echo esc_attr($prefix);?>',this,true,'#<?php echo esc_attr($overlay_id);?>', document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-tones]:checked'), false, parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature').value).toFixed(1), parseInt(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>MaxTokens').value), parseInt(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Choices').value), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TopP').value).toFixed(2), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Presence').value).toFixed(1), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Frequency').value).toFixed(1), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-models]:checked').value);">
                                <span>Generate Edit</span>
                            </div>
                        </div>
                        <div id="<?php echo esc_attr($prefix);?>Insert<?php echo esc_attr($type);?>ContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-16 action-wrapper">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper'))">
                                <span>Reset</span>
                            </div>
                            <div class="brxc-overlay__action-btn" onClick='ADMINBRXC.copytoClipboard(this,document.querySelector("#<?php echo esc_attr($overlay_id);?> input[name=<?php echo esc_attr($prefix);?>-edit-results]:checked + label .message.assistant").textContent,"Content Copied!", "Copy Selected to Clipboard");'>
                                <span>Copy Selected to Clipboard</span>
                            </div>
                        </div>
                    </div>
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-4 history">
                        <div id="<?php echo esc_attr($prefix);?>History" class="brxc-ai-response-wrapper brxc-canvas empty"></div>
                        <div id="<?php echo esc_attr($prefix);?>InsertHistoryContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-16 action-wrapper">
                            <div class="brxc-overlay__action-btn" onclick="document.querySelector('#<?php echo esc_attr($prefix);?>History').innerHTML = '<p class=\'brxc__no-record\'>No records yet. Please come back here after you generated some AI content.</p>';ADMINBRXC.aihistory = [];document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannel.history .brxc-canvas').classList.add('empty');">
                                <span>Reset</span>
                            </div>
                            <div class="brxc-overlay__action-btn primary" onClick='ADMINBRXC.copytoClipboard(this,document.querySelector("#<?php echo esc_attr($overlay_id);?> input[name=openai-results]:checked + label .message.assistant").textContent,"Content Copied!", "Copy Selected to Clipboard");'>
                                <span>Copy Selected to Clipboard</span>
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
$brxc_modals['openai_text'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];