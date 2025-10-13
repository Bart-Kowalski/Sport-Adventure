<?php

namespace Wpcb2\Runner;


class PhpCode
{
    private $code;

    private $hook;

    private $priority;

    private $isNativePhp;

    public function __construct($code, $hook, $priority, $isNativePhp = false)
    {
        $this->code = $code;
        $this->hook = $hook;
        $this->priority = $priority;
        $this->isNativePhp = $isNativePhp;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getHook()
    {
        return $this->hook;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function isNativePhp()
    {
        return $this->isNativePhp();
    }
}
