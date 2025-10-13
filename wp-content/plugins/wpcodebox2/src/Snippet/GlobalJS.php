<?php

namespace Wpcb2\Snippet;


use Wpcb2\Repository\SnippetRepository;

class GlobalJS
{

    private $scripts = [];

    public function addScript($hook, $priority, $code, $id)
    {
		if($hook === 'plugins_loaded') {
			$hook = 'wp_head';
		}
        $this->scripts[$hook][$priority][] = ['code'=>$code, 'id'=>$id];
	}

    public function output()
    {
        if(!count($this->scripts)) {
            return;
        }

        if (is_array($this->scripts)) {
            foreach ($this->scripts as $hook => $priorities) {
                foreach ($priorities as $priority => $scripts) {
                    add_action($hook, function () use ($scripts) {

                        echo "<script type='text/javascript'>";
                        foreach ($scripts as $script) {
                            if(\Wpcb2\ConditionBuilder\ShouldExecute::should_execute($script['id'])) {
                                echo $script['code'] . "\n";
                            } else {
                                echo '';
                            }
                        }
                        echo "</script>";

                    }, $priority);

                }
            }
        }
    }


    public function getCodeForFP()
    {

		$code = "<?php\nif(!defined('ABSPATH')) { die(); } \n?>\n\n";

        foreach ($this->scripts as $hook => $priorities) {
            foreach ($priorities as $priority => $scripts) {

                $code .= "\n<?php\n\nadd_action('$hook', function () { ?>\n\n";

                $code .= "<script type='text/javascript'>\n";
                foreach ($scripts as $script) {

					$snippetRepository = new SnippetRepository();
					$snippet = $snippetRepository->getSnippet($script['id']);
					$conditions = $snippet['conditions'];

					// TODO: Fix inline styles conditions
					$conditionBuilderCode =  "<?php if(true) { ?>\n\n";

                    $code .= $conditionBuilderCode;

                    $code .= $script['code'] . "\n";

                    $code .= "\n<?php } ?>";
                }
                $code .= "</script>";

                $code .= " \n\n<?php }, $priority); ?>";
            }
        }

        return $code;
    }
}
