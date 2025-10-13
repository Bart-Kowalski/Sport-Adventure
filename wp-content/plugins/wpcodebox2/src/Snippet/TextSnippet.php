<?php

namespace Wpcb2\Snippet;


class TextSnippet extends Snippet
{
    function getCode()
    {
		return "die(); __halt_compiler(); ?>\n" . $this->code;
    }

    private function getHookCode($hook, $conditionCode)
	{
		return '';
	}
}
