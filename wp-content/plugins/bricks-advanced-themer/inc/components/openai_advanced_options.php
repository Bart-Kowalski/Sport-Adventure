<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}
?>
<div class="brxc-accordion-container">
    <div class="brxc-accordion-btn">
        <label>Advanced Options</label>
        <span></span>
    </div>
    <div class="brxc-accordion-panel">
        <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Model" class="has-tooltip">
            <span>AI Model</span>
            <div data-balloon="Check the various models specificities at https://platform.openai.com/docs/models/" data-balloon-pos="bottom" data-balloon-length="medium"><i class="fas fa-circle-question"></i></div>
        </label>
        <div class="brxc-overlay__panel-inline-btns-wrapper light m-bottom-24">
            <?php
            $index = 0;
            foreach($brxc_acf_fields['ai_models']['completion'] as $model){
                $str = strtolower( preg_replace( '/\s+/', '-', $model ) );
            ?>
                <input type="radio" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-<?php echo $str;?>" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-models" class="brxc-input__checkbox" value="<?php echo $str;?>" <?php echo ($index === 0 ) ? 'checked' : '';?>>
                <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-<?php echo $str;?>" class="brxc-overlay__panel-inline-btns"><?php echo $model;?></label>
            <?php $index++;
            }
            ?>
        </div>
        <?php if($include_tones){?>
            <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>System" class="brxc-input__label">Tone of voice <span class="brxc__light">(Optional)</span></label>
            <div class="brxc-overlay__panel-inline-btns-wrapper light">
            <?php 
            foreach($brxc_acf_fields['tone_of_voice'] as $tone){
                $str = strtolower( preg_replace( '/\s+/', '-', $tone ) );
            ?>
                <input type="checkbox" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-<?php echo $str;?>" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-tones" class="brxc-input__checkbox" data-tone="<?php echo $str;?>">
                <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-<?php echo $str;?>" class="brxc-overlay__panel-inline-btns"><?php echo $tone;?></label>
            <?php }?>
            <?php if($custom_tone){?>
                <input type="checkbox" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-custom" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-tones" class="brxc-input__checkbox" onChange="ADMINBRXC.toggleCustomToneVoice('<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>', this);" data-tone="custom">
                <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-custom" class="brxc-overlay__panel-inline-btns">Custom</label>
            <?php }?>
            </div>
            <?php if($custom_tone){?>
            <div class="brxc__text">
                <input type="text" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>System" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>System" placeholder="Type here any additional information on the System context." style="margin: 1.2rem 0 0;display:none;">
            </div>
            <?php }?>
        <?php }?>
        <div class="brxc-prompt-options-wrapper three-col">
            <div class="brxc-prompt-option">
                <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Choices" class="has-tooltip"><span>Num Choices</span><div data-balloon="How many chat completion choices to generate for each input message." data-balloon-pos="top" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></label>
                <div class="brxc__range">
                    <input type="range" min="1" max="5" step="1" value="1" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Choices" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Choices" class="brxc-input__range" oninput="document.querySelector('<?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>ChoicesValue').innerHTML = parseInt(event.target.value).toFixed(0)">
                    <span id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>ChoicesValue">1</span>
                </div>
            </div>
            <div class="brxc-prompt-option">
                <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>MaxTokens" class="has-tooltip"><span>Max Tokens per input</span><div data-balloon="The maximum number of tokens to generate in the completion. The token count of your prompt plus max_tokens cannot exceed the model's context length (4096)." data-balloon-pos="top" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></label>
                <div class="brxc__range">
                    <input type="range" min="0" max="32000" step="1" value="1000" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>MaxTokens" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>MaxTokens" class="brxc-input__range" oninput="document.querySelector('<?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>MaxTokensValue').innerHTML = parseInt(event.target.value).toFixed(0)">
                    <span id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>MaxTokensValue">1000</span>
                </div>
            </div>
            <div class="brxc-prompt-option">
                <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature" class="has-tooltip"><span>Temperature</span><div data-balloon="What sampling temperature to use, between 0 and 2. Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic. It's recommended altering this or top probability but not both." data-balloon-pos="top" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></label>
                <div class="brxc__range">
                    <input type="range" min="0" max="2" step="0.1" value="1" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature" class="brxc-input__range" oninput="document.querySelector('<?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TemperatureValue').innerHTML = parseFloat(event.target.value).toFixed(1)">
                    <span id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TemperatureValue">1</span>
                </div>
            </div>
            <div class="brxc-prompt-option">
                <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TopP" class="has-tooltip"><span>Top Probability</span><div data-balloon="An alternative to sampling with temperature, called nucleus sampling, where the model considers the results of the tokens with top_p probability mass. So 0.1 means only the tokens comprising the top 10% probability mass are considered. It's recommended altering this or temperature but not both." data-balloon-pos="top" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></label>
                <div class="brxc__range">
                    <input type="range" min="0.01" max="1" step="0.01" value="1" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TopP" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TopP" class="brxc-input__range" oninput="document.querySelector('<?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TopPValue').innerHTML = parseFloat(event.target.value).toFixed(2)">
                    <span id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TopPValue">1</span>
                </div>
            </div>
            <div class="brxc-prompt-option">
                <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Presence" class="has-tooltip"><span>Presence Penalty</span><div data-balloon="Number between -2.0 and 2.0. Positive values penalize new tokens based on whether they appear in the text so far, increasing the model's likelihood to talk about new topics." data-balloon-pos="top" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></span></label>
                <div class="brxc__range">
                    <input type="range" min="-2" max="2" step="0.1" value="0" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Presence" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Presence" class="brxc-input__range" oninput="document.querySelector('<?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>PresenceValue').innerHTML = parseFloat(event.target.value).toFixed(1)">
                    <span id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>PresenceValue">0</span>
                </div>
            </div>
            <div class="brxc-prompt-option">
                <label for="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Frequency" class="has-tooltip"><span>Frequency Penalty</span><div data-balloon="Number between -2.0 and 2.0. Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model's likelihood to repeat the same line verbatim." data-balloon-pos="top" data-balloon-length="large"><i class="fas fa-circle-question"></i></div></label>
                <div class="brxc__range">
                    <input type="range" min="-2" max="2" step="0.1" value="0" name="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Frequency" id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Frequency" class="brxc-input__range" oninput="document.querySelector('<?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>FrequencyValue').innerHTML = parseFloat(event.target.value).toFixed(1)">
                    <span id="<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>FrequencyValue">0</span>
                </div>
            </div>
        </div>
    </div>
</div>