<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;
use Wpcb2\ConditionBuilder\WordPressContext;

class Taxonomy extends Condition
{

    const IS = 0;
    const IS_NOT = 1;


    public function is_satisfied()
    {
        if(!$this->wordPressContext->is_frontend()) {
            return false;
        }

        $category_name = $this->conditionData->get_extra_data();
        $taxonomy_name = $category_name['value'];

        $current_post_termids = [];

        $category_data = $this->conditionData->get_extra_data2();
        $term_id = $category_data['value'];

        $current_post_terms = $this->wordPressContext->get_current_post_terms($taxonomy_name);

        if(!is_array($current_post_terms) || empty($current_post_terms)) {

            if($this->conditionData->get_condition_verb()['value'] == self::IS) {
                return false;
            }
            else {
                return true;
            }

        }

        foreach($current_post_terms as $term) {
            $current_post_termids[] = $term->term_id;
        }

        if( in_array($term_id, $current_post_termids) ) {
            if($this->conditionData->get_condition_verb()['value'] == self::IS) {
                return true;
            }
            else {
                return false;
            }
        } else {
            if($this->conditionData->get_condition_verb()['value'] == self::IS) {
                return false;
            }
            else {
                return true;
            }
        }

    }

	public function get_code()
	{
		$taxonomy_name = $this->conditionData->get_extra_data();
		$taxonomy_name = $taxonomy_name['value'];
		$taxonomy_term = $this->conditionData->get_extra_data2();
		$taxonomy_term = $taxonomy_term['value'];

		if($this->conditionData->get_condition_verb()['value'] == self::IS) {
			return '$wpContext->current_post_has_term("' . $taxonomy_name . '", "' . $taxonomy_term . '" )';

		}
		else {
			return '$wpContext->current_post_doesnt_have_term("' . $taxonomy_name . '", "' . $taxonomy_term . '" )';

		}
	}


}
