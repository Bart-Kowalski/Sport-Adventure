<?php


namespace Wpcb2\Actions;


use Wpcb2\Repository\SnippetRepository;

class CreateFolder
{
    public function execute()
    {

        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

		$snippetRepository = new SnippetRepository();
		$folderId = $snippetRepository->createFolder(['name' => $data['name']]);

		$manager = new \Wpcb2\FunctionalityPlugin\Manager(false);

		$manager->createFolder($data['name']);
		echo json_encode(['post_id' => $folderId]);

        die;

    }
}
