<?php


namespace Wpcb2\Actions;


class SwitchDevMode
{
    public function execute($id)
    {
		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();

		$snippet = $snippetRepository->getSnippet($id);
        $enabled = $snippet['devMode'];

		$snippetRepository->updateSnippet($id, [
			'devMode' => !$enabled
		]);
        die;
    }
}
