<?php

namespace Wpcb2\Snippet;


class JsonSnippet extends Snippet
{
    function getCode()
    {
		return $this->code;
    }

    private function getHookCode($hook, $conditionCode)
	{
		return '';
	}
}
