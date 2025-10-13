<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;
use Wpcb2\ConditionBuilder\WordPressContext;

class LoggedInUserRole extends Condition
{
    const IS = 0;
    const IS_NOT = 1;

    public function is_satisfied()
    {
        $logged_in_user_roles = $this->wordPressContext->get_logged_in_user_roles();

        $verb = $this->conditionData->get_condition_verb();

        if($verb['value'] === self::IS) {
            foreach($this->conditionData->get_extra_data() as $condition_data) {

                if(in_array($condition_data['value'], $logged_in_user_roles)) {
                    return true;
                }
            }
        }

        if($verb['value'] === self::IS_NOT) {
            foreach($this->conditionData->get_extra_data() as $condition_data) {
                if(in_array($condition_data['value'], $logged_in_user_roles)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

	public function get_code()
	{
		$logged_in_user_roles_to_check = [];
		foreach ($this->conditionData->get_extra_data() as $condition_data) {
			$logged_in_user_roles_to_check[] = $condition_data['value'];
		}

		$verb = $this->conditionData->get_condition_verb();

		$logged_in_user_roles_to_check = array_map(function($role) {
			return "'$role'";
		}, $logged_in_user_roles_to_check);

		if($verb['value'] === self::IS) {
			return '$wpContext->is_current_logged_in_user_role([' . implode(',', $logged_in_user_roles_to_check). '])';
		}

		if($verb['value'] === self::IS_NOT) {
			return '!$wpContext->is_current_logged_in_user_role([' . implode(',', $logged_in_user_roles_to_check). '])';
		}

		return '';
	}

}
