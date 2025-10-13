<?php

namespace Wpcb2\Snippet;


use Wpcb2\FunctionalityPlugin\Manager;

class JsSnippet extends Snippet
{

	function getCode()
	{
		if(Manager::$isTempPlugin) {
			$manager = new Manager(Manager::$tempPluginName, Manager::$isTempPlugin);
		} else
		{
			$manager = new Manager(false, false);
		}

		$hooks = $this->getHook();

		$render_type = $this->snippetData['renderType'];

		$code = '';
		if($render_type === 'none' ) {
			return '';
		}

		if ($render_type === 'external') {

			foreach ($hooks as $hook) {

				if ($hook['hook'] === 'custom_after_pagebuilders') {
					$hook['hook'] = 'wp_head';
					$hook['priority'] = 1000000;
				}


				$priority = $hook['priority'];
				$hook = $hook['hook'];

				if ($hook === 'plugins_loaded') {
					$hook = 'wp_head';
				}

				$dir = wp_upload_dir();

				$tagOptionsString = "";

				$tagOptions = $this->snippetData['tagOptions'];
				$tagOptions = json_decode($tagOptions, true);

				if (is_array($tagOptions)) {
					foreach ($tagOptions as $value) {
						if ($value['value'] === 'async') {
							$tagOptionsString .= " async ";
						} else if ($value['value'] === 'defer') {
							$tagOptionsString .= " defer ";
						}
					}
				}

				$wpcodeboxDir = $dir['baseurl'] . '/wpcodebox';

				$version_hash = substr(md5($this->snippetData['lastModified']), 0, 16);


				if ($this->isFp) {

					$folder = $this->getFolderName();
					if($folder) {
						$folder = $folder . '/';
					}

					$snippetCode = "\n" . '<script type="text/javascript" ' . $tagOptionsString . ' src="' . $manager->getCodeUrl() . 'assets/js/' . $folder . $manager->slugify($this->snippetData['title']) . '.js?v=' . $version_hash . '"></script>' . "\n";
				} else {
					$snippetCode = "\n" . '<script type="text/javascript" ' . $tagOptionsString . ' src="' . $wpcodeboxDir . DIRECTORY_SEPARATOR . $this->snippetData['id'] . '.js?v=' . $version_hash . '"></script>' . "\n";
				}
				$conditionCode = $this->getConditionCode();

				$code .= <<<EOD
add_action('$hook', function() {
    $conditionCode
?>
$snippetCode
        <?php
    }, $priority);

EOD;
			}


		} else {
			if ($manager->isEnabled()) {
				foreach ($hooks as $hook) {
					$priority = $hook['priority'];
					$hook = $hook['hook'];

					if ($hook === 'custom_after_pagebuilders') {
						$hook = 'wp_head';
						$priority = 1000000;
					}

					if ($hook === 'plugins_loaded') {
						$hook = 'wp_head';
					}

					$conditionCode = $this->getConditionCode();

					$snippetCode = $this->code;


					$code .= <<<EOD
add_action('$hook', function() {

        $conditionCode
        ?>
        <script type='text/javascript'>
        $snippetCode
        </script>

    <?php
    }, $priority);


EOD;

				}

				return $code;

			} else {
				foreach ($hooks as $hook) {
					if ($hook['hook'] === 'custom_after_pagebuilders') {
						$hook['hook'] = 'wp_head';
						$hook['priority'] = 1000000;
					}

					$this->globalJS->addScript($hook['hook'], $hook['priority'], $this->code, $this->snippetData['id']);
				}

			}
			return false;
		}


		return $code;

	}

	public function getFiles() {
		$files = [];
		if($this->getRenderType() === 'external') {
			$files[] = 'assets/js/' . $this->getFileName();
		}

		$files[] = 'snippets/' . $this->getMainFileName();

		return $files;
	}
}
