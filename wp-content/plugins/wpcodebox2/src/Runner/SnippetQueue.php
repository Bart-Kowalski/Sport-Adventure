<?php


namespace Wpcb2\Runner;


//TODO: See if this queue is really needed
class SnippetQueue
{
    /** @var PhpCode[][] */
    private $phpSnippets = [];

    public function addPhpCode(PhpCode $phpCode) {

        $this->phpSnippets[$phpCode->getHook()."-".$phpCode->getPriority()][] = $phpCode;
    }

    public function getPhp()
    {
        $code = '';

        foreach($this->phpSnippets as $hookPriority => $phpCodes) {

            $hookPriority = explode("-", $hookPriority);
            $hook = $hookPriority[0];
            $priority = $hookPriority[1];

            $code = "add_action('$hook', function() { \n";


            foreach($phpCodes as $phpCode) {

                if ($phpCode->isNativePhp()) {
                    $code .= "\n" . $phpCode->getCode();
                } else {
                    $code .= "\n ?> ". $phpCode->getCode(). " <?php ";
                }

            }

            $code .= " }, $priority);";
        }

        return $code;
    }
}
