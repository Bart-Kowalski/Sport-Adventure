<?php

namespace Wpcb2\Snippet;


use Wpcb2\ConditionBuilder\ConditionBuilder;

class PhpSnippet extends Snippet
{
    public function getCode()
    {
		$code = $this->code;

		$conditionCode = $this->getConditionCode();

		$pos = strpos($code, '<?php');
		if ($pos !== false) {
			$code = substr_replace($code, '', $pos, strlen('<?php'));
		}

		if ($this->endsWith(trim($code), '?>')) {
			$code = $this->strLreplace('?>', '', $code);
		}

		if($this->snippetData['runType'] === 'external') {

			$secret = $this->snippetData['secret'];
			return <<<EOD
add_action('wp_loaded', function() {

	if(!isset(\$_GET['wpcb_token'])) {
		return;
	}

	if(!ctype_alnum(\$_GET['wpcb_token'])) {
		return;
	}

	if(\$_GET['wpcb_token'] !== '$secret') {
		return;
	}

	$code

});
EOD;

		}

		if($this->snippetData['runType'] === 'never') {
			return $code;
		}

		$numberOfOpenTags = substr_count($code, '<?php');
		$numberOfCloseTags = substr_count($code, '?>');

		if($numberOfOpenTags === $numberOfCloseTags - 1) {
			$code = $code . "\n <?php\n";
		}

        $returnCode = '';
        $hooks = $this->getHook();

		foreach($hooks as $hook) {
			$returnCode .= $this->getHookCode($hook, $code, $conditionCode);
        }

        return $returnCode;
    }

    private function endsWith($string, $endString)
    {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }

    // Replace last occurance of string in another string
    private function strLreplace($search, $replace, $subject)
    {
        $pos = strrpos($subject, $search);

        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

    /**
     * @return bool|mixed|string
     */
    private function getHookCode($hook, $code, $conditionCode)
    {
        $initialHook = $hook;

		if(isset($hook['priority'])) {
			$priority = $hook['priority'];
		} else {
			$priority = 10;
		}

		if(isset($hook['hook']['value'])) {
		    $hook= $hook['hook']['value'];
        }
        if(isset($hook['hook'])) {
		    $hook = $hook['hook'];
        }

        if ($hook === 'custom_shortcode') {
            $shortcode = $initialHook['shortcode'];
            $shortcode = str_replace(['[', ']'], '', $shortcode);

            $code = <<<EOD
add_shortcode('$shortcode', function(\$atts, \$content = '') {
ob_start();

	// Code Snippet Code
    $code
    // End Code Snippet Code

return ob_get_clean();

    }, $priority);


EOD;
        } else if ($hook === 'custom_root') {

            $code = <<<EOD
                $conditionCode


	// Code Snippet Code
	$code
	// End Code Snippet Code
EOD;

        } else {
            $code = "\nadd_action('$hook', function() {\n
                $conditionCode

	// Code Snippet Code
    $code
    // End Code Snippet Code

}, $priority);";

        }


        return $code;
    }

}
