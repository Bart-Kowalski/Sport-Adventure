<?php


namespace Wpcb2\Actions;


use Wpcb2\Repository\SnippetRepository;
use Wpcb2\Service\HookMapper;

class CreateFolderFromCloud
{
	public function execute()
	{
		$data = file_get_contents("php://input");
		$data = json_decode($data, true);

		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();


		$folder = $snippetRepository->findFolderByRemoteId($data['id']);

		if($folder) {

			$local_folder_id = $folder['id'];

			if(isset($data['children']) && is_array($data['children'])) {
				foreach ($data['children'] as $child) {
					$this->processLocalSnippet($child, $local_folder_id);
				}
			}
		} else {

			$folderId = $snippetRepository->createFolder(
				[
					'name' => $data['title'],
					'remoteId' => $data['id']
				]
			);

			if(isset($data['children']) && is_array($data['children'])) {
				foreach ($data['children'] as $child) {
					$this->processLocalSnippet($child, $folderId);
				}
			}
		}

		echo json_encode(['folder_id' => $folderId]);
		die;
	}

	/**
	 * @param $post_id
	 * @param $data
	 */
	private function updateSnippet($snippetId, $data)
	{
		if (isset($data['tags'])) {

			$compiler = new \Wpcb2\Compiler();
			$code = $compiler->compileCode($data['code'], $data['tags']);
		} else {
			$code = $data['code'];
		}

		$snippetRepository = new SnippetRepository();

		$snippetData = [
			'title' => $data['title'],
			'description' => isset($data['description']) ? $data['description'] : '',
			'code' => $code,
		];

		if (is_array($data['runType'])) {
			$snippetData['runType'] = $data['runType']['value'];
		} else {
			$snippetData['runType'] = $data['runType'];
		}

		$snippetData['enabled'] = false;

		if(isset($data['tags'])) {
			$snippetData['codeType'] = $data['tags'];
		}


		if($data['id']) {
			$snippetData['savedToCloud'] = true;
			$snippetData['remoteId'] = $data['id'];
		}

		HookMapper::getCorrectHook($data, $snippetData);

		$snippetRepository->updateSnippet($snippetId,
			$snippetData);

	}


	private function createSnippet($data, $folder_id = false)
	{
		if (isset($data['tags'])) {

			$compiler = new \Wpcb2\Compiler();
			$code = $compiler->compileCode($data['code'], $data['tags']);
		} else {
			$code = $data['code'];
		}


		$snippetRepository = new SnippetRepository();

		$snippetData = [
			'title' => $data['title'],
			'description' => isset($data['description']) ? $data['description'] : '',
			'code' => $code,
			'original_code' => $data['code'],
			'enabled'=> false,
		];

		if($folder_id) {
			$snippetData['folderId'] = $folder_id;
		}

		if (is_array($data['runType'])) {
			$snippetData['runType'] = $data['runType']['value'];
		} else {
			$snippetData['runType'] = $data['runType'];
		}

		if (isset($data['tags'])) {
			$snippetData['codeType'] = $data['tags'];
		}

		if($data['id']) {
			$snippetData['savedToCloud'] = true;
			$snippetData['remoteId'] = $data['id'];
		}

		$snippetData['priority'] = 10;

		$snippetData = HookMapper::getCorrectHook($data, $snippetData);

		$snippetId = $snippetRepository->createSnippet($snippetData);

		return $snippetId;
	}

	/**
	 * @param $data
	 * @param $folder_already_exists_query
	 * @param $child
	 */
	private function processLocalSnippet($data, $localFolderId)
	{
		$snippetRepository = new SnippetRepository();

		$snippet = $snippetRepository->findSnippetByRemoteId($data['id']);

		if ($snippet) {

			$existingSnippetId = $snippet['id'];
			$this->updateSnippet($existingSnippetId, $data);

		} else {

			$this->createSnippet($data, $localFolderId);

		}
	}

	private function getDefaultHooks($codeType)
	{
		if ($codeType === 'php') {
			$hook = [
				['hook' => [
					'label' => 'Plugins Loaded (Default)',
					'value' => 'custom_plugins_loaded'
				]
				]];
		} else {
			$hook = [
				['hook' => [
					'label' => 'Frontend Header (Default)',
					'value' => 'custom_frontend_header'
				]
				]];
		}

		return $hook;
	}
}
