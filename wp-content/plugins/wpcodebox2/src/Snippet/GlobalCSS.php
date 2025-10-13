<?php

namespace Wpcb2\Snippet;


use Wpcb2\Repository\SnippetRepository;

class GlobalCSS
{

    private $styles = [];

    public function addStyle($hook, $priority, $code, $snippetId)
    {
		if($hook === 'plugins_loaded') {
			$hook = 'wp_head';
		}
        $this->styles[$hook][$priority][] = ['code' => $code, 'id' => $snippetId];

		return true;
    }

    public function output()
    {
        $this->outputAutoreload();

        if (!count($this->styles)) {
            return;
        }

        if (is_array($this->styles)) {
            foreach ($this->styles as $hook => $priorities) {
                if (is_array($priorities)) {
                    foreach ($priorities as $priority => $styles) {
                        add_action($hook, function () use ($styles) {

                            $snippetIds = '';

                            $code = '';
                            $renderedSnippetIds = [];

                            foreach ($styles as $style) {

                                if (\Wpcb2\ConditionBuilder\ShouldExecute::should_execute($style['id'])) {
                                    $code .= $style['code'] . "\n";
                                    $renderedSnippetIds[] = $style['id'];

                                }

                            }


                            if (current_user_can('manage_options')) {

                                $snippetIds = "wpcb-ids='";
                                $snippetIds .= implode(',', $renderedSnippetIds) . "'";
                            }


                            echo "<style type='text/css' $snippetIds class='wpcb2-inline-style'>\n";
                                echo $code;
                            echo "</style>";

                        }, $priority);
                    }
                }
            }
        }
    }

    public function outputAutoreload()
    {
        $this->registerAutoreloadEndpoint();

        add_action('wp_head', function () {
            if (!current_user_can('manage_options')) {
                return;
            }

            $url = admin_url('admin-ajax.php');
            $js = <<<EOD
 <script type='text/javascript'>

    addEventListener("storage", (ev) => {

        if(ev.key === 'wpcb2Reload') {

            document.querySelectorAll('.wpcb2-inline-style').forEach(function(current){

                let snippetIds = current.getAttribute('wpcb-ids');
                let formData = new FormData();
                formData.append('action', 'wpcb2_get_dev_code');
                formData.append('snippet_ids', snippetIds);


                fetch('$url', {
                      method: "POST",
                      body: formData,
                      credentials: 'same-origin',

                    }).then(response => response.json())
                     .then(response => {
                        current.textContent = response.code;
                     });
                });

                document.querySelectorAll('.wpcb2-external-style').forEach(function(current){

                    current.disabled = true;

                    let href = current.getAttribute('href');

                    if(href.includes('wpcb_rand')) {
                        href += Math.floor(Math.random() * 20);
                    } else {
                        href += '&wpcb_rand=' +  Math.floor(Math.random() * 1000);
                    }

                    current.setAttribute('href', href);
                    current.disabled = false;
                });

        }
    });

                </script>
EOD;
            echo $js;

        });
    }

    private function registerAutoreloadEndpoint()
    {
        add_action('wp_ajax_wpcb2_get_dev_code', function () {

            if (!current_user_can('manage_options')) {
                wp_die();
                return;
            }

            if (function_exists('session_write_close')) {
                session_write_close();
            }

            $snippet_ids = $_POST['snippet_ids'];

            $snippet_ids = explode(",", $snippet_ids);

            $code = "";
			$snippetRepository = new SnippetRepository();

			foreach ($snippet_ids as $snippet_id) {
            	$snippet = $snippetRepository->getSnippet($snippet_id);

                $snippet_code = $snippet['code'];

                $code .= $snippet_code . "\n";
            }

            echo json_encode(['code' => $code]);
            die;


        });
    }

    public function getCodeForFP()
    {

		$code = "<?php\nif(!defined('ABSPATH')) { die(); } \n?>\n\n";

        foreach ($this->styles as $hook => $priorities) {
            foreach ($priorities as $priority => $styles) {

                $code .= "<?php\n\nadd_action('$hook', function () { ?>\n\n";

                $snippetIds = "wpcb-ids='";
                $ids = [];
                foreach ($styles as $key => $style) {
                    $ids[] = $style['id'];
                }

                $snippetIds = $snippetIds . implode(',', $ids) . "'";

                $code .= "<style type='text/css' $snippetIds class='wpcb2-inline-style'>\n";
                foreach ($styles as $style) {


					$snippetRepository = new SnippetRepository();
					$snippet = $snippetRepository->getSnippet($style['id']);
                    $conditions = $snippet['conditions'];

					// TODO: Fix inline styles conditions
                    $conditionBuilderCode =  "<?php if(true) { ?>\n\n";

                    $code .= $conditionBuilderCode;

                    $code .= $style['code'] . "\n";

                    $code .= "\n<?php } ?>";
                }
                $code .= "</style>";

                $code .= " \n\n<?php }, $priority); ?>";
            }
        }

        return $code;
    }
}
