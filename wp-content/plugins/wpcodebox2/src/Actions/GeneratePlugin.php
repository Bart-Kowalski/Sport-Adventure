<?php
namespace Wpcb2\Actions;

use Wpcb2\FunctionalityPlugin\Manager;

class GeneratePlugin
{
    public function execute()
    {

		if(!class_exists('ZipArchive')) {
			echo 'Zip extension is not loaded';
			http_response_code(501);
			die;
		}

        ob_clean();

		$data = json_decode(file_get_contents("php://input"), true);

		if(empty($data['pluginDetails']['title'])) {
			$data['pluginDetails']['title'] = 'My Custom Functionality Plugin';
		}


		$manager = new Manager($data['pluginDetails']['title'],true);

		if(empty($data['pluginDetails']['description'])) {
			$data['pluginDetails']['description'] = 'This plugin stores custom functionality';
		}

		if(empty($data['pluginDetails']['version'])) {
			$data['pluginDetails']['version'] = '1.0.0';
		}

		if (empty($data['pluginDetails']['author'])) {
			$data['pluginDetails']['author'] = 'WPCodeBox';
		}

		$pluginZip = $manager->generatePlugin($data['selectedSnippets'], $data['pluginDetails']['title'], $data['pluginDetails']['description'], $data['pluginDetails']['author'], $data['pluginDetails']['version']);

		echo $pluginZip;

        die;
    }



}
