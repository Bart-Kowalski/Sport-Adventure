<?php

namespace Wpcb2\Snippet;


use Wpcb2\FunctionalityPlugin\Manager;

class StyleSnippet extends Snippet
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

		if($render_type === 'none') {
			return '';
		}

		if ($render_type === 'external') {

			foreach ($hooks as $hook) {
				$priority = $hook['priority'];
				$hook = $hook['hook'];

				if($hook === 'custom_after_pagebuilders') {
					$hook = 'wp_head';
					$priority = 1000000;
				}

				if($hook === 'plugins_loaded') {
					$hook = 'wp_head';
				}

				$conditionCode = $this->getConditionCode();

				$dir = wp_upload_dir();
				$wpcodeboxDir = $dir['baseurl'] . '/wpcodebox';
				$version_hash = substr(md5($this->snippetData['lastModified']), 0, 16);

				if ($this->isFp) {
					$folder = $this->getFolderName();
					if($folder) {
						$folder = $folder . '/';
					}
					$snippetCode = "\n" .  '<link rel="stylesheet" class="wpcb2-external-style" href="' . $manager->getCodeUrl() . 'assets/css/'  . $folder . $manager->slugify($this->snippetData['title']) . '.css?v='.$version_hash.'"/>' . "\n";
				} else {
					$snippetCode = '<link rel="stylesheet" class="wpcb2-external-style" href="' . $wpcodeboxDir . '/' . $this->snippetData['id'] . '.css?v=' . $version_hash . '"/>';
				}

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

			if($manager->isEnabled()) {
				foreach ($hooks as $hook) {
					$priority = $hook['priority'];
					$hook = $hook['hook'];

					if($hook === 'custom_after_pagebuilders') {
						$hook = 'wp_head';
						$priority = 1000000;
					}

					if($hook === 'plugins_loaded') {
						$hook = 'wp_head';
					}

					$conditionCode = $this->getConditionCode();

					$snippetIds = "wpcb-ids='".$this->snippetData['id'] . "'";
					$snippetCode = $this->code;


					$code .= <<<EOD
add_action('$hook', function() {

        $conditionCode
        ?>
        <style $snippetIds class='wpcb2-inline-style'>\n
        $snippetCode
        </style>

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
					$this->globalCSS->addStyle($hook['hook'], $hook['priority'], $this->code, $this->snippetData['id']);
				}
			}

			return false;

		}

		return $code;
	}

	public function getFiles()
	{

		$files = [];
		if($this->getRenderType() === 'external') {
			$files[] = 'assets/css/' . $this->getFileName();
		}

		if($this->getCodeType() === 'scssp') {
			$files[] = 'snippets/' . $this->getFileNameWithoutExtension() . '.scss';
		}

		$files[] = 'snippets/' . $this->getMainFileName();

		return $files;
	}
}
