<?php


namespace Wpcb2\Actions;


class UpdateFolderState
{
    public function execute($id)
    {
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

		$repository = new \Wpcb2\Repository\SnippetRepository();

		$childSnippets = $repository->getSnippetsInFolder($id);

        foreach($childSnippets as $snippet ) {

			$repository->updateSnippet($snippet['id'],[
				'enabled' => $data['state'] ? 1 : 0
			]);
		}
    }


}

