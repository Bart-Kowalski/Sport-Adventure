<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcPromptManagerOverlay';
$prefix_id = 'brxcPromptManager';
$prefix_class = 'brxc-query-manager';
// Heading
$modal_heading_title = 'AI Prompt Manager';

ob_start();

// Check if the value is in the allowed list, otherwise set to a default
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
    $at_global_settings_arr = get_option('bricks_advanced_themer_builder_settings');

    if(AT__Helpers::is_array($at_global_settings_arr, 'prompt_manager')){
        $elements = $at_global_settings_arr['prompt_manager'];
    } else {
        $elements = [
            [
                'id' => 'default_1',
                'label' => 'Convert Static Values to CSS Variables',
                'prompt' => 'Transform all static values in the CSS code (like colors, spacing, font sizes, etc.) into reusable CSS variables, grouping them logically (e.g., colors, typography, spacing). Make sure to replace all instances of these static values with the corresponding variables. Declare all the CSS variables at the beginning of the code. The variables should be declared as scoped of the target selector.',
                'category' => 'css'
            ],
            [
                'id' => 'default_2',
                'label' => 'Ensure Cross-Browser Compatibility',
                'prompt' => 'Analyze the CSS code and make sure it is fully compatible with all major browsers, excluding legacy versions of Internet Explorer. Add necessary vendor prefixes, fallbacks, or polyfills where required to ensure consistent behavior across browsers.',
                'category' => 'css'
            ],
            [
                'id' => 'default_3',
                'label' => 'Optimize CSS for Performance',
                'prompt' => 'Optimize the CSS code for better performance by removing unused styles, merging redundant rules, and minimizing the use of deeply nested selectors. Replace long, repetitive rules with shorthand properties and ensure the CSS is as clean and efficient as possible.',
                'category' => 'css'
            ],
            [
                'id' => 'default_4',
                'label' => 'Add Dark Mode Support',
                'prompt' => 'Enhance the CSS code by adding support for dark mode. Use the @media (prefers-color-scheme: dark) query to define alternative styles for elements. Ensure colors, shadows, and borders are adjusted for a visually appealing dark mode experience.',
                'category' => 'css'
            ],
            [
                'id' => 'default_5',
                'label' => 'Modularize and Simplify CSS Structure',
                'prompt' => 'Restructure the CSS code into modular components by grouping styles into reusable classes or BEM (Block Element Modifier) methodology. Break down complex selectors into simpler, maintainable parts and ensure consistency in naming conventions.',
                'category' => 'css'
            ]
        ];
    }

    // Pass the $elements array to the JavaScript file
wp_localize_script( 'brxc-builder', 'brxcPromptManager', $elements );  
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
                        <div id="promptManagerUI">
                            <div id="promptManagerUI__left">
                                <div class="brxc-overlay__search-box">
                                    <input type="text" class="class-filter" name="class-search" placeholder="Filter by name" data-type="title" oninput="ADMINBRXC.promptManagerStates.search = this.value;ADMINBRXC.promptManagerList();">
                                    <div class="iso-search-icon">
                                        <i class="bricks-svg ti-search"></i>
                                    </div>
                                    <div class="iso-reset light" data-balloon="Reset Filter" data-balloon-pos="bottom-right" onclick="ADMINBRXC.resetPromptFilter(this);">
                                        <i class="bricks-svg ti-close"></i>
                                    </div>
                                </div>
                                <div class="promptManagerUI__cat">
                                    <div id="brxcPromptCatListCanvas"></div>
                                </div>
                                <div class="promptManagerUI__prompts">
                                    <ul id="promptManagerUI__list"></ul>
                                    <div class="brxc-class-manager__footer"><input type="text" id="addNewPrompt" placeholder="Add a new Prompt" onkeyup="ADMINBRXC.addNewPrompt(event);"></div>
                                </div>
                            </div>
                            <div id="promptManagerUI__panel"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="brxc-overlay__footer">
                <div class="brxc-overlay__footer-wrapper">
                    <a class="brxc-overlay__action-btn secondary" style="margin-left: auto;" onClick="ADMINBRXC.savePromptManager()"><span>Save & Continue</span></a>
                    <a class="brxc-overlay__action-btn primary" onClick="ADMINBRXC.savePromptManager();ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');"><span>Save & Close</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }

$output = ob_get_clean();
$output = preg_replace('/>\s+</s', '><', $output);
$brxc_modals['prompt_manager'] = [
    "id" => $overlay_id,
    "html" => trim($output)
];