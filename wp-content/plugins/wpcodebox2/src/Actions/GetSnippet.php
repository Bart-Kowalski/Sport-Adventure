<?php


namespace Wpcb2\Actions;


use Wpcb2\Repository\SnippetRepository;
use Wpcb2\Service\SnippetToResponseMapper;

class GetSnippet
{
    public function execute($id)
    {
		global $wpdb;

		$snippetRepository = new SnippetRepository();
		$snippet = $snippetRepository->getSnippet($id);

		if(!$snippet) {
			wp_die('Snippet not found');
		}
        $postMapper = new SnippetToResponseMapper();
        $response = $postMapper->mapSnippetToResponse($snippet);

        echo json_encode($response);
        die;
    }
}
