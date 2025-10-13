<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;
use Wpcb2\ConditionBuilder\WordPressContext;

class CustomPhp extends Condition
{

    public function is_satisfied()
    {
        $code = $this->conditionData->get_extra_data();
        $code = 'return ' . str_replace('<?php', '', $code['value']);

        $result = eval($code);

        return $result;
    }

	public function get_code()
	{
		$code = $this->conditionData->get_extra_data();
		$code = str_replace('<?php', '', $code['value']);
		$code = str_replace(';', '', $code);
		$code = trim($code);

		return "($code)";

	}
}
