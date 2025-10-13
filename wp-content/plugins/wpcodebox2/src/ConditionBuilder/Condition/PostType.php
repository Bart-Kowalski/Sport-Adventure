<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;
use Wpcb2\ConditionBuilder\WordPressContext;

class PostType extends Condition
{

    const IS = 0;
    const IS_NOT = 1;

    public function is_satisfied()
    {
        if (!$this->wordPressContext->is_frontend()) {
            return false;
        }

        $post_type = $this->wordPressContext->get_post_type();

        $verb = $this->conditionData->get_condition_verb();

        if ($verb['value'] === self::IS) {

            if (!$post_type) {
                return false;
            }


            foreach ($this->conditionData->get_extra_data() as $condition_data) {
                if ($condition_data['value'] == $post_type) {
                    return true;
                }
            }
        }

        if ($verb['value'] === self::IS_NOT) {

            if (!$post_type) {
                return true;
            }

            foreach ($this->conditionData->get_extra_data() as $condition_data) {
                if ($condition_data['value'] == $post_type) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

	public function get_code()
	{

		$post_types_to_check =[];

		foreach($this->conditionData->get_extra_data() as $condition_data) {
			$post_types_to_check[] = $condition_data['value'];
		}

		$verb = $this->conditionData->get_condition_verb();

		// Wrap post types in quotes
		$post_types_to_check = array_map(function($post_type) {
			return "'" . $post_type . "'";
		}, $post_types_to_check);

		if($verb['value'] === self::IS) {
			return '$wpContext->is_current_post_type([' . implode(',', $post_types_to_check). '])';
		}

		if($verb['value'] === self::IS_NOT) {
			return '!$wpContext->is_current_post_type([' . implode(',', $post_types_to_check). '])';
		}

		return '';

	}

}
