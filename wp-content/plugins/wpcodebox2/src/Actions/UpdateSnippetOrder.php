<?php


namespace Wpcb2\Actions;


use Wpcb2\Service\ExternalFile;
use Wpcb2\Service\Minify\MinifyFactory;

class UpdateSnippetOrder
{
    public function execute()
    {
        $response = array();

        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();

        foreach($data as $orderItem) {

			if($orderItem['order'] < 1000) {
				$snippetRepository->updateSnippet($orderItem['id'], [
					'snippet_order' => $orderItem['order']
				]);
			} else {
				$snippetRepository->updateFolder($orderItem['id'], [
					'folder_order' => $orderItem['order']
				]);
			}
        }

        echo json_encode([]);
        die;
    }
}
