<?php

namespace Wpcb2\Snippet;


class SnippetFactory
{
    /** @var  GlobalCSS */
    private $globalCSS;

    /**
     * @var GlobalJS
     */
    private $globalJS;

    protected $snippetData;

    public function __construct(GlobalCSS $globalCSS, GlobalJS $globalJS, $snippet)
    {
        $this->globalCSS = $globalCSS;
        $this->globalJS = $globalJS;
        $this->snippetData = $snippet;
    }

    public function createInternalSnippet($isFp = false)
    {

		$codeType = $this->snippetData['codeType'];

        if (in_array($codeType, ['css', 'scss', 'less', 'scssp'])) {

            return new StyleSnippet($this->globalCSS, $this->globalJS, $this->snippetData, $isFp);

        } else if ($codeType === 'php') {

            return new PhpSnippet($this->globalCSS, $this->globalJS, $this->snippetData, $isFp);

        } else if ($codeType === 'js') {

            return new JsSnippet($this->globalCSS, $this->globalJS, $this->snippetData, $isFp);

        } else if ($codeType === 'html') {

            return new HtmlSnippet($this->globalCSS, $this->globalJS, $this->snippetData, $isFp);

        } else if ($codeType === 'ex_css') {

            return new ExternalCSS($this->globalCSS, $this->globalJS, $this->snippetData, $isFp);

        } else if ($codeType == 'ex_js') {
            return new ExternalJS($this->globalCSS, $this->globalJS, $this->snippetData, $isFp);

        } else if ($codeType == 'txt') {
			return new TextSnippet($this->globalCSS, $this->globalJS, $this->snippetData, $isFp);
		} else if ($codeType === 'json') {
			return new JsonSnippet($this->globalCSS, $this->globalJS, $this->snippetData, $isFp);
		}
    }
}
