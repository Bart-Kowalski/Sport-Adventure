<?php

namespace Wpcb2\Service;


use Wpcb2\Repository\SnippetRepository;

/**
 * Class ErrorParser
 * @package Wpcb\Service
 */
class ErrorParser
{
	const ERROR_LINE_OFFSET = 4;

    public function parseError($error)
    {
        if(!is_array($error)) {
            return false;
        }

        // If it's not a WPCB error, bail
        if(strpos($error['file'], 'QueryRunner.php') === false && strpos($error['message'], 'QueryRunner.php') === false) {
            return false;
        }

        if(strpos($error['message'], 'Call to undefined function')) {
            return $this->handleUndefinedFunctionError($error);
        }


        if(strpos($error['message'], 'Cannot redeclare') !== false) {

            if(strpos($error['file'], 'QueryRunner.php') !== false) {
                $this->handleFunctionAlreadyDefinedExternally($error);
            }

            if(strpos($error['file'], 'QueryRunner.php') === false && strpos($error['message'], 'QueryRunner.php') !== false) {
                $this->handleFunctionAlreadyDefinedInWPCB($error);
            }

        }

        return true;
    }

    private function handleUndefinedFunctionError($error)
    {
        $matches = [];
        preg_match_all('/undefined function (.*)\(\) in/', $error['message'], $matches);
        $function_name = $matches[1][0];

        $this->findAndDisableSnippetsThatDefineFunctionAtLine($function_name, $error['line'] - self::ERROR_LINE_OFFSET, $error, 'Call to undefined function' );

        return true;
    }

    private function handleFunctionAlreadyDefinedInWPCB($error)
    {
        $matches = [];
        preg_match_all('/Cannot redeclare (.*)\(\) \(previously declared in.*eval\(\)\'d code:([0-9]+)/', $error['message'], $matches);

        $function_name = $matches[1][0];
        $line = $matches[2][0];

        $error['line'] = $line - 1;
        $this->findAndDisableSnippetsThatDefineFunctionAtLine($function_name, $error['line'] - self::ERROR_LINE_OFFSET, $error, 'Function already defined' );

        return true;
    }

    private function handleFunctionAlreadyDefinedExternally($error)
    {
        $matches = [];
        preg_match_all('/Cannot redeclare (.*)\(\) \(previously declared in/', $error['message'], $matches);

        $function_name = $matches[1][0];

        $this->findAndDisableSnippetsThatDefineFunctionAtLine($function_name, $error['line'] - self::ERROR_LINE_OFFSET, $error, 'Function already defined' );

        return true;
    }

    public function disableSnippetAndLogError($snippetId, $error, $message)
    {

		$snippetRepository = new SnippetRepository();

		$snippetRepository->updateSnippet($snippetId,
			[
				'enabled' => 0,
				'error' => 1,
				'errorMessage' => $message,
				'errorTrace' => $error['message'],
				'errorLine' => $error['line'] - self::ERROR_LINE_OFFSET
			]
		);


        do_action('wpcb_snippet_disabled', $snippetId);

    }

    /**
     * @param $error
     * @param $wpdb
     * @param $function_name
     */
    private function findAndDisableSnippetsThatDefineFunctionAtLine($function_name, $function_line, $error, $message)
    {
		return true;

		$snippetRepository = new SnippetRepository();

		$results = $snippetRepository->getSnippetsThatDefineFunction($function_name);
		if(is_array($results) && count($results) > 0) {
			foreach ($results as $snippet) {

				if (is_array($snippet) && isset($snippet['enabled']) && $snippet['enabled']) {
					$lines = explode("\n", $snippet['code']);
					foreach ($lines as $lineNumber => $line) {
						if (strpos($line, $function_name . '(') !== false) {
							if ($lineNumber + 1 === $function_line) {
								$this->disableSnippetAndLogError($snippet['id'], $error, $message);
							}
						}
					}
				}
			}
		}
    }

}
