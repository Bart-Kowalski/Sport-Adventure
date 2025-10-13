<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;
use Wpcb2\ConditionBuilder\WordPressContext;

class Time extends Condition
{
    const IS_BEFORE = 0;

    const IS_AFTER = 1;

    public function is_satisfied()
    {
        $time = time();

        $extra_data = $this->conditionData->get_extra_data();
        $extra_data = strtotime($extra_data['value']);
        $condition_verb = $this->conditionData->get_condition_verb();

        if($condition_verb['value'] === self::IS_BEFORE) {
            if($time < $extra_data) {
                return true;
            }

            return false;
        }

        if($condition_verb['value'] === self::IS_AFTER) {

            if($time > $extra_data) {
                return true;
            }

            return false;
        }

        return false;
    }

	public function get_code()
	{
		$condition_verb = $this->conditionData->get_condition_verb();
		$extra_data = $this->conditionData->get_extra_data();

		if($condition_verb['value'] === self::IS_BEFORE) {
			return 'time() < strtotime("' . $extra_data['value'] . '")';
		}

		if($condition_verb['value'] === self::IS_AFTER) {
			return 'time() > strtotime("' . $extra_data['value'] . '")';
		}

		return '';

	}

}
