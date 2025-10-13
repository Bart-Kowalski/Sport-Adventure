<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;
use Wpcb2\ConditionBuilder\WordPressContext;

class LoggedInUser extends Condition
{
    const IS = 0;
    const IS_NOT = 1;

    public function is_satisfied()
    {
        $logged_in_user_id = $this->wordPressContext->get_logged_in_user_id();

        if(!$logged_in_user_id) {
            return false;
        }

        $verb = $this->conditionData->get_condition_verb();

        if($verb['value'] === self::IS) {
            foreach($this->conditionData->get_extra_data() as $condition_data) {
                if($condition_data['value'] == $logged_in_user_id) {
                    return true;
                }
            }
        }

        if($verb['value'] === self::IS_NOT) {
            foreach($this->conditionData->get_extra_data() as $condition_data) {
                if($condition_data['value'] == $logged_in_user_id) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

	public function get_code()
	{
		$logged_in_users_to_check =[];

		foreach($this->conditionData->get_extra_data() as $condition_data) {
			$logged_in_users_to_check[] = $condition_data['value'];
		}

		$verb = $this->conditionData->get_condition_verb();

		if($verb['value'] === self::IS) {
			return '$wpContext->is_current_logged_in_user([' . implode(',', $logged_in_users_to_check). '])';
		}

		if($verb['value'] === self::IS_NOT) {
			return '!$wpContext->is_current_logged_in_user([' . implode(',', $logged_in_users_to_check). '])';
		}

		return '';
	}

}
