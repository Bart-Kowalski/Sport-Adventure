<?php

namespace Wpcb2\Runner;

use Wpcb2\Service\ErrorParser;
use Wpcb2\Snippet\GlobalCSS;
use Wpcb2\Snippet\GlobalJS;
use Wpcb2\Snippet\SnippetFactory;

class QueryRunner
{
	public function __construct()
	{
		register_shutdown_function([$this, 'shutdown']);
		\add_filter('wp_php_error_message', [$this, 'getErrorMessage'], 10, 2);
	}


	public function shutdown()
	{
		$error = error_get_last();
		$errorParser = new ErrorParser();
		$errorParser->parseError($error);

	}

	public function getErrorMessage($message, $error)
	{
		if (function_exists('current_user_can') && function_exists('wp_get_current_user')) {
			if (current_user_can('manage_options')) {

				if (strpos($error['file'], 'QueryRunner.php') === false && strpos($error['message'], 'QueryRunner.php') === false) {
					return $message;
				}
				$safeModeUrl = get_admin_url(null, 'admin.php?page=wpcodebox2&safe_mode=1');

				$customMessage = <<<EOD
                <p>
                    If you recently made changes to a <strong>WPCodeBox snippet</strong> and you think that's what
                    causes the issue, here's
                    what you can do:
                </p>

                <p>Refresh this page, WPCodeBox might have already disabled the snippet causing this error</p>
				<p>If that didn't happen, yhou can open WPCodeBox in safe mode by following this link:
				<br/>
					<a href="$safeModeUrl" target="_blank">Open WPCodeBox in Safe Mode</a>
				</p>
                <p>If you still see this error, you can add the following line to your wp-config.php file:
                    <br/>
                    <br/>
                    <code>
                        define('WPCB_SAFE_MODE', true);
                    </code>

                    <br/>
                    <br/>
                    This will disable snippet execution, so you can fix the problem. Don't forget to remove that line
                    from
                    your wp-config.php file once
                    the problem is fixed.

                    <br/><br/>
                    You can read more about the WPCodeBox Safe Mode here: <a
                            href="https://docs.wpcodebox.com/safe_mode"
                            target="_blank">https://docs.wpcodebox.com/safe_mode</a>


                    <br/>
                    <br/>
                    Don't forget that you should <strong>always test WPCodeBox snippets in a separate tab</strong>,
                    because WPCodeBox will still work if you don't refresh the editor page.
                </p>

                <i><strong>This message is only visible to website admins.</strong></i>
EOD;

				return $message . $customMessage;
			}
		}


		return $message;
	}

	public function runQueries()
	{

		$snippetsRepository = new \Wpcb2\Repository\SnippetRepository();
		$snippets = $snippetsRepository->getSnippetsToExecute();

		$shouldExecuteService = new \Wpcb2\ConditionBuilder\ShouldExecute();

		$globalCSS = new GlobalCSS();
		$globalJS = new GlobalJS();

		foreach ($snippets as $snippet) {

			$snippetId = $snippet['id'];

			if (in_array($snippet['runType'], ['external', 'manual'])) {
				continue;
			}

			if ($snippet['codeType'] === 'scssp') {
				continue;
			}

			$should_execute = $shouldExecuteService->shouldExecutePreCheck($snippet);

			if (!$should_execute) {
				continue;
			}

			$snippetFactory = new SnippetFactory($globalCSS, $globalJS, $snippet);
			$snippet = $snippetFactory->createInternalSnippet(false);

			$code = $snippet->getCode();

			do_action('wpcb/before_snippet_run', $snippetId);

			\Wpcb2CurrentSnippet::$currentSnippet = $snippetId;

			if (strnatcmp(phpversion(), '7.0.0') >= 0) {
				try {
					eval($code);

				} catch (\Throwable $e) {
					$snippet->disableSnippetAndLogError($e);
					header('Location: ' . $_SERVER['REQUEST_URI']);
				}
			} else {

				global $errorSnippetId;

				$errorSnippetId = $snippetId;
				add_filter('wp_php_error_message', 'wpcb2_error_handler', 1);
				eval($code);
				remove_filter('wp_php_error_message', 'wpcb2_error_handler', 1);
			}

			do_action('wpcb/after_snippet_run', $snippetId);
		}

		$globalJS->output();
		$globalCSS->output();
	}
}
