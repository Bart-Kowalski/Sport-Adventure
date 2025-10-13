<?php

namespace Wpcb2\Actions;

use Wpcb2\Api\Api;

class UpdateSnippet
{
    public function execute($id)
    {
        $error = false;
		$api = new Api();

        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

		try {
			$api->updateSnippet($id, $data);
		} catch (\Exception $e) {
			echo json_encode([
				'error' => true,
				'message'=> $e->getMessage()
			]);
			die;
		}
		echo json_encode(['post_id' => $id]);
        die;
    }


}
