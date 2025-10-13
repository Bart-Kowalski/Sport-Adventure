<?php

namespace Wpcb2\FunctionalityPlugin\Service;

class FileSystem
{

	public function zipData( $source, $destination )
	{
		$zip = new \ZipArchive();
		if($zip->open($destination, \ZIPARCHIVE::CREATE) === true) {
			$source = realpath($source);
			$source = str_replace("\\", "/", $source);
			if(is_dir($source)) {
				$iterator = new \RecursiveDirectoryIterator($source);
				$iterator->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);
				$files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
				foreach($files as $file) {
					$file = realpath($file);
					$file = str_replace("\\", "/", $file);
					if(is_dir($file)) {
						$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
					}elseif(is_file($file)) {
						$zip->addFile($file,str_replace($source . '/', '', $file));
					}
				}
			}elseif(is_file($source)) {
				$zip->addFile($source,basename($source));
			}
		}
		return $zip->close();
	}

	public function copyFolder($from, $to)
	{
		if (!is_dir($from)) {
			return false;
		}

		if (!is_dir($to)) {
			if (!mkdir($to)) {
				return false;
			};
		}

		$dir = opendir($from);
		while (($ff = readdir($dir)) !== false) {
			if ($ff != "." && $ff != "..") {
				if (is_dir("$from$ff")) {
					$this->copyFolder("$from$ff/", "$to$ff/");
				} else {
					if (!copy("$from$ff", "$to$ff")) {
						exit("Error copying $from$ff to $to$ff");
					}
				}
			}
		}
		closedir($dir);

		return true;
	}


	public function recursiveRemoveDirectory($directory)
	{
		foreach(glob("{$directory}/*") as $file)
		{
			if(is_dir($file)) {
				$this->recursiveRemoveDirectory($file);
			} else {
				unlink($file);
			}
		}
		rmdir($directory);
	}
}
