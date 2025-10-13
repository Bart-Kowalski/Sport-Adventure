<?php

namespace Wpcb2\ConditionBuilder\Condition;


use Wpcb2\ConditionBuilder\Condition;

class IsMobile extends Condition
{
	const IS_MOBILE = 0;

	const IS_NOT_MOBILE = 1;

	public function is_satisfied()
	{
		$condition_verb = $this->conditionData->get_condition_verb();

		if (!function_exists('wp_is_mobile')) {
			return false;
		}

		if ($condition_verb['value'] === self::IS_MOBILE) {

			return wp_is_mobile();
		}

		if ($condition_verb['value'] === self::IS_NOT_MOBILE) {

			return !wp_is_mobile();
		}

		return false;
	}

	public function get_code()
	{
		$condition_verb = $this->conditionData->get_condition_verb();

		if ($condition_verb['value'] === self::IS_MOBILE) {
			return '$wpContext->is_mobile()';
		}
		if ($condition_verb['value'] === self::IS_NOT_MOBILE) {
			return '!$wpContext->is_mobile()';
		}

		return '';
	}

}
