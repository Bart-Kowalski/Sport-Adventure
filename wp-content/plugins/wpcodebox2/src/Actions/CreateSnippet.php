<?php
namespace Wpcb2\Actions;

use Wpcb2\Api\Api;

class CreateSnippet
{
    public function execute()
    {

		$api = new Api();

        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

		try {
			$id = $api->createSnippet($data);
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
