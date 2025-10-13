<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;
use Wpcb2\ConditionBuilder\WordPressContext;

class Post extends Condition
{
    const IS = 0;
    const IS_NOT = 1;

    public function is_satisfied()
    {
        if(!$this->wordPressContext->is_frontend()) {
            return false;
        }

        $current_post_id = $this->wordPressContext->get_current_post_id();

        $verb = $this->conditionData->get_condition_verb();

        if($verb['value'] === self::IS) {
            foreach($this->conditionData->get_extra_data() as $condition_data) {
                if($condition_data['value'] == $current_post_id) {
                    return true;
                }
            }
        }

        if($verb['value'] === self::IS_NOT) {
            foreach($this->conditionData->get_extra_data() as $condition_data) {
                if($condition_data['value'] == $current_post_id) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

	public function get_code()
	{

		$posts_to_check =[];

		foreach($this->conditionData->get_extra_data() as $condition_data) {
			$posts_to_check[] = $condition_data['value'];
		}

		$verb = $this->conditionData->get_condition_verb();

		if($verb['value'] === self::IS) {
			return '$wpContext->is_current_post([' . implode(',', $posts_to_check). '])';
		}

		if($verb['value'] === self::IS_NOT) {
			return '!$wpContext->is_current_post([' . implode(',', $posts_to_check). '])';
		}

		return '';

	}

}
