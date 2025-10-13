<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;
use Wpcb2\ConditionBuilder\WordPressContext;

class DayOfWeek extends Condition
{

	const IS = 0;
	const IS_NOT = 1;

	public function is_satisfied()
	{
		$condition_verb = $this->conditionData->get_condition_verb();

		if ($condition_verb['value'] === self::IS) {
			foreach ($this->conditionData->get_extra_data() as $value) {
				if ($value['value'] == $this->wordPressContext->get_day_of_week()) {
					return true;
				}
			}

			return false;
		}

		if ($condition_verb['value'] === self::IS_NOT) {
			foreach ($this->conditionData->get_extra_data() as $value) {
				if ($value['value'] == $this->wordPressContext->get_day_of_week()) {
					return false;
				}
			}

			return true;
		}

		return false;
	}

	public function get_code()
	{
		$code = '';
		$days_of_week = [];
		$condition_verb = $this->conditionData->get_condition_verb();

		foreach ($this->conditionData->get_extra_data() as $value) {
			$days_of_week[] = $value['value'];
		}

		if ($condition_verb['value'] === self::IS) {
			$code = '$wpContext->is_day_of_week(['.implode(",", $days_of_week).'])';

		}
		if ($condition_verb['value'] === self::IS_NOT) {
			$code = '!$wpContext->is_day_of_week(['.implode(",", $days_of_week).'])';
		}

		return $code;
	}

}
