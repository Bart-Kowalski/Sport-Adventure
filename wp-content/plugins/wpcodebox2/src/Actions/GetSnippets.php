<?php


namespace Wpcb2\Actions;


use Wpcb2\FunctionalityPlugin\Manager;
use Wpcb2\Service\SnippetToResponseMapper;

class GetSnippets
{
    public function execute()
    {
		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();
		$snippets = $snippetRepository->getSnippetsInFolder(0);

        $snippets_response = [];
        $folders_response = [];

		$manager = new Manager(false);
		$nonMatchingSnippets = $manager->checkSnippetSignatures();

        $snippetToResponseMapper = new SnippetToResponseMapper();
        foreach ($snippets as $snippet) {
            $snippets_response[] = $snippetToResponseMapper->mapSnippetToResponse($snippet);
        }

		$folders = $snippetRepository->getFolders();

        foreach ($folders as $folder) {
            $folders_response_item = [];

            $folders_response_item['title'] = $folder['name'];
            $folders_response_item['id'] = $folder['id'];
            $folders_response_item['savedToCloud'] = !!$folder['savedToCloud'];
            $folders_response_item['remoteId'] = $folder['remoteId'];
            $folders_response_item['order'] = $folder['folder_order'];

			$folderId = intval($folder['id']);
			$snippetsInFolder = $snippetRepository->getSnippetsInFolder($folderId);

            $postToResponseMapper = new SnippetToResponseMapper();
            foreach ($snippetsInFolder as $snippet) {
                $folders_response_item['children'][] = $postToResponseMapper->mapSnippetToResponse($snippet);
            }

            if (!isset($folders_response_item['children'])) {
                $folders_response_item['children'] = [];
            }

            $folders_response[] = $folders_response_item;
        }

        echo json_encode([
            'snippets' => $snippets_response,
            'folders' => $folders_response,
			'nonMatchingSnippets' => $nonMatchingSnippets
        ]);
        die;
    }
}
