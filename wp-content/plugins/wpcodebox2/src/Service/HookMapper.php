<?php

namespace Wpcb2\Service;

class HookMapper
{
	public static function getCorrectHook($data, $snippetData)
	{
		$codeType = $data['tags'];

		if (isset($data['hook'])) {

			// It's a 2.0 snippet
			if (is_array($data['hook']) && isset($data['hook'][0]['hook']['value'])) {
				$snippetData['hook'] = $data['hook'];
			} else if (is_array($data['hook']) && isset($data['hook']['value'])) {
				if ($codeType === 'php') {
					$hookData = $data['hook'];
					$hookData['priority'] = isset($data['hookPriority']) && $data['hookPriority'] ? $data['hookPriority'] : 10;
					$snippetData['hook'] = [['hook' => $hookData]];
				} else {
					$snippetData['hook'] = self::getDefaultHooks($codeType);
				}
			} else {
				$snippetData['hook'] = self::getDefaultHooks($codeType);
			}

		} else {
			$snippetData['hook'] = self::getDefaultHooks($codeType);
		}
		return $snippetData;
	}

	public static function getDefaultHooks($codeType)
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
