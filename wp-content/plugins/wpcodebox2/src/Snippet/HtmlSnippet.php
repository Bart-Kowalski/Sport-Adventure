<?php

namespace Wpcb2\Snippet;


class HtmlSnippet extends Snippet
{
    function getCode()
    {
        $hooks = $this->getHook();

        $returnCode = '';

        $conditionCode = $this->getConditionCode();

        foreach($hooks as $hook) {
            $returnCode .= $this->getHookCode($hook, $conditionCode);
        }

        return $returnCode;
    }

    private function getHookCode($hook, $conditionCode)
    {
        $processedHook = '';

        if(isset($hook['priority'])) {
            $priority = $hook['priority'];
        } else {
            $priority = 10;
        }

        if(isset($hook['shortcode'])) {
            $processedHook = $hook['shortcode'];
        }

        if(isset($hook['hook']['value'])) {
            $processedHook = $hook['hook']['value'];
        }
        if(isset($hook['hook'])) {
            $processedHook = $hook['hook'];
        }


        if ($processedHook === 'custom_shortcode') {

            $this->code = do_shortcode($this->code);
            $shortcode = $hook['shortcode'];
            $shortcode = str_replace(['[', ']'], '', $shortcode);
            $code = <<<EOD
add_shortcode('$shortcode', function(\$atts, \$content = '') {
ob_start();
?>$this->code<?php
return ob_get_clean();

    }, $priority);

EOD;
        } else {

            $parsedCode = do_shortcode($this->code);

            $code = <<<EOD

add_action('$processedHook', function() {
        $conditionCode
?>
     $parsedCode
  <?php
    }, $priority);


EOD;
        }
        return $code;
    }
}
