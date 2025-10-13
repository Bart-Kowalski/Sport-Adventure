<?php


namespace Wpcb2\Actions;


use Wpcb2\Api\Api;
use Wpcb2\Service\SCSSUpdater;


class DeleteSnippet
{
    public function execute($id)
    {
		$api = new Api();
		$api->deleteSnippet($id);

		echo json_encode([]);
        die;
    }
}
