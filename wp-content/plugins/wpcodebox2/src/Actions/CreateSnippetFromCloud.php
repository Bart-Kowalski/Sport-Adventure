<?php


namespace Wpcb2\Actions;


use Wpcb2\FunctionalityPlugin\Manager;
use Wpcb2\Repository\SnippetRepository;
use Wpcb2\Service\HookMapper;

class CreateSnippetFromCloud
{
    public function execute()
    {

        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

		$functionalityPlugin = new Manager(false);

		if (isset($data['tags'])) {

            try {
                $compiler = new \Wpcb2\Compiler();
                $code = $compiler->compileCode($data['code'], $data['tags']);

            } catch (\ScssPhp\ScssPhp\Exception\SassException $e) {
                $code = '';

                $error = $e->getMessage();
            }
        }


		$snippetRepository = new SnippetRepository();

		$snippet = $snippetRepository->findSnippetByRemoteId($data['remoteId']);

        if ($snippet) {


			$snippetId = $snippet['id'];
			$functionalityPlugin->deleteSnippet($snippetId);

			$snippetData = [
				'title' => $data['title'],
				'description' => isset($data['description']) ? $data['description'] : '',
				'enabled' => false
			];

            if($data['tags'] !== 'ex_js' && $data['tags'] !== 'ex_css') {

				$snippetData['code'] = $code;

            } else {

                $codeArr = [];

                if($data['tags'] === 'ex_js') {

                    $codeArr['code'] = "<script src='" . $data['code']. "'></script>";
                    $codeArr['externalUrl'] = $data['code'];

                } else if($data['tags'] === 'ex_css') {

                    $codeArr['code'] = '<link rel="stylesheet" href="' . $data['code'] . '"/>';
                    $codeArr['externalUrl'] = $data['code'];
                }

				$snippetData['code'] = json_encode($codeArr);
				$snippetData['externalUrl'] = $data['code'];

            }

			$snippetData['original_code'] = $data['code'];

            if (is_array($data['runType'])) {
				$snippetData['runType'] = $data['runType']['value'];
            } else {
				$snippetData['runType'] = $data['runType'];
            }

            if ($data['savedToCloud']) {
				$snippetData['savedToCloud'] = 1;
            }

            if (isset($data['tags'])) {
				$snippetData['codeType'] = $data['tags'];
            }


			$snippetRepository->updateSnippet($snippetId, $snippetData);

        } else {

			$snippetData = [
				'title' => $data['title'],
				'description' => isset($data['description']) ? $data['description'] : '',
				'priority' => 10,
				'enabled' => 0
            ];

            if($data['tags'] !== 'ex_js' && $data['tags'] !== 'ex_css') {
				$snippetData['code'] = $code;

            } else {

                $codeArr = [];

                if($data['tags'] === 'ex_js') {

                    $codeArr['code'] = "<script src=' " . $data['code']. "' ></script>";
                    $codeArr['externalUrl'] = $data['code'];

                } else if($data['tags'] === 'ex_css') {

                    $codeArr['code'] = '<link rel="stylesheet" href="' . $data['code'] . '"/>';
                    $codeArr['externalUrl'] = $data['code'];
                }

				$snippetData['code'] = json_encode($codeArr);
				$snippetData['externalUrl'] = $data['code'];

            }

			$snippetData['original_code'] = $data['code'];

            if (is_array($data['runType'])) {
				$snippetData['runType'] = $data['runType']['value'];
            } else {
				$snippetData['runType'] = $data['runType'];
             }

            if ($data['savedToCloud']) {
				$snippetData['savedToCloud'] = 1;
            }

            if ($data['id']) {
				$snippetData['remoteId'] = $data['remoteId'];
            }

            if (isset($data['tags'])) {
				$snippetData['codeType'] = $data['tags'];
            }

			$snippetData['priority'] = 10;

			$snippetData = HookMapper::getCorrectHook($data, $snippetData);

			$snippetId = $snippetRepository->createSnippet($snippetData);

        }


		$functionalityPlugin->saveSnippet($snippetId);

		echo json_encode(['post_id' => $snippetId]);
        die;
    }


}
