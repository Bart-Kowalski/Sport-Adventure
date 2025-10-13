<?php

namespace Wpcb2\Snippet;


class SnippetData
{

    protected $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function get($meta)
    {
		if(!isset($this->data[$meta])) {
			return false;
		}


		return $this->data[$meta];
	}
}
