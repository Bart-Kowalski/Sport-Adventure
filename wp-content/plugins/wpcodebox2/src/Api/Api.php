<?php

namespace Wpcb2\Api;

use Wpcb2\FunctionalityPlugin\Manager;
use Wpcb2\Repository\SnippetRepository;
use Wpcb2\Service\ExternalFile;
use Wpcb2\Service\HookMapper;
use Wpcb2\Service\Minify\MinifyFactory;
use Wpcb2\Service\SCSSUpdater;

class Api
{

	public function createSnippet($data)
	{
		$error = false;

		try {
			$compiler = new \Wpcb2\Compiler();
			$code = $compiler->compileCode($data['code'], $data['codeType']['value']);

		} catch (\ScssPhp\ScssPhp\Exception\SassException $e) {
			$code = '';

			$error = $e->getMessage();
		}

		if (isset($data['minify']) && $data['minify']) {
			$minifyFactory = new MinifyFactory();
			$minifyService = $minifyFactory->createMinifyService($data['codeType']['value']);
			$code = $minifyService->minify($code);
		}

		if ($data['title'] == '') {
			$data['title'] = 'Untitled';
		}

		if (!isset($data['priority'])) {
			$data['priority'] = 10;
		}

		if (!isset($data['conditions'])) {
			$data['conditions'] = [];
		}

		if (!isset($data['hook'])) {
			$data['hook'] = HookMapper::getDefaultHooks($data['codeType']['value']);
		}

		$snippetData = [
			'title' => $data['title'],
			'description' => isset($data['description']) ? $data['description'] : '',
			'priority' => $data['priority'],
			'runType' => $data['runType']['value'],
			'original_code' => $data['code'],
			'codeType' => $data['codeType']['value'],
			'conditions' => $data['conditions'],
			'location' => is_array($data['location']) ? $data['location']['value'] : '',
			'hook' => $data['hook'],
			'snippet_order' => -1
		];


		if ($data['codeType']['value'] === 'php') {
			$token = openssl_random_pseudo_bytes(16);
			$token = bin2hex($token);
			$token = sha1(uniqid() . wp_salt() . $token);

			$snippetData['secret'] = $token;
		}

		if ($data['codeType']['value'] !== 'ex_js' && $data['codeType']['value'] !== 'ex_css') {
			$snippetData['code'] = $code;
		} else {

			$codeArr = [];

			if ($data['codeType']['value'] === 'ex_js') {

				$tagOptions = "";
				foreach ($data['tagOptions'] as $value) {
					if (isset($value['value'])) {
						if ($value['value'] === 'async') {
							$tagOptions .= " async ";
						} else if ($value['value'] === 'defer') {
							$tagOptions .= " defer ";
						}
					}
				}

				$codeArr['code'] = "<script " . $tagOptions . " src='" . $data['externalUrl'] . "' ></script>";
				$codeArr['tagOptions'] = $data['tagOptions'];
				$codeArr['externalUrl'] = $data['externalUrl'];

			} else if ($data['codeType']['value'] === 'ex_css') {

				$codeArr['code'] = '<link rel="stylesheet" href="' . $data['externalUrl'] . '"/>';
				$codeArr['externalUrl'] = $data['externalUrl'];
			}

			$snippetData['code'] = json_encode($codeArr);
		}

		if (isset($data['tagOptions'])) {
			$snippetData['tagOptions'] = $data['tagOptions'];
		}

		if (isset($data['renderType']) && is_array($data['renderType'])) {
			$snippetData['renderType'] = $data['renderType']['value'];
		}
		if (isset($data['minify'])) {
			$snippetData['minify'] = $data['minify'];
		}

		$snippetData['lastModified'] = time();

		$snippetRepository = new SnippetRepository();

		$snippetData['title'] = $snippetRepository->getUniqueTitle($snippetData['title'], 0);
		$id = $snippetRepository->createSnippet($snippetData);

		if (isset($data['renderType']) && is_array($data['renderType']) && $data['renderType']['value'] === 'external') {

			$extension = $data['codeType']['value'];
			if ($extension === 'scss' || $extension === 'less') {
				$extension = 'css';
			}

			$externalFileService = new ExternalFile();
			$externalFileService->writeContentToFile($id . '.' . $extension, $code);
		}

		$functionalityPlugin = new Manager(false);

		if ($functionalityPlugin->isEnabled()) {
			$functionalityPlugin->saveSnippet($id);

		}

		if ($error) {
			throw new \Exception($error);
		}

		return $id;
	}

	public function updateSnippet($id, $data)
	{

		$error = false;

		$functionalityPlugin = new Manager(false);

		try {
			$compiler = new \Wpcb2\Compiler();
			$code = $compiler->compileCode($data['code'], $data['codeType']['value']);

		} catch (\ScssPhp\ScssPhp\Exception\SassException $e) {
			$code = '';

			$error = $e->getMessage();
		}
		if (isset($data['minify']) && $data['minify']) {
			$minifyFactory = new MinifyFactory();
			$minifyService = $minifyFactory->createMinifyService($data['codeType']['value']);
			$code = $minifyService->minify($code);
		}
		if ($data['title'] === '') {
			$data['title'] = 'Untitled';
		}

		$snippetRepository = new SnippetRepository();

		$snippetData = [
			'title' => $data['title'],
			'description' => isset($data['description']) ? $data['description'] : '',
			'priority' => $data['priority'],
			'runType' => $data['runType']['value'],
			'original_code' => $data['code'],
			'codeType' => $data['codeType']['value'],
			'conditions' => $data['conditions'],
			'location' => is_array($data['location']) ? $data['location']['value'] : '',
			'hook' => $data['hook']
		];

		if ($data['codeType']['value'] !== 'ex_js' && $data['codeType']['value'] !== 'ex_css') {

			$snippetData['code'] = $code;

		} else {

			$codeArr = [];

			if ($data['codeType']['value'] === 'ex_js') {

				$tagOptions = "";
				foreach ($data['tagOptions'] as $value) {
					if (isset($value['value'])) {
						if ($value['value'] === 'async') {
							$tagOptions .= " async ";
						} else if ($value['value'] === 'defer') {
							$tagOptions .= " defer ";
						}
					}
				}

				$codeArr['code'] = "<script " . $tagOptions . " src='" . $data['externalUrl'] . "'></script>";
				$codeArr['tagOptions'] = $data['tagOptions'];
				$codeArr['externalUrl'] = $data['externalUrl'];

			} else if ($data['codeType']['value'] === 'ex_css') {

				$codeArr['code'] = '<link rel="stylesheet" href="' . $data['externalUrl'] . '"/>';
				$codeArr['externalUrl'] = $data['externalUrl'];
			}

			$snippetData['code'] = json_encode($codeArr);
		}
		if (isset($data['renderType']) && is_array($data['renderType'])) {
			$snippetData['renderType'] = $data['renderType']['value'];
		}
		if (isset($data['minify'])) {
			$snippetData['minify'] = $data['minify'];
		}

		if (isset($data['addToQuickActions'])) {
			$snippetData['addToQuickActions'] = $data['addToQuickActions'];
		}

		if (isset($data['saved_to_cloud']) && $data['saved_to_cloud']) {
			$snippetData['saved_to_cloud'] = $data['saved_to_cloud'];
		}

		if (isset($data['tagOptions'])) {
			$snippetData['tagOptions'] = $data['tagOptions'];
		}

		if (isset($data['externalUrl'])) {
			$snippetData['externalUrl'] = $data['externalUrl'];
		}

		$snippetData['lastModified'] = time();

		$functionalityPlugin->deleteSnippet($id);

		$snippetRepository->updateSnippet($id, $snippetData);

		try {
			$errorPost = false;
			// Recompile the code that uses this partial
			if ($data['codeType']['value'] === 'scssp') {


				$snippetsThatUsePartial = $snippetRepository->getSnippetsThatUsePartial($data['title']);

				if (is_array($snippetsThatUsePartial) && count($snippetsThatUsePartial) > 0) {
					foreach ($snippetsThatUsePartial as $snippetThatUsePartial) {

						$errorPost = $snippetThatUsePartial['title'];

						if ($snippetThatUsePartial['codeType'] === 'scss') {
							$scssUpdater = new SCSSUpdater();
							$scssUpdater->recompileCode($snippetThatUsePartial);
						}
					}
				}
			}
		} catch (\ScssPhp\ScssPhp\Exception\SassException $e) {
			$code = '';
			$error = $e->getMessage() . ' in SCSS Snippet: ' . $errorPost;
		}

		$externalFileService = new ExternalFile();

		if (isset($data['renderType']) && is_array($data['renderType']) && $data['renderType']['value'] === 'external') {
			$extension = $data['codeType']['value'];
			if ($extension === 'scss' || $extension === 'less') {
				$extension = 'css';
			}
			$externalFileService->writeContentToFile($id . '.' . $extension, $code);
		} else {
			$externalFileService->deleteFile($id);
		}


		$functionalityPlugin->saveSnippet($id);

		if ($error) {
			throw new \Exception($error);
		}
	}

	public function runSnippet($snippetId, $return = false)
	{
        if($return) {
            ob_start();
        }
		$snippetRepository = new SnippetRepository();
		$snippet = $snippetRepository->getSnippet($snippetId);

		if(!$snippet) {
			return false;
		}

		$code = $snippet['code'];
		$pos = strpos($code, '<?php');
		if ($pos !== false) {
			$code = substr_replace($code, '', $pos, strlen('<?php'));
		}

		eval($code);

        if($return) {
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }

		return true;
	}

	public function deleteSnippet($id)
	{
		$externalFileService = new \Wpcb2\Service\ExternalFile();
		$externalFileService->deleteFile($id);

		$fp = new \Wpcb2\FunctionalityPlugin\Manager(false);
		$fp->deleteSnippet($id);

		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();
		$snippetRepository->deleteSnippet($id);
	}
}
