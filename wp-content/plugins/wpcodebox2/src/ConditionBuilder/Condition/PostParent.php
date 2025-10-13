<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;
use Wpcb2\ConditionBuilder\WordPressContext;

class PostParent extends Condition
{
    const IS = 0;
    const IS_NOT = 1;

    public function is_satisfied()
    {
        if(!$this->wordPressContext->is_frontend()) {
            return false;
        }

        $parent_of_current_post_id = $this->wordPressContext->get_post_parent();

        $verb = $this->conditionData->get_condition_verb();

        if($verb['value'] === self::IS) {

            if(!$parent_of_current_post_id) {
                return false;
            }

            foreach($this->conditionData->get_extra_data() as $condition_data) {
                if($condition_data['value'] == $parent_of_current_post_id) {
                    return true;
                }
            }
        }

        if($verb['value'] === self::IS_NOT) {

            if(!$parent_of_current_post_id) {
                return true;
            }

            foreach($this->conditionData->get_extra_data() as $condition_data) {
                if($condition_data['value'] == $parent_of_current_post_id) {
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
			return '$wpContext->is_current_post_parent([' . implode(',', $posts_to_check). '])';
		}

		if($verb['value'] === self::IS_NOT) {
			return '!$wpContext->is_current_post_parent([' . implode(',', $posts_to_check). '])';
		}

	}
}
