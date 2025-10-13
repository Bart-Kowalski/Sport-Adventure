<?php


namespace Wpcb2\Actions;


use Wpcb2\FunctionalityPlugin\Manager;
use Wpcb2\FunctionalityPlugin\PluginsFolderNotWritableException;

class ChangeFPStatus
{
	public function execute()
	{
		$functionalityPluginManager = new Manager(false);

		if (!$functionalityPluginManager->isEnabled()) {
			try {
				$functionalityPluginManager->enable();
			} catch (PluginsFolderNotWritableException $e) {
				echo json_encode(['success' => false, 'message' => 'The Plugins folder is not writable']);
				die;

			}
		} else {
			$functionalityPluginManager->disable();
		}


		echo json_encode(['success' => true]);
		die;
	}
}
