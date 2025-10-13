<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;
use Wpcb2\ConditionBuilder\WordPressContext;

class PageUrl extends Condition
{
    const CONTAINS = 0;
    const NOT_CONTAINS = 1;

	const IS = 2;

    function is_satisfied()
    {
        $current_url = $this->wordPressContext->get_current_url();
        $verb = $this->conditionData->get_condition_verb();
        $extra_data = $this->conditionData->get_extra_data();

        if($verb['value'] === self::CONTAINS) {
            if (strpos($current_url, $extra_data['value']) !== false) {
                return true;
            }
        }

        if($verb['value'] === self::NOT_CONTAINS) {
            if(strpos($current_url, $extra_data['value']) === false) {
                return true;
            }
        }

		if($verb['value'] === self::IS) {
			if($current_url === $extra_data['value']) {
				return true;
			}
		}

        return false;
    }

	public function get_code()
	{
		$verb = $this->conditionData->get_condition_verb();
		$extra_data = $this->conditionData->get_extra_data();

		if($verb['value'] === self::CONTAINS) {
			return '$wpContext->current_url_contains("' . $extra_data['value'] . '")';
		}

		if($verb['value'] === self::NOT_CONTAINS) {
			return '!$wpContext->current_url_contains("' . $extra_data['value'] . '")';
		}

		if($verb['value'] === self::IS) {
			return '$wpContext->current_url_is("' . $extra_data['value'] . '")';
		}

		return '';

	}
}
