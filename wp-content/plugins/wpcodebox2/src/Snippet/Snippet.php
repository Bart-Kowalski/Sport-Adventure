<?php

namespace Wpcb2\Snippet;


use Wpcb2\ConditionBuilder\ConditionBuilder;
use Wpcb2\Repository\SnippetRepository;

abstract class Snippet
{

	protected $code;

	/** @var  GlobalCSS */
	protected $globalCSS;

	/** @var  GlobalJS */
	protected $globalJS;

	protected $snippetData;

	protected $isFp = false;

	public function __construct(GlobalCSS $globalCSS, GlobalJS $globalJS, $snippet, $isFp = false)
	{

		$this->code = $snippet['code'];

		$this->globalCSS = $globalCSS;
		$this->globalJS = $globalJS;
		$this->isFp = $isFp;
		$this->snippetData = $snippet;
	}

	public function getId()
	{
		return $this->snippetData['id'];
	}

	/**
	 * @return array
	 */
	protected function getHook()
	{

		$hooks = $this->snippetData['hook'];

		if(!$hooks) {
			return false;
		}

		$hooks = json_decode($hooks, true);

		if (is_array($hooks) && isset($hooks[0]['hook'])) {
			$response = [];
			foreach ($hooks as $hook) {
				$response[] = $this->processHook($hook);
			}

			return $response;
		} else {
			return [$this->processHook($hooks)];
		}

	}

	private function processHook($hookData)
	{
		if (isset($hookData['hook']) && is_array($hookData['hook'])) {
			$hook = $hookData['hook']['value'];
		} else if (isset($hookData['hook'])) {
			$hook = $hookData['hook'];
		}

		if (isset($hookData['value'])) {
			$hook = $hookData['value'];
		}


		if ($hook === 'custom_custom_action') {
			$hook = $hookData['customAction'];
		}

		$hook = $this->mapCustomHookToWPHooks($hook);

		if(!isset($hookData['priority'])) {
			$priority = 10;
		} else {
			$priority = (int) $hookData['priority'];
		}

		return array(
			'hook' => $this->mapCustomHookToWPHooks($hook),
			'priority' => $priority,
			'shortcode' => isset($hookData['shortcode']) ? $hookData['shortcode'] : '',
			'customAction' => isset($hookData['customAction']) ? $hookData['customAction'] : '');

	}

	public function disableSnippetAndLogError(\Throwable $e)
	{

        $snippetRepository = new SnippetRepository();

        $snippetRepository->updateSnippet($this->snippetData['id'], [
            'enabled' => false,
            'error' => 1,
            'errorMessage' => $e->getMessage(),
            'errorTrace' => $e->getTraceAsString(),
            'errorLine' => $e->getLine()-4
        ]);

		do_action('wpcb_snippet_disabled', $this->snippetData['id']);

	}

	public function getConditionCode()
	{
		if ($this->isFp) {
			$conditionCode = "{{WPCB_CONDITION_CODE}}";
		} else {
			$conditionCode = "if(!\Wpcb2\ConditionBuilder\ShouldExecute::should_execute(" . $this->snippetData['id'] . ")) { return false; }";
		}

		return $conditionCode;
	}

	abstract function getCode();

	public function getOriginalCode()
	{
		return $this->snippetData['original_code'];
	}

	/**
	 * @param $hook
	 * @return string
	 */
	protected function mapCustomHookToWPHooks($hook)
	{
		if ($hook == 'custom_frontend_header') {
			$hook = 'wp_head';
		}

		if ($hook == 'custom_login_header') {
			$hook = 'login_head';
		}

		if ($hook === 'custom_admin_header') {
			$hook = 'admin_head';
		}

		if ($hook === 'custom_frontend_footer') {
			$hook = 'wp_footer';
		}

		if ($hook == 'custom_login_footer') {
			$hook = 'login_footer';
		}

		if ($hook == 'custom_admin_footer') {
			$hook = 'admin_footer';
		}

		if ($hook == 'custom_plugins_loaded') {
			$hook = 'plugins_loaded';
		}

		if ($hook == 'root') {
			$hook = 'custom_root';
		}
		return $hook;
	}

	public function isEnabled()
	{
		return $this->snippetData['enabled'];
	}

	public function getTitle()
	{
		return $this->snippetData['title'];
	}

	public function getMainFileName()
	{
		if($this->isAsset() && $this->getRenderType() === 'external') {
			return $this->getFileNameWithoutExtension() . '_enqueue_' . $this->getFileExtension(). '.php';
		}

		if($this->isAsset() && $this->getRenderType() === 'inline') {
			return $this->getFileNameWithoutExtension() . '_inline_' . $this->getFileExtension(). '.php';
		}

		return $this->getFileNameWithoutExtension() . '.php';

	}
	public function getFileName()
	{

		$extension = $this->getFileExtension();

		$fileName = $this->getFileNameWithoutExtension();

		return $fileName . '.' . $extension;
	}

	public function getFileNameWithoutExtension()
	{

		$title = $this->snippetData['title'];

		$title = $this->slugify($title);


		if($this->snippetData['codeType'] === 'scssp') {
			$title = '_' . $title;
		}

		if($this->snippetData['folderId'] && isset($this->snippetData['folder']['name'])) {
			return $this->slugify($this->snippetData['folder']['name']) . DIRECTORY_SEPARATOR . $title;
		}

		return $title;
	}


	public function getFileExtension()
	{
		$extension = $this->snippetData['codeType'];


		if($extension === 'scss') {
			$extension = 'css';
		}

			if($extension === 'scssp') {
			$extension = 'scss';
		}


		if($extension === 'ex_css' || $extension === 'ex_js') {
			$extension = 'php';
		}
		if($this->snippetData['codeType'] === 'txt') {
			$extension = 'txt.php';
		}

		return $extension;
	}

	public function getFolderName() {

		if($this->snippetData['folderId']) {
			return $this->slugify($this->snippetData['folder']['name']);
		}

		return false;
	}

	public function slugify($text, string $divider = '_')
	{
		// replace non letter or digits by divider
		$text = preg_replace('~[^\pL\d]+~u', $divider, $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, $divider);

		// remove duplicate divider
		$text = preg_replace('~-+~', $divider, $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text)) {
			return 'n-a';
		}

		return $text;
	}

	public function shouldAddToMainPluginFile()
	{
		if(isset($this->snippetData['renderType']) && $this->snippetData['renderType'] === 'none') {
			return false;
		}

		if($this->snippetData['codeType'] === 'php' && $this->snippetData['runType'] === 'never') {
			return false;
		}

		if($this->snippetData['codeType'] === 'json') {
			return false;
		}

		if($this->snippetData['codeType'] === 'scssp') {
			return false;
		}

		if($this->snippetData['codeType'] === 'txt') {
			return false;
		}
		// Don't add run manual snippets to the main file
		if($this->snippetData['codeType'] === 'php' && $this->snippetData['runType'] === 'once') {
			return false;
		}

		return true;
	}

	public function isAsset()
	{
		return ($this instanceof StyleSnippet && $this->snippetData['codeType'] !== 'scssp') || $this instanceof JsSnippet;
	}

	public function getFPConditionCode()
	{
		$conditions = json_decode($this->snippetData['conditions'], true);

		if ($conditions) {
			$conditionBuilder = new ConditionBuilder($conditions);

			return $conditionBuilder->get_code();
		}

		return '';

	}

	public function getCompiledCode() {
		return $this->snippetData['code'];
	}

	public function getCodeType() {
		return $this->snippetData['codeType'];
	}

	public function getRenderType() {
		return $this->snippetData['renderType'];
	}

	public function getRunType() {
		if(isset($this->snippetData['runType'])) {
			return $this->snippetData['runType'];
		} else {
			return false;
		}
	}

	public function getFiles()
	{
		return [
			'snippets/' . $this->getFileName()
		];
	}

	public function getSignature() {
		return sha1($this->snippetData['code'] . $this->snippetData['original_code'] .  $this->snippetData['conditions']);
	}

}
