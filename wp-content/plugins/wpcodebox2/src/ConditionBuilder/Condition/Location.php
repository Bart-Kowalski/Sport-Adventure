<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;

class Location extends Condition
{

    const IS_EVERYWHERE = 0;
    const IS_FRONTEND = 1;
    const IS_ADMIN = 2;
    const IS_LOGIN = 3;

    public function is_satisfied()
    {
        $condition_verb = $this->conditionData->get_condition_verb();

        if($condition_verb['value'] === self::IS_EVERYWHERE) {
            return true;
        }

        if($condition_verb['value'] === self::IS_FRONTEND && $this->wordPressContext->is_frontend()) {
            return true;
        }

        if($condition_verb['value'] === self::IS_ADMIN && !$this->wordPressContext->is_frontend()) {
            return true;
        }

        if($condition_verb['value'] === self::IS_LOGIN && $this->wordPressContext->is_login()) {
            return true;
        }


        return false;
    }

	public function get_code()
	{
		$condition_verb = $this->conditionData->get_condition_verb();

		if($condition_verb['value'] === self::IS_EVERYWHERE) {
			return '$wpContext->is_everywhere()';
		}

		if($condition_verb['value'] === self::IS_FRONTEND) {
			return '$wpContext->is_frontend()';
		}

		if($condition_verb['value'] === self::IS_ADMIN) {
			return '!$wpContext->is_frontend()';
		}

		if($condition_verb['value'] === self::IS_LOGIN) {
			return '$wpContext->is_login()';
		}

		return '';
	}

}
