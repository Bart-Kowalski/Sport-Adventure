<?php

namespace Wpcb2\ConditionBuilder;


abstract class Condition
{
    /**
     * @var WordPressContext
     */
    protected $wordPressContext;

    /**
     * @var ConditionData
     */
    protected $conditionData;

    public function __construct(WordPressContext $wordPressContext, ConditionData $conditionData)
    {

        $this->wordPressContext = $wordPressContext;
        $this->conditionData = $conditionData;
    }

    abstract function is_satisfied();

	abstract function get_code();

}
