<?php
/**
 * Automatic.css Base Feature class file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Features;

use Automatic_CSS\Model\Database_Settings;

/**
 * Base Feature class.
 */
class Base {

	// TODO: this class is currently empty, but we plan to use it in the future.
	// Meanwhile, Feature PHP files should extend it.

	/**
	 * Check if user has admin permission or builder full access.
	 * Useful for check if we can enqueue assets for the feature
	 *
	 * @return bool
	 */
	public function has_user_full_access() {
		return \Automatic_CSS\Helpers\Permissions::current_user_has_full_access();
	}
}
