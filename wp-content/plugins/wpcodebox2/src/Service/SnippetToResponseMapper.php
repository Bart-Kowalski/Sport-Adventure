<?php

namespace Wpcb2\Service;

class SnippetToResponseMapper
{
    public function mapSnippetToResponse($snippet)
    {
		$snippet['id'] = intval($snippet['id']);

		$manager = new \Wpcb2\FunctionalityPlugin\Manager(false);
		$snippet['filePath'] = $manager->getSnippetPath($snippet);

		$snippet['savedToCloud'] = !!$snippet['savedToCloud'];
		$snippet['error'] = !!$snippet['error'];
		$snippet['minify'] = !!$snippet['minify'];
		$snippet['devMode'] = !!$snippet['devMode'];
		$snippet['enabled'] = !!$snippet['enabled'];
		$snippet['addToQuickActions'] = !!$snippet['addToQuickActions'];

		$snippet['order'] = intval($snippet['snippet_order']);


		if($snippet['hook']) {
			$snippet['hook'] = json_decode($snippet['hook'], true);
		} else {
			$snippet['hook'] = [];
		}

		if($snippet['tagOptions']) {
			$snippet['tagOptions'] = json_decode($snippet['tagOptions'], true);
			if(!$snippet['tagOptions']) {
				$snippet['tagOptions'] = [];
			}
		} else {
			$snippet['tagOptions'] = [];
		}

		$renderType = $snippet['renderType'];

		if ($renderType === 'external') {
			$renderTypeValue = [
				'label' => 'External File',
				'value' => 'external'
			];
		} else if ($renderType === 'none') {
			$renderTypeValue = [
				'label' => 'Do not render',
				'value' => 'none'
			];
		}
		else {
			$renderTypeValue = [
				'label' => 'Inline',
				'value' => 'inline'
			];
		}

		$snippet['renderType'] = $renderTypeValue;


		$codeType = $snippet['codeType'];

		$original_code = $snippet['original_code'];
		$code = $snippet['code'];

		if ($codeType === "php") {
			$codeType = [
				'value' => 'php',
				'label' => "PHP"
			];
		}

		if ($codeType === 'css') {
			$codeType = [
				'value' => 'css',
				'label' => "CSS"
			];

			$code = $original_code;

		}

		if ($codeType === 'scssp') {
			$codeType = [
				'value' => 'scssp',
				'label' => "SCSS Partial"
			];

			$code = $original_code;
		}

		if ($codeType === 'scss') {
			$codeType = [
				'value' => 'scss',
				'label' => "SCSS"
			];

			$code = $original_code;
		}

		if ($codeType === 'less') {
			$codeType = [
				'value' => 'less',
				'label' => "LESS"
			];

			$code = $original_code;
		}

		if ($codeType === 'js') {
			$codeType = [
				'value' => 'js',
				'label' => "JavaScript"
			];

			$code = $original_code;

			$codeData['tagOptions'] = $snippet['tagOptions'];

		}

		if ($codeType === 'html') {
			$codeType = [
				'value' => 'html',
				'label' => "HTML"
			];
		}

		if ($codeType === 'txt') {
			$codeType = [
				'value' => 'txt',
				'label' => "Plain Text"
			];
		}

		if ($codeType === 'ex_css') {
			$codeType = [
				'value' => 'ex_css',
				'label' => "CSS (External File)"
			];

			$codeData = json_decode($code, true);
		}



		if ($codeType === 'ex_js') {
			$codeType = [
				'value' => 'ex_js',
				'label' => "JavaScript (External File)"
			];
			$codeData = json_decode($code, true);
		}

		if ($codeType === 'json') {
			$codeType = [
				'value' => 'json',
				'label' => "JSON"
			];
		}


		$runType = $snippet['runType'];

		if (!$runType) {
			$runType = "always";
		}

		if ($runType == "once") {
			$runType = [
				'value' => "once",
				'label' => "Manual (On Demand)"
			];
		} else if ($runType == 'always') {
			$runType = [
				'value' => "always",
				'label' => "Always (On Page Load)"
			];
		} else if ($runType == 'never') {
			$runType = [
				'value' => "never",
				'label' => "Do not run"
			];
		} else if ($runType == 'external') {
			$runType = [
				'value' => "external",
				'label' => "Using external secure URL"
			];
		}

		$snippet['runType'] = $runType;

		$snippet['code'] = $code;


		$snippet['codeType'] = $codeType;
		if(isset($codeType['value'])) {
			$snippet['tags'] = $codeType['value'];
		} else {
			$snippet['tags'] = 'php';
			$snippet['codeType'] = [
				'value' => 'php',
				'label' => "PHP"
			];
		}

		if(isset($snippet['conditions'])) {
			$snippet['conditions'] = json_decode($snippet['conditions'], true);

			if(!$snippet['conditions']) {
				$snippet['conditions'] = [];
			}
		} else {
			$snippet['conditions'] = [];
		}


		$snippet['externalUrl'] = isset($codeData) && is_array($codeData) && isset($codeData['externalUrl']) ? $codeData['externalUrl'] : [];


		$snippet['secureUrl'] = get_site_url() . '?wpcb_token=' . $snippet['secret'];

		return $snippet;

    }

    /**
     * @param $codeType
     * @param $hookPriority
     * @param $location_value
     * @param $where_to_run
     * @return array
     */
    private function mapSettingsToHook($codeType, $hookPriority, $location_value, $where_to_run)
    {
        $hook = [];

        if ($codeType === "php") {
            $hook = [
                [
                    'hook' => ['label' => 'Plugins Loaded (Default)', 'value' => 'plugins_loaded'],
                    'priority' => $hookPriority
                ]
            ];
        } else {
            if ($location_value['value'] === 'header' && ($where_to_run === 'everywhere' || $where_to_run === 'custom' )) {

                $hook = [
                    ['hook' => ['label' => 'Frontend Header (Default)', 'value' => 'custom_frontend_header'], 'priority' => $hookPriority],
                    ['hook' => ['label' => 'Login Header', 'value' => 'custom_login_header'], 'priority' => $hookPriority],
                    ['hook' => ['label' => 'Admin Header', 'value' => 'custom_admin_header'], 'priority' => $hookPriority],
                ];
            } else if ($location_value['value'] === 'footer' && ($where_to_run === 'everywhere' || $where_to_run === 'custom')) {
                $hook = [
                    ['hook' => ['label' => 'Frontend Footer', 'value' => 'custom_frontend_footer'], 'priority' => $hookPriority],
                    ['hook' => ['label' => 'Login Footer', 'value' => 'custom_login_footer'], 'priority' => $hookPriority],
                    ['hook' => ['label' => 'Admin Footer', 'value' => 'custom_admin_footer'], 'priority' => $hookPriority]
                ];
            }


            if ($location_value['value'] === 'header' && $where_to_run === 'admin') {

                $hook = [
                    ['hook' => ['label' => 'Admin Header', 'value' => 'custom_admin_header'], 'priority' => $hookPriority],
                ];
            } else if ($location_value['value'] === 'footer' && $where_to_run === 'admin') {
                $hook = [
                    ['hook' => ['label' => 'Admin Footer', 'value' => 'custom_admin_footer'], 'priority' => $hookPriority]
                ];
            }

            if ($location_value['value'] === 'header' && $where_to_run === 'frontend') {

                $hook = [
                    ['hook' => ['label' => 'Frontend Header (Default)', 'value' => 'custom_frontend_header'], 'priority' => $hookPriority],
                ];
            } else if ($location_value['value'] === 'footer' && $where_to_run === 'frontend') {
                $hook = [
                    ['hook' => ['label' => 'Frontend Footer', 'value' => 'custom_frontend_footer'], 'priority' => $hookPriority],
                ];
            }
        }
        return $hook;
    }

}
