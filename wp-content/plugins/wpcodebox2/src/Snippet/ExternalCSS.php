<?php

namespace Wpcb2\Snippet;


class ExternalCSS extends Snippet
{

    function getCode()
    {
        $hooks = $this->getHook();

        $codeData = json_decode($this->code, true);

        $conditionCode = $this->getConditionCode();

        if(!isset($codeData['code'])) {
            return;
        }

		foreach($hooks as $hook) {

			if($hook['hook'] === 'custom_after_pagebuilders') {
				$hook['hook'] = 'wp_head';
				$hook['priority'] = 1000000;
			}

			if($hook['hook'] === 'plugins_loaded') {
				$hook['hook'] = 'wp_head';
			}


			$code = <<<EOD
add_action('$hook[hook]', function() {
    $conditionCode
?>
        $codeData[code]
        <?php
    }, $hook[priority]);

EOD;
		}

        return $code;
    }
}
