<?php
namespace Wpcb2\Actions;

class DownloadPlugin
{
    public function execute()
    {

		if(!class_exists('ZipArchive')) {
			echo 'Zip extension is not loaded';
			http_response_code(501);
			die;
		}

        ob_clean();

		$pluginName = 'wpcodebox_functionality_plugin';

		if(defined('WPCB_FP_PLUGIN_NAME')) {
			$pluginName = WPCB_FP_PLUGIN_NAME;
		}

		$slugifier = new \Wpcb2\FunctionalityPlugin\Service\Slugifier();
		$pluginName = $slugifier->slugify($pluginName);

		$tempDir = sys_get_temp_dir();
		$destination = $tempDir . DIRECTORY_SEPARATOR . $pluginName . '.zip';

		$source = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $pluginName;
		$this->zipData($source, $destination);

		echo file_get_contents($destination);

		unlink($destination);

        die;
    }

	private function zipData( $source, $destination )
	{
		$zip = new \ZipArchive();
		if($zip->open($destination, \ZIPARCHIVE::CREATE) === true) {
			$source = realpath($source);
			if(is_dir($source)) {
				$iterator = new \RecursiveDirectoryIterator($source);
				$iterator->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);
				$files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
				foreach($files as $file) {
					$file = realpath($file);
					if(is_dir($file)) {
						$zip->addEmptyDir(str_replace($source . DIRECTORY_SEPARATOR, '', $file . DIRECTORY_SEPARATOR));
					}elseif(is_file($file)) {
						$zip->addFile($file,str_replace($source . DIRECTORY_SEPARATOR, '', $file));
					}
				}
			}elseif(is_file($source)) {
				$zip->addFile($source,basename($source));
			}
		}
		return $zip->close();
	}

}
