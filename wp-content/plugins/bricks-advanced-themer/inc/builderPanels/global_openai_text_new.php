<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcGlobalOpenAIOverlay';
$prefix = 'global-openai';
// Heading
$modal_heading_title = 'OpenAI Assistant';
$theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
//for loops
$i = 0;
// Languages
$languages = [
    'Afrikaans' => 'af',
    'Arabic' => 'ar',
    'Armenian' => 'hy',
    'Azerbaijani' => 'az',
    'Belarusian' => 'be',
    'Bosnian' => 'bs',
    'Bulgarian' => 'bg',
    'Catalan' => 'ca',
    'Chinese' => 'zh',
    'Croatian' => 'hr',
    'Czech' => 'cs',
    'Danish' => 'da',
    'Dutch' => 'nl',
    'English' => 'en',
    'Estonian' => 'et',
    'Finnish' => 'fi',
    'French' => 'fr',
    'Galician' => 'gl',
    'German' => 'de',
    'Greek' => 'el',
    'Hebrew' => 'he',
    'Hindi' => 'hi',
    'Hungarian' => 'hu',
    'Icelandic' => 'is',
    'Indonesian' => 'id',
    'Italian' => 'it',
    'Japanese' => 'ja',
    'Kannada' => 'kn',
    'Kazakh' => 'kk',
    'Korean' => 'ko',
    'Latvian' => 'lv',
    'Lithuanian' => 'lt',
    'Macedonian' => 'mk',
    'Malay' => 'ms',
    'Marathi' => 'mr',
    'Maori' => 'mi',
    'Nepali' => 'ne',
    'Norwegian' => 'no',
    'Persian' => 'fa',
    'Polish' => 'pl',
    'Portuguese' => 'pt',
    'Romanian' => 'ro',
    'Russian' => 'ru',
    'Serbian' => 'sr',
    'Slovak' => 'sk',
    'Slovenian' => 'sl',
    'Spanish' => 'es',
    'Swahili' => 'sw',
    'Swedish' => 'sv',
    'Tagalog' => 'tl',
    'Tamil' => 'ta',
    'Thai' => 'th',
    'Turkish' => 'tr',
    'Ukrainian' => 'uk',
    'Urdu' => 'ur',
    'Vietnamese' => 'vi',
    'Welsh' => 'cy',
];

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
<div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" onmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
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
                    <input type="radio" id="<?php echo esc_attr($prefix);?>-images" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="calc(2 * (-100% - 80px))" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                    <label for="<?php echo esc_attr($prefix);?>-images" class="brxc-input__label">Images</label>
                    <input type="radio" id="<?php echo esc_attr($prefix);?>-stt" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="calc(3 * (-100% - 80px))" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                    <label for="<?php echo esc_attr($prefix);?>-stt" class="brxc-input__label">Speech to Text</label>
                    <input type="radio" id="<?php echo esc_attr($prefix);?>-tts" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="calc(4 * (-100% - 80px))" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                    <label for="<?php echo esc_attr($prefix);?>-tts" class="brxc-input__label">Text to Speech</label>
                    <input type="radio" id="<?php echo esc_attr($prefix);?>-history" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="calc(5 * (-100% - 80px))" onClick="ADMINBRXC.mounAIHistory('<?php echo esc_attr($prefix);?>', '#<?php echo esc_attr($overlay_id);?>');ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
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
                        <div id="<?php echo esc_attr($prefix);?>GenerateContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-auto generate-content active">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>GenerateContentWrapper'))"><span>Reset</span></div>
                            <div class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.getAIResponse('<?php echo esc_attr($prefix);?>',this,true,'#<?php echo esc_attr($overlay_id);?>', document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-tones]:checked'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>System').value, parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature').value).toFixed(1), parseInt(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>MaxTokens').value), parseInt(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Choices').value), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TopP').value).toFixed(2), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Presence').value).toFixed(1), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Frequency').value).toFixed(1), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-models]:checked').value);"><span>Generate Content</span></div>
                        </div>
                        <div id="<?php echo esc_attr($prefix);?>InsertContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-auto action-wrapper">
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
                        <div id="<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-auto generate-content active">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper'))">
                                <span>Reset</span>
                            </div>
                            <div class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.getEditAIResponse('<?php echo esc_attr($prefix);?>',this,true,'#<?php echo esc_attr($overlay_id);?>', document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-tones]:checked'), false, parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature').value).toFixed(1), parseInt(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>MaxTokens').value), parseInt(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Choices').value), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TopP').value).toFixed(2), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Presence').value).toFixed(1), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Frequency').value).toFixed(1), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-models]:checked').value);">
                                <span>Generate Edit</span>
                            </div>
                        </div>
                        <div id="<?php echo esc_attr($prefix);?>Insert<?php echo esc_attr($type);?>ContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-auto action-wrapper">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper'))">
                                <span>Reset</span>
                            </div>
                            <div class="brxc-overlay__action-btn" onClick='ADMINBRXC.copytoClipboard(this,document.querySelector("#<?php echo esc_attr($overlay_id);?> input[name=<?php echo esc_attr($prefix);?>-edit-results]:checked + label .message.assistant").textContent,"Content Copied!", "Copy Selected to Clipboard");'>
                                <span>Copy Selected to Clipboard</span>
                            </div>
                        </div>
                    </div>
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-3 image accordion v1">
                    <?php 
                    $pannel = '.brxc-overlay__pannel-3.image';
                    $type = 'Images';
                    $custom_tone = false;
                    $include_tones = false;
                    ?>
                        <div class="brxc-field__wrapper">
                            <label class="brxc-input__label">User Prompt <span class="brxc__light">(Required - Max 1000 characters)</span></label>
                            <?php include \BRICKS_ADVANCED_THEMER_PATH . '/inc/components/openai_no_reset.php';?>
                            <textarea name="<?php echo esc_attr($prefix);?>-prompt-text" id="<?php echo esc_attr($prefix);?>Images" class="<?php echo esc_attr($prefix);?>-prompt-text reset-value-on-reset message input" placeholder="Describe your image here..." cols="30" rows="5" maxlength="1000"></textarea>
                        </div>
                        <div class="brxc-accordion-container">
                            <div class="brxc-accordion-btn">
                                <label>Advanced Options</label>
                                <span></span>
                            </div>
                            <div class="brxc-accordion-panel">
                                <div class="brxc-prompt-options-wrapper two-col">
                                    <div class="brxc-prompt-option">
                                        <label for="<?php echo esc_attr($prefix);?>ImagesChoices" class="brxc-input__label">Num Choices</label>
                                        <div class="brxc__range">
                                            <input type="range" min="1" max="10" step="1" value="1" name="<?php echo esc_attr($prefix);?>ImagesChoices" id="<?php echo esc_attr($prefix);?>ImagesChoices" class="brxc-input__range" oninput="document.querySelector('#<?php echo esc_attr($prefix);?>ImagesChoicesValue').innerHTML = parseInt(event.target.value)">
                                            <span id="<?php echo esc_attr($prefix);?>ImagesChoicesValue">1</span>
                                        </div>
                                    </div>
                                    <div class="brxc-prompt-option">
                                        <label class="brxc-input__label">Image Size</label>
                                        <div class="brxc-overlay__panel-inline-btns-wrapper light">
                                            <input type="radio" id="<?php echo esc_attr($prefix);?>-256" name="<?php echo esc_attr($prefix);?>-images" class="brxc-input__radio" checked>
                                            <label for="<?php echo esc_attr($prefix);?>-256" class="brxc-overlay__panel-inline-btns">256x256</label>
                                            <input type="radio" id="<?php echo esc_attr($prefix);?>-512" name="<?php echo esc_attr($prefix);?>-images" class="brxc-input__radio">
                                            <label for="<?php echo esc_attr($prefix);?>-512" class="brxc-overlay__panel-inline-btns">512x512</label>
                                            <input type="radio" id="<?php echo esc_attr($prefix);?>-1024" name="<?php echo esc_attr($prefix);?>-images" class="brxc-input__radio">
                                            <label for="<?php echo esc_attr($prefix);?>-1024" class="brxc-overlay__panel-inline-btns">1024x1024</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="<?php echo esc_attr($prefix);?>GenerateImagesContentWrapper" class="brxc-ai-response-wrapper brxc-overlay__action-btn-wrapper right m-top-auto generate-content active">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>GenerateImagesContentWrapper'))">
                                <span>Reset</span>
                            </div>
                            <div class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.getImageAIResponse('<?php echo esc_attr($prefix);?>', this,true, '#<?php echo esc_attr($overlay_id);?>', parseInt(document.querySelector('#<?php echo esc_attr($prefix);?>ImagesChoices').value), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?>-images]:checked + label').textContent);">
                                <span>Generate Image(s)</span>
                            </div>
                        </div>
                        <div id="<?php echo esc_attr($prefix);?>InsertImagesContentWrapper" class="brxc-ai-response-wrapper brxc-overlay__action-btn-wrapper right m-top-auto action-wrapper">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>GenerateImagesContentWrapper'))">
                                <span>Reset</span>
                            </div>
                            <div class="brxc-overlay__action-btn" onclick='ADMINBRXC.downloadAIImage(document.querySelector("#<?php echo esc_attr($overlay_id);?> input[name=<?php echo esc_attr($prefix);?>-images-results]:checked + label img.brxc__image").src)'>
                                <span>Download</span>
                            </div>
                            <div class="brxc-overlay__action-btn primary" onClick='ADMINBRXC.saveAIImagetoMediaLibrary(this,document.querySelector("#<?php echo esc_attr($overlay_id);?> input[name=<?php echo esc_attr($prefix);?>-images-results]:checked + label img.brxc__image").src);'>
                                <span>Save to Media Library</span>
                            </div>
                        </div>
                    </div>
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-4 spp accordion v1">
                    <?php 
                    $pannel = '.brxc-overlay__pannel-4.spp';
                    $type = 'Spp';
                    $custom_tone = false;
                    $include_tones = false;
                    ?>
                        <div class="brxc-prompt-option">
                            <label for="brxcTTSInput" class="has-tooltip"><span>Audio File</span><div data-balloon="File size limit is 25MB. Supported formats: mp3, mp4, mpeg, mpga, m4a, wav, and webm." data-balloon-pos="bottom-left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></label>
                            <input type="file" id="brxcTTSInput" class="brxc__file-input m-bottom-24"/>
                        </div>
                        <div class="brxc-accordion-container">
                            <div class="brxc-accordion-btn">
                                <label>Advanced Options</label>
                                <span></span>
                            </div>
                            <div class="brxc-accordion-panel">
                                <div class="brxc-prompt-options-wrapper two-col">
                                    <div class="brxc-prompt-option">
                                        <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Language" class="has-tooltip"><span>Language</span><div data-balloon="Choose the language of the transcription." data-balloon-pos="bottom-left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></label>
                                        <div class="brxc-select">
                                            <select id="<?php echo esc_attr($prefix);?>Language" style="background-color: var(--builder-bg-3);margin:0;">
                                                <?php 
                                                foreach ($languages as $language => $value){
                                                    ?>
                                                    <option value="<?php echo esc_attr($value)?>"<?php echo $language === "English" ? ' selected="selected"' : '' ?>><?php echo esc_attr($language)?></option>
                                                    <?php
                                                };
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="brxc-prompt-option">
                                        <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature" class="has-tooltip"><span>Temperature</span><div data-balloon="The sampling temperature, between 0 and 1. Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic." data-balloon-pos="bottom" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></label>
                                        <div class="brxc__range">
                                            <input type="range" min="0" max="1" step="0.1" value="0.8" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature" class="brxc-input__range" oninput="document.querySelector('<?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TemperatureValue').innerHTML = parseFloat(event.target.value).toFixed(1)">
                                            <span id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TemperatureValue">0.8</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="brxcTTSCanvas" class="m-bottom-24"></div>
                        <div id="<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper" class="brxc-ai-response-wrapper brxc-overlay__action-btn-wrapper right m-top-auto generate-content active">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper'))">
                                <span>Reset</span>
                            </div>
                            <div class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.generateAudioTranscription('<?php echo esc_attr($prefix);?>',this,true, '#<?php echo esc_attr($overlay_id);?>', document.querySelector('#<?php echo esc_attr($prefix);?>Language').value, parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature').value).toFixed(1))">
                                <span>Generate Transcription</span>
                            </div>
                        </div>
                    </div>
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-5 tts accordion v1">
                    <?php 
                    $pannel = '.brxc-overlay__pannel-5.tts';
                    $type = 'Tts';
                    $custom_tone = false;
                    $include_tones = false;
                    ?>
                        <div class="brxc-field__wrapper">
                            <label class="brxc-input__label">User Prompt <span class="brxc__light">(Required)</span></label>
                            <?php include \BRICKS_ADVANCED_THEMER_PATH . '/inc/components/openai_no_reset.php';?>
                            <textarea name="<?php echo esc_attr($prefix);?>-prompt-text" id="<?php echo esc_attr($prefix);?>PromptTextTTS" class="<?php echo esc_attr($prefix);?>-prompt-text reset-value-on-reset message user" placeholder="Type your prompt text here..." cols="30" rows="3"></textarea>
                        </div>
                        <div class="brxc-accordion-container">
                            <div class="brxc-accordion-btn">
                                <label>Advanced Options</label>
                                <span></span>
                            </div>
                            <div class="brxc-accordion-panel">
                                <div class="brxc-prompt-options-wrapper two-col">
                                    <div class="brxc-prompt-option">
                                        <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Voices" class="has-tooltip"><span>Voices</span><div data-balloon="The voice to use when generating the audio." data-balloon-pos="bottom-left" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></label>
                                        <div class="brxc-select">
                                            <select id="<?php echo esc_attr($prefix);?>Voices" style="background-color: var(--builder-bg-3);margin:0;">
                                                <?php 
                                                $voices = ['alloy','echo','fable','onyx','nova','shimmer'];
                                                foreach ($voices as $voice){
                                                    ?>
                                                    <option value="<?php echo esc_attr($voice)?>"<?php echo $voice === "alloy" ? ' selected="selected"' : '' ?>><?php echo esc_attr($voice)?></option>
                                                    <?php
                                                };
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="brxc-prompt-option">
                                        <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Speed" class="has-tooltip"><span>Speed</span><div data-balloon="The speed of the generated audio. Select a value from 0.25 to 4.0. 1.0 is the default." data-balloon-pos="bottom" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></label>
                                        <div class="brxc__range">
                                            <input type="range" min="0.25" max="4.00" step="0.01" value="1.00" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Speed" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Speed" class="brxc-input__range" oninput="document.querySelector('<?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>SpeedValue').innerHTML = parseFloat(event.target.value).toFixed(1)">
                                            <span id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>SpeedValue">1.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <audio id="brxcAudioPlayer" controls src=""></audio>
                        <div id="<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper" class="brxc-ai-response-wrapper brxc-overlay__action-btn-wrapper right m-top-auto generate-content active">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper'))">
                                <span>Reset</span>
                            </div>
                            <div class="brxc-overlay__action-btn primary" onclick="
                                ADMINBRXC.generateAudioSpeech(
                                    '<?php echo esc_attr($prefix);?>',
                                    this,
                                    true, 
                                    '#<?php echo esc_attr($overlay_id);?>',
                                    document.querySelector('#<?php echo esc_attr($prefix);?>PromptTextTTS').value,
                                    document.querySelector('#<?php echo esc_attr($prefix);?>Voices').value,
                                    document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Speed').value
                                )">
                                <span>Generate Speech</span>
                            </div>
                        </div>
                    </div>
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-5 history">
                        <div id="<?php echo esc_attr($prefix);?>History" class="brxc-ai-response-wrapper brxc-canvas empty m-bottom-24"></div>
                        <div id="<?php echo esc_attr($prefix);?>InsertHistoryContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-auto action-wrapper">
                            <div class="brxc-overlay__action-btn" onclick="document.querySelector('#<?php echo esc_attr($prefix);?>History').innerHTML = '<p class=\'brxc__no-record\'>No records yet. Please come back here after you generated some AI content.</p>';ADMINBRXC.aihistory = [];document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .brxc-overlay__pannel.history .brxc-canvas').classList.add('empty');">
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
$brxc_modals['global_openai_text'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];